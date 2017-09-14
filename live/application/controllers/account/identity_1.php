<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
class identity extends MY_Site_Controller {

    /**
     * Titles
     * @var array
     */
    var $titles = array(
        'STD' => 'Student Identity',
        'CCH' => 'Coach Identity',
        'PRT' => 'Partner Identity',
        'ADM' => 'Admin Identity',
        'SPR' => 'Student Partner Identity'
    );

    /**
     * Models
     * @var array
     * access link for role
     */
    var $access = array(
        'STD' => array('profile', 'token'),
        'CCH' => array('profile', 'education', 'geography'),
        'PRT' => array('profile'),
        'ADM' => array('profile'),
        'SPR' => array('profile'),
    );

    /**
     * Views
     * @var array
     */
    var $views = array(
        'STD' => 'student',
        'CCH' => 'coach',
        'PRT' => 'partner',
        'ADM' => 'admin',
        'SPR' => 'student_partner',
    );

    /**
     * Detail
     * @var array
     */
    var $detail = array(
        'education' => 'Education',
        'geography' => 'Home Town',
        'profile' => 'Profile',
        'social_media' => 'Social Media',
        'token' => 'Token',
    );

    /**
     * User role
     * @var string
     */
    var $role = '';
    
    var $upload_path = 'uploads/images/';

    // Constructor
    public function __construct() {
        parent::__construct();
        // Load Model
        $this->load->model('user_role_model');
        $this->load->model('user_model');
        $this->load->model('identity_model');
        // To convert id to name of Social Media
        $this->load->model('social_media_model');

        // Get user role
        $this->role = $this->auth_manager->role();
        if (!$this->role) {
            $this->messages->add('Error for displaying your profile', 'danger');
            redirect('home');
        }
    }

    // Index
    public function index() {
        $this->template->title = $this->titles[$this->role];
        //saving identity by role to use as link
        $vars = array('identity' => $this->access[$this->role]);

        $this->template->content->view('default/contents/identity/index', $vars);
        $this->template->publish();
    }

    // Detail
    public function detail($key) {
        //checking access previlege for role
        if (!in_array($key, $this->access[$this->role])) {
            $this->messages->add('Access Denied');
            redirect('account/identity');
        }

        // Querying record based on ID and role checking
        // If there is no social media will redirect to fill/login one of the social media
        $data = $this->identity_model->get_identity($key)->where('user_id', $this->auth_manager->userid())->get_all();
        if (!$data) {
            $this->messages->add('Record with given ID is not found', 'danger');
            redirect('account/identity');
        }

        if (!@$data[0]->profile_picture) {
            @$data[0]->profile_picture = '/uploads/images/profile.jpg';
        }
        
        //print_r($data);exit;

        $this->template->title = 'Detail ' . $this->detail[$key];
        $vars = array(
            'title' => 'Detail ' . $this->detail[$key],
            'data' => $data,
            // convert id to name of Social Media
            'id_to_name' => $this->social_media_model->dropdown('id', 'name'),
            'email' => $this->user_model->select('email')->where('id', $this->auth_manager->userid())->get()
        );

        $this->template->content->view('default/contents/identity/' . $key . '/index', $vars);
        // Publish template by specific identity part
        $this->template->publish();
    }

    // Edit
    public function edit($key = '', $id = '') {
        //checking access previlege for role
        if (!in_array($key, $this->access[$this->role])) {
            $this->messages->add('Access Denied');
            redirect('account/identity');
        }
        // setting key for edit data
        $this->session->set_userdata("key", $key);

        // Querying record based on ID and checking
        $data = $this->identity_model->get_identity($key)->where('user_id', $this->auth_manager->userid())->where('id', $id)->get();

        if (!$data) {
            $this->messages->add('Record with given ID is not found', 'danger');
            redirect('account/identity');
        }
        if (!$data->profile_picture) {
            $data->profile_picture = '/uploads/images/profile.jpg';
        }
        $this->template->title = 'Edit ' . $this->detail[$key];
        $vars = array(
            'title' => 'Edit ' . $this->detail[$key],
            'data' => $data,
            'form_action' => 'update'
        );
        $this->template->content->view('default/contents/identity/' . $key . '/form', $vars);
        $this->template->publish();
    }

    // Update
    public function update() {

        //checking access previlege for role
        if (!in_array($this->session->userdata("key"), $this->access[$this->role])) {
            $this->messages->add('Access Denied');
            redirect('account/identity');
        }

        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('account/identity');
        }
        
        //storing the data
        $data = array();
        
        $profile_picture = $this->do_upload('profile_picture');
        if(!$profile_picture){
            $this->messages->add('Failed to upload image', 'error');
        }else{
            $data['profile_picture'] = $this->upload_path . $profile_picture['file_name'];
        }
        
        foreach ($this->input->post() as $t => $value) {
            if ($t != "__submit") {
                $data[$t] = $value;
            }
        }
        
        // Inserting and checking
        $id = $this->identity_model->get_identity($this->session->userdata("key"))->select('id')->where('user_id', $this->auth_manager->userid())->get();
        if (!$this->identity_model->get_identity($this->session->userdata("key"))->update($id->id, $data)) {
            $this->messages->add(validation_errors(), 'danger');
            $this->edit($this->session->userdata("key"), $id->id);
            return;
        }

        //unsetting key
        $key = $this->session->userdata("key");
        $this->session->unset_userdata("key");

        $this->messages->add('Update Succeeded', 'success');
        redirect('account/identity/detail/' . $key);
    }

    private function do_upload($name) {
        $config['upload_path'] = $this->upload_path;
        $config['allowed_types'] = 'gif|jpg|png';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($name)) {
            //$this->upload->display_errors();
            return FALSE;
        } else {
            return $this->upload->data();
        }
    }
}
