<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class adding extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // load model for student partner
        $this->load->model('user_model');
        //
        $this->load->model('class_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('class_member_model');
        $this->load->model('class_schedule_model');
        $this->load->model('class_week_model');
        $this->load->model('subgroup_model');
        $this->load->model('specific_settings_model');

        //adding student
        $this->load->model('creator_member_model');
        $this->load->model('identity_model');
        $this->load->model('user_token_model');
        $this->load->model('student_detail_profile_model');
        $this->load->model('partner_setting_model');
        $this->load->library('phpass');
        $this->load->library('queue');
        $this->load->library('email_structure');
        $this->load->library('send_email');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'SPR') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }


    // Index
    public function index() {
        $this->template->title = 'Add Class';
        $vars = array(
            'classes' => $this->class_model->select('id, class_name, student_amount, start_date, end_date')->where('student_partner_id', $this->auth_manager->userid())->get_all(),
        );
        $this->template->content->view('default/contents/adding/class/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function student($subgroup_id = '') {

        $setting = $this->db->select('*')->from('specific_settings')->where('partner_id',$subgroup_id)->get()->result();
        if(count($setting) == 0){
            $setting = $this->db->select('*')->from('global_settings')->where('type','partner')->get()->result();
        }

        // get sub group by partner id
        $getsubgroup = $this->subgroup_model->select('*')->where('id',$subgroup_id)->where('type','student')->get_all();
        // echo "<pre>";
        // print_r($getsubgroup);
        // exit();
        $subgroup = '';
        foreach ($getsubgroup as $value) {
            $subgroup[$value->id] = $value->name;
        }

        $this->template->title = 'Add Student';
        $vars = array(
            'form_action' => 'create_student',
            'max_student' => @$setting->max_student_class,
            'subgroup' => $subgroup,
            'subgroup_id' => $subgroup_id
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();
        $this->template->content->view('default/contents/adding/student/form', $vars);
        $this->template->publish();
    }

    public function create_student($subgroup_id = '') {
        $id = $this->auth_manager->userid();
        $id_partner = $this->auth_manager->partner_id($id);
        // get subgroup

        // Creating a student user data must be followed by creating profile data and status still disable/need approval from admin
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'warning');
            redirect('student_partner/subgroup/list_student/'.@$subgroup_id);
        }

        $rules = array(
            array('field'=>'fullname', 'label' => 'Name', 'rules'=>'trim|required|xss_clean|max_length[150]'),
            array('field'=>'email', 'label' => 'Email', 'rules'=>'trim|required|xss_clean|valid_email|max_length[150]|callback_is_email_available'),
            array('field'=>'date_of_birth', 'label' => 'Birthday', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'gender', 'label' => 'Gender', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'phone', 'label' => 'Phone Number', 'rules'=>'trim|required|xss_clean|max_length[150]'),
            array('field'=>'token_amount', 'label' => 'Token Amount', 'rules'=>'trim|required|xss_clean|numeric|max_length[150]')
        );
        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            $this->student($subgroup_id);
            return;
        }

        // $setting = $this->partner_setting_model->where('partner_id',$this->auth_manager->partner_id())->get();

        // checking token
        $request_token = $this->input->post('token_amount');

        // check max token student
        $get_setting = $this->specific_settings_model->get_partner_settings($id_partner);

        $max_token_for_student = $get_setting[0]->max_token_for_student;
        $max_token_for_student;

       
        // =======================

        // check token student_partner
        $get_user_token = $this->user_token_model->get_token($id,'user');
        $user_token = $get_user_token[0]->token_amount;
     
        // check jika token user tidak mencukupi
        if($user_token < $request_token){
            $this->messages->add('Your token not enough ', 'warning');
            redirect('student_partner/subgroup/student/'.@$subgroup_id);
        }

        // check token student
        $get_token = $this->user_token_model->get_token($student_id,'user');
        $student_token = $get_token[0]->token_amount;
        // =================

        // check jika total token melebihi max token
        $total_request_token = $request_token+$student_token;


        if($total_request_token > $max_token_for_student){
            $this->messages->add('Token Request exceeds the maximum, maximum token for student = '.$max_token_for_student, 'warning');
            redirect('student_partner/subgroup/student/'.@$subgroup_id);
        }

        // end of checking token

        // checking student quota
        $student_member = $this->db->select('id')
                                   ->from('student_supplier_to_student')
                                   ->where('id_student_supplier', $this->auth_manager->userid())
                                   ->get();
        
        if(@$student_member->num_rows() >= @$get_setting->max_student_supplier){
            $this->messages->add('Exceeded Maximum Quota', 'warning');
            redirect('student_partner/subgroup/list_student/'.@$subgroup_id);
        }


        // cek maks student token
        // if($this->input->post('token_amount') > $get_setting->max_token_for_student){
        //     $this->messages->add('Exceeded Maximum Token', 'warning');
        //     redirect('student_partner/subgroup/student/'.@$subgroup_id);
        // }


        // checking if the email is valid/ not been used yet
        if (!$this->isValidEmail($this->input->post('email'))) {
            $this->messages->add('Email has been used', 'danger');
            $this->student($this->input->post('partner_id'));
            return;
        }
        // generating password
        $password = $this->generateRandomString();

        $check_email = $this->db->select('email')
                        ->from('users')
                        ->where('email',$this->input->post('email'))
                        ->get();
        if($check_email->num_rows()>0){
            $this->messages->add('Email already registered');
            $this->student();
        }

        // inserting user data
        $user = array(
            'email' => $this->input->post('email'),
            'password' => $this->phpass->hash($password),
            'role_id' => 1,
            'status' => 'active',
        );

        // Inserting and checking to users table then storing insert_id into $insert_id
        $this->db->trans_begin();
        $user_id = $this->user_model->insert($user);
        if (!$user_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'warning');
            $this->student();
            return;
        }


        // inserting student supplier to student
        $ssts = $this->db->insert('student_supplier_to_student', array('id_student_supplier' => $this->auth_manager->userid(), 'id_student' => $user_id, 'dcrea' => time(),'dupd' => time()));


        // Inserting user profile data
        $user_id_to_partner_id = $this->identity_model->get_identity('profile')->dropdown('user_id', 'partner_id');
        $profile = array(
            'profile_picture' => 'uploads/images/profile.jpg',
            'user_id' => $user_id,
            'fullname' => $this->input->post('fullname'),
            'nickname' => $this->input->post('nickname'),
            'gender' => $this->input->post('gender'),
            'date_of_birth' => $this->input->post('date_of_birth'),
            'phone' => $this->input->post('phone'),
            'partner_id' => $user_id_to_partner_id[$this->auth_manager->userid()],
            'user_timezone' => 27,
            'subgroup_id' => $this->input->post('subgroup'),
            'dcrea' => time(),
            'dupd' => time()
        );

        // inserting creator member
        $creator_member = array(
            'creator_id' => $this->auth_manager->userid(),
            'member_id' => $user_id
        );

        if (!$this->creator_member_model->insert($creator_member)) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'warning');
            $this->student();
            return;
        }


        // Inserting and checking to profile table then storing it into users_profile table
        $profile_id = $this->identity_model->get_identity('profile')->insert($profile);
        if (!$profile_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'warning');
            $this->student();
            return;
        }

        // inserting user token data
        $token = array(
            'user_id' => $user_id,
            'token_amount' => $this->input->post('token_amount'),
        );

        // Inserting and checking to profile table then storing it into users_profile table
        $token_id = $this->user_token_model->insert($token);
        if (!$token_id) {
            $this->db->trans_rollback();
            $this->student();
            return;
        }

        $geography = array(
            'user_id' => $user_id,
        );
        $geography_id = $this->identity_model->get_identity('geography')->insert($geography, true);
        if (!$geography_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'warning');
            $this->student();
            return;
        }
        $student_detail_profile = array(
            'student_id' => $user_id,
        );
        $student_detail_profile_id = $this->student_detail_profile_model->insert($student_detail_profile);
        if (!$student_detail_profile_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'warning');
            $this->student();
            return;
        }

        $gpid = $this->auth_manager->partner_id($this->auth_manager->userid());

        $set_regid = $this->auth_manager->region_id($gpid);

        $regid = '';

        if($set_regid == ''){
            $regid = 0;
        } else if($set_regid != 0){
            $regid = $set_regid;
        }

        $partner_notification = array(
            'user_id' => $regid,
            'description' => 'A new student has been added, Please decide to approve or decline it.',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );
        // echo "<pre>";
        // print_r($partner_notification);
        // exit;
        // coach's data for reminder messaging
        // IMPORTANT : array index in content must be in mutual with table field in database
        $data_partner = array(
            'table' => 'user_notifications',
            'content' => $partner_notification,
        );



         $this->db->trans_commit();

        // messaging inserting data notification

        $this->user_notification_model->insert($partner_notification);

        // =========
        $email_admin = $this->user_model->select('id, email')->where('id', $regid)->get_all();
        $adminmail = $email_admin[0]->email;
        // email
        $this->send_email->create_user($this->input->post('email'), $password,'created', $this->input->post('fullname'), 'student');
        $this->send_email->notif_admin($adminmail, $password,'created', $this->input->post('fullname'), 'student');

       

        $this->messages->add('Inserting Student Succeeded', 'success');
        redirect('student_partner/subgroup/list_student/'.@$subgroup_id);
    }

    public function multiple_student($subgroup_id) {

        $this->template->title = 'Add Multiple Student';

        $setting = $this->partner_setting_model->where('partner_id',$this->auth_manager->partner_id())->get();

        $student_member = $this->db->select('id')
                                   ->from('student_supplier_to_student')
                                   ->where('id_student_supplier', $this->auth_manager->userid())
                                   ->get();
        


        $max_student = $setting->max_student_supplier - $student_member->num_rows();

        if($max_student < 1){
            $max_student = '0';
        }

        $vars = array(
            'classes' => $this->class_model->select('id, class_name, student_amount, start_date, end_date')->where('student_partner_id', $this->auth_manager->userid())->get_all(),
            'max_student' => $max_student,
            'subgroup_id' => $subgroup_id
        );
        $this->template->content->view('default/contents/adding/multiple_student/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function create_multiple_student($subgroup_id = '') {
        $id = $this->auth_manager->userid();
        $id_partner = $this->auth_manager->partner_id($id);

        if (!isset($_POST["submit"])) {
            $this->messages->add('invalid Action', 'warning');
            redirect('student_partner/adding/multiple_student/'.@$subgroup_id);
        }
        $file = $_FILES['file']['tmp_name'];
        if (!$file) {
            $this->messages->add('invalid Action', 'warning');
            redirect('student_partner/adding/multiple_student/'.@$subgroup_id);
        }
        // $handle = fopen($file, "r");


        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $data_student = '';
        $count_temp = 0;
        //extract to a PHP readable array format
        foreach ($cell_collection as $cell) {
         $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
         $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
         $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

         //data_student will/should be in row 1 only.
             if ($row == 1) {
                 $data_student[$row][$column] = $data_value;
                 $count_temp++;
             } else {
                 $data_student[$row][$column] = $data_value;
             }


        }

        // ================
        // new checking student quota
        if($count_temp > 12){
             $this->messages->add('Exceeded Maximum Quota', 'warning');
            redirect('student_partner/subgroup/list_student/'.@$subgroup_id);
        }

        $get_setting = $this->partner_setting_model->where('partner_id',$this->auth_manager->partner_id())->get();

        // checking student quota
        $student_member = $this->db->select('id')
                                   ->from('student_supplier_to_student')
                                   ->where('id_student_supplier', $this->auth_manager->userid())
                                   ->get();
        $total_student = $student_member->num_rows()+$count_temp;

        if(@$total_student >= @$get_setting->max_student_supplier){
            $this->messages->add('Exceeded Maximum Quota', 'warning');
            redirect('student_partner/subgroup/list_student/'.@$subgroup_id);
        }

        foreach ($data_student as $key => $value) {
            if($key > 2){
                $email = $value['B'];
                $fullname = $value['C'];
                $gender = $value['D'];
                $get_date_of_birth = $value['E'];
                $phone = $value['F'];   
                $password = $this->generateRandomString();
                
                $date_of_birth = date('Y-m-d',($get_date_of_birth - 25569) * 86400);

                /* ==============
                    proses insert
                ================*/
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->messages->add('Invalid Email ' . $email, 'warning');
                } else {
                    if (!$this->isValidEmail($email)) {
                        $this->messages->add('Email ' . $email . ' has been used', 'warning');
                    } else {
                        // checking token
                        $request_token = $this->input->post('token_amount');

                        // check max token student
                        
                        $max_token_for_student = $get_setting[0]->max_token_for_student;
                        $max_token_for_student;

                       
                        // =======================

                        // check token student_partner
                        $get_user_token = $this->user_token_model->get_token($id,'user');
                        $user_token = $get_user_token[0]->token_amount;
                     
                        // check jika token user tidak mencukupi
                        if($user_token < $request_token){
                            $this->messages->add('Your token not enough ', 'warning');
                            redirect('student_partner/adding/multiple_student/'.@$subgroup_id);
                        }

                        // check token student
                        $get_token = $this->user_token_model->get_token($student_id,'user');
                        $student_token = $get_token[0]->token_amount;
                        // =================

                        // check jika total token melebihi max token
                        $total_request_token = $request_token+$student_token;


                        if($total_request_token > $max_token_for_student){
                            $this->messages->add('Token Request exceeds the maximum, maximum token for student = '.$max_token_for_student, 'warning');
                            redirect('student_partner/adding/multiple_student/'.@$subgroup_id);
                        }

                        // end of checking token

                        // inserting user data
                        $user = array(
                            'email' => $email,
                            'password' => $this->phpass->hash($password),
                            'role_id' => 1,
                            'status' => 'active',
                        );

                        // Inserting and checking to users table then storing insert_id into $insert_id
                        $this->db->trans_begin();
                        $user_id = $this->user_model->insert($user);
                        if (!$user_id) {
                            $this->db->trans_rollback();
                        }

                        // inserting creator member
                        $creator_member = array(
                            'creator_id' => $this->auth_manager->userid(),
                            'member_id' => $user_id
                        );

                        if (!$this->creator_member_model->insert($creator_member)) {
                            $this->db->trans_rollback();
                        }

                        // inserting student supplier to student
                        $ssts = $this->db->insert('student_supplier_to_student', array('id_student_supplier' => $this->auth_manager->userid(), 'id_student' => $user_id, 'dcrea' => time(),'dupd' => time()));


                        // Inserting user profile data
                        //$user_id_to_partner_id = $this->identity_model->get_identity('profile')->select('partner_id')->where('user_id', $this-auth_manager->userid())->get();
                        $profile = array(
                            'profile_picture' => 'uploads/images/profile.jpg',
                            'user_id' => $user_id,
                            'fullname' => $fullname,
                            'gender' => $gender,
                            'date_of_birth' => $date_of_birth,
                            'phone' => $phone,
                            //'dyned_pro_id' => $dyned_pro_id,
                            'partner_id' => $this->auth_manager->partner_id(),
                            'user_timezone' => 27,
                            'subgroup_id' => $subgroup_id
                        );

                        // Inserting and checking to profile table then storing it into users_profile table
                        $profile_id = $this->identity_model->get_identity('profile')->insert($profile);
                        if (!$profile_id) {
                            $this->db->trans_rollback();
                        }

                        // inserting user token data
                        $data_token = array(
                            'user_id' => $user_id,
                            'token_amount' => 0,
                        );

                        // Inserting and checking to profile table then storing it into users_profile table
                        $token_id = $this->user_token_model->insert($data_token);
                        if (!$token_id) {
                            $this->db->trans_rollback();
                        }

                        $geography = array(
                            'user_id' => $user_id,
                        );
                        $geography_id = $this->identity_model->get_identity('geography')->insert($geography, true);
                        if (!$geography_id) {
                            $this->db->trans_rollback();
                        }
                        $student_detail_profile = array(
                            'student_id' => $user_id,
                        );
                        $student_detail_profile_id = $this->student_detail_profile_model->insert($student_detail_profile);
                        if (!$student_detail_profile_id) {
                            $this->db->trans_rollback();
                        }

                        $this->db->trans_commit();
                        $this->messages->add('Multiple Student Created', 'success');

                        // send email and notification

                        $gpid = $this->auth_manager->partner_id($this->auth_manager->userid());

                        $set_regid = $this->auth_manager->region_id($gpid);

                        $regid = '';

                        if($set_regid == ''){
                            $regid = 0;
                        } else if($set_regid != 0){
                            $regid = $set_regid;
                        }

                        $partner_notification = array(
                            'user_id' => $regid,
                            'description' => 'A new student has been added, Please decide to approve or decline it.',
                            'status' => 2,
                            'dcrea' => time(),
                            'dupd' => time(),
                        );
                        // echo "<pre>";
                        // print_r($partner_notification);
                        // exit;
                        // coach's data for reminder messaging
                        // IMPORTANT : array index in content must be in mutual with table field in database
                        $data_partner = array(
                            'table' => 'user_notifications',
                            'content' => $partner_notification,
                        );

                        // messaging inserting data notification

                        $this->user_notification_model->insert($partner_notification);

                         // =========
                        $email_admin = $this->user_model->select('id, email')->where('id', $regid)->get_all();
                        $adminmail = $email_admin[0]->email;
                        // email
                        $this->send_email->create_user($this->input->post('email'), $password,'created', $this->input->post('fullname'), 'student');
                        $this->send_email->notif_admin($adminmail, $password,'created', $this->input->post('fullname'), 'student');
                    }

                }
                /*============
                  end process
                ============*/
            }

        }
        redirect('student_partner/subgroup/list_student/'.@$subgroup_id);
        
    }

    private function generateRandomString($length = 5) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    private function isValidEmail($email = '') {
        if ($this->user_model->where('email', $email)->get_all()) {
            return false;
        } else {
            return true;
        }
    }

    public function is_email_available($email) {
        if ($this->user_model->where('email', $email)->get_all()) {
            $this->form_validation->set_message('is_email_available', $email . ' has been registered, use another email');
            return false;
        } else {
            return true;
        }
    }

    public function classes() {
        $this->template->title = 'Add Class';

        $vars = array(
            'form_action' => 'create_class'
        );
        $this->template->content->view('default/contents/adding/class/form', $vars);
        $this->template->publish();
    }

    public function create_class() {
        // Creating a student user data must be followed by creating profile data and status still disable/need approval from admin
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'warning');
            redirect('student_partner/adding');
        }

        $setting = $this->partner_setting_model->where('partner_id',$this->auth_manager->partner_id())->get();
        // inserting user data
        $class = array(
            'student_partner_id' => $this->auth_manager->userid(),
            'class_name' => $this->input->post('class_name'),
            'student_amount' => $setting->max_student_class,
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
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
        redirect('student_partner/adding/');
    }

    public function class_member($class_id = '') {
        $this->template->title = 'Add Class Member';
        $vars = array(
            'members' => $this->class_member_model->get_student_member($class_id),
            'title' => $this->class_model->select('class_name')->where('id', $class_id)->get(),
        );
        $this->template->content->view('default/contents/adding/class/member_index', $vars);

        //publish template
        $this->template->publish();
    }

    public function create_class_member() {

    }

//    public function student() {
//        $this->template->title = 'Add Student';
//        $vars = array(
//            'form_action' => 'create_student'
//        );
//        $this->template->content->view('default/contents/adding/student/form', $vars);
//        $this->template->publish();
//    }
//
//    public function create_student() {
//        // Creating a student user data must be followed by creating profile data and status still disable/need approval from admin
//        if (!$this->input->post('__submit')) {
//            $this->messages->add('Invalid action', 'warning');
//            redirect('partner/adding');
//        }
//
//        //encrypting password before insert it to database
//        if ($this->input->post('password')) {
//            $password = $this->phpass->hash($this->input->post('password'));
//        } else {
//            $password = null;
//        }
//
//
//        // inserting user data
//        $user = array(
//            'email' => $this->input->post('email'),
//            'password' => $password,
//            'role_id' => 1,
//            'status' => 'disable',
//        );
//
//        // Inserting and checking to users table then storing insert_id into $insert_id
//        $this->db->trans_begin();
//        $user_id = $this->user_model->insert($user);
//        if (!$user_id) {
//            $this->db->trans_rollback();
//            $this->messages->add(validation_errors(), 'warning');
//            $this->student();
//            return;
//        }
//
//        // Inserting user profile data
//        $user_id_to_partner_id = $this->identity_model->get_identity('profile')->dropdown('user_id', 'partner_id');
//        $profile = array(
//            'user_id' => $user_id,
//            'fullname' => $this->input->post('fullname'),
//            'nickname' => $this->input->post('nickname'),
//            'gender' => $this->input->post('gender'),
//            'date_of_birth' => $this->input->post('date_of_birth'),
//            'phone' => $this->input->post('phone'),
//            'partner_id' => $user_id_to_partner_id[$this->auth_manager->userid()],
//        );
//
//        // Inserting and checking to profile table then storing it into users_profile table
//        $profile_id = $this->identity_model->get_identity('profile')->insert($profile);
//        if (!$profile_id) {
//            $this->db->trans_rollback();
//            $this->messages->add(validation_errors(), 'warning');
//            $this->student();
//            return;
//        }
//
//        // inserting user token data
//        $token = array(
//            'user_id' => $user_id,
//        );
//
//        // Inserting and checking to profile table then storing it into users_profile table
//        $token_id = $this->identity_model->get_identity('token')->insert($token);
//        if (!$token_id) {
//            $this->db->trans_rollback();
//            $this->student();
//            return;
//        }
//
//        $this->db->trans_commit();
//
//        $this->messages->add('Inserting Student Succeeded', 'success');
//        redirect('partner/adding');
//    }
//
//    public function coach() {
//        $this->template->title = 'Add Coach';
//        $vars = array(
//            'form_action' => 'create_coach'
//        );
//        $this->template->content->view('default/contents/adding/coach/form', $vars);
//        $this->template->publish();
//    }
//
//    public function create_coach() {
//        // Creating a coach user data must be followed by creating profile, geography, education data and status still disable/need approval from admin
//        if (!$this->input->post('__submit')) {
//            $this->messages->add('Invalid action', 'warning');
//            redirect('partner/adding');
//        }
//
//        //encrypting password before insert it to database
//        if ($this->input->post('password')) {
//            $password = $this->phpass->hash($this->input->post('password'));
//        } else {
//            $password = null;
//        }
//
//
//        // Inserting user data
//        $user = array(
//            'email' => $this->input->post('email'),
//            'password' => $password,
//            'role_id' => 2,
//            'status' => 'disable',
//        );
//
//        $this->db->trans_begin();
//        // Inserting and checking to users table then storing insert_id into $user_id
//        $user_id = $this->user_model->insert($user);
//        if (!$user_id) {
//            $this->db->trans_rollback();
//            $this->messages->add(validation_errors(), 'warning');
//            $this->coach();
//            return;
//        }
//
//
//        // Inserting user profile data
//        $user_id_to_partner_id = $this->identity_model->get_identity('profile')->dropdown('user_id', 'partner_id');
//        $profile = array(
//            'user_id' => $user_id,
//            'fullname' => $this->input->post('fullname'),
//            'nickname' => $this->input->post('nickname'),
//            'gender' => $this->input->post('gender'),
//            'date_of_birth' => $this->input->post('date_of_birth'),
//            'phone' => $this->input->post('phone'),
//            'partner_id' => $user_id_to_partner_id[$this->auth_manager->userid()],
//        );
//
//        // Inserting and checking to profile table then storing it into users_profile table
//        $profile_id = $this->identity_model->get_identity('profile')->insert($profile);
//        if (!$profile_id) {
//            $this->db->trans_rollback();
//            $this->messages->add(validation_errors(), 'warning');
//            $this->coach();
//            return;
//        }
//
//        // Inserting user home town data
//        $geography = array(
//            'user_id' => $user_id,
//            'country' => 'data',
//            'state' => 'data',
//            'city' => 'data',
//            'zip' => 'data',
//        );
//
//        //$this->db->insert('user_geography', $geography);
//        //exit;
//        // Inserting and checking to geography table then storing it into users_georaphy table
//        $geography_id = $this->identity_model->get_identity('geography')->insert($geography);
//
//        //print_r($this->user_geography_model); exit;
//        //$geography_id = $this->user_geography_model->insert($geography);
//        //$this->{$this->models[$this->table('geography')]},
//        //echo('<pre>');
//        //print_r(get_object_vars($this->identity_model->get_identity('geography'))); exit;
//        //echo($this->db->last_query()); exit;
//        if (!$geography_id) {
//            $this->user_model->delete($user_id);
//            $this->messages->add(validation_errors(), 'warning');
//            $this->coach();
//            return;
//        }
//
//
//
//        // Inserting user education data
//        $education = array(
//            'user_id' => $user_id,
//            'teaching_credential' => 'data',
//            'dyned_certification_level' => 'data',
//            'year_experience' => 'data',
//            'special_english_skill' => 'data',
//        );
//
//        // Inserting and checking to geography table then storing it into users_georaphy table
//        $education_id = $this->identity_model->get_identity('education')->insert($education);
//        if (!$education_id) {
//            $this->db->trans_rollback();
//            $this->messages->add(validation_errors(), 'warning');
//            $this->coach();
//            return;
//        }
//
//        // Inserting user schedule and offwork data
//        foreach ($this->days as $d) {
//            $schedule = array(
//                'user_id' => $user_id,
//                'day' => $d,
//            );
//
//            // Inserting and checking to geography table then storing it into users_georaphy table
//            $schedule_id = $this->schedule_model->insert($schedule);
//            if (!$schedule_id) {
//                $this->db->trans_rollback();
//                $this->messages->add(validation_errors(), 'warning');
//                $this->coach();
//                return;
//            }
//
//            //inserting user offwork
//            $offwork = array(
//                'schedule_id' => $schedule_id,
//            );
//
//            // Inserting and checking to geography table then storing it into users_georaphy table
//            $offwork_id = $this->offwork_model->insert($offwork);
//            if (!$offwork_id) {
//                $this->db->trans_rollback();
//                $this->messages->add(validation_errors(), 'warning');
//                $this->coach();
//                return;
//            }
//        }
//
//        $this->db->trans_commit();
//
//        $this->messages->add('Inserting Coach Succeeded', 'success');
//        redirect('partner/adding');
//    }
//
//    public function edit($day) {
//        // setting day for editing adding data
//        $this->session->set_userdata("day_adding", $day);
//
//        $this->template->title = 'Edit Schedule';
//        $vars = array(
//            'adding' => $this->offwork_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id', $this->auth_manager->userid())->where('day', $day)->get_all(),
//            'form_action' => 'update'
//        );
//        $this->template->content->view('default/contents/adding/form', $vars);
//
//        //publish template
//        $this->template->publish();
//    }
//
//    public function update() {
//        if (!$this->input->post('__submit')) {
//            $this->messages->add('Invalid action', 'warning');
//            redirect('home');
//        }
//        //$this->session->userdata("day_adding");
//        $temp = $this->offwork_model->select('id')->where('user_id', $this->auth_manager->userid())->where('day', $this->session->userdata("day_adding"))->get_all();
//        foreach ($temp as $t) {
//            $adding = array(
//                'start_time' => $this->input->post('start_time_' . $t->id),
//                'end_time' => $this->input->post('end_time_' . $t->id),
//            );
//
//            // Inserting and checking
//            if (!$this->offwork_model->update($t->id, $adding)) {
//                $this->messages->add(validation_errors(), 'warning');
//                $this->edit($this->auth_manager->userid());
//                return;
//            }
//        }
//
//        //unsetting day_adding
//        $this->session->unset_userdata("day_adding");
//
//        $this->messages->add('Update Succeeded', 'success');
//        redirect('coach/adding');
//    }
//
//    public function delete($day = '', $id = '') {
//        //deleting data if in a day has more than one adding
//        if (count($this->offwork_model->where('user_id', $this->auth_manager->userid())->where('day', $day)->get_all()) > 1) {
//            $this->offwork_model->where('user_id', $this->auth_manager->userid())->where('day', $day)->delete($id);
//            $this->messages->add('Delete Succeeded', 'success');
//            redirect('coach/adding/edit/' . $day);
//        }
//        //one coach must has one adding each day in database even if start_time and end_time null
//        else if (count($this->offwork_model->where('user_id', $this->auth_manager->userid())->where('day', $day)->get_all() == 1)) {
//            $adding = array(
//                'start_time' => null,
//                'end_time' => null,
//            );
//
//            // Inserting and checking
//            $id = $this->offwork_model->where('user_id', $this->auth_manager->userid())->where('day', $day)->get();
//            if (!$this->offwork_model->update($id->id, $adding)) {
//                $this->messages->add(validation_errors(), 'warning');
//                $this->edit($this->auth_manager->userid());
//                return;
//            }
//            redirect('coach/adding/');
//        }
//    }
//
//    public function test() {
//        $selectedTime = "9:15:00";
//        $endTime = strtotime("+5 hours +15 minutes", strtotime($selectedTime));
//        echo date('H:i:s', strtotime($selectedTime) + 900);
//        exit;
//    }

    public function run_validation($rules=''){
        $this->load->library('form_validation');
        if (!empty($rules)) {
            if (is_array($rules)) {
                $this->form_validation->set_rules($rules);
                return $this->form_validation->run();
            } else {
                return $this->form_validation->run($rules);
            }
        } else {
            return TRUE;
        }
    }
}
