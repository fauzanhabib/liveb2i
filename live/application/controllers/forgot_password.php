<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class forgot_password extends MY_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->library('queue');
        $this->load->library('email_structure');
        $this->load->library('send_email');
    }

    // Index
    public function index() {
        //echo 'test'; exit;
        // User is already logged in
        if ($this->auth->loggedin()) {
            redirect('account/identity/detail/profile');
        }

        $this->template->title = 'Forgot Password';
        $this->template->set_template('default/contents/forgot_password/index');
        $this->template->publish();
    }

    public function send_password() {
        //print_r($this->input->post()); exit;
        if (!$this->input->post('__submit') || !$this->input->post('email')) {
            $this->messages->add('Invalid Action', 'warning');
            redirect('login');
        }

        $user = $this->user_model->where('email', $this->input->post('email'))->get();
        if (!$user) {
            $this->messages->add("Email doesn't exist", 'warning');
            redirect('forgot_password');
        }

        // generating password
        $password = $this->generateRandomString();

        $user_data = array(
            'password' => $this->phpass->hash($password),
        );
        $this->user_model->update($user->id, $user_data);

        $this->send_email->forgot_password($user->email, $password);
        
        $this->messages->add('New password has been sent', 'success');
        redirect('login');
    }

    private function generateRandomString($length = 5) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

}
