<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class adding extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // load model for student partner
        $this->load->model('user_model');
        $this->load->model('user_profile_model');
        //
        $this->load->model('class_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('class_member_model');
        $this->load->model('class_schedule_model');
        $this->load->model('class_week_model');
        $this->load->model('subgroup_model');
        $this->load->model('specific_settings_model');
        $this->load->model('global_settings_model');
        $this->load->model('timezone_model');
        $this->load->model('partner_model');


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
        $this->load->library('send_sms');
        $this->load->library('common_function');

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

        //publish templates
        $this->template->publish();
    }

    public function student($subgroup_id = '') {

        $id = $this->auth_manager->userid();
        $id_partner = $this->auth_manager->partner_id($id);

        $partner = $this->partner_model->select('name, address, country, state, city, zip')->where('id',$id_partner)->get_all();
        $partner_country = $partner[0]->country;

        $option_country = $this->common_function->country_code;
        $code = array_column($option_country, 'dial_code', 'name');
        $newoptions = $code;
        $arsearch = array_search($partner_country, array_column($option_country, 'name'));
        $dial_code = $option_country[$arsearch]['dial_code'];

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
        $timezones = $this->timezone_model->where_not_in('minutes',array('-210','330','570',))->dropdown('id', 'timezone');

        $this->template->title = 'Add Student';
        $vars = array(
            'form_action' => 'create_student',
            'max_student' => @$setting->max_student_class,
            'subgroup' => $subgroup,
            'subgroup_id' => $subgroup_id,
            'timezones' => $timezones,
            'server_code' => $this->common_function->server_code(),
            'option_country' => $this->common_function->country_code,
            'partner_country' => $partner_country,
            'dial_code' => $dial_code
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();
        $this->template->content->view('default/contents/adding/student/form', $vars);
        $this->template->publish();
    }

    public function create_student ($subgroup_id = '') {

        $id = $this->auth_manager->userid();
        $id_partner = $this->auth_manager->partner_id($id);

        $partner = $this->partner_model->select('name, address, country, state, city, zip')->where('id',$id_partner)->get_all();
        $partner_country = $partner[0]->country;

        $option_country = $this->common_function->country_code;
        $code = array_column($option_country, 'dial_code', 'name');
        $newoptions = $code;
        $arsearch = array_search($partner_country, array_column($option_country, 'name'));
        $dial_code = $option_country[$arsearch]['dial_code'];
        // get subgroup

        // Creating a student user data must be followed by creating profile data and status still disable/need approval from admin
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'warning');
            redirect('student_partner/subgroup/list_student/'.@$subgroup_id);
        }

        $rules = array(
            array('field'=>'fullname', 'label' => 'Name', 'rules'=>'trim|required|xss_clean|max_length[150]'),
            array('field'=>'email', 'label' => 'Email', 'rules'=>'trim|required|xss_clean|valid_email|max_length[150]|callback_is_email_available'),
            array('field'=>'email_pro_id', 'label' => 'email_pro_id', 'rules'=>'trim|required|xss_clean|valid_email|max_length[150]|callback_check_email_pro_id'),
            array('field'=>'date_of_birth', 'label' => 'Birthday', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'gender', 'label' => 'Gender', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'phone', 'label' => 'Phone Number', 'rules'=>'trim|required|xss_clean|max_length[150]'),
            array('field'=>'token_amount', 'label' => 'Token Amount', 'rules'=>'trim|required|xss_clean|numeric|max_length[150]'),
            array('field'=>'server_dyned_pro', 'label' => 'Server DynDd Pro', 'rules'=>'trim|required|xss_clean')
        );
        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            redirect('student_partner/subgroup/student/'.@$subgroup_id);

            return;
        }

        if($this->input->post('cert_studying') == "0"){
            $this->messages->add('Please input valid Dyned Pro ID', 'warning');
            redirect('student_partner/subgroup/student/'.@$subgroup_id);
        }

        // checking token
        $request_token = $this->input->post('token_amount');

        $id = $this->auth_manager->userid();
        $id_partner = $this->auth_manager->partner_id($id);

        // check apakah status setting region allow atau disallow
        $region_id = $this->auth_manager->region_id($id_partner);

        $get_status_setting_region = $this->specific_settings_model->get_specific_settings($region_id,'region');

        $max_token_for_student = '';
        $max_student_supplier = '';
        $max_student_class = '';
        if($get_status_setting_region[0]->status_set_setting == 0){
            $get_setting = $this->global_settings_model->get_partner_settings();
            $max_token_for_student = $get_setting[0]->max_token_for_student;
            $max_student_supplier = $get_setting[0]->max_student_supplier;
            $max_student_class = $get_setting[0]->max_student_class;

        } else {
            $get_setting = $this->specific_settings_model->get_partner_settings($id_partner);
            $max_token_for_student = $get_setting[0]->max_token_for_student;
            $max_student_supplier = $get_setting[0]->max_student_supplier;
            $max_student_class = $get_setting[0]->max_student_class;
        }
        // =======================
        $get_user_token = $this->user_token_model->get_token($id,'user');
        $user_token = $get_user_token[0]->token_amount;


        // check jika token user tidak mencukupi
        if($user_token < $request_token){
            $this->messages->add('Your token not enough ', 'warning');
            redirect('student_partner/subgroup/student/'.@$subgroup_id);
        }

        // check token student
        $get_token = $this->user_token_model->get_token($id,'user');
        $student_token = $get_token[0]->token_amount;
        // =================

        // xxxxxx======xxxxxx
         // total maximum student
        $get_student_member = $this->db->select('member_id')
                                   ->from('creator_members')
                                   ->where('creator_id', $this->auth_manager->userid())
                                   ->get()->result();

        $tot_stu = 0;
        foreach ($get_student_member as $key) {
            $student_member = $this->db->select('id')
                                   ->from('users')
                                   ->where('id',$key->member_id)
                                   ->where('status','active')
                                   ->get()->result();
            if($student_member){
                $tot_stu++;
            }
        }

        // total maximum student in class
        $get_stu_class = $this->db->select('user_id')
                                  ->from('user_profiles')
                                  ->where('subgroup_id',$subgroup_id)
                                  ->get()->result();

        $tot_stu_class = 0;
        foreach ($get_stu_class as $v) {
            $student_member_class = $this->db->select('id')
                                   ->from('users')
                                   ->where('id',$v->user_id)
                                   ->where('status','active')
                                   ->get()->result();
            if($student_member_class){
                $tot_stu_class++;
            }
        }
        // xxxxxx======xxxxxx
        if(@$tot_stu_class >= @$max_student_class){
            $this->messages->add('Exceeded Maximum Quota Class', 'warning');
            redirect('student_partner/subgroup/list_student/'.@$subgroup_id);
        }

        if(@$tot_stu >= @$max_student_supplier){
            $this->messages->add('Exceeded Maximum Quota student affiliate', 'warning');
            redirect('student_partner/subgroup/list_student/'.@$subgroup_id);
        }

        // update token
        $update_token = $user_token - $request_token;
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

        $country_code = $this->input->post('dial_code');
        $phone_number = $this->input->post('phone');
        $phone = $country_code . $phone_number;
        $full_number = substr($phone, 1);


        // Inserting user profile data
        $user_id_to_partner_id = $this->identity_model->get_identity('profile')->dropdown('user_id', 'partner_id');
        $profile = array(
            'profile_picture' => 'uploads/images/profile.jpg',
            'user_id' => $user_id,
            'fullname' => $this->input->post('fullname'),
            'nickname' => $this->input->post('nickname'),
            'gender' => $this->input->post('gender'),
            'date_of_birth' => $this->input->post('date_of_birth'),
            'dial_code' => $country_code,
            'phone' => $phone_number,
            'partner_id' => $user_id_to_partner_id[$this->auth_manager->userid()],
            'user_timezone' => 27,
            'subgroup_id' => $subgroup_id,
            'dyned_pro_id' => $this->input->post('email_pro_id'),
            'server_dyned_pro' => $this->input->post('server_dyned_pro'),
            'cert_studying' => $this->input->post('cert_studying'),
            'pt_score' => $this->input->post('pt_score'),
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


        // ======
        // $scripts = $this->db->select('*')
        //           ->from('script')
        //           ->like('certificate_plan', $this->input->post('cert_studying'))
        //           ->get()->result();

        //     $script_total = count($scripts);
        //     $datascript =array();
        //     $n = 1;


        //     for($i=0; $i < $script_total; $i++)
        //     {
        //         $datascript[$i] = array(
        //         'user_id'   => $user_id,
        //         'script_id' => $n,
        //         'cert_plan' => $this->input->post('cert_studying'),
        //         'status'    => '0'
        //         );
        //         $n++;
        //     }

        //     $this->db->insert_batch('coaching_scripts', $datascript);
        // ======

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
            'dcrea' => time(),
            'dupd' => time()
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
            'country' => $this->input->post('country')
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
            'description' => 'A new student has been added',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );
        // coach's data for reminder messaging
        // IMPORTANT : array index in content must be in mutual with table field in database
        $data_partner = array(
            'table' => 'user_notifications',
            'content' => $partner_notification,
        );

        // update token user
        $data_update_token = array('token_amount' => $update_token);

        $sql_update_token = $this->db->where('user_id',$id)
                                     ->update('user_tokens',$data_update_token);

        // insert into table token req
        $dt_token_req = array('token_amount' => $request_token,
                              'approve_id' => $id,
                              'user_id' => $user_id,
                              'status' => 'given',
                              'dcrea' => time(),
                              'dupd' => time()
                             );
        $this->db->insert('token_requests',$dt_token_req);

        $student_notification = array(
            'user_id' => $user_id,
            'description' => 'Congratulation '.$this->input->post('fullname').' and Welcome to DynEd Live.',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );

        $partners = $this->partner_model->select('*')->where('id', $this->auth_manager->partner_id())->get_all();
        // echo "<pre>";
        // print_r($partners);
        $partnername = $partners[0]->name;
        // echo $partnername;


         $this->db->trans_commit();



        // messaging inserting data notification

        $this->user_notification_model->insert($partner_notification);

        $this->user_notification_model->insert($student_notification);

        // =========
        $email_admin = $this->user_model->select('id, email')->where('id', $regid)->get_all();
        $adminmail = $email_admin[0]->email;
        // email
        $this->send_email->create_user($this->input->post('email'), $password,'created', $this->input->post('fullname'), 'student', $partnername);
        // $this->send_email->notif_admin($adminmail, $password,'created', $this->input->post('fullname'), 'student');

        //sms
        //$this->send_sms->create_student($full_number, $this->input->post('fullname'), $this->input->post('email'));



        $this->messages->add('Inserting Student Successful', 'success');
        redirect('student_partner/subgroup/list_student/'.@$subgroup_id);
    }

    public function multiple_student($subgroup_id) {

        $this->db->where('creator_id',$this->auth_manager->userid());
        $this->db->delete('temp_multiple_students');

        $this->template->title = 'Add Multiple Student';
        $id_partner = $this->auth_manager->partner_id();
        $region_id = $this->auth_manager->region_id($id_partner);

        $get_status_setting_region = $this->specific_settings_model->get_specific_settings($region_id,'region');

        $max_student_supplier = '';
        if(@$get_status_setting_region[0]->status_set_setting == 0){
            $get_setting = $this->global_settings_model->get_partner_settings();
            $max_student_supplier = $get_setting[0]->max_student_supplier;
        } else {
            $get_setting = $this->specific_settings_model->get_partner_settings($id_partner);
            $max_student_supplier = $get_setting[0]->max_student_supplier;
        }

        $student_member = $this->db->select('member_id')
                                   ->from('creator_members')
                                   ->where('creator_id', $this->auth_manager->userid())
                                   ->get();


        $max_student = $max_student_supplier - $student_member->num_rows();

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
        $file_type = $_FILES['file']['type'];
        if($file_type != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
            $this->messages->add('invalid type file', 'warning');
            redirect('student_partner/adding/multiple_student/'.@$subgroup_id);
        }
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
        // echo $count_temp;
        // exit();
        // if($count_temp > 12){
        //      $this->messages->add('Exceeded Maximum Quota', 'warning');
        //     redirect('student_partner/subgroup/list_student/'.@$subgroup_id);
        // }

        $region_id = $this->auth_manager->region_id($id_partner);

        $get_status_setting_region = $this->specific_settings_model->get_specific_settings($region_id,'region');

        $max_student_supplier = '';
        $max_token_for_student = '';

        if($get_status_setting_region[0]->status_set_setting == 0){
            $get_setting = $this->global_settings_model->get_partner_settings();
            $max_student_supplier = $get_setting[0]->max_student_supplier;
            $max_token_for_student = $get_setting[0]->max_token_for_student;
        } else {
            $get_setting = $this->specific_settings_model->get_partner_settings($id_partner);
            $max_student_supplier = $get_setting[0]->max_student_supplier;
            $max_token_for_student = $get_setting[0]->max_token_for_student;
        }

        $get_user_token = $this->user_token_model->get_token($id,'user');
        $user_token = $get_user_token[0]->token_amount;

        $student_member = $this->db->select('member_id')
                                   ->from('creator_members')
                                   ->where('creator_id', $this->auth_manager->userid())
                                   ->get();

        $total_student = $student_member->num_rows();


        if(@$total_student > @$max_student_supplier){
            $this->messages->add('Exceeded Maximum Quota', 'warning');
            redirect('student_partner/subgroup/list_student/'.@$subgroup_id);
        }

        foreach ($data_student as $key => $value) {
            if($key > 2){
                $fullname = @$value['B'];
                $email = @$value['C'];
                $email_dyned_pro = @$value['D'];
                $server = @$value['E'];
                $birthdate = @$value['F'];
                $gender = @$value['G'];
                $phone = @$value['H'];
                $tokent_amount_request = @$value['I'];
                $timezone = @$value['J'];
                $password = $this->generateRandomString();


                $date_of_birth = date('Y-m-d',($birthdate - 25569) * 86400);

                if(empty($email_dyned_pro)){
                   redirect('student_partner/adding/multiple_student/'.@$subgroup_id);

                }

                // check jika token user tidak mencukupi
                if($user_token < $tokent_amount_request){
                    $this->messages->add('Your token not enough ', 'warning');
                    redirect('student_partner/adding/multiple_student/'.@$subgroup_id);
                }

                 // check token student
                $get_token = $this->user_token_model->get_token($id,'user');
                $student_token = $get_token[0]->token_amount;
                // =================

                if($tokent_amount_request > $max_token_for_student){
                    $this->messages->add('Exceeded Maximum Token', 'warning');
                    redirect('student_partner/adding/multiple_student/'.@$subgroup_id);
                }

                // update token
                $update_token = $user_token - $tokent_amount_request;

                /* ==============
                    proses insert
                ================*/
                if (!$this->isValidDynedProID($email_dyned_pro)) {
                        $this->messages->add('DynEd Pro ID ' . $email_dyned_pro . ' has been used', 'warning');
                        redirect('student_partner/adding/multiple_student/'.@$subgroup_id);
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // $this->messages->add('Invalid Email ' . $email, 'warning');
                } else {
                    if (!$this->isValidEmail($email)) {
                        $this->messages->add('Email ' . $email . ' has been used', 'warning');
                        redirect('student_partner/adding/multiple_student/'.@$subgroup_id);
                    } else {
                        // checking token
                        $request_token = $this->input->post('token_amount');

                        // =======================

                        // check token student_partner
                        $get_user_token = $this->user_token_model->get_token($id,'user');
                        $user_token = $get_user_token[0]->token_amount;

                        // check jika token user tidak mencukupi
                        if($user_token < $request_token){
                            $this->messages->add('Your token not enough ', 'warning');
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
                            'dyned_pro_id' => $email_dyned_pro,
                            'server_dyned_pro' => $server,
                            'partner_id' => $this->auth_manager->partner_id(),
                            'user_timezone' => $timezone,
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
                            'token_amount' => $tokent_amount_request,
                        );

                        // Inserting and checking to profile table then storing it into users_profile table
                        $token_id = $this->user_token_model->insert($data_token);
                        if (!$token_id) {
                            $this->db->trans_rollback();
                        }
                         // update token user
                         $data_update_token = array('token_amount' => $update_token);

                        $sql_update_token = $this->db->where('user_id',$id)
                                                     ->update('user_tokens',$data_update_token);


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
                            'description' => 'A new student has been added.',
                            'status' => 2,
                            'dcrea' => time(),
                            'dupd' => time(),
                        );


                        $user_notification = array(
                            'user_id' => $user_id,
                            'description' => 'Welcome to DynEd',
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
                        $this->user_notification_model->insert($user_notification);


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

    public function create_multiple_student2($subgroup_id = '') {
        $id = $this->auth_manager->userid();
        $id_partner = $this->auth_manager->partner_id($id);

        if ((!isset($_POST["submit"])) && (!isset($_POST["preview"]))) {
            $this->messages->add('invalid Action', 'warning');
            redirect('student_partner/adding/multiple_student/'.@$subgroup_id);
        }
        $file = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        if($file_type != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
            $this->messages->add('invalid type file', 'warning');
            redirect('student_partner/adding/multiple_student/'.@$subgroup_id);
        }
        if (!$file) {
            $this->messages->add('invalid Action', 'warning');
            redirect('student_partner/adding/multiple_student/'.@$subgroup_id);
        }


        $this->load->library('excel');

        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $data_student = '';
        $count_temp = 0;
        foreach ($cell_collection as $cell) {
         $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
         $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
         $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

             if ($row == 1) {
                 $data_student[$row][$column] = $data_value;
                 $count_temp++;
             } else {
                 $data_student[$row][$column] = $data_value;
             }


        }


        $region_id = $this->auth_manager->region_id($id_partner);

        $get_status_setting_region = $this->specific_settings_model->get_specific_settings($region_id,'region');

        $max_student_supplier = '';
        $max_token_for_student = '';

        if($get_status_setting_region[0]->status_set_setting == 0){
            $get_setting = $this->global_settings_model->get_partner_settings();
            $max_student_supplier = $get_setting[0]->max_student_supplier;
            $max_token_for_student = $get_setting[0]->max_token_for_student;
        } else {
            $get_setting = $this->specific_settings_model->get_partner_settings($id_partner);
            $max_student_supplier = $get_setting[0]->max_student_supplier;
            $max_token_for_student = $get_setting[0]->max_token_for_student;
        }

        $get_user_token = $this->user_token_model->get_token($id,'user');
        $user_token = $get_user_token[0]->token_amount;

       $student_member = $this->db->select('member_id')
                                   ->from('creator_members')
                                   ->where('creator_id', $this->auth_manager->userid())
                                   ->get();


        $max_student = $max_student_supplier - $student_member->num_rows();



        $cekidot = [];
        foreach ($data_student as $key => $value) {

            if(($key > 2) && (@$value['B'] != '')){
                $fullname = @$value['B'];
                $email = @$value['C'];
                $email_dyned_pro = @$value['D'];
                $server = @$value['E'];
                $birthdate = @$value['F'];
                $gender = @$value['G'];
                $phone = @$value['H'];
                $tokent_amount_request = @$value['I'];
                $timezone = @$value['J'];
                $password = $this->generateRandomString();


                $date_of_birth = date('Y-m-d',($birthdate - 25569) * 86400);


                if (isset($_POST["preview"])) {


                    $status_email_dyned_pro = 'Enable';

                    if(empty($email_dyned_pro)){
                       $status_email_dyned_pro = 'Email '.$email_dyned_pro.' Not Found';

                    }

                    // check jika token user tidak mencukupi
                    if($user_token < $tokent_amount_request){
                        $status_token = 'Your token not enough ';
                    }

                     // check token student
                    $get_token = $this->user_token_model->get_token($id,'user');
                    $student_token = $get_token[0]->token_amount;
                    // =================

                    if($tokent_amount_request > $max_token_for_student){
                        $status_token_max = 'Exceeded Maximum Token';

                    }

                    // update token
                    $update_token = $user_token - $tokent_amount_request;

                    /* ==============
                        proses insert
                    ================*/
                    if (!$this->isValidDynedProID($email_dyned_pro)) {
                            $status_email_dyned_pro = 'DynEd Pro ID ' . $email_dyned_pro . ' has been used';

                    }

                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        // $this->messages->add('Invalid Email ' . $email, 'warning');
                    } else {

                        if (!$this->isValidEmail($email_dyned_pro)) {
                            $status_email_dyned_pro = 'DynEd Pro ID ' . $email_dyned_pro . ' has been used';

                        } else {
                            // checking token
                            $request_token = $this->input->post('token_amount');

                            // =======================

                            // check token student_partner
                            $get_user_token = $this->user_token_model->get_token($id,'user');
                            $user_token = $get_user_token[0]->token_amount;



                            // Inserting user profile data
                            //$user_id_to_partner_id = $this->identity_model->get_identity('profile')->select('partner_id')->where('user_id', $this-auth_manager->userid())->get();

                            $email_student = '';

                            $valid_email = $this->isValidEmail($email);

                            $status_email = '';
                            if($valid_email == 1){
                                $status_email = 'Enable';
                            } else if($valid_email == 0){
                                $status_email = $email." has been used";
                            }


                            $profile = array(
                                'profile_picture' => 'uploads/images/profile.jpg',
                                'fullname' => $fullname,
                                'gender' => $gender,
                                'date_of_birth' => $date_of_birth,
                                'phone' => $phone,
                                'dyned_pro_id' => $email_dyned_pro,
                                'server_dyned_pro' => $server,
                                'pt_score' => $this->cert_studying22($email_dyned_pro,$server),
                                'partner_id' => $this->auth_manager->partner_id(),
                                'subgroup_id' => $subgroup_id,
                                'creator_id' => $this->auth_manager->userid(),
                                'email' => $email,
                                'password' => $this->phpass->hash($password),
                                'role_id' => 1,
                                'status' => 'disable',
                                'status_email' => $status_email,
                                'status_email_dyned_pro' => $status_email_dyned_pro,
                                'token_for_student' => $tokent_amount_request,
                                'my_token' => $user_token,
                                'max_upload_student' => $max_student,
                                'timezone' => $timezone
                            );


                            // insert to table temp_multiple_students

                            $this->db->insert('temp_multiple_students', $profile);
                         }

                    }
                }
                /*============
                  end process
                ============*/


            }


        }

        // if($cekidot){
        //     $this->session->set_flashdata('my_super_array', $cekidot);
        //     redirect('student_partner/adding/statusPTScore/'.$subgroup_id);
        //     exit();
        // }


        $this->session->set_flashdata('start','start');
        redirect('student_partner/adding/preview');

    }

    function preview(){

        $this->template->title = 'Preview Add Multiple Students';

        $user_id = $this->auth_manager->userid();


        $data = $this->db->select('*')->from('temp_multiple_students')->where('creator_id',$user_id)->get()->result();

        if($data[0]->id == ''){
            redirect('student_partner/subgroup/');
        }

        $subgroup_id = $data[0]->subgroup_id;

        $check_session = $this->session->flashdata('start');
        if($check_session != 'start'){
            $this->db->where('creator_id',$user_id);
            $this->db->delete('temp_multiple_students');
            redirect('student_partner/subgroup/list_student/'.$subgroup_id);

        } else {


            $this->template->content->view('default/contents/adding/preview',['data' => $data]);

            //publish template
            $this->template->publish();
        }

    }

    function cancel($subgroup_id = ''){

        $user_id = $this->auth_manager->userid();

        $this->db->where('creator_id',$user_id);
        $this->db->delete('temp_multiple_students');
        redirect('student_partner/subgroup/list_student/'.$subgroup_id);


    }

    function submit_multiple_sudents(){
        $creator_id = $this->auth_manager->userid();


        $data = $this->db->select('*')->from('temp_multiple_students')->where('creator_id',$creator_id)->get()->result();


        foreach ($data as $d) {

            $status_insert = '';

            if($d->pt_score == '0'){
                $status_insert = ",DynEd Pro ID can't be used";
                $this->db->where('id',$d->id);
                $this->db->update('temp_multiple_students',array('message' => $status_insert));
            }


            // checking token
            $request_token = $d->token_for_student;

            $id_partner = $this->auth_manager->partner_id($creator_id);

            // check apakah status setting region allow atau disallow
            $region_id = $this->auth_manager->region_id($id_partner);

            $get_status_setting_region = $this->specific_settings_model->get_specific_settings($region_id,'region');

            $max_token_for_student = '';
            $max_student_supplier = '';
            if($get_status_setting_region[0]->status_set_setting == 0){
                $get_setting = $this->global_settings_model->get_partner_settings();
                $max_token_for_student = $get_setting[0]->max_token_for_student;
                $max_student_supplier = $get_setting[0]->max_student_supplier;
            } else {
                $get_setting = $this->specific_settings_model->get_partner_settings($id_partner);
                $max_token_for_student = $get_setting[0]->max_token_for_student;
                $max_student_supplier = $get_setting[0]->max_student_supplier;
            }
            // =======================
            $user_token = $d->my_token;

            // check jika token user tidak mencukupi
            if($user_token < $request_token){
                $status_insert .= ',Not enough token ';
                $this->db->where('id',$d->id);
                $this->db->update('temp_multiple_students',array('message' => $status_insert));
            }



            // check token student
            $get_token = $this->user_token_model->get_token($creator_id,'user');
            $student_token = $get_token[0]->token_amount;
            // =================

            $student_member = $this->db->select('member_id')
                                       ->from('creator_members')
                                       ->where('creator_id', $this->auth_manager->userid())
                                       ->get();

            if(@$student_member->num_rows() >= @$max_student_supplier){
                $status_insert .= ',Maximun student exceeded';
                $this->db->where('id',$d->id);
                $this->db->update('temp_multiple_students',array('message' => $status_insert));
            }

            if($d->status_email_dyned_pro != 'enable'){
                $status_insert .= ',DynEd Pro id already registered';
                $this->db->where('id',$d->id);
                $this->db->update('temp_multiple_students',array('message' => $status_insert));
            }



            // update token
            $update_token = $user_token - $request_token;
            // checking if the email is valid/ not been used yet
            if ($d->status_email != 'Enable') {
                $status_insert .= ',User id already registered';
                $this->db->where('id',$d->id);
                $this->db->update('temp_multiple_students',array('message' => $status_insert));
            }


            // inserting user data
            $user = array(
                'email' => $d->email,
                'password' => $d->password,
                'role_id' => $d->role_id,
                'status' => 'disable',
            );


            // Inserting and checking to users table then storing insert_id into $insert_id
            $this->db->trans_begin();
            if($status_insert == 'insert'){

                $user_id = $this->user_model->insert($user);
                if (!$user_id) {
                    $this->db->trans_rollback();
                    $status_insert .= 'Insert failed ';
                    $this->db->where('id',$d->id);
                    $this->db->update('temp_multiple_students',array('message' => $status_insert));
                }


                // inserting student supplier to student
                $ssts = $this->db->insert('student_supplier_to_student', array('id_student_supplier' => $this->auth_manager->userid(), 'id_student' => $user_id, 'dcrea' => time(),'dupd' => time()));


            // Inserting user profile data
                $user_id_to_partner_id = $this->identity_model->get_identity('profile')->dropdown('user_id', 'partner_id');
                $profile = array(
                    'profile_picture' => 'uploads/images/profile.jpg',
                    'user_id' => $user_id,
                    'fullname' => $d->fullname,
                    'nickname' => $d->fullname,
                    'gender' => $d->gender,
                    'date_of_birth' => $d->date_of_birth,
                    'phone' => $d->phone,
                    'partner_id' => $d->partner_id,
                    'user_timezone' => $d->timezone,
                    'subgroup_id' => $d->subgroup_id,
                    'dyned_pro_id' => $d->dyned_pro_id,
                    'server_dyned_pro' => $d->server_dyned_pro,
                    'cert_studying' => $d->pt_score,
                    'dcrea' => time(),
                    'dupd' => time()
                );

                // inserting creator member
                $creator_member = array(
                    'creator_id' => $creator_id,
                    'member_id' => $user_id
                );

                if (!$this->creator_member_model->insert($creator_member)) {
                    $this->db->trans_rollback();
                    $status_insert .= ',<br > Insert to table creator failed';
                    $this->db->where('id',$d->id);
                    $this->db->update('temp_multiple_students',array('message' => $status_insert));

                }


                // ======
                $scripts = $this->db->select('*')
                          ->from('script')
                          ->like('certificate_plan', $d->pt_score)
                          ->get()->result();

                    $script_total = count($scripts);
                    $datascript =array();
                    $n = 1;


                    for($i=0; $i < $script_total; $i++)
                    {
                        $datascript[$i] = array(
                        'user_id'   => $user_id,
                        'script_id' => $n,
                        'cert_plan' => $d->pt_score,
                        'status'    => '0'
                        );
                        $n++;
                    }

                    $this->db->insert_batch('coaching_scripts', $datascript);
                // ======

                // Inserting and checking to profile table then storing it into users_profile table
                $profile_id = $this->identity_model->get_identity('profile')->insert($profile);
                if (!$profile_id) {
                    $this->db->trans_rollback();
                    $status_insert .= ',<br > Insert to table profile failed';
                    $this->db->where('id',$d->id);
                    $this->db->update('temp_multiple_students',array('message' => $status_insert));

                }


                // inserting user token data
                $token = array(
                    'user_id' => $user_id,
                    'token_amount' => $d->token_for_student,
                    'dcrea' => time(),
                    'dupd' => time()
                );

                // Inserting and checking to profile table then storing it into users_profile table
                $token_id = $this->user_token_model->insert($token);
                if (!$token_id) {
                    $this->db->trans_rollback();

                    $status_insert .= ',<br > Insert to table token failed';
                    $this->db->where('id',$d->id);
                    $this->db->update('temp_multiple_students',array('message' => $status_insert));
                }

                $geography = array(
                    'user_id' => $user_id,
                );
                // $geography_id = $this->identity_model->get_identity('geography')->insert($geography, true);
                // if (!$geography_id) {
                //     $this->db->trans_rollback();
                //     $this->messages->add(validation_errors(), 'warning');
                //     $this->student();
                //     return;
                // }
                $student_detail_profile = array(
                    'student_id' => $user_id,
                );
                $student_detail_profile_id = $this->student_detail_profile_model->insert($student_detail_profile);
                if (!$student_detail_profile_id) {
                    $this->db->trans_rollback();
                    $status_insert .= ',<br > Insert to table student detail failed';
                    $this->db->where('id',$d->id);
                    $this->db->update('temp_multiple_students',array('message' => $status_insert));
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
                    'description' => 'A new student has been added',
                    'status' => 2,
                    'dcrea' => time(),
                    'dupd' => time(),
                );
                // coach's data for reminder messaging
                // IMPORTANT : array index in content must be in mutual with table field in database
                $data_partner = array(
                    'table' => 'user_notifications',
                    'content' => $partner_notification,
                );

                // update token user
                $data_update_token = array('token_amount' => $update_token);

                $sql_update_token = $this->db->where('user_id',$creator_id)
                                             ->update('user_tokens',$data_update_token);

                // insert into table token req
                $dt_token_req = array('token_amount' => $request_token,
                                      'approve_id' => $creator_id,
                                      'user_id' => $user_id,
                                      'status' => 'given',
                                      'dcrea' => time(),
                                      'dupd' => time()
                                     );
                $this->db->insert('token_requests',$dt_token_req);

                $student_notification = array(
                    'user_id' => $user_id,
                    'description' => 'Congratulation '.$d->fullname.' and Welcome to DynEd Live.',
                    'status' => 2,
                    'dcrea' => time(),
                    'dupd' => time(),
                );

                $partners = $this->partner_model->select('*')->where('id', $this->auth_manager->partner_id())->get_all();
                // echo "<pre>";
                // print_r($partners);
                $partnername = $partners[0]->name;
                // echo $partnername;


                 $this->db->trans_commit();



                // messaging inserting data notification

                $this->user_notification_model->insert($partner_notification);

                $this->user_notification_model->insert($student_notification);

                // =========
                $email_admin = $this->user_model->select('id, email')->where('id', $regid)->get_all();
                $adminmail = $email_admin[0]->email;
                // email
                $this->send_email->create_user($this->input->post('email'), $d->password,'created', $d->fullname, 'student', $partnername);
                // $this->send_email->notif_admin($adminmail, $password,'created', $this->input->post('fullname'), 'student');

                $this->db->where('id',$d->id);
                $this->db->update('temp_multiple_students',array('message' => 'Succeded'));

            } else {
                $this->db->where('id',$d->id);
                $this->db->update('temp_multiple_students',array('message' => $status_insert));
                // update status gagal insert
            }
            /*==============
             end for each
            ==============*/
        }

            $this->session->set_flashdata('start','start');
            $this->session->set_flashdata('finish','finish');
            redirect('student_partner/adding/preview');
    }


    function cert_studying2($email,$server){
        $this->load->library('call2');


        // $email = "antonio.rodriguez@moultonrodriguez.com";
        // $server = "am1";


        // $this->call2->init("site11", "sutomo@dyned.com");
        $this->call2->init($server, $email);
        $a = $this->call2->getDataJson();
        $b = json_decode($a);


        if(@$b == ''){
            $cert_studying = 0;
        } else if(@$b->error == 'Invalid student email'){
                $cert_studying = 0;
        } else {
                $cert_studying = $b->cert_studying;
        }

        $hasil = [$email,$cert_studying];

        return $hasil;

    }

    function statusPTScore($subgroup_id){

        $a = $this->session->flashdata('my_super_array');
        if(!$a){
            redirect('student_partner/adding/multiple_student/'.$subgroup_id);
        }
        $this->load->view('default/layouts/preview',array('datapt' => $a));

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

    private function isValidDynedProID($email = '') {
        if ($this->user_profile_model->where('dyned_pro_id', $email)->get_all()) {
            return true;
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

        $this->messages->add('Inserting Class Successful', 'success');
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

    function cert_studying_multiple_student($email,$server){
        $this->load->library('call2');
        // $email = "antonio.rodriguez@moultonrodriguez.com";
        // $server = "am1";


        // $this->call2->init("site11", "sutomo@dyned.com");
        $this->call2->init($server, $email);
        $a = $this->call2->getDataJson();
        $b = json_decode($a);


        if(@$b == ''){
            $cert_studying = 0;
        } else if(@$b->error == 'Invalid student email'){
                $cert_studying = 0;
        } else {
                $cert_studying = $b->cert_studying;
        }


        return $cert_studying;

    }

    public function cert_studying22($email='',$server=''){
        $this->load->library('call2');


        // $email = "antonio.rodriguez@moultonrodriguez.com";
        // $email = "rendybustari@gmail.com";
        // $server = "id1";


        // $this->call2->init("site11", "sutomo@dyned.com");
        $this->call2->init($server, $email);
        $a = $this->call2->getDataJson();
        $b = json_decode($a);


        if(@$b == ''){
            $cert_studying = 0;
        } else if(@$b->error == 'Invalid student email'){
                $cert_studying = 0;
        } else {
                $cert_studying = $b->cert_studying;
        }


        return $cert_studying;

    }

    function cert_studying(){
        // exit('a');
        $this->load->library('call2');

        $email = $this->input->post('email');
        $server = $this->input->post('server');

        // $email = "hf23015864@njschool.com.cn";
        // $server = "cn2";


        // $this->call2->init("cn2", "hf23015864@njschool.com.cn");
        $this->call2->init($server, $email);
        $a = $this->call2->getDataJson();
        $b = json_decode($a);

        // echo "<pre>";print_r($b);exit();

        if(@$b == ''){
            $cert_studying = 0;
        } else if(@$b->error == 'Invalid student email'){
            $cert_studying = 0;
            $pt_score = 0;
        } else {
            $cert_studying = $b->cert_studying;
            $pt_score = $b->last_pt_score;
        }

        // echo "<pre>";print_r($b);exit();

        $var[] = [
          'cert_studying' => $cert_studying,
          'pt_score' => $pt_score
        ];

        echo json_encode($var);
        // echo $cert_studying;

    }

    function check_email_pro_id($email){

        $sql = $this->db->select('dyned_pro_id,server_dyned_pro')->from('user_profiles')->where('dyned_pro_id',$email)->get()->result();
        if($sql){
            // $this->form_validation->set_message('check_email_pro_id', $email.' has been registered, use another DynEd Pro ID');
            return true;
        } else {
            return true;
        }
    }


}
