<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class coach_upcoming_session extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('webex_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('class_member_model');

        $this->load->library('schedule_function');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CAM') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = "Upcoming Session";
        $data = $this->appointment_model->get_appointment_for_upcoming_session('coach_id', '', '', $this->session->userdata('coach_id'));
        $upcoming = array();
        if ($data) {
            foreach ($data as $d) {
                if ($d->date == date('Y-m-d')) {
                    if ($d->start_time >= date('H:i:s')) {
                        $upcoming [] = $d;
                    }
                } else {
                    $upcoming [] = $d;
                }
                $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
            }
        }

        $data_class_upcoming = $this->class_meeting_day_model->get_appointment_for_upcoming_session();
        $class_upcoming = '';
        if ($data_class_upcoming) {
            foreach ($data_class_upcoming as $data_class) {
                if ($data_class->date == date('Y-m-d')) {
                    if ($data_class->start_time > date('H:i:s')) {
                        $class_upcoming[] = $data_class;
                    }
                } else {
                    $class_upcoming [] = $data_class;
                }
                $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($data_class->date), $data_class->start_time, $data_class->end_time);
                $data_class->date = date('Y-m-d', $data_schedule['date']);
                $data_class->start_time = $data_schedule['start_time'];
                $data_class->end_time = $data_schedule['end_time'];
            }
        }

        $vars = array(
            'title' => 'Upcoming Session',
            'role' => 'Student',
            'data' => $upcoming,
            'data_class' => $class_upcoming,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'id_webex' => $this->webex_model->select('id')->where('status', 'SCHE')->get_all()
        );

        $this->template->content->view('default/contents/partner_monitor/coach_upcoming_session/index', $vars);
        $this->template->publish();
    }

    // public function one_to_one_session($page='', $id = '') {
    //     $this->template->title = 'Upcoming Session One to One';
    //     $offset = 0;
    //     $per_page = 5;
    //     $uri_segment = 4;
    //     $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner_monitor/coach_upcoming_session/one_to_one_session'), count($this->appointment_model->get_appointment_for_upcoming_session('coach_id', '', '', $id)), $per_page, $uri_segment);
    //     $data = $this->appointment_model->get_appointment_for_upcoming_session('coach_id', '', '', $id, $per_page, $offset);
    //     if ($data) {
    //         foreach ($data as $d) {
                
    //             $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
    //             $d->date = date('Y-m-d', $data_schedule['date']);
    //             $d->start_time = $data_schedule['start_time'];
    //             $d->end_time = $data_schedule['end_time'];
    //         }
    //     }
    //     $vars = array(
    //         'title' => 'Upcoming Session',
    //         'role' => 'Student',
    //         'data' => $data,
    //         'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
    //         'id_webex' => $this->webex_model->select('id')->where('status', 'SCHE')->get_all(),
    //         'pagination' => @$pagination,
    //         'coach_id' => $id
    //     );
    //     // echo "<pre>";
    //     // print_r($vars['data']);
    //     // exit;
    //     // die;

    //     $this->template->content->view('default/contents/partner_monitor/coach_upcoming_session/one_to_one_session', $vars);
    //     $this->template->publish();
    // }

    public function one_to_one_session($id='', $page='') {
        $this->template->title = 'Upcoming Session One to One';
        $date_from = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-1 month"));
        $prt_id = $this->auth_manager->userid();
        $offset = 0;
        $per_page = 3;
        $uri_segment = 5;

        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner_monitor/coach_upcoming_session/one_to_one_session/'.@$id), count($this->appointment_model->get_appointment_for_upcoming_session('coach_id', '', '', $id)), $per_page, $uri_segment);
        // $data = $this->appointment_model->get_appointment_for_upcoming_session('coach_id', $date_from, date('Y-m-d'), $id, $per_page, $offset);
        $data = $this->appointment_model->get_appointment_for_upcoming_session('coach_id', '', '', $id, $per_page, $offset);
    
        if ($data) {
            foreach ($data as $d) {
                $gmt_coach = $this->identity_model->new_get_gmt($id);
                    if(@!$gmt_coach){
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($prt_id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                    }else{
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                    }
                    
                //$data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($prt_id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);   
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
            }
     }
        // if ($data) {
        //     foreach ($data as $d) {
                
        //         $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->get_gmt($prt_id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
        //         $d->date = date('Y-m-d', $data_schedule['date']);
        //         $d->start_time = $data_schedule['start_time'];
        //         $d->end_time = $data_schedule['end_time'];
        //     }
        // }
        $vars = array(
            'title' => 'Upcoming Session',
            'role' => 'Student',
            'data' => $data,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'id_webex' => $this->webex_model->select('id')->where('status', 'SCHE')->get_all(),
            'pagination' => @$pagination,
            'coach_id' => $id,
            'prt_id' => $prt_id,
        );
        // echo "<pre>";
        // print_r($vars);
        // exit;
        // die;

        $this->template->content->view('default/contents/partner_monitor/coach_upcoming_session/one_to_one_session', $vars);
        $this->template->publish();
    }

    public function class_session($page='') {
        $this->template->title = 'Upcoming Session Class';
        $offset = 0;
        $per_page = 5;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner_monitor/coach_upcoming_session/class_session'), count($this->class_meeting_day_model->get_appointment_for_upcoming_session('', '', $this->session->userdata('coach_id'))), $per_page, $uri_segment);
        $data_class_upcoming = $this->class_meeting_day_model->get_appointment_for_upcoming_session('', '', $this->session->userdata('coach_id'), $per_page, $offset);
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
            'data_class' => $data_class_upcoming,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'id_webex' => $this->webex_model->select('id')->where('status', 'SCHE')->get_all(),
            'pagination' => @$pagination
        );

        $this->template->content->view('default/contents/partner_monitor/coach_upcoming_session/class_session', $vars);
        $this->template->publish();
    }

    // public function search($session = '', $page='') {
    //     $this->template->title = "Upcoming Session";
        
    //     if($this->input->post('date_from') && $this->input->post('date_to')){
    //         $this->session->set_userdata('date_from', $this->input->post('date_from'));
    //         $this->session->set_userdata('date_to', $this->input->post('date_to'));
    //     }
        
    //     $rules = array(
    //         array('field'=>'date_from', 'label' => 'Date From', 'rules'=>'trim|required|xss_clean'),
    //         array('field'=>'date_to', 'label' => 'Date To', 'rules'=>'trim|required|xss_clean')
    //     );

    //     if (!$this->common_function->run_validation($rules)) {
    //         $this->messages->add(validation_errors(), 'warning');
    //         if($session == 'one_to_one'){
    //             redirect('partner_monitor/coach_upcoming_session/one_to_one_session');
    //         }elseif($session == 'class'){
    //             redirect('partner_monitor/coach_upcoming_session/class_session');
    //         }
    //     }
        
    //     $offset = 0;
    //     $per_page = 5;
    //     $uri_segment = 4;
        
    //     if ($session == 'one_to_one') {
    //         $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner_monitor/coach_upcoming_session/search/one_to_one'), count($this->appointment_model->get_appointment_for_upcoming_session('coach_id', $this->input->post('date_from'), $this->input->post('date_to'), $this->session->userdata('coach_id'))), $per_page, $uri_segment);
    //         $data = $this->appointment_model->get_appointment_for_upcoming_session('coach_id', $this->input->post('date_from'), $this->input->post('date_to'), $this->session->userdata('coach_id'), $per_page, $offset);
    //         if ($data) {
    //             foreach ($data as $d) {
    //                 $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
    //                 $d->date = date('Y-m-d', $data_schedule['date']);
    //                 $d->start_time = $data_schedule['start_time'];
    //                 $d->end_time = $data_schedule['end_time'];
    //             }
    //         }
    //     } elseif ($session == 'class') {
    //         $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner_monitor/coach_upcoming_session/search/class'), count($this->class_meeting_day_model->get_appointment_for_upcoming_session($this->input->post('date_from'), $this->input->post('date_to'), $this->session->userdata('coach_id'))), $per_page, $uri_segment);
    //         $data_class_upcoming = $this->class_meeting_day_model->get_appointment_for_upcoming_session($this->input->post('date_from'), $this->input->post('date_to'), $this->session->userdata('coach_id'), $per_page, $offset);
    //         if ($data_class_upcoming) {
    //             foreach ($data_class_upcoming as $data_class) {
    //                 $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($data_class->date), $data_class->start_time, $data_class->end_time);
    //                 $data_class->date = date('Y-m-d', $data_schedule['date']);
    //                 $data_class->start_time = $data_schedule['start_time'];
    //                 $data_class->end_time = $data_schedule['end_time'];
    //             }
    //         }
    //     }else{
    //         $this->messages->add('Invalid ID', 'warning');
    //         redirect(site_url('partner_monitor/coach_upcoming_session/one_to_one_session'));
    //     }
        
    //     $vars = array(
    //         'title' => 'Upcoming Session',
    //         'role' => 'Student',
    //         'data' => @$data,
    //         'data_class' => @$data_class_upcoming,
    //         'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
    //         'id_webex' => $this->webex_model->select('id')->where('status', 'SCHE')->get_all(),
    //         'pagination' => @$pagination
    //     );

    //     $this->template->content->view('default/contents/partner_monitor/coach_upcoming_session/' . (($session == 'one_to_one') ? 'one_to_one_session' : 'class_session'), $vars);
    //     $this->template->publish();
    // }

    public function search($session='', $id='', $page='') {
        $this->template->title = "Upcoming Session";
        $prt_id = $this->auth_manager->userid();
        if($this->input->post('date_from') && $this->input->post('date_to')){
            $this->session->set_userdata('date_from', $this->input->post('date_from'));
            $this->session->set_userdata('date_to', $this->input->post('date_to'));
        }
        
        $rules = array(
            array('field'=>'date_from', 'label' => 'Date From', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'date_to', 'label' => 'Date To', 'rules'=>'trim|required|xss_clean')
        );

        if(($this->input->post('__submit')))
    {
        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            if($session == 'one_to_one'){
                redirect('partner_monitor/coach_upcoming_session/one_to_one_session/'.@$id);
            }elseif($session == 'class'){
                redirect('partner_monitor/coach_upcoming_session/class_session');
            }
        }
    }
        
        $offset = 0;
        $per_page = 5;
        $uri_segment = 6;
        
        if ($session == 'one_to_one') {
            $form_action = "search/one_to_one";
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner_monitor/coach_upcoming_session/search/one_to_one/'.@$id), count($this->appointment_model->get_appointment_for_upcoming_session('coach_id', $this->input->post('date_from'), $this->input->post('date_to'), $id)), $per_page, $uri_segment);
            $data = $this->appointment_model->get_appointment_for_upcoming_session('coach_id', $this->input->post('date_from'), $this->input->post('date_to'), $id, $per_page, $offset);
            if ($data) {
            foreach ($data as $d) {
                $gmt_coach = $this->identity_model->new_get_gmt($id);
                    if(@!$gmt_coach){
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($prt_id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                    }else{
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                    }
                
                //$data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($prt_id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);   
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
                }
            }
        } elseif ($session == 'class') {
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner_monitor/coach_upcoming_session/search/class'), count($this->class_meeting_day_model->get_appointment_for_upcoming_session($this->input->post('date_from'), $this->input->post('date_to'), $this->session->userdata('coach_id'))), $per_page, $uri_segment);
            $data_class_upcoming = $this->class_meeting_day_model->get_appointment_for_upcoming_session($this->input->post('date_from'), $this->input->post('date_to'), $this->session->userdata('coach_id'), $per_page, $offset);
            if ($data_class_upcoming) {
                foreach ($data_class_upcoming as $data_class) {
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($data_class->date), $data_class->start_time, $data_class->end_time);
                    $data_class->date = date('Y-m-d', $data_schedule['date']);
                    $data_class->start_time = $data_schedule['start_time'];
                    $data_class->end_time = $data_schedule['end_time'];
                }
            }
        }else{
            $this->messages->add('Invalid ID', 'warning');
            redirect(site_url('partner_monitor/coach_upcoming_session/one_to_one_session'));
        }

        
        $vars = array(
            'form_action' => 'search',
            'title' => 'Upcoming Session',
            'role' => 'Student',
            'data' => @$data,
            'data_class' => @$data_class_upcoming,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'id_webex' => $this->webex_model->select('id')->where('status', 'SCHE')->get_all(),
            'pagination' => @$pagination,
            'coach_id' => $id,
            'prt_id' => $prt_id
        );
        // echo "<pre>";
        // print_r($vars);
        // exit;
        // die;
        if($session == 'one_to_one'){
            $this->template->content->view('default/contents/partner_monitor/coach_upcoming_session/one_to_one_session', $vars);
        }elseif($session == 'class'){
            $this->template->content->view('default/contents/partner_monitor/coach_upcoming_session/class_session', $vars);
        }
        //$this->template->content->view('default/contents/partner_monitor/coach_upcoming_session/' . (($session == 'one_to_one') ? 'one_to_one_session' : 'class_session'), $vars);
        $this->template->publish();
    }

    public function student_detail($student_id = '') {
        $this->template->title = 'Student Detail';
        $vars = array(
            'data' => $this->identity_model->get_student_identity($student_id, '', $this->auth_manager->partner_id()),
        );
        $this->template->content->view('default/contents/upcoming_session/student_detail/index', $vars);
        $this->template->publish();
    }

}
