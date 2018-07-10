<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class manage_session extends MY_Site_Controller {

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

        // for messaging action and timing
        $this->load->library('phpass');
        $this->load->library('queue');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CAM') {
            $this->messages->add('Access Denied', 'danger');
            redirect('account/identity/profile');
        }
    }

    // Index
    public function index() {
        
    }

    public function reschedule($student_id = '', $appointment_id = '') {
        $this->template->title = 'Reschedule Appointment';

        // checking if appointment has already rescheduled
        $appointment_reschedule_data = $this->appointment_reschedule_model->select('id')->where('appointment_id', $appointment_id)->get();
        if ($appointment_reschedule_data) {
            $this->messages->add('apppointment has already rescheduled', 'danger');
            redirect('partner_monitor/manage_session/reschedule/'.$student_id.'/'.$appointment_id);
        }

        $appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, status')->where('id', $appointment_id)->where('student_id', $student_id)->get();

        if (!$appointment_data) {
            $this->messages->add('No Appointment Found', 'danger');
            redirect('partner_monitor/manage_session/reschedule/'.$student_id.'/'.$appointment_id);
        }

        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id', $appointment_data->coach_id)->get_all();

        if (!$schedule_data) {
            redirect('partner_monitor/manage_session/reschedule/'.$student_id.'/'.$appointment_id);
        }
        $coach_name = $this->identity_model->get_identity('profile')->select('fullname')->where('user_id', $appointment_data->coach_id)->get();
        $vars = array(
            'appointment_id' => $appointment_id,
            'student_id' => $student_id,
            'coach_id' => $appointment_data->coach_id,
            'coach_name' => $coach_name->fullname,
        );
        $this->template->content->view('default/contents/partner_monitor/manage_session/reschedule/schedule_detail', $vars);

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

    public function reschedule_booking($student_id = '', $appointment_id = '', $coach_id = '', $date = '', $start_time = '', $end_time = '') {
        //$this->db->trans_rollback(); exit;
        $date = strtotime($date);
        $appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, start_time, end_time')->where('id', $appointment_id)->where('student_id', $student_id)->get();
        
        if (!$appointment_data) {
            $this->messages->add('Invalid Appointment', 'danger');
            redirect('partner_monitor/schedule/manage/'.$student_id);
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
                $this->messages->add('Invalid Action', 'danger');
                $this->index();
                return;
            }
        } else {
            $this->messages->add('Invalid Action', 'danger');
            redirect('partner_monitor/manage_session/reschedule/'.$student_id.'/'.$appointment_id);
        }

        // updating appointment current status to reschedule
        $appointment_update = array(
            'status' => 'reschedule',
        );

        if (!$this->appointment_model->update($appointment_id, $appointment_update)) {
            $this->db->trans_rollback();
            $this->messages->add('Invalid Action', 'danger');
            redirect('partner_monitor/manage_session/reschedule/'.$student_id.'/'.$appointment_id);
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
                    $this->db->trans_rollback();
                    $this->messages->add(validation_errors(), 'danger');
                    $this->index();
                    return;
                }

                if (!$this->create_token_history($student_id, $appointment_id, $coach_token_cost->token_for_student, $student_token, 9)) {
                    $this->db->trans_rollback();
                    $this->messages->add('Error while create token history', 'danger');
                    $this->index();
                    return;
                }
            } else {
                if (!$this->create_token_history($student_id, $appointment_id, $coach_token_cost->token_for_student, $student_token_data->token_amount, 7)) {
                    $this->db->trans_rollback();
                    $this->messages->add('Error while create token history', 'danger');
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

        // inserting new appointment to appointment table
        $new_appointment_id = $this->appointment_model->insert($new_appointment);
        if (!$new_appointment_id) {
            $this->db->trans_rollback();
            $this->messages->add('Invalid Action', 'danger');
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
                $this->db->trans_rollback();
                $this->messages->add(validation_errors(), 'danger');
                $this->index();
                return;
            }

            if (!$this->create_token_history($student_id, $appointment_id, $coach_token_cost->token_for_student, $student_token, 1)) {
                $this->db->trans_rollback();
                $this->messages->add('Error while create token history', 'danger');
                $this->index();
                return;
            }
        }

        $this->db->trans_commit();

        
        // after coach partner rescheduled an appointment, send email to coach and to student
        // tube name for messaging action
        $tube = 'com.live.email';
        $id_to_email_address = $this->user_model->dropdown('id', 'email');
        $id_to_name = $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname');
        $data_student = array(
            'subject' => 'Appointment Rescheduled',
            'email' => $id_to_email_address[$student_id],
            'content' => 'Your appointment has been rescheduled by Coach Affiliate ' . $id_to_name[$this->auth_manager->userid()]. '. ' . date('l jS \of F Y', strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' with coach ' . $id_to_name[$new_appointment['coach_id']] . ' become ' . $new_appointment['date'] . ' from ' . $new_appointment['start_time'] . ' until ' . $new_appointment['end_time'] . '.',
        );

        $data_coach = array(
            'subject' => 'Appointment Rescheduled',
            'email' => $id_to_email_address[$coach_id],
            'content' => 'Your appointment has been rescheduled by Coach Affiliate ' . $id_to_name[$this->auth_manager->userid()]. '. ' . date('l jS \of F Y', strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' with student ' . $id_to_name[$student_id] . ' become ' . $new_appointment['date'] . ' from ' . $new_appointment['start_time'] . ' until ' . $new_appointment['end_time'] . '.'
        );

        $data_partner = array(
            'subject' => 'Appointment Rescheduled',
            'email' => $id_to_email_address[$this->auth_manager->userid],
            'content' => 'You have been rescheduled appointment between student '. $id_to_name[$student_id] . ' and coach ' . $id_to_name[$coach_id] .'. '.  date('l jS \of F Y', strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time .  ' become ' . $new_appointment['date'] . ' from ' . $new_appointment['start_time'] . ' until ' . $new_appointment['end_time'] . '.'
        );

        $this->queue->push($tube, $data_student, 'email.send_email');
        $this->queue->push($tube, $data_coach, 'email.send_email');
        $this->queue->push($tube, $data_partner, 'email.send_email');
        
        ////
        //  MESSAGING //////////////////////////////////////////////////////////
        ////
        // inserting notification
        if ($new_appointment_id) {
            $database_tube = 'com.live.database';

            // student notification data
            $student_notification = Array(
                'user_id' => $student_id,
                'description' => 'Your appointment has been rescheduled by Coach Affiliate ' . $id_to_name[$this->auth_manager->userid()]. '. ' . date('l jS \of F Y', strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' with coach ' . $id_to_name[$new_appointment['coach_id']] . ' become ' . $new_appointment['date'] . ' from ' . $new_appointment['start_time'] . ' until ' . $new_appointment['end_time'] . '.',
                'status' => '2',
                'dcrea' => time(),
                'dupd' => time()
            );
            
            // coach notification data
            $coach_notification = Array(
                'user_id' => $new_appointment['coach_id'],
                'description' => 'Your appointment has been rescheduled by Coach Affiliate ' . $id_to_name[$this->auth_manager->userid()]. '. ' . date('l jS \of F Y', strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' with student ' . $id_to_name[$student_id] . ' become ' . $new_appointment['date'] . ' from ' . $new_appointment['start_time'] . ' until ' . $new_appointment['end_time'] . '.',
                'status' => '2',
                'dcrea' => time(),
                'dupd' => time()
            );
            
            // coach partner notification data
            $coach_partner_notification = Array(
                'user_id' => $this->auth_manager->userid(),
                'description' => 'You have been rescheduled appointment between student '. $id_to_name[$student_id] . ' and coach ' . $id_to_name[$coach_id] .'. '.  date('l jS \of F Y', strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time .  ' become ' . $new_appointment['date'] . ' from ' . $new_appointment['start_time'] . ' until ' . $new_appointment['end_time'] . '.',
                'status' => '2',
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
            
            // coach partner's data for reminder messaging
            // IMPORTANT : array index in content must be in mutual with table field in database
            $data_coach_partner = array(
                'table' => 'user_notifications',
                'content' => $coach_partner_notification,
            );

            // messaging inserting data notification
            $this->queue->push($database_tube, $data_student, 'database.insert');
            $this->queue->push($database_tube, $data_coach, 'database.insert');
            $this->queue->push($database_tube, $data_coach_partner, 'database.insert');
        }

        $this->messages->add('Session Rescheduled', 'success');
        redirect('partner_monitor/schedule/manage/'.$student_id);
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

    public function cancel($student_id = '', $appointment_id = '') {
        // checking if appointment has already cancelled
        $appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, start_time, end_time, status')->where('id', $appointment_id)->get();
        if ($appointment_data->status == 'cancel') {
            $this->messages->add('Appointment has already cancelled', 'danger');
            redirect('partner_monitor/schedule/manage/'.$student_id);
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

            if (!$this->create_token_history($student_id, $appointment_id, $coach_token_cost->token_for_student, $student_token, 5)) {
                $this->messages->add('Error while create token history', 'danger');
                $this->index();
                return;
            }
        } else {
            if (!$this->create_token_history($student_id, $appointment_id, $coach_token_cost->token_for_student, $student_token_data->token_amount, 3)) {
                $this->messages->add('Error while create token history', 'danger');
                $this->index();
                return;
            }
        }

        //
        //
        //echo($time_left_before_session); exit;
        // after student cancelled an appointment, send email to coach
        // tube name for messaging action
        $tube = 'com.live.email';
        $id_to_email_address = $this->user_model->dropdown('id', 'email');
        $id_to_name = $this->identity_model->get_identity('profile')->dropdown('id', 'fullname');
        $data = array(
            'subject' => 'Appointment Cancelled',
            'email' => $id_to_email_address[$appointment_data->coach_id],
            'content' => 'Your appointment with student ' . $id_to_name[$student_id] . ' at ' . date("l jS \of F Y", strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' has been cancelled',
        );


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
        redirect('partner_monitor/schedule/manage/'.$student_id);
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
            $this->messages->add('Invalid apppointment id', 'danger');
            redirect('partner_monitor/manage_session/reschedule/'.$student_id.'/'.$appointment_id);
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

}
