<?php

/**
 * Class    : Histories.php
 * Author   : Ponel Panjaitan (ponel@pistarlabs.co.id)
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Coach_vrm extends MY_Site_Controller {
    
    // Constructor
    public function __construct() {
        parent::__construct();
        
        // Loading models
        $this->load->model('appointment_model');
        $this->load->model('appointment_history_model');
        $this->load->model('class_member_model');
        $this->load->model('identity_model');
        $this->load->library('call2');

        // Checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'ADM') {
            $this->messages->add('ERROR');
            redirect('home');
        }
    }
    
    public function index(){
        $this->template->title = 'Coach Session';
        $date_from = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-1 month"));
        $histories = $this->appointment_history_model->get_session_histories('coach_id', $date_from, date('Y-m-d'), 1);
        
        $vars = array(
            'form_action' => 'search',
            'histories' => $histories,
            'coach_id' => $this->auth_manager->userid(),
            'user' => 'coach',
            'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month"))
        );
        
        $this->template->content->view('default/contents/vrm/admin/index', $vars);
        $this->template->publish();
    }
    
    
    public function student_vrm($student_id = ''){
        // checking if student has dyned pro
        $data_dyned_pro = $this->identity_model->get_student_identity($student_id);
        
        if($data_dyned_pro[0]->dyned_pro_id && $data_dyned_pro[0]->server_dyned_pro){
               //TODO
               //Get id server and email from Database
               //getting dyned pro id and server id
               //$data_student = $this->identity_model->get_identity('profile')->where('user_id', $this->auth_manager->userid())->select('dyned_pro_id, server_dyned_pro')->get();
               $this->call2->init($data_dyned_pro[0]->server_dyned_pro, $data_dyned_pro[0]->dyned_pro_id);
               $vars = array(
                    'student_fullname' => $data_dyned_pro[0]->fullname,
                    'student_id' => $data_dyned_pro[0]->id,
                    'student_vrm' => $this->call2->getDataJson()
                );

                $this->template->content->view('default/contents/vrm/admin/detail_student', $vars);
                $this->template->publish();   
            
        }
        else{
            $vars = array(
                'student_fullname' => $data_dyned_pro[0]->fullname,
            );
            $this->template->content->view('default/contents/vrm/admin/detail_student', $vars);
            $this->template->publish();  
        }
    }
}
