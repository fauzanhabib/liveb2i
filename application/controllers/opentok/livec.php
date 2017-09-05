<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'vendor/autoload.php';

use OpenTok\OpenTok;
use OpenTok\ArchiveMode;
use OpenTok\MediaMode;
use OpenTok\Session;
use OpenTok\Role;


class Livec extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // load models for student info
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');
        $this->load->model('user_model');
        $this->load->library('Auth_manager');
        /* if ($this->auth_manager->role() != 'STD' || $this->auth_manager->role() != 'CCH') {
            redirect('home');
        } */
    }
    
    // Index
    public function index()
    {
        
        $this->template->title = "Live Session";
        
        $id = $this->auth_manager->userid();
        $utz = $this->db->select('user_timezone')
                ->from('user_profiles')
                ->where('user_id', $id)
                ->get()->result();
        $idutz = $utz[0]->user_timezone;
        $tz = $this->db->select('*')
                ->from('timezones')
                ->where('id', $idutz)
                ->get()->result();
        
        $today = date('Y-m-d');
        $timezone = $tz[0]->code;
        // $today = "2016-05-11";
        //$sessionId = $session->getSessionId();
        //$archive = $opentok->startArchive($sessionId);
        //session MUST BE -> RELAYED
        //token MUST BE -> PUBLISHER
        $sess = $this->db->select('*')
                ->from('appointments')
                ->where('student_id', $id)
                ->where('date', $today)
                ->get()->result();
                
        /* $hour = getdate();
        echo "<pre>";
        print_r($hour);
        exit(); */
        
        $data = $this->appointment_model->get_appointment_for_upcoming_session('student_id','','',  $this->auth_manager->userid());
        $data_class = $this->class_member_model->get_appointment_for_upcoming_session('', '', $this->auth_manager->userid());
        
        if ($data) {
            foreach ($data as $d) {
                $data_schedule = $this->convertBookSchedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
                
            }
        }
        
        if ($data_class) {
            foreach ($data_class as $d) {
                echo date('H:i:s');
                exit();
                $data_schedule = $this->convertBookSchedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);                                echo "<pre>";
                print_r($data_schedule);
                echo date('H:i:s');
                exit();

                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
            }
        }
        
        @$sessionId  = $sess[0]->session;
        @$token      = $sess[0]->token;
        $livesession = array(
            'sessionId' => @$sessionId,
            'token' => @$token
        );
        /* echo "<pre>";
        print_r($livesession);
        exit(); */

        $vars = array(
            'role' => 'Coach',
            'data' => $data,
            'data_class' => $data_class,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'sessionId' => $sessionId,
            'token' => $token
        );
        
        if($sessionId == NULL && $token == NULL){
            $this->template->content->view('contents/opentok/nosession', $vars);
            $this->template->publish();
        }
        else{
            
            $this->template->content->view('contents/opentok/session', $livesession);
            $this->template->publish();
        }
    }
    
    
    private function convertBookSchedule($minutes = '', $date = '', $start_time = '', $end_time = ''){
        // variable to get schedule out of date
        if($minutes > 0){
            if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)))) > strtotime($end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00'){
                $date = $date;
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))));
            }
            else if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)))) < strtotime($start_time)){
                $date = strtotime('+ 1days'.date('Y-m-d',$date));
                //$tomorrow = date('m-d-Y',strtotime($date . "+1 days"));
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)));
                
//                $date2 = strtotime('+ 1days'.date('Y-m-d',$date));
//                //$tomorrow = date('m-d-Y',strtotime($date . "+1 days"));
//                $start_time2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
//                $end_time2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)));
            }
        }
        else if($minutes < 0){
            if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)))) < strtotime($start_time)){
                $date = $date;
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)));
            }
            else if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)))) > strtotime($end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00'){
                $date = strtotime('- 1days'.date('Y-m-d',$date));
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
