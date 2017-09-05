<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'vendor/autoload.php';

use OpenTok\OpenTok;
use OpenTok\ArchiveMode;
use OpenTok\MediaMode;
use OpenTok\Session;
use OpenTok\Role;


class Live extends MY_Site_Controller {
    // Constructor
    public function __construct() {
        parent::__construct();
        // load models for student info
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');
        $this->load->model('user_model');
        $this->load->library('Auth_manager');
        $this->load->library('call1');
        $this->load->library('call2');
        $this->load->model('coaching_script_model');
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
        
        $minutes = $tz[0]->minutes;
        //User Hour
        date_default_timezone_set('UTC');
        $date     = date('H:i:s');
        $default  = strtotime($date);
        $usertime = $default+(60*$minutes);
        // $hour  = date("H:i:s", $usertime);     
        $hour  = date("H:i:s");     
        // $hour = "00:00:59";        
        // print_r($hour);
        // exit();
        $today = date('Y-m-d');
        // $timezone = $tz[0]->code;
        // $today    = "2016-10-17";
        //$sessionId = $session->getSessionId();
        //$archive = $opentok->startArchive($sessionId);
        //session MUST BE -> RELAYED
        //token MUST BE -> PUBLISHER
         
        $data       = $this->appointment_model->get_appointment_for_upcoming_session('student_id','','',  $this->auth_manager->userid());
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
                $data_schedule = $this->convertBookSchedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);                             
                
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
            }
        }
        if($this->auth_manager->role() == 'STD'){
            $sess = $this->db->select('*')
                    ->from('appointments')
                    ->where('student_id', $id)
                    ->where('date', $today)
                    ->where('start_time <=', $hour)
                    ->where('end_time >=', $hour)
                    ->order_by('id','desc')
                    ->get()->result();
        } else if($this->auth_manager->role() == 'CCH'){
            $sess = $this->db->select('*')
                    ->from('appointments')
                    ->where('coach_id', $id)
                    ->where('date', $today)
                    ->where('start_time <=', $hour)
                    ->where('end_time >=', $hour)
                    ->order_by('id','desc')
                    ->get()->result();
        }
         
        //Appointment Hour
        // $appoint_id = @$sess[0]->id;

        

        @$appoint  = $sess[0]->start_time;
        $default1  = strtotime($appoint);
        $usertime1 = $default1+(60*$minutes);
        
        //@$starthour_conv = date("H:i:s", $usertime1);
        //@$endhour_conv   = date("H:i:s", $usertime1);
       
        $opentok    = new OpenTok($this->config->item('opentok_key'), $this->config->item('opentok_secret'));
        @$sessionId = $sess[0]->session;
        @$token     = $sess[0]->token;
        // echo "<pre>";
        // print_r($appoint);
        // exit();
        $tipe_ = '';
        if($this->auth_manager->role() == "STD"){
            $tipe_ = 'student_id';
        } else if($this->auth_manager->role() == "CCH"){
            $tipe_ = 'coach_id';
        }
        
        if($this->auth_manager->role() == "STD"){
            $todaysession = $this->db->select('*')
                      ->from('appointments')
                      ->join('user_profiles', 'user_profiles.user_id = appointments.coach_id')
                      ->where($tipe_, $id)
                      ->where('date', $today)
                      ->order_by('start_time','asc')
                      ->get()->result();

            $allsess  = $this->db->select('*')
                      ->from('appointments')
                      ->join('user_profiles', 'user_profiles.user_id = appointments.coach_id')
                      ->where($tipe_, $id)
                      ->where('date >=', $today)
                      ->order_by('date','asc')
                      ->get()->result();
        } else if($this->auth_manager->role() == "CCH"){
            $todaysession = $this->db->select('*')
                      ->from('appointments')
                      ->join('user_profiles', 'user_profiles.user_id = appointments.student_id')
                      ->where($tipe_, $id)
                      ->where('date', $today)
                      ->order_by('start_time','asc')
                      ->get()->result();

            $allsess = $this->db->select('*')
                      ->from('appointments')
                      ->join('user_profiles', 'user_profiles.user_id = appointments.student_id')
                      ->where($tipe_, $id)
                      ->where('date >=', $today)
                      ->order_by('date','asc')
                      ->get()->result();
        }
        
        @$sesstoday      = $sess[0]->session;
        @$starthour_conv = $sess[0]->start_time;
        @$endhour_conv   = $sess[0]->end_time;

        // echo "<pre>";
        // print_r($sesstoday);
        // exit();

        $datetime1 = new DateTime($hour);
        $datetime2 = new DateTime($starthour_conv);
        $interval  = $datetime1->diff($datetime2);
        $different = $interval->format('%i minutes %s seconds.');
        $diff_min  = $interval->format('%i');
        $diff_sec  = $interval->format('%s');
        $diff_min_sec  = $diff_min * 60;
        $different_val = $diff_min_sec + $diff_sec;

        $datetime1 = new DateTime($hour);
        $datetime2 = new DateTime($endhour_conv);
        $diff_end  = $datetime1->diff($datetime2);
        $different_min = $diff_end->format('%i');
        $different_sec = $diff_end->format('%s');
        $min_to_sec    = $different_min * 60;
        $total_sec     = $different_sec + $min_to_sec;

        if($different_val <= 300 && $hour >= $starthour_conv){
            $sentence  = "Your session is already running for ";
            $notes_c   = "";
            $notes_s   = "You can participate until the session ends.";
            
        }
        else if($different_val >= 300 && $hour > $starthour_conv){
            $sentence  = "Your session is already running for ";
            $notes_s   = "You're already late to join the session, But you still can get your session.";
            $notes_c   = "You're already late more than 5 minutes. Student will get their tokens back and has the opportunity
                          to reschedule.";
            $annualtoken = 1;
        }
        else{
            $sentence   = "";
        }
        // echo "<pre>";
        // print_r($different_val);
        // exit();
        
        foreach ($todaysession as $t) {
                $data_schedule = $this->convertBookSchedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($t->date), $t->start_time, $t->end_time);
               
                $t->start_time = $data_schedule['start_time'];
                $t->end_time = $data_schedule['end_time'];
            }
        foreach ($allsess as $t) {
                $data_schedule = $this->convertBookSchedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($t->date), $t->start_time, $t->end_time);
               
                $t->start_time = $data_schedule['start_time'];
                $t->end_time = $data_schedule['end_time'];
            }

        @$a = $sess[0]->id;
        if($this->auth_manager->role() == 'STD'){
            $user = $this->db->select('*')
                  ->from('appointments')
                  ->join('user_profiles', 'user_profiles.user_id = appointments.coach_id')
                  ->where('appointments.id', $a)
                  ->get()->result();

            $user2 = $this->db->select('*')
                  ->from('appointments')
                  ->join('user_profiles', 'user_profiles.user_id = appointments.student_id')
                  // ->join('coaching_scripts', 'coaching_scripts.user_id = appointments.student_id')
                  ->where('appointments.id', $a)
                  ->get()->result();

        } else if($this->auth_manager->role() == 'CCH'){
            $user = $this->db->select('*')
                  ->from('appointments')
                  ->join('user_profiles', 'user_profiles.user_id = appointments.student_id')
                  ->join('coaching_scripts', 'coaching_scripts.user_id = appointments.student_id')
                  ->where('appointments.id', $a)
                  ->get()->result();

            $user2 = $this->db->select('*')
                  ->from('appointments')
                  ->join('user_profiles', 'user_profiles.user_id = appointments.student_id')
                  // ->join('coaching_scripts', 'coaching_scripts.user_id = appointments.student_id')
                  ->where('appointments.id', $a)
                  ->get()->result();
        }
      
        
        @$user_extract = $user[0];
        @$user_extract2 = $user2[0];
        @$std_id_for_cert = $user_extract2->student_id;
        
        // echo "<pre>";
        // print_r($cchscript);
        // exit();

        //  ------------------------------------------------------------------------------------------
        //  CALL1 AND CALL2 START --------------------------------------------------------------------
        //  ------------------------------------------------------------------------------------------
        
        // echo "<pre>";
        // print_r($allmodule);
        // exit();
        //  ------------------------------------------------------------------------------------------
        //  CALL1 AND CALL2 END ----------------------------------------------------------------------
        //  ------------------------------------------------------------------------------------------

        $varsnull = array(
            'role' => 'Coach',
            'data' => $allsess,
            'data_class' => $data_class,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname')
        );
         
        $varstoday = array(
            'role' => 'Coach',
            'sentence' => $sentence,
            'different' => $different,
            'data' => $todaysession,
            'data_class' => $data_class,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname')
         );            
        // echo "<pre>";
        // print_r($todaysession);
        // exit();
        if(@$sesstoday == NULL){
            $userrole   = $this->auth_manager->role();
            if($userrole == "STD"){
                $this->template->content->view('contents/opentok/student/nosession', $varsnull);
                $this->template->publish();
            }
            else if($userrole == "CCH"){
                $this->template->content->view('contents/opentok/coach/nosession', $varsnull);
                $this->template->publish();
            }
        }
        else{
            $userrole   = $this->auth_manager->role();
            if($userrole == "STD"){
                if(!$sess){
                
                    $this->template->content->view('contents/opentok/student/today', $varstoday);
                    $this->template->publish();
                }
                else{
                    
                    if($token == NULL){
                    $gentoken   = $opentok->generateToken($sessionId);
                    $checktoken = array(
                       'token' => $gentoken
                    );

                    $this->db->where($tipe_, $id);
                    $this->db->where('date', $today);
                    $this->db->where('session', $sessionId);
                    $this->db->update('appointments', $checktoken);
                    
                    $sessioning = $this->db->select('*')
                                ->from('appointments')
                                ->where($tipe_, $id)
                                ->where('session', $sessionId)
                                ->where('date', $today)
                                ->get()->result();
                                
                    @$sessionIdn  = $sessioning[0]->session;
                    @$tokenn      = $sessioning[0]->token;
                    $apiKey       = $this->config->item('opentok_key');
                    
                    $livesession = array(
                    'sessionId'  => @$sessionIdn,
                    'token'      => @$tokenn,
                    'apiKey'     => @$apiKey,
                    'sentence'   => $sentence,
                    'different'  => $different,
                    'total_sec'  => $total_sec,
                    'different_val'  => $different_val,
                    'notes_s'        => $notes_s,
                    'user_extract'   => $user_extract,
                    'appointment_id' => $sessioning[0]->id,
                    'allmodule'  => @$allmodule,
                    'cert_plan'  => @$cert_plan,
                    'student_vrm'    => @$student_vrm,
                    'student_vrm_json' => @$student_vrm_json
                    );
                    }
                    else{
                        $sessioninge = $this->db->select('*')
                                    ->from('appointments')
                                    ->where($tipe_, $id)
                                    ->where('session', $sessionId)
                                    ->where('date', $today)
                                    ->get()->result();
                                    
                        @$sessionIde  = $sessioninge[0]->session;
                        @$tokene      = $sessioninge[0]->token;
                        $apiKey       = $this->config->item('opentok_key');
                        
                        $livesession = array(
                        'sessionId'  => @$sessionIde,
                        'token'      => @$tokene,
                        'apiKey'     => @$apiKey,
                        'sentence'   => $sentence,
                        'different'  => $different,
                        'notes_s'    => $notes_s,
                        'total_sec'  => $total_sec,
                        'different_val'  => $different_val,
                        'user_extract'   => $user_extract,
                        'appointment_id' => $sess[0]->id,
                        'allmodule'  => @$allmodule,
                        'cert_plan'  => @$cert_plan,
                        'student_vrm'    => @$student_vrm,
                        'student_vrm_json' => @$student_vrm_json
                        );
                    }
                           
                    $std_hour_check = $this->db->select('std_attend')
                                    ->from('appointments')
                                    ->where($tipe_, $id)
                                    ->where('session', $sessionId)
                                    ->where('date', $today)
                                    ->get()->result();

                    $sa_exist = $std_hour_check[0]->std_attend;

                    if($sa_exist == "00:00:00"){

                      $id_appoint = $livesession['appointment_id'];
                      $data2s = array(
                         'std_attend' => $hour
                      );

                      $this->db->where('id', $id_appoint);
                      $this->db->update('appointments', $data2s);
                    }

                    $this->template->content->view('contents/opentok/student/session', $livesession);
                    $this->template->publish();
                }
            }
            else{
                if(!$sess){
                
                    $this->template->content->view('contents/opentok/coach/today', $varstoday);
                    //$this->template->content->view('contents/opentok/student/session', $livesession);
                    $this->template->publish();
                }
                else{
                    
                    if($token == NULL){
                    $gentoken   = $opentok->generateToken($sessionId);
                    $checktoken = array(
                       'token' => $gentoken
                    );

                    $this->db->where($tipe_, $id);
                    $this->db->where('date', $today);
                    $this->db->where('session', $sessionId);
                    $this->db->update('appointments', $checktoken);
                    
                    $sessioning = $this->db->select('*')
                                ->from('appointments')
                                ->where($tipe_, $id)
                                ->where('date', $today)
                                ->get()->result();
                                
                    @$sessionIdn  = $sessioning[0]->session;
                    @$tokenn      = $sessioning[0]->token;
                    $apiKey       = $this->config->item('opentok_key');
                    // $script = $this->coaching_script_model->get_student_script();
                    @$std_id   = $user_extract->student_id;
                    $script = $this->db->distinct()
                                ->select('s.unit')
                                ->from('coaching_scripts cs')
                                ->join('script s', 's.script_id = cs.script_id')
                                ->where('cs.user_id', @$std_id)
                                ->where('s.certificate_plan', @$std_cert)
                                ->get()->result();
                    $livesession = array(
                    'sessionId'  => @$sessionIdn,
                    'token'      => @$tokenn,
                    'apiKey'     => @$apiKey,
                    'sentence'   => $sentence,
                    'different'  => $different,
                    'notes_c'    => $notes_c,
                    'total_sec'  => $total_sec,
                    'script'     => @$script,
                    'allmodule'  => @$allmodule,
                    'cert_plan'  => @$cert_plan,
                    'content'    => @$content,
                    'student_vrm'    => @$student_vrm,
                    'annualtoken'    => @$annualtoken,
                    'different_val'  => $different_val,
                    'student_vrm_json'  => @$student_vrm_json,
                    'user_extract'   => $user_extract,
                    'appointment_id' => $sessioning[0]->id
                    );
                    }
                    else{
                        $sessioninge = $this->db->select('*')
                                    ->from('appointments')
                                    ->where($tipe_, $id)
                                    ->where('session', $sessionId)
                                    ->where('date', $today)
                                    ->get()->result();
                                    
                        @$sessionIde  = $sessioninge[0]->session;
                        @$tokene      = $sessioninge[0]->token;
                        $apiKey       = $this->config->item('opentok_key');
                        @$std_id   = $user_extract->student_id;
                        @$std_cert = $user_extract->cert_plan;
                        // echo "<pre>";
                        // print_r($std_cert);
                        // exit();

                        $script = $this->db->distinct()
                                ->select('s.unit')
                                ->from('coaching_scripts cs')
                                ->join('script s', 's.script_id = cs.script_id')
                                ->where('cs.user_id', $std_id)
                                ->where('s.certificate_plan', $std_cert)
                                ->get()->result();

                        $livesession = array(
                        'sessionId'  => @$sessionIde,
                        'token'      => @$tokene,
                        'apiKey'     => @$apiKey,
                        'sentence'   => $sentence,
                        'different'  => $different,
                        'notes_c'    => $notes_c,
                        'total_sec'  => $total_sec,
                        'script'     => $script,
                        'allmodule'  => @$allmodule,
                        'cert_plan'  => @$cert_plan,
                        'content'    => @$content,
                        'student_vrm'    => @$student_vrm,
                        'annualtoken'    => @$annualtoken,
                        'student_vrm_json' => @$student_vrm_json,
                        'different_val'  => $different_val,
                        'user_extract'   => $user_extract,
                        'user_extract2'  => $user_extract2,
                        'appointment_id' => $sess[0]->id
                        );
                    }
                    
                    $cch_hour_check = $this->db->select('cch_attend')
                                    ->from('appointments')
                                    ->where($tipe_, $id)
                                    ->where('session', $sessionId)
                                    ->where('date', $today)
                                    ->get()->result();

                    $ca_exist = $cch_hour_check[0]->cch_attend;

                    if($ca_exist == "00:00:00"){

                      $id_appoint = $livesession['appointment_id'];
                      $data2c = array(
                         'cch_attend' => $hour
                      );

                      $this->db->where('id', $id_appoint);
                      $this->db->update('appointments', $data2c);
                    }

                    // $archive = $opentok->getArchive('1');
                    // echo "<pre>";
                    // print_r($archive);
                    // exit();

                    $this->template->content->view('contents/opentok/coach/session', $livesession);
                    $this->template->publish();
                }
            }
        }
        /* print_r($a);
        exit(); */
    }
    
    public function kirim_chat()
    {
        $user=$this->input->post("user");
        $pesan=$this->input->post("pesan");
        $pesan2 = mysql_real_escape_string($pesan);
        $appointment_id = $this->input->post("appointment_id");
        
        $id  = $this->auth_manager->userid();
        $utz = $this->db->select('user_timezone')
                ->from('user_profiles')
                ->where('user_id', $id)
                ->get()->result();
        $idutz = $utz[0]->user_timezone;
        $tz    = $this->db->select('*')
                ->from('timezones')
                ->where('id', $idutz)
                ->get()->result();

                // echo "<pre>";
                // print_r($tz);
                // exit();
        
        $minutes = $tz[0]->minutes;
        //User Hour
        date_default_timezone_set('UTC');
        $date     = date('H:i:s');
        $default  = strtotime($date);
        $usertime = $default+(60*$minutes);
        $hourss = date("H:i:s", $usertime);     

        mysql_query("insert into chat (coach_name,chat_messages, appointment_id, time) 
          VALUES ('$user','$pesan2','$appointment_id','$hourss')");
        redirect("opentok/live/ambil_pesan");
    }
     
    public function ambil_pesan()
    {
        $id  = $this->auth_manager->userid();
        $utz = $this->db->select('user_timezone')
                ->from('user_profiles')
                ->where('user_id', $id)
                ->get()->result();
        $idutz = $utz[0]->user_timezone;
        $tz    = $this->db->select('*')
                ->from('timezones')
                ->where('id', $idutz)
                ->get()->result();

                // echo "<pre>";
                // print_r($tz);
                // exit();
        
        $minutes = $tz[0]->minutes;
        //User Hour
        date_default_timezone_set('UTC');
        $date     = date('H:i:s');
        $default  = strtotime($date);
        $usertime = $default+(60*$minutes);
        $hour = date("H:i:s");     
        // $hour = "00:00:59";        
        
        /* print_r($hour);
        exit(); */
        $today = date('Y-m-d');
        $timezone = $tz[0]->code;
        // $today = "2016-10-17";
        
        if($this->auth_manager->role() == 'STD'){
            $sess = $this->db->select('*')
                    ->from('appointments')
                    ->where('student_id', $id)
                    ->where('date', $today)
                    ->where('start_time <=', $hour)
                    ->where('end_time >=', $hour)
                    ->get()->result();
        } else if($this->auth_manager->role() == 'CCH'){
            $sess = $this->db->select('*')
                    ->from('appointments')
                    ->where('coach_id', $id)
                    ->where('date', $today)
                    ->where('start_time <=', $hour)
                    ->where('end_time >=', $hour)
                    ->get()->result();
        }
        @$a = $sess[0]->id;
        
        $tampil=mysql_query("select id, coach_name, chat_messages, time from chat where appointment_id='$a' order by id desc");
        $sendername = $tampil['coach_name'];
        // echo json_encode($sendername);
        while($r=mysql_fetch_array($tampil)){
        $r['coach_name'];
        echo "<li><b>$r[coach_name]</b> : $r[chat_messages] <div class='font-12' style='float:right;''>$r[time]</div>";
        }
    }

    public function save_cchnote()
    {
        $cch_note       = $this->input->post("cch_note");
        $appoint_id_cch = $this->input->post("appointment_id");
  
        $ins_note = array(
            'cch_notes' => $cch_note
        );
        
        $this->db->where('id', $appoint_id_cch);
        $this->db->update('appointments', $ins_note);
        

        // redirect("opentok/live/ambil_pesan");
    }
    
    function coaching_script(){
        if(!empty($_POST['check_list'])) {
            $check_list  = $_POST['check_list'];
            // $submit      = $_POST['submit'];
            //$uncheck     = $_POST['uncheck'];
            $std_id      = $_POST['std_id'];
            $status_script = $_POST['status_script'];
            // $status_script1 = $_POST['status_script1'];
            // print_r($status_script0);
            // echo "<pre>";
                    
            if($check_list != NULL){
                    // print_r($check_list);
                    // exit();
                    $this->db->trans_begin();

                        $this->db->where('user_id', $std_id);
                        $this->db->where_in('id', $check_list);
                        $this->db->update('coaching_scripts', array('status' => "1"));
                    $this->db->trans_commit();

                    $this->db->trans_begin();

                        $this->db->where('user_id', $std_id);
                        $this->db->where('status', '1');
                        $this->db->where_not_in('id', $check_list);
                        $this->db->update('coaching_scripts', array('status' => "0"));
                    $this->db->trans_commit();
                }
               
                $this->messages->add('Coaching Scripts Updated', 'success');

        }else {
            $this->messages->add('Please select objectives', 'error');
        }

            redirect('opentok/live');
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
