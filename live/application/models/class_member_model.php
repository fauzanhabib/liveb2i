<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class user_role_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class class_member_model extends MY_Model {

    // Table name in database
    var $table = 'class_members';
    // Validation rules
    var $validate = array(
    );

    public function get_student_member($class_id = '') {
        // getting student profile data where the user is active and join a class by class id
        $this->db->select("c.id, b.id as 'class_member_id', a.fullname, a.profile_picture, a.phone, a.gender, a.date_of_birth, sb.name as subgroup");
        $this->db->from('user_profiles a');
        $this->db->join('class_members b', 'a.user_id = b.student_id');
        $this->db->join('users c', 'a.user_id = c.id');
        $this->db->join('classes d', 'b.class_id = d.id');
        $this->db->join('subgroup sb', 'sb.id = a.subgroup_id');
        $this->db->where('b.class_id', $class_id);
        $this->db->where('c.status', 'active');
        $this->db->where('d.id', $class_id);
        return $this->db->get()->result();
    }
     
    public function get_unassigned_student($class_id = ''){
        // getting class member data
        $assigned_student = $this->db->query("select class_members.student_id from class_members where class_members.class_id = ".$class_id)->result_array();
        $assigned_student_temp = array();
        foreach($assigned_student as $a){
            $assigned_student_temp[] = $a['student_id'];
        }
        // temp for partner_id
        $partner_id = $this->auth_manager->partner_id();
        
        // getting unassigned student data
        $this->db->select('u.id, up.fullname, up.fullname, up.profile_picture, up.phone, up.gender, sb.name as subgroup');
        $this->db->from('users u');
        $this->db->join('user_profiles up', 'u.id = up.user_id');
        $this->db->join('subgroup sb', 'sb.id = up.subgroup_id');
        $this->db->where('up.partner_id', $partner_id);
        $this->db->where('u.role_id', 1);
        $this->db->where('u.status', 'active');
        if($assigned_student){
            $this->db->where_not_in('u.id', @$assigned_student_temp);
        }
        
        return $this->db->get()->result();
    }

    /**
     * @function get appointment for webex invitation
     * @param (int) meeting_identifier appointment id
     */
    public function get_appointment_for_webex_invitation($meeting_identifier=''){
        $this->db->select('cm.student_id, cm.class_id, cmd.id, cmd.date, cmd.start_time, cmd.end_time, up2.fullname as coach_name, up.fullname as student_name, us.email as student_email, wc.webex_meeting_number')
                ->from($this->table . ' cm')
                ->join('class_meeting_days cmd', 'cm.class_id = cmd.class_id')
                ->join('webex_class wc', 'cmd.id = wc.class_meeting_id')
                ->join('user_profiles up', 'cm.student_id = up.user_id')
                ->join('user_profiles up2', 'cmd.coach_id = up2.user_id')
                ->join('users us', 'cm.student_id = us.id')
                ->where('cmd.id', substr($meeting_identifier, 1));
        return $this->db->get()->result();
    }
    
    /**
     * FOR XML
     * @function get appointment for webex invitation
     * @param (int) meeting_identifier appointment id
     */
    public function get_appointment_for_webex_invitation_xml($meeting_identifier=''){
        $this->db->select('cm.student_id, cm.class_id, cmd.id, cmd.date, cmd.start_time, cmd.end_time, up2.fullname as coach_fullname, up.fullname as student_fullname, us.email as student_email, cl.class_name, cl.student_amount')
                ->from($this->table . ' cm')
                ->join('class_meeting_days cmd', 'cm.class_id = cmd.class_id')
                ->join('user_profiles up', 'cm.student_id = up.user_id')
                ->join('user_profiles up2', 'cmd.coach_id = up2.user_id')
                ->join('users us', 'cm.student_id = us.id')
                ->join('classes cl', 'cmd.class_id = cl.id')
                ->where('cmd.id', $meeting_identifier);
        return $this->db->get()->result();
    }
    
    /**
     * @function get appointment for ongoing session class for students or coach
     */
    public function get_appointment_for_ongoing_session(){
        date_default_timezone_set('Etc/GMT+0');
        
        $this->db->select('cl.id as class_id,cm.student_id, up.skype_id, cl.class_name, cmd.id, cmd.coach_id, cmd.date, cmd.start_time, cmd.end_time, wc.status as webex_status, wc.webex_meeting_number, wc.host_id, wc.id as webex_session_id, wc.password as session_password, wh.subdomain_webex, wh.webex_id, wh.password as host_password, wh.partner_id')
                ->from($this->table . ' cm')
                ->join('classes cl', 'cm.class_id = cl.id')
                ->join('user_profiles up', 'cm.student_id = up.user_id')
                ->join('class_meeting_days cmd', 'cm.class_id = cmd.class_id')
                ->join('webex_class wc', 'cmd.id = wc.class_meeting_id', 'left')
                ->join('webex_host wh', 'wc.host_id = wh.id', 'left')
                ->where('cmd.date', date('Y-m-d'))
                ->where('cmd.start_time <=', date('H:i:s'))
                ->where('cmd.end_time >=', date('H:i:s'));
        if($this->auth_manager->role() == 'STD'){
            $this->db->where('cm.student_id', $this->auth_manager->userid());
        }else if($this->auth_manager->role() == 'CCH'){
            $this->db->where('cmd.coach_id', $this->auth_manager->userid());
        }
        return $this->db->get()->result();
    }
    
    /**
     * @function get upcoming session CLASS for STUDENT
     */
    public function get_appointment_for_upcoming_session($date_from='', $date_to='', $user_id='', $limit='', $offset='') {
        date_default_timezone_set('Etc/GMT+0');
        $now_date = date('Y-m-d');
        $now_time = date('H:i:s');
        $this->db->select('cmd.id, cl.id as class_id, cl.class_name, ctc.token_for_student, cmd.coach_id, cmd.date, cmd.start_time, cmd.end_time, wc.status as webex_status, wc.host_id')
                ->from($this->table . ' cm')
                ->join('classes cl', 'cm.class_id = cl.id')
                ->join('class_meeting_days cmd', 'cm.class_id = cmd.class_id')
                ->join('coach_token_costs ctc', 'cmd.coach_id = ctc.coach_id')
                ->join('webex_class wc', 'cmd.id = wc.class_meeting_id', 'left')
                ->order_by('cmd.date', 'asc')->order_by('cmd.start_time', 'asc');
        
        if(!$date_from && !$date_to){
            $this->db->where('cmd.date >=', $now_date);
        }elseif ($date_from == $now_date) {
            $this->db->where("(cm.student_id = '$user_id' AND cmd.date = '$date_from' AND cmd.start_time > '$now_time' AND cmd.status = 'active') OR (cm.student_id = '$user_id' AND cmd.date > '$date_from' AND cmd.date <= '$date_to' AND cmd.status = 'active')");
        }else{
            $this->db->where('cmd.status', 'active');
            $this->db->where('cm.student_id', $user_id);
            $this->db->where('cmd.date >= ', $date_from);
            $this->db->where('cmd.date <= ', $date_to);
        }
        if($user_id){
            $this->db->where('cm.student_id', $user_id);
        }else{
            $this->db->where('cm.student_id', $this->auth_manager->userid());
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
     * @desc    Get session history with defined time period for STUDENT; maximum two months
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
        
        $this->db->select('cl.class_name, cmd.id, cmd.dupd, cmd.date, cmd.start_time, cmd.end_time, cmd.coach_id,cmd.class_id, up.fullname as coach_name, wc.stream_url, cl.class_name')
                ->from($this->table . ' cm')
                ->join('classes cl', 'cm.class_id = cl.id')
                ->join('class_meeting_days cmd', 'cm.class_id = cmd.class_id')
                ->join('user_profiles up', 'up.user_id = cmd.coach_id')
                ->join('webex_class wc', 'wc.class_meeting_id = cmd.id', 'left')
                ->order_by('cmd.date', 'desc')
                ->order_by('cmd.start_time', 'desc');
        if ($date_to == $now_date) {
            $this->db->where("(cm.$user_field = '$user_id' AND cmd.date = '$date_to' AND cmd.end_time < '$now_time' AND cmd.status = 'active') OR (cm.$user_field = '$user_id' AND cmd.date >= '$date_from' AND cmd.date < '$date_to' AND cmd.status = 'active')");
        }
        else{ 
            $this->db->where('cmd.status', 'active');
            $this->db->where('cm.'.$user_field, $user_id);
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
}

/* End of file class_member_model.php */
/* Location: ./application/models/class_member_model.php */
