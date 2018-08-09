<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class log_activity extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('user_log_model');
//        $this->load->model('creator_member_model');
//        // for messaging action and timing
//        $this->load->library('queue');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'RAM') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index($page='') {
        $offset = 0;
        $per_page = 10;
        $uri_segment = 4;
        //$pagination = $this->common_function->create_link_pagination($page, $offset, site_url('coach/histories/index'), count($this->appointment_model->get_session_histories('coach_id', $this->auth_manager->userid(), $date_from, date('Y-m-d'))), $per_page, $uri_segment);
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/log_activity/index'), count($this->user_log_model->get_log_data()), $per_page, $uri_segment);
        $this->template->title = 'Log Activity';
        $vars = array(
            'data' => $this->user_log_model->get_log_data($per_page, $offset),
            'pagination' => $pagination
        );
        $this->template->content->view('default/contents/admin_m/log_activity/index',$vars);

        //publish template
        $this->template->publish();
    }

}
