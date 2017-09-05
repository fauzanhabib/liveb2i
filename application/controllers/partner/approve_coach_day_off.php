<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class approve_coach_day_off extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('coach_day_off_model');
        $this->load->model('user_model');
        // for messaging action and timing
        $this->load->library('queue');
        $this->load->library('email_structure');
        $this->load->library('send_email');

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
            'status' => 'active'
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

}
