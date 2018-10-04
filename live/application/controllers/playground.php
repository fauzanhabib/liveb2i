<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'vendor/autoload.php';

use OpenTok\OpenTok;
use OpenTok\ArchiveMode;
use OpenTok\MediaMode;
use OpenTok\Session;
use OpenTok\Role;

class playground extends MY_Controller {

	// Constructor
	// public function __construct()
	// {
	// 	parent::__construct();
	// }

	// Index
	public function agora_test_site()
	{

      $this->load->view('contents/playground/agora_test_site');
	}

	public function opentok_test_site()
	{
			$apiObj = new OpenTok($this->config->item('opentok_key'), $this->config->item('opentok_secret'));
			// $session = $apiObj->createSession(array('mediaMode' => MediaMode::ROUTED));

			$sessionId = "1_MX40NjE1NzE0Mn5-MTUzODYyODAyOTM3OH5IUlZxZVhKNHhzY0lhL3V1Z2NhN0daYXZ-fg";
			$token 		 = "T1==cGFydG5lcl9pZD00NjE1NzE0MiZzaWc9NTk2Y2NlOTQ4NTJjMjVmZTA5MmMyM2E2NWQ5M2Q3OGQ4OWYwYTFhMzpzZXNzaW9uX2lkPTFfTVg0ME5qRTFOekUwTW41LU1UVXpPRFl5T0RBeU9UTTNPSDVJVWxaeFpWaEtOSGh6WTBsaEwzVjFaMk5oTjBkYVlYWi1mZyZjcmVhdGVfdGltZT0xNTM4NjI4MDU0Jm5vbmNlPTAuMDk0NzA0NjA4NTg1NTY5ODQmcm9sZT1wdWJsaXNoZXImZXhwaXJlX3RpbWU9MTU0MTIyMDA1NCZpbml0aWFsX2xheW91dF9jbGFzc19saXN0PQ==";
			// $sessionId = $session->getSessionId();
			// $token 		 = $apiObj->generateToken($sessionId);

			// echo "<pre>";print_r($token);exit();
			$var_simulator = array(
					'sessionId'  => @$sessionId,
					'token'      => @$token,
					'apiKey'     => @$this->config->item('opentok_key'),
			);


      $this->load->view('contents/playground/opentok_test_site', $var_simulator);
	}
}

/* End of file about.php */
/* Location: ./application/controllers/about.php */
