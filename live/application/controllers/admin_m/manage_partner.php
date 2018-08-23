<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class manage_partner extends MY_Site_Controller {

    var $upload_path = 'uploads/images/propic/';

    // Constructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('partner_model');
        $this->load->model('region_model');
        $this->load->model('user_model');
        $this->load->model('identity_model');
        $this->load->model('creator_member_model');
        $this->load->model('token_request_model');
        $this->load->model('history_request_model');
        $this->load->model('user_token_model');
        $this->load->model('user_geography_model');
        $this->load->model('user_profile_model');
        $this->load->model('subgroup_model');
        $this->load->model('specific_settings_model');
        $this->load->model('user_token_model');
        $this->load->model('global_settings_model');


        $this->load->library('phpass');
        $this->load->library('schedule_function');
        $this->load->library('common_function');
        $this->load->library('email_structure');
        $this->load->library('send_email');
        $this->load->library('send_sms');
        $this->load->model('user_notification_model');



        // for messaging action and timing
        $this->load->library('queue');

        date_default_timezone_set('Etc/GMT+0');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'RAM') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index($page='') {
        $this->template->title = 'Affiliates';

        $id_user = $this->auth->userid();

        $pull_reg_id = $this->db->select('region_id')
                     ->from('user_profiles')
                     ->where('user_id', $id_user)
                     ->get()->result();

        $reg_id = $pull_reg_id[0]->region_id;

        $pull_m_id = $this->db->select('u.id')
                      ->from('users u')
                      ->join('user_profiles us', 'us.user_id = u.id' )
                      ->where('u.role_id', '4')
                      ->where('us.region_id', $reg_id)
                      ->get()->result();

        $id = $pull_m_id[0]->id;

        // echo "<pre>";print_r($id);exit();

        $offset = 0;
        $per_page = 6;
        $uri_segment = 4;
        // $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/manage_partner_m/index'), count($this->partner_model->select('id, profile_picture, name, address, country, state, city, zip')->where('name not like', 'No Partner')->where('admin_regional_id',$id_region)->order_by('name', 'asc')->get_all()), $per_page, $uri_segment);

        // search
        $search_region = $this->input->post('search_partner');
        if($search_region != ''){
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/manage_partner_m/index'), count($this->partner_model->select('id, profile_picture, name, address, country, state, city, zip')->where('name not like', 'No Partner')->like('name',$search_region)->where('admin_regional_id',$id)->order_by('name', 'asc')->get_all()), $per_page, $uri_segment);
            $partner = $this->partner_model->select('id, profile_picture, name, address, country, state, city, zip')->where('admin_regional_id', $id)->where('admin_regional_id',$id)->like('name',$search_region)->order_by('name', 'asc')->limit($per_page)->offset($offset)->get_all();
        } else {
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/manage_partner_m/index'), count($this->partner_model->select('id, profile_picture, name, address, country, state, city, zip')->where('name not like', 'No Partner')->where('admin_regional_id',$id)->order_by('name', 'asc')->get_all()), $per_page, $uri_segment);
            $partner = $this->partner_model->select('id, profile_picture, name, address, country, state, city, zip')->where('admin_regional_id', $id)->where('admin_regional_id',$id)->order_by('name', 'asc')->limit($per_page)->offset($offset)->get_all();
        }

        // =========

        $vars = array(
            'partner' => $partner,
            'pagination' => @$pagination
        );


        $this->template->content->view('default/contents/manage_partner_m/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function add_partner() {
        $this->template->title = 'Add Affiliate';
        $vars = array(
            'option_country' => $this->common_function->country_code,
            'form_action' => 'create_partner'
        );

        $this->template->content->view('default/contents/manage_partner_m/add_partner/form', $vars);
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
            $this->messages->add('Update Affiliate`s Profile Picture Failed', 'success');
            redirect('admin_m/manage_partner_m/edit_partner/'.$id);
        }

        $this->messages->add('Affiliate`s Profile Picture has been Updated Successfully', 'success');
        redirect('admin_m/manage_partner_m/edit_partner/'.$id);
    }

    public function create_partner() {
        // Creating a partner
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('admin_m/manage_partner');
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
                redirect('admin_m/manage_partner_m/add_partner');
            }

        // inserting user data
        $partner = array(
            'admin_regional_id' => $this->auth_manager->userid(),
            'profile_picture' => 'uploads/images/default_logo.jpg',
            'name' => $this->input->post('name'),
            'address' => $this->input->post('address'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'zip' => $this->input->post('zip'),
            'country' => $this->input->post('country'),
        );

        $admin_profile = $this->user_profile_model->select('fullname, region_id')->where('user_id', $this->auth_manager->userid())->get_all();
        $admin_name = $admin_profile[0]->fullname;
        $admin_region = $admin_profile[0]->region_id;

        $superadmin_profile = $this->user_model->select('id, email')->where('role_id', 7)->get_all();
        $superadmin_id = $superadmin_profile[0]->id;
        $superadmin_email = $superadmin_profile[0]->email;

        // Inserting and checking to partner table
        $this->db->trans_begin();
        $user_id = $this->partner_model->insert($partner);
        if (!$user_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->add_partner();
            return;
        }
             // get global setting for partner

        $region_setting = $this->region_model->get_global_setting('partner');

        $specific_settings = [
            'partner_id' => $user_id,
            'session_duration' => $region_setting[0]->session_duration,
            'session_duration' => $region_setting[0]->session_duration,
            'standard_coach_cost' => $region_setting[0]->standard_coach_cost,
            'max_student_supplier' => $region_setting[0]->max_student_supplier,
            'max_student_class' => $region_setting[0]->max_student_class,
            'max_session_per_day' => $region_setting[0]->max_session_per_day,
            'max_day_per_week' => $region_setting[0]->max_day_per_week,
            // 'max_session_per_x_day' => $region_setting[0]->max_session_per_x_day,
            // 'x_day' => $region_setting[0]->x_day,
            'set_max_session' => $region_setting[0]->set_max_session,
            'max_token' => $region_setting[0]->max_token,
            'max_token_for_student' => $region_setting[0]->max_token_for_student,
            'status_set_setting' => $region_setting[0]->status_set_setting,
            'type' => $region_setting[0]->type
        ];

        // insert into table specific setting
        $this->region_model->insert_specific_setting($specific_settings);

        // insert into user token
        $this->db->insert('user_tokens', array('partner_id' => $user_id, 'token_amount' => $region_setting[0]->max_token));

        $this->db->trans_commit();

        $this->send_email->admin_create_affiliate($this->input->post('name'), $admin_name, $admin_region, $superadmin_email);

        $this->messages->add('Inserting Affiliate Successful', 'success');
        redirect('admin_m/manage_partner');
    }

    public function edit_partner($id = '') {
        $this->template->title = 'Edit Affiliate';
        $data = $this->partner_model->select('id, name, address, country, state, city, zip')->where('id', $id)->get();
        $vars = array(
            'data' => $data,
            'form_action' => 'update_partner',
            'partner' => $this->partner_model->select('profile_picture')->where('id', $id)->get()
        );
        $this->template->content->view('default/contents/manage_partner_m/add_partner/form', $vars);
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
        redirect('admin_m/manage_partner_m/detail/'.$id);
    }

    // Delete
    // public function delete_partner($id = '') {
    //     $this->partner_model->delete($id);
    //     $this->messages->add('Delete Succeeded', 'success');
    //     redirect('admin_m/manage_partner');
    // }

    function delete_partner(){

        if(!empty($_POST['check_list'])) {
            $check_list = $_POST['check_list'];
            $type_submit = $_POST['_submit'];

            // check apakah partner mempunyai supplier
            $check = $this->db->select('*')->from('user_profiles')->where_in('partner_id',$check_list)->get();
            if($check->num_rows() == 0){

                $this->db->trans_begin();
                $this->db->where_in('id',$check_list);
                $this->db->delete('partners');

                $this->db->trans_commit();
                $this->messages->add('Delete Successful', 'success');
            } else {
                $this->messages->add('You still have member', 'error');

            }
        } else {
            $this->messages->add('Please select region', 'error');
        }
        redirect('admin_m/manage_partner');
    }

    public function add_partner_member($partner = '',$role_id = '') {

        $this->template->title = 'Add Affiliate Member';
        $id_user = $this->auth->userid();

$pull_reg_id = $this->db->select('region_id')
             ->from('user_profiles')
             ->where('user_id', $id_user)
             ->get()->result();

$reg_id = $pull_reg_id[0]->region_id;

$pull_m_id = $this->db->select('u.id')
              ->from('users u')
              ->join('user_profiles us', 'us.user_id = u.id' )
              ->where('u.role_id', '4')
              ->where('us.region_id', $reg_id)
              ->get()->result();

$id = $pull_m_id[0]->id;

        $partner = $this->partner_model->select('name, address, country, state, city, zip')->where('id',$partner)->get_all();
        $partner_country = $partner[0]->country;

        $option_country = $this->common_function->country_code;
        $code = array_column($option_country, 'dial_code', 'name');
        $newoptions = $code;
        $arsearch = array_search($partner_country, array_column($option_country, 'name'));
        $dial_code = $option_country[$arsearch]['dial_code'];

        $vars = array(
            'selected' => $partner,
            'partner' => $this->partner_model->where('name not like', 'No Partner')->where('admin_regional_id', $id)->dropdown('id', 'name'),
            'form_action' => 'create_partner_member',
            'option_country' => $this->common_function->country_code,
            'partner_country' => $partner_country,
            'dial_code' => $dial_code
        );


        $this->template->content->view('default/contents/manage_partner_m/add_partner_member/form', $vars);
        $this->template->publish();
    }

    public function create_partner_member() {
        // Creating a member user as role partner
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('admin_m/manage_partner');
        }

        $partner = $this->partner_model->select('name, address, country, state, city, zip')->where('id',$this->input->post('partner_id'))->get_all();
        $partner_country = $partner[0]->country;

        $option_country = $this->common_function->country_code;
        $code = array_column($option_country, 'dial_code', 'name');
        $newoptions = $code;
        $arsearch = array_search($partner_country, array_column($option_country, 'name'));
        $dial_code = $option_country[$arsearch]['dial_code'];

        // generating password
        $password = $this->generateRandomString();
        $get_id_region = $this->partner_model->select('admin_regional_id,name')->where('id', $this->input->post('partner_id'))->get();
        // inserting user data
        if($this->input->post('role_id') != 3 && $this->input->post('role_id') != 5){
            $this->messages->add('Invalid affiliate type', 'danger');
            redirect('admin_m/manage_partner');
        }

        if(!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)){
            $this->messages->add('Invalid Email', 'danger');
            $this->add_partner_member($this->input->post('partner_id'));
            return;
        }

        $user = array(
            'email' => $this->input->post('email'),
            'password' => $this->phpass->hash($password),
            'role_id' => $this->input->post('role_id'),
            'status' => 'disable',
        );


        // checking if the email is valid/ not been used yet
        if (!$this->isValidEmail($this->input->post('email'))) {
            $this->messages->add('Email has been used', 'danger');
            $this->add_partner_member($this->input->post('partner_id'));
            return;
        }


        // check token
        if($this->input->post('role_id') == 5){
            $id = $this->auth_manager->userid();
            $request_token = $this->input->post('token_amount');


            // check apakah status setting region allow atau disallow
            $partner_id = $this->input->post('partner_id');
            $region_id = $this->auth_manager->region_id($partner_id);

            $get_status_setting_region = $this->specific_settings_model->get_specific_settings($region_id,'region');

            $max_token_student_supplier = '';
            if($get_status_setting_region[0]->status_set_setting == 0){
                $get_setting = $this->global_settings_model->get_partner_settings();
                $max_token_student_supplier = $get_setting[0]->max_token;
            } else {
                $get_setting = $this->specific_settings_model->get_partner_settings($partner_id);
                $max_token_student_supplier = $get_setting[0]->max_token;
            }

            // =======================

            // check token admin regional
            $get_user_token = $this->user_token_model->get_token($id,'user');
            $user_token = $get_user_token[0]->token_amount;

            // check jika token user tidak mencukupi
            if($user_token < $request_token){
                $this->messages->add('Your token not enough ', 'warning');
                redirect('admin_m/manage_partner_m/add_partner_member/'.$partner_id.'/'.$this->input->post('partner_id').'/student/');
            }

            // check jika request melebihi maksimal
            if($request_token > $max_token_student_supplier){
                $this->messages->add('Token Request exceeds the maximum, maximum token for student affiliate = '.$max_student_supplier, 'warning');
                redirect('admin_m/manage_partner_m/add_partner_member/'.$partner_id.'/'.$this->input->post('partner_id').'/student');
            }

            $total_token_region = $user_token-$request_token;

        }


        // end of check token


        // Inserting and checking to users table then storing insert_id into $insert_id
        $this->db->trans_begin();
        $user_id = $this->user_model->insert($user);
        if (!$user_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
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
        );

        // Inserting and checking to profile table then storing it into users_profile table
        $profile_id = $this->identity_model->get_identity('profile')->insert($profile);
        if (!$profile_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
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

        // insert creator member_of_student

        $creator_member = array(
                'creator_id' => $this->auth_manager->userid(),
                'member_id' => $user_id
            );

        $this->creator_member_model->insert($creator_member);

        // update token region
        if($this->input->post('role_id') == 5){
            $data_region = array('token_amount' => $total_token_region,
                  'dcrea' => time(),
                  'dupd' => time()
                );
                $update_student_token = $this->db->where('user_id', $id)
                                              ->update('user_tokens', $data_region);

            // insert into token request
            $dt_token_req = array('token_amount' => $request_token,
                          'approve_id' => $id,
                          'user_id' => $user_id,
                          'status' => 'given',
                          'dcrea' => time(),
                          'dupd' => time()
                         );

            $this->db->insert('token_requests',$dt_token_req);
        }

        $this->db->trans_commit();

        $id_to_email_address = $this->user_model->dropdown('id', 'email');
        // Tube name for messaging sending email action
        $tor = '';
        if($this->input->post('role_id') == 3){
            $tor = 'coach Affiliate';
            $type = 'Coach Affiliate';
        } else if($this->input->post('role_id') == 5){
            $tor = 'student Affiliate';
            $type = 'Student Affiliate';
        }

        // inserting user token data
        $token = array(
            'user_id' => $user_id,
            'token_amount' => $this->input->post('token_amount'),
        );

        // Inserting and checking to profile table then storing it into users_profile table
        $token_id = $this->user_token_model->insert($token);

        $partner_notification = array(
            'user_id' => '341',
            'description' => 'New '.$type.' registered, please approve/decline it.',
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

        $superadmin = $this->user_model->select('email')->where('role_id', 7)->get();
        $email_superadmin = $superadmin->email;

        // messaging inserting data notification

        $this->user_notification_model->insert($partner_notification);

        // email
        $this->send_email->admin_create_supplier($this->input->post('email'),$password,'created',$this->input->post('fullname'),$get_id_region->name,$tor);
        $this->send_email->notif_superadmin($email_superadmin,$password,'created',$this->input->post('fullname'),$get_id_region->name,$tor,$type);
        //$this->send_sms->create_partner($full_number, $this->input->post('fullname'), $this->input->post('email'));

        $this->messages->add('Inserting Affiliate Member Successful', 'success');
        if($this->input->post('role_id') == 3){
            redirect('admin_m/manage_partner_m/partner/coach/'.$this->input->post('partner_id'));
        }elseif($this->input->post('role_id') == 5){
            redirect('admin_m/manage_partner_m/partner/student/'.$this->input->post('partner_id'));
        }
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
    public function list_partner_member($partner_id='') {
        $this->template->title = 'List Affiliate Member';

        if(!$partner_id){
            $this->messages->add('Invalid ID Affiliate', 'error');
            redirect('admin_m/manage_partner');
        }

        $users = $this->user_model->get_partner_members($partner_id);
        $partner = $this->partner_model->select('id, name')->where('id', $partner_id)->get();
        $vars = array(
            'users' => $users,
            'partner' => $partner
        );
        $this->template->content->view('default/contents/manage_partner_member_m/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function supplier($type='',$partner_id='',$region_id=''){
        if(!$partner_id){
            $this->messages->add('Invalid ID Affiliate', 'error');
            redirect('admin_m/manage_partner');
        }

        $this->template->title = 'Affiliate';

        $users = $this->user_model->get_partner_members($type,$partner_id);
        $partner = $this->partner_model->select('*')->where('id', $partner_id)->get();
        $back = site_url('admin_m/manage_partner');

        $vars = array(
            'region_id' => $region_id,
            'users' => $users,
            'partner' => $partner,
            'partner_id' => $partner_id,
            'type' => $type
        );

        $this->template->content->view('default/contents/manage_partner_member_m/supplier', $vars);

        $this->template->publish();

    }

    public function partner($type='',$partner_id='',$region_id=''){

        $this->template->title = ucfirst($type).' Affiliate';

        if(!$partner_id){
            $this->messages->add('Invalid ID Affiliate', 'error');
            redirect('admin_m/manage_partner');
        }

        $users = $this->user_model->get_partner_members($type,$partner_id);
        $partner = $this->partner_model->select('*')->where('id', $partner_id)->get();

        $vars = array(
            'region_id' => $region_id,
            'users' => $users,
            'partner' => $partner,
            'partner_id' => $partner_id,
            'type' => $type,
            'back' => site_url('admin_m/manage_partner'),
            'option_country' => $this->common_function->country_code
        );

        $this->template->content->view('default/contents/manage_partner_member_m/supplier', $vars);

        $this->template->publish();

    }

    public function list_supplier($type='',$partner_id='', $page='' ) {
        if($type == ''){
            $type="coach";
        }

        $this->template->title = 'List Affiliate Member';

        if(!$partner_id){
            $this->messages->add('Invalid ID Affiliate', 'error');
            redirect('admin_m/manage_partner');
        }

        $offset = 0;
        $per_page = 6;
        $uri_segment = 6;


        $partner = $this->partner_model->select('*')->where('id', $partner_id)->get();

        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/manage_partner_m/list_supplier/'.$type.'/'.$partner_id.'/'), count($this->subgroup_model->select('*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id')->where('subgroup.partner_id',$partner_id)->where('subgroup.type',$type)->group_by('subgroup.id')->get_all()), $per_page, $uri_segment);
        $subgroup = $this->subgroup_model->select('*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id')->where('subgroup.partner_id',$partner_id)->where('subgroup.type',$type)->limit($per_page)->offset($offset)->group_by('subgroup.id')->get_all();

        $vars = array(
            'subgroup' => $subgroup,
            'region_id' => '',
            'partner_id' => $partner_id,
            'partner' => $partner,
            'type' => $type,
            'pagination' => @$pagination
        );
        // echo "<pre>";
        // print_r($vars);
        // exit();

        // $this->template->content->view('default/contents/manage_partner_member_m/index', $vars);
        $this->template->content->view('default/contents/manage_partner_member_m/subgroup', $vars);

        //publish template
        $this->template->publish();
    }

    public function list_partner($type='',$partner_id='', $page='' ) {
        if($type == ''){
            $type="coach";
        }

        $this->template->title = 'List Affiliate Member';

        if(!$partner_id){
            $this->messages->add('Invalid ID Affiliate', 'error');
            redirect('admin_m/manage_partner');
        }

        $offset = 0;
        $per_page = 5;
        $uri_segment = 6;

        $number_page = 0;
        if($page == ''){
            $number_page = (1 * $per_page)-$per_page+1;

        } else {
            $number_page = ($page * $per_page)-$per_page+1;
        }


        $partner = $this->partner_model->select('*')->where('id', $partner_id)->get();
        // echo $partner_id;
        // exit();

        // $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/manage_partner_m/list_partner/'.$type.'/'.$partner_id.'/'), count($this->subgroup_model->select('*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id')->where('subgroup.partner_id',$partner_id)->where('subgroup.type',$type)->get_all()), $per_page, $uri_segment);

        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/manage_partner/list_partner/'.$type.'/'.$partner_id.'/'), count($this->subgroup_model->select('*')->where('subgroup.partner_id',$partner_id)->where('subgroup.type',$type)->get_all()), $per_page, $uri_segment);

        // $subgroup = $this->subgroup_model->select('subgroup.*')->join('user_profiles','user_profiles.partner_id = subgroup.partner_id','left')->where('subgroup.partner_id',$partner_id)->where('subgroup.type',$type)->limit($per_page)->offset($offset)->group_by('subgroup.id')->get_all();

        $subgroup = $this->subgroup_model->select('subgroup.*')->where('subgroup.partner_id',$partner_id)->where('subgroup.type',$type)->limit($per_page)->offset($offset)->group_by('subgroup.id')->get_all();

        $vars = array(
            'subgroup' => $subgroup,
            'region_id' => '',
            'partner_id' => $partner_id,
            'partner' => $partner,
            'type' => $type,
            'pagination' => @$pagination,
            'back' => site_url('admin_m/manage_partner_m/detail/'.$partner_id),
            'number_page' => $number_page
        );
        // echo "<pre>";
        // print_r($vars);
        // exit();

        // $this->template->content->view('default/contents/manage_partner_member_m/index', $vars);
        $this->template->content->view('default/contents/manage_partner_member_m/subgroup', $vars);

        //publish template
        $this->template->publish();
    }

        public function move_supplier($type='',$partner_id='') {
        if($type == ''){
            $type="coach";
        }
        $this->template->title = 'List Affiliate Member';

        if(!$partner_id){
            $this->messages->add('Invalid ID Affiliate', 'error');
            redirect('superadmin_m/manage_partner');
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
            'partner_id' => $partner_id,
            'partner' => $partner,
            'type' => $type
        );
        // echo "<pre>";
        // print_r($vars);
        // exit();

        // $this->template->content->view('default/contents/manage_partner_member_m/index', $vars);
        $this->template->content->view('default/contents/manage_partner_member_m/subgroup', $vars);

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

    public function subgroup_action($type = '',$partner_id = '') {

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
                    $this->messages->add('Please Move your Coach to another affiliate', 'danger');
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

            redirect('admin_m/manage_partner_m/list_supplier/'.$type.'/'.$partner_id);
    }

    public function delete_partner_member($user_id = ''){
        $partner = $this->identity_model->get_identity('profile')->where('user_id', $user_id)->get();
        if($this->identity_model->get_partner_identity($user_id, '', '', '')){
            if($this->user_model->delete($user_id)){
                $this->messages->add('Delete Affiliate Member Successful', 'success');
                redirect('admin_m/manage_partner_m/list_partner_member/'.$partner->partner_id);
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

    public function upload_profile_picture($partner_id=''){
        // $profile_picture = $this->do_upload('profile_picture');

        // if (!$profile_picture) {
        //     $this->messages->add('Failed to upload image', 'error');
        //     return $this->detail($partner_id);
        // }
        // $data_profile_picture['profile_picture'] = $this->upload_path . $profile_picture['file_name'];
        // if (!$this->partner_model->update($partner_id, $data_profile_picture, TRUE)) {
        //     $this->messages->add(validation_errors(), 'warning');
        //     return $this->detail($partner_id);
        // }
            $profile_picture = $this->do_upload('profile_picture');
            // $resize = $this->do_resize($profile_picture);
            $propicname = $this->db->select('profile_picture')->from('partners')->where('id',$partner_id)->get()->result();
            $propicname = $propicname[0]->profile_picture;
            $propicname = explode('/', $propicname);

            if (!$profile_picture) {
                $this->messages->add('Failed to upload image', 'warning');
                // return $this->detail('profile');
                redirect('admin_m/manage_partner_m/detail/'.$partner_id);
            }
            // $data_profile_picture['profile_picture'] = $this->upload_path . $profile_picture['file_name'];
            $data_profile_picture = array('profile_picture' => $this->upload_path . $profile_picture['file_name']);

            // delete image sebelumnya
            $delete_image = $this->upload_path."".$propicname[3];;
            unlink($delete_image);

            if (!$this->db->where('id',$partner_id)->update('partners',$data_profile_picture)) {
                $this->messages->add(validation_errors(), 'warning');
                redirect('admin_m/manage_partner_m/detail/'.$partner_id);
            }


            $this->messages->add('Update Successful', 'success');
            redirect('admin_m/manage_partner_m/detail/'.$partner_id);
    }

    private function do_upload($name) {

        $config['upload_path'] = $this->upload_path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']     = '1024';
        $new_name = time().'-'.$_FILES["profile_picture"]['name'];
        $config['file_name'] = $new_name;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($name)) {
            $this->messages->add($this->upload->display_errors(), 'warning');
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

            // echo "<pre>";
            // echo "hai";
            // print_r($config);
            // exit();

            //this is the magic line that enables you generate multiple thumbnails
            //you have to call the initialize() function each time you call the resize()
            //otherwise it will not work and only generate one thumbnail

            $this->image_lib->initialize($config);
            return $this->image_lib->resize();

    }

    // private function do_upload($name) {
    //     $config['upload_path'] = $this->upload_path;
    //     $config['allowed_types'] = 'gif|jpg|png|jpeg';

    //     $this->load->library('upload', $config);

    //     if (!$this->upload->do_upload($name)) {
    //         echo $this->upload->display_errors();
    //         return FALSE;
    //     } else {
    //         return $this->upload->data();
    //     }
    // }

    public function detail($partner_id='', $region_id=''){
        $this->template->title = 'Affiliate Detail';
        if(!$partner_id){
            $this->messages->add('Invalid Action', 'warning');
            redirect('admin_m/manage_partner');
        }
        $partner = $this->partner_model->select('*')->where('id', $partner_id)->get();

        if(!$partner){
            $this->messages->add('Affiliate is not valid', 'warning');
            redirect('admin_m/manage_partner');
        }

        // get status set setting
        $status_set_setting = $this->db->select('status_set_setting')->from('specific_settings')->where('user_id',$this->auth->userid())->get()->result();
        // echo $status_set_setting[0]->status_set_setting;
        // echo "<pre>";
        // print_r($status_set_setting);
        // exit();

        $vars = array(
            'region_id' => '',
            'status_set_setting' => @$status_set_setting[0]->status_set_setting,
            'partner_id' => $partner_id,
            'partner' => @$partner,
            'students' => @$this->user_profile_model->get_students($partner_id, 1, 'first_page'),
            'coaches' => @$this->user_profile_model->get_coaches($partner_id, 2, 'first_page'),
            'student_count' => @$this->user_profile_model->get_students($partner_id),
            'coach_count' => @$this->user_profile_model->get_coaches($partner_id),
            'option_country' => $this->common_function->country_code,
        );

        $this->template->content->view('default/contents/manage_partner_m/detail', $vars);
        $this->template->publish();
    }

    public function coach_detail($partner_id='', $coach_id=''){
        $this->template->title = 'Coach Detail';
        if(!$coach_id || !$partner_id){
            $this->messages->add('Invalid Action', 'warning');
            redirect('admin_m/manage_partner');
        }
        $this->session->set_userdata('coach_id', $coach_id);
        $data = $this->identity_model->get_coach_identity($coach_id, '', '', $partner_id);
        $partner = $this->partner_model->select('*')->where('id', $partner_id)->get();
        if(!$data){
            $this->messages->add('Invalid ID', 'warning');
            redirect('admin_m/manage_partner_m/'.$partner_id);
        }

        $get_user_timezone = $this->db->select('minutes_val')->from('user_timezones')->where('user_id',$coach_id)->get()->result();

        if(!$get_user_timezone){
            $minute_user_timezone = 0;
        } else {
            $minute_user_timezone = $get_user_timezone[0]->minutes_val;
        }

        $get_utz =  $this->db->select('timezone')->from('timezones')->where('minutes',$minute_user_timezone)->get()->result();
        $user_tz = $get_utz[0]->timezone;

        $vars = array(
            'coach_id' => $coach_id,
            'data' => $data,
            'partner' => @$partner,
            'partner_id' => $partner_id,
            'user_tz' => $user_tz
        );
        $this->template->content->view('default/contents/admin_m/coach/detail', $vars);
        $this->template->publish();
    }

    // public function student_detail($partner_id='', $student_id=''){
    //     $this->template->title = 'Student Detail';
    //     if(!$student_id || !$partner_id){
    //         $this->messages->add('Invalid Action', 'warning');
    //         redirect('admin_m/manage_partner');
    //     }
    //     $this->session->set_userdata('student_id', $student_id);
    //     $data = $this->identity_model->get_student_identity($student_id, '', '');

    //     if(!$data){
    //         $this->messages->add('Invalid ID', 'warning');
    //         redirect('admin_m/manage_partner_m/member_of_student/'.$partner_id);
    //     }
    //     $vars = array(
    //         'partner_id' => $partner_id,
    //         'student' => $data
    //     );


    //     $this->template->content->view('default/contents/admin_m/student/detail', $vars);
    //     $this->template->publish();
    // }

    public function student_detail($partner_id='', $student_id=''){
        $this->template->title = 'Student Detail';
        if(!$student_id || !$partner_id){
            $this->messages->add('Invalid Action', 'warning');
            redirect('admin_m/manage_partner');
        }

        $partner = $this->partner_model->select('*')->where('id', $partner_id)->get();

        $this->session->set_userdata('student_id', $student_id);
        $data = $this->identity_model->get_student_identity($student_id, '', '');

        if(!$data){
            $this->messages->add('Invalid ID', 'warning');
            redirect('admin_m/manage_partner/member_of_student/'.$partner_id);
        }
        $vars = array(
            'partner_id' => $partner_id,
            'student' => $data,
            'partner' => $partner,
            'data' => $data
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();
        $this->template->content->view('default/contents/admin_m/student/detail', $vars);
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

    // public function member_of_student($status='active',$subgroup_id ='',$partner_id='', $page=''){
    //     $this->template->title = 'Member of Student';

    //     if(!$partner_id){
    //         $this->messages->add('Invalid ID', 'warning');
    //         redirect('admin_m/manage_partner');
    //     }

    //     $new_status = '';
    //     if($status == 'deactive'){
    //         $new_status = 'disable';
    //     } else {
    //         $new_status = $status;
    //     }


    //     $partner = $this->partner_model->select('*')->where('id',$partner_id)->get();

    //     $offset = 0;
    //     $per_page = 6;
    //     $uri_segment = 6;
    //     $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/manage_partner_m/member_of_student/'.$status.'/'.$subgroup_id.'/'.$partner_id), count($this->user_profile_model->get_students($partner_id,$subgroup_id,$new_status)), $per_page, $uri_segment);
    //     $vars = array(
    //         'subgroup_id' => $subgroup_id,
    //         'partner' => $partner,
    //         'partner_id' => $partner_id,
    //         'title' => 'Student Member',
    //         'students' => $this->user_profile_model->get_students($partner_id, $subgroup_id, $new_status, $per_page, $offset),
    //         'pagination' => @$pagination,
    //         'status' => $status
    //     );

    //     if($status == 'active'){
    //         $this->template->content->view('default/contents/admin_m/student/index', $vars);
    //     } elseif($status == 'deactive'){
    //         $this->template->content->view('default/contents/admin_m/student/index_deactive', $vars);
    //     }
    //     $this->template->publish();
    // }

    public function member_of_student($status='active',$subgroup_id ='', $partner_id='', $page=''){
        $this->template->title = 'Member of Student';

        if(!$partner_id){
            $this->messages->add('Invalid ID', 'warning');
            redirect('superadmin_m/manage_partner');
        }

        $partner = $this->partner_model->select('*')->where('id',$partner_id)->get();

        $status_set_setting = $this->db->select('status_set_setting')->from('specific_settings')->where('user_id',$this->auth->userid())->get()->result();

        $subgroup = $this->subgroup_model->select('*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id','left')->where('subgroup.partner_id',$partner_id)->where('subgroup.type','student')->group_by('subgroup.id')->get_all();

        $offset = 0;
        $per_page = 6;
        $uri_segment = 7;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/manage_partner_m/member_of_student/active/'.$subgroup_id.'/'.$partner_id), count($this->user_profile_model->get_students($partner_id,$subgroup_id,$status)), $per_page, $uri_segment);

        $number_page = 0;
        if($page == ''){
            $number_page = (1 * $per_page)-$per_page+1;

        } else {
            $number_page = ($page * $per_page)-$per_page+1;
        }

        $vars = array(
            'subgroup' => $subgroup,
            'subgroup_id' => $subgroup_id,
            'partner' => $partner,
            'partner_id' => $partner_id,
            'title' => 'Student Member',
            'students' => $this->user_profile_model->get_students($partner_id, $subgroup_id, $status, $per_page, $offset),
            'pagination' => @$pagination,
            'status' => $status,
            'type' => 'student',
            'status_set_setting' => @$status_set_setting[0]->status_set_setting,
            'back' => site_url('admin_m/manage_partner_m/detail/'.$partner_id),
            'number_page' => $number_page

        );

        // echo "<pre>";
        // print_r($subgroup);
        // exit();

        $this->template->content->view('default/contents/admin_m/student/index', $vars);
        $this->template->publish();
    }

    // public function member_of_coach($status='',$subgroup_id='',$partner_id='', $page=''){
    //     $this->template->title = 'Member of Student';

    //     if(!$partner_id){
    //         $this->messages->add('Invalid ID', 'warning');
    //         redirect('admin_m/manage_partner');
    //     }

    //     $new_status = '';
    //     if($status == 'deactive'){
    //         $new_status = 'disable';
    //     } else {
    //         $new_status = $status;
    //     }


    //     $offset = 0;
    //     $per_page = 6;
    //     $uri_segment = 5;

    //     $partner = $this->partner_model->select('*')->where('id',$partner_id)->get();

    //     $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/manage_partner_m/member_of_coach/'.$status.'/'.$subgroup_id.'/'.$partner_id), count($this->user_profile_model->get_coaches($partner_id,$subgroup_id,$new_status)), $per_page, $uri_segment);
    //     $vars = array(
    //         'subgroup_id' => $subgroup_id,
    //         'partner' => $partner,
    //         'partner_id' => $partner_id,
    //         'title' => 'Coach Member',
    //         'coaches' => $this->user_profile_model->get_coaches($partner_id,$subgroup_id,$new_status, $per_page, $offset),
    //         'pagination' => @$pagination,
    //         'status' => $status
    //     );
    //     // echo $status;
    //     // echo "<pre>";
    //     // print_r($vars);
    //     // exit();
    //     if($status == 'active'){
    //         $this->template->content->view('default/contents/admin_m/coach/index', $vars);
    //     } elseif($status == 'deactive'){
    //         $this->template->content->view('default/contents/admin_m/coach/index_deactive', $vars);
    //     }
    //     $this->template->publish();
    // }
        // Delete student
    function delete_student($subgroup_id = '',$partner_id = ''){

        if(!empty($_POST['check_list'])) {
            $check_list = $_POST['check_list'];
            $type_submit = $_POST['_submit'];

                $this->db->trans_begin();
                    $this->db->where('role_id', 1);
                    $this->db->where_in('id',$check_list);
                    $this->db->delete('users');

                    $this->db->flush_cache();

                    $this->db->where_in('user_id',$check_list);
                    $this->db->delete('user_profiles');

                    $this->db->flush_cache();

                    $this->db->where_in('user_id',$check_list);
                    $this->db->delete('user_tokens');

                    $this->db->flush_cache();

                    $this->db->where_in('id_student',$check_list);
                    $this->db->delete('student_supplier_to_student');
                $this->db->trans_commit();
                $this->messages->add('Delete Successful', 'success');
        } else {
            $this->messages->add('Please choose student', 'error');
        }
            redirect('admin_m/manage_partner_m/list_partner/student/'.$partner_id );
    }

    function delete_coach($subgroup_id = '',$partner_id = ''){

        if(!empty($_POST['check_list'])) {
            $check_list = $_POST['check_list'];
            $type_submit = $_POST['_submit'];

                $this->db->trans_begin();
                    $this->db->where('role_id', 2);
                    $this->db->where_in('id',$check_list);
                    $this->db->delete('users');

                    $this->db->flush_cache();

                    $this->db->where_in('user_id',$check_list);
                    $this->db->delete('user_profiles');

                    $this->db->flush_cache();

                    $this->db->where_in('user_id',$check_list);
                    $this->db->delete('user_tokens');

                    $this->db->flush_cache();

                    $this->db->where_in('id_student',$check_list);
                    $this->db->delete('student_supplier_to_student');
                $this->db->trans_commit();
                $this->messages->add('Delete Successful', 'success');
        } else {
            $this->messages->add('Please choose coach', 'error');
        }
            redirect('admin_m/manage_partner_m/member_of_coach/active/'.$subgroup_id.'/'.$partner_id );
    }

    public function member_of_coach($status='active',$subgroup_id='', $partner_id='', $page=''){
        $this->template->title = 'Member of Coach';

        if(!$partner_id){
            $this->messages->add('Invalid ID', 'warning');
            redirect('superadmin_m/manage_partner');
        }

        $offset = 0;
        $per_page = 6;
        $uri_segment = 7;

        $partner = $this->partner_model->select('*')->where('id',$partner_id)->get();

        $subgroup = $this->subgroup_model->select('*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id','left')->where('subgroup.partner_id',$partner_id)->where('subgroup.type','coach')->group_by('subgroup.id')->get_all();

        $number_page = 0;
        if($page == ''){
            $number_page = (1 * $per_page)-$per_page+1;

        } else {
            $number_page = ($page * $per_page)-$per_page+1;
        }


        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/manage_partner_m/member_of_coach/'.$status.'/'.$subgroup_id.'/'.$partner_id), count($this->user_profile_model->get_coaches($partner_id,$subgroup_id,$status)), $per_page, $uri_segment);
        $vars = array(
            'subgroup_id' => $subgroup_id,
            'subgroup' => $subgroup,
            'partner' => $partner,
            'partner_id' => $partner_id,
            'title' => 'Coach Member',
            'coaches' => $this->user_profile_model->get_coaches($partner_id,$subgroup_id,$status, $per_page, $offset),
            'pagination' => @$pagination,
            'status' => $status,
            'type' => 'coach',
            'back' => site_url('admin_m/manage_partner_m/detail/'.$partner_id),
            'number_page' => $number_page
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();

        $this->template->content->view('default/contents/admin_m/coach/index', $vars);
        $this->template->publish();
    }

    public function coach_action($type='',$subgroup_id='',$partner_id=''){

        if(!empty($_POST['check_list'])) {
            $check_list = $_POST['check_list'];
            $type_submit = $_POST['__submit'];
            if($type_submit == "delete_coach"){
                $this->db->trans_begin();
                    $this->db->where('role_id',2);
                    $this->db->where_in('id',$check_list);
                    $this->db->delete('users');

                    $this->db->flush_cache();

                    $this->db->where_in('user_id',$check_list);
                    $this->db->delete('user_profiles');
                $this->db->trans_commit();
                $this->messages->add('Delete Successful', 'success');

            } else if($type_submit == "deactive_coach"){
                $this->db->trans_begin();

                    $this->db->where('role_id',2);
                    $this->db->where_in('id',$check_list);
                    $this->db->update('users',array('status' => "disable"));
                $this->db->trans_commit();

                $this->messages->add('Deactive Successful', 'success');


            } else if($type_submit == "active_coach"){
                $this->db->trans_begin();

                    $this->db->where('role_id',2);
                    $this->db->where_in('id',$check_list);
                    $this->db->update('users',array('status' => "active"));
                $this->db->trans_commit();

                $this->messages->add('Active Successful', 'success');
            }

            redirect('admin_m/manage_partner_m/member_of_coach/'.$type.'/'.$subgroup_id.'/'.$partner_id);

        }
    }

    public function student_action($type='',$subgroup_id='',$partner_id=''){

        if(!empty($_POST['check_list'])) {
            $check_list = $_POST['check_list'];
            $type_submit = $_POST['__submit'];
            if($type_submit == "delete_student"){
                $this->db->trans_begin();
                    $this->db->where('role_id',1);
                    $this->db->where_in('id',$check_list);
                    $this->db->delete('users');

                    $this->db->flush_cache();

                    $this->db->where_in('user_id',$check_list);
                    $this->db->delete('user_profiles');
                $this->db->trans_commit();
                $this->messages->add('Delete Successful', 'success');

            } else if($type_submit == "deactive_student"){
                $this->db->trans_begin();

                    $this->db->where('role_id',1);
                    $this->db->where_in('id',$check_list);
                    $this->db->update('users',array('status' => "disable"));
                $this->db->trans_commit();

                $this->messages->add('Deactive Successful', 'success');


            } else if($type_submit == "active_student"){
                $this->db->trans_begin();

                    $this->db->where('role_id',1);
                    $this->db->where_in('id',$check_list);
                    $this->db->update('users',array('status' => "active"));
                $this->db->trans_commit();

                $this->messages->add('Active Successful', 'success');
            }

            redirect('admin_m/manage_partner_m/member_of_student/'.$type.'/'.$subgroup_id.'/'.$partner_id);

        }
    }


    public function token(){
        $this->template->title = 'Token Request';

        $region_id = $this->auth_manager->userid();

        // $get_region = $this->db->select('region_id')
        //                        ->from('user_profiles')
        //                        ->where('user_id',$this->auth_manager->userid())
        //                        ->get()->result();
        // $region_name = $get_region[0]->region_id;

        $vars = array(
            // 'data' => $this->token_request_model->get_token_request(null,5,$region_name),
            'data' => $this->token_request_model->new_get_token_request($region_id),

        );

        // echo "<pre>";
        // print_r($vars); exit;
        $this->template->content->view('default/contents/admin_m/request_token/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function history_token($page='')
    {

        $this->template->title = 'Token Request History';
        $region_id = $this->auth_manager->userid();

        // $get_region = $this->db->select('region_id')
        //                        ->from('user_profiles')
        //                        ->where('user_id',$this->auth_manager->userid())
        //                        ->get()->result();
        // $region_name = $get_region[0]->region_id;

        $id = $this->auth_manager->userid();
        // $utz = $this->db->select('user_timezone')
        //         ->from('user_profiles')
        //         ->where('user_id', $id)
        //         ->get()->result();
        // $idutz = $utz[0]->user_timezone;
        // $tz = $this->db->select('*')
        //         ->from('timezones')
        //         ->where('id', $idutz)
        //         ->get()->result();

        $get_minutes = @$this->identity_model->new_get_gmt($id)[0]->minutes;


        $minutes = '';
        if($minutes == 0){
            $minutes = 420;
        } else {
            $minutes = $get_minutes;
        }


        $vars = array(
            // 'data' => $this->history_request_model->select('token_requests.id, token_requests.dcrea, up.fullname as fullname, token_requests.dupd, up.region_id as region_id, u.email, up.fullname, token_requests.token_amount, token_requests.status')
            //                                       ->join('users u', 'u.id = token_requests.user_id')
            //                                       ->join('user_profiles up', 'up.user_id = u.id')
            //                                       ->where('u.role_id', 5)->where('u.status', 'active')
            //                                       ->where('token_requests.status !=','requested')
            //                                       ->where('token_requests.status !=','requested')
            //                                       ->where('up.region_id',$region_name)
            //                                       ->order_by('token_requests.id', 'desc')
            //                                       ->get_all(),
            'data' => $this->token_request_model->new_get_history_token_request($region_id),
            'minutes' => $minutes
        );


        $this->template->content->view('default/contents/admin_m/request_token/history_token', $vars);

        //publish template
        $this->template->publish();
    }

    public function approve_token($token_request_id = '') {
        date_default_timezone_set('Etc/GMT+0');
        if ($this->token_request_model->get_token_request($token_request_id,5)) {

            $token_request = $this->token_request_model->select('id, user_id, token_amount')->where('id', $token_request_id)->get();

            $token = $this->identity_model->get_identity('token')->select('id, user_id, token_amount')->where('user_id', $token_request->user_id)->get();

            $cur_token_ammount = '';
            if(count($token) == 0){
                $cur_token_ammount = 0;
            } else if(count($token) > 0){
                $cur_token_ammount = $token->token_amount;
            }


            $this->db->trans_begin();
            // cek sisa token region
            $get_token_region = $this->user_token_model->select('user_id,token_amount')->where('user_id', $this->auth_manager->userid())->get();

            if($get_token_region->token_amount < $token_request->token_amount){
                $this->messages->add('Not enough token', 'danger');
                redirect('admin_m/manage_partner_m/token');
            }


            // update student token
            $current_token = $token_request->token_amount + $cur_token_ammount;

            $update_data = array(
                'token_amount' => $current_token,
                'dupd' => time()
            );
            // $this->identity_model->get_identity('token')->update($token->id,$update_data);
            $update_token_region = $this->db->where('user_id',$token->user_id)->update('user_tokens',$update_data);



            $token_history = array(
                'user_id' => $token_request->user_id,
                'transaction_date' => time(),
                'token_amount' => $token_request->token_amount,
                'description' => 'Admin has approved you token request.',
                'token_status_id' => 3,
                'balance' => $current_token,
                'dcrea' => time(),
                'dupd' => time()
            );
            $this->token_histories_model->insert($token_history);

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

            $student_data = array(
                'token_amount'
            );
            $data = array(
                'status' => 'approved',
                'dupd' => time()
            );
            $this->token_request_model->update($token_request_id, $data);

            // update sisa token region
            $new_region_token = $get_token_region->token_amount - $token_request->token_amount;

            $update_token_region = $this->db->where('user_id',$get_token_region->user_id)->update('user_tokens',array('token_amount' => $new_region_token));

            // notification
            $partner_notification = array(
                'user_id' => $token_request->user_id,
                'description' => 'Your request for '.$token_request->token_amount.' tokens is approved.',
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
            $user_data = $this->user_model->select('users.email as email, user_profiles.fullname as fullname')->join('user_profiles','user_profiles.user_id = users.id')->where('users.id',$token_request->user_id)->get();

            $this->send_email->send_partner_approve_token($user_data->email,'approved',$user_data->fullname,$token_request->token_amount);


            $this->db->trans_commit();
            // $this->messaging_admin($token_request_id, 'approved');

            $this->messages->add('Approve Token Request Succeded', 'success');
            redirect('admin_m/manage_partner_m/token');
        }
        else{
            $this->messages->add('Token Might be Cancelled by Student Affiliate', 'error');
            redirect('admin_m/manage_partner_m/token');
        }
    }

    public function decline_token($token_request_id = '') {
        date_default_timezone_set('Etc/GMT+0');
        if ($this->token_request_model->get_token_request($token_request_id,5)) {
            $data = array(
                'status' => 'declined',
                'dupd' => time()
            );

            $this->token_request_model->update($token_request_id, $data);

            $token_request = $this->token_request_model->select('id, user_id, token_amount')->where('id', $token_request_id)->get();

             // notification
            $partner_notification = array(
                'user_id' => $token_request->user_id,
                'description' => 'Your request for '.$token_request->token_amount.' tokens is declined.',
                'status' => 2,
                'dcrea' => time(),
                'dupd' => time(),
            );

            $this->user_notification_model->insert($partner_notification);
            // ===============================
            // email
            $user_data = $this->user_model->select('users.email as email, user_profiles.fullname as fullname')->join('user_profiles','user_profiles.user_id = users.id')->where('users.id',$token_request->user_id)->get();

            $this->send_email->send_partner_approve_token($user_data->email,'declined',$user_data->fullname,$token_request->token_amount);


            // $this->messaging_admin($token_request_id, 'declined');

            $this->messages->add('Decline Token Request Succeded', 'success');
            redirect('admin_m/manage_partner_m/token');
        }
        else{

            $this->messages->add('Token Might be Cancelled by Student Affiliate', 'error');
            redirect('admin_m/manage_partner_m/token');
        }
    }

    public function setting($id='',$type=''){
        $setting = $this->region_model->get_partner_specific_setting($id);
        $region_setting = $this->specific_settings_model->get_specific_settings($this->auth_manager->userid(),'region');

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

        $back = site_url('admin_m/manage_partner_m/detail/'.$id);

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

        if(($type == "coach") || ($type == "")){
            $this->template->content->view('default/contents/superadmin_m/region/coach', $vars);
            //publish template
            // $this->template->publish();
        } else if($type == 'student'){
            $this->template->content->view('default/contents/superadmin_m/region/student', $vars);
            //publish template
            // $this->template->publish();
        }

        // $this->template->content->view('default/contents/superadmin_m/region/setting', $vars);
        $this->template->publish();
    }

    function update_setting($id){
        $region_setting = $this->specific_settings_model->get_specific_settings($this->auth_manager->userid(),'region');

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

       // =============================
           if($this->input->post('__submit') == 'region_student'){
            $rules = array(
                    array('field'=>'max_student_class', 'label' => 'max_student_class', 'rules'=>'trim|required|xss_clean'),
                    array('field'=>'max_student_supplier', 'label' => 'max_student_supplier', 'rules'=>'trim|required|xss_clean'),
                    array('field'=>'max_day_per_week', 'label' => 'max_day_per_week', 'rules'=>'trim|required|xss_clean'),
                    array('field'=>'max_session_per_day', 'label' => 'max_session_per_day', 'rules'=>'trim|required|xss_clean'),
                    array('field'=>'max_token', 'label' => 'max_token', 'rules'=>'trim|required|xss_clean'),
                    array('field'=>'max_token_for_student', 'label' => 'max_token_for_student', 'rules'=>'trim|required|xss_clean'),
                    // array('field'=>'max_session_per_x_day', 'label' => 'max_session_per_x_day', 'rules'=>'trim|required|xss_clean'),
                    // array('field'=>'x_day', 'label' => 'x_day', 'rules'=>'trim|required|xss_clean')
                );

            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                redirect('admin_m/manage_partner_m/setting/'.$id.'/student');
            }

            if($update_max_student_class > $max_student_class){
                $message_setting = 'Max Student Class '.$max_student_class;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/manage_partner_m/setting/'.$id.'/student');
            }

            if($update_max_student_supplier > $max_student_supplier){
                $message_setting = 'Max Student Affiliate '.$max_student_supplier;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/manage_partner_m/setting/'.$id.'/student');
            }

            if($update_max_day_per_week > $max_day_per_week){
                $message_setting = 'Max Day Per Week '.$max_day_per_week;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/manage_partner_m/setting/'.$id.'/student');
            }

            if($update_max_session_per_day > $max_session_per_day){
                $message_setting = 'Max Session Per Day '.$max_session_per_day;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/manage_partner_m/setting/'.$id.'/student');
            }

            if($update_max_token > $max_token){
                $message_setting = 'Max Token '.$max_token;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/manage_partner_m/setting/'.$id.'/student');
            }

            if($update_max_token_for_student > $max_token_for_student){
                $message_setting = 'Max Token For Student '.$max_token_for_student;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/manage_partner_m/setting/'.$id.'/student');
            }

            // if($update_max_session_per_x_day > $max_session_per_x_day){
            //     $message_setting = 'Max Session Per X Day '.$max_session_per_x_day;
            //     $this->messages->add($message_setting, 'warning');
            //     redirect('admin_m/manage_partner_m/setting/'.$id.'/student');
            // }

            // if($update_x_day > $x_day){
            //     $message_setting = 'Max X Day '.$x_day;
            //     $this->messages->add($message_setting, 'warning');
            //     redirect('admin_m/manage_partner_m/setting/'.$id.'/student');
            // }

            // if($update_set_max_session > $set_max_session){
            //     $message_setting = 'Max Session for Student is Set to '.$set_max_session;
            //     $this->messages->add($message_setting, 'warning');
            //     redirect('admin_m/manage_partner_m/setting/'.$id.'/student');
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

           // update token di table user token
           $update_token = $this->db->where('user_id',$id)->update('user_tokens',array('token_amount' => $this->input->post('max_token')));
           $type="student";
       } else if($this->input->post('__submit') == 'region_coach'){

            if($update_elite_coach_cost > $elite_coach_cost){
                $message_setting = 'Max Elite Coach Cost '.$elite_coach_cost;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/manage_partner_m/setting/'.$id.'/coach');
            }

            if($update_standard_coach_cost > $standard_coach_cost){
                $message_setting = 'Max Coach Cost '.$standard_coach_cost;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/manage_partner_m/setting/'.$id.'/coach');
            }

            if(($this->input->post('standard_coach_cost') < 1) || ($this->input->post('elite_coach_cost') < 1)){
                $this->messages->add('Standard Coach cost or Elite Coach cost minimum 1', 'warning');
                redirect('admin_m/manage_partner_m/setting/'.$id.'/coach');

            }

            $setting = array('session_duration' => $this->input->post('session_duration'),
                            'standard_coach_cost' => $this->input->post('standard_coach_cost'),
                            'elite_coach_cost' => $this->input->post('elite_coach_cost'));
            $type="coach";
       }

       $this->db->where('partner_id',$id);
       $this->db->update('specific_settings',$setting);

       $this->messages->add('Update Setting Successful', 'success');

       redirect('admin_m/manage_partner_m/setting/'.$id.'/'.$type);
    }

    function add_token($student_supplier_id='',$partner_id=''){
        $this->template->title = 'Add Tokens';

        $get_token = $this->db->select('token_amount')
                          ->from('user_tokens')
                          ->where('user_id',$this->auth_manager->userid())
                          ->get()->result();

        $token = $get_token[0]->token_amount;



        $link = base_url().'index.php/admin_m/manage_partner_m/action_add_token/'.$student_supplier_id.'/'.$partner_id;
        $cancel = base_url().'index.php/admin_m/manage_partner_m/partner/student/'.$partner_id;
        $vars = array(
                'link' => $link,
                'cancel' => $cancel,
                'token' => $token
                );

        $this->template->content->view('default/contents/managing/token/index', $vars);
        $this->template->publish();
    }

    function action_add_token($student_supplier_id = '',$partner_id = ''){
        date_default_timezone_set('Etc/GMT+0');

        $id = $this->auth_manager->userid();

        if (!$this->input->post('token') || $this->input->post('token') <=0) {
            $this->messages->add('Token Request Value Must be More than Zero', 'warning');
            redirect('admin_m/manage_partner_m/add_token/'.$student_supplier_id.'/'.$partner_id);
        }

        $request_token = $this->input->post('token');

        // check apakah status setting region allow atau disallow
        $region_id = $this->auth_manager->region_id($partner_id);

        $get_status_setting_region = $this->specific_settings_model->get_specific_settings($region_id,'region');

        $max_student_supplier = '';
        if($get_status_setting_region[0]->status_set_setting == 0){
            $get_setting = $this->global_settings_model->get_partner_settings();
            $max_student_supplier = $get_setting[0]->max_token;
        } else {
            $get_setting = $this->specific_settings_model->get_partner_settings($partner_id);
            $max_student_supplier = $get_setting[0]->max_token;
        }

        // =======================

        // check token student_partner
        $get_user_token = $this->user_token_model->get_token($id,'user');
        $user_token = $get_user_token[0]->token_amount;

        // check jika token user tidak mencukupi
        if($user_token < $request_token){
            $this->messages->add('Your token not enough ', 'warning');
            redirect('admin_m/manage_partner_m/add_token/'.$student_supplier_id.'/'.$partner_id);
        }

        // check token student
        $get_token = $this->user_token_model->get_token($student_supplier_id,'user');
        $student_token = '';
        if($get_token){
            $student_token = $get_token[0]->token_amount;
        } else {
            $student_token = 0;
        }

        // =================

        //get data for email
        $get_name = $this->user_profile_model->select('user_id, fullname')->where('user_id', $this->auth_manager->userid())->get_all();
        $get_name2 = $this->user_profile_model->select('user_id, fullname')->where('user_id', $student_supplier_id)->get_all();
        $get_email = $this->user_model->select('id, email, role_id')->where('id', $student_supplier_id)->get_all();


        // check jika total token melebihi max token
        $total_request_token = $request_token+$student_token;


        if($total_request_token > $max_student_supplier){
            $this->messages->add('Token Request exceeds the maximum, maximum token for student affiliate = '.$max_student_supplier, 'warning');
            redirect('admin_m/manage_partner_m/add_token/'.$student_supplier_id.'/'.$partner_id);
        }


        // jika gak ada masalah

        // pengurangan token user
        $this->db->trans_begin();

        $new_token_user = $user_token-$request_token;

        $data_user = array(
                'token_amount' => $new_token_user,
                'dcrea' => time(),
                'dupd' => time()
            );

        $update_user_token = $this->db->where('user_id', $id)
                                      ->update('user_tokens', $data_user);

        // // ===================

        // update token student


        if($get_token){
            $data_student = array('token_amount' => $total_request_token,
              'dcrea' => time(),
              'dupd' => time()
            );
            $update_student_token = $this->db->where('user_id', $student_supplier_id)
                                          ->update('user_tokens', $data_student);
        } else {
            $data_student = array(
              'user_id' => $student_supplier_id,
              'token_amount' => $total_request_token,
              'dcrea' => time(),
              'dupd' => time()
            );
            $this->db->insert('user_tokens',$data_student);
        }

        $this->db->trans_commit();

        // send notification and email
        $partner_notification = array(
            'user_id' => $student_supplier_id,
            'description' => $request_token.' tokens has been added by your Regional Admin.',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );

        // insert into table request for information
        $insert_table_req = array('approve_id' => $this->auth_manager->userid(),
                                  'user_id' => $student_supplier_id,
                                  'token_amount' => $request_token,
                                  'status' => 'added',
                                  'dcrea' => time(),
                                  'dupd' => time());
        $this->db->insert('token_requests',$insert_table_req);

        // insert into tabel token histories

        $data_histories_token = array('balance' => $total_request_token,
                                      'token_amount' => $request_token,
                                      'user_id' => $student_supplier_id,
                                      'transaction_date' => time(),
                                      'description' => 'Your token has been added by your Region',
                                      'token_status_id' => 33);

        $this->db->insert('token_histories', $data_histories_token);


         $this->db->trans_commit();

        // messaging inserting data notification

        $this->user_notification_model->insert($partner_notification);
        $this->send_email->add_token_partner($get_email[0]->email, $get_name[0]->fullname, $request_token, $get_email[0]->role_id, $get_name2[0]->fullname);




        $this->messages->add('Your token success added ', 'success');
        redirect('admin_m/manage_partner_m/partner/student/'.$partner_id);

    }



    function refund_token($student_partner_id='',$partner_id=''){

        $this->template->title = 'Refund Token';

        $data_token_partner = $this->db->select('token_amount')->from('user_tokens')->where('user_id', $student_partner_id)->get()->result();

        $data_token_refund = $this->db->select('token_amount, balance')
                                  ->from('token_histories')
                                  ->where('user_id', $student_partner_id)
                                  ->where('token_status_id',33)
                                  ->order_by('id','desc')
                                  ->get()->result();


        $token_partner = @$data_token_partner[0]->token_amount;

        $token_amount = @$data_token_refund[0]->token_amount;
        $balance = @$data_token_refund[0]->balance;

        $status = '';
        if($token_partner == $balance){
            $status = 1;
        }
        if($token_partner != $balance) {
            $status = 0;
        }
        if(!$data_token_refund){
            $status = 3;
        }

        $link = base_url().'index.php/admin_m/manage_partner_m/action_refund_token'.'/'.$student_partner_id."/".$partner_id;
        $cancel = base_url().'index.php/admin_m/manage_partner_m/partner/student/'.$partner_id;

        $vars = array('region_id' => $student_partner_id,
                     'status' => $status,
                     'token_amount' => $token_amount,
                     'link' => $link,
                     'cancel' => $cancel);

        $this->template->content->view('default/contents/managing/token/refund', $vars);

        //publish template
        $this->template->publish();
    }

    function action_refund_token($student_partner_id, $partner_id){
        $old_data_token_student = $this->db->select('token_amount')->from('user_tokens')->where('user_id', $student_partner_id)->get()->result();

        $data_token_refund = $this->db->select('token_amount, balance')
                                  ->from('token_histories')
                                  ->where('user_id', $student_partner_id)
                                  ->where('token_status_id',33)
                                  ->order_by('id','desc')
                                  ->get()->result();

        $token_student_partner = @$old_data_token_student[0]->token_amount;

        $token_amount = @$data_token_refund[0]->token_amount;
        $balance = @$data_token_refund[0]->balance;

        // check apakah tokennya sama

        if($token_student_partner != $balance) {
            $this->messages->add('Your token failed refund ', 'warning');
            redirect('admin_m/manage_partner_m/partner/student/'.$partner_id);
        }



        // =====

        // update token student partner
        $token_update = $balance-$token_amount;

        $data_users = array('token_amount' => $token_update,
                                  'dcrea' => time(),
                                  'dupd' => time()
                                  );

        $update_users_token = $this->db->where('user_id', $student_partner_id)
                                          ->update('user_tokens', $data_users);

        // update token region
        $old_data_token_region = $this->db->select('token_amount')->from('user_tokens')->where('user_id', $this->auth_manager->userid())->get()->result();
        $token_update_region = $old_data_token_region[0]->token_amount+$token_amount;

        $data_region = array('token_amount' => $token_update_region,
                                  'dcrea' => time(),
                                  'dupd' => time()
                                  );

        $update_users_token = $this->db->where('user_id', $this->auth_manager->userid())
                                          ->update('user_tokens', $data_region);
        // insert into tabel token histories
        $data_token_student_partner = $this->db->select('token_amount')->from('user_tokens')->where('user_id', $student_partner_id)->get()->result();
        $data_histories_token = array('balance' => $data_token_student_partner[0]->token_amount,
                                      'token_amount' => $token_amount,
                                      'user_id' => $student_partner_id,
                                      'transaction_date' => time(),
                                      'description' => 'Your tokens were refunded by Region',
                                      'token_status_id' => 35);

        $this->db->insert('token_histories', $data_histories_token);

        // insert into table token_requests
        $insert_table_req = array('approve_id' => $this->auth_manager->userid(),
                                  'user_id' => $student_partner_id,
                                  'token_amount' => $token_amount,
                                  'status' => 'refund',
                                  'dcrea' => time(),
                                  'dupd' => time());
        $this->db->insert('token_requests',$insert_table_req);

        // send notification and email
        $partner_notification = array(
            'user_id' => $student_partner_id,
            'description' => 'Your token has been refund. '.$token_amount.' by Region',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );




         $this->db->trans_commit();

        // messaging inserting data notification

        $this->user_notification_model->insert($partner_notification);

        $this->messages->add('Your token success refund ', 'success');
        redirect('admin_m/manage_partner_m/partner/student/'.$partner_id);

    }


}
