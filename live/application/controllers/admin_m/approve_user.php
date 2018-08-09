<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class approve_user extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('creator_member_model');
        $this->load->model('user_notification_model');

        // for messaging action and timing
        $this->load->library('queue');
        $this->load->library('email_structure');
        $this->load->library('send_email');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'RAM') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index($type='',$page='') {
        $this->template->title = 'Approve User';

        $offset = 0;
        $per_page = 6;
        $uri_segment = 5;
        // $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/manage_partner/index'), count($this->user_model->select('id, email, role_id, status')->where('status', 'disable')->order_by('id', 'desc')->get_all()), $per_page, $uri_segment);
        $partner = $this->partner_model->select('id,name')->get_All();

        $roles = array('1', '2');
        $vars = array(
            'users' => $this->user_model->select('users.id as id, users.email as email, users.role_id as role_id, users.status as status, users.password as password, user_profiles.fullname as fullname, user_profiles.pt_score as pt_score')->join('user_profiles','user_profiles.user_id = users.id')->where_in('users.role_id',$roles)->where('users.status', 'disable')->order_by('users.id', 'asc')->get_all(),
            // 'pagination' => @$pagination
            'partner' => $partner

        );

        if($type == 'coach'){
            $this->template->content->view('default/contents/approve_user/coach', $vars);
        } else if($type == 'student'){
            $this->template->content->view('default/contents/approve_user/student', $vars);
        }

        //publish template
        $this->template->publish();
    }

    public function approve($id = '',$type='') {
        //echo($id); exit;
        // Checking ID
        if (!$id) {
            $this->messages->add('Invalid User ID', 'danger');
            redirect('admin_m/approve_user');
        }

        // Storing user data
        $user_data = $this->user_model->select('users.email as email, users.password as password, user_profiles.fullname as fullname')->join('user_profiles','user_profiles.user_id = users.id')->where('users.id', $id)->get();
        $users = array(
            'email' => $user_data->email,
            'password' => $user_data->password,
            'status' => 'active'
        );

        // Inserting and checking
        if (!$this->user_model->update($id, $users)) {
            $this->messages->add(validation_errors(), 'Invalid ID');
            redirect('admin_m/approve_user/'.$type);
        }

        // notifikasi
        $creator = $this->db->select('creator_id')->from('creator_members')->where('member_id',$id)->get()->result();

        $partner_notification = array(
            'user_id' => $creator[0]->creator_id,
            'description' => 'The '.$type.' '.$user_data->fullname.' has been approved',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );
        // echo "<pre>";
        // print_r($partner_notification);
        // exit;
        // coach's data for reminder messaging
        // IMPORTANT : array index in content must be in mutual with table field in database
        $data_partner = array(
            'table' => 'user_notifications',
            'content' => $partner_notification,
        );

        // messaging inserting data notification

        $this->user_notification_model->insert($partner_notification);

        $member_notification = array(
            'user_id' => $id,
            'description' => 'Congratuliation '.$user_data->fullname.' and Welcome to DynEd Live.',
            'status' => 2,
            'dcrea' => time(),
            'dupd' => time(),
        );

        $this->user_notification_model->insert($member_notification);


        // =========
        $email_partner = $this->user_model->select('id, email')->where('id', $creator[0]->creator_id)->get_all();
        $partnermail = $email_partner[0]->email;
        // echo $partnermail;
        // exit;
        // kirim email
        $this->send_email->superadmin_approval_supplier($user_data->email,'approved',$user_data->fullname, $type);
        //$this->send_email->notif_partner($partnermail, '','approved',$user_data->fullname, $type);

        // $this->send_email->admin_approval_user($user_data->email,'','approved',$user_data->fullname, $type);
        // $this->send_email->notif_partner($partnermail, '','approved',$user_data->fullname, $type);

        $this->messages->add('Update Successful', 'success');
        redirect('admin_m/approve_user/index/'.$type);
    }

    public function decline($id = '',$type='') {
        // Checking ID
        if (!$id) {
            $this->messages->add('Invalid User ID', 'danger');
            redirect('admin_m/approve_user/index/'.$type);
        }


        $user_data = $this->user_model->select('users.email as email, users.password as password, user_profiles.fullname as fullname')->join('user_profiles','user_profiles.user_id = users.id')->where('users.id', $id)->get();

        // kirim email
        $this->send_email->superadmin_approval_supplier($user_data->email,'declined',$user_data->fullname, $type);
        $creator = $this->db->select('creator_id')->from('creator_members')->where('member_id',$id)->get()->result();

        // Inserting and checking
        // delete user from user profile
        $delete_user_profiles = $this->db->where('user_id',$id)->delete('user_profiles');
        if (!$this->user_model->delete($id)) {
            $this->messages->add(validation_errors(), 'Invalid ID');
            redirect('admin_m/approve_user/index/'.$type);
        }

        // delete user in table student_supplier_to_student
        $delete_student_supplier_to_student = $this->db->where('id_student',$id)->delete('student_supplier_to_student');


         // notifikasi

        $partner_notification = array(
            'user_id' => $creator[0]->creator_id,
            'description' => 'The '.$type.' '.$user_data->fullname.' has been declined',
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

        // =========
        $email_partner = $this->user_model->select('id, email')->where('id', $creator[0]->creator_id)->get_all();
        $partnermail = $email_partner[0]->email;
        // $this->send_email->notif_partner($partnermail, '','declined',$user_data->fullname, $type);
        // $this->send_email->superadmin_approval_supplier($user_data->email,'declined',$user_data->fullname, $type);
        //$this->send_email->notif_partner($partnermail, '','approved',$user_data->fullname, $type);

        $this->messages->add('Update Successful', 'success');
        redirect('admin_m/approve_user/index/'.$type);
    }

}
