<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class upcoming_session extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD') {
            $this->messages->add('ERROR');
            redirect('home');
        }
    }

    // Index
    public function index() {
        $this->template->title = "Upcoming Session";
        $data = $this->appointment_model
                ->where('status', 'active')
                ->where('student_id', $this->auth_manager->userid())
                ->where('date >=', date('Y-m-d'))
                //->where('start_time >=', date('H:i:s'))
                ->order_by('date', 'asc')->order_by('start_time', 'asc')
                ->get_all();

        $upcoming = array();
        foreach ($data as $d) {
            if ($d->date == date('Y-m-d')) {
                if ($d->start_time >= date('H:i:s')) {
                    $upcoming [] = $d;
                }
            } else {
                $upcoming [] = $d;
            }
        }

        $vars = array(
            'title' => 'Upcoming Session',
            'role' => 'Coach',
            // data student berdasarkan creator nya 'data' => $this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(),$this->auth_manager->userid()),
            'data' => $upcoming,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
        );

        $this->template->content->view('default/contents/upcoming_session/index', $vars);
        $this->template->publish();
    }

    public function search() {
        $this->template->title = "Upcoming Session";
        $data = $this->appointment_model
                ->where('status', 'active')
                ->where('student_id', $this->auth_manager->userid())
                ->where('date >=', $this->input->post('date_from'))
                ->where('date <=', $this->input->post('date_to'))
                //->where('start_time >=', date('H:i:s'))
                ->order_by('date', 'asc')->order_by('start_time', 'asc')
                ->get_all();

        $upcoming = array();
        foreach ($data as $d) {
            if ($d->date == date('Y-m-d')) {
                if ($d->start_time >= date('H:i:s')) {
                    $upcoming [] = $d;
                }
            } else {
                $upcoming [] = $d;
            }
        }

        $vars = array(
            'title' => 'Upcoming Session',
            'role' => 'Coach',
            'start_date' => $this->input->post('date_from'),
            'end_date' => $this->input->post('date_to'),
            // data student berdasarkan creator nya 'data' => $this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(),$this->auth_manager->userid()),
            'data' => $upcoming,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
        );

        $this->template->content->view('default/contents/upcoming_session/index', $vars);
        $this->template->publish();
    }

    public function coach_detail($coach_id = '') {
        $this->template->title = 'Detail Coach';
        $vars = array(
            'data' => $this->identity_model->get_coach_identity($coach_id, '', '', $this->auth_manager->partner_id()),
        );
        $this->template->content->view('default/contents/upcoming_session/student_detail/index', $vars);
        $this->template->publish();
    }

}
