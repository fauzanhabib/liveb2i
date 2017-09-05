<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class global_settings_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 */
class global_settings_model extends MY_Model {

	// Table name in database
	var $table = 'global_settings';

	// Validation rules
	var $validate = array(
			);
          
    public function get_global_settings($type)
    {
        $this->db->select("a.*");
        $this->db->from('global_settings a');
        $this->db->where('a.type', $type);
			return $this->db->get()->result();
		
    }
	
	public function get_partner_settings($type = 'Partner')
    {
        $this->db->select("a.*");
        $this->db->from('global_settings a');
        $this->db->where('a.type', $type);
			return $this->db->get()->result();
		
    }
	
}
/* End of file global_settings_model.php */
/* Location: ./application/models/partner_setting_model.php */
