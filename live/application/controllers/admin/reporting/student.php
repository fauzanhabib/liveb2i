<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // load models for student
        $this->load->model('token_request_model');
        $this->load->model('user_token_model');
        $this->load->model('identity_model');
        $this->load->model('user_model');
        $this->load->model('token_histories_model');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'ADM') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = 'Reporting | Student';
        // $partner_id = $this->auth_manager->partner_id();
        $id = $this->auth_manager->userid();

        $list_sp = $this->db->distinct()
                 ->select('pr.id, pr.name')
                 ->from('partners pr')
                 ->join('subgroup sg', 'sg.partner_id = pr.id')
                 ->where('pr.admin_regional_id',$id)
                 ->where('sg.type','student')
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

        $list_gr = $this->db->select('*')
                 ->from('subgroup')
                 ->where('partner_id',$partnerlistid)
                 ->where('type','student')
                 ->get()->result();

        $vars = array(
            'list_sg' => $list_gr,
            'list_sp' => $list_sp,
            'partnerlistid' => $partnerlistid
        );

        // echo "<pre>";print_r($list_gr);exit();

        $this->template->content->view('default/contents/admin/reporting/student/index', $vars);
        $this->template->publish();
    }

    public function studentreport() {
    $id = $this->auth_manager->userid();

    $list_sp = $this->db->distinct()
             ->select('pr.id, pr.name')
             ->from('partners pr')
             ->join('subgroup sg', 'sg.partner_id = pr.id')
             ->where('pr.admin_regional_id',$id)
             ->where('sg.type','student')
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

    $list_gr = $this->db->select('*')
             ->from('subgroup')
             ->where_in('partner_id',$partnerlistid)
             ->where('type','student')
             ->get()->result();

    $report       = $_POST['submit'];
    $defaultlist  = @$_POST['defaultlist'];
    $partner_id   = $this->auth_manager->partner_id();
    $subgrouplist = $_POST["subgrouplist"];
    $date_from    = $_POST["date_from"];
    $date_to      = $_POST["date_to"];

    if(!$subgrouplist){
        $subgrouplist = $defaultlist;
    }

    $sglist = explode(",", $subgrouplist);

        if($report == "Student Report"){
            $this->template->title = 'Student Report';
            
            $stu_rpt = $this->db->select('*')
                     ->from('user_profiles')
                     ->join('user_tokens','user_tokens.user_id = user_profiles.user_id')
                     ->join('users','users.id = user_profiles.user_id')
                     ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                     ->where_in('subgroup_id',$sglist)
                     ->where('users.status','active')
                     ->get()->result();

            $date_from1   = date('d-M-y', strtotime($_POST["date_from"]));
			$date_to1     = date('d-M-y', strtotime($_POST["date_to"])); 

            $vars = array(
                'stu_rpt'      => $stu_rpt,
                'partner_id'   => $partner_id,
                'subgrouplist' => $subgrouplist,
                'date_from1'    => $date_from1,
                'date_from'    => $date_from,
                'date_to1'      => $date_to1,
                'date_to'      => $date_to,
                'sglist'      => $sglist,
                'list_sg' => $list_gr,
                'list_sp' => $list_sp
            );

            // echo "<pre>";print_r($vars);exit();

            $this->template->content->view('default/contents/admin/reporting/student/studentreport', $vars);
            $this->template->publish();
        }else{
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
                     ->join('appointments','appointments.student_id = user_profiles.user_id')
                     ->order_by('date', 'DESC')
                     ->where_in('subgroup.id',$sglist)
                     ->where('appointments.status','completed')
                     ->get()->result();
            }else if(@$date_from && !@$date_to){
            $ses_rpt = $this->db->select('*')
                     ->from('user_profiles')
                     ->join('users','users.id = user_profiles.user_id')
                     ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                     ->join('appointments','appointments.student_id = user_profiles.user_id')
                     ->order_by('date', 'DESC')
                     ->where('date >=', $date_from)
                     ->where_in('subgroup.id',$sglist)
                     ->where('appointments.status','completed')
                     ->get()->result();
            }else if(@$date_from && @$date_to){
            $ses_rpt = $this->db->select('*')
                     ->from('user_profiles')
                     ->join('users','users.id = user_profiles.user_id')
                     ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                     ->join('appointments','appointments.student_id = user_profiles.user_id')
                     ->order_by('date', 'DESC')
                     ->where('date >=', $date_from)
                     ->where('date <=', $date_to)
                     ->where_in('subgroup.id',$sglist)
                     ->where('appointments.status','completed')
                     ->get()->result();
            }

            $selected = $this->db->select('*')
                         ->from('subgroup')
                         ->where_in('id',$sglist)
                         ->where('type','student')
                         ->get()->result();

            $noselect = $this->db->select('*')
                         ->from('subgroup')
                         ->where('partner_id',$partner_id)
                         ->where_not_in('id',$sglist)
                         ->where('type','student')
                         ->get()->result();

         	$date_from1   = date('d-M-y', strtotime($_POST["date_from"]));
			$date_to1     = date('d-M-y', strtotime($_POST["date_to"])); 

            $vars = array(
                'ses_rpt' => $ses_rpt,
                'spr_tz' => $spr_tz,
                'date_from' => @$date_from,
                'date_from1' => @$date_from1,
                'date_to' => @$date_to,
                'date_to1' => @$date_to1,
                'partner_id'   => $partner_id,
                'subgrouplist' => $subgrouplist,
                'selected' => $selected,
                'noselect' => $noselect,
                'list_sg' => $list_gr,
                'list_sp' => $list_sp
            );

            // echo "<pre>";print_r($vars);exit();

            $this->template->content->view('default/contents/admin/reporting/student/sessionreport', $vars);
            $this->template->publish();
        }
    }

    public function export_rpt(){
        $report = $_POST['submit'];
        $partner_id   = $this->auth_manager->partner_id();
        $subgrouplist = $_POST["subgrouplist"];
        $sglist       = explode(",", $subgrouplist);
        $date_from    = $_POST["date_from"];
        $date_to      = $_POST["date_to"];

        $this->template->title = 'Export Student Report';
            
        $stu_rpt = $this->db->select('*')
                 ->from('user_profiles')
                 ->join('user_tokens','user_tokens.user_id = user_profiles.user_id')
                 ->join('users','users.id = user_profiles.user_id')
                 ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                 ->where_in('subgroup_id',$sglist)
                 ->where('users.status','active')
                 ->get()->result();

        $vars = array(
            'stu_rpt'      => $stu_rpt,
            'partner_id'   => $partner_id,
            'subgrouplist' => $subgrouplist,
            'date_from'    => $date_from,
            'date_to'      => $date_to
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();

        $this->load->view('default/contents/admin/reporting/student/export_rpt', $vars);
    }

    public function export_ses(){
        $report = $_POST['submit'];
        $partner_id   = $this->auth_manager->partner_id();
        $subgrouplist = $_POST["subgrouplist"];
        $sglist       = explode(",", $subgrouplist);
        $date_from    = $_POST["date_from"];
        $date_to      = $_POST["date_to"];

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
                 ->join('appointments','appointments.student_id = user_profiles.user_id')
                 ->order_by('date', 'DESC')
                 ->where_in('subgroup.id',$sglist)
                 ->where('appointments.status','completed')
                 ->where('users.status','active')
                 ->get()->result();
        }else if(@$date_from && !@$date_to){
        $ses_rpt = $this->db->select('*')
                 ->from('user_profiles')
                 ->join('users','users.id = user_profiles.user_id')
                 ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                 ->join('appointments','appointments.student_id = user_profiles.user_id')
                 ->order_by('date', 'DESC')
                 ->where('date >=', $date_from)
                 ->where_in('subgroup.id',$sglist)
                 ->where('appointments.status','completed')
                 ->where('users.status','active')
                 ->get()->result();
        }else if(@$date_from && @$date_to){
        $ses_rpt = $this->db->select('*')
                 ->from('user_profiles')
                 ->join('users','users.id = user_profiles.user_id')
                 ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                 ->join('appointments','appointments.student_id = user_profiles.user_id')
                 ->order_by('date', 'DESC')
                 ->where('date >=', $date_from)
                 ->where('date <=', $date_to)
                 ->where_in('subgroup.id',$sglist)
                 ->where('appointments.status','completed')
                 ->where('users.status','active')
                 ->get()->result();
        }

        $vars = array(
            'ses_rpt' => $ses_rpt,
            'spr_tz' => $spr_tz,
            'date_from' => @$date_from,
            'date_to' => @$date_to,
            'partner_id'   => $partner_id,
            'subgrouplist' => $subgrouplist
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();

        $this->load->view('default/contents/admin/reporting/student/export_ses', $vars);
    }
    public function tokenhist($stdid=''){
        $pulldata = $this->db->select('*')
                    ->from('token_histories th')
                    ->join('user_profiles up','th.user_id = up.user_id')
                    ->where('th.user_id',$stdid)
                    ->get()->result();

        $histories = $this->db->select('th.user_id, th.transaction_date, th.token_amount, th.description , th.balance, th.dupd, ts.status, ts.status_description')
            ->from('token_histories th')
            ->join('token_status ts', 'ts.id = th.token_status_id')
            ->where('th.user_id', $stdid)
            ->order_by('th.id', 'desc')
            ->get()->result();

        $name = @$pulldata[0]->fullname;
        $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;

        $this->template->title = $name."'s Token History";
        $title = $name."'s Token History";

        $vars = array(
            'pulldata' => $pulldata,
            'histories' => $histories,
            'minutes' => $minutes,
            'title' => $title
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();

        $this->template->content->view('default/contents/student_partner/reporting/tokenhistview', $vars);
        $this->template->publish();

    }
    public function tokenusage($stdid=''){
        $pulldata = $this->db->select('*')
                    ->from('token_histories th')
                    ->join('user_profiles up','th.user_id = up.user_id')
                    ->where('th.user_id',$stdid)
                    ->get()->result();

        $histories = $this->db->select('th.user_id, th.transaction_date, th.token_amount, th.description , th.balance, th.dupd, ts.status, ts.status_description')
            ->from('token_histories th')
            ->join('token_status ts', 'ts.id = th.token_status_id')
            ->where('th.user_id', $stdid)
            ->where('th.token_status_id', 1)
            ->order_by('th.id', 'desc')
            ->get()->result();

        $name = @$pulldata[0]->fullname;
        $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;

        $this->template->title = $name."'s Token Usage";

        $title = $name."'s Token Usage";

        $vars = array(
            'pulldata' => $pulldata,
            'histories' => $histories,
            'minutes' => $minutes,
            'title' => $title
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();

        $this->template->content->view('default/contents/student_partner/reporting/tokenhistview', $vars);
        $this->template->publish();
    }
    public function completed($stdid=''){
        $ses_rpt = $this->db->select('*')
                 ->from('user_profiles')
                 ->join('users','users.id = user_profiles.user_id')
                 ->join('appointments','appointments.student_id = user_profiles.user_id')
                 ->order_by('date', 'DESC')
                 ->where('appointments.student_id', $stdid)
                 ->get()->result();

        $pulldata = $this->db->select('*')
                    ->from('token_histories th')
                    ->join('user_profiles up','th.user_id = up.user_id')
                    ->where('th.user_id',$stdid)
                    ->get()->result();

        $name = @$pulldata[0]->fullname;

        $this->template->title = $name."'s Completed Sessions";
        $title = $name."'s Completed Sessions";

        $id    = $this->auth_manager->userid();
        $get_tz  = $this->db->select('minutes_val')
                 ->from('user_timezones')
                 ->where('user_id',$id)
                 ->get()->result();
        $spr_tz = $get_tz[0]->minutes_val;

        $vars = array(
            'ses_rpt' => $ses_rpt,
            'spr_tz' => $spr_tz,
            'title' => $title
        );

        // echo "<pre>";
        // print_r($ses_rpt);
        // exit();

        $this->template->content->view('default/contents/student_partner/reporting/comses', $vars);
        $this->template->publish();
    }
}
