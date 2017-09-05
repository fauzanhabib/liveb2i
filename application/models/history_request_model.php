<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class user_role_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class history_request_model extends MY_Model {
    // Table name in database
    var $table = 'token_requests';
    // Validation rules
    var $validate = array(
    );
    
    
    // public function get_student_request($id = ''){
    //         $partner_id = $this->auth_manager->partner_id();
    //         $this->db->select('tr.id, u.id as student_id, u.email, up.fullname, tr.token_amount, tr.status')
    //             ->from('users'. ' u')
    //             ->join('user_profiles up', 'up.user_id = u.id')
    //             ->join('token_requests tr', 'tr.student_id = u.id')
    //             ->where('u.role_id', 1)
    //             ->where('u.status', 'active')
    //             ->where('up.partner_id', $partner_id);
    //             //->where('tr.status', '!requested');
    //             if($id){
    //                 $this->db->where('tr.id', $id);
    //             }
    //         return $this->db->get()->result();
    //     }

        public function get_student_request($id = ''){
            $partner_id = $this->auth_manager->partner_id();
            $this->db->select('tr.id, u.id as student_id, u.email, up.fullname, tr.token_amount, tr.status, p.name as partner')
                ->from('users'. ' u')
                ->join('user_profiles up', 'up.user_id = u.id')
                ->join('token_requests tr', 'tr.user_id = u.id')
                ->join('partners p', 'p.id = up.partner_id')
                ->where('u.role_id', 1)
                ->where('u.status', 'active')
                ->where('up.partner_id', $partner_id);
                //->where('tr.status', '!requested');
                if($id){
                    $this->db->where('tr.id', $id);
                }
            return $this->db->get()->result();
        }

      public function get_history_student_request($id = ''){
            $partner_id = $this->auth_manager->partner_id();
            $this->db->select('tr.id, u.id as student_id, u.email, up.fullname, tr.token_amount, tr.status, p.name as partner, tr.dcrea as date_created, tr.dupd as date_updated')
                ->from('users'. ' u')
                ->join('user_profiles up', 'up.user_id = u.id')
                ->join('token_requests tr', 'tr.user_id = u.id')
                ->join('partners p', 'p.id = up.partner_id')
                ->where('u.role_id', 1)
                ->where('u.status', 'active')
                ->where('up.partner_id', $partner_id);
                //->where('tr.status', '!requested');
                if($id){
                    $this->db->where('tr.approve_id', $id);
                }
            return $this->db->get()->result();
        }

    function get_region_request($id = ''){
       $this->db->select('token_requests.id, up.region_id as region_id, u.email, up.fullname, token_requests.token_amount, token_requests.status')
                ->from('token_requests')
                ->join('users u', 'u.id = token_requests.region_id')
                ->join('user_profiles up', 'up.user_id = u.id')
                ->where('u.role_id', 4)
                ->where('u.status', 'active');
                if($id){
                    $this->db->where('token_requests.id', $id);
                }
            return $this->db->get()->result();
    }
}

/* End of file token_request_model.php */
/* Location: ./application/models/token_request_model.php */
