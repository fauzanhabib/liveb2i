<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'vendor/autoload.php';

class Call_loader_coach extends MY_Site_Controller {

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
        // $std_id_for_cert = isset($_POST['std_id']);
        $std_id_for_cert=$this->input->post('std_id');

        $data_dyned_pro = $this->identity_model->get_student_identity($std_id_for_cert);
        $pro_id         = $data_dyned_pro[0]->dyned_pro_id;
        $pro_server     = $data_dyned_pro[0]->server_dyned_pro;
        $this->call2->init($data_dyned_pro[0]->server_dyned_pro, $data_dyned_pro[0]->dyned_pro_id);
        $this->call1->init($data_dyned_pro[0]->dyned_pro_id,'' , $data_dyned_pro[0]->server_dyned_pro);

        $student_vrm      = $this->call2->getdataObj();
        $student_vrm_json = $this->call2->getDataJson();

        $callOneJson    = $this->call1->callOneJson();
        $checkCallOne   = @$callOneJson->studentName;

        $student_cert   = @$student_vrm->cert_studying;

        if($student_cert != "Unknown" ){
          if(@$checkCallOne){
              $script = $this->db->distinct()
                      ->select('s.unit')
                      ->from('coaching_scripts cs')
                      ->join('script s', 's.id = cs.script_id')
                      ->where('cs.user_id', $std_id_for_cert)
                      ->where('s.certificate_plan', $student_cert)
                      ->get()->result();

              if(!@$script){
                  $scripts = $this->db->select('*')
                    ->from('script')
                    ->where('certificate_plan', $student_cert)
                    ->get()->result();

                  if(!@$scripts){
                    echo "Currently we haven't had the scripts for ".$student_cert." yet";exit();
                  }

                  $script_total = count($scripts);
                  $data =array();
                  $n = 0;

                  // echo "<pre>";print_r($scripts);exit();

                  for($i=0; $i < $script_total; $i++)
                  {
                      @$datascript[$i] = array(
                      'user_id'   => $std_id_for_cert,
                      'script_id' => $scripts[$n]->id,
                      'cert_plan' => $student_cert,
                      'status'    => '0'
                      );
                      $n++;
                  }

                  // echo "<pre>";print_r($student_cert);exit();
                  $this->db->insert_batch('coaching_scripts', @$datascript);


              }

              $bag = $this->db->select('*')
                   ->from('bag_of_tricks')
                   ->get()->result();
              $content = $bag['0']->content;

          }
        }else{
          // A1 = 0-0.5 PT
          // A2 = 0.5 > PT <= 1.5
          // B1 = 1.5 > PT <=2
          // B2 = 2 > PT <=2.5
          // C1 = 2.5 > PT <=3
          // C2 = PT > 3
          $student_pt = $student_vrm->last_pt_score;
          // $student_pt = "1.8";

          if($student_pt >= '0' && $student_pt <= "0.5"){
            $student_cert = "A1";
          }else if($student_pt > '0.5' && $student_pt <= "1.5"){
            $student_cert = "A2";
          }else if($student_pt > '1.5' && $student_pt <= "2"){
            $student_cert = "B1";
          }else if($student_pt > '2' && $student_pt <= "2.5"){
            $student_cert = "B2";
          }else if($student_pt > '2.5' && $student_pt <= "3"){
            $student_cert = "C1";
          }else if($student_pt > '3'){
            $student_cert = "C2";
          }

          // echo "<pre>";print_r($student_cert);exit();

          $script = $this->db->distinct()
                  ->select('s.unit')
                  ->from('coaching_scripts cs')
                  ->join('script s', 's.id = cs.script_id')
                  ->where('cs.user_id', $std_id_for_cert)
                  ->where('s.certificate_plan', $student_cert)
                  ->get()->result();

          if(!@$script){
              $scripts = $this->db->select('*')
                ->from('script')
                ->where('certificate_plan', $student_cert)
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
                  'cert_plan' => $student_cert,
                  'status'    => '0'
                  );
                  $n++;
              }


              $this->db->insert_batch('coaching_scripts', @$datascript);


          }

          $bag = $this->db->select('*')
               ->from('bag_of_tricks')
               ->get()->result();
          $content = $bag['0']->content;

        }

        $data = array(
              'content'   => @$content,
              'script'    => @$script,
              'student_vrm'    => @$student_vrm,
              'std_id_for_cert'  => @$std_id_for_cert,
              'student_vrm_json' => @$student_vrm_json
        );
        // echo "<pre>";print_r($script);exit();

        $this->load->view('contents/opentok/call_loader_view_script', $data);

    }
}
