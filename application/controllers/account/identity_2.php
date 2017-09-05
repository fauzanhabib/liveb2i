<?php

if (!defined('BASEPATH')) {
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
        'STD' => array('profile', 'token', 'geography', 'student_detail'),
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
        'student_detail' => 'Student Detail',
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
            redirect('account/identity');
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

        // setting key for edit data
        $this->session->set_userdata("key", $key);

        // Querying record based on ID and role checking
        // If there is no social media will redirect to fill/login one of the social media
        if ($this->auth_manager->role() == 'STD') {
            $data = $this->identity_model->get_student_identity($this->auth_manager->userid());
        } else if ($this->auth_manager->role() == 'CCH') {
            $data = $this->identity_model->get_coach_identity($this->auth_manager->userid());
        } else if ($this->auth_manager->role() == 'PRT') {
            $data = $this->identity_model->get_partner_identity($this->auth_manager->userid());
        } else if ($this->auth_manager->role() == 'SPR') {
            $data = $this->identity_model->get_partner_identity($this->auth_manager->userid(), '', '', '', 5);
        } else if ($this->auth_manager->role() == 'ADM') {
            $data = $this->identity_model->get_identity('user')->select('*')->where('id', $this->auth_manager->userid())->get_all();
        }
        
        if (!$data) {
            $this->messages->add('Record with given ID is not found', 'danger');
            redirect('account/identity');
        }

        if (!@$data[0]->profile_picture) {
            @$data[0]->profile_picture = 'uploads/images/profile.jpg';
        }

        $this->template->title = 'Detail ' . $this->detail[$key];
        $vars = array(
            'title' => 'Detail ' . $this->detail[$key],
            'data' => $data,
            'form_action' => 'update',
            'id_to_name' => $this->social_media_model->dropdown('id', 'name'),
            'email' => $this->user_model->select('email')->where('id', $this->auth_manager->userid())->get()
        );

        $this->template->content->view('default/contents/identity/' . $key . '/index', $vars);
        // Publish template by specific identity part
        $this->template->publish();
    }

    // Edit
    public function edit($key = '') {
        //checking access previlege for role
        if (!in_array($key, $this->access[$this->role])) {
            $this->messages->add('Access Denied');
            redirect('account/identity');
        }
        // setting key for edit data
        $this->session->set_userdata("key", $key);

        // Querying record based on ID and checking
        $data = $this->identity_model->get_identity($key)->where('user_id', $this->auth_manager->userid())->get();
        if (!$data) {
            $this->messages->add('Record with given ID is not found', 'danger');
            redirect('account/identity');
        }
        /* if (!$data->profile_picture) {
          $data->profile_picture = '/uploads/images/profile.jpg';
          } */
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
    public function update($part = '') {

        //checking access previlege for role
        if (!in_array($this->session->userdata("key"), $this->access[$this->role])) {
            $this->messages->add('Access Denied');
            redirect('account/identity');
        }

        if(!$part == 'info'){
            if (!$this->input->post('__submit')) {
                $this->messages->add('Invalid action', 'danger');
                redirect('account/identity');
            }
        }

        //storing the data
        $data = array();

        /* $profile_picture = $this->do_upload('profile_picture');
          if(!$profile_picture){
          $this->messages->add('Failed to upload image', 'error');
          }else{
          $data['profile_picture'] = $this->upload_path . $profile_picture['file_name'];
          } */

        /* foreach ($this->input->post() as $t => $value) {
          if ($t != "__submit") {
          $data[$t] = $value;
          }
          } */

        if ($part == 'info') {
            foreach ($this->input->post() as $t => $value) {
                if ($t != "__submit") {
                    $data[$t] = $value;
                }
            }

            $id_profile = $this->identity_model->get_identity('profile')->select('id')->where('user_id', $this->auth_manager->userid())->get();
            if (!$this->identity_model->get_identity('profile')->update($id_profile->id, $data)) {
                $this->messages->add(validation_errors(), 'danger');
                $this->edit('profile', $id_profile->id);
                return;
            }
        } else if ($part == 'more_student_info') {
            $data_geography = Array(
                'ge.city' => $this->input->post('city'),
                'ge.state' => $this->input->post('state'),
                'ge.country' => $this->input->post('country')
            );

            $data_student_detail = Array(
                'sd.language_goal' => $this->input->post('language_goal'),
                'sd.hobby' => $this->input->post('hobby'),
                'sd.like' => $this->input->post('like'),
                'sd.dislike' => $this->input->post('dislike')
            );

            $geography = $this->identity_model->get_identity('geography')->select('id')->where('user_id', $this->auth_manager->userid())->get();
            $student_detail = $this->identity_model->get_identity('student_detail')->select('id')->where('student_id', $this->auth_manager->userid())->get();
            $this->load->library('form_validation');
            $this->form_validation->set_rules($this->identity_model->get_validation('geography'));
            $this->form_validation->set_rules($this->identity_model->get_validation('student_detail'));

            if (!$this->form_validation->run()) {
                $this->messages->add(validation_errors(), 'danger');
                $this->detail('profile');
                return;
            }

            $this->db->set($data_geography);
            $this->db->set($data_student_detail);
            $this->db->where('ge.id', $geography->id);
            $this->db->where('sd.id', $student_detail->id);
            $this->db->where('ge.user_id = sd.student_id');
            $this->db->update('user_geography as ge, student_detail_profiles as sd');

            if (!$this->db->affected_rows()) {
                $this->messages->add('Update Failed', 'danger');
            }

            /*
              $this->db->trans_start(TRUE);
              if (!$this->identity_model->get_identity('geography')->update($id_geography->id, $data_geography)) {
              $this->messages->add(validation_errors(), 'danger');
              $this->edit('geography', $id_geography->id);
              return;
              }

              if (!$this->identity_model->get_identity('student_detail')->update($id_student_detail->id, $data_student_detail)) {
              $this->messages->add(validation_errors(), 'danger');
              $this->edit('student_detail', $id_student_detail->id);
              return;
              }
              $this->db->trans_complete();
             */
        } elseif ($part == 'account') {
            $old_password = $this->input->post('old_password');
            if($this->input->post('new_password') != $this->input->post('confirm_password')){
                $this->messages->add('Incorrect Confirmation Password', 'danger');
                $this->detail('profile');
                return;
            }
            $data = Array('password' => $this->auth_manager->hashing_password($this->input->post('new_password')));

            if (!$this->auth_manager->check_password($this->auth_manager->user_email(), $old_password)) {
                $this->messages->add('Incorrect Old Password', 'danger');
                $this->detail('profile');
                return;
            }
            $user = $this->identity_model->get_identity('user')->select('id')->where('id', $this->auth_manager->userid())->get();
            if (!$this->identity_model->get_identity('user')->update($user->id, $data)) {
                $this->messages->add(validation_errors(), 'danger');
                $this->detail('profile');
                return;
            }
        } elseif ($part == 'profile_picture') {
            $profile_picture = $this->do_upload('profile_picture');
            if (!$profile_picture) {
                $this->messages->add('Failed to upload image', 'error');
                return $this->detail('profile');
            } 
            $data_profile_picture['profile_picture'] = $this->upload_path . $profile_picture['file_name'];
            $profile = $this->identity_model->get_identity('profile')->select('id')->where('user_id', $this->auth_manager->userid())->get();
            if (!$this->identity_model->get_identity('profile')->update($profile->id, $data_profile_picture, TRUE)) {
                $this->messages->add(validation_errors(), 'danger');
                return $this->detail('profile');
            }
        } elseif ($part == 'education') {
            foreach ($this->input->post() as $t => $value) {
                if ($t != "__submit") {
                    $data[$t] = $value;
                }
            }

            $id_profile = $this->identity_model->get_identity('education')->select('id')->where('user_id', $this->auth_manager->userid())->get();
            if (!$this->identity_model->get_identity('education')->update($id_profile->id, $data)) {
                $this->messages->add(validation_errors(), 'danger');
                $this->detail('profile');
                return;
            }
        }

        // Inserting and checking
        /* $id = $this->identity_model->get_identity($this->session->userdata("key"))->select('id')->where('user_id', $this->auth_manager->userid())->get();
          if (!$this->identity_model->get_identity($this->session->userdata("key"))->update($id->id, $data)) {
          $this->messages->add(validation_errors(), 'danger');
          $this->edit($this->session->userdata("key"), $id->id);
          return;
          } */

        $this->session->unset_userdata("key");
        $this->messages->add('Update Succeeded', 'success');
        redirect('account/identity/detail/profile');
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
