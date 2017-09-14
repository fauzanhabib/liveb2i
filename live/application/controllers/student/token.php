<?php

/**
 * Class    : Histories.php
 * Author   : Ponel Panjaitan (ponel@pistarlabs.co.id)
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Token extends MY_Site_Controller {
    
    // Constructor
    public function __construct() {
        parent::__construct();
        
        // Loading models
        $this->load->model('token_histories_model');
        $this->load->model('identity_model');
        $this->load->library('schedule_function');

        // Checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD') {
            $this->messages->add('ERROR');
            redirect('home');
        }
    }
    
    public function index($page=''){
        $this->template->title = 'Student Token';
        $date_from = strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-12 month");
        
        $offset = 0;
        $per_page = 6;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student/token/index'), count($this->token_histories_model->get_token_histories_for_student($date_from, time())), 5, 4);
        $histories = $this->token_histories_model->get_token_histories_for_student($date_from, time(), 5, $offset);
        
        
        // $id = $this->auth_manager->userid();
        // $utz = $this->db->select('user_timezone')
        //         ->from('user_profiles')
        //         ->where('user_id', $id)
        //         ->get()->result();
        // $idutz = $utz[0]->user_timezone;
        // $tz = $this->db->select('*')
        //         ->from('timezones')
        //         ->where('id', $idutz)
        //         ->get()->result();
        
        // $minutes = $tz[0]->minutes;
        $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;



        $vars = array(
            'form_action' => 'search',
            'histories' => $histories,
            'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month")),
            'pagination' => @$pagination,
            'minutes' => $minutes
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();
        
        $this->template->content->view('default/contents/student/token/index', $vars);
        $this->template->publish();
    }
    
    public function search($page=''){
        $this->template->title = 'Student Token';
        
        if($this->input->post('date_from') && $this->input->post('date_to')){
            $this->session->set_userdata('date_from', $this->input->post('date_from'));
            $this->session->set_userdata('date_to', $this->input->post('date_to'));
        }
        $rules = array(
            array('field'=>'date_from', 'label' => 'Start Date', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'date_to', 'label' => 'End Date', 'rules'=>'trim|required|xss_clean')
        );
        if(($this->input->post('__submit'))){
            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                    redirect('student/token');
            }
        }
        
        $date_from = ($this->input->post('date_from') ? $this->input->post('date_from') : $this->session->userdata('date_from'));
        $date_to = ($this->input->post('date_to') ? $this->input->post('date_to') : $this->session->userdata('date_to'));
        
        if(!$date_from || !$date_to || $date_from > $date_to){
            $this->messages->add('Invalid time period', 'danger');
            redirect('student/token');
        }

        $stringdate = strtotime($date_to);
        $stringdatetoday = $stringdate+85399;

        $offset = 0;
        $per_page = 5;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student/token/search'), count($this->token_histories_model->get_token_histories_for_student(strtotime($date_from), $stringdatetoday)), $per_page, $uri_segment);
        $histories = $this->token_histories_model->get_token_histories_for_student(strtotime($date_from), $stringdatetoday, $per_page, $offset);
        
        // $id = $this->auth_manager->userid();
        // $utz = $this->db->select('user_timezone')
        //         ->from('user_profiles')
        //         ->where('user_id', $id)
        //         ->get()->result();
        // $idutz = $utz[0]->user_timezone;
        // $tz = $this->db->select('*')
        //         ->from('timezones')
        //         ->where('id', $idutz)
        //         ->get()->result();
        
        // $minutes = $tz[0]->minutes;
        $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;
        

        $vars = array(
            'form_action' => 'search',
            'histories' => $histories,
            'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month")),
            'pagination' => @$pagination,
            'minutes' => $minutes
        );
        
        $this->template->content->view('default/contents/student/token/index', $vars);
        $this->template->publish();
    }

}
