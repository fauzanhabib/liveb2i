<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class schedule extends MY_Site_Controller {
        // Constructor
	public function __construct()
	{
		parent::__construct();
                $this->load->model('schedule_model');
                $this->load->model('offwork_model');
                
                //checking user role and giving action
                if(!$this->auth_manager->role() || $this->auth_manager->role()!='CCH'){
                    $this->messages->add('ERROR');
                    redirect('account/identity/detail/profile');
                }
	}
        
	// Index
	public function index()
	{
            $this->template->title = 'Schedule';
            
            $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id',$this->auth_manager->userid())->get_all();
            if(!$schedule_data){
                redirect('account/identity/detail/profile');
            }
            $schedule = array();
            foreach($schedule_data as $s){
                $offwork = $this->offwork_model->get_offwork($this->auth_manager->userid(), $s->day);
                //offwork by day
                $start_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->start_time);
                $end_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->end_time);
                //schedule by day
                $start_time_schedule = DateTime::createFromFormat('H:i:s', $s->start_time);
                $end_time_schedule = DateTime::createFromFormat('H:i:s', $s->end_time);
                
                $schedule_temp = array();
                if($start_time_offwork == $start_time_schedule && $start_time_offwork == $end_time_schedule && $end_time_offwork == $start_time_schedule && $end_time_offwork == $end_time_schedule){
                    $schedule_temp[0] = array(
                        'start_time' => $s->start_time,
                        'end_time' => $s->end_time,
                    );
                }
                else if($start_time_offwork > $start_time_schedule && $start_time_offwork < $end_time_schedule && $end_time_offwork > $start_time_schedule && $end_time_offwork < $end_time_schedule){
                    $schedule_temp[0] = array(
                        'start_time' => $s->start_time,
                        'end_time' => $offwork[0]->start_time,
                    );
                    $schedule_temp[1] = array(
                        'start_time' => $offwork[0]->end_time,
                        'end_time' => $s->end_time,
                    );
                }
                else{
                    $schedule_temp[0] = array(
                        'start_time' => $s->start_time,
                        'end_time' => $s->end_time,
                    );
                }
                
                $schedule[$s->day] = $schedule_temp;
                unset($schedule_temp);
            }
            
            $vars = array(
                'schedule' => $schedule,
            );
            $this->template->content->view('default/contents/schedule/index', $vars);
            
            //publish template
            $this->template->publish();
	}
        
        public function edit($day){
            // setting day for editing schedule data
            //$this->session->set_userdata("day_schedule",$day);
            
            $this->template->title = 'Edit Schedule';
            
            $offwork = $this->offwork_model->get_offwork($this->auth_manager->userid(), $day);
            $schedule = $this->schedule_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id',$this->auth_manager->userid())->where('day',$day)->get();
            if(!$offwork && !$schedule){
                redirect('account/identity/detail/profile');
            }
            
            //offwork by day
            $start_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->start_time);
            $end_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->end_time);
            //schedule by day
            $start_time_schedule = DateTime::createFromFormat('H:i:s', $schedule->start_time);
            $end_time_schedule = DateTime::createFromFormat('H:i:s', $schedule->end_time);
            
            $schedule_temp = array();
            if($start_time_offwork == $start_time_schedule && $start_time_offwork == $end_time_schedule && $end_time_offwork == $start_time_schedule && $end_time_offwork == $end_time_schedule){
                $schedule_temp[0] = array(
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                );
            }
            else if($start_time_offwork > $start_time_schedule && $start_time_offwork < $end_time_schedule && $end_time_offwork > $start_time_schedule && $end_time_offwork < $end_time_schedule){
                $schedule_temp[0] = array(
                    'start_time' => $schedule->start_time,
                    'end_time' => $offwork[0]->start_time,
                );
                $schedule_temp[1] = array(
                    'start_time' => $offwork[0]->end_time,
                    'end_time' => $schedule->end_time,
                );
            }
            else{
                $schedule_temp[0] = array(
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                );
            }
            
            $vars = array(
                'schedule' => $schedule_temp,
                'day' => $day,
                'form_action'=>'update'
            );
            $this->template->content->view('default/contents/schedule/form', $vars);
           
            //publish template
            $this->template->publish();
        }
        
        public function update($day = ''){
            if( !$this->input->post('__submit')) { 
                $this->messages->add('Invalid action', 'danger');
                redirect('account/identity/detail/profile');
            }
            //$this->session->userdata("day_schedule"); exit;
            $schedule_id = $this->schedule_model->select('id')->where('user_id',$this->auth_manager->userid())->where('day',$day)->get();
            $offwork_id = $this->offwork_model->get_offwork($this->auth_manager->userid(), $day);
            
            if($this->input->post('start_time_0') && $this->input->post('end_time_0') && $this->input->post('start_time_1') && $this->input->post('end_time_1')){
                // updating schedule
                $schedule = array(
                    'start_time' => $this->input->post('start_time_0'),
                    'end_time' =>$this->input->post('end_time_1'),
                );
                
                // Inserting and checking
                if( ! $this->schedule_model->update($schedule_id->id, $schedule)) {
                    $this->messages->add(validation_errors(), 'danger');
                    $this->edit($this->auth_manager->userid()); return;
                }
                
                // updating offwork
                $offwork = array(
                    'start_time' => $this->input->post('end_time_0'),
                    'end_time' =>$this->input->post('start_time_1'),
                );
                
                // Inserting and checking
                if( ! $this->offwork_model->update($offwork_id[0]->id, $offwork)) {
                    $this->messages->add(validation_errors(), 'danger');
                    $this->edit($this->auth_manager->userid()); return;
                }
                
            }
            else if($this->input->post('start_time_0') && $this->input->post('end_time_0') && !$this->input->post('start_time_1') && !$this->input->post('end_time_1')){
                // updating schedule
                $schedule = array(
                    'start_time' => $this->input->post('start_time_0'),
                    'end_time' =>$this->input->post('end_time_0'),
                );
                
                // Inserting and checking
                if( ! $this->schedule_model->update($schedule_id->id, $schedule)) {
                    $this->messages->add(validation_errors(), 'danger');
                    $this->edit($this->auth_manager->userid()); return;
                }
                
                // updating offwork
                $offwork = array(
                    'start_time' => null,
                    'end_time' =>null,
                );
                
                // Inserting and checking
                if( ! $this->offwork_model->update($offwork_id[0]->id, $offwork)) {
                    $this->messages->add(validation_errors(), 'danger');
                    $this->edit($this->auth_manager->userid()); return;
                }
            }

            
            //unsetting day_schedule
            //$this->session->unset_userdata("day_schedule");
            
            $this->messages->add('Update Succeeded', 'success');
            redirect('coach/schedule');
        }
        
        public function delete($day='', $id='')
	{
            //deleting data if in a day has more than one schedule
            if(count($this->schedule_model->where('user_id',$this->auth_manager->userid())->where('day',$day)->get_all()) > 1){
              $this->schedule_model->where('user_id',$this->auth_manager->userid())->where('day',$day)->delete($id);
                $this->messages->add('Delete Succeeded', 'success');
                redirect('coach/schedule/edit/'.$day);  
            }
            //one coach must has one schedule each day in database even if start_time and end_time null
            else if(count($this->schedule_model->where('user_id',$this->auth_manager->userid())->where('day',$day)->get_all() == 1)){
                $schedule= array(
                    'start_time' => null,
                    'end_time' => null,
                );
                
                // Inserting and checking
                $id = $this->schedule_model->where('user_id',$this->auth_manager->userid())->where('day',$day)->get();
                if( ! $this->schedule_model->update($id->id, $schedule)) {
                    $this->messages->add(validation_errors(), 'danger');
                    $this->edit($this->auth_manager->userid()); return;
                }
                redirect('coach/schedule/');
            }
            
	}
        
        public function test(){
            $selectedTime = "9:15:00";
            $endTime = strtotime("+5 hours +15 minutes", strtotime($selectedTime));
            echo date('H:i:s', strtotime($selectedTime)+900); exit;
        }
        
}