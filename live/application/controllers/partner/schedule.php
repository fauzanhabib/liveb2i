<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Author   : Ponel Panjaitan (ponel@pistarlabs.co.id)
 */
class schedule extends MY_Site_Controller {

    var $partner = null;
    var $coaches = null;
    var $status = null;

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
        $this->load->library('queue'); 
        $this->load->library('email_structure');

        $this->partner = $this->user_profile_model->select('partner_id')->where('user_id', $this->auth_manager->userid())->get();
        $this->coaches = $this->user_profile_model->get_coaches($this->partner->partner_id);
        $this->status = array('active' => 'active', 'pending' => 'pending', 'reschedule' => 'reschedule', 'cancel' => 'cancel');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'PRT') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = 'Schedule';
        $vars = array(
            'user_profiles' => $this->user_profile_model->get_students($this->partner->partner_id)
        );
        $this->template->content->view('default/contents/partner/schedule/index', $vars);
        $this->template->publish();
    }
    
    
    // Test
    public function test() {
        $this->template->title = 'test';
        $this->template->content->view('default/contents/partner/schedule/test');
        $this->template->publish();
    }

    // manage
    public function manage($id) {
        $this->template->title = 'Manage Schedule';
        $appointment = $this->appointment_model->get_appointment_for_upcoming_session('student_id', '', '', $id);
        $user = $this->user_profile_model->select('fullname')->where('user_id', $id)->get();
        $vars = array(
            'appointments' => $appointment,
            'student_id' => $id,
            'user' => $user 
        );
        $this->template->content->view('default/contents/partner/schedule/manage', $vars);
        $this->template->publish();
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
        
        $this->template->content->view('default/contents/partner/schedule/form', $vars);
        $this->template->publish();*/
        
        $this->template->title = 'Find Coach';
        
        $coaches = $this->identity_model->get_coach_identity();
        
        $vars = array(
            'coaches' => $coaches,
            'student_id' => $student_id,
            'rating' => $this->coach_rating_model->get_average_rate()
        );

        $this->template->content->view('default/contents/partner/schedule/list_coach', $vars);
        $this->template->publish();
    }

    public function add($student_id) {
        if(!$student_id){
            $this->messages->add('Invalid ID', 'danger');
            redirect('partner/schedule');
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
            $this->messages->add(validation_errors(), 'danger');
            $this->add($student_id);
            return;
        }

        $student_email = $this->user_model->select('email')->where('id', $student_id)->get();
        $coach_email = $this->user_model->select('email')->where('id', $this->input->post('coach_id'))->get();
        
        $student_name = $this->user_profile_model->select('fullname')->where('user_id', $student_id)->get();
        $coach_name = $this->user_profile_model->select('fullname')->where('user_id', $this->input->post('coach_id'))->get();
        
        // Tube name for messaging action
        $tube = 'com.live.email';
        // Email's content that will be send to partner to inform that the student has been approved 
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
        
        $this->messages->add('Inserting Appointment succeeded', 'success');
        redirect('partner/schedule/manage/' . $student_id);
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
        $this->template->content->view('default/contents/partner/schedule/form', $vars);
        $this->template->publish();
    }

    public function do_reschedule($student_id='') {
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('account/identity/detail/profile');
        }

        if (!$this->input->post('student_appointment_id')) {
            $this->messages->add('Invalid ID', 'danger');
            redirect('partner/schedule');
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
            $this->messages->add(validation_errors(), 'danger');
            return;
        }
        
        // Updating appointment status become reschedule
        if (!$this->appointment_model->update($this->input->post('student_appointment_id'), $update_appointment)) {
            $this->messages->add(validation_errors(), 'danger');
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
            $this->messages->add(validation_errors(), 'danger');
            return;
        }
        if (!$this->db->trans_status()){
            $this->db->trans_rollback();
            $this->messages->add('Try again, something wrong while inserting/updating data to database', 'danger');
            return;
        }
            
        $this->db->trans_commit();
        $this->messages->add('Update Succeeded', 'success');
        redirect('partner/schedule/manage/' . $this->input->post('student_id'));
    }

    public function cancel($student_id='', $id = '') {
        $this->template->title = 'Delete Appointment';
        
        $cancel_appointment = array(
            'status' => 'cancel'
        );
        
        // Updating Appointment
        if (!$this->appointment_model->update($id, $cancel_appointment)) {
            $this->message->add(validation_errors(), 'danger');
            $this->messages->add("Canceling Appointment Failed", "error");
            return;
        }
        $this->messages->add("Canceling Appointment Succeeded", "success");
        redirect('partner/schedule/manage/' . $student_id);
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

        $this->template->content->view('default/contents/partner/schedule/form', $vars);
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

        $this->template->content->view('default/contents/partner/schedule/form', $vars);
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
            $this->template->content->view('default/contents/find_coach/availability', $vars);

            //publish template
            $this->template->publish();
        }

        if (!$this->is_date_available(trim($date_), 2)) {
            //$this->messages->add('Date is not valid ' . $date_, 'danger');
            //redirect('student/find_coaches/schedule_detail/' . $coach_id);
            $vars = array();
            $this->template->content->view('default/contents/find_coach/availability', $vars);
        }

        if ($this->is_day_off($coach_id, $date_) == true) {
            //$this->messages->add('Coach is not available on ' . $date_, 'danger');
            //redirect('student/find_coaches/schedule_detail/' . $coach_id);
            $vars = array();
            $this->template->content->view('default/contents/find_coach/availability', $vars);
        }
        //getting the day of $date
        $date = strtotime($date_);
        $day = strtolower(date('l', $date));


        //getting all data
        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
        $offwork = $this->offwork_model->get_offwork($coach_id, $schedule_data->day);
        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student
        $appointment = $this->appointment_model->select('id, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->get_all();
        // appointment with status temporary considered available for other student but not for student who is in the appointment and 
        // appointment where the student has make an appoinment on the specific date, so there will be no the same start time and end time to be shown to the student from other coach
        $appointment_student = $this->appointment_model->select('id, start_time, end_time')->where('student_id', $student_id)->where('status not like', 'cancel')->where('date', date("Y-m-d", $date))->get_all();
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
        $appointment_start_time_temp = array();
        $appointment_end_time_temp = array();
        foreach ($appointment as $a) {
            $appointment_start_time_temp[$a->start_time] = $a->start_time;
            $appointment_end_time_temp[$a->end_time] = $a->end_time;
        }
        // storing class meeting days to appointment temp
        foreach ($appointment_class as $a) {
            $appointment_start_time_temp[$a->start_time] = $a->start_time;
            $appointment_end_time_temp[$a->end_time] = $a->end_time;
        }
        foreach ($appointment_student as $a) {
            $appointment_start_time_temp[$a->start_time] = $a->start_time;
            $appointment_end_time_temp[$a->end_time] = $a->end_time;
        }

        $availability_temp = array();
        $availability_exist;
        
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
                    if (in_array($availability_exist['start_time'], $appointment_start_time_temp) && in_array($availability_exist['end_time'], $appointment_end_time_temp)) {
                        // no action
                    } else {
                        // storing availability that still active and not been boooked yet
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
        $vars = array(
            'availability' => $availability_temp,
            'search_by' => $search_by,
            'student_id' => $student_id,
            'coach_id' => $coach_id,
            'date' => $date,
            'cost' => $this->coach_token_cost_model->select('token_for_student')->where('coach_id', $coach_id)->get()
        );
        $this->template->content->view('default/contents/partner/schedule/availability', $vars);

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
        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id', $id)->get_all();

        if (!$schedule_data) {
            redirect('partner/schedule/detail/' . $id);
        }
        $schedule = array();
        foreach ($schedule_data as $s) {
            $offwork = $this->offwork_model->get_offwork($id, $s->day);
            //offwork by day
            $start_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->start_time);
            $end_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->end_time);
            //schedule by day
            $start_time_schedule = DateTime::createFromFormat('H:i:s', $s->start_time);
            $end_time_schedule = DateTime::createFromFormat('H:i:s', $s->end_time);

            $schedule_temp = array();
            if ($start_time_offwork == $start_time_schedule && $start_time_offwork == $end_time_schedule && $end_time_offwork == $start_time_schedule && $end_time_offwork == $end_time_schedule) {
                $schedule_temp[0] = array(
                    'start_time' => $s->start_time,
                    'end_time' => $s->end_time,
                );
            } else if ($start_time_offwork >= $start_time_schedule && $start_time_offwork <= $end_time_schedule && $end_time_offwork >= $start_time_schedule && $end_time_offwork <= $end_time_schedule) {
                $schedule_temp[0] = array(
                    'start_time' => $s->start_time,
                    'end_time' => $offwork[0]->start_time,
                );
                $schedule_temp[1] = array(
                    'start_time' => $offwork[0]->end_time,
                    'end_time' => $s->end_time,
                );
            } else {
                $schedule_temp[0] = array(
                    'start_time' => $s->start_time,
                    'end_time' => $s->end_time,
                );
            }

            $schedule[$s->day] = $schedule_temp;
            unset($schedule_temp);
        }

        // creating combo box based on surrent day until last day of the year
        // will improve option based on off day or vacation of coach 
        $year = date("Y");
        $date = time();

        //$datetime1 = date_create(date("Y-m-d"));
        $datetime1 = date_create(date("Y-m-d", strtotime(date("Y-m-d") . "+2 days")));
        $datetime2 = date_create($year . '-12-31');
        $interval = date_diff($datetime1, $datetime2);

        $num_days = $interval->days;

        // storing day off of coach and banned it from availabilty date
        $day_off = $this->coach_day_off_model->select('start_date, end_date')->where('coach_id', $id)->where('status', 'active')->get();

        $day_off_start_date = date_create(@$day_off->start_date);
        $day_off_end_date = date_create(@$day_off->end_date);
        $interval2 = date_diff($day_off_start_date, $day_off_end_date);
        $num_days2 = $interval2->days;
        
        $vars = array(
            'coach_id' => $id,
            'schedule' => $schedule,
        );
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

        $this->template->content->view('default/contents/partner/schedule/summary_book', $vars);
        //publish template
        $this->template->publish();
    }
    
    public function book_single_coach($student_id = '', $coach_id = '', $date = '', $start_time = '', $end_time = '') {
        try {
            // First of all, let's begin a transaction
            //$this->db->trans_begin();
            // A set of queries; if one fails, an exception should be thrown
            $isValid = $this->isAvailable($coach_id, $date, $start_time, $end_time);
            
            if ($isValid) {
                $availability = $this->isOnAvailability($student_id, $coach_id, date('Y-m-d', $date));
                if (in_array(array('start_time' => $start_time, 'end_time' => $end_time), $availability)) {
                    // go to next step 
                    //exit;
                } else {
                    $this->messages->add('Invalid Time', 'danger');
                    //redirect('student/find_coaches/book_by_single_date/' . date("Y-m-d", $date));
                    redirect('partner/schedule/create/'.$student_id);
                }
                // begin the transaction to ensure all data created or modified structural
                $token_cost = $this->coach_token_cost_model->select('token_for_student')->where('coach_id', $coach_id)->get();
                
                // updating remaining token student
                $remain_token = $this->update_token($student_id, $token_cost->token_for_student);
                if ($this->db->trans_status() === true && $remain_token >= 0 && $this->isAvailable($coach_id, $date, $start_time, $end_time)) {
                    $appointment_id = $this->create_appointment($student_id, $coach_id, $date, $start_time, $end_time, 'active');
                    $valid_appointment = count($this->appointment_model->where('coach_id', $coach_id)->where('date', date('Y-m-d', $date))->where('start_time', $start_time)->where('end_time', $end_time)->where('status', 'active')->get_all());
                    if ($this->db->trans_status() === true && $appointment_id && $valid_appointment == 1) {
                        $this->create_token_history($student_id, $appointment_id, $token_cost->token_for_student, $remain_token, 1);
                        // messaging to send email and creating notification based on appointment
                        $this->email_notification_appointment($student_id, $appointment_id);
                        // transaction finished / all criteria has been fulfilled
                        
                        // setup video conference, webex
                        $available_host = $this->webex_host_model->get_available_host($appointment_id);
                        if($available_host){
                            $this->create_session($available_host[0]->id, $appointment_id);
                            $message = "Appointment booked, you will use Webex for your session";
                        }else{
                            $message = "Appointment booked, you will use Webex for your session";
                        }                        
                        
                        
                        $this->messages->add($message, 'success');
                        redirect('partner/schedule/create/' . $student_id);
                        //redirect('student/find_coaches/single_date/');
                    } else {
                        $this->rollback_appointment($coach_id, date("Y-m-d", $date), $start_time, $end_time, ($remain_token + $token_cost->token_for_student));
                        $this->messages->add('Fail to book appointment, please try again.', 'danger');
                        //redirect('student/find_coaches/book_by_single_date/' . date("Y-m-d", $date));
                        redirect('student/find_coaches/single_date/');
                    }
                } else {
                    $this->messages->add('Not Enough Token', 'danger');
                    //redirect('student/find_coaches/book_by_single_date/' . date("Y-m-d", $date));
                    redirect('partner/create/'.$student_id);
                }
            } else {
                $this->messages->add('Invalid Appointment', 'danger');
                //redirect('student/find_coaches/single_date');
                redirect('partner/create/'.$student_id);
            }


            // If we arrive here, it means that no exception was thrown
            // i.e. no query has failed, and we can commit the transaction
            //$this->db->trans_commit();
        } catch (Exception $e) {
            // An exception has been thrown
            // We must rollback the transaction
            $this->db->trans_rollback();
            $this->messages->add('An error has occured, please try again.', 'danger');
            //redirect('student/find_coaches/book_by_single_date/' . date("Y-m-d", $date));
            redirect('student/find_coaches/single_date/');
        }
    }
    
    private function isOnAvailability($student_id= '', $coach_id = '', $date_ = '') {
        if (!$date_ || !$coach_id) {
            redirect('account/identity/detail/profile');
        }

        if (!$this->is_date_available(trim($date_), 2)) {
            $this->messages->add('Date is not valid ' . $date_, 'danger');
            return false;
            //redirect('student/find_coaches/schedule_detail/' . $coach_id);
        }

        if ($this->is_day_off($coach_id, $date_) == true) {
            $this->messages->add('Coach is not available on ' . $date_, 'danger');
            return false;
            //redirect('student/find_coaches/schedule_detail/' . $coach_id);
        }
        //getting the day of $date
        $date = strtotime($date_);
        $day = strtolower(date('l', $date));


        //getting all data
        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
        $offwork = $this->offwork_model->get_offwork($coach_id, $schedule_data->day);
        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student
        $appointment = $this->appointment_model->select('id, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->get_all();
        // appointment with status temporary considered available for other student but not for student who is in the appointment and 
        // appointment where the student has make an appoinment on the specific date, so there will be no the same start time and end time to be shown to the student from other coach
        $appointment_student = $this->appointment_model->select('id, start_time, end_time')->where('student_id', $student_id)->where('status not like', 'cancel')->where('date', date("Y-m-d", $date))->get_all();
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
        $appointment_start_time_temp = array();
        $appointment_end_time_temp = array();
        foreach ($appointment as $a) {
            $appointment_start_time_temp[$a->start_time] = $a->start_time;
            $appointment_end_time_temp[$a->end_time] = $a->end_time;
        }
        // storing class meeting days to appointment temp
        foreach ($appointment_class as $a) {
            $appointment_start_time_temp[$a->start_time] = $a->start_time;
            $appointment_end_time_temp[$a->end_time] = $a->end_time;
        }
        foreach ($appointment_student as $a) {
            $appointment_start_time_temp[$a->start_time] = $a->start_time;
            $appointment_end_time_temp[$a->end_time] = $a->end_time;
        }



        $availability_temp = array();
        $availability_exist;
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
                    if (in_array($availability_exist['start_time'], $appointment_start_time_temp) && in_array($availability_exist['end_time'], $appointment_end_time_temp)) {
                        // no action
                    } else {
                        // storing availability that still active and not been boooked yet
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

        return $availability_temp;
    }
    
    private function isAvailable($coach_id = '', $date = '', $start_time = '', $end_time = '') {
        // getting the day of $date
        $day = strtolower(date('l', $date));

        // getting all data
        $schedule_data = $this->schedule_model->select('id, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
        $offwork = $this->offwork_model->get_offwork($coach_id, $day);

        // convert time to be able to compare
        // offwork by day
        $start_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->start_time);
        $end_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->end_time);
        //schedule by day
        $start_time_schedule = DateTime::createFromFormat('H:i:s', $schedule_data->start_time);
        $end_time_schedule = DateTime::createFromFormat('H:i:s', $schedule_data->end_time);

        // booking time
        $start_time_booking = DateTime::createFromFormat('H:i:s', $start_time);
        $end_time_booking = DateTime::createFromFormat('H:i:s', $end_time);

        // check if coach availability has been booked or nothing
        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student
        $appointment = $this->appointment_model->select('id, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->where('start_time', $start_time)->where('end_time', $end_time)->get();

        if ($appointment) {
            return false;
        } else if (!$appointment) {
            // compare booking time if there is offwork in the schedule
            if ($start_time_offwork >= $start_time_schedule && $start_time_offwork <= $end_time_schedule && $end_time_offwork >= $start_time_schedule && $end_time_offwork <= $end_time_schedule) {
                // compare booking time if its in available range -> start time schedule to start time offwork and end time schedule to end time offwork
                if ($start_time_booking >= $start_time_schedule && $start_time_booking <= $start_time_offwork && $end_time_booking >= $start_time_schedule && $end_time_booking <= $start_time_offwork) {
                    return true;
                } else if ($start_time_booking >= $end_time_offwork && $start_time_booking <= $end_time_schedule && $end_time_booking >= $end_time_offwork && $end_time_booking <= $end_time_schedule) {
                    return true;
                } else {
                    return false;
                }
            } else {
                // compare booking time if there is NO offwork in the schedule
                if ($start_time_booking >= $start_time_schedule && $end_time_booking <= $end_time_schedule) {
                    return true;
                } else {
                    return false;
                }
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

    private function create_appointment($student_id='', $coach_id = '', $date = '', $start_time = '', $end_time = '', $appointment_status = '') {
        $status = false;
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

        $isValid = $this->isAvailable($coach_id, $date, $start_time, $end_time);
        $this->db->trans_begin();
        if ($isValid) {
            // Inserting and checking
            $appointment_id = $this->appointment_model->insert($booked);
            $status = true;
        } else if (!$isValid) {
            $this->db->trans_rollback();
            $status = false;
        }

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
    private function email_notification_appointment($student_id='', $appointment_id = '') {
        $data_appointment = $this->appointment_model->where('id', $appointment_id)->get();
        $email = $this->user_model->where('id', $data_appointment->coach_id)->or_where('id', $data_appointment->student_id)->dropdown('id', 'email');
        $fullname = $this->identity_model->get_identity('profile')->where('user_id', $data_appointment->coach_id)->or_where('user_id', $data_appointment->student_id)->dropdown('user_id', 'fullname');
        // tube name for messaging action
        $tube = 'com.live.email';
        // tube name for messaging notification
        $database_tube = 'com.live.database';

        $data = array(
            'subject' => 'Session Reminder',
            'email' => $email[$student_id],
            //'content' => 'You have an appointment with coach ' . $fullname[$data_appointment->coach_id] . ' , please prepare yourself 5 minutes before start the session at ' . date('l jS \of F Y', strtotime($data_appointment->date)) . ' ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time,
        );
        $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Session Reminder')
                .$this->email_structure->content('You have an appointment with coach ' . $fullname[$data_appointment->coach_id] . ' , please prepare yourself 5 minutes before start the session at ' . date('l jS \of F Y', strtotime($data_appointment->date)) . ' ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time)
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');

        // after booked, sending email to student
        $this->queue->push($tube, $data, 'email.send_email');

        //after booked, sending email to coach
        $data['email'] = $email[$data_appointment->coach_id];
        //$data['content'] = 'You have an appointment with student ' . $fullname[$student_id] . ' , please prepare yourself 5 minutes before start the session at ' . date('l jS \of F Y', strtotime($data_appointment->date)) . ' ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time;
        $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Session Reminder')
                .$this->email_structure->content('You have an appointment with student ' . $fullname[$student_id] . ' , please prepare yourself 5 minutes before start the session at ' . date('l jS \of F Y', strtotime($data_appointment->date)) . ' ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time)
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');
        $this->queue->push($tube, $data, 'email.send_email');

        // after booked, creating notification for student and coach
        $student_notification = array(
            'user_id' => $student_id,
            'description' => 'Your session will be started at ' . date('l jS \of F Y', strtotime($data_appointment->date)) . ' from ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time . ' with coach ' . $fullname[$data_appointment->coach_id],
            'status' => '2'
        );
        $coach_notification = array(
            'user_id' => $data_appointment->coach_id,
            'description' => 'Your session will be started at ' . date('l jS \of F Y', strtotime($data_appointment->date)) . ' from ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time . ' with student ' . $fullname[$student_id],
            'status' => '2'
        );
        $this->db->trans_begin();
        $this->user_notification_model->insert($student_notification);
        $this->user_notification_model->insert($coach_notification);
        if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            $this->messages->add('Try again, something wrong while inserting/updating data to database', 'danger');
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

            $data_student = array(
                'table' => 'user_notifications',
                'content' => $student_notification,
                'appointment_id' => $data_appointment->id
            );

            $this->queue->later($reminder2, $database_tube, $data_student, 'database.insert_while_appointment_still_valid');

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
            //$data['content'] = 'Soon you will have an appointment with coach ' . $fullname[$data_appointment->coach_id] . ', please prepare yourself 5 minutes before start the session at ' . $data_appointment->date . ' ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time;
            $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Last Session Reminder')
                .$this->email_structure->content('Soon you will have an appointment with coach ' . $fullname[$data_appointment->coach_id] . ', please prepare yourself 5 minutes before start the session at ' . $data_appointment->date . ' ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time)
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');

            // email to remind coach and student to attend the session before it begin
            $this->queue->later($reminder, $tube, $data, 'email.email_valid_appointment');
            $data['email'] = $email[$student_id];
            //$data['content'] = 'Soon you will have an appointment with student ' . $fullname[$student_id] . ', please prepare yourself 5 minutes before start the session at ' . $data_appointment->date . ' ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time;
            $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Last Session Reminder')
                .$this->email_structure->content('Soon you will have an appointment with student ' . $fullname[$student_id] . ', please prepare yourself 5 minutes before start the session at ' . $data_appointment->date . ' ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time)
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');
            
            $this->queue->later($reminder, $tube, $data, 'email.email_valid_appointment');

            // creating notification reminder for student and coach
            $student_notification['user_id'] = $student_id;
            $student_notification['description'] = 'Reminder! Your appointment will be started at ' . date('l jS \of F Y', strtotime($data_appointment->date)) . ' from ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time . ' with coach ' . $fullname[$data_appointment->coach_id];
            $student_notification['status'] = 2;
            $student_notification['dcrea'] = time();
            $student_notification['dupd'] = time();

            // update coach's notification for messaging
            $coach_notification['user_id'] = $data_appointment->coach_id;
            $coach_notification['description'] = 'Reminder! Your appointment will be started at ' . date('l jS \of F Y', strtotime($data_appointment->date)) . ' from ' . $data_appointment->start_time . ' until ' . $data_appointment->end_time . ' with student ' . $fullname[$data_appointment->student_id];
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
        
        if(!$appointment){
            $this->messages->add('Invalid apppointment id',  'danger');
            redirect('partner/create/'.$student_id);
        }
        $token_history = array(
            'user_id' => $student_id,
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
