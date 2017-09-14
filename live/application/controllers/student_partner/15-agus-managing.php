<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class managing extends MY_Site_Controller {

    var $day_index = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');

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
        $this->load->model('partner_model');
        $this->load->model('partner_setting_model');
        $this->load->model('subgroup_model');

        // load model for set meeting time
        $this->load->model('schedule_model');
        $this->load->model('offwork_model');
        $this->load->model('appointment_model');
        $this->load->model('coach_day_off_model');

        $this->load->model('webex_host_model');
        $this->load->model('class_member_model');
        $this->load->model('class_meeting_day_reschedule_model');
        $this->load->model('webex_class_model');

        // for student
        $this->load->model('user_token_model');

        // for messaging action and timing
        $this->load->library('queue');
        $this->load->library('schedule_function');
        $this->load->library('webex_function');

        $this->load->library('phpass');
        $this->load->library('email_structure');
        $this->load->library('send_email');


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
    public function index($page = '') {
        $this->template->title = 'Manage Classes';
        $offset = 0;
        $per_page = 5;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/managing/index'), count($this->class_model->select('id, class_name, student_amount, start_date, end_date')->where('student_partner_id', $this->auth_manager->userid())->where('end_date >=', date('Y-m-d'))->get_all()), $per_page, $uri_segment);

        $vars = array(
            'classes' => $this->class_model->select('id, class_name, student_amount, start_date, end_date')->where('student_partner_id', $this->auth_manager->userid())->where('end_date >=', date('Y-m-d'))->limit($per_page)->offset($offset)->get_all(),
            'pagination' => @$pagination
        );
        $this->template->content->view('default/contents/managing/class/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function add_class() {
        $setting = $this->partner_setting_model->where('partner_id',$this->auth_manager->partner_id())->get();
        $max_student_class = $setting->max_student_class;

        $this->template->title = 'Add Class';
         // get sub group by partner id
        // $getsubgroup = $this->subgroup_model->select('*')->where('partner_id',$this->auth_manager->partner_id())->where('type','student')->get_all();

        // $subgroup = '';
        // foreach ($getsubgroup as $value) {
        //     $subgroup[$value->id] = $value->name; 
        // }

        $vars = array(
            'form_action' => 'create_class',
            'max_student_class' => $max_student_class,
            // 'subgroup' => $subgroup
        );
        $this->template->content->view('default/contents/managing/class/form', $vars);
        $this->template->publish();
    }

    public function create_class() {
        // Creating a student user data must be followed by creating profile data and status still disable/need approval from admin
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'warning');
            redirect('student_partner/managing');
        }

        $rules = array(
            array('field' => 'class_name', 'label' => 'Class Name', 'rules' => 'trim|required|xss_clean|max_length[150]'),
            array('field' => 'start_date', 'label' => 'Start Date', 'rules' => 'trim|required|xss_clean'),
            array('field' => 'end_date', 'label' => 'End Date', 'rules' => 'trim|required|xss_clean'),
            array('field' => 'token_cost', 'label' => 'Token Cost', 'rules' => 'trim|required|xss_clean|numeric|max_length[150]')
        );

        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            $this->add_class();
            return;
        }
        // cek maksimum student dari partner setting
        // $get_max_student = $this->partner_setting_model->select('max_student_class')->where('partner_id','1')->get();
        $setting = $this->partner_setting_model->where('partner_id',$this->auth_manager->partner_id())->get();
        // inserting user data
        $class = array(
            // 'id_subgroup' => $this->input->post('subgroup'),
            'id_subgroup' => '',
            'student_partner_id' => $this->auth_manager->userid(),
            'class_name' => $this->input->post('class_name'),
            'student_amount' => $setting->max_student_class,
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
            'token_cost' => $this->input->post('token_cost'),
        );

        // Inserting and checking to users table then storing insert_id into $insert_id
        $this->db->trans_begin();
        $class_id = $this->class_model->insert($class);
        if (!$class_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'warning');
            $this->class();
            return;
        }
        $this->db->trans_commit();

        $this->messages->add('Inserting Class Succeeded', 'success');
        redirect('student_partner/managing/');
    }

    public function edit_class($class_id = '') {
        // setting id for updating data
        $this->session->set_userdata("class_id", $class_id);

        // checking invalidity class id depends on student partner id
        if ($this->isValidClass($class_id) == false) {
            $this->messages->add('Invalid id', 'warning');
            redirect('student_partner/managing/');
        }

        $class = $this->class_model->select('id, class_name, student_amount, start_date, end_date')->where('id', $class_id)->get();
        if (!$class) {
            $this->messages->add('Invalid class', 'error');
            redirect('student_partner/managing/');
        }

        if (strtotime($class->start_date) > strtotime(date('Y-m-d'))) {
            $start_date = date('Y-m-d');
        }
        $this->template->title = 'Edit Class';
        $vars = array(
            'data' => $class,
            'start_date' => @$start_date,
            'start_date_' => $class->start_date,
            'end_date' => $class->end_date,
            'form_action' => 'update_class'
        );
        //print_r($vars);exit;
        $this->template->content->view('default/contents/managing/class/form', $vars);
        $this->template->publish();
    }

    public function update_class() {
        // retrive class data
        $class_data = array(
            'class_name' => $this->input->post('class_name'),
            'student_amount' => $this->input->post('student_amount'),
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
        );

        $rules = array(
            array('field' => 'class_name', 'label' => 'Class Name', 'rules' => 'trim|required|xss_clean|max_length[150]'),
            array('field' => 'student_amount', 'label' => 'Student Amount', 'rules' => 'trim|required|xss_clean|numeric|max_length[150]'),
            array('field' => 'start_date', 'label' => 'Start Date', 'rules' => 'trim|required|xss_clean'),
            array('field' => 'end_date', 'label' => 'End Date', 'rules' => 'trim|required|xss_clean'),
        );

        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            $this->edit_class($this->session->userdata("class_id"));
            return;
        }

        $class = $this->class_model->select('id, end_date')->where('id', $this->session->userdata("class_id"))->get();
        if (!$class) {
            $this->messages->add('Ivalid action', 'error');
            redirect('student_partner/managing');
        }

        if (strtotime($this->input->post('start_date')) < strtotime(date('Y-m-d')) || strtotime($this->input->post('end_date')) < $class->end_date) {
            $this->messages->add('Ivalid action', 'error');
            redirect('student_partner/managing');
        }

        /* $end_vanish_dates = array();
          $start_vanish_dates = array();
          if($date->start_date != $this->input->post('start_date') || $date->end_date != $this->input->post('end_date')){
          if($this->input->post('end_date') < $date->end_date){
          $end_vanish_dates = $this->common_function->create_date_range_array($this->input->post('end_date'), $date->end_date);
          unset($end_vanish_dates[0]);
          }
          if($this->input->post('start_date') > $date->start_date){
          $start_vanish_dates = $this->common_function->create_date_range_array($date->start_date, $this->input->post('start_date'));
          unset($start_vanish_dates[count($start_vanish_dates)-1]);
          }

          $vanish_dates = array_merge($end_vanish_dates, $start_vanish_dates);
          } */

        // updating and checking to class table then storing insert_id into $insert_id
        $this->db->trans_begin();
        $class_id = $this->class_model->update($this->session->userdata("class_id"), $class_data);
        if (!$class_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'warning');
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
            $this->messages->add('Invalid id', 'warning');
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
            $this->messages->add('Maximum amount of ' . $this->isValidClass($class_id)['class_name'] . ' class member is ' . $max_class_member, 'warning');
            redirect('student_partner/managing/class_member/' . $class_id);
        }

        // checking invalidity class id depends on student partner id
        if ($this->isValidClass($class_id) == false) {
            $this->messages->add('Invalid id', 'warning');
            redirect('student_partner/managing/');
        }


        $this->template->title = 'Add ' . $this->isValidClass($class_id)['class_name'] . ' Class Member';

        //print_r($this->class_member_model->get_unassigned_student($class_id)); exit;
        $vars = array(
            'class_members' => $this->class_member_model->get_unassigned_student($class_id),
            'title' => $this->isValidClass($class_id)['class_name'],
            'class_id' => $class_id,
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();
        $this->template->content->view('default/contents/managing/class_member/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function create_class_member($class_id = '', $student_id = '') {
        // checking if the class and student id is valid and not unssigned to any class
        if ($this->isAssignedStudent($class_id, $student_id) == true) {
            $this->messages->add('Invalid Action', 'warning');
            redirect('student_partner/managing/class_member/' . $class_id);
        } else if ($this->isAssignedStudent($class_id, $student_id) == false) {
            $this->template->title = 'Add ' . $this->isValidClass($class_id)['class_name'] . ' Class Member';

            // get info student
            $getemail = $this->user_model->select('id, email')->where('id', $student_id)->get_all();
            $getname = $this->user_profile_model->select('user_id, fullname')->where('user_id', $student_id)->get_all();
            $getclass = $this->class_model->select('id, class_name')->where('id', $class_id)->get_all();
            $partnername = $this->user_profile_model->select('user_id, fullname')->where('user_id', $this->auth_manager->userid())->get_all();
            $partneremail = $this->user_model->select('id, email')->where('id', $this->auth_manager->userid())->get_all();
            $member = array(
                'class_id' => $class_id,
                'student_id' => $student_id,
                'email_student' => $getemail[0]->email,
                'name_student' => $getname[0]->fullname,
                'class_name' => $getclass[0]->class_name,
                'email_partner' => $partneremail[0]->email,
                'name_partner' => $partnername[0]->fullname,
            );
            // =============
            // inserting member and class data
            $member = array(
                'class_id' => $class_id,
                'student_id' => $student_id,
            );

            $this->create_token_history($class_id, $student_id);
            // Inserting and checking to users table then storing insert_id into $insert_id
            $this->db->trans_begin();
            $class_member_id = $this->class_member_model->insert($member);
            if (!$class_member_id) {
                $this->db->trans_rollback();
                $this->messages->add(validation_errors(), 'warning');
                $this->class();
                return;
            }
            $this->db->trans_commit();


            ///////////////////////////////////////////
            // Invite new member class to WebEX session
            ///////////////////////////////////////////
            $session_key_and_webex_host_info = $this->class_meeting_day_model->get_session_key_and_webex_host_info($class_id);

            $student = $this->user_model->get_user($student_id);
            $class = $this->class_model->where('id', $class_id)->get();
            if ($session_key_and_webex_host_info && $student && $class) {
                foreach ($session_key_and_webex_host_info as $skwhi) {
                    $converted_date = $this->schedule_function->convert_book_schedule(($this->identity_model->get_gmt($student_id)[0]->minutes), strtotime($skwhi->date), $skwhi->start_time, $skwhi->end_time);
                    if ($skwhi->webex_meeting_number && $class->student_amount < $skwhi->max_user) {
                        if ($this->webex_function->invite_to_session($student[0]->name, $student[0]->email, $skwhi->webex_meeting_number, $skwhi->webex_id, $skwhi->password, $skwhi->subdomain_webex, $skwhi->partner_id)) {


                            $student_id_notification = array(
                                'user_id' => $student_id,
                                'description' => 'You have session in class ' . $class->class_name . ' on '. date('l jS \of F Y', $converted_date['date']) .' at '. substr($converted_date['start_time'],0,5) . ' until '. substr($converted_date['end_time'],0,5),
                                'status' => 2,
                                'dcrea' => time(),
                                'dupd' => time(),
                            );
                            // echo "<pre>";
                            // print_r($student_id_notification);
                            // exit;
                            // coach's data for reminder messaging
                            // IMPORTANT : array index in content must be in mutual with table field in database
                            $data_student_id = array(
                                'table' => 'user_notifications',
                                'content' => $student_id_notification,
                            );

                            // messaging inserting data notification

                            $this->user_notification_model->insert($student_id_notification);

                            // messaging inserting data notification
                            // $this->queue->push($database_tube, $data_class_member, 'database.insert');
                        }
                    }
                }
            }

            if ($class_member_id) {
                $id_to_email_address = $this->user_model->dropdown('id', 'email');
                
                $data = array(
                    'subject' => 'Class Appointment',
                    'email' => $id_to_email_address[$student_id],
                        //'content' => 'You have been added to a class ' .$class_name->class_name. ', please attend the class based on class schedule',
                );
                $data['content'] = $this->email_structure->header()
                        . $this->email_structure->title('Class Appointment')
                        . $this->email_structure->content('You have been added to a class ' . $class->class_name . ', please attend the class based on class schedule')
                        //.$this->email_structure->button('JOIN SESSION')
                        . $this->email_structure->footer('');

                // after created, sending email to student
                // $this->queue->push($email_tube, $data, 'email.send_email');
            }

            //messaging for notification
            $class_member_notification = array(
                'user_id' => $student_id,
                'description' => 'You have been added to a class' . $class->class_name . ', please attend the class based on class schedule',
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

            $this->user_notification_model->insert($class_member_notification);
            
            // messaging inserting data notification
            // $this->queue->push($database_tube, $data_class_member, 'database.insert');
            $this->send_email->notif_student_partner($getemail[0]->email, $getname[0]->fullname, $getclass[0]->class_name, $partneremail[0]->email, $partnername[0]->fullname, 'created');
            $this->send_email->create_class_member($getemail[0]->email, $getname[0]->fullname, $getclass[0]->class_name, $partneremail[0]->email, $partnername[0]->fullname, 'created');

            $this->messages->add('Inserting Member to ' . $this->isValidClass($class_id)['class_name'] . ' Class Succeeded', 'success');
            redirect('student_partner/managing/class_member/' . $class_id);
        }
    }

    private function create_token_history($class_id = '', $student_id = '') {
        $class = $this->class_model->where('id', $class_id)->get();
        $student = $this->user_token_model->where('user_id', $student_id)->get();
        $class_cost = $class->token_cost;
        $student_token = $student->token_amount;
        $remain_token = $student_token - $class_cost;

        if (!$class || !$student) {
            $this->messages->add('Invalid class or student', 'warning');
            redirect('student_partner/managing');
        }

        if ($remain_token < 0) {
            $this->messages->add('Student Does not Have Enough Token to Attend the Class.', 'warning');
            redirect('student_partner/managing/class_schedule/' . $class_id);
        }
        $token_history = array(
            'user_id' => $student_id,
            'transaction_date' => strtotime(date('d-m-Y')),
            'description' => 'Class ' . $class->class_name . ' Fee.',
            'token_amount' => $class_cost,
            'token_status_id' => 1,
            'balance' => $remain_token
        );
        if ($this->token_histories_model->insert($token_history)) {
            return true;
        } else {
            return false;
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
            $this->messages->add('Invalid id', 'warning');
            redirect('student_partner/managing/');
        }

        //$data = $this->class_meeting_day_model->get_schedule($class_id);
        $class_data = $this->class_model->select('id, class_name, student_amount, start_date, end_date')->where('id', $class_id)->get();
        $data = $this->class_meeting_day_model->select('id, date, start_time, end_time, coach_id')->where(Array('class_id' => $class_id, 'status' => 'active'))->order_by('start_time', 'asc')->get_all();
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

    public function set_class_schedule($class_id = '', $date = '', $action = '', $class_meeting_id = '') {
        if ($date <= date('Y-m-d')) {
            $this->messages->add('Invalid Date', 'warning');
            redirect('student_partner/managing/class_schedule/' . $class_id);
        }

        if ($this->isValidDate($class_id, $date) == false || !$action || ($action == 're' && !$class_meeting_id) || ($action != 're' && $action != 'set')) {
            $this->messages->add('Invalid Action', 'warning');
            redirect('student_partner/managing/class_schedule/' . $class_id);
        }

        if (!$this->class_member_model->where('class_id', $class_id)->get_all()) {
            $this->messages->add('There is no student in this class, add student first!', 'warning');
            redirect('student_partner/managing/class_schedule/' . $class_id);
        }

        $data = $this->get_available_coach($date);

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
        $this->template->content->view('default/contents/managing/class_schedule/assign_coach_form', $vars);

        //publish template
        $this->template->publish();
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
            if ($this->is_date_available(trim($date_), 1) && !$this->is_day_off($coach_id, $date_) == true) {
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
                
            }

            if ($availability_temp) {
                if($coach_id_ && $coach_id == $coach_id_){
                   // none 
                }
                else{

            // echo "<pre>";
            // echo count($availability_temp);
            
            // print_r($availability_temp);
            // exit();


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

    private function convertTime($time = '') {
        if (date("H:i", strtotime(1 . 'minutes', strtotime($time))) != '00:00' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '01') {
            return date("H:i", strtotime(1 . 'minutes', strtotime($time)));
        } else {
            return $time;
        }
    }

    private function joinTime($schedule = '') {
        $schedule_temp = array();
        if (count($schedule) > 1) {
            for ($i = 0; $i < (count($schedule)); $i++) {
                if ($schedule[$i]['start_time'] != $schedule[$i]['end_time']) {
                    if ($i < (count($schedule) - 1) && strtotime($schedule[$i]['end_time']) == strtotime($schedule[$i + 1]['start_time'])) {
                        $schedule_temp[] = array(
                            'start_time' => $schedule[$i]['start_time'],
                            'end_time' => $schedule[$i + 1]['end_time'],
                        );
                        $i++;
                    } else {
                        $schedule_temp[] = array(
                            'start_time' => $schedule[$i]['start_time'],
                            'end_time' => $schedule[$i]['end_time'],
                        );
                    }
                }
            }
        } else if (count($schedule) == 1) {
            if ($schedule[0]['start_time'] != $schedule[0]['end_time']) {
                $schedule_temp[] = array(
                    'start_time' => $schedule[0]['start_time'],
                    'end_time' => $schedule[0]['end_time'],
                );
            }
        }

        return $schedule_temp;
    }

    private function session_duration($partner_id = '') {
        $setting = $this->partner_setting_model->get();
        return $setting->session_duration;
    }

    private function isValidAppointment($start_time = '', $end_time = '', $start_time_temp = '', $end_time_temp = '') {
        $status = true;
        for ($i = 0; $i < count($start_time_temp); $i++) {
            if (DateTime::createFromFormat('H:i:s', $start_time_temp[$i]) >= DateTime::createFromFormat('H:i:s', $start_time) && DateTime::createFromFormat('H:i:s', $start_time_temp[$i]) < DateTime::createFromFormat('H:i:s', $end_time)) {
                $status = false;
                break;
            } else if (DateTime::createFromFormat('H:i:s', $end_time_temp[$i]) > DateTime::createFromFormat('H:i:s', $start_time) && DateTime::createFromFormat('H:i:s', $start_time_temp[$i]) < DateTime::createFromFormat('H:i:s', $start_time)) {
                $status = false;
                break;
            }
        }
        return $status;
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
            $this->messages->add('Invalid Action', 'warning');
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
            $this->messages->add('Invalid Action', 'warning');
            redirect('student_partner/managing/class_schedule/' . $class_id);
        }

        // inserting member and class data
        $meeting_day = array(
            'class_id' => $class_id,
            'coach_id' => $coach_id,
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => 'active'
        );

        // Inserting and checking to users table then storing insert_id into $insert_id
        $this->db->trans_begin();
        $meeting_day_id = $this->class_meeting_day_model->insert($meeting_day);
        if (!$meeting_day_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'warning');
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
                    //'content' => 'You have an appointment in class at ' . $date . ' from ' . $start_time . ' until ' . $end_time,
            );
            $data['content'] = $this->email_structure->header()
                    . $this->email_structure->title('Class Appointment')
                    . $this->email_structure->content('You have an appointment in class at ' . $date . ' from ' . $start_time . ' until ' . $end_time)
                    //.$this->email_structure->button('JOIN SESSION')
                    . $this->email_structure->footer('');

            // after created, sending email to student
            $this->queue->push($tube, $data, 'email.send_email');

            //messaging for notification
            $database_tube = 'com.live.database';
            $coach_notification = array(
                'user_id' => $coach_id,
                'description' => 'You have an appointment in class at ' . $date . ' from ' . $start_time . ' until ' . $end_time,
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

        $available_host = $this->webex_host_model->get_available_host('c' . $meeting_day_id);
        if ($available_host && $this->webex_function->create_session($available_host[0]->id, 'c' . $meeting_day_id)) {
            $message = "Class Schedule Added, you will use Webex for your session";
        } else {
            $message = "Class Schedule Added, you will use Skype for your session";
        }

        $this->messages->add($message, 'success');
        redirect('student_partner/managing/class_schedule/' . $class_id);
    }

    public function do_reschedule_session($class_id = '', $class_meeting_day_id = '', $coach_id = '', $date_ = '', $start_time = '', $end_time = '') {
        $date = strtotime($date_);
        $class_meeting_day = $this->class_meeting_day_model->select('id, coach_id, date, start_time, end_time, class_id')->where('id', $class_meeting_day_id)->get();
        if (!$class_meeting_day) {
            $this->messages->add('Invalid Session 1', 'error');
            redirect('student_partner/managing');
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

        $webex_host = $this->webex_host_model->get_host("c" . $class_meeting_day_id);
        $webex_class = $this->webex_class_model->select('id')->where('class_meeting_id', $class_meeting_day_id)->get();

        if ($webex_host && $webex_class) {
            if (!$this->webex_function->delete_session($webex_host[0]->id, "c" . $class_meeting_day_id) || !$this->webex_class_model->delete($webex_class->id)) {
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
                        . $this->email_structure->title('Appointment Rescheduled')
                        . $this->email_structure->content('You have rescheduled appointment at ' . date('l jS \of F Y', strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with ' . $id_to_name[$new_class_meeting_day['coach_id']] . ' to ' . $new_class_meeting_day['date'] . ' from ' . $new_class_meeting_day['start_time'] . ' until ' . $new_class_meeting_day['end_time'] . '.')
                        //.$this->email_structure->button('JOIN SESSION')
                        . $this->email_structure->footer('');

                $this->queue->push($tube, $data_student, 'email.send_email');
            }
        }

        $data_coach = array(
            'subject' => 'Appointment Rescheduled',
            'email' => $id_to_email_address[$coach_id],
                //'content' => 'Your appointment at ' . date("l jS \of F Y", strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with class ' . $class_member[0]->class_name . ' has been rescheduled to ' . date("l jS \of F Y", $date) . ' from ' . $start_time . ' until ' . $end_time . '.',
        );
        $data_coach['content'] = $this->email_structure->header()
                . $this->email_structure->title('Appointment Rescheduled')
                . $this->email_structure->content('Your appointment at ' . date("l jS \of F Y", strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with class ' . $class_member[0]->class_name . ' has been rescheduled to ' . date("l jS \of F Y", $date) . ' from ' . $start_time . ' until ' . $end_time . '.')
                //.$this->email_structure->button('JOIN SESSION')
                . $this->email_structure->footer('');

        $this->queue->push($tube, $data_coach, 'email.send_email');

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // messaging
        // inserting notification
        if ($new_class_meeting_day_id) {
            $database_tube = 'com.live.database';

            // student notification data
            if ($class_member) {
                foreach ($class_member as $cm) {
                    $student_notification['user_id'] = $cm->student_id;
                    $student_notification['description'] = 'You have rescheduled appointment at ' . date('l jS \of F Y', strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with coach ' . $id_to_name[$new_class_meeting_day['coach_id']] . ' to ' . $new_class_meeting_day['date'] . ' from ' . $new_class_meeting_day['start_time'] . ' until ' . $new_class_meeting_day['end_time'] . '.';
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

            $available_host = $this->webex_host_model->get_available_host('c' . $new_class_meeting_day_id);
            if ($available_host && $this->webex_function->create_session($available_host[0]->id, 'c' . $new_class_meeting_day_id)) {
                $message = "Session Rescheduled, you will use Webex for your session";
            } else {
                $message = "Session Rescheduled, you will use Skype for your session";
            }
        }

        $this->messages->add($message, 'success');
        redirect('student_partner/managing');
    }

    private function is_available($coach_id = '', $date = '', $start_time = '', $end_time = '') {
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

    public function cancel_session($class_id = '', $class_meeting_day_id = '') {
        // checking if appointment has already canceled
        $class_meeting_day = $this->class_meeting_day_model->select('id, coach_id, date, start_time, end_time, status')->where('id', $class_meeting_day_id)->get();
        if ($class_meeting_day->status == 'cancel') {
            $this->messages->add('Session has already canceled', 'error');
            redirect('student_partner/managing');
        }

        //////////////////////////////////////////////////////////////////////////////////////
        // WEBEX
        //////////////////////////////////////////////////////////////////////////////////////
        $webex_host = $this->webex_host_model->get_host("c" . $class_meeting_day_id);
        $webex_class = $this->webex_class_model->select('id')->where('class_meeting_id', $class_meeting_day_id)->get();

        $this->db->trans_begin();
        if ($webex_host && $webex_class) {
            // delete session in webex 
            // delete session in webex_class table 
            if (!$this->webex_function->delete_session($webex_host[0]->id, "c" . $class_meeting_day_id) || !$this->webex_class_model->delete($webex_class->id)) {
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
        // after session canceled, send email to coach and students
        // tube name for messaging action
        $tube = 'com.live.email';
        $id_to_email_address = $this->user_model->dropdown('id', 'email');
        $id_to_name = $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname');

        $class_member = $this->class_member_model->get_appointment_for_webex_invitation_xml($class_meeting_day_id);

        if ($class_member) {
            foreach ($class_member as $cm) {
                $data_student = array(
                    'subject' => 'Session Canceled',
                    'email' => $cm->student_email,
                        //'content' => 'Your session at ' . date('l jS \of F Y', strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with class' . $cm->class_name . ' has been canceled.',
                );
                $data_student['content'] = $this->email_structure->header()
                        . $this->email_structure->title('Session Canceled')
                        . $this->email_structure->content('Your session at ' . date('l jS \of F Y', strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with class' . $cm->class_name . ' has been canceled.')
                        //.$this->email_structure->button('JOIN SESSION')
                        . $this->email_structure->footer('');

                $this->queue->push($tube, $data_student, 'email.send_email');
            }
        }

        $data_coach = array(
            'subject' => 'Session Canceled',
            'email' => $id_to_email_address[$class_meeting_day->coach_id],
                //'content' => 'Your session at ' . date("l jS \of F Y", strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with class ' . $class_member[0]->class_name . ' has been canceled.',
        );
        $data_coach['content'] = $this->email_structure->header()
                . $this->email_structure->title('Session Canceled')
                . $this->email_structure->content('Your session at ' . date("l jS \of F Y", strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with class ' . $class_member[0]->class_name . ' has been canceled.')
                //.$this->email_structure->button('JOIN SESSION')
                . $this->email_structure->footer('');

        $this->queue->push($tube, $data_coach, 'email.send_email');

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // messaging
        // inserting notification

        $database_tube = 'com.live.database';
        if ($class_member) {
            foreach ($class_member as $cm) {
                $student_notification['user_id'] = $cm->student_id;
                $student_notification['description'] = 'Your session ' . date('l jS \of F Y', strtotime($class_meeting_day->date)) . ' from ' . $class_meeting_day->start_time . ' until ' . $class_meeting_day->end_time . ' with class ' . $cm->class_name . ' has been canceled.';
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

        $this->messages->add('Session canceled', 'success');
        redirect('student_partner/managing/class_schedule/' . $class_id);
    }

}
