<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class vrm extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->library('vrm2');
        $this->load->library('call2');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->vrm2->init("id2", "suci@demo.com", "");
        //$this->vrm2->all_time();
        
        $vars = array(
            'this_week' => $this->vrm2->getThisWeek(),
            'all_time_wss' => $this->vrm2->all_time_wss,
            'all_time_days' => $this->vrm2->all_time_days,
            'all_time_hours' => $this->vrm2->all_time_hours,
            'dataRadar' => $this->vrm2->getDataRadar()
        );
        
        $this->template->content->view('default/contents/vrm/index', $vars);
        $this->template->publish();
    }
    
    public function testCall2(){
        //TODO
        //Get email student from database
        $this->call2->init("site11", "sutomo@dyned.com");
        
        echo header("Content-Type:application/json");
        print_r($this->call2->getDataJson());        
    }
}
