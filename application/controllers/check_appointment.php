<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Check_appointment extends MY_Controller {

	// Constructor
	public function __construct()
	{
		parent::__construct();
	}

	// Index
	public function index()
	{
		$servername = "idbuild.id.dyned.com";
		$username   = "root";
		$password   = "mysqldev!__";
		$dbname 	= "live_dev";

		$conn = new mysqli($servername, $username, $password, $dbname);
		
		$pull_app = ("select * from appointments");
        $r 	   	  = mysql_query($pull_app);

        echo "<pre>";
        print_r($conn);
	}
}

/* End of file about.php */
/* Location: ./application/controllers/about.php */