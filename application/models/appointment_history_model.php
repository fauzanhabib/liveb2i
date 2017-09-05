<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class appointment_history_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class appointment_history_model extends MY_Model {

    // Table name in database
    var $table = 'appointment_histories';
    // Validation rules
    var $validate = array();

    /**
     * @desc    Get session history for student with defined time period; maximum two months 
     * @param   (int)(student_id)
     * @param   (timestamp)(date_from)
     * @param   (timestamp)(date_to)
     */
    public function get_session_histories_for_student($date_from, $date_to){
        $this->db->select('ah.id, ap.date, ap.start_time, ap.end_time, up.fullname as coach_name')
            ->from($this->table . ' ah') 
            ->join('appointments ap', 'ap.id = th.appointment_id')
            ->join('user_profiles up', 'up.user_id = ap.coach_id')
            ->where('ap.student_id', $this->auth_manager->userid())
            ->where('th.booked_date <= ', $date_to)
            ->where('th.booked_date >= ', $date_from)
            ->order_by('th.dupd', 'asc');
        return $this->db->get()->result();
    }
    
    /**
     * @desc    Get session history with defined time period; maximum two months 
     * @param   (int)(coach_id)
     * @param   (date)(date_from) 'Y-m-d'
     * @param   (date)(date_to) 'Y-m-d'
     */
    public function get_session_histories($field_user_id, $date_from, $date_to, $limit='', $offset='') {
        $this->db->select('ap.id, ah.start_time as time1, ah.end_time as time2, ap.dupd, ap.date, ap.start_time, ap.end_time, up1.fullname as coach_name, up2.fullname as student_name, ap.coach_id, cr.rate, cr2.note')
                ->from($this->table . ' ah')
                ->join('appointments ap', 'ah.appointment_id = ap.id')
                ->join('user_profiles up1', 'up1.user_id = ap.coach_id')
                ->join('user_profiles up2', 'up2.user_id = ap.student_id')
                ->join('coach_ratings cr', 'cr.appointment_id = ap.id')
                ->join('coach_reports cr2', 'cr2.appointment_id = ap.id', 'left')
                ->order_by('ap.date', 'desc');
        if ($date_to == date('Y-m-d')) {
            $this->db->or_where('ap.'.$field_user_id, $this->auth_manager->userid());
            $this->db->where('ap.date = ', $date_to);
            $this->db->where('ap.end_time < ', date('H:i:s'));
            $this->db->where('ap.status', 'active');
            $this->db->where('ah.end_time !=', '00:00:00');
            
            $this->db->or_where('ap.'.$field_user_id, $this->auth_manager->userid());
            $this->db->where('ap.date >= ', $date_from);
            $this->db->where('ap.date < ', $date_to);
            $this->db->where('ap.status', 'active');
            $this->db->where('ah.end_time !=', '00:00:00');
        }
        else{
            $this->db->where('ap.status', 'active');
            $this->db->where('ap.'.$field_user_id, $this->auth_manager->userid());
            $this->db->where('ap.date >= ', $date_from);
            $this->db->where('ap.date <= ', $date_to);
            $this->db->where('ah.end_time !=', '00:00:00');
        }
        
        if($limit && $offset && $offset=="first_page"){
            $this->db->limit($limit);
            $this->db->offset(0);
        }elseif($limit && $offset){
            $this->db->limit($limit);
            $this->db->offset($offset);
        }
        return $this->db->get()->result();
    }
}

/* End of file appointment_history_model.php */
/* Location: ./application/models/appointment_history_model.php */
