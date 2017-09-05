<?php
/**
 * Class Webex Timer
 * Author Ponel Panjaitan
 */
if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
class webex extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_history_model');
        $this->load->model('appointment_model');
        $this->load->model('class_meeting_day_model');
    }
    
    // Index
    public function index() {}
    
    public function get_time_remain($meeting_identifier=''){
        if(substr($meeting_identifier, 0, 1) == 'c'){
            $appointment = $this->class_meeting_day_model->select('date, end_time')->where('id', substr($meeting_identifier, 1))->get();
            $timer = strtotime($appointment->date .' '.$appointment->end_time) - time('Y-m-d H:i:s');
        }else{
            $appointment = $this->appointment_model->select('date, end_time')->where('id', $meeting_identifier)->get();
            $timer = strtotime($appointment->date .' '.$appointment->end_time) - time('Y-m-d H:i:s');
        }
        echo $timer * 1000;
    }
    
    public function delete_meeting($meeting_identifier=''){
        if(substr($meeting_identifier, 0, 1) == 'c'){
        }else{
        }
    }
}
