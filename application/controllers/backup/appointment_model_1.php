<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class appointment_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class appointment_model extends MY_Model {

    // Table name in database
    var $table = 'appointments';
    // Validation rules
    var $validate = array(
    );

    function get_rate_coach() {
        //$this->db->distinct();
        
        $data = array();
        foreach ($this->get_distinct() as $a) {
            
            $this->db->select("a.id, a.coach_id , a.date, a.start_time, a.end_time, up.fullname, (AVG(cr.rate)) AS rating_average , count(rate) AS number_of_raters" );
            $this->db->from('appointments a');
            $this->db->where('a.coach_id', $a->coach_id);
            $this->db->where('status', 'active');
            $this->db->where('student_id', $this->auth_manager->userid());
            $this->db->where('date <=', date('Y-m-d'));
            $this->db->where('end_time <=', date('H:i:s'));
            $this->db->order_by("a.end_time", "desc");
            $this->db->order_by("a.date", "desc");
            $this->db->join('user_profiles up', 'a.coach_id = up.user_id');
            $this->db->join('coach_ratings cr', 'a.coach_id = cr.coach_id', 'left');
            $data[] = $this->db->get()->result()[0];
        }

        return $data;
    }

    function get_distinct() {
        $this->db->select("distinct(a.coach_id)");
        $this->db->from('appointments a');
        $this->db->where('status', 'active');
        $this->db->where('student_id', $this->auth_manager->userid());
        return $this->db->get()->result();
    }
    
    function count_raters($coach_id = ''){
        $this->db->select("count(rate) AS raters");
        $this->db->from('coach_ratings');
        $this->db->where('coach_id', $coach_id);
        return $this->db->get()->result();
    }
    
    /**
     * @desc    Get session history for coach with defined time period; maximum two months 
     * @param   (int)(coach_id)
     * @param   (timestamp)(date_from)
     * @param   (timestamp)(date_to)
     */
    function get_session_histories_for_coach($coach_id, $date_from, $date_to, $is_today_include){
        $this->db->select('ap.date, ap.start_time, ap.end_time, up1.fullname as coach_fullname, up2.fullname as student_fullname')
            ->from($this->table . ' ap')
            ->join('user_profiles up1', 'up1.user_id = ap.coach_id')
            ->join('user_profiles up2', 'up2.user_id = ap.student_id')
            ->where('ap.coach_id', $coach_id)
            ->where('date(ap.date) <= date', $date_to)
            ->where('date(ap.date) >= date', $date_from)
            ->order_by('ap.dcrea', 'asc');
        if($is_today_include == 1){
            $this->db->where('ap.end_time < ', 'CURTIME()');
        }
        
        return $this->db->get()->result();
    }
    
    function get_upcoming_session(){
        $this->db->select("*");
        $this->db->from('appointment a');
        $this->db->order_by("a.status", "desc"); 
        $this->db->join('user_roles b', 'a.role_id = b.id');
        $this->db->join('user_profiles c', 'a.id = c.user_id');
        if($creator_id){
            $this->db->join('creator_members d', 'a.id = d.member_id');
            $this->db->where('d.creator_id', $creator_id);
        }
        else{
            $this->db->where('a.status', 'active');
        }
        
        $this->db->where('b.id', 1);
        if($id)
            $this->db->where('a.id', $id);
        if($fullname)
            $this->db->like('c.fullname', $fullname, 'both');
        if($partner_id)
            $this->db->where('c.partner_id', $partner_id);

        return $this->db->get()->result();
    }

}

/* End of file appointment_model.php */
/* Location: ./application/models/appointment_model.php */
