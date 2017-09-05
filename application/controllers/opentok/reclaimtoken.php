<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reclaimtoken extends MY_Site_Controller {

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
        $id = $this->auth_manager->userid();

        $check = $this->db->select('*')
                ->from('token_histories_coach')
                ->where('appointment_id', $appoint_id)
                ->get()->result();

        if(@$check[0]->id == NULL){
          $user = $this->db->select('*')
                    ->from('appointments')
                    ->join('user_profiles', 'user_profiles.user_id = appointments.coach_id')
                    ->where('appointments.id', $appoint_id)
                    ->get()->result();

          $coachid = $user[0]->coach_id;

          $cost = $this->db->select('*')
                    ->from('appointments')
                    ->join('coach_token_costs', 'coach_token_costs.coach_id = appointments.coach_id')
                    ->where('coach_token_costs.coach_id', $coachid)
                    ->get()->result();

          $coachcost = $cost[0]->token_for_student;

          $token = $this->db->select('*')
                    ->from('user_tokens')
                    ->where('user_id', $id)
                    ->get()->result();
          $currtoken = $token[0]->token_amount;
          $updtoken  = $currtoken + $coachcost;
          $data = array(
              'token_amount' => $updtoken
          );

          $this->db->where('user_id', $id);
          $this->db->update('user_tokens', $data);

          $newtoken = $this->db->select('*')
                    ->from('user_tokens')
                    ->where('user_id', $id)
                    ->get()->result();

          $upd_balance = $newtoken[0]->token_amount;

          // $user = $this->db->select('*')
          //           ->from('appointments')
          //           ->join('user_profiles', 'user_profiles.user_id = appointments.coach_id')
          //           ->where('appointments.id', $appoint_id)
          //           ->get()->result();

          $date   = $user[0]->date;
          $start  = $user[0]->start_time;
          $end    = $user[0]->end_time;
          $start  = $user[0]->start_time;
          $name   = $user[0]->fullname;
          $amount = $coachcost;
          $desc   = 'Session with'.' '.$name.' '.'at'.' '.$date.' '.$start.' '.'until'.' '.$end;
          $tokenstat = 13;
          $time = time();

          $insert_hist = array(
              'user_id' => $id,
              'transaction_date' => $time,
              'token_amount' => $amount,
              'description' => $desc,
              'token_status_id' => $tokenstat,
              'balance' => $upd_balance,
              'dupd' => $time
          );
         // Session with Adam Jhonson at 2016-06-15 07:40:00 until 08:00:00
          $this->db->insert('token_histories', $insert_hist);
          // echo "<pre>";
          // print_r($insert_hist);
          // exit();
          
          $this->messages->add('Successfuly reclaim token.', 'success');
          redirect('student/token');
        }
        else{
          $this->messages->add('You have already reclaimed token.', 'warning');
          redirect('student/token');
        }
    }
    

}
