<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

/**
 * Class Auth_Manager
 *
 * Class library for managing site authentication
 * This library handling login, logout, forgot password and confirm forgot password
 *
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class Auth_Manager {

    /**
     * var $ci
     * CodeIgniter Instance
     */
    private $CI;

    public function __construct() {

        $this->CI = &get_instance();

        $this->CI->load->library('phpass');
        $this->CI->load->library('auth');

        $this->CI->load->model('user_model');
        $this->CI->load->model('user_profile_model');
        $this->CI->load->model('identity_model');
        $this->CI->load->model('user_role_model');
        $this->CI->load->model('user_notification_model');
        $this->CI->load->model('user_notification_model');
        $this->CI->load->model('partner_model');
        
        $this->CI->config->set_item('language','english');
    }

    /**
     * Login
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function login($email, $password) {
        // Checking existing user
        if (!$email) {
            $this->CI->messages->add('Please Insert Email', 'danger');
            return FALSE;
        }
        
        // Checking existing user
        if (!$password) {
            $this->CI->messages->add('Please Insert Password', 'danger');
            return FALSE;
        }



        // Querying user's data from database
        $user = $this->CI->user_model->select('id, email, password, role_id, status')
                ->where('email', $email)
                ->get();

        // Checking existing user
        if (!$user) {
            $this->CI->messages->add('User does not exist', 'danger');
            return FALSE;
        }



        // Checking validity password of user login with database
        if (!$this->CI->phpass->check($password, $user->password)) {
            $this->CI->messages->add('Incorrect password', 'danger');
            return FALSE;
        }

        // IMPORTANT used for later after adding function for admin to approve user 
        // Checking user status
        if ($user->status == 'disable') {
            $this->CI->messages->add('User does not actived yet', 'danger');
            return FALSE;
        }
        
        if ($user->status == 'deactive') {
            $this->CI->messages->add('User has been deactivated by admin', 'danger');
            return FALSE;
        }

        // Valid user
        // Adding login user session
        $this->CI->auth->login($user->id, TRUE);
        $this->CI->messages->clear();

        // update last_login
        // $this->CI->db->where('id', $user->id);
        // $this->CI->db->update('users',array('last_login' => date('Y-m-d')));
        
        // Getting user role based on logged in user
        $role_code = $this->CI->user_role_model->dropdown('id', 'code');
        $this->CI->session->set_userdata("auth_role", $role_code[$user->role_id]);

        // insert to table user_session
        session_start();    
        $session_user_login = session_id(); 

        $check_session = $this->CI->db->select('id, session')
                                      ->from('user_login')
                                      ->where('user_id',$user->id)
                                      ->get()->result();
     // jika id user sudah login dan sessionnya tidak sama
        if(($check_session) && ($check_session[0]->session != $session_user_login)){
                $this->CI->session->set_userdata('user_id_session',$user->id);
                redirect('home/confirmation');                

       } else {
            $data_user = array('user_id' => $user->id,
                              'session' => $session_user_login,
                              'dcrea' => time());

            $this->CI->db->trans_begin();
            $this->CI->db->insert('user_login',$data_user);

             if (!$this->CI->db->trans_status()) {
                $this->CI->db->trans_rollback();
                $this->messages->add('Try again, something wrong while inserting/updating data to database', 'warning');
                return;
            }
            $this->CI->db->trans_commit();


            return TRUE;
        }
    }

    /**
     * Logout
     * @return bool
     */
    public function logout() {
        // $user_id = $this->CI->auth->userid();
        // if($user_id){
        //     $this->CI->db->where('user_id',$user_id);
        //     $this->CI->db->delete('user_login');            
        // }

        //destroying session auth_role and authenticated user
        $this->CI->db->where('user_id',$this->userid());
        $this->CI->db->delete('user_login');
        $this->CI->session->unset_userdata("auth_role");
        $this->CI->auth->logout();
        return TRUE;
    }

    /**
     * Get User ID
     * @return mixed
     */
    public function userid() {
        return $this->CI->auth->userid();
    }

    /**
     * Get User Partner Id
     * @return mixed
     */
    public function partner_id($user_id='') {
        if($user_id){
            $partner = $this->CI->identity_model->get_identity('profile')->select('partner_id')->where('user_id', $user_id)->get();
        }
        else{
            $partner = $this->CI->identity_model->get_identity('profile')->select('partner_id')->where('user_id', $this->CI->auth->userid())->get();
        }
        return(@$partner->partner_id);
    }

     public function region_id($partner_id='') {
        if($partner_id){
            $region = $this->CI->partner_model->select('admin_regional_id')->where('id', $partner_id)->get();
        }
        return(@$region->admin_regional_id);
    }

    /**
     * Get User Notification
     * @return mixed
     */
    public function new_notification() {
        $this->CI->db->where('user_id', $this->CI->auth->userid());
        // status 2 means that the notification never been opened before
        $this->CI->db->where('status', 2);
        $this->CI->db->from('user_notifications');
        $notification = $this->CI->db->count_all_results();
        
        $data_notification = $this->CI->user_notification_model->where('user_id', $this->CI->auth->userid())->limit(3)->order_by('dcrea', 'desc')->get_all();
        foreach($data_notification as $d){
            $received_time[$d->id] =  $this->human_timing(date('Y-m-d H:i:s' ,$d->dcrea));
        }
        //$notification = $this->CI->user_notification_model->where('user_id', $this->CI->auth->userid())->count_all_result();
        return(array('notification' => @$notification, 'data_notification' => @$data_notification, 'received_time' => @$received_time));
    }
    private function human_timing($session_time = '') {
       if (DateTime::createFromFormat('Y-m-d H:i:s', trim($session_time)) != FALSE) {
           $time = time() - strtotime($session_time);
           $tokens = array(
               31536000 => 'year',
               2592000 => 'month',
               604800 => 'week',
               86400 => 'day',
               3600 => 'hour',
               60 => 'minute',
               1 => 'second'
           );

           foreach ($tokens as $unit => $text) {
               if ($time < $unit) {
                   continue;
               }
               $numberOfUnits = floor($time / $unit);
               if(trim($numberOfUnits.$text) == '1second'){
                   return "Just now";
               }else{
                   return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . ' ago';
               }
           }
       }
       else{
           return FALSE;
       }
   }

    /**
     * Get coach ongoing session
     * @return mixed
     */
    public function ongoing_session_coach() {
        $this->CI->db->where('status', 'active');
        $this->CI->db->where('coach_id', $this->CI->auth->userid());
        $this->CI->db->where('date =', date('Y-m-d'));
        $this->CI->db->where('start_time <=', date('H:i:s'));
        $this->CI->db->where('end_time >=', date('H:i:s'));
        $this->CI->db->from('appointments');

        $ongoing = $this->CI->db->count_all_results();
        
        $this->CI->db->where('coach_id', $this->CI->auth->userid());
        $this->CI->db->where('date =', date('Y-m-d'));
        $this->CI->db->where('start_time <=', date('H:i:s'));
        $this->CI->db->where('end_time >=', date('H:i:s'));
        $this->CI->db->from('class_meeting_days');

        $ongoing_class = $this->CI->db->count_all_results();        
        return(@$ongoing + @$ongoing_class);
    }
    
    /**
     * Get coach ongoing session
     * @return mixed
     */
    public function ongoing_session_student() {
        $this->CI->db->where('status', 'active');
        $this->CI->db->where('student_id', $this->CI->auth->userid());
        $this->CI->db->where('date =', date('Y-m-d'));
        $this->CI->db->where('start_time <=', date('H:i:s'));
        $this->CI->db->where('end_time >=', date('H:i:s'));
        $this->CI->db->from('appointments');

        $ongoing = $this->CI->db->count_all_results();
        
        $this->CI->db->from('class_members cm');
        $this->CI->db->join('class_meeting_days cmd', 'cm.class_id = cmd.class_id');
        $this->CI->db->where('cm.student_id', $this->CI->auth->userid());
        $this->CI->db->where('cmd.date =', date('Y-m-d'));
        $this->CI->db->where('cmd.start_time <=', date('H:i:s'));
        $this->CI->db->where('cmd.end_time >=', date('H:i:s'));
        $ongoing_class = $this->CI->db->count_all_results();
        return(@$ongoing + @$ongoing_class);
    }

    /**
     * Get User Role
     * @return mixed
     */
    public function role() {
        return $this->CI->session->userdata("auth_role");
    }


    public function gender(){
        return Array(
            '' => 'Select Gender',
            'Female' => 'Female',
            'Male' => 'Male',
            'Others' => 'Others'
        );
    }
    
    /**
     * Get User Email 
     * @return mixed
     */
    public function user_email() {
        //return $this->CI->auth->userid();
        $user = $this->CI->identity_model->get_identity('user')->select('email')->where('id', $this->CI->auth->userid())->get();
        return(@$user->email);
    }
    
    /**
     * @function check password
     * @return true / false
     */
    public function check_password($email, $password) {

        // Querying user's data from database
        $user = $this->CI->user_model->select('id, email, password, role_id, status')
                ->where('email', $email)
                ->get();

        // Checking existing user
        if (!$user) {
            $this->CI->messages->add('User does not exist', 'danger');
            return FALSE;
        }

        // Checking validity password of user login with database
        if (!$this->CI->phpass->check($password, $user->password)) {
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * @function hashing password
     */
    public function hashing_password($password=''){
        return $this->CI->phpass->hash($password);
    }
    
    /**
     * @function get avatar
     * @return string url avatar profile picture
     */
    public function get_avatar(){
        // Querying user's data from database
        $user = $this->CI->user_profile_model->select('profile_picture')
                ->where('user_id', $this->CI->auth->userid())
                ->get();

        // Checking existing user
        if (!$user) {
            return FALSE;
        }
        
        return $user->profile_picture;
    }
    
    /**
     * @function get name
     * @return string name
     */
    public function get_name(){
        // Querying user's data from database
        $user = $this->CI->user_profile_model->select('fullname')
                ->where('user_id', $this->CI->auth->userid())
                ->get();

        // Checking existing user
        if (!$user) {
            return FALSE;
        }
        
        $name = explode(" ", $user->fullname);
        
        return $name[0];
    }
    
    public function is_password_valid($pwd, &$errors) {
        $errors_init = $errors;

        if (strlen($pwd) < 6) {
            $errors[] = "Password too short!";
        }

        if (!preg_match("#[0-9]+#", $pwd)) {
            $errors[] = "Password must include at least one number!";
        }

        if (!preg_match("#[a-zA-Z]+#", $pwd)) {
            $errors[] = "Password must include at least one letter!";
        }     

        return ($errors == $errors_init);
    }
    
    /**
     * Get User Language
     * @return (string) language
     */
    public function get_lang() {
        $lang = $this->CI->user_model->select('lang')->where(Array('id'=>$this->CI->auth->userid(), 'status'=>'active'))->get();
        return(@$lang->lang);
    }
    
    /**
     * Language
     * @return (string) language
     */
    public function lang($id='', $params='') {
        $this->CI->lang->load('view');
        if($params){
            return vsprintf($this->CI->lang->line($id), $params);
        }
        return sprintf($this->CI->lang->line($id));
    }
}
