<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class subgroup extends MY_Site_Controller {

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
        $this->load->model('user_geography_model');
        $this->load->model('student_detail_profile_model');
        $this->load->model('timezone_model');
        $this->load->library('common_function');
        
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
    
    public function index($page='') {

        $this->template->title = 'Student Subgroups';
        $offset = 0;
        $per_page = 6;
        $uri_segment = 4;
        // exit($offset);

        $search_subgroup = $this->input->post('search_subgroup');

        if($search_subgroup != ''){
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/subgroup/index'), count($this->identity_model->get_subgroup_identity(null,'student',null, $search_subgroup)), $per_page, $uri_segment);
            $data = $this->identity_model->get_subgroup_identity(null,'student','active',null,$search_subgroup,$per_page, $offset);
            $data2 = $this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(), '', $per_page, $offset, '');
        } else if($search_subgroup == ''){
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/subgroup/index'), count($this->identity_model->get_subgroup_identity(null,'student',null,null)), $per_page, $uri_segment);
            $data = $this->identity_model->get_subgroup_identity(null,'student','active',null,null,$per_page, $offset);
            $data2 = $this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(), '', $per_page, $offset, '');
        }

        $id = $this->auth_manager->userid();
        $partner_id = $this->auth_manager->partner_id($id);

        $total_coach = $this->db->select('count(users.id) as id')
                                ->from('user_profiles')
                                ->join('users','users.id = user_profiles.user_id')
                                ->join('subgroup','user_profiles.subgroup_id = subgroup.id')
                                ->where('users.role_id','1')
                                ->where('users.status','active')
                                ->where('subgroup.status','active')
                                ->where('subgroup.partner_id',$partner_id)
                                ->get()->result();
        $total_coach = $total_coach[0]->id;

        // EXPORTING DATA ---------------------------------------------------------------------------------------

        $pull_export = $this->db->select('*')
                                ->from('user_profiles')
                                ->join('users','users.id = user_profiles.user_id')
                                ->join('subgroup','user_profiles.subgroup_id = subgroup.id')
                                ->where('users.role_id','1')
                                ->where('users.status','active')
                                ->where('subgroup.status','active')
                                ->where('subgroup.partner_id',$partner_id)
                                ->get()->result();

        // EXPORTING DATA ---------------------------------------------------------------------------------------
        
        // Total Sessions ---------------------------------------------------------
        $date_limit = date("Y-m-d");
        $hour_limit = date("H:i:s");
        $total_stud = count($data2);
        $w = 0;
        while($w < $total_stud){
            $asd[] = $data2[$w]->id;
            $session_pull[] = $this->db->select('*')
                    ->from('appointments')
                    ->where('student_id',$asd[$w])
                    ->where('date <=',$date_limit)
                    ->where('status','completed')
                    ->get()->result();
            $w++;
        }
        
        $total_sess_val = count(@$session_pull, COUNT_RECURSIVE);
        // Total Sessions ---------------------------------------------------------
        $total_sess_val = $total_sess_val - $w;
        // echo "<pre>";
        // print_r($total_sess_val);exit();
        // echo $pagination;
        // exit();
        $number_page = 0;
        if($page == ''){
            $number_page = (1 * $per_page)-$per_page+1;
            
        } else {
            $number_page = ($page * $per_page)-$per_page+1;
        }
        $vars = array(
        'data' => $data,
        'data2' => $data2,
        'pagination' => $pagination,
        'total_sess_val' => $total_sess_val,
        'total_coach'    => $total_coach,
        'number_page' => $number_page
        );

        // echo "<pre>";
        // print_r($data2);
        // exit();
        
        $this->template->content->view('default/contents/student_partner/managing_subgroup/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function index_disable($page='') {

        $this->template->title = 'Student Subgroups';
        $offset = 0;
        $per_page = 6;
        $uri_segment = 4;
        // exit($offset);

        $search_subgroup = $this->input->post('search_subgroup');

        if($search_subgroup != ''){
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/subgroup/index'), count($this->identity_model->get_subgroup_identity(null,'student',null, $search_subgroup)), $per_page, $uri_segment);
            $data = $this->identity_model->get_subgroup_identity(null,'student','disable',null,$search_subgroup,$per_page, $offset);
            $data2 = $this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(), '', $per_page, $offset, '');
        } else if($search_subgroup == ''){
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/subgroup/index'), count($this->identity_model->get_subgroup_identity(null,'student',null,null)), $per_page, $uri_segment);
            $data = $this->identity_model->get_subgroup_identity(null,'student','disable',null,null,$per_page, $offset);
            $data2 = $this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(), '', $per_page, $offset, '');
        }

        $id = $this->auth_manager->userid();
        $partner_id = $this->auth_manager->partner_id($id);

        $total_coach = $this->db->select('count(users.id) as id')
                                ->from('user_profiles')
                                ->join('users','users.id = user_profiles.user_id')
                                ->join('subgroup','user_profiles.subgroup_id = subgroup.id')
                                ->where('users.role_id','1')
                                ->where('users.status','disable')
                                ->where('subgroup.status','disable')
                                ->where('subgroup.partner_id',$partner_id)
                                ->get()->result();
        $total_coach = $total_coach[0]->id;

        // EXPORTING DATA ---------------------------------------------------------------------------------------

        $pull_export = $this->db->select('*')
                                ->from('user_profiles')
                                ->join('users','users.id = user_profiles.user_id')
                                ->join('subgroup','user_profiles.subgroup_id = subgroup.id')
                                ->where('users.role_id','1')
                                ->where('users.status','disable')
                                ->where('subgroup.status','disable')
                                ->where('subgroup.partner_id',$partner_id)
                                ->get()->result();

        // EXPORTING DATA ---------------------------------------------------------------------------------------
        
        // Total Sessions ---------------------------------------------------------
        $date_limit = date("Y-m-d");
        $hour_limit = date("H:i:s");
        $total_stud = count($data2);
        $w = 0;
        while($w < $total_stud){
            $asd[] = $data2[$w]->id;
            $session_pull[] = $this->db->select('*')
                    ->from('appointments')
                    ->where('student_id',$asd[$w])
                    ->where('date <=',$date_limit)
                    ->where('status','completed')
                    ->get()->result();
            $w++;
        }
        
        $total_sess_val = count(@$session_pull, COUNT_RECURSIVE);
        // Total Sessions ---------------------------------------------------------
        $total_sess_val = $total_sess_val - $w;
        // echo "<pre>";
        // print_r($total_sess_val);exit();
        // echo $pagination;
        // exit();
        $number_page = 0;
        if($page == ''){
            $number_page = (1 * $per_page)-$per_page+1;
            
        } else {
            $number_page = ($page * $per_page)-$per_page+1;
        }
        $vars = array(
        'data' => $data,
        'data2' => $data2,
        'pagination' => $pagination,
        'total_sess_val' => $total_sess_val,
        'total_coach'    => $total_coach,
        'number_page' => $number_page
        );

        // echo "<pre>";
        // print_r($data2);
        // exit();
        
        $this->template->content->view('default/contents/student_partner/managing_subgroup/index_disable', $vars);

        //publish template
        $this->template->publish();
    }

    public function delete_subgroup($id=''){

            if(!empty($_POST['check_list'])) {
                $check_list = $_POST['check_list'];
                $type_submit = $_POST['__submit'];
                if($type_submit == 'delete_subgroup'){
                    // check apakah group ada isinya
                    $check_group = $this->user_profile_model->select('*')->where_in('subgroup_id',$check_list)->get_all();

                    if(count($check_group) > 0){
                        
                        $this->db->trans_begin();
                        $status = array(
                            'status' => 'disable',
                        );
                        foreach ($check_group as $c){ 
                        $this->db->where('id',$c->user_id);
                        $this->db->update('users', $status);
                        }
                        $this->db->flush_cache();

                        $this->db->where_in('id',$check_list);
                        $this->db->where('type','student');
                        $this->db->update('subgroup', $status);
                        

                            // $this->db->where_in('id',$check_list);
                            // $this->db->where('type','student');
                            // $this->db->delete('subgroup');

                        $this->db->trans_commit();
                        $this->messages->add('Disable Group Successful', 'success');

                    } else if(count($check_group) == 0){
                        $this->db->trans_begin();
                        $status = array(
                            'status' => 'disable',
                        );
                        $this->db->where_in('id',$check_list);
                        $this->db->where('type','student');
                        $this->db->update('subgroup', $status);
                        $this->db->trans_commit();
                        $this->messages->add('Disable Group Successful', 'success');

                    }
                }
            }
       else{
                    $this->messages->add('Please Choose Subgroup', 'error');
       }
            redirect ('student_partner/subgroup');

    }

    public function enable_subgroup($id=''){

            if(!empty($_POST['check_list'])) {
                $check_list = $_POST['check_list'];
                $type_submit = $_POST['__submit'];
                if($type_submit == 'enable_subgroup'){
                    // check apakah group ada isinya
                    $check_group = $this->user_profile_model->select('*')->where_in('subgroup_id',$check_list)->get_all();

                    if(count($check_group) > 0){
                        
                        $this->db->trans_begin();
                        $status = array(
                            'status' => 'active',
                        );
                        foreach ($check_group as $c){ 
                        $this->db->where('id',$c->user_id);
                        $this->db->update('users', $status);
                        }
                        $this->db->flush_cache();

                        $this->db->where_in('id',$check_list);
                        $this->db->where('type','student');
                        $this->db->update('subgroup', $status);

                            // $this->db->where_in('id',$check_list);
                            // $this->db->where('type','student');
                            // $this->db->delete('subgroup');

                        $this->db->trans_commit();
                        $this->messages->add('Enable Group Successful', 'success');

                    }else if(count($check_group) == 0){
                        $this->db->trans_begin();
                        $status = array(
                            'status' => 'active',
                        );
                        $this->db->where_in('id',$check_list);
                        $this->db->where('type','student');
                        $this->db->update('subgroup', $status);
                        $this->db->trans_commit();
                        $this->messages->add('Enable Group Successful', 'success');

                    }
                }
            }
       else{
                    $this->messages->add('Please Choose Subgroup', 'error');
       }
            redirect ('student_partner/subgroup');

    }
    
    public function add_subgroup() {
        $this->template->title = 'Add Subgroup';
        $data = $this->identity_model->get_subgroup_identity();
        $vars = array(
            'data' => $data,
            'form_action' => 'create_subgroup'
        );
        //echo "<pre>";
        //print_r($vars);
        //exit;
        
        $this->template->content->view('default/contents/student_partner/managing_subgroup/add_subgroup_form', $vars);
        $this->template->publish();
    }
    
    public function create_subgroup() {
        // Creating a student
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'warning');
            redirect('student_partner/subgroup');
        }
        if(!$partner_id){
            $partner_id = $this->auth_manager->partner_id();
        }
        
        $rules = array(
                array('field'=>'name', 'label' => 'Name', 'rules'=>'trim|required|max_length[30]|xss_clean|callback_is_subgroup_available'),
                
            );

            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                redirect('student_partner/subgroup/add_subgroup');
            }
        
        // inserting user data
        $student = array(
            
            'name' => htmlentities($this->input->post('name')),
            'type' => 'student',
            'partner_id' => $partner_id,
        );

        // Inserting and checking to partner table
        $this->db->insert('subgroup', $student);

        $this->messages->add('Inserting Subgroup Successful', 'success');
        redirect('student_partner/subgroup');
    }

    public function is_subgroup_available($name) {
        $partner_id = $this->auth_manager->partner_id();
        if ($this->subgroup_model->where('name', $name)->where('type', 'student')->where('partner_id', $partner_id)->get_all()) {
            $this->form_validation->set_message('is_subgroup_available', $name . ' has been registered, use another name');
            return false;
        } else {
            return true;
        }
    }
    //---------------------------------------------------------------------------------------------------------------------
     // public function list_student($subgroup_id = '', $id = '', $page='') {
     public function list_student($subgroup_id = '', $page='') {
         
        $this->template->title = 'Detail Subgroup';
        $offset = 0;
        $per_page = 10;
        $uri_segment = 5;

        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/subgroup/list_student/'.$subgroup_id), count($this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(),'')), $per_page, $uri_segment);
        // echo $subgroup_id." - ".$id." -". $page." = ".$per_page;exit();
        // echo "id ".$id;
        $data = $this->identity_model->get_subgroup_identity('','student','active','',null);

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


        // Total Sessions ---------------------------------------------------------
        $all_coachs = $this->db->select('*')
                                ->from('user_profiles')
                                ->join('users','users.id = user_profiles.user_id')
                                ->join('user_tokens','users.id = user_tokens.user_id')
                                ->where('users.status','active')
                                ->where('users.role_id','1')
                                ->where('user_profiles.subgroup_id',$subgroup_id)
                                ->get()->result();

        $date_limit = date("Y-m-d");
        $hour_limit = date("H:i:s");
        $total_stud = count($all_coachs);
        $w = 0;
        while($w < $total_stud){
            $asd[] = $all_coachs[$w]->user_id;
            $session_pull[] = $this->db->select('*')
                    ->from('appointments')
                    ->where('student_id',$asd[$w])
                    ->where('date <=',$date_limit)
                    ->where('status','completed')
                    ->get()->result();
            $w++;
        }
        
        $total_sess_val = count(@$session_pull, COUNT_RECURSIVE);
        // Total Sessions ---------------------------------------------------------
        $total_sess_val = $total_sess_val - $w;
        // echo $total_sess_val;
        // echo "<pre>";
        // print_r($session_pull);
        // exit();

        $number_page = 0;
        if($page == ''){
            $number_page = (1 * $per_page)-$per_page+1;
            
        } else {
            $number_page = ($page * $per_page)-$per_page+1;
        }
        $vars = array(
            'data' => $data,
            'form_action' => 'update_subgroup',
            'data2' => $this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(), '', '', $offset, $subgroup_id),
            'subgroup_id' => $subgroup_id,
            'pagination' => $pagination,
            'total_sess_val' => $total_sess_val,
            'total_coach' => $total_coach,
            'number_page' => $number_page
        );
        // echo "<pre>";
        // print_r($vars);
        // exit();
 

        $this->template->content->view('default/contents/student_partner/managing_subgroup/detail', $vars);
        $this->template->publish();
    }

    public function list_disable_student($subgroup_id = '', $page='') {
         
        $this->template->title = 'Detail Subgroup';
        $offset = 0;
        $per_page = 10;
        $uri_segment = 5;

        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/subgroup/list_disable_student/'.$subgroup_id), count($this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(),'')), $per_page, $uri_segment);
        // echo $subgroup_id." - ".$id." -". $page." = ".$per_page;exit();
        // echo "id ".$id;
        $data = $this->identity_model->get_subgroup_identity('','student','disable','',null);

        $id = $this->auth_manager->userid();
        $partner_id = $this->auth_manager->partner_id($id);

        $total_coach = $this->db->select('count(users.id) as id')
                                ->from('user_profiles')
                                ->join('users','users.id = user_profiles.user_id')
                                ->join('subgroup','user_profiles.subgroup_id = subgroup.id')
                                ->where('users.role_id','1')
                                ->where('users.status','disable')
                                ->where('subgroup.partner_id',$partner_id)
                                ->get()->result();
        $total_coach = $total_coach[0]->id;


        // Total Sessions ---------------------------------------------------------
        $all_coachs = $this->db->select('*')
                                ->from('user_profiles')
                                ->join('users','users.id = user_profiles.user_id')
                                ->join('user_tokens','users.id = user_tokens.user_id')
                                ->where('users.status','disable')
                                ->where('users.role_id','1')
                                ->where('user_profiles.subgroup_id',$subgroup_id)
                                ->get()->result();

        $date_limit = date("Y-m-d");
        $hour_limit = date("H:i:s");
        $total_stud = count($all_coachs);
        $w = 0;
        while($w < $total_stud){
            $asd[] = $all_coachs[$w]->user_id;
            $session_pull[] = $this->db->select('*')
                    ->from('appointments')
                    ->where('student_id',$asd[$w])
                    ->where('date <=',$date_limit)
                    ->where('status','completed')
                    ->get()->result();
            $w++;
        }
        
        $total_sess_val = count(@$session_pull, COUNT_RECURSIVE);
        // Total Sessions ---------------------------------------------------------
        $total_sess_val = $total_sess_val - $w;
        // echo $total_sess_val;
        // echo "<pre>";
        // print_r($session_pull);
        // exit();

        $number_page = 0;
        if($page == ''){
            $number_page = (1 * $per_page)-$per_page+1;
            
        } else {
            $number_page = ($page * $per_page)-$per_page+1;
        }
        $vars = array(
            'data' => $data,
            'form_action' => 'update_subgroup',
            'data2' => $this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(), '', '', $offset, $subgroup_id),
            'subgroup_id' => $subgroup_id,
            'pagination' => $pagination,
            'total_sess_val' => $total_sess_val,
            'total_coach' => $total_coach,
            'number_page' => $number_page
        );
        // echo "<pre>";
        // print_r($vars);
        // exit();
 

        $this->template->content->view('default/contents/student_partner/managing_subgroup/detail_disable', $vars);
        $this->template->publish();
    }

    public function edit_subgroup($subgroup_id = '', $page='', $id='') {
         
        $this->template->title = 'Edit Subgroup';
        $offset = 0;
        $per_page = 6;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student_partner/member_list/student'.$subgroup_id), count($this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(),$subgroup_id)), $per_page, $uri_segment);
        
        $data = $this->identity_model->get_subgroup_identity($id,'student','',$subgroup_id);
        $vars = array(
            'data' => $data,
            'form_action' => 'update_subgroup',
            'data2' => $this->identity_model->get_student_identity('','',$this->auth_manager->partner_id(), '', $per_page, $offset, $subgroup_id),
            'subgroup_id' => $subgroup_id,
        );
        /* echo "<pre>";
        print_r($vars);
        exit; */

        $this->template->content->view('default/contents/student_partner/managing_subgroup/detail', $vars);
        $this->template->publish();
    }

    public function update_subgroup($id = '') {

        // inserting user data
         if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('student_partner/subgroup');
        }
        $rules = array(
                array('field'=>'name', 'label' => 'Name', 'rules'=>'trim|required|xss_clean'),
            );

            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                $this->detail($id);
                return;
            }
        
        $partner = array(
            'name' => htmlentities($this->input->post('name')),
        );
        
        // Inserting and checking to partner table
        $this->db->where('id', $id);
        $this->db->where('type', 'student');
        $this->db->update('subgroup', $partner); 

        $this->messages->add('Update Region Successful', 'success');
        redirect('student_partner/subgroup');
    }

    // Delete student
    function delete_student($subgroup_id = ''){
  
        if(!empty($_POST['check_list'])) {
            $check_list = $_POST['check_list'];
            $type_submit = $_POST['_submit'];

            $now_date = date('Y-m-d');
            // check apakah coach ada appointment
            $check_appointment = $this->db->select('id, date, status, coach_id')
                                          ->from('appointments')
                                          ->where_in('student_id',$check_list)
                                          ->where('date >=', $now_date)
                                          ->get()->result();
            

            if($check_appointment){
                $this->messages->add('This student still have a session scheduled', 'error');
                redirect('student_partner/subgroup/list_student/'.$subgroup_id);
            } else {
            $status = array(
                    'status' => 'disable',
                    );
                    $this->db->where('role_id',1);
                    $this->db->where_in('id',$check_list);
                    $this->db->update('users', $status);
            
                //     $this->db->trans_begin();
                //     $this->db->where('role_id', 1);
                //     $this->db->where_in('id',$check_list);
                //     $this->db->delete('users');

                //     $this->db->flush_cache();

                //     $this->db->where_in('user_id',$check_list);
                //     $this->db->delete('user_profiles');

                //     $this->db->flush_cache();

                //     $this->db->where_in('user_id',$check_list);
                //     $this->db->delete('user_tokens');

                //     $this->db->flush_cache();

                //     $this->db->where_in('id_student',$check_list);
                //     $this->db->delete('student_supplier_to_student');
                // $this->db->trans_commit();
                $this->messages->add('Delete Successful', 'success');
            }
        } else {
            $this->messages->add('Please choose student', 'error');
        }
            redirect('student_partner/subgroup/list_student/'.$subgroup_id);
    }
    
    public function setting($subgroup_id='', $id='')
    { 
        $data3 = $this->identity_model->get_subgroup_identity($id,'student','',$subgroup_id);
        $setting = $this->region_model->get_specific_setting($id);
        $data_subgroup = $this->identity_model->get_subgroup_identity($id);
        $vars = ['data' => $setting,
                'id' => $id,
                'data_subgroup' => $data_subgroup,
                'data3' => $data3,
                'subgroup_id' => $subgroup_id,];
                
        

        $this->template->content->view('default/contents/student_partner/setting', $vars);
        $this->template->publish();
    }
    
    function update_setting($id, $subgroup_id){
        
            $data3 = $this->identity_model->get_subgroup_identity($id,'student','',$subgroup_id);
            $rules = array(
                array('field'=>'max_student_class', 'label' => 'max_student_class', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'max_student_supplier', 'label' => 'max_student_supplier', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'max_day_per_week', 'label' => 'max_day_per_week', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'max_session_per_day', 'label' => 'max_session_per_day', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'max_token', 'label' => 'max_token', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'max_token_for_student', 'label' => 'max_token_for_student', 'rules'=>'trim|required|xss_clean'),
               
            );

        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            redirect('student_partner/subgroup/setting/'.$id);
        }

       $setting = array(
            'max_student_class' => $this->input->post('max_student_class'),
            'max_student_supplier' => $this->input->post('max_student_supplier'),
            'max_day_per_week' => $this->input->post('max_day_per_week'),
            'max_session_per_day' => $this->input->post('max_session_per_day'),
            'max_token' => $this->input->post('max_token'),
            'max_token_for_student' => $this->input->post('max_token_for_student'),
            
        );
        

       // update token di table user token
       $update_token = $this->db->where('user_id',$id)->update('user_tokens',array('token_amount' => $this->input->post('max_token')));

       $this->region_model->update_setting($id,$setting);

       $this->messages->add('Update Setting Successful', 'success');

       redirect('student_partner/subgroup/setting/'.$id);
    }
    
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
    
    public function student($subgroup_id = '') {

        $setting = $this->db->select('*')->from('specific_settings')->where('partner_id',$subgroup_id)->get()->result();
        if(count($setting) == 0){
            $setting = $this->db->select('*')->from('global_settings')->where('type','partner')->get()->result();
        }
        $partner_id = $this->auth_manager->partner_id();
        $partner = $this->partner_model->select('name, address, country, state, city, zip')->where('id',$partner_id)->get_all();
        $partner_country = $partner[0]->country;

        $option_country = $this->common_function->country_code;
        $code = array_column($option_country, 'dial_code', 'name');
        $newoptions = $code;
        $arsearch = array_search($partner_country, array_column($option_country, 'name'));
        $dial_code = $option_country[$arsearch]['dial_code'];

        // get sub group by partner id
        $getsubgroup = $this->subgroup_model->select('*')->where('partner_id',$this->auth_manager->partner_id())->where('type','student')->where('id',$subgroup_id)->get_all();
        // echo "<pre>";
        // print_r($getsubgroup);
        // exit();

        // baru diedit 27 sept 2017
        $subgroup = $getsubgroup[0]->name;
        // foreach ($getsubgroup as $value) {
        //     $subgroup[$value->id] = $value->name; 
        // }
        //$timezones = $this->timezone_model->where_not_in('minutes',array('-210','330','570',))->dropdown('id', 'timezone');
        
        $this->template->title = 'Add Student';
        $vars = array(
            'form_action' => 'create_student',
            'max_student' => @$setting->max_student_class,
            'subgroup' => $subgroup,
            'subgroup_id' => $subgroup_id,
            'server_code' => $this->common_function->server_code(),
            'option_country' => $this->common_function->country_code,
            'partner_country' => $partner_country,
            'dial_code' => $dial_code
        );

            

        $this->template->content->view('default/contents/adding/student/form', $vars);
        $this->template->publish();
    }

    public function dial_code(){

        $country = $this->input->post('country');

        $option_country = $this->common_function->country_code;
        $code = array_column($option_country, 'dial_code', 'name');
        $newoptions = $code;
        $arsearch = array_search($country, array_column($option_country, 'name'));
        $dial_code = $option_country[$arsearch]['dial_code'];

        echo $dial_code;

    }


    public function create_student() {
        // Creating a student user data must be followed by creating profile data and status still disable/need approval from admin
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'warning');
            redirect('student_partner/member_list/student');
        }
        
        $rules = array(
            array('field'=>'fullname', 'label' => 'Name', 'rules'=>'trim|required|xss_clean|max_length[150]'),
            array('field'=>'email', 'label' => 'Email', 'rules'=>'trim|required|xss_clean|valid_email|max_length[150]|callback_is_email_available'),
            array('field'=>'date_of_birth', 'label' => 'Birthday', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'gender', 'label' => 'Gender', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'phone', 'label' => 'Phone Number', 'rules'=>'trim|required|xss_clean|max_length[150]'),
            array('field'=>'token_amount', 'label' => 'Token Amount', 'rules'=>'trim|required|xss_clean|numeric|max_length[150]')
        );
        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            $this->student();
            return;
        }

        $setting = $this->partner_setting_model->where('user_id',$this->auth_manager->partner_id())->get();
        // checking student quota           
        $student_member = count ($this->identity_model->get_student_identity('','',$this->auth_manager->partner_id()));
        if(@$student_member >= @$setting->max_student_class){
            $this->messages->add('Exceeded Maximum Quota', 'warning');
            redirect('student_partner/subgroup/student');
        }

        // cek maks student token 
        if($this->input->post('token_amount') > $setting->max_token_for_student)

        // generating password
        $password = $this->generateRandomString();
        
        // inserting user data
        $user = array(
            'email' => $this->input->post('email'),
            'password' => $this->phpass->hash($password),
            'role_id' => 1,
            'status' => 'active',
        );
        
        // Inserting and checking to users table then storing insert_id into $insert_id
        $this->db->trans_begin();
        $user_id = $this->user_model->insert($user);
        if (!$user_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'warning');
            $this->student();
            return;
        }

        // inserting creator member
        $creator_member = array(
            'creator_id' => $this->auth_manager->userid(),
            'member_id' => $user_id
        );

        if (!$this->creator_member_model->insert($creator_member)) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'warning');
            $this->student();
            return;
        }

        // Inserting user profile data
        $user_id_to_partner_id = $this->identity_model->get_identity('profile')->dropdown('user_id', 'partner_id');
        $profile = array(
            'profile_picture' => 'uploads/images/profile.jpg',
            'user_id' => $user_id,
            'fullname' => htmlentities($this->input->post('fullname')),
            'nickname' => htmlentities($this->input->post('nickname')),
            'gender' => htmlentities($this->input->post('gender')),
            'date_of_birth' => $this->input->post('date_of_birth'),
            'phone' => htmlentities($this->input->post('phone')),
            'partner_id' => $user_id_to_partner_id[$this->auth_manager->userid()],
            'subgroup_id' => htmlentities($this->input->post('subgroup')),
            'dcrea' => time(),
            'dupd' => time()
        );

        // Inserting and checking to profile table then storing it into users_profile table
        $profile_id = $this->identity_model->get_identity('profile')->insert($profile);
        if (!$profile_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'warning');
            $this->student();
            return;
        }

        // inserting user token data
        $token = array(
            'user_id' => $user_id,
            'token_amount' => htmlentities($this->input->post('token_amount')),
        );

        // Inserting and checking to profile table then storing it into users_profile table
        $token_id = $this->user_token_model->insert($token);
        if (!$token_id) {
            $this->db->trans_rollback();
            $this->student();
            return;
        }

        $geography = array(
            'user_id' => $user_id,
            'country' => htmlentities($this->input->post('country'))
        );
        $geography_id = $this->identity_model->get_identity('geography')->insert($geography, true);
        if (!$geography_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'warning');
            $this->student();
            return;
        }
        $student_detail_profile = array(
            'student_id' => $user_id,
        );
        $student_detail_profile_id = $this->student_detail_profile_model->insert($student_detail_profile);
        if (!$student_detail_profile_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'warning');
            $this->student();
            return;
        }

        $this->db->trans_commit();
        $this->messages->add('Student Added', 'success');
        redirect('partner/subgroup/student');
    }
    
    /* public function delete_student($student_id = ''){
        if($this->identity_model->get_student_identity('','',$this->auth_manager->partner_id())){
            if($this->user_model->delete($student_id)){
                $this->messages->add('Delete Student Succeeded', 'success');
            }
        }
        redirect('student_partner/member_list/student');
    } */
    
    public function is_email_available($email) {
        if ($this->user_model->where('email', $email)->get_all()) {
            $this->form_validation->set_message('is_email_available', $email . ' has been registered, use another email');
            return false;
        } else {
            return true;
        }
    }

    public function download(){
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=exceldata.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo '<table border="1">
          <tr>
            <td>ID</td>
            <td>First Name</td>
            <td>Last Name</td>
            <td>Important info</td>
          </tr>
          <tr>
            <td>John</td>
            <td>Doe</td>
            <td>Nothing really...</td>
          </tr>
        </table>';
    }
}