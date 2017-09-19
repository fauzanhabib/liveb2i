<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class day_off extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('coach_day_off_model');
        $this->load->model('user_notification_model');
        // for coach detail profile
        $this->load->model('identity_model');
        // for messaging action and timing
        $this->load->library('queue');
        $this->load->library('email_structure');
        $this->load->library('send_email');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CCH') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        //echo(strtotime(date('Y-m-d'))); exit;
        $this->template->title = 'Day Off';

        $day_off_data = $this->coach_day_off_model->select('id, start_date, remark, end_date, status')->where('coach_id', $this->auth_manager->userid())->order_by('start_date', 'asc')->get_all();

        $vars = array(
            'data' => $day_off_data,
        );
        $this->template->content->view('default/contents/day_off/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function add() {
        $this->template->title = 'Add Day Off';
        $vars = array(
            'form_action' => 'create'
        );
        $this->template->content->view('default/contents/day_off/form', $vars);
        $this->template->publish();
    }

    public function create() {
        // Creating a student user data must be followed by creating profile data and status still disable/need approval from admin
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('coach/day_off');
        }

        $rules = array(
            array('field'=>'start_date', 'label' => 'Start Date', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'end_date', 'label' => 'End Date', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'remark', 'label' => 'Remark', 'rules'=>'trim|required|xss_clean')
        );
        
        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            $this->edit($this->session->userdata("day_off_id"));
            return;
        }
        $default_status = 'pending';
        if(!$this->checkSchedule($this->auth_manager->userid(),$this->input->post('start_date'), $this->input->post('end_date'))){
            $default_status = 'booked';
        }
        // inserting user data
        $day_off = array(
            'coach_id' => $this->auth_manager->userid(),
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
            'remark' => $this->input->post('remark'),
            'status' => $default_status,
        );
        
        if (!$this->isValidDayOff($day_off['start_date'], $day_off['end_date'])){
            $this->messages->add('Invalid date', 'danger');
            $this->add();
            return;
        }
        
        // if(!$this->checkSchedule($this->auth_manager->userid(),$day_off['start_date'], $day_off['end_date'])){
        //     $this->messages->add('You have already booked by your student', 'danger');
        //     $this->add();
        //     return;
        // }

        if(!$this->checkDayoff($this->auth_manager->userid(),$day_off['start_date'], $day_off['end_date'])){
            $this->messages->add('You have already requested on this date', 'danger');
            $this->add();
            return;
        }

        if(!$this->checkApproval($this->auth_manager->userid(),$day_off['start_date'], $day_off['end_date'])){
            $this->messages->add('Your request on this date have already declined', 'danger');
            $this->add();
            return;
        }

        // Inserting and checking to users table then storing insert_id into $insert_id
        $this->db->trans_begin();
        $day_off_id = $this->coach_day_off_model->insert($day_off);
        if (!$day_off_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            redirect('coach/day_off');
            return;
        }
        $this->db->trans_commit();

        if ($day_off_id) {
            $id_to_email_address = $this->user_model->dropdown('id', 'email');
            $id_to_name = $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname');
            // tube name for messaging action
            $tube = 'com.live.email';

            $partner_admin = $this->identity_model->get_partner_identity('', '', $this->auth_manager->partner_id(),'', '3');
            $name = $this->user_profile_model->select('user_id, fullname')->where('user_id', $this->auth_manager->userid())->get_all();

            $data = array(
                'subject' => 'Approve Coach Day Off',
                //'email' => $id_to_email_address[$this->auth_manager->userid()],
                //'content' => 'Coach ' . $id_to_name[$day_off['coach_id']] . ' asking for day off approval at ' . date('l jS \of F Y', strtotime($day_off['start_date'])) . ' until ' . date('l jS \of F Y', strtotime($day_off['end_date'])) . '. Please approve or decline the day off. ',
            );
            $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Approve Coach Day Off')
                .$this->email_structure->content('Coach ' . $id_to_name[$day_off['coach_id']] . ' asking for day off approval at ' . date('l jS \of F Y', strtotime($day_off['start_date'])) . ' until ' . date('l jS \of F Y', strtotime($day_off['end_date'])) . '. Please approve or decline the day off. ')
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');

            // after creating day off, sending email to every partner admin
            // foreach ($partner_admin as $p) {
            //     $data['email'] = $p->email;
            //     $this->queue->push($tube, $data, 'email.send_email');
            // }

            // creating data notifications
            // for notifications

            $partner_notification = array(
                'user_id' => $this->auth_manager->userid(),
                // 'description' => 'Coach ' . $id_to_name[$day_off['coach_id']] . ' asking for day off approval at ' . date('l jS \of F Y', strtotime($day_off['start_date'])) . ' until ' . date('l jS \of F Y', strtotime($day_off['end_date'])),
                'description' => 'New coach day off requested, please approve/decline',
                'status' => '2',
            );

            foreach ($partner_admin as $p) {
                $partner_notification['user_id'] = $p->id;
                $this->user_notification_model->insert($partner_notification);
            }

            //echo($booked['date']. " " .$booked['start_time']); exit;
            //$this->db->trans_commit();
            //echo($reminder); exit;
        }

        $this->send_email->coach_request_dayoff($partner_admin[0]->email, 'requested', $name[0]->fullname, $this->input->post('start_date'), $this->input->post('end_date'), $this->input->post('remark'), $partner_admin[0]->fullname);
        
        $this->messages->add('Requesting Day Off Succeded', 'success');
        redirect('coach/day_off');
    }

    public function edit($id = '') {
        // setting id for updating data
        $this->session->set_userdata("day_off_id", $id);

        $this->template->title = 'Edit Day Off';
        $vars = array(
            'form_action' => 'update',
            'data' => $this->coach_day_off_model->select('id, start_date, end_date, remark')->where('id', $id)->where('coach_id', $this->auth_manager->userid())->get(),
        );
        $this->template->content->view('default/contents/day_off/form', $vars);
        $this->template->publish();
    }

    public function update() {
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('coach/day_off');
        }
        
        $rules = array(
            array('field'=>'start_date', 'label' => 'Start Date', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'end_date', 'label' => 'End Date', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'remark', 'label' => 'Remark', 'rules'=>'trim|required|xss_clean')
        );
        
        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            $this->edit($this->session->userdata("day_off_id"));
            return;
        }

        // inserting user data
        $day_off = array(
            'coach_id' => $this->auth_manager->userid(),
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
            'remark' => $this->input->post('remark'),
            'status' => 'pending',
        );
        
        if (!$this->isValidDayOff($day_off['start_date'], $day_off['end_date'])){
            $this->messages->add('Invalid date', 'danger');
            $this->edit($this->session->userdata("day_off_id"));
            return;
        }

        //print_r($day_off); exit;
        // Inserting and checking to users table then storing insert_id into $insert_id
        $this->db->trans_begin();
        if (!$this->coach_day_off_model->update($this->session->userdata("day_off_id"), $day_off)) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            redirect('coach/day_off');
            return;
        }
        $this->db->trans_commit();
        $this->session->unset_userdata('day_off_id');

        $this->messages->add('Updating Day Off Succeded', 'success');
        redirect('coach/day_off');
    }
    
    function isValidDayOff($start_date = '', $end_date = ''){
        $current_date = strtotime(date('Y-m-d'));
        $start_date = strtotime(@$start_date);
        $end_date = strtotime(@$end_date);
        
        if ($start_date >= $current_date && $end_date >= $current_date && $start_date <= $end_date) {
            return true;
        }
        else {
            return false;
        }
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

    function checkDayoff($id_coach = '', $start_date = '', $end_date = ''){
        $check = $this->db->select('id')
                          ->from('coach_dayoffs')
                          ->where("(start_date BETWEEN '$start_date' AND '$end_date') AND (coach_id = '$id_coach') AND (status = 'pending' or status = 'active')")
                          ->get();
        
        if($check->num_rows() > 0){
            return false;
        } else {
            return true;
        }
    }

    function checkApproval($id_coach = '', $start_date = '', $end_date = ''){
        $check = $this->db->select('id')
                          ->from('coach_dayoffs')
                          ->where('start_date =',$start_date)
                          ->where('end_date =',$end_date)
                          ->where('coach_id',$id_coach)
                          ->where('status', 'decline')
                          ->get();
        
        if($check->num_rows() > 0){
            return false;
        } else {
            return true;
        }
    }

    public function edit2($day) {
        // setting day for editing day_off data
        $this->session->set_userdata("day_day_off", $day);

        $this->template->title = 'Edit Schedule';

        $offwork = $this->offwork_model->get_offwork($this->auth_manager->userid(), $day);
        $day_off = $this->coach_day_off_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id', $this->auth_manager->userid())->where('day', $day)->get();
        if (!$offwork && !$day_off) {
            redirect('account/identity/detail/profile');
        }

        //offwork by day
        $start_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->start_time);
        $end_time_offwork = DateTime::createFromFormat('H:i:s', $offwork[0]->end_time);
        //day_off by day
        $start_time_day_off = DateTime::createFromFormat('H:i:s', $day_off->start_time);
        $end_time_day_off = DateTime::createFromFormat('H:i:s', $day_off->end_time);

        $day_off_temp = array();
        if ($start_time_offwork == $start_time_day_off && $start_time_offwork == $end_time_day_off && $end_time_offwork == $start_time_day_off && $end_time_offwork == $end_time_day_off) {
            $day_off_temp[0] = array(
                'start_time' => $day_off->start_time,
                'end_time' => $day_off->end_time,
            );
        } else if ($start_time_offwork >= $start_time_day_off && $start_time_offwork <= $end_time_day_off && $end_time_offwork >= $start_time_day_off && $end_time_offwork <= $end_time_day_off) {
            $day_off_temp[0] = array(
                'start_time' => $day_off->start_time,
                'end_time' => $offwork[0]->start_time,
            );
            $day_off_temp[1] = array(
                'start_time' => $offwork[0]->end_time,
                'end_time' => $day_off->end_time,
            );
        } else {
            $day_off_temp[0] = array(
                'start_time' => $day_off->start_time,
                'end_time' => $day_off->end_time,
            );
        }

        $vars = array(
            'day_off' => $day_off_temp,
            'day' => $day,
            'form_action' => 'update'
        );
        $this->template->content->view('default/contents/day_off/form', $vars);

        //publish template
        $this->template->publish();
    }

    public function delete($id = '') {
        $this->coach_day_off_model->where('coach_id', $this->auth_manager->userid())->delete($id);
        $this->messages->add('Delete Successful', 'success');
        redirect('coach/day_off/' . $day);
    }

    public function update2() {
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('account/identity/detail/profile');
        }
        //$this->session->userdata("day_day_off");
        $day_off_id = $this->coach_day_off_model->select('id')->where('user_id', $this->auth_manager->userid())->where('day', $this->session->userdata("day_day_off"))->get();
        $offwork_id = $this->offwork_model->get_offwork($this->auth_manager->userid(), $this->session->userdata("day_day_off"));

        if ($this->input->post('start_time_0') && $this->input->post('end_time_0') && $this->input->post('start_time_1') && $this->input->post('end_time_1')) {
            // updating day_off
            $day_off = array(
                'start_time' => $this->input->post('start_time_0'),
                'end_time' => $this->input->post('end_time_1'),
            );

            // Inserting and checking
            if (!$this->coach_day_off_model->update($day_off_id->id, $day_off)) {
                $this->messages->add(validation_errors(), 'danger');
                $this->edit($this->auth_manager->userid());
                return;
            }

            // updating offwork
            $offwork = array(
                'start_time' => $this->input->post('end_time_0'),
                'end_time' => $this->input->post('start_time_1'),
            );

            // Inserting and checking
            if (!$this->offwork_model->update($offwork_id[0]->id, $offwork)) {
                $this->messages->add(validation_errors(), 'danger');
                $this->edit($this->auth_manager->userid());
                return;
            }
        } else if ($this->input->post('start_time_0') && $this->input->post('end_time_0') && !$this->input->post('start_time_1') && !$this->input->post('end_time_1')) {
            // updating day_off
            $day_off = array(
                'start_time' => $this->input->post('start_time_0'),
                'end_time' => $this->input->post('end_time_0'),
            );

            // Inserting and checking
            if (!$this->coach_day_off_model->update($day_off_id->id, $day_off)) {
                $this->messages->add(validation_errors(), 'danger');
                $this->edit($this->auth_manager->userid());
                return;
            }

            // updating offwork
            $offwork = array(
                'start_time' => null,
                'end_time' => null,
            );

            // Inserting and checking
            if (!$this->offwork_model->update($offwork_id[0]->id, $offwork)) {
                $this->messages->add(validation_errors(), 'danger');
                $this->edit($this->auth_manager->userid());
                return;
            }
        }


        //unsetting day_day_off
        $this->session->unset_userdata("day_day_off");

        $this->messages->add('Update Succeeded', 'success');
        redirect('coach/day_off');
    }

}
