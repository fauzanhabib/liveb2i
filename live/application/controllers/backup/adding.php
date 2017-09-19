<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class adding extends MY_Site_Controller {
    
        //$this->{$this->models[$this->role]}->select('id')->where('user_id',$this->auth_manager->userid())->get();
        //var for days
        var $days = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
        var $table = array(
            'education' => '$this->user_education_model',
            'geography' => 'user_geography_model',
            'profile' => '$this->user_profile_model',
            'social_media' => '$this->user_social_media_model',
            'token' => '$this->user_token_model',
        );
       
        // Constructor
	public function __construct()
	{
		parent::__construct();
                // load model for identity
                $this->load->model('user_geography_model');
                
                $this->load->model('user_model');
                $this->load->model('identity_model');
                $this->load->model('schedule_model');
                $this->load->model('offwork_model');
                $this->load->library('phpass');
                
                //checking user role and giving action
                if(!$this->auth_manager->role() || $this->auth_manager->role()!='PRT'){
                    $this->messages->add('Access Denied');
                    redirect('home');
                }
	}
        
	// Index
	public function index()
	{
            $this->template->title = 'Add Member';
            $this->template->content->view('default/contents/adding/index');
            
            //publish template
            $this->template->publish();
	}
        
        public function student(){
            $this->template->title = 'Add Student';
            $vars = array(
                'form_action'=>'create_student'
            );
            $this->template->content->view('default/contents/adding/student/form', $vars);
            $this->template->publish();
        }
        
        public function create_student(){
//            $this->db->trans_start();
//            $this->db->trans_complete();
//            if ($this->db->trans_status() === FALSE){}

//            if ($this->db->trans_status() === FALSE)
//            {
//                $this->db->trans_rollback();
//            }
//            else
//            {
//                $this->db->trans_commit();
//            }
            
            // Creating a student user data must be followed by creating profile data and status still disable/need approval from admin
            if( !$this->input->post('__submit')) {
			$this->messages->add('Invalid action', 'danger');
			redirect('partner/adding');
		}
                
                //encrypting password before insert it to database
                if($this->input->post('password')){
                    $password = $this->phpass->hash($this->input->post('password'));
                }
                else{
                    $password = null;
                }
                
                
		// inserting user data
		$user = array(
			'email'=>$this->input->post('email'),
			'password'=>$password,
                        'role_id'=>1,
                        'status'=>'disable',
			);
                
		// Inserting and checking to users table then storing insert_id into $insert_id
                $this->db->trans_begin();
                $user_id = $this->user_model->insert($user);
                if (!$user_id){
                    $this->db->trans_rollback();
                    $this->messages->add(validation_errors(), 'danger');
                    $this->student(); return;
		}
                
                // Inserting user profile data
                $user_id_to_partner_id = $this->identity_model->get_identity('profile')->dropdown('user_id','partner_id');
                $profile = array(
                    'user_id' => $user_id,
                    'fullname' => $this->input->post('fullname'),
                    'nickname' => $this->input->post('nickname'),
                    'gender' => $this->input->post('gender'),
                    'date_of_birth' => $this->input->post('date_of_birth'),
                    'phone' => $this->input->post('phone'),
                    'partner_id' => $user_id_to_partner_id[$this->auth_manager->userid()],
                );
                
                // Inserting and checking to profile table then storing it into users_profile table
                $profile_id = $this->identity_model->get_identity('profile')->insert($profile);
		if(!$profile_id) {
                        $this->db->trans_rollback();
			$this->messages->add(validation_errors(), 'danger');
			$this->student(); return;
		}
                
                // inserting user token data
                $token = array(
                    'user_id' => $user_id,
                );
                
                // Inserting and checking to profile table then storing it into users_profile table
                $token_id = $this->identity_model->get_identity('token')->insert($token);
		if(!$token_id) {
                    $this->db->trans_rollback();
                    $this->student(); return;
		}
                
                $this->db->trans_commit();

		$this->messages->add('Inserting Student Successful', 'success');
		redirect('partner/adding');
        }
        
        public function coach(){
            $this->template->title = 'Add Coach';
            $vars = array(
                'form_action'=>'create_coach'
            );
            $this->template->content->view('default/contents/adding/coach/form', $vars);
            $this->template->publish();
        }
        
        public function create_coach(){
            // Creating a coach user data must be followed by creating profile, geography, education data and status still disable/need approval from admin
            if( !$this->input->post('__submit')) {
			$this->messages->add('Invalid action', 'danger');
			redirect('partner/adding');
		}
                
                //encrypting password before insert it to database
                if($this->input->post('password')){
                    $password = $this->phpass->hash($this->input->post('password'));
                }
                else{
                    $password = null;
                }
                
                
		// Inserting user data
		$user = array(
			'email'=>$this->input->post('email'),
			'password'=>$password,
                        'role_id'=>2,
                        'status'=>'disable',
			);
                
                $this->db->trans_begin();
		// Inserting and checking to users table then storing insert_id into $user_id
                $user_id = $this->user_model->insert($user); 
		if( ! $user_id) {
                        $this->db->trans_rollback();
			$this->messages->add(validation_errors(), 'danger');
			$this->coach(); return;
		}
                
                
                // Inserting user profile data
                $user_id_to_partner_id = $this->identity_model->get_identity('profile')->dropdown('user_id','partner_id');
                $profile = array(
                    'user_id' => $user_id,
                    'fullname' => $this->input->post('fullname'),
                    'nickname' => $this->input->post('nickname'),
                    'gender' => $this->input->post('gender'),
                    'date_of_birth' => $this->input->post('date_of_birth'),
                    'phone' => $this->input->post('phone'),
                    'partner_id' => $user_id_to_partner_id[$this->auth_manager->userid()],
                );
                
                // Inserting and checking to profile table then storing it into users_profile table
                $profile_id = $this->identity_model->get_identity('profile')->insert($profile);
		if( ! $profile_id) {
                        $this->db->trans_rollback();
			$this->messages->add(validation_errors(), 'danger');
			$this->coach(); return;
		}
                
                // Inserting user home town data
                $geography = array(
                    'user_id' => $user_id,
                    'country' => 'data',
                    'state' => 'data',
                    'city' => 'data',
                    'zip' => 'data',
                );
                
                //$this->db->insert('user_geography', $geography); 
                //exit;
                // Inserting and checking to geography table then storing it into users_georaphy table
                $geography_id = $this->identity_model->get_identity('geography')->insert($geography);
                
                //print_r($this->user_geography_model); exit;
                //$geography_id = $this->user_geography_model->insert($geography);
                                //$this->{$this->models[$this->table('geography')]},
                //echo('<pre>');
                //print_r(get_object_vars($this->identity_model->get_identity('geography'))); exit;
                //echo($this->db->last_query()); exit;
		if( ! $geography_id) {
                        $this->user_model->delete($user_id);
			$this->messages->add(validation_errors(), 'danger');
			$this->coach(); return;
		}
                
                
                
                // Inserting user education data
                $education = array(
                    'user_id' => $user_id,
                    'teaching_credential' => 'data',
                    'dyned_certification_level' => 'data',
                    'year_experience' => 'data',
                    'special_english_skill' => 'data',
                );
                
                // Inserting and checking to geography table then storing it into users_georaphy table
                $education_id = $this->identity_model->get_identity('education')->insert($education);
		if( ! $education_id) {
                        $this->db->trans_rollback();
			$this->messages->add(validation_errors(), 'danger');
			$this->coach(); return;
		}
                
                // Inserting user schedule and offwork data
                foreach($this->days as $d){
                    $schedule = array(
                        'user_id' => $user_id,
                        'day' => $d,
                    );
                    
                    // Inserting and checking to geography table then storing it into users_georaphy table
                    $schedule_id = $this->schedule_model->insert($schedule);
                    if( ! $schedule_id) {
                            $this->db->trans_rollback();
                            $this->messages->add(validation_errors(), 'danger');
                            $this->coach(); return;
                    }
                    
                    //inserting user offwork
                    $offwork = array(
                        'schedule_id' => $schedule_id,
                    );
                    
                    // Inserting and checking to geography table then storing it into users_georaphy table
                    $offwork_id = $this->offwork_model->insert($offwork);
                    if( ! $offwork_id) {
                        $this->db->trans_rollback();
                        $this->messages->add(validation_errors(), 'danger');
                        $this->coach(); return;
                    }
                    
                }
                
                $this->db->trans_commit();
                
		$this->messages->add('Inserting Coach Successful', 'success');
		redirect('partner/adding');
        }
        
        
        public function edit($day){
            // setting day for editing adding data
            $this->session->set_userdata("day_adding",$day);
            
            $this->template->title = 'Edit Schedule';
            $vars = array(
                'adding' => $this->offwork_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id',$this->auth_manager->userid())->where('day',$day)->get_all(),
                'form_action'=>'update'
            );
            $this->template->content->view('default/contents/adding/form', $vars);
           
            //publish template
            $this->template->publish();
        }
        
        public function update(){
            if( !$this->input->post('__submit')) { 
                $this->messages->add('Invalid action', 'danger');
                redirect('home');
            }
            //$this->session->userdata("day_adding");
            $temp = $this->offwork_model->select('id')->where('user_id',$this->auth_manager->userid())->where('day',$this->session->userdata("day_adding"))->get_all();
            foreach($temp as $t){
                $adding= array(
                    'start_time' => $this->input->post('start_time_'.$t->id),
                    'end_time' =>$this->input->post('end_time_'.$t->id),
                );
                
                // Inserting and checking
                if( ! $this->offwork_model->update($t->id, $adding)) {
                    $this->messages->add(validation_errors(), 'danger');
                    $this->edit($this->auth_manager->userid()); return;
                }
                
            }
            
            //unsetting day_adding
            $this->session->unset_userdata("day_adding");
            
            $this->messages->add('Update Successful', 'success');
            redirect('coach/adding');
        }
        
        public function delete($day='', $id='')
	{
            //deleting data if in a day has more than one adding
            if(count($this->offwork_model->where('user_id',$this->auth_manager->userid())->where('day',$day)->get_all()) > 1){
              $this->offwork_model->where('user_id',$this->auth_manager->userid())->where('day',$day)->delete($id);
                $this->messages->add('Delete Successful', 'success');
                redirect('coach/adding/edit/'.$day);  
            }
            //one coach must has one adding each day in database even if start_time and end_time null
            else if(count($this->offwork_model->where('user_id',$this->auth_manager->userid())->where('day',$day)->get_all() == 1)){
                $adding= array(
                    'start_time' => null,
                    'end_time' => null,
                );
                
                // Inserting and checking
                $id = $this->offwork_model->where('user_id',$this->auth_manager->userid())->where('day',$day)->get();
                if( ! $this->offwork_model->update($id->id, $adding)) {
                    $this->messages->add(validation_errors(), 'danger');
                    $this->edit($this->auth_manager->userid()); return;
                }
                redirect('coach/adding/');
            }
            
	}
        
        public function test(){
            $selectedTime = "9:15:00";
            $endTime = strtotime("+5 hours +15 minutes", strtotime($selectedTime));
            echo date('H:i:s', strtotime($selectedTime)+900); exit;
        }
        
}