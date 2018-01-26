<?php

/**
 * Class    : Student_histories.php
 * Author   : Ponel Panjaitan (ponel@pistarlabs.co.id)
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Student_histories extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();

        // Loading models
        $this->load->model('appointment_model');
        $this->load->model('appointment_history_model');
        $this->load->model('class_meeting_day_model');

        $this->load->library('schedule_function');

        // Checking user role and giving action
        // if (!$this->auth_manager->role() || $this->auth_manager->role() != 'SPR') {
        //     $this->messages->add('ERROR');
        //     redirect('account/identity/detail/profile');
        // }
    }

    // public function index($student_id='', $page=''){
    //     $this->template->title = 'Session History';
    //     $date_from = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-1 month"));

    //     $offset = 0;
    //     $per_page = 5;
    //     $uri_segment = 5;
    //     $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/student_histories/index/'.@$student_id), count($this->appointment_model->get_session_histories('student_id', $student_id, $date_from, date('Y-m-d'))), $per_page, $uri_segment);

    //     $vars = array(
    //         'form_action' => 'search',
    //         'histories' => $this->appointment_model->get_session_histories('student_id', $student_id, $date_from, date('Y-m-d'), $per_page, $offset),
    //         'coach_id' => $this->auth_manager->userid(),
    //         'user' => 'student',
    //         'student_id' => @$student_id,
    //         'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month")),
    //         'pagination' => @$pagination
    //     );

    //     $this->template->content->view('default/contents/coach/history_session/index', $vars);
    //     $this->template->publish();
    // }

    public function index($student_id='', $page=''){
        $this->template->title = 'Student Session';
        $spr_id = $this->auth_manager->userid();
        $date_from = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-100 year"));
        $offset = 0;
        $per_page = 5;
        $uri_segment = 5;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/student_histories/index/'.@$student_id), count($this->appointment_model->get_session_histories('student_id', $student_id, $date_from, date('Y-m-d'))), $per_page, $uri_segment);
        $histories = $this->appointment_model->get_session_histories('student_id', $student_id, $date_from, date('Y-m-d'), $per_page, $offset);
        if ($histories) {
                 foreach (@$histories as $history) {
                //     $gmt_coach = $this->identity_model->new_get_gmt($history->coach_id);
                //     if(@!$gmt_coach){
                //     }else{
                //     $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($history->coach_id)[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                //     }

                    $gmt_student = $this->identity_model->new_get_gmt($student_id);
                    if(@!$gmt_student){
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($spr_id)[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                    }else{
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($student_id)[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                    }

                    //$data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($spr_id)[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                    $history->date = date('Y-m-d', $data_schedule['date']);
                    $history->start_time = $data_schedule['start_time'];
                    $history->end_time = $data_schedule['end_time'];
                }
            }
        $vars = array(
            'form_action' => 'search',
            'histories' => $histories,
            'spr_id' => $spr_id,
            'user' => 'student',
            'student_id' => @$student_id,
            'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month")),
            'pagination' => @$pagination,
        );

        $this->template->content->view('default/contents/student/history_session/index', $vars);
        $this->template->publish();
    }

    // public function search($session='', $student_id='', $page=''){
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
    //             redirect('student_partner/student_histories/index/'.@$student_id);
    //         }elseif($session == 'class'){
    //             redirect('student_partner/student_histories/class_session/'.@$student_id);
    //         }
    //     }

    //     $date_from = ($this->input->post('date_from') ? $this->input->post('date_from') : $this->session->userdata('date_from'));
    //     $date_to = ($this->input->post('date_to') ? $this->input->post('date_to') : $this->session->userdata('date_to'));
    //     if(!$date_from && !$date_to){
    //         redirect('student_partner/student_histories/index/'.@$student_id);
    //     }

    //     $offset = 0;
    //     $per_page = 5;
    //     $uri_segment = 5;

    //     if($session == 'one_to_one'){
    //         $form_action = "search/one_to_one";
    //         $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/student_histories/search/one_to_one/'.@$student_id), count($this->appointment_model->get_session_histories('student_id', @$student_id, $date_from, $date_to, $per_page, $offset)), $per_page, $uri_segment);
    //         $histories = $this->appointment_model->get_session_histories('student_id', @$student_id, $date_from, $date_to, $per_page, $offset);
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
    //         $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/student_histories/search/class/'.@$student_id), count($this->class_member_model->get_session_histories('student_id', @$student_id, $date_from, $date_to, $per_page, $offset)), $per_page, $uri_segment);
    //         $histories = $this->class_member_model->get_session_histories('student_id', @$student_id, $date_from, $date_to, $per_page, $offset);
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
    //         'user' => 'student',
    //         'student_id' => @$student_id,
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

    public function search($session='', $student_id='', $page=''){
        $this->template->title = 'Student Session';
        $spr_id = $this->auth_manager->userid();
        if($this->input->post('date_from') && $this->input->post('date_to')){
            $this->session->set_userdata('date_from', $this->input->post('date_from'));
            $this->session->set_userdata('date_to', $this->input->post('date_to'));
        }
        $rules = array(
            array('field'=>'date_from', 'label' => 'Date From', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'date_to', 'label' => 'Date To', 'rules'=>'trim|required|xss_clean')
        );
        if(($this->input->post('__submit'))){
                if (!$this->common_function->run_validation($rules)) {
                    $this->messages->add(validation_errors(), 'warning');
                    if($session == 'one_to_one'){
                        redirect('student_partner/student_histories/index/'.@$student_id);
                    }elseif($session == 'class'){
                        redirect('student_partner/student_histories/class_session/'.@$student_id);
                    }
                }
            }

        $date_from = ($this->input->post('date_from') ? $this->input->post('date_from') : $this->session->userdata('date_from'));
        $date_to = ($this->input->post('date_to') ? $this->input->post('date_to') : $this->session->userdata('date_to'));
        if(!$date_from && !$date_to){
            redirect('student_partner/student_histories');
        }

        $offset = 0;
        $per_page = 5;
        $uri_segment = 6;

        if($session == 'one_to_one'){
            $form_action = "search/one_to_one";
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/student_histories/search/one_to_one/'.@$student_id), count($this->appointment_model->get_session_histories('student_id', @$student_id, $date_from, $date_to, $per_page, $offset)), $per_page, $uri_segment);
            $histories = $this->appointment_model->get_session_histories('student_id', @$student_id, $date_from, $date_to, $per_page, $offset);
            if ($histories) {
                foreach (@$histories as $history) {
                    // $gmt_coach = $this->identity_model->new_get_gmt($history->coach_id);
                    // if(@!$gmt_coach){
                    // $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($spr_id)[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                    // }else{
                    // $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($history->coach_id)[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                    // }

                    $gmt_student = $this->identity_model->new_get_gmt($student_id);
                    if(@!$gmt_student){
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($spr_id)[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                    }else{
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($student_id)[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                    }

                    //$data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($spr_id)[0]->minutes, strtotime($history->date), $history->start_time, $history->end_time);
                    $history->date = date('Y-m-d', $data_schedule['date']);
                    $history->start_time = $data_schedule['start_time'];
                    $history->end_time = $data_schedule['end_time'];
                }
            }
        }elseif($session == 'class'){
            $form_action = "search/class";
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/student_histories/search/class/'.@$student_id), count($this->class_member_model->get_session_histories('student_id', @$student_id, $date_from, $date_to, $per_page, $offset)), $per_page, $uri_segment);
            $histories = $this->class_member_model->get_session_histories('student_id', @$student_id, $date_from, $date_to, $per_page, $offset);
            if ($histories) {
                foreach ($histories as $h) {
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($h->date), $h->start_time, $h->end_time);
                    $h->date = date('Y-m-d', $data_schedule['date']);
                    $h->start_time = $data_schedule['start_time'];
                    $h->end_time = $data_schedule['end_time'];
                }
            }
        }

        $vars = array(
            'form_action' => 'search',
            'histories' => $histories,
            'spr_id' => $this->auth_manager->userid(),
            'user' => 'student',
            'student_id' => @$student_id,
            'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month")),
            'pagination' => @$pagination,
        );
        if($session == 'one_to_one'){
            $this->template->content->view('default/contents/student/history_session/index', $vars);
        }elseif($session == 'class'){
            $this->template->content->view('default/contents/student/history_session/class', $vars);
        }

        $this->template->publish();
    }

    public function class_session($student_id='', $page=''){
        $this->template->title = 'Class Session Histories';
        $date_from = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-1 month"));

        $offset = 0;
        $per_page = 5;
        $uri_segment = 5;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/student_histories/class_session/'.@$student_id), count($this->class_member_model->get_session_histories('student_id', $student_id, $date_from, date('Y-m-d'))), $per_page, $uri_segment);
        $histories = $this->class_member_model->get_session_histories('student_id', $student_id, $date_from, date('Y-m-d'), $per_page, $offset);
        if ($histories) {
            foreach ($histories as $h) {
                $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($h->date), $h->start_time, $h->end_time);
                $h->date = date('Y-m-d', $data_schedule['date']);
                $h->start_time = $data_schedule['start_time'];
                $h->end_time = $data_schedule['end_time'];
            }
        }

        $vars = array(
            'form_action' => 'searchtyuio',
            'histories' => @$histories,
            'user' => 'student',
            'student_id' => $student_id,
            'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month")),
            'pagination' => @$pagination
        );

        $this->template->content->view('default/contents/coach/history_session/class', $vars);
        $this->template->publish();
    }
}
