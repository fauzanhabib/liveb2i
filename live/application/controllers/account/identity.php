<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class identity extends MY_Site_Controller {

    /**
     * Titles
     * @var array
     */
    var $titles = array(
        'STD' => 'Student Identity',
        'CCH' => 'Coach Identity',
        'PRT' => 'Affiliate Identity',
        'ADM' => 'Admin Identity',
        'SPR' => 'Student Affiliate Identity',
        'RAD' => 'Super Admin Identity',
    );

    /**
     * Models
     * @var array
     * access link for role
     */
    var $access = array(
        'STD' => array('profile', 'token', 'geography', 'student_detail'),
        'CCH' => array('profile', 'education', 'geography'),
        'PRT' => array('profile'),
        'ADM' => array('profile'),
        'SPR' => array('profile'),
        'RAD' => array('profile'),
    );

    /**
     * Views
     * @var array
     */
    var $views = array(
        'STD' => 'student',
        'CCH' => 'coach',
        'PRT' => 'partner',
        'ADM' => 'admin',
        'SPR' => 'student_partner',
        'RAD' => 'super_admin',
    );

    /**
     * Detail
     * @var array
     */
    var $detail = array(
        'education' => 'Education',
        'geography' => 'Home Town',
        'profile' => 'Profile',
        'social_media' => 'Social Media',
        'token' => 'Token',
        'student_detail' => 'Student Detail',
    );

    /**
     * User role
     * @var string
     */
    var $role = '';
    var $upload_path = 'uploads/images/propic/';

    // Constructor
    public function __construct() {
        parent::__construct();
        // Load Model
        $this->load->model('user_role_model');
        $this->load->model('user_model');
        $this->load->model('user_profile_model');
        $this->load->model('identity_model');
        $this->load->model('partner_model');
        // To convert id to name of Social Media
        $this->load->model('social_media_model');
        $this->load->model('timezone_model');
        
        $this->load->library('common_function');

        // Load language
        $this->lang->load('view');
        
        // Get user role
        $this->role = $this->auth_manager->role();
        if (!$this->role) {
            $this->messages->add('Error for displaying your profile', 'warning');
            redirect('logout');
        }
    }

    // Index
    public function index() {
        redirect('account/identity/detail/profile');
    }

    // Detail
    public function detail($key) {
//        echo('<pre>');
//        print_r($this->common_function->country_code); exit;
        //checking access previlege for role
        if (!in_array($key, $this->access[$this->role])) {
            $this->messages->add('Access Denied');
            redirect('logout');
        }

        // setting key for edit data
        $this->session->set_userdata("key", $key);
        
        // Querying record based on ID and role checking
        // If there is no social media will redirect to fill/login one of the social media
        if ($this->auth_manager->role() == 'STD') {
            $data = $this->identity_model->get_student_identity($this->auth_manager->userid());
            $get_region = $this->common_function->region_from_partner($data[0]->partner_id);
            @$name_region = $this->db->select('region_id')->from('user_profiles')->where('user_id',$get_region[0]->admin_regional_id)->get()->result();
            @$get_partner = $this->db->select('name')->from('partners')->where('id',$data[0]->partner_id)->get()->result();
            // echo "<pre>";
            // print_r($data);
            // exit();
        } else if ($this->auth_manager->role() == 'CCH') {
            $data = $this->identity_model->get_coach_identity($this->auth_manager->userid());
        } else if ($this->auth_manager->role() == 'PRT') {
            $data = $this->identity_model->get_partner_identity($this->auth_manager->userid(), '', '', '', 3);
            $get_region = $this->common_function->region_from_partner($data[0]->partner_id);
            @$name_region = $this->db->select('region_id')->from('user_profiles')->where('user_id',$get_region[0]->admin_regional_id)->get()->result();
            @$get_partner = $this->db->select('name')->from('partners')->where('id',$data[0]->partner_id)->get()->result();
        } else if ($this->auth_manager->role() == 'SPR') {
            $data = $this->identity_model->get_partner_identity($this->auth_manager->userid(), '', '', '', 5);
            $get_region = $this->common_function->region_from_partner($data[0]->partner_id);
            @$name_region = $this->db->select('region_id')->from('user_profiles')->where('user_id',$get_region[0]->admin_regional_id)->get()->result();
            @$get_partner = $this->db->select('name')->from('partners')->where('id',$data[0]->partner_id)->get()->result();
        } else if (($this->auth_manager->role() == 'ADM') || ($this->auth_manager->role() == 'RAD')) {
            $data = $this->identity_model->get_identity('user')->select('users.id as id, users.email as email, users.password as password, users.status as status, users.status_set_setting as status_set_setting, users.last_login as last_login, users.role_id as role_id, user_profiles.profile_picture as profile_picture, user_profiles.fullname as fullname, user_profiles.region_id as region_id, user_profiles.user_timezone as user_timezone')->join('user_profiles','user_profiles.user_id = users.id')->where('users.id', $this->auth_manager->userid())->get_all();
            $data_timezone_admin = $this->identity_model->get_identity('profile')->select('user_timezone')->where('user_id', $this->auth_manager->userid())->get();
        }// else if ($this->auth_manager->role() == 'RAD') {
        //     $data = $this->identity_model->get_identity('user')->select('*')->where('id', $this->auth_manager->userid())->get_all();
        //     $data_timezone_admin = $this->identity_model->get_identity('profile')->select('user_timezone')->where('user_id', $this->auth_manager->userid())->get();

        // }

            // echo "<pre>";
            // print_r($data);
            // exit();



        if (!$data) {
            $this->messages->add('Record with given ID is not found', 'warning');
            redirect('logout');
        }

        if (!@$data[0]->profile_picture) {
            @$data[0]->profile_picture = 'uploads/images/profile.jpg';
        }

        $this->template->title = 'Detail ' . $this->detail[$key];
        $timezones = $this->timezone_model->where_not_in('minutes',array('-210','330','570',))->dropdown('id', 'timezone');
        $get_user_timezone = $this->db->select('minutes_val')->from('user_timezones')->where('user_id',$this->auth_manager->userid())->get()->result();
        
        if(!$get_user_timezone){
            $minute_user_timezone = 0;            
        } else {
            $minute_user_timezone = $get_user_timezone[0]->minutes_val;
        }

        $get_utz =  $this->db->select('timezone')->from('timezones')->where('minutes',$minute_user_timezone)->get()->result();
        $user_tz = $get_utz[0]->timezone;
        $id_partner = $this->auth_manager->partner_id();
        $partner = $this->partner_model->select('name, address, country, state, city, zip')->where('id',$id_partner)->get_all();
        $partner_country = @$partner[0]->country;
        $get_country = $this->db->select('dial_code')->from('user_profiles')->where('user_id',$this->auth_manager->userid())->get()->result();
        $country_code = $get_country[0]->dial_code;
        if (!$country_code){
            $option_country = $this->common_function->country_code;
            $code = array_column($option_country, 'dial_code', 'name');
            $newoptions = $code;
            $arsearch = array_search($partner_country, array_column($option_country, 'name'));
            $country_code = $option_country[$arsearch]['dial_code'];
        }
       
        array_search('Indonesia', array_column($this->common_function->country_code, 'name'));

        //Check Number Verification ------------------------------
        $idchecknum = $this->auth_manager->userid();
        $getsat = $this->db->select('status')
                ->from('verified_numbers')
                ->where('user_id', $idchecknum)
                ->get()->result();

        $status = @$getsat[0]->status;
        //Check Number Verification ------------------------------

        $id = $this->auth_manager->userid();
        
        $pull_notif = $this->db->select('*')
                      ->from('user_notifications')
                      ->where('user_id', $id)
                      ->get()->result();

        $pull_name = $this->db->select('*')
                      ->from('user_profiles')
                      ->where('user_id', $id)
                      ->get()->result();

        if(!$pull_notif){

            $user_notification = array(
                'user_id' => $id,
                'description' => 'Congratulation '.$pull_name[0]->fullname.' and Welcome to DynEd Live.',
                'status' => 2,
                'dcrea' => time(),
                'dupd' => time(),
            );
                        
            $this->user_notification_model->insert($user_notification);
        }

        $vars = array(
            'name_region' => @$name_region,
            'title' => 'Detail ' . $this->detail[$key],
            'data' => $data,
            'form_action' => 'update',
            'id_to_name' => $this->social_media_model->dropdown('id', 'name'),
            'email' => $this->user_model->select('email')->where('id', $this->auth_manager->userid())->get(),
            'timezones' => $timezones,
            'user_tz' => $user_tz,
            'dial_code' => $this->common_function->country_code[array_search(@$data[0]->country, array_column($this->common_function->country_code, 'name'))]['dial_code'],
            'option_country' => $this->common_function->country_code,
            'admin_timezone' => @$data_timezone_admin->user_timezone,
            'country_code' => $country_code,
            'partner_country' => $partner_country,
            'status' => $status
             
        );
        if ($this->auth_manager->role() == 'STD') {
            $vars['server_dyned_pro'] = $this->common_function->server_code();
        }

        // echo $key;
       // echo('<pre>');
       // print_r($vars); exit;
        $this->template->content->view('default/contents/identity/' . $key . '/index', $vars);
        // Publish template by specific identity part
        $this->template->publish();
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

    // Edit
    public function edit($key = '') {
        //checking access previlege for role
        if (!in_array($key, $this->access[$this->role])) {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
        // setting key for edit data
        $this->session->set_userdata("key", $key);

        // Querying record based on ID and checking
        $data = $this->identity_model->get_identity($key)->where('user_id', $this->auth_manager->userid())->get();
        if (!$data) {
            $this->messages->add('Record with given ID is not found', 'warning');
            redirect('account/identity/detail/profile');
        }
        /* if (!$data->profile_picture) {
          $data->profile_picture = '/uploads/images/profile.jpg';
          } */
        $this->template->title = 'Edit ' . $this->detail[$key];
        $vars = array(
            'title' => 'Edit ' . $this->detail[$key],
            'data' => $data,
            'form_action' => 'update'
        );
        $this->template->content->view('default/contents/identity/' . $key . '/form', $vars);
        $this->template->publish();
    }

    // Update
    public function update($part = '') {

        //checking access previlege for role
        if (!in_array($this->session->userdata("key"), $this->access[$this->role])) {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }

        if(!$part == 'info'){
            if (!$this->input->post('__submit')) {
                $this->messages->add('Invalid action', 'warning');
                redirect('account/identity/detail/profile');
            }
        }

        //storing the data
        $data = array();
        
        if ($part == 'info') {


            foreach ($this->input->post() as $t => $value) {
                if ($t != "__submit" && $t != "spoken_lang") {
                    $data[$t] = $value; 
                }
                if($t == "spoken_language"){
                    if($value != ''){
                        $data["spoken_language"] = str_replace(',', '#', $value);
                    }
                }
                if($t == "fullname"){
                    if($value != ''){
                        $data['fullname'] = htmlentities($value);
                    }
                }
            }
            
            $rules = array(
                array('field'=>'fullname', 'label' => 'Name', 'rules'=>'trim|required|xss_clean|max_length[50]'),
                // array('field'=>'date_of_birth', 'label' => 'Birthday', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'spoken_language', 'label' => 'Spoken Language', 'rules'=>'trim|required|xss_clean|max_length[150]'),
                array('field'=>'gender', 'label' => 'Gender', 'rules'=>'trim|required|xss_clean')
            );
            
            if($this->input->post('date_of_birth') > date('Y-m-d', now())){
                $this->messages->add('Invalid Date', 'warning');
                redirect('account/identity/detail/profile');
            }

            
            if($this->auth_manager->role() == 'PRT' || $this->auth_manager->role() == 'SPR'){
                //$rules[] = array('field'=>'user_timezone', 'label' => 'Timezone', 'rules'=>'trim|required|xss_clean');
            }

            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                $this->detail('profile');
                return;
            }

            
            $id_profile = $this->identity_model->get_identity('profile')->select('id')->where('user_id', $this->auth_manager->userid())->get();
            if (!$this->identity_model->get_identity('profile')->update($id_profile->id, $data, TRUE)) {
                $this->messages->add(validation_errors(), 'warning');
                $this->detail('profile');
                return;
            }
            
        } else if ($part == 'more_info') {

            $rules = array(
                array('field'=>'skype_id', 'label' => 'Skype ID', 'rules'=>'trim|xss_clean'),
                array('field'=>'country', 'label' => 'Country', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'state', 'label' => 'State', 'rules'=>'trim|xss_clean'),
                array('field'=>'address', 'label' => 'Address', 'rules'=>'trim|xss_clean'),
                array('field'=>'city', 'label' => 'City', 'rules'=>'trim|xss_clean'),
                //array('field'=>'user_timezone', 'label' => 'Timezone', 'rules'=>'trim|required|xss_clean')
            );

            if($this->auth_manager->role() == 'CCH'){
           
                // get status appointment by userid
                                // get status appointment by userid
                $get_appointment = $this->db->select('id')
                                            ->from('appointments')
                                            ->where('coach_id',$this->auth_manager->userid())
                                            ->where('status !=','completed')
                                            ->where('date >=',date('Y-m-d'))
                                            ->get()->result();

                if($get_appointment){
                    $data_user_profile = Array(
                        'up.skype_id' => htmlentities($this->input->post('skype_id'))
                    );
                    //$this->messages->add('You can not change your timezone because you have upcoming sessions', 'warning');

                } else {
                    $data_user_profile = Array(
                        'up.skype_id' => htmlentities($this->input->post('skype_id')),
                        'up.user_timezone' => htmlentities($this->input->post('user_timezone')),
                        // 'up.phone' => $this->input->post('phone'),
                        'up.dial_code' => htmlentities($this->input->post('dial_code'))
                    );
                }


                $data_geography = Array(
                    'ge.city' => htmlentities($this->input->post('city')),
                    'ge.state' => htmlentities($this->input->post('state')),
                    'ge.address' => htmlentities($this->input->post('address')),
                    'ge.country' => htmlentities($this->input->post('country'))
                );
                
                $user_profile = $this->identity_model->get_identity('profile')->select('id')->where('user_id', $this->auth_manager->userid())->get();
                $geography = $this->identity_model->get_identity('geography')->select('id')->where('user_id', $this->auth_manager->userid())->get();
                
                if(!$user_profile || !$geography){
                    $this->messages->add('Update Failed', 'warning');
                    $this->detail('profile');
                    return;
                }

                if (!$this->common_function->run_validation($rules)) {
                    $this->messages->add(validation_errors(), 'warning');
                    $this->detail('profile');
                    return;
                }

                $this->db->set($data_geography);
                $this->db->set($data_user_profile);
                $this->db->where('ge.id', $geography->id);
                $this->db->where('up.id', $user_profile->id);
                $this->db->where('ge.user_id = up.user_id');
                $this->db->update('user_profiles as up, user_geography as ge');

                if (!$this->db->affected_rows()) {
                    $this->messages->add('Nothing changes', 'warning');
                    $this->detail('profile');
                    return;
                }
            }else if($this->auth_manager->role() == 'STD'){
                // get status appointment by userid
                $get_appointment = $this->db->select('id')
                                            ->from('appointments')
                                            ->where('student_id',$this->auth_manager->userid())
                                            ->where('status !=','completed')
                                            ->where('date >=',date('Y-m-d'))
                                            ->get()->result();
                if($get_appointment){
                    $data_user_profile = Array(
                        'up.skype_id' => htmlentities($this->input->post('skype_id')),
                        // 'up.phone' => $this->input->post('phone'),
                        'up.dial_code' => htmlentities($this->input->post('dial_code'))
                    );
                    //$this->messages->add('You can not change your timezone because you have upcoming sessions', 'warning');

                } else {
                    $data_user_profile = Array(
                        'up.skype_id' => htmlentities($this->input->post('skype_id')),
                        'up.user_timezone' => htmlentities($this->input->post('user_timezone')),
                        // 'up.phone' => $this->input->post('phone'),
                        'up.dial_code' => htmlentities($this->input->post('dial_code'))
                    );
                }
          


                $data_geography = Array(
                    'ge.city' => htmlentities($this->input->post('city')),
                    'ge.state' => htmlentities($this->input->post('state')),
                    'ge.address' => htmlentities($this->input->post('address')),
                    'ge.country' => htmlentities($this->input->post('country'))
                );

                $data_student_detail = Array(
                    'sd.language_goal' => htmlentities($this->input->post('language_goal')),
                    'sd.hobby' => htmlentities($this->input->post('hobby')),
                    'sd.like' => htmlentities($this->input->post('like')),
                    'sd.dislike' => htmlentities($this->input->post('dislike'))
                );
                
                $user_profile = $this->identity_model->get_identity('profile')->select('id')->where('user_id', $this->auth_manager->userid())->get();
                $geography = $this->identity_model->get_identity('geography')->select('id')->where('user_id', $this->auth_manager->userid())->get();
                $student_detail = $this->identity_model->get_identity('student_detail')->select('id')->where('student_id', $this->auth_manager->userid())->get();
                
                if(!$user_profile || !$geography || !$student_detail){
                    $this->messages->add('Update Failed ', 'warning');
                    $this->detail('profile');
                    return;
                }

                if (!$this->common_function->run_validation($rules)) {
                    $this->messages->add(validation_errors(), 'warning');
                    $this->detail('profile');
                    return;
                }

                $this->db->set($data_geography);
                $this->db->set($data_student_detail);
                $this->db->set($data_user_profile);
                $this->db->where('ge.id', $geography->id);
                $this->db->where('sd.id', $student_detail->id);
                $this->db->where('up.id', $user_profile->id);
                $this->db->where('ge.user_id = sd.student_id');
                $this->db->where('ge.user_id = up.user_id');
                $this->db->update('user_profiles as up, user_geography as ge, student_detail_profiles as sd');

                if (!$this->db->affected_rows()) {
                    $this->messages->add('Nothing changes', 'warning');
                    $this->detail('profile');
                    return;
                }
            }else{
                $this->messages->add('Update Failed', 'warning');
                $this->detail('profile');
                return;
            }
        } elseif ($part == 'account') {

            if($this->auth_manager->role() == 'RAD' || $this->auth_manager->role() == 'ADM'){
                $submit = $this->input->post('_submit');
                if($submit == 'SUBMITPASS'){

                    // ===========

                   $old_password = $this->input->post('old_password');
            
                    if (!$this->auth_manager->check_password($this->auth_manager->user_email(), $old_password)) {
                        $this->messages->add('Incorrect old password', 'warning');
                        $this->detail('profile');
                        return;
                    }
                    
                    $rules = array(
                        array('field'=>'old_password', 'label' => 'Old Password', 'rules'=>'trim|required|xss_clean'),
                        array('field'=>'new_password', 'label' => 'New Password', 'rules'=>'trim|required|xss_clean'),
                        array('field'=>'confirm_password', 'label' => 'Confirm Password', 'rules'=>'trim|required|xss_clean')
                    );

                    if (!$this->common_function->run_validation($rules)) {
                        $this->messages->add(validation_errors(), 'warning');
                        $this->detail('profile');
                        return;
                    }
                    
                    $errors = "";
                    if(!$this->auth_manager->is_password_valid($this->input->post('new_password'), $errors)){
                        foreach ($errors as $error){
                            $this->messages->add($error, 'warning');
                        }
                        $this->detail('profile');
                        return;
                    }
                    
                    if($this->input->post('new_password') != $this->input->post('confirm_password')){
                        $this->messages->add('Password not match', 'warning');
                        $this->detail('profile');
                        return;
                    }

                    $new_pass = $this->phpass->hash($this->input->post('new_password'));

                    $this->load->library('phpass');
                    $data = Array('password' => $new_pass);
                    
                    $user = $this->identity_model->get_identity('user')->select('id')->where('id', $this->auth_manager->userid())->get();
                    if (!$this->identity_model->get_identity('user')->update($user->id, $data)) {
                        $this->messages->add(validation_errors(), 'warning');
                        $this->detail('profile');
                        return;
                    }
                    
                    if($this->auth_manager->role() == 'ADM'){
                        $data_timezone_admin = array(
                            'user_timezone' => htmlentities($this->input->post('user_timezone')),
                        );
                        //print_r ($data_timezone_admin); exit;
                        $data_admin = $this->user_profile_model->where('user_id', $this->auth_manager->userid())->get();
                        if (!$this->user_profile_model->update($data_admin->id, $data_timezone_admin, TRUE)) {
                            $this->messages->add(validation_errors(), 'warning');
                            $this->detail('profile');
                            return;
                        }
                    }
                    // ===========

                } else if($submit == 'SAVE'){

                    $rules = array(
                        array('field'=>'fullname', 'label' => 'fullname', 'rules'=>'trim|required|xss_clean|max_length[50]')
                    );

                    if (!$this->common_function->run_validation($rules)) {
                        $this->messages->add(validation_errors(), 'warning');
                        $this->detail('profile');
                        return;
                    }

                    $data_admin = $this->user_profile_model->where('user_id', $this->auth_manager->userid())->get();
                    $data_fullname = ['fullname' => htmlentities($this->input->post('fullname')),
                                      'user_timezone' => htmlentities($this->input->post('user_timezone'))];
                    if (!$this->user_profile_model->update($data_admin->id, $data_fullname, TRUE)) {
                        $this->messages->add(validation_errors(), 'warning');
                        $this->detail('profile');
                        return;
                    }

                }
                    $this->session->unset_userdata("key");
                    $this->messages->add('Update Successful', 'success');
                    redirect('account/identity/detail/profile');
            }
            $old_password = $this->input->post('old_password');
            
            if (!$this->auth_manager->check_password($this->auth_manager->user_email(), $old_password)) {
                $this->messages->add('Incorrect old password', 'warning');
                $this->detail('profile');
                return;
            }
            
            $rules = array(
                array('field'=>'old_password', 'label' => 'Old Password', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'new_password', 'label' => 'New Password', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'confirm_password', 'label' => 'Confirm Password', 'rules'=>'trim|required|xss_clean')
            );

            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                $this->detail('profile');
                return;
            }
            
            $errors = "";
            if(!$this->auth_manager->is_password_valid($this->input->post('new_password'), $errors)){
                foreach ($errors as $error){
                    $this->messages->add($error, 'warning');
                }
                $this->detail('profile');
                return;
            }
            
            if($this->input->post('new_password') != $this->input->post('confirm_password')){
                $this->messages->add('Password not match', 'warning');
                $this->detail('profile');
                return;
            }
            
            $data = Array('password' => $this->auth_manager->hashing_password($this->input->post('new_password')));
            
            $user = $this->identity_model->get_identity('user')->select('id')->where('id', $this->auth_manager->userid())->get();
            if (!$this->identity_model->get_identity('user')->update($user->id, $data)) {
                $this->messages->add(validation_errors(), 'warning');
                $this->detail('profile');
                return;
            }
            
            if($this->auth_manager->role() == 'ADM'){
                $data_timezone_admin = array(
                    'user_timezone' => htmlentities($this->input->post('user_timezone')),
                );
                //print_r ($data_timezone_admin); exit;
                $data_admin = $this->user_profile_model->where('user_id', $this->auth_manager->userid())->get();
                if (!$this->user_profile_model->update($data_admin->id, $data_timezone_admin, TRUE)) {
                    $this->messages->add(validation_errors(), 'warning');
                    $this->detail('profile');
                    return;
                }
            }


        } elseif ($part == 'profile_picture') {
            $profile_picture = $this->do_upload('profile_picture');
            // $resize = $this->do_resize($profile_picture);
      
            $propicname = $this->db->select('profile_picture')->from('user_profiles')->where('user_id',$this->auth_manager->userid())->get()->result();
            $propicname = $propicname[0]->profile_picture;
            $propicname = explode('/', $propicname);
  
  

            if (!$profile_picture) {
                $this->messages->add('Failed to upload image', 'warning');
                return $this->detail('profile');
            } 
            $data_profile_picture['profile_picture'] = $this->upload_path . $profile_picture['file_name'];

            // delete image sebelumnya
            $delete_image = $this->upload_path."".$propicname[3];;
            unlink($delete_image);
      
            $profile = $this->identity_model->get_identity('profile')->select('id')->where('user_id', $this->auth_manager->userid())->get();
            if (!$this->identity_model->get_identity('profile')->update($profile->id, $data_profile_picture, TRUE)) {
                $this->messages->add(validation_errors(), 'warning');
                return $this->detail('profile');
            }

        } elseif ($part == 'education') {
            
            $rules = array(
                array('field'=>'higher_education', 'label' => 'Higher Education', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'undergraduate', 'label' => 'Undergraduate', 'rules'=>'trim|required|xss_clean')
            );

            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                $this->detail('profile');
                return;
            }
            
            foreach ($this->input->post() as $t => $value) {
                if ($t != "__submit") {
                    $data[$t] = $value;
                }
            } 

            $id_profile = $this->identity_model->get_identity('education')->select('id')->where('user_id', $this->auth_manager->userid())->get();
            if (!$this->identity_model->get_identity('education')->update($id_profile->id, $data)) {
                $this->messages->add(validation_errors(), 'warning');
                $this->detail('profile');
                return;
            }
        }

        $this->session->unset_userdata("key");
        $this->messages->add('Update Successful', 'success');
        redirect('account/identity/detail/profile');
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

    private function old_do_upload($name) {
        $config['upload_path'] = $this->upload_path."temp";
        // echo $this->upload_path."temp";
        // exit();
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
    
    public function disconnect_to_dyned_pro(){
        $data = array(
            'dyned_pro_id' => '',
            'server_dyned_pro' => '',
        );
        $user_profile_id = $this->user_profile_model->where('user_id', $this->auth_manager->userid())->get();
        if($this->user_profile_model->update($user_profile_id->id, $data)){
            $this->messages->add('Disconected To DynED PRO Succeeded', 'success');
            redirect('account/identity/detail/profile');
        }
        else{
            $this->messages->add('Invalid Action', 'Warning');
            redirect('account/identity/detail/profile');
        }
        
    }

    function alpha_dash_space($str){
        return ( ! preg_match("/^([-a-z_ ])+$/i", $str)) ? FALSE : TRUE;
    }

    public function verifynumber(){
        $userid = $this->auth_manager->userid();
        $realnum = $_POST['realnum'];
        $phcode = $_POST['phcode'];
        $phnum  = $_POST['phnum'];

        $countupd  = $_POST['countupd'];

        $data = array(
               'phone' => $phnum,
               'dial_code' => $phcode
            );

        $this->db->where('user_id', $userid);
        $this->db->update('user_profiles', $data); 

        $data2 = array(
               'country' => $countupd,
            );

        $this->db->where('user_id', $userid);
        $this->db->update('user_geography', $data2);

        $codenumber = mt_rand(1000,9999);

        $checkexist = $this->db->select('user_id')
	                ->from('verified_numbers')
	                ->where('user_id', $userid)
	                ->get()->result();

	    if(!@$checkexist[0]->user_id){
	        $data3 = array(
			   'user_id' => $userid,
			   'phone_verif' => $realnum,
			   'status' => 'Not Verified',
			   'code' => $codenumber
			);

			$this->db->insert('verified_numbers', $data3); 
		}else{
            $checkstatus =  $this->db->select('*')
                            ->from('verified_numbers')
                            ->where('user_id', $userid)
                            ->get()->result();

            $codestatus = $checkstatus[0]->status;
            $codeexpiry = $checkstatus[0]->expiration;
            $phone_db   = $checkstatus[0]->phone_verif;

            $currtime = date("Y-m-d H:i:s");

            if ($codestatus != "sent") {
                $data4 = array(
                    'phone_verif' => $realnum,
                    'code' => $codenumber
                );

                $this->db->where('user_id', $userid);
                $this->db->update('verified_numbers', $data4);
            }else if($codestatus = "sent" && $realnum != $phone_db){
                // exit();
                $data5 = array(
                    'phone_verif' => $realnum,
                    'code' => $codenumber,
                    'status' => 'Not Verified'
                );

                $this->db->where('user_id', $userid);
                $this->db->update('verified_numbers', $data5);
            }
		}

        echo $realnum;
    }

    public function changenumber(){
        $userid = $this->auth_manager->userid();

        $this->db->delete('verified_numbers', array('user_id' => $userid));
        $data4 = array(
            'phone' => ''
        );

        $this->db->where('user_id', $userid);
        $this->db->update('user_profiles', $data4);
    }

}
