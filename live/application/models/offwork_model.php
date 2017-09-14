<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class offwork_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class offwork_model extends MY_Model {

	// Table name in database
	var $table = 'offworks';

	// Validation rules
	var $validate = array(
			);
          
        public function get_offwork($user_id = null, $day=null){
        
            $this->db->select("a.id, a.date, a.schedule_id, a.start_time, a.end_time");
            $this->db->from('offworks a');
            $this->db->join('schedules b', 'b.id = a.schedule_id', 'full');
            $this->db->where('b.user_id', $user_id);
            $this->db->where('b.day', $day);
            $this->db->order_by("a.start_time", "asc"); 

            return $this->db->get()->result();
        }
}
/* End of file offwork_model.php */
/* Location: ./application/models/offwork_model.php */
