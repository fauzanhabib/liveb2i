<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class manage_appointments extends MY_Site_Controller {

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
        $this->load->model('webex_host_model');
        $this->load->model('webex_class_model');
        $this->load->model('webex_model');

        // for messaging action and timing
        $this->load->library('phpass');
        $this->load->library('queue');

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

    public function reschedule($appointment_id = '') {
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
        $date = strtotime($date_);
        $day = strtolower(date('l', $date));


        //getting all data
        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
        $offwork = $this->offwork_model->get_offwork($coach_id, $schedule_data->day);
        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student
        $appointment = $this->appointment_model->select('id, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('date', date("Y-m-d", $date))->get_all();

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

        //storing appointment to an array so can easily on searching / no object value inside
        $appointment_temp = array();
        foreach ($appointment as $a) {
            $appointment_temp[$a->start_time] = $a->start_time;
            $appointment_temp[$a->end_time] = $a->end_time;
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
                    if (in_array($availability_exist['start_time'], $appointment_temp) && in_array($availability_exist['end_time'], $appointment_temp)) {
                        
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
            //'schedule_id' => $schedule_data->id,
            'appointment_id' => $appointment_id,
            'availability' => $availability_temp,
            'coach_id' => $coach_id,
            'date' => $date,
        );
        $this->template->content->view('default/contents/manage_appointment/reschedule/availability', $vars);

        //publish template
        $this->template->publish();
    }

    public function reschedule_booking($appointment_id = '', $coach_id = '', $date = '', $start_time = '', $end_time = '') {
        //$this->db->trans_rollback();exit;
        $date = strtotime($date);
        $appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, start_time, end_time')->where('id', $appointment_id)->where('student_id', $this->auth_manager->userid())->get();
        if (!$appointment_data) {
            $this->messages->add('Invalid Appointment', 'danger');
            redirect('student/upcoming_session');
        }

        // Retrieve post
        $booked = array(
            'appointment_id' => $appointment_id,
            'date' => date('Y-m-d', $date),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => 'active',
        );

        $isValid = $this->isAvailable($coach_id, $date, $start_time, $end_time);

        // desiscion maker if available time is valid
        $this->db->trans_begin();
        if ($isValid) {
            // Inserting and checking
            if (!$this->appointment_reschedule_model->insert($booked)) {
                $this->db->trans_rollback();
                $this->messages->add('Invalid Action 1', 'danger');
                $this->index();
                return;
            }
        } else {
            $this->messages->add('Invalid Action 2', 'danger');
            redirect('student/upcoming_session');
        }

        // updating appointment current status to reschedule
        $appointment_update = array(
            'status' => 'reschedule',
        );

        $webex_host = $this->webex_host_model->get_host($appointment_id);
        $webex = $this->webex_model->select('id')->where('appointment_id', $appointment_id)->get();
        //print($webex->id);
        //$this->db->trans_rollback();exit;
        if(!$webex_host || !$webex){
            $this->db->trans_rollback();
            $this->messages->add('Invalid Session', 'error');
            redirect('student/upcoming_session');
        }
        
        if (!$this->appointment_model->update($appointment_id, $appointment_update) || !$this->delete_session($webex_host[0]->id, $appointment_id) || !$this->webex_model->delete($webex->id)) {
            $this->db->trans_rollback();
            $this->messages->add('Invalid Action', 'danger');
            redirect('student/upcoming_session');
        } else {
            //print("test");$this->db->trans_rollback();exit;
            // if student reschedule the session 2 days/ 48 hours before the sessioin start, student will get the token back 100%
            $student_token_data = $this->identity_model->get_identity('token')->where('user_id', $this->auth_manager->userid())->get();
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

                if (!$this->create_token_history($appointment_id, $coach_token_cost->token_for_student, $student_token, 9)) {
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
        }

        $day = strtolower(date('l', $date));
        $schedule_data = $this->schedule_model->select('id')->where('user_id', $coach_id)->where('day', $day)->get();
        $new_appointment = array(
            'student_id' => $this->auth_manager->userid(),
            'coach_id' => $coach_id,
            'schedule_id' => $schedule_data->id,
            'date' => date("Y-m-d", $date),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => 'active',
        );

        // inserting new appointment to appointment table
        $new_appointment_id = $this->appointment_model->insert($new_appointment);
        if (!$new_appointment_id) {
            $this->db->trans_rollback();
            $this->messages->add('Invalid Action', 'danger');
            $this->index();
            return;
        } else {
            // if student reschedule the session 2 days/ 48 hours before the sessioin start, student will get the token back 100%
            $student_token_data = $this->identity_model->get_identity('token')->where('user_id', $this->auth_manager->userid())->get();
            $coach_token_cost = $this->coach_token_cost_model->select('id, token_for_student')->where('coach_id', $appointment_data->coach_id)->get();

            $student_token = $student_token_data->token_amount - $coach_token_cost->token_for_student;
            $token_update = array(
                'token_amount' => $student_token,
            );

            if (!$this->identity_model->get_identity('token')->update($student_token_data->id, $token_update)) {
                $this->messages->add(validation_errors(), 'danger');
                $this->index();
                return;
            }

            if (!$this->create_token_history($appointment_id, $coach_token_cost->token_for_student, $student_token, 1)) {
                $this->messages->add('Error while create token history', 'danger');
                $this->index();
                return;
            }
        }

        $this->db->trans_commit();

//        // if student reschedule the session 2 days/ 48 hours before the session start, student will get the token back 100%
//        $time_left_before_session = $this->time_reminder_before_session($appointment_data->date . ' ' . $appointment_data->start_time, (2 * 24 * 60 * 60));
//        if ($time_left_before_session > 0) {
//            $coach_token_cost = $this->coach_token_cost_model->select('id, token_for_student')->where('coach_id', $appointment_data->coach_id)->get();
//            $student_token_data = $this->identity_model->get_identity('token')->where('user_id', $appointment_data->student_id)->get();
//            //print_r($student_token_data); exit;
//            $student_token = $student_token_data->token_amount + $coach_token_cost->token_for_student;
//
//            $token_update = array(
//                'token_amount' => $student_token,
//            );
//
//            if (!$this->identity_model->get_identity('token')->update($student_token_data->id, $token_update)) {
//                $this->messages->add(validation_errors(), 'danger');
//                $this->index();
//                return;
//            }
//        }
        // after student rescheduled an appointment, send email to coach
        // tube name for messaging action
        $tube = 'com.live.email';
        $id_to_email_address = $this->user_model->dropdown('id', 'email');
        $id_to_name = $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname');
        $data_student = array(
            'subject' => 'Appointment Rescheduled',
            'email' => $id_to_email_address[$this->auth_manager->userid()],
            'content' => 'You have rescheduled appointment at ' . date('l jS \of F Y', strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' with ' . $id_to_name[$new_appointment['coach_id']] . ' to ' . $new_appointment['date'] . ' from ' . $new_appointment['start_time'] . ' until ' . $new_appointment['end_time'] . '.',
        );

        $data_coach = array(
            'subject' => 'Appointment Rescheduled',
            'email' => $id_to_email_address[$coach_id],
            'content' => 'Your appointment at ' . date("l jS \of F Y", strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' with student ' . $id_to_name[$new_appointment['student_id']] . ' has been rescheduled to ' . date("l jS \of F Y", $date) . ' from ' . $start_time . ' until ' . $end_time . '.',
        );

        $data_partner = array(
            'subject' => 'Appointment Rescheduled',
            'content' => 'Student ' . $id_to_name[$this->auth_manager->userid()] . ' ask for rescheduled appointment at ' . date('l jS \of F Y', strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' with coach ' . $id_to_name[$new_appointment['coach_id']] . ' to ' . $new_appointment['date'] . ' from ' . $new_appointment['start_time'] . ' until ' . $new_appointment['end_time'] . '. Please Apporove or decline it.',
        );

        $this->queue->push($tube, $data_student, 'email.send_email');
        $this->queue->push($tube, $data_coach, 'email.send_email');

//        $partner_admin = $this->identity_model->get_partner_identity('', '', $this->auth_manager->partner_id());
//        foreach ($partner_admin as $p) {
//            $data_partner['email'] = $id_to_email_address[$p->id];
//            $this->queue->push($tube, $data_partner, 'email.send_email');
//        }
        //print_r($data); exit;
        // messaging
        // inserting notification
        if ($new_appointment_id) {
            $database_tube = 'com.live.database';

            // student notification data
            $student_notification['user_id'] = $this->auth_manager->userid();
            $student_notification['description'] = 'You have rescheduled appointment at ' . date('l jS \of F Y', strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' with coach ' . $id_to_name[$new_appointment['coach_id']] . ' to ' . $new_appointment['date'] . ' from ' . $new_appointment['start_time'] . ' until ' . $new_appointment['end_time'] . '.';
            $student_notification['status'] = 2;
            $student_notification['dcrea'] = time();
            $student_notification['dupd'] = time();

            // coach notification data
            $coach_notification['user_id'] = $new_appointment['coach_id'];
            $coach_notification['description'] = 'Your appointment at ' . date('l jS \of F Y', strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' with student ' . $id_to_name[$this->auth_manager->userid()] . ' has been rescheduled to ' . $new_appointment['date'] . ' from ' . $new_appointment['start_time'] . ' until ' . $new_appointment['end_time'] . '.';
            $coach_notification['status'] = 2;
            $coach_notification['dcrea'] = time();
            $coach_notification['dupd'] = time();

//            // partner notification data
//            $partner_notification['description'] = 'Student ' . $id_to_name[$this->auth_manager->userid()] . ' ask for rescheduled appointment at ' . date('l jS \of F Y', strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' with coach ' . $id_to_name[$new_appointment['coach_id']] . ' to ' . $new_appointment['date'] . ' from ' . $new_appointment['start_time'] . ' until ' . $new_appointment['end_time'] . '. Please Apporove or decline it.';
//            $partner_notification['status'] = 2;
//            $partner_notification['dcrea'] = time();
//            $partner_notification['dupd'] = time();
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

            //echo('<pre>');
            //print_r($data_student);
            //print_r($data_coach); exit;
            // messaging inserting data notification
            $this->queue->push($database_tube, $data_student, 'database.insert');
            $this->queue->push($database_tube, $data_coach, 'database.insert');

//            foreach ($partner_admin as $p) {
//                $partner_notification['user_id'] = $p->id;
//                $data_partner = array(
//                    'table' => 'user_notifications',
//                    'content' => $partner_notification,
//                );
//                $this->queue->push($database_tube, $data_partner, 'database.insert');
//            }
        }

        $available_host = $this->webex_host_model->get_available_host($new_appointment_id);
        if($available_host){
            $this->create_session($available_host[0]->id, $new_appointment_id);
            $message = "Session Rescheduled, you will use Webex for your session";
        }else{
            $message = "Session Rescheduled, you will use Skype for your session";
        }
        
        $this->messages->add($message, 'success');
        redirect('student/upcoming_session');
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
        $appointment = $this->appointment_model->select('id, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('date', date("Y-m-d", $date))->where('start_time', $start_time)->where('end_time', $end_time)->get();

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

    public function cancel($appointment_id = '') {
        // checking if appointment has already canceled
        $appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, start_time, end_time, status')->where('id', $appointment_id)->get();
        if ($appointment_data->status == 'cancel') {
            $this->messages->add('Appointment has already canceled', 'danger');
            redirect('student/upcoming_session');
        }

        $webex_host = $this->webex_host_model->get_host($appointment_id);
        $webex = $this->webex_model->select('id')->where('appointment_id', $appointment_id)->get();
        
        if(!$webex_host || !$webex){
            $this->messages->add('Invalid Session', 'error');
            redirect('student/upcoming_session');
        }
        
        // updating appointment (change status to cancel)
        // storing data

        $appointment = array(
            'status' => 'cancel',
        );

        // Updating and checking to appoinment table
        $this->db->trans_begin();
        if (!$this->appointment_model->update($appointment_id, $appointment) || !$this->delete_session($webex_host[0]->id, $appointment_id) || !$this->webex_model->delete($webex->id)) {
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
            if (!$this->create_token_history($appointment_id, $coach_token_cost->token_for_student, $student_token_data->token_amount, 3)) {
                $this->messages->add('Error while create token history', 'danger');
                $this->index();
                return;
            }
        }

        //
        //
        //echo($time_left_before_session); exit;
        // after student canceled an appointment, send email to coach
        // tube name for messaging action
        $tube = 'com.live.email';
        $id_to_email_address = $this->user_model->dropdown('id', 'email');
        $id_to_name = $this->identity_model->get_identity('profile')->dropdown('id', 'fullname');
        $data = array(
            'subject' => 'Appointment Canceled',
            'email' => $id_to_email_address[$appointment_data->coach_id],
            'content' => 'Your appointment with student ' . $id_to_name[$this->auth_manager->userid()] . ' at ' . date("l jS \of F Y", strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' has been canceled',
        );


        $this->queue->push($tube, $data, 'email.send_email');

        //messaging for notification
        $database_tube = 'com.live.database';
        $coach_notification = array(
            'user_id' => $appointment_data->coach_id,
            'description' => 'Your appointment with student ' . $id_to_name[$this->auth_manager->userid()] . ' at ' . date("l jS \of F Y", strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' has been canceled.',
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
        $result = $this->common_function->post_it($d, $URL, $XML_PORT);
        //print_r(htmlspecialchars($result));exit;

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
        $result = $this->common_function->post_it($d, $URL, $XML_PORT);

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
                //$error = $simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->reason;
                return false;
            }
        }
    }
}
