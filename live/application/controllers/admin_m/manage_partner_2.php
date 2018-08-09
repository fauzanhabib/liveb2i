<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class manage_partner extends MY_Site_Controller {

    var $upload_path = 'uploads/images/';
    
    // Constructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('partner_model');
        $this->load->model('user_model');
        $this->load->model('identity_model');
        $this->load->library('phpass');

        // for messaging action and timing
        $this->load->library('queue');
        
        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'RAM') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = 'Add Partner';
        $vars = array(
            'partner' => $this->partner_model->select('id, profile_picture, name, address, country, state, city, zip')->where('name not like', 'No Partner')->order_by('name', 'asc')->get_all(),
        );
        $this->template->content->view('default/contents/manage_partner/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function add_partner() {
        $this->template->title = 'Add Partner';
        $vars = array(
            'form_action' => 'create_partner'
        );
        $this->template->content->view('default/contents/manage_partner/add_partner/form', $vars);
        $this->template->publish();
    }

    
    public function partner_profile_picture($id=''){
        $profile_picture = $this->do_upload('profile_picture');
        if (!$profile_picture) {
            $this->messages->add('Failed to upload image', 'error');
            return $this->detail('profile');
        } 
        $data_profile_picture['profile_picture'] = $this->upload_path . $profile_picture['file_name'];
        $partner = $this->partner_model->select('id')->where('id', $id)->get();
        if (!$this->partner_model->update($partner->id, $data_profile_picture, TRUE)) {
            $this->messages->add('Update Partner Profile Picture Failed', 'success');
            redirect('admin_m/manage_partner/edit_partner/'.$id);
        }
        
        $this->messages->add('Partner Profile Picture has been Updated Successfully', 'success');
        redirect('admin_m/manage_partner/edit_partner/'.$id);
    }
    
    public function create_partner() {
        // Creating a partner
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('admin_m/manage_partner');
        }

        // inserting user data
        $partner = array(
            'profile_picture' => 'uploads/images/default_logo.jpg',
            'name' => $this->input->post('name'),
            'address' => $this->input->post('address'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'zip' => $this->input->post('zip'),
            'country' => $this->input->post('country'),
        );

        // Inserting and checking to partner table
        $this->db->trans_begin();
        if (!$this->partner_model->insert($partner)) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->add_partner();
            return;
        }
        $this->db->trans_commit();

        $this->messages->add('Inserting Partner Succeeded', 'success');
        redirect('admin_m/manage_partner');
    }

    public function edit_partner($id = '') {
        $this->template->title = 'Edit Partner';
        $data = $this->partner_model->select('id, name, address, country, state, city, zip')->where('id', $id)->get();
        $vars = array(
            'data' => $data,
            'form_action' => 'update_partner',
            'partner' => $this->partner_model->select('profile_picture')->where('id', $id)->get()
        );
        $this->template->content->view('default/contents/manage_partner/add_partner/form', $vars);
        $this->template->publish();
    }

    public function update_partner($id = '') {
        // Creating a partner
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('admin_m/manage_partner');
        }

        // inserting user data
        $partner = array(
            'name' => $this->input->post('name'),
            'address' => $this->input->post('address'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'zip' => $this->input->post('zip'),
            'country' => $this->input->post('country'),
        );

        // Inserting and checking to partner table
        $this->db->trans_begin();
        if (!$this->partner_model->update(@$id, $partner)) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->add_partner();
            return;
        }
        $this->db->trans_commit();

        $this->messages->add('Inserting Partner Succeeded', 'success');
        redirect('admin_m/manage_partner');
    }

    // Delete
    public function delete_partner($id = '') {
        $this->partner_model->delete($id);
        $this->messages->add('Delete Succeeded', 'success');
        redirect('admin_m/manage_partner');
    }

    public function add_partner_member($partner = '') {
        $this->template->title = 'Add Partner Member';
        $vars = array(
            'selected' => $partner,
            'partner' => $this->partner_model->where('name not like', 'No Partner')->dropdown('id', 'name'),
            'form_action' => 'create_partner_member'
        );
        $this->template->content->view('default/contents/manage_partner/add_partner_member/form', $vars);
        $this->template->publish();
    }

    public function create_partner_member() {
        // Creating a member user as role partner
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('admin_m/manage_partner');
        }

        // generating password
        $password = $this->generateRandomString();
        
        // inserting user data
        if($this->input->post('role_id') != 3 && $this->input->post('role_id') != 5){
            $this->messages->add('Invalid partner type', 'danger');
            redirect('admin_m/manage_partner');
        }
        
        if(!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)){
            $this->messages->add('Invalid Email', 'danger');
            $this->add_partner_member($this->input->post('partner_id'));
            return;
        }
        
        $user = array(
            'email' => $this->input->post('email'),
            'password' => $this->phpass->hash($password),
            'role_id' => $this->input->post('role_id'),
            'status' => 'active',
        );

        
        // checking if the email is valid/ not been used yet
        if (!$this->isValidEmail($this->input->post('email'))) {
            $this->messages->add('Email has been used', 'danger');
            $this->add_partner_member($this->input->post('partner_id'));
            return;
        }

        // Inserting and checking to users table then storing insert_id into $insert_id
        $this->db->trans_begin();
        $user_id = $this->user_model->insert($user);
        if (!$user_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->add_partner_member($this->input->post('partner_id'));
            return;
        }

        // Inserting user profile data
        $profile = array(
            'profile_picture' => 'uploads/images/profile.jpg', // default profile picture
            'user_id' => $user_id,
            'fullname' => $this->input->post('fullname'),
            'nickname' => $this->input->post('nickname'),
            'gender' => $this->input->post('gender'),
            'date_of_birth' => $this->input->post('date_of_birth'),
            'phone' => $this->input->post('phone'),
            'partner_id' => $this->input->post('partner_id'),
        );

        // Inserting and checking to profile table then storing it into users_profile table
        $profile_id = $this->identity_model->get_identity('profile')->insert($profile);
        if (!$profile_id) {
            $this->db->trans_rollback();
            $this->messages->add(validation_errors(), 'danger');
            $this->add_partner_member($this->input->post('partner_id'));
            return;
        }

        $this->db->trans_commit();

        $id_to_email_address = $this->user_model->dropdown('id', 'email');
        // Tube name for messaging sending email action
        $tube = 'com.live.email';
        // Email's content to inform partner admin their DynEd Live account
        $data_partner = array(
            'subject' => 'Welcome',
            'email' => $id_to_email_address[$user_id],
            'content' => 'Welcome to DynEd Live, account information: Email = ' . $user['email'] . ' Password = ' . $password . ' You can login to DynEd Live as Partner Admin. For more information, please ask the administrator. Thank you',
        );
        $this->queue->push($tube, $data_partner, 'email.send_email');

        //messaging for notification
        $database_tube = 'com.live.database';
        $partner_notification = array(
            'user_id' => $user_id,
            'description' => 'Hi '.$profile['fullname'].'. Welcome to DynEd Live, you have accsess as Partner admin.',
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
        $this->queue->push($database_tube, $data_partner, 'database.insert');

        $this->messages->add('Inserting Partner Member Succeeded', 'success');
        redirect('admin_m/manage_partner/list_partner_member/'.$this->input->post('partner_id'));
    }

    function generateRandomString($length = 5) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    function isValidEmail($email = '') {
        if ($this->user_model->where('email', $email)->get_all()) {
            return false;
        } else {
            return true;
        }
    }

    // Index
    public function list_partner_member($partner_id='') {
        $this->template->title = 'List Partner Member';
        
        if(!$partner_id){
            $this->messages->add('Invalid ID Partner', 'error');
            redirect('admin_m/manage_partner');
        }
        $users = $this->user_model->get_partner_members($partner_id);
        $partner = $this->partner_model->select('id, name')->where('id', $partner_id)->get();
        $vars = array(
            'users' => $users,
            'partner' => $partner,
        );
        $this->template->content->view('default/contents/manage_partner_member/index', $vars);

        //publish template
        $this->template->publish();
    }
    
    public function delete_partner_member($user_id = ''){
        $partner = $this->identity_model->get_identity('profile')->where('user_id', $user_id)->get();
        if($this->identity_model->get_partner_identity($user_id, '', '', '')){
            if($this->user_model->delete($user_id)){
                $this->messages->add('Delete Partner Member Succeeded', 'success');
                redirect('admin_m/manage_partner/list_partner_member/'.$partner->partner_id);
            }
            else{
                $this->messages->add('Invalid Action', 'danger');
                redirect('account/identity/detail/profile');
            }
        }
        else{
            $this->messages->add('Invalid Action', 'danger');
            redirect('account/identity/detail/profile');
        }
        
    }
    
    private function do_upload($name) {
        $config['upload_path'] = $this->upload_path;
        $config['allowed_types'] = 'gif|jpg|png';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($name)) {
            echo $this->upload->display_errors(); exit;
            return FALSE;
        } else {
            return $this->upload->data();
        }
    }
}
