<?php

/**
 * Class    : Histories.php
 * Author   : Ponel Panjaitan (ponel@pistarlabs.co.id)
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Vrm extends MY_Site_Controller {
    
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
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CAM') {
            $this->messages->add('ERROR');
            redirect('home');
        }
    }
    
    public function single_student($student_id = ''){
        $this->template->title = 'Student Progress Report';
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

                $this->template->content->view('default/contents/vrm/student/index', $vars);
                $this->template->publish();   
            
        }
        else{
            $this->messages->add($data_dyned_pro[0]->fullname. ' has not connected to DynEd Pro yet', 'warning');
            redirect('partner_monitor/coach_upcoming_session/one_to_one_session/');
        }
    }
    public function multiple_student($class_id){
        $students_data = $this->class_member_model->select("student_id")->where("class_id",$class_id)->get_all();

        $students_identity = array ();
        $students_id = array();
        foreach ($students_data as $value) {
            $my_identity = $this->identity_model->get_identity('profile')->where('user_id',$value->student_id)->get();
            array_push($students_identity, $my_identity);
            array_push($students_id, $my_identity->user_id);
        }
    
        $vars = array(
            'students_identity' => $students_identity,
            'students_id'   => $students_id,
            'class_id' => $class_id
        );
        
        $this->template->content->view('default/contents/vrm/coach_partner_monitor/index', $vars);
        $this->template->publish();
    }
}
