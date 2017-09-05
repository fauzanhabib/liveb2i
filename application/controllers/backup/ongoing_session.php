<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ongoing_session extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CCH') {
            $this->messages->add('ERROR');
            redirect('home');
        }
    }

    // Index
    public function index() {
        $this->template->title = "Ongoing Session";
        $data = $this->appointment_model
                ->where('status', 'active')
                ->where('coach_id', $this->auth_manager->userid())
                ->where('date =', date('Y-m-d'))
                ->where('start_time <=', date('H:i:s'))
                ->where('end_time >=', date('H:i:s'))
                ->get();
        if(!$data){
            $this->messages->add('There is no ongoing session', 'danger');
            redirect('account/identity');
        }

        $vars = array(
            'title' => 'Ongoing Session',
            // data student berdasarkan creator nya 'data' => $this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(),$this->auth_manager->userid()),
            'data' => $data,
            'student_name' => $this->identity_model->get_identity('profile')->select('fullname, skype_id')->where('user_id', @$data->student_id)->get(),
        );

        $this->template->content->view('default/contents/ongoing_session/coach/index', $vars);
        $this->template->publish();
    }

}
