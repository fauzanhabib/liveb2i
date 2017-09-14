<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
/**
 * Class        Student_token_histories_model
 * @author      Ponel Panjaitan <ponel@pistarlabs.co.id>
 */
class Student_token_histories_model extends MY_Model {

    // Table name in database
    var $table = 'student_token_histories';
    // Validation rules
    var $validate = array();
    
    /**
     * @desc    Get token history with defined time period; maximum two months 
     * @param   (int)(student_id)
     * @param   (timestamp)(date_from)
     * @param   (timestamp)(date_to)
     */
    public function get_token_histories_with_time_period_defined($student_id, $date_from, $date_to){
        $this->db->select('sth.id, up2.fullname as student_fullname, up1.fullname as coach_fullname, sth.cost, sth.dupd ,sth.booked_date')
            ->from($this->table . ' sth')
            ->join('user_profiles up1', 'sth.coach_id = up1.user_id')
            ->join('user_profiles up2', 'sth.student_id = up2.user_id')
            ->where('sth.booked_date <= ', $date_to)
            ->where('sth.booked_date >= ', $date_from)    
            ->where('sth.student_id', $student_id)
            ->order_by('up2.fullname', 'asc');
        return $this->db->get()->result();
    }
}

/* End of file student_token_histories_model.php */
/* Location: ./application/models/student_token_histories_model.php */
