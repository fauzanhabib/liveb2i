<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'vendor/autoload.php';

use OpenTok\OpenTok;
use OpenTok\ArchiveMode;
use OpenTok\MediaMode;
use OpenTok\Session;
use OpenTok\Role;

class Leavesession extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');
        $this->load->library('downloadrecord');

        //checking user role and giving action
    }

    // Index
    public function index() {
        $appoint_id = $this->input->post("appoint_id");
        $annualtoken = $this->input->post("annualtoken");
        $id = $this->auth_manager->userid();


        if($this->auth_manager->role() == 'STD'){
            $sess = $this->db->select('*')
                    ->from('appointments')
                    ->where('id', $appoint_id)
                    ->get()->result();
            $role = 'STD';

            $deletesess = $this->db->delete('session_live', array('appointment_id' => $appoint_id, 'user_id' => $id));

        } else if($this->auth_manager->role() == 'CCH'){
            $sess = $this->db->select('*')
                    ->from('appointments')
                    ->where('id', $appoint_id)
                    ->get()->result();

            $role = 'CCH';

            $deletesess = $this->db->delete('session_live', array('appointment_id' => $appoint_id, 'user_id' => $id));

        }
        @$a = $sess[0]->id;
        // echo "<pre>";print_r($a);exit();
        $upd_status_appointment = array(
           'status' => 'completed'
        );

        $this->db->where('id', $appoint_id);
        $this->db->update('appointments', $upd_status_appointment);

        $deletechat = $this->db->delete('chat', array('appointment_id' => $a));

        if($this->auth_manager->role() == 'STD'){
            $user = $this->db->select('*')
                  ->from('appointments')
                  ->join('user_profiles', 'user_profiles.user_id = appointments.coach_id')
                  ->where('appointments.id', $a)
                  ->get()->result();
        } else if($this->auth_manager->role() == 'CCH'){
            $user = $this->db->select('*')
                  ->from('appointments')
                  ->join('user_profiles', 'user_profiles.user_id = appointments.student_id')
                  ->where('appointments.id', $a)
                  ->get()->result();
        }


        $user_extract = $user[0];
        $coach_id     = $user_extract->coach_id;
        $student_id   = $user_extract->student_id;
        $date         = $user_extract->date;
        $cch_notes    = $user_extract->cch_notes;

        $usersss = $this->db->select('*')
              ->from('user_profiles')
              ->where('user_id', $student_id)
              ->get()->result();

        $student_name = $usersss[0]->fullname;

        $tz = $this->db->select('*')
                ->from('user_timezones')
                ->where('user_id', $coach_id)
                ->get()->result();

        $minutes = $tz[0]->minutes_val;
        date_default_timezone_set('UTC');
        $start_time   = $user_extract->start_time;
        $default      = strtotime($start_time);
        $usertime1    = $default+(60*$minutes);
        $start_conv   = date("H:i", $usertime1);

        $end_time     = $user_extract->end_time;
        $default2     = strtotime($end_time);
        $usertime2    = $default2+(60*$minutes);
        $end_conv     = date("H:i", $usertime2);

        $time = $start_conv." - ".$end_conv;

        $type_coach = $this->db->select('coach_type_id')
                ->from('user_profiles')
                ->where('user_id', $coach_id)
                ->get()->result();

        $type_id = $type_coach[0]->coach_type_id;


        //----------
        $partner_id = $this->auth_manager->partner_id($this->auth_manager->userid());
        $region_id = $this->auth_manager->region_id($partner_id);

        $setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();
        $region_setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('specific_settings')->where('user_id',$region_id)->get()->result();
        $global_setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('global_settings')->where('type','partner')->get()->result();

        $standard_coach_cost = @$setting[0]->standard_coach_cost;
        if(!$standard_coach_cost || $standard_coach_cost == 0){
            $standard_coach_cost_region = @$region_setting[0]->standard_coach_cost;
            $standard_coach_cost = $standard_coach_cost_region;
            if(!$standard_coach_cost_region || $standard_coach_cost_region == 0){
                $standard_coach_cost_global = @$global_setting[0]->standard_coach_cost;
                $standard_coach_cost = $standard_coach_cost_global;
            }
        }

        $elite_coach_cost = @$setting[0]->elite_coach_cost;
        if(!$elite_coach_cost || $elite_coach_cost == 0){
            $elite_coach_cost_region = @$region_setting[0]->elite_coach_cost;
            $elite_coach_cost = $elite_coach_cost_region;
            if(!$elite_coach_cost_region || $elite_coach_cost_region == 0){
                $elite_coach_cost_global = @$global_setting[0]->elite_coach_cost;
                $elite_coach_cost = $elite_coach_cost_global;
            }
        }

        $cost = '';
        if($type_id == 1){
            $cost = $standard_coach_cost;
        }else if($type_id == 2){
            $cost = $elite_coach_cost;
        }
        //----------

// print_r($student_id);
//         exit();
        $student_att = $user_extract->std_attend;
        $coach_att   = $user_extract->cch_attend;

        $std_att_dif = strtotime($student_att) - strtotime($start_time);
        $cch_att_dif = strtotime($coach_att) - strtotime($start_time);

        $datetime1 = new DateTime($student_att);
        $datetime2 = new DateTime($coach_att);
        $interval = $datetime1->diff($datetime2);

        // $cch_att_val2 = $interval->format('%I:%S');
        $cch_att_val = date("i:s", $cch_att_dif);
        $std_att_val = date("i:s", $std_att_dif);

        // echo "<pre>";print_r($cch_att_val);exit();
        // Coach get token
        if(@$cch_att_val < '05:00' && @$cch_att_val != NULL){
            // exit('a');
            //Preventing refresh to insert token
            // echo "<pre>";print_r($cch_att_val);exit();
            $check = $this->db->select('*')
                    ->from('token_histories_coach')
                    ->where('appointment_id', $a)
                    ->get()->result();

            // echo "<pre>";print_r($check);exit();
            if(@$check[0]->id == NULL){
                $curr_token_pull = $this->db->select('token_amount')
                    ->from('user_tokens')
                    ->where('user_id', $coach_id)
                    ->get()->result();

                $curr_token = $curr_token_pull[0]->token_amount;
                $upd_token = $curr_token + $cost;
                // echo "<pre>";
                // print_r($upd_token);
                // exit();

                $token_coach_update = array(
                    'token_amount' => $upd_token
                );

                $this->db->where('user_id', $coach_id);
                $this->db->update('user_tokens', $token_coach_update);

                $token_coach_hist = array(
                    'coach_id' => $coach_id,
                    'date'     => $date,
                    'time'     => $time,
                    'flag'     => 1,
                    'description'  => "Session Completed / Coach gets token",
                    'upd_token'    => $upd_token,
                    'token_amount' => $cost,
                    'student_name' => $student_name,
                    'appointment_id'  => $a
                );
                $this->db->insert('token_histories_coach', $token_coach_hist);
            }
            //Preventing refresh to insert token
        }

        // Student get refund
        if(@$cch_att_val > '05:00' || @$cch_att_val == NULL || @$cch_att_val == '00:00'){
            // exit('b');
            //Preventing refresh to insert token

            $checks = $this->db->select('*')
                    ->from('token_histories_coach')
                    ->where('appointment_id', $a)
                    ->get()->result();

            $datest     = date('H:i:s');
            $defaultst  = strtotime($datest);
            $usertimest = $defaultst+(60*$minutes);


            if(@$checks[0]->id == NULL){
                $curr_token_pulls = $this->db->select('token_amount')
                    ->from('user_tokens')
                    ->where('user_id', $student_id)
                    ->get()->result();

                $curr_tokens = $curr_token_pulls[0]->token_amount;
                $upd_token   = $curr_tokens + $cost;

                $curr_token_cch = $this->db->select('token_amount')
                    ->from('user_tokens')
                    ->where('user_id', $coach_id)
                    ->get()->result();

                $curr_tokens_cch = $curr_token_cch[0]->token_amount;
                // echo "<pre>";
                // print_r($curr_tokens_cch);
                // exit();

                $token_student_update = array(
                    'token_amount' => $upd_token
                );

                $this->db->where('user_id', $student_id);
                $this->db->update('user_tokens', $token_student_update);

                if($cch_att_val == NULL || $cch_att_val == '00:00'){
                    $desc = "Coach didn't attend the session";
                	$idstat = 21;
                }else{
                    $desc = "Coach is Late / Student tokens refunded";
                    $idstat = 13;
                }

                $token_coach_hist = array(
                    'coach_id' => $coach_id,
                    'date'     => $date,
                    'time'     => $time,
                    'flag'     => 2,
                    'description'  => $desc,
                    'upd_token'    => $curr_tokens_cch,
                    'token_amount' => 0,
                    'student_name' => $student_name,
                    'appointment_id'  => $a
                );

                // echo "<pre>";print_r($token_coach_hist);exit();
                $this->db->insert('token_histories_coach', $token_coach_hist);

                //update token histories for student
                // if($this->auth_manager->role() == 'STD'){
                $token_std_hist = array(
                    'user_id' => $student_id,
                    'transaction_date' => time(),
                    'token_amount'     => $cost,
                    'description'      => $desc,
                    'token_status_id'  => $idstat,
                    'appointment_id'   => $a,
                    'balance' => $upd_token,
                    'dcrea'   => 0,
                    'dupd'    => 0
                );
                $this->db->insert('token_histories', $token_std_hist);
                //}

            }
            //Preventing refresh to insert token
            // echo "<pre>";
            // print_r($token_std_hist);
            // exit();
        }

        //$opentok->stopArchive($archiveId);
        // $archive = $opentok->getArchive($sessid);

        $sessionhist = array(
            'user' => $user_extract,
            'role' => $role,
            'cch_notes'    => $cch_notes,
            'appointment_id' => $appoint_id
            // 'download' => $download
        );

        // print_r($user_extract);
        // exit();

        $this->template->title = "Session Summaries";
        $this->template->content->view('contents/opentok/leave', $sessionhist);
        $this->template->publish();
    }

    public function rate_coach(){
        $star  = $this->input->post("star");
        $coach_id  = $this->input->post("coach_id");
        $appointment_id  = $this->input->post("appointment_id");

        $coachrate = $this->db->select('*')
                ->from('coach_ratings')
                ->where('appointment_id', $appointment_id)
                ->get()->result();

        if(@$coachrate[0] == NULL){
            $rate = array(
                'rate'   => $star,
                'status' => "rated",
                'coach_id' => $coach_id,
                'dcrea'  => " ",
                'dupd'   => " ",
                'description'    => " ",
                'appointment_id' => $appointment_id
            );
            $this->db->insert('coach_ratings', $rate);
        }else if($coachrate){
            $update_rate = array(
                'rate' => $star
            );

            $this->db->where('appointment_id', $appointment_id);
            $this->db->update('coach_ratings', $update_rate);
        }

    }

    public function check_rate(){
        $coach_id  = $this->input->post("coach_id");
        $appointment_id  = $this->input->post("appointment_id");

        $coachrate = $this->db->select('*')
                ->from('coach_ratings')
                ->where('appointment_id', $appointment_id)
                ->get()->result();

        if(@$coachrate[0] == NULL){
            echo "You haven't rated the coach.";
        }else if($coachrate){
            echo "exit";
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

}
