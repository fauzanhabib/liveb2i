<?php

/**
 * Class    : Histories.php
 * Author   : Ponel Panjaitan (ponel@pistarlabs.co.id)
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Histories extends MY_Site_Controller {
    
    // Constructor
    public function __construct() {
        parent::__construct();
        
        // Loading models
        $this->load->model('partner_model');
        $this->load->model('user_model');
        $this->load->model('appointment_model');

        // Checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'ADM') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }
    
    public function partner(){
        $this->template->title = 'Partner';
        $partners = $this->partner_model->select('id, name, address')->get_all();
        
        $vars = array(
            'form_action' => 'search',
            'partners' => $partners,
        );
        
        $this->template->content->view('default/contents/histories/partner', $vars);
        $this->template->publish();
    }
    
    public function coach($partner_id){
        $this->template->title = 'Partner';
        $coaches = $this->user_model->get_coach_by_partner($partner_id);
        
        $vars = array(
            'form_action' => 'search',
            'coaches' => $coaches,
        );
        
        $this->template->content->view('default/contents/histories/coach', $vars);
        $this->template->publish();
    }
    
    public function session($coach_id){
        $this->template->title = 'Coach Session';
        $date_from = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-100 year"));
        $histories = $this->appointment_model->get_session_histories_for_coach($coach_id, $date_from, date('Y-m-d'), 1);
        
        $vars = array(
            'form_action' => 'search',
            'histories' => $histories,
            'coach_id' => $coach_id,
            'user' => 'admin'
        );
        
        $this->template->content->view('default/contents/histories/coach_session', $vars);
        $this->template->publish();
    }
    
    public function search($coach_id){
        $this->template->title = 'Coach Session';
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('admin/histories/coach');
        }
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        
        if(!$date_from || !$date_to){
            $this->messages->add('Invalid time period', 'danger');
            redirect('admin/histories/coach');
        }
        $is_today_include = 0;
        if($date_to == date('Y-m-d')){
            $is_today_include = 1;
        }
        
        $histories = $this->appointment_model->get_session_histories_for_coach($coach_id, $date_from, $date_to, $is_today_include);
        $vars = array(
            'form_action' => 'search',
            'histories' => $histories
        );
        $this->template->content->view('default/contents/histories/coach_session', $vars);
        $this->template->publish();
    }
}
