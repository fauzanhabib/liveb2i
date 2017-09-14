<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class user_role_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class coach_day_off_model_history extends MY_Model {

    // Table name in database
    var $table = 'coach_dayoffs';
    // Validation rules
    var $validate = array(
    );

    public function get_coach_day_off($coach_id = '', $limit='', $offset='') {
        $partner_id = $this->auth_manager->partner_id();
        $this->db->select("cd.id, cd.coach_id, cd.start_date, cd.end_date, cd.remark, cd.status, up.fullname, up.profile_picture");
        $this->db->from('coach_dayoffs cd');
        $this->db->join('user_profiles up', 'cd.coach_id = up.user_id');
        $this->db->where('up.partner_id', $partner_id);
        //$this->db->where('cd.status', 'pending');
        if ($coach_id){
            $this->db->where('cd.coach_id', $coach_id);
        }

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

/* End of file day_off_model.php */
/* Location: ./application/models/day_off_model.php */
