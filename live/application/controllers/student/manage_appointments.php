<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class manage_appointments extends MY_Site_Controller {

    var $day_index = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    // Constructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('appointment_model');
        $this->load->model('appointment_reschedule_model');
        $this->load->model('schedule_model');
        $this->load->model('offwork_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('user_model');
        $this->load->model('identity_model');
        $this->load->model('coach_token_cost_model');
        $this->load->model('token_histories_model');
        $this->load->model('coach_day_off_model');
        $this->load->model('partner_model');
        $this->load->model('partner_setting_model');
        $this->load->model('specific_settings_model');
        $this->load->model('webex_host_model');
        $this->load->model('webex_class_model');
        $this->load->model('webex_model');

        // for messaging action and timing
        $this->load->library('phpass');
        $this->load->library('queue');
        $this->load->library('webex_function');
        $this->load->library('email_structure');
        $this->load->library('send_email');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD') {
            $this->messages->add('Access Denied', 'danger');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = 'Manage Appointment';
        $appointment = $this->appointment_model->select('id, coach_id, date, schedule_id, start_time, end_time, status')->where('student_id', $this->auth_manager->userid())->where('date >=', date('Y-m-d'))->where('status', 'active')->order_by('date', 'asc')->get_all();
        $appointment_temp = array();
        foreach ($appointment as $a) {
            if ($a->date == date('Y-m-d')) {
                if ($a->start_time >= date('H:i:s')) {
                    $appointment_temp [] = $a;
                }
            } else {
                $appointment_temp[] = $a;
            }
        }
        $vars = array(
            'appointment' => $appointment_temp,
        );
        $this->template->content->view('default/contents/manage_appointment/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function reschedule($appointment_id = '', $coach_id = '', $page = ''){
        $this->template->title = 'Reschedule Appointment';

        // checking if appointment has already rescheduled
        $appointment_reschedule_data = $this->appointment_reschedule_model->select('id')->where('appointment_id', $appointment_id)->get();
        
        if ($appointment_reschedule_data) {
            $this->messages->add('apppointment has already rescheduled', 'warning');
            redirect('student/upcoming_session');
        }

        $appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, start_time, end_time, status')->where('id', $appointment_id)->get();
        $student_id_ = $appointment_data->student_id;
        $old_coach_id = $appointment_data->coach_id;  
        $date = $appointment_data->date;  
        $get_start_time = $appointment_data->start_time;

        $gmt_student = $this->identity_model->new_get_gmt($student_id_);
        
        // student
        $minutes = $gmt_student[0]->minutes;
        // coach

        @date_default_timezone_set('UTC');
        // student
        $st  = strtotime($get_start_time);
        $usertime1 = $st+(60*$minutes);
        $start_time = date("H:i", $usertime1);

        $week_date = $this->x_week_range($date);
 
       // get other coach
        $offset = 0;
        $per_page = 6;
        $uri_segment = 6;

        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student/manage_appointments/reschedule/'.$appointment_id."/".$coach_id."/"), count($this->identity_model->get_coach_identity(null)), $per_page, $uri_segment);
        $coaches = $this->identity_model->get_coach_identity(null, null, null, null, null, null, null, $per_page, $offset);

        $partner_id = $this->auth_manager->partner_id($this->auth_manager->userid());

        $setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();
        $standard_coach_cost = $setting[0]->standard_coach_cost;
        $elite_coach_cost = $setting[0]->elite_coach_cost;
        $session_duration = $setting[0]->session_duration;

        $vars = array(
            'coaches' => $coaches,
            'rating' => $this->coach_rating_model->get_average_rate(),
            'pagination' => @$pagination,
            'standard_coach_cost' => $standard_coach_cost,
            'elite_coach_cost' => $elite_coach_cost,
            'session_duration' => $session_duration,
            'old_coach_id' => $old_coach_id,
            'end_date' => $week_date[1],
            'start_date' => $date, 
            'start_time' => $start_time
        );
       // echo('<pre>');
       // print_r($vars); exit;
        $this->session->set_userdata('appointment_id', $appointment_id);

       $this->template->content->view('default/contents/manage_appointment/reschedule/select_coach', $vars);
       $this->template->publish();
    }

    public function availability($search_by = '', $coach_id = '', $date_ = '', $start_hour_ = '') {
        $this->template->title = 'Availability';
        
        if (!$date_ || !$coach_id) {
            
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
        //print_r(date('Y-m-d', $date));
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


                $date_parameter = strtotime($date_);
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
                                                // @date_default_timezone_set('Etc/GMT'.(-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                                @date_default_timezone_set('Etc/GMT'.(-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                                // if ($date_ == date('Y-m-d', strtotime(date('Y-m-d') . ' + 2 days'))) {
                                                //     exit('hai');
                                                //     if (DateTime::createFromFormat('H:i:s', $availability_exist['start_time']) >= DateTime::createFromFormat('H:i:s', date('H:i:s'))) {
                                                //         $availability_temp[] = $availability_exist;
                                                //     }
                                                // } else {
                                                    $availability_temp[] = $availability_exist;
                                                // }
                                    }
                                    if($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes > 0){
                                        @date_default_timezone_set('Etc/GMT'.($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 : $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                    }
                                }
                            }
                        }
                    }
                }
        
        $vars = array(
            'availability' => $availability_temp,
            'coach_id' => $coach_id,
            'date' => $date_parameter,
            'date_title' => strtotime($date_),
            'search_by' => $search_by,
            'start_hour_' => $start_hour_,
            'cost' => $this->coach_token_cost_model->select('token_for_student')->where('coach_id', $coach_id)->get()
        );
        // echo "<pre>";
        // print_r($availability_temp);
        // exit();

        $this->template->content->view('default/contents/find_coach/reschedule/availability', $vars);

        //publish template
        $this->template->publish();
    }

    public function schedule_detail($id = '') {
        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id', $id)->order_by('id', 'asc')->get_all();
        $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;
        if (!$schedule_data) {
            redirect('student/find_coaches/single_date');
        }

        $schedule = array();
        //foreach($schedule_data as $s){
        for ($i = 0; $i < count($schedule_data); $i++) {
            $schedule[$schedule_data[$i]->day] = $this->schedule_block($id, $schedule_data[$i]->day, $schedule_data[$i]->start_time, $schedule_data[$i]->end_time, $schedule_data[$this->convert_gmt($i, $minutes)]->day, $schedule_data[$this->convert_gmt($i, $minutes)]->start_time, $schedule_data[$this->convert_gmt($i, $minutes)]->end_time);
            if(!$schedule[$schedule_data[$i]->day]){
                $schedule[$schedule_data[$i]->day] = array(
                    array(
                        'start_time' => '00:00:00',
                        'end_time' => '00:00:00',
                    )   
                );
            }
            
        }
        $vars = array(
            'coach_id' => $id,
            'schedule' => $schedule,
        );
        $this->template->content->view('default/contents/find_coach/schedule_detail', $vars);

        //publish template
        $this->template->publish();
    }

    public function summary_book($search_by = '', $coach_id = '', $date = '', $start_time = '', $end_time = '') {
        
        $this->template->title = 'Reschedule Booking Summary';

        $partner_id = $this->auth_manager->partner_id($this->auth_manager->userid());
        
        $setting = $this->db->select('standard_coach_cost,elite_coach_cost')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();
        $standard_coach_cost = $setting[0]->standard_coach_cost;
        $elite_coach_cost = $setting[0]->elite_coach_cost;

        $vars = array(
            'data_coach' => $this->identity_model->get_coach_identity($coach_id),
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'search_by' => $search_by,
            'standard_coach_cost' => $standard_coach_cost,
            'elite_coach_cost' => $elite_coach_cost
        );


        $this->template->content->view('default/contents/find_coach/reschedule/summary_book', $vars);
        //publish template
        $this->template->publish();
    }

    public function booking($coach_id = '', $date_ = '', $start_time_ = '', $end_time_ = '', $token) {

        $appointment_id_old = $this->session->userdata('appointment_id');
    

        $start_time_available = $start_time_;
        $end_time_available = $end_time_;
        
        $convert = $this->schedule_function->convert_book_schedule(-($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes), $date_, $start_time_, $end_time_);
        $date = $convert['date'];
        $dateconvert = date('Y-m-d', $date_);
        $dateconvertcoach = date('Y-m-d', $date);
        $start_time = $convert['start_time'];
        $end_time = $convert['end_time'];



        // timezone
                    $id_student = $this->auth_manager->userid();
                    
   
                    // student
                    $gmt_student = $this->identity_model->new_get_gmt($id_student);
                    // coach
                    $gmt_coach = $this->identity_model->new_get_gmt($coach_id);
              
        
                    // student
                    $minutes = $gmt_student[0]->minutes;
                    // coach
                    $minutes_coach = $gmt_coach[0]->minutes;

                    @date_default_timezone_set('UTC');
                    // student
                    $st  = strtotime($start_time);
                    $usertime1 = $st+(60*$minutes);
                    $start_hour = date("H:i", $usertime1);

                    $et  = strtotime($end_time);
                    $usertime2 = $et+(60*$minutes)-(5*60);
                    $end_hour = date("H:i", $usertime2);

                    // coach

                    $st_coach  = strtotime($start_time);
                    $usertime1_coach = $st_coach+(60*$minutes_coach);
                    $start_hour_coach = date("H:i", $usertime1_coach);

                    $et_coach  = strtotime($end_time);
                    $usertime2_coach = $et_coach+(60*$minutes_coach)-(5*60);
                    $end_hour_coach = date("H:i", $usertime2_coach);


        $check_max_book_coach_per_day = $this->max_book_coach_per_day($coach_id,$date);
        if(!$check_max_book_coach_per_day){
            $this->messages->add('This coach has exceeded maximum booked today', 'warning');
            redirect('student/manage_appointments/new_reschedule/'.$appointment_id_old);
            
        }

        //print_r(date('Y-m-d', $date)); exit;
        
        try {
            // First of all, let's begin a transaction
            // A set of queries; if one fails, an exception should be thrown
            $isValid = $this->isAvailable($coach_id, $date, $start_time, $end_time);
            if ($isValid) {
                $availability = $this->isOnAvailability($coach_id, date('Y-m-d', $date_));

                if (in_array(array('start_time' => $start_time_available, 'end_time' => $end_time_available), $availability)) {
                    // go to next step 
                    //exit;
                } else {
                    $this->messages->add('Invalid Time', 'warning');
                    redirect('student/manage_appointments/new_reschedule/'.$appointment_id_old);
                }
                // begin the transaction to ensure all data created or modified structural
 
                 $token_cost = $token;


                $remain_token = $this->update_token($token_cost);
                
                if ($this->db->trans_status() === true && $remain_token >= 0){
                    
                    redirect('student/manage_appointments/reschedule_booking/'.$appointment_id_old."/".$coach_id."/". $dateconvert."/". $start_hour."/". $end_hour);
                } else {
                    $this->db->trans_rollback();
                    $this->messages->add('Not Enough Token', 'warning');
                    redirect('student/manage_appointments/new_reschedule/'.$appointment_id_old);
                }
            } else {
                $this->db->trans_rollback();
                $this->messages->add('Invalid Appointment', 'warning');
                redirect('student/manage_appointments/new_reschedule/'.$appointment_id_old);
            }


            // If we arrive here, it means that no exception was thrown
            // i.e. no query has failed, and we can commit the transaction

        } catch (Exception $e) {
            // An exception has been thrown
            // We must rollback the transaction
            $this->db->trans_rollback();
            $this->messages->add('An error has occured, please try again.', 'warning');
            redirect('student/manage_appointments/new_reschedule/'.$appointment_id_old);
        }
    }

    private function isAvailable($coach_id = '', $date = '', $start_time = '', $end_time = '') {
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
        $appointment_student = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->where('start_time', $start_time)->where('end_time', $end_time)->get();
        // appointment coach in class
        $appointment_class = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', date("Y-m-d", $date))->where('start_time', $start_time)->where('end_time', $end_time)->get();
        
        // partner setting about student appointment
        // $setting = $this->partner_setting_model->get();
        $partner_id = $this->auth_manager->partner_id($coach_id);
        
        // check apakah status setting region allow atau disallow
        $region_id = $this->auth_manager->region_id($partner_id);
        
        $get_status_setting_region = $this->specific_settings_model->get_specific_settings($region_id,'region');
        
        $max_session_per_day = '';
        $max_day_per_week = '';
        if($get_status_setting_region[0]->status_set_setting == 0){
            $get_setting = $this->global_settings_model->get_partner_settings();
            $max_session_per_day = $get_setting[0]->max_session_per_day; 
            $max_day_per_week = $get_setting[0]->max_day_per_week; 
        } else {
            $get_setting = $this->specific_settings_model->get_partner_settings($partner_id);
            $max_session_per_day = $get_setting[0]->max_session_per_day;
            $max_day_per_week = $get_setting[0]->max_day_per_week;
        }
      
        $student_id = $this->auth_manager->userid();
        $appointment_count = count($this->appointment_model->where('student_id', $student_id)->where('date', date("Y-m-d", $date))->get_all());
     
        $appointment_count_week = 0;
        foreach($this->get_date_week($date) as $s){
            $appointment_count_week = $appointment_count_week + count($this->appointment_model->where('student_id', $student_id)->where('date', $s)->get_all());
        }
     
        $status1 = 0;
        if ($appointment || $appointment_student || $appointment_class) {
            return false;
        } else if (!$appointment) {
            if($appointment_count < $max_session_per_day && $appointment_count_week < $max_day_per_week){
                foreach($schedule as $s){
                    if(strtotime($start_time) >= strtotime($s['start_time']) && strtotime($end_time) <= strtotime($s['end_time'])){
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
            else{
                $this->messages->add('Exceeded Max Session Per Day or Week', 'warning');
                return false; 
                // diganti tanggal 23 maret 2017

                // return true; 
            }
        }
    }

    private function isOnAvailability($coach_id = '', $date_ = '') {
        if (!$date_ || !$coach_id) {
            //redirect(home);
            $vars = array();
            $this->template->content->view('default/contents/find_coach/availability', $vars);

            //publish template
            $this->template->publish();
        }
        
        // checking if the date is valid
        // if (!$this->is_date_available(trim($date_), 0)) {
        if (!$this->is_date_available(trim($date_), -1)) {
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
                                if(!$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes == 0){
                                    @date_default_timezone_set('Etc/GMT'.(-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                }
                                        // if ($date_ == date('Y-m-d', strtotime(date('Y-m-d') . ' + 2 days'))) {
                                        //     if (DateTime::createFromFormat('H:i:s', $availability_exist['start_time']) >= DateTime::createFromFormat('H:i:s', date('H:i:s'))) {
                                        //         $availability_temp[] = $availability_exist;
                                        //     }
                                        // } else {
                                            $availability_temp[] = $availability_exist;
                                        // }
                                        
                                        // mengatasi tanggal yang tidak sesuai
                                        if($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes <= 0){
                                            @date_default_timezone_set('Etc/GMT'.($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 : $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                        }
                            }
                        }
                    }
                }
            }
        }

        return $availability_temp;
    }

    public function max_book_coach_per_day($coach_id='',$date=''){
        // get setting partner
        // $coach_id = '187';
        // $date = '2016-07-28';
        $partner_id = $this->auth_manager->partner_id($coach_id);

        // check apakah status setting region allow atau disallow
        $region_id = $this->auth_manager->region_id($partner_id);
        
        $get_status_setting_region = $this->specific_settings_model->get_specific_settings($region_id,'region');
        
        $max_per_day = '';
        if($get_status_setting_region[0]->status_set_setting == 0){
            $get_setting = $this->global_settings_model->get_partner_settings();
            $max_per_day = $get_setting[0]->max_session_per_day; 
        } else {
            $get_setting = $this->specific_settings_model->get_partner_settings($id_partner);
            $max_per_day = $get_setting[0]->max_session_per_day;
        }

        $max_coach = count($this->appointment_model->select('id')->where('coach_id',$coach_id)->where('date',$date)->get_All());
   
        if($max_coach > $max_per_day){
            return false;
        } else {
            return true;
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

    private function update_token($cost = '') {
        $status = false;
        $student_token = $this->identity_model->get_identity('token')->select('id, token_amount')->where('user_id', $this->auth_manager->userid())->get();
        //$coach_cost = $this->coach_token_cost_model->select('token_for_student')->where('coach_id', $coach_id)->get();
        if ($student_token->token_amount < $cost) {
            $status = false;
        } else if ($student_token->token_amount >= $cost) {
            $remain_token = $student_token->token_amount - $cost;
            $data = array(
                'token_amount' => $remain_token,
            );
            $this->identity_model->get_identity('token')->update($student_token->id, $data);
            $status = true;
        }

        if ($status == true) {
            return $remain_token;
        } else {
            return -1;
        }
    }

    function x_week_range($date) {
        $ts = strtotime($date);
        $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', strtotime('next saturday', $start));

        $a = [$start_date, $end_date];

        return $a;
    }

    public function old_reschedule($appointment_id = '') {
        $this->template->title = 'Reschedule Appointment';

        // checking if appointment has already rescheduled
        $appointment_reschedule_data = $this->appointment_reschedule_model->select('id')->where('appointment_id', $appointment_id)->get();
        if ($appointment_reschedule_data) {
            $this->messages->add('apppointment has already rescheduled', 'danger');
            redirect('student/upcoming_session');
        }

        $appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, status')->where('id', $appointment_id)->where('student_id', $this->auth_manager->userid())->get();

        if (!$appointment_data) {
            $this->messages->add('No Appointment Found', 'danger');
            redirect('student/upcoming_session');
        }

        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id', $appointment_data->coach_id)->get_all();

        if (!$schedule_data) {
            redirect('student/upcoming_session');
        }
        $coach_name = $this->identity_model->get_identity('profile')->select('fullname')->where('user_id', $appointment_data->coach_id)->get();
        $vars = array(
            'appointment_id' => $appointment_id,
            'coach_id' => $appointment_data->coach_id,
            'coach_name' => $coach_name->fullname,
        );
        $this->template->content->view('default/contents/manage_appointment/reschedule/schedule_detail', $vars);

        //publish template
        $this->template->publish();
    }

    public function reschedule_session($appointment_id = '', $coach_id = '', $date_ = '') {
        if (!$date_ || !$coach_id) {
            $vars = array();
            $this->template->content->view('default/contents/manage_appointment/reschedule/availability', $vars);

            //publish template
            $this->template->publish();
        }

        if (!$this->is_date_available(trim($date_), 2)) {
            $vars = array();
            $this->template->content->view('default/contents/manage_appointment/reschedule/availability', $vars);

            //publish template
            $this->template->publish();
        }

        if ($this->is_day_off($coach_id, $date_)) {
            $vars = array();
            $this->template->content->view('default/contents/manage_appointment/reschedule/availability', $vars);

            //publish template
            $this->template->publish();
        }

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
        $appointment_student = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', date("Y-m-d", $date))->get_all();
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
                    // checking if availability is existed in the appointment
                    if (in_array($availability_exist['start_time'], $appointment_start_time_temp) && in_array($availability_exist['end_time'], $appointment_end_time_temp)) {
                        // no action
                    } else {
                        // storing availability that still active and not been boooked yet
                        if($this->isValidAppointment($availability_exist['start_time'], $availability_exist['end_time'], $appointment_start_time_temp, $appointment_end_time_temp)){
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

        $vars = array(
            'appointment_id' => $appointment_id,
            'availability' => $availability_temp,
            'coach_id' => $coach_id,
            'date' => $date,
        );
        $this->template->content->view('default/contents/manage_appointment/reschedule/availability', $vars);

        //publish template
        $this->template->publish();
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
                } else if (strtotime($schedule_temp2) > strtotime($s1['start_time']) && strtotime($schedule_temp) < strtotime($s1['end_time'])) {
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
        // if(date("H:i", strtotime(1 . 'minutes', strtotime($time))) != '00:00' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '01'){
        if(date("H:i", strtotime(1 . 'minutes', strtotime($time))) != '00:00' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '01' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '11' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '21' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '31' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '41' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '51' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '06' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '16' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '26' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '36' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '46' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '56'){
            return date("H:i", strtotime(1 . 'minutes', strtotime($time)));
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
//        $data =  ($this->partner_model->select('id, session_per_block_by_partner, session_per_block_by_admin')->where('id', $partner_id)->get());
//        if(!$data->session_per_block_by_admin){
//            return $data->session_per_block_by_partner;
//        }
//        else{
//            return $data->session_per_block_by_admin;
//        }
        //$setting = $this->partner_setting_model->get();
        $setting = $this->specific_settings_model->get_partner_settings($partner_id);
        $set_setting = $setting[0]->session_duration+5;
        return $set_setting;
        //return $setting->session_duration;
    }

    public function reschedule_booking($appointment_id = '', $coach_id = '', $date = '', $start_time = '', $end_time = '') {
        
        $get_name_student = $this->db->select('fullname')->from('user_profiles')->where('user_id',$this->auth_manager->userid())->get()->result();
        $get_email_student = $this->db->select('email')->from('users')->where('id',$this->auth_manager->userid())->get()->result();
        $get_name_coach = $this->db->select('fullname')->from('user_profiles')->where('user_id',$coach_id)->get()->result();
        $get_email_coach = $this->db->select('email')->from('users')->where('id',$coach_id)->get()->result();

        $name_student = $get_name_student[0]->fullname;
        $email_student = $get_email_student[0]->email;
        $name_coach = $get_name_coach[0]->fullname;
        $email_coach = $get_email_coach[0]->email;


        $convert = $this->convertBookSchedule(-($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes), strtotime($date), $start_time, $end_time);
        $date = $convert['date'];
        $start_time = $convert['start_time'];
        $end_time = $convert['end_time'];
        
        $appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, start_time, end_time')->where('id', $appointment_id)->where('student_id', $this->auth_manager->userid())->get();
        if (!$appointment_data) {
            $this->messages->add('Invalid Appointment', 'danger');
            redirect('student/upcoming_session');
        }

        $appointment_data_student = $this->schedule_function->convert_book_schedule(($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes), strtotime($appointment_data->date), $appointment_data->start_time, $appointment_data->end_time);
        $appointment_data_coach = $this->schedule_function->convert_book_schedule(($this->identity_model->new_get_gmt($coach_id)[0]->minutes), strtotime($appointment_data->date), $appointment_data->start_time, $appointment_data->end_time);

        // Retrieve post
        $booked = array(
            'appointment_id' => $appointment_id,
            'old_coach_id' => $appointment_data->coach_id,
            'user_id_reschedule' => $this->auth_manager->userid(),
            'date' => $appointment_data->date,
            'start_time' => $appointment_data->start_time,
            'end_time' => $appointment_data->end_time,
            'status' => 'active',
        );


        $isValid = $this->isAvailable($coach_id, $date, $start_time, $end_time);
        // echo "<pre>";
        // print_r($booked);
        // exit();

        // desiscion maker if available time is valid
        $this->db->trans_begin();
        if ($isValid) {
            // Inserting and checking
            // exit('bouya');
            $insert_appointment_reschedule = $this->db->insert('appointment_reschedules',$booked);
            if (!$insert_appointment_reschedule) {
                $this->db->trans_rollback();
                $this->messages->add('Invalid Action', 'danger');
                // exit('ba');
                $this->index();
                return;
            }
        } else {
            $this->messages->add('Invalid Action', 'danger');
            redirect('student/upcoming_session');
        }

        // exit('hai');
        // updating appointment current status to reschedule
        $appointment_update = array(
            'status' => 'reschedule',
            'coach_id' => $coach_id,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'date' => $date
        );

        ///////////////////////////////////////////////////////////////////////////////////
        //WEBEX
        ///////////////////////////////////////////////////////////////////////////////////
        // $webex_host = $this->webex_host_model->get_host($appointment_id);
        // $webex = $this->webex_model->select('id')->where('appointment_id', $appointment_id)->get();
        
        // if($webex_host && $webex){
            // delete session in webex
            // delete session from table webex
            // if(!$this->delete_session($webex_host[0]->id, $appointment_id) || !$this->webex_model->delete($webex->id)){
            //     $this->db->trans_rollback();
            //     $this->messages->add('Invalid Session', 'error');
            //     redirect('student/upcoming_session');
            // }
        // }
        
        // update table appointment
        // $appointment_id = (int)$appointment_id;
        // echo $appointment_id;
        // exit();
        // $update_appointment = $this->db->where('id',$appointment_id)->update('appointments',$appointment_update);
        // print_r($update_appointment);
        // exit();
        if (!$update_appointment) {
            // $this->db->trans_rollback();
            
            // $this->messages->add('Invalid Action', 'danger');
            // redirect('student/upcoming_session');
        } else {
            // if student reschedule the session 2 days/ 48 hours before the sessioin start, student will get the token back 100%
            // $student_token_data = $this->identity_model->get_identity('token')->where('user_id', $this->auth_manager->userid())->get();
            $coach_token_cost = $this->coach_token_cost_model->select('id, token_for_student')->where('coach_id', $appointment_data->coach_id)->get();
            $time_left_before_session = $this->time_reminder_before_session($appointment_data->date . ' ' . $appointment_data->start_time, (2 * 24 * 60 * 60));

            // if ($time_left_before_session > 0) {
            //     $student_token = $student_token_data->token_amount + $coach_token_cost->token_for_student;
            //     $token_update = array(
            //         'token_amount' => $student_token,
            //     );

            //     if (!$this->identity_model->get_identity('token')->update($student_token_data->id, $token_update)) {
            //         $this->messages->add(validation_errors(), 'danger');
            //         exit('error1');
            //         $this->index();
            //         return;
            //     }

            //     if (!$this->create_token_history($appointment_id, $coach_token_cost->token_for_student, $student_token, 9)) {
            //         $this->messages->add('Error while create token history', 'danger');
            //         exit('error2');
            //         $this->index();
            //         return;
            //     }
            // } else {
            //     if (!$this->create_token_history($appointment_id, $coach_token_cost->token_for_student, $student_token_data->token_amount, 7)) {
            //         $this->messages->add('Error while create token history', 'danger');
            //         exit('error3');
            //         $this->index();
            //         return;
            //     }
            // }
        }

        // exit('hos');
        $day = strtolower(date('l', $date));
        $schedule_data = $this->schedule_model->select('id')->where('user_id', $coach_id)->where('day', $day)->get();
        $new_appointment = array(
            'student_id' => $this->auth_manager->userid(),
            'coach_id' => $coach_id,
            'schedule_id' => $schedule_data->id,
            'date' => date("Y-m-d", $date),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => 'reschedule',
        );

        // inserting new appointment to appointment table
        // $new_appointment_id = $this->appointment_model->insert($new_appointment);

        $new_update_appointment_id = $this->db->where('id',$appointment_id)->update('appointments',$new_appointment);

        // if (!$new_appointment_id) {
        //     $this->db->trans_rollback();
        //     $this->messages->add('Invalid Action', 'danger');
        //     $this->index();
        //     return;
        // } else {
            // if student reschedule the session 2 days/ 48 hours before the sessioin start, student will get the token back 100%
            $student_token_data = $this->identity_model->get_identity('token')->where('user_id', $this->auth_manager->userid())->get();
            $coach_token_cost = $this->coach_token_cost_model->select('id, token_for_student')->where('coach_id', $appointment_data->coach_id)->get();

            // $student_token = $student_token_data->token_amount - $coach_token_cost->token_for_student;
            // $token_update = array(
            //     'token_amount' => $student_token,
            // );

            // if (!$this->identity_model->get_identity('token')->update($student_token_data->id, $token_update)) {
            //     $this->messages->add(validation_errors(), 'danger');
            //     $this->index();
            //     return;
            // }

            // if (!$this->create_token_history($appointment_id, $coach_token_cost->token_for_student, $student_token, 1)) {
            //     $this->messages->add('Error while create token history', 'danger');
            //     $this->index();
            //     return;
            // }
        // }
        $this->db->trans_commit();

        // after student rescheduled an appointment, send email to coach
        // tube name for messaging action
        // send email notification

        // messaging
        // inserting notification
        // if ($new_appointment_id) {
            $database_tube = 'com.live.database';

            // student notification data

            $student_notification = array(
                'user_id' => $this->auth_manager->userid(),
                'description' => 'You have a rescheduled session with ' . $name_coach,
                'status' => 2,
                'dcrea' => time(),
                'dupd' => time()
            );

            // coach notification data
            $coach_notification = array(
                'user_id' => $coach_id,
                'description' => 'You have a rescheduled session with ' . $name_student,
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

            // messaging inserting data notification
            $this->user_notification_model->insert($student_notification);
            $this->user_notification_model->insert($coach_notification);

            
        $new_appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, start_time, end_time')->where('id', $appointment_id)->where('student_id', $this->auth_manager->userid())->get();
        $new_appointment_data_student = $this->schedule_function->convert_book_schedule(($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes), strtotime($new_appointment_data->date), $new_appointment_data->start_time, $new_appointment_data->end_time);
        $new_appointment_data_coach = $this->schedule_function->convert_book_schedule(($this->identity_model->new_get_gmt($coach_id)[0]->minutes), strtotime($new_appointment_data->date), $new_appointment_data->start_time, $new_appointment_data->end_time);
        
        // student

        $gmt_student = $this->identity_model->new_get_gmt($this->auth_manager->userid());
        $st = strtotime($new_appointment_data_coach['start_time']);
        $usertime1 = $st;
        $start_hour = date("H:i", $usertime1);

        $et = strtotime($new_appointment_data_coach['end_time']);
        $usertime2 = $et-(5*60);
        $end_hour = date("H:i", $usertime2);

        $old_st = strtotime($appointment_data_coach['start_time']);
        $old_usertime1 = $old_st;
        $old_start_hour = date("H:i", $old_usertime1);

        $old_et = strtotime($appointment_data_coach['end_time']);
        $old_et_student = $old_et-(5*60);
        $et_student = date("H:i", $old_et_student);

        // coach

        $gmt_coach = $this->identity_model->new_get_gmt($coach_id);
        $st_coach = strtotime($new_appointment_data_student['start_time']);
        $usertime1_coach = $st_coach;
        $start_hour_coach = date("H:i", $usertime1_coach);

        $et_coach = strtotime($new_appointment_data_student['end_time']);
        $usertime2_coach = $et_coach-(5*60);
        $end_hour_coach = date("H:i", $usertime2_coach);

        $old_st_coach = strtotime($appointment_data_student['start_time']);
        $old_usertime1_coach = $old_st_coach;
        $old_start_hour_coach = date("H:i", $old_usertime1_coach);

        $old_et_coach = strtotime($appointment_data_student['end_time']);
        $old_et_coach_convert = $old_et_coach-(5*60);
        $et_coach = date("H:i", $old_et_coach_convert);

        $student_gmt = $gmt_student[0]->gmt;
        $coach_gmt = $gmt_coach[0]->gmt;

        $this->send_email->student_reschedule($email_coach, $name_student, $name_coach, date('Y-m-d', strtotime($appointment_data->date)), $appointment_data_coach['start_time'], $appointment_data_coach['end_time'], date('Y-m-d', $new_appointment_data_coach['date']), $start_hour, $end_hour, $coach_gmt);
        $this->send_email->notif_student_reschedule($email_student, $name_student, $name_coach, date('Y-m-d', strtotime($appointment_data->date)), $appointment_data_student['start_time'], $appointment_data_student['end_time'], date('Y-m-d', $new_appointment_data_student['date']), $start_hour_coach, $end_hour_coach, $student_gmt);
             // messaging inserting data notification
            // $this->queue->push($database_tube, $data_student, 'database.insert');
            // $this->queue->push($database_tube, $data_coach, 'database.insert');
        // }

        // $available_host = $this->webex_host_model->get_available_host($new_appointment_id);
        // if($available_host){
        //     $this->create_session($available_host[0]->id, $new_appointment_id);
        //     $message = "Session Rescheduled, you will use Webex for your session";
        // }else{
        //     $message = "Session Rescheduled, you will use Skype for your session";
        // }
        $message = "Session Rescheduled";
        $this->messages->add($message, 'success');
        redirect('student/upcoming_session');
    }


    public function cancel($appointment_id = '') {
        // checking if appointment has already cancelled
        $appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, start_time, end_time, status')->where('id', $appointment_id)->get();
        if ($appointment_data->status == 'cancel') {
            $this->messages->add('Appointment has already cancelled', 'danger');
            redirect('student/upcoming_session');
        }

        // updating appointment (change status to cancel)
        // storing data
        $appointment = array(
            'status' => 'cancel',
        );
        
        ////////////////////////////////////////////////////////////////////////
        // WEBEX
        ////////////////////////////////////////////////////////////////////////
        $webex_host = $this->webex_host_model->get_host($appointment_id);
        $webex = $this->webex_model->select('id')->where('appointment_id', $appointment_id)->get();
        
        $this->db->trans_begin();
        if($webex_host && $webex){
            // delete session in webex
            // delete session from table webex
            if(!$this->delete_session($webex_host[0]->id, $appointment_id) || !$this->webex_model->delete($webex->id)){
                $this->db->trans_rollback();
                $this->messages->add('Error while deleting session in webex', 'error');
                redirect('student/upcoming_session');
            }
        }
        print_r($appointment);
        exit();
        // Updating and checking to appoinment table
        if (!$this->appointment_model->update($appointment_id, $appointment)) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->index();
            return;
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
                $this->messages->add(validation_errors(), 'danger');
                $this->index();
                return;
            }

            if (!$this->create_token_history($appointment_id, $coach_token_cost->token_for_student, $student_token, 5)) {
                $this->messages->add('Error while create token history', 'danger');
                $this->index();
                return;
            }
        } else {
            if (!$this->create_token_history($appointment_id, $coach_token_cost->token_for_student, $student_token_data->token_amount, 7)) {
                $this->messages->add('Error while create token history', 'danger');
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
            //'content' => 'Your appointment with student ' . $id_to_name[$this->auth_manager->userid()] . ' at ' . date("l jS \of F Y", strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' has been cancelled',
        );
        $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Appointment Cancelled')
                .$this->email_structure->content('Your appointment with student ' . $id_to_name[$this->auth_manager->userid()] . ' at ' . date("l jS \of F Y", strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' has been cancelled')
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');


        $this->queue->push($tube, $data, 'email.send_email');

        //messaging for notification
        $database_tube = 'com.live.database';
        $coach_notification = array(
            'user_id' => $appointment_data->coach_id,
            'description' => 'Your appointment with student ' . $id_to_name[$this->auth_manager->userid()] . ' at ' . date("l jS \of F Y", strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' has been cancelled.',
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
        redirect('student/upcoming_session');
    }

    public function time_reminder_before_session($session_time, $delay_time) {
        if (DateTime::createFromFormat('Y-m-d H:i:s', trim($session_time)) !== FALSE) {
            $now = (date('Y-m-d H:i:s', time() + $delay_time));
            return (((strtotime($session_time) - strtotime($now))) < 0 ? FALSE : (strtotime($session_time) - strtotime($now)));
        } else {
            return FALSE;
        }
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

    private function create_token_history($appointment_id = '', $coach_cost = '', $remain_token = '', $status = '') {
        $appointment = $this->appointment_model->get_appointment($appointment_id);

        if (!$appointment) {
            $this->messages->add('Invalid apppointment id', 'danger');
            redirect('student/find_coaches/single_date');
        }
        $token_history = array(
            'user_id' => $this->auth_manager->userid(),
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
    
    private function isValidAppointment($start_time = '', $end_time = '', $start_time_temp = '', $end_time_temp = ''){
        $status = true;
        for($i=0;$i<count($start_time_temp);$i++){
            //DateTime::createFromFormat('H:i:s', $start_time_temp[$i])
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
    
    public function create_session($host_id = '', $meeting_identifier = '') {
        $webex_host = $this->webex_host_model->select('subdomain_webex, partner_id, webex_id, timezones, password')->where('id', $host_id)->get();

        if (!$webex_host || !$meeting_identifier) {
            $this->messages->add('Invalid Host ID or Meeting Identifier', 'error');
            redirect('coach/upcoming_session');
        }

        // Input attendance/s
        if (substr($meeting_identifier, 0, 1) == 'c') {
            $appointment = $this->class_member_model->get_appointment_for_webex_invitation_xml($meeting_identifier);
            $meeting = $this->class_meeting_day_model->select('*')->where('id', substr($meeting_identifier, 1))->get();

            if (!$appointment || !$meeting) {
                $this->messages->add('Invalid Meeting Identifier', 'error');
                return FALSE;
            }
            foreach ($appointment as $a) {
                $attendance .= htmlspecialchars("<attendee><person><name>$a->student_fullname</name><email>$a->student_email</email></person><role>ATTENDEE</role><joinStatus>INVITE</joinStatus></attendee>");
            }
            $conf_name = "Class " . $appointment[0]->class_name . " on " . $appointment[0]->date . " at " . $appointment[0]->start_time . " " . $appointment[0]->end_time . " With Coach " . $appointment[0]->coach_fullname;
            $max_user = 1;
            $duration = 20;
        } else {
            $appointment = $this->appointment_model->get_appointment_for_webex_invitation_xml($meeting_identifier);
            $meeting = $this->appointment_model->select('*')->where('id', $meeting_identifier)->get();

            if (!$appointment) {
                $this->messages->add('Invalid Meeting Identifier', 'error');
                return FALSE;
            }
            $attendance = htmlspecialchars("<attendee><person><name>{$appointment[0]->student_fullname}</name><email>{$appointment[0]->student_email}</email></person><role>ATTENDEE</role><joinStatus>INVITE</joinStatus></attendee>");
            $conf_name = "Student " . $appointment[0]->student_fullname . " on " . $appointment[0]->date . " at " . $appointment[0]->start_time . " " . $appointment[0]->end_time . " With Coach " . $appointment[0]->coach_fullname;
            $max_user = 1;
            $duration = 20;
        }

        $attendance = htmlspecialchars_decode($attendance);
        $password = $this->common_function->generate_random_string(6);
        $date = str_replace('-', '/', substr($appointment[0]->date, 5) . '-' . (substr($appointment[0]->date, 0, 4))) . ' ' . $appointment[0]->start_time;

        $XML_SITE = $webex_host->subdomain_webex . ".webex.com";
        $XML_PORT = "443";

        // Set calling user information
        $d["UID"] = $webex_host->webex_id; // WebEx username
        $d["PWD"] = $webex_host->password; // WebEx password
        $d["SNM"] = $webex_host->subdomain_webex; //Demo Site SiteName
        $d["PID"] = $webex_host->partner_id; //Demo Site PartnerID

        $d["XML"] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <serv:message xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
                xmlns:serv=\"http://www.webex.com/schemas/2002/06/service\">
                <header>
                    <securityContext>
                    <webExID>{$d["UID"]}</webExID>
                    <password>{$d["PWD"]}</password>
                    <siteName>{$d["SNM"]}</siteName>
                    <partnerID>{$d["PID"]}</partnerID>
                    </securityContext>
                </header>
                <body>
                <bodyContent xsi:type=\"java:com.webex.service.binding.meeting.CreateMeeting\"
                    xmlns:meet=\"http://www.webex.com/schemas/2002/06/service/meeting\">    
                    <accessControl>
                        <meetingPassword>{$password}</meetingPassword>
                    </accessControl>
                    <metaData>
                        <confName>{$conf_name}</confName>
                        <agenda>Test</agenda>
                    </metaData>
                    <participants>
                        <maxUserNumber>4</maxUserNumber>
                        <attendees>
                            {$attendance}
                        </attendees>
                    </participants>
                    <enableOptions>
                        <chat>true</chat>
                        <poll>true</poll>
                        <voip>true</voip>
                        <audioVideo>true</audioVideo>
                        <attendeeList>true</attendeeList>
                        <chatHost>true</chatHost>
                        <chatPresenter>true</chatPresenter>
                        <chatAllAttendees>true</chatAllAttendees>
                        <meetingRecord>true</meetingRecord>
                        <autoDeleteAfterMeetingEnd>false</autoDeleteAfterMeetingEnd>
                    </enableOptions>
                    <schedule>
                        <startDate>{$date}</startDate>
                        <joinTeleconfBeforeHost>false</joinTeleconfBeforeHost>
                        <duration>{$duration}</duration>
                        <timeZoneID>{$webex_host->timezones}</timeZoneID>
                    </schedule>
                    <telephony>
                        <telephonySupport>NONE</telephonySupport>
                    </telephony>
                <attendeeOptions>
                    <emailInvitations>true</emailInvitations>
                </attendeeOptions>
                </bodyContent>
                </body>
                </serv:message>";

        $URL = "http://{$XML_SITE}/WBXService/XMLService";
        $result = $this->webex_function->post_it($d, $URL, $XML_PORT);

        // Clean xml
        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
        $simple_xml = simplexml_load_string($clean_result);
        if ($simple_xml === false) {
            $this->messages->add('Bad xml', 'error');
            return FALSE;
        } else {
            if ($simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->result == 'SUCCESS') {
                $tube = 'com.live.database';
                if (substr($meeting_identifier, 0, 1) == 'c') {
                    $data = Array(
                        'class_meeting_id' => substr($meeting_identifier, 1),
                        'host_id' => $host_id,
                        'webex_meeting_number' => $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('meet', true)->meetingkey,
                        'status' => 'SCHE',
                        'password' => $password
                    );
                    if ($this->webex_class_model->insert($data)) {
                        $student_emails = $this->class_member_model->get_appointment_for_webex_invitation($meeting_identifier);
                        if ($student_emails) {
                            foreach ($student_emails as $se) {
                                $student_notification [] = array(
                                    'user_id' => $se->student_id,
                                    'description' => 'You just invited by ' . $se->coach_name . ' to join a WebEx Meeting on ' . $se->date . ' at ' . $se->start_time . ' until ' . $se->end_time . '. Check your email to see the detail invitation!',
                                    'status' => 2,
                                    'dcrea' => time(),
                                    'dupd' => time(),
                                );
                            }

                            // IMPORTANT : array index in content must be in mutual with table field in database
                            foreach ($student_notification as $sn) {
                                $data = array(
                                    'table' => 'user_notifications',
                                    'content' => $sn
                                );
                                // messaging inserting data notification
                                $this->queue->push($tube, $data, 'database.insert');
                            }
                        }else{
                            return false;
                        }
                        return true;
                    }else{
                        return false;
                    }
                } else {
                    $data = Array(
                        'meeting_type' => '11', //Standard Meeting Center
                        'number_attendance' => '1',
                        'appointment_id' => $meeting_identifier,
                        'host_id' => $host_id,
                        'webex_meeting_number' => $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('meet', true)->meetingkey,
                        'status' => 'SCHE',
                        'password' => $password
                    );
                    if ($this->webex_model->insert($data)) {
                        $student_emails = $this->appointment_model->get_appointment_for_webex_invitation($meeting_identifier);
                        if ($student_emails) {
                            $student_notification = array(
                                'user_id' => $student_emails[0]->student_id,
                                'description' => 'You just invited by ' . $student_emails[0]->coach_name . ' to join a WebEx Meeting on ' . $student_emails[0]->date . ' at ' . $student_emails[0]->start_time . ' until ' . $student_emails[0]->end_time . '. Check your email to see the detail invitation!',
                                'status' => 2,
                                'dcrea' => time(),
                                'dupd' => time(),
                            );
                            $data = array(
                                'table' => 'user_notifications',
                                'content' => $student_notification
                            );
                            // messaging inserting data notification
                            $this->queue->push($tube, $data, 'database.insert');
                        }
                        return true;
                    }else{
                        return false;
                    }
                }
            } else {
                return false;
            }
        }
    }

}
