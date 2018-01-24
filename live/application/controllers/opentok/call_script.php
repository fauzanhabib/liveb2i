<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'vendor/autoload.php';

class Call_script extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');
        $this->load->model('user_model');
        $this->load->library('Auth_manager');
        $this->load->model('coaching_script_model');
        $this->load->library('Study_progress');
    }

    // Index
    public function call_ajax() {
      // exit('a');
        // $std_id_for_cert = isset($_POST['std_id']);
        // $val_step    = $pull_step->study_path_index;
        // $val_lesson  = $pull_lesson[1];
        // echo "<pre>";print_r($val_step);exit();

        // $val_step = 30;
        $std_id_for_cert=$this->input->post('std_id');

        $get_gl_users = $this->db->select('cl_id, sso_username')
                ->from('users')
                ->where('id', $std_id_for_cert)
                ->get()->result();

        $id_gl_users = $get_gl_users[0]->cl_id;
        // echo "<pre>";print_r($get_gl_users);exit();
        $get_gl_dsa = $this->db->select('cl_name')
                ->from('dsa_cert_levels')
                ->where('cl_id', $id_gl_users)
                ->get()->result();
                // echo "<pre>";print_r($std_id_for_cert);exit();

        $std_sso = $get_gl_users[0]->sso_username;

        // $tokenresult = $this->study_progress->GenerateToken($std_sso);
        // $gsp = json_decode($this->study_progress->GetStudyProgress($tokenresult));
        $pull_gcp = $this->db->select('*')
                  ->from('b2c_student_progress')
                  ->where('user_id', $std_id_for_cert)
                  ->get()->result();

        $gsp = json_decode(@$pull_gcp[0]->json_gsp);
        $gcp = json_decode(@$pull_gcp[0]->json_gcp);
        $gwp = json_decode(@$pull_gcp[0]->json_gwp);

        if(@$gsp->data->certification_level){
          $pull_step   = end($gsp->data->study->units);
          $pull_lesson = explode('_',@$gsp->data->last_lesson_code);

          $check_step    = $pull_step->study_path_index;
          $check_lesson  = $pull_lesson[1];
          // $val_step    = 37;
          // $val_lesson  = 'ndem1u1';
          if(@$check_step && @$check_lesson){
            $val_step    = @$check_step;
            $val_lesson  = @$check_lesson;
          }else{
            $def_lesson = $this->db->select('lesson')
                        ->from('b2c_script')
                        ->where('certificate_plan', $get_gl_dsa[0]->cl_name)
                        ->get()->result();

            $val_lesson = $def_lesson[0]->lesson;
            $val_step   = 0;
          }

        }else{
          $def_lesson = $this->db->select('lesson')
                      ->from('b2c_script')
                      ->where('certificate_plan', $get_gl_dsa[0]->cl_name)
                      ->get()->result();

          $val_lesson = $def_lesson[0]->lesson;
          $val_step   = 0;
        }

        // if(!@$val_lesson){
        //   $def_lesson = $this->db->select('lesson')
        //               ->from('b2c_script')
        //               ->where('certificate_plan', $get_gl_dsa[0]->cl_name)
        //               ->get()->result();
        //
        //   $val_lesson = $def_lesson[0]->lesson;
        //   $val_step   = 0;
        // }



        $script = $this->db->distinct()
                ->select('bc.unit')
                ->from('b2c_script_student bs')
                ->join('b2c_script bc', 'bc.id = bs.script_id')
                ->where('bs.user_id', $std_id_for_cert)
                ->where('bc.certificate_plan', $get_gl_dsa[0]->cl_name)
                ->get()->result();
                // echo "<pre>";print_r($script);exit();

        $limiter = $this->db->select('id')
                 ->from('b2c_script')
                 ->where('certificate_plan', $get_gl_dsa[0]->cl_name)
                 ->where('lesson =', $val_lesson)
                 ->where('step_bot <=', $val_step)
                 ->where('step_up >=', $val_step)
                 ->get()->result();



        if(@$limiter){
          $limit_bot = @$limiter[0]->id;
          $limit_top = end($limiter)->id;
        }else{
          $def_lesson = $this->db->select('lesson, unit')
                      ->from('b2c_script')
                      ->where('certificate_plan', $get_gl_dsa[0]->cl_name)
                      ->get()->result();

          $val_lesson = $def_lesson[0]->lesson;
          $val_step   = 0;
          // echo "<pre>";print_r($val_step);exit();
          $limit_bot  = 0;
          $def_lesson2 = $this->db->select('id')
                      ->from('b2c_script')
                      ->where('certificate_plan', $get_gl_dsa[0]->cl_name)
                      ->where('unit', $def_lesson[0]->unit)
                      ->get()->result();
          $limit_top  = end($def_lesson2)->id;
        }
        // echo "<pre>";print_r(end($def_lesson2)->id);exit();

      $script_hist = $this->db->distinct()
              ->select('bc.unit')
              ->from('b2c_script_student bs')
              ->join('b2c_script bc', 'bc.id = bs.script_id')
              ->where('bs.user_id', $std_id_for_cert)
              ->where('bc.certificate_plan', $get_gl_dsa[0]->cl_name)
              ->where('bc.id <', $limit_bot)
              ->get()->result();


      $script_curr = $this->db->distinct()
              ->select('bc.unit')
              ->from('b2c_script_student bs')
              ->join('b2c_script bc', 'bc.id = bs.script_id')
              ->where('bs.user_id', $std_id_for_cert)
              ->where('bc.certificate_plan', $get_gl_dsa[0]->cl_name)
              ->where('bc.lesson =', $val_lesson)
              ->where('bc.step_up >=', $val_step)
              ->where('bc.step_bot <=', $val_step)
              ->get()->result();

              // echo "<pre>";print_r($val_step);exit();

      $script_next = $this->db->distinct()
              ->select('bc.unit')
              ->from('b2c_script_student bs')
              ->join('b2c_script bc', 'bc.id = bs.script_id')
              ->where('bs.user_id', $std_id_for_cert)
              ->where('bc.certificate_plan', $get_gl_dsa[0]->cl_name)
              ->where('bc.id >', $limit_top)
              ->get()->result();

        if(!@$script){
            $scripts = $this->db->select('*')
              ->from('b2c_script')
              ->where('certificate_plan', $get_gl_dsa[0]->cl_name)
              ->get()->result();

            $script_total = count($scripts);
            $data =array();
            $n = 0;

            // echo "<pre>";print_r($scripts);exit();

            for($i=0; $i < $script_total; $i++)
            {
                @$datascript[$i] = array(
                'user_id'   => $std_id_for_cert,
                'script_id' => $scripts[$n]->id,
                'cert_plan' => $get_gl_dsa[0]->cl_name,
                'status'    => '0'
                );
                $n++;
            }

            // $this->db->set($datascript);
            $this->db->insert_batch('b2c_script_student', @$datascript);
            // echo "<pre>";print_r($script_curr);exit();
            // $data = array(
            //   'std_id_for_cert'  => @$std_id_for_cert
            // );
            // $this->load->view('contents/opentok/coach/call_script_view_first', $data);
        }

    $bag = $this->db->select('*')
         ->from('bag_of_tricks')
         ->get()->result();
    $content = $bag['0']->content;

    $data = array(
          'content'   => @$content,
          'script'    => @$script,
          'script_hist'    => @$script_hist,
          'script_curr'    => @$script_curr,
          'script_next'    => @$script_next,
          'lesson_step'    => @$val_step,
          'std_id_for_cert'  => @$std_id_for_cert,
          'std_cert'  => @$get_gl_dsa[0]->cl_name
    );
    // echo "<pre>";print_r($script_curr);exit();

    $this->load->view('contents/opentok/coach/call_script_view', $data);
  }
  public function update_script(){
    $script_id = $this->input->post('script_id');
    $std_id    = $this->input->post('std_id');

    $data = array(
               'status' => 1
            );

    $this->db->where('id', $script_id);
    $this->db->update('b2c_script_student', $data);
    echo 'success';
  }

}
