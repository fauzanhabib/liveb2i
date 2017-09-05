<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class set_session extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
    }

    // Index
    public function index() {
    }
    
    public function set($index = '', $value = '') {
        // setting session with index and value
        $this->session->set_userdata($index, $value);
    }
    
    public function set_duration_time() {
        // setting session for beginning the duration and time of the beginning
        //$now = date();
        $time = date('H:i:s');
        $duration_begin = strtolower(date('M')).date(',d,Y,').$time;
        $this->session->set_userdata('duration_begin', $duration_begin);
        $this->session->set_userdata('current_server_time', $duration_begin);
        $this->session->set_userdata('duration_begin_time', $time);
        $this->session->set_userdata('timer', 'begin');
        
        echo($duration_begin);
    }
    
    public function end_duration(){
        // setting session for beginning the duration and time of the beginning
        $time = date('H:i:s');
        $duration_end = strtolower(date('M')).date(',d,Y,').$time;
        $this->session->set_userdata('current_server_time', $duration_end);
        $this->session->set_userdata('duration_end_time', $time);
        $this->session->set_userdata('timer', 'finished');
        
        echo($duration_end);
    }
    
    public function current_server_time(){
        // add seconds to date
        //$current_server_time = strtolower(date('M')).date(',d,Y,H:i:s', time() + 1);
        $current_server_time = strtolower(date('M')).date(',d,Y,H:i:s');
        $this->session->set_userdata('current_server_time', $current_server_time);
        echo($current_server_time);
    }
    
    public function destroy(){
        $this->session->unset_userdata('timer');
        $this->session->unset_userdata('duration_begin');
        $this->session->unset_userdata('duration_end');
        $this->session->unset_userdata('current_server_time');
        $this->session->unset_userdata('duration_begin_time');
        $this->session->unset_userdata('duration_end_time');
        //echo(@$this->session->userdata('sample'));
        //echo($this->session->userdata('timer'));
        //echo($this->session->userdata('duration_begin'));
    }
}
