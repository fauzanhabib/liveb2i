<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class forgot_password extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
    }

    // Index
    public function index() {
        //echo 'test'; exit;
        // User is already logged in
        if ($this->auth->loggedin()) {
            redirect('account/identity/detail/profile');
        }
        $this->template->title = 'Forgot Password';
        $this->template->content->view('default/contents/forgot_password/index');

        //publish templates
        $this->template->publish();
    }
    
    public function send_password(){
        
    }

}
