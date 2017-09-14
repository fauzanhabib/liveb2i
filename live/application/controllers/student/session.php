<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class session extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index($coach_id = '') {
        $this->messages->add('Invalid Action', 'warning');
        redirect('student/ongoing_session');
    }
    // Index
    public function coach_detail($coach_id = '') {
        if(!$coach_id){
            $this->messages->add('Invalid Action', 'warning');
            redirect('student/ongoing_session');
        }
        $this->template->title = 'Coach Detail';
        $data = $this->identity_model->get_coach_identity($coach_id, '', '', $this->auth_manager->partner_id());
        if(!$data){
            $this->messages->add('Invalid Action', 'warning');
            redirect('student/ongoing_session');
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
            'data' => $data,
            'user_tz' => $user_tz
        );
        $this->template->content->view('default/contents/student/session/coach_detail', $vars);
        $this->template->publish();
    }

}
