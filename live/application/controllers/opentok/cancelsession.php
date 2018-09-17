<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cancelsession extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');

        //checking user role and giving action
    }

    // Index
    public function index() {
        $appoint_id = $this->input->post("appoint_id");
        $flag       = $this->input->post("flag");
       
        // print_r($flag);
        // exit();
        if($this->auth_manager->role() == 'STD'){
            $user = $this->db->select('*')
                  ->from('appointments')
                  ->join('user_profiles', 'user_profiles.user_id = appointments.coach_id')
                  ->where('appointments.id', $appoint_id)
                  ->get()->result();
            $role = 'STD';
        } else if($this->auth_manager->role() == 'CCH'){
            $user = $this->db->select('*')
                  ->from('appointments')
                  ->join('user_profiles', 'user_profiles.user_id = appointments.student_id')
                  ->where('appointments.id', $appoint_id)
                  ->get()->result();
            $role = 'CCH';
        }
      
        
        $user_extract = $user[0];

        // $cch_attend = $user_extract->cch_attend;
        // $std_attend = $user_extract->std_attend;
        // $start      = $user_extract->start_time;

        // $datetime1 = new DateTime($start);
        // $datetime2 = new DateTime($cch_attend);
        // $int_cch   = $datetime1->diff($datetime2);
        // $diff_cch  = $int_cch->format('%i minutes %s seconds.');
        // $diff_cch_val = $int_cch->format('%i');
        // $diff_min_sec1 = $diff_cch_val * 60;
        // $diff_cch_sec = $int_cch->format('%s');
        // $diff_cch_total = $diff_cch_sec + $diff_min_sec1;

        // $datetime  = new DateTime($start);
        // $datetime3 = new DateTime($std_attend);
        // $int_std   = $datetime->diff($datetime3);
        // $diff_std  = $int_std->format('%i minutes %s seconds.');
        // $diff_std_min = $int_std->format('%i');
        // $diff_min_sec = $diff_std_min * 60;
        // $diff_std_sec = $int_std->format('%s');
        // $diff_std_total = $diff_std_sec + $diff_min_sec;

        if ( $flag == "student" ){
            $notice = 'Coach is late more than 10 minutes, student will get a token refund.';
        }
        else if ( $flag == "coach" ){
            $notice = 'Student is late more than 10 minutes, you still get your token.';

            $coach_id     = $user_extract->coach_id;

            $cost_pull = $this->db->select('token_for_student')
                ->from('coach_token_costs')
                ->where('coach_id', $coach_id)
                ->get()->result();

            $utz = $this->db->select('user_timezone')
                ->from('user_profiles')
                ->where('user_id', $coach_id)
                ->get()->result();
            $idutz = $utz[0]->user_timezone;
            $tz = $this->db->select('*')
                    ->from('timezones')
                    ->where('id', $idutz)
                    ->get()->result();
            
            $minutes = $tz[0]->minutes;
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

            $cost = $cost_pull[0]->token_for_student;
            $date         = $user_extract->date;
            $student_name = $user_extract->fullname;
            
            $a = $appoint_id;
            //Preventing refresh to insert token

            $check = $this->db->select('*')
                    ->from('token_histories_coach')
                    ->where('appointment_id', $a)
                    ->get()->result();

            // echo "<pre>";
            // print_r($user_extract);
            // exit();
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

                $partner_id = $this->auth_manager->partner_id($coach_id);
                  $organization_id = '';
                  $organization_id = $this->db->select('gv_organizations.id')
                            ->from('gv_organizations')
                            ->join('users', 'users.organization_code = gv_organizations.organization_code')
                            ->where('users.id', $coach_id)
                            ->get()->result();

                  if(empty($organization_id)){
                      $organization_id = '';
                  }else{
                      $organization_id = $organization_id[0]->id;
                  }

                $token_coach_hist = array(
                    'coach_id' => $coach_id,
                    'partner_id' => $partner_id,
                    'organization_id' => $organization_id,
                    'date'     => $date,
                    'time'     => $time,
                    'upd_token'    => $upd_token,
                    'token_amount' => $cost,
                    'student_name' => $student_name,
                    'appointment_id'  => $a
                );
                $this->db->insert('token_histories_coach', $token_coach_hist);
            }
        }

        // print_r($diff_std_total);
        // exit();  

        $sessionhist = array(
            'notice' => @$notice,
            'flag'   => @$flag,
            'user'   => $user_extract,
            'role'   => $role,
            'appoint_id'   => $appoint_id
        );


        $this->template->title = "Session Summaries";
        $this->template->content->view('contents/opentok/cancel', $sessionhist);
        $this->template->publish();
    }
    

}
