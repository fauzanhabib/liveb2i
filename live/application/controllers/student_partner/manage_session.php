<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class manage_session extends MY_Site_Controller {

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

        // for messaging action and timing
        $this->load->library('phpass');
        $this->load->library('queue');
        $this->load->library('webex_function');
        $this->load->library('email_structure');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'SPR') {
            $this->messages->add('Access Denied', 'warning');
            redirect('account/identity/profile');
        }
    }

    public function reschedule($student_id = '', $appointment_id = '') {
        $this->template->title = 'Reschedule Appointment';

        // checking if appointment has already rescheduled
        $appointment_reschedule_data = $this->appointment_reschedule_model->select('id')->where('appointment_id', $appointment_id)->get();
        if ($appointment_reschedule_data) {
            $this->messages->add('apppointment has already rescheduled', 'warning');
            redirect('student_partner/schedule/manage/'.$appointment_id);
        }

        $appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, status')->where('id', $appointment_id)->where('student_id', $student_id)->get();
        if (!$appointment_data) {
            $this->messages->add('No Appointment Found', 'warning');
            redirect('student_partner/schedule/manage/'.$student_id);
        }

        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id', $appointment_data->coach_id)->get_all();

        if (!$schedule_data) {
            redirect('student_partner/manage_session/reschedule/'.$student_id.'/'.$appointment_id);
        }
        $coach_name = $this->identity_model->get_identity('profile')->select('fullname')->where('user_id', $appointment_data->coach_id)->get();
        $vars = array(
            'appointment_id' => $appointment_id,
            'student_id' => $student_id,
            'coach_id' => $appointment_data->coach_id,
            'coach_name' => $coach_name->fullname,
        );
        $this->template->content->view('default/contents/student_partner/manage_session/reschedule/schedule_detail', $vars);

        //publish template
        $this->template->publish();
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
        if (!$this->is_date_available(trim($date_), 2)) {
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
        $minutes = $this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes;
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
                                date_default_timezone_set('Etc/GMT'.(-$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60));
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
        

        $vars = array(
            'appointment_id' => $appointment_id,
            'availability' => $availability_temp,
            'coach_id' => $coach_id,
            'date' => $date,
        );
        //echo('<pre>');print_r($vars);exit;
        $this->template->content->view('default/contents/manage_appointment/reschedule/availability', $vars);

        //publish template
        $this->template->publish();
    }

    public function reschedule_booking($student_id = '', $appointment_id = '', $coach_id = '', $date = '', $start_time = '', $end_time = '') {
        $convert = $this->convertBookSchedule(-($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes), strtotime($date), $start_time, $end_time);
        $date = $convert['date'];
        $start_time = $convert['start_time'];
        $end_time = $convert['end_time'];
//        $this->db->trans_rollback();
//        echo('<pre>');echo(date('Y-m-d',$convert['date'])); print_r($convert); exit;
        $appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, start_time, end_time')->where('id', $appointment_id)->where('student_id', $student_id)->get();
        if (!$appointment_data) {
            $this->messages->add('Invalid Appointment', 'warning');
            redirect('student_partner/schedule');
        }

        // Retrieve post
        $booked = array(
            'appointment_id' => $appointment_id,
            'date' => date('Y-m-d', $date),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => 'active',
        );

        $isValid = $this->isAvailable($student_id, $coach_id, $date, $start_time, $end_time);
        
        // desiscion maker if available time is valid
        $this->db->trans_begin();
        if ($isValid) {
            // Inserting and checking
            if (!$this->appointment_reschedule_model->insert($booked)) {
                $this->db->trans_rollback();
                $this->messages->add('Invalid Action', 'warning');
                $this->index();
                return;
            }
        } else {
            $this->messages->add('Invalid Action', 'warning');
            redirect('student_partner/schedule');
        }
        
        // updating appointment current status to reschedule
        $appointment_update = array(
            'status' => 'reschedule',
        );

        ///////////////////////////////////////////////////////////////////////////////////
        //DELETE WEBEX
        ///////////////////////////////////////////////////////////////////////////////////
        $webex_host = $this->webex_host_model->get_host($appointment_id);
        $webex = $this->webex_model->select('id')->where('appointment_id', $appointment_id)->get();
        
        if($webex_host && $webex){
            // delete session in webex
            // delete session from table webex
            if(!$this->webex_function->delete_session($webex_host[0]->id, $appointment_id) || !$this->webex_model->delete($webex->id)){
                $this->db->trans_rollback();
                $this->messages->add('Invalid Session', 'error');
                redirect('student_partner/manage_session/reschedule/'.@$student_id.'/'.@$appointment_id);
            }
        }
        
        // update table appointment
        if (!$this->appointment_model->update($appointment_id, $appointment_update)) {
            $this->db->trans_rollback();
            $this->messages->add('Invalid Action', 'warning');
            redirect('student_partner/schedule');
        } else {
            // if student reschedule the session 2 days/ 48 hours before the sessioin start, student will get the token back 100%
            $student_token_data = $this->identity_model->get_identity('token')->where('user_id', $student_id)->get();
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

                if (!$this->create_token_history($student_id, $appointment_id, $coach_token_cost->token_for_student, $student_token, 9)) {
                    $this->messages->add('Error while create token history', 'warning');
                    $this->index();
                    return;
                }
            } else {
                if (!$this->create_token_history($student_id, $appointment_id, $coach_token_cost->token_for_student, $student_token_data->token_amount, 7)) {
                    $this->messages->add('Error while create token history', 'warning');
                    $this->index();
                    return;
                }
            }
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
            'status' => 'active',
        );
//$this->db->trans_rollback();
//        echo('<pre>');echo(date('Y-m-d',$convert['date'])); print_r($new_appointment); exit;
        // inserting new appointment to appointment table
        $new_appointment_id = $this->appointment_model->insert($new_appointment);
        if (!$new_appointment_id) {
            $this->db->trans_rollback();
            $this->messages->add('Invalid Action', 'warning');
            $this->index();
            return;
        } else {
            // if student reschedule the session 2 days/ 48 hours before the sessioin start, student will get the token back 100%
            $student_token_data = $this->identity_model->get_identity('token')->where('user_id', $student_id)->get();
            $coach_token_cost = $this->coach_token_cost_model->select('id, token_for_student')->where('coach_id', $appointment_data->coach_id)->get();

            $student_token = $student_token_data->token_amount - $coach_token_cost->token_for_student;
            $token_update = array(
                'token_amount' => $student_token,
            );

            if (!$this->identity_model->get_identity('token')->update($student_token_data->id, $token_update)) {
                $this->messages->add(validation_errors(), 'warning');
                $this->index();
                return;
            }

            if (!$this->create_token_history($student_id, $appointment_id, $coach_token_cost->token_for_student, $student_token, 1)) {
                $this->messages->add('Error while create token history', 'warning');
                $this->index();
                return;
            }
        }
        $this->db->trans_commit();

        $data_appointment = $this->appointment_model->where('id', $appointment_id)->get();
        
        $convert_data_student1 = $this->schedule_function->convert_book_schedule(($this->identity_model->get_gmt($student_id)[0]->minutes), strtotime($appointment_data->date), $appointment_data->start_time, $appointment_data->end_time);
        $convert_data_coach1 = $this->schedule_function->convert_book_schedule(($this->identity_model->get_gmt($coach_id)[0]->minutes), strtotime($appointment_data->date), $appointment_data->start_time, $appointment_data->end_time);
        $convert_data_student2 = $this->schedule_function->convert_book_schedule(($this->identity_model->get_gmt($student_id)[0]->minutes), strtotime($new_appointment['date']), $new_appointment['start_time'], $new_appointment['end_time']);
        $convert_data_coach2 = $this->schedule_function->convert_book_schedule(($this->identity_model->get_gmt($coach_id)[0]->minutes), strtotime($new_appointment['date']), $new_appointment['start_time'], $new_appointment['end_time']);
        $gmt_student = $this->identity_model->get_gmt($student_id)[0]->timezone;
        $gmt_coach = $this->identity_model->get_gmt($coach_id)[0]->timezone;
        //print_r($convert_data_student1); exit;
        // after student rescheduled an appointment, send email to coach
        // tube name for messaging action
        $tube = 'com.live.email';
        $id_to_email_address = $this->user_model->dropdown('id', 'email');
        $id_to_name = $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname');
        $data_student = array(
            'subject' => 'Appointment Rescheduled',
            'email' => $id_to_email_address[$student_id],
            ///'content' => 'Rescheduled appointment by Partner Admin at ' . date('l jS \of F Y', $convert_data_student1['date']) . ' from ' . $convert_data_student1['start_time'] . ' until ' . $convert_data_student1['end_time'] . ' with ' . $id_to_name[$new_appointment['coach_id']] . ' to ' . date('l jS \of F Y', $convert_data_student2['date']) . ' from ' . $convert_data_student2['start_time'] . ' until ' . $convert_data_student2['end_time'] . ' '.$gmt_student.'.',
        );
        $data_student['content'] = $this->email_structure->header()
                .$this->email_structure->title('Appointment Rescheduled')
                .$this->email_structure->content('Rescheduled appointment by Partner Admin at ' . date('l jS \of F Y', $convert_data_student1['date']) . ' from ' . $convert_data_student1['start_time'] . ' until ' . $convert_data_student1['end_time'] . ' with ' . $id_to_name[$new_appointment['coach_id']] . ' to ' . date('l jS \of F Y', $convert_data_student2['date']) . ' from ' . $convert_data_student2['start_time'] . ' until ' . $convert_data_student2['end_time'] . ' '.$gmt_student.'.')
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');

        $data_coach = array(
            'subject' => 'Appointment Rescheduled',
            'email' => $id_to_email_address[$coach_id],
            //'content' => 'Your appointment at ' . date("l jS \of F Y", $convert_data_coach1['date']) . ' from ' . $convert_data_coach1['start_time'] . ' until ' . $convert_data_coach1['end_time'] . ' with student ' . $id_to_name[$new_appointment['student_id']] . ' has been rescheduled to ' . date("l jS \of F Y", $convert_data_coach2['date']) . ' from ' . $convert_data_coach2['start_time'] . ' until ' . $convert_data_coach2['end_time'] . ' '.$gmt_coach.'.',
        );
        $data_coach['content'] = $this->email_structure->header()
                .$this->email_structure->title('Appointment Rescheduled')
                .$this->email_structure->content('Your appointment at ' . date("l jS \of F Y", $convert_data_coach1['date']) . ' from ' . $convert_data_coach1['start_time'] . ' until ' . $convert_data_coach1['end_time'] . ' with student ' . $id_to_name[$new_appointment['student_id']] . ' has been rescheduled to ' . date("l jS \of F Y", $convert_data_coach2['date']) . ' from ' . $convert_data_coach2['start_time'] . ' until ' . $convert_data_coach2['end_time'] . ' '.$gmt_coach.'.')
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');

        $this->queue->push($tube, $data_student, 'email.send_email');
        $this->queue->push($tube, $data_coach, 'email.send_email');

        // messaging
        // inserting notification
        if ($new_appointment_id) {
            $database_tube = 'com.live.database';

            // student notification data
            $student_notification['user_id'] = $student_id;
            $student_notification['description'] = 'Rescheduled appointment by Partner Admin at ' . date('l jS \of F Y', $convert_data_student1['date']) . ' from ' . $convert_data_student1['start_time'] . ' until ' . $convert_data_student1['end_time'] . ' with ' . $id_to_name[$new_appointment['coach_id']] . ' to ' . date('l jS \of F Y', $convert_data_student2['date']) . ' from ' . $convert_data_student2['start_time'] . ' until ' . $convert_data_student2['end_time'] . ' '.$gmt_student.'.';
            $student_notification['status'] = 2;
            $student_notification['dcrea'] = time();
            $student_notification['dupd'] = time();

            // coach notification data
            $coach_notification['user_id'] = $new_appointment['coach_id'];
            $coach_notification['description'] = 'Your appointment at ' . date("l jS \of F Y", $convert_data_coach1['date']) . ' from ' . $convert_data_coach1['start_time'] . ' until ' . $convert_data_coach1['end_time'] . ' with student ' . $id_to_name[$new_appointment['student_id']] . ' has been rescheduled to ' . date("l jS \of F Y", $convert_data_coach2['date']) . ' from ' . $convert_data_coach2['start_time'] . ' until ' . $convert_data_coach2['end_time'] . ' '.$gmt_coach.'.';
            $coach_notification['status'] = 2;
            $coach_notification['dcrea'] = time();
            $coach_notification['dupd'] = time();

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
            $this->queue->push($database_tube, $data_student, 'database.insert');
            $this->queue->push($database_tube, $data_coach, 'database.insert');
        }

        ////////////////////////////////////////
        // CREATE WEBEX
        ////////////////////////////////////////
        $available_host = $this->webex_host_model->get_available_host($new_appointment_id);
        if($available_host && $this->webex_function->create_session($available_host[0]->id, $new_appointment_id)){
            $message = "Session Rescheduled, you will use Webex for your session";
        }else{
            $message = "Session Rescheduled, you will use Skype for your session";
        }
        
        $this->messages->add($message, 'success');
        redirect('student_partner/schedule/manage/'.$student_id);
    }
    
    private function get_date_week($date = ''){
        $index = array_search(strtolower(date("l", $date)), $this->day_index);
        $date_index = array();
        for($i=0;$i<7;$i++){
            $date_index[] = date('Y-m-d', strtotime(date('Y-m-d', $date). ''. ($i-$index).' days'));
        }
        return $date_index;
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
            return false;
        } else if (!$appointment) {
            //if($appointment_count < $setting->max_session_per_day && $appointment_count_week < $setting->max_day_per_week){
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
    }

    public function cancel($student_id = '', $appointment_id = '') {
        // checking if appointment has already cancelled
        $appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, start_time, end_time, status')->where('id', $appointment_id)->get();
        if ($appointment_data->status == 'cancel') {
            $this->messages->add('Appointment has already cancelled', 'warning');
            redirect('student_partner/schedule/manage/'.$student_id);
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
                redirect('student_partner/schedule/manage/'.@$student_id);
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

        $this->messages->add('Updating Appointment Succeeded', 'success');
        redirect('student_partner/schedule/manage/'.$student_id);
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

    private function create_token_history($student_id = '', $appointment_id = '', $coach_cost = '', $remain_token = '', $status = '') {
        $appointment = $this->appointment_model->get_appointment($appointment_id);

        if (!$appointment) {
            $this->messages->add('Invalid apppointment id', 'warning');
            redirect('student_partner/manage_session/reschedule/'.$student_id.'/'.$appointment_id);
        }
        $token_history = array(
            'user_id' => $student_id,
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
        $minutes = $this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes;

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
        if(date("H:i", strtotime(1 . 'minutes', strtotime($time))) != '00:00' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '01'){
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
        $setting = $this->partner_setting_model->get();
        return $setting->session_duration;
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
}
