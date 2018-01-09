<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class dashboard extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('webex_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('class_member_model');

        $this->load->library('schedule_function');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CCH') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
       $this->template->title = "Dashboard";


       // $asd = 360 / 60;
       // $wh = floor($asd);
       // $fract = $asd - $wh;

       // if($fract == 0){
       //      $asdf = $asd + 0;
       // }
       // echo $asdf;exit();
        //------------wm
        $id    = $this->auth_manager->userid();
        // date_default_timezone_set('Asia/Jakarta');

        $tipe = '';
        if($this->auth_manager->role() == "STD"){
            $tipe = 'student_id';
        } else if($this->auth_manager->role() == "CCH"){
            $tipe = 'coach_id';
        }

        $nowd  = date('Y-m-d');
        // $nowd  = "2016-08-21";
        $hour_start_db  = date('H:i:s');
        // $hour_start_db  = "02:40:01";

        $pull_appoint = $this->db->select('*')
                      ->from('appointments')
                      ->where($tipe, $id)
                      ->where('date =', $nowd)
                      ->where('end_time >=', $hour_start_db)
                      ->where('status', 'active')
                      ->order_by('date', 'ASC')
                      ->order_by('start_time', 'ASC')
                      ->limit(5)
                      ->get()->result();

        $pull_appoint2 = $this->db->select('*')
                      ->from('appointments')
                      ->where($tipe, $id)
                      ->where('date =', $nowd)
                      ->where('end_time >=', $hour_start_db)
                      ->where('status', 'active')
                      ->order_by('date', 'ASC')
                      ->order_by('start_time', 'ASC')
                      ->limit(5)
                      ->get()->result();
        //------------wm
        // echo "<pre>";print_r($pull_appoint);exit();
        $data = @$pull_appoint;
        // $data = $this->appointment_model->get_appointment_for_upcoming_session('coach_id', '', '', $this->auth_manager->userid());

        if ($data) {
            foreach ($data as $d) {
                $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time);
                $d->date = date('Y-m-d', $data_schedule['date']);
                $d->start_time = $data_schedule['start_time'];
                $d->end_time = $data_schedule['end_time'];
            }
        }

        $data_class_upcoming = $this->class_meeting_day_model->get_appointment_for_upcoming_session('', '', $this->auth_manager->userid());

        if ($data_class_upcoming) {
            foreach ($data_class_upcoming as $data_class) {
                $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($data_class->date), $data_class->start_time, $data_class->end_time);
                $data_class->date = date('Y-m-d', $data_schedule['date']);
                $data_class->start_time = $data_schedule['start_time'];
                $data_class->end_time = $data_schedule['end_time'];
            }
        }
        // wimo----------------------
        $wm2         = @$pull_appoint2[0];
        $he_pull2    = strtotime(@$wm2->end_time) - (5 * 60);
        $hourend2    = date("H:i:s", $he_pull2);

        $n;

        if (strtotime(date("H:i:s")) > strtotime($hourend2)) {
            $n = 1;
        }else{
            $n = 0;
        }


        $wm    = @$pull_appoint[$n];
        $wm_id = @$wm->id;
        // echo "<pre>";
        // print_r($wm_id);
        // exit();
        //User Hour
        date_default_timezone_set('UTC');
        $hourstart  = @$wm->start_time;
        $datestart  = @$wm->date;
        $he_pull    = strtotime(@$wm->end_time) - (5 * 60);
        $hourend    = date("H:i:s", $he_pull);

        $tz = $this->db->select('*')
            ->from('user_timezones')
            ->where('user_id', $id)
            ->get()->result();

        $minutes = @$tz[0]->minutes_val;
        $gmt_val = @$tz[0]->gmt_val;

        if(@$gmt_val > 0){
            @$gmt_val = "+".@$gmt_val;
        }

        $date     = date('H:i:s');
        $default2 = strtotime($date);
        $usertime2 = $default2+(60*$minutes);
        $nowh  = date("H:i:s", $usertime2);
        // $nowh  = "09:40:01";
        // $nowd  = date("Y-m-d");


        $nowc  = $nowd.' '.$nowh;
        $countdown = $datestart.' '.$hourstart;

        // wimo----------------------

        //Check Already Opened Live Session -------------------------------
        $checksess = $this->db->select('*')
                    ->from('session_live')
                    ->where('user_id', $id)
                    ->where('appointment_id', $wm_id)
                    ->get()->result();

        $statuscheck = @$checksess[0]->status;

        // echo "<pre>";print_r($hourend);exit();
        //Check Already Opened Live Session -------------------------------

        $pull_notif = $this->db->select('*')
                      ->from('user_notifications')
                      ->where('user_id', $id)
                      ->get()->result();

        $pull_name = $this->db->select('*')
                      ->from('user_profiles')
                      ->where('user_id', $id)
                      ->get()->result();

        if(!$pull_notif){

            $user_notification = array(
                'user_id' => $id,
                'description' => 'Congratulation '.$pull_name[0]->fullname.' and Welcome to DynEd Live.',
                'status' => 2,
                'dcrea' => time(),
                'dupd' => time(),
            );

            $this->user_notification_model->insert($user_notification);
        }

        if(@$wm->app_type == 1){
          $url_session = site_url('b2c/student/agora/');
        }else if(@$wm->app_type == 0){
          $url_session = site_url('b2c/student/opentok/live/');
        }

        // echo "<pre>";print_r($wm->app_type);exit();

        $vars = array(
            'title' => 'Upcoming Session',
            'role' => 'Student',
            'userid'    => $id,
            'nowh'  => $nowh,
            'data'  => $data,
            'nowd'  => $nowd,
            'nowc'  => $nowc,
            'wm'    => $wm,
            'wm_id' => $wm_id,
            'gmt_val' => @$gmt_val,
            'statuscheck'  => @$statuscheck,
            'hourstart'  => $hourstart,
            'datestart'  => $datestart,
            'hourend'    => $hourend,
            'data_class' => $data_class_upcoming,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'id_webex' => $this->webex_model->select('id')->where('status', 'SCHE')->get_all(),
            'countdown'  => $countdown,
            'url_session'  => $url_session
        );


        $this->template->content->view('default/contents/coach/dashboard/index',$vars);
        $this->template->publish();
    }

    public function get_id(){
        $id  = $this->auth_manager->userid();

         $tz = $this->db->select('*')
            ->from('user_timezones')
            ->where('user_id', $id)
            ->get()->result();

        $minutes = @$tz[0]->minutes_val;

        $tipe = '';
        if($this->auth_manager->role() == "STD"){
            $tipe = 'student_id';
        } else if($this->auth_manager->role() == "CCH"){
            $tipe = 'coach_id';
        }

        $nowd  = date('Y-m-d');
        // $nowd  = "2016-08-21";
        $hour_start_db  = date('H:i:s');
        // $hour_start_db  = "02:40:01";

        $pull_appoint = $this->db->select('*')
                      ->from('appointments')
                      ->where($tipe, $id)
                      ->where('date =', $nowd)
                      ->where('end_time >=', $hour_start_db)
                      ->where('status', 'active')
                      ->order_by('date', 'ASC')
                      ->order_by('start_time', 'ASC')
                      ->limit(5)
                      ->get()->result();

        $he_pull2    = strtotime(@$pull_appoint[0]->end_time) - (5 * 60);
        $hourend2    = date("H:i:s", $he_pull2);

        $n;

        if (strtotime(date("H:i:s")) > strtotime($hourend2)) {
            $n = 1;
        }else{
            $n = 0;
        }

        $wm    = @$pull_appoint[$n];
        $wm_id = @$wm->id;
        echo $wm_id;
    }

    public function student_detail(){
        $student_id = $this->input->post('student_id');
        // $student_id = '338';

        $data = $this->identity_model->get_student_identity($student_id);


        $name = $data[0]->fullname;
        $email = $data[0]->email;
        $birthdate = $data[0]->date_of_birth;
        $gender = $data[0]->gender;
        $timezone = $data[0]->timezone;
        $profile_picture = $data[0]->profile_picture;

        $var[] = ['name' => $name,
                'email' => $email,
                'birthdate' => $birthdate,
                'gender' => $gender,
                'timezone' => $timezone,
                'profile_picture' => $profile_picture,
                ];

        echo json_encode($var);

    }

    public function clear_live(){
        $id    = $this->auth_manager->userid();

        $this->db->where('user_id', $id);
        $this->db->delete('session_live');
    }

}
