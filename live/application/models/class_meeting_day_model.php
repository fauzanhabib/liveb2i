<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class user_role_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class class_meeting_day_model extends MY_Model {

	// Table name in database
	var $table = 'class_meeting_days';

	// Validation rules
	var $validate = array(
			);
        
    public function get_schedule($class_id = '') {
        // getting student profile data where the user is active and join a class by class id
        $this->db->select("cmd.id, cmd.date, cmd.start_time, cmd.end_time");
        $this->db->from('class_meeting_days cmd');
        $this->db->where('cmd.class_id', $class_id);
        return $this->db->get()->result();
    }
    
    /**
     * @function get upcoming session CLASS for COACH
     */
    public function get_appointment_for_upcoming_session($date_from='', $date_to='', $coach_id='', $limit='', $offset='') {
        date_default_timezone_set('Etc/GMT+0');
        $now_date = date('Y-m-d');
        $now_time = date('H:i:s');
        
        if(!$coach_id){
            $coach_id = $this->auth_manager->userid();
        }
        
        $this->db->select('cmd.id, cl.id as class_id, cl.class_name, cmd.date, cmd.start_time, cmd.end_time, wc.status as webex_status')
                ->from($this->table . ' cmd')
                ->join('webex_class wc', 'cmd.id = wc.class_meeting_id', 'left')
                ->join('classes cl', 'cmd.class_id = cl.id')
                ->order_by('cmd.date', 'asc')->order_by('cmd.start_time', 'asc');
        if(!$date_from && !$date_to){
            $this->db->where("(cmd.date = '$now_date' AND cmd.start_time > '$now_time' AND cmd.status = 'active' AND cmd.coach_id = '$coach_id') or (cmd.date > '$now_date' AND cmd.status = 'active' AND cmd.coach_id = '$coach_id')");
        }elseif ($date_from == $now_date) {
            $this->db->where("(cmd.date = '$date_from' AND cmd.start_time > '$now_time' AND cmd.status = 'active' AND cmd.coach_id = '$coach_id') OR (cmd.date > '$date_from' AND cmd.date <= '$date_to' AND cmd.status = 'active' AND cmd.coach_id = '$coach_id')");
        }else{
            $this->db->where('cmd.status', 'active');
            $this->db->where('cmd.date >= ', $date_from);
            $this->db->where('cmd.date <= ', $date_to);
            $this->db->where('cmd.coach_id <= ', $coach_id);
        }
        
        if($limit && $offset && $offset=="first_page"){
            $this->db->limit($limit);
            $this->db->offset(0);
        }elseif($limit && $offset){
            $this->db->limit($limit);
            $this->db->offset($offset);
        }
        
        return $this->db->get()->result();
    }
    
    /**
     * @desc    Get session history with defined time period for COACH; maximum two months
     * @param   (string)(field_user_id) coach_id or student_id
     * @param   (int)(user_id)
     * @param   (date)(date_from) 'Y-m-d'
     * @param   (date)(date_to) 'Y-m-d'
     * @param   (int)(limit)
     * @param   (int)(offset)
     */
    public function get_session_histories($user_field, $user_id, $date_from, $date_to, $limit='', $offset='') {
        date_default_timezone_set('Etc/GMT+0');
        $now_date = date('Y-m-d');
        $now_time = date('H:i:s');
        
        $this->db->select('cmd.id, cmd.dupd, cmd.date, cmd.start_time, cmd.end_time, cmd.coach_id,cmd.class_id, up.fullname as coach_name, wc.stream_url, cl.class_name')
                ->from($this->table . ' cmd')
                ->join('user_profiles up', 'up.user_id = cmd.coach_id')
                ->join('webex_class wc', 'wc.class_meeting_id = cmd.id', 'left')
                ->join('classes cl', 'cl.id = cmd.class_id')
                ->order_by('cmd.date', 'desc')
                ->order_by('cmd.start_time', 'desc');
        if ($date_to == $now_date) {
            $this->db->where("(cmd.$user_field = '$user_id' AND cmd.date = '$date_to' AND cmd.end_time < '$now_time' AND cmd.status = 'active') OR (cmd.$user_field = '$user_id' AND cmd.date >= '$date_from' AND cmd.date < '$date_to' AND cmd.status = 'active')");
        }
        else{
            $this->db->where('cmd.status', 'active');
            $this->db->where('cmd.'.$user_field, $user_id);
            $this->db->where('cmd.date >= ', $date_from);
            $this->db->where('cmd.date <= ', $date_to);
        }
        
        if($limit && $offset && $offset=="first_page"){
            $this->db->limit($limit);
            $this->db->offset(0);
        }elseif($limit && $offset){
            $this->db->limit($limit);
            $this->db->offset($offset);
        }
        return $this->db->get()->result();
    }
    
    public function get_session_key_and_webex_host_info($class_id){
        date_default_timezone_set('Etc/GMT+0');
        $now_date = date('Y-m-d');
        $now_time = date('H:i:s');
        $this->db->select('cmd.date, cmd.start_time, cmd.end_time, wc.webex_meeting_number, wh.subdomain_webex, wh.webex_id, wh.partner_id, wh.password, wh.max_user, wh.max_duration')
            ->from($this->table .' cmd')
            ->join('webex_class wc','wc.class_meeting_id = cmd.id', 'left')
            ->join('webex_host wh', 'wc.host_id = wh.id', 'left');
        $this->db->where("(cmd.class_id = '$class_id' AND cmd.date = '$now_date' AND cmd.start_time < '$now_time' AND cmd.status = 'active') OR (cmd.class_id = '$class_id' AND cmd.date > '$now_date'  AND cmd.status = 'active')");
        return $this->db->get()->result();
    }
}
/* End of file class_meeting_day_model.php */
/* Location: ./application/models/class_meeting_day_model.php */
