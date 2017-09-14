<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class set_duration extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_history_model');
    }

    // Index
    public function index() {
    }
    
    public function create_duration($appointment_id){
        $data = array(
            'appointment_id' => $appointment_id,
            'start_time' => $this->session->userdata('duration_begin_time'),
        );
        
        $appointment_history_id = $this->appointment_history_model->insert($data);
        $this->session->set_userdata('appointment_history_id', $appointment_history_id);
    }
    
    public function update_duration(){
        $data = array(
            'end_time' => $this->session->userdata('duration_end_time'),
        );
        
        $appointment_history_id = $this->appointment_history_model->update($this->session->userdata('appointment_history_id'), $data);
    }


}
