<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class managing extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // load models for student partner
        $this->load->model('user_model');
        $this->load->model('identity_model');
        // load models for class
        $this->load->model('class_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('class_member_model');
        $this->load->model('class_schedule_model');
        $this->load->model('class_week_model');

        // load model for set meeting time
        $this->load->model('schedule_model');
        $this->load->model('offwork_model');
        $this->load->model('appointment_model');
        $this->load->model('coach_day_off_model');

        // for messaging action and timing
        $this->load->library('queue');

        $this->load->library('phpass');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'SPR') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    // function to check class is belong to user as role student_partner
    function isValidClass($class_id = '') {
        $data = $this->class_model->select('id, class_name, student_amount')->where('id', $class_id)->where('student_partner_id', $this->auth_manager->userid())->get();
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

    // function to check if the student has the same partner as student_partner
    function isValidStudent($student_id = '') {
        $partner_id = $this->auth_manager->partner_id();
        $data = $this->identity_model->get_identity('profile')->where('user_id', $student_id)->where('partner_id', $partner_id)->get();
        $active_user = $this->user_model->select('id')->where('id', $student_id)->where('status', 'active')->get();
        if ($data && $active_user) {
            return $data;
        } else {
            return false;
        }
    }

    function isValidCoach($coach_id = '') {
        $partner_id = $this->auth_manager->partner_id();
        $data = $this->identity_model->get_identity('profile')->where('user_id', $coach_id)->where('partner_id', $partner_id)->get();
        $active_user = $this->user_model->select('id')->where('id', $coach_id)->where('status', 'active')->get();
        if ($data && $active_user) {
            return $data;
        } else {
            return false;
        }
    }

    // fucntion to check if the student is assigned to specific class
    function isAssignedStudent($class_id = '', $student_id = '') {
        if ($this->isValidClass($class_id) && $this->isValidStudent($student_id)) {
            $data = $this->class_member_model->select('id')->where('class_id', $class_id)->where('student_id', $student_id)->get();
            if ($data) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
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

    // Index
    public function index() {
        $this->template->title = 'Add Class';
        $vars = array(
            'classes' => $this->class_model->select('id, class_name, student_amount, start_date, end_date')->where('student_partner_id', $this->auth_manager->userid())->where('end_date >=',date('Y-m-d'))->get_all(),
        );
        $this->template->content->view('default/contents/managing/class/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function add_class() {
        $this->template->title = 'Add Class';
        $vars = array(
            'form_action' => 'create_class'
        );
        $this->template->content->view('default/contents/managing/class/form', $vars);
        $this->template->publish();
    }

    public function create_class() {
        // Creating a student user data must be followed by creating profile data and status still disable/need approval from admin
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('student_partner/managing');
        }

        // inserting user data
        $class = array(
            'student_partner_id' => $this->auth_manager->userid(),
            'class_name' => $this->input->post('class_name'),
            'student_amount' => $this->input->post('student_amount'),
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
        );

        // Inserting and checking to users table then storing insert_id into $insert_id
        $this->db->trans_begin();
        $class_id = $this->class_model->insert($class);
        if (!$class_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->class();
            return;
        }
        $this->db->trans_commit();

        // This code is used to devide and count weeks from start date and end date
//        $date1 = strtotime($this->input->post('end_date'));
//        $date2 = strtotime($this->input->post('start_date'));
//        $datediff = $date1 - $date2;
//        $datediff = $datediff/(60*60*24);
//        $weeks = $datediff/7;
//        
//        // ceil() used to round up decimal
//        // to determine days in each week based on start date and end date of class
//        $temp_day = array();
//        for($i=1;$i<=ceil($weeks);$i++){
//            if($i == ceil($weeks)){
//                $temp_day[] = ($datediff) - (((ceil($weeks)-1)*7));
//            }
//            else{
//                $temp_day[] = 7;
//            }
//        }

        $this->messages->add('Inserting Class Succeeded', 'success');
        redirect('student_partner/managing/');
    }

    public function edit_class($class_id = '') {
        // setting id for updating data
        $this->session->set_userdata("class_id", $class_id);

        // checking invalidity class id depends on student partner id
        if ($this->isValidClass($class_id) == false) {
            $this->messages->add('Invalid id', 'danger');
            redirect('student_partner/managing/');
        }

        $this->template->title = 'Edit Class';
        $vars = array(
            'data' => $this->class_model->select('id, class_name, student_amount, start_date, end_date')->where('id', $class_id)->get(),
            'form_action' => 'update_class'
        );
        $this->template->content->view('default/contents/managing/class/form', $vars);
        $this->template->publish();
    }

    public function update_class() {
        // retrive class data
        $class = array(
            'class_name' => $this->input->post('class_name'),
            'student_amount' => $this->input->post('student_amount'),
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
        );

        // updating and checking to class table then storing insert_id into $insert_id
        $this->db->trans_begin();
        $class_id = $this->class_model->update($this->session->userdata("class_id"), $class);
        if (!$class_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            redirect('student_partner/managing/');
        }
        $this->db->trans_commit();

        $this->messages->add('Updating Class Succeeded', 'success');
        redirect('student_partner/managing/');
    }

    public function delete_class($class_id = '') {
        //deleting data if in a day has more than one managing
        if ($this->isValidClass($class_id) == true) {
            $this->class_model->delete($class_id);
            $this->messages->add('Delete Succeeded', 'success');
            redirect('student_partner/managing/');
        }
    }

    public function class_member($class_id = '') {
        // checking invalidity class id depends on student partner id
        if ($this->isValidClass($class_id) == false) {
            $this->messages->add('Invalid id', 'danger');
            redirect('student_partner/managing/');
        }

        $this->template->title = $this->isValidClass($class_id)['class_name'] . ' Class Member';



        $members = $this->class_member_model->get_student_member($class_id);
        
        $vars = array(
            'class_id' => $class_id,
            'members' => $members,
            'title' => $this->isValidClass($class_id)['class_name'],
        );
        $this->template->content->view('default/contents/managing/class/member_index', $vars);

        //publish template
        $this->template->publish();
    }

    public function add_class_member($class_id = '') {
        // checking if the amount of member has excedeed the max amount of class members
        $assigned_student_amount = count($this->class_member_model->get_student_member($class_id));
        $max_class_member = $this->isValidClass($class_id)['student_amount'];
        if ($assigned_student_amount >= $max_class_member) {
            $this->messages->add('Maximum amount of ' . $this->isValidClass($class_id)['class_name'] . ' class member is ' . $max_class_member, 'danger');
            redirect('student_partner/managing/class_member/' . $class_id);
        }

        // checking invalidity class id depends on student partner id
        if ($this->isValidClass($class_id) == false) {
            $this->messages->add('Invalid id', 'danger');
            redirect('student_partner/managing/');
        }


        $this->template->title = 'Add ' . $this->isValidClass($class_id)['class_name'] . ' Class Member';

        //print_r($this->class_member_model->get_unassigned_student($class_id)); exit;
        $vars = array(
            'class_members' => $this->class_member_model->get_unassigned_student($class_id),
            'title' => $this->isValidClass($class_id)['class_name'],
            'class_id' => $class_id,
        );
        $this->template->content->view('default/contents/managing/class_member/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function create_class_member($class_id = '', $student_id = '') {
        // checking if the class and student id is valid and not unssigned to any class
        if ($this->isAssignedStudent($class_id, $student_id) == true) {
            $this->messages->add('Invalid Action', 'danger');
            redirect('student_partner/managing/class_member/' . $class_id);
        } else if ($this->isAssignedStudent($class_id, $student_id) == false) {
            $this->template->title = 'Add ' . $this->isValidClass($class_id)['class_name'] . ' Class Member';

            // inserting member and class data
            $member = array(
                'class_id' => $class_id,
                'student_id' => $student_id,
            );

            // Inserting and checking to users table then storing insert_id into $insert_id
            $this->db->trans_begin();
            $class_member_id = $this->class_member_model->insert($member);
            if (!$class_member_id) {
                $this->db->trans_rollback();
                $this->messages->add(validation_errors(), 'danger');
                $this->class();
                return;
            }
            $this->db->trans_commit();

            if ($class_member_id) {
                $id_to_email_address = $this->user_model->dropdown('id', 'email');
                // tube name for messaging action
                $tube = 'com.live.email';

                $data = array(
                    'subject' => 'Class Appointment',
                    'email' => $id_to_email_address[$student_id],
                    'content' => 'You have been added to a class, please attend the class based on class schedule',
                );

                // after created, sending email to student
                $this->queue->push($tube, $data, 'email.send_email');
            }

            //messaging for notification
            $database_tube = 'com.live.database';
            $class_member_notification = array(
                'user_id' => $student_id,
                'description' =>  'You have been added to a class, please attend the class based on class schedule',
                'status' => 2,
                'dcrea' => time(),
                'dupd' => time(),
            );
            // coach's data for reminder messaging
            // IMPORTANT : array index in content must be in mutual with table field in database
            $data_class_member = array(
                'table' => 'user_notifications',
                'content' => $class_member_notification,
            );

            // messaging inserting data notification
            $this->queue->push($database_tube, $data_class_member, 'database.insert');


            $this->messages->add('Inserting Member to ' . $this->isValidClass($class_id)['class_name'] . ' Class Succeeded', 'success');
            redirect('student_partner/managing/class_member/' . $class_id);
        }
    }

    public function delete_class_member($class_id = '', $student_id = '') {
        //deleting data if in a day has more than one managing
        if ($this->isAssignedStudent($class_id, $student_id) == true) {
            $this->class_member_model->where('class_id', $class_id)->where('student_id', $student_id)->delete();
            $this->messages->add('Delete Succeeded', 'success');
            redirect('student_partner/managing/class_member/' . $class_id);
        }
    }

    public function class_schedule($class_id = '') {
        // checking invalidity class id depends on student partner id
        if ($this->isValidClass($class_id) == false) {
            $this->messages->add('Invalid id', 'danger');
            redirect('student_partner/managing/');
        }

        //$data = $this->class_meeting_day_model->get_schedule($class_id);
        $class_data = $this->class_model->select('id, class_name, student_amount, start_date, end_date')->where('id', $class_id)->get();
        $data = $this->class_meeting_day_model->select('id, date, start_time, end_time, coach_id')->where('class_id', $class_id)->order_by('start_time', 'asc')->get_all();
        $data_temp = array();
        foreach ($data as $d) {
            $data_temp[$d->date][] = $d;
            //print_r($d->date); exit;
        }
        //echo('<pre>');
        //print_r($data_temp); exit;
        //print_r($data_temp); exit;
        // This code is used to devide and count weeks from start date and end date
        $date1 = date_create($class_data->start_date);
        $date2 = date_create($class_data->end_date);
        $interval = date_diff($date1, $date2);
        $datediff = $interval->days + 1;
        $weeks = $datediff / 7;

        // ceil() used to round up decimal
        // to determine days in each week based on start date and end date of class
        $temp_day = array();
        for ($i = 1; $i <= ceil($weeks); $i++) {
            if ($i == ceil($weeks)) {
                $temp_day[] = ($datediff) - (((ceil($weeks) - 1) * 7));
            } else {
                $temp_day[] = 7;
            }
        }

        $date1 = strtotime($class_data->start_date);
        $date2 = strtotime($class_data->end_date);

        $date = $class_data->start_date;
        $temp_week = array();
        for ($i = 0; $i < count($temp_day); $i++) {
            for ($j = 0; $j < $temp_day[$i]; $j++) {
                // adding days based on wich week. Multiple it by 7
                $date = mktime(0, 0, 0, date("m", $date1), date("d", $date1) + ($j + ($i * 7)), date("Y", $date1));
                $temp_week[$i][date('Y-m-d', $date)] = date('D j M Y', $date);
            }
        }
        //print_r($temp_week); exit;


        $this->template->title = 'Set Class Schedule';

        $vars = array(
            'class_data' => $class_data,
            'week' => $temp_week,
            'data' => $data,
            'schedule' => $data_temp,
            'date_range' => $datediff,
            'start_date' => $class_data->start_date,
            'end_date' => $class_data->end_date,
            'class_id' => $class_id,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
        );
        $this->template->content->view('default/contents/managing/class_schedule/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function set_class_schedule($class_id = '', $date = '') {
        if ($date <= date('Y-m-d')) {
            $this->messages->add('Invalid Date', 'danger');
            redirect('student_partner/managing/class_schedule/' . $class_id);
        }
        
        if ($this->isValidDate($class_id, $date) == false) {
            $this->messages->add('Invalid Action', 'danger');
            redirect('student_partner/managing/class_schedule/' . $class_id);
        }
        
        $data = $this->get_available_coach($date);
        //echo('<pre>');
        //print_r ($data); exit;

        $this->template->title = 'Add Class Schedule';
        //$data = $this->identity_model->get_coach_identity(null, null, null, $this->auth_manager->partner_id(), '');

        $vars = array(
            'title' => $this->isValidClass($class_id)['class_name'],
            'class_id' => $class_id,
            'date' => strtotime($date),
            'data' => $data,
        );
        $this->template->content->view('default/contents/managing/class_schedule/assign_coach_form', $vars);

        //publish template
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
                    'coach_id' => $coach_id,
                    'fullname' => $cd->fullname,
                    'country' => $cd->country,
                    'phone' => $cd->phone,
                    'token_for_student' => $cd->token_for_student,
                    'availability' => $availability_temp,
                );
            }
        }


        return ($available_coach);
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

    public function set_meeting_time($class_id = '', $coach_id = '', $date = '') {
        if (!$this->isValidDate($class_id, $date) || !$this->isValidCoach($coach_id)) {
            $this->messages->add('Invalid Action', 'danger');
            redirect('student_partner/managing/class_schedule/' . $class_id);
        }

        $this->template->title = 'Book Meeting time';
        //getting the day of $date
        $date = strtotime($date);
        $day = strtolower(date('l', $date));

        //getting all data
        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
        $offwork = $this->offwork_model->get_offwork($coach_id, $schedule_data->day);
        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student
        $appointment = $this->appointment_model->select('id, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('date', date("Y-m-d", $date))->get_all();
        // appointment in class
        $meeting_time = $this->class_meeting_day_model->select('id, start_time, end_time')->where('class_id', $class_id)->where('coach_id', $coach_id)->where('date', date('Y-m-d', $date))->get_all();


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

        foreach ($meeting_time as $m) {
            $appointment_temp[$m->start_time] = $m->start_time;
            $appointment_temp[$m->end_time] = $m->end_time;
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
                        // no action
                    } else {
                        // storing availability that still active and not been boooked yet
                        $availability_temp[] = $availability_exist;
                    }
                }
            }
        }
        //print_r($availability_temp); exit;
        $vars = array(
            'title' => $this->isValidClass($class_id)['class_name'],
            'availability' => $availability_temp,
            'class_id' => $class_id,
            'coach_id' => $coach_id,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('id', 'fullname'),
            'date' => $date,
        );

        $this->template->content->view('default/contents/managing/class_schedule/availability_form', $vars);
        $this->template->publish();
    }

    public function create_meeting_day($class_id = '', $coach_id = '', $date = '', $start_time = '', $end_time = '') {
        if (!$this->isValidDate($class_id, $date) || !$this->isValidCoach($coach_id)) {
            $this->messages->add('Invalid Action', 'danger');
            redirect('student_partner/managing/class_schedule/' . $class_id);
        }

        // inserting member and class data
        $meeting_day = array(
            'class_id' => $class_id,
            'coach_id' => $coach_id,
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
        );

        // Inserting and checking to users table then storing insert_id into $insert_id
        $this->db->trans_begin();
        $meeting_day_id = $this->class_meeting_day_model->insert($meeting_day);
        if (!$meeting_day_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->class();
            return;
        }
        $this->db->trans_commit();

        if ($meeting_day_id) {
            $id_to_email_address = $this->user_model->dropdown('id', 'email');
            // tube name for messaging action
            $tube = 'com.live.email';

            $data = array(
                'subject' => 'Class Appointment',
                'email' => $id_to_email_address[$coach_id],
                'content' => 'You have an appointment in class at ' . $date . ' from ' . $start_time . ' until ' . $end_time,
            );

            // after created, sending email to student
            $this->queue->push($tube, $data, 'email.send_email');
            
            //messaging for notification
            $database_tube = 'com.live.database';
            $coach_notification = array(
                'user_id' => $coach_id,
                'description' =>  'You have an appointment in class at ' . $date . ' from ' . $start_time . ' until ' . $end_time,
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
        }

        $this->messages->add('Class Schedule Added', 'success');
        redirect('student_partner/managing/class_schedule/' . $class_id);
    }

}
