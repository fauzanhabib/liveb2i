<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class global_settings_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 */
class specific_settings_model extends MY_Model {

	// Table name in database
	var $table = 'specific_settings';

	// Validation rules
	var $validate = array(
			);
          
    public function get_specific_settings($id, $type)
    {
        $this->db->select("a.*");
        $this->db->from('specific_settings a');
        $this->db->where('a.type', $type);
        $this->db->where('a.user_id', $id);
			return $this->db->get()->result();
		
    }
	
	public function get_partner_settings($id, $type = 'Partner')
    {
        $this->db->select("*");
        $this->db->from('specific_settings a');
        $this->db->where('a.type', $type);
        $this->db->where('a.partner_id', $id);
			return $this->db->get()->result();
		
    }
	
}
/* End of file global_settings_model.php */
/* Location: ./application/models/partner_setting_model.php */
