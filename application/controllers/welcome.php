<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Site_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('queue');
	}

	public function index()
	{
		$data = array(
			'nama' => 'Apriwin',
			'email' => 'apriwin@pistarlabs.com',
			'phone' => '08123456789'
			);

		$tube = 'com.pistarlabs.update';

		// Langsung
		$this->queue->push($tube, $data, 'update.sms');

		// Tunggu 5 detik
                $this->queue->later(3, $tube, $data, 'update.sendemail');
                
		$this->queue->later(2, $tube, $data, 'update.email');
                
                

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */