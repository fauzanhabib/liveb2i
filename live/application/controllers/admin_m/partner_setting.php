<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class partner_setting extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('partner_setting_model');
        $this->load->model('global_settings_model');
        // for messaging action and timing
        $this->load->library('queue');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'RAM') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = 'Affiliate Setting';
        $data = $this->partner_setting_model->get();
        //print_r($data); //exit;
        $vars = array(
            'data' => $data,
        );

        $this->template->content->view('default/contents/partner_setting/setting_partner', $vars);
        //publish template
        $this->template->publish();
    }
    
    // public function update_setting($role = ''){

    //     if(!$this->input->post('__submit')){
    //         $this->messages->add('Update Failed', 'warning');
    //         redirect('admin_m/partner_setting');
    //     }
    //     if(!$role){
    //         $this->messages->add('Update Failed', 'warning');
    //     }
    //     else if($role == 'student'){
    //         $data = array(
    //             'max_student' => $this->input->post('max_student'),
    //             'max_day_per_week' => $this->input->post('max_day_per_week'),
    //             'max_session_per_day' => $this->input->post('max_session_per_day'),
    //             'token_for_student' => $this->input->post('token_for_student'),
    //         );
    //         $setting_id = $this->partner_setting_model->get();
    //         if( ! $this->partner_setting_model->update($setting_id->id, $data)) {
    //             $this->messages->add('Update Failed', 'warning');
    //         }
    //         else{
    //             $this->messages->add('Update Succeded', 'success');
    //         }
    //     }
    //     else if($role == 'coach'){
    //         $data = array(
    //             'session_duration' => $this->input->post('session_duration'),
    //         );
    //         $setting_id = $this->partner_setting_model->get();
    //         if( ! $this->partner_setting_model->update($setting_id->id, $data)) {
    //             $this->messages->add('Update Failed', 'warning');
    //         }
    //         else{
    //             $this->messages->add('Update Succeded', 'success');
    //         }
    //     }
        
    //     redirect('admin_m/partner_setting');
    // }

    public function update_setting($type){

        if(!$this->input->post('__submit')){
            $this->messages->add('Update Failed', 'warning');
            redirect('admin_m/partner_setting/setting_partner/'.$type);
        }
        if(!$type){
            $this->messages->add('Update Failed', 'warning');
        }

        if($type == 'student'){
            // get region setting
            $region_setting = $this->global_settings_model->get_global_settings('partner');

            $session_duration = $region_setting[0]->session_duration;
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
            $update_session_duration = $this->input->post('session_duration');


            if($update_max_student_class > $max_student_class){
                $message_setting = 'Max Student Class '.$max_student_class;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/partner_setting/setting_partner/student');
            }

            if($update_max_student_supplier > $max_student_supplier){
                $message_setting = 'Max Student Affiliate '.$max_student_supplier;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/partner_setting/setting_partner/student');
            }

            if($update_max_day_per_week > $max_day_per_week){
                $message_setting = 'Max Day Per Week '.$max_day_per_week;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/partner_setting/setting_partner/student');
            }

            if($update_max_session_per_day > $max_session_per_day){
                $message_setting = 'Max Session Per Day '.$max_session_per_day;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/partner_setting/setting_partner/student');
            }

            if($update_max_token > $max_token){
                $message_setting = 'Max Token '.$max_token;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/partner_setting/setting_partner/student');
            }

            if($update_max_token_for_student > $max_token_for_student){
                $message_setting = 'Max Token For Student '.$max_token_for_student;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/partner_setting/setting_partner/student');
            }

            // if($update_max_session_per_x_day > $max_session_per_x_day){
            //     $message_setting = 'Max Token For Student '.$max_token_for_student;
            //     $this->messages->add($message_setting, 'warning');
            //     redirect('admin_m/partner_setting/setting_partner/student');
            // }

            // if($update_x_day > $x_day){
            //     $message_setting = 'Max X Day '.$x_day;
            //     $this->messages->add($message_setting, 'warning');
            //     redirect('admin_m/partner_setting/setting_partner/student');
            // }

            if($update_set_max_session != $set_max_session){
                $message_setting = 'Session Duration '.$set_max_session." Minutes";
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/partner_setting/setting_partner/student');
            }

            if($update_session_duration != $session_duration){
                $message_setting = 'Max Session for Student is Set to '.$set_max_session;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/partner_setting/setting_partner/student');
            }

            
        // ======================================================
            // update partner setting

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
                            
            );
        }

            $partner_id = $this->auth_manager->partner_id();

            $this->db->where('partner_id', $partner_id);
            $this->db->update('specific_settings', $data); 

            $this->messages->add('Update Region Successful', 'success');
            redirect('admin_m/partner_setting/setting_partner/student');
                        
    
    }
    
//    public function setting_account(){
//        $this->template->content->view('default/contents/partner_setting/setting_account');
//
//        //publish template
//        $this->template->publish();
//    }
    
    public function setting_partner($type=''){
        // cek apakah id partner sudah ada di table spesific setting_partner
        $partner_id = $this->auth_manager->partner_id();
        $checking = count($this->partner_setting_model->select('partner_id')->where('partner_id', $partner_id)->get());

        if($checking == 0){
           // $data = $this->partner_setting_model->select('*')->where('partner_id', $this->auth_manager->userid())->get();
            $data = $this->db->select('*')->from('specific_settings')->where('partner_id', $this->auth_manager->userid())->get()->result();
        } else if($checking == 1){
            // $data = $this->partner_setting_model->select('*')->where('partner_id', $partner_id)->get();
            $data = $this->db->select('*')->from('specific_settings')->where('partner_id', $partner_id)->get()->result();
        }

        $vars = array(
            'data' => $data,
        );


        if($type == ''){
            $type="coach";
        }

        $this->template->content->view('default/contents/partner_setting/'.$type,$vars);

        //publish template
        $this->template->publish();
    }
    
//    public function setting_session(){
//        $this->template->content->view('default/contents/partner_setting/setting_session');
//
//        //publish template
//        $this->template->publish();
//    }

    public function update_setting_partner($user_id=''){
        // get partner setting
        // $region_id = $this->auth_manager->region_id($user_id);
        // $region_setting = $this->partner_setting_model->select('*')->where('user_id', $region_id)->get();
        $partner_setting = $this->partner_setting_model->select('*')->where('partner_id', $this->auth_manager->partner_id())->get();

        $session_duration = $partner_setting->session_duration;
        $max_student_supplier = $partner_setting->max_student_supplier;
        $max_student_class = $partner_setting->max_student_class;
        $max_session_per_day = $partner_setting->max_session_per_day;
        $max_day_per_week = $partner_setting->max_day_per_week;
        $max_token = $partner_setting->max_token;
        $max_token_for_student = $partner_setting->max_token_for_student;
        // $max_session_per_x_day = $partner_setting->max_session_per_x_day;
        // $x_day = $partner_setting->x_day;
        $set_max_session = $partner_setting->set_max_session;

        // ==============================================================

        // get input update setting
        $rules = array(
                array('field'=>'max_student_class', 'label' => 'max_student_class', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'max_student_supplier', 'label' => 'max_student_supplier', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'max_day_per_week', 'label' => 'max_day_per_week', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'max_session_per_day', 'label' => 'max_session_per_day', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'max_token', 'label' => 'max_token', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'max_token_for_student', 'label' => 'max_token_for_student', 'rules'=>'trim|required|xss_clean'),
                // array('field'=>'max_session_per_x_day', 'label' => 'max_session_per_x_day', 'rules'=>'trim|required|xss_clean'),
                // array('field'=>'x_day', 'label' => 'x_day', 'rules'=>'trim|required|xss_clean'),
                array('field'=>'session_duration', 'label' => 'session_duration', 'rules'=>'trim|required|xss_clean'),
               );

        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            redirect('admin_m/partner_setting/setting_partner/'.$user_id);
        }

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
            $update_session_duration = $this->input->post('session_duration');


            if($update_max_student_class > $max_student_class){
                $message_setting = 'Max Student Class '.$max_student_class;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/partner_setting/setting_partner/'.$user_id);
            }

            if($update_max_student_supplier > $max_student_supplier){
                $message_setting = 'Max Student Supplier '.$max_student_supplier;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/partner_setting/setting_partner/'.$user_id);
            }

            if($update_max_day_per_week > $max_day_per_week){
                $message_setting = 'Max Day Per Week '.$max_day_per_week;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/partner_setting/setting_partner/'.$user_id);
            }

            if($update_max_session_per_day > $max_session_per_day){
                $message_setting = 'Max Session Per Day '.$max_session_per_day;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/partner_setting/setting_partner/'.$user_id);
            }

            if($update_max_token > $max_token){
                $message_setting = 'Max Token '.$max_token;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/partner_setting/setting_partner/'.$user_id);
            }

            if($update_max_token_for_student > $max_token_for_student){
                $message_setting = 'Max Token For Student '.$max_token_for_student;
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/partner_setting/setting_partner/'.$user_id);
            }

            // if($update_max_session_per_x_day > $max_session_per_x_day){
            //     $message_setting = 'Max Session Per X Day '.$max_session_per_x_day;
            //     $this->messages->add($message_setting, 'warning');
            //     redirect('admin_m/partner_setting/setting_partner/'.$user_id);
            // }

            // if($update_x_day > $x_day){
            //     $message_setting = 'Max X Day '.$x_day;
            //     $this->messages->add($message_setting, 'warning');
            //     redirect('admin_m/partner_setting/setting_partner/'.$user_id);
            // }

            if($update_set_max_session != $set_max_session){
                $message_setting = 'Session Duration '.$set_max_session." Minutes";
                $this->messages->add($message_setting, 'warning');
                redirect('admin_m/partner_setting/setting_partner/'.$user_id);
            }

            // if($update_session_duration != $session_duration){
            //     $message_setting = 'Max Session for Student is Set to '.$set_max_session;
            //     $this->messages->add($message_setting, 'warning');
            //     redirect('admin_m/partner_setting/setting_partner/'.$user_id);
            // }

            
        // ======================================================

       // check user id di table specific setting

       $checking = count($this->partner_setting_model->select('user_id')->where('user_id', $user_id)->get());

        if($checking == 0){
            // insert
           $setting = array(
                'partner_id' => $user_id,
                'max_student_class' => $this->input->post('max_student_class'),
                'max_student_supplier' => $this->input->post('max_student_supplier'),
                'max_day_per_week' => $this->input->post('max_day_per_week'),
                'max_session_per_day' => $this->input->post('max_session_per_day'),
                'max_token' => $this->input->post('max_token'),
                'max_token_for_student' => $this->input->post('max_token_for_student'),
                // 'max_session_per_x_day' => $this->input->post('max_session_per_x_day'), 
                // 'x_day' => $this->input->post('x_day'),
                'set_max_session' => $this->input->post('set_max_session'),
                'session_duration' => $this->input->post('session_duration'),
                'status_set_setting' => $this->input->post('status_set_setting')
                
            );
            $this->partner_setting_model->insert($setting);
            $this->messages->add('Update Partner Setting Successful', 'success');
            redirect('admin_m/partner_setting/setting_partner/'.$user_id);


       
        } else if($checking == 1){
            // update
           $setting = array(
                'max_student_class' => $this->input->post('max_student_class'),
                'max_student_supplier' => $this->input->post('max_student_supplier'),
                'max_day_per_week' => $this->input->post('max_day_per_week'),
                'max_session_per_day' => $this->input->post('max_session_per_day'),
                'max_token' => $this->input->post('max_token'),
                'max_token_for_student' => $this->input->post('max_token_for_student'),
                // 'max_session_per_x_day' => $this->input->post('max_session_per_x_day'), 
                // 'x_day' => $this->input->post('x_day'),
                'set_max_session' => $this->input->post('set_max_session'),
                'session_duration' => $this->input->post('session_duration'),
                'status_set_setting' => $this->input->post('status_set_setting')
                
            );
            $this->db->where('partner_id',$user_id);
            $this->db->update($this->partner_setting_model->table,$setting);
            $this->messages->add('Update Partner Setting Successful', 'success');
            redirect('admin_m/partner_setting/setting_partner/'.$user_id);
            
        }

    }

    

}
