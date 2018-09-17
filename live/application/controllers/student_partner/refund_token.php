<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Refund_token extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('partner_setting_model');
        $this->load->model('subgroup_model');
        $this->load->model('region_model');
        $this->load->model('identity_model');
        $this->load->model('creator_member_model');
        $this->load->model('user_token_model');
        $this->load->model('student_detail_profile_model');
        $this->load->model('timezone_model');
        $this->load->model('specific_settings_model');
        $this->load->model('global_settings_model');
        

        $this->load->library('send_email');
        $this->load->library('send_sms');
        
        // for messaging action and timing

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'SPR') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    public function index($page='', $subgroup_id = '') {

        $this->template->title = 'Refund Tokens';
        $offset = 0;
        $per_page = 10;
        $uri_segment = 4;

        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/refund_token/index/'), count($this->identity_model->get_student_identity(NULL,NULL,$this->auth_manager->partner_id(),NULL)), $per_page, $uri_segment);
  
        $data2 = $this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(),'','', $offset, $subgroup_id);
 
        $data = $this->identity_model->get_subgroup_identity('','student','',null);

        $id = $this->auth_manager->userid();

        $get_token = $this->db->select('token_amount')
                          ->from('user_tokens')
                          ->where('user_id',$this->auth_manager->userid())
                          ->get()->result();

        $token = $get_token[0]->token_amount;

        $link = base_url().'index.php/student_partner/refund_token/action_refund_token';
        
        $vars = array(
            'data' => $data,
            'form_action' => 'update_subgroup',
            'data2' => $this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(), '', '', $offset, $subgroup_id),
            'subgroup_id' => $subgroup_id,
            'pagination'  => $pagination,
            'token'       => $token,
            'link'        => $link

        );
        

        $this->template->content->view('default/contents/student_partner/reporting/refund_token', $vars);
        $this->template->publish();
    }

    function action_refund_token(){
        $student_id = $this->input->post('student_id');
        $token_refund = $this->input->post('token_refund');

        // get data token student
        $old_data_token_student = $this->db->select('token_amount')->from('user_tokens')->where('user_id', $student_id)->get()->result();

        $data_token_refund = $this->db->select('token_amount, balance')
                                  ->from('token_histories')
                                  ->where('user_id', $student_id)
                                  ->where('token_status_id',15)
                                  ->order_by('id','desc')
                                  ->get()->result();
  
        $token_student = @$old_data_token_student[0]->token_amount;

        $token_amount = @$data_token_refund[0]->token_amount;
        $balance = @$data_token_refund[0]->balance;

        // check apakah tokennya sama

        
        if($token_student != $balance) {
            $this->messages->add('Your token failed refund ', 'warning');
            redirect('student_partner/refund_token/');
        }



        // =====

        // update token student
        $token_update = $balance-$token_amount;

        $data_users = array('token_amount' => $token_update,
                                  'dcrea' => time(),
                                  'dupd' => time()
                                  );

        $update_users_token = $this->db->where('user_id', $student_id)
                                          ->update('user_tokens', $data_users);

       
        // update token student affiliate
        $old_data_token_student_affiliate = $this->db->select('token_amount')->from('user_tokens')->where('user_id', $this->auth_manager->userid())->get()->result();

        $new_token_student_affiliate = $old_data_token_student_affiliate[0]->token_amount+$token_amount;
        $data_token_student_affiliate = array('token_amount' => $new_token_student_affiliate,
                                  'dcrea' => time(),
                                  'dupd' => time()
                                  );

        $update_users_token = $this->db->where('user_id', $this->auth_manager->userid())
                                          ->update('user_tokens', $data_token_student_affiliate);

        // insert into tabel token histories
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

        $data_histories_token = array('balance' => $token_update,
                                      'token_amount' => $token_amount,
                                      'user_id' => $student_id,
                                      'partner_id' => $partner_id,
                                      'organization_id' => $organization_id,
                                      'transaction_date' => time(),
                                      'description' => 'Your tokens were refunded by Student Affiliate',
                                      'token_status_id' => 31);


        $this->db->insert('token_histories', $data_histories_token);

        // insert into table token_requests
        $insert_table_req = array('approve_id' => $this->auth_manager->userid(),
                                  'user_id' => $student_id,
                                  'token_amount' => $token_amount,
                                  'status' => 'refund',
                                  'dcrea' => time(),
                                  'dupd' => time());
        $this->db->insert('token_requests',$insert_table_req);

        // send notification and email
        $partner_notification = array(
            'user_id' => $student_id,
            'description' => 'Your token has been refund. '.$token_amount.' by Student Affiliate',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );


         $this->db->trans_commit();

        // messaging inserting data notification

        $this->user_notification_model->insert($partner_notification);
        $this->send_email->add_token($get_email[0]->email, $get_name[0]->fullname, $request_token, $get_email[0]->role_id, $get_name2[0]->fullname);

        
        $this->messages->add('Token refund success', 'success');
        redirect('student_partner/refund_token/');
    }

}