<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class member_list extends MY_Site_Controller {
    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('identity_model');
        $this->load->model('user_model');
        $this->load->model('user_profile_model');
        $this->load->model('subgroup_model');
        $this->load->model('coach_token_cost_model');
        $this->load->model('appointment_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('user_log_model');
        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'PRT') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = 'Member List';
        $this->template->content->view('default/contents/member_list/index');

        //publish template
        $this->template->publish();
    }

    public function subgroup(){
        $this->template->title = "Subgroup";

        $partner_id = $this->auth_manager->partner_id();
        // =================
        // get sub group by partner id
        $subgroup = $this->subgroup_model->select('*')->join('user_profiles','user_profiles.subgroup_id = subgroup.id')->where('subgroup.partner_id',$partner_id)->where('subgroup.type','coach')->group_by('subgroup.id')->get_all();

        // echo "<pre>";
        // print_r($subgroup);
        // exit();
        $vars = [
            'subgroup' => $subgroup
        ];

        $this->template->content->view('default/contents/member_list/coach/subgroup/index', $vars);
        $this->template->publish();

    }
    
    public function coach($subgroup_id = '',$page=''){
        $this->template->title = "Coach Member";
        $offset = 0;
        $per_page = 6;
        $uri_segment = 5;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner/member_list/coach/'.$subgroup_id), count($this->identity_model->get_coach_identity('','','',$this->auth_manager->partner_id(),$subgroup_id)), $per_page, $uri_segment);
        $vars = array(
            'title' => 'Coach Member',
            'data' => $this->identity_model->get_coach_identity('','','',$this->auth_manager->partner_id(), '', '', '', $per_page, $offset, $subgroup_id),
            'pagination' => @$pagination
        );
        
        $this->template->content->view('default/contents/member_list/coach/index', $vars);
        $this->template->publish();
    }
    
    public function edit_coach($coach_id = ''){
        $this->template->title = "Edit Coach";
        $vars = array(
            'title' => 'Edit Coach',
            'data' => $this->identity_model->get_coach_identity($coach_id,'','',$this->auth_manager->partner_id()),
            'token_for_student' => $this->coach_token_cost_model->where('coach_id', $coach_id)->get(),
            'form_action' => 'update_coach',
        );
        // setting day for editing adding data
        $this->session->set_userdata("coach_list_id", $coach_id);
        
        //print_r($vars); exit;
        $this->template->content->view('default/contents/member_list/coach/form', $vars);
        $this->template->publish();
    }
    
    public function update_coach(){
        // Creating a student user data must be followed by creating profile data and status still disable/need approval from admin
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('partner/member_list');
        }
        
        $rules = array(
            array('field'=>'fullname', 'label' => 'Name', 'rules'=>'trim|required|xss_clean|max_length[150]'),
            array('field'=>'date_of_birth', 'label' => 'Birthday', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'gender', 'label' => 'Gender', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'phone', 'label' => 'Phone Number', 'rules'=>'trim|required|xss_clean|max_length[150]'),
            array('field'=>'token_for_student', 'label' => 'Token Cost For Student', 'rules'=>'trim|required|xss_clean|numeric|max_length[150]'),
            //array('field'=>'token_for_group', 'label' => 'Token Cost For Group', 'rules'=>'trim|required|xss_clean|numeric|max_length[150]')
        );
        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            $this->edit_coach($this->session->userdata("coach_list_id"));
            return;
        }

        if($this->input->post('date_of_birth') > date('Y-m-d', now())){
            $this->messages->add('Invalid Date', 'warning');
            $this->edit_coach($this->session->userdata("coach_list_id"));
            return;
        }
        
        // updating coach token cost
        $coach_data = array(
            'fullname' => $this->input->post('fullname'),
            'nickname' => $this->input->post('nickname'),
            'gender' => $this->input->post('gender'),
            'date_of_birth' => $this->input->post('date_of_birth'),
            'phone' => $this->input->post('phone'),
            'subgroup_id' => $this->input->post('subgroup'),
            'dupd' => time()
        );

        $profile_id = $this->identity_model->get_identity('profile')->select('id, user_id')->where('user_id', $this->session->userdata("coach_list_id"))->get();
        // Updating and checking to users coach_token_cost
        if (!$this->identity_model->get_identity('profile')->update(@$profile_id->id,$coach_data)) {
            $this->messages->add(validation_errors(), 'danger');
            $this->edit_coach($this->session->userdata("coach_list_id"));
            return;
        }
        
        $coach_token_data = array(
            'token_for_student' => $this->input->post('token_for_student'),
            //'token_for_group' => $this->input->post('token_for_group')
        );
        $coach_token_cost_id = $this->coach_token_cost_model->select('id')->where('coach_id', $this->session->userdata("coach_list_id"))->get();
        // Updating and checking to users coach_token_cost
        if (!$this->coach_token_cost_model->update(@$coach_token_cost_id->id, $coach_token_data)) {
            $this->messages->add(validation_errors(), 'danger');
            $this->index();
            return;
        }
        
        //unsetting day_adding
        $this->session->unset_userdata("coach_list_id");
        
        $this->messages->add('Update Succeeded', 'success');
        redirect('partner/member_list/detail/'.$profile_id->user_id);
    }
    
    public function delete_coach($coach_id = ''){
        // checking if there are still remaining upcoming session of coach, if so the session must be assign to another coach or cancelled
        $one_to_one_schedule = $this->appointment_model->get_appointment_for_upcoming_session('coach_id', '', '', $coach_id);
        $class_schedule = $this->class_meeting_day_model->get_appointment_for_upcoming_session('', '', $coach_id);
        if($one_to_one_schedule || $class_schedule){
            $this->messages->add('Coach still has remaining upcoming sessoin, please reassign to another coach or cancel the session', 'warning');
            redirect('partner/member_list/detail/'.$coach_id);
        }
        if($this->identity_model->get_coach_identity($coach_id,'','',$this->auth_manager->partner_id())){
            //creating log
            $coach_name = $this->identity_model->get_identity('profile')->select('fullname')->where('user_id',$coach_id)->get();
            $admin_name = $this->identity_model->get_identity('profile')->select('fullname')->where('user_id',$this->auth_manager->userid())->get();
            $data = array(
                'user_id' => $this->auth_manager->userid(),
                'description' => 'Coach Partner Admin ' .$admin_name->fullname. ' deleted coach ' .$coach_name->fullname ,
            );
            if($this->user_log_model->insert($data)){
                if($this->user_model->delete($coach_id)){
                    $this->messages->add('Delete Coach Succeeded', 'success');
                }
            }
            //print_r($data); exit;
        }
        redirect('partner/member_list/coach');
    }
    
    public function detail($coach_id=''){
        $this->template->title = 'Coach Detail';
        if(!$coach_id){
            $this->messages->add('Invalid Action', 'warning');
            redirect('partner/member_list/coach');
        }
        $data = $this->identity_model->get_coach_identity($coach_id, '', '', $this->auth_manager->partner_id());
            
        // get subgroup
        $subgroup = $this->subgroup_model->select('subgroup.name as subgroupname')->join('user_profiles', 'user_profiles.subgroup_id = subgroup.id')->where('user_profiles.user_id',$coach_id)->get();

        // list subgroup
        $partner_id = $this->auth_manager->partner_id();
        // =================
        // get sub group by partner id
        $getlistsubgroup = $this->subgroup_model->select('*')->where('partner_id',$partner_id)->where('type','coach')->get_all();

        $listsubgroup = '';
        foreach ($getlistsubgroup as $value) {
            $listsubgroup[$value->id] = $value->name;        
        }
        
        if(!$data){
            $this->messages->add('Invalid ID', 'warning');
            redirect('partner/member_list/coach');
        }
        $this->session->set_userdata('coach_id', $coach_id);
        // setting day for editing adding data
        $this->session->set_userdata("coach_list_id", $coach_id);
        $vars = array(
            'data' => $data,
            'subgroup' => $subgroup,
            'listsubgroup' => $listsubgroup
        );
        $this->template->content->view('default/contents/member_list/coach/detail', $vars);
        $this->template->publish();
    }
    
    /**
     * Function availability
     * @param (string)(search_by) redirecting page by value of search_by
     * @param (string)(coach_id) coach id to get schedule
     * @param (date)(date) detail of date
     */
    
    public function availability($search_by = '', $coach_id = '', $date_ = '') {
        $this->load->library('schedule_function');
        $data = $this->schedule_function->availability($search_by, $coach_id, $date_);
        $this->template->content->view('default/contents/member_list/coach/availability', $data);
        $this->template->publish();
    }
    
    public function schedule_detail($id = '') {
        $this->load->library('schedule_function');
        $vars = $this->schedule_function->schedule_detail($id);
        $this->template->content->view('default/contents/find_coach/schedule_detail', $vars);
        $this->template->publish();
    }
}