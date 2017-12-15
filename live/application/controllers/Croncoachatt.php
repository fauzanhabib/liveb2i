<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Croncoachatt extends MY_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');

        //checking user role and giving action
    }

    // Index
    public function index() {

    $nowdate  = date("Y-m-d");
    $hour_start_db  = date('H:i:s');

    $pulldb = $this->db->select('*')
            ->from('appointments')
            ->where('date <=', $nowdate)
            ->where('end_time <', $hour_start_db)
            ->where('status', 'active')
            ->order_by('id', 'DESC')
            ->get()->result();


    // echo "<pre>";print_r($pulldb);exit();
    if(@$pulldb){
      foreach($pulldb as $pd){
          $appointment_id   = $pd->id;

          $coach_id   = $pd->coach_id;
          $student_id = $pd->student_id;

          $cch_attend = $pd->cch_attend;
          $std_attend = $pd->std_attend;

          $start_time = $pd->start_time;

          $usersss = $this->db->select('*')
                ->from('user_profiles')
                ->where('user_id', $student_id)
                ->get()->result();

          $student_name = @$usersss[0]->fullname;

          $tz = $this->db->select('*')
                  ->from('user_timezones')
                  ->where('user_id', $coach_id)
                  ->get()->result();

          $minutes = @$tz[0]->minutes_val;
          date_default_timezone_set('UTC');
          $start_time   = $pd->start_time;
          $default      = strtotime($start_time);
          $usertime1    = $default+(60*$minutes);
          $start_conv   = date("H:i", $usertime1);

          $end_time     = $pd->end_time;
          $default2     = strtotime($end_time);
          $usertime2    = $default2+(60*$minutes);
          $end_conv     = date("H:i", $usertime2);

          $timecoach    = $start_conv." - ".$end_conv;

          $date       = $pd->date;

          // PULL COACH COST STARTS---------------------------------------------
          $type_coach = $this->db->select('coach_type_id')
                  ->from('user_profiles')
                  ->where('user_id', $coach_id)
                  ->get()->result();

          $type_id = @$type_coach[0]->coach_type_id;

          $partner_id = $this->auth_manager->partner_id($coach_id);
          // echo "<pre>";print_r($partner_id);exit();
          $setting = $this->db->select('standard_coach_cost,elite_coach_cost')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();
          $standard_coach_cost = @$setting[0]->standard_coach_cost;
          $elite_coach_cost = @$setting[0]->elite_coach_cost;

          $cost = '';
          if($type_id == 1){
              $cost = $standard_coach_cost;
          }else if($type_id == 2){
              $cost = $elite_coach_cost;
          }
          // PULL COACH COST ENDS---------------------------------------------
          // echo "<pre>";echo $cch_attend;

          // CONDITION: STUDENT ATTENDS STARTS--------------------------------
          if($std_attend != NULL){
              if($std_attend != '00:00:00'){

                  $checks = $this->db->select('*')
                          ->from('token_histories_coach')
                          ->where('appointment_id', $appointment_id)
                          ->get()->result();

                  if(@$checks[0]->id == NULL){

                      $cch_att_dif = strtotime($cch_attend) - strtotime($start_time);
                      $cch_att_val = date("i:s", $cch_att_dif);

                      //Coach Attend but checking if late or not---------------------------------------------
                      if(@$cch_attend != NULL){
                          if(@$cch_attend != '00:00:00'){

                              //coach is late - student get refund
                              if($cch_att_val > '05:00'){
                                  $stu_token_pulls = $this->db->select('token_amount')
                                      ->from('user_tokens')
                                      ->where('user_id', $student_id)
                                      ->get()->result();

                                  $coach_token_pulls = $this->db->select('token_amount')
                                      ->from('user_tokens')
                                      ->where('user_id', $coach_id)
                                      ->get()->result();

                                  $stu_tokens   = $stu_token_pulls[0]->token_amount;
                                  $coach_tokens = @$coach_token_pulls[0]->token_amount;

                                  if(@$coach_tokens == NULL){
                                      $coach_tokens = 0;
                                  }

                                  $stu_upd_token   = $stu_tokens + $cost;

                                  //Update student token --------------------------------
                                  $token_student_update = array(
                                      'token_amount' => $stu_upd_token
                                  );

                                  $this->db->where('user_id', $student_id);
                                  $this->db->update('user_tokens', $token_student_update);
                                  //Update student token --------------------------------

                                  //Insert coach token history --------------------------------
                                  $token_coach_hist = array(
                                      'coach_id' => $coach_id,
                                      'date'     => $date,
                                      'time'     => $timecoach,
                                      'flag'     => 2,
                                      'description'  => 'Coach is Late / Student tokens refunded',
                                      'upd_token'    => @$coach_tokens,
                                      'token_amount' => 0,
                                      'student_name' => $student_name,
                                      'appointment_id'  => $appointment_id
                                  );
                                  $this->db->insert('token_histories_coach', $token_coach_hist);
                                  //Insert coach token history --------------------------------


                                  //Insert student token history --------------------------------
                                  $token_std_hist = array(
                                      'user_id' => $student_id,
                                      'transaction_date' => time(),
                                      'token_amount'     => $cost,
                                      'description'      => 'Coach is Late / Student tokens refunded',
                                      'token_status_id'  => 13,
                                      'balance' => $stu_upd_token,
                                      'dcrea'   => 0,
                                      'dupd'    => 0
                                  );
                                  $this->db->insert('token_histories', $token_std_hist);
                                  //Insert student token history --------------------------------

                                  // echo "<pre>";echo $stu_upd_token;
                              }

                          }
                      }
                      //Coach Attend but checking if late or not---------------------------------------------

                      //Coach Didnt attend ---------------------------------------------
                      else if(@$cch_attend == NULL || @$cch_attend == '00:00:00'){
                          $stu_token_pulls = $this->db->select('token_amount')
                              ->from('user_tokens')
                              ->where('user_id', $student_id)
                              ->get()->result();

                          $coach_token_pulls = $this->db->select('token_amount')
                              ->from('user_tokens')
                              ->where('user_id', $coach_id)
                              ->get()->result();

                          $stu_tokens   = $stu_token_pulls[0]->token_amount;
                          $coach_tokens = @$coach_token_pulls[0]->token_amount;

                          if(@$coach_tokens == NULL){
                              $coach_tokens = 0;
                          }

                          $stu_upd_token   = $stu_tokens + $cost;

                          //Update student token --------------------------------
                          $token_student_update = array(
                              'token_amount' => $stu_upd_token
                          );

                          $this->db->where('user_id', $student_id);
                          $this->db->update('user_tokens', $token_student_update);
                          //Update student token --------------------------------

                          //Insert coach token history --------------------------------
                          $token_coach_hist = array(
                              'coach_id' => $coach_id,
                              'date'     => $date,
                              'time'     => $timecoach,
                              'flag'     => 2,
                              'description'  => "Coach didn't attend the session",
                              'upd_token'    => $coach_tokens,
                              'token_amount' => 0,
                              'student_name' => $student_name,
                              'appointment_id'  => $appointment_id
                          );
                          $this->db->insert('token_histories_coach', $token_coach_hist);
                          //Insert coach token history --------------------------------


                          //Insert student token history --------------------------------
                          $token_std_hist = array(
                              'user_id' => $student_id,
                              'transaction_date' => time(),
                              'token_amount'     => $cost,
                              'description'      => "Coach didn't attend the session",
                              'token_status_id'  => 21,
                              'balance' => $stu_upd_token,
                              'dcrea'   => 0,
                              'dupd'    => 0
                          );
                          $this->db->insert('token_histories', $token_std_hist);
                          //Insert student token history --------------------------------

                          // echo "<pre>";echo $stu_upd_token;
                      }
                      //Coach Didnt attend ---------------------------------------------

                  }


              }
          }
          // CONDITION: STUDENT ATTENDS ENDS--------------------------------
          $updcomplete = array(
              'status' => 'completed'
          );

          $this->db->where('id', $appointment_id);
          $this->db->update('appointments', $updcomplete);

      }

      }
    }
}
