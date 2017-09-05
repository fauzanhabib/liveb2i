<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class user_role_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class token_request_model extends MY_Model {
    // Table name in database
    var $table = 'token_requests';
    // Validation rules
    var $validate = array(
    );
    
    
    public function get_student_request($id = ''){
            $partner_id = $this->auth_manager->partner_id();
            $this->db->select('tr.id, u.id as student_id, u.email, up.fullname, tr.token_amount, p.name as partner')
                ->from('users'. ' u')
                ->join('user_profiles up', 'up.user_id = u.id')
                ->join('token_requests tr', 'tr.user_id = u.id')
                ->join('partners p', 'p.id = up.partner_id')
                ->where('u.role_id', 1)
                ->where('u.status', 'active')
                ->where('up.partner_id', $partner_id)
                ->where('tr.status', 'requested');
                if($id){
                    $this->db->where('tr.id', $id);
                }
            return $this->db->get()->result();
        }

    function new_get_token_request($id = ''){
        $this->db->select('tr.id, tr.token_amount, tr.user_id as user_id, up.region_id as region_name')
                ->from('token_requests tr')
                ->join('user_profiles up', 'up.user_id = tr.approve_id')
                ->where('tr.status', 'requested')
                ->where('tr.approve_id',$id);
            return $this->db->get()->result();             
    }

    function new_get_history_token_request($id = ''){
        $this->db->select('tr.id, tr.token_amount, tr.user_id as user_id, tr.status as status, tr.dcrea as dcrea, tr.dupd as dupd, up.region_id as region_name')
                ->from('token_requests tr')
                ->join('user_profiles up', 'up.user_id = tr.approve_id')
                ->where('tr.status !=', 'requested')
                ->where('tr.approve_id',$id);
            return $this->db->get()->result();             
    }



    function get_token_request($id = '',$type='',$region_name=''){
       if($type == 5){
           $this->db->select('tr.id, tr.token_amount, up.fullname as fullname, u.email, p.name as partner, up.region_id as region_id')
                ->from('token_requests tr')
                ->join('users u', 'u.id = tr.user_id')
                ->join('user_profiles up', 'up.user_id = tr.user_id')
                ->join('partners p', 'p.id = up.partner_id')
                ->where('u.role_id', $type)
                ->where('u.status', 'active')
                ->where('tr.status', 'requested');
                if($region_name){
                    $this->db->where('up.region_id',$region_name);
                }
                if($id){
                    $this->db->where('tr.id', $id);
                }
        } else {
           $this->db->select('tr.id, up.region_id as region_id, u.email, up.fullname, tr.token_amount')
                ->from('token_requests tr')
                ->join('users u', 'u.id = tr.user_id')
                ->join('user_profiles up', 'up.user_id = tr.user_id')
                ->where('u.role_id', $type)
                ->where('u.status', 'active')
                ->where('tr.status', 'requested');
                if($id){
                    $this->db->where('tr.id', $id);
                }
        }
            return $this->db->get()->result();
    }

}

/* End of file token_request_model.php */
/* Location: ./application/models/token_request_model.php */
