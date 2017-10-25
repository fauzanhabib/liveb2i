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
        $this->load->library('call1');
        $this->load->library('call2');
        $this->load->model('coaching_script_model');
    }

    // Index
    public function call_ajax() {
      // exit('a');
        // $std_id_for_cert = isset($_POST['std_id']);
        $lesson_step = 65;
        $std_id_for_cert=$this->input->post('std_id');

        $get_gl_users = $this->db->select('cl_id')
                ->from('users')
                ->where('id', $std_id_for_cert)
                ->get()->result();

        $id_gl_users = $get_gl_users[0]->cl_id+1;

        $get_gl_dsa = $this->db->select('cl_name')
                ->from('dsa_cert_levels')
                ->where('cl_id', $id_gl_users)
                ->get()->result();

        $script = $this->db->distinct()
                ->select('bc.unit')
                ->from('b2c_script_student bs')
                ->join('b2c_script bc', 'bc.id = bs.script_id')
                ->where('bs.user_id', $std_id_for_cert)
                ->where('bc.certificate_plan', $get_gl_dsa[0]->cl_name)
                // ->where('bc.step_bot <=', $lesson_step)
                ->get()->result();
                // echo "<pre>";print_r($script);exit();

      $script_hist = $this->db->distinct()
              ->select('bc.unit')
              ->from('b2c_script_student bs')
              ->join('b2c_script bc', 'bc.id = bs.script_id')
              ->where('bs.user_id', $std_id_for_cert)
              ->where('bc.certificate_plan', $get_gl_dsa[0]->cl_name)
              ->where('bc.step_up <=', $lesson_step)
              ->get()->result();

      $script_curr = $this->db->distinct()
              ->select('bc.unit')
              ->from('b2c_script_student bs')
              ->join('b2c_script bc', 'bc.id = bs.script_id')
              ->where('bs.user_id', $std_id_for_cert)
              ->where('bc.certificate_plan', $get_gl_dsa[0]->cl_name)
              ->where('bc.step_up >=', $lesson_step)
              ->where('bc.step_bot <=', $lesson_step)
              ->get()->result();

      $script_next = $this->db->distinct()
              ->select('bc.unit')
              ->from('b2c_script_student bs')
              ->join('b2c_script bc', 'bc.id = bs.script_id')
              ->where('bs.user_id', $std_id_for_cert)
              ->where('bc.certificate_plan', $get_gl_dsa[0]->cl_name)
              ->where('bc.step_bot >=', $lesson_step)
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


            $this->db->insert_batch('b2c_script_student', @$datascript);


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
          'lesson_step'    => @$lesson_step,
          'std_id_for_cert'  => @$std_id_for_cert,
          'std_cert'  => @$get_gl_dsa[0]->cl_name
    );
    // echo "<pre>";print_r($script_curr);exit();

    $this->load->view('contents/opentok/coach/call_script_view', $data);
  }

}
