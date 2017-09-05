<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class test404 extends MY_Controller {

	// Constructor
	public function __construct()
	{
		parent::__construct();
	}

	// Index
	public function index()
	{
            $this->template->title = 'About';

            $this->template->set_template('default/layouts/test404');
            $this->template->publish();
	}
}

/* End of file about.php */
/* Location: ./application/controllers/about.php */