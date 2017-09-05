<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class social_media_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class user_log_model extends MY_Model {

	// Table name in database
	var $table = 'user_logs';

	// Validation rules
	var $validate = array(
			);
        public function get_log_data($limit='', $offset=''){
            $this->db->select("ul.id, ul.description, ul.dcrea, up.fullname");
            $this->db->from('user_logs ul');
            $this->db->join('user_profiles up', 'ul.user_id = up.user_id');
            $this->db->order_by('ul.dcrea', 'desc');
            
            ///////////////////////////////////////////////
        // Pagination
        ///////////////////////////////////////////////
        if($limit && $offset && $offset=="first_page"){
            $this->db->limit($limit);
            $this->db->offset(0);
        }elseif($limit && $offset){
            $this->db->limit($limit);
            $this->db->offset($offset);
        }
        ///////////////////////////////////////////////

            return $this->db->get()->result();
        }
          
}
/* End of file social_media_model.php */
/* Location: ./application/models/social_media_model.php */
