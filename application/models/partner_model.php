<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class user_role_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class partner_model extends MY_Model {

	// Table name in database
	var $table = 'partners';

	// Validation rules
	var $validate = array(
			);
          

        public function get_student_supplier($id = ''){
            $this->db->distinct();
            $this->db->select("p.id, p.profile_picture, p.name, p.address, p.city, p.state, p.zip, p.country");
            $this->db->from('partners p');
            $this->db->join('user_profiles up', 'p.id = up.partner_id');
            $this->db->join('users u', 'u.id = up.user_id');
            $this->db->where('u.role_id', 5);
            if($id){
                $this->db->where('p.admin_regional_id', $id);
            }
            return $this->db->get()->result();
        }
        
        public function get_coach_supplier($id = ''){
            $this->db->distinct();
            $this->db->select("p.id, p.profile_picture, p.name, p.address, p.city, p.state, p.zip, p.country");
            $this->db->from('partners p');
            $this->db->join('user_profiles up', 'p.id = up.partner_id');
            $this->db->join('users u', 'u.id = up.user_id');
            $this->db->where('u.role_id', 3);
            if($id){
                $this->db->where('p.admin_regional_id', $id);
            }

            return $this->db->get()->result();
        }

        public function get_id_region($partner_id){
            $this->db->select('admin_regional_id as id_region');
            $this->db->from('partners');
            $this->db->where('id',$partner_id);
            return $this->db->get()->result();            
        }
}
/* End of file partner_model.php */
/* Location: ./application/models/partner_model.php */
