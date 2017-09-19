<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class adding extends MY_Site_Controller {

    //$this->{$this->models[$this->role]}->select('id')->where('user_id',$this->auth_manager->userid())->get();
    //var for days
    var $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    var $table = array(
        'education' => '$this->user_education_model',
        'geography' => 'user_geography_model',
        'profile' => '$this->user_profile_model',
        'social_media' => '$this->user_social_media_model',
        'token' => '$this->user_token_model',
    );

    // Constructor
    public function __construct() {
        parent::__construct();
        // load model for identity
        $this->load->model('user_geography_model');
        $this->load->model('subgroup_model');
        $this->load->model('user_model');
        $this->load->model('identity_model');
        $this->load->model('schedule_model');
        $this->load->model('offwork_model');
        $this->load->model('coach_token_cost_model');
        $this->load->model('creator_member_model');
        $this->load->model('user_education_model');
        $this->load->model('user_notification_model');
        $this->load->model('user_token_model');
        $this->load->model('timezone_model');

        
        // for messaging action and timing
        $this->load->library('queue');

        $this->load->library('phpass');
        $this->load->library('email_structure');
        $this->load->library('send_email');
        $this->load->library('send_sms');
        $this->load->library('common_function');

        date_default_timezone_set('Etc/GMT+0');


        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'PRT') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = 'Add Member';
        $this->template->content->view('default/contents/adding/index');

        //publish template
        $this->template->publish();
    }

    function generateRandomString($length = 5) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

//    public function student() {
//        $this->template->title = 'Add Student';
//        $vars = array(
//            'form_action' => 'create_student'
//        );
//        $this->template->content->view('default/contents/adding/student/form', $vars);
//        $this->template->publish();
//    }

//    public function create_student() {
//        // Creating a student user data must be followed by creating profile data and status still disable/need approval from admin
//        if (!$this->input->post('__submit')) {
//            $this->messages->add('Invalid action', 'danger');
//            redirect('partner/adding');
//        }
//
//        // generating password
//        $password = $this->generateRandomString();
//
//        // inserting user data
//        $user = array(
//            'email' => $this->input->post('email'),
//            'password' => $this->phpass->hash($password),
//            'role_id' => 1,
//            'status' => 'disable',
//        );
//
//        if (!$this->isValidEmail($this->input->post('email'))) {
//            $this->messages->add('Email has been used', 'danger');
//            $this->student();
//            return;
//        }
//
//        // Inserting and checking to users table then storing insert_id into $insert_id
//        $this->db->trans_begin();
//        $user_id = $this->user_model->insert($user);
//        if (!$user_id) {
//            $this->db->trans_rollback();
//            $this->messages->add(validation_errors(), 'danger');
//            $this->student();
//            return;
//        }
//
//        // inserting creator member
//        $creator_member = array(
//            'creator_id' => $this->auth_manager->userid(),
//            'member_id' => $user_id
//        );
//
//        if (!$this->creator_member_model->insert($creator_member)) {
//            $this->db->trans_rollback();
//            $this->messages->add(validation_errors(), 'danger');
//            $this->student();
//            return;
//        }
//
//        // Inserting user profile data
//        $user_id_to_partner_id = $this->identity_model->get_identity('profile')->dropdown('user_id', 'partner_id');
//        $profile = array(
//            'profile_picture' => 'uploads/images/profile.jpg',
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
//            $this->messages->add(validation_errors(), 'danger');
//            $this->student();
//            return;
//        }
//
//        // inserting user token data
//        $token = array(
//            'user_id' => $user_id,
//            'token_amount' => $this->input->post('token_amount'),
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
//
//        $id_to_email_address = $this->user_model->dropdown('id', 'email');
//        $email_address = $this->user_model->select('id, email')->where('role_id', '4')->get_all();
//        // Tube name for messaging action
//        $tube = 'com.live.email';
//        // Email's content to inform administrators that new student need to approve
//        foreach ($email_address as $email) {
//            $data[] = array(
//                'subject' => 'Approve students',
//                'email' => $email->email,
//                'content' => 'New student has been added , Please approve or decline it.',
//            );
//        }
//        // Email's content to inform student their password
//        $data2 = array(
//            'subject' => 'Welcome',
//            'email' => $id_to_email_address[$user_id],
//            'content' => 'Welcome to Dyned Live as student, acccount information: Email = ' . $user['email'] . ' Password = ' . $password . ' This account is still disable, until Administrator approved, You will recive an email if this account has been approved',
//        );
//
//        // Pushing queues to Pheanstalk server 
//        foreach ($data as $d) {
//            $this->queue->push($tube, $d, 'email.send_email');
//        }
//        $this->queue->push($tube, $data2, 'email.send_email');
//
//        //messaging for notification
//        $database_tube = 'com.live.database';
//        foreach ($email_address as $e) {
//            $admin_notification [] = array(
//                'user_id' => $e->id,
//                'description' => 'New student has been added , Please decide to approve or decline it.',
//                'status' => 2,
//                'dcrea' => time(),
//                'dupd' => time(),
//            );
//        }
//
//
//
//        // coach's data for reminder messaging
//        // IMPORTANT : array index in content must be in mutual with table field in database
//        foreach ($admin_notification as $a) {
//            $data_admin = array(
//                'table' => 'user_notifications',
//                'content' => $a,
//            );
//
//            // messaging inserting data notification
//            $this->queue->push($database_tube, $data_admin, 'database.insert');
//        }
//
//
//        $this->messages->add('Inserting Student Succeeded', 'success');
//        redirect('partner/adding');
//    }

    function isValidEmail($email = '') {
        if ($this->user_model->where('email', $email)->get_all()) {
            return false;
        } else {
            return true;
        }
    }

    public function coach() {

        $this->template->title = 'Add Coach';

        // get partner id
        // $get_partner_id = $this->user_profile_model->select('partner_id')->where('user_id',$this->auth_manager->userid())->get();

        // $partner_id = $get_partner_id->partner_id;
        // // =================
        // get sub group by partner id
        $getsubgroup = $this->subgroup_model->select('*')->where('partner_id',$this->auth_manager->partner_id())->where('type','coach')->get_all();

        $subgroup = '';
        foreach ($getsubgroup as $value) {
            $subgroup[$value->id] = $value->name; 
        }
        $timezones = $this->timezone_model->where_not_in('minutes',array('-210','330','570',))->dropdown('id', 'timezone');
        $coach_type = $this->db->select('*')->from('coach_type')->get();

        $id_partner = $this->auth_manager->partner_id($id);
        
        $partner = $this->partner_model->select('name, address, country, state, city, zip')->where('id',$id_partner)->get_all();
        $partner_country = $partner[0]->country;

        $option_country = $this->common_function->country_code;
        $code = array_column($option_country, 'dial_code', 'name');
        $newoptions = $code;
        $arsearch = array_search($partner_country, array_column($option_country, 'name'));
        $dial_code = $option_country[$arsearch]['dial_code'];

        $vars = array(
            'form_action' => 'create_coach',
            'subgroup' => $subgroup,
            'timezones' => $timezones,
            'coach_type' => $coach_type,
            'server_code' => $this->common_function->server_code(),
            'option_country' => $this->common_function->country_code,
            'partner_country' => $partner_country,
            'dial_code' => $dial_code
        );
        $this->template->content->view('default/contents/adding/coach/form', $vars);
        $this->template->publish();
    }

    public function create_coach($idsubgroup = '') {
        // Creating a coach user data must be followed by creating profile, geography, education data and status still disable/need approval from admin
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('partner/member_list/coach');
        }

        $id = $this->auth_manager->userid();
        $id_partner = $this->auth_manager->partner_id($id);

        $partner = $this->partner_model->select('name, address, country, state, city, zip')->where('id',$id_partner)->get_all();
        $partner_country = $partner[0]->country;

        $option_country = $this->common_function->country_code;
        $code = array_column($option_country, 'dial_code', 'name');
        $newoptions = $code;
        $arsearch = array_search($partner_country, array_column($option_country, 'name'));
        $dial_code = $option_country[$arsearch]['dial_code'];

        $rules = array(
            array('field'=>'fullname', 'label' => 'Name', 'rules'=>'trim|required|xss_clean|max_length[150]'),
            array('field'=>'email', 'label' => 'Email', 'rules'=>'trim|required|xss_clean|valid_email|max_length[150]|callback_is_email_available'),
            array('field'=>'email_pro_id', 'label' => 'email_pro_id', 'rules'=>'trim|required|xss_clean|valid_email|max_length[150]|callback_check_email_pro_id'),
            array('field'=>'date_of_birth', 'label' => 'Birthday', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'gender', 'label' => 'Gender', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'phone', 'label' => 'Phone Number', 'rules'=>'trim|required|xss_clean|max_length[150]'),
            array('field'=>'ptscore', 'label' => 'PT Score', 'rules'=>'trim|required|xss_clean|callback_check_ptscore'),
            // array('field'=>'token_for_student', 'label' => 'Token Cost For Student', 'rules'=>'trim|required|xss_clean|numeric|max_length[150]|greater_than[0]'),
            //array('field'=>'token_for_group', 'label' => 'Token Cost For Group', 'rules'=>'trim|required|xss_clean|numeric|max_length[150]')
        );
        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            $this->coach();
            return;
        }

        // generating password
        $password = $this->generateRandomString();
        
        // Inserting user data
        $user = array(
            'email' => $this->input->post('email'),
            'password' => $this->phpass->hash($password),
            'role_id' => 2,
            'status' => 'disable',
            'dcrea' => time(),
            'dupd' => time()
        );


        $this->db->trans_begin();
        // Inserting and checking to users table then storing insert_id into $user_id

        $user_id = $this->user_model->insert($user);
        if (!$user_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->coach();
            return;
        }
//        $this->db->trans_rollback();
//        print_r($user_id); exit;

        // inserting creator member
        $creator_member = array(
            'creator_id' => $this->auth_manager->userid(),
            'member_id' => $user_id
        );
        
        
        $creator_member_id = $this->creator_member_model->insert($creator_member);
        
        if (!$creator_member_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->student();
            return;
        }

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
            'partner_id' => $this->auth_manager->partner_id(),
            'user_timezone' => $this->input->post('user_timezone'),
            'subgroup_id' => $this->input->post('subgroup'),
            'dyned_pro_id' => $this->input->post('email_pro_id'),
            'server_dyned_pro' => $this->input->post('server_dyned_pro'),
            'pt_score' => $this->input->post('ptscore'),
            'coach_type_id' => $this->input->post('coach_type_id'),
            'dcrea' => time(),
            'dupd' => time()
        );
        
        

        // Inserting and checking to profile table then storing it into users_profile table
        $profile_id = $this->identity_model->get_identity('profile')->insert($profile);
        if (!$profile_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->coach();
            return;
        }
        
        
        // Inserting coach token cost profile data
        $token_cost = array(
            'coach_id' => $user_id,
            'token_for_student' => 0,
            // 'token_for_group' => $this->input->post('token_for_group'),
            'token_for_group' => 0,
            'dcrea' => time(),
            'dupd' => time()
        );
        
        // Inserting and checking to profile table then storing it into users_profile table
        $token_cost_id = $this->coach_token_cost_model->insert($token_cost);
        if (!$token_cost_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->coach();
            return;
        }

        // insert to user token
                // inserting user token data
        $token = array(
            'user_id' => $user_id,
            'token_amount' => 0,
            'dcrea' => time(),
            'dupd' => time()
        );

        // Inserting and checking to profile table then storing it into users_profile table
        // $token_id = $this->user_token_model->insert($token);
        $token_id = $this->db->insert('user_tokens',$token);

        

        // Inserting user home town data
        $geography = array(
            'user_id' => $user_id,
        );


        // Inserting and checking to geography table then storing it into users_georaphy table
        $geography_id = $this->user_geography_model->insert($geography);
        
        if (!$geography_id) {
            $this->user_model->delete($user_id);
            $this->messages->add(validation_errors(), 'danger');
            $this->coach();
            return;
        }



        // Inserting user education data
        $education = array(
            'user_id' => $user_id,
        );

        // Inserting and checking to geography table then storing it into users_georaphy table
        $education_id = $this->user_education_model->insert($education);
        if (!$education_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->coach();
            return;
        }
        
        // Inserting user schedule and offwork data
        foreach ($this->days as $d) {
            $schedule = array(
                'user_id' => $user_id,
                'day' => $d,
                'dcrea' => time(),
                'dupd' => time()
            );

            // Inserting and checking to geography table then storing it into users_georaphy table
            $schedule_id = $this->schedule_model->insert($schedule);
            if (!$schedule_id) {
                $this->db->trans_rollback();
                $this->messages->add(validation_errors(), 'danger');
                $this->coach();
                return;
            }

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
            'description' => 'New coach has been added, Please decide to approve or decline.',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );

        
        $coach_notification = array(
            'user_id' => $user_id,
            'description' => 'Congratulation '.$this->input->post('fullname').' and Welcome to DynEd Live.',
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

        $check_email = $this->db->select('email')
                        ->from('users')
                        ->where('email',$this->input->post('email'))
                        ->get();
        if($check_email->num_rows()>0){
            $this->messages->add('Email already registered');
            $this->coach();
        }


         $this->db->trans_commit();
        // messaging inserting data notification
         
        $this->user_notification_model->insert($partner_notification);
        // $this->user_notification_model->insert($coach_notification);



        // =====email====
        
        $id_to_email_address = $this->user_model->dropdown('id', 'email');
       
        $email_address = $this->user_model->select('id, email')->where('role_id', '4')->get_all();
        $email_admin = $this->user_model->select('id, email')->where('id', $regid)->get_all();
        $adminmail = $email_admin[0]->email;
        $name_admin = $this->user_profile_model->select('user_id, fullname')->where('user_id', $regid)->get_all();
        $adminname = $name_admin[0]->fullname;
        $partners = $partners = $this->partner_model->select('*')->where('id', $this->auth_manager->partner_id())->get_all();
        $partnername = $partners[0]->name;
        
        // Tube name for messaging action


        $this->send_email->create_user($this->input->post('email'), $password,'created', $this->input->post('fullname'), 'coach', $partnername);
        $this->send_email->notif_admin($adminmail, $password,'created', $this->input->post('fullname'), 'coach', $adminname);
        //$this->send_sms->create_coach($full_number, $this->input->post('fullname'), $this->input->post('email'));



        $this->messages->add('Inserting Coach Successful', 'success');
        redirect('partner/subgroup/list_coach/'.$this->input->post('subgroup'));
    }
    
    public function student_token($student_id = ''){
        
    }
    
    public function is_email_available($email) {
        if ($this->user_model->where('email', $email)->get_all()) {
            $this->form_validation->set_message('is_email_available', $email . ' has been registered, use another email');
            return false;
        } else {
            return true;
        }
    }

    function ptscore(){
        $this->load->library('call2');

      
        $email = $this->input->post('email');
        $server = $this->input->post('server');
   

        // $this->call2->init("site11", "sutomo@dyned.com");
        $this->call2->init($server, $email);
        $a = $this->call2->getDataJson();
        $b = json_decode($a);
     
        $ptscore = '';
        if(@$b == ''){
            $ptscore = 0;
        } else if(@$b->error == 'Invalid student email'){
                $ptscore = 0;
        } else {
                $ptscore = $b->initial_pt_score;    
        }
        // if(($server == 'cn1') || ($server == 'cn2') || ($server == 'mx1') || ($server == 'mn1') || ($server == 'mone') || ($server == 'vs1')){
        //     $ptscore = "Email is not registered in this server";
        // } else {
 
        //     if(@$b->error == 'Invalid student email'){
        //         $ptscore = 0;
        //     } else {
        //         $ptscore = $b->last_pt_score;    
        //     }
        // }

        echo $ptscore;
        
        // echo header("Content-Type:application/json");
        // print_r($a); 
        
    }

    function check_email_pro_id($email){

        $sql = $this->db->select('dyned_pro_id,server_dyned_pro')->from('user_profiles')->where('dyned_pro_id',$email)->get()->result();
        if($sql){
            $this->form_validation->set_message('check_email_pro_id', $email.' has been registered, use another DynEd Pro ID');
            return false;
        } else {
            return true;
        }
    }

    function check_ptscore($ptscore){

        if($ptscore < 2.5){
            $this->form_validation->set_message('check_ptscore', 'The minimal PT Score for Coach is 2.5');
            return false;
        } else {
            return true;
        }
    }



}
