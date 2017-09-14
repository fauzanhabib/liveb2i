<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cronsession extends MY_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');

        //checking user role and giving action
    }

    // Index
    public function index() {

        if (!$this->input->is_cli_request()) {
            show_error('Direct access is not allowed');
        }

      $nowdate  = date("Y-m-d"); 
      $hour_start_db  = date('H:i:s');

      $listapp = $this->db->select('*')
              ->from('appointments')
              ->where('date <=', $nowdate)
              ->where('end_time <', $hour_start_db)
              ->where('cch_attend', NULL)
              ->where('std_attend', NULL)
              ->order_by('id', 'DESC')
              ->get()->result();

      // echo "<pre>";print_r($listapp);exit();
      foreach(@$listapp as $la){
        $coach_id   = $la->coach_id;
        $student_id = $la->student_id;
        $appoint_id = $la->id;
        $date       = $la->date;

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
        $start_time   = $la->start_time;
        $default      = strtotime($start_time);
        $usertime1    = $default+(60*$minutes);
        $start_conv   = date("H:i", $usertime1);

        $end_time     = $la->end_time;
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
        $prt_id = $this->db->select('*')
              ->from('user_profiles')
              ->where('user_id', $coach_id)
              ->get()->result();

        $partner_id = $prt_id[0]->partner_id;
        
        $setting = $this->db->select('standard_coach_cost,elite_coach_cost')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();
        $standard_coach_cost = $setting[0]->standard_coach_cost;
        $elite_coach_cost = $setting[0]->elite_coach_cost;

        $cost = '';
        if($type_id == 1){
            $cost = $standard_coach_cost;
        }else if($type_id == 2){
            $cost = $elite_coach_cost;
        }

        $checks = $this->db->select('*')
                ->from('token_histories_coach')
                ->where('appointment_id', $appoint_id)
                ->get()->result();

        if(@$checks[0]->id == NULL){
            $curr_token_pulls = $this->db->select('token_amount')
                ->from('user_tokens')
                ->where('user_id', $student_id)
                ->get()->result();

            $curr_tokens = $curr_token_pulls[0]->token_amount;
            $upd_token   = $curr_tokens + $cost;

            // echo "<pre>";print_r($upd_token);exit();
            $curr_token_cch = $this->db->select('token_amount')
                ->from('user_tokens')
                ->where('user_id', $coach_id)
                ->get()->result();

            $curr_tokens_cch = @$curr_token_cch[0]->token_amount;
            // echo "<pre>";
            // print_r($curr_token_cch);
            // exit();

            $token_student_update = array(
                'token_amount' => $upd_token
            );

            $this->db->where('user_id', $student_id);
            $this->db->update('user_tokens', $token_student_update);

            $desc = "Your tokens were refunded because you and your coach didn't attend the session";
            $idstat = 23;

            $token_coach_hist = array(
                'coach_id' => $coach_id,
                'date'     => $date,
                'time'     => $time,
                'flag'     => 2,
                'description'  => $desc,
                'upd_token'    => @$curr_tokens_cch,
                'token_amount' => 0,
                'student_name' => $student_name,
                'appointment_id'  => $appoint_id
            );
            $this->db->insert('token_histories_coach', $token_coach_hist);

            //update token histories for student
            $token_std_hist = array(
                'user_id' => $student_id,
                'transaction_date' => time(),
                'token_amount'     => $cost,
                'description'      => $desc,
                'token_status_id'  => $idstat,
                'balance' => $upd_token,
                'dcrea'   => 0,
                'dupd'    => 0
            );
            $this->db->insert('token_histories', $token_std_hist);

        }

        // echo "<pre>";print_r($checks);
      }

    }
    

}