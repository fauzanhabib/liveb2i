<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class rate_coaches extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // load models for student partner
        $this->load->model('user_model');
        $this->load->model('identity_model');
        // load models for class
        $this->load->model('class_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('class_member_model');
        $this->load->model('class_schedule_model');
        $this->load->model('class_week_model');
        $this->load->model('coach_rating_model');

        // load model for set meeting time
        $this->load->model('schedule_model');
        $this->load->model('offwork_model');
        $this->load->model('appointment_model');

        // for messaging action and timing
        $this->load->library('pagination');
        $this->load->library('queue');
        $this->load->library('phpass');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD') {
            $this->messages->add('Access Denied');
            redirect('home');
        }
    }

    // Index
    public function index($page='') {
        $offset = 0;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student/rate_coaches/index'), count($this->coach_rating_model->get_rate_coach()), 7, 4);
        $this->template->title = 'Rate Coach';
        $vars = array(
            'data' => $this->coach_rating_model->get_rate_coach(7, $offset),
            'rating' => $this->coach_rating_model->get_average_rate(),
            'pagination' => @$pagination
        );
        
        $this->template->content->view('default/contents/rate_coach/index', $vars);

        //publish template
        $this->template->publish();
    }
    
    public function rate($id = '', $coach_id = ''){
        $this->template->title = 'Rate Coach';
        
        // setting id for updating data
        $this->session->set_userdata("rate_id", $id);
        
        $coach_name = $this->identity_model->get_identity('profile')->select('fullname')->where('user_id', $coach_id)->get();
        $vars = array(
            'form_action' => 'update_rate',
            'coach_name' => $coach_name->fullname,
            'rate_id' => $id,
        );
        $this->template->content->view('default/contents/rate_coach/form', $vars);
        $this->template->publish();
    }
    
    public function update_rate($id = '', $rate='', $description){
        
        // retrive class data
        $data = array(
            'rate' => $rate,
            'description' => $description,
            'status' => 'rated',
        );

        // updating and checking to class table then storing insert_id into $insert_id
        $this->db->trans_begin();
        $coach_rating_id = $this->coach_rating_model->update($id, $data);
        if (!$coach_rating_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            redirect('student/rate_coaches/');
        }
        $this->db->trans_commit();
        
        $this->session->unset_userdata('rate_id');

        $this->messages->add('Coach has been rated', 'success');
        redirect('student/rate_coaches/');
    }

}
