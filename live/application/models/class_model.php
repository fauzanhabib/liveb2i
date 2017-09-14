<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class user_role_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class class_model extends MY_Model {

    // Table name in database
    var $table = 'classes';
    // Validation rules
    var $validate = array(
    );

    public function get_schedule($class_id = '') {
        // getting student profile data where the user is active and join a class by class id
        $this->db->select("cmd.id, cmd.date, cmd.start_time, cmd.end_time, a.fullname");
        $this->db->from('class_meeting_days cmd');
        $this->db->join('class c', 'cmd.class_id = c.id');
        $this->db->where('c.id', $class_id);
        return $this->db->get()->result();
    }
    
    public function get_class_detail($student_id = '', $coach_id = ''){
        $this->db->select("c.id, c.class_name, c.student_amount, c.start_date, c.end_date, c.student_partner_id, c.token_cost");
        $this->db->from('classes c');
        if($student_id){
            $this->db->join('class_members cm', 'c.id = cm.class_id');
            $this->db->where('cm.student_id', $student_id);
            $this->db->where('c.end_date >=', date('Y-m-d'));
        }
        else if($coach_id){
            $this->db->join('class_meeting_days cmd', 'c.id = cmd.class_id');
            $this->db->where('cmd.coach_id', $coach_id);
            $this->db->where('c.end_date >=', date('Y-m-d'));
            $this->db->where('cmd.date >=', date('Y-m-d'));
            $this->db->distinct();
        }
        
        return $this->db->get()->result();
    }
}

/* End of file class_model.php */
/* Location: ./application/models/class_model.php */
