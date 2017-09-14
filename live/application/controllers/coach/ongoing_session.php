<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class ongoing_session extends MY_Site_Controller {

    // Constructor
    public function __construct() { 
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('class_member_model');
        $this->load->model('identity_model');
        
        $this->load->library('queue');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CCH') {
            $this->messages->add('ERROR');
            redirect('home');
        } 
    } 
    
    // Index
    public function index() {
        $this->template->title = "Ongoing Session";
        $data = $this->appointment_model->get_appointment_for_ongoing_session('coach_id');
        $data_class = $this->class_member_model->get_appointment_for_ongoing_session();
        
        if($data){

            $data_schedule = $this->convertBookSchedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($data[0]->date), $data[0]->start_time, $data[0]->end_time);
            $data[0]->date = date('Y-m-d', $data_schedule['date']);
            $data[0]->start_time = $data_schedule['start_time'];
            $data[0]->end_time = $data_schedule['end_time'];
            if($data[0]->webex_id){    
                if(!$this->session->userdata('stream_id') || @$this->session->userdata('stream_id') != $data[0]->id){
                    $this->get_stream_url('webex', $data[0]->webex_session_id, $data[0]->webex_meeting_number, $data[0]->subdomain_webex, $data[0]->webex_id, $data[0]->host_password, 30 * 60);
                    $this->session->set_userdata('stream_id', $data[0]->id);
                }
            }
            
            $this->session->set_userdata('meeting_identifier', $data[0]->id);
            $this->session->set_userdata('host_id', $data[0]->host_id);
        }
        $skype_id_list = '';
        if($data_class){
            $data_class_schedule = $this->convertBookSchedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($data_class[0]->date), $data_class[0]->start_time, $data_class[0]->end_time);
            $data_class[0]->date = date('Y-m-d', $data_class_schedule['date']);
            $data_class[0]->start_time = $data_class_schedule['start_time'];
            $data_class[0]->end_time = $data_class_schedule['end_time'];
            
            $this->session->set_userdata('meeting_identifier', 'c'.$data_class[0]->id);
            $this->session->set_userdata('host_id', $data_class[0]->host_id);
            $skype_id='';
            foreach($data_class as $d){
                $skype_id .= $d->skype_id . ';';
            }
            $skype_id_list = substr($skype_id, 0, strlen($skype_id)-1);
            if($data_class[0]->webex_id){   
                if(!$this->session->userdata('stream_id') || @$this->session->userdata('stream_id') != $data_class[0]->id){
                    $this->get_stream_url('webex_class', $data_class[0]->webex_session_id, $data_class[0]->webex_meeting_number, $data_class[0]->subdomain_webex, $data_class[0]->webex_id, $data_class[0]->host_password, 30 * 60);
                    $this->session->set_userdata('stream_id', $data_class[0]->id);
                }
            }
        }
        
        $vars = array(  
            'title' => 'Ongoing Session', 
            'data' => $data,
            'data_class' => $data_class,
            'site_url' => 'apidemoeu',
            'student_name' => $this->identity_model->get_identity('profile')->select('fullname, skype_id')->where('user_id', @$data[0]->student_id)->get(),
            'skype_id_list' => $skype_id_list
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();
        
        $this->template->content->view('default/contents/coach/ongoing_session/index', $vars);
        $this->template->publish();
    }

    public function get_stream_url($table='', $id='', $session_key='',$subdomain_webex='', $webex_id='', $password='', $time=''){
        $tube = 'com.live.download';
        $data = Array(
            'table' => $table,
            'id' => $id,
            'subdomain_webex' => $subdomain_webex,
            'webex_id'=> $webex_id,
            'password'=> $password,
            'session_key' => $session_key
        );
        $this->queue->later($time, $tube, $data, 'download.webex');
        $this->queue->later(($time*2), $tube, $data, 'download.webex');
    }
    private function convertBookSchedule($minutes = '', $date = '', $start_time = '', $end_time = '') {
        // variable to get schedule out of date

        if ($minutes > 0) {
            if (strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)))) > strtotime($end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00') {
                $date = $date;
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))));
            } else if (strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)))) < strtotime($start_time)) {
                $date = strtotime('+ 1days' . date('Y-m-d', $date));
                //$tomorrow = date('m-d-Y',strtotime($date . "+1 days"));
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)));

//                $date2 = strtotime('+ 1days'.date('Y-m-d',$date));
//                //$tomorrow = date('m-d-Y',strtotime($date . "+1 days"));
//                $start_time2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
//                $end_time2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)));
            }
        } else if ($minutes < 0) {
            if (strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)))) < strtotime($start_time)) {
                $date = $date;
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)));
            } else if (strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)))) > strtotime($end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00') {
                $date = strtotime('- 1days' . date('Y-m-d', $date));
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))));

//                $date2 = strtotime('- 1days'.date('Y-m-d',$date));
//                $start_time2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
//                $end_time2 = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))));
            }
        }

        return array(
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
//            'date2' => @$date2,
//            'start_time2' => @$start_time2,
//            'end_time2' => @$end_time2,
        );
    }
}
