<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class approve_coach_day_off extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('coach_day_off_model');
        $this->load->model('user_model');
        $this->load->model('user_notification_model');
        $this->load->model('identity_model');
        $this->load->model('appointment_model');
        // for messaging action and timing
        $this->load->library('queue');
        $this->load->library('email_structure');
        $this->load->library('send_email');
        $this->load->library('send_sms');
        $this->load->library('schedule_function');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'PRT') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index($page='') {
        $this->template->title = 'Approve Coach Day Off';
        $offset = 0;
        $per_page = 5;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner/approve_coach_day_off/index'), count($this->coach_day_off_model->get_coach_day_off()), $per_page, $uri_segment);
        $vars = array(
            'data' => $this->coach_day_off_model->get_coach_day_off('', $per_page, $offset),
            'pagination' => @$pagination
        );
        $this->template->content->view('default/contents/approve_coach_day_off/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function approve($id = '') {
        //echo($id); exit;
        // Checking ID
        if (!$id) {
            $this->messages->add('Invalid User ID', 'danger');
            redirect('partner/approve_coach_day_off');
        }

        // Storing user data
        $day_off = $this->coach_day_off_model->select('coach_id, start_date, end_date, remark, status')->where('id', $id)->get();
        $data = array(
            'coach_id' => $day_off->coach_id,
            'start_date' => $day_off->start_date,
            'end_date' => $day_off->end_date,
            'remark' => $day_off->remark,
            'status' => 'approved'
        );
        if(!$this->checkSchedule($day_off->coach_id, $day_off->start_date, $day_off->end_date)){
            $this->messages->add('This coach is already booked by a student', 'danger');
            redirect('partner/approve_coach_day_off');
        }
        $coachmail = $this->user_model->select('id, email')->where('id', $day_off->coach_id)->get_all();
        $name = $this->user_profile_model->select('user_id, fullname')->where('user_id', $day_off->coach_id)->get_all();

        // Inserting and checking
        if (!$this->coach_day_off_model->update($id, $data)) {
            $this->messages->add(validation_errors(), 'Invalid ID');
            redirect('partner/approve_coach_day_off');
        }

        $id_to_email_address = $this->user_model->dropdown('id', 'email');
        // Tube name for messaging sending email action
        $tube = 'com.live.email';
        // Email's content to inform partner admin their DynEd Live account
        $data_coach = array(
            'subject' => 'Day Off Approval',
            'email' => $id_to_email_address[$data['coach_id']],
            //'content' => 'Your day off request from '.$data['start_date']. ' until ' .$data['end_date']. ' has been approved.',
        );
        $data_coach['content'] = $this->email_structure->header()
                .$this->email_structure->title('Day Off Approval')
                .$this->email_structure->content('Your day off request from '.$data['start_date']. ' until ' .$data['end_date']. ' has been approved.')
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');
        // $this->queue->push($tube, $data_coach, 'email.send_email');

        //messaging for notification
        $database_tube = 'com.live.database';
        $coach_notification = array(
            'user_id' => $data['coach_id'],
            'description' => 'Your day-off request has been approved.',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );
        // coach's data for reminder messaging
        // IMPORTANT : array index in content must be in mutual with table field in database
        $data_coach = array(
            'table' => 'user_notifications',
            'content' => $coach_notification,
        );
            $this->user_notification_model->insert($coach_notification);
        
        $this->send_email->coach_partner_approve_dayoff($coachmail[0]->email, 'approved', $name[0]->fullname, $day_off->start_date, $day_off->end_date, $day_off->remark);
    

        // messaging inserting data notification
        // $this->queue->push($database_tube, $data_coach, 'database.insert');



        $this->messages->add('Update Succeeded', 'success');
        redirect('partner/approve_coach_day_off');
    }

    public function decline($id = '') {
        //echo($id); exit;
        // Checking ID
        if (!$id) {
            $this->messages->add('Invalid User ID', 'danger');
            redirect('partner/approve_coach_day_off');
        }

        // Storing user data
        $day_off = $this->coach_day_off_model->select('coach_id, start_date, end_date, remark, status')->where('id', $id)->get();
        $data = array(
            'coach_id' => $day_off->coach_id,
            'start_date' => $day_off->start_date,
            'end_date' => $day_off->end_date,
            'remark' => $day_off->remark,
            'status' => 'decline'
        );

        $coachmail = $this->user_model->select('id, email')->where('id', $day_off->coach_id)->get_all();
        $name = $this->user_profile_model->select('user_id, fullname')->where('user_id', $day_off->coach_id)->get_all();
        

        // Inserting and checking
        if (!$this->coach_day_off_model->update($id, $data)) {
            $this->messages->add(validation_errors(), 'Invalid ID');
            redirect('partner/approve_coach_day_off');
        }
        
        $id_to_email_address = $this->user_model->dropdown('id', 'email');
        // Tube name for messaging sending email action
        $tube = 'com.live.email';
        // Email's content to inform partner admin their DynEd Live account
        $data_coach = array(
            'subject' => 'Day Off Cancelled',
            'email' => $id_to_email_address[$data['coach_id']],
            //'content' => 'Your day off request from '.$data['start_date']. ' until ' .$data['end_date']. ' has been declined.',
        );
        $data_coach['content'] = $this->email_structure->header()
                .$this->email_structure->title('Day Off Cancelled')
                .$this->email_structure->content('Your day-off request has been declined.')
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');
        
        // $this->queue->push($tube, $data_coach, 'email.send_email');

        //messaging for notification
        $database_tube = 'com.live.database';
        $coach_notification = array(
            'user_id' => $data['coach_id'],
            'description' => 'Your day off request from '.$data['start_date']. ' until ' .$data['end_date']. ' has been declined.',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );
        // coach's data for reminder messaging
        // IMPORTANT : array index in content must be in mutual with table field in database
        $data_coach = array(
            'table' => 'user_notifications',
            'content' => $coach_notification,
        );

        // messaging inserting data notification
        // $this->queue->push($database_tube, $data_coach, 'database.insert');
            $this->user_notification_model->insert($coach_notification);
         $this->send_email->coach_partner_approve_dayoff($coachmail[0]->email, 'declined', $name[0]->fullname, $day_off->start_date, $day_off->end_date, $day_off->remark);
            


        $this->messages->add('Update Succeeded', 'success');
        redirect('partner/approve_coach_day_off');
    }

    public function edit($id = '', $start_date = '', $end_date = '', $page = '') {
        
        $prt_id = $this->auth_manager->userid();
        
        $offset = 0;
        $per_page = 10;
        $uri_segment = 7;
        
        $coach_data = $this->coach_day_off_model->select('id, coach_id, start_date, end_date, remark')->where('id', $id)->get();
        $coach_id = $coach_data->coach_id;

        $subgroup_data = $this->db->select('user_profiles.fullname as fullname')->from('user_profiles')->where('user_profiles.user_id',$coach_id)->get()->result();
        $fullname = $subgroup_data[0]->fullname;
        
            $form_action = "search/one_to_one";
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner/approve_coach_day_off/edit/'.@$id.'/'.@$start_date.'/'.@$end_date), count($this->appointment_model->get_appointment_for_upcoming_session('coach_id', $start_date, $end_date, $coach_id)), $per_page, $uri_segment);
            $data = $this->appointment_model->get_appointment_for_upcoming_session('coach_id', $start_date, $end_date, $coach_id, $per_page, $offset);

            if ($data) {
            foreach ($data as $d) {
                $gmt_coach = $this->identity_model->new_get_gmt($coach_id);
                    if(@!$gmt_coach){
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($prt_id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                    }else{
                    $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($coach_id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                    }
                
                //$data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($prt_id)[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);   
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
                }
            }
        

        
        $vars = array(
            'form_action' => 'search',
            'role' => 'Student',
            'data' => @$data,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'pagination' => @$pagination,
            'coach_id' => $coach_id,
            'prt_id' => $prt_id,
            'fullname' => $fullname
        );
        // $this->send_sms->testing();
        $this->template->content->view('default/contents/day_off/partner_form', $vars);
        $this->template->publish();
    }

    public function edit2($id = '') {
        // setting id for updating data
        //$this->session->set_userdata("day_off_id", $id);

        $data = $this->coach_day_off_model->select('id, coach_id, start_date, end_date, remark')->where('id', $id)->get();
        $coach_id = $data->coach_id;

        $subgroup_data = $this->db->select('user_profiles.subgroup_id as subgroup_id, user_profiles.coach_type_id as coach_type_id')->from('user_profiles')->where('user_profiles.user_id',$coach_id)->get()->result();
        $subgroup_id = $subgroup_data[0]->subgroup_id;
        $coach_type_id = $subgroup_data[0]->coach_type_id;

        $coach_data = $this->identity_model->get_coach_identity('', '', '', $this->auth_manager->partner_id());
        $selected_coach = $this->identity_model->get_coach_identity($coach_id, '', '', '', '', '', '', '', '', $subgroup_id);

        $this->template->title = 'Edit Day Off';
        $vars = array(
            'form_action' => 'update',
            'data' => $data,
            'coaches' => $this->identity_model->where('partner_id', $this->auth_manager->partner_id())->where('coach_type_id', $coach_type_id)->dropdown('user_id', 'fullname'),
            'selected' => $selected_coach,
            'coach_id' => $coach_id,
            'dayoff_id' => $id
        );
        // echo "<pre>";
        // print_r($vars);
        // exit();
        $this->template->content->view('default/contents/day_off/partner_form', $vars);
        $this->template->publish();
    }

    public function update($id = '', $coach_id = '') {
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('coach/day_off');
        }

        $user_id = $this->input->post('user_id');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $check = $this->db->select('id')
                          ->from('appointments')
                          ->where('date >=',$start_date)
                          ->where('date <=',$end_date)
                          ->where('coach_id',$coach_id)
                          ->get()
                          ->result();

        foreach($check as $c){
            $appointments = array(
            'coach_id' => $user_id,
        );

            $this->db->where('id', $c->id);
            $this->db->update('appointments', $appointments); 
        }

        // inserting user data
        // $day_off = array(
        //     'status' => 'pending',
        // );

        // $this->db->where('id', $id);
        // $this->db->update('coach_dayoffs', $day_off); 
        

    }

    function checkSchedule($id_coach = '', $start_date = '', $end_date = ''){
        $check = $this->db->select('id')
                          ->from('appointments')
                          ->where('date >=',$start_date)
                          ->where('date <=',$end_date)
                          ->where('coach_id',$id_coach)
                          ->get();
        
        if($check->num_rows() > 0){
            return false;
        } else {
            return true;
        }
    }

}
