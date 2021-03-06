<?php

/**
 * Class    : Histories.php
 * Author   : Ponel Panjaitan (ponel@pistarlabs.co.id)
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Coach_histories extends MY_Site_Controller {
    
    // Constructor
    public function __construct() {
        parent::__construct();
        
        // Loading models
        $this->load->model('appointment_model');
        $this->load->model('appointment_history_model');
        $this->load->model('class_meeting_day_model');

        $this->load->library('schedule_function');
        $this->load->library('common_function');
        // Checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CAM') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // public function index($page='',$id = ''){
    //     $this->template->title = 'Session Histories';
    //     $date_from = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-1 month"));
        
    //     $offset = 0;
    //     $per_page = 5;
    //     $uri_segment = 4;
    //     $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('coach/histories/index'), count($this->appointment_model->get_session_histories('coach_id', $id, $date_from, date('Y-m-d'))), $per_page, $uri_segment);
    //     $histories = $this->appointment_model->get_session_histories('coach_id', $id, $date_from, date('Y-m-d'), $per_page, $offset);
        
    //     if ($histories) {
    //         foreach ($histories as $h) {
    //             $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($h->date), $h->start_time, $h->end_time);
    //             $h->date = date('Y-m-d', $data_schedule['date']);
    //         }
    //     }


    //     $minutes = $this->common_function->get_usertimezone($id);
        
    //     $vars = array(
    //         'form_action' => 'search/one_to_one',
    //         'histories' => @$histories,
    //         'coach_id' => $id,
    //         'user' => 'coach',
    //         'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month")),
    //         'pagination' => @$pagination,
    //         'minutes' => $minutes
    //     );
        
    //     $this->template->content->view('default/contents/coach/history_session/index', $vars);
    //     $this->template->publish();
    // }

    public function index($id = '', $page=''){

        $this->template->title = 'Coach Session';
        $date_from = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-100 year"));
        $prt_id = $this->auth_manager->userid();
        $offset = 0;
        $per_page = 3;
        $uri_segment = 5;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner_monitor/coach_histories/index/'.@$id), count($this->appointment_model->get_session_histories('coach_id', $id, $date_from, date('Y-m-d'))), $per_page, $uri_segment);
        $histories = $this->appointment_model->get_session_histories('coach_id', $id, $date_from, date('Y-m-d'), $per_page, $offset);
        if ($histories) {
                foreach (@$histories as $history) {
                    $gmt_coach = $this->identity_model->new_get_gmt($id);
                    if(@!$gmt_coach){
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($prt_id)[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                    }else{
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($id)[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                    }
                    
                    //$data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($prt_id)[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                    $history->date = date('Y-m-d', $data_schedule['date']);
                    $history->start_time = $data_schedule['start_time'];
                    $history->end_time = $data_schedule['end_time'];
                }
            }
        $vars = array(
            'form_action' => 'search',
            'histories' => $histories,
            'coach_ids' => $this->auth_manager->userid(),
            'user' => 'coach',
            //'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month")),
            'coach_id' => $id,
            'pagination' => @$pagination,
        );
        // echo "<pre>";
        // print_r($vars);
        // exit;
        // die;
        
        
        $this->template->content->view('default/contents/coach/history_session/index', $vars);
        $this->template->publish();
    }
    
    // public function index($page='', $id = ''){

    //     $this->template->title = 'Coach Session';
    //     $date_from = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-1 month"));
    //     $minutes = $this->common_function->get_usertimezone($id);
    //     $offset = 0;
    //     $per_page = 5;
    //     $uri_segment = 4;
    //     $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner_monitor/coach_histories/index'), count($this->appointment_model->get_session_histories('coach_id', $id, $date_from, date('Y-m-d'))), $per_page, $uri_segment);
        
    //     $vars = array(
    //         'form_action' => 'search',
    //         'histories' => $this->appointment_model->get_session_histories('coach_id', $id, $date_from, date('Y-m-d'), $per_page, $offset),
    //         'coach_id' => $this->auth_manager->userid(),
    //         'user' => 'coach',
    //         'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month")),
    //         'pagination' => @$pagination,
    //         'coach_id' => $id,
    //         'minutes' => $minutes
    //     );
        
    //     $this->template->content->view('default/contents/coach/history_session/index', $vars);
    //     $this->template->publish();
    // }
    
    // public function search($session='', $page=''){
    //     $this->template->title = 'Coach Session';
    //     if($this->input->post('date_from') && $this->input->post('date_to')){
    //         $this->session->set_userdata('date_from', $this->input->post('date_from'));
    //         $this->session->set_userdata('date_to', $this->input->post('date_to'));
    //     }
        
    //     $rules = array(
    //         array('field'=>'date_from', 'label' => 'Date From', 'rules'=>'trim|required|xss_clean'),
    //         array('field'=>'date_to', 'label' => 'Date To', 'rules'=>'trim|required|xss_clean')
    //     );

    //     if (!$this->common_function->run_validation($rules)) {
    //         $this->messages->add(validation_errors(), 'warning');
    //         if($session == 'one_to_one'){
    //             redirect('partner_monitor/coach_histories');
    //         }elseif($session == 'class'){
    //             redirect('partner_monitor/coach_histories/class_session');
    //         }
    //     }
        
    //     $date_from = ($this->input->post('date_from') ? $this->input->post('date_from') : $this->session->userdata('date_from'));
    //     $date_to = ($this->input->post('date_to') ? $this->input->post('date_to') : $this->session->userdata('date_to'));
    //     if(!$date_from && !$date_to){
    //         redirect('partner_monitor/coach_histories');
    //     }
                
    //     $offset = 0;
    //     $per_page = 5;
    //     $uri_segment = 4;
        
    //     if($session == 'one_to_one'){
    //         $form_action = "search/one_to_one";
    //         $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner_monitor/coach_histories/search/one_to_one'), count($this->appointment_model->get_session_histories('coach_id', $this->session->userdata('coach_id'), $date_from, $date_to, $per_page, $offset)), $per_page, $uri_segment);
    //         $histories = $this->appointment_model->get_session_histories('coach_id', $this->session->userdata('coach_id'), $date_from, $date_to, $per_page, $offset);
    //         if ($histories) {
    //             foreach ($histories as $h) {
    //                 $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($h->date), $h->start_time, $h->end_time);
    //                 $h->date = date('Y-m-d', $data_schedule['date']);
    //                 $h->start_time = $data_schedule['start_time'];
    //                 $h->end_time = $data_schedule['end_time'];
    //             }
    //         }
    //     }elseif($session == 'class'){
    //         $form_action = "search/class";
    //         $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner_monitor/coach_histories/search/class'), count($this->class_meeting_day_model->get_session_histories('coach_id', $this->session->userdata('coach_id'), $date_from, $date_to, $per_page, $offset)), $per_page, $uri_segment);
    //         $histories = $this->class_meeting_day_model->get_session_histories('coach_id', $this->session->userdata('coach_id'), $date_from, $date_to, $per_page, $offset);
    //         if ($histories) {
    //             foreach ($histories as $h) {
    //                 $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($h->date), $h->start_time, $h->end_time);
    //                 $h->date = date('Y-m-d', $data_schedule['date']);
    //                 $h->start_time = $data_schedule['start_time'];
    //                 $h->end_time = $data_schedule['end_time'];
    //             }
    //         }
    //     }
        
    //     $vars = array(
    //         'form_action' => 'search',
    //         'histories' => $histories,
    //         'coach_id' => $this->auth_manager->userid(),
    //         'user' => 'coach',
    //         'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month")),
    //         'pagination' => @$pagination
    //     );
    //     if($session == 'one_to_one'){
    //         $this->template->content->view('default/contents/coach/history_session/index', $vars);
    //     }elseif($session == 'class'){
    //         $this->template->content->view('default/contents/coach/history_session/class', $vars);
    //     }
        
    //     $this->template->publish();
    // }
    public function search($session='', $id='', $page=''){
        $this->template->title = 'Coach Session';
        if($this->input->post('date_from') && $this->input->post('date_to')){
            $this->session->set_userdata('date_from', $this->input->post('date_from'));
            $this->session->set_userdata('date_to', $this->input->post('date_to'));
        }
        $prt_id = $this->auth_manager->userid();
        $rules = array(
            array('field'=>'date_from', 'label' => 'Date From', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'date_to', 'label' => 'Date To', 'rules'=>'trim|required|xss_clean')
        );
        if(($this->input->post('__submit'))){
            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                if($session == 'one_to_one'){
                    redirect('partner_monitor/coach_histories/index/'.@$id);
                    
                }elseif($session == 'class'){
                    redirect('partner_monitor/coach_histories/class_session');
                    
                }
            }
        }
        
        $date_from = ($this->input->post('date_from') ? $this->input->post('date_from') : $this->session->userdata('date_from'));
        $date_to = ($this->input->post('date_to') ? $this->input->post('date_to') : $this->session->userdata('date_to'));
        if(!$date_from && !$date_to){
            redirect('partner_monitor/coach_histories');
            
        }
                
        $offset = 0;
        $per_page = 3;
        $uri_segment = 6;
        
        if($session == 'one_to_one'){
            $form_action = "search/one_to_one";
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner_monitor/coach_histories/search/one_to_one/'.@$id), count($this->appointment_model->get_session_histories('coach_id', $id, $date_from, $date_to, $per_page, $offset)), $per_page, $uri_segment);
            $histories = $this->appointment_model->get_session_histories('coach_id', $id, $date_from, $date_to, $per_page, $offset);
            if ($histories) {
                foreach (@$histories as $history) {
                    $gmt_coach = $this->identity_model->new_get_gmt($id);
                    if(@!$gmt_coach){
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($prt_id)[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                    }else{
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($id)[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                    }
                    
                    //$data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($prt_id)[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                    $history->date = date('Y-m-d', $data_schedule['date']);
                    $history->start_time = $data_schedule['start_time'];
                    $history->end_time = $data_schedule['end_time'];
                }
            }
        }elseif($session == 'class'){
            $form_action = "search/class";
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner_monitor/coach_histories/search/class'), count($this->class_meeting_day_model->get_session_histories('coach_id', $this->session->userdata('coach_id'), $date_from, $date_to, $per_page, $offset)), $per_page, $uri_segment);
            $histories = $this->class_meeting_day_model->get_session_histories('coach_id', $this->session->userdata('coach_id'), $date_from, $date_to, $per_page, $offset);
            if ($histories) {
                foreach ($histories as $h) {
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($h->date), $h->start_time, $h->end_time);
                    $h->date = date('Y-m-d', $data_schedule['date']);
                    $h->start_time = $data_schedule['start_time'];
                    $h->end_time = $data_schedule['end_time'];
                }
            }
        }
        
        $vars = array(
            'session' => $session,
            'form_action' => 'search',
            'histories' => $histories,
            'coach_id' => $id,
            'user' => 'coach',
            'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month")),
            'pagination' => @$pagination,
        );
        // echo "<pre>";
        // print_r($vars);
        // exit;
        // die;
        if($session == 'one_to_one'){
            $this->template->content->view('default/contents/coach/history_session/index', $vars);
        }elseif($session == 'class'){
            $this->template->content->view('default/contents/coach/history_session/class', $vars);
        }
        
        $this->template->publish();
    }

    public function class_session($page=''){
        $this->template->title = 'Class Session Histories';
        $date_from = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-1 month"));
        
        $offset = 0;
        $per_page = 5;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner_monitor/coach_histories/class_session'), count($this->class_meeting_day_model->get_session_histories('coach_id', $this->session->userdata('coach_id'), $date_from, date('Y-m-d'))), $per_page, $uri_segment);
        $histories = $this->class_meeting_day_model->get_session_histories('coach_id', $this->session->userdata('coach_id'), $date_from, date('Y-m-d'), $per_page, $offset);
        if ($histories) {
            foreach ($histories as $h) {
                $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($h->date), $h->start_time, $h->end_time);
                $h->date = date('Y-m-d', $data_schedule['date']);
                $h->start_time = $data_schedule['start_time'];
                $h->end_time = $data_schedule['end_time'];
            }
        }
        
        $vars = array(
            'form_action' => 'searchtyuio',
            'histories' => @$histories,
            'coach_id' => $this->auth_manager->userid(),
            'user' => 'coach',
            'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month")),
            'pagination' => @$pagination
        );
        
        $this->template->content->view('default/contents/coach/history_session/class', $vars);
        $this->template->publish();
    }
}
