<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'vendor/autoload.php';

use OpenTok\OpenTok;
use OpenTok\ArchiveMode;
use OpenTok\MediaMode;
use OpenTok\Session;
use OpenTok\Role;

class Downloadrecord extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');

        //checking user role and giving action
    }

    // Index
    public function index() {
        $appoint_id = $this->input->post("appoint_id");

        if($this->auth_manager->role() == 'STD'){
            $sess = $this->db->select('*')
                    ->from('appointments')
                    ->where('if', $appoint_id)
                    ->get()->result();
            $role = 'CCH';
        } else if($this->auth_manager->role() == 'CCH'){
            $sess = $this->db->select('*')
                    ->from('appointments')
                    ->where('id', $appoint_id)
                    ->get()->result();

            $role = 'STD';
        }
        @$a = $sess[0]->id;

        
        $deletechat = $this->db->delete('chat', array('appointment_id' => $a));

        if($this->auth_manager->role() == 'STD'){
            $user = $this->db->select('*')
                  ->from('appointments')
                  ->join('user_profiles', 'user_profiles.user_id = appointments.coach_id')
                  ->where('appointments.id', $a)
                  ->get()->result();
        } else if($this->auth_manager->role() == 'CCH'){
            $user = $this->db->select('*')
                  ->from('appointments')
                  ->join('user_profiles', 'user_profiles.user_id = appointments.student_id')
                  ->where('appointments.id', $a)
                  ->get()->result();
        }
        
         
        $user_extract = $user[0];

        $opentok    = new OpenTok($this->config->item('opentok_key'), $this->config->item('opentok_secret'));
        $sessid       = $user_extract->session;

        $this->host = 'https://api.opentok.com/v2/partner/45621312/archive';
        $ch = curl_init($this->host);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('X-TB-PARTNER-AUTH: 45621312:701237532dcdef5da13ef2deee8029e11f84c060'));
        $result = curl_exec($ch);
        curl_close($ch);
        $record = json_decode($result);


        // $archive = $opentok->getArchive($sessid);
        // //echo "<pre>";
        // echo $archive;
        // exit();
        $sessionhist = array(
            'user' => $user_extract,
            'role' => $role
        );

        $this->template->title = "Session Summaries";
        $this->template->content->view('contents/opentok/leave', $sessionhist);
        $this->template->publish();
    }
    

}
