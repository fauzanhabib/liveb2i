<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class subgroup extends MY_Site_Controller {
	var $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    var $table = array(
        'education' => '$this->user_education_model',
        'geography' => 'user_geography_model',
        'profile' => '$this->user_profile_model',
        'social_media' => '$this->user_social_media_model',
        'token' => '$this->user_token_model',
    );

    // Constructor
    public function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('identity_model');
        $this->load->model('user_geography_model');
        $this->load->model('region_model');
        $this->load->model('subgroup_model');
        $this->load->model('schedule_model');
        $this->load->model('offwork_model');
        $this->load->model('coach_token_cost_model');
        $this->load->model('creator_member_model');
        $this->load->model('user_education_model');
        $this->load->model('timezone_model');

        // for messaging action and timing
        $this->load->library('queue');
		$this->load->library('common_function');
		
        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'PRT') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }
	
		function generateRandomString($length = 5) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }
	
  //   public function index($subgroup_id='', $page='') {
  //       $this->template->title = 'Add Subgroup';
  //       $offset = 0;
  //       $per_page = 6;
  //       $uri_segment = 4;
		// $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner/member_list/coach'.$subgroup_id), count($this->identity_model->get_coach_identity('','',$this->auth_manager->partner_id(),$subgroup_id)), $per_page, $uri_segment);
  //       $search_partner = $this->input->post('search_partner');
		
  //       $data = $this->identity_model->get_subgroup_identity(null,'coach');
  //       $vars = array(
  //       'data' => $data,
		// 'data2' => $this->identity_model->get_coach_identity('','',$this->auth_manager->partner_id(), '', $per_page, $offset, $subgroup_id),
		// 'pagination' => @$pagination,
  //       );
        
  //       $this->template->content->view('default/contents/partner/managing_subgroup/index', $vars);

  //       //publish template
  //       $this->template->publish();
  //   }

    public function index($page='') {
        $this->template->title = 'Add Group';
        $offset = 0;
        $per_page = 6;
        $uri_segment = 4;
        // exit($offset);

        $search_subgroup = $this->input->post('search_subgroup');

        if($search_subgroup != ''){
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner/subgroup/index'), count($this->identity_model->get_subgroup_identity(null,'coach',null,$search_subgroup)), $per_page, $uri_segment);
            $data = $this->identity_model->get_subgroup_identity(null,'coach','active',null,$search_subgroup,$per_page, $offset);
            $data2 = $this->identity_model->get_coach_identity('','',$this->auth_manager->partner_id(), '', $per_page, $offset, '');
        } else if($search_subgroup == ''){
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner/subgroup/index'), count($this->identity_model->get_subgroup_identity(null,'coach',null,null)), $per_page, $uri_segment);
            $data = $this->identity_model->get_subgroup_identity(null,'coach','active',null,null,$per_page, $offset);
            $data2 = $this->identity_model->get_coach_identity('','',$this->auth_manager->partner_id(), '', $per_page, $offset, '');
        }

        $id = $this->auth_manager->userid();
        $partner_id = $this->auth_manager->partner_id($id);

        $total_coach = $this->db->select('count(users.id) as id')
                                ->from('user_profiles')
                                ->join('users','users.id = user_profiles.user_id')
                                ->where('users.role_id','2')
                                ->where('users.status','active')
                                ->where('user_profiles.partner_id',$partner_id)
                                ->get()->result();
        $total_coach = $total_coach[0]->id;



        // Total Sessions ---------------------------------------------------------
        $all_coach = $this->db->select('*')
                                ->from('user_profiles')
                                ->join('users','users.id = user_profiles.user_id')
                                ->join('user_tokens','users.id = user_tokens.user_id')
                                ->join('subgroup','user_profiles.subgroup_id = subgroup.id')
                                ->where('users.role_id','2')
                                ->where('users.status','active')
                                ->where('subgroup.status','active')
                                ->where('subgroup.partner_id',$partner_id)
                                ->get()->result();

        $date_limit = date("Y-m-d");
        $hour_limit = date("H:i:s");
        $total_stud = count($all_coach);
        $w = 0;
        while($w < $total_stud){
            $asd[] = $all_coach[$w]->user_id;
            $session_pull[] = $this->db->select('*')
                    ->from('appointments')
                    ->where('coach_id',$asd[$w])
                    ->where('date <=',$date_limit)
                    ->where('status','completed')
                    ->get()->result();
            $w++;
        }
        
        $total_sess_val = count(@$session_pull, COUNT_RECURSIVE);
        $total_sess_val = $total_sess_val - $w;
        // Total Sessions ---------------------------------------------------------
        // echo "<pre>";
        // print_r($total_sess_val);
        // exit();

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
        'all_coach' => $all_coach,
        'pagination' => $pagination,
        'total_sess_val' => $total_sess_val,
        'total_coach' => $total_coach,
        'number_page' => $number_page
        );

        // echo "<pre>";
        // print_r($all_coach);
        // exit();
        
        $this->template->content->view('default/contents/partner/managing_subgroup/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function index_disable($page='') {
        $this->template->title = 'Add Group';
        $offset = 0;
        $per_page = 6;
        $uri_segment = 4;
        // exit($offset);

        $search_subgroup = $this->input->post('search_subgroup');

        if($search_subgroup != ''){
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner/subgroup/index'), count($this->identity_model->get_subgroup_identity(null,'coach',null,$search_subgroup)), $per_page, $uri_segment);
            $data = $this->identity_model->get_subgroup_identity(null,'coach','disable',null,$search_subgroup,$per_page, $offset);
            $data2 = $this->identity_model->get_coach_identity('','',$this->auth_manager->partner_id(), '', $per_page, $offset, '');
        } else if($search_subgroup == ''){
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner/subgroup/index'), count($this->identity_model->get_subgroup_identity(null,'coach',null,null)), $per_page, $uri_segment);
            $data = $this->identity_model->get_subgroup_identity(null,'coach','disable',null,null,$per_page, $offset);
            $data2 = $this->identity_model->get_coach_identity('','',$this->auth_manager->partner_id(), '', $per_page, $offset, '');
        }

        $id = $this->auth_manager->userid();
        $partner_id = $this->auth_manager->partner_id($id);

        $total_coach = $this->db->select('count(users.id) as id')
                                ->from('user_profiles')
                                ->join('users','users.id = user_profiles.user_id')
                                ->where('users.role_id','2')
                                ->where('users.status','disable')
                                ->where('user_profiles.partner_id',$partner_id)
                                ->get()->result();
        $total_coach = $total_coach[0]->id;



        // Total Sessions ---------------------------------------------------------
        $all_coach = $this->db->select('*')
                                ->from('user_profiles')
                                ->join('users','users.id = user_profiles.user_id')
                                ->join('user_tokens','users.id = user_tokens.user_id')
                                ->join('subgroup','user_profiles.subgroup_id = subgroup.id')
                                ->where('users.role_id','2')
                                ->where('users.status','disable')
                                ->where('subgroup.status','disable')
                                ->where('subgroup.partner_id',$partner_id)
                                ->get()->result();

        $date_limit = date("Y-m-d");
        $hour_limit = date("H:i:s");
        $total_stud = count($all_coach);
        $w = 0;
        while($w < $total_stud){
            $asd[] = $all_coach[$w]->user_id;
            $session_pull[] = $this->db->select('*')
                    ->from('appointments')
                    ->where('coach_id',$asd[$w])
                    ->where('date <=',$date_limit)
                    ->where('status','completed')
                    ->get()->result();
            $w++;
        }
        
        $total_sess_val = count(@$session_pull, COUNT_RECURSIVE);
        $total_sess_val = $total_sess_val - $w;
        // Total Sessions ---------------------------------------------------------
        // echo "<pre>";
        // print_r($total_sess_val);
        // exit();

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
        'all_coach' => $all_coach,
        'pagination' => $pagination,
        'total_sess_val' => $total_sess_val,
        'total_coach' => $total_coach,
        'number_page' => $number_page
        );

        // echo "<pre>";
        // print_r($all_coach);
        // exit();
        
        $this->template->content->view('default/contents/partner/managing_subgroup/index_disable', $vars);

        //publish template
        $this->template->publish();
    }
    
    public function add_subgroup() {
        $this->template->title = 'Add Group';
        $data = $this->identity_model->get_subgroup_identity();
        $vars = array(
            'data' => $data,
            'form_action' => 'create_subgroup'
        );
        //echo "<pre>";
        //print_r($vars);
        //exit;
        
        $this->template->content->view('default/contents/partner/managing_subgroup/add_subgroup_form', $vars);
        $this->template->publish();
    }
    
    public function create_subgroup() {
        // Creating a partner
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'warning');
            redirect('partner/subgroup');
        }
        if(!$partner_id){
            $partner_id = $this->auth_manager->partner_id();
        }
        
        $rules = array(
                array('field'=>'name', 'label' => 'Name', 'rules'=>'trim|required|xss_clean|max_length[30]|callback_is_subgroup_available'),
                
            );

            if (!$this->common_function->run_validation($rules)) {
                $this->messages->add(validation_errors(), 'warning');
                redirect('partner/subgroup/add_subgroup');
            }
        
        // inserting user data
        $partner = array(
            
            'name' => $this->input->post('name'),
            'type' => 'coach',
            'partner_id' => $partner_id,
        );


        // Inserting and checking to partner table
        $this->db->insert('subgroup', $partner);

        $this->messages->add('Inserting Group Successful', 'success');
        redirect('partner/subgroup');
    }

    public function is_subgroup_available($name) {
        $partner_id = $this->auth_manager->partner_id();
        if ($this->subgroup_model->where('name', $name)->where('type', 'coach')->where('partner_id', $partner_id)->get_all()) {
            $this->form_validation->set_message('is_subgroup_available', $name . ' has been registered, use another name');
            return false;
        } else {
            return true;
        }
    }
	
	public function setting($subgroup_id='', $id='')
    { 
		$data3 = $this->identity_model->get_subgroup_identity($id,'coach','',$subgroup_id);
        $setting = $this->region_model->get_specific_setting($id);
        $data_subgroup = $this->identity_model->get_subgroup_identity($id);
        $vars = ['data' => $setting,
                'id' => $id,
				'data_subgroup' => $data_subgroup,
				'data3' => $data3,
				'subgroup_id' => $subgroup_id,];
				
		

        $this->template->content->view('default/contents/partner/setting', $vars);
        $this->template->publish();
    }

    public function download($subgroup_id = ''){
        $partner = $this->auth_manager->partner_id();
        $coach = $this->db->select('up.user_id, up.fullname, s.name')
                            ->from('user_profiles up')
                            ->join('users u', 'u.id = up.user_id')
                            ->join('subgroup s', 's.id = up.subgroup_id')
                            ->where('u.role_id', 2)
                            ->where('u.status', 'active')
                            ->where('s.partner_id', $partner)
                            ->get()->result();
        
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=CoachLate.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<table border="1">
        <thead>
          <tr>
            <th>Coach Name</th>
            <th>Subgroup</th>
            <th>Appointment Date</th>
            <th>Start Time</th>
            <th>Coach Attendance</th>
          </tr>
        </thead>
        <tbody>';
        foreach(@$coach as $c) { 
            $data = $this->db->select('id, coach_id, date, start_time, cch_attend')->from('appointments')->where('coach_id', $c->user_id)->where('status', 'completed')->get()->result();
                foreach(@$data as $d) { 
                    $cch_att_dif = strtotime($d->cch_attend) - strtotime($d->start_time);
                    $cch_att_val = date("H:i:s", $cch_att_dif);
                    if($cch_att_dif > '300'){ ; echo '
          <tr>
            <td>';echo $c->fullname; echo '</td>
            <td>';echo $c->name; echo '</td>
            <td>';echo $d->date; echo '</td>
            <td>';echo $d->start_time; echo '</td>
            <td>';echo $d->cch_attend; echo '</td>
          </tr>';
      } } } echo '
          </tbody>
        </table>';
    }
	
    //---------------------------------------------------------------------------------------------------------------------
     public function edit_subgroup($subgroup_id = '', $page='', $id='') {
        
        $this->template->title = 'Edit Group';
        $offset = 0;
        $per_page = 6;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner/managing_subgroup/detail'.$subgroup_id), count($this->identity_model->get_coach_identity('','','',$this->auth_manager->partner_id(),$subgroup_id)), $per_page, $uri_segment);
        
        $data = $this->identity_model->get_subgroup_identity($id,'coach','',$subgroup_id);
        $vars = array(
            'data' => $data,
            'form_action' => 'update_subgroup',
            'data2' => $this->identity_model->get_coach_identity('','','',$this->auth_manager->partner_id(), '', '', '', $per_page, $offset, $subgroup_id),
            'subgroup_id' => $subgroup_id,
        );

        $this->template->content->view('default/contents/partner/managing_subgroup/detail', $vars);
        $this->template->publish();
    }

    public function list_coach($subgroup_id = '', $page='', $id='') {

        $this->template->title = 'Edit Group';
		$offset = 0;
        $per_page = 10;
        $uri_segment = 5;
		$pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner/subgroup/list_coach/'.$subgroup_id), count($this->identity_model->get_coach_identity('','','',$this->auth_manager->partner_id(),null,null,null)), $per_page, $uri_segment,$subgroup_id);
        
        $data = $this->identity_model->get_subgroup_identity($id,'coach','active','','');
        $partner_id = $this->auth_manager->partner_id($id);
        // Total Sessions ---------------------------------------------------------
        $all_coachs = $this->db->select('*')
                                ->from('user_profiles')
                                ->join('users','users.id = user_profiles.user_id')
                                ->join('user_tokens','users.id = user_tokens.user_id')
                                ->where('users.status','active')
                                ->where('users.role_id','2')
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
                    ->where('coach_id',$asd[$w])
                    ->where('date <=',$date_limit)
                    ->where('status','completed')
                    ->get()->result();
            $w++;
        }

        $total_sess_val = count(@$session_pull, COUNT_RECURSIVE);
        $total_sess_val = $total_sess_val - $w;
        // Total Sessions ---------------------------------------------------------

        $number_page = 0;
        if($page == ''){
            $number_page = (1 * $per_page)-$per_page+1;
            
        } else {
            $number_page = ($page * $per_page)-$per_page+1;
        }

        $vars = array(
            'data' => $data,
            'form_action' => 'update_subgroup',
			'data2' => $this->identity_model->get_coach_identity('','','',$this->auth_manager->partner_id(), '', '', '', '', $offset, $subgroup_id),
            'all_coachs' => $all_coachs,
            'total_coach' => $this->identity_model->get_coach_identity('','','',$this->auth_manager->partner_id()),
            'subgroup_id' => $subgroup_id,
			'total_sess_val' => $total_sess_val,
            'pagination' => $pagination,
            'number_page' => $number_page
        );
        // echo "<pre>";
        // print_r($vars);
        // exit();
        // die();

        $this->template->content->view('default/contents/partner/managing_subgroup/detail', $vars);
        $this->template->publish();
    }

    public function list_disable_coach($subgroup_id = '', $page='', $id='') {

        $this->template->title = 'Edit Group';
        $offset = 0;
        $per_page = 10;
        $uri_segment = 5;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner/subgroup/list_coach/'.$subgroup_id), count($this->identity_model->get_coach_identity('','','',$this->auth_manager->partner_id(),null,null,null)), $per_page, $uri_segment,$subgroup_id);
        
        $data = $this->identity_model->get_subgroup_identity($id,'coach','disable',null);
        $partner_id = $this->auth_manager->partner_id($id);
        // Total Sessions ---------------------------------------------------------
        $all_coachs = $this->db->select('*')
                                ->from('user_profiles')
                                ->join('users','users.id = user_profiles.user_id')
                                ->join('user_tokens','users.id = user_tokens.user_id')
                                ->where('users.status','disable')
                                ->where('users.role_id','2')
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
                    ->where('coach_id',$asd[$w])
                    ->where('date <=',$date_limit)
                    ->where('status','completed')
                    ->get()->result();
            $w++;
        }

        $total_sess_val = count(@$session_pull, COUNT_RECURSIVE);
        $total_sess_val = $total_sess_val - $w;
        // Total Sessions ---------------------------------------------------------

        $number_page = 0;
        if($page == ''){
            $number_page = (1 * $per_page)-$per_page+1;
            
        } else {
            $number_page = ($page * $per_page)-$per_page+1;
        }

        $vars = array(
            'data' => $data,
            'form_action' => 'update_subgroup',
            'data2' => $this->identity_model->get_coach_identity('','','',$this->auth_manager->partner_id(), '', '', '', '', $offset, $subgroup_id),
            'all_coachs' => $all_coachs,
            'total_coach' => $this->identity_model->get_coach_identity('','','',$this->auth_manager->partner_id()),
            'subgroup_id' => $subgroup_id,
            'total_sess_val' => $total_sess_val,
            'pagination' => $pagination,
            'number_page' => $number_page
        );

        

        $this->template->content->view('default/contents/partner/managing_subgroup/detail_disable', $vars);
        $this->template->publish();
    }

    public function update_subgroup($id = '') {

        // inserting user data
         if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('partner/subgroup');
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
            'name' => $this->input->post('name'),
        );
        
        // Inserting and checking to partner table
        $this->db->where('id', $id);
        $this->db->where('type', 'coach');
        $this->db->update('subgroup', $partner); 

        $this->messages->add('Update Group Successful', 'success');
        redirect('partner/subgroup');
    }

    // Delete
    // public function delete_subgroup($id='') {
    //   if(!empty($_POST['check_list'])) {
    //             $check_list = $_POST['check_list'];
    //             $type_submit = $_POST['__submit'];
       
    //             if($type_submit == 'delete_subgroup'){
    //                 // check apakah group ada isinya
    //                 $check_group = $this->user_profile_model->select('user_profiles.id')->join('users','users.id = user_profiles.user_id')->where('users.status','active')->where_in('user_profiles.subgroup_id',$check_list)->get_all();
    //                 if(count($check_group) == 0){
    //                     $this->db->trans_begin();

    //                         $this->db->where_in('id',$check_list);
    //                         $this->db->where('type','coach');
    //                         $this->db->delete('subgroup');

    //                     $this->db->trans_commit();
    //                     $this->messages->add('Deleted Succeeded', 'success');

    //                 } else if(count($check_group) > 0){
    //                     $this->messages->add('This group has a Coach', 'error');

    //                 }
    //             }
    //         }
    //   else{
    //                 $this->messages->add('Please Choose Subgroup', 'error');
    //    }

    //         redirect ('partner/subgroup');

    // }

    public function delete_subgroup($id='') {
          if(!empty($_POST['check_list'])) {
                    $check_list = $_POST['check_list'];
                    $type_submit = $_POST['__submit'];
           
                    if($type_submit == 'delete_subgroup'){
                        // check apakah group ada isinya
                        $check_group = $this->user_profile_model->select('*')->where_in('subgroup_id',$check_list)->get_all();
                        // $check_group2 = $this->user_profile_model->select('user_profiles.id')->join('users','users.id = user_profiles.user_id')->where('users.status','disable')->where_in('user_profiles.subgroup_id',$check_list)->get_all();
                        if(count($check_group) == 0){
                            $this->db->trans_begin();
                            $status = array(
                            'status' => 'disable',
                            );

                                $this->db->where_in('id',$check_list);
                                $this->db->where('type','coach');
                                $this->db->update('subgroup', $status);

                            $this->db->trans_commit();
                            $this->messages->add('Disable Group Succeeded', 'success');

                        } else if(count($check_group) > 0){
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
                        $this->db->where('type','coach');
                        $this->db->update('subgroup', $status);

                        $this->db->trans_commit();
                        $this->messages->add('Disable Group Succeeded', 'success');
                            // $this->messages->add('This group has a Coach', 'error');
                        } 
                        // else if(count($check_group2) > 0){
                        //     $this->db->trans_begin();
                        // $status = array(
                        //     'status' => 'disable',
                        // );
                        // foreach ($check_group2 as $c2){ 
                        // $this->db->where('id',$c2->user_id);
                        // $this->db->update('users', $status);
                        // }
                        // $this->db->flush_cache();

                        // $this->db->where_in('id',$check_list);
                        // $this->db->where('type','coach');
                        // $this->db->update('subgroup', $status);

                        // $this->db->trans_commit();
                        // $this->messages->add('Disable Group Succeeded', 'success');
                        //     // $this->messages->add('This group has a Coach', 'error');

                        // }
                    }
                }
          else{
                        $this->messages->add('Please Choose Group', 'error');
           }

          redirect ('partner/subgroup');

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
                        $this->db->where('type','coach');
                        $this->db->update('subgroup', $status);

                            // $this->db->where_in('id',$check_list);
                            // $this->db->where('type','student');
                            // $this->db->delete('subgroup');

                        $this->db->trans_commit();
                        $this->messages->add('Enable Group Succeeded', 'success');

                    }else if(count($check_group) == 0){
                        $this->db->trans_begin();
                        $status = array(
                            'status' => 'active',
                        );
                        $this->db->where_in('id',$check_list);
                        $this->db->where('type','coach');
                        $this->db->update('subgroup', $status);
                        $this->db->trans_commit();
                        $this->messages->add('Enable Group Succeeded', 'success');

                    }
                }
            }
       else{
                    $this->messages->add('Please Choose Subgroup', 'error');
       }
            redirect ('partner/subgroup');

    }

    function delete_coach($id){
        if(!empty($_POST['check_list'])) {
            $check_list = $_POST['check_list'];
            
            $type_submit = $_POST['_submit'];
            $now_date = date('Y-m-d');
            // check apakah coach ada appointment
            $check_appointment = $this->db->select('id, date, status, coach_id')
                                          ->from('appointments')
                                          ->where_in('coach_id',$check_list)
                                          ->where('date >=', $now_date)
                                          ->get()->result();

            if($check_appointment){

                $this->messages->add('This coach has upcoming session scheduled, reassign the session to delete this coach', 'error');
                redirect('partner/subgroup/list_coach/'.$id);

            } else {
                $status = array(
                    'status' => 'disable',
                    );
                $this->db->where('role_id',2);
                $this->db->where_in('id',$check_list);
                $this->db->update('users', $status);

                // $this->db->flush_cache();

                // $this->db->where_in('user_id',$check_list);
                // $this->db->delete('user_profiles');

                $this->messages->add('Delete Successful', 'success');

            }
            
        } else {
            $this->messages->add('Please choose coach', 'error');
        }

        redirect('partner/subgroup/list_coach/'.$id);
    }
	
	public function coach($subgroup_id = '') {
        
        $this->template->title = 'Add Coach';

        // get partner id
        // $get_partner_id = $this->user_profile_model->select('partner_id')->where('user_id',$this->auth_manager->userid())->get();

        // $partner_id = $get_partner_id->partner_id;
        // // =================
        // get sub group by partner id
        $getsubgroup = $this->subgroup_model->select('*')->where('partner_id',$this->auth_manager->partner_id())->where('type','coach')->where('id',$subgroup_id)->get_all();
        //$timezones = $this->timezone_model->where_not_in('minutes',array('-210','330','570',))->dropdown('id', 'timezone');
        $coach_type = $this->db->select('*')->from('coach_type')->get();

        // baru diedit 27 sept 2017
        $subgroup = $getsubgroup[0]->name;
        // foreach ($getsubgroup as $value) {
        //     $subgroup[$value->id] = $value->name; 
        // }

        $partner_id = $this->auth_manager->partner_id();
        $partner = $this->partner_model->select('name, address, country, state, city, zip')->where('id',$partner_id)->get_all();
        $partner_country = $partner[0]->country;

        $option_country = $this->common_function->country_code;
        $code = array_column($option_country, 'dial_code', 'name');
        $newoptions = $code;
        $arsearch = array_search($partner_country, array_column($option_country, 'name'));
        $dial_code = $option_country[$arsearch]['dial_code'];
        
        $vars = array(
            'form_action' => 'create_coach',
            'subgroup' => $subgroup,
            'subgroup_id' => $subgroup_id,
            'coach_type' => $coach_type,
            'server_code' => $this->common_function->server_code(),
            'option_country' => $this->common_function->country_code,
            'partner_country' => $partner_country,
            'dial_code' => $dial_code
        );


        $this->template->content->view('default/contents/adding/coach/form', $vars);
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

    public function create_coach() {
        // Creating a coach user data must be followed by creating profile, geography, education data and status still disable/need approval from admin
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('partner/subgroup/coach');
        }

        $rules = array(
            array('field'=>'fullname', 'label' => 'Name', 'rules'=>'trim|required|xss_clean|max_length[150]'),
            array('field'=>'email', 'label' => 'Email', 'rules'=>'trim|required|xss_clean|valid_email|max_length[150]|callback_is_email_available'),
            array('field'=>'date_of_birth', 'label' => 'Birthday', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'gender', 'label' => 'Gender', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'phone', 'label' => 'Phone Number', 'rules'=>'trim|required|xss_clean|max_length[150]'),
            array('field'=>'token_for_student', 'label' => 'Token Cost For Student', 'rules'=>'trim|required|xss_clean|numeric|max_length[150]'),
            //array('field'=>'token_for_group', 'label' => 'Token Cost For Group', 'rules'=>'trim|required|xss_clean|numeric|max_length[150]')
        );
        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            $this->coach();
            return;
        }
        
        // generating password
        $password = $this->generateRandomString();
        
        // Inserting user data
        $user = array(
            'email' => $this->input->post('email'),
            'password' => $this->phpass->hash($password),
            'role_id' => 2,
            'status' => 'disable',
            'dcrea' => time(),
            'dupd' => time()
        );


        $this->db->trans_begin();
        // Inserting and checking to users table then storing insert_id into $user_id
        $user_id = $this->user_model->insert($user);
        if (!$user_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->coach();
            return;
        }
//        $this->db->trans_rollback();
//        print_r($user_id); exit;

        // inserting creator member
        $creator_member = array(
            'creator_id' => $this->auth_manager->userid(),
            'member_id' => $user_id
        );
        
        
        $creator_member_id = $this->creator_member_model->insert($creator_member);
        
        if (!$creator_member_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->student();
            return;
        }


        // Inserting user profile data
        $user_id_to_partner_id = $this->identity_model->get_identity('profile')->dropdown('user_id', 'partner_id');
        $profile = array(
            'profile_picture' => 'uploads/images/profile.jpg',
            'user_id' => $user_id,
            'fullname' => $this->input->post('fullname'),
            'nickname' => $this->input->post('nickname'),
            'gender' => $this->input->post('gender'),
            'date_of_birth' => $this->input->post('date_of_birth'),
            'phone' => $this->input->post('phone'),
            'partner_id' => $this->auth_manager->partner_id(),
            'subgroup_id' => $this->input->post('subgroup'),
            'dcrea' => time(),
            'dupd' => time()
        );
        
        

        // Inserting and checking to profile table then storing it into users_profile table
        $profile_id = $this->identity_model->get_identity('profile')->insert($profile);
        if (!$profile_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->coach();
            return;
        }
        
        
        // Inserting coach token cost profile data
        $token_cost = array(
            'coach_id' => $user_id,
            'token_for_student' => $this->input->post('token_for_student'),
            'token_for_group' => $this->input->post('token_for_group'),
            'dcrea' => time(),
            'dupd' => time()
        );
        
        // Inserting and checking to profile table then storing it into users_profile table
        $token_cost_id = $this->coach_token_cost_model->insert($token_cost);
        if (!$token_cost_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->coach();
            return;
        }

        

        // Inserting user home town data
        $geography = array(
            'user_id' => $user_id,
            'country' => $this->input->post('country')
        );


        // Inserting and checking to geography table then storing it into users_georaphy table
        $geography_id = $this->user_geography_model->insert($geography);
        
        if (!$geography_id) {
            $this->user_model->delete($user_id);
            $this->messages->add(validation_errors(), 'danger');
            $this->coach();
            return;
        }



        // Inserting user education data
        $education = array(
            'user_id' => $user_id,
        );

        // Inserting and checking to geography table then storing it into users_georaphy table
        $education_id = $this->user_education_model->insert($education);
        if (!$education_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->coach();
            return;
        }
        
        // Inserting user schedule and offwork data
        foreach ($this->days as $d) {
            $schedule = array(
                'user_id' => $user_id,
                'day' => $d,
                'dcrea' => time(),
                'dupd' => time()
            );

            // Inserting and checking to geography table then storing it into users_georaphy table
            $schedule_id = $this->schedule_model->insert($schedule);
            if (!$schedule_id) {
                $this->db->trans_rollback();
                $this->messages->add(validation_errors(), 'danger');
                $this->coach();
                return;
            }

        }

        $this->db->trans_commit();
		$this->messages->add('Coach Added', 'success');
		redirect('partner/subgroup/coach');
	}
    
    
    
    public function is_email_available($email) {
        if ($this->user_model->where('email', $email)->get_all()) {
            $this->form_validation->set_message('is_email_available', $email . ' has been registered, use another email');
            return false;
        } else {
            return true;
        }
    }

    public function coach_detail($subgroup_id = '', $id = ''){

        $this->template->title = 'Coach Detail';
        $data = $this->identity_model->get_coach_identity($id, '', '', $this->auth_manager->partner_id(), '', '', '', '', '', $subgroup_id);
        
        if(!$data){
            $this->messages->add('Invalid Action', 'warning');
            redirect('partner/subgroup/list_coach/'.$subgroup_id);
        }

        $get_user_timezone = $this->db->select('minutes_val')->from('user_timezones')->where('user_id',$id)->get()->result();
    
        if(!$get_user_timezone){
            $minute_user_timezone = 0;            
        } else {
            $minute_user_timezone = $get_user_timezone[0]->minutes_val;
        }

        $get_utz =  $this->db->select('timezone')->from('timezones')->where('minutes',$minute_user_timezone)->get()->result();
        $user_tz = $get_utz[0]->timezone;

        $vars = array(
            'data' => $data,
            'coach_id' => $id,
            'user_tz' => $user_tz
        );
        

        $this->template->content->view('default/contents/partner/managing_subgroup/coach_detail', $vars);
        $this->template->publish();
    }


    // public function coach_detail($subgroup_id = '', $id = ''){
        
    //     $this->template->title = 'Coach Detail';
    //     $data = $this->identity_model->get_coach_identity($id, '', '', $this->auth_manager->partner_id());
    
    //     if(!$data){
    //         $this->messages->add('Invalid Action', 'warning');
    //         redirect('partner/subgroup/list_coach/'.$subgroup_id);
    //     }
    //     $vars = array(
    //         'data' => $data,
    //     );
        

    //     $this->template->content->view('default/contents/partner/managing_subgroup/coach_detail', $vars);
    //     $this->template->publish();
    // }

}