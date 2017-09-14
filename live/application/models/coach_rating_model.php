<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class user_role_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class coach_rating_model extends MY_Model {

    // Table name in database
    var $table = 'coach_ratings';
    // Validation rules
    var $validate = array(
    );

    function get_rate_coach($limit='', $offset='') {
        $this->db->select("up.fullname, cr.id, cr.rate, cr.description, cr.status, a.coach_id, a.date, a.start_time, a.end_time");
        $this->db->from('coach_ratings cr');
        $this->db->join('appointments a', 'cr.appointment_id = a.id');
        $this->db->join('user_profiles up', 'a.coach_id = up.user_id');
        $this->db->where('a.student_id',$this->auth_manager->userid());
        $this->db->where('cr.status', 'unrated');
        $this->db->order_by("a.date", "desc");
        $this->db->order_by("a.end_time", "desc");
        if($limit && $offset && $offset=="first_page"){
            $this->db->limit($limit);
            $this->db->offset(0);
        }elseif($limit && $offset){
            $this->db->limit($limit);
            $this->db->offset($offset);
        }
        return $this->db->get()->result();
    }
    
    
    function get_average_rate() {
        // getting total sum rating
        $this->db->select("a.coach_id, sum(cr.rate) as sum");
        $this->db->group_by('a.coach_id'); 
        $this->db->from('coach_ratings cr');
        $this->db->join('appointments a', 'cr.appointment_id = a.id');
        $this->db->where('a.student_id',$this->auth_manager->userid());
        $this->db->where('cr.status', 'rated');

        $rate = array();
        foreach($this->db->get()->result() as $a){
            $rate[$a->coach_id] = $a->sum;
        }
        
        // getting total count rating
        $this->db->select("a.coach_id, count(cr.rate) as count");
        $this->db->group_by('a.coach_id'); 
        $this->db->from('coach_ratings cr');
        $this->db->join('appointments a', 'cr.appointment_id = a.id');
        $this->db->where('a.student_id',$this->auth_manager->userid());
        $this->db->where('cr.status', 'rated');
        
        foreach($this->db->get()->result() as $a){
            $rate[$a->coach_id] = $rate[$a->coach_id]/$a->count;
        }
        
        return $rate;
    }
    

}

/* End of file coach_rating_model.php */
/* Location: ./application/models/coach_rating_model.php */
