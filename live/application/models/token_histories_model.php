<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
/**
 * Class        Token_histories_model
 * @author      Ponel Panjaitan <ponel@pistarlabs.co.id>
 */
class Token_histories_model extends MY_Model {

    // Table name in database
    var $table = 'token_histories';
    // Validation rules
    var $validate = array();
    
    /**
     * @desc    Get token history for student with defined time period; maximum two months 
     * @param   (int)(student_id)
     * @param   (timestamp)(date_from)
     * @param   (timestamp)(date_to)
     */
    public function get_token_histories_for_student($date_from, $date_to, $limit='', $offset=''){
        $this->db->select('th.user_id, th.transaction_date, th.token_amount, th.description , th.balance, th.dupd, ts.status, ts.status_description')
            ->from($this->table . ' th')
            ->join('token_status ts', 'ts.id = th.token_status_id')
            ->where('th.user_id', $this->auth_manager->userid())
            ->where('th.transaction_date <= ', $date_to)
            ->where('th.transaction_date >= ', $date_from)
            ->order_by('th.id', 'desc');
        if($limit && $offset && $offset=="first_page"){
            $this->db->limit($limit);
            $this->db->offset(0);
        }elseif($limit && $offset){
            $this->db->limit($limit);
            $this->db->offset($offset);
        }
        return $this->db->get()->result();
    }
}

/* End of file student_token_histories_model.php */
/* Location: ./application/models/student_token_histories_model.php */
