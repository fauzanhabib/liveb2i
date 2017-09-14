<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// UPDATE: Jogi (31/05/2015)
// class Home extends MY_Site_Controller {
class Home extends MY_Controller {

        // Constructor
	public function __construct()
	{
		parent::__construct();
	}
        
	// Index
	public function index()
	{

            $this->template->title = 'Home';
            $this->template->set_template('default/layouts/home');
            $this->template->publish();
	}

	function confirmation(){
            $this->template->title = 'Confirmation';
            $this->template->set_template('default/layouts/confirmation');
            $this->template->publish();
	}

    public function check_login(){
        $user_id = $this->auth_manager->userid();

        @session_start();    
        @$session_user_login = session_id();

        $check_session = $this->db->select('user_login.id, user_login.session')
                          ->from('user_login')
                          ->where('user_login.user_id',$user_id)
                          ->where('user_login.session',$session_user_login)
                          ->get()->result();

        if(count($check_session) == 0){
        	echo 0;
        } else {
        	echo 1;
        }
    }
}