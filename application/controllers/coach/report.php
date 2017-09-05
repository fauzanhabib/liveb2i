<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class report extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('coach_report_model');
        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CCH') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        redirect('coach/histories');
    }

    public function create($appointment_id = '', $note = '') {
        //print_r($note); exit;
        $data = array(
            'appointment_id' => $appointment_id,
            'note' => $note,
        );
        // Inserting and checking
        if (!$this->coach_report_model->insert($data)) {
            $this->messages->add('Failed', 'warning');
            redirect('coach/histories');
        }
        else{
            $this->messages->add('Report Created', 'success');
            redirect('coach/histories');
        }
    }

}
