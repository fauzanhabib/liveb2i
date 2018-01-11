<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class user_role_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class user_profile_model extends MY_Model {

    // Table name in database
    var $table = 'user_profiles';
    // Validation rules
    var $validate = array();

    // Get student per partner
    public function get_students($partner='', $subgroup_id = '', $status='', $limit='', $offset='') {

        $this->db->select('u.status as status, u.email as email, up.user_id as id, up.fullname, up.subgroup_id as subgroup_id, up.profile_picture, up.gender, up.date_of_birth, up.dial_code, up.phone, ug.country')
            ->from($this->table . ' up')
            ->join('users u', 'u.id = up.user_id')
            ->join('user_profiles c', 'u.id = c.user_id')
            ->join('user_geography ug', 'u.id = ug.user_id')
            ->join('subgroup s','up.subgroup_id = s.id')
            // ->where(array('u.status' => $status, 'u.role_id' => '1', 'up.partner_id' => $partner));
            ->where(array('u.status' => $status, 'u.role_id' => '1', 's.partner_id' => $partner));
           if($subgroup_id){
                $this->db->where('c.subgroup_id',$subgroup_id);
            }
            $this->db->order_by('fullname', 'asc');
        
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
    
    public function get_students_supplier($partner='', $subgroup_id = '', $limit='', $offset='') {
        $this->db->select('u.email as email, up.user_id as id, up.fullname, up.subgroup_id as subgroup_id, up.profile_picture, up.gender, up.date_of_birth, ug.country')
            ->from($this->table . ' up')
            ->join('users u', 'u.id = up.user_id')
            ->join('user_profiles c', 'u.id = c.user_id')
            ->join('user_geography ug', 'u.id = ug.user_id')
            ->where(array('u.status' => 'active', 'u.role_id' => '5', 'up.partner_id' => $partner));
           if($subgroup_id){
                $this->db->where('c.subgroup_id',$subgroup_id);
            }
            $this->db->order_by('fullname', 'asc');
        
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
    
    // Get student per partner
    // public function get_coaches($partner='', $limit='', $offset='') {
    public function get_coaches($partner='', $subgroup_id = '', $status='', $limit='', $offset='') {
        $this->db->select('u.email as email, u.status as status, up.user_id as id, up.fullname, up.profile_picture, up.subgroup_id as subgroup_id, up.gender, up.date_of_birth, ug.country')
            ->from($this->table . ' up')
            ->join('users u', 'u.id = up.user_id')
            ->join('user_geography ug', 'u.id = ug.user_id')
            ->join('subgroup s','up.subgroup_id = s.id')
            ->where('s.id',$subgroup_id)
            ->where('u.status',$status)
            // ->where(array('u.role_id' => '2', 'up.partner_id' => $partner))
            // ->where(array('u.status' => $status, 'u.role_id' => '1', 's.partner_id' => $partner));
            ->where(array('u.role_id' => '2', 's.partner_id' => $partner))
            ->order_by('id', 'asc');
        
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

    public function get_coaches_supplier($partner='', $limit='', $offset='') {
        $this->db->select('u.email as email, up.user_id as id, up.fullname, up.profile_picture, up.subgroup_id as subgroup_id, up.gender, up.date_of_birth, ug.country')
            ->from($this->table . ' up')
            ->join('users u', 'u.id = up.user_id')
            ->join('user_geography ug', 'u.id = ug.user_id')
            ->where(array('u.status' => 'active', 'u.role_id' => '3', 'up.partner_id' => $partner))
            ->order_by('id', 'asc');
        
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

/* End of file user_role_model.php */
/* Location: ./application/models/user_role_model.php */
