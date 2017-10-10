<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class region extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('user_profile_model');
        $this->load->model('creator_member_model');
        $this->load->model('identity_model');
        $this->load->model('partner_model');
        $this->load->model('region_model');
        $this->load->model('user_token_model');
        $this->load->model('timezone_model');
        // $this->load->model('region_settings');
        // for messaging action and timing
        // $this->load->library('queue');
        // $this->load->library('email_structure');
        $this->load->library('send_email');

        date_default_timezone_set('Etc/GMT+0');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'RAD') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
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

    function isValidNameRegion($region_name = '') {
        if ($this->user_profile_model->like('region_id', $region_name)->get_all()) {
            return false;
        } else {
            return true;
        }
    }

    // Index
    public function index($status='',$page='') {

            $offset = 0;
            $per_page = 6;
            $uri_segment = 5;

            $region_setting = $this->region_model->get_global_setting('region');

            $this->template->title = 'Manage Admin';

            $search_region = $this->input->post('search_region');


        if(($status == '') || ($status == 'active')){
            $status = 'active'; 

            if($search_region != ''){
                $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('superadmin/region/index/active/'), count($this->identity_model->get_region_admin_identity(NULL, NULL, NULL, NULL, NULL,$status, $search_region)), $per_page, $uri_segment);
                $data = $this->identity_model->get_region_admin_identity(NULL, NULL, NULL, NULL, NULL, $status, $search_region,$per_page,$offset);
            } else {
                $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('superadmin/region/index/active/'), count($this->identity_model->get_region_admin_identity(NULL, NULL, NULL, NULL, NULL, $status)), $per_page, $uri_segment);
                $data = $this->identity_model->get_region_admin_identity(NULL, NULL, NULL, NULL, NULL,$status, NULL, $per_page,$offset);
            }
        } else if($status == 'deactivate'){
            $status = 'disable';

             if($search_region != ''){
                $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('superadmin/region/index/deactivate/'), count($this->identity_model->get_region_admin_identity(NULL, NULL, NULL, NULL, NULL, $status, $search_region)), $per_page, $uri_segment);
                $data = $this->identity_model->get_region_admin_identity(NULL, NULL, NULL, NULL, NULL, $status, $search_region,$per_page,$offset);
            } else {
                $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('superadmin/region/index/deactivate/'), count($this->identity_model->get_region_admin_identity(NULL, NULL, NULL, NULL, NULL, $status)), $per_page, $uri_segment);
                $data = $this->identity_model->get_region_admin_identity(NULL, NULL, NULL, NULL, NULL,$status, NULL, $per_page,$offset);
            }
        }

        $vars = array(
            'data' => $data,
            'pagination' => @$pagination
        );

        // echo "<pre>";
        // print_r($vars);


        if($status == 'disable'){
            $status = 'deactivate';
            $this->template->content->view('default/contents/superadmin/region/list_region_'.$status, $vars);            
        } else {
            $status = 'active';
            $this->template->content->view('default/contents/superadmin/region/list_region_'.$status, $vars);            

        }



        $this->template->publish();
    }

    public function add_region() {
        $this->template->title = 'Add Region';

        $data_coach_supplier = $this->partner_model->get_coach_supplier();
        $region = $this->identity_model->get_region();

        $timezones = $this->timezone_model->where_not_in('minutes',array('-210','330','570',))->dropdown('id', 'timezone');

        $vars = array(
             'action' => 'create',
            'region' => $region,
             'timezones' => $timezones,
            'data_coach_supplier' => $data_coach_supplier,
            'form_action' => 'create_admin_region'
        );

        $this->template->content->view('default/contents/superadmin/region/add_region', $vars);
        $this->template->publish();
    }

    function create(){
        date_default_timezone_set('Etc/GMT+0');
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('superadmin/region/add_region');
        }

        $rules = array(
                array('field'=>'fullname', 'label' => 'Fullname', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'email', 'label' => 'Email', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'region', 'label' => 'Region', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'token_amount', 'label' => 'Token amount', 'rules'=>'trim|integer|required|xss_clean')
            );
        

            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                redirect('superadmin/region/add_region');
            }
           
        
        $region = array_filter(explode(',' , $this->input->post('region')));
        // inserting user data
        $admin = array(
            'profile_picture' => 'uploads/images/default_logo.jpg',
            'fullname' => $this->input->post('fullname'),
            'email' => $this->input->post('email'),
            'gender' => '',
            'state' => '',
            'date_of_birth' => '',
            'phone' => '',
            'dcrea' => time(),
            'dupd' => time()
        );

         // checking if the region name is valid/ not been used yet
        if (!$this->isValidNameRegion($this->input->post('region'))) {
            $this->messages->add('Region Name has been used', 'danger');
            $this->add_region($this->input->post('partner_id'));
            return;
        }


        // checking if the email is valid/ not been used yet
        if (!$this->isValidEmail($this->input->post('email'))) {
            $this->messages->add('Email has been used', 'danger');
            $this->add_region($this->input->post('partner_id'));
            return;
        }

        if(!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)){
            $this->messages->add('Invalid Email', 'danger');
            $this->add_region($this->input->post('partner_id'));
            return;
        }

         // generating password
        $password = $this->generateRandomString();
       
        $this->db->trans_begin();
        // Inserting user data
        $user = array(
            'email' => $this->input->post('email'),
            'password' => $this->phpass->hash($password),
            'role_id' => 4,
            'status' => 'active',
            'dcrea' => time(),
            'dupd' => time()
        );


        // Inserting and checking to users table then storing insert_id into $user_id
        $user_id = $this->user_model->insert($user);
        if (!$user_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->add_admin();
            return;
        }

        // Inserting user profile data
        $user_id_to_partner_id = $this->identity_model->get_identity('profile')->dropdown('user_id', 'partner_id');

        $profile = array(
            'profile_picture' => 'uploads/images/profile.jpg',
            'user_id' => $user_id,
            'fullname' => $this->input->post('fullname'),
            'nickname' => $this->input->post('fullname'),
            'gender' => '',
            'date_of_birth' => '',
            'phone' => '',
            'partner_id' => NULL,
            'region_id' => $this->input->post('region'),
            'user_timezone' => 27,
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

          // get global setting for region

        $region_setting = $this->region_model->get_global_setting('region');

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
            'max_session_per_x_day' => $region_setting[0]->max_session_per_x_day,
            'x_day' => $region_setting[0]->x_day,
            'set_max_session' => $region_setting[0]->set_max_session,
            'status_set_setting' => $this->input->post('status_set_setting'),
            'type' => $region_setting[0]->type,
            'dcrea' => time(),
            'dupd' => time()
        ];

        // insert into table specific setting
        $this->region_model->insert_specific_setting($specific_settings);

        // insert into user token
        $this->db->insert('user_tokens', array('user_id' => $user_id, 'token_amount' => $this->input->post('token_amount'), 'dcrea' => time(), 'dupd' => time()));

        // insert into token request
        $dt_token_request = array('approve_id' => $this->auth_manager->userid(),
                                  'user_id' => $user_id,
                                  'token_amount' => $this->input->post('token_amount'),
                                  'status' => 'given',
                                  'dcrea' => time(),
                                  'dupd' => time());
        $this->db->insert('token_requests',$dt_token_request);

        $this->db->trans_commit();

        $member_notification = array(
            'user_id' => $user_id,
            'description' => 'Congratulation '.$this->input->post('fullname').' and Welcome to DynEd Live',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );
        
        $this->user_notification_model->insert($member_notification);        

        // send email   
        $this->send_email->superadmin_create_region($this->input->post('fullname'), $this->input->post('email'), $password, 'add');
        // ============

        $this->messages->add('Inserting Admin Regional Successful', 'success');
        redirect('superadmin/region');
        
    }

    public function detail($id='', $page='')
    {

        $this->template->title = 'Detail Region';

        $data_admin = $this->identity_model->get_region_admin_identity($id);

        $id_regional = $data_admin[0]->id;

        $get_old_email = $this->db->select('email')
                              ->from('users')
                              ->where('id',$id)
                              ->get()->result();
        $old_email = $get_old_email[0]->email;

        $submit = $this->input->post('_submit');
                if($submit == 'SAVE'){

                     $rules = array(
                        array('field'=>'region', 'label' => 'region', 'rules'=>'trim|required|xss_clean|max_length[50]'),
                        array('field'=>'email', 'label' => 'Email', 'rules'=>'trim|required|valid_email')
                    );

                    if (!$this->common_function->run_validation($rules)) {
                        $this->messages->add(validation_errors(), 'warning');
                        redirect('superadmin/region/detail/'.$id_regional);
                        return;
                    }

                     // check email
                     $status_email = $this->cek_emailnya($id, $this->input->post('email'));

                     if($status_email != 1){
                        $this->messages->add('Email already registered', 'warning');
                        redirect('superadmin/region/detail/'.$id_regional);
                     }

                    $update_admin = $this->user_profile_model->where('user_id', $id_regional)->get();
                    $data_region = ['region_id' => $this->input->post('region')];

                     
                    
                    
                    if (!$this->user_profile_model->update($update_admin->id, $data_region, TRUE)) {
                        $this->messages->add(validation_errors(), 'warning');
                        redirect('superadmin/region/detail/'.$id_regional);
                        return;
                    }

                    if($old_email != $this->input->post('email')){

                        $pass_default = 'dyned123';
                        $data_surel = ['email' => $this->input->post('email'),
                                       'password' => $this->phpass->hash($pass_default)];
                        $update_email = $this->db->where('id', $id)
                                                 ->update('users', $data_surel);
                        // old_data
                        $old_data = $this->db->select('region_id')
                                             ->from('user_profiles')
                                             ->where('user_id',$id)
                                             ->get()->result();

                        // sent email

                        $this->send_email->superadmin_edit_email_region($old_data[0]->region_id, $email, $pass_default);
                    }

                    
                    $this->messages->add('Update Successful', 'success');
                    redirect('superadmin/region/detail/'.$id_regional);
                }
                    
                    
        // get info token
        $token = $this->user_token_model->select('token_amount')->where('user_id',$id_regional)->get();

        // get status set partner setting
        $status_set_setting = $this->region_model->get_specific_setting($id_regional);
 
        $status_set_setting = @$status_set_setting[0]->status_set_setting;
        // ==============

        $offset = 0;
        $per_page = 6;
        $uri_segment = 5;

        // search
        $search_region = $this->input->post('search_region');
        if($search_region != ''){
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('superadmin/region/detail/'.$id.'/'), count($this->partner_model->select('id, profile_picture, name, address, country, state, city, zip')->where('name not like', 'No Partner')->like('name',$search_region)->where('admin_regional_id',$id)->order_by('name', 'asc')->get_all()), $per_page, $uri_segment);           
            $partner = $this->partner_model->select('id, profile_picture, name, address, country, state, city, zip')->where('admin_regional_id', $id_regional)->where('admin_regional_id',$id)->like('name',$search_region)->order_by('name', 'asc')->limit($per_page)->offset($offset)->get_all();
        } else {
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('superadmin/region/detail/'.$id.'/'), count($this->partner_model->select('id, profile_picture, name, address, country, state, city, zip')->where('name not like', 'No Partner')->where('admin_regional_id',$id)->order_by('name', 'asc')->get_all()), $per_page, $uri_segment);
            $partner = $this->partner_model->select('id, profile_picture, name, address, country, state, city, zip')->where('admin_regional_id', $id_regional)->where('admin_regional_id',$id)->order_by('name', 'asc')->limit($per_page)->offset($offset)->get_all();
        }

        // =========

        $vars = [
            'token' => $token,
            'id_regional' => $id_regional,
            'data_admin' => $data_admin,
            'partner' => $partner,
            'pagination' => $pagination,
            'status_set_setting' => $status_set_setting
        ];

        // echo "<pre>";
        // print_r($vars);exit();


        $this->template->content->view('default/contents/superadmin/region/detail_region', $vars);
        $this->template->publish();
    }

    function cek_emailnya($id=2061, $email){
        // check prev email
        $a = $this->db->select('email')
                      ->from('users')
                      ->where('id',$id)
                      ->get()->result();
        $b = $a[0]->email;

        if($email != $b){
            $cemail = $this->db->select('email')
                          ->from('users')
                          ->where('email =',$email)
                          ->get()->result();
            if(!$cemail){
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }

    }

    function change_status(){
        $select = $this->input->post('option');
        $id = $this->input->post('id');
        
        $this->db->where('user_id',$id);
        $this->db->update('specific_settings',array('status_set_setting'=>$select));
        
    }

    function update($id){

        $rules = array(
                array('field'=>'fullname', 'label' => 'Fullname', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'region', 'label' => 'Region', 'rules'=>'trim|required|xss_clean'),
            );



            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                redirect('superadmin/region/detail/'.$id);
            }

            $fullname = $this->input->post('fullname');
            $region = $this->input->post('region');

            $data_update = ['fullname' => $fullname,
                            'region_id' => $region];

            // $this->db->trans_begin();

            $this->region_model->update_region($id, $data_update);


            // $this->db->trans_commit();


            $this->messages->add('Update Successful', 'success');

            redirect('superadmin/region/detail/'.$id);

        
    }

    public function setting($id='', $type='')
    { 
        $setting = $this->region_model->get_specific_setting($id);

        $back = site_url('superadmin/region/detail/'.$id);
        
        $vars = ['data' => $setting,
                'id' => $id,
                'back' => $back];

        if(($type == "coach") || ($type == "")){
            $this->template->content->view('default/contents/superadmin/region/coach', $vars);
            //publish template
            // $this->template->publish();
        } else if($type == 'student'){
            $this->template->content->view('default/contents/superadmin/region/student', $vars);
            //publish template
            // $this->template->publish();
        }

        // $this->template->content->view('default/contents/superadmin/region/setting', $vars);
        $this->template->publish();
    }

    function delete_partner($region_id = ''){
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
                $this->messages->add('Please Move your affiliate', 'danger');

            }
        } else {
            $this->messages->add('Please select affiliate', 'danger');
        }

        redirect('superadmin/region/detail/'.$region_id);
    }

    function region_active(){
        if(!empty($_POST['check_list'])) {
            $check_list = $_POST['check_list'];
            $type_submit = $_POST['_submit'];
            if($type_submit == "delete"){
                    $check = $this->db->select('id')
                                      ->from('partners')
                                      ->where_in('admin_regional_id',$check_list)
                                      ->get();
                    
                    if($check->num_rows() == 0){
                        $this->db->trans_begin();
                    
                        $this->db->where('role_id',4);
                        $this->db->where_in('id',$check_list);
                        $this->db->delete('users');

                        $this->db->flush_cache();

                        $this->db->where_in('user_id',$check_list);
                        $this->db->delete('user_profiles');
                        $this->db->trans_commit();
                        $this->messages->add('Delete Successful', 'success');

                    } else if($check->num_rows() > 0){
                        $this->messages->add('You still have a member', 'warning');
                    }


            } else if($type_submit == "deactive"){
                $this->db->trans_begin();

                    $this->db->where('role_id',4);
                    $this->db->where_in('id',$check_list);
                    $this->db->update('users',array('status' => "disable"));
                $this->db->trans_commit();

                $this->messages->add('Deactive Successful', 'success');
            }

        } else {
            $this->messages->add('Please select region', 'error');
        }

            redirect('superadmin/region/index/active');
    }    

    function region_inactive(){
        // if(!empty($_POST['check_list'])) {
        //     $check_list = $_POST['check_list'];
        //     $type_submit = $_POST['_submit'];
        //     if($type_submit == "delete"){
        //         $this->db->trans_begin();
        //             $this->db->where('role_id',4);
        //             $this->db->where_in('id',$check_list);
        //             $this->db->delete('users');

        //             $this->db->flush_cache();

        //             $this->db->where_in('user_id',$check_list);
        //             $this->db->delete('user_profiles');
        //         $this->db->trans_commit();
        //         $this->messages->add('Deleted Succeeded', 'success');

        //     } else if($type_submit == "deactive"){
        //         $this->db->trans_begin();

        //             $this->db->where('role_id',4);
        //             $this->db->where_in('id',$check_list);
        //             $this->db->update('users',array('status' => "active"));
        //         $this->db->trans_commit();

        //         $this->messages->add('Actived Succeeded', 'success');
        //     }


        // }

        //     redirect('superadmin/region/index/inactive');
      // =================================
        if(!empty($_POST['check_list'])) {
            $check_list = $_POST['check_list'];
            $type_submit = $_POST['_submit'];
            if($type_submit == "delete"){
                    $check = $this->db->select('id')
                                      ->from('partners')
                                      ->where_in('admin_regional_id',$check_list)
                                      ->get();
                    
                    if($check->num_rows() == 0){
                        $this->db->trans_begin();
                    
                        $this->db->where('role_id',4);
                        $this->db->where_in('id',$check_list);
                        $this->db->delete('users');

                        $this->db->flush_cache();

                        $this->db->where_in('user_id',$check_list);
                        $this->db->delete('user_profiles');
                        $this->db->trans_commit();
                        $this->messages->add('Deleted Successful', 'success');

                    } else if($check->num_rows() > 0){
                        $this->messages->add('You still have a member', 'warning');
                    }


            } else if($type_submit == "deactive"){
                $this->db->trans_begin();

                    $this->db->where('role_id',4);
                    $this->db->where_in('id',$check_list);
                    $this->db->update('users',array('status' => "active"));
                $this->db->trans_commit();

                $this->messages->add('Active Successful', 'success');
            }

        } else {
            $this->messages->add('Please select region', 'danger');
        }

        redirect('superadmin/region/index/deactivate');
        // =================================

    }

    function update_setting($id){
        if($this->input->post('__submit') == 'region_student'){
            $rules = array(
                    array('field'=>'max_student_class', 'label' => 'max_student_class', 'rules'=>'trim|required|xss_clean'),
                    array('field'=>'max_student_supplier', 'label' => 'max_student_supplier', 'rules'=>'trim|required|xss_clean'),
                    array('field'=>'max_day_per_week', 'label' => 'max_day_per_week', 'rules'=>'trim|required|xss_clean'),
                    array('field'=>'max_session_per_day', 'label' => 'max_session_per_day', 'rules'=>'trim|required|xss_clean'),
                    array('field'=>'max_token', 'label' => 'max_token', 'rules'=>'trim|required|xss_clean'),
                    array('field'=>'max_token_for_student', 'label' => 'max_token_for_student', 'rules'=>'trim|required|xss_clean'),
                    array('field'=>'max_session_per_x_day', 'label' => 'max_session_per_x_day', 'rules'=>'trim|required|xss_clean'),
                    array('field'=>'x_day', 'label' => 'x_day', 'rules'=>'trim|required|xss_clean')
                );

            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                redirect('superadmin/region/setting/'.$id.'/student');
            }

            if($this->input->post('max_day_per_week') > 7){
                $this->messages->add('Max day per week cannot more than 7', 'warning');
                redirect('superadmin/region/setting/'.$id.'/student');
            }

            if($this->input->post('max_session_per_day') > 96){
                $this->messages->add('Max session per day cannot more than 96', 'warning');
                redirect('superadmin/region/setting/'.$id.'/student');
            }

           $setting = array(
                'max_student_class' => $this->input->post('max_student_class'),
                'max_student_supplier' => $this->input->post('max_student_supplier'),
                'max_day_per_week' => $this->input->post('max_day_per_week'),
                'max_session_per_day' => $this->input->post('max_session_per_day'),
                'max_token' => $this->input->post('max_token'),
                'max_token_for_student' => $this->input->post('max_token_for_student'),
                'max_session_per_x_day' => $this->input->post('max_session_per_x_day'), 
                'x_day' => $this->input->post('x_day'),
                'set_max_session' => $this->input->post('set_max_session'),
                
                
            );

           // update token di table user token
           $update_token = $this->db->where('user_id',$id)->update('user_tokens',array('token_amount' => $this->input->post('max_token')));
           $type="student";
       } else if($this->input->post('__submit') == 'region_coach'){

                if(($this->input->post('standard_coach_cost') < 1) || ($this->input->post('elite_coach_cost') < 1)){
                    $this->messages->add('Standard Coach cost or Elite Coach cost minimum 1', 'warning');
                    redirect('superadmin/region/setting/'.$id.'/coach');
                    
                }
            
            $setting = array('session_duration' => $this->input->post('session_duration'),
                            'standard_coach_cost' => $this->input->post('standard_coach_cost'),
                            'elite_coach_cost' => $this->input->post('elite_coach_cost'));
            $type="coach";
       }

       $this->region_model->update_setting($id,$setting);

       $this->messages->add('Update Setting Successful', 'success');

       redirect('superadmin/region/setting/'.$id.'/'.$type);
    }

    function refund_token($region_id){
        $this->template->title = 'Refund Token';

        $data_token_region = $this->db->select('token_amount')->from('user_tokens')->where('user_id', $region_id)->get()->result();

        $data_token_refund = $this->db->select('token_amount, balance')
                                  ->from('token_histories')
                                  ->where('user_id', $region_id)
                                  ->where('token_status_id',27)
                                  ->order_by('id','desc')
                                  ->get()->result();
  
        $token_region = @$data_token_region[0]->token_amount;

        $token_amount = @$data_token_refund[0]->token_amount;
        $balance = @$data_token_refund[0]->balance;

        $status = '';
        if($token_region == $balance){
            $status = 1;
        } 
        if($token_region != $balance) {
            $status = 0;
        }
        if(!$data_token_refund){
            $status = 3;
        }

        $link = base_url().'index.php/superadmin/region/action_refund_token'.'/'.$region_id;
        $cancel = base_url().'index.php/superadmin/region/detail/'.$region_id;

        $vars = array('region_id' => $region_id,
                     'status' => $status,
                     'token_amount' => $token_amount,
                     'link' => $link,
                     'cancel' => $cancel);

        $this->template->content->view('default/contents/managing/token/refund', $vars);
        $this->template->publish();

    }

    function action_refund_token($region_id){
        $old_data_token_region = $this->db->select('token_amount')->from('user_tokens')->where('user_id', $region_id)->get()->result();

        $data_token_refund = $this->db->select('token_amount, balance')
                                  ->from('token_histories')
                                  ->where('user_id', $region_id)
                                  ->where('token_status_id',27)
                                  ->order_by('id','desc')
                                  ->get()->result();
  
        $token_region = @$old_data_token_region[0]->token_amount;

        $token_amount = @$data_token_refund[0]->token_amount;
        $balance = @$data_token_refund[0]->balance;

        // check apakah tokennya sama
        
        if($token_region != $balance) {
            $this->messages->add('Your token failed refund ', 'warning');
            redirect('superadmin/region/detail/'.$region_id);
        }



        // =====

        // update token user
        $token_update = $balance-$token_amount;

        $data_users = array('token_amount' => $token_update,
                                  'dcrea' => time(),
                                  'dupd' => time()
                                  );

        $update_users_token = $this->db->where('user_id', $region_id)
                                          ->update('user_tokens', $data_users);

        $data_token_region = $this->db->select('token_amount')->from('user_tokens')->where('user_id', $region_id)->get()->result();

        // insert into tabel token histories

        $data_histories_token = array('balance' => $data_token_region[0]->token_amount,
                                      'token_amount' => $token_amount,
                                      'user_id' => $region_id,
                                      'transaction_date' => time(),
                                      'description' => 'Your tokens were refunded by Super admin',
                                      'token_status_id' => 29);

        $this->db->insert('token_histories', $data_histories_token);

        // insert into table token_requests
        $insert_table_req = array('approve_id' => $this->auth_manager->userid(),
                                  'user_id' => $region_id,
                                  'token_amount' => $token_amount,
                                  'status' => 'refund',
                                  'dcrea' => time(),
                                  'dupd' => time());
        $this->db->insert('token_requests',$insert_table_req);

        // send notification and email
        $partner_notification = array(
            'user_id' => $region_id,
            'description' => 'Your token has been refund. '.$token_amount.' by Super Admin',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );


         $this->db->trans_commit();

        // messaging inserting data notification

        $this->user_notification_model->insert($partner_notification);
        $this->send_email->add_token($get_email[0]->email, $get_name[0]->fullname, $request_token, $get_email[0]->role_id, $get_name2[0]->fullname);

        $this->messages->add('Your token success refund ', 'success');
        redirect('superadmin/region/detail/'.$region_id);

    }

    function add_token($region_id){
        $this->template->title = 'Add Tokens';

        $link = base_url().'index.php/superadmin/region/action_add_token'.'/'.$region_id;
        $cancel = base_url().'index.php/superadmin/region/detail/'.$region_id;
        $vars = array(
                'link' => $link,
                'cancel' => $cancel
                );

        $this->template->content->view('default/contents/managing/token/index', $vars);
        $this->template->publish();
    }

    function action_add_token($region_id = ''){
        date_default_timezone_set('Etc/GMT+0');
        
        if (!$this->input->post('token') || $this->input->post('token') <=0) {
            $this->messages->add('Token Request Value Must be More than Zero', 'warning');
            redirect('superadmin/region/add_token/'.$region_id);
        }

        $request_token = $this->input->post('token');

     
        // check token region
        $get_token = $this->user_token_model->get_token($region_id,'user');
        $student_token = '';
        if($get_token){
            $student_token = $get_token[0]->token_amount;    
        } else {
            $student_token = 0;
        }

        //get data for email
        $get_name = $this->user_profile_model->select('user_id, fullname')->where('user_id', $this->auth_manager->userid())->get_all();
        $get_name2 = $this->user_profile_model->select('user_id, fullname')->where('user_id', $region_id)->get_all();
        $get_email = $this->user_model->select('id, email, role_id')->where('id', $region_id)->get_all();
 
        // $student_token = $get_token[0]->token_amount;
        // =================

        // check jika total token melebihi max token
        $total_request_token = $request_token+$student_token;


        // if($total_request_token > $max_student_supplier){
        //     $this->messages->add('Token Request exceeds the maximum, maximum token for student = '.$max_student_supplier, 'warning');
        //     redirect('admin/manage_partner/add_token/'.$student_supplier_id.'/'.$partner_id);
        // }


        // jika gak ada masalah

        // pengurangan token user
        $this->db->trans_begin();

        // update token student

        if($get_token){
            $data_student = array('token_amount' => $total_request_token,
                                  'dcrea' => time(),
                                  'dupd' => time()
                                  );

            $update_student_token = $this->db->where('user_id', $region_id)
                                          ->update('user_tokens', $data_student); 
        } else {
            $data_student = array(
              'user_id' => $region_id,
              'token_amount' => $total_request_token,
              'dcrea' => time(),
              'dupd' => time()
            );
            $this->db->insert('user_tokens',$data_student);
        } 

        // insert into table request for information
        $insert_table_req = array('approve_id' => $this->auth_manager->userid(),
                                  'user_id' => $region_id,
                                  'token_amount' => $request_token,
                                  'status' => 'added',
                                  'dcrea' => time(),
                                  'dupd' => time());
        $this->db->insert('token_requests',$insert_table_req);

        // insert into tabel token histories

        $data_histories_token = array('balance' => $total_request_token,
                                      'token_amount' => $request_token,
                                      'user_id' => $region_id,
                                      'transaction_date' => time(),
                                      'description' => 'Your token has been added by your Super Admin',
                                      'token_status_id' => 27);

        $this->db->insert('token_histories', $data_histories_token);


        $this->db->trans_commit();

        // send notification and email
        $partner_notification = array(
            'user_id' => $region_id,
            'description' => 'Your token has been added. '.$request_token.' by Super Admin',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );


         $this->db->trans_commit();

        // messaging inserting data notification

        $this->user_notification_model->insert($partner_notification);
        $this->send_email->add_token($get_email[0]->email, $get_name[0]->fullname, $request_token, $get_email[0]->role_id, $get_name2[0]->fullname);
        


        $this->messages->add('Your token success added ', 'success');
        redirect('superadmin/region/detail/'.$region_id);
            
    }
}