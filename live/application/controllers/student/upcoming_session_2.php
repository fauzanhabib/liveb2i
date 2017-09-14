<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class upcoming_session extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD') {
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
            'data' => $upcoming,
            'data_class' => $upcoming_class,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
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

    public function search() {
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
    }

    public function coach_detail($coach_id = '') {
        redirect('student/session/coach_detail/'.$coach_id);
    }
}
