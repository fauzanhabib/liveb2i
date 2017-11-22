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
        // $utz = $this->db->select('user_timezone')
        //         ->from('user_profiles')
        //         ->where('user_id', $id)
        //         ->get()->result();
        // $idutz = $utz[0]->user_timezone;
        // $tz = $this->db->select('*')
        //         ->from('timezones')
        //         ->where('id', $idutz)
        //         ->get()->result();

        // $minutes = $tz[0]->minutes;
        $tz    = $this->db->select('*')
                ->from('user_timezones')
                ->where('user_id', $id)
                ->get()->result();


        $minutes = $tz[0]->minutes_val;
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


        $data       = $this->appointment_model->get_appointment_for_upcoming_session('student_id','','',  $this->auth_manager->userid());
        $data_class = $this->class_member_model->get_appointment_for_upcoming_session('', '', $this->auth_manager->userid());

        // if ($data) {
        //     foreach ($data as $d) {
        //         $data_schedule = $this->convertBookSchedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
        //         $d->date = date('Y-m-d', $data_schedule['date']);
        //         $d->start_time = $data_schedule['start_time'];
        //         $d->end_time = $data_schedule['end_time'];

        //     }
        // }

        // if ($data_class) {
        //     foreach ($data_class as $d) {
        //         $data_schedule = $this->convertBookSchedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);

        //         $d->date = date('Y-m-d', $data_schedule['date']);
        //         $d->start_time = $data_schedule['start_time'];
        //         $d->end_time = $data_schedule['end_time'];
        //     }
        // }

        $appoint_id = $this->input->post("appoint_id");
        if(!$appoint_id)
        {
            $appoint_id = $this->input->post("appoint_id2");
        }

        // print_r($appoint_id);exit();

        if($this->auth_manager->role() == 'STD'){
            $sess = $this->db->select('*')
                    ->from('appointments')
                    ->where('student_id', $id)
                    ->where('id', $appoint_id)
                    ->get()->result();

        } else if($this->auth_manager->role() == 'CCH'){
            $sess = $this->db->select('*')
                    ->from('appointments')
                    ->where('coach_id', $id)
                    ->where('id', $appoint_id)
                    ->get()->result();
            // echo "<pre>";
            // print_r($checkcch);exit();
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

        @$sesstoday      = $sess[0]->session;
        @$starthour_conv = $sess[0]->start_time;
        @$endhour_conv   = $sess[0]->end_time;

        // echo "<pre>";print_r($opentok);exit();

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
        $total_sec     = $different_sec + $min_to_sec - 300;

        if($different_val <= 300 && $hour >= $starthour_conv){
            $sentence  = "Your session started ";
            @$notes_c   = "";
            $notes_s   = "You may participate in the session until it ends.";

        }
        else if($different_val >= 300 && $hour > $starthour_conv){
            $sentence  = "Your session started ";
            $notes_s   = "You're already late to join the session, But you still can get your session.";
            @$notes_c   = "You're already late more than 5 minutes. Student will get their tokens back and has the opportunity
                          to reschedule.";
            $annualtoken = 1;
        }
        else{
            $sentence   = "";
        }

        if($this->auth_manager->role() == 'STD'){
            $tipe_ = 'student_id';
            $user = $this->db->select('*')
                  ->from('appointments')
                  ->join('user_profiles', 'user_profiles.user_id = appointments.coach_id')
                  ->where('appointments.id', $appoint_id)
                  ->get()->result();

            $user2 = $this->db->select('*')
                  ->from('appointments')
                  ->join('user_profiles', 'user_profiles.user_id = appointments.coach_id')
                  // ->join('coaching_scripts', 'coaching_scripts.user_id = appointments.student_id')
                  ->where('appointments.id', $appoint_id)
                  ->get()->result();

        } else if($this->auth_manager->role() == 'CCH'){
            $tipe_ = 'coach_id';
            $user = $this->db->select('*')
                  ->from('appointments')
                  ->join('user_profiles', 'user_profiles.user_id = appointments.student_id')
                  ->join('coaching_scripts', 'coaching_scripts.user_id = appointments.student_id')
                  ->where('appointments.id', $appoint_id)
                  ->get()->result();

            $user2 = $this->db->select('*')
                  ->from('appointments')
                  ->join('user_profiles', 'user_profiles.user_id = appointments.student_id')
                  // ->join('coaching_scripts', 'coaching_scripts.user_id = appointments.student_id')
                  ->where('appointments.id', $appoint_id)
                  ->get()->result();
        }


        @$user_extract = $user[0];
        @$user_extract2 = $user2[0];
        @$std_id_for_cert = $user_extract2->student_id;
        // echo "<pre>";print_r($user_extract);exit();

        $userrole   = $this->auth_manager->role();
        if($userrole == "STD"){
            if(@$appoint_id){
                if(@$token == NULL){
                    $gentoken   = $opentok->generateToken($sessionId);
                    $checktoken = array(
                       'token' => $gentoken
                    );

                    $this->db->where('id', $appoint_id);
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
                    'sessionId'  => @$sessionId,
                    'token'      => @$tokenn,
                    'apiKey'     => @$apiKey,
                    'sentence'   => $sentence,
                    'different'  => $different,
                    'total_sec'  => $total_sec,
                    'different_val'  => $different_val,
                    'notes_s'        => @$notes_s,
                    'user_extract'   => @$user_extract,
                    'user_extract2'   => @$user_extract2,
                    'appointment_id' => $appoint_id,
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
                    'sessionId'  => @$sessionId,
                    'token'      => @$token,
                    'apiKey'     => @$apiKey,
                    'sentence'   => $sentence,
                    'different'  => $different,
                    'notes_s'    => @$notes_s,
                    'total_sec'  => $total_sec,
                    'different_val'  => $different_val,
                    'user_extract'   => @$user_extract,
                    'user_extract2'   => @$user_extract2,
                    'appointment_id' => $appoint_id,
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

                if($sa_exist == NULL){

                  $id_appoint = $livesession['appointment_id'];
                  $data2s = array(
                     'std_attend' => $hour
                  );

                  $this->db->where('id', $id_appoint);
                  $this->db->update('appointments', $data2s);
                }

                // echo "<pre>";print_r($livesession);exit();
                $this->template->content->view('contents/opentok/student/session', $livesession);
                $this->template->publish();
            }else{
                $this->template->content->view('contents/opentok/student/nosession');
                $this->template->publish();
            }
        }

        else{
            if(@$appoint_id){
                if(@$token == NULL){
                    $gentoken   = $opentok->generateToken($sessionId);
                    $checktoken = array(
                       'token' => $gentoken
                    );

                    $this->db->where('id', $appoint_id);
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
                    // $script = $this->coaching_script_model->get_student_script();
                    @$std_id   = $user_extract->student_id;

                    $livesession = array(
                    'sessionId'  => @$sessionId,
                    'token'      => @$tokenn,
                    'apiKey'     => @$apiKey,
                    'sentence'   => $sentence,
                    'different'  => $different,
                    'notes_c'    => @$notes_c,
                    'total_sec'  => $total_sec,
                    'allmodule'  => @$allmodule,
                    'cert_plan'  => @$cert_plan,
                    'content'    => @$content,
                    'student_vrm'    => @$student_vrm,
                    'annualtoken'    => @$annualtoken,
                    'different_val'  => $different_val,
                    'student_vrm_json'  => @$student_vrm_json,
                    'user_extract'   => @$user_extract,
                    'user_extract2'   => @$user_extract2,
                    'appointment_id' => $appoint_id
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

                    // study dashboard
                    $this->load->library('Study_progress');
                    $tokenresult = $this->study_progress->GenerateToken();

                    $gsp = json_decode($this->study_progress->GetStudyProgress($tokenresult));
                    $gcp = json_decode($this->study_progress->GetCurrentProgress($tokenresult));
                    $gwp = json_decode($this->study_progress->GetWeeklyProgress($tokenresult));

                    $mt_status_to_colour = array(
                      "passed" => "bg-blue-gradient",
                      "open" => "bg-white-gradient",
                      "locked" => "",
                      "failed" => "bg-red-gradient"
                    );

                    $mt_color = [];
                    $k = 1;
                    $max_buletan_student = sizeof($gsp->data->study->mastery_tests);
                    
                    for($l=0;$l<$max_buletan_student;$l++){
                      $mt_color['mt'.$k] = @$mt_status_to_colour[$gsp->data->coach->sessions[$l]->status];
                      $k++;
                    }

                    $coach_status_color = array(
                      "passed" => "bg-green-gradient",
                      "open" => "bg-white-gradient",
                      "locked" => "",
                      "failed" => "bg-red-gradient"
                      );

                    $ct_color = [];
                    $j = 1;
                    $max_buletan = sizeof($gsp->data->coach->sessions);
                    
                    for($i=0;$i<$max_buletan;$i++){
                      $ct_color['cc'.$j] = @$coach_status_color[$gsp->data->coach->sessions[$i]->status];
                      $j++;
                    }
                    // ===============



                    $livesession = array(
                    'sessionId'  => @$sessionId,
                    'token'      => @$token,
                    'apiKey'     => @$apiKey,
                    'sentence'   => $sentence,
                    'different'  => $different,
                    'notes_c'    => @$notes_c,
                    'total_sec'  => $total_sec,
                    'allmodule'  => @$allmodule,
                    'cert_plan'  => @$cert_plan,
                    'content'    => @$content,
                    'student_vrm'    => @$student_vrm,
                    'annualtoken'    => @$annualtoken,
                    'student_vrm_json' => @$student_vrm_json,
                    'different_val'  => $different_val,
                    'user_extract'   => @$user_extract,
                    'user_extract2'  => @$user_extract2,
                    'appointment_id' => $appoint_id,
                    'gsp' => @$gsp,
                    'gcp' => @$gcp,
                    'gwp' => @$gwp,
                    'mt_color' => @$mt_color,
                    'ct_color' => @$ct_color,
                    'student_profile' => @$student_profile,
                    'max_buletan_student' => @$max_buletan_student,
                    'max_buletan' => @$max_buletan
                    );
                }

                $cch_hour_check = $this->db->select('cch_attend')
                                ->from('appointments')
                                ->where($tipe_, $id)
                                ->where('session', $sessionId)
                                ->where('date', $today)
                                ->get()->result();

                $ca_exist = $cch_hour_check[0]->cch_attend;

                if($ca_exist == NULL){

                  $id_appoint = $livesession['appointment_id'];
                  $data2c = array(
                     'cch_attend' => $hour
                  );

                  $this->db->where('id', $id_appoint);
                  $this->db->update('appointments', $data2c);
                }

                //check if student is b2c or b2i
                $std_idpull = $this->db->select('student_id')
                                ->from('appointments')
                                ->where('id', $livesession['appointment_id'])
                                ->get()->result();

                $std_check_id = $std_idpull[0]->student_id;

                $b2c_checkpull = $this->db->select('login_type')
                                ->from('users')
                                ->where('id', $std_check_id)
                                ->get()->result();

                $b2c_id = $b2c_checkpull[0]->login_type;
                // echo "<pre>";print_r($b2c_id);exit();
                //check if student is b2c or b2i
                if($b2c_id == 0){
                  $this->template->content->view('contents/opentok/coach/session', $livesession);
                  $this->template->publish();
                }else{
 
                  $this->template->content->view('contents/opentok/coach/session_b2c', $livesession);
                  $this->template->publish();
                }
            }else{

                $this->template->content->view('contents/opentok/coach/nosession');
                $this->template->publish();
            }
        }

    }

    public function session_leave(){
        $id = $this->auth_manager->userid();
        $id_appointment = $this->input->post("appointment_id");

        if($this->auth_manager->role() == 'STD'){

            $updstd = array(
               'status' => 0
            );

            $this->db->where('user_id', $id);
            $this->db->where('appointment_id', $id_appointment);
            $this->db->update('session_live', $updstd);


        } else if($this->auth_manager->role() == 'CCH'){

            $updcch = array(
               'status' => 0
            );

            $this->db->where('user_id', $id);
            $this->db->where('appointment_id', $id_appointment);
            $this->db->update('session_live', $updcch);

        }
    }

    public function session_stay(){
        $id = $this->auth_manager->userid();
        $id_appointment = $this->input->post("appointment_id");

        if($this->auth_manager->role() == 'STD'){

            $updstd = array(
               'status' => 1
            );

            $this->db->where('user_id', $id);
            $this->db->where('appointment_id', $id_appointment);
            $this->db->update('session_live', $updstd);


        } else if($this->auth_manager->role() == 'CCH'){

            $updcch = array(
               'status' => 1
            );

            $this->db->where('user_id', $id);
            $this->db->where('appointment_id', $id_appointment);
            $this->db->update('session_live', $updcch);

        }
    }

    public function kirim_chat()
    {
        $user=$this->input->post("user");
        $pesan=$this->input->post("pesan");
        // $pesan2 = mysqli_real_escape_string($pesan);
        $appointment_id = $this->input->post("appointment_id");

        $id  = $this->auth_manager->userid();

        $tz    = $this->db->select('*')
                ->from('user_timezones')
                ->where('user_id', $id)
                ->get()->result();


        $minutes = $tz[0]->minutes_val;
        //User Hour
        $hourss = date("H:i");

        $datachat = array(
           'coach_name' => $user,
           'chat_messages' => $pesan,
           'appointment_id' => $appointment_id,
           'time' => $hourss
        );

        $this->db->insert('chat', $datachat);

        // mysql_query("insert into chat (coach_name,chat_messages, appointment_id, time)
        //   VALUES ('$user','$pesan','$appointment_id','$hourss')");
        redirect("opentok/live/ambil_pesan");
    }

    public function ambil_pesan()
    {
        $id  = $this->auth_manager->userid();
        $tz    = $this->db->select('*')
                ->from('user_timezones')
                ->where('user_id', $id)
                ->get()->result();


        $minutes = $tz[0]->minutes_val;

                // echo "<pre>";
                // print_r($tz);
                // exit();

        // $minutes = $tz[0]->minutes;
        //User Hour
        date_default_timezone_set('UTC');
        $hour = date("H:i:s");
        // $hour = "00:00:59";

        /* print_r($hour);
        exit(); */
        $today = date('Y-m-d');

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

        $loadchat = $this->db->select('id, coach_name, chat_messages, time')
                    ->from('chat')
                    ->where('appointment_id', $a)
                    ->order_by('id', 'DESC')
                    ->get()->result();

        foreach(@$loadchat as $lc){
            $date     = $lc->time;
            $default  = strtotime($date);
            $usertime = $default+(60*$minutes);
            $tchat    = date("H:i", $usertime);
            echo "<li><b>$lc->coach_name</b> : $lc->chat_messages <div class='font-12' style='float:right;''>$tchat</div>";
        }
        // $tampil=mysql_query("select id, coach_name, chat_messages, time from chat where appointment_id='$a' order by id desc");
        // $sendername = $tampil['coach_name'];

        // while($r=mysql_fetch_array($tampil)){
        // $r['coach_name'];
        // echo "<li><b>$r[coach_name]</b> : $r[chat_messages] <div class='font-12' style='float:right;''>$r[time]</div>";
        // }
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

    public function store_session(){

        $id = $this->auth_manager->userid();
        $appoint_id = $this->input->post("appointment_id");

        if($this->auth_manager->role() == 'STD'){
            if(@$appoint_id){

                $checksess = $this->db->select('*')
                    ->from('session_live')
                    ->where('user_id', $id)
                    ->where('appointment_id', $appoint_id)
                    ->get()->result();

                $checkstd = @$checksess[0]->status;

                if(!@$checksess){
                    $sesslivestd = array(
                       'appointment_id' => $appoint_id,
                       'user_id' => $id,
                       'status'  => 1
                    );

                    $this->db->insert('session_live', $sesslivestd);
                }else if(@$checkstd == 0){
                    $updstd = array(
                       'status' => 1
                    );

                    $this->db->where('user_id', $id);
                    $this->db->where('appointment_id', $appoint_id);
                    $this->db->update('session_live', $updstd);
                }
            }

        } else if($this->auth_manager->role() == 'CCH'){
            if(@$appoint_id){

                $checksess = $this->db->select('*')
                    ->from('session_live')
                    ->where('user_id', $id)
                    ->where('appointment_id', $appoint_id)
                    ->get()->result();

                $checkstd = @$checksess[0]->status;

                if(!@$checksess){
                    $sesslivecch = array(
                       'appointment_id' => $appoint_id,
                       'user_id' => $id,
                       'status' => 1
                    );

                    $this->db->insert('session_live', $sesslivecch);
                }else if(@$checkcch == 0){
                    $updcch = array(
                       'status' => 1
                    );

                    $this->db->where('user_id', $id);
                    $this->db->where('appointment_id', $appoint_id);
                    $this->db->update('session_live', $updcch);
                }
            }
        }

    }

    public function check_sess(){
        $id = $this->auth_manager->userid();
        $appoint_id = $this->input->post("appointment_id");

        $checksess = $this->db->select('*')
                    ->from('session_live')
                    ->where('user_id', $id)
                    ->where('appointment_id', $appoint_id)
                    ->get()->result();

        $stat_val = @$checksess[0]->status;

        echo $stat_val;

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
