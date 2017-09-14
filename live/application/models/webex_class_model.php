<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

/**
 * Class webex_class_model
 * @author      Ponel Panjaitan <ponel@pistarlabs.co.id>
 */
class Webex_class_model extends MY_Model {

    // Table name in database
    var $table = 'webex_class';
    // Validation rules
    var $validate = array();
    
    /**
     * @function checking host wheather being used by others
     * @return (array) hostes that not availabe to use
     * @param (date) date Y-m-d
     * @param (time) start_time H:i:s
     * @param (time) end_time H:i:s
     */
    public function used_host($date='', $start_time='', $end_time='', $partner_id=''){
        $this->db->select('wc.host_id')
            ->from($this->table .' wc')
            ->join('class_meeting_days cmd', 'wc.class_meeting_id = cmd.id')
            ->join('webex_host wh', 'wc.host_id = wh.id')
            ->join('user_profiles up', 'up.user_id = cmd.coach_id')
            ->where('up.partner_id' , $partner_id)
            ->where('cmd.date' , $date)
            ->where('cmd.start_time >=' , $start_time)
            ->where('cmd.end_time <=' , $end_time);
        return $this->db->get()->result();
    }
}

/* End of file webex_model.php */
/* Location: ./application/models/webex_model.php */
