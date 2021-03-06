<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class find_coaches extends MY_Site_Controller {

    var $day_index = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    // Constructor
    public function __construct() {
        parent::__construct();
        
        // load models 
        $this->load->model('appointment_model');
        $this->load->model('appointment_reschedule_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('class_member_model');
        $this->load->model('coach_day_off_model');
        $this->load->model('coach_token_cost_model');
        $this->load->model('coach_rating_model');
        $this->load->model('identity_model');
        $this->load->model('offwork_model');
        $this->load->model('partner_model');
        $this->load->model('schedule_model');
        $this->load->model('token_histories_model');
        $this->load->model('user_notification_model');
        $this->load->model('webex_host_model');
        $this->load->model('webex_class_model');
        $this->load->model('webex_model');
        

        //load libraries
        $this->load->library('queue');
        $this->load->library('schedule_function');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = 'Find Coach';
        $vars = array(
            'coaches' => $this->identity_model->get_coach_identity(),
            'rating' => $this->coach_rating_model->get_average_rate()
        );
        $this->template->content->view('default/contents/find_coach/index', $vars);
        $this->template->publish();
    }

    public function detail($id = '') {
        $this->template->title = 'Find Coach';
        $vars = array(
            'coaches' => $this->identity_model->get_coach_identity($id),
        );
        $this->template->content->view('default/contents/find_coach/detail', $vars);
        $this->template->publish();
    }

    public function search($category = null) {
        $this->template->title = 'Find Coach';
        if ($category == 'name' || $category == null) {
            $coaches = $this->identity_model->get_coach_identity(null, @$this->input->post('search_key'));
        } else if ($category == 'country') {
            $coaches = $this->identity_model->get_coach_identity(null, null, @$this->input->post('search_key'));
        } else if ($category == 'availability') {
            $coaches = $this->identity_model->get_coach_identity(null, @$this->input->post('search_key'));
        } else if ($category == 'spoken_language') {
            $coaches = $this->identity_model->get_coach_identity(null, null, null, null, null, null, @$this->input->post('search_key'));
        } else {
            redirect('account/identity/detail/profile');
        }

        $vars = array(
            'coaches' => $coaches,
            'selected' => $category,
            'rating' => $this->coach_rating_model->get_average_rate()
        );

        $this->template->content->view('default/contents/find_coach/' . $category . '/index', $vars);
        $this->template->publish();
    }

    public function single_date() {
        $this->template->content->view('default/contents/find_coach/availability/single_date/index');
        $this->template->publish();
    }

    public function get_available_coach($date = '') {
        // fungsi untuk mengambil available coach berdasarkan parameter tanggal
        // akan di pakai di single date dan multiple date
        $date_ = $date;
        $coach_id = 2;
        $coach_data = $this->identity_model->get_coach_identity('', '', '', $this->auth_manager->partner_id());
        $available_coach = array();
        foreach ($coach_data as $cd) {
            $coach_id = $cd->id;
            $availability_temp = array();
            if ($this->is_date_available(trim($date_), 2) && !$this->is_day_off($coach_id, $date_) == true) {
                //getting the day of $date  
                $minutes = $this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes;
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
                    $duration = (strtotime($a['end_time']) - strtotime($a['start_time'])) / ($this->session_duration($this->auth_manager->partner_id())->session_per_block * 60);
                    if ($duration > 1) {
                        for ($i = 0; $i < $duration; $i++) {
                            $availability_exist = array(
                                // adding  minutes for every session
                                'start_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id())->session_per_block * 60) * ($i))),
                                'end_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id())->session_per_block * 60) * ($i + 1))),
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
                
            }

            if ($availability_temp) {
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


        return ($available_coach);
    }

    public function book_by_single_date($date = '') {
        $this->template->title = 'Detail Schedule';

        if ($date <= date('Y-m-d')) {
            $this->messages->add('Invalid Date', 'danger');
            redirect('student/find_coaches/single_date/');
        }

        $data = $this->get_available_coach($date);
        $vars = array(
            'data' => $data,
            'date' => $date,
            'rating' => $this->coach_rating_model->get_average_rate()
        );
        $this->template->content->view('default/contents/find_coach/book_by_availability/single_date/index', $vars);
        $this->template->publish();
    }

    public function book_single_coach($coach_id = '', $date = '', $start_time = '', $end_time = '') {
        $convert = $this->schedule_function->convert_book_schedule(-($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes), $date, $start_time, $end_time);
        $date = $convert['date'];
        $start_time = $convert['start_time'];
        $end_time = $convert['end_time'];
        try {
            // First of all, let's begin a transaction
            // A set of queries; if one fails, an exception should be thrown
            $isValid = $this->isAvailable($coach_id, $date, $start_time, $end_time);
            if ($isValid) {
                $availability = $this->isOnAvailability($coach_id, date('Y-m-d', $date));
                if (in_array(array('start_time' => $start_time, 'end_time' => $end_time), $availability)) {
                    // go to next step
                } else {
                    $this->messages->add('Invalid Time', 'danger');
                    redirect('student/find_coaches/single_date/');
                }
                // begin the transaction to ensure all data created or modified structural
                $token_cost = $this->coach_token_cost_model->select('token_for_student')->where('coach_id', $coach_id)->get();
                // updating remaining token student
                $remain_token = $this->update_token($token_cost->token_for_student);
                if ($this->db->trans_status() === true && $remain_token >= 0 && $this->isAvailable($coach_id, $date, $start_time, $end_time)) {
                    $appointment_id = $this->create_appointment($coach_id, $date, $start_time, $end_time, 'active');
                    $valid_appointment = count($this->appointment_model->where('coach_id', $coach_id)->where('date', date('Y-m-d', $date))->where('start_time', $start_time)->where('end_time', $end_time)->where('status', 'active')->get_all());
                    if ($this->db->trans_status() === true && $appointment_id && $valid_appointment == 1) {
                        $this->create_token_history($appointment_id, $token_cost->token_for_student, $remain_token, 1);
                        // messaging to send email and creating notification based on appointment
                        $this->email_notification_appointment($appointment_id);
                        // transaction finished / all criteria has been fulfilled
                        
                        ///////////////////////////////////////////////////////////////////////////////
                        // SETUP WEBEX/SKYPE
                        ///////////////////////////////////////////////////////////////////////////////
                        $available_host = $this->webex_host_model->get_available_host($appointment_id);
                        if($available_host){
                            $this->create_session($available_host[0]->id, $appointment_id);
                            $message = "Appointment booked, you will use Webex for your session";
                        }else{
                            $message = "Appointment booked, you will use Skype for your session";
                        }                        
                        
                        $this->messages->add($message, 'success');
                        redirect('student/find_coaches/book_by_single_date/' . date("Y-m-d", $date));
                    } else {
                        $this->rollback_appointment($coach_id, date("Y-m-d", $date), $start_time, $end_time, ($remain_token + $token_cost->token_for_student));
                        $this->messages->add('Fail to book appointment, please try again.', 'danger');
                        redirect('student/find_coaches/single_date/');
                    }
                } else {
                    $this->messages->add('Not Enough Token', 'danger');
                    redirect('student/find_coaches/single_date/');
                }
            } else {
                $this->messages->add('Invalid Appointment', 'danger');
                redirect('student/find_coaches/single_date/');
            }


            // If we arrive here, it means that no exception was thrown
            // i.e. no query has failed, and we can commit the transaction
            //$this->db->trans_commit();
        } catch (Exception $e) {
            // An exception has been thrown
            // We must rollback the transaction
            $this->db->trans_rollback();
            $this->messages->add('An error has occured, please try again.', 'danger');
            redirect('student/find_coaches/single_date/');
        }
    }
    
    public function multiple_date() {
        $this->template->title = 'Book Multiple Date Coach';
        $vars = array(
            'temporary_booking' => $this->appointment_model->where('student_id', $this->auth_manager->userid())->where('status', 'temporary')->get_all(),
        );
        $this->template->content->view('default/contents/find_coach/availability/multiple_date/index', $vars);
        $this->template->publish();
    }
    
    /**
     * Function block
     * redirecting to book coach by multiple date by index of all date inserted
     */

    public function book_by_multiple_date() {
        // unset session date before setting the new one
        $this->session->unset_userdata('date_1');
        $this->session->unset_userdata('date_2');
        $this->session->unset_userdata('date_3');
        $this->session->unset_userdata('date_4');
        $this->session->unset_userdata('date_5');

        $i = 1;
        foreach ($this->input->post() as $d) {
            if ($d != '' && $d != $this->input->post('__submit')) {
                $this->session->set_userdata('date_' . $i++, $d);
            }
        }

        redirect('student/find_coaches/book_by_multiple_date_index/1');
    }

    public function book_by_multiple_date_index($index = '') {
        $this->template->title = 'Detail Multiple Date';
        if (!$this->session->userdata('date_' . $index)) {
            $this->messages->add('Fill the date before search', 'danger');
            redirect('student/find_coaches/multiple_date');
        }

        $data = $this->get_available_coach($this->session->userdata('date_' . $index));
        $vars = array(
            'data' => $data,
            'temporary_booking' => $this->appointment_model->where('student_id', $this->auth_manager->userid())->where('status', 'temporary')->get_all(),
            'id_to_token_cost' => $this->coach_token_cost_model->dropdown('coach_id', 'token_for_student'),
            'index' => $index,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'rating' => $this->coach_rating_model->get_average_rate()
        );
        $this->template->content->view('default/contents/find_coach/book_by_availability/multiple_date/index', $vars);
        $this->template->publish();
    }

    public function book_multiple_coach($coach_id = '', $date = '', $start_time = '', $end_time = '', $index = '') {
        $convert = $this->schedule_function->convert_book_schedule(-($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes), $date, $start_time, $end_time);
        $date = $convert['date'];
        $start_time = $convert['start_time'];
        $end_time = $convert['end_time'];

        // memakai fungsi yang sama dengan booking
        // dipakai sementara
        $this->template->title = 'Book Multiple Date Coach';

        if ($this->create_appointment($coach_id, $date, $start_time, $end_time, 'temporary')) {
            $this->messages->add('Session booked temporary', 'success');
        } else {
            $this->messages->add('Session no longer available', 'danger');
        }
        redirect('student/find_coaches/book_by_multiple_date_index/' . $index);
    }

    public function confirm_book_by_multiple_date() {
        $this->template->title = 'Confirm book by multiple data';
        $data = $this->appointment_model->where('student_id', $this->auth_manager->userid())->where('status', 'temporary')->order_by('dcrea', 'asc')->get_all();
        
        $data_temp = array();
        foreach($data as $d){
            $data_temp[] = array(
                'id' => $d->id,
                'coach_id' => $d->coach_id,
                'data' => $this->schedule_function->convert_book_schedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time),
            );
        }
        if (!$data_temp) {
            $this->messages->add('No session booked', 'warning');
            redirect('student/find_coaches/multiple_date');
        } else {
            $vars = array(
                'data' => $data_temp,
                'token_cost' => $this->coach_token_cost_model->dropdown('coach_id', 'token_for_student'),
                'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            );

            $this->template->content->view('default/contents/find_coach/confirm_book_by_multiple_date/index', $vars);
            //publish template
            $this->template->publish();
        }
    }
    
    public function test_messaging($appointment_id = ''){
        $this->email_notification_appointment($appointment_id);
        echo('Success'); exit;
    }

    private function email_notification_appointment($appointment_id = '') {
        $data_appointment = $this->appointment_model->where('id', $appointment_id)->get();
        
        $data_student = $this->schedule_function->convert_book_schedule(($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes), strtotime($data_appointment->date), $data_appointment->start_time, $data_appointment->end_time);
        $data_coach = $this->schedule_function->convert_book_schedule(($this->identity_model->get_gmt($data_appointment->coach_id)[0]->minutes), strtotime($data_appointment->date), $data_appointment->start_time, $data_appointment->end_time);
        $gmt_student = $this->identity_model->get_gmt($this->auth_manager->userid())[0]->timezone;
        $gmt_coach = $this->identity_model->get_gmt($data_appointment->coach_id)[0]->timezone;
        
       
        // coach and student identity to be used on sending email or creating notifaction database
        $email = $this->user_model->where('id', $data_appointment->coach_id)->or_where('id', $data_appointment->student_id)->dropdown('id', 'email');
        $fullname = $this->identity_model->get_identity('profile')->where('user_id', $data_appointment->coach_id)->or_where('user_id', $data_appointment->student_id)->dropdown('user_id', 'fullname');
        // tube name for messaging action
        $tube = 'com.live.email';
        // tube name for messaging notification
        $database_tube = 'com.live.database';

        $data = array(
            'subject' => 'Session Reminder',
            'email' => $email[$this->auth_manager->userid()],
            'content' => 'You have an appointment with coach ' . $fullname[$data_appointment->coach_id] . ' , please prepare yourself 5 minutes before start the session at ' . date('l jS \of F Y', $data_student['date']) . ' ' . $data_student['start_time'] . ' until ' . $data_student['end_time'] . ' ' . $gmt_student,
        );

        // after booked, sending email to student
        $this->queue->push($tube, $data, 'email.send_email');

        //after booked, sending email to coach
        $data['email'] = $email[$data_appointment->coach_id];
        $data['content'] = 'You have an appointment with student ' . $fullname[$this->auth_manager->userid()] . ' , please prepare yourself 5 minutes before start the session at ' . date('l jS \of F Y', $data_coach['date']) . ' ' . $data_coach['start_time'] . ' until ' . $data_coach['end_time'] . ' ' . $gmt_coach;
        $this->queue->push($tube, $data, 'email.send_email');

        // after booked, creating notification for student and coach
        $student_notification = array(
            'user_id' => $this->auth_manager->userid(),
            'description' => 'Your session will be started at ' . date('l jS \of F Y', $data_student['date']) . ' from ' . $data_student['start_time'] . ' until ' . $data_student['end_time'] . ' with coach ' . $fullname[$data_appointment->coach_id] . ' ' . $gmt_student,
            'status' => '2'
        );
        $coach_notification = array(
            'user_id' => $data_appointment->coach_id,
            'description' => 'Your session will be started at ' . date('l jS \of F Y', $data_coach['date']) . ' from ' . $data_coach['start_time'] . ' until ' . $data_coach['end_time'] . ' with student ' . $fullname[$this->auth_manager->userid()] . ' ' . $gmt_coach,
            'status' => '2'
        );
        $this->db->trans_begin();
        $this->user_notification_model->insert($student_notification);
        $this->user_notification_model->insert($coach_notification);
        if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            $this->messages->add('Try again, something wrong while inserting/updating data to database', 'danger');
            return;
        }
        $this->db->trans_commit();

        // 2 hours before session time
        $reminder = $this->time_reminder_before_session($data_appointment->date . " " . $data_appointment->start_time, (10 * 60));
        // reminder for student to rate coach after session time finished
        $reminder2 = $this->time_reminder_before_session($data_appointment->date . " " . $data_appointment->end_time, (40 * 60));

        if ($reminder2 && $reminder2 >= 0) {
            // sending email if the status of appointment still active
            $data['subject'] = 'Rate Coach';
            $data["appointment_id"] = $data_appointment->id;
            $data['content'] = 'The session with coach ' . $fullname[$data_appointment->coach_id] . ' has been done. Please rate the coach.';
            $data['email'] = $email[$this->auth_manager->userid()];
            // email to remind coach and student to attend the session before it begin
            $this->queue->later($reminder2, $tube, $data, 'email.email_valid_appointment');

            // notification
            // update student's notification for messaging
            $student_notification['user_id'] = $this->auth_manager->userid();
            $student_notification['description'] = 'The session with coach ' . $fullname[$data_appointment->coach_id] . ' has been done. Please rate the coach.';
            $student_notification['status'] = 2;
            $student_notification['dcrea'] = time();
            $student_notification['dupd'] = time();

            $data_reminder_student = array(
                'table' => 'user_notifications',
                'content' => $student_notification,
                'appointment_id' => $data_appointment->id
            );

            $this->queue->later($reminder2, $database_tube, $data_reminder_student, 'database.insert_while_appointment_still_valid');

            // for creating  rating of session
            $session_rating['appointment_id'] = $data_appointment->id;
            $session_rating['status'] = 'unrated';
            $session_rating['dcrea'] = time();
            $session_rating['dupd'] = time();

            $data_rating = array(
                'table' => 'coach_ratings',
                'content' => $session_rating,
                'appointment_id' => $data_appointment->id
            );

            $this->queue->later($reminder2, $database_tube, $data_rating, 'database.insert_while_appointment_still_valid');
        }

        if ($reminder && $reminder > 0) {
            // sending email if the status of appointment still active
            $data['subject'] = 'Last Session Reminder';
            $data["appointment_id"] = $data_appointment->id;
            $data['content'] = 'Soon you will have an appointment with coach ' . $fullname[$data_appointment->coach_id] . ', please prepare yourself 5 minutes before start the session at ' . $data_student['date'] . ' ' . $data_student['start_time'] . ' until ' . $data_student['end_time'] . ' ' . $gmt_student;
            // email to remind coach and student to attend the session before it begin
            $this->queue->later($reminder, $tube, $data, 'email.email_valid_appointment');
            $data['email'] = $email[$this->auth_manager->userid()];
            $data['content'] = 'Soon you will have an appointment with student ' . $fullname[$this->auth_manager->userid()] . ', please prepare yourself 5 minutes before start the session at ' . $data_coach['date'] . ' ' . $data_coach['start_time'] . ' until ' . $data_coach['end_time'];
            $this->queue->later($reminder, $tube, $data, 'email.email_valid_appointment');

            // creating notification reminder for student and coach
            $student_notification['user_id'] = $this->auth_manager->userid();
            $student_notification['description'] = 'Reminder! Your appointment will be started at ' . date('l jS \of F Y', $data_student['date']) . ' from ' . $data_student['start_time'] . ' until ' . $data_student['end_time'] . ' with coach ' . $fullname[$data_appointment->coach_id] . ' ' . $gmt_student;
            $student_notification['status'] = 2;
            $student_notification['dcrea'] = time();
            $student_notification['dupd'] = time();

            // update coach's notification for messaging
            $coach_notification['user_id'] = $data_appointment->coach_id;
            $coach_notification['description'] = 'Reminder! Your appointment will be started at ' . date('l jS \of F Y', $data_coach['date']) . ' from ' . $data_coach['start_time'] . ' until ' . $data_coach['end_time'] . ' with student ' . $fullname[$data_appointment->student_id] . ' ' .$gmt_coach;
            $coach_notification['status'] = 2;
            $coach_notification['dcrea'] = time();
            $coach_notification['dupd'] = time();

            // student's data for reminder messaging
            // IMPORTANT : array index in content must be in mutual with table field in database
            $data_student = array(
                'table' => 'user_notifications',
                'content' => $student_notification,
                'appointment_id' => $data_appointment->id
            );

            // coach's data for reminder messaging
            // IMPORTANT : array index in content must be in mutual with table field in database
            $data_coach = array(
                'table' => 'user_notifications',
                'content' => $coach_notification,
                'appointment_id' => $data_appointment->id
            );

            $this->queue->later($reminder, $database_tube, $data_student, 'database.insert_while_appointment_still_valid');
            $this->queue->later($reminder, $database_tube, $data_coach, 'database.insert_while_appointment_still_valid');
        }
    }

    public function delete_temporary_appointment($id = '') {
        $data = $this->appointment_model->select('status')->where('id', $id)->get();
        if ($data && $data->status == 'temporary') {
            if ($this->appointment_model->delete($id)) {
                $this->messages->add('Delete succeded ', 'success');
            }
        }
        redirect('student/find_coaches/confirm_book_by_multiple_date');
    }

    public function schedule_detail($id = '') {
        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id', $id)->order_by('id', 'asc')->get_all();
        $minutes = $this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes;
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
    
    private function convert_gmt($index = '', $minutes = '') {
        if ($minutes > 0) {
            return (($index - 1) >= 0 ? ($index - 1) : 6);
        } else {
            return (($index + 1) <= 6 ? ($index + 1) : 0);
        }
    }
    
    /**
     * Function block
     * @param (string)(coach_id) coach id to get schedule
     * @param (date)(day) detail of day
     * @param (time)(start_time) detail of start time
     * @param (time)(end_time) detail of end time
     * return schedule divide by offwork of coach before converted to by coach gmt
     */
    
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

    /**
     * Function availability
     * @param (string)(search_by) redirecting page by value of search_by
     * @param (string)(coach_id) coach id to get schedule
     * @param (date)(date) detail of date
     */
    
    public function availability($search_by = '', $coach_id = '', $date_ = '') {
        $this->template->title = 'Availability';
        
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
            $duration = (strtotime($a['end_time']) - strtotime($a['start_time'])) / ($this->session_duration($this->auth_manager->partner_id())->session_per_block * 60);
            if ($duration > 1) {
                for ($i = 0; $i < $duration; $i++) {
                    $availability_exist = array(
                        // adding  minutes for every session
                        'start_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id())->session_per_block * 60) * ($i))),
                        'end_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id())->session_per_block * 60) * ($i + 1))),
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
            'availability' => $availability_temp,
            'coach_id' => $coach_id,
            'date' => $date,
            'search_by' => $search_by,
            'cost' => $this->coach_token_cost_model->select('token_for_student')->where('coach_id', $coach_id)->get()
        );
        $this->template->content->view('default/contents/find_coach/availability', $vars);

        //publish template
        $this->template->publish();
    }
    
    private function convertAppointment($start_time = '', $end_time = ''){
        $minutes = $this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes;
        
        if($minutes > 0){
            
        }
    }

    private function isOnAvailability($coach_id = '', $date_ = '') {
        if (!$date_ || !$coach_id) {
            redirect('account/identity/detail/profile');
        }

        if (!$this->is_date_available(trim($date_), 2)) {
            $this->messages->add('Date is not valid ' . $date_, 'danger');
            return false;
            //redirect('student/find_coaches/schedule_detail/' . $coach_id);
        }

        if ($this->is_day_off($coach_id, $date_) == true) {
            $this->messages->add('Coach is not available on ' . $date_, 'danger');
            return false;
            //redirect('student/find_coaches/schedule_detail/' . $coach_id);
        }
        //getting the day of $date
        $date = strtotime($date_);
        $day = strtolower(date('l', $date));


        //getting all data
        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
        $offwork = $this->offwork_model->get_offwork($coach_id, $schedule_data->day);
        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student
        $appointment = $this->appointment_model->select('id, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->get_all();
        // appointment with status temporary considered available for other student but not for student who is in the appointment and 
        // appointment where the student has make an appoinment on the specific date, so there will be no the same start time and end time to be shown to the student from other coach
        $appointment_student = $this->appointment_model->select('id, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', date("Y-m-d", $date))->get_all();
        // appointment coach in class
        $appointment_class = $this->class_meeting_day_model->select('id, start_time, end_time')->where('coach_id', $coach_id)->where('date', date("Y-m-d", $date))->get_all();

        //offwork by day
        $start_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->start_time);
        $end_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->end_time);
        //schedule by day
        $start_time_schedule = DateTime::createFromFormat('H:i:s', $schedule_data->start_time);
        $end_time_schedule = DateTime::createFromFormat('H:i:s', $schedule_data->end_time);

        $availability = array();
        // split the availability time base on offwork
        if ($start_time_offwork >= $start_time_schedule && $start_time_offwork <= $end_time_schedule && $end_time_offwork >= $start_time_schedule && $end_time_offwork <= $end_time_schedule) {
            $availability[0] = array(
                'start_time' => $schedule_data->start_time,
                'end_time' => $offwork[0]->start_time,
            );
            $availability[1] = array(
                'start_time' => $offwork[0]->end_time,
                'end_time' => $schedule_data->end_time,
            );
        } else {
            $availability[0] = array(
                'start_time' => $schedule_data->start_time,
                'end_time' => $schedule_data->end_time,
            );
        }

        // storing appointment to an array so can easily on searching / no object value inside
        $appointment_start_time_temp = array();
        $appointment_end_time_temp = array();
        foreach ($appointment as $a) {
            $appointment_start_time_temp[] = $a->start_time;
            $appointment_end_time_temp[] = $a->end_time;
        }
        // storing class meeting days to appointment temp
        foreach ($appointment_class as $a) {
            $appointment_start_time_temp[] = $a->start_time;
            $appointment_end_time_temp[] = $a->end_time;
        }
        foreach ($appointment_student as $a) {
            $appointment_start_time_temp[] = $a->start_time;
            $appointment_end_time_temp[] = $a->end_time;
        }



        $availability_temp = array();
        $availability_exist;
        foreach ($availability as $a) {
            $duration = (strtotime($a['end_time']) - strtotime($a['start_time'])) / ($this->session_duration($this->auth_manager->partner_id())->session_per_block * 60);
            if ($duration > 0) {
                for ($i = 0; $i < $duration; $i++) {
                    $availability_exist = array(
                        // adding 30 minutes for every session
                        'start_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id())->session_per_block * 60) * ($i))),
                        'end_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id())->session_per_block * 60) * ($i + 1))),
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

        return $availability_temp;
    }

    private function isCoachAvailable($coach_id = '', $date = '') {
        
    }

    private function create_appointment($coach_id = '', $date = '', $start_time = '', $end_time = '', $appointment_status = '') {
        $status = false;
        // getting the day of $date
        $day = strtolower(date('l', $date));
        $schedule_data = $this->schedule_model->select('id, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
        // Retrieve post
        $booked = array(
            'student_id' => $this->auth_manager->userid(),
            'coach_id' => $coach_id,
            'schedule_id' => $schedule_data->id,
            'date' => date("Y-m-d", $date),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => $appointment_status,
        );

        $isValid = $this->isAvailable($coach_id, $date, $start_time, $end_time);
        $this->db->trans_begin();
        if ($isValid) {
            // Inserting and checking
            $appointment_id = $this->appointment_model->insert($booked);
            $status = true;
        } else if (!$isValid) {
            $this->db->trans_rollback();
            $status = false;
        }

        if ($appointment_id && $status == true && $this->db->trans_status() === true) {
            $this->db->trans_commit();
            return $appointment_id;
        } else {
            $this->db->trans_rollback();
            return false;
        }
    }

    // used when confirmation book coach with multiple date
    private function update_appointment($appointment_id) {
        $status = false;
        $data_appointment = $this->appointment_model->where('id', $appointment_id)->get();
        $invalid_appointment = $this->appointment_model->where('coach_id', $data_appointment->coach_id)->where('date', $data_appointment->date)->where('start_time', $data_appointment->start_time)->where('end_time', $data_appointment->end_time)->where('status', 'active')->get();

        // Retrieve post
        $booked = array(
            'status' => 'active',
        );
        $this->db->trans_begin();
        if ($invalid_appointment) {
            $this->db->trans_rollback();
            $status = false;
        } else {
            $this->appointment_model->update($appointment_id, $booked);
            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $status = true;
            } else {
                $this->db->trans_rollback();
                $status = false;
            }
        }

        return $status;
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

    private function create_token_history($appointment_id = '', $coach_cost = '', $remain_token = '', $status='') {
        $appointment = $this->appointment_model->get_appointment($appointment_id);
        
        if(!$appointment){
            $this->messages->add('Invalid apppointment id',  'danger');
            redirect('student/find_coaches/single_date');
        }
        $token_history = array(
            'user_id' => $this->auth_manager->userid(),
            'transaction_date' => strtotime(date('d-m-Y')),
            'description' => 'Session with '.$appointment[0]->coach_fullname .' at '. $appointment[0]->date .' '. $appointment[0]->start_time . ' until ' . $appointment[0]->end_time,
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

    public function confirm_book() {
        // check if the appoinment still available to be booked by student
        // all appointment has status temporary, if still available to be booked by student then it will change to active
        // make all procedure/action for session booked
        $data_temporary_appointment = $this->appointment_model->select('id, coach_id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status', 'temporary')->get_all();
        if (!$data_temporary_appointment) {
            $this->messages->add('No booked appointment', 'error');
            echo "No booked appointment";exit;
        }
        $available_appointment_temp = array();
        $unavailable_appointment_temp = array();
        $coach_name = $this->identity_model->get_identity('profile')->where('partner_id', $this->auth_manager->partner_id())->dropdown('user_id', 'fullname');
        foreach ($data_temporary_appointment as $d) {
            if ($this->isAvailable($d->coach_id, strtotime($d->date), $d->start_time, $d->end_time)) {
                $available_appointment_temp[] = $d;
            } else {
                $unavailable_appointment_temp[] = $d;
                $messages[] = 'Session with coach ' . $coach_name[$d->coach_id] . ' at ' . date('l jS \of F Y', strtotime($d->date)) . ' from ' . $d->start_time . ' to ' . $d->end_time . ' is no longer available.';
            }
        }
        
        if ($unavailable_appointment_temp) {
            $message='';
            foreach($messages as $m){
               $message .= $m . "#"; 
               $this->messages->add($m, 'error');
            }
            echo "No available appointment#". $message; exit;
        } else {
            $token_cost = $this->coach_token_cost_model->dropdown('coach_id', 'token_for_student');
            $total_token_cost_temp = 0;
            foreach ($available_appointment_temp as $a) {
                $total_token_cost_temp += $token_cost[$a->coach_id];
            }
            $remain_token = $this->update_token($total_token_cost_temp);
            if ($remain_token >= 0) {
                foreach ($available_appointment_temp as $a) {
                    $status_update = $this->update_appointment($a->id);
                    $valid_appointment = count($this->appointment_model->where('coach_id', $a->coach_id)->where('date', $a->date)->where('start_time', $a->start_time)->where('end_time', $a->end_time)->where('status', 'active')->get_all());
                    if (!$status_update) {
                        // rollback all appointment to temporary
                        $this->rollback_update_appointment($available_appointment_temp, ($remain_token + $total_token_cost_temp));
                        $this->messages->add('Fail Update Appointment', 'danger');
                        echo "Updated appointment failed";exit;
                    } else if ($this->db->trans_status() === true && $valid_appointment == 1) {
                        // adding every action for updating appointment to active
                        // making token history, notification, sending email
                        // adding token history
                        $remain_temp = 0;
                        $remain_temp += $token_cost[$a->coach_id];
                        if (!$this->create_token_history($a->id, $token_cost[$a->coach_id], ($remain_token + $total_token_cost_temp - $remain_temp), 1)) {
                            // rollback all appointment to temporary
                            $this->rollback_update_appointment($available_appointment_temp, ($remain_token + $total_token_cost_temp));
                            
                            $this->messages->add('Fail, please try again!', 'danger');
                            echo "Failed, please try again!";exit;
                        } else {
                            // messaging and creating notification based on each appointment
                            $this->email_notification_appointment($a->id);
                        }
                    } else {
                        // rollback all appointment to temporary
                        $this->rollback_update_appointment($available_appointment_temp, ($remain_token + $total_token_cost_temp));
                        
                        $this->messages->add('Fail to confirm, please try again. ', 'danger');
                        echo "Confirmation failed"; exit;
                    }
                }
                foreach($available_appointment_temp as $appointment){
                    $available_host = $this->webex_host_model->get_available_host($appointment->id);
                    if(@$available_host && $this->create_session(@$available_host[0]->id, $appointment->id)){
                        $messages[]="Appointment booked, you will use WEBEX for your session " .$appointment->date . " at " . $appointment->start_time;
                        $this->messages->add("Appointment booked, you will use WEBEX for your session ".$appointment->date." at ".$appointment->start_time, 'success');
                    }else{
                        $messages[]="Appointment booked, you will use SKYPE for your session " .$appointment->date . " at " . $appointment->start_time;
                        $this->messages->add("Appointment booked, you will use SKYPE for your session ".$appointment->date." at ".$appointment->start_time, 'success');
                    }
                }
                $message = '';
                foreach($messages as $m){
                    $message .= $m . "#";
                }
                echo "Success#".$message;exit;
            } else {
                $this->messages->add('Not enough token, purchase more token or delete some sessions.', 'danger');
                echo "Not enough token, purchase more token or delete some sessions";exit;
            }
        }
    }

    public function booking($coach_id = '', $date = '', $start_time = '', $end_time = '') {
        $convert = $this->schedule_function->convert_book_schedule(-($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes), $date, $start_time, $end_time);
        $date = $convert['date'];
        $start_time = $convert['start_time'];
        $end_time = $convert['end_time'];
        
        try {
            // First of all, let's begin a transaction
            // A set of queries; if one fails, an exception should be thrown
            $isValid = $this->isAvailable($coach_id, $date, $start_time, $end_time);
            if ($isValid) {
                $availability = $this->isOnAvailability($coach_id, date('Y-m-d', $date));
                if (in_array(array('start_time' => $start_time, 'end_time' => $end_time), $availability)) {
                    // go to next step 
                    //exit;
                } else {
                    $this->messages->add('Invalid Time', 'danger');
                    redirect('student/find_coaches/search/name/');
                }
                // begin the transaction to ensure all data created or modified structural
                $token_cost = $this->coach_token_cost_model->select('token_for_student')->where('coach_id', $coach_id)->get();
                // updating remaining token student
                $this->db->trans_begin();
                $remain_token = $this->update_token($token_cost->token_for_student);
                if ($this->db->trans_status() === true && $remain_token >= 0 && $this->isAvailable($coach_id, $date, $start_time, $end_time)) {
                    $appointment_id = $this->create_appointment($coach_id, $date, $start_time, $end_time, 'active');
                    $valid_appointment = count($this->appointment_model->where('coach_id', $coach_id)->where('date', date('Y-m-d', $date))->where('start_time', $start_time)->where('end_time', $end_time)->where('status', 'active')->get_all());
                    if ($this->db->trans_status() === true && $appointment_id && $valid_appointment == 1) {
                        // creating token history
                        $this->create_token_history($appointment_id, $token_cost->token_for_student, $remain_token, 1);
                        // messaging to send email and creating notification based on appointment
                        $this->email_notification_appointment($appointment_id);
                        // transaction finished / all criteria has been fulfilled
                        $this->db->trans_commit();
                        $this->messages->add('Appointment Booked', 'success');
                        redirect('student/find_coaches/search/name/');
                    } else {
                        //throw $e;
                        $this->db->trans_rollback();
                        $this->rollback_appointment($coach_id, date("Y-m-d", $date), $start_time, $end_time, ($remain_token + $token_cost->token_for_student));
                        $this->messages->add('Fail to book appointment, please try again.', 'danger');
                        redirect('student/find_coaches/search/name/');
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->messages->add('Not Enough Token', 'danger');
                    redirect('student/find_coaches/search/name/');
                }
            } else {
                $this->db->trans_rollback();
                $this->messages->add('Invalid Appointment', 'danger');
                redirect('student/find_coaches/search/name/');
            }


            // If we arrive here, it means that no exception was thrown
            // i.e. no query has failed, and we can commit the transaction

        } catch (Exception $e) {
            // An exception has been thrown
            // We must rollback the transaction
            $this->db->trans_rollback();
            $this->messages->add('An error has occured, please try again.', 'danger');
            redirect('student/find_coaches/search/name/');
        }
    }

    private function rollback_appointment($coach_id = '', $date = '', $start_time = '', $end_time = '', $token = '') {
        // deleting appointment
        $this->appointment_model->where('coach_id', $coach_id)->where('student_id', $this->auth_manager->userid())->where('date', $date)->where('start_time', $start_time)->where('end_time', $end_time)->where('status', 'active')->delete();
        // updating remaining student token
        $data = array(
            'token_amount' => $token,
        );
        $id = $this->identity_model->get_identity('token')->where('user_id', $this->auth_manager->userid())->get();
        $this->identity_model->get_identity('token')->update($id->id, $data);
    }

    private function rollback_update_appointment($appointment = '', $token = '') {
        $data_appointment = array(
            'status' => 'temporary',
        );
        foreach ($appointment as $a) {
            $this->appointment_model->update($a->id, $data_appointment);
        }
        // updating remaining student token
        $data = array(
            'token_amount' => $token,
        );
        $id = $this->identity_model->get_identity('token')->where('user_id', $this->auth_manager->userid())->get();
        $this->identity_model->get_identity('token')->update($id->id, $data);
    }

    /**
     * Function time_reminder_before_session
     *  
     * @param (string)(session_time) session time ('Y-m-d H:i:s')
     * @param (int)(delay_time) delay time before session time (s)
     *
     * @return if the function not return positive int, return FALSE
     */
    private function time_reminder_before_session($session_time, $delay_time) {
        if (DateTime::createFromFormat('Y-m-d H:i:s', trim($session_time)) != FALSE) {
            $now = (date('Y-m-d H:i:s', time() + $delay_time));
            return (((strtotime($session_time) - strtotime($now))) < 0 ? FALSE : (strtotime($session_time) - strtotime($now)));
        } else {
            return FALSE;
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
        
        $status1 = 0;
        if ($appointment || $appointment_student || $appointment_class) {
            return false;
        } else if (!$appointment) {
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
                return false;
            }
        }
    }

    /**
     * Function is_date_available
     * @param (string)(date) date that will be checked wheather available ('Y-m-d')
     * @param (int)(day) sum of day()
     * @return return TRUE if date available
     */
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

    private function create_date_range_array($strDateFrom, $strDateTo) {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.
        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange = array();

        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
            while ($iDateFrom < $iDateTo) {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange, date('Y-m-d', $iDateFrom));
            }
        }
        return $aryRange;
    }

    public function summary_book($search_by = '', $coach_id = '', $date = '', $start_time = '', $end_time = '') {
        $vars = array(
            'data_coach' => $this->identity_model->get_coach_identity($coach_id),
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'search_by' => $search_by,
        );

        $this->template->content->view('default/contents/find_coach/summary_book/index', $vars);
        //publish template
        $this->template->publish();
    }
    
    private function session_duration($partner_id = ''){
        return ($this->partner_model->select('id, session_per_block')->where('id', $partner_id)->get());
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
                redirect('coach/upcoming_session');
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
                redirect('coach/upcoming_session');
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
                        <timeZoneID>20</timeZoneID>
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
        $result = $this->common_function->post_it($d, $URL, $XML_PORT);

        // Clean xml
        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
        $simple_xml = simplexml_load_string($clean_result);
        if ($simple_xml === false) {
            return false;
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
                        $student_emails = $this->class_member_model->get_appointment_for_webex_invitation_xml($meeting_identifier);
                        if ($student_emails) {
                            foreach ($student_emails as $se) {
                                $gmt = $this->identity_model->get_gmt($se->student_id)[0]->timezone;
                                $converted_time = $this->schedule_function->convert_book_schedule(-($this->identity_model->get_gmt($se->student_id)[0]->minutes), strtotime($se->date), $se->start_time, $se->end_time);
                                $student_notification [] = array(
                                    'user_id' => $se->student_id,
                                    'description' => 'Class '.$se->class_name.'. You have session with ' . $se->coach_name . ' on ' . $converted_time['date'] . ' at ' . $converted_time['start_time'] . ' until ' . $converted_time['end_time'] .' '. $gmt . ' using WEBEX.'.' Check your email to see the detail invitation!',
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
                        $gmt = $this->identity_model->get_gmt($student_emails[0]->student_id)[0]->timezone;
                        $converted_time = $this->schedule_function->convert_book_schedule(-($this->identity_model->get_gmt($student_emails[0]->student_id)[0]->minutes), strtotime($student_emails[0]->date), $student_emails[0]->start_time, $student_emails[0]->end_time);
                        if ($student_emails) {
                            $student_notification = array(
                                'user_id' => $student_emails[0]->student_id,
                                'description' => 'You just invited by ' . $student_emails[0]->coach_name . ' to join a WebEx Meeting on ' . $converted_time['date'] . ' at ' . $converted_time['start_time'] . ' until ' . $converted_time['end_time'] .' '. $gmt . '. Check your email to see the detail invitation!',
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
