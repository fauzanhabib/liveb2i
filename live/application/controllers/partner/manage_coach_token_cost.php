<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class manage_coach_token_cost extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('identity_model');
        $this->load->model('coach_token_cost_model');
        $this->load->model('user_model');
        // for messaging action and timing
        $this->load->library('queue');
        $this->load->library('email_structure');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'PRT') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = "Manage Coach Token Cost";
        $vars = array(
            'title' => 'Manage Coach Token Cost',
            'data' => $this->identity_model->get_coach_identity('','','',$this->auth_manager->partner_id()),
        );
        $this->template->content->view('default/contents/manage_coach_token_cost/index', $vars);
        $this->template->publish();
    }
    
    public function edit($coach_id = ''){
        $this->template->title = "Edit Coach Token Cost";
        $vars = array(
            'title' => 'Edit Coach Token Cost',
            'data' => $this->identity_model->get_coach_identity($coach_id,'','',$this->auth_manager->partner_id()),
            'form_action' => 'update',
        );
        // setting day for editing adding data
        $this->session->set_userdata("coach_id", $coach_id);
        
        //print_r($vars); exit;
        $this->template->content->view('default/contents/manage_coach_token_cost/form', $vars);
        $this->template->publish();
    }
    
    public function update(){
        //print_r($this->input); exit;
        // Creating a student user data must be followed by creating profile data and status still disable/need approval from admin
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('partner/manage_coach_token_cost');
        }

        // updating coach token cost
        $token_cost = array(
            'token_for_student' => $this->input->post('token_for_student'),
            'token_for_group' => $this->input->post('token_for_group'),
        );

        $coach_id_to_token_id = $this->coach_token_cost_model->dropdown('coach_id', 'id');
        $coach_id_to_token_id = @$coach_id_to_token_id[$this->session->userdata("coach_id")];
        // Updating and checking to users coach_token_cost
        if (!$this->coach_token_cost_model->update(@$coach_id_to_token_id,$token_cost)) {
            $this->messages->add(validation_errors(), 'danger');
            $this->index();
            return;
        }
        
        $id_to_email_address = $this->user_model->dropdown('id', 'email');
        // Tube name for messaging sending email action
        $tube = 'com.live.email';
        // Email's content to inform partner admin their DynEd Live account
        $data_coach = array(
            'subject' => '[Update] Token Cost',
            'email' => $id_to_email_address[$this->session->userdata("coach_id")],
            //'content' => 'Your token cost has been updated. Token for student = '. $token_cost['token_for_student']. ' and token for group = ' .$token_cost['token_for_group'],
        );
        $data_coach['content'] = $this->email_structure->header()
                .$this->email_structure->title('[Update] Token Cost')
                .$this->email_structure->content('Your token cost has been updated. Token for student = '. $token_cost['token_for_student']. ' and token for group = ' .$token_cost['token_for_group'])
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');
        $this->queue->push($tube, $data_coach, 'email.send_email');

        //messaging for notification
        $database_tube = 'com.live.database';
        $coach_notification = array(
            'user_id' => $this->session->userdata("coach_id"),
            'description' => 'Your token cost has been updated. Token for student = '. $token_cost['token_for_student']. ' and token for group = ' .$token_cost['token_for_group'],
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );
        // coach's data for reminder messaging
        // IMPORTANT : array index in content must be in mutual with table field in database
        $data_coach = array(
            'table' => 'user_notifications',
            'content' => $coach_notification,
        );

        // messaging inserting data notification
        $this->queue->push($database_tube, $data_coach, 'database.insert');
        
        //unsetting day_adding
        $this->session->unset_userdata("coach_id");
        
        $this->messages->add('Update Successful', 'success');
        redirect('partner/manage_coach_token_cost');


    }
}