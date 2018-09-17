<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class approve_token_requests extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // load models for student
        $this->load->model('token_request_model');
        $this->load->model('identity_model');
        $this->load->model('user_model');
        $this->load->model('token_histories_model');
        // for messaging action and timing
        $this->load->library('queue');
        $this->load->library('email_structure');
        $this->load->library('send_email');


        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'SPR') {
            $this->messages->add('Access Denied');
            redirect('student_partner/approve_token_request');
        }

        date_default_timezone_set('Etc/GMT+0');
    }

    // Index
    public function index() {
        $this->template->title = 'Token Request';
        $id = $this->auth_manager->userid();
        $vars = array(
            'data' => $this->token_request_model->new_get_token_request($id),
        );

        //print_r($vars); exit;
        $this->template->content->view('default/contents/approve_token_request/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function approve($token_request_id = '') {
        date_default_timezone_set('Etc/GMT+0');
        if ($this->token_request_model->get_student_request($token_request_id)) {
            $token_request = $this->token_request_model->select('id, user_id, token_amount')->where('id', $token_request_id)->get();
            $token = $this->identity_model->get_identity('token')->select('id, user_id, token_amount')->where('user_id', $token_request->user_id)->get();
            
            // token student partner
            $get_user_token = $this->db->select('token_amount')->from('user_tokens')->where('user_id',$this->auth_manager->userid())->get()->result();

            // jika token amount student partner < token request
            if($get_user_token[0]->token_amount < $token_request->token_amount){
                $this->messages->add('Your token not enough', 'warning');
                redirect('student_partner/approve_token_requests');
            }

            // update student token 
            $current_token = $token_request->token_amount + $token->token_amount;


            $update_data = array(
                'token_amount' => $current_token,
            );
            $studentemail = $this->user_model->select('id, email')->where('id', $token_request->user_id)->get_all();
            $name = $this->user_profile_model->select('user_id, fullname')->where('user_id', $token_request->user_id)->get_all();
            
            $this->identity_model->get_identity('token')->update($token->id,$update_data);

            // update student partner token
            $current_partner_token = $get_user_token[0]->token_amount - $token_request->token_amount;


            $update_data_partner = array(
                'token_amount' => $current_partner_token,
            );
            
            $update_token_partner = $this->db->where('user_id', $this->auth_manager->userid())
                                             ->update('user_tokens', $update_data_partner);

            $partner_id = $this->auth_manager->partner_id($token_request->user_id);
            $organization_id = '';
            $organization_id = $this->db->select('gv_organizations.id')
                      ->from('gv_organizations')
                      ->join('users', 'users.organization_code = gv_organizations.organization_code')
                      ->where('users.id', $token_request->user_id)
                      ->get()->result();

            if(empty($organization_id)){
                $organization_id = '';
            }else{
                $organization_id = $organization_id[0]->id;
            }
            
            $token_history = array(
                'user_id' => $token_request->user_id,
                'partner_id' => $partner_id,
                'organization_id' => $organization_id,
                'transaction_date' => time(),
                'token_amount' => $token_request->token_amount,
                'description' => 'Partner admin has approved you token request.',
                'token_status_id' => 3,
                'balance' => $current_token,
            );
            $this->db->insert('token_histories',$token_history);            
            
            $student_data = array(
                'token_amount'
            );


            // $data_token_request = array('approve_id' => $this->auth_manager->userid(),
            //                             'user_id' =>  $token_request->user_id,
            //                             'token_amount' => $token_request->token_amount,
            //                             'status' => 'approved',
            //                             'dcrea' => time(),
            //                             'dupd' => time()
            //                             );
            // $this->db->insert('token_requests',$data_token_request);

            // send notification
            // $token_history = array(
            //     'user_id' => $token_request->user_id,
            //     'transaction_date' => time(),
            //     'token_amount' => $token_request->token_amount,
            //     'description' => 'Super admin has approved you token request.',
            //     'token_status_id' => 3,
            //     'balance' => $current_token,
            //     'dcrea' => time(),
            //     'dupd' => time()
            // );

            // $this->token_histories_model->insert($token_history);

            $user_data = $this->user_model->select('users.email as email, user_profiles.fullname as fullname')->join('user_profiles','user_profiles.user_id = users.id')->where('users.id',$token_request->user_id)->get();          
            
            $student_data = array(
                'token_amount'
            );
            $data = array(
                'status' => 'approved',
                'dcrea' => time(),
                'dupd' => time()
            );
            $this->token_request_model->update($token_request_id, $data);
            // $this->messaging_admin($token_request_id, 'approved');

            $partner_notification = array(
                'user_id' => $token_request->user_id,
                'description' => 'Your token request has been approved.',
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
            // ===============================
            // email
            $this->send_email->student_supplier_approve_token($studentemail[0]->email, 'approved', $name[0]->fullname, $token_request->token_amount);
            
            // $this->send_email->send_region_approve_token($user_data->email,'approved',$user_data->fullname,$token_request->token_amount);
            // ==============


            $this->token_request_model->update($token_request_id, $data);
            $this->messaging_student($token_request_id, 'approved');
            
            $this->messages->add('Approve Token Request Succeded', 'success');
            redirect('student_partner/approve_token_requests');
        }
        else{
            $this->messages->add('Invalid Action', 'danger');
            redirect('student_partner/approve_token_requests');
        }
    }

    public function decline($token_request_id = '') {

        date_default_timezone_set('Etc/GMT+0');
        if ($this->token_request_model->get_student_request($token_request_id)) {
            $data = array(
                'status' => 'declined',
                'dcrea' => time(),
                'dupd' => time(),
            );
            $this->token_request_model->update($token_request_id, $data);
            // $this->messaging_student($token_request_id, 'declined');
            $token_request = $this->token_request_model->select('id, user_id, token_amount')->where('id', $token_request_id)->get();
            $token = $this->identity_model->get_identity('token')->select('id, user_id, token_amount')->where('user_id', $token_request->user_id)->get();

            $partner_id = $this->auth_manager->partner_id($token_request->user_id);
            $organization_id = '';
            $organization_id = $this->db->select('gv_organizations.id')
                      ->from('gv_organizations')
                      ->join('users', 'users.organization_code = gv_organizations.organization_code')
                      ->where('users.id', $token_request->user_id)
                      ->get()->result();

            if(empty($organization_id)){
                $organization_id = '';
            }else{
                $organization_id = $organization_id[0]->id;
            }
      
            $token_history = array(
                'user_id' => $token_request->user_id,
                'partner_id' => $partner_id,
                'organization_id' => $organization_id,
                'transaction_date' => time(),
                'token_amount' => $token_request->token_amount,
                'description' => 'Partner admin has declined you token request.',
                'token_status_id' => 4,

                'balance' => $token->token_amount,
            );
            $this->db->insert('token_histories',$token_history);

            // $data_token_request = array('approve_id' => $this->auth_manager->userid(),
            //                 'user_id' =>  $token_request->user_id,
            //                 'token_amount' => $token_request->token_amount,
            //                 'status' => 'declined',
            //                 'dcrea' => time(),
            //                 'dupd' => time()
            //                 );

            // $this->db->insert('token_requests',$data_token_request);
            $studentemail = $this->user_model->select('id, email')->where('id', $token_request->user_id)->get_all();
            $name = $this->user_profile_model->select('user_id, fullname')->where('user_id', $token_request->user_id)->get_all();

            $this->send_email->student_supplier_approve_token($studentemail[0]->email, 'declined', $name[0]->fullname, $token_request->token_amount);
            
            $this->messages->add('Decline Token Request Succeded', 'success');
            redirect('student_partner/approve_token_requests');
        }
        else{
            $this->messages->add('Invalid Action', 'danger');
            redirect('student_partner/approve_token_requests');
        }
    }

    function messaging_student($token_request_id = '', $content = '') {
        // Tube name for messaging action
        $tube = 'com.live.email';
        $database_tube = 'com.live.database';
        // for student name
        $token_request_data = $this->token_request_model->select('user_id, token_amount')->where('id', $token_request_id)->get();
        // student data
        $tudent_email = $this->user_model->select('email')->where('id', $token_request_data->user_id)->get();
        $data = array();
        $student_notification = array();
        
        $data = array(
            'email' => $tudent_email->email,
        );

        // messaging notification 
        $student_notification = array(
            //'description' => 'The student ' . $student_name->fullname . ' asked for token, please confirm or reject the request',
            'user_id' => $token_request_data->user_id,
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );
        
        // data messaging
        // Email's content that will be send to partner to inform that the student request for token 
        // the data based on approval of partner admin
        
        // if ($content == 'approved') {
        //     $data['subject'] = 'Token Request Approved';
        //     //$data['content'] = 'Your token request ' . $token_request_data->token_amount . ' has been approved by Partner Admin';
        //     $data['content'] = $this->email_structure->header()
        //         .$this->email_structure->title('Token Request Approved')
        //         .$this->email_structure->content('Your token request ' . $token_request_data->token_amount . ' has been approved by Partner Admin')
        //         //.$this->email_structure->button('JOIN SESSION')
        //         .$this->email_structure->footer('');
            
        //     $student_notification['description'] = 'Your token request ' . $token_request_data->token_amount . ' has been approved by Partner Admin';
        // } else if ($content == 'declined') {
        //     $data['subject'] = 'Token Request Declined';
        //     //$data['content'] = 'Your token request ' . $token_request_data->token_amount . ' has been declined by Partner Admin';
        //     $data['content'] = $this->email_structure->header()
        //         .$this->email_structure->title('Token Request Declined')
        //         .$this->email_structure->content('Your token request ' . $token_request_data->token_amount . ' has been declined by Partner Admin')
        //         //.$this->email_structure->button('JOIN SESSION')
        //         .$this->email_structure->footer('');
            
        //     $student_notification['description'] = 'Your token request ' . $token_request_data->token_amount . ' has been declined by Partner Admin';
        // }
        
        // sending email and creating notification to all partner admin
        // Email's content that will be send to partner to inform that the student request for token 
        // Pushing queues to Pheanstalk Server
        // $this->queue->push($tube, $data, 'email.send_email');

        // messaging notification 
        // coach's data for acceptence student information messaging
        // IMPORTANT : array index in content must be in mutual with table field in database
        $data_student = array(
            'table' => 'user_notifications',
            'content' => $student_notification,
        );
        // messaging inserting data notification for coach partner / partner admin
        // $this->queue->push($database_tube, $data_student, 'database.insert');
    }

}
