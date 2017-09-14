<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class social_media_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class user_token_model extends MY_Model {

	// Table name in database
	var $table = 'user_tokens';

	// Validation rules
	var $validate = array(
			);


	public function get_token($id,$type){
		$this->db->select("*");
        $this->db->from('user_tokens');
	    if($type=="user"){
	        $this->db->where('user_id', $id);        	
	    }

	    if($type=="partner"){
	        $this->db->where('partner_id', $id);        	
	    }

			return $this->db->get()->result();
	}
          
}
/* End of file social_media_model.php */
/* Location: ./application/models/social_media_model.php */
