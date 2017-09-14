<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Webextest extends MY_Controller {

	// Constructor
	public function __construct()
	{
		parent::__construct();
	}

	// Index
	public function index(){

            
        
        // $this->template->title = 'Login';
       
        // $this->template->set_template('default/layouts/login');
        // $this->template->publish();
	}
    public function view_xml(){
       $xml_path = APPPATH . "controllers/testing.xml";
       $xml=simplexml_load_file($xml_path);
       echo "<pre>";
       print_r($xml);  
    }


}

/* End of file login.php */
/* Location: ./application/controllers/login.php */