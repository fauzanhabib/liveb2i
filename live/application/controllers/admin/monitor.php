<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class monitor extends MY_Site_Controller {
    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('identity_model');
        $this->load->model('user_model');
        $this->load->model('subgroup_model');
        $this->load->model('specific_settings_model');
        $this->load->model('user_token_model');
        $this->load->model('global_settings_model');

        $this->load->library('queue');
        $this->load->library('send_email');

        //checking user role and giving action
        if (!$this->auth_manager->role()) {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    public function index(){
        $this->template->title = "Monitor Accounts";

        $id = $this->auth->userid();
        $pull_reg_id = $this->db->select('region_id')
                     ->from('user_profiles')
                     ->where('user_id', $id)
                     ->get()->result();

        $reg_id = $pull_reg_id[0]->region_id;
        // =================
        // get sub group by partner id
        $pull_monitor = $this->db->select('u.id, us.fullname, u.email, u.status')
                      ->from('users u')
                      ->join('user_profiles us', 'us.user_id = u.id' )
                      ->where('u.role_id', '11')
                      ->where('us.region_id', $reg_id)
                      ->get()->result();

        // echo "<pre>";print_r($pull_monitor);exit();
        $vars = array(
            'monitor_list' => $pull_monitor
        );

        $this->template->content->view('default/contents/admin/monitor/index', $vars);
        $this->template->publish();

    }

    public function add_monitor_acc(){
      $id = $this->auth->userid();
      $mon_email = $this->input->post("email");
      $mon_name  = $this->input->post("name");

      $pull_reg_id = $this->db->select('region_id')
                   ->from('user_profiles')
                   ->where('user_id', $id)
                   ->get()->result();

      $reg_id = $pull_reg_id[0]->region_id;

      // $partner_id = $this->auth_manager->partner_id();
      $password = $this->generateRandomString();

      $user = array(
          'email' => $mon_email,
          'password' => $this->phpass->hash($password),
          'role_id' => '11',
          'status' => 'active',
      );

      if (!$this->isValidEmail($this->input->post('email'))) {
          $this->messages->add('Email has been used', 'danger');
          redirect('admin/monitor');
      }

      $user_id = $this->user_model->insert($user);

      $profile = array(
          'profile_picture' => 'uploads/images/profile.jpg', // default profile picture
          'user_id' => $user_id,
          'fullname' => $mon_name,
          'region_id' => $reg_id,
          'user_timezone' => 27,
      );

      $profile_id = $this->identity_model->get_identity('profile')->insert($profile);

      // echo "<pre>";print_r($profile_id);exit();

      // echo "<pre>";print_r($partners);exit();

      $this->send_email->create_user($mon_email, $password,'created', $mon_name, 'Regional Admin Monitor', $reg_id);

      $this->messages->add('Monitor Account Added Successfully', 'success');
      redirect('admin/monitor');

    }

    public function dis_mon(){
      $mon_id = $this->input->post("userid");

      $stat = array(
         'status' => 'disable'
      );

      $this->db->where('id', $mon_id);
      $this->db->update('users', $stat);

    }

    public function ena_mon(){
      $mon_id = $this->input->post("userid");

      $stat = array(
         'status' => 'active'
      );

      $this->db->where('id', $mon_id);
      $this->db->update('users', $stat);

    }

    public function del_mon(){
      $mon_id = $this->input->post("userid");

      $this->db->where('id', $mon_id);
      $this->db->delete('users');

      $this->db->where('user_id', $mon_id);
      $this->db->delete('user_profiles');

    }

    function isValidEmail($email = '') {
        if ($this->user_model->where('email', $email)->get_all()) {
            return false;
        } else {
            return true;
        }
    }

    function generateRandomString($length = 5) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }
}
