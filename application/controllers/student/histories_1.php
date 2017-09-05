<?php

/**
 * Class    : Histories.php
 * Author   : Ponel Panjaitan (ponel@pistarlabs.co.id)
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Histories extends MY_Site_Controller {
    
    // Constructor
    public function __construct() {
        parent::__construct();
        
        // Loading models
        $this->load->model('token_histories_model');
        $this->load->model('appointment_history_model');

        // Checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }
    
    public function index(){
        $this->template->title = 'Student Token';
        $date_from = date('Y-m-d',strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-1 month"));
        $histories = $this->appointment_history_model->get_session_histories('student_id', $date_from, date('Y-m-d'));
        
        $vars = array(
            'form_action' => 'search',
            'histories' => $histories,
            'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month"))
        );
        
        $this->template->content->view('default/contents/student/history_session/index', $vars);
        $this->template->publish();
    }
    
    public function search(){
        $this->template->title = 'Student Token';
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('student/histories/token');
        }
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        
        if(!$date_from || !$date_to || $date_from > $date_to){
            $this->messages->add('Invalid time period', 'danger');
            redirect('student/histories');
        }
        
        $histories = $this->appointment_history_model->get_session_histories('student_id', $date_from, $date_to);
        
        $vars = array(
            'form_action' => 'search',
            'histories' => $histories,
            'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month"))
        );
        
        $this->template->content->view('default/contents/student/history_session/index', $vars);
        $this->template->publish();
    }
}
