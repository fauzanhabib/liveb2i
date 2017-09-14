<?php
if (! defined('BASEPATH')) {
 exit('No direct script access allowed');
}
class Cron extends MY_Controller
{

public function __construct()
    {
   parent::__construct();
   	$this->load->library('email');
    $this->load->library('queue');
    $this->load->library('email_structure');
    $this->load->library('send_email');
    $this->load->library('send_sms');
    $this->load->library('schedule_function');
   	$this->load->model('CronRunner_Model');
    $this->load->model('coach_day_off_model');
    }

public function run()
    {
     	if (!$this->input->is_cli_request()) {
       	show_error('Direct access is not allowed');
        }

   	$date = date("Y-m-d H:i:s");
    $str = strtotime($date);

    $appointment_student = $this->CronRunner_Model->get_days_appointments_student();
    foreach ($appointment_student as $q) {
            $gmt_student = $this->identity_model->new_get_gmt($q->student_id);
            $minutes_student = $gmt_student[0]->minutes;
            $date_student = $q->date;
            $time_student = $q->start_time;
            $combine_student = $date_student.' '.$time_student;
            $str_student = strtotime($combine_student);  
            $convert_student = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($q->student_id)[0]->minutes, strtotime($q->date), $q->start_time, $q->end_time); 
            $date_convert_student = date("Y-m-d", $convert_student['date']);
            $st_convert_student = $convert_student['start_time'];
            $et_convert_student = $convert_student['end_time'];

            $st  = strtotime($st_convert_student);
            $usertime1 = $st;
            $start_hour = date("H:i", $usertime1);

            $et  = strtotime($et_convert_student);
            $usertime2 = $et-(5*60);
            $end_hour = date("H:i", $usertime2);

            $dial_code = $q->student_code;
            $phone = $q->student_phone;
            $full_number = $dial_code . $phone;
            $student_phone = substr($full_number, 1);

            $diff = ($str_student-$str);
            if($q->flag_email == 0){
                if ($diff >= 0 && $diff <= 86400) {
                        $data = array(
                        'flag_email' => 1
                        );

                        $this->db->where('id', $q->id);
                        $this->db->update('appointments', $data); 

                        //$this->send_sms->student_reminder($student_phone, $q->student_name, $q->coach_name, $date_convert_student, $start_hour, $end_hour);
                        $this->send_email->student_reminder($q->student_email, $q->coach_name, $q->student_name, $date_convert_student, $start_hour, $end_hour, $q->student_gmt);                        
                }  
            }
        }

  	$appointment_coach = $this->CronRunner_Model->get_days_appointments_coach();
    foreach ($appointment_coach as $qc) {
            $date_coach = $qc->date;
            $time_coach = $qc->start_time;
            $combine_coach = $date_coach.' '.$time_coach;
            $str_coach = strtotime($combine_coach);  
            $convert_coach = $this->schedule_function->convert_book_schedule($this->identity_model->new_get_gmt($qc->coach_id)[0]->minutes, strtotime($qc->date), $qc->start_time, $qc->end_time); 
            $date_convert_coach = date("Y-m-d", $convert_coach['date']);
            $st_convert_coach = $convert_coach['start_time'];
            $et_convert_coach = $convert_coach['end_time'];

            $st_coach  = strtotime($st_convert_coach);
            $usertime1_coach = $st_coach;
            $start_hour_coach = date("H:i", $usertime1_coach);

            $et_coach  = strtotime($et_convert_coach);
            $usertime2_coach = $et_coach-(5*60);
            $end_hour_coach = date("H:i", $usertime2_coach);

            $dial_code_coach = $qc->coach_code;
            $phone_coach = $qc->coach_phone;
            $full_number_coach = $dial_code_coach . $phone_coach;
            $coach_phone = substr($full_number_coach, 1);

            $diff = ($str_coach-$str);
            if($qc->flag_email == 1){
                if ($diff >= 0 && $diff <= 86400) {
                        $data = array(
                        'flag_email' => 2
                        );
                     
                        $this->db->where('id', $qc->id);
                        $this->db->update('appointments', $data);

                        //$this->send_sms->coach_reminder($coach_phone, $qc->student_name, $qc->coach_name, $date_convert_coach, $start_hour_coach, $end_hour_coach);
                        $this->send_email->coach_reminder($qc->coach_email, $qc->coach_name, $qc->student_name, $date_convert_coach, $start_hour_coach, $end_hour_coach, $qc->coach_gmt);        
                }  
            }
        }
    }

public function done()
    {
        if (!$this->input->is_cli_request()) {
        show_error('Direct access is not allowed');
        }

        $datenow = date("Y-m-d");
        $strdatenow = strtotime($datenow);
        $day_off_data = $this->coach_day_off_model->select('id, start_date, remark, end_date, status')->get_all();
        foreach ($day_off_data as $d) {
            $end_date = $d->end_date;
            $str_end = strtotime($end_date);

            $diff = ($strdatenow-$str_end);
            if($d->status == 'active'){
                if($diff >= 172800){
                        $data = array(
                        'status' => 'done' 
                        );
                        $this->db->where('id', $d->id);
                        $this->db->update('coach_dayoffs', $data);
                }
            }elseif($d->status == 'pending'){
                if($diff >= 172800){
                        $data = array(
                        'status' => 'expired' 
                        );
                        $this->db->where('id', $d->id);
                        $this->db->update('coach_dayoffs', $data);
                }
            }
        }
    }
}