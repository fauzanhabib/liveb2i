<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class hai extends MY_Controller {

	// Constructor
	public function __construct()
	{
		parent::__construct();
        $this->load->library('queue');
        $this->load->library('email_structure');
        $this->load->library('send_email');

	}

	// Index
	public function index()
	{
           echo $_SERVER['SERVER_NAME'];
	}
}

/* End of file about.php */
/* Location: ./application/controllers/about.php */