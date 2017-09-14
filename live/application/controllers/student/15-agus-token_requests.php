<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class token_requests extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // load models for student
        $this->load->model('token_request_model');
        $this->load->model('identity_model');
        $this->load->model('user_model');
        $this->load->model('user_token_model');
        $this->load->model('specific_settings_model');
        // for messaging action and timing
        $this->load->library('queue');
        $this->load->library('email_structure');
        $this->load->library('send_email');
        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = 'Token Request';
        $vars = array(
            'data' => $this->token_request_model->select('id, token_amount')->where('user_id', $this->auth_manager->userid())->where('status', 'requested')->get(),
            'remain_token' => $this->identity_model->select('id, token_amount')->get_identity('token')->where('user_id', $this->auth_manager->userid())->get(),
        );

        $this->template->content->view('default/contents/token_request/index', $vars, $overwrite=FALSE);

        //publish template
        $this->template->publish();
    }

    public function add() {
        if ($this->isRequested()) {
            $this->messages->add('You Have Requested Token', 'danger');
            redirect('student/token_requests');
        }
        $this->template->title = 'Request Token';
        $vars = array(
            'form_action' => 'create'
        );
        $this->template->content->view('default/contents/token_request/form', $vars);
        $this->template->publish();
    }

    public function create() {
        date_default_timezone_set('Etc/GMT+0');

        // Creating a partner
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'warning');
            redirect('student/token_requests');
        }
        
        if (!$this->input->post('token_amount') || $this->input->post('token_amount') <=0) {
            $this->messages->add('Token Request Value Must be More than Zero', 'Warning');
            redirect('student/token_requests');
        }

        $req_token = $this->input->post('token_amount');

        // $get_idsp = $this->db->select('id_student_supplier')->from('student_supplier_to_student')->where('id_student',$this->auth_manager->userid())->get()->result();
        $get_idsp = $this->db->select('creator_id')->from('creator_members')->where('member_id',$this->auth_manager->userid())->get()->result();
        
        if(!$get_idsp){
            $this->messages->add('Invalid action', 'danger');
            redirect('student/token_requests');
        }
        $idsp = $get_idsp[0]->creator_id;

        // check status token
        $ctr = $this->db->select('id')
                        ->from('token_requests')
                        ->where('user_id',$this->auth_manager->userid())
                        ->where('status','requested')
                        ->get()->result();
        if($ctr){
            $this->messages->add('Sorry, your has already request token', 'warning');
            redirect('student/token_requests');
        }

        // check token user
        $check_token = $this->user_token_model->get_token($this->auth_manager->userid(),'user');
        $user_token = $check_token[0]->token_amount;
        $total_req_token = $req_token+$user_token;

        // check max token for student
        $partner_id = $this->auth_manager->partner_id($idsp);
        
        $check_setting = $this->specific_settings_model->get_partner_settings($partner_id);
        
        $max_token_for_student = $check_setting[0]->max_token_for_student;
        
        if($max_token_for_student < $total_req_token){
            $this->messages->add('Sorry, your request token exceeded max token', 'warning');
            redirect('student/token_requests');
        }


        $partner = $this->user_model->select('id, email')->where('id', $idsp)->get_All();
        $emailpartner = $partner[0]->email;
        $user = $this->user_profile_model->select('user_id, fullname')->where('user_id', $this->auth_manager->userid())->get_All();
        $userfullname = $user[0]->fullname;

        // inserting user data
        $token_request = array(
            'user_id' => $this->auth_manager->userid(),
            'token_amount' => $this->input->post('token_amount'),
            'status' => 'requested',
        );

        // Inserting and checking to partner table
        $this->db->trans_begin();
        if (!$this->token_request_model->insert($token_request)) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            redirect('student/token_requests');
        } 
        else {
            // messaging partner
            // $this->messaging_partner('requested');
        }
            $this->db->trans_commit();

                    // notification
            $get_name = $this->db->select('user_profiles.fullname as fullname, users.email as email')->from('user_profiles')->join('users','users.id = user_profiles.user_id')->where('user_profiles.user_id',$this->auth_manager->userid())->get()->result();
            $fullname = $get_name[0]->fullname;

            // =============
            if($idsp){
                $partner_notification = array(
                    'user_id' => $idsp,
                    'description' => $fullname.' asked for token, please confirm or reject the request.',
                    'status' => 2,
                    'dcrea' => time(),
                    'dupd' => time(),
                );
                // coach's data for reminder messaging
                // IMPORTANT : array index in content must be in mutual with table field in database
                $data_partner = array(
                    'table' => 'user_notifications',
                    'content' => $partner_notification,
                );
            }



            $this->user_notification_model->insert($partner_notification);
            $this->send_email->student_request($emailpartner, $this->input->post('token_amount'), $userfullname);

        // =======

        $this->messages->add('Request Token Succeeded', 'success');
        redirect('student/token_requests');
    }

    public function cancel() {
        $data = array(
            'status' => 'canceled',
        );
        
        $id = $this->isRequested();
        if ($id) {
            $this->token_request_model->update($id, $data);
            // messaging partner
            // $this->messaging_partner('canceled');
            $this->messages->add('Request Token Canceled', 'success');
            redirect('student/token_requests');
        } 
        else {
            $this->messages->add('Invalid Action', 'danger');
            redirect('student/token_requests');
        }
    }

    function isRequested() {
        $id = $this->token_request_model->where('user_id', $this->auth_manager->userid())->where('status', 'requested')->get();
        return (@$id->id);
    }

    function messaging_partner($content = '') {
        // Tube name for messaging action
        $tube = 'com.live.email';
        $database_tube = 'com.live.database';
        // for student name
        $student_name = $this->identity_model->get_identity('profile')->select('fullname')->where('user_id', $this->auth_manager->userid())->get();
        // data messaging
        // Email's content that will be send to partner to inform that the student request for token 
        $data = array();
        // messaging notification 
        $partner_notification = array(
            //'description' => 'The student ' . $student_name->fullname . ' asked for token, please confirm or reject the request',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );
        
        if($content == 'requested'){
            $data['subject'] = 'Token Request';
            //$data['content'] = 'The student ' . $student_name->fullname . ' asked for token, please confirm or reject the request';
            $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Token Request')
                .$this->email_structure->content('The student ' . $student_name->fullname . ' asked for token, please confirm or reject the request')
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');
            
            $partner_notification['description'] = 'The student ' . $student_name->fullname . ' asked for token, please confirm or reject the request';
        }
        else if($content == 'canceled'){
            $data['subject'] = 'Canceled Token Request';
            //$data['content'] = 'The student ' . $student_name->fullname . ' has canceled the token request';
            $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Canceled Token Request')
                .$this->email_structure->content('The student ' . $student_name->fullname . ' has canceled the token request')
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');
            
            $partner_notification['description'] = 'The student ' . $student_name->fullname . ' has canceled the token request';
        }

        foreach ($this->user_model->get_email_student_partner() as $a) {
            // sending email and creating notification to all student partner admin
            // Email's content that will be send to partner to inform that the student request for token 
            $data['email'] = $a->email;
            // Pushing queues to Pheanstalk Server
            $this->queue->push($tube, $data, 'email.send_email');

            // messaging notification 
            $partner_notification['user_id'] = $a->id;
            // coach's data for acceptence student information messaging
            // IMPORTANT : array index in content must be in mutual with table field in database
            $data_partner = array(
                'table' => 'user_notifications',
                'content' => $partner_notification,
            );
            // messaging inserting data notification for coach partner / partner admin
            $this->queue->push($database_tube, $data_partner, 'database.insert');
        }
    }
    
    //function messaging_student($content = '') {
//        // Tube name for messaging action
//        $tube = 'com.live.email';
//        $database_tube = 'com.live.database';
//        // for student name
//        $student_name = $this->identity_model->get_identity('profile')->select('fullname')->where('user_id', $this->auth_manager->userid())->get();
//        // data messaging
//        // Email's content that will be send to partner to inform that the student request for token 
//        $data = array(
//            'subject' => 'Token Request',
//            'content' => 'The student ' . $student_name->fullname . ' asked for token, please confirm or reject the request',
//        );
//        // messaging notification 
//        $student_notification = array(
//            'description' => 'The student ' . $student_name->fullname . ' asked for token, please confirm or reject the request',
//            'status' => 2,
//            'dcrea' => time(),
//            'dupd' => time(),
//        );
//            // sending email and creating notification to all partner admin
//            // Email's content that will be send to partner to inform that the student request for token 
//            $data['email'] = $a->email;
//            // Pushing queues to Pheanstalk Server
//            $this->queue->push($tube, $data, 'email.send_email');
//
//            // messaging notification 
//            $partner_notification['user_id'] = $a->id;
//            // coach's data for acceptence student information messaging
//            // IMPORTANT : array index in content must be in mutual with table field in database
//            $data_partner = array(
//                'table' => 'user_notifications',
//                'content' => $partner_notification,
//            );
//            // messaging inserting data notification for coach partner / partner admin
//            $this->queue->push($database_tube, $data_partner, 'database.insert');
    //}

}
