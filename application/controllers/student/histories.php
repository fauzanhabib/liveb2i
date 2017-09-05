<?php

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
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');
        $this->load->model('identity_model');
        $this->load->library('downloadrecord');
        $this->load->library('schedule_function');

        // Checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }
    
    public function index($page=''){
        $this->template->title = 'Session Histories';
        $date_from = date('Y-m-d',strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-100 year"));
        $student_id = $this->auth_manager->userid();
        $offset = 0;
        $per_page = 5;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student/histories/index'), count($this->appointment_model->get_session_histories('student_id', $this->auth_manager->userid(),$date_from, date('Y-m-d'))), $per_page, $uri_segment);
        $histories = $this->appointment_model->get_session_histories('student_id', $this->auth_manager->userid(), $date_from, date('Y-m-d'), $per_page, $offset);
        if ($histories) {
            foreach ($histories as $history) {
                $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                $history->date = date('Y-m-d', $data_schedule['date']);
                $history->start_time = $data_schedule['start_time'];
                $history->end_time = $data_schedule['end_time'];
            }
        }
        foreach($histories as $his){
            $sessid[] = $his->session;
        }
        // CONVERT ATTENDANCE TIME
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


        // END
        
        // API FOR DOWNLOAD RECORD ----------------------------------
        // @$apirecord = $this->downloadrecord->init();
        
        // @$items = @$apirecord->items;
        // foreach(@$items as $a){
        //     $sessionID = $a->sessionId;
        //     $url       = $a->url;
        //     $archId    = $a->id;

        //     if($sessionID == @$sessid){
        //         $download = $url;
        //         $archiveId = $archId;
        //     }
        // }
        // API END ---------------------------------------------------
        $vars = array(
            'form_action' => 'search',
            'histories' => @$histories,
            'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-1 month")),
            'pagination' => @$pagination,
            'minutes' => @$minutes,
            'student_id' => $student_id

        );
        
        // echo "<pre>";
        // print_r($minutes);
        // exit();
        $this->template->content->view('default/contents/student/history_session/index', $vars);
        $this->template->publish();
    }
    
    public function search($session='', $page=''){
        $this->template->title = 'Student Token';
        
        if($this->input->post('date_from') && $this->input->post('date_to')){
            $this->session->set_userdata('date_from', $this->input->post('date_from'));
            $this->session->set_userdata('date_to', $this->input->post('date_to'));
        }
        
        $rules = array(
            array('field'=>'date_from', 'label' => 'Start Date', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'date_to', 'label' => 'End Date', 'rules'=>'trim|required|xss_clean')
        );
        $student_id = $this->auth_manager->userid();
        if(($this->input->post('__submit'))){
            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                if($session == 'one_to_one'){
                    redirect('student/histories');
                }elseif($session == 'class'){
                    redirect('student/histories/class_session');
                }
            }
        }
        
        $date_from = ($this->input->post('date_from') ? $this->input->post('date_from') : $this->session->userdata('date_from'));
        $date_to = ($this->input->post('date_to') ? $this->input->post('date_to') : $this->session->userdata('date_to'));
        
        if(!$date_from || !$date_to || $date_from > $date_to){
            $this->messages->add('Invalid time period', 'warning');
            if($session == 'one_to_one'){
                redirect('student/histories');
            }elseif($session == 'class'){
                redirect('student/histories/class_session');
            }
        }
        
        $offset = 0;
        $per_page = 5;
        $uri_segment = 5;
        
        if($session == 'one_to_one'){
            $form_action = "search/one_to_one";
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student/histories/search/one_to_one'), count($this->appointment_model->get_session_histories('student_id', $this->auth_manager->userid(), $date_from, $date_to, $per_page, $offset)), $per_page, $uri_segment);
            $histories = $this->appointment_model->get_session_histories('student_id', $this->auth_manager->userid(), $date_from, $date_to, $per_page, $offset);
            if ($histories) {
                foreach ($histories as $history) {
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                    $history->date = date('Y-m-d', $data_schedule['date']);
                    $history->start_time = $data_schedule['start_time'];
                    $history->end_time = $data_schedule['end_time'];
                }
            }
        }elseif($session == 'class'){
            $form_action = "search/class";
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student/histories/search/class'), count($this->class_member_model->get_session_histories('student_id', $this->auth_manager->userid(), $date_from, $date_to, $per_page, $offset)), $per_page, $uri_segment);
            $histories = $this->class_member_model->get_session_histories('student_id', $this->auth_manager->userid(), $date_from, $date_to, $per_page, $offset);
            if ($histories) {
                foreach ($histories as $history) {
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($h->date), $h->start_time, $h->end_time);
                    $history->date = date('Y-m-d', $data_schedule['date']);
                    $history->start_time = $data_schedule['start_time'];
                    $history->end_time = $data_schedule['end_time'];
                }
            }
        }

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
            'histories' => @$histories,
            'minutes' => @$minutes,
            'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month")),
            'pagination' => @$pagination,
            'student_id' => $student_id
        );
        
        $this->template->content->view('default/contents/student/history_session/index', $vars);
        $this->template->publish();
    }
    
    public function class_session($page=''){
        $this->template->title = 'Class Session Histories';
        $date_from = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-1 month"));
        
        $offset = 0;
        $per_page = 5;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student/histories/class_session'), count($this->class_member_model->get_session_histories('student_id', $this->auth_manager->userid(), $date_from, date('Y-m-d'))), $per_page, $uri_segment);
        $histories = $this->class_member_model->get_session_histories('student_id', $this->auth_manager->userid(), $date_from, date('Y-m-d'), $per_page, $offset);
        if ($histories) {
            foreach ($histories as $h) {
                $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($h->date), $h->start_time, $h->end_time);
                $h->date = date('Y-m-d', $data_schedule['date']);
                $h->start_time = $data_schedule['start_time'];
                $h->end_time = $data_schedule['end_time'];
            }
        }
        
        $vars = array(
            'form_action' => 'search/class',
            'histories' => @$histories,
            'user' => 'coach',
            'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month")),
            'pagination' => @$pagination
        );
        
        $this->template->content->view('default/contents/student/history_session/class', $vars);
        $this->template->publish();
    }
}
