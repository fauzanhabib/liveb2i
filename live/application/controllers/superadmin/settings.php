<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class settings extends MY_Site_Controller {
    
    // Constructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('global_settings_model');

        
        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'RAD') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    function index(){
        echo "hai";
    }
    
    public function region($type = '') {
        $this->template->title = 'Global Region Setting';
        $data = $this->global_settings_model->get_global_settings('region');
 
        $vars = array(
            'data' => $data,
        );

        if(($type == "coach") || ($type == "")){
            $this->template->content->view('default/contents/superadmin/settings/region_coach', $vars);
            //publish template
            $this->template->publish();
        } else if($type == 'student'){
            $this->template->content->view('default/contents/superadmin/settings/region_student', $vars);
            //publish template
            $this->template->publish();
        }

        // $this->template->content->view('default/contents/superadmin/settings/region', $vars);
        // //publish template
        // $this->template->publish();
        
    }

    public function partner($type = '')
    {
        $this->template->title = 'Global Partner Setting';
        $data = $this->global_settings_model->get_global_settings('partner');

        $vars = array(
            'data' => $data,
        );

        if(($type == "coach") || ($type == "")){
            $this->template->content->view('default/contents/superadmin/settings/partner_coach', $vars);
            //publish template
            $this->template->publish();
        } else if($type == 'student'){
            $this->template->content->view('default/contents/superadmin/settings/partner_student', $vars);
            //publish template
            $this->template->publish();
        }

        // $this->template->content->view('default/contents/superadmin/settings/partner', $vars);
        //publish template
        // $this->template->publish();
    }
    
    public function update_setting($type){
        // echo('<pre>');
        // print_r($this->input->post()); exit;
        if(!$this->input->post('__submit')){
            $this->messages->add('Update Failed', 'warning');
            redirect('superadmin/settings/'.$type);
        }
        if(!$type){
            $this->messages->add('Update Failed', 'warning');
        }


        if($type == 'region'){
            if($this->input->post('__submit') == 'region_student'){
                $data = array(
                    'session_duration' => $this->input->post('session_duration'),   
                    'max_token' => $this->input->post('max_token'),
                    'max_token_for_student' => $this->input->post('max_token_for_student'),
                    'max_student_class' => $this->input->post('max_student_class'),
                    'max_student_supplier' => $this->input->post('max_student_supplier'),
                    'max_day_per_week' => $this->input->post('max_day_per_week'),
                    'max_session_per_day' => $this->input->post('max_session_per_day'), 
                    // 'max_session_per_x_day' => $this->input->post('max_session_per_x_day'), 
                    // 'x_day' => $this->input->post('x_day'),
                    'set_max_session' => $this->input->post('set_max_session'),
                    'status_set_setting' => $this->input->post('status_set_setting'), 

                                
                );

                if($this->input->post('max_day_per_week') > 7){
                    $this->messages->add('Max day per week cannot more than 7', 'warning');
                    redirect('superadmin/settings/region/student');
                }

                if($this->input->post('max_session_per_day') > 96){
                    $this->messages->add('Max session per day cannot more than 96', 'warning');
                    redirect('superadmin/settings/region/student');
                }

                $target_redirect = "region/student";
            } else if($this->input->post('__submit') == 'region_coach'){
                if(($this->input->post('standard_coach_cost') < 1) || ($this->input->post('elite_coach_cost') < 1)){
                    $this->messages->add('Standard Coach cost or Elite Coach cost minimum 1', 'warning');
                    redirect('superadmin/settings/region/coach');
                }

                $data = array('session_duration' => $this->input->post('session_duration'),
                            'standard_coach_cost' => $this->input->post('standard_coach_cost'),
                            'elite_coach_cost' => $this->input->post('elite_coach_cost'));
                $target_redirect = "region/coach";
            }
        } else if($type == 'partner'){

            // get region setting
            $region_setting = $this->global_settings_model->get_global_settings('region');

            if($this->input->post('__submit') == 'partner_student'){
                $max_student_supplier = $region_setting[0]->max_student_supplier;
                $max_student_class = $region_setting[0]->max_student_class;
                $max_session_per_day = $region_setting[0]->max_session_per_day;
                $max_day_per_week = $region_setting[0]->max_day_per_week;
                $max_token = $region_setting[0]->max_token;
                $max_token_for_student = $region_setting[0]->max_token_for_student;
                // $max_session_per_x_day = $region_setting[0]->max_session_per_x_day;
                // $x_day = $region_setting[0]->x_day;
                $set_max_session = $region_setting[0]->set_max_session;

                // =========================
                // cek perbandingan setting max region setting dengan update input
                $update_max_student_class = $this->input->post('max_student_class');
                $update_max_student_supplier = $this->input->post('max_student_supplier');
                $update_max_day_per_week = $this->input->post('max_day_per_week');
                $update_max_session_per_day = $this->input->post('max_session_per_day');
                $update_max_token = $this->input->post('max_token');
                $update_max_token_for_student = $this->input->post('max_token_for_student');
                // $update_max_session_per_x_day = $this->input->post('max_session_per_x_day');
                // $update_x_day = $this->input->post('x_day');
                $update_set_max_session = $this->input->post('set_max_session');


                // if($this->input->post('max_session_per_day') > $this->input->post('max_day_per_week')){
                //     $this->messages->add('Max session per day cannot exceeded max day per week', 'warning');
                //     redirect('superadmin/settings/partner/student');
                // }
                


                if($update_max_student_class > $max_student_class){
                    $message_setting = 'Max Student Class '.$max_student_class;
                    $this->messages->add($message_setting, 'warning');
                    redirect('superadmin/settings/partner/student');
                }

                if($update_max_student_supplier > $max_student_supplier){
                    $message_setting = 'Max Student Affiliate '.$max_student_supplier;
                    $this->messages->add($message_setting, 'warning');
                    redirect('superadmin/settings/partner/student');
                }

                if($update_max_day_per_week > $max_day_per_week){
                    $message_setting = 'Max Day Per Week '.$max_day_per_week;
                    $this->messages->add($message_setting, 'warning');
                    redirect('superadmin/settings/partner/student');
                }

                if($update_max_session_per_day > $max_session_per_day){
                    $message_setting = 'Max Session Per Day '.$max_session_per_day;
                    $this->messages->add($message_setting, 'warning');
                    redirect('superadmin/settings/partner/student');
                }

                if($update_max_token > $max_token){
                    $message_setting = 'Max Token '.$max_token;
                    $this->messages->add($message_setting, 'warning');
                    redirect('superadmin/settings/partner/student');
                }

                if($update_max_token_for_student > $max_token_for_student){
                    $message_setting = 'Max Token For Student '.$max_token_for_student;
                    $this->messages->add($message_setting, 'warning');
                    redirect('superadmin/settings/partner/student');
                }

                // if($update_max_session_per_x_day > $max_session_per_x_day){
                //     $message_setting = 'Max Session Per X Day '.$max_session_per_x_day;
                //     $this->messages->add($message_setting, 'warning');
                //     redirect('superadmin/settings/partner/student');
                // }

                // if($update_x_day > $x_day){
                //     $message_setting = 'Max X Day '.$x_day;
                //     $this->messages->add($message_setting, 'warning');
                //     redirect('superadmin/settings/partner/student');
                // }

                // if($update_set_max_session != $set_max_session){
                //     $message_setting = 'Max Session for Student is Set to '.$set_max_session;
                //     $this->messages->add($message_setting, 'warning');
                //     redirect('superadmin/settings/partner/student');
                // }
                // update partner setting

                $data = array(
                    'max_token' => $this->input->post('max_token'),
                    'max_token_for_student' => $this->input->post('max_token_for_student'),
                    'max_student_class' => $this->input->post('max_student_class'),
                    'max_student_supplier' => $this->input->post('max_student_supplier'),
                    'max_day_per_week' => $this->input->post('max_day_per_week'),
                    'max_session_per_day' => $this->input->post('max_session_per_day'), 
                    // 'max_session_per_x_day' => $this->input->post('max_session_per_x_day'), 
                    // 'x_day' => $this->input->post('x_day'),
                    'set_max_session' => $this->input->post('set_max_session'),
                                
                );

                $target_redirect = "partner/student";

            } else if($this->input->post('__submit') == 'partner_coach'){
              
                $session_duration = $region_setting[0]->session_duration;
                $standard_coach_cost = $region_setting[0]->standard_coach_cost;
                $elite_coach_cost = $region_setting[0]->elite_coach_cost;

                $update_session_duration = $this->input->post('session_duration');
                $update_standard_coach_cost = $this->input->post('standard_coach_cost');
                $update_elite_coach_cost = $this->input->post('elite_coach_cost');
        

                if($update_session_duration != $session_duration){
                    $message_setting = 'Session Duration '.$session_duration." Minutes";
                    $this->messages->add($message_setting, 'warning');
                    redirect('superadmin/settings/partner/coach');
                }

                if($update_standard_coach_cost > $standard_coach_cost){
                    $message_setting = 'Max Coach Cost '.$standard_coach_cost;
                    $this->messages->add($message_setting, 'warning');
                    redirect('superadmin/settings/partner/coach');
                }

                if($update_elite_coach_cost > $elite_coach_cost){
                    $message_setting = 'Max Elite Coach Cost '.$elite_coach_cost;
                    $this->messages->add($message_setting, 'warning');
                    redirect('superadmin/settings/partner/coach');
                }

                if(($this->input->post('standard_coach_cost') < 1) || ($this->input->post('elite_coach_cost') < 1)){
                    $this->messages->add('Standard Coach cost or Elite Coach cost minimum 1', 'warning');
                    redirect('superadmin/settings/partner/coach');
                }

                $data = array('session_duration' => $this->input->post('session_duration'),
                            'standard_coach_cost' => $this->input->post('standard_coach_cost'),
                            'elite_coach_cost' => $this->input->post('elite_coach_cost'));
                $target_redirect = "partner/coach";
            }

        }

            $this->db->where('type', $type);
            $this->db->update('global_settings', $data); 

            $this->messages->add('Update setting '.$type.' Successful', 'success');
            redirect('superadmin/settings/'.$target_redirect);
                        
    
    }
        

    
    public function setting_partner(){
        $this->template->content->view('default/contents/superadmin/settings/region');

        //publish template
        $this->template->publish();
    }

}
