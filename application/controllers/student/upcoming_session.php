<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class upcoming_session extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');

        $this->load->library('common_function');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index($page = '') {
        $this->template->title = "Upcoming Session";
        $offset = 0;
        $per_page = 5;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student/upcoming_session/index/'), count($this->appointment_model->get_appointment_for_upcoming_session('student_id', '', '', $this->auth_manager->userid())), $per_page, $uri_segment);
        $data = $this->appointment_model->get_appointment_for_upcoming_session('student_id','','', $this->auth_manager->userid(), $per_page, $offset);
        $data_class = $this->class_member_model->get_appointment_for_upcoming_session('student_id', '', '', $this->auth_manager->userid(), $per_page, $offset);
        
        
        if ($data) {
            foreach ($data as $d) {
                $data_schedule = $this->convertBookSchedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
                
            }
        }
        
        if ($data_class) {
            foreach ($data_class as $d) {
                // echo date('H:i:s');
                // exit();
                $data_schedule = $this->convertBookSchedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);                                
                // print_r($data_schedule);
                // echo date('H:i:s');
                // exit();

                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
            }
        }

        // $minutes = $this->common_function->get_usertimezone($this->auth_manager->userid());
        $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;

        $vars = array(
            'title' => 'Upcoming Session',
            'role' => 'Coach',
            'data' => $data,
            'minutes' => $minutes,
            'data_class' => $data_class,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'pagination' => @$pagination
        );

        $this->template->content->view('default/contents/student/upcoming_session/index', $vars);
        $this->template->publish();
    }
    
    private function convertBookSchedule($minutes = '', $date = '', $start_time = '', $end_time = ''){
        // variable to get schedule out of date
        if($minutes > 0){
            if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)))) > strtotime($end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00'){
                $date = $date;
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))));
            }
            else if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)))) < strtotime($start_time)){
                $date = strtotime('+ 1days'.date('Y-m-d',$date));
                //$tomorrow = date('m-d-Y',strtotime($date . "+1 days"));
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)));
                
//                $date2 = strtotime('+ 1days'.date('Y-m-d',$date));
//                //$tomorrow = date('m-d-Y',strtotime($date . "+1 days"));
//                $start_time2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
//                $end_time2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)));
            }
        }
        else if($minutes < 0){
            if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)))) < strtotime($start_time)){
                $date = $date;
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)));
            }
            else if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)))) > strtotime($end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00'){
                $date = strtotime('- 1days'.date('Y-m-d',$date));
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))));
                
//                $date2 = strtotime('- 1days'.date('Y-m-d',$date));
//                $start_time2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
//                $end_time2 = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))));
            }
        }
        
        return array(
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
//            'date2' => @$date2,
//            'start_time2' => @$start_time2,
//            'end_time2' => @$end_time2,
        );
    }

    public function search($page = '') {
        $this->template->title = "Upcoming Session";
        
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
                    redirect('student/upcoming_session');
            }
        }
        
        $date_from = ($this->input->post('date_from') ? $this->input->post('date_from') : $this->session->userdata('date_from'));
        $date_to = ($this->input->post('date_to') ? $this->input->post('date_to') : $this->session->userdata('date_to'));
        
        $offset = 0;
        $per_page = 5;
        $uri_segment = 4;

        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student/upcoming_session/search/'), count($this->appointment_model->get_appointment_for_upcoming_session('student_id', $date_from, $date_to, $this->auth_manager->userid())), $per_page, $uri_segment);
        $data = $this->appointment_model->get_appointment_for_upcoming_session('student_id', $this->input->post('date_from'), $this->input->post('date_to'), $this->auth_manager->userid(), $per_page, $offset);
        $data_class = $this->class_member_model->get_appointment_for_upcoming_session($this->input->post('date_from'), $this->input->post('date_to'), $this->auth_manager->userid());

        if ($data) {
            foreach ($data as $d) {
                $data_schedule = $this->convertBookSchedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
            }
        }

        if ($data_class) {
            foreach ($data_class as $d) {
                $data_schedule = $this->convertBookSchedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
            }
        }

        // $minutes = $this->common_function->get_usertimezone($this->auth_manager->userid());
        $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;

        $vars = array(
            'title' => 'Upcoming Session',
            'role' => 'Coach',
            'start_date' => $this->input->post('date_from'),
            'end_date' => $this->input->post('date_to'),
            'data' => $data,
            'data_class' => $data_class,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'minutes' => $minutes,
            'pagination' => @$pagination
        );

        $this->template->content->view('default/contents/student/upcoming_session/index', $vars);
        $this->template->publish();
    }

    public function coach_detail($coach_id = '') {
        redirect('student/session/coach_detail/'.$coach_id);
    }
}
