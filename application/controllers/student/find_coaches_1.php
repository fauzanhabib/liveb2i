<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class find_coaches extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();

        // load models
        $this->load->model('identity_model');
        $this->load->model('schedule_model');
        $this->load->model('offwork_model');
        $this->load->model('appointment_model');
        $this->load->model('appointment_reschedule_model');
        $this->load->model('user_notification_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('coach_day_off_model');
        $this->load->model('coach_token_cost_model');
        $this->load->model('token_histories_model');
        $this->load->model('coach_rating_model');

        //load libraries
        $this->load->library('queue');

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
        //print_r($this->input->post());
        $coach_data = $this->identity_model->get_coach_identity('', '', '', $this->auth_manager->partner_id());
        //echo('<pre>');
        //print_r($coach_data); exit;
        $available_coach = array();
        foreach ($coach_data as $cd) {
            $coach_id = $cd->id;
            $availability_temp = array();
            if ($this->is_date_available(trim($date_), 2) && !$this->is_day_off($coach_id, $date_) == true) {
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
                    $appointment_start_time_temp[$a->start_time] = $a->start_time;
                    $appointment_end_time_temp[$a->end_time] = $a->end_time;
                }
                // storing class meeting days to appointment temp
                foreach ($appointment_class as $a) {
                    $appointment_start_time_temp[$a->start_time] = $a->start_time;
                    $appointment_end_time_temp[$a->end_time] = $a->end_time;
                }
                foreach ($appointment_student as $a) {
                    $appointment_start_time_temp[$a->start_time] = $a->start_time;
                    $appointment_end_time_temp[$a->end_time] = $a->end_time;
                }




                $availability_exist;
                foreach ($availability as $a) {
                    $duration = (strtotime($a['end_time']) - strtotime($a['start_time'])) / 1800;
                    if ($duration > 0) {
                        for ($i = 0; $i < $duration; $i++) {
                            $availability_exist = array(
                                // adding 30 minutes for every session
                                'start_time' => date('H:i:s', strtotime($a['start_time']) + (1800 * ($i))),
                                'end_time' => date('H:i:s', strtotime($a['start_time']) + (1800 * ($i + 1))),
                            );
                            // checking if availability is existed in the appointment
                            if (in_array($availability_exist['start_time'], $appointment_start_time_temp) && in_array($availability_exist['end_time'], $appointment_end_time_temp)) {
                                // no action
                            } else {
                                // storing availability that still active and not been boooked yet
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
        );
        $this->template->content->view('default/contents/find_coach/book_by_availability/single_date/index', $vars);
        $this->template->publish();
    }

    public function book_single_coach($coach_id = '', $date = '', $start_time = '', $end_time = '') {
        try {
            // First of all, let's begin a transaction
            //$this->db->trans_begin();
            // A set of queries; if one fails, an exception should be thrown
            $isValid = $this->isAvailable($coach_id, $date, $start_time, $end_time);
            if ($isValid) {
                $availability = $this->isOnAvailability($coach_id, date('Y-m-d', $date));
                if (in_array(array('start_time' => $start_time, 'end_time' => $end_time), $availability)) {
                    // go to next step 
                    //exit;
                } else {
                    $this->messages->add('Invalid Time', 'danger');
                    //redirect('student/find_coaches/book_by_single_date/' . date("Y-m-d", $date));
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
                        $this->messages->add('Appointment Booked', 'success');
                        redirect('student/find_coaches/book_by_single_date/' . date("Y-m-d", $date));
                        //redirect('student/find_coaches/single_date/');
                    } else {
                        $this->rollback_appointment($coach_id, date("Y-m-d", $date), $start_time, $end_time, ($remain_token + $token_cost->token_for_student));
                        $this->messages->add('Fail to book appointment, please try again.', 'danger');
                        //redirect('student/find_coaches/book_by_single_date/' . date("Y-m-d", $date));
                        redirect('student/find_coaches/single_date/');
                    }
                } else {
                    $this->messages->add('Not Enough Token', 'danger');
                    //redirect('student/find_coaches/book_by_single_date/' . date("Y-m-d", $date));
                    redirect('student/find_coaches/single_date/');
                }
            } else {
                $this->messages->add('Invalid Appointment', 'danger');
                //redirect('student/find_coaches/single_date');
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
            //redirect('student/find_coaches/book_by_single_date/' . date("Y-m-d", $date));
            redirect('student/find_coaches/single_date/');
        }
    }

    public function multiple_date() {
        $this->template->title = 'Book Multiple Date Coach';

        $vars = array(
            'temporary_booking' => $this->appointment_model->where('student_id', $this->auth_manager->userid())->where('status', 'temporary')->get_all(),
        );
        //print_r($vars); exit;
        $this->template->content->view('default/contents/find_coach/availability/multiple_date/index', $vars);
        $this->template->publish();
    }

    public function book_by_multiple_date() {
        //print_r($this->input->post()); exit;
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



        //print_r($this->session->userdata); exit;
        //print_r($this->input->post()); exit;

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
        );
        $this->template->content->view('default/contents/find_coach/book_by_availability/multiple_date/index', $vars);
        $this->template->publish();
    }

    public function book_multiple_coach($coach_id = '', $date = '', $start_time = '', $end_time = '', $index = '') {
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

        if (!$data) {
            $this->messages->add('No session booked', 'danger');
            redirect('student/find_coaches/multiple_date');
        } else {
            $vars = array(
                'data' => $data,
                'token_cost' => $this->coach_token_cost_model->dropdown('coach_id', 'token_for_student'),
                'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            );

            $this->template->content->view('default/contents/find_coach/confirm_book_by_multiple_date/index', $vars);
            //$this->template->content->view('default/contents/find_coach/confirm_book_by_multiple_date/index_1', $vars);
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
        
//        $reminder = $this->time_reminder_before_session($data_appointment->date . " " . $data_appointment->start_time, (10 * 60));
//        // reminder for student to rate coach after session time finished
//        $reminder2 = $this->time_reminder_before_session($data_appointment->date . " " . $data_appointment->end_time, (40 * 60));
//        echo($reminder.'<br>'.$reminder2); exit;
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
            'content' => 'You have an appointment with coach ' . $fullname[$data_appointment->coach_id] . ' , please prepare yourself 5 minutes before start the session at ' . date('l jS \of F Y', strtotime($data_appointment->date)) . ' ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time,
        );

        // after booked, sending email to student
        $this->queue->push($tube, $data, 'email.send_email');

        //after booked, sending email to coach
        $data['email'] = $email[$data_appointment->coach_id];
        $data['content'] = 'You have an appointment with student ' . $fullname[$this->auth_manager->userid()] . ' , please prepare yourself 5 minutes before start the session at ' . date('l jS \of F Y', strtotime($data_appointment->date)) . ' ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time;
        $this->queue->push($tube, $data, 'email.send_email');

        // after booked, creating notification for student and coach
        $student_notification = array(
            'user_id' => $this->auth_manager->userid(),
            'description' => 'Your session will be started at ' . date('l jS \of F Y', strtotime($data_appointment->date)) . ' from ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time . ' with coach ' . $fullname[$data_appointment->coach_id],
            'status' => '2'
        );
        $coach_notification = array(
            'user_id' => $data_appointment->coach_id,
            'description' => 'Your session will be started at ' . date('l jS \of F Y', strtotime($data_appointment->date)) . ' from ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time . ' with student ' . $fullname[$this->auth_manager->userid()],
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

            $data_student = array(
                'table' => 'user_notifications',
                'content' => $student_notification,
                'appointment_id' => $data_appointment->id
            );

            $this->queue->later($reminder2, $database_tube, $data_student, 'database.insert_while_appointment_still_valid');

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
            $data['content'] = 'Soon you will have an appointment with coach ' . $fullname[$data_appointment->coach_id] . ', please prepare yourself 5 minutes before start the session at ' . $data_appointment->date . ' ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time;
            // email to remind coach and student to attend the session before it begin
            $this->queue->later($reminder, $tube, $data, 'email.email_valid_appointment');
            $data['email'] = $email[$this->auth_manager->userid()];
            $data['content'] = 'Soon you will have an appointment with student ' . $fullname[$this->auth_manager->userid()] . ', please prepare yourself 5 minutes before start the session at ' . $data_appointment->date . ' ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time;
            $this->queue->later($reminder, $tube, $data, 'email.email_valid_appointment');

            // creating notification reminder for student and coach
            $student_notification['user_id'] = $this->auth_manager->userid();
            $student_notification['description'] = 'Reminder! Your appointment will be started at ' . date('l jS \of F Y', strtotime($data_appointment->date)) . ' from ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time . ' with coach ' . $fullname[$data_appointment->coach_id];
            $student_notification['status'] = 2;
            $student_notification['dcrea'] = time();
            $student_notification['dupd'] = time();

            // update coach's notification for messaging
            $coach_notification['user_id'] = $data_appointment->coach_id;
            $coach_notification['description'] = 'Reminder! Your appointment will be started at ' . date('l jS \of F Y', strtotime($data_appointment->date)) . ' from ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time . ' with student ' . $fullname[$data_appointment->student_id];
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
        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id', $id)->get_all();

        if (!$schedule_data) {
            redirect('student/find_coaches/detail/' . $id);
        }
        $schedule = array();
        foreach ($schedule_data as $s) {
            $offwork = $this->offwork_model->get_offwork($id, $s->day);
            //offwork by day
            $start_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->start_time);
            $end_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->end_time);
            //schedule by day
            $start_time_schedule = DateTime::createFromFormat('H:i:s', $s->start_time);
            $end_time_schedule = DateTime::createFromFormat('H:i:s', $s->end_time);

            $schedule_temp = array();
            if ($start_time_offwork == $start_time_schedule && $start_time_offwork == $end_time_schedule && $end_time_offwork == $start_time_schedule && $end_time_offwork == $end_time_schedule) {
                $schedule_temp[0] = array(
                    'start_time' => $s->start_time,
                    'end_time' => $s->end_time,
                );
            } else if ($start_time_offwork > $start_time_schedule && $start_time_offwork < $end_time_schedule && $end_time_offwork > $start_time_schedule && $end_time_offwork < $end_time_schedule) {
                $schedule_temp[0] = array(
                    'start_time' => $s->start_time,
                    'end_time' => $offwork[0]->start_time,
                );
                $schedule_temp[1] = array(
                    'start_time' => $offwork[0]->end_time,
                    'end_time' => $s->end_time,
                );
            } else {
                $schedule_temp[0] = array(
                    'start_time' => $s->start_time,
                    'end_time' => $s->end_time,
                );
            }

            $schedule[$s->day] = $schedule_temp;
            unset($schedule_temp);
        }

        // creating combo box based on surrent day until last day of the year
        // will improve option based on off day or vacation of coach 
        $year = date("Y");
        $date = time();

        //$datetime1 = date_create(date("Y-m-d"));
        $datetime1 = date_create(date("Y-m-d", strtotime(date("Y-m-d") . "+2 days")));
        $datetime2 = date_create($year . '-12-31');
        $interval = date_diff($datetime1, $datetime2);

        $num_days = $interval->days;

        // storing day off of coach and banned it from availabilty date
        $day_off = $this->coach_day_off_model->select('start_date, end_date')->where('coach_id', $id)->where('status', 'active')->get();

        $day_off_start_date = date_create(@$day_off->start_date);
        $day_off_end_date = date_create(@$day_off->end_date);
        $interval2 = date_diff($day_off_start_date, $day_off_end_date);
        $num_days2 = $interval2->days;

//        $option_date = array();
//        $option_date [0] = 'Select Date';
//        for ($i = 0; $i <= $num_days; ++$i) {
//            $date = mktime(0, 0, 0, date("m"), date("d") + 2 + $i, date("Y"));
//
//            if ($i == 0) {
//                if (strtotime(@$day_off->start_date) <= strtotime(date('Y-m-d', $date)) && strtotime(@$day_off->end_date) >= strtotime(date('Y-m-d', $date))) {
//                    $date1 = date_create(@$day_off->start_date);
//                    $date2 = date_create(date('Y-m-d', $date));
//                    $interval3 = date_diff($date1, $date2);
//                    $num_days3 = $interval3->days;
//                    $num_days2 = $num_days2 - $num_days3;
//                }
//            }
//
//            $day_off_interval;
//            if (@$num_days3) {
//                $day_off_interval = date('Y-m-d', strtotime(@$day_off->start_date) + (24 * 60 * 60 * $num_days3));
//            } else {
//                $day_off_interval = @$day_off->start_date;
//            }
//            //print_r($day_off_interval); exit;
//
//            if ($day_off_interval == date('Y-m-d', $date)) {
//                $i = $i + $num_days2;
//            } else {
//                $option_date[date('Y-m-d', $date)] = date('D j M Y', $date);
//            }
//        }

        $vars = array(
            'coach_id' => $id,
            'schedule' => $schedule,
        );
        $this->template->content->view('default/contents/find_coach/schedule_detail', $vars);

        //publish template
        $this->template->publish();
    }

    public function availability($search_by = '', $coach_id = '', $date_ = '') {
        $this->template->title = 'Availability';

        if (!$date_ || !$coach_id) {
            //redirect(home);
            $vars = array();
            $this->template->content->view('default/contents/find_coach/availability', $vars);

            //publish template
            $this->template->publish();
        }

        if (!$this->is_date_available(trim($date_), 2)) {
            //$this->messages->add('Date is not valid ' . $date_, 'danger');
            //redirect('student/find_coaches/schedule_detail/' . $coach_id);
            $vars = array();
            $this->template->content->view('default/contents/find_coach/availability', $vars);
        }

        if ($this->is_day_off($coach_id, $date_) == true) {
            //$this->messages->add('Coach is not available on ' . $date_, 'danger');
            //redirect('student/find_coaches/schedule_detail/' . $coach_id);
            $vars = array();
            $this->template->content->view('default/contents/find_coach/availability', $vars);
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
        if ($start_time_offwork > $start_time_schedule && $start_time_offwork < $end_time_schedule && $end_time_offwork > $start_time_schedule && $end_time_offwork < $end_time_schedule) {
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
            $appointment_start_time_temp[$a->start_time] = $a->start_time;
            $appointment_end_time_temp[$a->end_time] = $a->end_time;
        }
        // storing class meeting days to appointment temp
        foreach ($appointment_class as $a) {
            $appointment_start_time_temp[$a->start_time] = $a->start_time;
            $appointment_end_time_temp[$a->end_time] = $a->end_time;
        }
        foreach ($appointment_student as $a) {
            $appointment_start_time_temp[$a->start_time] = $a->start_time;
            $appointment_end_time_temp[$a->end_time] = $a->end_time;
        }



        $availability_temp = array();
        $availability_exist;
        foreach ($availability as $a) {
            $duration = (strtotime($a['end_time']) - strtotime($a['start_time'])) / 1800;
            if ($duration > 0) {
                for ($i = 0; $i < $duration; $i++) {
                    $availability_exist = array(
                        // adding 30 minutes for every session
                        'start_time' => date('H:i:s', strtotime($a['start_time']) + (1800 * ($i))),
                        'end_time' => date('H:i:s', strtotime($a['start_time']) + (1800 * ($i + 1))),
                    );
                    // checking if availability is existed in the appointment
                    if (in_array($availability_exist['start_time'], $appointment_start_time_temp) && in_array($availability_exist['end_time'], $appointment_end_time_temp)) {
                        // no action
                    } else {
                        // storing availability that still active and not been boooked yet
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
            $appointment_start_time_temp[$a->start_time] = $a->start_time;
            $appointment_end_time_temp[$a->end_time] = $a->end_time;
        }
        // storing class meeting days to appointment temp
        foreach ($appointment_class as $a) {
            $appointment_start_time_temp[$a->start_time] = $a->start_time;
            $appointment_end_time_temp[$a->end_time] = $a->end_time;
        }
        foreach ($appointment_student as $a) {
            $appointment_start_time_temp[$a->start_time] = $a->start_time;
            $appointment_end_time_temp[$a->end_time] = $a->end_time;
        }



        $availability_temp = array();
        $availability_exist;
        foreach ($availability as $a) {
            $duration = (strtotime($a['end_time']) - strtotime($a['start_time'])) / 1800;
            if ($duration > 0) {
                for ($i = 0; $i < $duration; $i++) {
                    $availability_exist = array(
                        // adding 30 minutes for every session
                        'start_time' => date('H:i:s', strtotime($a['start_time']) + (1800 * ($i))),
                        'end_time' => date('H:i:s', strtotime($a['start_time']) + (1800 * ($i + 1))),
                    );
                    // checking if availability is existed in the appointment
                    if (in_array($availability_exist['start_time'], $appointment_start_time_temp) && in_array($availability_exist['end_time'], $appointment_end_time_temp)) {
                        // no action
                    } else {
                        // storing availability that still active and not been boooked yet
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

        return $availability_temp;
    }

    private function isCoachAvailable($coach_id = '', $date = '') {
        
    }

    /*
     * fungsi yang perlu dipisah dari fungsi booking    
     * 1. fungsi create appointment (check)
     * 2. fungsi check token (check)
     * 3. fungsi messaging email and notification appointment (check)
     * 4. update appointment status from temporary to active (check)
     */

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
            $this->messages->add('No booked appointment', 'danger');
            redirect('student/find_coaches/confirm_book_by_multiple_date/');
        }
        $available_appointment_temp = array();
        $unavailable_appointment_temp = array();
        $coach_name = $this->identity_model->get_identity('profile')->where('partner_id', $this->auth_manager->partner_id())->dropdown('user_id', 'fullname');
        foreach ($data_temporary_appointment as $d) {
            if ($this->isAvailable($d->coach_id, strtotime($d->date), $d->start_time, $d->end_time)) {
                $available_appointment_temp[] = $d;
            } else {
                $unavailable_appointment_temp[] = $d;
                $this->messages->add('Session with coach ' . $coach_name[$d->coach_id] . ' at ' . date('l jS \of F Y', strtotime($d->date)) . ' from ' . $d->start_time . ' to ' . $d->end_time . ' is no longer available.', 'danger');
            }
        }

        if ($unavailable_appointment_temp) {
            redirect('student/find_coaches/confirm_book_by_multiple_date/');
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
                        redirect('student/find_coaches/confirm_book_by_multiple_date/');
                    } else if ($this->db->trans_status() === true && $valid_appointment == 1) {
                        // adding every action for updating appointment to active
                        // making token history, notification, sending email
                        // adding token history
                        $remain_temp += $token_cost[$a->coach_id];
                        if (!$this->create_token_history($a->id, $token_cost[$a->coach_id], ($remain_token + $total_token_cost_temp - $remain_temp), 1)) {
                            // rollback all appointment to temporary
                            $this->rollback_update_appointment($available_appointment_temp, ($remain_token + $total_token_cost_temp));
                            $this->messages->add('Fail, please try again!', 'danger');
                            redirect('student/find_coaches/confirm_book_by_multiple_date/');
                        } else {
                            // messaging and creating notification based on each appointment
                            $this->email_notification_appointment($a->id);
                        }
                    } else {
                        // rollback all appointment to temporary
                        $this->rollback_update_appointment($available_appointment_temp, ($remain_token + $total_token_cost_temp));
                        $this->messages->add('Fail to confirm, please try again. ', 'danger');
                        redirect('student/find_coaches/confirm_book_by_multiple_date/');
                    }
                }
                $this->messages->add('Multiple Book ', 'success');
                redirect('student/find_coaches/multiple_date');
            } else {
                $this->messages->add('Not enough token, purchase more token or delete some sessions.', 'danger');
                redirect('student/find_coaches/confirm_book_by_multiple_date/');
            }
        }

        $this->messages->add('Multiple Book ', 'success');
        redirect('student/find_coaches/multiple_date');
    }

    public function booking($coach_id = '', $date = '', $start_time = '', $end_time = '') {
        try {
            // First of all, let's begin a transaction
            //$this->db->trans_begin();
            // A set of queries; if one fails, an exception should be thrown
            $isValid = $this->isAvailable($coach_id, $date, $start_time, $end_time);
            if ($isValid) {
                $availability = $this->isOnAvailability($coach_id, date('Y-m-d', $date));
                if (in_array(array('start_time' => $start_time, 'end_time' => $end_time), $availability)) {
                    // go to next step 
                    //exit;
                } else {
                    $this->messages->add('Invalid Time', 'danger');
                    //redirect('student/find_coaches/availability/' . $coach_id . '/' . date("Y-m-d", $date));
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
                    //print_r($valid_appointment); exit;
                    if ($this->db->trans_status() === true && $appointment_id && $valid_appointment == 1) {
                        // creating token history
                        $this->create_token_history($appointment_id, $token_cost->token_for_student, $remain_token, 1);
                        // messaging to send email and creating notification based on appointment
                        $this->email_notification_appointment($appointment_id);
                        // transaction finished / all criteria has been fulfilled
                        $this->db->trans_commit();
                        $this->messages->add('Appointment Booked', 'success');
                        //redirect('student/find_coaches/availability/' . $coach_id . '/' . date("Y-m-d", $date));
                        redirect('student/find_coaches/search/name/');
                    } else {
                        //throw $e;
                        $this->db->trans_rollback();
                        //echo ($remain_token.' '.$token_cost->token_for_student); exit;
                        $this->rollback_appointment($coach_id, date("Y-m-d", $date), $start_time, $end_time, ($remain_token + $token_cost->token_for_student));
                        $this->messages->add('Fail to book appointment, please try again.', 'danger');
                        //redirect('student/find_coaches/availability/' . $coach_id . '/' . date("Y-m-d", $date));
                        redirect('student/find_coaches/search/name/');
                    }
                } else {

                    //throw $e;
                    $this->db->trans_rollback();
                    $this->messages->add('Not Enough Token', 'danger');
                    //redirect('student/find_coaches/availability/' . $coach_id . '/' . date("Y-m-d", $date));
                    redirect('student/find_coaches/search/name/');
                }
            } else {
                //throw $e;
                $this->db->trans_rollback();
                $this->messages->add('Invalid Appointment', 'danger');
                //redirect('student/find_coaches/availability/' . $coach_id . '/' . date("Y-m-d", $date));
                redirect('student/find_coaches/search/name/');
            }


            // If we arrive here, it means that no exception was thrown
            // i.e. no query has failed, and we can commit the transaction
            //$this->db->trans_commit();
        } catch (Exception $e) {
            // An exception has been thrown
            // We must rollback the transaction
            $this->db->trans_rollback();
            $this->messages->add('An error has occured, please try again.', 'danger');
            //redirect('student/find_coaches/availability/' . $coach_id . '/' . date("Y-m-d", $date));
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
        // getting the day of $date
        $day = strtolower(date('l', $date));

        // getting all data
        $schedule_data = $this->schedule_model->select('id, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
        $offwork = $this->offwork_model->get_offwork($coach_id, $day);

        // convert time to be able to compare
        // offwork by day
        $start_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->start_time);
        $end_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->end_time);
        //schedule by day
        $start_time_schedule = DateTime::createFromFormat('H:i:s', $schedule_data->start_time);
        $end_time_schedule = DateTime::createFromFormat('H:i:s', $schedule_data->end_time);

        // booking time
        $start_time_booking = DateTime::createFromFormat('H:i:s', $start_time);
        $end_time_booking = DateTime::createFromFormat('H:i:s', $end_time);

        // check if coach availability has been booked or nothing
        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student
        $appointment = $this->appointment_model->select('id, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->where('start_time', $start_time)->where('end_time', $end_time)->get();

        if ($appointment) {
            return false;
        } else if (!$appointment) {
            // compare booking time if there is offwork in the schedule
            if ($start_time_offwork >= $start_time_schedule && $start_time_offwork <= $end_time_schedule && $end_time_offwork >= $start_time_schedule && $end_time_offwork <= $end_time_schedule) {
                // compare booking time if its in available range -> start time schedule to start time offwork and end time schedule to end time offwork
                if ($start_time_booking >= $start_time_schedule && $start_time_booking <= $start_time_offwork && $end_time_booking >= $start_time_schedule && $end_time_booking <= $start_time_offwork) {
                    return true;
                } else if ($start_time_booking >= $end_time_offwork && $start_time_booking <= $end_time_schedule && $end_time_booking >= $end_time_offwork && $end_time_booking <= $end_time_schedule) {
                    return true;
                } else {
                    return false;
                }
            } else {
                // compare booking time if there is NO offwork in the schedule
                if ($start_time_booking >= $start_time_schedule && $end_time_booking <= $end_time_schedule) {
                    return true;
                } else {
                    return false;
                }
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

}
