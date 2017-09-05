<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class token extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // load models for student
        $this->load->model('token_request_model');
        $this->load->model('user_token_model');
        $this->load->model('identity_model');
        $this->load->model('user_model');
        $this->load->model('history_request_model');
        $this->load->model('user_notification_model');
        
        // for messaging action and timing
        $this->load->library('queue');
        $this->load->library('email_structure');
        $this->load->library('send_email');
        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'ADM') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
        // set default timezone
        date_default_timezone_set('Etc/GMT+0');
    }

    // Index
    public function index() {
        $this->template->title = 'Request Token';
        $vars = array(
            'data' => $this->token_request_model->select('id, token_amount')->where('user_id', $this->auth_manager->userid())->where('status', 'requested')->get(),
            'remain_token' => $this->identity_model->select('id, token_amount')->get_identity('token')->where('user_id', $this->auth_manager->userid())->get(),
        );

        $this->template->content->view('default/contents/admin/token/index', $vars, $overwrite=false);

        //publish template
        $this->template->publish();
    }

    public function add() {
        if ($this->isRequested()) {
            $this->messages->add('You Have Requested Token', 'danger');
            redirect('admin/request_token');
        }

        $this->template->title = 'Request Token';
        $vars = array(
            'form_action' => 'create'
        );
        $this->template->content->view('default/contents/admin/token/form', $vars, $overwrite=false);
        $this->template->publish();
    }

    public function create() {
        date_default_timezone_set('Etc/GMT+0');
        // Creating a partner
        
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'warning');
            redirect('admin/token');
        }
        
        if (!$this->input->post('token_amount') || $this->input->post('token_amount') <=0) {
            $this->messages->add('Token Request Value Must be More than Zero', 'warning');
            redirect('admin/token');
        }

        // check user already request token
        $ct = $this->token_request_model->select('id')->where('user_id',$this->auth_manager->userid())->where('status','requested')->get();
        if(count($ct) > 0){
            $this->messages->add('You have already request', 'warning');
            redirect('admin/token');
        }

        // get id superadmin
        $superadmin = $this->db->select('id,email')->from('users')->where('role_id',7)->get()->result();
        $id_superadmin = $superadmin[0]->id;
        $email_superadmin = $superadmin[0]->email;

    
        // inserting user data
        $token_request = array(
            'approve_id' => $id_superadmin,
            'user_id' => $this->auth_manager->userid(),
            'token_amount' => $this->input->post('token_amount'),
            'status' => 'requested',
            'dcrea' => time(),
            'dupd' => time()
        );
        

        // Inserting and checking to partner table
        $this->db->trans_begin();
        if (!$this->token_request_model->insert($token_request)) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            redirect('admin/token');
        } 
        else {
            // messaging partner
            $this->messaging_partner('requested');
        }
        $this->db->trans_commit();

        // notification
        $get_name = $this->db->select('user_profiles.fullname as fullname, users.email as email')->from('user_profiles')->join('users','user_profiles.user_id = users.id')->where('user_profiles.user_id',$this->auth_manager->userid())->get()->result();
        $fullname = $get_name[0]->fullname;



        $partner_notification = array(
            'user_id' => $id_superadmin,
            'description' => 'New token request from '.$fullname,
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

        // send email
        $this->send_email->send_admin_request_token($get_name[0]->email,$fullname,$this->input->post('token_amount'),'requested');
        $this->send_email->add_token_region($email_superadmin,$fullname,$this->input->post('token_amount'),'requested');

        $this->messages->add('Request Token Succeeded', 'success');
        redirect('admin/token');
        
    }

    public function cancel() {
        $data = array(
            'status' => 'cancelled',
        );
        
        $id = $this->isRequested();
        if ($id) {
            // get token
            $get_token = $this->token_request_model->select('token_requests.token_amount as token_amount, users.email as email')->join('users','users.id = token_requests.user_id')->where('token_requests.id',$id)->where('token_requests.status','requested')->get();

            $this->token_request_model->update($id, $data);
            // messaging partner
            $this->messaging_partner('cancelled');
            $this->messages->add('Request Token Cancelled', 'success');

            $get_name = $this->db->select('user_profiles.fullname as fullname, users.email as email')->from('user_profiles')->join('users','user_profiles.user_id = users.id')->where('user_profiles.user_id',$this->auth_manager->userid())->get()->result();
            $fullname = $get_name[0]->fullname;

            // get superadmin
            $superadmin = $this->db->select('id,email')->from('users')->where('role_id',7)->get()->result();
            $id_superadmin = $superadmin[0]->id;
            $email_superadmin = $superadmin[0]->email;

            // send email
            $this->send_email->send_admin_request_token($get_token->email,$get_token->token_amount,'cancelled');
            $this->send_email->add_token_region($email_superadmin,$fullname,$get_token->token_amount,'cancelled');


            redirect('admin/token');
        } 
        else {
            $this->messages->add('Your Request has already been Approved or Declined by Super Admin', 'error');
            redirect('admin/token');
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
        // $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin/token/history/'), count($this->history_request_model->select('token_requests.id, up.region_id as region_id, u.email, up.fullname, token_requests.token_amount, token_requests.status')->join('users u', 'u.id = token_requests.user_id')->join('user_profiles up', 'up.user_id = u.id')->where('u.role_id', 4)->where('token_requests.user_id',$this->auth_manager->userid())->where('u.status', 'active')->where('token_requests.status !=','requested')->order_by('up.fullname', 'asc')->get_all()), $per_page, $uri_segment);
       $id = $this->auth_manager->userid();
        $utz = $this->db->select('user_timezone')
                ->from('user_profiles')
                ->where('user_id', $id)
                ->get()->result();
        $idutz = $utz[0]->user_timezone;
        $tz = $this->db->select('*')
                ->from('timezones')
                ->where('id', $idutz)
                ->get()->result();
        
        $minutes = '';
        if($minutes == 0){
            $minutes = 420;
        } else {
            $minutes = $tz[0]->minutes;
        }
        $vars = array(
            'data' => $this->history_request_model->select('token_requests.id, token_requests.dcrea, token_requests.dupd, up.region_id as region_id, u.email, up.fullname, token_requests.token_amount, token_requests.status')->join('users u', 'u.id = token_requests.user_id')->join('user_profiles up', 'up.user_id = u.id')->where('u.role_id', 4)->where('token_requests.user_id',$this->auth_manager->userid())->where('u.status', 'active')->where('token_requests.status !=','requested')->order_by('up.fullname', 'asc')->get_all(),
            'minutes' => $minutes
            // 'pagination' => $pagination
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();

        $this->template->content->view('default/contents/admin/token/history', $vars);

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

    function rendy(){
        $tube = 'com.live.email';
        $data = array(
            'subject' => 'Approve Coach Day Off',
            'email' => 'rendybustari@gmail.com',
            'content' => 'Coach  asking for day off approval at ',
        );
        $this->queue->push($tube, $data, 'email.send_email');
    }

}
