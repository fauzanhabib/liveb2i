<?php

/**
 * Class    : Histories.php
 * Author   : Ponel Panjaitan (ponel@pistarlabs.co.id)
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Token_withdrawals extends MY_Site_Controller {
    
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
        $this->template->title = 'Token Withdrawals';
        
        $id = $this->auth_manager->userid();
        $balance_pull = $this->db->select('*')
                ->from('user_tokens')
                ->where('user_id', $id)
                ->get()->result();
        $balance_arr = @$balance_pull[0];
        $balance = array(
            'balance' => $balance_arr
        );
        // echo "<pre>";
        // print_r($balance);
        // exit();

        $this->template->content->view('default/contents/coach/token_withdrawals/index', $balance);
        $this->template->publish();
    }
}