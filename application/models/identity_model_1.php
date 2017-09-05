<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class identity_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class identity_model extends MY_Model {

    // Table name in database, the default is profile because every role has it
    var $table;
    
    var $temp = array(
//        'communication_tool' => 'user_communication_tools',
        'education' => 'user_educations',
        'geography' => 'user_geography',
        'profile' => 'user_profiles',
        'social_media' => 'user_social_media',
        'token' => 'user_tokens',
        'user' => 'users',
        'student_detail' => 'student_detail_profiles'
    );
    
    var $validate;
    
    // Validation rules for profile
    var $temp_validate = array(
        'education' => array(
                            array('field'=>'teaching_credential', 'label' => 'Teaching Credential', 'rules'=>'trim|required|xss_clean'),
                            array('field'=>'year_experience', 'label' => 'Year Experience', 'rules'=>'trim|required|xss_clean'),
                            array('field'=>'special_english_skill', 'label' => 'Special English Skill', 'rules'=>'trim|required|xss_clean'),
                        ),
        'geography' => array(
                            array('field'=>'country', 'label' => 'Country', 'rules'=>'trim|required|xss_clean'),
                            array('field'=>'state', 'label' => 'State', 'rules'=>'trim|required|xss_clean'),
                            array('field'=>'city', 'label' => 'City', 'rules'=>'trim|required|xss_clean'),
                        ),
        'profile' => array(
                            array('field'=>'fullname', 'label' => 'Name', 'rules'=>'trim|required|xss_clean')
                        ),
        'social_media' => array(
                            array('field'=>'code', 'label' => 'Code', 'rules'=>'trim|required|xss_clean'),
                            array('field'=>'name', 'label' => 'Name', 'rules'=>'trim|required|xss_clean'),
                        ),
        'token' => array(
                        ),
        'user' => array(
                        ),
        'student_detail' => array(
                        )
    );

	
    public function get_identity($key)
    {
        unset($this->table);
        $this->table = $this->temp[$key];
        $this->validate = $this->temp_validate[$key];
        return $this->identity_model;
        
    }
    
    public function get_geography()
    {
        unset($this->table);
        $this->table = $this->temp['geography'];
        $this->validate = $this->temp_validate['geography'];
        return $this->identity_model;
        
    }
    
    
    
    public function get_education()
    {
        $table = 'user_educations';
        $validate = array();
        return $this->identity_model;
        
    }
    
    public function get_validation($key=''){
        return $this->temp_validate[$key];
    }
    
    public function get_student_identity($id = '', $fullname = '', $partner_id = '', $creator_id = '', $limit='', $offset='')
    {
        $this->db->select("a.id, a.status, a.email, b.code as 'role', c.profile_picture, c.fullname, c.nickname, c.gender, c.date_of_birth, c.phone, c.skype_id, c.partner_id, c.dyned_pro_id, c.spoken_language, c.user_timezone, d.token_amount, e.city, e.state, e.zip, e.country, f.language_goal, f.like, f.dislike, f.hobby");
        $this->db->from('users a');
        $this->db->order_by("a.status", "desc"); 
        $this->db->join('user_roles b', 'a.role_id = b.id');
        $this->db->join('user_profiles c', 'a.id = c.user_id');
        $this->db->join('user_tokens d', 'a.id = d.user_id');
        $this->db->join('user_geography e', 'a.id = e.user_id', 'left');
        $this->db->join('student_detail_profiles f', 'a.id = f.student_id', 'left');
        if($creator_id){
            $this->db->join('creator_members g', 'a.id = g.member_id');
            $this->db->where('g.creator_id', $creator_id);
        }
        else{
            $this->db->where('a.status', 'active');
        }
        
        $this->db->where('b.id', 1);
        if($id){
            $this->db->where('a.id', $id);
        }
        if($fullname){
            $this->db->like('c.fullname', $fullname, 'both');
        }
        if($partner_id){
            $this->db->where('c.partner_id', $partner_id);
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
    
    public function get_coach_identity($id = '', $fullname = '', $country = '', $partner_id = '', $date_available = '', $creator_id = '', $spoken_language='', $limit='', $offset='')
    {   
        if(!$partner_id){
            $partner_id = $this->auth_manager->partner_id();
        }
        //echo('<pre>');
        //print_r($this->get_coach_supplier($partner_id)); exit;
        $coach_supplier = $this->get_coach_supplier($partner_id);
        
        $this->db->select("a.id, a.status, a.email, b.code as 'role', c.profile_picture, c.fullname, c.nickname, c.gender, c.date_of_birth, c.phone, c.skype_id, c.partner_id, c.dyned_pro_id, c.spoken_language, c.user_timezone, d.teaching_credential, d.dyned_certification_level, d.year_experience, d.special_english_skill, d.higher_education, d.undergraduate, d.masters, d.phd, e.city, e.state, e.zip, e.country, h.token_for_student, h.token_for_group, i.timezone");
        $this->db->from('users a');
        $this->db->order_by("a.status", "desc");
        $this->db->join('user_roles b', 'a.role_id = b.id');
        $this->db->join('user_profiles c', 'a.id = c.user_id');
        $this->db->join('user_educations d', 'a.id = d.user_id');
        $this->db->join('user_geography e', 'a.id = e.user_id', 'full');
        $this->db->join('coach_token_costs h', 'a.id = h.coach_id');
        $this->db->join('timezones i', 'c.user_timezone = i.id');
        if($date_available){
            $this->db->join('coach_dayoffs f', 'a.id = f.coach_id', 'full');
        }
        if($creator_id){
            $this->db->join('creator_members g', 'a.id = g.member_id');
            $this->db->where('g.creator_id', $creator_id);
        }
        else{
            $this->db->where('a.status', 'active');
        }
        
        $this->db->where('b.id', 2);
        if($id)
            $this->db->where('a.id', $id);
        if($fullname)
            $this->db->like('c.fullname', $fullname, 'both');
        if($country)
            $this->db->where('e.country', $country);
        if($spoken_language){
            $this->db->like('c.spoken_language', $spoken_language);
        }
        if($partner_id){
            if(!$id){
                $this->db->where('c.partner_id', $partner_id);
                foreach(@$coach_supplier as $cs){
                    $this->db->or_where('c.partner_id', $cs->coach_supplier_id);
                }
            }
        }
        
        
        if($date_available){
            if($this->db->set("day_off_status", "case when f.status = 'disable'")){
                $this->db->where('f.start_date > ', $date_available);
                $this->db->or_where('f.end_date < ', $date_available);
            }
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
    
    private function get_coach_supplier($student_partner_id){
            $this->db->select("csr.coach_supplier_id");
            $this->db->from('coach_supplier_relations csr');
            $this->db->join('student_supplier_relations ssr', 'csr.class_matchmaking_id = ssr.class_matchmaking_id');
            $this->db->where('ssr.student_supplier_id', $student_partner_id);

            return $this->db->get()->result();
        }
    
    public function get_partner_identity($id = '', $fullname = '', $partner_id = '', $creator_id = '', $role_id='')
    {
        $this->db->select("a.id, a.status, a.email, b.code as 'role', c.profile_picture, c.fullname, c.nickname, c.gender, c.date_of_birth, c.phone, c.skype_id, c.partner_id, c.dyned_pro_id, c.spoken_language, c.user_timezone");
        $this->db->from('users a');
        $this->db->order_by("a.status", "desc"); 
        $this->db->join('user_roles b', 'a.role_id = b.id');
        $this->db->join('user_profiles c', 'a.id = c.user_id');
        if($creator_id){
            $this->db->join('creator_members d', 'a.id = d.member_id');
            $this->db->where('d.creator_id', $creator_id);
        }
        else{
            $this->db->where('a.status', 'active');
        }
        if($role_id == '5'){
            $this->db->where('b.id', 5);
        }
        else if($role_id){
            $this->db->where('b.id', 3);
        }
        else{
            $this->db->where('b.id', 5);
            $this->db->or_where('b.id', 3);
        }
        if($id)
            $this->db->where('a.id', $id);
        if($fullname)
            $this->db->like('c.fullname', $fullname, 'both');
        if($partner_id)
            $this->db->where('c.partner_id', $partner_id);

        return $this->db->get()->result();
    }
    
    public function get_gmt($user_id){
        $this->db->select("up.user_timezone, t.timezone_index, t.timezone, t.minutes");
        $this->db->from('user_profiles up');
        $this->db->join('timezones t', 'up.user_timezone = t.id');
        $this->db->where('up.user_id', $user_id);
       
        return $this->db->get()->result();
    }
    
    
          
}
/* End of file student_identity_model.php */
/* Location: ./application/models/student_identity_model.php */
