<?php

/**
 * Class    : Histories.php
 * Author   : Ponel Panjaitan (ponel@pistarlabs.co.id)
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Student_vrm extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();

        // Loading models
        $this->load->model('appointment_model');
        $this->load->model('appointment_history_model');
        $this->load->model('class_member_model');
        $this->load->model('identity_model');
        $this->load->library('call1');
        $this->load->library('call2');

        // Checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    public function index(){
        $this->template->title = 'Coach Session';
        $date_from = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-1 month"));
        $histories = $this->appointment_history_model->get_session_histories('coach_id', $date_from, date('Y-m-d'), 1);

        $vars = array(
            'form_action' => 'search',
            'histories' => $histories,
            'coach_id' => $this->auth_manager->userid(),
            'user' => 'coach',
            'start_date' => date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-2 month"))
        );

        $this->template->content->view('default/contents/vrm/coach/index', $vars);
        $this->template->publish();
    }
    public function single_student(){
        $this->template->title = 'Student Progress Report';
        $data_dyned_pro = $this->identity_model->get_student_identity($this->auth_manager->userid());

        if($data_dyned_pro[0]->dyned_pro_id && $data_dyned_pro[0]->server_dyned_pro){
               //TODO
               //Get id server and email from Database
               //getting dyned pro id and server id
               //$data_student = $this->identity_model->get_identity('profile')->where('user_id', $this->auth_manager->userid())->select('dyned_pro_id, server_dyned_pro')->get();
               // echo "<pre>";print_r($data_dyned_pro[0]->dyned_pro_id);exit();
               $this->call2->init($data_dyned_pro[0]->server_dyned_pro, $data_dyned_pro[0]->dyned_pro_id);
               $this->call1->init($data_dyned_pro[0]->dyned_pro_id,'' , $data_dyned_pro[0]->server_dyned_pro);
               $callOneJson  = $this->call1->callOneJson();
               $student_vrm2 = $this->call2->getdataObj();

               // echo "<pre>";print_r($student_vrm2);exit();

               $checkCallOne   = @$callOneJson->studentName;
               $module_extract = @$callOneJson->lessonCompletion;
               if($checkCallOne){
                 if(@$module_extract){
                    // $placement_test = end(@$callOneJson->placementTestGENs);
                    // $pt_val         = @$placement_test->placementLevel;
                    $student_cert   = @$student_vrm2->cert_studying;
                    $certif_code = @$callOneJson->groupCertPlan;
                    if($certif_code == 1){
                        $student_type = 'Academic';
                    }else if($certif_code == 2){
                        $student_type = 'Academic+';
                    }else if($certif_code == 3){
                        $student_type = 'Professional';
                    }else if($certif_code == 4){
                        $student_type = 'Academic (Legacy)';
                    }else if($certif_code == 5){
                        $student_type = 'Professional (Legacy)';
                    }else if($certif_code == 6){
                        $student_type = 'Professional (Europe)';
                    }

                    // echo "<pre>";
                    // print_r($student_vrm2);
                    // exit();

                    // --------------------------------------------------------------
                    // NDE ----------------------------------------------------------
                    // --------------------------------------------------------------
                    if($module_extract){
                        foreach(@$module_extract as $me){
                            // $titleNde   = "NDE";
                            $titleNde   = "New Dynamic English";
                            $lessonCode = $me->lessonCode;
                            $percent    = $me->percent;

                            if($lessonCode == '010-1M0'){
                                $nde1    = "Module 1";
                                $lcnde1  = $lessonCode;
                                $pcnde1  = $percent;
                            }
                            if($lessonCode == '010-2M0'){
                                $nde2    = "Module 2";
                                $lcnde2  = $lessonCode;
                                $pcnde2  = $percent;
                            }
                            if($lessonCode == '010-3M0'){
                                $nde3    = "Module 3";
                                $lcnde3  = $lessonCode;
                                $pcnde3  = $percent;
                            }
                            if($lessonCode == '010-4M0'){
                                $nde4    = "Module 4";
                                $lcnde4  = $lessonCode;
                                $pcnde4  = $percent;
                            }
                            if($lessonCode == '010-5M0'){
                                $nde5    = "Module 5";
                                $lcnde5  = $lessonCode;
                                $pcnde5  = $percent;
                            }
                            if($lessonCode == '010-6M0'){
                                $nde6    = "Module 6";
                                $lcnde6  = $lessonCode;
                                $pcnde6  = $percent;
                            }
                            if($lessonCode == '010-7M0'){
                                $nde7    = "Module 7";
                                $lcnde7  = $lessonCode;
                                $pcnde7  = $percent;
                            }
                            if($lessonCode == '010-8M0'){
                                $nde8    = "Module 8";
                                $lcnde8  = $lessonCode;
                                $pcnde8  = $percent;
                            }
                        }
                    }
                    if(isset($nde1)){
                    $nde1;$pcnde1;
                    }else{$nde1 = 'Module 1';$pcnde1 = 0;}

                    if(isset($nde2)){
                    $nde2;$pcnde2;
                    }else{$nde2 = 'Module 2';$pcnde2 = 0;}

                    if(isset($nde3)){
                    $nde3;$pcnde3;
                    }else{$nde3 = 'Module 3';$pcnde3 = 0;}

                    if(isset($nde4)){
                    $nde4;$pcnde4;
                    }else{$nde4 = 'Module 4';$pcnde4 = 0;}

                    if(isset($nde5)){
                    $nde5;$pcnde5;
                    }else{$nde5 = 'Module 5';$pcnde5 = 0;}

                    if(isset($nde6)){
                    $nde6;$pcnde6;
                    }else{$nde6 = 'Module 6';$pcnde6 = 0;}

                    if(isset($nde7)){
                    $nde7;$pcnde7;
                    }else{$nde7 = 'Module 7';$pcnde7 = 0;}

                    if(isset($nde8)){
                    $nde8;$pcnde8;
                    }else{$nde8 = 'Module 8';$pcnde8 = 0;}


                    $nde = array(
                        'nde1' => $nde1, 'nde2' => $nde2, 'nde3' => $nde3, 'nde4' => $nde4,
                        'nde5' => $nde5, 'nde6' => $nde6, 'nde7' => $nde7, 'nde8' => $nde8,
                        'pcnde1' => $pcnde1, 'pcnde2' => $pcnde2, 'pcnde3' => $pcnde3, 'pcnde4' => $pcnde4,
                        'pcnde5' => $pcnde5, 'pcnde6' => $pcnde6, 'pcnde7' => $pcnde7, 'pcnde8' => $pcnde8,
                        'titleNde' => @$titleNde
                    );

                    // --------------------------------------------------------------
                    // NDE END ------------------------------------------------------
                    // --------------------------------------------------------------

                    // --------------------------------------------------------------
                    // FE START -----------------------------------------------------
                    // --------------------------------------------------------------
                    if($module_extract){
                        foreach(@$module_extract as $fe){
                            // $titleFe = "FE";
                            $titleFe = "First English";
                            $lessonCodeFe = $fe->lessonCode;
                            $percentFe    = $fe->percent;

                            if($lessonCodeFe == '027-1M0'){
                                $fe1    = "Unit 1";
                                $lcfe1  = $lessonCodeFe;
                                $pcfe1  = $percentFe;
                            }
                            if($lessonCodeFe == '027-2M0'){
                                $fe2    = "Unit 2";
                                $lcfe2  = $lessonCodeFe;
                                $pcfe2  = $percentFe;
                            }
                            if($lessonCodeFe == '027-3M0'){
                                $fe3    = "Unit 3";
                                $lcfe3  = $lessonCodeFe;
                                $pcfe3  = $percentFe;
                            }
                            if($lessonCodeFe == '027-4M0'){
                                $fe4    = "Unit 4";
                                $lcfe4  = $lessonCodeFe;
                                $pcfe4  = $percentFe;
                            }
                        }
                    }
                    if(isset($fe1)){
                    $fe1;$pcfe1;
                    }else{$fe1 = 'Unit 1';$pcfe1 = 0;}

                    if(isset($fe2)){
                    $fe2;$pcfe2;
                    }else{$fe2 = 'Unit 2';$pcfe2 = 0;}

                    if(isset($fe3)){
                    $fe3;$pcfe3;
                    }else{$fe3 = 'Unit 3';$pcfe3 = 0;}

                    if(isset($fe4)){
                    $fe4;$pcfe4;
                    }else{$fe4 = 'Unit 4';$pcfe4 = 0;}

                    $fe = array(
                        'fe1' => $fe1, 'fe2' => $fe2, 'fe3' => $fe3, 'fe4' => $fe4,
                        'pcfe1' => $pcfe1, 'pcfe2' => $pcfe2, 'pcfe3' => $pcfe3, 'pcfe4' => $pcfe4, 'titleFe' => @$titleFe
                    );
                    // --------------------------------------------------------------
                    // FE END -------------------------------------------------------
                    // --------------------------------------------------------------


                    // --------------------------------------------------------------
                    // Functioning In Business Start --------------------------------
                    // --------------------------------------------------------------
                    if($module_extract){
                        foreach($module_extract as $fib){
                            // $titleFib = "FIB";
                            $titleFib = "Functioning in Business";
                            $lessonCodeFib = $fib->lessonCode;
                            $percentFib    = $fib->percent;

                            if($lessonCodeFib == '038-1C1'){
                                $fib1    = "Intro";
                                $lcfib1  = $lessonCodeFib;
                                $pcfib1  = $percentFib;
                            }if($lessonCodeFib == '038-2CY'){
                                $fib2    = "Episode 1";
                                $lcfib2  = $lessonCodeFib;
                                $pcfib2  = $percentFib;
                            }if($lessonCodeFib == '038-3CX'){
                                $fib3    = "Episode 2";
                                $lcfib3  = $lessonCodeFib;
                                $pcfib3  = $percentFib;
                            }if($lessonCodeFib == '038-4CZ'){
                                $fib4    = "Episode 3";
                                $lcfib4  = $lessonCodeFib;
                                $pcfib4  = $percentFib;
                            }if($lessonCodeFib == '038-5CY'){
                                $fib5    = "Episode 4";
                                $lcfib5  = $lessonCodeFib;
                                $pcfib5  = $percentFib;
                            }if($lessonCodeFib == '038-6CZ'){
                                $fib6    = "Episode 5";
                                $lcfib6  = $lessonCodeFib;
                                $pcfib6  = $percentFib;
                            }if($lessonCodeFib == '038-7CY'){
                                $fib7    = "Episode 6";
                                $lcfib7  = $lessonCodeFib;
                                $pcfib7  = $percentFib;
                            }if($lessonCodeFib == '038-8CZ'){
                                $fib8    = "Episode 7";
                                $lcfib8  = $lessonCodeFib;
                                $pcfib8  = $percentFib;
                            }if($lessonCodeFib == '038-9CY'){
                                $fib9    = "Episode 8";
                                $lcfib9  = $lessonCodeFib;
                                $pcfib9  = $percentFib;
                            }if($lessonCodeFib == '038-ACX'){
                                $fib10    = "Episode 9";
                                $lcfib10  = $lessonCodeFib;
                                $pcfib10  = $percentFib;
                            }
                        }
                    }
                    if(isset($fib1)){
                    $fib1;$pcfib1;
                    }else{$fib1 = 'Intro';$pcfib1 = 0;}

                    if(isset($fib2)){
                    $fib2;$pcfib2;
                    }else{$fib2 = 'Episode 1';$pcfib2 = 0;}

                    if(isset($fib3)){
                    $fib3;$pcfib3;
                    }else{$fib3 = 'Episode 2';$pcfib3 = 0;}

                    if(isset($fib4)){
                    $fib4;$pcfib4;
                    }else{$fib4 = 'Episode 3';$pcfib4 = 0;}

                    if(isset($fib5)){
                    $fib5;$pcfib5;
                    }else{$fib5 = 'Episode 4';$pcfib5 = 0;}

                    if(isset($fib6)){
                    $fib6;$pcfib6;
                    }else{$fib6 = 'Episode 5';$pcfib6 = 0;}

                    if(isset($fib7)){
                    $fib7;$pcfib7;
                    }else{$fib7 = 'Episode 6';$pcfib7 = 0;}

                    if(isset($fib8)){
                    $fib8;$pcfib8;
                    }else{$fib8 = 'Episode 7';$pcfib8 = 0;}

                    if(isset($fib9)){
                    $fib9;$pcfib9;
                    }else{$fib9 = 'Episode 8';$pcfib9 = 0;}

                    if(isset($fib10)){
                    $fib10;$pcfib10;
                    }else{$fib10 = 'Episode 9';$pcfib10 = 0;}

                    $fib = array(
                        'fib1' => $fib1, 'fib2' => $fib2, 'fib3' => $fib3, 'fib4' => $fib4,
                        'fib5' => $fib5, 'fib6' => $fib6, 'fib7' => $fib7, 'fib8' => $fib8, 'fib9' => $fib9, 'fib10' => $fib10,
                        'pcfib1' => $pcfib1, 'pcfib2' => $pcfib2, 'pcfib3' => $pcfib3, 'pcfib4' => $pcfib4,
                        'pcfib5' => $pcfib5, 'pcfib6' => $pcfib6, 'pcfib7' => $pcfib7, 'pcfib8' => $pcfib8,
                        'pcfib9' => $pcfib9, 'pcfib10' => $pcfib10,
                        'titleFib' => @$titleFib
                    );

                    // --------------------------------------------------------------
                    // Functioning In Business END ----------------------------------
                    // --------------------------------------------------------------

                    // --------------------------------------------------------------
                    // The Lost Secret START ----------------------------------------
                    // --------------------------------------------------------------
                    if($module_extract){
                        foreach($module_extract as $tls){
                            // $titleTls      = "TLS";
                            $titleTls      = "The Lost Secret";
                            $lessonCodeTls = $tls->lessonCode;
                            $percentTls    = $tls->percent;

                            if($lessonCodeTls == '039-1CZ'){
                                $tls1    = "Episode 1&2";
                                $lctls1  = $lessonCodeTls;
                                $pctls1  = $percentTls;
                            }if($lessonCodeTls == '039-2CY'){
                                $tls2    = "Episode 3";
                                $lctls2  = $lessonCodeTls;
                                $pctls2  = $percentTls;
                            }if($lessonCodeTls == '039-3CX'){
                                $tls3    = "Episode 4";
                                $lctls3  = $lessonCodeTls;
                                $pctls3  = $percentTls;
                            }if($lessonCodeTls == '039-4CZ'){
                                $tls4    = "Episode 5";
                                $lctls4  = $lessonCodeTls;
                                $pctls4  = $percentTls;
                            }if($lessonCodeTls == '039-5CY'){
                                $tls5    = "Episode 6";
                                $lctls5  = $lessonCodeTls;
                                $pctls5  = $percentTls;
                            }if($lessonCodeTls == '039-6CZ'){
                                $tls6    = "Episode 7";
                                $lctls6  = $lessonCodeTls;
                                $pctls6  = $percentTls;
                            }if($lessonCodeTls == '039-7CY'){
                                $tls7    = "Episode 8";
                                $lctls7  = $lessonCodeTls;
                                $pctls7  = $percentTls;
                            }if($lessonCodeTls == '039-8CX'){
                                $tls8    = "Episode 9";
                                $lctls8  = $lessonCodeTls;
                                $pctls8  = $percentTls;
                            }if($lessonCodeTls == '039-8CX'){
                                $tls9    = "Episode 10";
                                $lctls9  = $lessonCodeTls;
                                $pctls9  = $percentTls;
                            }
                            if($lessonCodeTls == '039-8CX'){
                                $tls10    = "Episode 11";
                                $lctls10  = $lessonCodeTls;
                                $pctls10  = $percentTls;
                            }
                        }
                    }
                    if(isset($tls1)){
                    $tls1;$pctls1;
                    }else{$tls1 = 'Episode 1&2';$pctls1 = 0;}

                    if(isset($tls2)){
                    $tls2;$pctls2;
                    }else{$tls2 = 'Episode 3';$pctls2 = 0;}

                    if(isset($tls3)){
                    $tls3;$pctls3;
                    }else{$tls3 = 'Episode 4';$pctls3 = 0;}

                    if(isset($tls4)){
                    $tls4;$pctls4;
                    }else{$tls4 = 'Episode 5';$pctls4 = 0;}

                    if(isset($tls5)){
                    $tls5;$pctls5;
                    }else{$tls5 = 'Episode 6';$pctls5 = 0;}

                    if(isset($tls6)){
                    $tls6;$pctls6;
                    }else{$tls6 = 'Episode 7';$pctls6 = 0;}

                    if(isset($tls7)){
                    $tls7;$pctls7;
                    }else{$tls7 = 'Episode 8';$pctls7 = 0;}

                    if(isset($tls8)){
                    $tls8;$pctls8;
                    }else{$tls8 = 'Episode 9';$pctls8 = 0;}

                    if(isset($tls9)){
                    $tls9;$pctls9;
                    }else{$tls9 = 'Episode 10';$pctls9 = 0;}

                    if(isset($tls10)){
                    $tls10;$pctls10;
                    }else{$tls10 = 'Episode 11';$pctls10 = 0;}

                    $tls = array(
                        'tls1' => $tls1, 'tls2' => $tls2, 'tls3' => $tls3, 'tls4' => $tls4, 'tls5' => $tls5,
                        'tls6' => $tls6, 'tls7' => $tls7, 'tls8' => $tls8, 'tls9' => $tls9, 'tls10' => $tls10,
                        'pctls1' => $pctls1, 'pctls2' => $pctls2, 'pctls3' => $pctls3, 'pctls4' => $pctls4, 'pctls5' => $pctls5,
                        'pctls6' => $pctls6, 'pctls7' => $pctls7, 'pctls8' => $pctls8, 'pctls9' => $pctls9, 'pctls10' => $pctls10,
                        'titleTls' => @$titleTls
                    );

                    // --------------------------------------------------------------
                    // The Lost Secret END ------------------------------------------
                    // --------------------------------------------------------------


                    // --------------------------------------------------------------
                    // Dialogue START -----------------------------------------------
                    // --------------------------------------------------------------
                    if($module_extract){
                        foreach($module_extract as $dlg){
                            // $titleDlg      = "DLG";
                            $titleDlg      = "Dialogue";
                            $lessonCodeDlg = $dlg->lessonCode;
                            $percentDlg    = $dlg->percent;

                            if($lessonCodeDlg == '056-1M0'){
                                $dlg1    = "Unit 1";
                                $lcdlg1  = $lessonCodeDlg;
                                $pcdlg1  = $percentDlg;
                            }if($lessonCodeDlg == '056-2M0'){
                                $dlg2    = "Unit 2";
                                $lcdlg2  = $lessonCodeDlg;
                                $pcdlg2  = $percentDlg;
                            }if($lessonCodeDlg == '056-3M0'){
                                $dlg3    = "Unit 3";
                                $lcdlg3  = $lessonCodeDlg;
                                $pcdlg3  = $percentDlg;
                            }
                        }
                    }
                    if(isset($dlg1)){
                    $dlg1;$pcdlg1;
                    }else{$dlg1 = 'Unit 1';$pcdlg1 = 0;}

                    if(isset($dlg2)){
                    $dlg;$pcdlg2;
                    }else{$dlg2 = 'Unit 2';$pcdlg2 = 0;}

                    if(isset($dlg3)){
                    $dlg3;$pcdlg3;
                    }else{$dlg3 = 'Unit 3';$pcdlg3 = 0;}

                    $dlg = array(
                        'dlg1' => $dlg1, 'dlg2' => $dlg2, 'dlg3' => $dlg3,
                        'pcdlg1' => $pcdlg1, 'pcdlg2' => $pcdlg2, 'pcdlg3' => $pcdlg3,  'titleDlg' => @$titleDlg
                    );

                    // --------------------------------------------------------------
                    // Dialogue END -------------------------------------------------
                    // --------------------------------------------------------------

                    // --------------------------------------------------------------
                    // Dynamic Business English START -------------------------------
                    // --------------------------------------------------------------
                    if($module_extract){
                        foreach($module_extract as $dbe){
                            // $titleDbe      = "DBE";
                            $titleDbe      = "Dynamic Business English";
                            $lessonCodeDbe = $dbe->lessonCode;
                            $percentDbe    = $dbe->percent;

                            if($lessonCodeDlg == '059-1CZ'){
                                $dbe1    = "Unit 1";
                                $lcdbe1  = $lessonCodeDbe;
                                $pcdbe1  = $percentDbe;
                            }if($lessonCodeDlg == '059-2CZ'){
                                $dbe2    = "Unit 2";
                                $lcdbe2  = $lessonCodeDbe;
                                $pcdbe2  = $percentDbe;
                            }if($lessonCodeDlg == '059-3CZ'){
                                $dbe3    = "Unit 3";
                                $lcdbe3  = $lessonCodeDbe;
                                $pcdbe3  = $percentDbe;
                            }if($lessonCodeDlg == '059-4CZ'){
                                $dbe4    = "Unit 4";
                                $lcdbe4  = $lessonCodeDbe;
                                $pcdbe4  = $percentDbe;
                            }if($lessonCodeDlg == '059-5CZ'){
                                $dbe5    = "Unit 5";
                                $lcdbe5  = $lessonCodeDbe;
                                $pcdbe5  = $percentDbe;
                            }if($lessonCodeDlg == '059-6CZ'){
                                $dbe6    = "Unit 6";
                                $lcdbe6  = $lessonCodeDbe;
                                $pcdbe6  = $percentDbe;
                            }
                        }
                    }
                    if(isset($dbe1)){
                    $dbe1;$pcdbe1;
                    }else{$dbe1 = 'Unit 1';$pcdbe1 = 0;}

                    if(isset($dbe2)){
                    $dbe2;$pcdbe2;
                    }else{$dbe2 = 'Unit 2';$pcdbe2 = 0;}

                    if(isset($dbe3)){
                    $dbe3;$pcdbe3;
                    }else{$dbe3 = 'Unit 3';$pcdbe3 = 0;}

                    if(isset($dbe4)){
                    $dbe4;$pcdbe4;
                    }else{$dbe4 = 'Unit 4';$pcdbe4 = 0;}

                    if(isset($dbe5)){
                    $dbe5;$pcdbe5;
                    }else{$dbe5 = 'Unit 5';$pcdbe5 = 0;}

                    if(isset($dbe6)){
                    $dbe6;$pcdbe6;
                    }else{$dbe6 = 'Unit 6';$pcdbe6 = 0;}

                    $dbe = array(
                        'dbe1' => $dbe1, 'dbe2' => $dbe2, 'dbe3' => $dbe3, 'dbe4' => $dbe4, 'dbe5' => $dbe5, 'dbe6' => $dbe6,
                        'pcdbe1' => $pcdbe1, 'pcdbe2' => $pcdbe2, 'pcdbe3' => $pcdbe3, 'pcdbe4' => $pcdbe4, 'pcdbe5' => $pcdbe5,
                        'pcdbe6' => $pcdbe6,
                        'titleDbe' => @$titleDbe
                    );

                    // --------------------------------------------------------------
                    // Dynamic Business English END ---------------------------------
                    // --------------------------------------------------------------

                    // --------------------------------------------------------------
                    // Advance Listening START --------------------------------------
                    // --------------------------------------------------------------
                    if($module_extract){
                        foreach($module_extract as $als){
                            // $titleAls      = "ALG";
                            $titleAls      = "Advanced Listening";
                            $lessonCodeAls = $als->lessonCode;
                            $percentAls    = $als->percent;

                            if($lessonCodeAls == '062-1M0'){
                                $als1    = "Unit 1";
                                $lcals1  = $lessonCodeAls;
                                $pcals1  = $percentAls;
                            }if($lessonCodeAls == '062-7M0'){
                                $als2    = "Unit 2";
                                $lcals2  = $lessonCodeAls;
                                $pcals2  = $percentAls;
                            }if($lessonCodeAls == '062-9M0'){
                                $als3    = "Unit 3";
                                $lcals3  = $lessonCodeAls;
                                $pcals3  = $percentAls;
                            }if($lessonCodeAls == '062-CM0'){
                                $als4    = "Unit 4";
                                $lcals4  = $lessonCodeAls;
                                $pcals4  = $percentAls;
                            }if($lessonCodeAls == '062-JM0'){
                                $als5    = "Unit 5";
                                $lcals5  = $lessonCodeAls;
                                $pcals5  = $percentAls;
                            }

                            if($lessonCodeAls == '062-NM0'){
                                $als6    = "Unit 6";
                                $lcals6  = $lessonCodeAls;
                                $pcals6  = $percentAls;
                            }if($lessonCodeAls == '062-PM0'){
                                $als7    = "Unit 7";
                                $lcals7  = $lessonCodeAls;
                                $pcals7  = $percentAls;
                            }if($lessonCodeAls == '062-UM0'){
                                $als8    = "Unit 8";
                                $lcals8  = $lessonCodeAls;
                                $pcals8  = $percentAls;
                            }if($lessonCodeAls == '062-WM0'){
                                $als9    = "Unit 9";
                                $lcals9  = $lessonCodeAls;
                                $pcals9  = $percentAls;
                            }
                        }
                    }
                    if(isset($als1)){
                    $als1;$pcals1;
                    }else{$als1 = 'Unit 1';$pcals1 = 0;}
                    if(isset($als2)){
                    $als2;$pcals2;
                    }else{$als2 = 'Unit 2';$pcals2 = 0;}
                    if(isset($als3)){
                    $als3;$pcals3;
                    }else{$als3 = 'Unit 3';$pcals3 = 0;}
                    if(isset($als4)){
                    $als4;$pcals4;
                    }else{$als4 = 'Unit 4';$pcals4 = 0;}
                    if(isset($als5)){
                    $als5;$pcals5;
                    }else{$als5 = 'Unit 5';$pcals5 = 0;}
                    if(isset($als6)){
                    $als6;$pcals6;
                    }else{$als6 = 'Unit 6';$pcals6 = 0;}
                    if(isset($als7)){
                    $als7;$pcals7;
                    }else{$als7 = 'Unit 7';$pcals7 = 0;}
                    if(isset($als8)){
                    $als8;$pcals8;
                    }else{$als8 = 'Unit 8';$pcals8 = 0;}
                    if(isset($als9)){
                    $als9;$pcals9;
                    }else{$als9 = 'Unit 9';$pcals9 = 0;}

                    $als = array(
                        'als1' => $als1, 'als2' => $als2, 'als3' => $als3, 'als4' => $als4, 'als5' => $als5, 'als6' => $als6,
                        'als7' => $als7, 'als8' => $als8, 'als9' => $als9,
                        'pcals1' => $pcals1, 'pcals2' => $pcals2, 'pcals3' => $pcals3, 'pcals4' => $pcals4, 'pcals5' => $pcals5,
                        'pcals6' => $pcals6, 'pcals7' => $pcals7, 'pcals8' => $pcals8, 'pcals9' => $pcals9,
                        'titleAls' => @$titleAls
                    );

                    // --------------------------------------------------------------
                    // Advance Listening END ----------------------------------------
                    // --------------------------------------------------------------

                    // --------------------------------------------------------------
                    // English For Success START ------------------------------------
                    // --------------------------------------------------------------
                    if($module_extract){
                        foreach($module_extract as $efs){
                            // $titleEfs      = "EFS";
                            $titleEfs      = "English For Success";
                            $lessonCodeEfs = $efs->lessonCode;
                            $percentEfs    = $efs->percent;

                            if($lessonCodeEfs == '063-1M0'){
                                $efs1    = "Unit 1";
                                $lcefs1  = $lessonCodeEfs;
                                $pcefs1  = $percentEfs;
                            }if($lessonCodeEfs == '063-2M0'){
                                $efs2    = "Unit 2";
                                $lcefs2  = $lessonCodeEfs;
                                $pcefs2  = $percentEfs;
                            }if($lessonCodeEfs == '063-3M0'){
                                $efs3    = "Unit 3";
                                $lcefs3  = $lessonCodeEfs;
                                $pcefs3  = $percentEfs;
                            }if($lessonCodeEfs == '063-4M0'){
                                $efs4    = "Unit 4";
                                $lcefs4  = $lessonCodeEfs;
                                $pcefs4  = $percentEfs;
                            }if($lessonCodeEfs == '063-5M0'){
                                $efs5    = "Unit 5";
                                $lcefs5  = $lessonCodeEfs;
                                $pcefs5  = $percentEfs;
                            }


                            if($lessonCodeEfs == '063-6M0'){
                                $efs6    = "Unit 6";
                                $lcefs6  = $lessonCodeEfs;
                                $pcefs6  = $percentEfs;
                            }if($lessonCodeEfs == '063-7M0'){
                                $efs7    = "Unit 7";
                                $lcefs7  = $lessonCodeEfs;
                                $pcefs7  = $percentEfs;
                            }if($lessonCodeEfs == '063-8M0'){
                                $efs8    = "Unit 8";
                                $lcefs8  = $lessonCodeEfs;
                                $pcefs8  = $percentEfs;
                            }if($lessonCodeEfs == '063-9M0'){
                                $efs9    = "Unit 9";
                                $lcefs9  = $lessonCodeEfs;
                                $pcefs9  = $percentEfs;
                            }if($lessonCodeEfs == '063-AM0'){
                                $efs10    = "Unit 10";
                                $lcefs10  = $lessonCodeEfs;
                                $pcefs10  = $percentEfs;
                            }


                            if($lessonCodeEfs == '063-BM0'){
                                $efs11    = "Unit 11";
                                $lcefs11  = $lessonCodeEfs;
                                $pcefs11  = $percentEfs;
                            }if($lessonCodeEfs == '063-CM0'){
                                $efs12    = "Unit 12";
                                $lcefs12  = $lessonCodeEfs;
                                $pcefs12  = $percentEfs;
                            }if($lessonCodeEfs == '063-DM0'){
                                $efs13    = "Unit 13";
                                $lcefs13  = $lessonCodeEfs;
                                $pcefs13  = $percentEfs;
                            }if($lessonCodeEfs == '063-EM0'){
                                $efs14    = "Unit 14";
                                $lcefs14  = $lessonCodeEfs;
                                $pcefs14  = $percentEfs;
                            }if($lessonCodeEfs == '063-FM0'){
                                $efs15    = "Unit 15";
                                $lcefs15  = $lessonCodeEfs;
                                $pcefs15  = $percentEfs;
                            }


                            if($lessonCodeEfs == '063-GM0'){
                                $efs16    = "Unit 16";
                                $lcefs16  = $lessonCodeEfs;
                                $pcefs16  = $percentEfs;
                            }if($lessonCodeEfs == '063-HM0'){
                                $efs17    = "Unit 17";
                                $lcefs17  = $lessonCodeEfs;
                                $pcefs17  = $percentEfs;
                            }if($lessonCodeEfs == '063-IM0'){
                                $efs18    = "Unit 18";
                                $lcefs18  = $lessonCodeEfs;
                                $pcefs18  = $percentEfs;
                            }if($lessonCodeEfs == '063-JM0'){
                                $efs19    = "Unit 19";
                                $lcefs19  = $lessonCodeEfs;
                                $pcefs19  = $percentEfs;
                            }if($lessonCodeEfs == '063-KM0'){
                                $efs20    = "Unit 20";
                                $lcefs20  = $lessonCodeEfs;
                                $pcefs20  = $percentEfs;
                            }
                        }
                    }
                    if(isset($efs1)){
                    $efs1;$pcefs1;
                    }else{$efs1 = 'Unit 1';$pcefs1 = 0;}
                    if(isset($efs2)){
                    $efs2;$pcefs2;
                    }else{$efs2 = 'Unit 2';$pcefs2 = 0;}
                    if(isset($efs3)){
                    $efs3;$pcefs3;
                    }else{$efs3 = 'Unit 3';$pcefs3 = 0;}
                    if(isset($efs4)){
                    $efs4;$pcefs4;
                    }else{$efs4 = 'Unit 4';$pcefs4 = 0;}
                    if(isset($efs5)){
                    $efs5;$pcefs5;
                    }else{$efs5 = 'Unit 5';$pcefs5 = 0;}


                    if(isset($efs6)){
                    $efs6;$pcefs6;
                    }else{$efs6 = 'Unit 6';$pcefs6 = 0;}
                    if(isset($efs7)){
                    $efs7;$pcefs7;
                    }else{$efs7 = 'Unit 7';$pcefs7 = 0;}
                    if(isset($efs8)){
                    $efs8;$pcefs8;
                    }else{$efs8 = 'Unit 8';$pcefs8 = 0;}
                    if(isset($efs9)){
                    $efs9;$pcefs9;
                    }else{$efs9 = 'Unit 9';$pcefs9 = 0;}
                    if(isset($efs10)){
                    $efs2;$pcefs10;
                    }else{$efs10 = 'Unit 10';$pcefs10 = 0;}


                    if(isset($efs11)){
                    $efs11;$pcefs11;
                    }else{$efs11 = 'Unit 11';$pcefs11 = 0;}
                    if(isset($efs12)){
                    $efs12;$pcefs12;
                    }else{$efs12 = 'Unit 12';$pcefs12 = 0;}
                    if(isset($efs13)){
                    $efs13;$pcefs13;
                    }else{$efs13 = 'Unit 13';$pcefs13 = 0;}
                    if(isset($efs14)){
                    $efs14;$pcefs14;
                    }else{$efs14 = 'Unit 14';$pcefs14 = 0;}
                    if(isset($efs15)){
                    $efs15;$pcefs15;
                    }else{$efs15 = 'Unit 15';$pcefs15 = 0;}


                    if(isset($efs16)){
                    $efs16;$pcefs16;
                    }else{$efs16 = 'Unit 16';$pcefs16 = 0;}
                    if(isset($efs17)){
                    $efs17;$pcefs17;
                    }else{$efs17 = 'Unit 17';$pcefs17 = 0;}
                    if(isset($efs18)){
                    $efs18;$pcefs18;
                    }else{$efs18 = 'Unit 18';$pcefs18 = 0;}
                    if(isset($efs19)){
                    $efs19;$pcefs19;
                    }else{$efs19 = 'Unit 19';$pcefs19 = 0;}
                    if(isset($efs20)){
                    $efs20;$pcefs20;
                    }else{$efs20 = 'Unit 20';$pcefs20 = 0;}

                    $efs = array(
                        'efs1' => $efs1, 'efs2' => $efs2, 'efs3' => $efs3, 'efs4' => $efs4, 'efs5' => $efs5, 'efs6' => $efs6, 'efs7' => $efs7,
                        'efs8' => $efs8, 'efs9' => $efs9, 'efs10' => $efs10, 'efs11' => $efs11, 'efs12' => $efs12, 'efs13' => $efs13,
                        'efs14' => $efs14, 'efs15' => $efs15, 'efs16' => $efs16, 'efs17' => $efs17, 'efs18' => $efs18, 'efs19' => $efs19,
                        'efs20' => $efs20, 'pcefs1' => $pcefs1, 'pcefs2' => $pcefs2, 'pcefs3' => $pcefs3, 'pcefs4' => $pcefs4, 'pcefs5' => $pcefs5,
                        'pcefs6' => $pcefs6, 'pcefs7' => $pcefs7, 'pcefs8' => $pcefs8, 'pcefs9' => $pcefs9, 'pcefs10' => $pcefs10,
                        'pcefs11' => $pcefs11, 'pcefs12' => $pcefs12, 'pcefs13' => $pcefs13, 'pcefs14' => $pcefs14, 'pcefs15' => $pcefs15,
                        'pcefs16' => $pcefs16, 'pcefs17' => $pcefs17, 'pcefs18' => $pcefs18, 'pcefs19' => $pcefs19, 'pcefs20' => $pcefs20,
                        'titleEfs' => @$titleEfs
                    );

                    // --------------------------------------------------------------
                    // English For Success END --------------------------------------
                    // --------------------------------------------------------------


                    // --------------------------------------------------------------
                    // Reading For Success START ------------------------------------
                    // --------------------------------------------------------------
                    if($module_extract){
                        foreach($module_extract as $rfs){
                            // $titleRfs      = "RFS";
                            $titleRfs      = "Reading For Success";
                            $lessonCodeRfs = $rfs->lessonCode;
                            $percentRfs    = $rfs->percent;

                            if($lessonCodeRfs == '073-1M0'){
                                $rfs1    = "Unit 1";
                                $lcrfs1  = $lessonCodeRfs;
                                $pcrfs1  = $percentRfs;
                            }if($lessonCodeRfs == '073-2M0'){
                                $rfs2    = "Unit 2";
                                $lcrfs2  = $lessonCodeRfs;
                                $pcrfs2  = $percentRfs;
                            }if($lessonCodeRfs == '073-3M0'){
                                $rfs3    = "Unit 3";
                                $lcrfs3  = $lessonCodeRfs;
                                $pcrfs3  = $percentRfs;
                            }if($lessonCodeRfs == '073-4M0'){
                                $rfs4    = "Unit 4";
                                $lcrfs4  = $lessonCodeRfs;
                                $pcrfs4  = $percentRfs;
                            }if($lessonCodeRfs == '073-5M0'){
                                $rfs5    = "Unit 5";
                                $lcrfs5  = $lessonCodeRfs;
                                $pcrfs5  = $percentRfs;
                            }


                            if($lessonCodeRfs == '073-6M0'){
                                $rfs6    = "Unit 6";
                                $lcrfs6  = $lessonCodeRfs;
                                $pcrfs6  = $percentRfs;
                            }if($lessonCodeRfs == '073-7M0'){
                                $rfs7    = "Unit 7";
                                $lcrfs7  = $lessonCodeRfs;
                                $pcrfs7  = $percentRfs;
                            }if($lessonCodeRfs == '073-8M0'){
                                $rfs8    = "Unit 8";
                                $lcrfs8  = $lessonCodeRfs;
                                $pcrfs8  = $percentRfs;
                            }if($lessonCodeRfs == '073-9M0'){
                                $rfs9    = "Unit 9";
                                $lcrfs9  = $lessonCodeRfs;
                                $pcrfs9  = $percentRfs;
                            }if($lessonCodeRfs == '073-AM0'){
                                $rfs10    = "Unit 10";
                                $lcrfs10  = $lessonCodeRfs;
                                $pcrfs10  = $percentRfs;
                            }


                            if($lessonCodeRfs == '073-BM0'){
                                $rfs11    = "Unit 11";
                                $lcrfs11  = $lessonCodeRfs;
                                $pcrfs11  = $percentRfs;
                            }if($lessonCodeRfs == '073-CM0'){
                                $rfs12    = "Unit 12";
                                $lcrfs12  = $lessonCodeRfs;
                                $pcrfs12  = $percentRfs;
                            }if($lessonCodeRfs == '073-DM0'){
                                $rfs13    = "Unit 13";
                                $lcrfs13  = $lessonCodeRfs;
                                $pcrfs13  = $percentRfs;
                            }if($lessonCodeRfs == '073-EM0'){
                                $rfs14    = "Unit 14";
                                $lcrfs14  = $lessonCodeRfs;
                                $pcrfs14  = $percentRfs;
                            }if($lessonCodeRfs == '073-FM0'){
                                $rfs15    = "Unit 15";
                                $lcrfs15  = $lessonCodeRfs;
                                $pcrfs15  = $percentRfs;
                            }


                            if($lessonCodeRfs == '073-GM0'){
                                $rfs16    = "Unit 16";
                                $lcrfs16  = $lessonCodeRfs;
                                $pcrfs16  = $percentRfs;
                            }if($lessonCodeRfs == '073-HM0'){
                                $rfs17    = "Unit 17";
                                $lcrfs17  = $lessonCodeRfs;
                                $pcrfs17  = $percentRfs;
                            }if($lessonCodeRfs == '073-IM0'){
                                $rfs18    = "Unit 18";
                                $lcrfs18  = $lessonCodeRfs;
                                $pcrfs18  = $percentRfs;
                            }if($lessonCodeRfs == '073-JM0'){
                                $rfs19    = "Unit 19";
                                $lcrfs19  = $lessonCodeRfs;
                                $pcrfs19  = $percentRfs;
                            }if($lessonCodeRfs == '073-KM0'){
                                $rfs20    = "Unit 20";
                                $lcrfs20  = $lessonCodeRfs;
                                $pcrfs20  = $percentRfs;
                            }
                        }
                    }
                    if(isset($rfs1)){
                    $rfs1;$pcrfs1;
                    }else{$rfs1 = 'Unit 1';$pcrfs1 = 0;}
                    if(isset($rfs2)){
                    $rfs2;$pcrfs2;
                    }else{$rfs2 = 'Unit 2';$pcrfs2 = 0;}
                    if(isset($rfs3)){
                    $rfs3;$pcrfs3;
                    }else{$rfs3 = 'Unit 3';$pcrfs3 = 0;}
                    if(isset($rfs4)){
                    $rfs4;$pcrfs4;
                    }else{$rfs4 = 'Unit 4';$pcrfs4 = 0;}
                    if(isset($rfs5)){
                    $rfs5;$pcrfs5;
                    }else{$rfs5 = 'Unit 5';$pcrfs5 = 0;}


                    if(isset($rfs6)){
                    $rfs6;$pcrfs6;
                    }else{$rfs6 = 'Unit 6';$pcrfs6 = 0;}
                    if(isset($rfs7)){
                    $rfs7;$pcrfs7;
                    }else{$rfs7 = 'Unit 7';$pcrfs7 = 0;}
                    if(isset($rfs8)){
                    $rfs8;$pcrfs8;
                    }else{$rfs8 = 'Unit 8';$pcrfs8 = 0;}
                    if(isset($rfs9)){
                    $rfs9;$pcrfs9;
                    }else{$rfs9 = 'Unit 9';$pcrfs9 = 0;}
                    if(isset($rfs10)){
                    $rfs2;$pcrfs10;
                    }else{$rfs10 = 'Unit 10';$pcrfs10 = 0;}


                    if(isset($rfs11)){
                    $rfs11;$pcrfs11;
                    }else{$rfs11 = 'Unit 11';$pcrfs11 = 0;}
                    if(isset($rfs12)){
                    $rfs12;$pcrfs12;
                    }else{$rfs12 = 'Unit 12';$pcrfs12 = 0;}
                    if(isset($rfs13)){
                    $rfs13;$pcrfs13;
                    }else{$rfs13 = 'Unit 13';$pcrfs13 = 0;}
                    if(isset($rfs14)){
                    $rfs14;$pcrfs14;
                    }else{$rfs14 = 'Unit 14';$pcrfs14 = 0;}
                    if(isset($rfs15)){
                    $rfs15;$pcrfs15;
                    }else{$rfs15 = 'Unit 15';$pcrfs15 = 0;}


                    if(isset($rfs16)){
                    $rfs16;$pcrfs16;
                    }else{$rfs16 = 'Unit 16';$pcrfs16 = 0;}
                    if(isset($rfs17)){
                    $rfs17;$pcrfs17;
                    }else{$rfs17 = 'Unit 17';$pcrfs17 = 0;}
                    if(isset($rfs18)){
                    $rfs18;$pcrfs18;
                    }else{$rfs18 = 'Unit 18';$pcrfs18 = 0;}
                    if(isset($rfs19)){
                    $rfs19;$pcrfs19;
                    }else{$rfs19 = 'Unit 19';$pcrfs19 = 0;}
                    if(isset($rfs20)){
                    $rfs20;$pcrfs20;
                    }else{$rfs20 = 'Unit 20';$pcrfs20 = 0;}

                    $rfs = array(
                        'rfs1' => $rfs1, 'rfs2' => $rfs2, 'rfs3' => $rfs3, 'rfs4' => $rfs4, 'rfs5' => $rfs5, 'rfs6' => $rfs6, 'rfs7' => $rfs7,
                        'rfs8' => $rfs8, 'rfs9' => $rfs9, 'rfs10' => $rfs10, 'rfs11' => $rfs11, 'rfs12' => $rfs12, 'rfs13' => $rfs13,
                        'rfs14' => $rfs14, 'rfs15' => $rfs15, 'rfs16' => $rfs16, 'rfs17' => $rfs17, 'rfs18' => $rfs18, 'rfs19' => $rfs19,
                        'rfs20' => $rfs20, 'pcrfs1' => $pcrfs1, 'pcrfs2' => $pcrfs2, 'pcrfs3' => $pcrfs3, 'pcrfs4' => $pcrfs4, 'pcrfs5' => $pcrfs5,
                        'pcrfs6' => $pcrfs6, 'pcrfs7' => $pcrfs7, 'pcrfs8' => $pcrfs8, 'pcrfs9' => $pcrfs9, 'pcrfs10' => $pcrfs10,
                        'pcrfs11' => $pcrfs11, 'pcrfs12' => $pcrfs12, 'pcrfs13' => $pcrfs13, 'pcrfs14' => $pcrfs14, 'pcrfs15' => $pcrfs15,
                        'pcrfs16' => $pcrfs16, 'pcrfs17' => $pcrfs17, 'pcrfs18' => $pcrfs18, 'pcrfs19' => $pcrfs19, 'pcrfs20' => $pcrfs20,
                        'titleRfs' => @$titleRfs
                    );

                    // --------------------------------------------------------------
                    // Reading For Success END --------------------------------------
                    // --------------------------------------------------------------

                    // --------------------------------------------------------------
                    // English By The Numbers START ---------------------------------
                    // --------------------------------------------------------------
                    if($module_extract){
                        foreach($module_extract as $ebn){
                            // $titleEbn   = "EBTN";
                            $titleEbn   = "English by the Numbers";
                            $lessonCodeEbn = $me->lessonCode;
                            $percentEbn    = $me->percent;

                            if($lessonCodeEbn == '034-1C1'){
                                $ebn1    = "Unit 1";
                                $lcebn1  = $lessonCodeEbn;
                                $pcebn1  = $percentEbn;
                            }
                            if($lessonCodeEbn == '034-1C2'){
                                $ebn2    = "Unit 2";
                                $lcebn2  = $lessonCodeEbn;
                                $pcebn2  = $percentEbn;
                            }
                            if($lessonCodeEbn == '034-1C3'){
                                $ebn3    = "Unit 3";
                                $lcebn3  = $lessonCodeEbn;
                                $pcebn3  = $percentEbn;
                            }
                            if($lessonCodeEbn == '034-1C4'){
                                $ebn4    = "Unit 4";
                                $lcebn4  = $lessonCodeEbn;
                                $pcebn4  = $percentEbn;
                            }
                            if($lessonCodeEbn == '034-1C5'){
                                $ebn5    = "Unit 5";
                                $lcebn5  = $lessonCodeEbn;
                                $pcebn5  = $percentEbn;
                            }
                            if($lessonCodeEbn == '034-1C7'){
                                $ebn6    = "Unit 6";
                                $lcebn6  = $lessonCodeEbn;
                                $pcebn6  = $percentEbn;
                            }
                            if($lessonCodeEbn == '034-1C6'){
                                $ebn7    = "Unit 7";
                                $lcebn7  = $lessonCodeEbn;
                                $pcebn7  = $percentEbn;
                            }
                        }
                    }
                    if(isset($ebn1)){
                    $ebn1;$pcebn1;
                    }else{$ebn1 = 'Unit 1';$pcebn1 = 0;}

                    if(isset($ebn2)){
                    $ebn2;$pcebn2;
                    }else{$ebn2 = 'Unit 2';$pcebn2 = 0;}

                    if(isset($ebn3)){
                    $ebn3;$pcebn3;
                    }else{$ebn3 = 'Unit 3';$pcebn3 = 0;}

                    if(isset($ebn4)){
                    $ebn4;$pcebn4;
                    }else{$ebn4 = 'Unit 4';$pcebn4 = 0;}

                    if(isset($ebn5)){
                    $ebn5;$pcebn5;
                    }else{$ebn5 = 'Unit 5';$pcebn5 = 0;}

                    if(isset($ebn6)){
                    $ebn6;$pcebn6;
                    }else{$ebn6 = 'Unit 6';$pcebn6 = 0;}

                    if(isset($ebn7)){
                    $ebn7;$pcebn7;
                    }else{$ebn7 = 'Unit 7';$pcebn7 = 0;}

                    $ebn = array(
                        'ebn1' => $ebn1, 'ebn2' => $ebn2, 'ebn3' => $ebn3, 'ebn4' => $ebn4,
                        'ebn5' => $ebn5, 'ebn6' => $ebn6, 'ebn7' => $ebn7,
                        'pcebn1' => $pcebn1, 'pcebn2' => $pcebn2, 'pcebn3' => $pcebn3, 'pcebn4' => $pcebn4,
                        'pcebn5' => $pcebn5, 'pcebn6' => $pcebn6, 'pcebn7' => $pcebn7,
                        'titleEbn' => @$titleEbn
                    );

                    // --------------------------------------------------------------
                    // English By The Numbers START ---------------------------------
                    // --------------------------------------------------------------

                        $ndeu1 = array_slice($nde, 0, 1);
                        $ndep1 = array_slice($nde, 8, 1);
                        $ndet1 = array_slice($nde, 16);

                        $allmodule1 = array(
                            'fe1' => $fe,
                            'nde1' => array_merge($ndeu1, $ndep1, $ndet1)
                          );

                        $ndeu2 = array_slice($nde, 1, 2);
                        $ndep2 = array_slice($nde, 9, 2);
                        $ndet2 = array_slice($nde, 16);

                        $tlsu2 = array_slice($tls, 0, 3);
                        $tlsp2 = array_slice($tls, 10, 3);
                        $tlst2 = array_slice($tls, 20);

                        $allmodule2 = array(
                            'nde2' => array_merge($ndeu2, $ndep2, $ndet2),
                            'tls2' => array_merge($tlsu2, $tlsp2, $tlst2)
                          );

                        $ndeu3 = array_slice($nde, 3, 1);
                        $ndep3 = array_slice($nde, 11, 1);
                        $ndet3 = array_slice($nde, 16);

                        $dbeu3 = array_slice($dbe, 0, 1);
                        $dbep3 = array_slice($dbe, 6, 1);
                        $dbet3 = array_slice($dbe, 12);

                        $tlsu3 = array_slice($tls, 3, 7);
                        $tlsp3 = array_slice($tls, 13, 7);
                        $tlst3 = array_slice($tls, 16);

                        $ebnu3 = array_slice($ebn, 0, 2);
                        $ebnp3 = array_slice($ebn, 7, 2);
                        $ebnt3 = array_slice($ebn, 14);

                        $allmodule3 = array(
                            'nde3' => array_merge($ndeu3, $ndep3, $ndet3),
                            'dbe3' => array_merge($dbeu3, $dbep3, $dbet3),
                            'tls3' => array_merge($tlsu3, $tlsp3, $tlst3),
                            'ebn3' => array_merge($ebnu3, $ebnp3, $ebnt3)
                          );

                        $ndeu4 = array_slice($nde, 4, 2);
                        $ndep4 = array_slice($nde, 12, 2);
                        $ndet4 = array_slice($nde, 16);

                        $dbeu4 = array_slice($dbe, 1, 2);
                        $dbep4 = array_slice($dbe, 7, 2);
                        $dbet4 = array_slice($dbe, 12);

                        $fibu4 = array_slice($fib, 0, 2);
                        $fibp4 = array_slice($fib, 10, 2);
                        $fibt4 = array_slice($fib, 20);

                        $ebnu4 = array_slice($ebn, 2, 2);
                        $ebnp4 = array_slice($ebn, 9, 2);
                        $ebnt4 = array_slice($ebn, 14);

                        $allmodule4 = array(
                            'nde4' => array_merge($ndeu4, $ndep4, $ndet4),
                            'dbe4' => array_merge($dbeu4, $dbep4, $dbet4),
                            'fib4' => array_merge($fibu4, $fibp4, $fibt4),
                            'ebn4' => array_merge($ebnu4, $ebnp4, $ebnt4)
                          );

                        $ndeu5 = array_slice($nde, 6, 1);
                        $ndep5 = array_slice($nde, 14, 1);
                        $ndet5 = array_slice($nde, 16);

                        $dbeu5 = array_slice($dbe, 3, 2);
                        $dbep5 = array_slice($dbe, 9, 2);
                        $dbet5 = array_slice($dbe, 12);

                        $fibu5 = array_slice($fib, 2, 8);
                        $fibp5 = array_slice($fib, 12, 8);
                        $fibt5 = array_slice($fib, 20);

                        $ebnu5 = array_slice($ebn, 4, 2);
                        $ebnp5 = array_slice($ebn, 11, 2);
                        $ebnt5 = array_slice($ebn, 14);

                        $alsu5 = array_slice($als, 0, 3);
                        $alsp5 = array_slice($als, 9, 3);
                        $alst5 = array_slice($als, 18);

                        $dlgu5 = array_slice($dlg, 0, 1);
                        $dlgp5 = array_slice($dlg, 3, 1);
                        $dlgt5 = array_slice($dlg, 6);

                        $allmodule5 = array(
                            'nde5' => array_merge($ndeu5, $ndep5, $ndet5),
                            'dbe5' => array_merge($dbeu5, $dbep5, $dbet5),
                            'fib5' => array_merge($fibu5, $fibp5, $fibt5),
                            'ebn5' => array_merge($ebnu5, $ebnp5, $ebnt5),
                            'dlg5' => array_merge($dlgu5, $dlgp5, $dlgt5),
                            'als5' => array_merge($alsu5, $alsp5, $alst5)
                          );

                        $ndeu6 = array_slice($nde, 7, 1);
                        $ndep6 = array_slice($nde, 15, 1);
                        $ndet6 = array_slice($nde, 16);

                        $dbeu6 = array_slice($dbe, 5, 1);
                        $dbep6 = array_slice($dbe, 11, 2);
                        $dbet6 = array_slice($dbe, 12);

                        $dlgu6 = array_slice($dlg, 1, 2);
                        $dlgp6 = array_slice($dlg, 4, 2);
                        $dlgt6 = array_slice($dlg, 6);

                        $ebnu6 = array_slice($ebn, 6, 1);
                        $ebnp6 = array_slice($ebn, 13, 1);
                        $ebnt6 = array_slice($ebn, 14);

                        $alsu6 = array_slice($als, 3, 6);
                        $alsp6 = array_slice($als, 12, 6);
                        $alst6 = array_slice($als, 18);

                        $allmodule6 = array(
                            'nde6' => array_merge($ndeu6, $ndep6, $ndet6),
                            'dbe6' => array_merge($dbeu6, $dbep6, $dbet6),
                            'dlg6' => array_merge($dlgu6, $dlgp6, $dlgt6),
                            'ebn6' => array_merge($ebnu6, $ebnp6, $ebnt6),
                            'als6' => array_merge($alsu6, $alsp6, $alst6)
                          );
                }
              }

               // $cchnote = $this->db->select('*')
               //            ->from('appointments')
               //            ->where('student_id', $student_id)
               //            ->where('status', 'completed')
               //            ->order_by('id','desc')
               //            ->get()->result();


               $vars = array(
                    'student_fullname' => $data_dyned_pro[0]->fullname,
                    'student_id' => $data_dyned_pro[0]->id,
                    'student_vrm' => $this->call2->getDataJson(),
                    'student_vrm2'     => $student_vrm2,
                    'cchnote'     => @$cchnote,
                    'student_cert' => @$student_cert,
                    'allmodule1'   => @$allmodule1,
                    'allmodule2'   => @$allmodule2,
                    'allmodule3'   => @$allmodule3,
                    'allmodule4'   => @$allmodule4,
                    'allmodule5'   => @$allmodule5,
                    'allmodule6'   => @$allmodule6
                );

               // echo "<pre>";print_r($vars);exit();

                $this->template->content->view('default/contents/vrm/student/index', $vars);
                $this->template->publish();

        }
        else{
            $vars = array(
                'server_code' => $this->common_function->server_code(),
            );
            $this->template->content->view('default/contents/vrm/student/login_form', $vars);
            $this->template->publish();
        }
    }

    public function login(){
        $vars = array(
            'server_code' => $this->common_function->server_code(),
        );
        $this->template->content->view('default/contents/vrm/student/login_form', $vars);
        $this->template->publish();
    }

    public function connect_dyned_pro(){
        if($this->input->post('__submit') && $this->input->post('dyned_pro_id') && $this->input->post('password') && $this->input->post('server_dyned_pro')){
           // print_r($this->input->post()); exit;

            // checking if it can connect to call one
            // place the function in the first conditional
            // if($this->call1->init($this->input->post('dyned_pro_id'), $this->input->post('password'), $this->input->post('server_dyned_pro'))->is_valid()){
            if($this->call1->init($this->input->post('dyned_pro_id'), $this->input->post('password'), $this->input->post('server_dyned_pro'))){
                $data = array(
                    'dyned_pro_id' => $this->input->post('dyned_pro_id'),
                    'server_dyned_pro' => $this->input->post('server_dyned_pro'),
                );

                $user_profile_id = $this->identity_model->get_identity('profile')->where('user_id', $this->auth_manager->userid())->select('id')->get();
                //print_r($user_profile_id); exit;
                $this->identity_model->get_identity('profile')->update($user_profile_id->id, $data, TRUE);

                $this->messages->add('Connect to your DynEd Pro Successful', 'success');
                redirect('student/student_vrm/single_student');
            }
            else{
                $this->messages->add('Invalid DynEd Pro Id or Password, Please try again.', 'warning');
                redirect('student/student_vrm/single_student');
            }
        }
        else{
            $this->messages->add('Invalid DynEd Pro Id or Password, Please try again.', 'warning');
            redirect('student/student_vrm/single_student');
        }
        //print_r($this->input->post()); exit;
    }
}
