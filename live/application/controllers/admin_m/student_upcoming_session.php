<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student_upcoming_session extends MY_Site_Controller {
 
    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('identity_model');
        
        $this->load->library('schedule_function');
        $this->load->library('auth_manager');
                

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'RAM') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = "Upcoming Session";
        $data = $this->appointment_model->get_appointment_for_upcoming_session('student_id');
        $data_class = $this->class_member_model->get_appointment_for_upcoming_session();
        
        $upcoming = array();
        if ($data) {
            foreach ($data as $d) {
                if ($d->date == date('Y-m-d')) {
                    if ($d->start_time >= date('H:i:s')) {
                        $upcoming [] = $d;
                    }
                } else {
                    $upcoming [] = $d;
                }
                $data_schedule = $this->convertBookSchedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
                
            }
        }

        $upcoming_class = array();
        if ($data_class) {
            foreach ($data_class as $d) {
                if ($d->date == date('Y-m-d')) {
                    if ($d->start_time >= date('H:i:s')) {
                        $upcoming_class [] = $d;
                    }
                } else {
                    $upcoming_class [] = $d;
                }
                $data_schedule = $this->convertBookSchedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
            }
        }

        $vars = array(
            'title' => 'Upcoming Session',
            'role' => 'Coach',
            'data' => $upcoming,
            'data_class' => $upcoming_class,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
        );

        $this->template->content->view('default/contents/student/upcoming_session/index', $vars);
        $this->template->publish();
    }
    
    /*public function search() {
        $this->template->title = "Upcoming Session";
        $data = $this->appointment_model->get_appointment_for_upcoming_session('student_id', $this->input->post('date_from'), $this->input->post('date_to'));
        $data_class = $this->class_member_model->get_appointment_for_upcoming_session($this->input->post('date_from'), $this->input->post('date_to'));

        $upcoming = array();
        if ($data) {
            foreach ($data as $d) {
                if ($d->date == date('Y-m-d')) {
                    if ($d->start_time >= date('H:i:s')) {
                        $upcoming [] = $d;
                    }
                } else {
                    $upcoming [] = $d;
                }
                $data_schedule = $this->convertBookSchedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
            }
        }

        $upcoming_class = array();
        if ($data_class) {
            foreach ($data_class as $d) {
                if ($d->date == date('Y-m-d')) {
                    if ($d->start_time >= date('H:i:s')) {
                        $upcoming_class [] = $d;
                    }
                } else {
                    $upcoming_class [] = $d;
                }
                $data_schedule = $this->convertBookSchedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
            }
        }

        $vars = array(
            'title' => 'Upcoming Session',
            'role' => 'Coach',
            'start_date' => $this->input->post('date_from'),
            'end_date' => $this->input->post('date_to'),
            'data' => $upcoming,
            'data_class' => $upcoming_class,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
        );

        $this->template->content->view('default/contents/student/upcoming_session/index', $vars);
        $this->template->publish();
    }*/

    public function coach_detail($coach_id = '') {
        redirect('student/session/coach_detail/'.$coach_id);
    }
    
    // public function one_to_one_session($student_id='', $page='') {
    //     $offset = 0;
    //     $per_page = 5;
    //     $uri_segment = 5;
    //     $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/student_upcoming_session/one_to_one_session/'.$student_id), count($this->appointment_model->get_appointment_for_upcoming_session('student_id', '', '', $student_id)), $per_page, $uri_segment);
    //     $data = $this->appointment_model->get_appointment_for_upcoming_session('student_id', '', '', $student_id, $per_page, $offset);
    //     if ($data) {
    //         foreach ($data as $d) {
    //             $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
    //             $d->date = date('Y-m-d', $data_schedule['date']);
    //             $d->start_time = $data_schedule['start_time'];
    //             $d->end_time = $data_schedule['end_time'];
    //         }
    //     }
    //     $vars = array(
    //         'title' => 'Upcoming Session',
    //         'role' => 'Student',
    //         'data' => $data,
    //         'student_id' => @$student_id,
    //         'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
    //         'id_webex' => $this->webex_model->select('id')->where('status', 'SCHE')->get_all(),
    //         'pagination' => @$pagination
    //     );

    //     $this->template->content->view('default/contents/admin_m/student_upcoming_session/one_to_one_session', $vars);
    //     $this->template->publish();
    // }

    public function one_to_one_session($student_id='', $page='') {
        $this->template->title = "Upcoming Session";
        $adm_id = $this->auth_manager->userid();
        $offset = 0;
        $per_page = 5;
        $uri_segment = 5;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/student_upcoming_session/one_to_one_session/'.$student_id), count($this->appointment_model->get_appointment_for_upcoming_session('student_id', '', '', $student_id)), $per_page, $uri_segment);
        $data = $this->appointment_model->get_appointment_for_upcoming_session('student_id', '', '', $student_id, $per_page, $offset);
        if ($data) {
            foreach ($data as $d) {
                $gmt_student = $this->identity_model->new_get_gmt($student_id);
                    if(@!$gmt_student){
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($adm_id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                    }else{
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($student_id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                    }
                
                //$data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($spr_id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);    
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
            }
        }
        $vars = array(
            'title' => 'Upcoming Session',
            'role' => 'Student',
            'data' => $data,
            'student_id' => @$student_id,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'id_webex' => $this->webex_model->select('id')->where('status', 'SCHE')->get_all(),
            'pagination' => @$pagination
        );

        $this->template->content->view('default/contents/admin_m/student_upcoming_session/one_to_one_session', $vars);
        $this->template->publish();
    }

    public function class_session($student_id='', $page='') {
        $offset = 0;
        $per_page = 5;
        $uri_segment = 5;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/student_upcoming_session/class_session/'.@$student_id), count($this->class_member_model->get_appointment_for_upcoming_session('', '', @$student_id)), $per_page, $uri_segment);
        $data_class_upcoming = $this->class_member_model->get_appointment_for_upcoming_session('', '', $student_id, $per_page, $offset);
        if ($data_class_upcoming) {
            foreach ($data_class_upcoming as $data_class) {
                $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($data_class->date), $data_class->start_time, $data_class->end_time);
                $data_class->date = date('Y-m-d', $data_schedule['date']);
                $data_class->start_time = $data_schedule['start_time'];
                $data_class->end_time = $data_schedule['end_time'];
            }
        }

        $vars = array(
            'title' => 'Upcoming Session',
            'role' => 'Student',
            'student_id' => @$student_id,
            'data_class' => @$data_class_upcoming,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'id_webex' => $this->webex_model->select('id')->where('status', 'SCHE')->get_all(),
            'pagination' => @$pagination
        );

        $this->template->content->view('default/contents/admin_m/student_upcoming_session/class_session', $vars);
        $this->template->publish();
    }

    // public function search($session = '', $student_id='',  $page='') {
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
    //         redirect('admin_m/student_upcoming_session/'.$session.'_session/'.@$student_id);
    //     }
        
    //     $date_from = ($this->input->post('date_from') ? $this->input->post('date_from') : $this->session->userdata('date_from'));
    //     $date_to = ($this->input->post('date_to') ? $this->input->post('date_to') : $this->session->userdata('date_to'));
    //     if(!$date_from && !$date_to){
    //         redirect('admin_m/student_upcoming_session/'.$session.'_session/'.@$student_id);
    //     }
        
    //     $offset = 0;
    //     $per_page = 5;
    //     $uri_segment = 5;
 
    //     if ($session == 'one_to_one') {
    //         $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/student_upcoming_session/search/one_to_one/'.@$student_id), count($this->appointment_model->get_appointment_for_upcoming_session('student_id', $date_from, $date_to, $student_id)), $per_page, $uri_segment);
    //         $data = $this->appointment_model->get_appointment_for_upcoming_session('student_id', $date_from, $date_to, $student_id, $per_page, $offset);
    //         if ($data) {
    //             foreach ($data as $d) {
    //                 $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
    //                 $d->date = date('Y-m-d', $data_schedule['date']);
    //                 $d->start_time = $data_schedule['start_time'];
    //                 $d->end_time = $data_schedule['end_time'];
    //             }
    //         }
    //     } elseif ($session == 'class') {
    //         $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/student_upcoming_session/search/class/'.@$student_id), count($this->class_member_model->get_appointment_for_upcoming_session($date_from, $date_to, $student_id)), $per_page, $uri_segment);
    //         $data_class = $this->class_member_model->get_appointment_for_upcoming_session($this->input->post('date_from'), $this->input->post('date_to'), $student_id, $per_page, $offset);
    //         if ($data_class) {
    //             foreach ($data_class as $data) {
    //                 $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($data->date), $data->start_time, $data->end_time);
    //                 $data->date = date('Y-m-d', $data_schedule['date']);
    //                 $data->start_time = $data_schedule['start_time'];
    //                 $data->end_time = $data_schedule['end_time'];
    //             }
    //         }
    //     }
        
    //     $vars = array(
    //         'title' => 'Upcoming Session',
    //         'role' => 'Student',
    //         'data' => @$data,
    //         'data_class' => @$data_class,
    //         'student_id' => @$student_id,
    //         'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
    //         'id_webex' => $this->webex_model->select('id')->where('status', 'SCHE')->get_all(),
    //         'pagination' => @$pagination
    //     );

    //     $this->template->content->view('default/contents/admin_m/student_upcoming_session/' . (($session == 'one_to_one') ? 'one_to_one_session' : 'class_session'), $vars);
    //     $this->template->publish();
    // }

    public function search($session = '', $student_id='',  $page='') {
        $this->template->title = 'Coach Session';
        $adm_id = $this->auth_manager->userid();
        if($this->input->post('date_from') && $this->input->post('date_to')){
            $this->session->set_userdata('date_from', $this->input->post('date_from'));
            $this->session->set_userdata('date_to', $this->input->post('date_to'));
        }
        
        $rules = array(
            array('field'=>'date_from', 'label' => 'Date From', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'date_to', 'label' => 'Date To', 'rules'=>'trim|required|xss_clean')
        );

        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            redirect('admin_m/student_upcoming_session/'.$session.'_session/'.@$student_id);
        }
        
        $date_from = ($this->input->post('date_from') ? $this->input->post('date_from') : $this->session->userdata('date_from'));
        $date_to = ($this->input->post('date_to') ? $this->input->post('date_to') : $this->session->userdata('date_to'));
        if(!$date_from && !$date_to){
            redirect('admin_m/student_upcoming_session/'.$session.'_session/'.@$student_id);
        }
        
        $offset = 0;
        $per_page = 5;
        $uri_segment = 6;
 
        if ($session == 'one_to_one') {
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/student_upcoming_session/search/one_to_one/'.@$student_id), count($this->appointment_model->get_appointment_for_upcoming_session('student_id', $date_from, $date_to, $student_id)), $per_page, $uri_segment);
            $data = $this->appointment_model->get_appointment_for_upcoming_session('student_id', $date_from, $date_to, $student_id, $per_page, $offset);
            if ($data) {
                foreach ($data as $d) {
                    $gmt_student = $this->identity_model->new_get_gmt($student_id);
                    if(@!$gmt_student){
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($adm_id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                    }else{
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($student_id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                    }
                
                //$data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($spr_id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);    
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
                }
            }
        } elseif ($session == 'class') {
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/student_upcoming_session/search/class/'.@$student_id), count($this->class_member_model->get_appointment_for_upcoming_session($date_from, $date_to, $student_id)), $per_page, $uri_segment);
            $data_class = $this->class_member_model->get_appointment_for_upcoming_session($this->input->post('date_from'), $this->input->post('date_to'), $student_id, $per_page, $offset);
            if ($data_class) {
                foreach ($data_class as $data) {
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($data->date), $data->start_time, $data->end_time);
                    $data->date = date('Y-m-d', $data_schedule['date']);
                    $data->start_time = $data_schedule['start_time'];
                    $data->end_time = $data_schedule['end_time'];
                }
            }
        }
        
        $vars = array(
            'title' => 'Upcoming Session',
            'role' => 'Student',
            'data' => @$data,
            'data_class' => @$data_class,
            'student_id' => @$student_id,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'id_webex' => $this->webex_model->select('id')->where('status', 'SCHE')->get_all(),
            'pagination' => @$pagination
        );

        $this->template->content->view('default/contents/admin_m/student_upcoming_session/' . (($session == 'one_to_one') ? 'one_to_one_session' : 'class_session'), $vars);
        $this->template->publish();
    }
    
}
