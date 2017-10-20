<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class user_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class user_model extends MY_Model {

    // Table name in database
    var $table = 'users';
    // Validation rules
    var $validate = array(
        array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|xss_clean'),
            //array('field'=>'password', 'label' => 'Password', 'rules'=>'trim|required|xss_clean'),
    );

    public function get_coach_by_partner($partner_id) {
        $this->db->select('u.id, up.fullname')
                ->from($this->table . ' u')
                ->join('user_profiles up', 'up.user_id = u.id')
                ->where('u.role_id', 2)
                ->where('u.status', 'active')
                ->where('up.partner_id', $partner_id);
        return $this->db->get()->result();
    }

    public function get_email_partner() {
        $partner_id = $this->auth_manager->partner_id();
        $this->db->select('u.id, u.email, up.fullname')
                ->from($this->table . ' u')
                ->join('user_profiles up', 'up.user_id = u.id')
                ->where('u.role_id', 3)
                ->where('u.status', 'active')
                ->where('up.partner_id', $partner_id);
        return $this->db->get()->result();
    }
    
    public function get_email_student_partner() {
        $partner_id = $this->auth_manager->partner_id();
        $this->db->select('u.id, u.email, up.fullname')
                ->from($this->table . ' u')
                ->join('user_profiles up', 'up.user_id = u.id')
                ->where('u.role_id', 5)
                ->where('u.status', 'active')
                ->where('up.partner_id', $partner_id);
        return $this->db->get()->result();
    }

    public function get_partner_members($type='',$partner_id='', $user_id='') {
        // exit($type);
        if($type == 'coach'){
            @$ntype = 3;
        } elseif( $type == 'student'){
            @$ntype = array(5,8);
        } elseif($type=''){
            @$ntype = 3;
        }

        $this->db->select('u.id, u.email, up.profile_picture, up.fullname, up.gender, up.date_of_birth, up.dial_code, up.phone, up.skype_id, up.dyned_pro_id, p.name as partner_name, ur.code as role, ur.id as partner_type')
                ->from($this->table . ' u')
                ->join('user_profiles up', 'up.user_id = u.id')
                ->join('partners p', 'up.partner_id = p.id')
                ->join('user_roles ur', 'ur.id = u.role_id')
                ->where('up.partner_id', $partner_id);
                // ->where("(u.role_id = '3' OR u.role_id = '5')", NULL, FALSE)
                if($type == 'coach'){
                    $this->db->where('u.role_id',$ntype);
                } else if($type == 'student'){
                    $this->db->where_in('u.role_id',$ntype);
                }
                $this->db->where('u.status', 'active')
                ->where('up.partner_id', $partner_id);
                if($user_id){
                    $this->db->where('u.id', $user_id);
                }
        return $this->db->get()->result();
    }


    
     /**
      * @func       get_user
      * @param      (int)(user_id)
      * @return     name and email user
      */
    public function get_user($user_id){
        $this->db->select('u.id, u.email, up.fullname as name')
                ->from($this->table . ' u')
                ->join('user_profiles up', 'up.user_id = u.id')
                ->where('u.status', 'active')
                ->where('u.id', $user_id);
        return $this->db->get()->result();
    }

    function get_approval_supplier($type, $per_page, $offset){
        return $this->db->select('a.id as id, a.email as email, a.role_id as role_id, a.status status, p.name as partner')
                ->from('users a')
                ->join('user_profiles us', 'us.user_id = a.id')
                ->join('partners p', 'us.partner_id = p.id')
                ->where('a.status', 'disable')
                ->where('a.role_id', $type)
                ->order_by('a.dcrea', 'desc')
                ->limit($per_page)
                ->offset($offset)
                ->get()->result();
    }

    function superadmin_get_approval_supplier($type, $per_page, $offset){
        return $this->db->select('a.id as id, a.email as email, a.role_id as role_id, a.status status, p.name as partner, us.partner_id as partner_id')
                ->from('users a')
                ->join('user_profiles us', 'us.user_id = a.id')
                ->join('partners p', 'us.partner_id = p.id','left')
                ->where('a.status', 'disable')
                ->where('a.role_id', $type)
                ->order_by('a.dcrea', 'desc')
                ->limit($per_page)
                ->offset($offset)
                ->get()->result();
    }


}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
