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
//                            array('field'=>'teaching_credential', 'label' => 'Teaching Credential', 'rules'=>'trim|required|xss_clean'),
//                            array('field'=>'year_experience', 'label' => 'Year Experience', 'rules'=>'trim|required|xss_clean'),
//                            array('field'=>'special_english_skill', 'label' => 'Special English Skill', 'rules'=>'trim|required|xss_clean'),
                        ),
        'geography' => array(
//                            array('field'=>'country', 'label' => 'Country', 'rules'=>'trim|required|xss_clean'),
//                            array('field'=>'state', 'label' => 'State', 'rules'=>'trim|required|xss_clean'),
//                            array('field'=>'city', 'label' => 'City', 'rules'=>'trim|required|xss_clean'),
                        ),
        'profile' => array(
            array('field'=>'fullname', 'label' => 'Name', 'rules'=>'trim|required|xss_clean'),
            /*array('field'=>'date_of_birth', 'label' => 'Birtday', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'spoken_language', 'label' => 'Preferred Language', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'gender', 'label' => 'Gender', 'rules'=>'trim|required|xss_clean')*/
        ),
        'social_media' => array(
            array('field'=>'code', 'label' => 'Code', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'name', 'label' => 'Name', 'rules'=>'trim|required|xss_clean')
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

    public function get_student_identity($id = '', $fullname = '', $partner_id = '', $creator_id = '', $limit = '', $offset = '', $subgroup_id = '')
    {
        if(($this->uri->segment(1) == 'student_partner') && ($this->uri->segment(2) != 'member_list') && ($this->uri->segment(2) != 'add_token') && ($this->uri->segment(3) != 'student_detail')){
             $subgroup_id = $this->uri->segment(4);
        }
        $this->db->select("a.id, a.status, a.email, b.code as 'role', c.profile_picture, c.fullname, c.nickname, c.gender, c.date_of_birth, c.dial_code, c.phone, c.skype_id, c.partner_id, c.dyned_pro_id, c.spoken_language, c.user_timezone, c.dyned_pro_id, c.server_dyned_pro, c.cert_studying, d.token_amount, e.city, e.state, e.zip, e.country,e.address, f.language_goal, f.like, f.dislike, f.hobby, j.timezone");
        $this->db->from('users a');
        // $this->db->order_by("a.status", "desc");
        $this->db->join('user_roles b', 'a.role_id = b.id','left');
        $this->db->join('user_profiles c', 'a.id = c.user_id','left');
        $this->db->join('user_tokens d', 'a.id = d.user_id','left');
        $this->db->join('user_geography e', 'a.id = e.user_id', 'left');
        $this->db->join('user_timezones i', 'i.user_id = a.id','left');
        $this->db->join('timezones j', 'j.minutes = i.minutes_val','left');
        $this->db->join('student_detail_profiles f', 'a.id = f.student_id', 'left');
        $this->db->order_by('c.user_id', 'desc');
        $this->db->group_by('a.id');
        if($creator_id){
            $this->db->join('creator_members g', 'a.id = g.member_id');
            $this->db->where('g.creator_id', $creator_id);
        }
        else{
            if(($this->uri->segment(3) == 'list_disable_student') || ($this->uri->segment(3) == 'index_disable')){
                $this->db->where('a.status', 'disable');
            }else
            {
                // $this->db->where('a.status', 'active');
            }
        }

        if (is_array($subgroup_id)) {
             $this->db->where_in('c.subgroup_id',$subgroup_id);
        }
        if($subgroup_id){
            $this->db->where('c.subgroup_id',$subgroup_id);
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

    public function get_coach_identity($id = '', $fullname = '', $country = '', $partner_id = '', $date_available = '', $creator_id = '', $spoken_language='', $limit='', $offset='', $subgroup_id = ''){
        // echo $subgroup_id;
        // exit();
        if(($this->uri->segment(1) == 'partner') && ($this->uri->segment(3) != 'coach_detail')){
           $subgroup_id = $this->uri->segment(4);
        }
        $coach_group = '';
        if(($this->uri->segment(1) == 'partner') && ($this->uri->segment(3) == 'reschedule')){
            $subgroup_id = '';
            $appointment_id = $this->uri->segment(4);

            $student_id = $this->db->select('appointments.student_id as student_id')->from('appointments')->where('appointments.id',$appointment_id)->get()->result();
            $student_id = $student_id[0]->student_id;

            $user_subgroup = $this->db->select('user_profiles.subgroup_id as subgroup_id')->from('user_profiles')->where('user_profiles.user_id',$student_id)->get()->result();
            $user_subgroup = $user_subgroup[0]->subgroup_id;
            $coach_group = $this->get_coach_group($user_subgroup);
        }



//        $fullname = 'coach1';
//        print_r($fullname); //exit;
        if(!$partner_id){
            $partner_id = $this->auth_manager->partner_id();
        }

            $cert_studying = $this->db->select('user_profiles.cert_studying as cert_studying')->from('user_profiles')->where('user_profiles.user_id',$this->auth_manager->userid())->get()->result();
            $cert_studying = $cert_studying[0]->cert_studying;

            if($this->auth_manager->role() == 'STD'){
                $user_subgroup = $this->db->select('user_profiles.subgroup_id as subgroup_id')->from('user_profiles')->where('user_profiles.user_id',$this->auth_manager->userid())->get()->result();
                $user_subgroup = $user_subgroup[0]->subgroup_id;
                $coach_group = $this->get_coach_group($user_subgroup);

                $partner_subgroup = $this->db->select('id')->from('subgroup')->where('partner_id', $partner_id)->where('status', 'active')->where('type', 'student')->get()->result();

                $partner_subgroup_group = array();
                foreach($partner_subgroup as $psg){
                    $partner_subgroup_group[] = $this->get_coach_group($psg->id);
                }

                $partner_subgroup_student = array();
                foreach($partner_subgroup as $psg){
                    $partner_subgroup_student[] = $this->get_student_group($psg->id);
                }

                $check_array_coach = array_filter($partner_subgroup_group);
                $check_array_student = array_filter($partner_subgroup_student);
                // if($user_subgroup == $partner_subgroup_student[0][0]->subgroup_id){
                //     echo "a";
                //     exit();
                // }else{
                //     echo "b";
                //     exit();
                // }

                // echo "<pre>";
                // print_r($check_array_coach);
                // exit();

                $partner_group = array();
                foreach($coach_group as $cogu){
                    $partner_group[] = $this->get_partner_group($cogu->subgroup_id);
                }

                $partners_group = array();
                $pagu_c = 0;
                $core_c = 0;
                @$coach_supplier = $this->get_coach_supplier($partner_id);
                $coach_relation = array();
                foreach($coach_supplier as $sup){
                    @$coach_relation[] = $this->db->select('class_matchmaking_id')->from('coach_supplier_relations')->where('coach_supplier_id', $sup->coach_supplier_id)->order_by('id', 'desc')->get()->result();
                }
                
                $corel = array();
                foreach($coach_relation as $cr){
                    @$corel[] = $cr[0]->class_matchmaking_id;
                }
                $corel_new = array_unique($corel);
                $coregro = array();
                foreach($corel_new as $cl){
                    @$coregro[] = $this->db->select('subgroup_id')->from('coach_group_relations')->where('class_matchmaking_id', $cl)->get()->result();
                }
                $partner_group2 = array();
                foreach($coregro as $cgo){
                    foreach($cgo as $cgval){
                        $partner_group2[] = $this->get_partner_group($cgval->subgroup_id);
                    }
                }
                // if(empty($coregro[0])){
                //     echo "a";
                //     exit();
                // }else{
                //     echo "b";
                //     exit();
                // }
            }
            // echo "<pre>";
            // print_r($partner_group2);
            // exit();
        @$coach_supplier = $this->get_coach_supplier($partner_id);
        // echo('<pre>');
        // print_r($this->get_coach_supplier($partner_id)); exit;
        
        $this->db->select("a.id, a.status, a.email, b.code as 'role', c.profile_picture, c.fullname, c.nickname, c.gender, c.date_of_birth, c.dial_code, c.phone, c.skype_id, c.partner_id, c.dyned_pro_id, c.spoken_language, c.user_timezone, c.pt_score, d.teaching_credential, d.dyned_certification_level, d.year_experience, d.special_english_skill, d.higher_education, d.undergraduate, d.masters, d.phd, e.city, e.state, e.zip, e.country, e.address, h.token_for_student, h.token_for_group, j.timezone, c.coach_type_id as coach_type_id");
        $this->db->from('users a');
        $this->db->order_by("a.status", "desc");
        $this->db->join('user_roles b', 'a.role_id = b.id');
        $this->db->join('user_profiles c', 'a.id = c.user_id');
        $this->db->join('user_educations d', 'a.id = d.user_id');
        $this->db->join('user_geography e', 'a.id = e.user_id', 'full');
        $this->db->join('coach_token_costs h', 'a.id = h.coach_id');
        $this->db->join('user_timezones i', 'i.user_id = a.id','left');
        $this->db->join('timezones j', 'j.minutes = i.minutes_val','left');
        $this->db->order_by('c.fullname', 'asc');
        if($subgroup_id){
            $this->db->where('c.subgroup_id',$subgroup_id);
        }
        if($partner_id){
            if(!$id){

                if(($this->auth_manager->role() == 'STD') || ($this->auth_manager->role() == 'SPR') || ($this->auth_manager->role() == 'PRT')){
                    //$this->db->where('c.partner_id', $partner_id);
                    if($coach_group){
                        $partner_array= array($partner_id);
                        $group_array= array($user_subgroup);
                        foreach(@$coach_supplier as $cs){
                            foreach(@$coach_group as $cg){
                            if($cs->coach_supplier_id != $partner_id){
                                if($cg->subgroup_id != $user_subgroup){
                                    //$this->db->or_where('c.partner_id', $cs->coach_supplier_id);
                                    $partner_array[] = $cs->coach_supplier_id;
                                    $group_array[] = $cg->subgroup_id;
                                    }
                                }
                            }
                        }
                    $new_partner_array= array_unique($partner_array);
                    $new_group_array= array_unique($group_array);
                    foreach($partner_group as $pg){
                        $partners_group[] = $pg[$pagu_c]->partner_id;
                        if (($key = array_search($pg[$pagu_c]->partner_id, $new_partner_array)) !== false) {
                                unset($new_partner_array[$key]);
                            }
                    }
                    $this->db->where_in('c.subgroup_id', $new_group_array);
                         if($date_available){
                            $this->db->join('coach_dayoffs f', 'a.id = f.coach_id', 'full');
                         }
                         if($creator_id){
                             $this->db->join('creator_members g', 'a.id = g.member_id');
                             $this->db->where('g.creator_id', $creator_id);
                         }
                         if(($this->uri->segment(3) == 'list_disable_coach') || ($this->uri->segment(3) == 'index_disable')){
                            $this->db->where('a.status', 'disable');
                         }else
                         {
                            $this->db->where('a.status', 'active');
                         }
                            $this->db->where('b.id', 2);
                         if($id){
                            $this->db->where('a.id', $id);
                         }
                         if($fullname){
                            $this->db->where("c.fullname LIKE '%$fullname%'");
                         }
                         if($country){
                            $this->db->where('e.country', $country);
                         }
                         if($spoken_language){
                            $this->db->where("c.spoken_language LIKE '%$spoken_language%'");
                         }

                         if($date_available){
                             if($this->db->set("day_off_status", "case when f.status = 'disable'")){
                                 $this->db->where('f.start_date > ', $date_available);
                                 $this->db->or_where('f.end_date < ', $date_available);
                             }
                         }
                         if($this->uri->segment(1) != 'b2c'){
                             if(($this->uri->segment(1) == 'student') && ($this->uri->segment(2) == 'find_coaches')){
                             // echo $cert_studying;
                             // exit();
                                 if(($cert_studying == 'A1') || ($cert_studying == 'A2')){
                                    $this->db->where('c.pt_score >=','2.5');
                                 }else if (($cert_studying == 'B1') || ($cert_studying == 'B2')){
                                    $this->db->where('c.pt_score >=','3');
                                 } else if (($cert_studying == 'C1') || ($cert_studying == 'C2')){
                                    $this->db->where('c.pt_score >=','3.5');
                                 }
                                 else if($cert_studying == 0){
                                    $this->db->where('c.pt_score >','999999');
                                 }
                             }
                         }else{
                             if(($this->uri->segment(2) == 'student') && ($this->uri->segment(3) == 'find_coaches')){
                             // echo $cert_studying;
                             // exit();
                                 if(($cert_studying == 'A1') || ($cert_studying == 'A2')){
                                    $this->db->where('c.pt_score >=','2.5');
                                 } else if (($cert_studying == 'B1') || ($cert_studying == 'B2')){
                                    $this->db->where('c.pt_score >=','3');
                                 } else if (($cert_studying == 'C1') || ($cert_studying == 'C2')){
                                    $this->db->where('c.pt_score >=','3.5');
                                 }
                                 else if($cert_studying == 0){
                                    $this->db->where('c.pt_score >','999999');
                                 }
                             }
                         }
                         $this->db->or_where_in('c.partner_id', $new_partner_array);
                    }elseif(empty($coach_group) && empty($check_array_coach) && empty($check_array_student) && $coach_supplier && empty($coregro[0])){
                        // echo "a";
                        // exit();
                        $partner_array= array($partner_id);
                        foreach(@$coach_supplier as $cs){
                            if($cs->coach_supplier_id != $partner_id){
                                    //$this->db->or_where('c.partner_id', $cs->coach_supplier_id);
                                    $partner_array[] = $cs->coach_supplier_id;
                            }
                        }
                        $new_partner_array= array_unique($partner_array);
                        $this->db->where_in('c.partner_id', $new_partner_array);
                    }elseif(empty($coach_group) && ($check_array_student || $check_array_coach) && $coach_supplier){
                        // echo "b";
                        // exit();
                            foreach($check_array_student as $ch){
                                $ccc = $ch[$pagu_c]->subgroup_id;
                                if($ccc == $user_subgroup){
                                    $partner_array= array($partner_id);
                                    foreach(@$coach_supplier as $cs){
                                        if($cs->coach_supplier_id != $partner_id){
                                                //$this->db->or_where('c.partner_id', $cs->coach_supplier_id);
                                                $partner_array[] = $cs->coach_supplier_id;
                                        }
                                    }
                                    $new_partner_array= array_unique($partner_array);
                                    $this->db->where_in('c.partner_id', $new_partner_array);
                                }else{
                                    $this->db->where('c.partner_id', $partner_id);
                                }
                            }
                    }elseif(empty($coach_group) && empty($check_array_coach) && empty($check_array_student) && $coach_supplier && $coregro){
                            // echo 'c';
                            // exit();
                            $partner_array= array($partner_id);
                            $coregro_array= array();
                            foreach(@$coach_supplier as $cs){
                                foreach(@$coregro as $cgo){
                                    foreach($cgo as $cgval){
                                        $coregro_array[] = $cgval->subgroup_id;
                                        $partner_array[] = $cs->coach_supplier_id;
                                    }
                                }
                            }
                            $new_partner_array= array_unique($partner_array);
                            $new_group_array= array_unique($coregro_array);
                            foreach($partner_group2 as $pg2){
                                $partners_group2[] = $pg2[$pagu_c]->partner_id;
                                if(($key = array_search($pg2[$pagu_c]->partner_id, $new_partner_array)) !== false){
                                        unset($new_partner_array[$key]);
                                }
                            }
                            $this->db->where_in('c.subgroup_id', $new_group_array);
                            $this->db->or_where_in('c.partner_id', $new_partner_array);
                    }
                }else{
                    $this->db->where('c.partner_id', $partner_id);
                }
            }
        }
        if($date_available){
            $this->db->join('coach_dayoffs f', 'a.id = f.coach_id', 'full');
        }
        if($creator_id){
            $this->db->join('creator_members g', 'a.id = g.member_id');
            $this->db->where('g.creator_id', $creator_id);
        }
        else{
            if(($this->uri->segment(3) == 'list_disable_coach') || ($this->uri->segment(3) == 'index_disable')){
                $this->db->where('a.status', 'disable');
            }else
            {
                $this->db->where('a.status', 'active');
            }
        }

        $this->db->where('b.id', 2);
        if($id){
            $this->db->where('a.id', $id);
        }
        if($fullname){
            $this->db->where("c.fullname LIKE '%$fullname%'");
        }
        if($country){
            $this->db->where('e.country', $country);
        }
        if($spoken_language){
            $this->db->where("c.spoken_language LIKE '%$spoken_language%'");
        }

        if($date_available){
            if($this->db->set("day_off_status", "case when f.status = 'disable'")){
                $this->db->where('f.start_date > ', $date_available);
                $this->db->or_where('f.end_date < ', $date_available);
            }
        }
        if($this->uri->segment(1) != 'b2c'){
            if(($this->uri->segment(1) == 'student') && ($this->uri->segment(2) == 'find_coaches')){
                // echo $cert_studying;
                // exit();
               if(($cert_studying == 'A1') || ($cert_studying == 'A2')){
                    $this->db->where('c.pt_score >=','2.5');
                } else if (($cert_studying == 'B1') || ($cert_studying == 'B2')){
                    $this->db->where('c.pt_score >=','3');
                } else if (($cert_studying == 'C1') || ($cert_studying == 'C2')){
                    $this->db->where('c.pt_score >=','3.5');
                }
                else if($cert_studying == 0){
                    $this->db->where('c.pt_score >','999999');
                }
            }
        }else{
            if(($this->uri->segment(2) == 'student') && ($this->uri->segment(3) == 'find_coaches')){
                // echo $cert_studying;
                // exit();
               if(($cert_studying == 'A1') || ($cert_studying == 'A2')){
                    $this->db->where('c.pt_score >=','2.5');
                } else if (($cert_studying == 'B1') || ($cert_studying == 'B2')){
                    $this->db->where('c.pt_score >=','3');
                } else if (($cert_studying == 'C1') || ($cert_studying == 'C2')){
                    $this->db->where('c.pt_score >=','3.5');
                }
                else if($cert_studying == 0){
                    $this->db->where('c.pt_score >','999999');
                }
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

    public function get_coach_identity_reschedule($id = '', $fullname = '', $country = '', $partner_id = '', $date_available = '', $creator_id = '', $spoken_language='', $coach_type = '',$limit='', $offset='', $subgroup_id = ''){
        // echo $id;
        // exit($coach_type);

        if(($this->uri->segment(1) == 'partner') && ($this->uri->segment(3) != 'coach_detail')){
           $subgroup_id = $this->uri->segment(4);
        }
        $coach_group = '';
        if(($this->uri->segment(1) == 'partner') && ($this->uri->segment(3) == 'reschedule')){
            $subgroup_id = '';
            $appointment_id = $this->uri->segment(4);

            $student_id = $this->db->select('appointments.student_id as student_id')->from('appointments')->where('appointments.id',$appointment_id)->get()->result();
            $student_id = $student_id[0]->student_id;

            $user_subgroup = $this->db->select('user_profiles.subgroup_id as subgroup_id')->from('user_profiles')->where('user_profiles.user_id',$student_id)->get()->result();
            $user_subgroup = $user_subgroup[0]->subgroup_id;
            $coach_group = $this->get_coach_group($user_subgroup);
        }



//        $fullname = 'coach1';
       // print_r($id); exit;
        if(!$partner_id){
            $partner_id = $this->auth_manager->partner_id();
        }

            $cert_studying = $this->db->select('user_profiles.cert_studying as cert_studying')->from('user_profiles')->where('user_profiles.user_id',$this->auth_manager->userid())->get()->result();
            $cert_studying = $cert_studying[0]->cert_studying;

            if($this->auth_manager->role() == 'STD'){
                $user_subgroup = $this->db->select('user_profiles.subgroup_id as subgroup_id')->from('user_profiles')->where('user_profiles.user_id',$this->auth_manager->userid())->get()->result();
                $user_subgroup = $user_subgroup[0]->subgroup_id;
                $coach_group = $this->get_coach_group($user_subgroup);
            }

        //echo('<pre>');
        //print_r($this->get_coach_supplier($partner_id)); exit;
        $coach_supplier = $this->get_coach_supplier($partner_id);

        $this->db->select("a.id, a.status, a.email, b.code as 'role', c.profile_picture, c.fullname, c.nickname, c.gender, c.date_of_birth, c.dial_code, c.phone, c.skype_id, c.partner_id, c.dyned_pro_id, c.spoken_language, c.user_timezone, c.pt_score, d.teaching_credential, d.dyned_certification_level, d.year_experience, d.special_english_skill, d.higher_education, d.undergraduate, d.masters, d.phd, e.city, e.state, e.zip, e.country, e.address, h.token_for_student, h.token_for_group, j.timezone, c.coach_type_id as coach_type_id");
        $this->db->from('users a');
        $this->db->order_by("a.status", "desc");
        $this->db->join('user_roles b', 'a.role_id = b.id');
        $this->db->join('user_profiles c', 'a.id = c.user_id');
        $this->db->where('c.coach_type_id', $coach_type);
        $this->db->where('a.status', 'active');
        $this->db->join('user_educations d', 'a.id = d.user_id');
        $this->db->join('user_geography e', 'a.id = e.user_id', 'full');
        $this->db->join('coach_token_costs h', 'a.id = h.coach_id');
        $this->db->join('user_timezones i', 'i.user_id = a.id','left');
        $this->db->join('timezones j', 'j.minutes = i.minutes_val','left');
        $this->db->order_by('c.fullname', 'asc');

        if($subgroup_id){
            $this->db->where('c.subgroup_id',$subgroup_id);
        }
        if($country){
            $this->db->where('e.country',$country);
        }

        if($spoken_language){
            $this->db->where('c.spoken_language',$spoken_language);
        }
        if($partner_id){
            if(!$id){

                if(($this->auth_manager->role() == 'STD') || ($this->auth_manager->role() == 'SPR') || ($this->auth_manager->role() == 'PRT')){
                    //$this->db->where('c.partner_id', $partner_id);
                    if($coach_group){
                        $partner_array= array($partner_id);
                        $group_array= array($user_subgroup);
                        foreach(@$coach_supplier as $cs){
                            foreach(@$coach_group as $cg){
                            if($cs->coach_supplier_id != $partner_id){
                                if($cg->subgroup_id != $user_subgroup){
                                    //$this->db->or_where('c.partner_id', $cs->coach_supplier_id);
                                    $partner_array[] = $cs->coach_supplier_id;
                                    $group_array[] = $cg->subgroup_id;
                                    }
                                }
                            }
                        }
                    $this->db->where_in('c.partner_id', $partner_array);
                    $this->db->or_where_in('c.subgroup_id', $group_array);
                    }else{
                        $partner_array= array($partner_id);
                        foreach(@$coach_supplier as $cs){
                            if($cs->coach_supplier_id != $partner_id){
                                    //$this->db->or_where('c.partner_id', $cs->coach_supplier_id);
                                    $partner_array[] = $cs->coach_supplier_id;
                            }
                        }
                        $this->db->where_in('c.partner_id', $partner_array);
                    }
                }else{
                    $this->db->where('c.partner_id', $partner_id);
                }
            }
        }
        if($date_available){
            $this->db->join('coach_dayoffs f', 'a.id = f.coach_id', 'full');
        }
        if($creator_id){
            $this->db->join('creator_members g', 'a.id = g.member_id');
            $this->db->where('g.creator_id', $creator_id);
        }
        else{
            if(($this->uri->segment(3) == 'list_disable_coach') || ($this->uri->segment(3) == 'index_disable')){
                $this->db->where('a.status', 'disable');
            }else
            {
                $this->db->where('a.status', 'active');
            }
        }

        $this->db->where('b.id', 2);
        $this->db->where('c.coach_type_id', $coach_type);

        if($id)
            $this->db->where('a.id', $id);
        if($fullname)
            $this->db->like('c.fullname', $fullname, 'both');
        if($country)
            $this->db->where('e.country', $country);
        if($spoken_language){
            $this->db->like('c.spoken_language', $spoken_language);
        }

        if($date_available){
            if($this->db->set("day_off_status", "case when f.status = 'disable'")){
                $this->db->where('f.start_date > ', $date_available);
                $this->db->or_where('f.end_date < ', $date_available);
            }
        }

        if(($this->uri->segment(1) == 'student') && ($this->uri->segment(2) == 'find_coaches')){
            // echo $cert_studying;
            // exit();
           if(($cert_studying == 'A1') || ($cert_studying == 'A2')){
                $this->db->where('c.pt_score >=','2.5');
            } else if (($cert_studying == 'B1') || ($cert_studying == 'B2')){
                $this->db->where('c.pt_score >=','3');
            } else if (($cert_studying == 'C1') || ($cert_studying == 'C2')){
                $this->db->where('c.pt_score >=','3.5');
            }
            else if($cert_studying == 0){
                $this->db->where('c.pt_score >','999999');
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

    public function get_new_coach_identity_rescedule($partner_id = '', $coach_id='', $cert_studying = ''){

        if(($this->uri->segment(1) == 'partner') && ($this->uri->segment(3) == 'reschedule')){
            $subgroup_id = '';
            $appointment_id = $this->uri->segment(4);

            $student_id = $this->db->select('appointments.student_id as student_id')->from('appointments')->where('appointments.id',$appointment_id)->get()->result();
            $student_id = $student_id[0]->student_id;

            $user_subgroup = $this->db->select('user_profiles.subgroup_id as subgroup_id, user_profiles.partner_id as partner_id')->from('user_profiles')->where('user_profiles.user_id',$student_id)->get()->result();
            $partner_id = $user_subgroup[0]->partner_id;
            $user_subgroup = $user_subgroup[0]->subgroup_id;
            $coach_group = $this->get_coach_group($user_subgroup);
        }



//        $fullname = 'coach1';
//        print_r($fullname); //exit;
        if(!$partner_id){
            $partner_id = $this->auth_manager->partner_id();
        }

            $cert_studying = $this->db->select('user_profiles.cert_studying as cert_studying')->from('user_profiles')->where('user_profiles.user_id',$this->auth_manager->userid())->get()->result();
            $cert_studying = $cert_studying[0]->cert_studying;

            if($this->auth_manager->role() == 'STD'){
                $user_subgroup = $this->db->select('user_profiles.subgroup_id as subgroup_id')->from('user_profiles')->where('user_profiles.user_id',$this->auth_manager->userid())->get()->result();
                $user_subgroup = $user_subgroup[0]->subgroup_id;
                $coach_group = $this->get_coach_group($user_subgroup);
            }

        $coach_supplier = $this->get_coach_supplier($partner_id);

        $this->db->select("a.id, a.status, a.email, b.code as 'role', c.profile_picture, c.fullname, c.nickname, c.gender, c.date_of_birth, c.phone, c.skype_id, c.partner_id, c.dyned_pro_id, c.spoken_language, c.user_timezone, c.pt_score, d.teaching_credential, d.dyned_certification_level, d.year_experience, d.special_english_skill, d.higher_education, d.undergraduate, d.masters, d.phd, e.city, e.state, e.zip, e.country, e.address, h.token_for_student, h.token_for_group, i.timezone, c.coach_type_id as coach_type_id");
        $this->db->from('users a');
        $this->db->order_by("a.status", "desc");
        $this->db->join('user_roles b', 'a.role_id = b.id', 'left');
        $this->db->join('user_profiles c', 'a.id = c.user_id', 'left');
        $this->db->join('user_educations d', 'a.id = d.user_id', 'left');
        $this->db->join('user_geography e', 'a.id = e.user_id', 'left');
        $this->db->join('coach_token_costs h', 'a.id = h.coach_id', 'left');
        $this->db->join('timezones i', 'c.user_timezone = i.id', 'left');
        $this->db->order_by('c.fullname', 'asc');
        $this->db->where('a.id !=', $coach_id);
        $this->db->where('a.status', 'active');

        if($partner_id){

                if(($this->auth_manager->role() == 'STD') || ($this->auth_manager->role() == 'SPR') || ($this->auth_manager->role() == 'PRT')){
                    //$this->db->where('c.partner_id', $partner_id);
                    if($coach_group){
                        $partner_array= array($partner_id);
                        $group_array= array($user_subgroup);
                        foreach(@$coach_supplier as $cs){
                            foreach(@$coach_group as $cg){
                            if($cs->coach_supplier_id != $partner_id){
                                if($cg->subgroup_id != $user_subgroup){
                                    //$this->db->or_where('c.partner_id', $cs->coach_supplier_id);
                                    $partner_array[] = $cs->coach_supplier_id;
                                    $group_array[] = $cg->subgroup_id;
                                    }
                                }
                            }
                        }
                    $this->db->where_in('c.partner_id', $partner_array);
                    $this->db->where_in('c.subgroup_id', $group_array);
                    }else{
                        $partner_array= array($partner_id);
                        foreach(@$coach_supplier as $cs){
                            if($cs->coach_supplier_id != $partner_id){
                                    //$this->db->or_where('c.partner_id', $cs->coach_supplier_id);
                                    $partner_array[] = $cs->coach_supplier_id;
                            }
                        }
                        $this->db->where_in('c.partner_id', $partner_array);
                    }
                }else{
                    $this->db->where('c.partner_id', $partner_id);
                }
        }


        $this->db->where('b.id', 2);


        if($cert_studying){

           if(($cert_studying == 'A1') || ($cert_studying == 'A2')){
                $this->db->where('c.pt_score >=','2.5');
            } else if (($cert_studying == 'B1') || ($cert_studying == 'B2')){
                $this->db->where('c.pt_score >=','3');
            } else if (($cert_studying == 'C1') || ($cert_studying == 'C2')){
                $this->db->where('c.pt_score >=','3.5');
            }
            else if($cert_studying == 0){
                $this->db->where('c.pt_score >','999999');
            }
        }

        ///////////////////////////////////////////////
        // Pagination
        ///////////////////////////////////////////////
        // if($limit && $offset && $offset=="first_page"){
        //     $this->db->limit($limit);
        //     $this->db->offset(0);
        // }elseif($limit && $offset){
        //     $this->db->limit($limit);
        //     $this->db->offset($offset);
        // }
        ///////////////////////////////////////////////
        return $this->db->get()->result();
    }

    public function get_coach_identity_rescedule($partner_id = '', $cert_studying = ''){

        if(($this->uri->segment(1) == 'partner') && ($this->uri->segment(3) == 'reschedule')){
            $subgroup_id = '';
            $appointment_id = $this->uri->segment(4);

            $student_id = $this->db->select('appointments.student_id as student_id')->from('appointments')->where('appointments.id',$appointment_id)->get()->result();
            $student_id = $student_id[0]->student_id;

            $user_subgroup = $this->db->select('user_profiles.subgroup_id as subgroup_id')->from('user_profiles')->where('user_profiles.user_id',$student_id)->get()->result();
            $user_subgroup = $user_subgroup[0]->subgroup_id;
            $coach_group = $this->get_coach_group($user_subgroup);
        }



//        $fullname = 'coach1';
//        print_r($fullname); //exit;
        if(!$partner_id){
            $partner_id = $this->auth_manager->partner_id();
        }

            $cert_studying = $this->db->select('user_profiles.cert_studying as cert_studying')->from('user_profiles')->where('user_profiles.user_id',$this->auth_manager->userid())->get()->result();
            $cert_studying = $cert_studying[0]->cert_studying;

            if($this->auth_manager->role() == 'STD'){
                $user_subgroup = $this->db->select('user_profiles.subgroup_id as subgroup_id')->from('user_profiles')->where('user_profiles.user_id',$this->auth_manager->userid())->get()->result();
                $user_subgroup = $user_subgroup[0]->subgroup_id;
                $coach_group = $this->get_coach_group($user_subgroup);
            }

        $coach_supplier = $this->get_coach_supplier($partner_id);

        $this->db->select("a.id, a.status, a.email, b.code as 'role', c.profile_picture, c.fullname, c.nickname, c.gender, c.date_of_birth, c.phone, c.skype_id, c.partner_id, c.dyned_pro_id, c.spoken_language, c.user_timezone, c.pt_score, d.teaching_credential, d.dyned_certification_level, d.year_experience, d.special_english_skill, d.higher_education, d.undergraduate, d.masters, d.phd, e.city, e.state, e.zip, e.country, e.address, h.token_for_student, h.token_for_group, i.timezone, c.coach_type_id as coach_type_id");
        $this->db->from('users a');
        $this->db->order_by("a.status", "desc");
        $this->db->join('user_roles b', 'a.role_id = b.id');
        $this->db->join('user_profiles c', 'a.id = c.user_id');
        $this->db->join('user_educations d', 'a.id = d.user_id');
        $this->db->join('user_geography e', 'a.id = e.user_id', 'full');
        $this->db->join('coach_token_costs h', 'a.id = h.coach_id');
        $this->db->join('timezones i', 'c.user_timezone = i.id');
        $this->db->order_by('c.fullname', 'asc');
        $this->db->where('a.status', 'active');

        if($partner_id){

                if(($this->auth_manager->role() == 'STD') || ($this->auth_manager->role() == 'SPR') || ($this->auth_manager->role() == 'PRT')){
                    //$this->db->where('c.partner_id', $partner_id);
                    if($coach_group){
                        $partner_array= array($partner_id);
                        $group_array= array($user_subgroup);
                        foreach(@$coach_supplier as $cs){
                            foreach(@$coach_group as $cg){
                            if($cs->coach_supplier_id != $partner_id){
                                if($cg->subgroup_id != $user_subgroup){
                                    //$this->db->or_where('c.partner_id', $cs->coach_supplier_id);
                                    $partner_array[] = $cs->coach_supplier_id;
                                    $group_array[] = $cg->subgroup_id;
                                    }
                                }
                            }
                        }
                    $this->db->where_in('c.partner_id', $partner_array);
                    $this->db->where_in('c.subgroup_id', $group_array);
                    }else{
                        $partner_array= array($partner_id);
                        foreach(@$coach_supplier as $cs){
                            if($cs->coach_supplier_id != $partner_id){
                                    //$this->db->or_where('c.partner_id', $cs->coach_supplier_id);
                                    $partner_array[] = $cs->coach_supplier_id;
                            }
                        }
                        $this->db->where_in('c.partner_id', $partner_array);
                    }
                }else{
                    $this->db->where('c.partner_id', $partner_id);
                }
        }

        $this->db->where('b.id', 2);


        if($cert_studying){

           if(($cert_studying == 'A1') || ($cert_studying == 'A2')){
                $this->db->where('c.pt_score >=','2.5');
            } else if (($cert_studying == 'B1') || ($cert_studying == 'B2')){
                $this->db->where('c.pt_score >=','3');
            } else if (($cert_studying == 'C1') || ($cert_studying == 'C2')){
                $this->db->where('c.pt_score >=','3.5');
            }
            else if($cert_studying == 0){
                $this->db->where('c.pt_score >','999999');
            }
        }

        ///////////////////////////////////////////////
        // Pagination
        ///////////////////////////////////////////////
        // if($limit && $offset && $offset=="first_page"){
        //     $this->db->limit($limit);
        //     $this->db->offset(0);
        // }elseif($limit && $offset){
        //     $this->db->limit($limit);
        //     $this->db->offset($offset);
        // }
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

        private function get_coach_group($student_group_id){
            $this->db->select("cgr.subgroup_id");
            $this->db->from('coach_group_relations cgr');
            $this->db->join('student_group_relations sgr', 'cgr.class_matchmaking_id = sgr.class_matchmaking_id');
            $this->db->where('sgr.subgroup_id', $student_group_id);

            return $this->db->get()->result();
        }

        private function get_student_group($student_group_id){
            $this->db->select("sgr.subgroup_id");
            $this->db->from('student_group_relations sgr');
            $this->db->where('sgr.subgroup_id', $student_group_id);

            return $this->db->get()->result();
        }

        private function get_partner_group($group_id){
            $this->db->select("s.partner_id");
            $this->db->from('subgroup s');
            $this->db->join('partners p', 's.partner_id = p.id');
            $this->db->where('s.id', $group_id);

            return $this->db->get()->result();
        }

    public function get_partner_identity($id = '', $fullname = '', $partner_id = '', $creator_id = '', $role_id='')
    {

        $this->db->select("a.id, a.status, a.email, b.code as 'role', c.profile_picture, c.fullname, c.nickname, c.gender, c.date_of_birth, c.phone, c.skype_id, c.partner_id, c.dyned_pro_id, c.spoken_language, c.user_timezone");
        $this->db->from('users a');
        $this->db->order_by("a.status", "desc");
        $this->db->join('user_roles b', 'a.role_id = b.id');
        $this->db->join('user_profiles c', 'a.id = c.user_id');
        $this->db->order_by('c.fullname', 'asc');
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
        $this->db->select("up.user_timezone, t.timezone_index, t.code, t.timezone, t.minutes");
        $this->db->from('user_profiles up');
        $this->db->join('timezones t', 'up.user_timezone = t.id');
        $this->db->where('up.user_id', $user_id);

        return $this->db->get()->result();
    }

    public function new_get_gmt($user_id){
        $this->db->select("minutes_val as minutes, gmt_val as gmt");
        $this->db->from('user_timezones');
        $this->db->where('user_id', $user_id);

        return $this->db->get()->result();
    }

    // public function get_region_admin_identity($id = '', $fullname = '', $partner_id = '', $creator_id = '', $role_id='')
    // {
    //     $this->db->select("a.id, a.status, a.email, b.code as 'role', c.profile_picture, c.fullname, c.nickname, c.gender, c.date_of_birth, c.phone, c.skype_id, c.partner_id, c.dyned_pro_id, c.spoken_language, c.user_timezone, r.name as region_name");
    //     $this->db->from('users a');
    //     $this->db->order_by("a.status", "desc");
    //     $this->db->join('user_roles b', 'a.role_id = b.id');
    //     $this->db->join('user_profiles c', 'a.id = c.user_id');
    //     $this->db->join('regions r', 'c.region_id = r.id');
    //     $this->db->order_by('c.fullname', 'asc');
    //     $this->db->where('a.status', 'active');
    //     $this->db->where('b.id', 4);
    //     if($id)
    //         $this->db->where('a.id', $id);
    //     return $this->db->get()->result();

    // }

    public function update_profile($partner_id,$profile_picture)
     {
         $this->db->where('user_id',$partner_id);
         $this->db->update('user_profiles',$profile_picture);
     }

    public function get_region_admin_identity($id = '', $fullname = '', $partner_id = '', $creator_id = '', $role_id='', $status='', $search_region='', $per_page='', $offset='')
    {

        $this->db->select("a.id, a.status, a.email, b.code as 'role', c.profile_picture, c.fullname, c.nickname, c.gender, c.date_of_birth, c.phone, c.skype_id, c.partner_id, c.dyned_pro_id, c.spoken_language, c.user_timezone, c.region_id");
        $this->db->from('users a');
        $this->db->order_by("a.status", "desc");
        $this->db->join('user_roles b', 'a.role_id = b.id');
        $this->db->join('user_profiles c', 'a.id = c.user_id');
        $this->db->order_by('c.region_id', 'asc');
        $this->db->where('b.id', 4);
        if($status)
            $this->db->where('a.status', $status);

        if($per_page)
            $this->db->limit($per_page);
        if($offset)
            $this->db->offset($offset);
        if($search_region)
            $this->db->like('c.region_id', $search_region);
        if($id)
            $this->db->where('a.id', $id);
        return $this->db->get()->result();
    }

    public function get_region(){
        return $this->db->select('id, name')
                 ->from('regions')
                 ->order_by('name','asc')
                 ->get()->result();
    }

    function get_admin_partner($id = '',$page,$offset){
        return $this->db->select('*')
                        ->from('partners')
                        ->where('admin_regional_id',$id)
                        ->order_by('id', 'asc')
                        ->limit($page)
                        ->offset($offset)
                        ->get()->result();

    }

    public function group_region($id = ''){
        $this->db->select('u.user_id as user_id, GROUP_CONCAT(r.name) as region_name ');
        $this->db->from('admin_regional_relation u');
        $this->db->join('regions r', 'u.region_id = r.id');
        $this->db->group_by('u.user_id');
        if($id)
            $this->db->where('u.user_id',$id);
        return $this->db->get()->result();
    }


    // coach partner
    public function get_coach_partner_identity($id = '', $fullname = '', $partner_id = '', $creator_id = '', $role_id=''){
        $this->db->select("a.id, a.status, a.email, b.code as 'role', c.profile_picture, c.fullname, c.nickname, c.gender, c.date_of_birth, c.phone, c.skype_id, c.partner_id, c.dyned_pro_id, c.spoken_language, c.user_timezone, p.name as name_partner, p.admin_regional_id as admin_regional_id");
        $this->db->from('users a');
        $this->db->order_by("a.status", "desc");
        $this->db->join('user_roles b', 'a.role_id = b.id');
        $this->db->join('user_profiles c', 'a.id = c.user_id');
        $this->db->join('partners p', 'c.partner_id = p.id');
        $this->db->join('admin_regional_relation arp', 'p.admin_regional_id = arp.user_id');
        $this->db->order_by('p.id', 'asc');
        $this->db->where('a.status', 'active');
        $this->db->where('b.id', 3);
        $this->db->group_by('a.id');
        if($id)
            $this->db->where('a.id', $id);
        return $this->db->get()->result();
    }

    public function get_subgroup_identity($id = '', $type = '', $status = '', $partner_id = '', $search ='', $limit='', $offset=''){
      if(!$partner_id){
                $partner_id = $this->auth_manager->partner_id();
            }


        $this->db->select("a.id, a.name, a.type");
        $this->db->select("a.id, a.name, a.type, b.user_id, a.partner_id");
        $this->db->from('subgroup a');
        $this->db->order_by("a.name", "asc");
        $this->db->join('user_profiles b', 'a.partner_id = b.partner_id');
        $this->db->join('users c', 'c.id = b.user_id');
        $this->db->where('a.type', $type);
        $this->db->where('a.status', $status);
        $this->db->where('b.partner_id', $partner_id);

        $this->db->like('a.name',$search,'both');
        $this->db->group_by('a.id', $partner_id);

        if($id)
            $this->db->where('a.id', $id);


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
/* End of file student_identity_model.php */
/* Location: ./application/models/student_identity_model.php */
