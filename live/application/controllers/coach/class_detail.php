<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class class_detail extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('class_model');
        $this->load->model('class_member_model');
        $this->load->model('class_meeting_day_model');
        

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CCH') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $vars = array(
            'data' => $this->class_model->get_class_detail('',$this->auth_manager->userid()),
        );
        
        $this->template->content->view('default/contents/class_detail/coach/index', $vars);
        $this->template->publish();
    }
    
    public function schedule($class_id = ''){
        //$data = $this->class_meeting_day_model->get_schedule($class_id);
        $class_data = $this->class_model->select('id, class_name, student_amount, start_date, end_date')->where('id', $class_id)->get();
        $data = $this->class_meeting_day_model->select('id, date, start_time, end_time, coach_id')->where(Array('class_id' => $class_id, 'status'=>'active'))->order_by('start_time', 'asc')->get_all();
        $data_temp = array();
        foreach ($data as $d) {
            $data_temp[$d->date][] = $d;
            //print_r($d->date); exit;
        }
        //echo('<pre>');
        //print_r($data_temp); exit;
        //print_r($data_temp); exit;
        // This code is used to devide and count weeks from start date and end date
        $date1 = date_create($class_data->start_date);
        $date2 = date_create($class_data->end_date);
        $interval = date_diff($date1, $date2);
        $datediff = $interval->days + 1;
        $weeks = $datediff / 7;

        // ceil() used to round up decimal
        // to determine days in each week based on start date and end date of class
        $temp_day = array();
        for ($i = 1; $i <= ceil($weeks); $i++) {
            if ($i == ceil($weeks)) {
                $temp_day[] = ($datediff) - (((ceil($weeks) - 1) * 7));
            } else {
                $temp_day[] = 7;
            }
        }

        $date1 = strtotime($class_data->start_date);
        $date2 = strtotime($class_data->end_date);

        $date = $class_data->start_date;
        $temp_week = array();
        for ($i = 0; $i < count($temp_day); $i++) {
            for ($j = 0; $j < $temp_day[$i]; $j++) {
                // adding days based on wich week. Multiple it by 7
                $date = mktime(0, 0, 0, date("m", $date1), date("d", $date1) + ($j + ($i * 7)), date("Y", $date1));
                $temp_week[$i][date('Y-m-d', $date)] = date('D j M Y', $date);
            }
        }
        //print_r($temp_week); exit;
        $class_members = $this->class_member_model->get_student_member($class_id);


        $this->template->title = 'Set Class Schedule';

        $vars = array(
            'class_data' => $class_data,
            'week' => $temp_week,
            'data' => $data,
            'schedule' => $data_temp,
            'date_range' => $datediff,
            'start_date' => $class_data->start_date,
            'end_date' => $class_data->end_date,
            'class_id' => $class_id,
            'id_to_name' => $this->identity_model->get_identity('profile')->dropdown('user_id', 'fullname'),
            'class_member' => $class_members,
        );
//        echo('<pre>');
//        print_r($vars); exit;
        $this->template->content->view('default/contents/class_detail/coach/detail', $vars);

        //publish template
        $this->template->publish();
    }
    
    public function member($class_id = ''){
        $class_members = $this->class_member_model->get_student_member($class_id);
        $vars = array(
            'members' => $class_members,
        );
        $this->template->content->view('default/contents/class_detail/coach/member', $vars);
        //publish template
        $this->template->publish();
    }
    
    // function to check class is belong to user as role student_partner
    function isValidClass($class_id = '') {
        $data = $this->class_model->select('id, class_name, student_amount')->where('id', $class_id)->where('student_partner_id', $this->auth_manager->userid())->get();
        if ($data) {
            // returning class name and student amount if class valid
            return array(
                'class_name' => @$data->class_name,
                'student_amount' => @$data->student_amount,
            );
        } else {
            return false;
        }
    }

}
