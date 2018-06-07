
<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class pagenotfound extends MY_Site_Controller {
    private $CI;
    public function __construct() {
        $this->CI = &get_instance();
        if(!@$this->CI){
          parent::__construct();
          $this->load->helper('url');
        }
    }

    public function index() {
        // load 404 view
        if($this->CI){
          $this->CI->template->title = '404 Page Not Found';
          $this->CI->load->view('default/layouts/pagenotfound');
        }else{
          $this->load->view('default/layouts/pagenotfound');
        }
    }
}
?>
