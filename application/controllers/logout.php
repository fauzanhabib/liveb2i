<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Logout
class Logout extends MY_Controller {

	// Constructor
	public function __construct()
	{
		parent::__construct();
	}

	// Index
	public function index()
	{	
			// echo $this->auth_manager->role();		
		$this->auth_manager->logout();
		redirect('login');
	}

	function update_session(){
		$user_id = $this->auth_manager->userid();
        session_start();    
        $session_user_login = session_id(); 

        $this->db->where('user_id',$user_id);
        $this->db->where('session',$session_user_login);
        $this->db->delete('user_login');


        redirect('home');
	}

}

/* End of file logout.php */
/* Location: ./application/controllers/logout.php */