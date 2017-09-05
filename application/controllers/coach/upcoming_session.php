<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class upcoming_session extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('webex_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('partner_model');
        $this->load->model('class_member_model');
        $this->load->model('identity_model');
        
        
        $this->load->library('schedule_function');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CCH') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index($page = '') {
        $this->template->title = "Upcoming Session";
        $offset = 0;
        $per_page = 5;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('coach/upcoming_session/index/'), count($this->appointment_model->get_appointment_for_upcoming_session('coach_id', '', '', $this->auth_manager->userid())), $per_page, $uri_segment);
        $data = $this->appointment_model->get_appointment_for_upcoming_session('coach_id', '', '', $this->auth_manager->userid(), $per_page, $offset);
        
        if ($data) {
            foreach ($data as $d) {
                // $gmt_student = $this->identity_model->new_get_gmt($d->student_id);
                //     if(@!$gmt_student){
                //     $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                //     }else{
                //     $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($d->student_id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                //     }
                $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);    
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
            }
        }

        $data_class_upcoming = $this->class_meeting_day_model->get_appointment_for_upcoming_session('', '', $this->auth_manager->userid());
        
        if ($data_class_upcoming) {
            foreach ($data_class_upcoming as $data_class) {
                $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($data_class->date), $data_class->start_time, $data_class->end_time);
                $data_class->date = date('Y-m-d', $data_schedule['date']);
                $data_class->start_time = $data_schedule['start_time'];
                $data_class->end_time = $data_schedule['end_time'];
            }
        }

        $vars = array(
            'title' => 'Upcoming Session',
            'role' => 'Student',
            'data' => $data,
            'data_class' => $data_class_upcoming,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'id_webex' => $this->webex_model->select('id')->where('status', 'SCHE')->get_all(),
            'pagination' => @$pagination
        );

        $this->template->content->view('default/contents/coach/upcoming_session/index', $vars);
        $this->template->publish();
    }
    
    public function search($page = '') {
        $this->template->title = "Upcoming Session";

        if($this->input->post('date_from') && $this->input->post('date_to')){
            $this->session->set_userdata('date_from', $this->input->post('date_from'));
            $this->session->set_userdata('date_to', $this->input->post('date_to'));
        }
        
        $rules = array(
            array('field'=>'date_from', 'label' => 'Start Date', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'date_to', 'label' => 'End Date', 'rules'=>'trim|required|xss_clean')
        );
        if(($this->input->post('__submit'))){
            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                    redirect('coach/upcoming_session');
            }
        }

        $date_from = ($this->input->post('date_from') ? $this->input->post('date_from') : $this->session->userdata('date_from'));
        $date_to = ($this->input->post('date_to') ? $this->input->post('date_to') : $this->session->userdata('date_to'));
        
        $offset = 0;
        $per_page = 5;
        $uri_segment = 4;

        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('coach/upcoming_session/search/'), count($this->appointment_model->get_appointment_for_upcoming_session('coach_id', $date_from, $date_to, $this->auth_manager->userid())), $per_page, $uri_segment);
        $data = $this->appointment_model->get_appointment_for_upcoming_session('coach_id', $this->input->post('date_from'), $this->input->post('date_to'), $this->auth_manager->userid(), $per_page, $offset);
        if ($data) {
            foreach ($data as $d) {
                // $gmt_student = $this->identity_model->new_get_gmt($d->student_id);
                //     if(@!$gmt_student){
                //     $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                //     }else{
                //     $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($d->student_id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                //     }

                $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);    
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
            }
        }

        $data_class_upcoming = $this->class_meeting_day_model->get_appointment_for_upcoming_session($this->input->post('date_from'), $this->input->post('date_to'), $this->auth_manager->userid());
        if ($data_class_upcoming) {
            foreach ($data_class_upcoming as $data_class) {
                $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($data_class->date), $data_class->start_time, $data_class->end_time);
                $data_class->date = date('Y-m-d', $data_schedule['date']);
                $data_class->start_time = $data_schedule['start_time'];
                $data_class->end_time = $data_schedule['end_time'];
            }
        }

        $vars = array(
            'title' => 'Upcoming Session',
            'role' => 'Student',
            'data' => $data,
            'data_class' => $data_class_upcoming,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'id_webex' => $this->webex_model->select('id')->where('status', 'SCHE')->get_all(),
            'pagination' => @$pagination
        );

        $this->template->content->view('default/contents/coach/upcoming_session/index', $vars);
        $this->template->publish();
    }

    public function student_detail($student_id = '',$partner_id='') {
        $this->template->title = 'Student Detail';
        if(!$student_id){
            $this->messages->add('Invalid Action', 'warning');
            redirect('coach/upcoming_session');
        }
        $this->session->set_userdata('student_id', $student_id);
        $data = $this->identity_model->get_student_identity($student_id, '', '');



        // $partner = $this->db->select('*')->from('partners')->where('id',$partner_id)->get()->result();
        $partner = $this->partner_model->select('*')->where('id',$this->auth_manager->partner_id())->get();

        
        if(!$data){
            $this->messages->add('Invalid ID', 'warning');
            redirect('coach/upcoming_session');
        }
        $vars = array(
            'student' => $data,
            'partner_id' => $this->auth_manager->partner_id(),
            'partner' => $partner
        );
        $this->template->content->view('default/contents/admin/student/detail', $vars);
        $this->template->publish();
    }

}