<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'vendor/autoload.php';

class Multiple extends MY_Site_Controller {
    // Constructor
    public function __construct() {
        parent::__construct();
        // load models for student info
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');
        $this->load->model('user_model');
        $this->load->library('Auth_manager');
        $this->load->library('call1');
        $this->load->library('call2');
        $this->load->model('coaching_script_model');
        /* if ($this->auth_manager->role() != 'STD' || $this->auth_manager->role() != 'CCH') {
            redirect('home');
        } */
    }
    
    // Index
    public function index(){
        $this->template->title = "Multiple Session Are Not Allowed";

        $this->template->content->view('contents/opentok/coach/nosession');
        $this->template->publish();
    }

}