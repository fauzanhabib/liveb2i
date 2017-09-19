<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class manage_partner extends MY_Site_Controller {

    var $upload_path = 'uploads/images/';
    
    // Constructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('partner_model');
        $this->load->model('user_model');
        $this->load->model('user_profile_model');
        $this->load->model('identity_model');
        $this->load->model('creator_member_model');
        $this->load->model('user_token_model');
        $this->load->model('user_geography_model');
        $this->load->model('token_request_model');
        $this->load->model('history_request_model');
        $this->load->model('subgroup_model');
        $this->load->model('region_model');
        $this->load->model('partner_model');
        $this->load->model('specific_settings_model');
        $this->load->model('global_settings_model');

        $this->load->library('phpass');
        $this->load->library('schedule_function');
        $this->load->library('common_function');
        $this->load->library('email_structure');

        // for messaging action and timing
        $this->load->library('queue');
        $this->load->library('send_email');
        $this->load->library('send_sms');

        
        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'RAD') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index($page='') {
        $this->template->title = 'Add Affiliate';
        
        $offset = 0;
        $per_page = 6;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin/manage_partner/index'), count($this->partner_model->select('id, profile_picture, name, address, country, state, city, zip')->where('name not like', 'No Partner')->order_by('name', 'asc')->get_all()), $per_page, $uri_segment);
        
        $vars = array(
            'partner' => $this->partner_model->select('id, profile_picture, name, address, country, state, city, zip')->where('name not like', 'No Partner')->order_by('name', 'asc')->limit($per_page)->offset($offset)->get_all(),
            'pagination' => @$pagination
        );

        $this->template->content->view('default/contents/manage_partner/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function add_partner() {
        $this->template->title = 'Add Affiliate';
        $id = $this->uri->segment(4);
        
        $region = $this->identity_model->get_region_admin_identity($id);

        if($id == ''){
            redirect('account/identity/detail/profile');
        }
        $vars = array(
            'region' => @$region,
            'option_country' => $this->common_function->country_code,
            'form_action' => 'create_partner',
            'id' => $id
        );
        $this->template->content->view('default/contents/manage_partner/add_partner/form', $vars);
        $this->template->publish();
    }

    
    public function partner_profile_picture($id=''){
        $profile_picture = $this->do_upload('profile_picture');
        if (!$profile_picture) {
            $this->messages->add('Failed to upload image', 'error');
            return $this->detail('profile');
        } 
        $data_profile_picture['profile_picture'] = $this->upload_path . $profile_picture['file_name'];
        $partner = $this->partner_model->select('id')->where('id', $id)->get();
        if (!$this->partner_model->update($partner->id, $data_profile_picture, TRUE)) {
            $this->messages->add('Update Affiliate Profile Picture Failed', 'success');
            redirect('admin/manage_partner/edit_partner/'.$id);
        }
        
        $this->messages->add('Affiliate Profile Picture has been Updated Successfully', 'success');
        redirect('admin/manage_partner/edit_partner/'.$id);
    }
    
    public function create_partner($id = '') {
        // Creating a partner
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('admin/manage_partner');
        }

        $rules = array(
                array('field'=>'name', 'label' => 'Name', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'address', 'label' => 'Address', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'city', 'label' => 'City', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'state', 'label' => 'State', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'zip', 'label' => 'ZIP', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'country', 'label' => 'Country', 'rules'=>'trim|required|xss_clean')
            );

            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                redirect('admin/manage_partner/add_partner');
            }
        
        // inserting user data
        $partner = array(
            'admin_regional_id' => $id,
            'profile_picture' => 'uploads/images/default_logo.jpg',
            'name' => $this->input->post('name'),
            'address' => $this->input->post('address'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'zip' => $this->input->post('zip'),
            'country' => $this->input->post('country'),
        );

        // Inserting and checking to partner table
        $this->db->trans_begin();
        $user_id = $this->partner_model->insert($partner);
        if (!$user_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->add_partner();
            return;
        }

          // get global setting for region

        $region_setting = $this->region_model->get_global_setting('partner');

        $specific_settings = [
            'partner_id' => $user_id,
            'session_duration' => $region_setting[0]->session_duration,
            'max_student_supplier' => $region_setting[0]->max_student_supplier,
            'max_student_class' => $region_setting[0]->max_student_class,
            'max_session_per_day' => $region_setting[0]->max_session_per_day,
            'max_day_per_week' => $region_setting[0]->max_day_per_week,
            'max_token' => $region_setting[0]->max_token,
            'max_token_for_student' => $region_setting[0]->max_token_for_student,
            'status_set_setting' => $region_setting[0]->status_set_setting,
            'type' => $region_setting[0]->type,
            'dcrea' => time(),
            'dupd' => time()
        ];



        // insert into table specific setting
        $this->region_model->insert_specific_setting($specific_settings);

        // insert into user token
        // $this->db->insert('user_tokens', array('partner_id' => $user_id, 'token_amount' => $region_setting[0]->max_token));

        // get token region
        // $token_region = $this->user_token_model->select('token_amount')->where('user_id',$id)->get();
        // $token_region = $token_region->token_amount;
        
        // update token region
        // $new_token = $token_region-$region_setting[0]->max_token;

        // if($new_token <= 0){
        //     $this->messages->add('Not enough token', 'danger');
        //     redirect('superadmin/region/detail/'.$id);
        // }

        // $data_new_token = ['token_amount' => $new_token];

        // $update_new_token = $this->db->where('user_id',$id)->update('user_tokens',$data_new_token);

        $this->db->trans_commit();

        $this->messages->add('Inserting Affiliate Successful', 'success');
        redirect('superadmin/region/detail/'.$id);
    }


    public function edit_partner($id = '') {
        $this->template->title = 'Affiliate Partner';
        $data = $this->partner_model->select('id, name, address, country, state, city, zip')->where('id', $id)->get();
        $vars = array(
            'data' => $data,
            'form_action' => 'update_partner',
            'partner' => $this->partner_model->select('profile_picture')->where('id', $id)->get()
        );
        $this->template->content->view('default/contents/manage_partner/add_partner/form', $vars);
        $this->template->publish();
    }

    public function update_partner($id = '') {
        // inserting user data
        
        $rules = array(
                array('field'=>'name', 'label' => 'Name', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'address', 'label' => 'Address', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'city', 'label' => 'City', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'state', 'label' => 'State', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'zip', 'label' => 'ZIP', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'country', 'label' => 'Country', 'rules'=>'trim|required|xss_clean')
            );

            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                $this->detail($id);
                return;
            }
        
        $partner = array(
            'name' => $this->input->post('name'),
            'address' => $this->input->post('address'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'zip' => $this->input->post('zip'),
            'country' => $this->input->post('country')
        );

        // Inserting and checking to partner table
        $this->db->trans_begin();
        if (!$this->partner_model->update(@$id, $partner)) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            return $this->edit_partner($id);
        }
        $this->db->trans_commit();

        $this->messages->add('Updating Affiliate Successful', 'success');
        redirect('superadmin/manage_partner/detail/'.$id);
    }

    // Delete
    public function delete_partner($id = '') {
        $this->partner_model->delete($id);

        $delete_user_token = $this->db->where('user_id',$id)
                                      ->delete('user_tokens');

        $delete_specific_setting = $this->db->where('user_id',$id)
                                      ->delete('specific_settings');

        $this->messages->add('Delete Successful', 'success');
        redirect('superadmin/region');
    }

    public function add_partner_member($partner = '') {

        $region_id = $this->auth_manager->region_id($partner);

        $partner = $this->partner_model->select('id, name, address, country, state, city, zip')->where('id',$partner)->get_all();
        $partner_country = $partner[0]->country;

        $option_country = $this->common_function->country_code;
        $code = array_column($option_country, 'dial_code', 'name');
        $newoptions = $code;
        $arsearch = array_search($partner_country, array_column($option_country, 'name'));
        $dial_code = $option_country[$arsearch]['dial_code'];

        $this->template->title = 'Add Affiliate Member';
        $vars = array(
            'selected' => $partner,
            'region_id' => $region_id,
            'partner' => $this->partner_model->where('name not like', 'No Partner')->dropdown('id', 'name'),
            'form_action' => 'create_partner_member',
            'option_country' => $this->common_function->country_code,
            'partner_country' => $partner_country,
            'dial_code' => $dial_code
        );

        $this->template->content->view('default/contents/manage_partner/add_partner_member/form', $vars);
        $this->template->publish();
    }

    public function create_partner_member($partner_id = '') {
        // Creating a member user as role partner
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'error');
            redirect('superadmin/manage_partner');
        }
        
        @$get_region_id = @$this->common_function->region_from_partner($this->input->post('partner_id'));

        $partner = $this->partner_model->select('name, address, country, state, city, zip')->where('id',$this->input->post('partner_id'))->get_all();
        $partner_country = $partner[0]->country;

        $option_country = $this->common_function->country_code;
        $code = array_column($option_country, 'dial_code', 'name');
        $newoptions = $code;
        $arsearch = array_search($partner_country, array_column($option_country, 'name'));
        $dial_code = $option_country[$arsearch]['dial_code'];


        // generating password
        $password = $this->generateRandomString();
        
        // inserting user data
        if($this->input->post('role_id') != 3 && $this->input->post('role_id') != 5){
            $this->messages->add('Invalid partner type', 'error');
            redirect('superadmin/manage_partner');
        }
        
        if(!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)){
            $this->messages->add('Invalid Email', 'error');
            $this->add_partner_member($this->input->post('partner_id'));
            return;
        }

        $partner_id = $this->input->post('partner_id');
        $region_id = $this->auth_manager->region_id($partner_id);


         // check token
        if($this->input->post('role_id') == 5){
            $id = $this->auth_manager->userid();
            $request_token = $this->input->post('token_amount');

            // check apakah status setting region allow atau disallow
            
            $get_status_setting_region = $this->specific_settings_model->get_specific_settings($region_id,'region');
            
            $max_token_student_supplier = '';
            if($get_status_setting_region[0]->status_set_setting == 0){
                $get_setting = $this->global_settings_model->get_partner_settings();
                $max_token_student_supplier = $get_setting[0]->max_token; 
            } else {
                $get_setting = $this->specific_settings_model->get_partner_settings($partner_id);
                $max_token_student_supplier = $get_setting[0]->max_token;
            }

            // ==========

            // check apakah token region mencukupi
            $get_token_region = $this->user_token_model->get_token($region_id,'user');
            $region_token = $get_token_region[0]->token_amount;
            if($region_token < $request_token){
                $this->messages->add("This region doesn't have enough tokens to add", 'warning');
                redirect('superadmin/manage_partner/add_partner_member/'.$partner_id.'/student');
            }
            
            // ==========

            // check jika request melebihi maksimal
            if($request_token > $max_token_student_supplier){
                $this->messages->add('Token Request exceeds the maximum, maximum token for student affiliate = '.$max_token_student_supplier, 'warning');
                redirect('superadmin/manage_partner/add_partner_member/'.$partner_id.'/student');
            }

        }


        // end of check token
        
        $user = array(
            'email' => $this->input->post('email'),
            'password' => $this->phpass->hash($password),
            'role_id' => $this->input->post('role_id'),
            'status' => 'active',
            'dcrea' => time(),
            'dupd' => time()
        );

        
        // checking if the email is valid/ not been used yet
        if (!$this->isValidEmail($this->input->post('email'))) {
            $this->messages->add('Email has been used', 'error');
            $this->add_partner_member($this->input->post('partner_id'));
            return;
        }

        // Inserting and checking to users table then storing insert_id into $insert_id
        $this->db->trans_begin();
        $user_id = $this->user_model->insert($user);
        if (!$user_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'error');
            $this->add_partner_member($this->input->post('partner_id'));
            return;
        }

        $country_code = $this->input->post('dial_code');
        $phone_number = $this->input->post('phone');
        $phone = $country_code . $phone_number;
        $full_number = substr($phone, 1);

        // Inserting user profile data
        $profile = array(
            'profile_picture' => 'uploads/images/profile.jpg', // default profile picture
            'user_id' => $user_id,
            'fullname' => $this->input->post('fullname'),
            'nickname' => $this->input->post('nickname'),
            'gender' => $this->input->post('gender'),
            'date_of_birth' => $this->input->post('date_of_birth'),
            'dial_code' => $country_code,
            'phone' => $phone_number,
            'partner_id' => $this->input->post('partner_id'),
            'skype_id' => $this->input->post('skype_id'),
            'user_timezone' => 27,
            'dcrea' => time(),
            'dupd' => time()
        );

        // Inserting and checking to profile table then storing it into users_profile table
        $profile_id = $this->identity_model->get_identity('profile')->insert($profile);
        if (!$profile_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'error');
            $this->add_partner_member($this->input->post('partner_id'));
            return;
        }

        $geography = array(
            'user_id' => $user_id,
            'country' => $this->input->post('country')
        );


        // Inserting and checking to geography table then storing it into users_georaphy table
        $geography_id = $this->user_geography_model->insert($geography);
        
        if (!$geography_id) {
            $this->user_model->delete($user_id);
            $this->messages->add(validation_errors(), 'danger');
            $this->coach();
            return;
        }

                // inserting user token data
        $token = array(
            'user_id' => $user_id,
            'token_amount' => $this->input->post('token_amount'),
        );

        // Inserting and checking to profile table then storing it into users_profile table
        $token_id = $this->user_token_model->insert($token);

        $get_id_region = $this->partner_model->select('admin_regional_id,name')->where('id', $this->input->post('partner_id'))->get();
        
        // jika role = student partner, insert data ke table user token
        if($this->input->post('role_id') == 5){

            $token_student_supplier = [
            'user_id' => $user_id,
            'token_amount' => 0,
            'dcrea' => time(),
            'dupd' => time()
            ];
             $this->user_token_model->insert($token_student_supplier);

            $token_requests = $this->input->post('token_amount');

             // update token region
             $update_token_region = $region_token-$token_requests;

             $sql_update_token_region = $this->db->where('user_id',$region_id)
                                                 ->update('user_tokens',array('token_amount' => $update_token_region));

             // insert into table spesific setting
             // get id region region
             
             // $id_region = $get_id_region->admin_regional_id;
             $region_setting = $this->region_model->get_specific_setting($region_id);
             
            $specific_settings = [
                'user_id' => $user_id,
                'session_duration' => $region_setting[0]->session_duration,
                'standard_coach_cost' => $region_setting[0]->standard_coach_cost,
                'elite_coach_cost' => $region_setting[0]->elite_coach_cost,
                'max_student_supplier' => $region_setting[0]->max_student_supplier,
                'max_student_class' => $region_setting[0]->max_student_class,
                'max_session_per_day' => $region_setting[0]->max_session_per_day,
                'max_day_per_week' => $region_setting[0]->max_day_per_week,
                'max_token' => $region_setting[0]->max_token,
                'max_token_for_student' => $region_setting[0]->max_token_for_student,
                'status_set_setting' => $region_setting[0]->status_set_setting,
                'type' => 'partner',
                'dcrea' => time(),
                'dupd' => time()
            ];

            $this->db->insert('specific_settings',$specific_settings);
   
        }

        // =====================================================

        $this->db->trans_commit();

        $id_to_email_address = $this->user_model->dropdown('id', 'email');
        // Tube name for messaging sending email action
        //messaging for notification

        $partner_notification = array(
            'user_id' => $user_id,
            'description' => 'Hi '.$profile['fullname'].'. Welcome to DynEd Live, you have permission to access Dyned Live as Affiliate admin.',
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

        // messaging inserting data notification
        $this->send_email->admin_create_supplier($this->input->post('email'),$password,'created',$this->input->post('fullname'),$get_id_region->name);
        // $this->queue->push($database_tube, $data_partner, 'database.insert');
        $this->messages->add('Inserting Supplier Succeeded', 'success');
        $get_region_name = $this->db->select('region_id')->from('user_profiles')->where('user_id',$region_id)->get()->result();
       
        $get_email_region = $this->user_model->select('email')->where('id', $region_id)->get_all();
        $email_region = $get_email_region[0]->email;
       
        if($this->input->post('role_id') == 5){
            $region_notification = array(
                'user_id' => $region_id,
                'description' => 'New Student Affiliate created',
                'status' => 2,
                'dcrea' => time(),
                'dupd' => time(),
            );

            
            $this->send_email->notif_ad_reg_stu($email_region,$this->input->post('fullname'),$get_region_name[0]->region_id, $this->input->post('token_amount'));

            redirect('superadmin/manage_partner/partner/student/'.$this->input->post('partner_id').'/'.@$get_region_id[0]->admin_regional_id);
        }elseif($this->input->post('role_id') == 3){
            $this->send_email->notif_ad_reg_coa($email_region,$this->input->post('fullname'),$get_region_name[0]->region_id);

            redirect('superadmin/manage_partner/partner/coach/'.$this->input->post('partner_id').'/'.@$get_region_id[0]->admin_regional_id);
        }
        //$this->send_sms->create_partner($full_number, $this->input->post('fullname'), $this->input->post('email'));
    }

    public function dial_code(){

        $country = $this->input->post('country');

        $option_country = $this->common_function->country_code;
        $code = array_column($option_country, 'dial_code', 'name');
        $newoptions = $code;
        $arsearch = array_search($country, array_column($option_country, 'name'));
        $dial_code = $option_country[$arsearch]['dial_code'];

        echo $dial_code;

    }

    public function setting($id='', $type=''){
        $this->template->title = 'Affiliate Setting';

        $setting = $this->region_model->get_partner_specific_setting($id);
        $regionid = $this->auth_manager->region_id($id);
        $region_setting = $this->specific_settings_model->get_specific_settings($regionid,'region');

        $max_student_supplier = $region_setting[0]->max_student_supplier;
        $max_student_class = $region_setting[0]->max_student_class;
        $max_session_per_day = $region_setting[0]->max_session_per_day;
        $max_day_per_week = $region_setting[0]->max_day_per_week;
        $max_token = $region_setting[0]->max_token;
        $max_token_for_student = $region_setting[0]->max_token_for_student;
        // $max_session_per_x_day = $region_setting[0]->max_session_per_x_day;
        // $x_day = $region_setting[0]->x_day;
        $set_max_session = $region_setting[0]->set_max_session;
        $max_session_duration = $region_setting[0]->session_duration;
        $standard_coach_cost = $region_setting[0]->standard_coach_cost;
        $elite_coach_cost = $region_setting[0]->elite_coach_cost;
        $back = site_url('superadmin/manage_partner/detail/'.$id);

        
        $vars = ['data' => $setting,
                'id' => $id,
                'back' => $back,
                'max_student_supplier' => $max_student_supplier,
                'max_student_class' => $max_student_class,
                'max_session_per_day' => $max_session_per_day,
                'max_day_per_week' => $max_day_per_week,
                'max_token' => $max_token,
                'max_token_for_student' => $max_token_for_student,
                // 'max_session_per_x_day' => $max_session_per_x_day, 
                // 'x_day' => $x_day,
                'set_max_session' => $set_max_session,
                'max_session_duration' => $max_session_duration,
                'standard_coach_cost' => $standard_coach_cost,
                'elite_coach_cost' => $elite_coach_cost];

        if($type == '' || $type == 'coach'){
            $this->template->content->view('default/contents/superadmin/region/coach', $vars);
        } else {
            $this->template->content->view('default/contents/superadmin/region/setting', $vars);
        }
        $this->template->publish();
    }

function update_setting($id) {
        $regionid = $this->auth_manager->region_id($id);
        $region_setting = $this->specific_settings_model->get_specific_settings($regionid,'region');

        $max_student_supplier = $region_setting[0]->max_student_supplier;
        $max_student_class = $region_setting[0]->max_student_class;
        $max_session_per_day = $region_setting[0]->max_session_per_day;
        $max_day_per_week = $region_setting[0]->max_day_per_week;
        $max_token = $region_setting[0]->max_token;
        $max_token_for_student = $region_setting[0]->max_token_for_student;
        // $max_session_per_x_day = $region_setting[0]->max_session_per_x_day;
        // $x_day = $region_setting[0]->x_day;
        $set_max_session = $region_setting[0]->set_max_session;
        $max_session_duration = $region_setting[0]->session_duration;
        $standard_coach_cost = $region_setting[0]->standard_coach_cost;
        $elite_coach_cost = $region_setting[0]->elite_coach_cost;

                     // cek perbandingan setting max region setting dengan update input
            $update_max_student_class = $this->input->post('max_student_class');
            $update_max_student_supplier = $this->input->post('max_student_supplier');
            $update_max_day_per_week = $this->input->post('max_day_per_week');
            $update_max_session_per_day = $this->input->post('max_session_per_day');
            $update_max_token = $this->input->post('max_token');
            $update_max_token_for_student = $this->input->post('max_token_for_student');
            // $update_max_session_per_x_day = $this->input->post('max_session_per_x_day');
            // $update_x_day = $this->input->post('x_day');
            $update_set_max_session = $this->input->post('set_max_session');
            $update_max_session_duration = $this->input->post('session_duration');
            $update_standard_coach_cost = $this->input->post('standard_coach_cost');
            $update_elite_coach_cost = $this->input->post('elite_coach_cost');

        if($this->input->post('__submit') == 'region_student'){
            $type = 'student';
            $rules = array(
                    array('field'=>'max_student_class', 'label' => 'max_student_class', 'rules'=>'trim|required|xss_clean'),
                    array('field'=>'max_student_supplier', 'label' => 'max_student_supplier', 'rules'=>'trim|required|xss_clean'),
                    array('field'=>'max_day_per_week', 'label' => 'max_day_per_week', 'rules'=>'trim|required|xss_clean'),
                    array('field'=>'max_session_per_day', 'label' => 'max_session_per_day', 'rules'=>'trim|required|xss_clean'),
                    array('field'=>'max_token', 'label' => 'max_token', 'rules'=>'trim|required|xss_clean'),
                    // array('field'=>'max_session_per_x_day', 'label' => 'max_session_per_x_day', 'rules'=>'trim|required|xss_clean'),
                    // array('field'=>'x_day', 'label' => 'x_day', 'rules'=>'trim|required|xss_clean')
            );

            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                redirect('superadmin/manage_partner/setting/'.$id.'/student');
            }

            if($update_max_student_class > $max_student_class){
                $message_setting = 'Max Student Class '.$max_student_class;
                $this->messages->add($message_setting, 'warning');
                redirect('superadmin/manage_partner/setting/'.$id.'/student');
            }

            if($update_max_student_supplier > $max_student_supplier){
                $message_setting = 'Max Student Affiliate '.$max_student_supplier;
                $this->messages->add($message_setting, 'warning');
                redirect('superadmin/manage_partner/setting/'.$id.'/student');
            }

            if($update_max_day_per_week > $max_day_per_week){
                $message_setting = 'Max Day Per Week '.$max_day_per_week;
                $this->messages->add($message_setting, 'warning');
                redirect('superadmin/manage_partner/setting/'.$id.'/student');
            }

            if($update_max_session_per_day > $max_session_per_day){
                $message_setting = 'Max Session Per Day '.$max_session_per_day;
                $this->messages->add($message_setting, 'warning');
                redirect('superadmin/manage_partner/setting/'.$id.'/student');
            }

            if($update_max_token > $max_token){
                $message_setting = 'Max Token '.$max_token;
                $this->messages->add($message_setting, 'warning');
                redirect('superadmin/manage_partner/setting/'.$id.'/student');
            }

            if($update_max_token_for_student > $max_token_for_student){
                $message_setting = 'Max Token For Student '.$max_token_for_student;
                $this->messages->add($message_setting, 'warning');
                redirect('superadmin/manage_partner/setting/'.$id.'/student');
            }

            // if($update_max_session_per_x_day > $max_session_per_x_day){
            //     $message_setting = 'Max Session Per X Day '.$max_session_per_x_day;
            //     $this->messages->add($message_setting, 'warning');
            //     redirect('superadmin/manage_partner/setting/'.$id.'/student');
            // }

            // if($update_x_day > $x_day){
            //     $message_setting = 'Max X Day '.$x_day;
            //     $this->messages->add($message_setting, 'warning');
            //     redirect('superadmin/manage_partner/setting/'.$id.'/student');
            // }

            // if($update_set_max_session != $set_max_session){
            //     $message_setting = 'Max Session for Student is Set to '.$set_max_session;
            //     $this->messages->add($message_setting, 'warning');
            //     redirect('superadmin/manage_partner/setting/'.$id.'/student');
            // }

            $setting = array(
                'max_student_class' => $this->input->post('max_student_class'),
                'max_student_supplier' => $this->input->post('max_student_supplier'),
                'max_day_per_week' => $this->input->post('max_day_per_week'),
                'max_session_per_day' => $this->input->post('max_session_per_day'),
                'max_token' => $this->input->post('max_token'),
                'max_token_for_student' => $this->input->post('max_token_for_student'),
                // 'max_session_per_x_day' => $this->input->post('max_session_per_x_day'), 
                // 'x_day' => $this->input->post('x_day'),
                'set_max_session' => $this->input->post('set_max_session'),
                         
            );
        } else if($this->input->post('__submit') == 'region_coach'){
            $type = 'coach';
            if($update_max_session_duration > $max_session_duration){
                $message_setting = 'Max Session Duration '.$max_session_duration;
                $this->messages->add($message_setting, 'warning');
                redirect('superadmin/manage_partner/setting/'.$id.'/coach');
            }


            if($update_elite_coach_cost > $elite_coach_cost){
                $message_setting = 'Max Elite Coach Cost '.$elite_coach_cost;
                $this->messages->add($message_setting, 'warning');
                redirect('superadmin/manage_partner/setting/'.$id.'/coach');
            }

            if($update_standard_coach_cost > $standard_coach_cost){
                $message_setting = 'Max Coach Cost '.$standard_coach_cost;
                $this->messages->add($message_setting, 'warning');
                redirect('superadmin/manage_partner/setting/'.$id.'/coach');
            }


             if(($this->input->post('standard_coach_cost') < 1) || ($this->input->post('elite_coach_cost') < 1)){
                    $this->messages->add('Standard Coach cost or Elite Coach cost minimum 1', 'warning');
                    redirect('superadmin/manage_partner/setting/'.$id.'/coach');
                    
                }

            $setting = array(
                'session_duration' => $this->input->post('session_duration'),
                'standard_coach_cost' => $this->input->post('standard_coach_cost'),
                'elite_coach_cost' => $this->input->post('elite_coach_cost')         
            );
        }


       // $this->region_model->update_setting($id,$setting);
       $this->db->where('partner_id',$id);
       $this->db->update('specific_settings',$setting);

       $this->messages->add('Update Setting Successful', 'success');

       redirect('superadmin/manage_partner/setting/'.$id.'/'.$type);
    }

    function generateRandomString($length = 5) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    function isValidEmail($email = '') {
        if ($this->user_model->where('email', $email)->get_all()) {
            return false;
        } else {
            return true;
        }
    }

    // Index
    public function list_partner_member($partner_id='',$region_id='') {
        $this->template->title = 'List Affiliate Member';
        
        if(!$partner_id){
            $this->messages->add('Invalid ID Partner', 'error');
            redirect('superadmin/manage_partner');
        }
        
        $users = $this->user_model->get_partner_members($partner_id);
        $partner = $this->partner_model->select('id, name')->where('id', $partner_id)->get();
        $vars = array(
            'region_id' => $region_id,
            'users' => $users,
            'partner' => $partner
        );

        $this->template->content->view('default/contents/manage_partner_member/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function supplier($type='',$partner_id='',$region_id=''){
        if(!$partner_id){
            $this->messages->add('Invalid ID Affiliate', 'error');
            redirect('superadmin/manage_partner');
        }
        
        $users = $this->user_model->get_partner_members($type,$partner_id);
        $partner = $this->partner_model->select('*')->where('id', $partner_id)->get();
        $vars = array(
            'region_id' => $region_id,
            'users' => $users,
            'partner' => $partner,
            'partner_id' => $partner_id,
            'type' => $type
        );

        $this->template->content->view('default/contents/manage_partner_member/supplier', $vars);

        $this->template->publish();

    }

    public function partner($type='',$partner_id='',$region_id=''){
        $this->template->title = ucfirst($type).' Affiliate';
        
        if(!$partner_id){
            $this->messages->add('Invalid ID Partner', 'error');
            redirect('superadmin/manage_partner');
        }
        
        $users = $this->user_model->get_partner_members($type,$partner_id);
        $partner = $this->partner_model->select('*')->where('id', $partner_id)->get();

        $vars = array(
            'region_id' => $region_id,
            'users' => $users,
            'partner' => $partner,
            'partner_id' => $partner_id,
            'type' => $type,
            'back' => site_url('superadmin/region/detail/'.$region_id)
        );

        $this->template->content->view('default/contents/manage_partner_member/supplier', $vars);

        $this->template->publish();

    }

    public function list_supplier($type='',$partner_id='',$region_id='', $page='') {
        if($type == ''){
            $type="coach";
        }
        $this->template->title = 'List Affiliate Member';
        
        if(!$partner_id){
            $this->messages->add('Invalid ID Affiliate', 'error');
            redirect('superadmin/manage_partner');
        }

        $offset = 0;
        $per_page = 6;
        $uri_segment = 7;


        $partner = $this->partner_model->select('*')->where('id', $partner_id)->get();

        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('superadmin/manage_partner/list_supplier/'.$type.'/'.$partner_id.'/'.$region_id.'/'), count($this->subgroup_model->select('*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id')->where('subgroup.partner_id',$partner_id)->where('subgroup.type',$type)->group_by('subgroup.id')->get_all()), $per_page, $uri_segment);
        $subgroup = $this->subgroup_model->select('*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id','left')->where('subgroup.partner_id',$partner_id)->where('subgroup.type',$type)->limit($per_page)->offset($offset)->group_by('subgroup.id')->get_all();
        
        $vars = array(
            'subgroup' => $subgroup,
            'region_id' => $region_id,
            'partner_id' => $partner_id,
            'partner' => $partner,
            'type' => $type,
            'pagination' => @$pagination

        );
        // echo "<pre>";
        // print_r($vars);
        // exit();

        // $this->template->content->view('default/contents/manage_partner_member/index', $vars);
        $this->template->content->view('default/contents/manage_partner_member/subgroup', $vars);

        //publish template
        $this->template->publish();
    }
    
     public function list_partner($type='',$partner_id='',$region_id='', $page='') {
        if($type == ''){
            $type="coach";
        }
        $this->template->title = 'List Affiliate Member';
        
        if(!$partner_id){
            $this->messages->add('Invalid ID Affiliate', 'error');
            redirect('superadmin/manage_partner');
        }

        $offset = 0;
        $per_page = 6;
        $uri_segment = 7;


        $partner = $this->partner_model->select('*')->where('id', $partner_id)->get();

        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('superadmin/manage_partner/list_supplier/'.$type.'/'.$partner_id.'/'.$region_id.'/'), count($this->subgroup_model->select('*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id')->where('subgroup.partner_id',$partner_id)->where('subgroup.type',$type)->group_by('subgroup.id')->get_all()), $per_page, $uri_segment);
        // $subgroup = $this->subgroup_model->select('*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id')->where('subgroup.partner_id',$partner_id)->where('subgroup.type',$type)->limit($per_page)->offset($offset)->group_by('subgroup.id')->get_all();
        $subgroup = $this->subgroup_model->select('subgroup.*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id','left')->where('subgroup.partner_id',$partner_id)->where('subgroup.type',$type)->limit($per_page)->offset($offset)->group_by('subgroup.id')->get_all();
        
        $vars = array(
            'subgroup' => $subgroup,
            'region_id' => $region_id,
            'partner_id' => $partner_id,
            'partner' => $partner,
            'type' => $type,
            'pagination' => @$pagination,
            'back' => site_url('superadmin/region/detail/'.$region_id)

        );
        // echo "<pre>";
        // print_r($vars);
        // exit();

        // $this->template->content->view('default/contents/manage_partner_member/index', $vars);
        $this->template->content->view('default/contents/manage_partner_member/subgroup', $vars);

        //publish template
        $this->template->publish();
    }
    
    public function move_supplier($type='',$partner_id='',$region_id='') {
        if($type == ''){
            $type="coach";
        }
        $this->template->title = 'List Affiliate Member';
        
        if(!$partner_id){
            $this->messages->add('Invalid ID Affiliate', 'error');
            redirect('superadmin/manage_partner');
        }


        $partner = $this->partner_model->select('*')->where('id', $partner_id)->get();

        $subgroup = $this->subgroup_model->select('*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id')->where('subgroup.partner_id',$partner_id)->where('subgroup.type',$type)->group_by('subgroup.id')->get_all();
  
        $get_region = $this->user_profile_model->select('*')->join('users','user_profiles.user_id = users.id')->where('users.role_id',4)->get_all();

        // echo "<pre>";
        // print_r($get_region);
        // exit();
        $vars = array(
            'get_region' => $get_region,
            'subgroup' => $subgroup,
            'region_id' => $region_id,
            'partner_id' => $partner_id,
            'partner' => $partner,
            'type' => $type
        );
        // echo "<pre>";
        // print_r($vars);
        // exit();

        // $this->template->content->view('default/contents/manage_partner_member/index', $vars);
        $this->template->content->view('default/contents/manage_partner_member/subgroup', $vars);

        //publish template
        $this->template->publish();
    }

    function get_partner_from_select(){
        $id = $_POST['id'];
        // $id = 378;
        $get_partner = $this->partner_model->select('*')->where('admin_regional_id',$id)->get_all();
        if(count($get_partner) == 0){
            echo "<option value=''>No Partner</option>";
        } else {
            foreach ($get_partner as $key) {
                echo "<option value=".$key->id.">".$key->name."</option>";
            }
        }
    }

    public function subgroup_action($type = '',$partner_id = '',$region_id = '') {

        if(!empty($_POST['check_list'])) {
            $check_list = $_POST['check_list'];
            $type_submit = $_POST['_submit'];
            if($type_submit == "subgroup_delete"){
                // check apakah group ada isinya
                $check_group = $this->user_profile_model->select('*')->where_in('subgroup_id',$check_list)->get_all();

                if(count($check_group) == 0){
                    $this->db->trans_begin();
                        $this->db->where('role_id',4);
                        $this->db->where_in('id',$check_list);
                        $this->db->delete('users');

                        $this->db->flush_cache();

                        $this->db->where_in('user_id',$check_list);
                        $this->db->delete('user_profiles');
                    $this->db->trans_commit();
                    $this->messages->add('Delete Successful', 'success');
                } else if(count($check_group) > 0){
                    $this->messages->add('Please Move your Coach to another partner', 'danger');
                }


            } else if($type_submit == "subgroup_move"){
                $move_partner = $this->input->post('move_partner');
                $this->db->trans_begin();

                    $this->db->where('type',$type);
                    $this->db->where_in('id',$check_list);
                    $this->db->update('subgroup',array('partner_id' => $move_partner));
                $this->db->trans_commit();

                $this->messages->add('Move Successful', 'success');
            }

        }

            redirect('superadmin/manage_partner/list_supplier/'.$type.'/'.$partner_id.'/'.$region_id);
    }
    
    public function delete_partner_member($user_id = ''){
        $partner = $this->identity_model->get_identity('profile')->where('user_id', $user_id)->get();
        if($this->identity_model->get_partner_identity($user_id, '', '', '')){
            if($this->user_model->delete($user_id)){
                $this->messages->add('Delete Affiliate Member Successful', 'success');
                redirect('admin/manage_partner/list_partner_member/'.$partner->partner_id);
            }
            else{
                $this->messages->add('Invalid Action', 'danger');
                redirect('account/identity/detail/profile');
            }
        }
        else{
            $this->messages->add('Invalid Action', 'danger');
            redirect('account/identity/detail/profile');
        }
        
    }
    
    private function do_upload($name) {
        $config['upload_path'] = $this->upload_path."temp";
        $config['allowed_types'] = 'gif|jpg|png|jpeg';

        $new_name = time().'-'.$_FILES["profile_picture"]['name'];
        $config['file_name'] = $new_name;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($name)) {
            echo $this->upload->display_errors();
            return FALSE;
        } else {
            return $this->upload->data();
        }
    }

    private function do_resize($image_data){
            $this->load->library('image_lib');
            $resized_path = 'uploads/images/';

            $name_file = $image_data['file_name'];

            //your desired config for the resize() function
            $config = array(
            'source_image'      => $image_data['full_path'], //path to the uploaded image
            'new_image'         => $resized_path, //path to
            'maintain_ratio'    => true,
            'width'             => 256,
            'height'            => 256
            );
         
            //this is the magic line that enables you generate multiple thumbnails
            //you have to call the initialize() function each time you call the resize()
            //otherwise it will not work and only generate one thumbnail

            $this->image_lib->initialize($config);
            return $this->image_lib->resize();
         
    }

    public function detail($partner_id='',$region_id=''){
        $this->template->title = 'Affiliate Detail';
        if(!$partner_id){
            $this->messages->add('Invalid Action', 'warning');
            redirect('superadmin/manage_partner');
        }
        $partner = $this->partner_model->select('*')->where('id', $partner_id)->get();
        if(!$partner){
            $this->messages->add('Patner is not valid', 'warning');
            redirect('superadmin/manage_partner');
        }

        // get status set partner setting
        $id_region = $this->partner_model->get_id_region($partner_id);
        $id_region = $id_region[0]->id_region;

        $status_set_setting = $this->region_model->get_specific_setting($id_region);
        $status_set_setting = $status_set_setting[0]->status_set_setting;
        // ==============

        $vars = array(
            'region_id' => $id_region,
            'partner_id' => $partner_id,
            'partner' => @$partner,
            'students' => @$this->user_profile_model->get_students($partner_id, 5, 'first_page'),
            'coaches' => @$this->user_profile_model->get_coaches($partner_id, 3, 'first_page'),
            'student_count' => @$this->user_profile_model->get_students($partner_id),
            'coach_count' => @$this->user_profile_model->get_coaches($partner_id),
            'option_country' => $this->common_function->country_code,
            'status_set_setting' => $status_set_setting
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();
        
        $this->template->content->view('default/contents/manage_partner/detail', $vars);
        $this->template->publish();
    }
    
    public function coach_detail($partner_id='', $coach_id=''){
        $this->template->title = 'Coach Detail';
        if(!$coach_id || !$partner_id){
            $this->messages->add('Invalid Action', 'warning');
            redirect('admin/manage_partner');
        }

        $this->session->set_userdata('coach_id', $coach_id);
        $data = $this->identity_model->get_coach_identity($coach_id, '', '', $partner_id);
        $partner = $this->partner_model->select('*')->where('id', $partner_id)->get();
        
        if(!$data){
            $this->messages->add('Invalid ID', 'warning');
            redirect('admin/manage_partner/'.$partner_id);
        }
        $vars = array(
            'coach_id' => $coach_id,
            'data' => $data,
            'partner' => @$partner,
            'partner_id' => $partner_id
        );
        $this->template->content->view('default/contents/admin/coach/detail', $vars);
        $this->template->publish();
    }
    
    public function student_detail($partner_id='', $student_id=''){

        $this->template->title = 'Student Detail';
        if(!$student_id || !$partner_id){
            $this->messages->add('Invalid Action', 'warning');
            redirect('admin/manage_partner');
        }
        $this->session->set_userdata('student_id', $student_id);

        $data = $this->identity_model->get_student_identity($student_id, '', '','','','','','');
        // echo "<pre>";
        // print_r($data);
        // exit();
        if(!$data){
            $this->messages->add('Invalid ID', 'warning');
            redirect('admin/manage_partner/member_of_student/'.$partner_id);
        }
        $partner = $this->partner_model->select('*')->where('id',$partner_id)->get();

        $vars = array(
            'partner_id' => $partner_id,
            'student' => $data,
            'partner' => $partner
        );
        $this->template->content->view('default/contents/admin/student/detail', $vars);
        $this->template->publish();
    }
    
    /**
     * Function availability
     * @param (string)(search_by) redirecting page by value of search_by
     * @param (string)(coach_id) coach id to get schedule
     * @param (date)(date) detail of date
     */
    
    public function availability($search_by = '', $coach_id = '', $date_ = '') {
        $this->load->library('schedule_function');
        $data = $this->schedule_function->availability($search_by, $coach_id, $date_);
        $this->template->content->view('default/contents/member_list/coach/availability', $data);
        $this->template->publish();
    }
    
    public function schedule_detail($id = '') {
        $this->load->library('schedule_function');
        $vars = $this->schedule_function->schedule_detail($id);
        $this->template->content->view('default/contents/find_coach/schedule_detail', $vars);
        $this->template->publish();
    }
    
    public function member_of_student($status='active',$subgroup_id ='', $partner_id='', $page=''){
        $this->template->title = 'Member of Student';
        
        if(!$partner_id){
            $this->messages->add('Invalid ID', 'warning');
            redirect('superadmin/manage_partner');
        }
        
        $partner = $this->partner_model->select('*')->where('id',$partner_id)->get();

        $id_region = $this->partner_model->get_id_region($partner_id);
        $id_region = $id_region[0]->id_region;
        $status_set_setting = $this->region_model->get_specific_setting($id_region);
        $status_set_setting = $status_set_setting[0]->status_set_setting;

        $subgroup = $this->subgroup_model->select('*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id')->where('subgroup.partner_id',$partner_id)->where('subgroup.type','student')->where('subgroup.id', $subgroup_id)->get_all();
        if($subgroup){
          $subgroup = $this->subgroup_model->select('*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id')->where('subgroup.partner_id',$partner_id)->where('subgroup.type','student')->where('subgroup.id', $subgroup_id)->get_all();
        }else{
            $subgroup = $this->subgroup_model->select('*')->where('subgroup.partner_id',$partner_id)->where('subgroup.type','student')->where('subgroup.id', $subgroup_id)->get_all();
        }
        $region_id = $this->auth_manager->region_id($partner_id);
        $offset = 0;
        $per_page = 6;
        $uri_segment = 7;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('superadmin/manage_partner/member_of_student/active/'.$subgroup_id.'/'.$partner_id), count($this->user_profile_model->get_students($partner_id,$subgroup_id,$status)), $per_page, $uri_segment);

        $number_page = 0;
        if($page == ''){
            $number_page = (1 * $per_page)-$per_page+1;
            
        } else {
            $number_page = ($page * $per_page)-$per_page+1;
        }
        $vars = array(
            'subgroup' => $subgroup,
            'subgroup_id' => $subgroup_id,
            'region_id' => $region_id,
            'partner' => $partner,
            'partner_id' => $partner_id,
            'title' => 'Student Member',
            'students' => $this->user_profile_model->get_students($partner_id, $subgroup_id, $status, $per_page, $offset),
            'pagination' => @$pagination,
            'status' => $status,
            'type' => 'student',
            'status_set_setting' => $status_set_setting,
            'back' => site_url('superadmin/region/detail/'.$region_id),
            'number_page' => $number_page
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();
        
        $this->template->content->view('default/contents/admin/student/index', $vars);
        $this->template->publish();
    }
    
    public function member_of_coach($status='active',$subgroup_id='', $partner_id='', $page=''){
        $this->template->title = 'Member of Coach';
        
        if(!$partner_id){
            $this->messages->add('Invalid ID', 'warning');
            redirect('superadmin/manage_partner');
        }
        
        $offset = 0;
        $per_page = 6;
        $uri_segment = 7;

        $partner = $this->partner_model->select('*')->where('id',$partner_id)->get();
        
        $subgroup = $this->subgroup_model->select('*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id')->where('subgroup.partner_id',$partner_id)->where('subgroup.type','coach')->where('subgroup.id', $subgroup_id)->get_all();
        if($subgroup){
          $subgroup = $this->subgroup_model->select('*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id')->where('subgroup.partner_id',$partner_id)->where('subgroup.type','coach')->where('subgroup.id', $subgroup_id)->get_all();
        }else{
            $subgroup = $this->subgroup_model->select('*')->where('subgroup.partner_id',$partner_id)->where('subgroup.type','coach')->where('subgroup.id', $subgroup_id)->get_all();
        }
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('superadmin/manage_partner/member_of_coach/'.$status.'/'.$subgroup_id.'/'.$partner_id), count($this->user_profile_model->get_coaches($partner_id,$subgroup_id,$status)), $per_page, $uri_segment);

        $region_id = $this->auth_manager->region_id($partner_id);

        $number_page = 0;
        if($page == ''){
            $number_page = (1 * $per_page)-$per_page+1;
            
        } else {
            $number_page = ($page * $per_page)-$per_page+1;
        }

        $vars = array(
            'subgroup' => $subgroup,
            'subgroup_id' => $subgroup_id,
            'region_id' => $region_id,
            'partner' => $partner,
            'partner_id' => $partner_id,
            'title' => 'Coach Member',
            'coaches' => $this->user_profile_model->get_coaches($partner_id,$subgroup_id,$status, $per_page, $offset),
            'pagination' => @$pagination,
            'status' => $status,
            'type' => 'coach',
            'back' => site_url('superadmin/region/detail/'.$region_id),
            'number_page' => $number_page
        );

        // echo "<pre>";
        // print_r($vars);
        // exit(); 
        
        $this->template->content->view('default/contents/admin/coach/index', $vars);
        $this->template->publish();
    }
    
    public function upload_profile_picture($partner_id='',$type=''){

        $profile_picture = $this->do_upload('profile_picture');

        $resize = $this->do_resize($profile_picture);

        
        if (!$profile_picture) {
            $this->messages->add('Failed to upload image', 'error');
            return $this->detail($partner_id);
        } 

        $data_profile_picture['profile_picture'] = $this->upload_path . $profile_picture['file_name'];
        
        if($type == 'region'){
            if (!$this->identity_model->update_profile($partner_id,$data_profile_picture, TRUE)) {
                $this->messages->add(validation_errors(), 'warning');
                return $this->detail($partner_id);
            }

        } else if($type != 'region'){

            if (!$this->partner_model->update($partner_id, $data_profile_picture, TRUE)) {
                $this->messages->add(validation_errors(), 'warning');
                return $this->detail($partner_id);
            }
        }

        // delete image asli
            $delete_image = $this->upload_path."temp/".$profile_picture['file_name'];
            unlink($delete_image);

        $this->messages->add('Update Successful', 'success');
        redirect('admin/manage_partner/detail/'.$partner_id);
    }

    public function approve_coach($page=''){

        $this->template->title = 'Approve Coach Affiliate';
        
        $offset = 0;
        $per_page = 6;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('superadmin/managing_partner/index'), count($this->user_model->select('id, email, role_id, status')->where('status', 'disable')->where('role_id',2)->order_by('dcrea', 'desc')->get_all()), $per_page, $uri_segment);
        
        $users = $this->user_model->superadmin_get_approval_supplier(3,$per_page, $offset);

        $vars = array(
            'users' => $users,
            'pagination' => @$pagination
        );

        $this->template->content->view('default/contents/superadmin/approval/coach', $vars);

        //publish templates
        $this->template->publish();

    }

    public function approve_student($page=''){

        $this->template->title = 'Approve Student Affiliate';
        
        $offset = 0;
        $per_page = 6;
        $uri_segment = 4;
        // $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('superadmin/managing_partner/index'), count($this->user_model->select('id, email, role_id, status')->where('status', 'disable')->order_by('dcrea', 'desc')->get_all()), $per_page, $uri_segment);
        
        $vars = array(
            'users' => $this->user_model->get_approval_supplier(5,$per_page, $offset),
            // 'pagination' => @$pagination
        );

        $this->template->content->view('default/contents/superadmin/approval/student', $vars);

        //publish template
        $this->template->publish();

    }

    public function approve_supplier($id = '', $type='') {
        // echo $id." - ".$type; exit;
        // Checking ID
        if (!$id) {
            $this->messages->add('Invalid User ID', 'danger');
            redirect('superadmin/managing_partner');
        }

        // Storing user data
        $user_data = $this->user_model->select('users.email as email, users.password as password, user_profiles.fullname as fullname')->join('user_profiles','user_profiles.user_id = users.id')->where('users.id', $id)->get();

        $partner = $this->creator_member_model->select('creator_members.member_id as member_id, creator_members.creator_id as creator_id, user_profiles.fullname as fullname, users.email as email')->join('users','users.id = creator_members.creator_id')->join('user_profiles','user_profiles.user_id = creator_members.creator_id')->where('creator_members.member_id', $id)->get();

        $users = array(
            'email' => $user_data->email,
            'password' => $user_data->password,
            'status' => 'active',
            'fullname' => $user_data->fullname
        );

        // echo "<pre>";
        // print_r($users);
        // exit;

        // Inserting and checking
        if (!$this->user_model->update($id, $users)) {
            $this->messages->add(validation_errors(), 'Invalid ID');
            redirect('superadmin/managing_partner');
        }

        // email
        $this->send_email->superadmin_approval_supplier($user_data->email,'approved',$user_data->fullname);
        $this->send_email->notif_creator($user_data->email,'approved',$user_data->fullname, $partner->email,$partner->fullname);
        
        // messaging notification 
        $database_tube = 'com.live.database';
        $partner_notification = array(
            'user_id' => $partner->creator_id,
            'description' =>  ucfirst($user_data->fullname). ' has been approved by Super Admin',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );

        $this->user_notification_model->insert($partner_notification);
        
        $member_notification = array(
            'user_id' => $partner->member_id,
            'description' => 'Congratulation ' .$user_data->fullname. ' and Welcome to DynEd Live',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );
        
        $this->user_notification_model->insert($member_notification);
        
        // coach's data for acceptence student information messaging
        // IMPORTANT : array index in content must be in mutual with table field in database
        $data_partner = array(
            'table' => 'user_notifications',
            'content' => $partner_notification,
        );
        
        // student's data for acceptence student information messaging
        // IMPORTANT : array index in content must be in mutual with table field in database
        $data_member = array(
            'table' => 'user_notifications',
            'content' => $member_notification,
        );

        // messaging inserting data notification for creator
        //$this->queue->push($database_tube, $data_partner, 'database.insert');
        // messaging inserting data notification for member
        //$this->queue->push($database_tube, $data_member, 'database.insert');

        $this->messages->add('Update Successful', 'success');
        redirect('superadmin/manage_partner/approve_'.$type);
    }

    public function decline_supplier($id = '', $type='') {
        // Checking ID
        if (!$id) {
            $this->messages->add('Invalid User ID', 'danger');
            redirect('superadmin/managing_partner');
        }
        
       
        // Pushing queues to Pheanstalk Server
        //$this->queue->push($tube, $data, 'email.send_email');
        $user_data = $this->user_model->select('users.email as email, users.password as password, user_profiles.fullname as fullname')->join('user_profiles','user_profiles.user_id = users.id')->where('users.id', $id)->get();
        $partner = $this->creator_member_model->select('creator_members.member_id as member_id, creator_members.creator_id as creator_id, user_profiles.fullname as fullname, users.email as email')->join('users','users.id = creator_members.creator_id')->join('user_profiles','user_profiles.user_id = creator_members.creator_id')->where('creator_members.member_id', $id)->get();
        // Tube name for messaging action
        // get user token
        $data_id_token = array($id,$partner->creator_id);
        $get_token_user = $this->db->select('token_amount')
                                   ->from('user_tokens')
                                   ->where_in('user_id',$data_id_token)
                                   ->get()->result();


        $token_user = $get_token_user[1]->token_amount;
        $token_region = $get_token_user[0]->token_amount;

        $total_token_region = $token_region+$token_user;
        
        // update token region
        $data_region = array('token_amount' => $total_token_region,
                  'dcrea' => time(),
                  'dupd' => time()
                );
                $update_student_token = $this->db->where('user_id', $partner->creator_id)
                                              ->update('user_tokens', $data_region);

        // messaging notification 
        if($type == 'student'){
            $partner_notification = array(
                'user_id' => $partner->creator_id,
                'description' => 'The student affiliate ' .$user_data->fullname. ' has been declined and your ' .$token_user. ' tokens have been refunded',
                'status' => 2,
                'dcrea' => time(),
                'dupd' => time(),
            );
        }elseif($type == 'coach'){
            $partner_notification = array(
                'user_id' => $partner->creator_id,
                'description' => 'The coach affiliate ' .$user_data->fullname. ' has been declined',
                'status' => 2,
                'dcrea' => time(),
                'dupd' => time(),
            );
        }

        
        
        // coach's data for acceptence student information messaging
        // IMPORTANT : array index in content must be in mutual with table field in database
        $data_partner = array(
            'table' => 'user_notifications',
            'content' => $partner_notification,
        );

        $this->user_notification_model->insert($partner_notification);

        // delete user from user profile
        $delete_user_profiles = $this->db->where('user_id',$id)->delete('user_profiles');
        // Inserting and checking
        if (!$this->user_model->delete($id)) {
            $this->messages->add(validation_errors(), 'Invalid ID');
            redirect('superadmin/manage_partner/approve_'.$type);
        }

        $this->send_email->superadmin_approval_supplier($user_data->email,'declined',$user_data->fullname);
        $this->send_email->notif_creator($user_data->email,'declined',$user_data->fullname, $partner->email, $partner->fullname);
        
        // Email's content to inform students that their account has been activated

        
        $this->messages->add('Update Successful', 'success');
        redirect('superadmin/manage_partner/approve_'.$type);
    }

    public function token(){
        $this->template->title = 'Token Request';
        $vars = array(
            'data' => $this->token_request_model->get_token_request(null,4),
        );

        $this->template->content->view('default/contents/superadmin/token/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function history_token($page=''){
        // set gmt superadmin
        date_default_timezone_set('Etc/GMT-0');

        $this->template->title = 'Token Request History';
       
        $offset = 0;
        $per_page = 20;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('superadmin/manage_partner/history_token/'), count($this->history_request_model->select('token_requests.id, up.region_id as region_id, u.email, up.fullname, token_requests.token_amount, token_requests.status')->join('users u', 'u.id = token_requests.user_id')->join('user_profiles up', 'up.user_id = u.id')->where('u.role_id', 4)->where('u.status', 'active')->where('token_requests.status !=','requested')->order_by('token_requests.id', 'desc')->get_all()), $per_page, $uri_segment);

        $id = $this->auth_manager->userid();

        $vars = array(
            // 'data' => $this->history_request_model->select('token_requests.id, token_requests.dcrea, token_requests.dupd, up.region_id as region_id, u.email, up.fullname, token_requests.token_amount, token_requests.status')->join('users u', 'u.id = token_requests.user_id')->join('user_profiles up', 'up.user_id = u.id')->where('u.role_id', 4)->where('u.status', 'active')->where('token_requests.status !=','requested')->order_by('token_requests.id', 'desc')->limit($per_page)->offset($offset)->get_all(),
            'data' => $this->history_request_model->select('token_requests.id, token_requests.dcrea, token_requests.dupd, up.region_id as region_id, u.email, up.fullname, token_requests.token_amount, token_requests.status')->join('users u', 'u.id = token_requests.user_id')->join('user_profiles up', 'up.user_id = u.id')->where('u.role_id', 4)->where('u.status', 'active')->where('token_requests.status !=','requested')->order_by('token_requests.id', 'desc')->get_all(),
            // 'pagination' => $pagination
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();

        $this->template->content->view('default/contents/superadmin/token/history_token', $vars);

        //publish template
        $this->template->publish();
    }

    public function approve_token($token_request_id = '') {
        date_default_timezone_set('Etc/GMT+0');
        if ($this->token_request_model->get_token_request($token_request_id,4)) {
            $token_request = $this->token_request_model->select('id, user_id, token_amount')->where('id', $token_request_id)->get();
            $token = $this->identity_model->get_identity('token')->select('id, user_id, token_amount')->where('user_id', $token_request->user_id)->get();
            
            $cur_token_ammount = '';
            if(count($token) == 0){
                $cur_token_ammount = 0;
            } else if(count($token) > 0){
                $cur_token_ammount = $token->token_amount;
            }

            // update student token 
            $current_token = $token_request->token_amount + $cur_token_ammount;
            $update_data = array(
                'token_amount' => $current_token,
            );
            $this->identity_model->get_identity('token')->update($token->id,$update_data);

            // jika data token belum ada

             if(count($token) == 0){
                $token_student_supplier = [
                                            'user_id' => $token_request->user_id,
                                            'token_amount' => $current_token,
                                            'dcrea' => time(),
                                            'dupd' => time()
                                            ];
                $this->user_token_model->insert($token_student_supplier);
            }
            // =========================
            
            $token_history = array(
                'user_id' => $token_request->user_id,
                'transaction_date' => time(),
                'token_amount' => $token_request->token_amount,
                'description' => 'Super admin has approved you token request.',
                'token_status_id' => 3,
                'balance' => $current_token,
                'dcrea' => time(),
                'dupd' => time()
            );

            $this->token_histories_model->insert($token_history);

            $user_data = $this->user_model->select('users.email as email, user_profiles.fullname as fullname')->join('user_profiles','user_profiles.user_id = users.id')->where('users.id',$token_request->user_id)->get();          
            
            $student_data = array(
                'token_amount'
            );
            $data = array(
                'status' => 'approved'
            );
            $this->token_request_model->update($token_request_id, $data);
            // $this->messaging_admin($token_request_id, 'approved');

            $partner_notification = array(
                'user_id' => $token_request->user_id,
                'description' => 'Your token request '.$token_request->token_amount.' has been approved by Super Admin.',
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

            // messaging inserting data notification

            $this->user_notification_model->insert($partner_notification);
            // ===============================
            // email
            $this->send_email->send_region_approve_token($user_data->email,'approved',$user_data->fullname,$token_request->token_amount);

            $this->messages->add('Approve Token Request Succeded', 'success');
            redirect('superadmin/manage_partner/token');
        }
        else{
            $this->messages->add('Token Might be Cancelled by Region Admin', 'error');
            redirect('superadmin/manage_partner/token');
        }
    }

    public function decline_token($token_request_id = '') {

        date_default_timezone_set('Etc/GMT+0');
        if ($this->token_request_model->get_token_request($token_request_id,4)) {
            $data = array(
                'status' => 'declined',
                'dupd' => time()
            );

            $this->token_request_model->update($token_request_id, $data);
            // $this->messaging_admin($token_request_id, 'declined');
            $token_request = $this->token_request_model->select('id, user_id, token_amount')->where('id', $token_request_id)->get();
            $user_data = $this->user_model->select('users.email as email, user_profiles.fullname as fullname')->join('user_profiles','user_profiles.user_id = users.id')->where('users.id',$token_request->user_id)->get();
            $cur_token_ammount = $token_request->token_amount;
                $partner_notification = array(
                'user_id' => $token_request->user_id,
                'description' => 'Your token request '.$cur_token_ammount.' has been declined by Super Admin.',
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

            // messaging inserting data notification

            $this->user_notification_model->insert($partner_notification);
            // ===============================
            // email

            $this->send_email->send_region_approve_token($user_data->email,'declined',$user_data->fullname,$token_request->token_amount);

            $this->messages->add('Decline Token Request Succeded', 'success');
            redirect('superadmin/manage_partner/token');
        }
        else{

            $this->messages->add('Token Might be Cancelled by Region Admin', 'error');
            redirect('superadmin/manage_partner/token');
        }
    }
}
