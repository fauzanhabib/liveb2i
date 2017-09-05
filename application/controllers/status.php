<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// live_messaging_email
// database_worker

class Status extends CI_Controller {

	var $checklist = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function index()
	{
		$this->checklist = array(
			'apache' => $this->_check_apache(), 
			'mysql' => $this->_check_mysql(),
			'beanstalkd' => $this->_check_beanstalkd()
			);

		$this->_check_worker();

		header('Content-Type: application/json');
		echo json_encode($this->checklist);
	}

	private function _check_mysql()
	{
		@$mysqli = new mysqli($this->db->hostname, $this->db->username, $this->db->password, $this->db->database);

		// Test a connection
		if ($mysqli->connect_errno) {
			return "ERROR";
		}

		// Check server if alive
		if ( ! $mysqli->ping()) {
			return "ERROR";
		}

		// Close connection
		$mysqli->close();

		return "OK";
	}

	private function _check_beanstalkd()
	{
		$result = "ERROR";

		$function = 'fsockopen';
		$params = ['127.0.0.1', '11300', &$errNum, &$errStr];
		$params[] = 1;

		// Trying to connect
		$connection = @call_user_func_array($function, $params);
		if (!empty($errNum) || !empty($errStr)) {
			return $result;
		}
		$connected = is_resource($connection);
		if ($connected) {
			stream_set_timeout($connection, -1);
		}
		$result = "OK";

		// Disconnect
		fwrite($connection, "quit", strlen("quit"));
		$connected = !fclose($connection);
		if (!$connected) {
			$connection = null;
		}

		return $result;
	}

	private function _check_worker()
	{
		$worker = array(
			//'database_worker' => '[l]ive_messaging_database.php',
			'email_worker' => '[l]ive_messaging_email.php',
			//'download_worker' => '[l]ive_messaging_download.php'
			);


		foreach ($worker as $key => $value) {
			$ok = shell_exec("ps -eF | grep ".$value);
			$this->checklist[$key] = "ERROR";

			if(strlen($ok)) { 
				$this->checklist[$key] = "OK";				
			}
		}

	}


	private function _check_apache()
	{
		$host = 'localhost';
		$port = 80;
		$timeout = 5;
		$result = "ERROR";

		if (@$fp = fsockopen($host, $port, $errCode, $errStr, $timeout)) {
  			$result = "OK";
		}

		@fclose($fp);

		return $result;
	}
}

/* End of file status.php */
/* Location: ./application/controllers/status.php */
