<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class member_list extends MY_Site_Controller {
    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('identity_model');
        $this->load->model('user_model');
        $this->load->model('subgroup_model');
        $this->load->model('specific_settings_model');
        $this->load->model('user_token_model');
        $this->load->model('global_settings_model');


        $this->load->library('send_email');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'SPN') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    public function subgroup(){
        $this->template->title = "Group";

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

        $this->template->content->view('default/contents/member_list_neo/student/subgroup/index', $vars);
        $this->template->publish();

    }

    public function student($subgroup_id = '',$page=''){
        $this->template->title = "Student Member";

        $offset = 0;
        $per_page = 6;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/member_list_neo/student'.$subgroup_id), count($this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(),$subgroup_id)), $per_page, $uri_segment);

        $vars = array(
            'title' => 'Student Member',
            'data' => $this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(), '', $per_page, $offset, $subgroup_id),
            'pagination' => @$pagination
        );

        // buat total token
         // $sum=0; foreach ($data as $d) { $sum += $d->token_amount; } echo "Total Token : ".$sum;
        $this->template->content->view('default/contents/member_list_neo/student/index', $vars);
        $this->template->publish();
    }

    public function edit_student($student_id =''){
        $this->template->title = "Edit Student";
        $vars = array(
            'title' => 'Edit Student',
            'data' => $this->identity_model->get_student_identity($student_id,'',$this->auth_manager->partner_id()),
            'form_action' => 'update_student',
        );
        // setting day for editing adding data
        $this->session->set_userdata("student_list_id", $student_id);

        //print_r($vars); exit;
        $this->template->content->view('default/contents/member_list_neo/student/form', $vars);
        $this->template->publish();
    }

    public function update_student($student_id = ''){

        // Creating a student user data must be followed by creating profile data and status still disable/need approval from admin
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('student_partner/member_list_neo/student_detail/'.$student_id);
        }

        $rules = array(
            array('field'=>'fullname', 'label' => 'Name', 'rules'=>'trim|required|xss_clean|max_length[150]'),
            array('field'=>'date_of_birth', 'label' => 'Birthday', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'gender', 'label' => 'Gender', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'phone', 'label' => 'Phone Number', 'rules'=>'trim|required|xss_clean|max_length[150]')
        );


        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            $this->edit_student($this->session->userdata("student_list_id"));
            return;
        }

        // updating coach token cost
        $student_data = array(
            'fullname' => $this->input->post('fullname'),
            'gender' => $this->input->post('gender'),
            'date_of_birth' => $this->input->post('date_of_birth'),
            'phone' => $this->input->post('phone'),
            'subgroup_id' => $this->input->post('subgroup'),
            'dupd' => time()
        );

        $profile_id = $this->identity_model->get_identity('profile')->select('id, user_id')->where('user_id', $this->session->userdata("student_list_id"))->get();
        // Updating and checking to users coach_token_cost
        if (!$this->identity_model->get_identity('profile')->update(@$profile_id->id,$student_data)) {
            $this->messages->add(validation_errors(), 'danger');
            $this->index();
            return;
        }

        //unsetting day_adding
        $this->session->unset_userdata("student_list_id");

        $this->messages->add('Update Successful', 'success');
        redirect('student_partner/member_list_neo/student_detail/'.$profile_id->user_id);
    }

    public function delete_student($student_id = ''){
        if($this->identity_model->get_student_identity('','',$this->auth_manager->partner_id())){
            if($this->user_model->delete($student_id)){
                $this->messages->add('Delete Student Successful', 'success');
            }
        }
        redirect('student_partner/member_list_neo/student');
    }

    public function student_detail($student_id='',$subgroup_id=''){
        $partner_id = $this->auth_manager->partner_id();
        $this->template->title = 'Student Detail';

        if(!$student_id || !$partner_id){
            $this->messages->add('Invalid Action', 'warning');
            redirect('student_partner/member_list_neo/student');
        }
        $this->session->set_userdata('student_id', $student_id);
        $data = $this->identity_model->get_student_identity($student_id, '', $partner_id);

        // get subgroup
        $subgroup = $this->subgroup_model->select('subgroup.name as subgroupname, subgroup.id as subgroupid')->join('user_profiles', 'user_profiles.subgroup_id = subgroup.id')->where('user_profiles.user_id',$student_id)->get_all();
        // =================
        // get sub group by partner id
        $getlistsubgroup = $this->subgroup_model->select('*')->where('partner_id',$partner_id)->where('type','student')->get_all();

        // $listsubgroup = '';
        foreach($getlistsubgroup as $value){
            $listsubgroup[$value->id] = $value->name;
        }

        if(!$data){
            $this->messages->add('Invalid ID', 'warning');
            redirect('student_partner/member_list_neo/student');
        }
        //$this->session->set_userdata('coach_id', $student_id);
        $back = base_url('index.php/student_partner/subgroup/list_student/'.$subgroup_id);
        $vars = array(
            'student_id' => $student_id,
            'student' => $data,
            'subgroup' => $subgroup,
            'listsubgroup' => $listsubgroup,
            'subgroup_id' => $subgroup_id,
            'back' => $back
        );
        // echo "<pre>";
        // print_r($subgroup);
        // exit();
        // die();
        $this->session->set_userdata("student_list_id", $student_id);
        $this->template->content->view('default/contents/member_list_neo/student/detail', $vars);
        $this->template->publish();
    }

    function add_token($student_id='',$subgroup_id=''){
        $this->template->title = 'Add Tokens';

        $get_token = $this->db->select('token_amount')
                          ->from('user_tokens')
                          ->where('user_id',$this->auth_manager->userid())
                          ->get()->result();

        $token = $get_token[0]->token_amount;

        $link = base_url().'index.php/student_partner/member_list_neo/action_add_token/'.$student_id.'/'.$subgroup_id;
        $cancel = base_url().'index.php/student_partner/member_list_neo/student_detail/'.$student_id.'/'.$subgroup_id;
        $vars = array(
                'link' => $link,
                'cancel' => $cancel,
                'token' => $token
                );

        $this->template->content->view('default/contents/managing/token/index', $vars);
        $this->template->publish();
    }

    function action_add_token($student_id = '',$subgroup_id = ''){
        date_default_timezone_set('Etc/GMT+0');

        $id = $this->auth_manager->userid();
        $id_partner = $this->auth_manager->partner_id($id);


        if (!$this->input->post('token') || $this->input->post('token') <=0) {
            $this->messages->add('Token Request Value Must be More than Zero', 'warning');
            redirect('student_partner/member_list_neo/add_token/'.$student_id.'/'.$subgroup_id);
        }

        $request_token = $this->input->post('token');


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
            redirect('student_partner/member_list_neo/add_token/'.$student_id.'/'.$subgroup_id);
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
            redirect('student_partner/member_list_neo/add_token/'.$student_id.'/'.$subgroup_id);
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
        $partner_id = $this->auth_manager->partner_id($student_id);
        $organization_id = '';
        $organization_id = $this->db->select('gv_organizations.id')
                  ->from('gv_organizations')
                  ->join('users', 'users.organization_code = gv_organizations.organization_code')
                  ->where('users.id', $student_id)
                  ->get()->result();

        $subgroup_id = $this->db->select('subgroup_id')
                                ->from('user_profiles')
                                ->where('user_profiles.user_id', $student_id)
                                ->get()->result();

        @$subgroup_id = $subgroup_id[0]->subgroup_id;

        if(empty($organization_id)){
            $organization_id = '';
        }else{
            $organization_id = $organization_id[0]->id;
        }

        $insert_table_hist = array('user_id' => $student_id,
                                   'partner_id' => $partner_id,
                                   'student_affiliate_subgroup_id' => $subgroup_id,
                                   'organization_id' => $organization_id,       
                                   'transaction_date' => time(),
                                   'token_amount' => $request_token,
                                   'description' => 'Your Token has been added by your Student Affiliate',
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
        $this->send_email->add_token($get_email[0]->email, $get_name[0]->fullname, $request_token, $get_email[0]->role_id, $get_name2[0]->fullname);


        $this->messages->add('Your token success added ', 'success');
        redirect('student_partner/member_list_neo/student_detail/'.$student_id.'/'.$subgroup_id);

    }
}
