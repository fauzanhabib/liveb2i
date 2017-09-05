<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class about extends MY_Controller {

	// Constructor
	public function __construct()
	{
		parent::__construct();
	}

	// Index
	public function index()
	{
            $this->template->title = 'About Us';

            $this->template->set_template('default/layouts/about');
            $this->template->publish();
	}
}

/* End of file about.php */
/* Location: ./application/controllers/about.php */