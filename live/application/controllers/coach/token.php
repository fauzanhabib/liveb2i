<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Token extends MY_Site_Controller {
    
    // Constructor
    public function __construct() {
        parent::__construct();

        // Checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CCH') {
            $this->messages->add('ERROR');
            redirect('home');
        }
    }
    
    public function index(){
        $this->template->title = 'Token Histories';
        
        $id = $this->auth_manager->userid();
        $token_hist_pull = $this->db->select('*')
                ->from('token_histories_coach')
                ->where('coach_id', $id)
                ->order_by('date', 'desc')
                ->order_by('time', 'desc')
                ->get()->result();
        
        $token_hist = array(
            'token_hist' => $token_hist_pull
        );
        // echo "<pre>";
        // print_r($token_hist);
        // exit();

        $this->template->content->view('default/contents/coach/token/index', $token_hist);
        $this->template->publish();
    }
}