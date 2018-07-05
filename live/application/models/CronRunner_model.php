<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cronrunner_model extends MY_Model
{

 public function get_days_appointments_student()
 {  
        $date = date("Y-m-d");
        $str = strtotime($date);
        $this->db->select('ap.cch_attend, ap.std_attend, ap.id, ap.dupd, ap.date, ap.start_time, ap.end_time, ap.student_id, up1.fullname as coach_name, up2.fullname as student_name, up1.phone as coach_phone, up2.phone as student_phone, up1.dial_code as coach_code, up2.dial_code as student_code, ap.coach_id, u1.email as coach_email, u2.email as student_email, ut1.minutes_val as coach_minutes, ut1.gmt_val as coach_gmt, ut2.minutes_val as student_minutes, ut2.gmt_val as student_gmt, ap.flag_email, ap.flag_sms')
                  ->from('appointments ap')
                  ->join('user_profiles up1', 'up1.user_id = ap.coach_id')
                  ->join('user_profiles up2', 'up2.user_id = ap.student_id')
                  ->join('users u1', 'u1.id = ap.coach_id')
                  ->join('users u2', 'u2.id = ap.student_id')
                  ->join('user_timezones ut1', 'ut1.user_id = ap.coach_id')
                  ->join('user_timezones ut2', 'ut2.user_id = ap.student_id');
                  
                  return $this->db->get()->result();
        
   }

   public function get_days_appointments_coach()
 {  
        $date = date("Y-m-d");
        $str = strtotime($date);
        $this->db->select('ap.cch_attend, ap.std_attend, ap.id, ap.dupd, ap.date, ap.start_time, ap.end_time, ap.student_id, up1.fullname as coach_name, up2.fullname as student_name, up1.phone as coach_phone, up2.phone as student_phone, up1.dial_code as coach_code, up2.dial_code as student_code, ap.coach_id, u1.email as coach_email, u2.email as student_email, ut1.minutes_val as coach_minutes, ut1.gmt_val as coach_gmt, ut2.minutes_val as student_minutes, ut2.gmt_val as student_gmt, ap.flag_email, ap.flag_sms')
                  ->from('appointments ap')
                  ->join('user_profiles up1', 'up1.user_id = ap.coach_id')
                  ->join('user_profiles up2', 'up2.user_id = ap.student_id')
                  ->join('users u1', 'u1.id = ap.coach_id')
                  ->join('users u2', 'u2.id = ap.student_id')
                  ->join('user_timezones ut1', 'ut1.user_id = ap.coach_id')
                  ->join('user_timezones ut2', 'ut2.user_id = ap.student_id');
                  
                  return $this->db->get()->result();
        
   }
}