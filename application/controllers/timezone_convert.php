<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timezone_convert extends MY_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('Auth_manager');
    }
	// Index
	public function index(){
    
    $min_raw = $this->input->post("n");
    $userid  = $this->auth_manager->userid();


    if ($min_raw < 0) {
      $minutes = abs($min_raw);
    }else if($min_raw > 0){
      $minutes = $min_raw * -1;
    }else if($min_raw == 0){
      $minutes = $min_raw;
    }

    $gmt_val = $minutes / 60;


    $timezone = array(
           'user_id' => $userid,
           'gmt_val' => $gmt_val,
           'minutes_val' => $minutes,
           'log_date' => date('Y-m-d H:i:s')
        );
    $this->db->replace('user_timezones', $timezone);
    
  }

}

/* End of file about.php */
/* Location: ./application/controllers/about.php */