<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class appointment_reschedule_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class appointment_reschedule_model extends MY_Model {

	// Table name in database
	var $table = 'appointment_reschedules';

	// Validation rules
	var $validate = array(
			);
        
    public function get_reschedule_data($coach_id = '', $date = '')
    {   
        //getting data of appointment reschedule join with appointment and specified by coach_id and date
        $this->db->select("a.id, a.date, a.start_time, a.end_time");
        $this->db->from('appointment_reschedules a');
        $this->db->join('appointments b', 'a.appointment_id = b.id');
        $this->db->where('b.coach_id', $coach_id);
        $this->db->where('a.date', $date);
        
        return $this->db->get()->result();
    }
          
}
/* End of file appointment_reschedule_model.php */
/* Location: ./application/models/appointment_reschedule_model.php */
