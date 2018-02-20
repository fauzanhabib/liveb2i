<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {

	// Constructor
	public function __construct()
	{
		parent::__construct();
	}

	// Index
	public function index(){

            // User is already logged in
            if ($this->auth->loggedin()) {

                // check user_id dan session
                $user_id = $this->auth_manager->userid();
                session_start();
                $session_user_login = session_id();

                $check_session = $this->db->select('user_login.user_id, user_login.session')
                                      ->from('user_login')
                                      ->where('user_login.user_id',$user_id)
                                      ->where('user_login.session',$session_user_login)
                                      ->get()->result();
                if($check_session){
                    if((!$check_session[0]->user_id) && (!$check_session[0]->session)){

                        $this->session->set_userdata('user_id_session',$user->id);
                        redirect('home/confirmation');
                    } else if (!$check_session){

                        redirect('login');
                    } else {
                        if($this->auth_manager->role() == 'STD'){
                            redirect('student/dashboard');
                        } else if($this->auth_manager->role() == 'CCH'){
                            redirect('coach/dashboard');
                        } else{
                            redirect('account/identity/detail/profile');
                        }
                    }
                } else {
                    redirect('logout');
                }

            }

            // Checking user's login attempt
            if($this->input->post('__submit')) {
                // Success to identify
                if( $this->auth_manager->login( $this->input->post('email'), $this->input->post('password')) ) {

                    // insert timezone
                    $min_raw = $this->input->post("min_raw");
                    $userid  = $this->auth_manager->userid();


                    if ($min_raw < 0) {
                      $minutes = abs($min_raw);
                    }else if($min_raw > 0){
                      $minutes = $min_raw * -1;
                    }

                    $gmt_val = @$minutes / 60;

                    if(@$minutes == NULL){
                        $minutes = 0;
                    }

                    $timezone = array(
                           'user_id' => $userid,
                           'gmt_val' => $gmt_val,
                           'minutes_val' => @$minutes,
                           'log_date' => date('Y-m-d H:i:s')
                        );
                    $this->db->replace('user_timezones', $timezone);
                    // ====

                    if($this->auth_manager->role() == 'STD'){
											$check_pro_ID = $this->db->select('dyned_pro_id')
																->from('user_profiles')
																->where('user_id', $userid)
																->get()->result();

												// echo $userid;
												if(!$check_pro_ID[0]->dyned_pro_id){
													$this->messages->add('DynEd Pro ID not connected', 'warning');
													redirect('logout');
												}else{
                        	redirect('student/dashboard');
												}
                    } else if($this->auth_manager->role() == 'CCH'){
                        redirect('coach/dashboard');
                    } else{
											// exit();
                        redirect('account/identity/detail/profile');
                    }
                }
                // Not valid user
                redirect('login');
            }

        // Set Template
        // $this->template->content->view('default/contents/login/index');
        $this->template->title = 'Login';

        $this->template->set_template('default/layouts/login');
        $this->template->publish();
	}

    function update_login(){
        session_start();
        $session_user_login = session_id();
        $user_id = $this->input->post('user_id');

        $update = $this->db->where('user_id',$user_id)
                           ->update('user_login',array('session' => $session_user_login));

        if($update){
            echo true;
        } else {
            echo false;
        }
    }


}

/* End of file login.php */
/* Location: ./application/controllers/login.php */
