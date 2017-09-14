<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class user_role_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 */
class class_matchmaking_model extends MY_Model {

    // Table name in database
    var $table = 'class_matchmakings';
    // Validation rules
    var $validate = array(
    );
    
    public function get_relation() {
        // getting student profile data where the user is active and join a class by class id
        $this->db->select("cm.id, ssr.student_supplier_id, csr.coach_supplier_id, p1.name as student_partner_name, p2.name as coach_partner_name");
        $this->db->from('class_matchmakings cm');
        $this->db->join('student_supplier_relations ssr', 'cm.id = ssr.class_matchmaking_id');
        $this->db->join('coach_supplier_relations csr', 'cm.id = csr.class_matchmaking_id');
        $this->db->join('partners p1', 'ssr.student_supplier_id = p1.id');
        $this->db->join('partners p2', 'csr.coach_supplier_id = p2.id');
        //$this->db->where('cm.id', $class_matchmaking_id);
        return $this->db->get()->result();
    }
    
    public function get_student_supplier($class_matchmaking_id = '',$id = ''){
        $this->db->select("p.*");
        $this->db->from('partners p');
        $this->db->join('student_supplier_relations ssr', 'p.id = ssr.student_supplier_id');
        $this->db->where('ssr.class_matchmaking_id', $class_matchmaking_id);
        if($id){
            $this->db->where('p.admin_regional_id',$id);
        }
        return $this->db->get()->result();
    }
    
    public function get_coach_supplier($class_matchmaking_id = '',$id = ''){
        $this->db->select("p.*");
        $this->db->from('partners p');
        $this->db->join('coach_supplier_relations csr', 'p.id = csr.coach_supplier_id');
        $this->db->where('csr.class_matchmaking_id', $class_matchmaking_id);
        if($id){
            $this->db->where('p.admin_regional_id',$id);
        }
        return $this->db->get()->result();
    }

     public function get_student_group($class_matchmaking_id = '',$id = ''){
        $this->db->select("s.*");
        $this->db->from('subgroup s');
        $this->db->join('student_group_relations sgr', 's.id = sgr.subgroup_id');
        $this->db->where('sgr.class_matchmaking_id', $class_matchmaking_id);
        if($id){
            $this->db->where('s.partner_id',$id);
        }
        return $this->db->get()->result();
    }
    
    public function get_coach_group($class_matchmaking_id = '',$id = ''){
        $this->db->select("s.*");
        $this->db->from('subgroup s');
        $this->db->join('coach_group_relations cgr', 's.id = cgr.subgroup_id');
        $this->db->where('cgr.class_matchmaking_id', $class_matchmaking_id);
        if($id){
            $this->db->where('s.partner_id',$id);
        }
        return $this->db->get()->result();
    }

    
}

/* End of file class_matchmaking_model.php */
/* Location: ./application/models/class_matchmaking_model.php */
