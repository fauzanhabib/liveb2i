<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class contact_us extends MY_Controller {

	// Constructor
	public function __construct()
	{
		parent::__construct();
	}

	// Index
	public function index()
	{
            $this->template->title = 'Contact Us';

            $this->template->set_template('default/layouts/contact_us');
            $this->template->publish();
	}
}

/* End of file about.php */
/* Location: ./application/controllers/about.php */