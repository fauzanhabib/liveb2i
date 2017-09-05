<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class offwork extends MY_Site_Controller {
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
            $this->template->content->view('default/contents/offwork/index');
            
            //publish template
            $this->template->publish();
	}
        
        public function edit($day){
            // setting day for editing offwork data
            $this->session->set_userdata("day_offwork",$day);
            
            $this->template->title = 'Edit Schedule';
            $vars = array(
                'offwork' => $this->offwork_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id',$this->auth_manager->userid())->where('day',$day)->get_all(),
                'form_action'=>'update'
            );
            $this->template->content->view('default/contents/offwork/form', $vars);
           
            //publish template
            $this->template->publish();
        }
        
        public function update(){
            if( !$this->input->post('__submit')) { 
                $this->messages->add('Invalid action', 'danger');
                redirect('account/identity/detail/profile');
            }
            //$this->session->userdata("day_offwork");
            $temp = $this->offwork_model->select('id')->where('user_id',$this->auth_manager->userid())->where('day',$this->session->userdata("day_offwork"))->get_all();
            foreach($temp as $t){
                $offwork= array(
                    'start_time' => $this->input->post('start_time_'.$t->id),
                    'end_time' =>$this->input->post('end_time_'.$t->id),
                );
                
                // Inserting and checking
                if( ! $this->offwork_model->update($t->id, $offwork)) {
                    $this->messages->add(validation_errors(), 'danger');
                    $this->edit($this->auth_manager->userid()); return;
                }
                
            }
            
            //unsetting day_offwork
            $this->session->unset_userdata("day_offwork");
            
            $this->messages->add('Update Succeeded', 'success');
            redirect('coach/offwork');
        }
        
        public function delete($day='', $id='')
	{
            //deleting data if in a day has more than one offwork
            if(count($this->offwork_model->where('user_id',$this->auth_manager->userid())->where('day',$day)->get_all()) > 1){
              $this->offwork_model->where('user_id',$this->auth_manager->userid())->where('day',$day)->delete($id);
                $this->messages->add('Delete Succeeded', 'success');
                redirect('coach/offwork/edit/'.$day);  
            }
            //one coach must has one offwork each day in database even if start_time and end_time null
            else if(count($this->offwork_model->where('user_id',$this->auth_manager->userid())->where('day',$day)->get_all() == 1)){
                $offwork= array(
                    'start_time' => null,
                    'end_time' => null,
                );
                
                // Inserting and checking
                $id = $this->offwork_model->where('user_id',$this->auth_manager->userid())->where('day',$day)->get();
                if( ! $this->offwork_model->update($id->id, $offwork)) {
                    $this->messages->add(validation_errors(), 'danger');
                    $this->edit($this->auth_manager->userid()); return;
                }
                redirect('coach/offwork/');
            }
            
	}
        
        public function test(){
            $selectedTime = "9:15:00";
            $endTime = strtotime("+5 hours +15 minutes", strtotime($selectedTime));
            echo date('H:i:s', strtotime($selectedTime)+900); exit;
        }
        
}