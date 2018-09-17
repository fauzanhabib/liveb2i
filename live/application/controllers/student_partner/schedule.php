<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Author   : Ponel Panjaitan (ponel@pistarlabs.co.id)
 */
class schedule extends MY_Site_Controller {

    var $student_partner = null;
    var $coaches = null;
    var $status = null;
    var $day_index = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    
    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('appointment_reschedule_model');
        $this->load->model('user_profile_model');
        $this->load->model('schedule_model');
        $this->load->model('offwork_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('coach_day_off_model');
        $this->load->model('coach_rating_model');
        $this->load->model('coach_token_cost_model');
        $this->load->model('token_histories_model');
        $this->load->model('webex_host_model');
        $this->load->model('class_member_model');
        $this->load->model('partner_setting_model');
        $this->load->model('subgroup_model');
        $this->load->library('queue');
        $this->load->library('schedule_function');
        $this->load->library('webex_function');
        $this->load->library('email_structure');

        $this->student_partner = $this->user_profile_model->select('partner_id')->where('user_id', $this->auth_manager->userid())->get();
        $this->coaches = $this->user_profile_model->get_coaches($this->student_partner->partner_id);
        $this->status = array('active' => 'active', 'pending' => 'pending', 'reschedule' => 'reschedule', 'cancel' => 'cancel');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'SPR') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index($subgroup_id = '',$page='') {
        $this->template->title = 'Create Session';
        
        $offset = 0;
        $per_page = 8;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/schedule/index/'.$subgroup_id), count($this->user_profile_model->get_students($this->student_partner->partner_id,$subgroup_id)), $per_page, $uri_segment);
        
        $vars = array(
            'user_profiles' => $this->user_profile_model->get_students($this->student_partner->partner_id, $subgroup_id, $per_page, $offset),
            'pagination'=> @$pagination
        );
        $this->template->content->view('default/contents/student_partner/schedule/index', $vars);
        $this->template->publish();
    }
    
    
    // Test
    public function test() {
        $this->template->title = 'test';
        $this->template->content->view('default/contents/student_partner/schedule/test');
        $this->template->publish();
    }

    public function subgroup(){
        $this->template->title = "Subgroup";

        $partner_id = $this->auth_manager->partner_id();
        // =================
        // get sub group by partner id
        $subgroup = $this->subgroup_model->select('*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id')->where('subgroup.partner_id',$partner_id)->where('subgroup.type','student')->group_by('subgroup.id')->get_all();

        // echo "<pre>";
        // print_r($subgroup);
        // exit();
        $vars = [
            'subgroup' => $subgroup
        ];

        $this->template->content->view('default/contents/student_partner/schedule/subgroup/index', $vars);
        $this->template->publish();

    }

    // manage
    public function manage($student_id) {
        $this->template->title = 'Manage Schedule';
        $appointment = $this->appointment_model->get_appointment_for_upcoming_session('student_id', '', '', $student_id);
        foreach($appointment as $d){
            $data_schedule = $this->convertBookSchedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
            $d->date = date('Y-m-d', $data_schedule['date']);
            $d->start_time = $data_schedule['start_time'];
            $d->end_time = $data_schedule['end_time'];
        }
        $user = $this->user_profile_model->select('fullname')->where('user_id', $student_id)->get();
        $vars = array(
            'appointments' => $appointment,
            'student_id' => $student_id,
            'user' => $user 
        );
        $this->template->content->view('default/contents/student_partner/schedule/manage', $vars);
        $this->template->publish();
    }
    
    private function convertBookSchedule($minutes = '', $date = '', $start_time = '', $end_time = ''){
        // variable to get schedule out of date
        if($minutes > 0){
            if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)))) > strtotime($end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00'){
                $date = $date;
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))));
            }
            else if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)))) < strtotime($start_time)){
                $date = strtotime('+ 1days'.date('Y-m-d',$date));
                //$tomorrow = date('m-d-Y',strtotime($date . "+1 days"));
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)));
                
//                $date2 = strtotime('+ 1days'.date('Y-m-d',$date));
//                //$tomorrow = date('m-d-Y',strtotime($date . "+1 days"));
//                $start_time2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
//                $end_time2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)));
            }
        }
        else if($minutes < 0){
            if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)))) < strtotime($start_time)){
                $date = $date;
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)));
            }
            else if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)))) > strtotime($end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00'){
                $date = strtotime('- 1days'.date('Y-m-d',$date));
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))));
                
//                $date2 = strtotime('- 1days'.date('Y-m-d',$date));
//                $start_time2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
//                $end_time2 = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))));
            }
        }
        
        return array(
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
//            'date2' => @$date2,
//            'start_time2' => @$start_time2,
//            'end_time2' => @$end_time2,
        );
    }

    // Create Schedule
    public function create($student_id='') {

        /*$coaches_arr = null;
        for ($i = 0; $i < count($this->coaches); $i++) {
            $coaches_arr[$this->coaches[$i]->id] = $this->coaches[$i]->fullname;
        }
        
        $date = array_keys($this->date_for_one_year($this->coaches[0]->id));
        
        $vars = array(
            'coaches' => $coaches_arr,
            'status' => $this->status,
            'dates' => $this->date_for_one_year($this->coaches[0]->id),
            'availabilities' => $this->availability('', $this->coaches[0]->id, '2015-06-19'),
            'student_id' => $student_id,
            'form_action' => 'add'
        );
        
        $this->template->content->view('default/contents/student_partner/schedule/form', $vars);
        $this->template->publish();*/
        
        if(!$this->identity_model->get_student_identity($student_id)){
            $this->messages->add('Invalid Student', 'warning');
            redirect('student_partner/schedule');
        }
        
        $this->template->title = 'Find Coach';
        
        $coaches = $this->identity_model->get_coach_identity();
        
        $vars = array(
            'coaches' => $coaches,
            'student_id' => $student_id,
            'rating' => $this->coach_rating_model->get_average_rate()
        );

        $this->template->content->view('default/contents/student_partner/schedule/list_coach', $vars);
        $this->template->publish();
    }

    public function add($student_id) {
        if(!$student_id){
            $this->messages->add('Invalid ID', 'warning');
            redirect('student_partner/schedule');
        }
        $time = explode('-', trim($this->input->post('time')));        
        $schedule = $this->schedule_model->select('id')->where(array('user_id' => $this->input->post('coach_id'), 'day' => strtolower(date('l', strtotime($this->input->post('date'))))))->get();        
        $vars = array(
            'student_id' => $student_id,
            'coach_id' => $this->input->post('coach_id'),
            'date' => $this->input->post('date'),
            'schedule_id' => $schedule->id,
            'start_time' => $time[0],
            'end_time' => $time[1],
            'status' => 'active'
        );
        
        if (!$this->appointment_model->insert($vars)) {
            $this->messages->add(validation_errors(), 'warning');
            $this->add($student_id);
            return;
        }

        $student_email = $this->user_model->select('email')->where('id', $student_id)->get();
        $coach_email = $this->user_model->select('email')->where('id', $this->input->post('coach_id'))->get();
        
        $student_name = $this->user_profile_model->select('fullname')->where('user_id', $student_id)->get();
        $coach_name = $this->user_profile_model->select('fullname')->where('user_id', $this->input->post('coach_id'))->get();
        
        // Tube name for messaging action
        $tube = 'com.live.email';
        // Email's content that will be send to student_partner to inform that the student has been approved 
        $content_student_email = array(
            'subject' => 'New Appointment',
            'email' => $student_email->email,
            //'content' => 'You have new appointment with ' . $coach_name->fullname. 'at '.$time[0]. ' until '.$time[1],
        );
        $content_student_email['content'] = $this->email_structure->header()
                .$this->email_structure->title('New Appointment')
                .$this->email_structure->content('You have new appointment with ' . $coach_name->fullname. 'at '.$time[0]. ' until '.$time[1])
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');
        // Email's content to inform students that their account has been activated
        $content_coach_email = array(
            'subject' => 'New Appointment',
            'email' => $coach_email->email,
            //'content' => 'You have new appointment with ' . $student_name->fullname. 'at '.$time[0]. ' until '.$time[1],
        );
        $content_coach_email['content'] = $this->email_structure->header()
                .$this->email_structure->title('New Appointment')
                .$this->email_structure->content('You have new appointment with ' . $student_name->fullname. 'at '.$time[0]. ' until '.$time[1])
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');
        // Pushing queues to Pheanstalk Server
        $this->queue->push($tube, $content_student_email, 'email.send_email');
        $this->queue->push($tube, $content_coach_email, 'email.send_email');
        
        //$content_student_email['content'] = 'You have new appointment with ' . $coach_name->fullname. 'at '.$time[0]. ' until '.$time[1]. ' will start, please prepare yourself';
        $content_student_email['content'] = $this->email_structure->header()
                .$this->email_structure->title('New Appointment')
                .$this->email_structure->content('You have new appointment with ' . $coach_name->fullname. 'at '.$time[0]. ' until '.$time[1]. ' will start, please prepare yourself')
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');
        
        //$content_coach_email['content'] = 'You have new appointment with ' . $student_name->fullname. 'at '.$time[0]. ' until '.$time[1]. ' will start, please prepare yourself';
        $content_coach_email['content'] = $this->email_structure->header()
                .$this->email_structure->title('New Appointment')
                .$this->email_structure->content('You have new appointment with ' . $student_name->fullname. 'at '.$time[0]. ' until '.$time[1]. ' will start, please prepare yourself')
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');
        
        $delay = $this->time_reminder_before_session(($this->input->post('date').' '.$time[0]), 7200);
        if($delay){        
            $this->queue->later($delay, $tube, $content_student_email, 'email.send_email');
            $this->queue->later($delay, $tube, $content_coach_email, 'email.send_email');
        }
        
        $this->messages->add('Inserting Appointment Successful', 'success');
        redirect('student_partner/schedule/manage/' . $student_id);
    }

    public function reschedule($student_id='', $id='') {
        $this->template->title = 'Edit Schedule';
        $coaches_arr = null;
        for ($i = 0; $i < count($this->coaches); $i++) {
            $coaches_arr[$this->coaches[$i]->id] = $this->coaches[$i]->fullname;
        }
        $student_appointment = $this->appointment_model->select('*')->where('id', $id)->get();
        $vars = array(
            'student_appointment' => $student_appointment,
            'student_id' => $student_id,
            'coaches' => $coaches_arr,
            'dates' => $this->date_for_one_year($this->coaches[0]->id),
            'availabilities' => $this->availability($student_appointment->coach_id, $student_appointment->date),
            'date' => $student_appointment->date,
            'availability' => $student_appointment->start_time . ' - ' . $student_appointment->end_time,
            'form_action' => 'do_reschedule'
        );
        $this->template->content->view('default/contents/student_partner/schedule/form', $vars);
        $this->template->publish();
    }

    public function do_reschedule($student_id='') {
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'warning');
            redirect('account/identity/detail/profile');
        }

        if (!$this->input->post('student_appointment_id')) {
            $this->messages->add('Invalid ID', 'warning');
            redirect('student_partner/schedule');
        }
        
        $this->db->trans_begin();
        $schedule = $this->schedule_model->select('id')->where(array('user_id' => $this->input->post('coach_id'), 'day' => strtolower(date('l', strtotime($this->input->post('date'))))))->get();
        $time = explode('-', trim($this->input->post('time')));
        $new_appointment = array(
            'student_id' => $student_id,
            'coach_id' => $this->input->post('coach_id'),
            'date' => $this->input->post('date'),
            'schedule_id' => $schedule->id,
            'start_time' => $time[0],
            'end_time' => $time[1],
            'status' => 'pending'
        );
        
        $update_appointment = array(
            'status' => 'reschedule'
        );
        
        // Inserting new pending appointment
        $id_last_appointment = $this->appointment_model->insert($new_appointment);
        if (!$id_last_appointment) {
            $this->messages->add(validation_errors(), 'warning');
            return;
        }
        
        // Updating appointment status become reschedule
        if (!$this->appointment_model->update($this->input->post('student_appointment_id'), $update_appointment)) {
            $this->messages->add(validation_errors(), 'warning');
            return;
        }
        
        $appointment = $this->appointment_model->select('*')->where('id', $this->input->post('student_appointment_id'))->get();
        $old_appointment_reschedule = array(
            'appointment_id' => $appointment->id,
            'date' => $appointment->date,
            'start_time' => $appointment->start_time,
            'end_time' => $appointment->end_time,
            'status' => $appointment->status
        );    
        
        $last_appointment = $this->appointment_model->select('*')->where('id', $id_last_appointment)->get();
        $new_appointment_reschedule = array(
            'appointment_id' => $last_appointment->id,
            'date' => $last_appointment->date,
            'start_time' => $last_appointment->start_time,
            'end_time' => $last_appointment->end_time,
            'status' => $last_appointment->status
        );
        
        if (!$this->appointment_reschedule_model->insert($old_appointment_reschedule) || !$this->appointment_reschedule_model->insert($new_appointment_reschedule)) {
            $this->messages->add(validation_errors(), 'warning');
            return;
        }
        if (!$this->db->trans_status()){
            $this->db->trans_rollback();
            $this->messages->add('Try again, something wrong while inserting/updating data to database', 'warning');
            return;
        }
            
        $this->db->trans_commit();
        $this->messages->add('Update Successful', 'success');
        redirect('student_partner/schedule/manage/' . $this->input->post('student_id'));
    }

    public function cancel2($student_id='', $id = '') {
        $this->template->title = 'Delete Appointment';
        
        $cancel_appointment = array(
            'status' => 'cancel'
        );
        
        // Updating Appointment
        if (!$this->appointment_model->update($id, $cancel_appointment)) {
            $this->message->add(validation_errors(), 'warning');
            $this->messages->add("Canceling Appointment Failed", "error");
            return;
        }
        $this->messages->add("Canceling Appointment Successful", "success");
        redirect('student_partner/schedule/manage/' . $student_id);
    }
    
    public function cancel($student_id = '',$appointment_id = '') {
        // checking if appointment has already cancelled
        $appointment_data = $this->appointment_model->select('id, student_id, coach_id, date, start_time, end_time, status')->where('id', $appointment_id)->get();
        if ($appointment_data->status == 'cancel') {
            $this->messages->add('Appointment has already cancelled', 'danger');
            redirect('student_partner/schedule/manage/' . $student_id);
        }

        // updating appointment (change status to cancel)
        // storing data
        $appointment = array(
            'status' => 'cancel',
        );
        
        ////////////////////////////////////////////////////////////////////////
        // WEBEX
        ////////////////////////////////////////////////////////////////////////
        $webex_host = $this->webex_host_model->get_host($appointment_id);
        $webex = $this->webex_model->select('id')->where('appointment_id', $appointment_id)->get();
        
        $this->db->trans_begin();
        if($webex_host && $webex){
            // delete session in webex
            // delete session from table webex
            if(!$this->delete_session($webex_host[0]->id, $appointment_id) || !$this->webex_model->delete($webex->id)){
                $this->db->trans_rollback();
                $this->messages->add('Error while deleting session in webex', 'error');
                redirect('student_partner/schedule/manage/' . $student_id);
            }
        }

        // Updating and checking to appoinment table
        if (!$this->appointment_model->update($appointment_id, $appointment)) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->index();
            return;
        }
        $this->db->trans_commit();

        // if student cancel the session 2 days/ 48 hours before the sessioin start, student will get the token back 100%
        $student_token_data = $this->identity_model->get_identity('token')->where('user_id', $appointment_data->student_id)->get();
        $coach_token_cost = $this->coach_token_cost_model->select('id, token_for_student')->where('coach_id', $appointment_data->coach_id)->get();
        $time_left_before_session = $this->time_reminder_before_session($appointment_data->date . ' ' . $appointment_data->start_time, (2 * 24 * 60 * 60));

        if ($time_left_before_session > 0) {
            $student_token = $student_token_data->token_amount + $coach_token_cost->token_for_student;
            $token_update = array(
                'token_amount' => $student_token,
            );

            if (!$this->identity_model->get_identity('token')->update($student_token_data->id, $token_update)) {
                $this->messages->add(validation_errors(), 'danger');
                $this->index();
                return;
            }

            if (!$this->create_token_history($appointment_id, $coach_token_cost->token_for_student, $student_token, 5)) {
                $this->messages->add('Error while create token history', 'danger');
                $this->index();
                return;
            }
        } else {
            if (!$this->create_token_history($appointment_id, $coach_token_cost->token_for_student, $student_token_data->token_amount, 7)) {
                $this->messages->add('Error while create token history', 'danger');
                $this->index();
                return;
            }
        }
        
        // after student cancelled an appointment, send email to coach
        // tube name for messaging action
        $tube = 'com.live.email';
        $id_to_email_address = $this->user_model->dropdown('id', 'email');
        $id_to_name = $this->identity_model->get_identity('profile')->dropdown('id', 'fullname');
        $data = array(
            'subject' => 'Appointment Cancelled',
            'email' => $id_to_email_address[$appointment_data->coach_id],
            //'content' => 'Your appointment with student ' . $id_to_name[$student_id] . ' at ' . date("l jS \of F Y", strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' has been cancelled',
        );
        $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Appointment Cancelled')
                .$this->email_structure->content('Your appointment with student ' . $id_to_name[$student_id] . ' at ' . date("l jS \of F Y", strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' has been cancelled')
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');


        $this->queue->push($tube, $data, 'email.send_email');

        //messaging for notification
        $database_tube = 'com.live.database';
        $coach_notification = array(
            'user_id' => $appointment_data->coach_id,
            'description' => 'Your appointment with student ' . $id_to_name[$student_id] . ' at ' . date("l jS \of F Y", strtotime($appointment_data->date)) . ' from ' . $appointment_data->start_time . ' until ' . $appointment_data->end_time . ' has been cancelled.',
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
        $this->queue->push($database_tube, $data_coach, 'database.insert');

        $this->messages->add('Updating Appointment Successful', 'success');
        redirect('student_partner/schedule/manage/' . $student_id);
    }

    public function by_coach($student_id = '', $coach_id = '') {
        $this->template->title = 'Schedule';
        if (!$coach_id) {
            redirect('coach/schedule');
        }

        $coaches_arr = null;
        for ($i = 0; $i < count($this->coaches); $i++) {
            $coaches_arr[$this->coaches[$i]->id] = $this->coaches[$i]->fullname;
        }

        $date = array_keys($this->date_for_one_year($coach_id));
        $vars = array(
            'coaches' => $coaches_arr,
            'status' => $this->status,
            'dates' => $this->date_for_one_year($coach_id),
            'availabilities' => $this->availability($coach_id, $date[0]),
            'form_action' => 'add',
            'coach' => $coach_id,
            'student_id' => $student_id
        );

        $this->template->content->view('default/contents/student_partner/schedule/form', $vars);
        $this->template->publish();
    }

    public function by_date($student_id = '', $coach_id = '', $date = '') {
        $this->template->title = 'Schedule';
        if (!$date || !$coach_id) {
            redirect('coach/schedule');
        }

        $coaches_arr = null;
        for ($i = 0; $i < count($this->coaches); $i++) {
            $coaches_arr[$this->coaches[$i]->id] = $this->coaches[$i]->fullname;
        }

        $vars = array(
            'coaches' => $coaches_arr,
            'status' => $this->status,
            'dates' => $this->date_for_one_year($coach_id),
            'availabilities' => $this->availability($coach_id, $date),
            'coach' => $coach_id,
            'date' => $date,
            'student_id' => $student_id,
            'form_action' => 'add'
        );

        $this->template->content->view('default/contents/student_partner/schedule/form', $vars);
        $this->template->publish();
    }

    public function date_for_one_year($id) {
        // creating combo box based on surrent day until last day of the year
        // will improve option based on off day or vacation of coach 
        $year = date("Y");
        $date = time();

        $datetime1 = date_create(date("Y-m-d"));
        $datetime2 = date_create($year . '-12-31');
        $interval = date_diff($datetime1, $datetime2);

        $num_days = $interval->days;

        // storing day off of coach and banned it from availabilty date
        $day_off = $this->coach_day_off_model->select('start_date, end_date')->where('coach_id', $id)->where('status', 'active')->get();

        $day_off_start_date = date_create(@$day_off->start_date);
        $day_off_end_date = date_create(@$day_off->end_date);
        $interval2 = date_diff($day_off_start_date, $day_off_end_date);
        $num_days2 = $interval2->days;

        $option_date = array();
        for ($i = 0; $i <= $num_days; ++$i) {
            $date = mktime(0, 0, 0, date("m"), date("d") + $i, date("Y"));

            if ($i == 0) {
                if (strtotime(@$day_off->start_date) <= strtotime(date('Y-m-d', $date)) && strtotime(@$day_off->end_date) >= strtotime(date('Y-m-d', $date))) {
                    $date1 = date_create(@$day_off->start_date);
                    $date2 = date_create(date('Y-m-d', $date));
                    $interval3 = date_diff($date1, $date2);
                    $num_days3 = $interval3->days;
                    $num_days2 = $num_days2 - $num_days3;
                }
            }

            $day_off_interval = '';
            if (@$num_days3) {
                $day_off_interval = date('Y-m-d', strtotime(@$day_off->start_date) + (24 * 60 * 60 * $num_days3));
            } else {
                $day_off_interval = @$day_off->start_date;
            }

            if ($day_off_interval == date('Y-m-d', $date)) {
                $i = $i + $num_days2;
            } else {
                $option_date[date('Y-m-d', $date)] = date('D j M Y', $date);
            }
        }
        return $option_date;
    }

    public function availability1($coach_id = '', $date_params = '') {
        $this->template->title = 'Availability';

        if (!$date_params || !$coach_id) {
            redirect('account/identity/detail/profile');
        }
        //getting the day of $date
        $date = strtotime($date_params);
        $day = strtolower(date('l', $date));

        //getting all data
        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
        $offwork = $this->offwork_model->get_offwork($coach_id, $schedule_data->day);
        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student
        $appointment = $this->appointment_model->select('id, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('date', date("Y-m-d", $date))->get_all();
        // appointment coach in class
        $appointment_class = $this->class_meeting_day_model->select('id, start_time, end_time')->where('coach_id', $coach_id)->where('date', date("Y-m-d", $date))->get_all();

        //offwork by day
        $start_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->start_time);
        $end_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->end_time);
        //schedule by day
        $start_time_schedule = DateTime::createFromFormat('H:i:s', $schedule_data->start_time);
        $end_time_schedule = DateTime::createFromFormat('H:i:s', $schedule_data->end_time);

        $availability = array();
        // split the availability time base on offwork
        if ($start_time_offwork >= $start_time_schedule && $start_time_offwork <= $end_time_schedule && $end_time_offwork >= $start_time_schedule && $end_time_offwork <= $end_time_schedule) {
            $availability[0] = array(
                'start_time' => $schedule_data->start_time,
                'end_time' => $offwork[0]->start_time,
            );
            $availability[1] = array(
                'start_time' => $offwork[0]->end_time,
                'end_time' => $schedule_data->end_time,
            );
        } else {
            $availability[0] = array(
                'start_time' => $schedule_data->start_time,
                'end_time' => $schedule_data->end_time,
            );
        }

        // storing appointment to an array so can easily on searching / no object value inside
        $appointment_temp = array();
        foreach ($appointment as $a) {
            $appointment_temp[$a->start_time] = $a->start_time;
            $appointment_temp[$a->end_time] = $a->end_time;
        }
        // storing class meeting days to appointment temp
        foreach ($appointment_class as $a) {
            $appointment_temp[$a->start_time] = $a->start_time;
            $appointment_temp[$a->end_time] = $a->end_time;
        }

        $availability_temp = array();
        $availability_exist = array();
        foreach ($availability as $a) {
            $duration = (strtotime($a['end_time']) - strtotime($a['start_time'])) / 1800;
            if ($duration > 0) {
                for ($i = 0; $i < $duration; $i++) {
                    $availability_exist = array(
                        // adding 30 minutes for every session
                        'start_time' => date('H:i:s', strtotime($a['start_time']) + (1800 * ($i))),
                        'end_time' => date('H:i:s', strtotime($a['start_time']) + (1800 * ($i + 1))),
                    );
                    // checking if availability is existed in the appointment
                    if (in_array($availability_exist['start_time'], $appointment_temp) && in_array($availability_exist['end_time'], $appointment_temp)) {
                        // no action
                    } else {
                        // storing availability that still active and not been boooked yet
                        $availability_temp[$availability_exist['start_time'] . ' - ' . $availability_exist['end_time']] = $availability_exist['start_time'] . ' - ' . $availability_exist['end_time'];
                    }
                }
            }
        }
        return $availability_temp;
    }
    
    public function availability($search_by='', $student_id='', $coach_id = '', $date_ = '') {
        $this->template->title = 'Availability';

        if (!$date_ || !$coach_id) {
            //redirect(home);
            $vars = array();
            $this->template->content->view('default/contents/student_partner/schedule/availability', $vars);

            //publish template
            $this->template->publish();
        }

        if (!$this->is_date_available(trim($date_), 2)) {
            //$this->messages->add('Date is not valid ' . $date_, 'warning');
            //redirect('student/find_coaches/schedule_detail/' . $coach_id);
            $vars = array();
            $this->template->content->view('default/contents/student_partner/schedule/availability', $vars);
        }

        if ($this->is_day_off($coach_id, $date_) == true) {
            //$this->messages->add('Coach is not available on ' . $date_, 'warning');
            //redirect('student/find_coaches/schedule_detail/' . $coach_id);
            $vars = array();
            $this->template->content->view('default/contents/student_partner/schedule/availability', $vars);
        }
        // getting the day of $date
        // getting gmt minutes
        $minutes = $this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes;
        $date = strtotime($date_);
        //print_r(date('Y-m-d', $date));
        // getting day and day after or before based on gmt
        $day = strtolower(date('l', $date));
        $day2 = $this->day_index[$this->convert_gmt(array_search($day, $this->day_index), $minutes)];

        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student
        $appointment = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->get_all();
        // appointment with status temporary considered available for other student but not for student who is in the appointment and 
        // appointment where the student has make an appoinment on the specific date, so there will be no the same start time and end time to be shown to the student from other coach
        $appointment_student = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $student_id)->where('status not like', 'cancel')->where('date', date("Y-m-d", $date))->get_all();
        // appointment coach in class
        $appointment_class = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', date("Y-m-d", $date))->get_all();

        // storing appointment to an array so can easily on searching / no object value inside
        $appointment_start_time_temp = array();
        $appointment_end_time_temp = array();
        
        // getting all unavailable schedule to be not shown on coach availability
        foreach ($appointment as $a) {
            if($minutes > 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            else if($minutes < 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            else if($minutes == 0){
                $appointment_start_time_temp[] = $a->start_time;
                $appointment_end_time_temp[] = $a->end_time;
            }
        }
        // storing class meeting days to appointment temp
        foreach ($appointment_class as $a) {
            if($minutes > 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            else if($minutes < 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            else if($minutes == 0){
                $appointment_start_time_temp[] = $a->start_time;
                $appointment_end_time_temp[] = $a->end_time;
            }
        }
        
        foreach ($appointment_student as $a) {
            if($minutes > 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            else if($minutes < 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            else if($minutes == 0){
                $appointment_start_time_temp[] = $a->start_time;
                $appointment_end_time_temp[] = $a->end_time;
            }
        }
        
        if($minutes > 0){
            $date2 = date("Y-m-d", strtotime('-1 day'.date("Y-m-d",$date)));
            $appointment2 = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $date2)->get_all();
            $appointment_student2 = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $student_id)->where('status not like', 'cancel')->where('date', $date2)->get_all();
            $appointment_class2 = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', $date2)->get_all();
            
            foreach($appointment2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            foreach($appointment_student2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            foreach($appointment_class2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            
        }
        else if($minutes < 0){
            $date2 = date("Y-m-d", strtotime('+1 day'.date("Y-m-d",$date)));
            $appointment2 = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $date2)->get_all();
            $appointment_student2 = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $student_id)->where('status not like', 'cancel')->where('date', $date2)->get_all();
            $appointment_class2 = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', $date2)->get_all();
            foreach($appointment2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            foreach($appointment_student2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            foreach($appointment_class2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
        }
        

        
        //getting all data
        $schedule_data1 = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
        $schedule_data2 = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day2)->get();
        
        $availability = $this->schedule_block($coach_id, $day, $schedule_data1->start_time, $schedule_data1->end_time, $schedule_data2->day, $schedule_data2->start_time, $schedule_data2->end_time);


        $availability_temp = array();
        $availability_exist;
        foreach ($availability as $a) {
            $duration = (strtotime($a['end_time']) - strtotime($a['start_time'])) / ($this->session_duration($this->auth_manager->partner_id()) * 60);
            if ($duration >= 1) {
                for ($i = 0; $i < $duration; $i++) {
                    $availability_exist = array(
                        // adding  minutes for every session
                        'start_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id()) * 60) * ($i))),
                        'end_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id()) * 60) * ($i + 1))),
                    );
                    
                    // checking if the time is not out of coach schedule
                    if(strtotime($availability_exist['end_time']) <= strtotime($a['end_time'])){
                        // checking if availability is existed in the appointment
                        if (in_array($availability_exist['start_time'], $appointment_start_time_temp) && in_array($availability_exist['end_time'], $appointment_end_time_temp)) {
                            // no action
                        } else {
                            // storing availability that still active and not been boooked yet
                            if($this->isValidAppointment($availability_exist['start_time'], $availability_exist['end_time'], $appointment_start_time_temp, $appointment_end_time_temp)){
                                date_default_timezone_set('Etc/GMT'.(-$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                        if ($date_ == date('Y-m-d', strtotime(date('Y-m-d') . ' + 2 days'))) {
                                            if (DateTime::createFromFormat('H:i:s', $availability_exist['start_time']) >= DateTime::createFromFormat('H:i:s', date('H:i:s'))) {
                                                $availability_temp[] = $availability_exist;
                                            }
                                        } else {
                                            $availability_temp[] = $availability_exist;
                                        }
                            }
                        }
                    }
                }
            }
        }
        $vars = array(
            'availability' => $availability_temp,
            'search_by' => $search_by,
            'student_id' => $student_id,
            'coach_id' => $coach_id,
            'date' => $date,
            'cost' => $this->coach_token_cost_model->select('token_for_student')->where('coach_id', $coach_id)->get()
        );
        
        $this->template->content->view('default/contents/student_partner/schedule/availability', $vars);

        //publish template
        $this->template->publish();
    }
    
    private function convert_gmt($index = '', $minutes = '') {
        if ($minutes > 0) {
            return (($index - 1) >= 0 ? ($index - 1) : 6);
        } else {
            return (($index + 1) <= 6 ? ($index + 1) : 0);
        }
    }
    
    private function schedule_block($coach_id = '', $day1 = '', $start_time1 = '', $end_time1 = '', $day2 = '', $start_time2 = '', $end_time2 = '') {
        $schedule1 = $this->block($coach_id, $day1, $start_time1, $end_time1);
        $schedule2 = $this->block($coach_id, $day2, $start_time2, $end_time2);

        $schedule = array();
        $minutes = $this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes;

        $time = strtotime('00:00:00');
        $startTime = date("H:i:s", strtotime((-$minutes) . 'minutes', $time));
        $endTime = date("H:i:s", strtotime('+30 minutes', $time));

        if ($minutes == 0) {
            $schedule = $schedule1;
        } else if ($minutes > 0) {
            foreach ($schedule2 as $s2) {
                $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['start_time'])));
                $schedule_temp2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['end_time'])));
                if (strtotime($schedule_temp) < strtotime($s2['start_time'])) {
//                        break;
                    $s2['start_time'] = $schedule_temp;
                    $s2['end_time'] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['end_time'])));
                    $schedule[] = array(
                        'start_time' => $this->convertTime($s2['start_time']),
                        'end_time' => $this->convertTime($s2['end_time']),
                    );
                } else if (strtotime($schedule_temp) > strtotime($s2['start_time']) && strtotime($schedule_temp2) < strtotime($s2['end_time'])) {
                    $schedule[] = array(
                        'start_time' => '00:00:00',
                        'end_time' => $this->convertTime($schedule_temp2),
                    );
                }
            }


            foreach ($schedule1 as $s1) {
                $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s1['start_time'])));
                if (strtotime($schedule_temp) < strtotime($s1['start_time']) || $schedule_temp == '00:00:00') {
                    break;
                } else {
                    $s1['start_time'] = $schedule_temp;
                    $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s1['end_time'])));
                    if (strtotime($schedule_temp) < strtotime($s1['end_time']) || $schedule_temp == '00:00:00') {
                        $s1['end_time'] = '23:59:59';
                    } else {
                        $s1['end_time'] = $schedule_temp;
                    }

                    $schedule[] = array(
                        'start_time' => $this->convertTime($s1['start_time']),
                        'end_time' => $this->convertTime($s1['end_time']),
                    );
                }
            }
        } else {
            foreach ($schedule1 as $s1) {
                $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s1['end_time'])));
                $schedule_temp2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s1['start_time'])));
                if (strtotime($schedule_temp) > strtotime($s1['end_time']) || $schedule_temp == '00:00:00') {
                    //break;
                } else if (strtotime($schedule_temp2) > strtotime($s1['start_time']) && strtotime($schedule_temp) < strtotime($s1['end_time'])) {
                    //$s1['start_time'] = '00:00:00';
                    $schedule[] = array(
                        'start_time' => '00:00:00',
                        'end_time' => $this->convertTime($schedule_temp),
                    );
                } else if (strtotime($schedule_temp2) <= strtotime($s1['start_time'])) {
                    $schedule[] = array(
                        'start_time' => $this->convertTime($schedule_temp2),
                        'end_time' => $this->convertTime($schedule_temp),
                    );
                }
            }

            foreach ($schedule2 as $s2) {
                $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['start_time'])));
                $schedule_temp2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['end_time'])));
                if (strtotime($schedule_temp) < strtotime($s2['start_time'])) {
                    break;
                } else if (strtotime($schedule_temp) > strtotime($s2['start_time']) && strtotime($schedule_temp2) > strtotime($s2['end_time'])) {
                    $schedule[] = array(
                        'start_time' => $this->convertTime($schedule_temp),
                        'end_time' => $this->convertTime($schedule_temp2),
                    );
                } else if (strtotime($schedule_temp) > strtotime($s2['start_time']) && strtotime($schedule_temp2) < strtotime($s2['end_time'])) {
                    $schedule[] = array(
                        'start_time' => $this->convertTime($schedule_temp),
                        'end_time' => '23:59:59',
                    );
                }
            }
        }
        return $this->joinTime($schedule);
    }
    
    private function block($coach_id = '', $day = '', $start_time = '', $end_time = '') {
        $offwork = $this->offwork_model->get_offwork($coach_id, $day);
        $schedule_temp = array();
        if ($offwork) {
            //foreach($offwork as $o){
            for ($i = 0; $i <= count($offwork); $i++) {
                if ($i == 0) {
                    $schedule_temp[] = array(
                        'start_time' => $start_time,
                        'end_time' => $offwork[0]->start_time,
                    );
                } else if ($i > 0 && $i < (count($offwork))) {
                    $schedule_temp[] = array(
                        'start_time' => $offwork[$i - 1]->end_time,
                        'end_time' => $offwork[$i]->start_time,
                    );
                } else if ($i == (count($offwork))) {
                    $schedule_temp[] = array(
                        'start_time' => $offwork[$i - 1]->end_time,
                        'end_time' => $end_time,
                    );
                }
            }
        } else {
            $schedule_temp[] = array(
                'start_time' => $start_time,
                'end_time' => $end_time,
            );
        }

        return $schedule_temp;
    }
    
    private function convertTime($time = ''){
        if(date("H:i", strtotime(1 . 'minutes', strtotime($time))) != '00:00' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '01' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '21' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '31'){
            return date("H:i", strtotime(1 . 'minutes', strtotime($time)));
        }
        else{
            return $time;
        }
    }
    
    private function joinTime($schedule = ''){
        $schedule_temp = array();
        if(count($schedule) > 1){
            for($i=0;$i<(count($schedule));$i++){
                if($schedule[$i]['start_time'] != $schedule[$i]['end_time']){
                    if($i<(count($schedule)-1) && strtotime($schedule[$i]['end_time']) == strtotime($schedule[$i+1]['start_time'])){
                        $schedule_temp[] = array(
                            'start_time' => $schedule[$i]['start_time'],
                            'end_time' => $schedule[$i+1]['end_time'],
                        );
                        $i++;
                    }
                    else{
                        $schedule_temp[] = array(
                            'start_time' => $schedule[$i]['start_time'],
                            'end_time' => $schedule[$i]['end_time'],
                        );
                    }
                }
            }
        }
        else if(count($schedule) == 1){
            if($schedule[0]['start_time'] != $schedule[0]['end_time']){
                $schedule_temp[] = array(
                    'start_time' => $schedule[0]['start_time'],
                    'end_time' => $schedule[0]['end_time'],
                );
            }
        }
        
        return $schedule_temp;
    }
    
    private function session_duration($partner_id = ''){  
        $setting = $this->partner_setting_model->get();
        return $setting->session_duration;
    }
    
    private function isValidAppointment($start_time = '', $end_time = '', $start_time_temp = '', $end_time_temp = ''){
        $status = true;
        for($i=0;$i<count($start_time_temp);$i++){
            if(DateTime::createFromFormat('H:i:s', $start_time_temp[$i]) >= DateTime::createFromFormat('H:i:s', $start_time) && DateTime::createFromFormat('H:i:s', $start_time_temp[$i]) < DateTime::createFromFormat('H:i:s', $end_time)){
                $status = false;
                break;
            }
            else if(DateTime::createFromFormat('H:i:s', $end_time_temp[$i]) > DateTime::createFromFormat('H:i:s', $start_time) && DateTime::createFromFormat('H:i:s', $start_time_temp[$i]) < DateTime::createFromFormat('H:i:s', $start_time)){
                $status = false;
                break;
            }
        }
        return $status;
    }
    
    public function availability2($search_by = '', $coach_id = '', $date_ = '') {
        $this->template->title = 'Availability';
        //print_r($date_);
        if (!$date_ || !$coach_id) {
            //redirect(home);
            $vars = array();
            $this->template->content->view('default/contents/student_partner/schedule/availability', $vars);

            //publish template
            $this->template->publish();
        }
        
        // checking if the date is valid
        if (!$this->is_date_available(trim($date_), 2)) {
            $vars = array();
            $this->template->content->view('default/contents/student_partner/schedule/availability', $vars);
        }
        
        // checking if the date is in day off
        if ($this->is_day_off($coach_id, $date_) == true) {
            $vars = array();
            $this->template->content->view('default/contents/student_partner/schedule/availability', $vars);
        }
        
        // getting the day of $date
        // getting gmt minutes
        $minutes = $this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes;
        $date = strtotime($date_);
        //print_r(date('Y-m-d', $date));
        // getting day and day after or before based on gmt
        $day = strtolower(date('l', $date));
        $day2 = $this->day_index[$this->convert_gmt(array_search($day, $this->day_index), $minutes)];

        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student
        $appointment = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->get_all();
        // appointment with status temporary considered available for other student but not for student who is in the appointment and 
        // appointment where the student has make an appoinment on the specific date, so there will be no the same start time and end time to be shown to the student from other coach
        $appointment_student = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', date("Y-m-d", $date))->get_all();
        // appointment coach in class
        $appointment_class = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', date("Y-m-d", $date))->get_all();

        // storing appointment to an array so can easily on searching / no object value inside
        $appointment_start_time_temp = array();
        $appointment_end_time_temp = array();
        
        // getting all unavailable schedule to be not shown on coach availability
        foreach ($appointment as $a) {
            if($minutes > 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            else if($minutes < 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            else if($minutes == 0){
                $appointment_start_time_temp[] = $a->start_time;
                $appointment_end_time_temp[] = $a->end_time;
            }
        }
        // storing class meeting days to appointment temp
        foreach ($appointment_class as $a) {
            if($minutes > 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            else if($minutes < 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            else if($minutes == 0){
                $appointment_start_time_temp[] = $a->start_time;
                $appointment_end_time_temp[] = $a->end_time;
            }
        }
        
        foreach ($appointment_student as $a) {
            if($minutes > 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            else if($minutes < 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            else if($minutes == 0){
                $appointment_start_time_temp[] = $a->start_time;
                $appointment_end_time_temp[] = $a->end_time;
            }
        }
        
        if($minutes > 0){
            $date2 = date("Y-m-d", strtotime('-1 day'.date("Y-m-d",$date)));
            $appointment2 = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $date2)->get_all();
            $appointment_student2 = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', $date2)->get_all();
            $appointment_class2 = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', $date2)->get_all();
            
            foreach($appointment2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            foreach($appointment_student2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            foreach($appointment_class2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            
        }
        else if($minutes < 0){
            $date2 = date("Y-m-d", strtotime('+1 day'.date("Y-m-d",$date)));
            $appointment2 = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $date2)->get_all();
            $appointment_student2 = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', $date2)->get_all();
            $appointment_class2 = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', $date2)->get_all();
            foreach($appointment2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            foreach($appointment_student2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            foreach($appointment_class2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
        }
        

        
        //getting all data
        $schedule_data1 = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
        $schedule_data2 = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day2)->get();
        
        $availability = $this->schedule_block($coach_id, $day, $schedule_data1->start_time, $schedule_data1->end_time, $schedule_data2->day, $schedule_data2->start_time, $schedule_data2->end_time);



        $availability_temp = array();
        $availability_exist;
        foreach ($availability as $a) {
            $duration = (strtotime($a['end_time']) - strtotime($a['start_time'])) / ($this->session_duration($this->auth_manager->partner_id()) * 60);
            if ($duration >= 1) {
                for ($i = 0; $i < $duration; $i++) {
                    $availability_exist = array(
                        // adding  minutes for every session
                        'start_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id()) * 60) * ($i))),
                        'end_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id()) * 60) * ($i + 1))),
                    );
                    
                    // checking if the time is not out of coach schedule
                    if(strtotime($availability_exist['end_time']) <= strtotime($a['end_time'])){
                        // checking if availability is existed in the appointment
                        if (in_array($availability_exist['start_time'], $appointment_start_time_temp) && in_array($availability_exist['end_time'], $appointment_end_time_temp)) {
                            // no action
                        } else {
                            // storing availability that still active and not been boooked yet
                            if($this->isValidAppointment($availability_exist['start_time'], $availability_exist['end_time'], $appointment_start_time_temp, $appointment_end_time_temp)){
                                date_default_timezone_set('Etc/GMT'.(-$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                        if ($date_ == date('Y-m-d', strtotime(date('Y-m-d') . ' + 2 days'))) {
                                            if (DateTime::createFromFormat('H:i:s', $availability_exist['start_time']) >= DateTime::createFromFormat('H:i:s', date('H:i:s'))) {
                                                $availability_temp[] = $availability_exist;
                                            }
                                        } else {
                                            $availability_temp[] = $availability_exist;
                                        }
                            }
                        }
                    }
                }
            }
        }
        
        $vars = array(
            'availability' => $availability_temp,
            'coach_id' => $coach_id,
            'date' => strtotime($date_),
            'search_by' => $search_by,
            'cost' => $this->coach_token_cost_model->select('token_for_student')->where('coach_id', $coach_id)->get()
        );
//        echo('<pre>');
//        print_r($vars); exit;
        $this->template->content->view('default/contents/student_partner/schedule/availability', $vars);

        //publish template
        $this->template->publish();
    }
    
    /**
     * Function time_reminder_before_session
     *  
     * @param (string)(session_time) session time ('Y-m-d H:i:s')
     * @param (int)(delay_time) delay time before session time (s)
     * 
     * @return if the function not return positive int, return FALSE 
     */
    public function time_reminder_before_session($session_time, $delay_time) {
        if (DateTime::createFromFormat('Y-m-d H:i:s', trim($session_time)) != FALSE) {
            $now = (date('Y-m-d H:i:s', time() + $delay_time));
            return (((strtotime($session_time) - strtotime($now))) < 0 ? FALSE: (strtotime($session_time) - strtotime($now)));
        }
        else{
            return FALSE;
        }
    }
    
    /**
     * Function is_date_available
     * @param (string)(date) date that will be checked wheather available ('Y-m-d')
     * @param (int)(day) sum of day()
     * @return return TRUE if date available
     */
    private function is_date_available($date, $day) {
        if ((DateTime::createFromFormat('Y-m-d', trim($date)) != FALSE) && (strtotime($date) >= strtotime(date('Y-m-d', strtotime("+" . $day . "days"))))) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function schedule_detail($id = '') {
        $vars = $this->schedule_function->schedule_detail($id);
        $this->template->content->view('default/contents/find_coach/schedule_detail', $vars);

        //publish template
        $this->template->publish();
    }
    
    private function is_day_off($coach_id = '', $date_ = '') {
        $day_off = $this->coach_day_off_model->select('start_date, end_date')->where('coach_id', $coach_id)->where('status', 'active')->get();
        $start_date = strtotime(@$day_off->start_date);
        $end_date = strtotime(@$day_off->end_date);
        $date = strtotime($date_);

        if ($date >= $start_date && $date <= $end_date || $date < mktime(0, 0, 0, date("m"), date("d"), date("Y"))) {
            return true;
        } else if (!$day_off) {
            return false;
        } else {
            return false;
        }
    }
    
    public function summary_book($search_by = '', $student_id='', $coach_id = '', $date = '', $start_time = '', $end_time = '') {
        $vars = array(
            'data_coach' => $this->identity_model->get_coach_identity($coach_id),
            'data_student' => $this->identity_model->get_student_identity($student_id),
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'search_by' => $search_by,
        );

        $this->template->content->view('default/contents/student_partner/schedule/summary_book', $vars);
        //publish template
        $this->template->publish();
    }
    
    public function book_single_coach($student_id = '', $coach_id = '', $date_ = '', $start_time_ = '', $end_time_ = '') {
        //exit;

        $start_time_available = $start_time_;
        $end_time_available = $end_time_;
        
        $convert = $this->schedule_function->convert_book_schedule(-($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes), $date_, $start_time_, $end_time_);
        $date = $convert['date'];
        $start_time = $convert['start_time'];
        $end_time = $convert['end_time'];
        
        try {
            // First of all, let's begin a transaction
            // A set of queries; if one fails, an exception should be thrown
            $isValid = $this->isAvailable($coach_id, $date, $start_time, $end_time);
            if ($isValid) {
                $availability = $this->isOnAvailability($student_id, $coach_id, date('Y-m-d', $date_));
                if (in_array(array('start_time' => $start_time_available, 'end_time' => $end_time_available), $availability)) {
                    // go to next step
                } else {
                    $this->messages->add('Invalid Time', 'warning');
                    redirect('student_partner/schedule/create/'.$student_id);
                }


                // begin the transaction to ensure all data created or modified structural
                $token_cost = $this->coach_token_cost_model->select('token_for_student')->where('coach_id', $coach_id)->get();
                // updating remaining token student
                $remain_token = $this->update_token($student_id, $token_cost->token_for_student);
                if ($this->db->trans_status() === true && $remain_token >= 0 && $this->isAvailable($coach_id, $date, $start_time, $end_time)) {
                    $appointment_id = $this->create_appointment($student_id, $coach_id, $date, $start_time, $end_time, 'active');
                    $valid_appointment = count($this->appointment_model->where('coach_id', $coach_id)->where('date', date('Y-m-d', $date))->where('start_time', $start_time)->where('end_time', $end_time)->where('status', 'active')->get_all());  
                    
                    if ($this->db->trans_status() === true && $appointment_id > 0 && $valid_appointment == 1) {
                         
                        $this->create_token_history($student_id, $appointment_id, $token_cost->token_for_student, $remain_token, 1);
                        // messaging to send email and creating notification based on appointment
                        // $this->email_notification_appointment($student_id, $appointment_id);
                        // transaction finished / all criteria has been fulfilled
                        
                        ///////////////////////////////////////////////////////////////////////////////
                        // SETUP WEBEX/SKYPE
                        ///////////////////////////////////////////////////////////////////////////////
                        
                        $available_host = $this->webex_host_model->get_available_host($appointment_id);
                      
                        if($available_host && $this->webex_function->create_session($available_host[0]->id, $appointment_id > 0)){
                            $message = "Appointment booked, student will use Webex for your session";
                        }else{
                            $message = "Appointment booked, student will use Skype for your session";
                        }                        
                        $this->messages->add($message, 'success');
                        redirect('student_partner/schedule/manage/'.$student_id);
                    } else {
                        $this->rollback_appointment($student_id, $coach_id, date("Y-m-d", $date), $start_time, $end_time, ($remain_token + $token_cost->token_for_student));
                        $this->messages->add('Fail to book appointment, please try again.', 'warning');
                        redirect('student_partner/schedule/create/'.$student_id);
                    }
                  
                } else {
                    $this->messages->add('Not Enough Token', 'warning');
                    redirect('student_partner/schedule/manage/'.$student_id);
                }
            } else {
                $this->messages->add('Invalid Appointment', 'warning');
                redirect('student_partner/schedule/create/'.$student_id);
            }


            // If we arrive here, it means that no exception was thrown
            // i.e. no query has failed, and we can commit the transaction
            //$this->db->trans_commit();
        } catch (Exception $e) {
            // An exception has been thrown
            // We must rollback the transaction
            $this->db->trans_rollback();
            $this->messages->add('An error has occured, please try again.', 'warning');
            redirect('student/find_coaches/single_date/');
        }
        
        
    }
    
    private function isOnAvailability($student_id = '', $coach_id = '', $date_ = '') {
        if (!$date_ || !$coach_id || !$student_id) {
            //redirect(home);
            $vars = array();
            $this->template->content->view('default/contents/student_partner/schedule/availability', $vars);

            //publish template
            $this->template->publish();
        }
        
        // checking if the date is valid
        if (!$this->is_date_available(trim($date_), 2)) {
            $vars = array();
            $this->template->content->view('default/contents/student_partner/schedule/availability', $vars);
        }
        
        // checking if the date is in day off
        if ($this->is_day_off($coach_id, $date_) == true) {
            $vars = array();
            $this->template->content->view('default/contents/student_partner/schedule/availability', $vars);
        }
        
        // getting the day of $date
        // getting gmt minutes
        $minutes = $this->identity_model->get_gmt($student_id)[0]->minutes;
        $date = strtotime($date_);
        // getting day and day after or before based on gmt
        $day = strtolower(date('l', $date));
        $day2 = $this->day_index[$this->convert_gmt(array_search($day, $this->day_index), $minutes)];

        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student
        $appointment = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->get_all();
        // appointment with status temporary considered available for other student but not for student who is in the appointment and 
        // appointment where the student has make an appoinment on the specific date, so there will be no the same start time and end time to be shown to the student from other coach
        $appointment_student = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $student_id)->where('status not like', 'cancel')->where('date', date("Y-m-d", $date))->get_all();
        // appointment coach in class
        $appointment_class = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', date("Y-m-d", $date))->get_all();

        // storing appointment to an array so can easily on searching / no object value inside
        $appointment_start_time_temp = array();
        $appointment_end_time_temp = array();
        
        // getting all unavailable schedule to be not shown on coach availability
        foreach ($appointment as $a) {
            if($minutes > 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            else if($minutes < 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            else if($minutes == 0){
                $appointment_start_time_temp[] = $a->start_time;
                $appointment_end_time_temp[] = $a->end_time;
            }
        }
        // storing class meeting days to appointment temp
        foreach ($appointment_class as $a) {
            if($minutes > 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            else if($minutes < 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            else if($minutes == 0){
                $appointment_start_time_temp[] = $a->start_time;
                $appointment_end_time_temp[] = $a->end_time;
            }
        }
        
        foreach ($appointment_student as $a) {
            if($minutes > 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            else if($minutes < 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            else if($minutes == 0){
                $appointment_start_time_temp[] = $a->start_time;
                $appointment_end_time_temp[] = $a->end_time;
            }
        }
        
        if($minutes > 0){
            $date2 = date("Y-m-d", strtotime('-1 day'.date("Y-m-d",$date)));
            $appointment2 = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $date2)->get_all();
            $appointment_student2 = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $student_id)->where('status not like', 'cancel')->where('date', $date2)->get_all();
            $appointment_class2 = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', $date2)->get_all();
            
            foreach($appointment2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            foreach($appointment_student2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            foreach($appointment_class2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            
        }
        else if($minutes < 0){
            $date2 = date("Y-m-d", strtotime('+1 day'.date("Y-m-d",$date)));
            $appointment2 = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $date2)->get_all();
            $appointment_student2 = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $student_id)->where('status not like', 'cancel')->where('date', $date2)->get_all();
            $appointment_class2 = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', $date2)->get_all();
            foreach($appointment2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            foreach($appointment_student2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            foreach($appointment_class2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
        }
        

        
        //getting all data
        $schedule_data1 = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
        $schedule_data2 = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day2)->get();
        
        $availability = $this->schedule_block($coach_id, $day, $schedule_data1->start_time, $schedule_data1->end_time, $schedule_data2->day, $schedule_data2->start_time, $schedule_data2->end_time);



        $availability_temp = array();
        $availability_exist;
        foreach ($availability as $a) {
            $duration = (strtotime($a['end_time']) - strtotime($a['start_time'])) / ($this->session_duration($this->auth_manager->partner_id()) * 60);
            if ($duration >= 1) {
                for ($i = 0; $i < $duration; $i++) {
                    $availability_exist = array(
                        // adding  minutes for every session
                        'start_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id()) * 60) * ($i))),
                        'end_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id()) * 60) * ($i + 1))),
                    );
                    
                    // checking if the time is not out of coach schedule
                    if(strtotime($availability_exist['end_time']) <= strtotime($a['end_time'])){
                        // checking if availability is existed in the appointment
                        if (in_array($availability_exist['start_time'], $appointment_start_time_temp) && in_array($availability_exist['end_time'], $appointment_end_time_temp)) {
                            // no action
                        } else {
                            // storing availability that still active and not been boooked yet
                            if($this->isValidAppointment($availability_exist['start_time'], $availability_exist['end_time'], $appointment_start_time_temp, $appointment_end_time_temp)){
                                date_default_timezone_set('Etc/GMT'.(-$this->identity_model->get_gmt($student_id)[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->get_gmt($student_id)[0]->minutes/60 : -$this->identity_model->get_gmt($student_id)[0]->minutes/60));
                                        if ($date_ == date('Y-m-d', strtotime(date('Y-m-d') . ' + 2 days'))) {
                                            if (DateTime::createFromFormat('H:i:s', $availability_exist['start_time']) >= DateTime::createFromFormat('H:i:s', date('H:i:s'))) {
                                                $availability_temp[] = $availability_exist;
                                            }
                                        } else {
                                            $availability_temp[] = $availability_exist;
                                        }
                                        
                                        // mengatasi tanggal yang tidak sesuai
                                        if($this->identity_model->get_gmt($student_id)[0]->minutes < 0){
                                            date_default_timezone_set('Etc/GMT'.($this->identity_model->get_gmt($student_id)[0]->minutes/60 >= 0 ? '+'.$this->identity_model->get_gmt($student_id)[0]->minutes/60 : $this->identity_model->get_gmt($student_id)[0]->minutes/60));
                                        }
                            }
                        }
                    }
                }
            }
        }

        return $availability_temp;
    }
    
    private function get_date_week($date = ''){
        $index = array_search(strtolower(date("l", $date)), $this->day_index);
        $date_index = array();
        for($i=0;$i<7;$i++){
            $date_index[] = date('Y-m-d', strtotime(date('Y-m-d', $date). ''. ($i-$index).' days'));
        }
        return $date_index;
    }
    
    private function isAvailable($coach_id = '', $date = '', $start_time = '', $end_time = '') {
        //getting the day of $date
        $day = strtolower(date('l', $date));
        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id', $coach_id)->where('day', $day)->order_by('id', 'asc')->get();
        $schedule = $this->block($coach_id, $day, $schedule_data->start_time, $schedule_data->end_time);

        // check if coach availability has been booked or nothing
        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student
        $appointment = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->where('start_time', $start_time)->where('end_time', $end_time)->get();
        // appointment with status temporary considered available for other student but not for student who is in the appointment and 
        // appointment where the student has make an appoinment on the specific date, so there will be no the same start time and end time to be shown to the student from other coach
        $appointment_student = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->where('start_time', $start_time)->where('end_time', $end_time)->get();
        // appointment coach in class
        $appointment_class = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', date("Y-m-d", $date))->where('start_time', $start_time)->where('end_time', $end_time)->get();
        
        // partner setting about student appointment
        $setting = $this->partner_setting_model->get();
        $appointment_count = count($this->appointment_model->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->get_all());
        //print_r($this->get_date_week($date)); exit;
        $appointment_count_week = 0;
        foreach($this->get_date_week($date) as $s){
            $appointment_count_week = $appointment_count_week + count($this->appointment_model->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $s)->get_all());
        }
//        print_r($appointment_count); 
//        print_r($appointment_count_week); 
//        print_r($setting->max_session_per_day); 
//        print_r($setting->max_day_per_week);
//        exit;
//        echo('<pre>');
//        echo(date('Y-m-d', $date));
//        echo('<br>');
//        print_r($start_time);
//        print_r($end_time);
//        print_r($schedule); exit;
        $status1 = 0;
        if ($appointment || $appointment_student || $appointment_class) {
            return false;
        } else if (!$appointment) {
            if($appointment_count < $setting->max_session_per_day && $appointment_count_week < $setting->max_day_per_week){
                foreach($schedule as $s){
                    if(strtotime($start_time) >= strtotime($s['start_time']) && strtotime($end_time) <= strtotime($s['end_time'])){
                        $status1 = 1;
                        break;
                    }
                }
                if($status1 == 1){
                    return true;
                }
                else{
                    $this->messages->add('Invalid Appointment Time', 'warning');
                    return false;
                }
            }
            else{
                $this->messages->add('Exceeded Max Session Per Day or Week', 'warning');
                return false;
            }
        }
    }
    
    /*
     * fungsi yang perlu dipisah dari fungsi booking    
     * 1. fungsi create appointment (check)
     * 2. fungsi check token (check)
     * 3. fungsi messaging email and notification appointment (check)
     * 4. update appointment status from temporary to active (check)
     */

    private function create_appointment($student_id = '', $coach_id = '', $date = '', $start_time = '', $end_time = '', $appointment_status = '') {
//        print_r(date('Y-m-d', $date)); 
//        print_r($start_time); 
//        print_r($end_time); 
//        
//        
//        $this->db->trans_rollback();
//        exit;
        //$status = false;
        // getting the day of $date
        $day = strtolower(date('l', $date));
        $schedule_data = $this->schedule_model->select('id, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
        // Retrieve post
        $booked = array(
            'student_id' => $student_id,
            'coach_id' => $coach_id,
            'schedule_id' => $schedule_data->id,
            'date' => date("Y-m-d", $date),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => $appointment_status,
        );

        $this->db->trans_begin();
            // Inserting and checking
            $appointment_id = $this->appointment_model->insert($booked);
            $status = $appointment_id;
        if ($appointment_id && $status == true && $this->db->trans_status() === true) {
            $this->db->trans_commit();
            return $appointment_id;
        } else {
            $this->db->trans_rollback();
            return false;
        }
    }
    
    private function update_token($student_id='', $cost = '') {
        $status = false;
        $student_token = $this->identity_model->get_identity('token')->select('id, token_amount')->where('user_id', $student_id)->get();
        //$coach_cost = $this->coach_token_cost_model->select('token_for_student')->where('coach_id', $coach_id)->get();
        if ($student_token->token_amount < $cost) {
            $status = false;
        } else if ($student_token->token_amount >= $cost) {
            $remain_token = $student_token->token_amount - $cost;
            $data = array(
                'token_amount' => $remain_token,
            );
            $this->identity_model->get_identity('token')->update($student_token->id, $data);
            $status = true;
        }

        if ($status == true) {
            return $remain_token;
        } else {
            return -1;
        }
    }
    private function email_notification_appointment($student_id = '', $appointment_id = '') {
        $data_appointment = $this->appointment_model->where('id', $appointment_id)->get();
        
        $data_student = $this->schedule_function->convert_book_schedule(($this->identity_model->get_gmt($student_id)[0]->minutes), strtotime($data_appointment->date), $data_appointment->start_time, $data_appointment->end_time);
        $data_coach = $this->schedule_function->convert_book_schedule(($this->identity_model->get_gmt($data_appointment->coach_id)[0]->minutes), strtotime($data_appointment->date), $data_appointment->start_time, $data_appointment->end_time);
        $gmt_student = $this->identity_model->get_gmt($student_id)[0]->timezone;
        $gmt_coach = $this->identity_model->get_gmt($data_appointment->coach_id)[0]->timezone;
        
       
        // coach and student identity to be used on sending email or creating notifaction database
        $email = $this->user_model->where('id', $data_appointment->coach_id)->or_where('id', $data_appointment->student_id)->dropdown('id', 'email');
        $fullname = $this->identity_model->get_identity('profile')->where('user_id', $data_appointment->coach_id)->or_where('user_id', $data_appointment->student_id)->dropdown('user_id', 'fullname');
        // tube name for messaging action
        $tube = 'com.live.email';
        // tube name for messaging notification
        $database_tube = 'com.live.database';

        $data = array(
            'subject' => 'Session Reminder',
            'email' => $email[$student_id],
            //'content' => 'You have an appointment with coach ' . $fullname[$data_appointment->coach_id] . ' , please prepare yourself 5 minutes before start the session at ' . date('l jS \of F Y', $data_student['date']) . ' ' . $data_student['start_time'] . ' until ' . $data_student['end_time'] . ' ' . $gmt_student,
        );
        $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Session Reminder')
                .$this->email_structure->content('You have an appointment with coach ' . $fullname[$data_appointment->coach_id] . ' , please prepare yourself 5 minutes before start the session at ' . date('l jS \of F Y', $data_student['date']) . ' ' . $data_student['start_time'] . ' until ' . $data_student['end_time'] . ' ' . $gmt_student)
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');

        // after booked, sending email to student
        $this->queue->push($tube, $data, 'email.send_email');

        //after booked, sending email to coach
        $data['email'] = $email[$data_appointment->coach_id];
        //$data['content'] = 'You have an appointment with student ' . $fullname[$student_id] . ' , please prepare yourself 5 minutes before start the session at ' . date('l jS \of F Y', $data_coach['date']) . ' ' . $data_coach['start_time'] . ' until ' . $data_coach['end_time'] . ' ' . $gmt_coach;
        $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Session Reminder')
                .$this->email_structure->content('You have an appointment with student ' . $fullname[$student_id] . ' , please prepare yourself 5 minutes before start the session at ' . date('l jS \of F Y', $data_coach['date']) . ' ' . $data_coach['start_time'] . ' until ' . $data_coach['end_time'] . ' ' . $gmt_coach)
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');
        
        $this->queue->push($tube, $data, 'email.send_email');

        // after booked, creating notification for student and coach
        $student_notification = array(
            'user_id' => $student_id,
            'description' => 'Your session will be started at ' . date('l jS \of F Y', $data_student['date']) . ' from ' . $data_student['start_time'] . ' until ' . $data_student['end_time'] . ' with coach ' . $fullname[$data_appointment->coach_id] . ' ' . $gmt_student,
            'status' => '2'
        );
        $coach_notification = array(
            'user_id' => $data_appointment->coach_id,
            'description' => 'Your session will be started at ' . date('l jS \of F Y', $data_coach['date']) . ' from ' . $data_coach['start_time'] . ' until ' . $data_coach['end_time'] . ' with student ' . $fullname[$student_id] . ' ' . $gmt_coach,
            'status' => '2'
        );
        $this->db->trans_begin();
        $this->user_notification_model->insert($student_notification);
        $this->user_notification_model->insert($coach_notification);
        if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            $this->messages->add('Try again, something wrong while inserting/updating data to database', 'warning');
            return;
        }
        $this->db->trans_commit();

        // 2 hours before session time
        $reminder = $this->time_reminder_before_session($data_appointment->date . " " . $data_appointment->start_time, (10 * 60));
        // reminder for student to rate coach after session time finished
        $reminder2 = $this->time_reminder_before_session($data_appointment->date . " " . $data_appointment->end_time, (40 * 60));

        if ($reminder2 && $reminder2 >= 0) {
            // sending email if the status of appointment still active
            $data['subject'] = 'Rate Coach';
            $data["appointment_id"] = $data_appointment->id;
            //$data['content'] = 'The session with coach ' . $fullname[$data_appointment->coach_id] . ' has been done. Please rate the coach.';
            $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Rate Coach')
                .$this->email_structure->content('The session with coach ' . $fullname[$data_appointment->coach_id] . ' has been done. Please rate the coach.')
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');
            
            $data['email'] = $email[$student_id];
            // email to remind coach and student to attend the session before it begin
            $this->queue->later($reminder2, $tube, $data, 'email.email_valid_appointment');

            // notification
            // update student's notification for messaging
            $student_notification['user_id'] = $student_id;
            $student_notification['description'] = 'The session with coach ' . $fullname[$data_appointment->coach_id] . ' has been done. Please rate the coach.';
            $student_notification['status'] = 2;
            $student_notification['dcrea'] = time();
            $student_notification['dupd'] = time();

            $data_reminder_student = array(
                'table' => 'user_notifications',
                'content' => $student_notification,
                'appointment_id' => $data_appointment->id
            );

            $this->queue->later($reminder2, $database_tube, $data_reminder_student, 'database.insert_while_appointment_still_valid');

            // for creating  rating of session
            $session_rating['appointment_id'] = $data_appointment->id;
            $session_rating['status'] = 'unrated';
            $session_rating['dcrea'] = time();
            $session_rating['dupd'] = time();

            $data_rating = array(
                'table' => 'coach_ratings',
                'content' => $session_rating,
                'appointment_id' => $data_appointment->id
            );

            $this->queue->later($reminder2, $database_tube, $data_rating, 'database.insert_while_appointment_still_valid');
        }

        if ($reminder && $reminder > 0) {
            // sending email if the status of appointment still active
            $data['subject'] = 'Last Session Reminder';
            $data["appointment_id"] = $data_appointment->id;
            //$data['content'] = 'Soon you will have an appointment with coach ' . $fullname[$data_appointment->coach_id] . ', please prepare yourself 5 minutes before start the session at ' . $data_student['date'] . ' ' . $data_student['start_time'] . ' until ' . $data_student['end_time'] . ' ' . $gmt_student;
            $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Last Session Reminder')
                .$this->email_structure->content('Soon you will have an appointment with coach ' . $fullname[$data_appointment->coach_id] . ', please prepare yourself 5 minutes before start the session at ' . $data_student['date'] . ' ' . $data_student['start_time'] . ' until ' . $data_student['end_time'] . ' ' . $gmt_student)
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');

            // email to remind coach and student to attend the session before it begin
            $this->queue->later($reminder, $tube, $data, 'email.email_valid_appointment');
            $data['email'] = $email[$student_id];
            //$data['content'] = 'Soon you will have an appointment with student ' . $fullname[$student_id] . ', please prepare yourself 5 minutes before start the session at ' . $data_coach['date'] . ' ' . $data_coach['start_time'] . ' until ' . $data_coach['end_time'];
            $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Last Session Reminder')
                .$this->email_structure->content('Soon you will have an appointment with student ' . $fullname[$student_id] . ', please prepare yourself 5 minutes before start the session at ' . $data_coach['date'] . ' ' . $data_coach['start_time'] . ' until ' . $data_coach['end_time'])
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');
            
            $this->queue->later($reminder, $tube, $data, 'email.email_valid_appointment');

            // creating notification reminder for student and coach
            $student_notification['user_id'] = $student_id;
            $student_notification['description'] = 'Reminder! Your appointment will be started at ' . date('l jS \of F Y', $data_student['date']) . ' from ' . $data_student['start_time'] . ' until ' . $data_student['end_time'] . ' with coach ' . $fullname[$data_appointment->coach_id] . ' ' . $gmt_student;
            $student_notification['status'] = 2;
            $student_notification['dcrea'] = time();
            $student_notification['dupd'] = time();

            // update coach's notification for messaging
            $coach_notification['user_id'] = $data_appointment->coach_id;
            $coach_notification['description'] = 'Reminder! Your appointment will be started at ' . date('l jS \of F Y', $data_coach['date']) . ' from ' . $data_coach['start_time'] . ' until ' . $data_coach['end_time'] . ' with student ' . $fullname[$data_appointment->student_id] . ' ' .$gmt_coach;
            $coach_notification['status'] = 2;
            $coach_notification['dcrea'] = time();
            $coach_notification['dupd'] = time();

            // student's data for reminder messaging
            // IMPORTANT : array index in content must be in mutual with table field in database
            $data_student = array(
                'table' => 'user_notifications',
                'content' => $student_notification,
                'appointment_id' => $data_appointment->id
            );

            // coach's data for reminder messaging
            // IMPORTANT : array index in content must be in mutual with table field in database
            $data_coach = array(
                'table' => 'user_notifications',
                'content' => $coach_notification,
                'appointment_id' => $data_appointment->id
            );

            $this->queue->later($reminder, $database_tube, $data_student, 'database.insert_while_appointment_still_valid');
            $this->queue->later($reminder, $database_tube, $data_coach, 'database.insert_while_appointment_still_valid');
        }
    }
    
    private function rollback_appointment($student_id = '', $coach_id = '', $date = '', $start_time = '', $end_time = '', $token = '') {
        // deleting appointment
        $this->appointment_model->where('coach_id', $coach_id)->where('student_id', $student_id)->where('date', $date)->where('start_time', $start_time)->where('end_time', $end_time)->where('status', 'active')->delete();
        // updating remaining student token
        $data = array(
            'token_amount' => $token,
        );
        $id = $this->identity_model->get_identity('token')->where('user_id', $student_id)->get();
        $this->identity_model->get_identity('token')->update($id->id, $data);
    }
    
    private function create_token_history($student_id='', $appointment_id = '', $coach_cost = '', $remain_token = '', $status='') {
        $appointment = $this->appointment_model->get_appointment($appointment_id);
        $partner_id = $this->auth_manager->partner_id($student_id);
        $organization_id = '';
        $organization_id = $this->db->select('gv_organizations.id')
                  ->from('gv_organizations')
                  ->join('users', 'users.organization_code = gv_organizations.organization_code')
                  ->where('users.id', $student_id)
                  ->get()->result();

        if(empty($organization_id)){
            $organization_id = '';
        }else{
            $organization_id = $organization_id[0]->id;
        }
        
        if(!$appointment){
            $this->messages->add('Invalid apppointment id',  'warning');
            redirect('student_partner/create/'.$student_id);
        }
        $token_history = array(
            'appointment_id' => $appointment_id,
            'user_id' => $student_id,
            'partner_id' => $partner_id,
            'organization_id' => $organization_id,
            'transaction_date' => strtotime(date('d-m-Y')),
            'description' => 'Session with '.$appointment[0]->coach_fullname .' at '. $appointment[0]->date .' '. $appointment[0]->start_time . ' until ' . $appointment[0]->end_time,
            'token_amount' => $coach_cost,
            'token_status_id' => $status,
            'balance' => $remain_token
        );
        if ($this->token_histories_model->insert($token_history)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function detail($id = '') {
        $this->template->title = 'Find Coach';
        $vars = array(
            'coaches' => $this->identity_model->get_coach_identity($id),
        );
        $this->template->content->view('default/contents/find_coach/detail', $vars);
        $this->template->publish();
    }
    
    public function create_session($host_id = '', $meeting_identifier = '') {
        $webex_host = $this->webex_host_model->select('subdomain_webex, partner_id, webex_id, timezones, password')->where('id', $host_id)->get();

        if (!$webex_host || !$meeting_identifier) {
            $this->messages->add('Invalid Host ID or Meeting Identifier', 'error');
            redirect('coach/upcoming_session');
        }

        // Input attendance/s
        if (substr($meeting_identifier, 0, 1) == 'c') {
            $appointment = $this->class_member_model->get_appointment_for_webex_invitation_xml($meeting_identifier);
            $meeting = $this->class_meeting_day_model->select('*')->where('id', substr($meeting_identifier, 1))->get();

            if (!$appointment || !$meeting) {
                $this->messages->add('Invalid Meeting Identifier', 'error');
                redirect('coach/upcoming_session');
            }
            foreach ($appointment as $a) {
                $attendance .= htmlspecialchars("<attendee><person><name>$a->student_fullname</name><email>$a->student_email</email></person><role>ATTENDEE</role><joinStatus>INVITE</joinStatus></attendee>");
            }
            $conf_name = "Class " . $appointment[0]->class_name . " on " . $appointment[0]->date . " at " . $appointment[0]->start_time . " " . $appointment[0]->end_time . " With Coach " . $appointment[0]->coach_fullname;
            $max_user = 1;
            $duration = 20;
        } else {
            $appointment = $this->appointment_model->get_appointment_for_webex_invitation_xml($meeting_identifier);
            $meeting = $this->appointment_model->select('*')->where('id', $meeting_identifier)->get();

            if (!$appointment) {
                $this->messages->add('Invalid Meeting Identifier', 'error');
                redirect('coach/upcoming_session');
            }
            $attendance = htmlspecialchars("<attendee><person><name>{$appointment[0]->student_fullname}</name><email>{$appointment[0]->student_email}</email></person><role>ATTENDEE</role><joinStatus>INVITE</joinStatus></attendee>");
            $conf_name = "Student " . $appointment[0]->student_fullname . " on " . $appointment[0]->date . " at " . $appointment[0]->start_time . " " . $appointment[0]->end_time . " With Coach " . $appointment[0]->coach_fullname;
            $max_user = 1;
            $duration = 20;
        }

        $attendance = htmlspecialchars_decode($attendance);
        $password = $this->common_function->generate_random_string(6);
        $date = str_replace('-', '/', substr($appointment[0]->date, 5) . '-' . (substr($appointment[0]->date, 0, 4))) . ' ' . $appointment[0]->start_time;

        $XML_SITE = $webex_host->subdomain_webex . ".webex.com";
        $XML_PORT = "443";

        // Set calling user information
        $d["UID"] = $webex_host->webex_id; // WebEx username
        $d["PWD"] = $webex_host->password; // WebEx password
        $d["SNM"] = $webex_host->subdomain_webex; //Demo Site SiteName
        $d["PID"] = $webex_host->partner_id; //Demo Site PartnerID

        $d["XML"] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <serv:message xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
                xmlns:serv=\"http://www.webex.com/schemas/2002/06/service\">
                <header>
                    <securityContext>
                    <webExID>{$d["UID"]}</webExID>
                    <password>{$d["PWD"]}</password>
                    <siteName>{$d["SNM"]}</siteName>
                    <partnerID>{$d["PID"]}</partnerID>
                    </securityContext>
                </header>
                <body>
                <bodyContent xsi:type=\"java:com.webex.service.binding.meeting.CreateMeeting\"
                    xmlns:meet=\"http://www.webex.com/schemas/2002/06/service/meeting\">	
                    <accessControl>
                        <meetingPassword>{$password}</meetingPassword>
                    </accessControl>
                    <metaData>
                        <confName>{$conf_name}</confName>
                        <agenda>Test</agenda>
                    </metaData>
                    <participants>
                        <maxUserNumber>4</maxUserNumber>
                        <attendees>
                            {$attendance}
                        </attendees>
                    </participants>
                    <enableOptions>
                        <chat>true</chat>
                        <poll>true</poll>
                        <voip>true</voip>
                        <audioVideo>true</audioVideo>
                        <attendeeList>true</attendeeList>
                        <chatHost>true</chatHost>
                        <chatPresenter>true</chatPresenter>
                        <chatAllAttendees>true</chatAllAttendees>
                        <meetingRecord>true</meetingRecord>
                        <autoDeleteAfterMeetingEnd>false</autoDeleteAfterMeetingEnd>
                    </enableOptions>
                    <schedule>
                        <startDate>{$date}</startDate>
                        <joinTeleconfBeforeHost>false</joinTeleconfBeforeHost>
                        <duration>{$duration}</duration>
                        <timeZoneID>{$webex_host->timezones}</timeZoneID>
                    </schedule>
                    <telephony>
                        <telephonySupport>NONE</telephonySupport>
                    </telephony>
                <attendeeOptions>
                    <emailInvitations>true</emailInvitations>
                </attendeeOptions>
                </bodyContent>
                </body>
                </serv:message>";

        $URL = "http://{$XML_SITE}/WBXService/XMLService";
        $result = $this->common_function->post_it($d, $URL, $XML_PORT);

        // Clean xml
        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
        $simple_xml = simplexml_load_string($clean_result);
        if ($simple_xml === false) {
            return false;
        } else {
            if ($simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->result == 'SUCCESS') {
                $tube = 'com.live.database';
                if (substr($meeting_identifier, 0, 1) == 'c') {
                    $data = Array(
                        'class_meeting_id' => substr($meeting_identifier, 1),
                        'host_id' => $host_id,
                        'webex_meeting_number' => $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('meet', true)->meetingkey,
                        'status' => 'SCHE',
                        'password' => $password
                    );
                    if ($this->webex_class_model->insert($data)) {
                        $student_emails = $this->class_member_model->get_appointment_for_webex_invitation($meeting_identifier);
                        if ($student_emails) {
                            foreach ($student_emails as $se) {
                                $student_notification [] = array(
                                    'user_id' => $se->student_id,
                                    'description' => 'You just invited by ' . $se->coach_name . ' to join a WebEx Meeting on ' . $se->date . ' at ' . $se->start_time . ' until ' . $se->end_time . '. Check your email to see the detail invitation!',
                                    'status' => 2,
                                    'dcrea' => time(),
                                    'dupd' => time(),
                                );
                            }

                            // IMPORTANT : array index in content must be in mutual with table field in database
                            foreach ($student_notification as $sn) {
                                $data = array(
                                    'table' => 'user_notifications',
                                    'content' => $sn
                                );
                                // messaging inserting data notification
                                $this->queue->push($tube, $data, 'database.insert');
                            }
                        }else{
                            return false;
                        }
                        return true;
                    }else{
                        return false;
                    }
                } else {
                    $data = Array(
                        'meeting_type' => '11', //Standard Meeting Center
                        'number_attendance' => '1',
                        'appointment_id' => $meeting_identifier,
                        'host_id' => $host_id,
                        'webex_meeting_number' => $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('meet', true)->meetingkey,
                        'status' => 'SCHE',
                        'password' => $password
                    );
                    if ($this->webex_model->insert($data)) {
                        $student_emails = $this->appointment_model->get_appointment_for_webex_invitation($meeting_identifier);
                        if ($student_emails) {
                            $student_notification = array(
                                'user_id' => $student_emails[0]->student_id,
                                'description' => 'You just invited by ' . $student_emails[0]->coach_name . ' to join a WebEx Meeting on ' . $student_emails[0]->date . ' at ' . $student_emails[0]->start_time . ' until ' . $student_emails[0]->end_time . '. Check your email to see the detail invitation!',
                                'status' => 2,
                                'dcrea' => time(),
                                'dupd' => time(),
                            );
                            $data = array(
                                'table' => 'user_notifications',
                                'content' => $student_notification
                            );
                            // messaging inserting data notification
                            $this->queue->push($tube, $data, 'database.insert');
                        }
                        return true;
                    }else{
                        return false;
                    }
                }
            } else {
                //$error = $simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->reason;
                return false;
            }
        }
    }
}
