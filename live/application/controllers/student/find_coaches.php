<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once 'vendor/autoload.php';

use OpenTok\OpenTok;
use OpenTok\ArchiveMode;
use OpenTok\MediaMode;
use OpenTok\Session;
use OpenTok\Role;

class find_coaches extends MY_Site_Controller {

    var $day_index = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    // Constructorss
    public function __construct() {
        parent::__construct();

        // load models
        $this->load->model('appointment_model');
        $this->load->model('appointment_reschedule_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('class_member_model');
        $this->load->model('coach_day_off_model');
        $this->load->model('coach_token_cost_model');
        $this->load->model('coach_rating_model');
        $this->load->model('identity_model');
        $this->load->model('offwork_model');
        $this->load->model('partner_model');
        $this->load->model('schedule_model');
        $this->load->model('token_histories_model');
        $this->load->model('user_notification_model');
        $this->load->model('webex_host_model');
        $this->load->model('webex_class_model');
        $this->load->model('webex_model');
        $this->load->model('partner_setting_model');
        $this->load->model('global_settings_model');
        $this->load->model('specific_settings_model');


        //load libraries
        $this->load->library('queue');
        $this->load->library('schedule_function');
        $this->load->library('webex_function');
        $this->load->library('email_structure');
        $this->load->library('send_email');

        @date_default_timezone_set('Etc/GMT+0');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD'){
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {

        $this->template->title = 'Find Coach';
        $vars = array(
            'coaches' => $this->identity_model->get_coach_identity(),
            'rating' => $this->coach_rating_model->get_average_rate()
        );
        $this->template->content->view('default/contents/find_coach/index', $vars);
        $this->template->publish();
    }

    public function detail($id = '') {
        $this->template->title = 'Find Coach';
        $vars = array(
            'coaches' => $this->identity_model->get_coach_identity($id),
        );
        $this->template->content->view('default/contents/find_coach/detail', $vars);
        $this->template->publish();
    }

    public function search($category = null, $page='') {
        //print_r($this->email_structure->header().$this->email_structure->title('ini title').$this->email_structure->content('ini content').$this->email_structure->button('ini button').$this->email_structure->footer('ini footer')); EXIT;
        $this->template->title = 'Find Coach';



        $offset = 0;
        $per_page = 6;
        $uri_segment = 5;
        if ($category == 'name' || $category == null) {
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student/find_coaches/search/name'), count($this->identity_model->get_coach_identity(null, @$this->input->post('search_key'))), $per_page, $uri_segment);
            $coaches = $this->identity_model->get_coach_identity(null, @$this->input->post('search_key'), null, null, null, null, null, $per_page, $offset);
        } else if ($category == 'country') {
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student/find_coaches/search/country'), count($this->identity_model->get_coach_identity(null, null, @$this->input->post('search_key'))), $per_page, $uri_segment);
            $coaches = $this->identity_model->get_coach_identity(null, null, @$this->input->post('search_key'), null, null, null, null, $per_page, $offset);
        } else if ($category == 'spoken_language') {
            $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student/find_coaches/search/spoken_language'), count($this->identity_model->get_coach_identity(null, null, null, null, null, null, @$this->input->post('search_key'))), $per_page, $uri_segment);
            $coaches = $this->identity_model->get_coach_identity(null, null, null, null, null, null, @$this->input->post('search_key'), $per_page, $offset);
        } else {
            redirect('account/identity/detail/profile');
        }

        // $partner_id = $this->auth_manager->partner_id($this->auth_manager->userid());
        // $region_id = $this->auth_manager->region_id($partner_id);

        // $setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();
        // $region_setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('specific_settings')->where('user_id',$region_id)->get()->result();
        // $global_setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('global_settings')->where('type','partner')->get()->result();

        // $standard_coach_cost = @$setting[0]->standard_coach_cost;
        // if(!$standard_coach_cost || $standard_coach_cost == 0){
        //     $standard_coach_cost_region = @$region_setting[0]->standard_coach_cost;
        //     $standard_coach_cost = $standard_coach_cost_region;
        //     if(!$standard_coach_cost_region || $standard_coach_cost_region == 0){
        //         $standard_coach_cost_global = @$global_setting[0]->standard_coach_cost;
        //         $standard_coach_cost = $standard_coach_cost_global;
        //     }
        // }

        // $elite_coach_cost = @$setting[0]->elite_coach_cost;
        // if(!$elite_coach_cost || $elite_coach_cost == 0){
        //     $elite_coach_cost_region = @$region_setting[0]->elite_coach_cost;
        //     $elite_coach_cost = $elite_coach_cost_region;
        //     if(!$elite_coach_cost_region || $elite_coach_cost_region == 0){
        //         $elite_coach_cost_global = @$global_setting[0]->elite_coach_cost;
        //         $elite_coach_cost = $elite_coach_cost_global;
        //     }
        // }

        // $session_duration = @$setting[0]->session_duration;
        // if(!$session_duration || $session_duration == 0){
        //     $session_duration_region = @$region_setting[0]->session_duration;
        //     $session_duration = $session_duration_region;
        //     if(!$session_duration_region || $session_duration_region == 0){
        //         $session_duration_global = @$global_setting[0]->session_duration;
        //         $session_duration = $session_duration_global;
        //     }
        // }

        $vars = array(
            'coaches' => $coaches,
            'selected' => $category,
            'rating' => $this->coach_rating_model->get_average_rate(),
            'pagination' => @$pagination,
            // 'standard_coach_cost' => @$standard_coach_cost,
            // 'elite_coach_cost' => @$elite_coach_cost,
            'session_duration' => @$session_duration
        );
       // echo('<pre>');
       // print_r($vars); exit;

        $this->template->content->view('default/contents/find_coach/' . $category . '/index', $vars);
        $this->template->publish();
    }

    public function single_date() {

        $this->template->title = 'Single Date';

        $this->load->library('call2');

        $sql = $this->db->select('dyned_pro_id, server_dyned_pro')->from('user_profiles')->where('user_id',$this->auth_manager->userid())->get()->result();
        $dyned_pro_id = $sql[0]->dyned_pro_id;
        $server_dyned_pro = $sql[0]->server_dyned_pro;

        // $this->call2->init("site11", "sutomo@dyned.com");
        $this->call2->init($server_dyned_pro, $dyned_pro_id);
        $a = $this->call2->getDataJson();
        $b = json_decode($a);
        // echo "<pre>";
        // print_r($b);
        // exit();

        $cert_studying = '';

        if(@$b == ''){
            $cert_studying = 0;
        } else if(@$b->error == 'Invalid student email'){
                $cert_studying = 0;
        } else {
                $cert_studying = $b->cert_studying;
        }


        // update student pt score
        if($cert_studying != 0){
            $this->db->where('user_id',$this->auth_manager->userid());
            $this->db->update('user_profiles',array('cert_studying' => $cert_studying, 'dcrea' => time(), 'dupd' => time()));
        }
        $this->template->content->view('default/contents/find_coach/availability/single_date/index');
        $this->template->publish();
    }


    public function get_available_coach($date = '', $per_page='', $offset='') {
        // fungsi untuk mengambil available coach berdasarkan parameter tanggal
        // akan di pakai di single date dan multiple date

        $date_ = $date;
        $coach_id = 2;
        $coach_data = $this->identity_model->get_coach_identity('', '', '', $this->auth_manager->partner_id());
        $available_coach = array();
        // echo $this->auth_manager->userid()." - ".$this->auth_manager->partner_id();
        // echo "<pre>";
        // print_r($coach_data);
        // exit();
        foreach ($coach_data as $cd) {
            $coach_id = $cd->id;
            $availability_temp = array();

            // if ($this->is_date_available(trim($date_), 1) && !$this->is_day_off($coach_id, $date_) == true) {
            if ($this->is_date_available(trim($date_), -1) && !$this->is_day_off($coach_id, $date_) == true) {



                //getting the day of $date
                $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;
                $date = strtotime($date_);
                $day = strtolower(date('l', $date));
                $day2 = $this->day_index[$this->convert_gmt(array_search($day, $this->day_index), $minutes)];

                // appointment data specify by coach, date and status
                // appointment with status cancel considered available for other student
                $appointment = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->get_all();
                // appointment with status temporary considered available for other student but not for student who is in the appointment and
                // appointment where the student has make an appoinment on the specific date, so there will be no the same start time and end time to be shown to the student from other coach
                $appointment_student = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', date("Y-m-d", $date))->get_all();
                // appointment coach in class
                $appointment_class = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', date("Y-m-d", $date))->get_all();

                // storing appointment to an array so can easily on searching / no object value inside
                $appointment_start_time_temp = array();
                $appointment_end_time_temp = array();

                foreach ($appointment as $a) {
                    if($minutes > 0){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                        }
                    }
                    else if($minutes < 0){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                        }
                    }
                    else if($minutes == 0){
                        $appointment_start_time_temp[] = $a->start_time;
                        $appointment_end_time_temp[] = $a->end_time;
                    }
                }

                // storing class meeting days to appointment temp
                foreach ($appointment_class as $a) {
                    if($minutes > 0){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                        }
                    }
                    else if($minutes < 0){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                        }
                    }
                    else if($minutes == 0){
                        $appointment_start_time_temp[] = $a->start_time;
                        $appointment_end_time_temp[] = $a->end_time;
                    }
                }

                foreach ($appointment_student as $a) {
                    if($minutes > 0){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                        }
                    }
                    else if($minutes < 0){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                        }
                    }
                    else if($minutes == 0){
                        $appointment_start_time_temp[] = $a->start_time;
                        $appointment_end_time_temp[] = $a->end_time;
                    }
                }


                if($minutes > 0){
                    $date2 = date("Y-m-d", strtotime('-1 day'.date("Y-m-d",$date)));

                    $appointment2 = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $date2)->get_all();
                    $appointment_student2 = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', $date2)->get_all();
                    $appointment_class2 = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', $date2)->get_all();

                    foreach($appointment2 as $a){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                        }
                    }
                    foreach($appointment_student2 as $a){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                        }
                    }
                    foreach($appointment_class2 as $a){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                        }
                    }

                }
                else if($minutes < 0){
                    $date2 = date("Y-m-d", strtotime('+1 day'.date("Y-m-d",$date)));
                    $appointment2 = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $date2)->get_all();
                    $appointment_student2 = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', $date2)->get_all();
                    $appointment_class2 = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', $date2)->get_all();

                    foreach($appointment2 as $a){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                        }
                    }
                    foreach($appointment_student2 as $a){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                        }
                    }
                    foreach($appointment_class2 as $a){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                        }
                    }
                }


                //getting all data
                $schedule_data1 = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
                $schedule_data2 = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day2)->get();

                $availability = $this->schedule_block($coach_id, $day, $schedule_data1->start_time, $schedule_data1->end_time, $schedule_data2->day, $schedule_data2->start_time, $schedule_data2->end_time);



                $availability_temp = array();
                $availability_exist;
                foreach ($availability as $a) {
                    $duration = (strtotime($a['end_time']) - strtotime($a['start_time'])) / ($this->session_duration($this->auth_manager->partner_id()) * 60);
                    if ($duration >= 1) {
                        for ($i = 0; $i < $duration; $i++) {
                            $availability_exist = array(
                                // adding  minutes for every session
                                'start_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id()) * 60) * ($i))),
                                'end_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id()) * 60) * ($i + 1))),
                            );
                            // checking if the time is not out of coach schedule
                            if(strtotime($availability_exist['end_time']) <= strtotime($a['end_time'])){
                                // checking if availability is existed in the appointment
                                if (in_array($availability_exist['start_time'], $appointment_start_time_temp) && in_array($availability_exist['end_time'], $appointment_end_time_temp)) {
                                    // no action
                                } else {
                                    // storing availability that still active and not been boooked yet
                                    if($this->isValidAppointment($availability_exist['start_time'], $availability_exist['end_time'], $appointment_start_time_temp, $appointment_end_time_temp)){
                                                // @date_default_timezone_set('Etc/GMT'.(-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                                @date_default_timezone_set('Etc/GMT'.(-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                                // if ($date_ == date('Y-m-d', strtotime(date('Y-m-d') . ' + 2 days'))) {
                                                //     exit('hai');
                                                //     if (DateTime::createFromFormat('H:i:s', $availability_exist['start_time']) >= DateTime::createFromFormat('H:i:s', date('H:i:s'))) {
                                                //         $availability_temp[] = $availability_exist;
                                                //     }
                                                // } else {
                                                    $availability_temp[] = $availability_exist;
                                                // }
                                    }
                                    if($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes > 0){
                                        @date_default_timezone_set('Etc/GMT'.($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 : $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                    }
                                }
                            }
                        }
                    }
                }

            }



            if ($availability_temp) {
                $available_coach[] = array(
                    'profile_picture' => $cd->profile_picture,
                    'coach_id' => $coach_id,
                    'fullname' => $cd->fullname,
                    'country' => $cd->country,
                    'token_for_student' => $cd->token_for_student,
                    'availability' => $availability_temp,
                    'pt_score' => $cd->pt_score,
                    'coach_type_id' => $cd->coach_type_id
                );
            }
        }
            // echo $cd->fullname;
            // echo "<pre>";
            // print_r($availability_temp);
            // exit();

        if($per_page && $offset && $offset=="first_page"){
            $offset = 0;
            return (array_slice($available_coach, $offset, $per_page));
        }elseif($per_page && $offset){
            return (array_slice($available_coach, $offset, $per_page));
        }
        // exit();
        return $available_coach;
    }

    public function book_by_single_date($date = '', $page='') {
        /*$booking_type =  $this->input->post('selector');

        if(!$booking_type){
            $this->messages->add('Invalid Booking Type', 'warning');
            redirect('student/find_coaches/single_date/');
        }

        $recurring_booking_type = '';
        if($booking_type == 'single-book'){
            $recurring_booking_type = 1;
        } else if($booking_type = 'multiple-book'){
            $recurring_booking_type = $this->input->post('type_booking');
        } */

        $booking_type =  $this->input->post('selector');

        if(!$booking_type){
            $booking_type = $this->session->userdata("selector_booking_type");
            if(!$booking_type){
                $this->messages->add('Invalid Booking Type', 'warning');
                redirect('student/find_coaches/single_date/');
            }
        }

        $this->session->set_userdata('selector_booking_type',$booking_type);

        $recurring_booking_type = '';
        if($booking_type == 'single-book'){
            $recurring_booking_type = 1;
        } else if($booking_type = 'multiple-book'){
            $recurring_booking_type = $this->input->post('type_booking');
            if(!$recurring_booking_type){
                $recurring_booking_type = $this->session->userdata("recurring_booking_type");
            }
        }

        $this->session->set_userdata("recurring_booking_type",$recurring_booking_type);



        $this->template->title = 'Detail Schedule';

        if ($date <= date('Y-m-d')) {
            $this->messages->add('Invalid Date', 'warning');
            redirect('student/find_coaches/single_date/');
        }

        $offset = 0;
        $per_page = 6;
        $uri_segment = 5;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('student/find_coaches/book_by_single_date/'.$date), count($this->get_available_coach($date)), $per_page, $uri_segment);

        $cert_studying = $this->db->select('cert_studying')->from('user_profiles')->where('user_id',$this->auth_manager->userid())->get()->result();


        $data = $this->get_available_coach($date, $per_page, $offset);

        $partner_id = $this->auth_manager->partner_id($this->auth_manager->userid());

        $setting = $this->db->select('standard_coach_cost,elite_coach_cost')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();
        $standard_coach_cost = $setting[0]->standard_coach_cost;
        $elite_coach_cost = $setting[0]->elite_coach_cost;

        $gmt_student = $this->identity_model->new_get_gmt($this->auth_manager->userid());


        $vars = array(
            'gmt_user' => $gmt_student[0]->minutes,
            'gmt_val_user' => $gmt_student[0]->gmt,
            'data' => $data,
            'date' => $date,
            // 'standard_coach_cost' => $standard_coach_cost,
            // 'elite_coach_cost' => $elite_coach_cost,
            'rating' => $this->coach_rating_model->get_average_rate(),
            'pagination' => @$pagination,
            'cert_studying' => $cert_studying[0]->cert_studying
        );

        // echo "<pre>";
        // print_r($data);
        // exit();
        if(!$data){

            $this->messages->add('Coach not found', 'warning');
            redirect('student/find_coaches/single_date/');
        }

        $this->template->content->view('default/contents/find_coach/book_by_availability/single_date/index', $vars);
        $this->template->publish();
    }

    public function book_single_coach($coach_id = '', $date_ = '', $start_time_ = '', $end_time_ = '', $token = ''){
        $recuring = $this->session->userdata('recurring_booking_type');

        if(!$recuring){
            $recuring = 1;
        }

        if($recuring == 1) {
            $frequency = [0];
        }

        if($recuring == 2) {
            $frequency = [0,7];
        }

        if($recuring == 3) {
            $frequency = [0,7,7];
        }

        if($recuring == 4) {
            $frequency = [0,7,7,7];
        }


        // book otomatis 4x
        $arr_message = [];
        foreach ($frequency as $value) {
            $message = '';
            $date_ = strtotime("+".$value." day", $date_);
            // set defaul timezone
           @date_default_timezone_set('Etc/GMT+0');

            $start_time_available = $start_time_;
            $end_time_available = $end_time_;

            $date_notif = date('l jS \of F Y', @$date_);

            $convert = $this->schedule_function->convert_book_schedule(-($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes), $date_, $start_time_, $end_time_);
            $date = $convert['date'];

            $dateconvert = date('Y-m-d', $date_);
            $dateconvertcoach = date('Y-m-d', $date);
            $start_time = $convert['start_time'];
            $end_time = $convert['end_time'];
            // timezone
            $id_student = $this->auth_manager->userid();

            // student
            $gmt_student = $this->identity_model->new_get_gmt($id_student);
            // coach
            $gmt_coach = $this->identity_model->new_get_gmt($coach_id);


            // student
            $minutes = $gmt_student[0]->minutes;
            // coach
            $minutes_coach = $gmt_coach[0]->minutes;

            @date_default_timezone_set('UTC');
            // student
            $st  = strtotime($start_time);
            $usertime1 = $st+(60*$minutes);
            $start_hour = date("H:i", $usertime1);

            $et  = strtotime($end_time);
            $usertime2 = $et+(60*$minutes)-(5*60);
            $end_hour = date("H:i", $usertime2);

            // coach

            $st_coach  = strtotime($start_time);
            $usertime1_coach = $st_coach+(60*$minutes_coach);
            $start_hour_coach = date("H:i", $usertime1_coach);

            $et_coach  = strtotime($end_time);
            $usertime2_coach = $et_coach+(60*$minutes_coach)-(5*60);
            $end_hour_coach = date("H:i", $usertime2_coach);

            // $check_max_book_coach_per_day = $this->max_book_coach_per_day($coach_id,$date);
            // if(!$check_max_book_coach_per_day){
            //     $this->messages->add('This coach has exceeded maximum booked today', 'warning');
            //     redirect('student/find_coaches/search/name/');
            // }


                $isValid = $this->isAvailable($coach_id, $date, $start_time, $end_time);

                $message = '';
                if(!$isValid){
                    $this->messages->add('Invalid Appointment Or Coach is Having Day Off', 'warning');
                    $message = 'Invalid Appointment Or Coach is Having Day Off';
                }

               $dayoff = $this->is_day_off($coach_id, $dateconvertcoach,$start_time, $end_time);

                // if dayoff 1, coach cuti
                if($dayoff){
                    $message = "Coach is Having Day Off";
                    $this->messages->add('Coach is Having Day Off', 'warning');

                }

                $token_cost = $token;

                $remain_token = $this->update_token($token_cost);

                if($remain_token < 0){
                    $message = "Not Enough Token";
                    $this->messages->add('Not Enough Token', 'warning');
                }

                $data_schedule = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($coach_id)[0]->minutes, strtotime($date), $start_time, $end_time);
                    // print_r($data_schedule);
                    // echo date('Y-m-d', 1523404800);
                    // exit();


                if(($message == '') && ($remain_token >= 0)){
                    // update token
                    $s_t = $this->identity_model->get_identity('token')->select('id, token_amount')->where('user_id', $this->auth_manager->userid())->get();
                    $r_t = $s_t->token_amount - $token;
                    $data = array(
                        'token_amount' => $r_t,
                    );

                    $u_t = $this->identity_model->get_identity('token')->update($s_t->id, $data);

                    // =====
                    $appointment_id = $this->create_appointment($coach_id, $date, $start_time, $end_time, 'active');

                    $get_date_apd = $this->db->select('date, start_time, end_time')->from('appointments')->where('id',$appointment_id)->get()->result();
                    $new_date_apd_coach = strtotime($get_date_apd[0]->date);
                    $new_start_time_coach = $get_date_apd[0]->start_time;
                    $new_end_time_coach = $get_date_apd[0]->end_time;

                    $convert_coach_plus = $this->schedule_function->convert_book_schedule(($this->identity_model->new_get_gmt($coach_id)[0]->minutes), $new_date_apd_coach, $new_start_time_coach, $new_end_time_coach);

                    $new_date_for_coach = date('Y-m-d', $convert_coach_plus['date']);

                    $valid_appointment = count($this->appointment_model->where('coach_id', $coach_id)->where('date', date('Y-m-d', $date))->where('start_time', $start_time)->where('end_time', $end_time)->where('status', 'active')->get_all());

                    $emailcoach = $this->user_model->select('id, email')->where('id', $coach_id)->get_all();

                    $namecoach = $this->user_profile_model->select('user_id, fullname')->where('user_id', $coach_id)->get_all();

                    $namestudent = $this->user_profile_model->select('user_id, fullname')->where('user_id', $this->auth_manager->userid())->get_all();

                    $emailstudent = $this->user_model->select('id, email')->where('id', $this->auth_manager->userid())->get_all();

                    $message = 'Booking successful';

                    if(($this->db->trans_status() == 1) && ($appointment_id) && ($valid_appointment == 1)) {

                        $this->create_token_history($appointment_id, $token_cost, $remain_token, 1);
                        // messaging to send email and creating notification based on appointment
                        // $this->email_notification_appointment($appointment_id);
                        $message = 'Booking successful';

                        $coach_notification = array(
                            'user_id' => $coach_id,
                            'description' => $namestudent[0]->fullname.' has session booked with you',
                            'status' => 2,
                            'dcrea' => time(),
                            'dupd' => time(),
                        );

                        $student_notification = array(
                            'user_id' => $this->auth_manager->userid(),
                            'description' => 'New session booked with '.$namecoach[0]->fullname,
                            'status' => 2,
                            'dcrea' => time(),
                            'dupd' => time(),
                        );

                        $this->user_notification_model->insert($coach_notification);
                        $this->user_notification_model->insert($student_notification);

                        $student_gmt = $gmt_student[0]->gmt;
                        $coach_gmt = $gmt_coach[0]->gmt;

                        $this->send_email->student_book_coach($emailstudent[0]->email, $emailcoach[0]->email, $namestudent[0]->fullname, $namecoach[0]->fullname, $start_hour, $end_hour, $dateconvert, 'booked', $student_gmt);
                        $this->send_email->notif_coach($emailstudent[0]->email, $emailcoach[0]->email, $namestudent[0]->fullname, $namecoach[0]->fullname, $start_hour_coach, $end_hour_coach, $new_date_for_coach, 'booked', $coach_gmt);


                    } else {

                        $this->rollback_appointment($coach_id, date("Y-m-d", $date), $start_time, $end_time, ($remain_token + $token_cost));
                        $messages = 'Fail to book appointment, please try again.';

                    }

                }


                if($message != 'Booking successful'){
                    $arr_message[] = $message;

                }

                $this->session->set_flashdata('booking_message',$arr_message);
                //redirect('student/upcoming_session');

        }

            $this->session->set_flashdata('booking_message',$arr_message);
            redirect('student/upcoming_session');

    }

    public function old_book_single_coach($coach_id = '', $date_ = '', $start_time_ = '', $end_time_ = '',$token) {
        // for isOnAvailability
        // convert date student


        // @date_default_timezone_set('Etc/GMT+7');
        // $dateconvert_ = date('Y-m-d', $date_);
        // echo $dateconvert_."<br>";

        // @date_default_timezone_set('Etc/GMT7');
        // $dateconvert_ = date('Y-m-d', $date_);
        // echo $dateconvert_."<br>";

        // $gmt_coach = $this->db->select("minutes_val as minutes, gmt_val as gmt")
        //              ->from('user_timezones')
        //              ->where('user_id', $this->auth_manager->userid())
        //              ->get()->result();
        // @date_default_timezone_set('Etc/GMT+'.$gmt_coach[0]->gmt*(1));
        // $dateconvert_ = date('Y-m-d', $date_);
        // echo '+'.$gmt_coach[0]->gmt*(1)." ". $dateconvert_;
        // exit();

        // $chek_date = gmdate('Y-m-d', strtotime($dateconvert_) );
        // if($chek_date < $dateconvert_){
        //     $date_ = date('Y-m-d',date(strtotime("+1 day", strtotime("$dateconvert_"))));
        // } else if($chek_date > $dateconvert_){
        //     $date_ = date('Y-m-d',date(strtotime("-1 day", strtotime("$dateconvert_"))));
        // } else {
        //     $date_ = $dateconvert_;
        // }

        // $date_ = strtotime($date_);
        @date_default_timezone_set('Etc/GMT+0');
        // $dateconvert_ = date('Y-m-d', $date_);
        // $chek_date = gmdate('Y-m-d', strtotime($dateconvert_) );
        // echo $chek_date;
        // exit();


        $start_time_available = $start_time_;
        $end_time_available = $end_time_;

        $date_notif = date('l jS \of F Y', @$date_);

        $convert = $this->schedule_function->convert_book_schedule(-($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes), $date_, $start_time_, $end_time_);
        $date = $convert['date'];
        $dateconvert = date('Y-m-d', $date_);
        $dateconvertcoach = date('Y-m-d', $date);
        $start_time = $convert['start_time'];
        $end_time = $convert['end_time'];

        // timezone
                    $id_student = $this->auth_manager->userid();

                    // student
                    $gmt_student = $this->identity_model->new_get_gmt($id_student);
                    // coach
                    $gmt_coach = $this->identity_model->new_get_gmt($coach_id);


                    // student
                    $minutes = $gmt_student[0]->minutes;
                    // coach
                    $minutes_coach = $gmt_coach[0]->minutes;

                    @date_default_timezone_set('UTC');
                    // student
                    $st  = strtotime($start_time);
                    $usertime1 = $st+(60*$minutes);
                    $start_hour = date("H:i", $usertime1);

                    $et  = strtotime($end_time);
                    $usertime2 = $et+(60*$minutes)-(5*60);
                    $end_hour = date("H:i", $usertime2);

                    // coach

                    $st_coach  = strtotime($start_time);
                    $usertime1_coach = $st_coach+(60*$minutes_coach);
                    $start_hour_coach = date("H:i", $usertime1_coach);

                    $et_coach  = strtotime($end_time);
                    $usertime2_coach = $et_coach+(60*$minutes_coach)-(5*60);
                    $end_hour_coach = date("H:i", $usertime2_coach);

        $check_max_book_coach_per_day = $this->max_book_coach_per_day($coach_id,$date);
        if(!$check_max_book_coach_per_day){
            $this->messages->add('This coach has exceeded maximum booked today', 'warning');
            redirect('student/find_coaches/single_date/');
        }

        try {
            // First of all, let's begin a transaction
            // A set of queries; if one fails, an exception should be thrown
            $isValid = $this->isAvailable($coach_id, $date, $start_time, $end_time);

            if ($isValid) {

                $availability = $this->isOnAvailability($coach_id, date('Y-m-d', $date_));

                if (in_array(array('start_time' => $start_time_available, 'end_time' => $end_time_available), $availability)) {
                    // go to next step

                } else {
                    $this->messages->add('Invalid Time', 'warning');
                    redirect('student/find_coaches/single_date/');
                }
                // begin the transaction to ensure all data created or modified structural

                // $token_cost = $this->coach_token_cost_model->select('token_for_student')->where('coach_id', $coach_id)->get();
                $token_cost = $token;
                // echo "<pre>";
                // print_r($token_cost);
                // exit();
                // updating remaining token student

                $remain_token = $this->update_token($token_cost);


                // if ($this->db->trans_status() === true && $remain_token >= 0 && $this->isAvailable($coach_id, $date, $start_time, $end_time)) {
                if ($this->db->trans_status() === true && $remain_token >= 0) {

                    $appointment_id = $this->create_appointment($coach_id, $date, $start_time, $end_time, 'active');

                    $get_date_apd = $this->db->select('date, start_time, end_time')->from('appointments')->where('id',$appointment_id)->get()->result();
                    $new_date_apd_coach = strtotime($get_date_apd[0]->date);
                    $new_start_time_coach = $get_date_apd[0]->start_time;
                    $new_end_time_coach = $get_date_apd[0]->end_time;

                    $convert_coach_plus = $this->schedule_function->convert_book_schedule(($this->identity_model->new_get_gmt($coach_id)[0]->minutes), $new_date_apd_coach, $new_start_time_coach, $new_end_time_coach);

                    $new_date_for_coach = date('Y-m-d', $convert_coach_plus['date']);

                    $valid_appointment = count($this->appointment_model->where('coach_id', $coach_id)->where('date', date('Y-m-d', $date))->where('start_time', $start_time)->where('end_time', $end_time)->where('status', 'active')->get_all());

                    $emailcoach = $this->user_model->select('id, email')->where('id', $coach_id)->get_all();

                    $namecoach = $this->user_profile_model->select('user_id, fullname')->where('user_id', $coach_id)->get_all();

                    $namestudent = $this->user_profile_model->select('user_id, fullname')->where('user_id', $this->auth_manager->userid())->get_all();

                    $emailstudent = $this->user_model->select('id, email')->where('id', $this->auth_manager->userid())->get_all();


                    // if($idutz == $idutz_coach){
                    //     $start_hour_coach = $start_hour;
                    //     $end_hour_coach = $end_hour;
                    // }

                    // =============

                    // echo $emailstudent[0]->email." - ".$emailcoach[0]->email." - ".$namestudent[0]->fullname." - ".$namecoach[0]->fullname." - ".$start_time." - ".$end_time." - ".$dateconvert;
                    // exit();
                    // echo $this->db->trans_status();
                    // exit();


                    if ($this->db->trans_status() == 1 && $appointment_id && $valid_appointment == 1) {

                        $this->create_token_history($appointment_id, $token_cost, $remain_token, 1);
                        // messaging to send email and creating notification based on appointment
                        // $this->email_notification_appointment($appointment_id);
                        $message = 'Booking successful';

                        $coach_notification = array(
                            'user_id' => $coach_id,
                            'description' => $namestudent[0]->fullname.' has session booked with you',
                            'status' => 2,
                            'dcrea' => time(),
                            'dupd' => time(),
                        );

                        $student_notification = array(
                            'user_id' => $this->auth_manager->userid(),
                            'description' => 'New session booked with '.$namecoach[0]->fullname,
                            'status' => 2,
                            'dcrea' => time(),
                            'dupd' => time(),
                        );

                        $this->user_notification_model->insert($coach_notification);
                        $this->user_notification_model->insert($student_notification);

                        $student_gmt = $gmt_student[0]->gmt;
                        $coach_gmt = $gmt_coach[0]->gmt;

                        // $this->send_email->student_book_coach($emailstudent[0]->email, $emailcoach[0]->email, $namestudent[0]->fullname, $namecoach[0]->fullname, $start_hour, $end_hour, $dateconvert, 'booked', $student_gmt);
                        // $this->send_email->notif_coach($emailstudent[0]->email, $emailcoach[0]->email, $namestudent[0]->fullname, $namecoach[0]->fullname, $start_hour_coach, $end_hour_coach, $new_date_for_coach, 'booked', $coach_gmt);

                        $this->messages->add($message, 'success');

                        redirect('student/find_coaches/book_by_single_date/' . date("Y-m-d", $date));
                    } else {
                        $this->rollback_appointment($coach_id, date("Y-m-d", $date), $start_time, $end_time, ($remain_token + $token_cost));
                        $this->messages->add('Fail to book appointment, please try again.', 'warning');
                        redirect('student/find_coaches/single_date/');
                    }
                } else {
                    $this->messages->add('Not Enough Token', 'warning');
                    redirect('student/find_coaches/single_date/');
                }
            } else {
                $this->messages->add('Invalid Appointment', 'warning');
                redirect('student/find_coaches/single_date/');
            }



            // If we arrive here, it means that no exception was thrown
            // i.e. no query has failed, and we can commit the transaction
            //$this->db->trans_commit();
        } catch (Exception $e) {
            // An exception has been thrown
            // We must rollback the transaction
            $this->db->trans_rollback();
            $this->messages->add('An error has occured, please try again.', 'warning');
            redirect('student/find_coaches/single_date/');
        }
    }

    public function multiple_date() {
        $this->template->title = 'Multiple Date';
        $vars = array(
            'temporary_booking' => $this->appointment_model->where('student_id', $this->auth_manager->userid())->where('status', 'temporary')->get_all(),
        );
        $this->template->content->view('default/contents/find_coach/availability/multiple_date/index', $vars);
        $this->template->publish();
    }


    /**
     * Function block
     * to get all inserted multiple date and make it in to array
     * redirecting to book coach by multiple date by index of all date inserted
     */

    public function book_by_multiple_date() {
        // unset session date before setting the new one
        $this->session->unset_userdata('date_1');
        $this->session->unset_userdata('date_2');
        $this->session->unset_userdata('date_3');
        $this->session->unset_userdata('date_4');
        $this->session->unset_userdata('date_5');

        $select_date = array();

        $i = 1;
        foreach ($this->input->post() as $d) {
            if ($d != '' && $d != $this->input->post('__submit')) {
                $select_date[] = $d;
                $this->session->set_userdata('date_' . $i++, $d);
            }
        }

        $vars = array('select_date' => $select_date);
        $this->session->set_flashdata('my_super_array', $select_date);

        redirect('student/find_coaches/book_by_multiple_date_index/1');
    }

    /**
     * Function availability
     * @param (int)(index) redirecting page by value of search_by
     */
    public function book_by_multiple_date_index($index = '') {

        $this->template->title = 'Detail Multiple Date';
        if (!$this->session->userdata('date_' . $index)) {
            $this->messages->add('Fill the date before search', 'warning');
            redirect('student/find_coaches/multiple_date');
        }

        $data = $this->get_available_coach($this->session->userdata('date_' . $index));
        $select_date = $this->session->flashdata('my_super_array');

        $vars = array(
            'data' => $data,
            'temporary_booking' => $this->appointment_model->where('student_id', $this->auth_manager->userid())->where('status', 'temporary')->get_all(),
            'id_to_token_cost' => $this->coach_token_cost_model->dropdown('coach_id', 'token_for_student'),
            'index' => $index,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'rating' => $this->coach_rating_model->get_average_rate(),
            'select_date' => $select_date
        );
        $this->template->content->view('default/contents/find_coach/book_by_availability/multiple_date/index', $vars);
        $this->template->publish();
    }

    public function book_multiple_coach($coach_id = '', $date = '', $start_time = '', $end_time = '', $index = '') {
//        echo($this->session->userdata('date_1'));
//        echo(date('Y-m-d', $date));
        $convert = $this->schedule_function->convert_book_schedule(-($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes), $date, $start_time, $end_time);
        $date = $convert['date'];
        $start_time = $convert['start_time'];
        $end_time = $convert['end_time'];

        // $check_max_book_coach_per_day = $this->max_book_coach_per_day($coach_id,$date);
        // if(!$check_max_book_coach_per_day){
        //     $this->messages->add('This coach has exceeded maximum booked today', 'warning');
        //     redirect('student/find_coaches/book_by_multiple_date_index/' . $index);
        // }

        //echo('<pre>');print_r($convert);
        //echo(date('Y-m-d', $convert['date'])); exit;
        // memakai fungsi yang sama dengan booking
        // dipakai sementara
        $this->template->title = 'Book Multiple Date Coach';

        if ($this->create_appointment($coach_id, $date, $start_time, $end_time, 'temporary')) {
            $this->messages->add('Session booked temporary', 'success');
        } else {
            $this->messages->add('Session no longer available', 'warning');
        }
        redirect('student/find_coaches/book_by_multiple_date_index/' . $index);
    }

    public function confirm_book_by_multiple_date() {
        $this->template->title = 'Confirm book by multiple data';
        $data = $this->appointment_model->where('student_id', $this->auth_manager->userid())->where('status', 'temporary')->order_by('dcrea', 'asc')->get_all();

        $data_temp = array();
        foreach($data as $d){
            $data_temp[] = array(
                'id' => $d->id,
                'coach_id' => $d->coach_id,
                'data' => $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($d->date), $d->start_time, $d->end_time),
            );
        }
        if (!$data_temp) {
            $this->messages->add('No session booked', 'warning');
            redirect('student/find_coaches/multiple_date');
        } else {
            $vars = array(
                'data' => $data_temp,
                'token_cost' => $this->coach_token_cost_model->dropdown('coach_id', 'token_for_student'),
                'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            );

            $this->template->content->view('default/contents/find_coach/confirm_book_by_multiple_date/index', $vars);
            //publish template
            $this->template->publish();
        }
    }

    public function test_messaging($appointment_id = ''){
        $this->email_notification_appointment($appointment_id);
        echo('Success');
         // exit;
    }

    private function email_notification_appointment($appointment_id = '') {
        $data_appointment = $this->appointment_model->where('id', $appointment_id)->get();

        $data_student = $this->schedule_function->convert_book_schedule(($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes), strtotime($data_appointment->date), $data_appointment->start_time, $data_appointment->end_time);
        $data_coach = $this->schedule_function->convert_book_schedule(($this->identity_model->new_get_gmt($data_appointment->coach_id)[0]->minutes), strtotime($data_appointment->date), $data_appointment->start_time, $data_appointment->end_time);
        $gmt_student = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->timezone;
        $gmt_coach = $this->identity_model->new_get_gmt($data_appointment->coach_id)[0]->timezone;


        // coach and student identity to be used on sending email or creating notifaction database
        $email = $this->user_model->where('id', $data_appointment->coach_id)->or_where('id', $data_appointment->student_id)->dropdown('id', 'email');
        $fullname = $this->identity_model->get_identity('profile')->where('user_id', $data_appointment->coach_id)->or_where('user_id', $data_appointment->student_id)->dropdown('user_id', 'fullname');
        // tube name for messaging action
        $tube = 'com.live.email';
        // tube name for messaging notification
        $database_tube = 'com.live.database';

        $data = array(
            'subject' => 'Session Reminder',
            'email' => $email[$this->auth_manager->userid()],
            //'content' => 'You have an appointment with coach ' . $fullname[$data_appointment->coach_id] . ' , please prepare yourself 5 minutes before start the session at ' . date('l jS \of F Y', $data_student['date']) . ' ' . $data_student['start_time'] . ' until ' . $data_student['end_time'] . ' ' . $gmt_student,
        );
//        $data['headers'] = "MIME-Version: 1.0\r\n";
//
//        $data['headers'] .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Session Reminder')
                .$this->email_structure->content('You have an appointment with coach ' . $fullname[$data_appointment->coach_id] . ' , please prepare yourself 5 minutes before start the session at ' . date('l jS \of F Y', $data_student['date']) . ' ' . $data_student['start_time'] . ' until ' . $data_student['end_time'] . ' ' . $gmt_student)
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');
        // after booked, sending email to student
        $this->queue->push($tube, $data, 'email.send_email');

        //after booked, sending email to coach
        $data['email'] = $email[$data_appointment->coach_id];
        //$data['content'] = 'You have an appointment with student ' . $fullname[$this->auth_manager->userid()] . ' , please prepare yourself 5 minutes before start the session at ' . date('l jS \of F Y', $data_coach['date']) . ' ' . $data_coach['start_time'] . ' until ' . $data_coach['end_time'] . ' ' . $gmt_coach;

        $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Session Reminder')
                .$this->email_structure->content('You have an appointment with student ' . $fullname[$this->auth_manager->userid()] . ' , please prepare yourself 5 minutes before start the session at ' . date('l jS \of F Y', $data_coach['date']) . ' ' . $data_coach['start_time'] . ' until ' . $data_coach['end_time'] . ' ' . $gmt_coach)
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');

        $this->queue->push($tube, $data, 'email.send_email');

        // after booked, creating notification for student and coach
        $student_notification = array(
            'user_id' => $this->auth_manager->userid(),
            'description' => 'Your session will be started at ' . date('l jS \of F Y', $data_student['date']) . ' from ' . $data_student['start_time'] . ' until ' . $data_student['end_time'] . ' with coach ' . $fullname[$data_appointment->coach_id] . ' ' . $gmt_student,
            'status' => '2'
        );
        $coach_notification = array(
            'user_id' => $data_appointment->coach_id,
            'description' => 'Your session will be started at ' . date('l jS \of F Y', $data_coach['date']) . ' from ' . $data_coach['start_time'] . ' until ' . $data_coach['end_time'] . ' with student ' . $fullname[$this->auth_manager->userid()] . ' ' . $gmt_coach,
            'status' => '2'
        );
        $this->db->trans_begin();
        $this->user_notification_model->insert($student_notification);
        $this->user_notification_model->insert($coach_notification);
        if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            $this->messages->add('Try again, something wrong while inserting/updating data to database', 'warning');
            return;
        }
        $this->db->trans_commit();

        // 2 hours before session time
        $reminder = $this->time_reminder_before_session($data_appointment->date . " " . $data_appointment->start_time, (10 * 60));
        // reminder for student to rate coach after session time finished
        $reminder2 = $this->time_reminder_before_session($data_appointment->date . " " . $data_appointment->end_time, (40 * 60));

        if ($reminder2 && $reminder2 >= 0) {
            // sending email if the status of appointment still active
            $data['subject'] = 'Rate Coach';
            $data["appointment_id"] = $data_appointment->id;
            //$data['content'] = 'The session with coach ' . $fullname[$data_appointment->coach_id] . ' has been done. Please rate the coach.';

            $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Rate Coach')
                .$this->email_structure->content('The session with coach ' . $fullname[$data_appointment->coach_id] . ' has been done. Please rate the coach.')
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');

            $data['email'] = $email[$this->auth_manager->userid()];
            // email to remind coach and student to attend the session before it begin
            $this->queue->later($reminder2, $tube, $data, 'email.email_valid_appointment');

            // notification
            // update student's notification for messaging
            $student_notification['user_id'] = $this->auth_manager->userid();
            $student_notification['description'] = 'The session with coach ' . $fullname[$data_appointment->coach_id] . ' has been done. Please rate the coach.';
            $student_notification['status'] = 2;
            $student_notification['dcrea'] = time();
            $student_notification['dupd'] = time();

            $data_reminder_student = array(
                'table' => 'user_notifications',
                'content' => $student_notification,
                'appointment_id' => $data_appointment->id
            );

            $this->queue->later($reminder2, $database_tube, $data_reminder_student, 'database.insert_while_appointment_still_valid');

            // for creating  rating of session
            $session_rating['appointment_id'] = $data_appointment->id;
            $session_rating['status'] = 'unrated';
            $session_rating['dcrea'] = time();
            $session_rating['dupd'] = time();

            $data_rating = array(
                'table' => 'coach_ratings',
                'content' => $session_rating,
                'appointment_id' => $data_appointment->id
            );

            $this->queue->later($reminder2, $database_tube, $data_rating, 'database.insert_while_appointment_still_valid');
        }

        if ($reminder && $reminder > 0) {
            // sending email if the status of appointment still active
            $data['subject'] = 'Last Session Reminder';
            $data["appointment_id"] = $data_appointment->id;
            $data['content'] = 'Soon you will have an appointment with coach ' . $fullname[$data_appointment->coach_id] . ', please prepare yourself 5 minutes before start the session at ' . $data_student['date'] . ' ' . $data_student['start_time'] . ' until ' . $data_student['end_time'] . ' ' . $gmt_student;
            // email to remind coach and student to attend the session before it begin
            $this->queue->later($reminder, $tube, $data, 'email.email_valid_appointment');
            $data['email'] = $email[$this->auth_manager->userid()];
            //$data['content'] = 'Soon you will have an appointment with student ' . $fullname[$this->auth_manager->userid()] . ', please prepare yourself 5 minutes before start the session at ' . $data_coach['date'] . ' ' . $data_coach['start_time'] . ' until ' . $data_coach['end_time'];

            $data['content'] = $this->email_structure->header()
                .$this->email_structure->title('Session Reminder')
                .$this->email_structure->content('Soon you will have an appointment with student ' . $fullname[$this->auth_manager->userid()] . ', please prepare yourself 5 minutes before start the session at ' . $data_coach['date'] . ' ' . $data_coach['start_time'] . ' until ' . $data_coach['end_time'])
                //.$this->email_structure->button('JOIN SESSION')
                .$this->email_structure->footer('');

            $this->queue->later($reminder, $tube, $data, 'email.email_valid_appointment');

            // creating notification reminder for student and coach
            $student_notification['user_id'] = $this->auth_manager->userid();
            $student_notification['description'] = 'Reminder! Your appointment will be started at ' . date('l jS \of F Y', $data_student['date']) . ' from ' . $data_student['start_time'] . ' until ' . $data_student['end_time'] . ' with coach ' . $fullname[$data_appointment->coach_id] . ' ' . $gmt_student;
            $student_notification['status'] = 2;
            $student_notification['dcrea'] = time();
            $student_notification['dupd'] = time();

            // update coach's notification for messaging
            $coach_notification['user_id'] = $data_appointment->coach_id;
            $coach_notification['description'] = 'Reminder! Your appointment will be started at ' . date('l jS \of F Y', $data_coach['date']) . ' from ' . $data_coach['start_time'] . ' until ' . $data_coach['end_time'] . ' with student ' . $fullname[$data_appointment->student_id] . ' ' .$gmt_coach;
            $coach_notification['status'] = 2;
            $coach_notification['dcrea'] = time();
            $coach_notification['dupd'] = time();

            // student's data for reminder messaging
            // IMPORTANT : array index in content must be in mutual with table field in database
            $data_student = array(
                'table' => 'user_notifications',
                'content' => $student_notification,
                'appointment_id' => $data_appointment->id
            );

            // coach's data for reminder messaging
            // IMPORTANT : array index in content must be in mutual with table field in database
            $data_coach = array(
                'table' => 'user_notifications',
                'content' => $coach_notification,
                'appointment_id' => $data_appointment->id
            );

            $this->queue->later($reminder, $database_tube, $data_student, 'database.insert_while_appointment_still_valid');
            $this->queue->later($reminder, $database_tube, $data_coach, 'database.insert_while_appointment_still_valid');
        }
    }

    public function delete_temporary_appointment($id = '') {
        $data = $this->appointment_model->select('status')->where('id', $id)->get();
        if ($data && $data->status == 'temporary') {
            if ($this->appointment_model->delete($id)) {
                $this->messages->add('Delete succeded ', 'success');
            }
        }
        redirect('student/find_coaches/confirm_book_by_multiple_date');
    }

    public function schedule_detail($id = '') {
        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id', $id)->order_by('id', 'asc')->get_all();
        $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;
        if (!$schedule_data) {
            redirect('student/find_coaches/single_date');
        }

        $schedule = array();
        //foreach($schedule_data as $s){
        for ($i = 0; $i < count($schedule_data); $i++) {
            $schedule[$schedule_data[$i]->day] = $this->schedule_block($id, $schedule_data[$i]->day, $schedule_data[$i]->start_time, $schedule_data[$i]->end_time, $schedule_data[$this->convert_gmt($i, $minutes)]->day, $schedule_data[$this->convert_gmt($i, $minutes)]->start_time, $schedule_data[$this->convert_gmt($i, $minutes)]->end_time);
            if(!$schedule[$schedule_data[$i]->day]){
                $schedule[$schedule_data[$i]->day] = array(
                    array(
                        'start_time' => '00:00:00',
                        'end_time' => '00:00:00',
                    )
                );
            }

        }
        $vars = array(
            'coach_id' => $id,
            'schedule' => $schedule,
        );
        $this->template->content->view('default/contents/find_coach/schedule_detail', $vars);

        //publish template
        $this->template->publish();
    }

    private function schedule_block($coach_id = '', $day1 = '', $start_time1 = '', $end_time1 = '', $day2 = '', $start_time2 = '', $end_time2 = '') {
        $schedule1 = $this->block($coach_id, $day1, $start_time1, $end_time1);
        $schedule2 = $this->block($coach_id, $day2, $start_time2, $end_time2);

        $schedule = array();
        $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;

        $time = strtotime('00:00:00');
        $startTime = date("H:i:s", strtotime((-$minutes) . 'minutes', $time));
        $endTime = date("H:i:s", strtotime('+30 minutes', $time));

        if ($minutes == 0) {
            $schedule = $schedule1;
        } else if ($minutes > 0) {
            foreach ($schedule2 as $s2) {
                $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['start_time'])));
                $schedule_temp2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['end_time'])));
                if (strtotime($schedule_temp) < strtotime($s2['start_time'])) {
//                        break;
                    $s2['start_time'] = $schedule_temp;
                    $s2['end_time'] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['end_time'])));
                    $schedule[] = array(
                        'start_time' => $this->convertTime($s2['start_time']),
                        'end_time' => $this->convertTime($s2['end_time']),
                    );
                } else if (strtotime($schedule_temp) > strtotime($s2['start_time']) && strtotime($schedule_temp2) < strtotime($s2['end_time'])) {
                    $schedule[] = array(
                        'start_time' => '00:00:00',
                        'end_time' => $this->convertTime($schedule_temp2),
                    );
                }
            }


            foreach ($schedule1 as $s1) {
                $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s1['start_time'])));
                if (strtotime($schedule_temp) < strtotime($s1['start_time']) || $schedule_temp == '00:00:00') {
                    break;
                } else {
                    $s1['start_time'] = $schedule_temp;
                    $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s1['end_time'])));
                    if (strtotime($schedule_temp) < strtotime($s1['end_time']) || $schedule_temp == '00:00:00') {
                        $s1['end_time'] = '23:59:59';
                    } else {
                        $s1['end_time'] = $schedule_temp;
                    }

                    $schedule[] = array(
                        'start_time' => $this->convertTime($s1['start_time']),
                        'end_time' => $this->convertTime($s1['end_time']),
                    );
                }
            }
        } else {
            foreach ($schedule1 as $s1) {
                $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s1['end_time'])));
                $schedule_temp2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s1['start_time'])));
                if (strtotime($schedule_temp) > strtotime($s1['end_time']) || $schedule_temp == '00:00:00') {
                    //break;
                } else if (strtotime($schedule_temp2) > strtotime($s1['start_time']) && strtotime($schedule_temp) < strtotime($s1['end_time'])) {
                    //$s1['start_time'] = '00:00:00';
                    $schedule[] = array(
                        'start_time' => '00:00:00',
                        'end_time' => $this->convertTime($schedule_temp),
                    );
                } else if (strtotime($schedule_temp2) <= strtotime($s1['start_time'])) {
                    $schedule[] = array(
                        'start_time' => $this->convertTime($schedule_temp2),
                        'end_time' => $this->convertTime($schedule_temp),
                    );
                }
            }

            foreach ($schedule2 as $s2) {
                $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['start_time'])));
                $schedule_temp2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['end_time'])));
                if (strtotime($schedule_temp) < strtotime($s2['start_time'])) {
                    break;
                } else if (strtotime($schedule_temp) > strtotime($s2['start_time']) && strtotime($schedule_temp2) > strtotime($s2['end_time'])) {
                    $schedule[] = array(
                        'start_time' => $this->convertTime($schedule_temp),
                        'end_time' => $this->convertTime($schedule_temp2),
                    );
                } else if (strtotime($schedule_temp) > strtotime($s2['start_time']) && strtotime($schedule_temp2) < strtotime($s2['end_time'])) {
                    $schedule[] = array(
                        'start_time' => $this->convertTime($schedule_temp),
                        'end_time' => '23:59:59',
                    );
                }
            }
        }
        return $this->joinTime($schedule);
    }

    private function convert_gmt($index = '', $minutes = '') {
        if ($minutes > 0) {
            return (($index - 1) >= 0 ? ($index - 1) : 6);
        } else {
            return (($index + 1) <= 6 ? ($index + 1) : 0);
        }
    }

    /**
     * Function block
     * @param (string)(coach_id) coach id to get schedule
     * @param (date)(day) detail of day
     * @param (time)(start_time) detail of start time
     * @param (time)(end_time) detail of end time
     * return schedule divide by offwork of coach before converted to by coach gmt
     */

    private function block($coach_id = '', $day = '', $start_time = '', $end_time = '') {
        $offwork = $this->offwork_model->get_offwork($coach_id, $day);
        $schedule_temp = array();
        if ($offwork) {
            //foreach($offwork as $o){
            for ($i = 0; $i <= count($offwork); $i++) {
                if ($i == 0) {
                    $schedule_temp[] = array(
                        'start_time' => $start_time,
                        'end_time' => $offwork[0]->start_time,
                    );
                } else if ($i > 0 && $i < (count($offwork))) {
                    $schedule_temp[] = array(
                        'start_time' => $offwork[$i - 1]->end_time,
                        'end_time' => $offwork[$i]->start_time,
                    );
                } else if ($i == (count($offwork))) {
                    $schedule_temp[] = array(
                        'start_time' => $offwork[$i - 1]->end_time,
                        'end_time' => $end_time,
                    );
                }
            }
        } else {
            $schedule_temp[] = array(
                'start_time' => $start_time,
                'end_time' => $end_time,
            );
        }

        return $schedule_temp;
    }

    private function convertTime($time = ''){
        // if(date("H:i", strtotime(1 . 'minutes', strtotime($time))) != '00:00' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '01' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '21' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '31'){
        if(date("H:i", strtotime(1 . 'minutes', strtotime($time))) != '00:00' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '01' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '11' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '21' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '31' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '41' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '51' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '06' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '16' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '26' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '36' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '46' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '54' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '56'){

        // if(date("H:i", strtotime(1 . 'minutes', strtotime($time))) != '00:00' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '01' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '11' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '21' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '31' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '41' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '51' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '06' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '16' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '26' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '36' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '46'){
            return date("H:i", strtotime(1 . 'minutes', strtotime($time)));
        }
        else{
            return $time;
        }
    }

    private function joinTime($schedule = ''){
        $schedule_temp = array();
        if(count($schedule) > 1){
            for($i=0;$i<(count($schedule));$i++){
                if($schedule[$i]['start_time'] != $schedule[$i]['end_time']){
                    if($i<(count($schedule)-1) && strtotime($schedule[$i]['end_time']) == strtotime($schedule[$i+1]['start_time'])){
                        $schedule_temp[] = array(
                            'start_time' => $schedule[$i]['start_time'],
                            'end_time' => $schedule[$i+1]['end_time'],
                        );
                        $i++;
                    }
                    else{
                        $schedule_temp[] = array(
                            'start_time' => $schedule[$i]['start_time'],
                            'end_time' => $schedule[$i]['end_time'],
                        );
                    }
                }
            }
        }
        else if(count($schedule) == 1){
            if($schedule[0]['start_time'] != $schedule[0]['end_time']){
                $schedule_temp[] = array(
                    'start_time' => $schedule[0]['start_time'],
                    'end_time' => $schedule[0]['end_time'],
                );
            }
        }

        return $schedule_temp;
    }

    /**
     * Function availability
     * @param (string)(search_by) redirecting page by value of search_by
     * @param (string)(coach_id) coach id to get schedule
     * @param (date)(date) detail of date
     */

    public function availability($search_by = '', $coach_id = '', $date_ = '') {
        $this->template->title = 'Availability';
        // print_r($date_);
        if (!$date_ || !$coach_id) {
            //redirect(home);
            $vars = array();
            $this->template->content->view('default/contents/find_coach/availability', $vars);

            //publish template
            $this->template->publish();
        }

        // checking if the date is valid
        if (!$this->is_date_available(trim($date_), 0)) {
            $vars = array();
            $this->template->content->view('default/contents/find_coach/availability', $vars);
        }
         // checking if the date is in day off
        if ($this->is_day_off($coach_id, $date_) == true) {

            $vars = array();
            $this->template->content->view('default/contents/find_coach/availability', $vars);
        }

        // getting the day of $date
        // getting gmt minutes
        $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;

        $date = strtotime($date_);
        //print_r(date('Y-m-d', $date));
        // getting day and day after or before based on gmt
        $day = strtolower(date('l', $date));
        $day2 = $this->day_index[$this->convert_gmt(array_search($day, $this->day_index), $minutes)];

        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student
        $appointment = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->get_all();
        // appointment with status temporary considered available for other student but not for student who is in the appointment and
        // appointment where the student has make an appoinment on the specific date, so there will be no the same start time and end time to be shown to the student from other coach
        $appointment_student = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', date("Y-m-d", $date))->get_all();
        // appointment coach in class
        $appointment_class = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', date("Y-m-d", $date))->get_all();


        // storing appointment to an array so can easily on searching / no object value inside
                $appointment_start_time_temp = array();
                $appointment_end_time_temp = array();

                foreach ($appointment as $a) {
                    if($minutes > 0){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                        }
                    }
                    else if($minutes < 0){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                        }
                    }
                    else if($minutes == 0){
                        $appointment_start_time_temp[] = $a->start_time;
                        $appointment_end_time_temp[] = $a->end_time;
                    }
                }

                // storing class meeting days to appointment temp
                foreach ($appointment_class as $a) {
                    if($minutes > 0){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                        }
                    }
                    else if($minutes < 0){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                        }
                    }
                    else if($minutes == 0){
                        $appointment_start_time_temp[] = $a->start_time;
                        $appointment_end_time_temp[] = $a->end_time;
                    }
                }

                foreach ($appointment_student as $a) {
                    if($minutes > 0){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                        }
                    }
                    else if($minutes < 0){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                        }
                    }
                    else if($minutes == 0){
                        $appointment_start_time_temp[] = $a->start_time;
                        $appointment_end_time_temp[] = $a->end_time;
                    }
                }


                if($minutes > 0){
                    $date2 = date("Y-m-d", strtotime('-1 day'.date("Y-m-d",$date)));

                    $appointment2 = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $date2)->get_all();
                    $appointment_student2 = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', $date2)->get_all();
                    $appointment_class2 = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', $date2)->get_all();

                    foreach($appointment2 as $a){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                        }
                    }
                    foreach($appointment_student2 as $a){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                        }
                    }
                    foreach($appointment_class2 as $a){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                        }
                    }

                }
                else if($minutes < 0){
                    $date2 = date("Y-m-d", strtotime('+1 day'.date("Y-m-d",$date)));
                    $appointment2 = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $date2)->get_all();
                    $appointment_student2 = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', $date2)->get_all();
                    $appointment_class2 = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', $date2)->get_all();

                    foreach($appointment2 as $a){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                        }
                    }
                    foreach($appointment_student2 as $a){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                        }
                    }
                    foreach($appointment_class2 as $a){
                        if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                            $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                            $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                        }
                    }
                }


                //getting all data
                $schedule_data1 = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
                $schedule_data2 = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day2)->get();

                $availability = $this->schedule_block($coach_id, $day, $schedule_data1->start_time, $schedule_data1->end_time, $schedule_data2->day, $schedule_data2->start_time, $schedule_data2->end_time);

                $minutes_definer = $minutes * -1;
                $def_zero = strtotime('00:00:00');
                $def_calc = strtotime($minutes_definer.'minutes', $def_zero);
                $hour_definer = date('H:i',$def_calc);

                //edited new schedules start ===================================
                $pullsched1 = $this->db->distinct()->select('s_block')
                    ->from('new_schedules')
                    ->where('coach_id', $coach_id)
                    ->where('s_day', $day)
                    ->where('s_start_time <=', $hour_definer)
                    ->get()->result();

                $pullsched2 = $this->db->distinct()->select('s_block')
                    ->from('new_schedules')
                    ->where('coach_id', $coach_id)
                    ->where('s_day', $day2)
                    ->where('s_start_time >=', $hour_definer)
                    ->get()->result();

                $pullsched_merge = array_merge($pullsched1, $pullsched2);

                $unique = array();
                foreach ($pullsched_merge as $object) {
                    if (isset($unique[$object->s_block])) {
                        continue;
                    }
                    $unique[$object->s_block] = $object;
                }

                $total_block_f = count($unique);

                $define_block = array_column($unique, 's_block');

                // echo "<pre>";print_r($define_block);exit();

                $allscheds = array();

                for($i=0;$i<$total_block_f;$i++){
                  $pullsched = $this->db->select('*')
                      ->from('new_schedules')
                      ->where('coach_id', $coach_id)
                      ->where('s_block', $define_block[$i])
                      ->get()->result();

                  if(count($pullsched) > 1){
                    $getblock  = $pullsched[0]->s_block;

                    $getday0   = $pullsched[0]->s_day;
                    $getstart0 = $pullsched[0]->s_start_time;

                    $st_str0 = strtotime($getday0.', '.$getstart0);
                    $st_cal0 = strtotime($minutes.'minutes', $st_str0);
                    $st_print0 = date('H:i',$st_cal0);

                    $getday1   = $pullsched[1]->s_day;
                    $getstart1 = $pullsched[1]->s_end_time;

                    $st_str1 = strtotime($getday1.', '.$getstart1);
                    $st_cal1 = strtotime($minutes.'minutes', $st_str1);
                    $st_print1 = date('H:i',$st_cal1);

                    $push_day = date('l',$st_cal0);
                    $getid = $pullsched[0]->id;

                    $push_sched = array(
                      'start_time' => $st_print0.':00',
                      'end_time' => $st_print1.':00'
                    );

                    array_push($allscheds, $push_sched);

                    // echo "<pre>";print_r($st_print0);exit();
                  }else{
                    $getblock  = @$pullsched[0]->s_block;

                    $getday0   = @$pullsched[0]->s_day;
                    $getstart0 = @$pullsched[0]->s_start_time;
                    $getend0 = @$pullsched[0]->s_end_time;

                    $st_str0 = strtotime($getday0.', '.$getstart0);
                    $st_cal0 = strtotime($minutes.'minutes', $st_str0);
                    $st_print0 = date('H:i',$st_cal0);

                    $st_str1 = strtotime($getday0.', '.$getend0);
                    $st_cal1 = strtotime($minutes.'minutes', $st_str1);
                    $st_print1 = date('H:i',$st_cal1);

                    $push_day = date('l',$st_cal0);
                    $getid = @$pullsched[0]->id;

                    $push_sched = array(
                      'start_time' => $st_print0.':00',
                      'end_time' => $st_print1.':00'
                    );

                    array_push($allscheds, $push_sched);
                  }

                  // echo "<pre>";print_r($allscheds);exit();
                }

                $check_url = base_url();
                // $check_url = "https://live.dyned.com";
                if (strpos($check_url, 'live.dyned.com') !== false) {
                  // exit('a');
                } else {
                  $availability = $allscheds;
                  // exit('aa');
                }

                // echo "<pre>";print_r($allscheds);exit();

                //edited new schedules end =====================================


                $date_parameter = strtotime($date_);
                $availability_temp = array();
                $availability_exist;
                foreach ($availability as $a) {
                    $duration = (strtotime($a['end_time']) - strtotime($a['start_time'])) / ($this->session_duration($this->auth_manager->partner_id()) * 60);
                    if ($duration >= 1) {
                        for ($i = 0; $i < $duration; $i++) {
                            $availability_exist = array(
                                // adding  minutes for every session
                                'start_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id()) * 60) * ($i))),
                                'end_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id()) * 60) * ($i + 1))),
                            );
                            // checking if the time is not out of coach schedule
                            if(strtotime($availability_exist['end_time']) <= strtotime($a['end_time'])){
                                // checking if availability is existed in the appointment
                                if (in_array($availability_exist['start_time'], $appointment_start_time_temp) && in_array($availability_exist['end_time'], $appointment_end_time_temp)) {
                                    // no action
                                } else {
                                    // storing availability that still active and not been boooked yet
                                    if($this->isValidAppointment($availability_exist['start_time'], $availability_exist['end_time'], $appointment_start_time_temp, $appointment_end_time_temp)){
                                                // @date_default_timezone_set('Etc/GMT'.(-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                                @date_default_timezone_set('Etc/GMT'.(-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                                // if ($date_ == date('Y-m-d', strtotime(date('Y-m-d') . ' + 2 days'))) {
                                                //     exit('hai');
                                                //     if (DateTime::createFromFormat('H:i:s', $availability_exist['start_time']) >= DateTime::createFromFormat('H:i:s', date('H:i:s'))) {
                                                //         $availability_temp[] = $availability_exist;
                                                //     }
                                                // } else {
                                                    $availability_temp[] = $availability_exist;
                                                // }
                                    }
                                    if($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes > 0){
                                        @date_default_timezone_set('Etc/GMT'.($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 : $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                    }
                                }
                            }
                        }
                    }
                }

        $vars = array(
            'availability' => $availability_temp,
            'coach_id' => $coach_id,
            'date' => $date_parameter,
            'date_title' => strtotime($date_),
            'search_by' => $search_by,
            'cost' => $this->coach_token_cost_model->select('token_for_student')->where('coach_id', $coach_id)->get()
        );
        // echo "<pre>";print_r($allscheds);exit();
//        echo('<pre$vars
//        print_r(date('Y-m-d','1450962000')); exit;
        $this->template->content->view('default/contents/find_coach/availability', $vars);

        //publish template
        $this->template->publish();
    }

    private function convertAppointment($start_time = '', $end_time = ''){
        $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;

        if($minutes > 0){

        }
    }

    private function isOnAvailability($coach_id = '', $date_ = '') {
        if (!$date_ || !$coach_id) {
            //redirect(home);
            $vars = array();
            $this->template->content->view('default/contents/find_coach/availability', $vars);

            //publish template
            $this->template->publish();
        }

        // checking if the date is valid
        // if (!$this->is_date_available(trim($date_), 0)) {
        if (!$this->is_date_available(trim($date_), -1)) {
            $vars = array();
            $this->template->content->view('default/contents/find_coach/availability', $vars);
        }

        // checking if the date is in day off
        if ($this->is_day_off($coach_id, $date_) == true) {
            $vars = array();
            $this->template->content->view('default/contents/find_coach/availability', $vars);
        }

        // getting the day of $date
        // getting gmt minutes
        $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;
        $date = strtotime($date_);
        // getting day and day after or before based on gmt
        $day = strtolower(date('l', $date));
        $day2 = $this->day_index[$this->convert_gmt(array_search($day, $this->day_index), $minutes)];

        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student
        $appointment = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->get_all();
        // appointment with status temporary considered available for other student but not for student who is in the appointment and
        // appointment where the student has make an appoinment on the specific date, so there will be no the same start time and end time to be shown to the student from other coach
        $appointment_student = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', date("Y-m-d", $date))->get_all();
        // appointment coach in class
        $appointment_class = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', date("Y-m-d", $date))->get_all();

        // storing appointment to an array so can easily on searching / no object value inside
        $appointment_start_time_temp = array();
        $appointment_end_time_temp = array();

        // getting all unavailable schedule to be not shown on coach availability
        foreach ($appointment as $a) {
            if($minutes > 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            else if($minutes < 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            else if($minutes == 0){
                $appointment_start_time_temp[] = $a->start_time;
                $appointment_end_time_temp[] = $a->end_time;
            }
        }
        // storing class meeting days to appointment temp
        foreach ($appointment_class as $a) {
            if($minutes > 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            else if($minutes < 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            else if($minutes == 0){
                $appointment_start_time_temp[] = $a->start_time;
                $appointment_end_time_temp[] = $a->end_time;
            }
        }

        foreach ($appointment_student as $a) {
            if($minutes > 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            else if($minutes < 0){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            else if($minutes == 0){
                $appointment_start_time_temp[] = $a->start_time;
                $appointment_end_time_temp[] = $a->end_time;
            }
        }

        if($minutes > 0){
            $date2 = date("Y-m-d", strtotime('-1 day'.date("Y-m-d",$date)));
            $appointment2 = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $date2)->get_all();
            $appointment_student2 = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', $date2)->get_all();
            $appointment_class2 = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', $date2)->get_all();

            foreach($appointment2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            foreach($appointment_student2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }
            foreach($appointment_class2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)))) < strtotime($a->start_time)){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)));
                }
            }

        }
        else if($minutes < 0){
            $date2 = date("Y-m-d", strtotime('+1 day'.date("Y-m-d",$date)));
            $appointment2 = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $date2)->get_all();
            $appointment_student2 = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('date', $date2)->get_all();
            $appointment_class2 = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', $date2)->get_all();
            foreach($appointment2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            foreach($appointment_student2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
            foreach($appointment_class2 as $a){
                if(strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time)))) > strtotime($a->end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00'){
                    $appointment_start_time_temp[] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->start_time)));
                    $appointment_end_time_temp[] = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($a->end_time))));
                }
            }
        }



        //getting all data
        $schedule_data1 = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
        $schedule_data2 = $this->schedule_model->select('id, user_id, day, start_time, end_time')->where('user_id', $coach_id)->where('day', $day2)->get();

        $availability = $this->schedule_block($coach_id, $day, $schedule_data1->start_time, $schedule_data1->end_time, $schedule_data2->day, $schedule_data2->start_time, $schedule_data2->end_time);



        $availability_temp = array();
        $availability_exist;
        foreach ($availability as $a) {
            $duration = (strtotime($a['end_time']) - strtotime($a['start_time'])) / ($this->session_duration($this->auth_manager->partner_id()) * 60);
            if ($duration >= 1) {
                for ($i = 0; $i < $duration; $i++) {
                    $availability_exist = array(
                        // adding  minutes for every session
                        'start_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id()) * 60) * ($i))),
                        'end_time' => date('H:i:s', strtotime($a['start_time']) + (($this->session_duration($this->auth_manager->partner_id()) * 60) * ($i + 1))),
                    );

                    // checking if the time is not out of coach schedule
                    if(strtotime($availability_exist['end_time']) <= strtotime($a['end_time'])){
                        // checking if availability is existed in the appointment
                        if (in_array($availability_exist['start_time'], $appointment_start_time_temp) && in_array($availability_exist['end_time'], $appointment_end_time_temp)) {
                            // no action
                        } else {
                            // storing availability that still active and not been boooked yet
                            if($this->isValidAppointment($availability_exist['start_time'], $availability_exist['end_time'], $appointment_start_time_temp, $appointment_end_time_temp)){
                                if(!$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes == 0){
                                    @date_default_timezone_set('Etc/GMT'.(-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                }
                                        // if ($date_ == date('Y-m-d', strtotime(date('Y-m-d') . ' + 2 days'))) {
                                        //     if (DateTime::createFromFormat('H:i:s', $availability_exist['start_time']) >= DateTime::createFromFormat('H:i:s', date('H:i:s'))) {
                                        //         $availability_temp[] = $availability_exist;
                                        //     }
                                        // } else {
                                            $availability_temp[] = $availability_exist;
                                        // }

                                        // mengatasi tanggal yang tidak sesuai
                                        if($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes <= 0){
                                            @date_default_timezone_set('Etc/GMT'.($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60 : $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes/60));
                                        }
                            }
                        }
                    }
                }
            }
        }

        return $availability_temp;
    }

    private function isCoachAvailable($coach_id = '', $date = '') {

    }

    /* function opentok(){
        $opentok = new OpenTok($this->config->item('opentok_key'), $this->config->item('opentok_secret'));
        $gensession = $opentok->createSession(array('mediaMode' => MediaMode::RELAYED));
        $session = $gensession->getSessionId();
        $token = $gensession->generateToken(array('expireTime' => time()+(7 * 24 * 60 * 60)));


    } */

     private function create_appointment($coach_id = '', $date = '', $start_time = '', $end_time = '', $appointment_status = '') {
//        print_r(date('Y-m-d', $date));
//        print_r($start_time);
//        print_r($end_time);
//
//
//        $this->db->trans_rollback();
//        exit;
        //$status = false;
        // getting the day of $date
        $id    = $this->auth_manager->userid();
        $check_sess = $this->db->select('session_type')
                    ->from('user_profiles')
                    ->where('user_id',$id)
                    ->get()->result();

        $day = strtolower(date('l', $date));
        $schedule_data = $this->schedule_model->select('id, start_time, end_time')->where('user_id', $coach_id)->where('day', $day)->get();
        // Retrieve post
        // edit dari sini
        if($check_sess[0]->session_type == '0'){
          // exit('a');
          $opentok = new OpenTok($this->config->item('opentok_key'), $this->config->item('opentok_secret'));
          $sessionOptions = array(
              'archiveMode' => ArchiveMode::ALWAYS,
              'mediaMode' => MediaMode::ROUTED
          );
          $gensession = $opentok->createSession($sessionOptions);
          $session    = $gensession->getSessionId();
          $app_type = '0';
        }else if($check_sess[0]->session_type == '1'){
          $session = date("Y-m-d", $date)."".$id."".$coach_id."".$start_time."".$end_time;
          // echo "<pre>";print_r($session);exit();
          // exit('b');
          $app_type = '1';
        }

        // $token = $opentok->generateToken($session, array(
        //                                  'expireTime' => time()+(7 * 24 * 60 * 60)
        //                                  ));
        // =========
        // echo "<pre>";
        // print_r($gensession);
        // exit();
        if($session == ''){
            $message = 'Booking failed';
            $this->messages->add($message, 'success');
            redirect('student/find_coaches/single_date');
        }
        $booked = array(
            'student_id' => $this->auth_manager->userid(),
            'coach_id' => $coach_id,
            'schedule_id' => $schedule_data->id,
            'date' => date("Y-m-d", $date),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => $appointment_status,
            'session' => $session,
            'app_type' => $app_type
        );
        //  echo "<pre>";
        // print_r($booked);
        // exit();

        //$isValid = $this->isAvailable($coach_id, $date, $start_time, $end_time);
        $this->db->trans_begin();
        //if ($isValid) {
            // Inserting and checking
            $appointment_id = $this->appointment_model->insert($booked);
            $status = $appointment_id;
//        } else if (!$isValid) {
//            $this->db->trans_rollback();
//            $status = false;
//        }

        if ($appointment_id && $status == true && $this->db->trans_status() === true) {
            $this->db->trans_commit();
            return $appointment_id;
        } else {
            $this->db->trans_rollback();
            return false;
        }
    }
    // used when confirmation book coach with multiple date
    private function update_appointment($appointment_id) {
        $status = false;
        $data_appointment = $this->appointment_model->where('id', $appointment_id)->get();
        $invalid_appointment = $this->appointment_model->where('coach_id', $data_appointment->coach_id)->where('date', $data_appointment->date)->where('start_time', $data_appointment->start_time)->where('end_time', $data_appointment->end_time)->where('status', 'active')->get();

        // Retrieve post
        $booked = array(
            'status' => 'active',
        );
        $this->db->trans_begin();
        if ($invalid_appointment) {
            $this->db->trans_rollback();
            $status = false;
        } else {
            $this->appointment_model->update($appointment_id, $booked);
            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $status = true;
            } else {
                $this->db->trans_rollback();
                $status = false;
            }
        }

        return $status;
    }

    private function update_token($cost = '') {
        $status = false;
        $student_token = $this->identity_model->get_identity('token')->select('id, token_amount')->where('user_id', $this->auth_manager->userid())->get();
        //$coach_cost = $this->coach_token_cost_model->select('token_for_student')->where('coach_id', $coach_id)->get();
        if ($student_token->token_amount < $cost) {
            $status = false;
        } else if ($student_token->token_amount >= $cost) {
            $remain_token = $student_token->token_amount - $cost;
            $status = true;
        }

        if ($status == true) {
            return $remain_token;
        } else {
            return -1;
        }
    }

    private function create_token_history($appointment_id = '', $coach_cost = '', $remain_token = '', $status='') {
        $appointment = $this->appointment_model->get_appointment($appointment_id);

        if(!$appointment){
            $this->messages->add('Invalid apppointment id',  'warning');
            redirect('student/find_coaches/single_date');
        }
        $token_history = array(
            'user_id' => $this->auth_manager->userid(),
            'appointment_id' => $appointment_id,
            // 'transaction_date' => strtotime(date('d-m-Y')),
            'transaction_date' => time(),
            'description' => 'Session with '.$appointment[0]->coach_fullname .' on '. $appointment[0]->date .' from '. $appointment[0]->start_time . ' to ' . $appointment[0]->end_time,
            'token_amount' => $coach_cost,
            'token_status_id' => $status,
            'balance' => $remain_token
        );
        if ($this->token_histories_model->insert($token_history)) {
            return true;
        } else {
            return false;
        }
    }

    public function confirm_book() {
        // check if the appoinment still available to be booked by student
        // all appointment has status temporary, if still available to be booked by student then it will change to active
        // make all procedure/action for session booked
        $data_temporary_appointment = $this->appointment_model->select('id, coach_id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status', 'temporary')->get_all();
        if (!$data_temporary_appointment) {
            $this->messages->add('No booked appointment', 'error');
            echo "No booked appointment";
            // exit;
        }
        $available_appointment_temp = array();
        $unavailable_appointment_temp = array();
        $coach_name = $this->identity_model->get_identity('profile')->where('partner_id', $this->auth_manager->partner_id())->dropdown('user_id', 'fullname');
        foreach ($data_temporary_appointment as $d) {
            if ($this->isAvailable($d->coach_id, strtotime($d->date), $d->start_time, $d->end_time)) {
                $available_appointment_temp[] = $d;
            } else {
                $unavailable_appointment_temp[] = $d;
                $messages[] = 'Session with coach ' . $coach_name[$d->coach_id] . ' at ' . date('l jS \of F Y', strtotime($d->date)) . ' from ' . $d->start_time . ' to ' . $d->end_time . ' is no longer available.';
            }
        }

        if ($unavailable_appointment_temp) {
            $message='';
            foreach($messages as $m){
               $message .= $m . "#";
               $this->messages->add($m, 'error');
            }
            echo "No available appointment#". $message;
             // exit;
        } else {
            $token_cost = $this->coach_token_cost_model->dropdown('coach_id', 'token_for_student');
            $total_token_cost_temp = 0;
            foreach ($available_appointment_temp as $a) {
                $total_token_cost_temp += $token_cost[$a->coach_id];
            }
            $remain_token = $this->update_token($total_token_cost_temp);
            if ($remain_token >= 0) {
                foreach ($available_appointment_temp as $a) {
                    $status_update = $this->update_appointment($a->id);
                    $valid_appointment = count($this->appointment_model->where('coach_id', $a->coach_id)->where('date', $a->date)->where('start_time', $a->start_time)->where('end_time', $a->end_time)->where('status', 'active')->get_all());
                    if (!$status_update) {
                        // rollback all appointment to temporary
                        $this->rollback_update_appointment($available_appointment_temp, ($remain_token + $total_token_cost_temp));
                        $this->messages->add('Fail Update Appointment', 'warning');
                        echo "Updated appointment failed";
                        // exit;
                    } else if ($this->db->trans_status() === true && $valid_appointment == 1) {
                        // adding every action for updating appointment to active
                        // making token history, notification, sending email
                        // adding token history
                        $remain_temp = 0;
                        $remain_temp += $token_cost[$a->coach_id];
                        if (!$this->create_token_history($a->id, $token_cost[$a->coach_id], ($remain_token + $total_token_cost_temp - $remain_temp), 1)) {
                            // rollback all appointment to temporary
                            $this->rollback_update_appointment($available_appointment_temp, ($remain_token + $total_token_cost_temp));

                            $this->messages->add('Fail, please try again!', 'warning');
                            echo "Failed, please try again!";
                            // exit;
                        } else {
                            // messaging and creating notification based on each appointment
                            $this->email_notification_appointment($a->id);
                        }
                    } else {
                        // rollback all appointment to temporary
                        $this->rollback_update_appointment($available_appointment_temp, ($remain_token + $total_token_cost_temp));

                        $this->messages->add('Fail to confirm, please try again. ', 'warning');
                        echo "Confirmation failed";
                         // exit;
                    }
                }
                foreach($available_appointment_temp as $appointment){
                    $available_host = $this->webex_host_model->get_available_host($appointment->id);
                    if(@$available_host && $this->webex_function->create_session(@$available_host[0]->id, $appointment->id)){
                        $messages[]="Appointment booked, you will use WEBEX for your session " .$appointment->date . " at " . $appointment->start_time;
                        $this->messages->add("Appointment booked, you will use WEBEX for your session ".$appointment->date." at ".$appointment->start_time, 'success');
                    }else{
                        $messages[]="Appointment booked, you will use SKYPE for your session " .$appointment->date . " at " . $appointment->start_time;
                        $this->messages->add("Appointment booked, you will use SKYPE for your session ".$appointment->date." at ".$appointment->start_time, 'success');
                    }
                }
                $message = '';
                foreach($messages as $m){
                    $message .= $m . "#";
                }
                echo "Success#".$message;
                // exit;
            } else {
                $this->messages->add('Not enough token, purchase more token or delete some sessions.', 'warning');
                echo "Not enough token, purchase more token or delete some sessions";
                // exit;
            }
        }
    }

    public function booking($coach_id = '', $date_ = '', $start_time_ = '', $end_time_ = '', $token) {
        //print_r(date('Y-m-d','1450962000'));exit;
        // exit('hai');
        // for isOnAvailability
        $start_time_available = $start_time_;
        $end_time_available = $end_time_;

        $convert = $this->schedule_function->convert_book_schedule(-($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes), $date_, $start_time_, $end_time_);
        $date = $convert['date'];
        $dateconvert = date('Y-m-d', $date_);
        $dateconvertcoach = date('Y-m-d', $date);
        $start_time = $convert['start_time'];
        $end_time = $convert['end_time'];

        // timezone
                    $id_student = $this->auth_manager->userid();


                    // student
                    $gmt_student = $this->identity_model->new_get_gmt($id_student);
                    // coach
                    $gmt_coach = $this->identity_model->new_get_gmt($coach_id);


                    // student
                    $minutes = $gmt_student[0]->minutes;
                    // coach
                    $minutes_coach = $gmt_coach[0]->minutes;

                    @date_default_timezone_set('UTC');
                    // student
                    $st  = strtotime($start_time);
                    $usertime1 = $st+(60*$minutes);
                    $start_hour = date("H:i", $usertime1);

                    $et  = strtotime($end_time);
                    $usertime2 = $et+(60*$minutes)-(5*60);
                    $end_hour = date("H:i", $usertime2);

                    // coach

                    $st_coach  = strtotime($start_time);
                    $usertime1_coach = $st_coach+(60*$minutes_coach);
                    $start_hour_coach = date("H:i", $usertime1_coach);

                    $et_coach  = strtotime($end_time);
                    $usertime2_coach = $et_coach+(60*$minutes_coach)-(5*60);
                    $end_hour_coach = date("H:i", $usertime2_coach);

        // $check_max_book_coach_per_day = $this->max_book_coach_per_day($coach_id,$date);
        // if(!$check_max_book_coach_per_day){
        //     $this->messages->add('This coach has exceeded maximum booked today', 'warning');
        //     redirect('student/find_coaches/search/name/');
        // }

        //print_r(date('Y-m-d', $date)); exit;

        try {
            // First of all, let's begin a transaction
            // A set of queries; if one fails, an exception should be thrown
            $isValid = $this->isAvailable($coach_id, $date, $start_time, $end_time);
            if ($isValid) {
                $availability = $this->isOnAvailability($coach_id, date('Y-m-d', $date_));
//
                if (in_array(array('start_time' => $start_time_available, 'end_time' => $end_time_available), $availability)) {
                    // go to next step
                    //exit;
                } else {
                    $this->messages->add('Invalid Time', 'warning');
                    redirect('student/find_coaches/search/name/');
                }

                 $token_cost = $token;


                $remain_token = $this->update_token($token_cost);
                //if ($this->db->trans_status() === true && $remain_token >= 0 && $this->isAvailable($coach_id, $date, $start_time, $end_time)) {

                if ($this->db->trans_status() === true && $remain_token >= 0){
                    $appointment_id = $this->create_appointment($coach_id, $date, $start_time, $end_time, 'active');

                    $get_date_apd = $this->db->select('date, start_time, end_time')->from('appointments')->where('id',$appointment_id)->get()->result();
                    $new_date_apd_coach = strtotime($get_date_apd[0]->date);
                    $new_start_time_coach = $get_date_apd[0]->start_time;
                    $new_end_time_coach = $get_date_apd[0]->end_time;

                    $convert_coach_plus = $this->schedule_function->convert_book_schedule(($this->identity_model->new_get_gmt($coach_id)[0]->minutes), $new_date_apd_coach, $new_start_time_coach, $new_end_time_coach);

                    $new_date_for_coach = date('Y-m-d', $convert_coach_plus['date']);

                    $emailcoach = $this->user_model->select('id, email')->where('id', $coach_id)->get_all();

                    $namecoach = $this->user_profile_model->select('user_id, fullname')->where('user_id', $coach_id)->get_all();

                    $namestudent = $this->user_profile_model->select('user_id, fullname')->where('user_id', $this->auth_manager->userid())->get_all();

                    $emailstudent = $this->user_model->select('id, email')->where('id', $this->auth_manager->userid())->get_all();

                    $valid_appointment = count($this->appointment_model->where('coach_id', $coach_id)->where('date', date('Y-m-d', $date))->where('start_time', $start_time)->where('end_time', $end_time)->where('status', 'active')->get_all());
                    if ($this->db->trans_status() === true && $appointment_id && $valid_appointment == 1) {
                        // creating token history
                        $this->create_token_history($appointment_id, $token_cost, $remain_token, 1);
                        // messaging to send email and creating notification based on appointment
                        //$this->email_notification_appointment($appointment_id);
                        // transaction finished / all criteria has been fulfilled
                        $message = 'Booking successful';

                        $coach_notification = array(
                            'user_id' => $coach_id,
                            'description' => $namestudent[0]->fullname.' has session booked with you',
                            'status' => 2,
                            'dcrea' => time(),
                            'dupd' => time(),
                        );

                        $student_notification = array(
                            'user_id' => $this->auth_manager->userid(),
                            'description' => 'New session booked with '.$namecoach[0]->fullname,
                            'status' => 2,
                            'dcrea' => time(),
                            'dupd' => time(),
                        );

                        $this->user_notification_model->insert($coach_notification);
                        $this->user_notification_model->insert($student_notification);

                        $student_gmt = $gmt_student[0]->gmt;
                        $coach_gmt = $gmt_coach[0]->gmt;

                        $this->send_email->student_book_coach($emailstudent[0]->email, $emailcoach[0]->email, $namestudent[0]->fullname, $namecoach[0]->fullname, $start_hour, $end_hour, $dateconvert, 'booked', $student_gmt);
                        $this->send_email->notif_coach($emailstudent[0]->email, $emailcoach[0]->email, $namestudent[0]->fullname, $namecoach[0]->fullname, $start_hour_coach, $end_hour_coach, $new_date_for_coach, 'booked', $coach_gmt);


                        $this->messages->add($message, 'success');
                        redirect('student/find_coaches/search/name/');
                    } else {
                        //throw $e;
                        $this->db->trans_rollback();
                        $this->rollback_appointment($coach_id, date("Y-m-d", $date), $start_time, $end_time, ($remain_token + $token_cost));
                        $this->messages->add('Fail to book appointment, please try again.', 'warning');
                        redirect('student/find_coaches/search/name/');
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->messages->add('Not Enough Token', 'warning');
                    redirect('student/find_coaches/search/name/');
                }
            } else {
                $this->db->trans_rollback();
                $this->messages->add('Invalid Appointment', 'warning');
                redirect('student/find_coaches/search/name/');
            }


            // If we arrive here, it means that no exception was thrown
            // i.e. no query has failed, and we can commit the transaction

        } catch (Exception $e) {
            // An exception has been thrown
            // We must rollback the transaction
            $this->db->trans_rollback();
            $this->messages->add('An error has occured, please try again.', 'warning');
            redirect('student/find_coaches/search/name/');
        }
    }

    private function rollback_appointment($coach_id = '', $date = '', $start_time = '', $end_time = '', $token = '') {
        // deleting appointment
        $this->appointment_model->where('coach_id', $coach_id)->where('student_id', $this->auth_manager->userid())->where('date', $date)->where('start_time', $start_time)->where('end_time', $end_time)->where('status', 'active')->delete();
        // updating remaining student token
        $data = array(
            'token_amount' => $token,
        );
        $id = $this->identity_model->get_identity('token')->where('user_id', $this->auth_manager->userid())->get();
        $this->identity_model->get_identity('token')->update($id->id, $data);
    }

    private function rollback_update_appointment($appointment = '', $token = '') {
        $data_appointment = array(
            'status' => 'temporary',
        );
        foreach ($appointment as $a) {
            $this->appointment_model->update($a->id, $data_appointment);
        }
        // updating remaining student token
        $data = array(
            'token_amount' => $token,
        );
        $id = $this->identity_model->get_identity('token')->where('user_id', $this->auth_manager->userid())->get();
        $this->identity_model->get_identity('token')->update($id->id, $data);
    }

    /**
     * Function time_reminder_before_session
     *
     * @param (string)(session_time) session time ('Y-m-d H:i:s')
     * @param (int)(delay_time) delay time before session time (s)
     *
     * @return if the function not return positive int, return FALSE
     */
    private function time_reminder_before_session($session_time, $delay_time) {
        if (DateTime::createFromFormat('Y-m-d H:i:s', trim($session_time)) != FALSE) {
            $now = (date('Y-m-d H:i:s', time() + $delay_time));
            return (((strtotime($session_time) - strtotime($now))) < 0 ? FALSE : (strtotime($session_time) - strtotime($now)));
        } else {
            return FALSE;
        }
    }

    private function get_date_week($date = ''){
        $index = array_search(strtolower(date("l", $date)), $this->day_index);
        $date_index = array();
        for($i=0;$i<7;$i++){
            $date_index[] = date('Y-m-d', strtotime(date('Y-m-d', $date). ''. ($i-$index).' days'));
        }
        return $date_index;
    }

    public function max_book_coach_per_day($coach_id='',$date=''){
        // get setting partner
        // $coach_id = '187';
        // $date = '2016-07-28';
        $student_id = $this->auth_manager->userid();
        $partner_id = $this->auth_manager->partner_id($coach_id);

        // check apakah status setting region allow atau disallow
        $region_id = $this->auth_manager->region_id($partner_id);

        $get_status_setting_region = $this->specific_settings_model->get_specific_settings($region_id,'region');

        $max_per_day = '';
        if($get_status_setting_region[0]->status_set_setting == 0){
            $get_setting = $this->global_settings_model->get_partner_settings();
            $max_per_day = $get_setting[0]->max_session_per_day;
        } else {
            $get_setting = $this->specific_settings_model->get_partner_settings($partner_id);
            $max_per_day = $get_setting[0]->max_session_per_day;
        }

        $max_coach = count($this->appointment_model->select('id')->where('coach_id',$coach_id)->where('date',$date)->get_All());

        if($max_coach > $max_per_day){
            return false;
        } else {
            return true;
        }
    }

    private function isAvailable($coach_id = '', $date = '', $start_time = '', $end_time = '') {
        //getting the day of $date
        $day = strtolower(date('l', $date));
        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id', $coach_id)->where('day', $day)->order_by('id', 'asc')->get();
        $schedule = $this->block($coach_id, $day, $schedule_data->start_time, $schedule_data->end_time);

        // check if coach availability has been booked or nothing
        // appointment data specify by coach, date and status
        // appointment with status cancel considered available for other student
        $appointment = $this->appointment_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->where('start_time', $start_time)->where('end_time', $end_time)->get();
        // appointment with status temporary considered available for other student but not for student who is in the appointment and
        // appointment where the student has make an appoinment on the specific date, so there will be no the same start time and end time to be shown to the student from other coach
        $appointment_student = $this->appointment_model->select('id, date, start_time, end_time')->where('student_id', $this->auth_manager->userid())->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->where('start_time', $start_time)->where('end_time', $end_time)->get();
        // appointment coach in class
        $appointment_class = $this->class_meeting_day_model->select('id, date, start_time, end_time')->where('coach_id', $coach_id)->where('date', date("Y-m-d", $date))->where('start_time', $start_time)->where('end_time', $end_time)->get();

        // partner setting about student appointment
        // $setting = $this->partner_setting_model->get();
        $student_id = $this->auth_manager->userid();
        $partner_id = $this->auth_manager->partner_id($student_id);

        // check apakah status setting region allow atau disallow
        $region_id = $this->auth_manager->region_id($partner_id);

        $get_status_setting_region = $this->specific_settings_model->get_specific_settings($region_id,'region');

        $max_session_per_day = '';
        $max_day_per_week = '';
        if($get_status_setting_region[0]->status_set_setting == 0){
            $get_setting = $this->global_settings_model->get_partner_settings();
            $max_session_per_day = $get_setting[0]->max_session_per_day;
            $max_day_per_week = $get_setting[0]->max_day_per_week;
        } else {
            $get_setting = $this->specific_settings_model->get_partner_settings($partner_id);
            $max_session_per_day = $get_setting[0]->max_session_per_day;
            $max_day_per_week = $get_setting[0]->max_day_per_week;
        }
        // $setting = $this->db->select('max_session_per_day, max_day_per_week')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();


      // $appointment_count = count($this->appointment_model->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', date("Y-m-d", $date))->get_all());

        $appointment_count = count($this->appointment_model->where('student_id', $student_id)->where('date', date("Y-m-d", $date))->get_all());

        // print_r($this->get_date_week($date)); exit;
        $appointment_count_week = 0;
        foreach($this->get_date_week($date) as $s){
            $appointment_count_week = $appointment_count_week + count($this->appointment_model->where('student_id', $student_id)->where('date', $s)->get_all());
            // $appointment_count_week = $appointment_count_week + count($this->appointment_model->where('coach_id', $coach_id)->where('status not like', 'cancel')->where('status not like', 'temporary')->where('date', $s)->get_all());
        }

       //  echo $partner_id." - ".$coach_id." - ".date('Y-m-d',$date);
       //  echo "<br />";
       // print_r($appointment_count);
       // echo "<br >";
       // print_r($appointment_count_week);
       // echo "<br >";
       // print_r($setting[0]->max_session_per_day);
       // echo "<br >";
       // print_r($setting[0]->max_day_per_week);
       // exit;
//        echo('<pre>');
//        echo(date('Y-m-d', $date));
//        echo('<br>');
//        print_r($start_time);
//        print_r($end_time);
//        print_r($schedule); exit;


        $status1 = 0;
        if ($appointment || $appointment_student || $appointment_class) {
            return false;
        } else if (!$appointment) {
            if($appointment_count < $max_session_per_day && $appointment_count_week < $max_day_per_week){
                foreach($schedule as $s){
                    if(strtotime($start_time) >= strtotime($s['start_time']) && strtotime($end_time) <= strtotime($s['end_time'])){
                        $status1 = 1;
                        break;
                    }
                }
                if($status1 == 1){
                    return true;
                }
                else{
                    $this->messages->add('Invalid Appointment Time', 'warning');
                    return false;
                }
            }
            else{
                $this->messages->add('Exceeded Max Session Per Day or Week', 'warning');
                return false;
                // diganti tanggal 23 maret 2017

                // return true;
            }
        }
    }

    /**
     * Function is_date_available
     * @param (string)(date) date that will be checked wheather available ('Y-m-d')
     * @param (int)(day) sum of day()
     * @return return TRUE if date available
     */

    function IntervalDays($CheckIn,$CheckOut){
        $CheckInX = explode("-", $CheckIn);
        $CheckOutX =  explode("-", $CheckOut);
        $date1 =  mktime(0, 0, 0, $CheckInX[1],$CheckInX[2],$CheckInX[0]);
        $date2 =  mktime(0, 0, 0, $CheckOutX[1],$CheckOutX[2],$CheckOutX[0]);
        $interval =($date2 - $date1)/(3600*24);
        // returns numberofdays
        return  $interval ;
    }

    private function is_date_available($date, $day) {
        $day = 0;

        $d = $date;
        $e = date('Y-m-d', strtotime("+" . $day . "days"));

        $h = $this->IntervalDays($d,$e);

        if ((DateTime::createFromFormat('Y-m-d', trim($date)) != FALSE) && (strtotime($date) >= strtotime(date('Y-m-d', strtotime("+" . $day . "days"))))) {
            return TRUE;
        } if($h > 0){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function is_day_off($coach_id = '', $date_ = '',$start_time = '', $end_time = '') {

	$date_ = strtotime($date_);

	$convert = @$this->schedule_function->convert_book_schedule(($this->identity_model->new_get_gmt($coach_id)[0]->minutes), $date_, $start_time, $end_time);
    $date = $convert['date'];


    $date_ = date('Y-m-d', $date);

        $day_off = $this->db->select('coach_id, start_date, end_date')
                        ->from('coach_dayoffs')
                        ->where('coach_id', $coach_id)
                        ->where('status', 'approved')
                        ->where('start_date <=', $date_)
                        ->where('end_date >=', $date_)
                        ->get()->result();
        $start_date = @$day_off->start_date;
        // $end_date = strtotime(@$day_off->end_date);
        $end_date = @$day_off->end_date;
        // $date = strtotime($date_);
        $date = $date_;

        foreach ($day_off as $do) {
            $start_date = @$do->start_date;
            $end_date = @$do->end_date;
            $date = $date_;

            // echo "<br />yak ". $date." - ". $start_date.'<br />';

            if ($date >= $start_date && $date <= $end_date){
                // echo $coach_id." tanggal ". $date. " start date ". $start_date." end date ". $end_date." cuti";
                // exit();
                return true;
            } else if (!$day_off) {
                // echo $coach_id. " start date ". $start_date." end date ". $end_date." gak cuti";
                // exit();
                return false;
            } else {
                // echo $coach_id." tanggal ". $date. " start date ". $start_date." end date ". $end_date." gak cuti juga";
                // exit();
                return false;
            }


        }


    }

    private function is_day_off1($coach_id = '', $date_ = '') {

        // $coach_id = '1907';
        // $date_ = '2016-11-22';
        $day_off = $this->coach_day_off_model->select('*')->where('coach_id', $coach_id)->where('status', 'active')->where('start_date >=',date('Y-m-d'))->get_all();
        // $start_date = strtotime(@$day_off->start_date);
        // $end_date = strtotime(@$day_off->end_date);

        $date = strtotime($date_);
        // echo count($day_off);
        //  echo "<pre>";
        // print_r($day_off);
        // exit();


        for($i=0; $i<count($day_off); $i++){
            // echo $i;
            if($date_ <= @$day_off[$i]->end_date){
                $start_date = strtotime(@$day_off[$i]->start_date);
                $end_date = strtotime(@$day_off[$i]->end_date);
                echo $date_." | ". $day_off[$i]->start_date." - ".$day_off[$i]->end_date."<br />";
                    if ($date_ > $day_off[$i]->start_date && $date_ < $day_off[$i]->end_date) {
                        return true;
                        echo "cuti";

                    } else if ($date_ <= $day_off[$i]->start_date && $date_ <= $day_off[$i]->end_date) {
                        // echo "cuti";
                        return true;

                    } else if ($date_ > $day_off[$i]->start_date && $date_ <= $day_off[$i]->end_date) {
                        // echo "cuti";
                        return true;

                    } else if($date_ > $day_off[$i]->start_date && $date_ > $day_off[$i]->end_date){
                        // echo "cuti";

                    } else if (!$day_off) {
                        return false;
                        // echo "gak cuti";

                    } else {
                        return false;
                        // echo "gak cuti";

                    }

                    // echo "<br />";
                }
                // =====
        }


    }

    private function create_date_range_array($strDateFrom, $strDateTo) {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.
        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange = array();

        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
            while ($iDateFrom < $iDateTo) {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange, date('Y-m-d', $iDateFrom));
            }
        }
        return $aryRange;
    }

    public function summary_book($search_by = '', $coach_id = '', $date = '', $start_time = '', $end_time = '') {
        $this->template->title = 'Booking Summary';

        $partner_id = $this->auth_manager->partner_id($coach_id);
        $region_id = $this->auth_manager->region_id($partner_id);

        $setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();
        $region_setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('specific_settings')->where('user_id',$region_id)->get()->result();
        $global_setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('global_settings')->where('type','partner')->get()->result();

        $standard_coach_cost = @$setting[0]->standard_coach_cost;
        if(!$standard_coach_cost || $standard_coach_cost == 0){
            $standard_coach_cost_region = @$region_setting[0]->standard_coach_cost;
            $standard_coach_cost = $standard_coach_cost_region;
            if(!$standard_coach_cost_region || $standard_coach_cost_region == 0){
                $standard_coach_cost_global = @$global_setting[0]->standard_coach_cost;
                $standard_coach_cost = $standard_coach_cost_global;
            }
        }

        $elite_coach_cost = @$setting[0]->elite_coach_cost;
        if(!$elite_coach_cost || $elite_coach_cost == 0){
            $elite_coach_cost_region = @$region_setting[0]->elite_coach_cost;
            $elite_coach_cost = $elite_coach_cost_region;
            if(!$elite_coach_cost_region || $elite_coach_cost_region == 0){
                $elite_coach_cost_global = @$global_setting[0]->elite_coach_cost;
                $elite_coach_cost = $elite_coach_cost_global;
            }
        }

        $vars = array(
            'data_coach' => $this->identity_model->get_coach_identity($coach_id),
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'search_by' => $search_by,
            'standard_coach_cost' => $standard_coach_cost,
            'elite_coach_cost' => $elite_coach_cost
        );

        // echo '<pre>';print_r($vars);exit();
        $this->template->content->view('default/contents/find_coach/summary_book/index', $vars);
        //publish template
        $this->template->publish();
    }

    private function session_duration($partner_id = ''){
//        $data =  ($this->partner_model->select('id, session_per_block_by_partner, session_per_block_by_admin')->where('id', $partner_id)->get());
//        if(!$data->session_per_block_by_admin){
//            return $data->session_per_block_by_partner;
//        }
//        else{
//            return $data->session_per_block_by_admin;
//        }
//
        // $setting = $this->partner_setting_model->get();
        $setting = $this->specific_settings_model->get_partner_settings($partner_id);
        $set_setting = $setting[0]->session_duration+5;
        return $set_setting;
        // return $setting[0]->session_duration;
    }

    private function isValidAppointment($start_time = '', $end_time = '', $start_time_temp = '', $end_time_temp = ''){
        $status = true;
        for($i=0;$i<count($start_time_temp);$i++){
            if(DateTime::createFromFormat('H:i:s', $start_time_temp[$i]) >= DateTime::createFromFormat('H:i:s', $start_time) && DateTime::createFromFormat('H:i:s', $start_time_temp[$i]) < DateTime::createFromFormat('H:i:s', $end_time)){
                $status = false;
                break;
            }
            else if(DateTime::createFromFormat('H:i:s', $end_time_temp[$i]) > DateTime::createFromFormat('H:i:s', $start_time) && DateTime::createFromFormat('H:i:s', $start_time_temp[$i]) < DateTime::createFromFormat('H:i:s', $start_time)){
                $status = false;
                break;
            }
        }
        return $status;
    }
}
