<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Coach extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('identity_model');
        $this->load->model('user_geography_model');
        $this->load->model('region_model');
        $this->load->model('subgroup_model');
        $this->load->model('schedule_model');
        $this->load->model('offwork_model');
        $this->load->model('coach_token_cost_model');
        $this->load->model('creator_member_model');
        $this->load->model('user_education_model');
        $this->load->model('timezone_model');

        // for messaging action and timing
        $this->load->library('queue');
        $this->load->library('common_function');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'RAM') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

        function generateRandomString($length = 5) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }


    public function index() {
        $this->template->title = 'Reporting';
        $id_user = $this->auth->userid();

$pull_reg_id = $this->db->select('region_id')
             ->from('user_profiles')
             ->where('user_id', $id_user)
             ->get()->result();

$reg_id = $pull_reg_id[0]->region_id;

$pull_m_id = $this->db->select('u.id')
              ->from('users u')
              ->join('user_profiles us', 'us.user_id = u.id' )
              ->where('u.role_id', '4')
              ->where('us.region_id', $reg_id)
              ->get()->result();

$id = $pull_m_id[0]->id;

        $list_sp = $this->db->distinct()
                 ->select('pr.id, pr.name')
                 ->from('partners pr')
                 ->join('subgroup sg', 'sg.partner_id = pr.id')
                 ->where('pr.admin_regional_id',$id)
                 ->where('sg.type','coach')
                 ->get()->result();

        $i = 0;
        $len = count($list_sp);
        $partnerlistid = '';
        foreach($list_sp as $ls){
            $id = $ls->id;

            if ($i == 0) {
                $partnerlistid .= $id.', ';
            } else if ($i == $len - 1) {
                $partnerlistid .= $id;
            }
            $i++;
        }

        $list_sg = $this->db->select('*')
                         ->from('subgroup')
                         ->where('partner_id',$partnerlistid)
                         ->where('type','coach')
                         ->get()->result();

        $vars = array(
            'list_sp' => $list_sp,
            'list_sg' => $list_sg,
            'partnerlistid' => $partnerlistid
        );
        // echo "<pre>";print_r($vars);exit();
        $this->template->content->view('default/contents/admin_m/reporting/coach/index', $vars);
        $this->template->publish();
    }

    public function coachreport() {
    $id_user = $this->auth->userid();

$pull_reg_id = $this->db->select('region_id')
             ->from('user_profiles')
             ->where('user_id', $id_user)
             ->get()->result();

$reg_id = $pull_reg_id[0]->region_id;

$pull_m_id = $this->db->select('u.id')
              ->from('users u')
              ->join('user_profiles us', 'us.user_id = u.id' )
              ->where('u.role_id', '4')
              ->where('us.region_id', $reg_id)
              ->get()->result();

$id = $pull_m_id[0]->id;
    $list_sp = $this->db->distinct()
             ->select('pr.id, pr.name')
             ->from('partners pr')
             ->join('subgroup sg', 'sg.partner_id = pr.id')
             ->where('pr.admin_regional_id',$id)
             ->where('sg.type','coach')
             ->get()->result();

    $i = 0;
    $len = count($list_sp);
    $partnerlistid = '';
    foreach($list_sp as $ls){
        $id = $ls->id;

        if ($i == 0) {
            $partnerlistid .= $id.', ';
        } else if ($i == $len - 1) {
            $partnerlistid .= $id;
        }
        $i++;
    }

    $list_sg = $this->db->select('*')
                     ->from('subgroup')
                     ->where('partner_id',$partnerlistid)
                     ->where('type','coach')
                     ->get()->result();

    $report       = $_POST['submit'];
    $defaultlist  = @$_POST['defaultlist'];
    $partner_id   = $this->auth_manager->partner_id();
    $subgrouplist = $_POST["subgrouplist"];
    $date_from    = $_POST["date_from"];
    $date_to      = $_POST["date_to"];

    $date_from1   = date('d-M-y', strtotime($_POST["date_from"]));
    $date_to1     = date('d-M-y', strtotime($_POST["date_to"]));

    if(!$subgrouplist){
        $subgrouplist = $defaultlist;
    }
    // echo "<pre>";print_r($report);exit();
    $sglist = explode(",", $subgrouplist);

        if($report == "Coach Summary"){
            $this->template->title = 'Coach Summary';

            $cch_sum = $this->db->select('*')
                     ->from('user_profiles')
                     ->join('users','users.id = user_profiles.user_id')
                     ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                     ->where_in('subgroup.id',$sglist)
                     ->get()->result();


            $selected = $this->db->select('*')
                         ->from('subgroup')
                         ->where_in('id',$sglist)
                         ->where('type','coach')
                         ->get()->result();

            $noselect = $this->db->select('*')
                         ->from('subgroup')
                         ->where('partner_id',$partner_id)
                         ->where_not_in('id',$sglist)
                         ->where('type','coach')
                         ->get()->result();

         //    $setting = $this->db->select('standard_coach_cost,elite_coach_cost')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();
         //    $standard_coach_cost = $setting[0]->standard_coach_cost;
        	// $elite_coach_cost = $setting[0]->elite_coach_cost;

            $vars = array(
                'cch_sum'      => $cch_sum,
                'partner_id'   => $partner_id,
                'subgrouplist' => $subgrouplist,
                'date_from'    => $date_from,
                'date_to'      => $date_to,
                'date_from1'    => $date_from1,
                'date_to1'      => $date_to1,
                'sglist'      => $sglist,
                'selected' => $selected,
                'noselect' => $noselect,
                'list_sp' => $list_sp,
                'list_sg' => $list_sg,
                'partnerlistid' => $partnerlistid
                // 'standard_coach_cost' => $standard_coach_cost,
                // 'elite_coach_cost' => $elite_coach_cost
            );

            // echo "<pre>";print_r($vars);exit();

            $this->template->content->view('default/contents/admin_m/reporting/coach/coachsummary', $vars);
            $this->template->publish();
        }else if($report == "Rating Summary"){
            $this->template->title = 'Rating Summary';

            $id    = $this->auth_manager->userid();
            $get_tz  = $this->db->select('minutes_val')
                     ->from('user_timezones')
                     ->where('user_id',$id)
                     ->get()->result();
            $spr_tz = $get_tz[0]->minutes_val;

            $listcoach = $this->db->select('*')
	                     ->from('user_profiles')
	                     ->join('users','users.id = user_profiles.user_id')
	                     ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
	                     ->where_in('subgroup.id',$sglist)
	                     ->get()->result();

            $selected = $this->db->select('*')
                         ->from('subgroup')
                         ->where_in('id',$sglist)
                         ->where('type','coach')
                         ->get()->result();

            $noselect = $this->db->select('*')
                         ->from('subgroup')
                         ->where('partner_id',$partner_id)
                         ->where_not_in('id',$sglist)
                         ->where('type','coach')
                         ->get()->result();

            $vars = array(
                'listcoach' => $listcoach,
                'spr_tz' => $spr_tz,
                'date_from' => @$date_from,
                'date_to' => @$date_to,
                'date_from1' => @$date_from1,
                'date_to1' => @$date_to1,
                'partner_id'   => $partner_id,
                'subgrouplist' => $subgrouplist,
                'list_sp' => $list_sp,
                'list_sg' => $list_sg,
                'partnerlistid' => $partnerlistid
            );

            // echo "<pre>";
            // print_r($vars);
            // exit();

            $this->template->content->view('default/contents/admin_m/reporting/coach/ratingsummary', $vars);
            $this->template->publish();
        }else if($report == "Session Report"){
            $this->template->title = 'Session Report';

            $id    = $this->auth_manager->userid();
            $get_tz  = $this->db->select('minutes_val')
                     ->from('user_timezones')
                     ->where('user_id',$id)
                     ->get()->result();
            $spr_tz = $get_tz[0]->minutes_val;

            if(!@$date_from){
            $ses_rpt = $this->db->select('*')
                     ->from('user_profiles')
                     ->join('users','users.id = user_profiles.user_id')
                     ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                     ->join('appointments','appointments.coach_id = user_profiles.user_id')
                     ->order_by('date', 'DESC')
                     ->where_in('subgroup.id',$sglist)
                     ->get()->result();
            }else if(@$date_from && !@$date_to){
            $ses_rpt = $this->db->select('*')
                     ->from('user_profiles')
                     ->join('users','users.id = user_profiles.user_id')
                     ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                     ->join('appointments','appointments.coach_id = user_profiles.user_id')
                     ->order_by('date', 'DESC')
                     ->where('date >=', $date_from)
                     ->where_in('subgroup.id',$sglist)
                     ->get()->result();
            }else if(@$date_from && @$date_to){
            $ses_rpt = $this->db->select('*')
                     ->from('user_profiles')
                     ->join('users','users.id = user_profiles.user_id')
                     ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                     ->join('appointments','appointments.coach_id = user_profiles.user_id')
                     ->order_by('date', 'DESC')
                     ->where('date >=', $date_from)
                     ->where('date <=', $date_to)
                     ->where_in('subgroup.id',$sglist)
                     ->get()->result();
            }

            $selected = $this->db->select('*')
                         ->from('subgroup')
                         ->where_in('id',$sglist)
                         ->where('type','coach')
                         ->get()->result();

            $noselect = $this->db->select('*')
                         ->from('subgroup')
                         ->where('partner_id',$partner_id)
                         ->where_not_in('id',$sglist)
                         ->where('type','coach')
                         ->get()->result();

            $date_from1   = date('d-M-y', strtotime($_POST["date_from"]));
			$date_to1     = date('d-M-y', strtotime($_POST["date_to"]));

            $vars = array(
                'ses_rpt' => $ses_rpt,
                'spr_tz' => $spr_tz,
                'date_from' => @$date_from,
                'date_to' => @$date_to,
                'date_from1' => @$date_from1,
                'date_to1' => @$date_to1,
                'partner_id'   => $partner_id,
                'subgrouplist' => $subgrouplist,
                'list_sp' => $list_sp,
                'list_sg' => $list_sg,
                'partnerlistid' => $partnerlistid
            );

            // echo "<pre>";print_r($vars);exit();

            $this->template->content->view('default/contents/admin_m/reporting/coach/sessionreport', $vars);
            $this->template->publish();
        }
    }

    public function detail($user_id=""){
    	$getcchname = $this->db->select('fullname')
                    ->from('user_profiles')
                    ->where('user_id',$user_id)
                    ->get()->result();

        $cch_name = $getcchname[0]->fullname;

        $pullses = $this->db->select('ap.id, ap.date, up.fullname, ap.dcrea, ap.start_time, ap.end_time')
                 ->from('appointments ap')
                 ->join('user_profiles up', 'up.user_id = ap.student_id')
                 ->where('ap.coach_id',$user_id)
                 ->where('ap.status','completed')
                 ->order_by('ap.date', 'DESC')
                 ->get()->result();

        $partner_id   = $this->auth_manager->partner_id();
        $setting = $this->db->select('standard_coach_cost,elite_coach_cost')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();
        $standard_coach_cost = $setting[0]->standard_coach_cost;
    	$elite_coach_cost = $setting[0]->elite_coach_cost;

    	$pullcoachprof = $this->db->select('coach_type_id')
                        ->from('user_profiles')
                        ->where('user_id',$user_id)
                        ->get()->result();

        $coachtype = $pullcoachprof[0]->coach_type_id;

        if($coachtype == 1){
            $tokencost = $standard_coach_cost;
        }else if($coachtype == 2){
            $tokencost = $elite_coach_cost;
        }

        $id    = $this->auth_manager->userid();
        $get_tz  = $this->db->select('minutes_val')
                 ->from('user_timezones')
                 ->where('user_id',$id)
                 ->get()->result();
        $spr_tz = $get_tz[0]->minutes_val;

        $vars = array(
        	'pullses' => $pullses,
        	'spr_tz' => $spr_tz,
        	'cch_name' => $cch_name,
        	'tokencost' => $tokencost
        );

        // echo "<pre>";print_r($vars);exit();

    	$this->template->title = 'Session Detail of '.$cch_name;
    	$this->template->content->view('default/contents/partner/reporting/detail', $vars);
        $this->template->publish();
    }

    public function export_summary(){
    	$report       = $_POST['submit'];
	    $partner_id   = $this->auth_manager->partner_id();
	    $subgrouplist = $_POST["subgrouplist"];
	    $date_from    = $_POST["date_from"];
	    $date_to      = $_POST["date_to"];
	    $date_from1   = date('d-M-y', strtotime($_POST["date_from"]));
		  $date_to1     = date('d-M-y', strtotime($_POST["date_to"]));
	    $sglist = explode(",", $subgrouplist);

	    $this->template->title = 'Coach Summary';

        // if(!@$date_from){
        $cch_sum = $this->db->select('*')
                 ->from('user_profiles')
                 ->join('users','users.id = user_profiles.user_id')
                 // ->join('token_histories_coach','token_histories_coach.coach_id = user_profiles.user_id')
                 ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                 // ->order_by('date', 'DESC')
                 ->where_in('subgroup.id',$sglist)
                 ->get()->result();

        $selected = $this->db->select('*')
                     ->from('subgroup')
                     ->where_in('id',$sglist)
                     ->where('type','coach')
                     ->get()->result();

        $noselect = $this->db->select('*')
                     ->from('subgroup')
                     ->where('partner_id',$partner_id)
                     ->where_not_in('id',$sglist)
                     ->where('type','coach')
                     ->get()->result();

        $setting = $this->db->select('standard_coach_cost,elite_coach_cost')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();
        $standard_coach_cost = @$setting[0]->standard_coach_cost;
    	  $elite_coach_cost = @$setting[0]->elite_coach_cost;

        $vars = array(
            'cch_sum'      => $cch_sum,
            'partner_id'   => $partner_id,
            'subgrouplist' => $subgrouplist,
            'date_from'    => $date_from,
            'date_to'      => $date_to,
            'date_from1'   => $date_from1,
            'date_to1'     => $date_to1,
            'sglist'       => $sglist,
            'selected' => $selected,
            'noselect' => $noselect,
            'standard_coach_cost' => $standard_coach_cost,
            'elite_coach_cost' => $elite_coach_cost
        );

        // echo "<pre>";print_r($vars);exit();

        $this->load->view('default/contents/admin_m/reporting/coach/coachsummaryexp', $vars);
    }
    public function export_session(){
    	$report       = $_POST['submit'];
	    $partner_id   = $this->auth_manager->partner_id();
	    $subgrouplist = $_POST["subgrouplist"];
	    $date_from    = $_POST["date_from"];
	    $date_to      = $_POST["date_to"];
	    $date_from1   = date('d-M-y', strtotime($_POST["date_from"]));
		  $date_to1     = date('d-M-y', strtotime($_POST["date_to"]));
	    $sglist = explode(",", $subgrouplist);

	    $this->template->title = 'Session Report';

        $id    = $this->auth_manager->userid();
        $get_tz  = $this->db->select('minutes_val')
                 ->from('user_timezones')
                 ->where('user_id',$id)
                 ->get()->result();
        $spr_tz = $get_tz[0]->minutes_val;

        if(!@$date_from){
        $ses_rpt = $this->db->select('*')
                 ->from('user_profiles')
                 ->join('users','users.id = user_profiles.user_id')
                 ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                 ->join('appointments','appointments.coach_id = user_profiles.user_id')
                 ->order_by('date', 'DESC')
                 ->where_in('subgroup.id',$sglist)
                 ->get()->result();
        }else if(@$date_from && !@$date_to){
        $ses_rpt = $this->db->select('*')
                 ->from('user_profiles')
                 ->join('users','users.id = user_profiles.user_id')
                 ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                 ->join('appointments','appointments.coach_id = user_profiles.user_id')
                 ->order_by('date', 'DESC')
                 ->where('date >=', $date_from)
                 ->where_in('subgroup.id',$sglist)
                 ->get()->result();
        }else if(@$date_from && @$date_to){
        $ses_rpt = $this->db->select('*')
                 ->from('user_profiles')
                 ->join('users','users.id = user_profiles.user_id')
                 ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                 ->join('appointments','appointments.coach_id = user_profiles.user_id')
                 ->order_by('date', 'DESC')
                 ->where('date >=', $date_from)
                 ->where('date <=', $date_to)
                 ->where_in('subgroup.id',$sglist)
                 ->get()->result();
        }

        $selected = $this->db->select('*')
                     ->from('subgroup')
                     ->where_in('id',$sglist)
                     ->where('type','coach')
                     ->get()->result();

        $noselect = $this->db->select('*')
                     ->from('subgroup')
                     ->where('partner_id',$partner_id)
                     ->where_not_in('id',$sglist)
                     ->where('type','coach')
                     ->get()->result();

        $date_from1   = date('d-M-y', strtotime($_POST["date_from"]));
		$date_to1     = date('d-M-y', strtotime($_POST["date_to"]));

        $vars = array(
            'ses_rpt' => $ses_rpt,
            'spr_tz' => $spr_tz,
            'date_from' => @$date_from,
            'date_to' => @$date_to,
            'date_from1' => @$date_from1,
            'date_to1' => @$date_to1,
            'partner_id'   => $partner_id,
            'subgrouplist' => $subgrouplist,
            'selected' => $selected,
            'noselect' => $noselect
        );

	    $this->load->view('default/contents/admin_m/reporting/coach/sessionreportexp', $vars);
	}

	public function export_rating(){
		$report       = $_POST['submit'];
    $partner_id   = $this->auth_manager->partner_id();
    $subgrouplist = $_POST["subgrouplist"];
    $date_from    = $_POST["date_from"];
    $date_to      = $_POST["date_to"];
    $date_from1   = date('d-M-y', strtotime($_POST["date_from"]));
	  $date_to1     = date('d-M-y', strtotime($_POST["date_to"]));
	  $sglist = explode(",", $subgrouplist);

		$this->template->title = 'Rating Summary';

        $id    = $this->auth_manager->userid();
        $get_tz  = $this->db->select('minutes_val')
                 ->from('user_timezones')
                 ->where('user_id',$id)
                 ->get()->result();
        $spr_tz = $get_tz[0]->minutes_val;

        $listcoach = $this->db->select('*')
                     ->from('user_profiles')
                     ->join('users','users.id = user_profiles.user_id')
                     ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                     ->where_in('subgroup.id',$sglist)
                     ->get()->result();

        $selected = $this->db->select('*')
                     ->from('subgroup')
                     ->where_in('id',$sglist)
                     ->where('type','coach')
                     ->get()->result();

        $noselect = $this->db->select('*')
                     ->from('subgroup')
                     ->where('partner_id',$partner_id)
                     ->where_not_in('id',$sglist)
                     ->where('type','coach')
                     ->get()->result();

        $vars = array(
            'listcoach' => $listcoach,
            'spr_tz' => $spr_tz,
            'date_from' => @$date_from,
            'date_to' => @$date_to,
            'date_from1' => @$date_from1,
            'date_to1' => @$date_to1,
            'partner_id'   => $partner_id,
            'subgrouplist' => $subgrouplist,
            'selected' => $selected,
            'noselect' => $noselect
        );

        $this->load->view('default/contents/admin_m/reporting/coach/ratingsummaryexp', $vars);
	}

}
