<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class managing extends MY_Site_Controller {
    var $day_index = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    // Constructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('appointment_model');
        $this->load->model('appointment_reschedule_model');
        $this->load->model('schedule_model');
        $this->load->model('offwork_model');
        $this->load->model('user_model');
        $this->load->model('identity_model');
        $this->load->model('coach_token_cost_model');
        $this->load->model('token_histories_model');
        $this->load->model('coach_day_off_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('partner_model');
        $this->load->model('partner_setting_model');
        $this->load->model('webex_host_model');
        $this->load->model('class_meeting_day_reschedule_model');
        $this->load->model('specific_settings_model');
        // for messaging action and timing
        $this->load->library('phpass');
        $this->load->library('queue');
        $this->load->library('webex_function');
        $this->load->library('email_structure');
        $this->load->library('send_email');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'PRT') {
            $this->messages->add('Access Denied', 'danger');
            redirect('account/identity/profile');
        }
    }

    // Index
    public function index() {

    }

    public function set_class_schedule($class_id = '', $date = '', $action='', $class_meeting_id='') {
        //echo('test'); exit;
        if ($date <= date('Y-m-d')) {
            $this->messages->add('Invalid Date', 'warning');
            redirect('partner/member_list/coach/' . $class_id);
        }

        if ($this->isValidDate($class_id, $date) == false || !$action || ($action == 're' && !$class_meeting_id) || ($action !='re' && $action != 'set')) {
            $this->messages->add('Invalid Action', 'warning');
            redirect('partner/member_list/coach/' . $class_id);
        }

        if(!$this->class_member_model->where('class_id', $class_id)->get_all()){
            $this->messages->add('There is no student in this class, add student first!', 'warning');
            redirect('partner/member_list/coach/' . $class_id);
        }
        // print_r($this->session->userdata('coach_id')); exit;
        if($action == 're'){
            $data = $this->get_available_coach($date, $this->session->userdata('coach_id'));
        }
        else{
            $data = $this->get_available_coach($date);
        }
        // echo('<pre>');print_r($data);exit;
        $this->template->title = 'Add Class Schedule';
        //$data = $this->identity_model->get_coach_identity(null, null, null, $this->auth_manager->partner_id(), '');

        $vars = array(
            'title' => $this->isValidClass($class_id)['class_name'],
            'class_id' => $class_id,
            'date' => strtotime($date),
            'data' => $data,
            'action' => $action,
            'class_meeting_id' => $class_meeting_id
        );
        $this->template->content->view('default/contents/managing/partner/class_schedule/assign_coach_form', $vars);

        //publish template
        $this->template->publish();
    }

    // function to check if the date is between start date and end date of class
    function isValidDate($class_id = '', $date = '') {
        if ($this->isValidClass($class_id)) {
            $data = $this->class_model->select('start_date, end_date')->where('id', $class_id)->get();
            $start_date_class = strtotime($data->start_date);
            $end_date_class = strtotime($data->end_date);
            $date = strtotime($date);

            if ($date >= $start_date_class && $date <= $end_date_class) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    // function to check class is belong to user as role partner
    function isValidClass($class_id = '') {
        $data = $this->class_model->select('id, class_name, student_amount')->where('id', $class_id)->get();
        if ($data) {
            // returning class name and student amount if class valid
            return array(
                'class_name' => @$data->class_name,
                'student_amount' => @$data->student_amount,
            );
        } else {
            return false;
        }
    }

    public function get_available_coach($date = '', $coach_id_ = '') {
        // fungsi untuk mengambil available coach berdasarkan parameter tanggal
        // akan di pakai di single date dan multiple date
        $date_ = $date;
        $coach_data = $this->identity_model->get_coach_identity('', '', '', $this->auth_manager->partner_id());
        $available_coach = array();
        foreach ($coach_data as $cd) {
        //if($coach_id_){
            $coach_id = $cd->id;
            $availability_temp = array();
            if ($this->is_date_available(trim($date_), 2) && !$this->is_day_off($coach_id, $date_) == true) {
                //getting the day of $date
                $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;
                $date = strtotime($date_);
                $day = strtolower(date('l', $date));
                $day2 = $this->day_index[$this->convert_gmt(array_search($day, $this->day_index), $minutes)];

                // appointment data specify by coach, date and status
                // appointment with status cancel considered available for other student
                $appointment = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->get_all();
                // appointment with status temporary considered available for other student but not for student who is in the appointment and
                // appointment where the student has make an appoinment on the specific date, so there will be no the same start time and end time to be shown to the student from other coach
                //$appointment_student = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $student_id)->where('status not like', 'cancel')->where('date', date("Y-m-d", $date))->get_all();
                // appointment coach in class
                $appointment_class = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', date("Y-m-d", $date))->get_all();

                // storing appointment to an array so can easily on searching / no object value inside
                $appointment_start_time_temp = array();
                $appointment_end_time_temp = array();
                foreach ($appointment as $a) {
                    if($minutes > 0){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                        }
                    }
                    else if($minutes < 0){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                        }
                    }
                    else if($minutes == 0){
                        $appointment_start_time_temp[] = $a->start_time;
                        $appointment_end_time_temp[] = $a->end_time;
                    }
                }

                // storing class meeting days to appointment temp
                foreach ($appointment_class as $a) {
                    if($minutes > 0){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                        }
                    }
                    else if($minutes < 0){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                        }
                    }
                    else if($minutes == 0){
                        $appointment_start_time_temp[] = $a->start_time;
                        $appointment_end_time_temp[] = $a->end_time;
                    }
                }

//                foreach ($appointment_student as $a) {
//                    if($minutes > 0){
//                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
//                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
//                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
//                        }
//                    }
//                    else if($minutes < 0){
//                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
//                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
//                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
//                        }
//                    }
//                    else if($minutes == 0){
//                        $appointment_start_time_temp[] = $a->start_time;
//                        $appointment_end_time_temp[] = $a->end_time;
//                    }
//                }

                if($minutes > 0){
                    $date2 = date("Y-m-d", strtotime('-1 day'.date("Y-m-d",$date)));
                    $appointment2 = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $date2)->get_all();
                    //$appointment_student2 = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $student_id)->where('status not like', 'cancel')->where('date', $date2)->get_all();
                    $appointment_class2 = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', $date2)->get_all();

                    foreach($appointment2 as $a){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                        }
                    }
//                    foreach($appointment_student2 as $a){
//                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
//                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
//                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
//                        }
//                    }
                    foreach($appointment_class2 as $a){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                        }
                    }

                }
                else if($minutes < 0){
                    $date2 = date("Y-m-d", strtotime('+1 day'.date("Y-m-d",$date)));
                    $appointment2 = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $date2)->get_all();
                    //$appointment_student2 = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $student_id)->where('status not like', 'cancel')->where('date', $date2)->get_all();
                    $appointment_class2 = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', $date2)->get_all();

                    foreach($appointment2 as $a){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                        }
                    }
//                    foreach($appointment_student2 as $a){
//                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
//                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
//                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
//                        }
//                    }
                    foreach($appointment_class2 as $a){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                        }
                    }
                }


                //getting all data
                $schedule_data1 = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
                $schedule_data2 = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day2)->get();

                $availability = $this->schedule_block($coach_id, $day, $schedule_data1->start_time, $schedule_data1->end_time, $schedule_data2->day, $schedule_data2->start_time, $schedule_data2->end_time);



                $availability_temp = array();
                $availability_exist;
                foreach ($availability as $a) {
                    $duration = (strtotime($a['end_time']) - strtotime($a['start_time'])) / ($this->session_duration($this->auth_manager->partner_id()) * 60);
                    if ($duration >= 1) {
                        for ($i = 0; $i < $duration; $i++) {
                            $availability_exist = array(
                                // adding  minutes for every session
                                'start_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id()) * 60) * ($i))),
                                'end_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id()) * 60) * ($i + 1))),
                            );
                            // checking if the time is not out of coach schedule
                            if(strtotime($availability_exist['end_time']) <= strtotime($a['end_time'])){
                                // checking if availability is existed in the appointment
                                if (in_array($availability_exist['start_time'], $appointment_start_time_temp) && in_array($availability_exist['end_time'], $appointment_end_time_temp)) {
                                    // no action
                                } else {
                                    // storing availability that still active and not been boooked yet
                                    if($this->isValidAppointment($availability_exist['start_time'], $availability_exist['end_time'], $appointment_start_time_temp, $appointment_end_time_temp)){
                                                date_default_timezone_set('Etc/GMT'.(-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                                if ($date_ == date('Y-m-d', strtotime(date('Y-m-d') . ' + 2 days'))) {
                                                    if (DateTime::createFromFormat('H:i:s', $availability_exist['start_time']) >= DateTime::createFromFormat('H:i:s', date('H:i:s'))) {
                                                        $availability_temp[] = $availability_exist;
                                                    }
                                                } else {
                                                    $availability_temp[] = $availability_exist;
                                                }
                                    }
                                }
                            }
                        }
                    }
                }

            }

            if ($availability_temp) {
                if($coach_id_ && $coach_id == $coach_id_){
                   // none
                }
                else{
                    $available_coach[] = array(
                        'profile_picture' => $cd->profile_picture,
                        'coach_id' => $coach_id,
                        'fullname' => $cd->fullname,
                        'country' => $cd->country,
                        'token_for_student' => $cd->token_for_student,
                        'availability' => $availability_temp,
                    );
                }

            }
        //}
        }

//        if($per_page && $offset && $offset=="first_page"){
//            $offset = 0;
//            return (array_slice($available_coach, $offset, $per_page));
//        }elseif($per_page && $offset){
//            return (array_slice($available_coach, $offset, $per_page));
//        }
        return $available_coach;
    }

    private function is_date_available($date, $day) {
        if ((DateTime::createFromFormat('Y-m-d', trim($date)) != FALSE) && (strtotime($date) >= strtotime(date('Y-m-d', strtotime("+" . $day . "days"))))) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function is_day_off($coach_id = '', $date_ = '') {
        $day_off = $this->coach_day_off_model->select('start_date, end_date')->where('coach_id', $coach_id)->where('status', 'active')->get();
        $start_date = strtotime(@$day_off->start_date);
        $end_date = strtotime(@$day_off->end_date);
        $date = strtotime($date_);

        if ($date >= $start_date && $date <= $end_date || $date < mktime(0, 0, 0, date("m"), date("d"), date("Y"))) {
            return true;
        } else if (!$day_off) {
            return false;
        } else {
            return false;
        }
    }

    private function convert_gmt($index = '', $minutes = '') {
        if ($minutes > 0) {
            return (($index - 1) >= 0 ? ($index - 1) : 6);
        } else {
            return (($index + 1) <= 6 ? ($index + 1) : 0);
        }
    }

    private function schedule_block($coach_id = '', $day1 = '', $start_time1 = '', $end_time1 = '', $day2 = '', $start_time2 = '', $end_time2 = '') {
        $schedule1 = $this->block($coach_id, $day1, $start_time1, $end_time1);
        $schedule2 = $this->block($coach_id, $day2, $start_time2, $end_time2);

        $schedule = array();
        $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;

        $time = strtotime('00:00:00');
        $startTime = date("H:i:s", strtotime((-$minutes) . 'minutes', $time));
        $endTime = date("H:i:s", strtotime('+30 minutes', $time));

        if ($minutes == 0) {
            $schedule = $schedule1;
        } else if ($minutes > 0) {
            foreach ($schedule2 as $s2) {
                $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['start_time'])));
                $schedule_temp2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['end_time'])));
                if (strtotime($schedule_temp) < strtotime($s2['start_time'])) {
//                        break;
                    $s2['start_time'] = $schedule_temp;
                    $s2['end_time'] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['end_time'])));
                    $schedule[] = array(
                        'start_time' => $this->convertTime($s2['start_time']),
                        'end_time' => $this->convertTime($s2['end_time']),
                    );
                } else if (strtotime($schedule_temp) > strtotime($s2['start_time']) && strtotime($schedule_temp2) < strtotime($s2['end_time'])) {
                    $schedule[] = array(
                        'start_time' => '00:00:00',
                        'end_time' => $this->convertTime($schedule_temp2),
                    );
                }
            }


            foreach ($schedule1 as $s1) {
                $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s1['start_time'])));
                if (strtotime($schedule_temp) < strtotime($s1['start_time']) || $schedule_temp == '00:00:00') {
                    break;
                } else {
                    $s1['start_time'] = $schedule_temp;
                    $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s1['end_time'])));
                    if (strtotime($schedule_temp) < strtotime($s1['end_time']) || $schedule_temp == '00:00:00') {
                        $s1['end_time'] = '23:59:59';
                    } else {
                        $s1['end_time'] = $schedule_temp;
                    }

                    $schedule[] = array(
                        'start_time' => $this->convertTime($s1['start_time']),
                        'end_time' => $this->convertTime($s1['end_time']),
                    );
                }
            }
        } else {
            foreach ($schedule1 as $s1) {
                $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s1['end_time'])));
                $schedule_temp2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s1['start_time'])));
                if (strtotime($schedule_temp) > strtotime($s1['end_time']) || $schedule_temp == '00:00:00') {
                    //break;
                } else if (strtotime($schedule_temp2) > strtotime($s1['start_time']) && strtotime($schedule_temp) < strtotime($s1['end_time'])) {
                    //$s1['start_time'] = '00:00:00';
                    $schedule[] = array(
                        'start_time' => '00:00:00',
                        'end_time' => $this->convertTime($schedule_temp),
                    );
                } else if (strtotime($schedule_temp2) <= strtotime($s1['start_time'])) {
                    $schedule[] = array(
                        'start_time' => $this->convertTime($schedule_temp2),
                        'end_time' => $this->convertTime($schedule_temp),
                    );
                }
            }

            foreach ($schedule2 as $s2) {
                $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['start_time'])));
                $schedule_temp2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['end_time'])));
                if (strtotime($schedule_temp) < strtotime($s2['start_time'])) {
                    break;
                } else if (strtotime($schedule_temp) > strtotime($s2['start_time']) && strtotime($schedule_temp2) > strtotime($s2['end_time'])) {
                    $schedule[] = array(
                        'start_time' => $this->convertTime($schedule_temp),
                        'end_time' => $this->convertTime($schedule_temp2),
                    );
                } else if (strtotime($schedule_temp) > strtotime($s2['start_time']) && strtotime($schedule_temp2) < strtotime($s2['end_time'])) {
                    $schedule[] = array(
                        'start_time' => $this->convertTime($schedule_temp),
                        'end_time' => '23:59:59',
                    );
                }
            }
        }
        return $this->joinTime($schedule);
    }

    private function block($coach_id = '', $day = '', $start_time = '', $end_time = '') {
        $offwork = $this->offwork_model->get_offwork($coach_id, $day);
        $schedule_temp = array();
        if ($offwork) {
            //foreach($offwork as $o){
            for ($i = 0; $i <= count($offwork); $i++) {
                if ($i == 0) {
                    $schedule_temp[] = array(
                        'start_time' => $start_time,
                        'end_time' => $offwork[0]->start_time,
                    );
                } else if ($i > 0 && $i < (count($offwork))) {
                    $schedule_temp[] = array(
                        'start_time' => $offwork[$i - 1]->end_time,
                        'end_time' => $offwork[$i]->start_time,
                    );
                } else if ($i == (count($offwork))) {
                    $schedule_temp[] = array(
                        'start_time' => $offwork[$i - 1]->end_time,
                        'end_time' => $end_time,
                    );
                }
            }
        } else {
            $schedule_temp[] = array(
                'start_time' => $start_time,
                'end_time' => $end_time,
            );
        }

        return $schedule_temp;
    }

    private function convertTime($time = ''){
        if(date("H:i", strtotime(1 . 'minutes', strtotime($time))) != '00:00' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '01'){
            // return date("H:i", strtotime(1 . 'minutes', strtotime($time)));
            return date("H:i", strtotime(0 . 'minutes', strtotime($time)));
        }
        else{
            return $time;
        }
    }

    private function joinTime($schedule = ''){
        $schedule_temp = array();
        if(count($schedule) > 1){
            for($i=0;$i<(count($schedule));$i++){
                if($schedule[$i]['start_time'] != $schedule[$i]['end_time']){
                    if($i<(count($schedule)-1) && strtotime($schedule[$i]['end_time']) == strtotime($schedule[$i+1]['start_time'])){
                        $schedule_temp[] = array(
                            'start_time' => $schedule[$i]['start_time'],
                            'end_time' => $schedule[$i+1]['end_time'],
                        );
                        $i++;
                    }
                    else{
                        $schedule_temp[] = array(
                            'start_time' => $schedule[$i]['start_time'],
                            'end_time' => $schedule[$i]['end_time'],
                        );
                    }
                }
            }
        }
        else if(count($schedule) == 1){
            if($schedule[0]['start_time'] != $schedule[0]['end_time']){
                $schedule_temp[] = array(
                    'start_time' => $schedule[0]['start_time'],
                    'end_time' => $schedule[0]['end_time'],
                );
            }
        }

        return $schedule_temp;
    }

    private function session_duration($partner_id = ''){
       $setting = $this->specific_settings_model->get_partner_settings($partner_id);
        // echo $setting[0]->session_duration+5;
        // exit();
        return $setting[0]->session_duration+5;
    }

    private function isValidAppointment($start_time = '', $end_time = '', $start_time_temp = '', $end_time_temp = ''){
        $status = true;
        for($i=0;$i<count($start_time_temp);$i++){
            if(DateTime::createFromFormat('H:i:s', $start_time_temp[$i]) >= DateTime::createFromFormat('H:i:s', $start_time) && DateTime::createFromFormat('H:i:s', $start_time_temp[$i]) < DateTime::createFromFormat('H:i:s', $end_time)){
                $status = false;
                break;
            }
            else if(DateTime::createFromFormat('H:i:s', $end_time_temp[$i]) > DateTime::createFromFormat('H:i:s', $start_time) && DateTime::createFromFormat('H:i:s', $start_time_temp[$i]) < DateTime::createFromFormat('H:i:s', $start_time)){
                $status = false;
                break;
            }
        }
        return $status;
    }

    public function do_reschedule_session($class_id='', $class_meeting_day_id = '', $coach_id = '', $date_ = '', $start_time = '', $end_time = '') {
        $date = strtotime($date_);
        $class_meeting_day = $this->class_meeting_day_model->select('id, coach_id, date, start_time, end_time, class_id')->where('id', $class_meeting_day_id)->get();
        if (!$class_meeting_day) {
            $this->messages->add('Invalid Session 1', 'error');
            redirect('partner/member_list/coach/');
        }

        // Retrieve post
        $booked = array(
            'class_meeting_day_id' => $class_meeting_day_id,
            'date' => date('Y-m-d', $date),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => 'active',
        );

        $this->db->trans_begin();
        // Inserting and checking
        $class_meeting_day_reschedule = $this->class_meeting_day_reschedule_model->insert($booked);
        if (!$class_meeting_day_reschedule) {
            $this->db->trans_rollback();
            $this->messages->add('Invalid Action 1', 'error');
            $this->index();
            return;
        }

        // updating appointment current status to reschedule
        $class_meeting_day_update = array(
            'status' => 'reschedule',
        );

        $webex_host = $this->webex_host_model->get_host("c".$class_meeting_day_id);
        $webex_class = $this->webex_class_model->select('id')->where('class_meeting_id', $class_meeting_day_id)->get();

        if($webex_host && $webex_class){
            if (!$this->webex_function->delete_session($webex_host[0]->id, "c".$class_meeting_day_id) || !$this->webex_class_model->delete($webex_class->id)) {
                $this->db->trans_rollback();
                $this->messages->add('Error when deleting session in webex', 'error');
                redirect('student/upcoming_session');
            }
        }

        if (!$this->class_meeting_day_model->update($class_meeting_day_id, $class_meeting_day_update)) {
            $this->db->trans_rollback();
            $this->messages->add('Invalid Action 3', 'warning');
            redirect('student/upcoming_session');
        }

        $new_class_meeting_day = array(
            'coach_id' => $coach_id,
            'date' => date("Y-m-d", $date),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'class_id' => $class_id,
            'status' => 'active',
        );

        // inserting new session to class_meeting_day table
        $new_class_meeting_day_id = $this->class_meeting_day_model->insert($new_class_meeting_day);
        if (!$new_class_meeting_day_id) {
            $this->db->trans_rollback();
            $this->messages->add('Invalid Action 4', 'warning');
            $this->index();
            return;
        }

        $this->db->trans_commit();


        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // after student rescheduled an appointment, send email to coach
        // tube name for messaging action
        $tube = 'com.live.email';
        $id_to_email_address = $this->user_model->dropdown('id', 'email');
        $id_to_name = $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname');

        $class_member = $this->class_member_model->get_appointment_for_webex_invitation_xml($class_meeting_day_id);

        if ($class_member) {
            foreach ($class_member as $cm) {
                $data_student = array(
                    'subject' => 'Appointment Rescheduled',
                    'email' => $cm->student_email,
                    //'content' => 'You have rescheduled appointment at ' . date('l jS \of F Y', strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with ' . $id_to_name[$new_class_meeting_day['coach_id']] . ' to ' . $new_class_meeting_day['date'] . ' from ' . $new_class_meeting_day['start_time'] . ' until ' . $new_class_meeting_day['end_time'] . '.',
                );
                $data_student['content'] = $this->email_structure->header()
                                        .$this->email_structure->title('Appointment Rescheduled')
                                        .$this->email_structure->content('You have rescheduled appointment at ' . date('l jS \of F Y', strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with ' . $id_to_name[$new_class_meeting_day['coach_id']] . ' to ' . $new_class_meeting_day['date'] . ' from ' . $new_class_meeting_day['start_time'] . ' until ' . $new_class_meeting_day['end_time'] . '.')
                                        //.$this->email_structure->button('JOIN SESSION')
                                        .$this->email_structure->footer('');
                $this->queue->push($tube, $data_student, 'email.send_email');
            }
        }

        $data_coach = array(
            'subject' => 'Appointment Rescheduled',
            'email' => $id_to_email_address[$coach_id],
            //'content' => 'Your appointment at ' . date("l jS \of F Y", strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with class ' . $class_member[0]->class_name . ' has been rescheduled to ' . date("l jS \of F Y", $date) . ' from ' . $start_time . ' until ' . $end_time . '.',
        );
        $data_coach['content'] = $this->email_structure->header()
                .$this->email_structure->title('Appointment Rescheduled')
                .$this->email_structure->content('Your appointment at ' . date("l jS \of F Y", strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with class ' . $class_member[0]->class_name . ' has been rescheduled to ' . date("l jS \of F Y", $date) . ' from ' . $start_time . ' until ' . $end_time . '.')
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');

        $this->queue->push($tube, $data_coach, 'email.send_email');

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // messaging
        // inserting notification
        if ($new_class_meeting_day_id) {
            $database_tube = 'com.live.database';

            // student notification data
            //echo('<pre>');print_r($new_class_meeting_day);exit;
            if($class_member){
                foreach ($class_member as $cm) {
                    $student_notification['user_id'] = $cm->student_id;
                    $student_notification['description'] = 'Rescheduled appointment at ' . date('l jS \of F Y', strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with coach ' . $id_to_name[$new_class_meeting_day['coach_id']] . ' to ' . $new_class_meeting_day['date'] . ' from ' . $new_class_meeting_day['start_time'] . ' until ' . $new_class_meeting_day['end_time'] . '.';
                    $student_notification['status'] = 2;
                    $student_notification['dcrea'] = time();
                    $student_notification['dupd'] = time();

                    // student's data for reminder messaging
                    // IMPORTANT : array index in content must be in mutual with table field in database
                    $data_student = array(
                        'table' => 'user_notifications',
                        'content' => $student_notification,
                    );
                    $this->queue->push($database_tube, $data_student, 'database.insert');
                }
            }


            // coach notification data
            $coach_notification['user_id'] = $new_class_meeting_day['coach_id'];
            $coach_notification['description'] = 'Your appointment at ' . date('l jS \of F Y', strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with class ' . $class_member[0]->class_name . ' has been rescheduled to ' . $new_appointment['date'] . ' from ' . $new_appointment['start_time'] . ' until ' . $new_appointment['end_time'] . '.';
            $coach_notification['status'] = 2;
            $coach_notification['dcrea'] = time();
            $coach_notification['dupd'] = time();


            // coach's data for reminder messaging
            // IMPORTANT : array index in content must be in mutual with table field in database
            $data_coach = array(
                'table' => 'user_notifications',
                'content' => $coach_notification,
            );
            $this->queue->push($database_tube, $data_coach, 'database.insert');

            $available_host = $this->webex_host_model->get_available_host('c'.$new_class_meeting_day_id);
            if($available_host && $this->webex_function->create_session($available_host[0]->id, 'c'.$new_class_meeting_day_id)){
                $message = "Session Rescheduled, you will use Webex for your session";
            }else{
                $message = "Session Rescheduled, you will use Skype for your session";
            }
        }

        $this->messages->add($message, 'success');
        redirect('partner/coach_upcoming_session/class_session');
    }

    public function cancel_session($class_id='', $class_meeting_day_id = '') {
        // checking if appointment has already cancelled
        $class_meeting_day = $this->class_meeting_day_model->select('id, coach_id, date, start_time, end_time, status')->where('id', $class_meeting_day_id)->get();
        if ($class_meeting_day->status == 'cancel') {
            $this->messages->add('Session has already cancelled', 'error');
            redirect('partner/member_list/coach/');
        }

        //////////////////////////////////////////////////////////////////////////////////////
        // WEBEX
        //////////////////////////////////////////////////////////////////////////////////////
        $webex_host = $this->webex_host_model->get_host("c".$class_meeting_day_id);
        $webex_class = $this->webex_class_model->select('id')->where('class_meeting_id', $class_meeting_day_id)->get();

        $this->db->trans_begin();
        if($webex_host && $webex_class){
            // delete session in webex
            // delete session in webex_class table
            if (!$this->webex_function->delete_session($webex_host[0]->id, "c".$class_meeting_day_id) || !$this->webex_class_model->delete($webex_class->id)) {
                $this->db->trans_rollback();
                $this->messages->add('Error when deleting session in webex', 'error');
                redirect('student/upcoming_session');
            }
        }

        // updating appointment (change status to cancel)
        // storing data
        $class_meeting_day_update = array(
            'status' => 'cancel',
        );
        // Updating and checking to appoinment table
        if (!$this->class_meeting_day_model->update($class_meeting_day_id, $class_meeting_day_update)) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'error');
            $this->index();
            return;
        }
        $this->db->trans_commit();

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // after session cancelled, send email to coach and students
        // tube name for messaging action
        $tube = 'com.live.email';
        $id_to_email_address = $this->user_model->dropdown('id', 'email');
        $id_to_name = $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname');

        $class_member = $this->class_member_model->get_appointment_for_webex_invitation_xml($class_meeting_day_id);

        if ($class_member) {
            foreach ($class_member as $cm) {
                $data_student = array(
                    'subject' => 'Session Cancelled',
                    'email' => $cm->student_email,
                    //'content' => 'Your session at ' . date('l jS \of F Y', strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with class' . $cm->class_name . ' has been cancelled.',
                );
                $data_student['content'] = $this->email_structure->header()
                                .$this->email_structure->title('Session Cancelled')
                                .$this->email_structure->content('Your session at ' . date('l jS \of F Y', strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with class' . $cm->class_name . ' has been cancelled.')
                                //.$this->email_structure->button('JOIN SESSION')
                                .$this->email_structure->footer('');
                $this->queue->push($tube, $data_student, 'email.send_email');
            }
        }

        $data_coach = array(
            'subject' => 'Session Cancelled',
            'email' => $id_to_email_address[$class_meeting_day->coach_id],
            //'content' => 'Your session at ' . date("l jS \of F Y", strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with class ' . $class_member[0]->class_name . ' has been cancelled.',
        );
        $data_coach['content'] = $this->email_structure->header()
                        .$this->email_structure->title('Session Cancelled')
                        .$this->email_structure->content('Your session at ' . date("l jS \of F Y", strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with class ' . $class_member[0]->class_name . ' has been cancelled.')
                        //.$this->email_structure->button('JOIN SESSION')
                        .$this->email_structure->footer('');

        $this->queue->push($tube, $data_coach, 'email.send_email');

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // messaging
        // inserting notification

        $database_tube = 'com.live.database';
        if($class_member){
            foreach ($class_member as $cm) {
                $student_notification['user_id'] = $cm->student_id;
                $student_notification['description'] = 'Your session ' . date('l jS \of F Y', strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with class ' . $cm->class_name . ' has been cancelled.';
                $student_notification['status'] = 2;
                $student_notification['dcrea'] = time();
                $student_notification['dupd'] = time();

                // student's data for reminder messaging
                // IMPORTANT : array index in content must be in mutual with table field in database
                $data_student_notification = array(
                    'table' => 'user_notifications',
                    'content' => $student_notification,
                );
                $this->queue->push($database_tube, $data_student_notification, 'database.insert');
            }
        }


        // coach notification data
        $coach_notification['user_id'] = $class_meeting_day->coach_id;
        $coach_notification['description'] = 'Your session at ' . date('l jS \of F Y', strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with class ' . $class_member[0]->class_name . ' has been canceld.';
        $coach_notification['status'] = 2;
        $coach_notification['dcrea'] = time();
        $coach_notification['dupd'] = time();


        // coach's data for reminder messaging
        // IMPORTANT : array index in content must be in mutual with table field in database
        $data_coach_notification = array(
            'table' => 'user_notifications',
            'content' => $coach_notification,
        );
        $this->queue->push($database_tube, $data_coach_notification, 'database.insert');

        $this->messages->add('Session cancelled', 'success');
        redirect('partner/member_list/coach/');
    }

    public function reschedule($appointment_id = '', $id_coach = '') {

        $this->template->title = 'Reschedule Appointment';

        // checking if appointment has already rescheduled
        $appointment_reschedule_data = $this->appointment_reschedule_model->select('id')->where('appointment_id', $appointment_id)->get();

        if ($appointment_reschedule_data) {
            $this->messages->add('apppointment has already rescheduled', 'warning');
            redirect('partner/coach_upcoming_session/one_to_one_session/'.$id_coach);
        }

        $appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, start_time, end_time, status')->where('id', $appointment_id)->get();
        $student_id_ = $appointment_data->student_id;
        $coach_id_ = $appointment_data->coach_id;


        $cert_studying = $this->db->select('cert_studying')->from('user_profiles')->where('user_id',$student_id_)->get()->result();
        $cert_studying = $cert_studying[0]->cert_studying;



        $convert = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($student_id_)[0]->minutes, strtotime($appointment_data->date), $appointment_data->start_time, $appointment_data->end_time);
        $date = date('Y-m-d', $convert['date']);
        $start_time = $convert['start_time'];
        $end_time = $convert['end_time'];
        //echo('<pre>');print_r(date('Y-m-d', $convert['date']));exit;
        if (!$appointment_data) {
            $this->messages->add('No Appointment Found', 'warning');
            redirect('partner/member_list/coach');
        }
        //$data = $this->get_available_coach($appointment_data->date, $appointment_data->coach_id);
        //echo('<pre>');print_r($data);exit;
        // $coach_list = $this->identity_model->get_coach_identity(null, null, null, $this->auth_manager->partner_id());

        $coach_type_id = $this->db->select('coach_type_id')->from('user_profiles')->where('user_id',$coach_id_)->get()->result();
        $coach_type_id = $coach_type_id[0]->coach_type_id;

        $type_coach = $this->db->query('SELECT standard_coach_cost, elite_coach_cost FROM (specific_settings) WHERE partner_id = '.$this->auth_manager->partner_id())->result();

        $coach_list = $this->identity_model->get_new_coach_identity_rescedule($this->auth_manager->partner_id(), '', $cert_studying, $coach_type_id);

        // echo "<pre>";
        // print_r($coach_list);
        // exit();

        $temp = array();
        foreach($coach_list as $c){
            $temp2 = $this->reschedule_session($appointment_id, $c->id, $date);
            // echo "<pre>";
            // print_r($temp2);
            // if($temp2['availability'] && $c->id != $appointment_data->coach_id){ /* untuk di live */
            if($temp2['availability'] && $c->id != $appointment_data->coach_id){ /* untuk di idbuild */
                $temp[] = $temp2;

            }
        }

        $vars = array(
            'appointment_id' => $appointment_id,
            'student_id' => $appointment_data->student_id,
            'date' => strtotime($date),
            'data' => $temp,
            'coach_id' => $coach_id_,
            'coach_type_id' => $coach_type_id,
            'standard_coach_cost' => $type_coach[0]->standard_coach_cost,
            'elite_coach_cost' => $type_coach[0]->elite_coach_cost,
            'cert_studying' => $cert_studying,
            'student_id' => $student_id_
        );
        $this->template->content->view('default/contents/managing/partner/one_to_one/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function reschedule_booking($student_id = '', $appointment_id = '', $coach_id = '', $date = '', $start_time = '', $end_time = '', $coach_cost_old = '', $coach_cost_new = '') {
        $get_name_student = $this->db->select('fullname')->from('user_profiles')->where('user_id',$student_id)->get()->result();
        $get_email_student = $this->db->select('email')->from('users')->where('id',$student_id)->get()->result();
        $get_id_coach = $this->db->select('coach_id')->from('appointments')->where('id',$appointment_id)->get()->result();
        $id_coach = $get_id_coach[0]->coach_id;

        $get_name_coach = $this->db->select('fullname')->from('user_profiles')->where('user_id',$id_coach)->get()->result();
        $get_email_coach = $this->db->select('email')->from('users')->where('id',$id_coach)->get()->result();
        $get_new_name_coach = $this->db->select('fullname')->from('user_profiles')->where('user_id',$coach_id)->get()->result();
        $get_new_email_coach = $this->db->select('email')->from('users')->where('id',$coach_id)->get()->result();

        $name_student = $get_name_student[0]->fullname;
        $email_student = $get_email_student[0]->email;
        $name_coach = $get_name_coach[0]->fullname;
        $email_coach = $get_email_coach[0]->email;
        $new_name_coach = $get_new_name_coach[0]->fullname;
        $new_email_coach = $get_new_email_coach[0]->email;
        // ================
        $convert = $this->convertBookSchedule(-($this->identity_model->new_get_gmt($student_id)[0]->minutes), strtotime($date), $start_time, $end_time);
        $date = $convert['date'];
        $start_time = $convert['start_time'];
        $end_time = $convert['end_time'];


        $get_token_student = $this->db->select('token_amount')->from('user_tokens')->where('user_id',$student_id)->get()->result();
        $student_token = $get_token_student[0]->token_amount;
        $selisih_cost = $coach_cost_new - $coach_cost_old;
        $update_student_token = '';
        // jika coach_cost_new lebih tinggi dari coach_coach_old
        if($selisih_cost >= 0){
            if($student_token >= $selisih_cost){
                $update_student_token = $student_token - $selisih_cost;
            } else if($student_token < $selisih_cost){
                $this->messages->add('Your token not enough', 'warning');
                redirect('partner/managing/reschedule/'.$appointment_id);
            }
        } else if($selisih_cost < 0){
            if($student_token >= $selisih_cost){
                $update_student_token = $student_token - $selisih_cost;
            } else if($student_token < $selisih_cost){
                $this->messages->add('Your token not enough', 'warning');
                redirect('partner/managing/reschedule/'.$appointment_id);
            }
        }


        $appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, start_time, end_time')->where('id', $appointment_id)->where('student_id', $student_id)->get();
        if (!$appointment_data) {
            $this->messages->add('Invalid Appointment', 'warning');
            redirect('partner/managing/reschedule/'.$appointment_id);
        }

        // Retrieve post
        $booked = array(
            'appointment_id' => $appointment_id,
            'old_coach_id' => $appointment_data->coach_id,
            'date' => $appointment_data->date,
            'start_time' => $appointment_data->start_time,
            'end_time' => $appointment_data->end_time,
            'status' => 'active',
        );

        $isValid = $this->isAvailable($student_id, $coach_id, $date, $start_time, $end_time);
//  echo "<pre>"; print_r($isValid); exit;
        // echo "<pre>";
        // print_r($booked);
        // exit();

        // desiscion maker if available time is valid
        $this->db->trans_begin();
        if ($isValid) {
            // Inserting and checking
             $insert_appointment_reschedule = $this->db->insert('appointment_reschedules',$booked);
            if (!$insert_appointment_reschedule) {
                $this->db->trans_rollback();
                $this->messages->add('Invalid Action', 'warning');
                // $this->index();
        // exit('a');
                redirect('partner/managing/reschedule/'.$appointment_id);

                return;
            }
        } else {
            $this->messages->add('Invalid Action', 'warning');
            // redirect('partner/member_list/coach');
        // exit('b');
            redirect('partner/managing/reschedule/'.$appointment_id);

        }

        // updating appointment current status to reschedule
        $appointment_update = array(
            'status' => 'reschedule',
        );


         // update table appointment
        $appointment_id = (int)$appointment_id;
        // echo $appointment_id;
        // exit();
        $update_appointment = $this->db->where('id',$appointment_id)->update('appointments',$appointment_update);
        // print_r($update_appointment);
        // exit();
        if (!$update_appointment) {
            $this->db->trans_rollback();

        // update table appointment
            $this->messages->add('Invalid Action', 'warning');
            // redirect('partner/member_list/coach');
            redirect('partner/managing/reschedule/'.$appointment_id);

        } else {
            // if student reschedule the session 2 days/ 48 hours before the sessioin start, student will get the token back 100%
            // $student_token_data = $this->identity_model->get_identity('token')->where('user_id', $student_id)->get();
            // $coach_token_cost = $this->coach_token_cost_model->select('id, token_for_student')->where('coach_id', $appointment_data->coach_id)->get();
            // $time_left_before_session = $this->time_reminder_before_session($appointment_data->date . ' ' . $appointment_data->start_time, (2 * 24 * 60 * 60));

            // if ($time_left_before_session > 0) {
            //     $student_token = $student_token_data->token_amount + $coach_token_cost->token_for_student;
            //     $token_update = array(
            //         'token_amount' => $student_token,
            //     );

            //     if (!$this->identity_model->get_identity('token')->update($student_token_data->id, $token_update)) {
            //         $this->messages->add(validation_errors(), 'warning');
            //         // $this->index();
            //         redirect('partner/managing/reschedule/'.$appointment_id);

            //         return;
            //     }

            //     if (!$this->create_token_history($student_id, $appointment_id, $coach_token_cost->token_for_student, $student_token, 9)) {
            //         $this->messages->add('Error while create token history', 'warning');
            //         // $this->index();
            //         redirect('partner/managing/reschedule/'.$appointment_id);

            //         return;
            //     }
            // } else {
            //     if (!$this->create_token_history($student_id, $appointment_id, $coach_token_cost->token_for_student, $student_token_data->token_amount, 7)) {
            //         $this->messages->add('Error while create token history', 'warning');
            //         // $this->index();
            //         redirect('partner/managing/reschedule/'.$appointment_id);

            //         return;
            //     }
            // }
        }

        $day = strtolower(date('l', $date));
        $schedule_data = $this->schedule_model->select('id')->where('user_id', $coach_id)->where('day', $day)->get();
        $new_appointment = array(
            'student_id' => $student_id,
            'coach_id' => $coach_id,
            'schedule_id' => $schedule_data->id,
            'date' => date("Y-m-d", $date),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => 'reschedule',
        );

        // update table appointment
        $update_appointments = $this->db->where('id',$appointment_id)
                                        ->update('appointments',$new_appointment);

        // inserting new appointment to appointment table
        // $new_appointment_id = $this->appointment_model->insert($new_appointment);
        // ==============
        // if (!$new_appointment_id) {
        //     $this->db->trans_rollback();
        //     $this->messages->add('Invalid Action', 'warning');
        //     // $this->index();
        //     redirect('partner/managing/reschedule/'.$appointment_id);

        //     return;
        // } else {
            // =====================
            // if student reschedule the session 2 days/ 48 hours before the sessioin start, student will get the token back 100%
            $student_token_data = $this->identity_model->get_identity('token')->where('user_id', $student_id)->get();
            // $coach_token_cost = $this->coach_token_cost_model->select('id, token_for_student')->where('coach_id', $appointment_data->coach_id)->get();

            // $student_token = $student_token_data->token_amount - $coach_token_cost->token_for_student;
            // $token_update = array(
            //     'token_amount' => $student_token,
            // );

            // if (!$this->identity_model->get_identity('token')->update($student_token_data->id, $token_update)) {
            //     $this->messages->add(validation_errors(), 'warning');
            //     // $this->index();
            //     redirect('partner/managing/reschedule/'.$appointment_id);

            //     return;
            // }

            // if (!$this->create_token_history($student_id, $appointment_id, $coach_token_cost->token_for_student, $student_token, 1)) {
            //     $this->messages->add('Error while create token history', 'warning');
            //     // $this->index();
            //     redirect('partner/managing/reschedule/'.$appointment_id);

            //     return;
            // }
        // }

        // update_new_token_student
        $data_update_new_token_student = ['token_amount' => $update_student_token];
        $update_new_token_student = $this->db->where('user_id',$student_id)->update('user_tokens',$data_update_new_token_student);

        $this->db->trans_commit();

        $data_appointment = $this->appointment_model->where('id', $appointment_id)->get();

        $convert_data_student1 = $this->schedule_function->convert_book_schedule(($this->identity_model->new_get_gmt($student_id)[0]->minutes), strtotime($appointment_data->date), $appointment_data->start_time, $appointment_data->end_time);
        $convert_data_coach1 = $this->schedule_function->convert_book_schedule(($this->identity_model->new_get_gmt($id_coach)[0]->minutes), strtotime($appointment_data->date), $appointment_data->start_time, $appointment_data->end_time);
        $convert_data_student2 = $this->schedule_function->convert_book_schedule(($this->identity_model->new_get_gmt($student_id)[0]->minutes), strtotime($new_appointment['date']), $new_appointment['start_time'], $new_appointment['end_time']);
        $convert_data_coach2 = $this->schedule_function->convert_book_schedule(($this->identity_model->new_get_gmt($coach_id)[0]->minutes), strtotime($new_appointment['date']), $new_appointment['start_time'], $new_appointment['end_time']);

        $gmt_student = $this->identity_model->new_get_gmt($student_id);
        $gmt_coach = $this->identity_model->new_get_gmt($coach_id);
        $gmt_coach_old = $this->identity_model->new_get_gmt($id_coach);

        $minutes = $gmt_student[0]->minutes;
        $minutes_coach_old = $gmt_coach_old[0]->minutes;
        $minutes_coach = $gmt_coach[0]->minutes;

        date_default_timezone_set('UTC');
        // student

        $st  = strtotime($convert_data_student2['start_time']);
        $usertime1 = $st;
        $start_hour = date("H:i", $usertime1);

        $et  = strtotime($convert_data_student2['end_time']);
        $usertime2 = $et-(5*60);
        $end_hour = date("H:i", $usertime2);

        $old_et = strtotime($convert_data_student1['end_time']);
        $old_et_student = $old_et-(5*60);
        $et_student = date("H:i", $old_et_student);
        // coach

        $st_coach  = strtotime($convert_data_coach2['start_time']);
        $usertime1_coach = $st_coach;
        $start_hour_coach = date("H:i", $usertime1_coach);

        $et_coach  = strtotime($convert_data_coach2['end_time']);
        $usertime2_coach = $et_coach-(5*60);
        $end_hour_coach = date("H:i", $usertime2_coach);

        $old_st_coach = strtotime($convert_data_coach1['start_time']);
        $old_st_coach_convert = $old_st_coach;
        $st_coach = date("H:i", $old_st_coach_convert);

        $old_et_coach = strtotime($convert_data_coach1['end_time']);
        $old_et_coach_convert = $old_et_coach-(5*60);
        $et_coach = date("H:i", $old_et_coach_convert);

        //print_r($convert_data_student1); exit;
        // after student rescheduled an appointment, send email to coach
        // tube name for messaging action
        // $tube = 'com.live.email';
        // $id_to_email_address = $this->user_model->dropdown('id', 'email');
        // $id_to_name = $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname');
        // $data_student = array(
        //     'subject' => 'Appointment Rescheduled',
        //     'email' => $id_to_email_address[$student_id],
        //     //'content' => 'Rescheduled appointment by Partner Admin at ' . date('l jS \of F Y', $convert_data_student1['date']) . ' from ' . $convert_data_student1['start_time'] . ' until ' . $convert_data_student1['end_time'] . ' with ' . $id_to_name[$new_appointment['coach_id']] . ' to ' . date('l jS \of F Y', $convert_data_student2['date']) . ' from ' . $convert_data_student2['start_time'] . ' until ' . $convert_data_student2['end_time'] . ' '.$gmt_student.'.',
        // );
        // $data_student['content'] = $this->email_structure->header()
        //         .$this->email_structure->title('Appointment Rescheduled')
        //         .$this->email_structure->content('Rescheduled appointment by Partner Admin at ' . date('l jS \of F Y', $convert_data_student1['date']) . ' from ' . $convert_data_student1['start_time'] . ' until ' . $convert_data_student1['end_time'] . ' with ' . $id_to_name[$new_appointment['coach_id']] . ' to ' . date('l jS \of F Y', $convert_data_student2['date']) . ' from ' . $convert_data_student2['start_time'] . ' until ' . $convert_data_student2['end_time'] . ' '.$gmt_student.'.')
        //         //.$this->email_structure->button('JOIN SESSION')
        //         .$this->email_structure->footer('');

        // $data_coach = array(
        //     'subject' => 'Appointment Rescheduled',
        //     'email' => $id_to_email_address[$coach_id],
        //     //'content' => 'Your appointment at ' . date("l jS \of F Y", $convert_data_coach1['date']) . ' from ' . $convert_data_coach1['start_time'] . ' until ' . $convert_data_coach1['end_time'] . ' with student ' . $id_to_name[$new_appointment['student_id']] . ' has been rescheduled to ' . date("l jS \of F Y", $convert_data_coach2['date']) . ' from ' . $convert_data_coach2['start_time'] . ' until ' . $convert_data_coach2['end_time'] . ' '.$gmt_coach.'.',
        // );
        // $data_coach['content'] = $this->email_structure->header()
        //         .$this->email_structure->title('Appointment Rescheduled')
        //         .$this->email_structure->content('Your appointment at ' . date("l jS \of F Y", $convert_data_coach1['date']) . ' from ' . $convert_data_coach1['start_time'] . ' until ' . $convert_data_coach1['end_time'] . ' with student ' . $id_to_name[$new_appointment['student_id']] . ' has been rescheduled to ' . date("l jS \of F Y", $convert_data_coach2['date']) . ' from ' . $convert_data_coach2['start_time'] . ' until ' . $convert_data_coach2['end_time'] . ' '.$gmt_coach.'.')
        //         //.$this->email_structure->button('JOIN SESSION')
        //         .$this->email_structure->footer('');

        // $this->queue->push($tube, $data_student, 'email.send_email');
        // $this->queue->push($tube, $data_coach, 'email.send_email');

        // messaging
        // inserting notification
        // if ($new_appointment_id) {
            // $database_tube = 'com.live.database';
           // student notification data

            $student_notification = array(
                'user_id' => $student_id,
                'description' => 'You have a rescheduled session with ' . $new_name_coach,
                'status' => 2,
                'dcrea' => time(),
                'dupd' => time()
            );
            // coach notification data
            $coach_notification = array(
                'user_id' => $coach_id,
                'description' => 'Your admin scheduled a session with ' . $name_student,
                'status' => 2,
                'dcrea' => time(),
                'dupd' => time()
            );

            // oldcoach notification data
            $oldcoach_notification = array(
                'user_id' => $id_coach,
                'description' => 'Your admin has rescheduled your session with ' . $name_student,
                'status' => 2,
                'dcrea' => time(),
                'dupd' => time()
            );
            // student's data for reminder messaging
            // IMPORTANT : array index in content must be in mutual with table field in database
            $data_student = array(
                'table' => 'user_notifications',
                'content' => $student_notification,
            );

            // coach's data for reminder messaging
            // IMPORTANT : array index in content must be in mutual with table field in database
            $data_coach = array(
                'table' => 'user_notifications',
                'content' => $coach_notification,
            );

            $data_oldcoach = array(
                'table' => 'user_notifications',
                'content' => $oldcoach_notification,
            );

            // messaging inserting data notification
            $this->user_notification_model->insert($student_notification);
            $this->user_notification_model->insert($coach_notification);
            $this->user_notification_model->insert($oldcoach_notification);
        // }
        $student_gmt = $gmt_student[0]->gmt;
        $coach_old_gmt = $gmt_coach_old[0]->gmt;
        $coach_gmt = $gmt_coach[0]->gmt;

        // email
        $this->send_email->partner_reschedule($email_student, $name_student, $name_coach, date("l jS \of F Y", $convert_data_student1['date']), $convert_data_student1['start_time'], $et_student, $new_name_coach, date('l jS \of F Y', $convert_data_student2['date']), $start_hour, $end_hour, $student_gmt);
        $this->send_email->notif_partner_reschedule($new_email_coach, $name_student, $name_coach, date("l jS \of F Y", $convert_data_student1['date']), $convert_data_student1['start_time'], $convert_data_student1['end_time'], $new_name_coach, date('l jS \of F Y', $convert_data_coach2['date']), $start_hour_coach, $end_hour_coach, $coach_gmt);
        $this->send_email->notif_coach_reschedule($email_coach, $name_student, $name_coach, date("l jS \of F Y", $convert_data_coach1['date']), $st_coach, $et_coach, date('l jS \of F Y', $convert_data_student2['date']), $convert_data_student2['start_time'], $convert_data_student2['end_time'], $coach_old_gmt);
        // ==========
        $message = "Session Rescheduled";
        $this->messages->add($message, 'success');
        redirect('partner/coach_upcoming_session/one_to_one_session/'.$coach_id);
    }

    public function reschedule_session($appointment_id = '', $coach_id = '', $date_ = '') {


        if (!$date_ || !$coach_id) {
            //redirect(home);
            $vars = array();
            $this->template->content->view('default/contents/find_coach/availability', $vars);

            //publish template
            $this->template->publish();
        }

        // checking if the date is valid
        if (!$this->is_date_available(trim($date_), 0)) {
            $vars = array();
            $this->template->content->view('default/contents/find_coach/availability', $vars);
        }

        // checking if the date is in day off
        if ($this->is_day_off($coach_id, $date_) == true) {
            $vars = array();
            $this->template->content->view('default/contents/find_coach/availability', $vars);
        }

        // getting the day of $date
        // getting gmt minutes
        $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;
        $date = strtotime($date_);

        // getting day and day after or before based on gmt
        $day = strtolower(date('l', $date));

        $day2 = $this->day_index[$this->convert_gmt(array_search($day, $this->day_index), $minutes)];

        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student

        $appointment = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->get_all();
        // appointment with status temporary considered available for other student but not for student who is in the appointment and
        // appointment where the student has make an appoinment on the specific date, so there will be no the same start time and end time to be shown to the student from other coach
        $appointment_student = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', date("Y-m-d", $date))->get_all();
        // appointment coach in class
        $appointment_class = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', date("Y-m-d", $date))->get_all();

        // storing appointment to an array so can easily on searching / no object value inside
        $appointment_start_time_temp = array();
        $appointment_end_time_temp = array();

        // getting all unavailable schedule to be not shown on coach availability
        foreach ($appointment as $a) {
            if($minutes > 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            else if($minutes < 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            else if($minutes == 0){
                $appointment_start_time_temp[] = $a->start_time;
                $appointment_end_time_temp[] = $a->end_time;
            }
        }
        // storing class meeting days to appointment temp
        foreach ($appointment_class as $a) {
            if($minutes > 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            else if($minutes < 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            else if($minutes == 0){
                $appointment_start_time_temp[] = $a->start_time;
                $appointment_end_time_temp[] = $a->end_time;
            }
        }

        foreach ($appointment_student as $a) {
            if($minutes > 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            else if($minutes < 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            else if($minutes == 0){
                $appointment_start_time_temp[] = $a->start_time;
                $appointment_end_time_temp[] = $a->end_time;
            }
        }

        if($minutes > 0){
            $date2 = date("Y-m-d", strtotime('-1 day'.date("Y-m-d",$date)));
            $appointment2 = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $date2)->get_all();
            $appointment_student2 = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', $date2)->get_all();
            $appointment_class2 = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', $date2)->get_all();

            foreach($appointment2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            foreach($appointment_student2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            foreach($appointment_class2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }

        }
        else if($minutes < 0){
            $date2 = date("Y-m-d", strtotime('+1 day'.date("Y-m-d",$date)));
            $appointment2 = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $date2)->get_all();
            $appointment_student2 = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', $date2)->get_all();
            $appointment_class2 = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', $date2)->get_all();
            foreach($appointment2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            foreach($appointment_student2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            foreach($appointment_class2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
        }



        //getting all data
        $schedule_data1 = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
        $schedule_data2 = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day2)->get();

        $availability = $this->schedule_block($coach_id, $day, $schedule_data1->start_time, $schedule_data1->end_time, $schedule_data2->day, $schedule_data2->start_time, $schedule_data2->end_time);


        $availability_temp = array();
        $availability_exist;
        foreach ($availability as $a) {
            $duration = (strtotime($a['end_time']) - strtotime($a['start_time'])) / ($this->session_duration($this->auth_manager->partner_id()) * 60);
            if ($duration >= 1) {
                for ($i = 0; $i < $duration; $i++) {
                    $availability_exist = array(
                        // adding  minutes for every session
                        'start_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id()) * 60) * ($i))),
                        'end_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id()) * 60) * ($i + 1))),
                    );

                    // checking if the time is not out of coach schedule
                    if(strtotime($availability_exist['end_time']) <= strtotime($a['end_time'])){
                        // checking if availability is existed in the appointment
                        if (in_array($availability_exist['start_time'], $appointment_start_time_temp) && in_array($availability_exist['end_time'], $appointment_end_time_temp)) {
                            // no action
                        } else {
                            // storing availability that still active and not been boooked yet
                            if($this->isValidAppointment($availability_exist['start_time'], $availability_exist['end_time'], $appointment_start_time_temp, $appointment_end_time_temp)){
                                date_default_timezone_set('Etc/GMT'.(-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                        if ($date_ == date('Y-m-d', strtotime(date('Y-m-d') . ' + 2 days'))) {
                                            if (DateTime::createFromFormat('H:i:s', $availability_exist['start_time']) >= DateTime::createFromFormat('H:i:s', date('H:i:s'))) {
                                                $availability_temp[] = $availability_exist;
                                            }
                                        } else {
                                            $availability_temp[] = $availability_exist;
                                        }
                            }
                        }
                    }
                }
            }
        }

        $coach_identity = $this->identity_model->get_coach_identity($coach_id);
        // echo('<pre>');print_r($coach_identity);exit;
        return  array(
            'profile_picture' => @$coach_identity[0]->profile_picture,
            'coach_id' => @$coach_identity[0]->id,
            'fullname' => @$coach_identity[0]->fullname,
            'country' => @$coach_identity[0]->country,
            'token_for_student' => @$coach_identity[0]->token_for_student,
            'availability' => $availability_temp,
            'coach_type_id' => @$coach_identity[0]->coach_type_id
        );

        //echo('<pre>');print_r($var);exit;
        //$this->template->content->view('default/contents/manage_appointment/reschedule/availability', $vars);

        //publish template
        //$this->template->publish();
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

    private function isAvailable($student_id = '', $coach_id = '', $date = '', $start_time = '', $end_time = '') {
        //getting the day of $date
        $day = strtolower(date('l', $date));
        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id', $coach_id)->where('day', $day)->order_by('id', 'asc')->get();
        $schedule = $this->block($coach_id, $day, $schedule_data->start_time, $schedule_data->end_time);

        // check if coach availability has been booked or nothing
        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student
        $appointment = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->where('start_time', $start_time)->where('end_time', $end_time)->get();
        // appointment with status temporary considered available for other student but not for student who is in the appointment and
        // appointment where the student has make an appoinment on the specific date, so there will be no the same start time and end time to be shown to the student from other coach
        $appointment_student = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $student_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->where('start_time', $start_time)->where('end_time', $end_time)->get();
        // appointment coach in class
        $appointment_class = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', date("Y-m-d", $date))->where('start_time', $start_time)->where('end_time', $end_time)->get();

        // partner setting about student appointment
        $setting = $this->partner_setting_model->get();
        $appointment_count = count($this->appointment_model->where('student_id', $student_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->get_all());
        //print_r($this->get_date_week($date)); exit;
        $appointment_count_week = 0;
        foreach($this->get_date_week($date) as $s){
            $appointment_count_week = $appointment_count_week + count($this->appointment_model->where('student_id', $student_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $s)->get_all());
        }
//        print_r($appointment_count);
//        print_r($appointment_count_week);
//        print_r($setting->max_session_per_day);
//        print_r($setting->max_day_per_week);
//        exit;
//        echo('<pre>');
//        echo(date('Y-m-d', $date));
//        echo('<br>');
//        print_r($start_time);
//        print_r($end_time);
//        print_r($schedule); exit;
        $status1 = 0;
        if ($appointment || $appointment_student || $appointment_class) {

    return true;
        } else if (!$appointment) {

            //if($appointment_count < $setting->max_session_per_day && $appointment_count_week < $setting->max_day_per_week){
            foreach($schedule as $s){
              $end_time_changer = $s['end_time'];
              if($end_time_changer == '16:59:00'){
                $end_time_changer = '17:00:00';
              }
              if(strtotime($start_time) >= strtotime($s['start_time']) && strtotime($end_time) <= strtotime($end_time_changer)){
                  $status1 = 1;
                  break;
              }
            }
            if($status1 == 1){
                return true;
            }
            else{
                $this->messages->add('Invalid Appointment Time', 'warning');
                return false;
            }
        }
    }

    private function get_date_week($date = ''){
        $index = array_search(strtolower(date("l", $date)), $this->day_index);
        $date_index = array();
        for($i=0;$i<7;$i++){
            $date_index[] = date('Y-m-d', strtotime(date('Y-m-d', $date). ''. ($i-$index).' days'));
        }
        return $date_index;
    }

    public function time_reminder_before_session($session_time, $delay_time) {
        if (DateTime::createFromFormat('Y-m-d H:i:s', trim($session_time)) !== FALSE) {
            $now = (date('Y-m-d H:i:s', time() + $delay_time));
            return (((strtotime($session_time) - strtotime($now))) < 0 ? FALSE : (strtotime($session_time) - strtotime($now)));
        } else {
            return FALSE;
        }
    }

    private function create_token_history($student_id = '', $appointment_id = '', $coach_cost = '', $remain_token = '', $status = '') {
        $appointment = $this->appointment_model->get_appointment($appointment_id);
        $partner_id = $this->auth_manager->partner_id($student_id);
        $organization_id = '';
        $organization_id = $this->db->select('gv_organizations.id')
                  ->from('gv_organizations')
                  ->join('users', 'users.organization_code = gv_organizations.organization_code')
                  ->where('users.id', $student_id)
                  ->get()->result();

        if(empty($organization_id)){
            $organization_id = $organization_id;
        }else{
            $organization_id = $organization_id[0]->id;
        }

        if (!$appointment) {
            $this->messages->add('Invalid apppointment id', 'warning');
            redirect('partner/member_list/coach');
        }
        $token_history = array(
            'appointment_id' => $appointment_id,
            'user_id' => $student_id,
            'partner_id' => $partner_id,
            'organization_id' => $organization_id,
            'transaction_date' => strtotime(date('d-m-Y')),
            'description' => 'Session with ' . $appointment[0]->coach_fullname . ' at ' . $appointment[0]->date . ' ' . $appointment[0]->start_time . ' until ' . $appointment[0]->end_time,
            'token_amount' => $coach_cost,
            'token_status_id' => $status,
            'balance' => $remain_token
        );
        if ($this->token_histories_model->insert($token_history)) {
            return true;
        } else {
            return false;
        }
    }

    public function cancel($student_id = '', $appointment_id = '') {
        // checking if appointment has already cancelled
        $appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, start_time, end_time, status')->where('id', $appointment_id)->get();
        if ($appointment_data->status == 'cancel') {
            $this->messages->add('Appointment has already cancelled', 'warning');
            redirect('partner/member_list/coach');
        }

        // updating appointment (change status to cancel)
        // storing data

        $appointment = array(
            'status' => 'cancel',
        );

        // Updating and checking to appoinment table
        $this->db->trans_begin();

        if (!$this->appointment_model->update($appointment_id, $appointment)) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'warning');
            $this->index();
            return;
        }
        ////////////////////////////////////////////////////////////////////////
        // WEBEX
        ////////////////////////////////////////////////////////////////////////
        $webex_host = $this->webex_host_model->get_host($appointment_id);
        $webex = $this->webex_model->select('id')->where('appointment_id', $appointment_id)->get();

        if($webex_host && $webex){
            // delete session in webex
            // delete session from table webex
            if(!$this->delete_session($webex_host[0]->id, $appointment_id) || !$this->webex_model->delete($webex->id)){
                $this->db->trans_rollback();
                $this->messages->add('Error while deleting session in webex', 'error');
                redirect('partner/member_list/coach');
            }
        }

        $this->db->trans_commit();

        // if student cancel the session 2 days/ 48 hours before the sessioin start, student will get the token back 100%
        $student_token_data = $this->identity_model->get_identity('token')->where('user_id', $appointment_data->student_id)->get();
        $coach_token_cost = $this->coach_token_cost_model->select('id, token_for_student')->where('coach_id', $appointment_data->coach_id)->get();
        $time_left_before_session = $this->time_reminder_before_session($appointment_data->date . ' ' . $appointment_data->start_time, (2 * 24 * 60 * 60));

        if ($time_left_before_session > 0) {
            $student_token = $student_token_data->token_amount + $coach_token_cost->token_for_student;
            $token_update = array(
                'token_amount' => $student_token,
            );

            if (!$this->identity_model->get_identity('token')->update($student_token_data->id, $token_update)) {
                $this->messages->add(validation_errors(), 'warning');
                $this->index();
                return;
            }

            if (!$this->create_token_history($student_id, $appointment_id, $coach_token_cost->token_for_student, $student_token, 5)) {
                $this->messages->add('Error while create token history', 'warning');
                $this->index();
                return;
            }
        } else {
            if (!$this->create_token_history($student_id, $appointment_id, $coach_token_cost->token_for_student, $student_token_data->token_amount, 3)) {
                $this->messages->add('Error while create token history', 'warning');
                $this->index();
                return;
            }
        }

        // after student cancelled an appointment, send email to coach
        // tube name for messaging action
        $tube = 'com.live.email';
        $id_to_email_address = $this->user_model->dropdown('id', 'email');
        $id_to_name = $this->identity_model->get_identity('profile')->dropdown('id', 'fullname');
        $data = array(
            'subject' => 'Appointment Cancelled',
            'email' => $id_to_email_address[$appointment_data->coach_id],
            //'content' => 'Your appointment with student ' . $id_to_name[$student_id] . ' at ' . date("l jS \of F Y", strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' has been cancelled',
        );
        $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Appointment Cancelled')
                .$this->email_structure->content('Your appointment with student ' . $id_to_name[$student_id] . ' at ' . date("l jS \of F Y", strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' has been cancelled')
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');


        $this->queue->push($tube, $data, 'email.send_email');

        //messaging for notification
        $database_tube = 'com.live.database';
        $coach_notification = array(
            'user_id' => $appointment_data->coach_id,
            'description' => 'Your appointment with student ' . $id_to_name[$student_id] . ' at ' . date("l jS \of F Y", strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' has been cancelled.',
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

        $this->messages->add('Updating Appointment Successful', 'success');
        redirect('partner/member_list/coach');
    }

    public function delete_session($host_id = '', $meeting_identifier = '') {
        if (!$host_id || !$meeting_identifier) {
            $this->messages->add('Invalid Host ID or Meeting Identifier', 'error');
            return FALSE;
        }

        $webex_host = $this->webex_host_model->select('subdomain_webex, partner_id, webex_id, timezones, password')->where('id', $host_id)->get();

        if (substr($meeting_identifier, 0, 1) == 'c') {
            $session = $this->webex_class_model->select('webex_meeting_number')->where('class_meeting_id', substr($meeting_identifier, 1))->get();
            if (!$session) {
                $this->messages->add('Invalid Meeting Identifier', 'error');
                return FALSE;
            }
        } else {
            $session = $this->webex_model->select('webex_meeting_number')->where('appointment_id', $meeting_identifier)->get();
            if (!$session) {
                $this->messages->add('Invalid Meeting Identifier', 'error');
                return FALSE;
            }
        }

        $XML_SITE = $webex_host->subdomain_webex . ".webex.com";
        $XML_PORT = "443";

        // Set calling user information
        $d["UID"] = $webex_host->webex_id; // WebEx username
        $d["PWD"] = $webex_host->password; // WebEx password
        $d["SNM"] = $webex_host->subdomain_webex; //Demo Site SiteName
        $d["PID"] = $webex_host->partner_id; //Demo Site PartnerID

        $d["XML"] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <serv:message xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
                <header>
                    <securityContext>
                    <webExID>{$d["UID"]}</webExID>
                    <password>{$d["PWD"]}</password>
                    <siteName>{$d["SNM"]}</siteName>
                    <partnerID>{$d["PID"]}</partnerID>
                    </securityContext>
                </header>
                <body>
                    <bodyContent xsi:type=\"java:com.webex.service.binding.meeting.DelMeeting\">
                        <meetingKey>{$session->webex_meeting_number}</meetingKey>
                    </bodyContent>
                </body>
                </serv:message>";

        $URL = "http://{$XML_SITE}/WBXService/XMLService";
        $result = $this->webex_function->post_it($d, $URL, $XML_PORT);

        // Clean xml
        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
        $simple_xml = simplexml_load_string($clean_result);
        $session_key = '';
        if ($simple_xml === false) {
            $this->messages->add('Bad XML!', 'error');
            return FALSE;
        } else {
            if ($simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->result == 'SUCCESS' || $simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->exceptionID == '060001') {
                return TRUE;
            }
        }
        return FALSE;
    }

}
