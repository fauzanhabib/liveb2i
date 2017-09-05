<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

/**
 * Class appointment_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class appointment_model extends MY_Model {

    // Table name in database
    var $table = 'appointments';
    // Validation rules
    var $validate = array();

    function get_rate_coach() {
        $data = array();
        foreach ($this->get_distinct() as $a) {

            $this->db->select("a.id, a.coach_id , a.date, a.start_time, a.end_time, up.fullname, (AVG(cr.rate)) AS rating_average , count(rate) AS number_of_raters");
            $this->db->from('appointments a');
            $this->db->where('a.coach_id', $a->coach_id);
            $this->db->where('status', 'active');
            $this->db->where('student_id', $this->auth_manager->userid());
            $this->db->where('date <=', date('Y-m-d'));
            $this->db->where('end_time <=', date('H:i:s'));
            $this->db->order_by("a.end_time", "desc");
            $this->db->order_by("a.date", "desc");
            $this->db->join('user_profiles up', 'a.coach_id = up.user_id');
            $this->db->join('coach_ratings cr', 'a.coach_id = cr.coach_id', 'left');
            $data[] = $this->db->get()->result()[0];
        }

        return $data;
    } 

    function get_distinct() {
        $this->db->select("distinct(a.coach_id)");
        $this->db->from('appointments a');
        $this->db->where('status', 'active');
        $this->db->where('student_id', $this->auth_manager->userid());
        return $this->db->get()->result();
    }

    function count_raters($coach_id = '') {
        $this->db->select("count(rate) AS raters");
        $this->db->from('coach_ratings');
        $this->db->where('coach_id', $coach_id);
        return $this->db->get()->result();
    }
    
    /**
     * @desc    Get session history with defined time period; maximum two months
     * @param   (string)(field_user_id) coach_id or student_id
     * @param   (int)(user_id)
     * @param   (date)(date_from) 'Y-m-d'
     * @param   (date)(date_to) 'Y-m-d'
     * @param   (int)(limit)
     * @param   (int)(offset)
     */
    public function get_session_histories($user_field='', $user_id='', $date_from='', $date_to='', $limit='', $offset='') {
        date_default_timezone_set('Etc/GMT+0');
        $now_date = date('Y-m-d');
        $now_time = date('H:i:s');
        $history_date = date('Y-m-d',strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-100 year"));

        if(!$user_id){
            $user_id = $this->auth_manager->userid();
        }

        $gmt_user = $this->identity_model->new_get_gmt($user_id);
        if(!$gmt_user){
            $gmt_user = $this->identity_model->new_get_gmt($this->auth_manager->userid());
        }
        $minutes = $gmt_user[0]->minutes;

        $string = strtotime($date_from);
        $min = $string - (60*$minutes);
        $stringtodate = date('Y-m-d', $min);
        $stringtotime = date('H:i:s', $min);

        $string_to = strtotime($date_to);
        $min_to = $string_to - (60*$minutes);
        $stringtodate_to = date('Y-m-d', $min_to);
        $stringtotime_to = date('H:i:s', $min_to);
        
        $this->db->select('ap.session, ap.downloadurl, ap.cch_attend, ap.std_attend, ap.id, ap.dupd, ap.date, ap.start_time, ap.end_time, ap.student_id, ap.status, up1.fullname as coach_name, up2.fullname as student_name, ap.coach_id, cr.rate, cr2.note, w.stream_url')
                ->from($this->table . ' ap')
                ->join('user_profiles up1', 'up1.user_id = ap.coach_id')
                ->join('user_profiles up2', 'up2.user_id = ap.student_id')
                ->join('coach_ratings cr', 'cr.appointment_id = ap.id', 'left')
                ->join('coach_reports cr2', 'cr2.appointment_id = ap.id', 'left')
                ->join('webex w', 'w.appointment_id = ap.id', 'left')
                ->order_by('ap.date', 'desc')
                ->order_by('ap.start_time', 'desc');
        // if ($date_to == $now_date) {
        //     $this->db->where("(ap.$user_field = '$user_id' AND ap.date = '$date_to' AND ap.end_time < '$now_time' AND ap.status = 'active') OR (ap.$user_field = '$user_id' AND ap.date >= '$date_from' AND ap.date < '$date_to' AND ap.status = 'active')");
        // }
        // else{
            if(($date_from==$history_date) && ($date_to==$now_date)){
                $this->db->where('ap.'.$user_field, $user_id);
                $this->db->where('ap.date >= ', $date_from);
                $this->db->where('ap.date <= ', $date_to);
                $this->db->where('ap.status', 'completed');
            }
            else{
                $this->db->where('ap.'.$user_field, $user_id);
                $this->db->where("(ap.date BETWEEN '$date_from' AND '$date_to') AND (ap.start_time<='$stringtotime') AND (ap.status='completed') or (ap.date BETWEEN '$stringtodate' AND '$stringtodate_to') AND (ap.start_time>='$stringtotime_to') AND (ap.status='completed')");
                //$this->db->where('ap.status', 'completed');
            }
        // }
        
        if($limit && $offset && $offset=="first_page"){
            $this->db->limit($limit);
            $this->db->offset(0);
        }elseif($limit && $offset){
            $this->db->limit($limit);
            $this->db->offset($offset);
        }
        return $this->db->get()->result();
    }
    
    public function get_appointment_for_upcoming_session($user_field='', $date_from='', $date_to='', $user_id='',$limit='', $offset='') {
        date_default_timezone_set('Etc/GMT+0');
        $now_date = date('Y-m-d');
        $now_time = date('H:i:s');
        
        if(!$user_id){
            $user_id = $this->auth_manager->userid();
        }

        $gmt_user = $this->identity_model->new_get_gmt($user_id);
        if(!$gmt_user){
            $gmt_user = $this->identity_model->new_get_gmt($this->auth_manager->userid());
        }
        $minutes = $gmt_user[0]->minutes;

        $string = strtotime($date_from);
        $min = $string - (60*$minutes);
        $stringtodate = date('Y-m-d', $min);
        $stringtotime = date('H:i:s', $min);

        $string_to = strtotime($date_to);
        $min_to = $string_to - (60*$minutes);
        $stringtodate_to = date('Y-m-d', $min_to);
        $stringtotime_to = date('H:i:s', $min_to);
        
        $this->db->select('ap.id, ap.student_id, ap.coach_id, ap.date, ap.start_time, ap.end_time, ap.status, ap.dcrea, ap.dupd, ctc.token_for_student, we.status as webex_status, up.fullname as student_name, up2.fullname as coach_fullname')
                ->from($this->table . ' ap ')
                ->join('coach_token_costs ctc', 'ap.coach_id = ctc.coach_id')
                ->join('webex we', 'ap.id = we.appointment_id', 'left')
                ->join('user_profiles up', "ap.student_id = up.user_id")
                ->join('user_profiles up2', "ap.coach_id = up2.user_id") 
                // ->where('ap.status', 'active')
                // ->where('ap.status', 'reschedule')
                ->order_by('ap.date', 'asc')->order_by('ap.start_time', 'asc');
        if(!$date_from && !$date_to){
            $this->db->where("(ap.$user_field = '$user_id' AND ap.date = '$now_date' AND ap.start_time > '$now_time' AND ap.status = 'active') or (ap.$user_field = '$user_id' AND ap.date = '$now_date' AND ap.start_time > '$now_time' AND ap.status = 'reschedule') or (ap.$user_field = '$user_id' AND ap.date > '$now_date' AND ap.status = 'active') or (ap.$user_field = '$user_id' AND ap.date > '$now_date' AND ap.status = 'reschedule')");
                                    
        }elseif ($date_from == $now_date) {
            
            $this->db->where("(ap.$user_field = '$user_id' AND ap.date = '$date_from' AND ap.start_time > '$now_time' AND ap.status = 'active') OR (ap.$user_field = '$user_id' AND ap.date = '$date_from' AND ap.start_time > '$now_time' AND ap.status = 'reschedule') OR (ap.$user_field = '$user_id' AND ap.date > '$date_from' AND ap.date <= '$date_to' AND ap.status = 'active') OR (ap.$user_field = '$user_id' AND ap.date > '$date_from' AND ap.date <= '$date_to' AND ap.status = 'reschedule')");
        }else{
            $this->db->where('ap.'.$user_field, $user_id);
            $this->db->where("(ap.date BETWEEN '$date_from' AND '$date_to') AND (ap.start_time<='$stringtotime') or (ap.date BETWEEN '$stringtodate' AND '$stringtodate_to') AND (ap.start_time>='$stringtotime_to')");
            $this->db->where("(ap.status='active' OR ap.status='reschedule')");
            //$this->db->where('ap.status', 'reschedule');
            // $this->db->where('ap.'.$user_field, $user_id);
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

    public function get_appointment_for_ongoing_session($field_id_user) {
        date_default_timezone_set('Etc/GMT+0');
        $this->db->select('ap.id, ap.student_id, ap.coach_id, ap.date, ap.start_time, ap.end_time, ap.status, ap.dcrea, ap.dupd, we.status as webex_status, we.webex_meeting_number, we.host_id, we.password as session_password,we.id as webex_session_id, wh.subdomain_webex, wh.webex_id, wh.partner_id, wh.password as host_password')
                ->from($this->table . ' ap ')
                ->join('webex we', 'ap.id = we.appointment_id', 'left')
                ->join('webex_host wh', 'we.host_id = wh.id', 'left')
                ->where('ap.status', 'active')
                ->where('ap.' . $field_id_user, $this->auth_manager->userid())
                ->where('ap.date =', date('Y-m-d'))
                ->where('ap.start_time <=', date('H:i:s'))
                ->where('ap.end_time >=', date('H:i:s'));

        return $this->db->get()->result();
    }

    public function update_multiple_book() {
        $data = array(
            'status' => 'active',
        );

        $this->db->where('student_id', $this->auth_manager->userid());
        $this->db->where('status', 'temporary');
        $this->db->update('appointments', $data);
    }

    /**
     * @function get appointment for webex invitation
     * @param (int) meeting_identifier appointment id
     */
    public function get_appointment_for_webex_invitation($meeting_identifier=''){
        $this->db->select('ap.id, ap.student_id, ap.date, ap.start_time, ap.end_time, up2.fullname as coach_name, up.fullname as student_name, us.email as student_email, we.webex_meeting_number')
                ->from($this->table . ' ap ')
                ->join('webex we', 'ap.id = we.appointment_id')
                ->join('user_profiles up', 'ap.student_id = up.user_id')
                ->join('user_profiles up2', 'ap.coach_id = up2.user_id')
                ->join('users us', 'ap.student_id = us.id')
                ->where('ap.status', 'active')
                ->where('ap.id', $meeting_identifier);
        return $this->db->get()->result();
    }
    
    /**
     * FOR XML
     * @function get appointment for webex invitation
     * @param (int) meeting_identifier appointment id
     */
    public function get_appointment_for_webex_invitation_xml($meeting_identifier=''){
        $this->db->select('ap.id, ap.student_id, ap.date, ap.start_time, ap.end_time, up2.fullname as coach_fullname, up.fullname as student_fullname, us.email as student_email')
                ->from($this->table . ' ap ')
                ->join('user_profiles up', 'ap.student_id = up.user_id')
                ->join('user_profiles up2', 'ap.coach_id = up2.user_id')
                ->join('users us', 'ap.student_id = us.id')
                ->where('ap.status', 'active')
                ->where('ap.id', $meeting_identifier);
        return $this->db->get()->result();
    }
    
    // Get student's appointments
    public function get_appointments($student) {
        $this->db->select('ap.id, ap.coach_id, up2.fullname as student_fullname, up.fullname as coach_fullname, ap.date, ap.start_time, ap.end_time, ap.status')
            ->from($this->table . ' ap')
            ->join('user_profiles up', 'ap.coach_id = up.user_id')
            ->join('user_profiles up2', 'ap.student_id = up2.user_id')    
            ->where(array('ap.student_id' => $student, 'ap.status' => 'active'))
            ->order_by('up2.fullname', 'asc')
            ->order_by('ap.date', 'asc')
            ->order_by('ap.start_time', 'asc');
        return $this->db->get()->result();
    }
    
    // Get student appointment
    public function get_appointment($appointment_id) {
        $this->db->select('ap.id, ap.coach_id, up2.fullname as student_fullname, up.fullname as coach_fullname, ap.date, ap.start_time, ap.end_time, ap.status')
            ->from($this->table . ' ap')
            ->join('user_profiles up', 'ap.coach_id = up.user_id')
            ->join('user_profiles up2', 'ap.student_id = up2.user_id')    
            ->where('ap.id', $appointment_id);
        return $this->db->get()->result();
    }
}

/* End of file appointment_model.php */
/* Location: ./application/models/appointment_model.php */