<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class coaching_script_model extends MY_Model {

	// Table name in database
	var $table = 'coaching_scripts';

	// Validation rules
	var $validate = array(
			);

 public function get_student_script(){
            
            // $this->db->select("cs.id, cs.user_id, cs.script_id, cs.status, up.fullname, s.script, s.certificate_plan, s.unit");
            // $this->db->from('coaching_scripts cs');
            // $this->db->join('user_profiles up', 'cs.user_id = up.user_id');
            // $this->db->join('users u', 'u.id = up.user_id');
            // $this->db->join('script s', 's.id = cs.script_id');
            // $this->db->where('cs.user_id', 340);
            // $this->db->where('u.role_id', 1);
            // $this->db->where('cs.status', 'Not Cleared');


            $this->db->select("*");
            $this->db->from('coaching_scripts cs');
            $this->db->join('script s', 's.id = cs.script_id');
            $this->db->where('cs.user_id', 340);
            $this->db->where('s.certificate_plan', 'A1');
            $this->db->where('cs.status', 'Not Cleared');
            return $this->db->get()->result();
        }



	}
/* End of file partner_model.php */
/* Location: ./application/models/partner_model.php */
