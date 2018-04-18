<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class new_schedule extends MY_Site_Controller {
  var $day_index = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');

  public function __construct() {
      parent::__construct();
      $this->load->model('schedule_model');
      $this->load->model('offwork_model');
      $this->load->model('identity_model');
      $this->load->model('partner_model');
      $this->load->model('partner_setting_model');
      $this->load->model('specific_settings_model');

      //checking user role and giving action
      if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CCH') {
          $this->messages->add('ERROR');
          redirect('account/identity/detail/profile');
      }
  }

  public function index(){
    $this->template->title = 'Schedules';

    //add Irawan
    $id    = $this->auth_manager->userid();
    $tz = $this->db->select('*')
        ->from('user_timezones')
        ->where('user_id', $id)
        ->get()->result();
    $gmt_vals = $tz[0]->gmt_val;

    $wh = floor($gmt_vals);
    $fract = $gmt_vals - $wh;

    if($fract == 0){
      $gmt_val = $gmt_vals + 0;
    }else{
      $gmt_val = $gmt_vals;
    }

    if($gmt_val > 0){
        $gmt_val = "+".$gmt_val;
    }
    //add Irawan
    $partner_id = $this->auth_manager->partner_id($this->auth_manager->userid());
    $setting = $this->session_duration($partner_id);

    $get_sched = $this->db->distinct()
        ->select('s_block')
        ->from('new_schedules')
        ->where('coach_id', $id)
        ->order_by('s_start_time', 'ASC')
        ->get()->result();

    $total_block = count($get_sched);

    $allscheds = array();

    for($i=0;$i<$total_block;$i++){
      $pullsched = $this->db->select('*')
          ->from('new_schedules')
          ->where('coach_id', $id)
          ->where('s_block', $i)
          ->get()->result();

      if(count($pullsched) > 1){
        $getblock  = $pullsched[0]->s_block;

        $getday0   = $pullsched[0]->s_day;
        $getstart0 = $pullsched[0]->s_start_time;

        $st_str0 = strtotime($getday0.', '.$getstart0);
        $st_cal0 = strtotime($gmt_val.'hours', $st_str0);
        $st_print0 = date('H:i',$st_cal0);

        $getday1   = $pullsched[1]->s_day;
        $getstart1 = $pullsched[1]->s_end_time;

        $st_str1 = strtotime($getday1.', '.$getstart1);
        $st_cal1 = strtotime($gmt_val.'hours', $st_str1);
        $st_print1 = date('H:i',$st_cal1);

        $push_day = date('l',$st_cal0);
        $getid = $pullsched[0]->id;

        $push_sched = array(
          's_start_time' => $st_print0,
          's_end_time' => $st_print1,
          's_day' => $push_day,
          's_block' => $getblock,
          'id' => $getid
        );

        array_push($allscheds, $push_sched);

        // echo "<pre>";print_r($st_print0);exit();
      }else{
        $getblock  = $pullsched[0]->s_block;

        $getday0   = $pullsched[0]->s_day;
        $getstart0 = $pullsched[0]->s_start_time;
        $getend0 = $pullsched[0]->s_end_time;

        $st_str0 = strtotime($getday0.', '.$getstart0);
        $st_cal0 = strtotime($gmt_val.'hours', $st_str0);
        $st_print0 = date('H:i',$st_cal0);

        $st_str1 = strtotime($getday0.', '.$getend0);
        $st_cal1 = strtotime($gmt_val.'hours', $st_str1);
        $st_print1 = date('H:i',$st_cal1);

        $push_day = date('l',$st_cal0);
        $getid = $pullsched[0]->id;

        $push_sched = array(
          's_start_time' => $st_print0,
          's_end_time' => $st_print1,
          's_day' => $push_day,
          's_block' => $getblock,
          'id' => $getid
        );

        array_push($allscheds, $push_sched);
      }

      // echo "<pre>";print_r($allscheds);exit();
    }

    // echo "<pre>";print_r($allscheds);exit();

    $vars = array(
        'gmt_val' => $gmt_val,
        'session_duration' => $setting[0]->session_duration,
        'schedules' => $allscheds
    );

    // echo "<pre>";print_r($vars);exit();

    $this->template->content->view('default/contents/schedule/new_index', $vars);
    $this->template->publish();
  }

  public function add_schedule(){
    $id    = $this->auth_manager->userid();
    $tz = $this->db->select('*')
        ->from('user_timezones')
        ->where('user_id', $id)
        ->get()->result();
    $gmt_vals = $tz[0]->gmt_val;

    $wh = floor($gmt_vals);
    $fract = $gmt_vals - $wh;

    if($fract == 0){
      $gmt_val = $gmt_vals + 0;
    }else{
      $gmt_val = $gmt_vals;
    }

    if($gmt_val > 0){
        $gmt_val = "+".$gmt_val;
    }

    $gmt_final = $gmt_val * -1;
    //add Irawan

    $getday   = $this->input->post("inp_day");
    $getstart = $this->input->post("inp_start");
    $getend   = $this->input->post("inp_end");

    $st_str = strtotime($getday.', '.$getstart);
    $st_cal = strtotime($gmt_final.'hours', $st_str);
    $st_db = date('H:i',$st_cal);

    $et_str = strtotime($getday.', '.$getend);
    $et_cal = strtotime($gmt_final.'hours', $et_str);
    $et_db = date('H:i',$et_cal);

    $day_st_db = date('l',$st_cal);
    $day_et_db = date('l',$et_cal);

    // echo "<pre>";print_r($day_st_db);exit();
    $check_sched = $this->db->distinct()
        ->select('s_block')
        ->from('new_schedules')
        ->where('coach_id', $id)
        ->order_by('s_block', 'Desc')
        ->get()->result();

    $block = @$check_sched[0]->s_block;
    // echo "<pre>";print_r($block);exit();
    if(@$check_sched){
      $block = $block + 1;
    }else{
      $block = 0;
    }

    if($day_st_db != $day_et_db){

      // Check if schedule exists ===============
      // $st_db = '23:00';
      $ch_exist = $this->db->select('*')
          ->from('new_schedules')
          ->where('coach_id', $id)
          ->where('s_day', $day_st_db)
          ->where('s_start_time >=', $st_db)
          ->get()->result();

      if(@$ch_exist){
        $this->messages->add('Schedule already exists', 'warning');
        redirect('coach/new_schedule');
      }

      // echo "<pre>";print_r($ch_exist);exit();
      // Check if schedule exists ===============

      $vars1 = array(
          'coach_id' => $id,
          's_day' => $day_st_db,
          's_start_time' => $st_db,
          's_end_time' => '23:59',
          's_block' => $block
      );

      $this->db->insert('new_schedules', $vars1);

      $vars2 = array(
          'coach_id' => $id,
          's_day' => $day_et_db,
          's_start_time' => '00:00',
          's_end_time' => $et_db,
          's_block' => $block
      );

      $this->db->insert('new_schedules', $vars2);

      $this->messages->add('Successfully add schedule on '.$getday, 'success');
      redirect('coach/new_schedule');
    }else{
      // Check if schedule exists ===============
      // $st_db = '23:00';
      $ch_exist = $this->db->select('*')
          ->from('new_schedules')
          ->where('coach_id', $id)
          ->where('s_day', $day_st_db)
          ->where('s_start_time <=', $st_db)
          ->where('s_end_time >=', $st_db)
          ->get()->result();

      if(@$ch_exist){
        $this->messages->add('Schedule already exists', 'warning');
        redirect('coach/new_schedule');
      }

      // echo "<pre>";print_r($ch_exist);exit();
      // Check if schedule exists ===============

      $vars = array(
          'coach_id' => $id,
          's_day' => $day_et_db,
          's_start_time' => $st_db,
          's_end_time' => $et_db,
          's_block' => $block
      );

      $this->db->insert('new_schedules', $vars);

      $this->messages->add('Successfully add schedule on '.$getday, 'success');
      redirect('coach/new_schedule');
    }

  }

  private function session_duration($partner_id = ''){
    $setting = $this->specific_settings_model->get_partner_settings($partner_id);

    return $setting;
  }

  public function update_schedule(){
    $id    = $this->auth_manager->userid();
    $tz = $this->db->select('*')
        ->from('user_timezones')
        ->where('user_id', $id)
        ->get()->result();
    $gmt_vals = $tz[0]->gmt_val;

    $wh = floor($gmt_vals);
    $fract = $gmt_vals - $wh;

    if($fract == 0){
      $gmt_val = $gmt_vals + 0;
    }else{
      $gmt_val = $gmt_vals;
    }

    if($gmt_val > 0){
        $gmt_val = "+".$gmt_val;
    }

    $gmt_final = $gmt_val * -1;
    //add Irawan

    $getday   = $this->input->post("edit_day");
    $getstart = $this->input->post("edit_start");
    $getend   = $this->input->post("edit_end");
    $getid    = $this->input->post("edit_id");

    $st_str = strtotime($getday.', '.$getstart);
    $st_cal = strtotime($gmt_final.'hours', $st_str);
    $st_db = date('H:i',$st_cal);

    $et_str = strtotime($getday.', '.$getend);
    $et_cal = strtotime($gmt_final.'hours', $et_str);
    $et_db = date('H:i',$et_cal);

    $day_st_db = date('l',$st_cal);
    $day_et_db = date('l',$et_cal);

    // echo "<pre>";print_r($day_st_db);exit();
    $check_sched = $this->db->distinct()
        ->select('s_block')
        ->from('new_schedules')
        ->where('coach_id', $id)
        ->order_by('s_block', 'Desc')
        ->get()->result();

    $block = $this->input->post("edit_block");

    if($day_st_db != $day_et_db){

      // Check if schedule exists ===============
      // $st_db = '23:00';
      $ch_exist = $this->db->select('*')
          ->from('new_schedules')
          ->where('coach_id', $id)
          ->where('s_day', $day_st_db)
          ->where('s_start_time >=', $st_db)
          ->where('s_block !=', $block)
          ->get()->result();

      // echo "<pre>";print_r($getid);exit();

      if(@$ch_exist){
        $this->messages->add('Schedule already exists', 'warning');
        redirect('coach/new_schedule');
      }

      // Check if schedule exists ===============

      $vars1 = array(
          's_day' => $day_st_db,
          's_start_time' => $st_db
      );
      // echo "<pre>";print_r($vars1);exit();

      // $this->db->insert('new_schedules', $vars1);
      $array_upd = array('coach_id =' => $id, 'id =' => $getid);

      $this->db->where($array_upd);
      $this->db->update('new_schedules', $vars1);

      $vars2 = array(
          's_day' => $day_et_db,
          's_end_time' => $et_db,
      );

      // $this->db->insert('new_schedules', $vars2);
      $getid2 = $getid +1;
      // echo "<pre>";print_r($getid2);exit();
      $array_upd = array('coach_id =' => $id, 'id =' => $getid2);

      $this->db->where($array_upd);
      $this->db->update('new_schedules', $vars2);

      $this->messages->add('Successfully update schedule on '.$getday, 'success');
      redirect('coach/new_schedule');
    }else{
      // Check if schedule exists ===============
      // $st_db = '23:00';
      $ch_exist = $this->db->select('*')
          ->from('new_schedules')
          ->where('coach_id', $id)
          ->where('s_day', $day_st_db)
          ->where('s_start_time >=', $st_db)
          ->where('s_block !=', $block)
          ->get()->result();

      if(@$ch_exist){
        $this->messages->add('Schedule already exists', 'warning');
        redirect('coach/new_schedule');
      }

      // echo "<pre>";print_r($ch_exist);exit();
      // Check if schedule exists ===============

      $vars = array(
          's_start_time' => $st_db,
          's_end_time' => $et_db,
          's_day' => $day_et_db
      );

      // echo "<pre>";print_r($block);exit();

      $array_upd = array('coach_id =' => $id, 's_block =' => $block);

      $this->db->where($array_upd);
      $this->db->update('new_schedules', $vars);

      // $this->db->insert('new_schedules', $vars);

      $this->messages->add('Successfully update schedule on '.$getday, 'success');
      redirect('coach/new_schedule');
    }

  }

}

?>
