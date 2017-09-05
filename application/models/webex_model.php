<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

/**
 * Class webex_model
 * @author      Ponel Panjaitan <ponel@pistarlabs.co.id>
 */
class webex_model extends MY_Model {

    // Table name in database
    var $table = 'webex';
    // Validation rules
    var $validate = array();
    
    /**
     * @function checking host wheather being used by others
     * @return (array) hostes that not availabe to use
     * @param (date) date Y-m-d
     * @param (time) start_time H:i:s
     * @param (time) end_time H:i:s
     */
    public function used_host($date, $start_time, $end_time, $partner_id){
        $this->db->select('we.host_id')
            ->from($this->table .' we')
            ->join('appointments ap','we.appointment_id = ap.id')
            ->join('user_profiles up', 'ap.coach_id = up.user_id')
            ->join('webex_host wh', 'we.host_id = wh.id')
            ->where('ap.status' , 'active')
            ->where('up.partner_id' , $partner_id)
            ->where('ap.date' , $date)
            ->where('ap.start_time >=' , $start_time)
            ->where('ap.end_time <=' , $end_time);
        return $this->db->get()->result();
    }
}

/* End of file webex_model.php */
/* Location: ./application/models/webex_model.php */
