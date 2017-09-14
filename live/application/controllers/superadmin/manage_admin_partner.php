<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class manage_admin_partner extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('identity_model');
        $this->load->model('partner_model');
        $this->load->model('admin_settings');
        // for messaging action and timing
        $this->load->library('queue');
        $this->load->library('email_structure');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'RAD') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    function generateRandomString($length = 5) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    function index(){
        $this->template->title = 'Manage Admin';

        $group_region = $this->identity_model->group_region();

        $data = $this->identity_model->get_coach_partner_identity();

        $vars = array(
            'group_region' => $group_region,
            'data' => $data
        );


        // echo "<pre>";
        // print_r($data);
        // exit();


        $this->template->content->view('default/contents/superadmin/manage_admin_partner/list_admin_partner', $vars);

        $this->template->publish();
    }

}