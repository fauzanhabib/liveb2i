<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Help extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = "Help";

        $id = $this->auth_manager->userid();

        $vars = array(

        );

        $this->template->content->view('default/contents/student/help/index',$vars);
        $this->template->publish();
    }
    

}
