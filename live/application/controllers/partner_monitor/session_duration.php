<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class session_duration extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('partner_model');
        $this->load->model('user_model');
        // for messaging action and timing
        $this->load->library('queue');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CAM') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        //print_r($this->coach_day_off_model->get_coach_day_off()); exit;
        $partner_id = $this->auth_manager->partner_id();
        $this->template->title = 'Manage Session Duration Coach';
        $vars = array(
            'data' => $this->partner_model->select('id, session_per_block_by_partner')->where('id', $partner_id)->get(),
        );
        $this->template->content->view('default/contents/session_duration/index', $vars);

        //publish template
        $this->template->publish(); 
    }

    public function update() {
        //echo($id); exit;
        // Checking ID
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('partner_monitor/manage_coach_token_cost');
        }
        // updating duration
        $data = array(
            'session_per_block_by_partner' => $this->input->post('session_per_block_by_partner'),
        );
        
        // Inserting and checking
        if (!$this->partner_model->update($this->auth_manager->partner_id(), $data)) {
            $this->messages->add(validation_errors(), 'Invalid ID');
            redirect('partner_monitor/member_list/coach');
        }
        
        $this->messages->add('Update Session Duration Successful', 'success');
        redirect('partner_monitor/member_list/coach');
    }
}
