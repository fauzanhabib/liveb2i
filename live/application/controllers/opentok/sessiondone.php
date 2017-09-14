<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'vendor/autoload.php';

use OpenTok\OpenTok;
use OpenTok\ArchiveMode;
use OpenTok\MediaMode;
use OpenTok\Session;
use OpenTok\Role;

class Sessiondone extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');
        $this->load->library('downloadrecord');

        //checking user role and giving action
    }

    // Index
    public function index() {
        $sessid = $this->input->post("sessionId");
        // $sessid = "1_MX40NTYyMTMxMn5-MTQ3MDIyMzg5MDU2NX5JSzNSdmk3ZGxpb3NrSWh0Y0UrN0VkUXB-QX4";
        $opentok    = new OpenTok($this->config->item('opentok_key'), $this->config->item('opentok_secret'));

        $asd = $this->downloadrecord->init();
        $items = $asd->items;
        foreach($items as $a){
            $sessionID = $a->sessionId;
            $url       = $a->url;
            $archId    = $a->id;
        //     echo "<pre>";
        // print_r($sessionID);
        // exit();
            if($sessionID == $sessid){
                // exit();
                $download = $url;
                $archiveId = $archId;
            }
        }
        //  echo "<pre>";
        // print_r($items);
        // exit();
        // 1_MX40NTYyMTMxMn5-MTQ3MDIyNDAyMzAyOX51c09kOHJTVFRBWEludkoyMW9IUXQzRmh-QX4
        //$opentok->stopArchive($archiveId);
        // $archive = $opentok->getArchive($sessid);
        // $download = "1_MX40NTYyMTMxMn5-MTQ3MDIyMzg5MDU2NX5JSzNSdmk3ZGxpb3NrSWh0Y0UrN0VkUXB-QX4";
        $sessdone = array(
            'downloadurl' => $download
        );

        $this->db->where('session', $sessid);
        $this->db->update('appointments', $sessdone);

        $this->template->title = "Download Records";
        $this->template->content->view('contents/opentok/sessiondone', $sessdone);
        $this->template->publish();
    }
    

}
