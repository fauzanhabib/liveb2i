
<?php 
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class pagenotfound extends MY_Site_Controller {
    private $CI;
    public function __construct() {
        $this->CI = &get_instance();
    } 
 
    public function index() {
        // load 404 view
        $this->CI->template->title = '404 Page Not Found';
        $this->CI->load->view('default/layouts/pagenotfound');
    } 
} 
?>