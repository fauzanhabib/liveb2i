<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class region_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 */
class region_model extends MY_Model {

	// Table name in database
	var $table = 'regions';

	// Validation rules
	var $validate = array(
			);
        
    public function get_region_admin($id = '')
    {
        $this->db->select("a.id, a.status, a.email, c.profile_picture, c.fullname, c.gender, c.date_of_birth, c.phone, c.skype_id, c.partner_id, c.dyned_pro_id, c.spoken_language, c.user_timezone, r.name as 'region_name', r.id as 'region_id'");
        $this->db->from('users a');
        //$this->db->order_by("a.status", "desc"); 
        //$this->db->join('user_roles b', 'a.role_id = b.id');
        $this->db->join('user_profiles c', 'a.id = c.user_id');
        $this->db->order_by('c.fullname', 'asc');
        $this->db->join('regions r', 'c.region_id = r.id');
        $this->db->where('a.role_id', 7);
//        if($creator_id){
//            $this->db->join('creator_members d', 'a.id = d.member_id');
//            $this->db->where('d.creator_id', $creator_id);
//        }
//        else{
//            $this->db->where('a.status', 'active');
//        }
//        if($role_id == '5'){
//            $this->db->where('b.id', 5);
//        }
//        else if($role_id){
//            $this->db->where('b.id', 3);
//        }
//        else{
//            $this->db->where('b.id', 5);
//            $this->db->or_where('b.id', 3);
//        }
        if($id)
            $this->db->where('a.id', $id);
//        if($fullname)
//            $this->db->like('c.fullname', $fullname, 'both');
//        if($partner_id)
//            $this->db->where('c.partner_id', $partner_id);

        return $this->db->get()->result();
    }
    
    public function get_partner($region_id='', $limit='', $offset='') {
        $this->db->select('p.id, p.profile_picture, p.name, p.address, p.country, p.state, p.city, p.zip, r.name as region_name')
            ->from('partners as p')
            ->join('regions r', 'r.id = p.region_id')
            //->join('user_geography ug', 'u.id = ug.user_id')
            ->where('p.region_id',$region_id)
            ->where('p.name not like', 'No Partner')
            ->order_by('p.name', 'asc');
        
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
    
    public function get_region(){
        $this->db->select("r.id, r.name, ps.id as setting_id");
        $this->db->from('regions r');
        $this->db->join('partner_settings ps', 'ps.region_id = r.id', 'left');
        $this->db->order_by('r.name', 'asc');
        return $this->db->get()->result();
    }

    function update_region($id, $data_update){

        $this->db->where('user_id',$id);
        $this->db->update('user_profiles', $data_update);
    }

    function get_global_setting($type = ''){
        return $this->db->select('*')
                 ->from('global_settings')
                 ->where('type',$type)
                 ->get()->result();
    }

    function get_specific_setting($id = ''){
        return $this->db->select('*')
                 ->from('specific_settings')
                 ->where('user_id',$id)
                 ->get()->result();
    }

    function get_partner_specific_setting($id = ''){
        return $this->db->select('*')
                 ->from('specific_settings')
                 ->where('partner_id',$id)
                 ->get()->result();
    }

    function insert_specific_setting($specific_settings){
        $this->db->insert('specific_settings',$specific_settings);
    }

    function update_setting($id, $setting){
        $this->db->where('user_id', $id);
        $this->db->update('specific_settings', $setting); 
    }
          
}
/* End of file partner_setting_model.php */
/* Location: ./application/models/partner_setting_model.php */
