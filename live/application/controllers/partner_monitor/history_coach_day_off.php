<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class history_coach_day_off extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('coach_day_off_model_history');
        $this->load->model('user_model');
        // for messaging action and timing
        $this->load->library('queue');
        $this->load->library('email_structure');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CAM') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index($page='') {
        $this->template->title = 'Histories of Coach Day Off';
        $offset = 0;
        $per_page = 5;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner_monitor/history_coach_day_off/index'), count($this->coach_day_off_model_history->get_coach_day_off()), $per_page, $uri_segment);
        $vars = array(
            'data' => $this->coach_day_off_model_history->get_coach_day_off('', $per_page, $offset),
            'pagination' => @$pagination
        );
        $this->template->content->view('default/contents/history_coach_day_off/index', $vars);

        //publish template
        $this->template->publish();
    }

 

}
