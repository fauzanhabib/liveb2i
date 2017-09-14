<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class request_token extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // load models for student
        $this->load->model('token_request_model');
        $this->load->model('user_token_model');
        $this->load->model('identity_model');
        $this->load->model('user_model');
        $this->load->model('history_request_model');
        $this->load->model('specific_settings_model');
        $this->load->model('global_settings_model');
        // for messaging action and timing
        $this->load->library('queue');
        $this->load->library('email_structure');
        $this->load->library('send_email');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'SPR') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = 'Request Token';
        $vars = array(
            'data' => $this->token_request_model->select('id, token_amount')->where('user_id', $this->auth_manager->userid())->where('status', 'requested')->get(),
            'remain_token' => $this->identity_model->select('id, token_amount')->get_identity('token')->where('user_id', $this->auth_manager->userid())->get(),
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();

        $this->template->content->view('default/contents/student_partner/token/index', $vars, $overwrite=false);

        //publish template
        $this->template->publish();
    }

    public function add() {
        if ($this->isRequested()) {
            $this->messages->add('You Have Requested Token', 'danger');
            redirect('student_partner/request_token');
        }

        $this->template->title = 'Request Token';
        $vars = array(
            'form_action' => 'create'
        );
        $this->template->content->view('default/contents/student_partner/token/form', $vars, $overwrite=false);
        $this->template->publish();
    }

    public function create() {
        date_default_timezone_set('Etc/GMT+0');
        // Creating a partner
        
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'warning');
            redirect('student_partner/request_token');
        }
        
        if (!$this->input->post('token_amount') || $this->input->post('token_amount') <=0) {
            $this->messages->add('Token Request Value Must be More than Zero', 'warning');
            redirect('student_partner/request_token');
        }

        // =========================

        $partner_id = $this->auth_manager->partner_id();

        // check apakah status setting region allow atau disallow
        $region_id = $this->auth_manager->region_id($partner_id);
        
        $get_status_setting_region = $this->specific_settings_model->get_specific_settings($region_id,'region');
        
        $max_token = '';
        if($get_status_setting_region[0]->status_set_setting == 0){
            $get_setting = $this->global_settings_model->get_partner_settings();

            $max_token = $get_setting[0]->max_token; 
        } else {
            $get_setting = $this->db->select('max_token')
                                ->from('specific_settings')
                                ->where('partner_id',$partner_id)
                                ->get()->result();
            $max_token = $get_setting[0]->max_token;
        }
        
        
        // ==========================


        // get token user
        $get_user_token = $this->db->select('token_amount')
                                   ->from('user_tokens')
                                   ->where('user_id',$this->auth_manager->userid())
                                   ->get()->result();
        $user_token = $get_user_token[0]->token_amount;
        // ===========================


        $total_token_request = $user_token+$this->input->post('token_amount');

        if($total_token_request > $max_token){
            $this->messages->add('Sorry, your token request has exceeded the maximum limit, your maximum limit is '.$max_token, 'warning');
            redirect('student_partner/request_token');
        }

        // notification
        $get_name = $this->db->select('user_profiles.fullname as fullname, users.email as email')->from('user_profiles')->join('users','users.id = user_profiles.user_id')->where('user_profiles.user_id',$this->auth_manager->userid())->get()->result();
        $fullname = $get_name[0]->fullname;

        // get id region
        $gpid = $this->auth_manager->partner_id($this->auth_manager->userid());

        $adminid = $this->auth_manager->region_id($gpid);

        $adminemail = $this->user_model->select('id, email')->where('id', $adminid)->get_all();
        // =============
     
        $adminname = $this->user_profile_model->select('user_id, fullname')->where('user_id', $adminid)->get_all();
        $name_admin = $adminname[0]->fullname;
        // inserting user data
        $token_request = array(
            'approve_id' => $adminid,
            'user_id' => $this->auth_manager->userid(),
            'token_amount' => $this->input->post('token_amount'),
            'status' => 'requested',
            'dcrea' => time(),
            'dupd' => time()
        );

        // Inserting and checking to partner table
        $this->db->trans_begin();
        // check user already request token
        $ct = $this->token_request_model->select('id')->where('user_id',$this->auth_manager->userid())->where('status','requested')->get();
        if(count($ct) > 0){
            $this->messages->add('You have already request', 'warning');
            redirect('admin/token');
        }

        if (!$this->token_request_model->insert($token_request)) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            redirect('student_partner/request_token');
        } 
        else {
            // exit('gagal');
            // messaging partner
            // $this->messaging_partner('requested');
        }
        


        $partner_notification = array(
            'user_id' => $this->auth_manager->region_id($gpid),
            'description' => 'New token request from Student Affiliate. Please decide to Approve/Decline.',
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

        // messaging inserting data notification
        $this->db->trans_commit();

        $this->user_notification_model->insert($partner_notification);

        // email
        
        $this->send_email->send_partner_request_token($get_name[0]->email, $fullname, $this->input->post('token_amount'), 'requested');
        $this->send_email->send_student_supplier_request_token($adminemail[0]->email, $this->input->post('token_amount'), 'requested', $fullname, $name_admin);
        // =============

        
        $this->messages->add('Request Token Succeeded', 'success');
        redirect('student_partner/request_token');
        
    }

    public function cancel() {
        $data = array(
            'status' => 'cancelled',
            'dupd' => time()
        );
        
        $id = $this->isRequested();
        if ($id) {
            $get_name = $this->db->select('user_profiles.fullname as fullname, users.email as email')->from('user_profiles')->join('users','users.id = user_profiles.user_id')->where('user_profiles.user_id',$this->auth_manager->userid())->get()->result();
            $fullname = $get_name[0]->fullname;
            $get_token = $this->db->select('user_id, token_amount')->from('user_tokens')->where('user_id', $this->auth_manager->userid())->get()->result();
            $tokenamount = $get_token[0]->token_amount;

            // get id region
            $gpid = $this->auth_manager->partner_id($this->auth_manager->userid());

            $adminid = $this->auth_manager->region_id($gpid);

            $adminemail = $this->user_model->select('id, email')->where('id', $adminid)->get_all();
            
            // =============

            $partner_notification = array(
                'user_id' => $this->auth_manager->region_id($gpid),
                'description' => $fullname.' Clark Kent has cancelled the token request.',
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

            // messaging inserting data notification

            $this->user_notification_model->insert($partner_notification);
            // email
            $this->send_email->send_admin_request_token($get_name[0]->email, $get_token[0]->token_amount, 'cancelled');
            $this->send_email->send_student_supplier_request_token($adminemail[0]->email, $get_token[0]->token_amount, 'cancelled', $fullname);
            
            // =============

            $this->token_request_model->update($id, $data);
            // messaging partner
            // $this->messaging_partner('cancelled');
            $this->messages->add('Request Token Cancelled', 'success');
            redirect('student_partner/request_token');
        } 
        else {
            $this->messages->add('Your Request has already been Approved or Declined by Regional Admin', 'error');
            redirect('student_partner/request_token');
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
        else if($content == 'cancelled'){
            $data['subject'] = 'Cancelled Token Request';
            //$data['content'] = 'The student ' . $student_name->fullname . ' has cancelled the token request';
            $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Cancelled Token Request')
                .$this->email_structure->content('The student ' . $student_name->fullname . ' has cancelled the token request')
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');
            
            $partner_notification['description'] = 'The student ' . $student_name->fullname . ' has cancelled the token request';
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

    function history($page=''){
        $this->template->title = 'Token Request History';
       
        $offset = 0;
        $per_page = 10;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/token/history/'), count($this->history_request_model->select('token_requests.id, up.region_id as region_id, u.email, up.fullname, token_requests.token_amount, token_requests.status')->join('users u', 'u.id = token_requests.user_id')->join('user_profiles up', 'up.user_id = u.id')->where('u.role_id', 5)->where('token_requests.user_id',$this->auth_manager->userid())->where('u.status', 'active')->where('token_requests.status !=','requested')->order_by('up.fullname', 'asc')->get_all()), $per_page, $uri_segment);
        
        $id = $this->auth_manager->userid();

        $vars = array(
            'data' => $this->history_request_model->select('token_requests.id, token_requests.dcrea, token_requests.dupd, up.region_id as region_id, u.email, up.fullname, token_requests.token_amount, token_requests.status')->join('users u', 'u.id = token_requests.user_id')->join('user_profiles up', 'up.user_id = u.id')->where('u.role_id', 5)->where('token_requests.user_id',$this->auth_manager->userid())->where('u.status', 'active')->where('token_requests.status !=','requested')->order_by('up.fullname', 'asc')->get_all(),
            'pagination' => $pagination,
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();

        $this->template->content->view('default/contents/student_partner/token/history', $vars);

        //publish template
        $this->template->publish();
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
