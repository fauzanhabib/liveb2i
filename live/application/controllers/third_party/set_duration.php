<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class set_duration extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_history_model');
        $this->load->model('appointment_model');
        
        //load libraries
        $this->load->library('queue');
    }

    // Index
    public function index() {
        
    }

    public function create_duration($appointment_id) {
        $data = array(
            'appointment_id' => $appointment_id,
            'start_time' => $this->session->userdata('duration_begin_time'),
        );

        $appointment_history_id = $this->appointment_history_model->insert($data);

        if ($appointment_history_id) {
            $tube = 'com.live.database';
            
            $appointment = $this->appointment_model->select('end_time')->where('id', $appointment_id)->get();
            $time = strtotime($appointment->end_time) - strtotime(date('H:i:s', time()));
            $appointment_history = Array(
                'end_time' => $appointment->end_time,
                'dupd' => time()
            );
            
            $data = array(
                'table' => 'appointment_histories',
                'appointment_id' => $appointment_id,
                'content' => $appointment_history
            );
            // messaging update data notification
            $this->queue->later(time, $tube, $data, 'database.update_when_end_time_not_divined');
        }
        $this->session->set_userdata('appointment_history_id', $appointment_history_id);
    }

    public function update_duration() {
        $data = array(
            'end_time' => $this->session->userdata('duration_end_time'),
        );

        $appointment_history_id = $this->appointment_history_model->update($this->session->userdata('appointment_history_id'), $data);
    }

}
