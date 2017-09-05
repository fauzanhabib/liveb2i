<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Add_token extends MY_Site_Controller {

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
        
        // for messaging action and timing

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'SPR') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    function generateRandomString($length = 5) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

     public function index($page='', $subgroup_id = '') {

        $this->template->title = 'Add Tokens';
        $offset = 0;
        $per_page = 10;
        $uri_segment = 4;

        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/add_token/index/'), count($this->identity_model->get_student_identity(NULL,NULL,$this->auth_manager->partner_id(),NULL)), $per_page, $uri_segment);
  
        $data2 = $this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(),'','', $offset, $subgroup_id);
        // echo "<pre>";
        // print_r($data2);
        // exit();   
        // echo $subgroup_id." - ".$id." -". $page." = ".$per_page;exit();
        // echo "id ".$id;
        $data = $this->identity_model->get_subgroup_identity('','student','',null);

        $id = $this->auth_manager->userid();
        $partner_id = $this->auth_manager->partner_id($id);

        $total_coach = $this->db->select('count(users.id) as id')
                                ->from('user_profiles')
                                ->join('users','users.id = user_profiles.user_id')
                                ->join('subgroup','user_profiles.subgroup_id = subgroup.id')
                                ->where('users.role_id','1')
                                ->where('users.status','active')
                                ->where('subgroup.partner_id',$partner_id)
                                ->get()->result();
        $total_coach = $total_coach[0]->id;

        $get_token = $this->db->select('token_amount')
                          ->from('user_tokens')
                          ->where('user_id',$this->auth_manager->userid())
                          ->get()->result();

        $token = $get_token[0]->token_amount;
        // echo "<pre>";
        // print_r($total_coach);
        // exit();
        $vars = array(
            'data' => $data,
            'form_action' => 'update_subgroup',
            'data2' => $this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(), '', '', $offset, $subgroup_id),
            'subgroup_id' => $subgroup_id,
            'pagination'  => $pagination,
            'token'       => $token,
            'total_coach' => $total_coach,
        );
        

        $this->template->content->view('default/contents/student_partner/reporting/index', $vars);
        $this->template->publish();
    }

    function action_add_token(){
        date_default_timezone_set('Etc/GMT+0');

        $id = $this->auth_manager->userid();
        $id_partner = $this->auth_manager->partner_id($id);


        if (!$this->input->post('single_token') || $this->input->post('single_token') <=0) {
            $this->messages->add('Token Request Value Must be More than Zero', 'warning');
            redirect('student_partner/add_token/');
        }
        $student_id    = $this->input->post("std_id");
        $request_token = $this->input->post("single_token");


        // exit();

        // check apakah status setting region allow atau disallow
        $region_id = $this->auth_manager->region_id($id_partner);
        
        $get_status_setting_region = $this->specific_settings_model->get_specific_settings($region_id,'region');
        
        $max_token_for_student = '';
        if($get_status_setting_region[0]->status_set_setting == 0){
            $get_setting = $this->global_settings_model->get_partner_settings();

            $max_token_for_student = $get_setting[0]->max_token_for_student; 
        } else {
            $get_setting = $this->specific_settings_model->get_partner_settings($id_partner);
            $max_token_for_student = $get_setting[0]->max_token_for_student;
        }
      
        // =======================

        // check token student_partner
        $get_user_token = $this->user_token_model->get_token($id,'user');
        $user_token = $get_user_token[0]->token_amount;
     
        // check jika token user tidak mencukupi
        if($user_token < $request_token){
            $this->messages->add('Your token is not enough ', 'warning');
            redirect('student_partner/add_token/');
        }

        // check token student
        $get_token = $this->user_token_model->get_token($student_id,'user');
        // $student_token = $get_token[0]->token_amount;
        $student_token = '';
        if($get_token){
            $student_token = $get_token[0]->token_amount;    
        } else {
            $student_token = 0;
        }
        // =================
        //get data for email
        $get_name = $this->user_profile_model->select('user_id, fullname')->where('user_id', $this->auth_manager->userid())->get_all();
        $get_name2 = $this->user_profile_model->select('user_id, fullname')->where('user_id', $student_id)->get_all();
        $get_email = $this->user_model->select('id, email, role_id')->where('id', $student_id)->get_all();
        
        // check jika total token melebihi max token
        $total_request_token = $request_token+$student_token;

        
        if($total_request_token > $max_token_for_student){
            $this->messages->add('Token Request exceeds the maximum, maximum token for student = '.$max_token_for_student, 'warning');
            redirect('student_partner/add_token/');
        }

    

        // jika gak ada masalah

        // pengurangan token user
        $this->db->trans_begin();

        $new_token_user = $user_token-$request_token;

        $data_user = array(
                'token_amount' => $new_token_user,
                'dcrea' => time(),
                'dupd' => time()
            );

        // echo "<pre>";
        // print_r($data_user);exit();

        $update_user_token = $this->db->where('user_id', $id)
                                      ->update('user_tokens', $data_user); 

        // // ===================

        // update token student


        if($get_token){
            $data_student = array('token_amount' => $total_request_token,
                                  'dcrea' => time(),
                                  'dupd' => time()
                                  );

            $update_student_token = $this->db->where('user_id', $student_id)
                                          ->update('user_tokens', $data_student); 
        } else {
            $data_student = array(
              'user_id' => $student_id,
              'token_amount' => $total_request_token,
              'dcrea' => time(),
              'dupd' => time()
            );
            $this->db->insert('user_tokens',$data_student);
        } 

        // insert into table request for information
        $insert_table_req = array('approve_id' => $this->auth_manager->userid(),
                                  'user_id' => $student_id,
                                  'token_amount' => $request_token,
                                  'status' => 'added',
                                  'dcrea' => time(),
                                  'dupd' => time());
        $this->db->insert('token_requests',$insert_table_req);

        // insert into token history
        $insert_table_hist = array('user_id' => $student_id,
                                   'transaction_date' => time(),
                                   'token_amount' => $request_token,
                                   'description' => 'Your Token has been added by your Student Partner',
                                   'token_status_id' => 15,
                                   'balance' => $total_request_token,
                                   'dcrea' => time(),
                                   'dupd' => time());
        $this->db->insert('token_histories',$insert_table_hist);


        $this->db->trans_commit();

        // send notification and email
        $partner_notification = array(
            'user_id' => $student_id,
            'description' => 'Your token has been added. '.$request_token,
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );


         $this->db->trans_commit();

        // messaging inserting data notification

        $this->user_notification_model->insert($partner_notification);
        $this->send_email->add_token_for_student($get_email[0]->email, $get_name[0]->fullname, $request_token, $get_email[0]->role_id, $get_name2[0]->fullname);


        $this->messages->add('Your token success added ', 'success');
        redirect('student_partner/add_token/');
            
    }

}