<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class webex_host_model
 * @author      Ponel Panjaitan <ponel@pistarlabs.co.id>
 */
class webex_host_model extends MY_Model {

    // Table name in database
    var $table = 'webex_host';
    // Validation rules
    var $validate = array(
        array('field' => 'subdomain_webex', 'label' => 'Subdomain Webex', 'rules' => 'trim|required|xss_clean'),
        array('field' => 'partner_id', 'label' => 'Partner ID', 'rules' => 'trim|required|xss_clean'),
        array('field' => 'webex_id', 'label' => 'Webex ID', 'rules' => 'trim|required|xss_clean'),
        array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|xss_clean'),
    );

    public function get_available_host($meeting_identifier='') {
        $this->load->model('webex_model');
        $this->load->model('webex_class_model');
        if(substr($meeting_identifier, 0, 1) == 'c'){
            $webex_class = $this->class_meeting_day_model->select('coach_id, date, start_time, end_time')->where('id', substr($meeting_identifier, 1))->get();
            if($webex_class){
                $partner_id = $this->auth_manager->partner_id($webex_class->coach_id);
                $used_host_class = $this->webex_class_model->used_host($webex_class->date, $webex_class->start_time, $webex_class->end_time, $partner_id);
                $used_host_single_session = $this->webex_model->used_host($webex_class->date, $webex_class->start_time, $webex_class->end_time, $partner_id);
            }
        }else{
            $appointment = $this->appointment_model->select('student_id, coach_id, date, start_time, end_time')->where(array ('id' => $meeting_identifier, 'status'=>'active'))->get();
            if($appointment){
                $partner_id = $this->auth_manager->partner_id($appointment->coach_id);
                $used_host_single_session = $this->webex_model->used_host($appointment->date, $appointment->start_time, $appointment->end_time, $partner_id);
                $used_host_class = $this->webex_class_model->used_host($appointment->date, $appointment->start_time, $appointment->end_time, $partner_id);
            }
        }
        
        if (!@$appointment && !@$webex_class) {
            $this->messages->add('The appointment doesn\'t exsist', 'error');
            redirect('student_partner/managing');
        }
      
        $this->db->select('wh.id, wh.webex_id');
        $this->db->from('webex_host wh');
        $this->db->join('user_profiles up', 'up.user_id = wh.user_id');
        $this->db->where('up.partner_id', $partner_id);
        
        if (@$used_host_single_session) {
            foreach ($used_host_single_session as $host) {
                $this->db->where('wh.id !=', $host->host_id);
            }
        }
        if (@$used_host_class) {
            foreach ($used_host_class as $host_class) {
                $this->webex_host_model->where('wh.id !=', $host_class->host_id);
            }
        }
        return $this->db->get()->result();
    }
    
    public function get_host($meeting_identifier=''){
        $this->db->select('wh.id')
                ->from('webex_host wh');
        
        if(substr($meeting_identifier, 0, 1) == 'c'){
            $this->db->join('webex_class wc', 'wh.id  = wc.host_id');
            $this->db->where('wc.class_meeting_id', substr($meeting_identifier, 1));
        }else{
            $this->db->join('webex we', 'wh.id = we.host_id');
            $this->db->where('we.appointment_id', $meeting_identifier);
        }
        return $this->db->get()->result();
    }
    
    public function get_host_per_partner($limit='', $offset=''){
        $partner_id = $this->auth_manager->partner_id($this->auth_manager->userid());
        $this->db->select('wh.id, wh.subdomain_webex, wh.webex_id')
                ->from('webex_host wh')
                ->join('user_profiles up', 'up.user_id = wh.user_id')
                ->where('up.partner_id', $partner_id);
        
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

/* End of file webex_host_model.php */
/* Location: ./application/models/webex_host_model.php */
