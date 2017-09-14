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

}

/* End of file appointment_model.php */
/* Location: ./application/models/appointment_model.php */
