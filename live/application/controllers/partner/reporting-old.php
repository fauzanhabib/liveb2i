<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class reporting extends MY_Site_Controller {

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
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'PRT') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }
	
		function generateRandomString($length = 5) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }
	

    public function index() {
        $this->template->title = 'Reporting';
        $partner = $this->auth_manager->partner_id();
        $coach = $this->db->select('up.user_id, up.fullname, s.name')
                            ->from('user_profiles up')
                            ->join('users u', 'u.id = up.user_id')
                            ->join('subgroup s', 's.id = up.subgroup_id')
                            ->where('u.role_id', 2)
                            ->where('u.status', 'active')
                            ->where('s.partner_id', $partner)
                            ->get()->result();
        
        $vars = array(
            'coach' => $coach,
            );
        
        $this->template->content->view('default/contents/partner/reporting/index', $vars);
        $this->template->publish();
    
    }

    public function search() {
        $startdate = $this->input->post('date_from');
        $enddate = $this->input->post('date_to');
        if($this->input->post('date_from') && $this->input->post('date_to')){
            $this->session->set_userdata('date_from', $this->input->post('date_from'));
            $this->session->set_userdata('date_to', $this->input->post('date_to'));
        }
        
        $rules = array(
            array('field'=>'date_from', 'label' => 'Date From', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'date_to', 'label' => 'Date To', 'rules'=>'trim|required|xss_clean')
        );

        if(($this->input->post('__submit')))
    {
        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            redirect('partner/reporting');
            
        }
    }
        
        $partner = $this->auth_manager->partner_id();
        $partnername = $this->db->select('name')->from('partners')->where('id', $partner)->get()->result();
        $name = $partnername[0]->name;
        $coach = $this->db->select('up.user_id, up.fullname, s.name')
                            ->from('user_profiles up')
                            ->join('users u', 'u.id = up.user_id')
                            ->join('subgroup s', 's.id = up.subgroup_id')
                            ->where('u.role_id', 2)
                            ->where('u.status', 'active')
                            ->where('s.partner_id', $partner)
                            ->get()->result();
        
                $vars = array(
            'coach' => $coach,
            'startdate' => $startdate,
            'enddate' => $enddate,
        );
        $this->template->content->view('default/contents/partner/reporting/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function download($startdate='', $enddate=''){

        $partner = $this->auth_manager->partner_id();
        $partnername = $this->db->select('name')->from('partners')->where('id', $partner)->get()->result();
        $name = $partnername[0]->name;
        if($this->uri->segment(4) == ''){
                        $file = $name.'`s Late Coach Until Now';
                    }else{
                        $file = $name.'`s Late Coach From '.$startdate.' To '.$enddate.'';  
                    }
        $coach = $this->db->select('up.user_id, up.fullname, s.name')
                            ->from('user_profiles up')
                            ->join('users u', 'u.id = up.user_id')
                            ->join('subgroup s', 's.id = up.subgroup_id')
                            ->where('u.role_id', 2)
                            ->where('u.status', 'active')
                            ->where('s.partner_id', $partner)
                            ->get()->result();
        
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$file.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<table border="1">
        <thead>
          <tr>
            <th>Coach Name</th>
            <th>Subgroup</th>
            <th>Appointment Date</th>
            <th>Start Time</th>
            <th>Coach Attendance</th>
          </tr>
        </thead>
        <tbody>';
        foreach(@$coach as $c) { 
            if($this->uri->segment(4) == ''){
                        $data = $this->db->select('id, coach_id, date, start_time, cch_attend')->from('appointments')->where('coach_id', $c->user_id)->where('status', 'completed')->get()->result();  
                    }else{
                        $data = $this->db->select('id, coach_id, date, start_time, cch_attend')->from('appointments')->where('coach_id', $c->user_id)->where('status', 'completed')->where('date BETWEEN "'. date('Y-m-d', strtotime($startdate)). '" and "'. date('Y-m-d', strtotime($enddate)).'"')->get()->result();
                    }
                        foreach(@$data as $d) { 
                    $cch_att_dif = strtotime($d->cch_attend) - strtotime($d->start_time);
                    $cch_att_val = date("H:i:s", $cch_att_dif);
                    if($cch_att_dif > '300'){ ; echo '
          <tr>
            <td>';echo $c->fullname; echo '</td>
            <td>';echo $c->name; echo '</td>
            <td>';echo $d->date; echo '</td>
            <td>';echo $d->start_time; echo '</td>
            <td>';echo $d->cch_attend; echo '</td>
          </tr>';
      } } } echo '
          </tbody>
        </table>';
    }
}