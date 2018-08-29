<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'vendor/autoload.php';

use OpenTok\OpenTok;
use OpenTok\ArchiveMode;
use OpenTok\MediaMode;
use OpenTok\Session;
use OpenTok\Role;

class Checkrecord extends MY_Site_Controller {

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
        $sessionID = $this->input->post("sessionid");

        $pullsess = $this->db->select('*')
                ->from('appointments')
                ->where('session', $sessionID)
                ->get()->result();

        if(@$pullsess[0]->key == '1'){
          $apiKey  = $this->config->item('opentok_key');
          $secret  = $this->config->item('opentok_secret');
        }else{
          $apiKey  = $this->config->item('opentok_key2');
          $secret  = $this->config->item('opentok_secret2');
        }

        $asd = $this->downloadrecord->init($apiKey, $secret);

        $items = $asd->items;
        foreach($items as $a){
            $sess    = $a->sessionId;
            $status  = $a->status;
            $url     = $a->url;

            if($sessionID == $sess){
                $stat = $status;
                $downloadurl = $url;
            }
        }

        $cchatt = $pullsess[0]->cch_attend;
        $stdatt = $pullsess[0]->std_attend;

        if (@$downloadurl == Null) {
            if($cchatt == NULL && $stdatt == NULL){
                $note = "No recorded session. Both student and coach didn't attend the session.";
            }else{
                $note = "This download link has expired. Recordings are only available for 72 hours after session. ";
            }
        }else{
            $note = "Recording links are only available for 72 hours after end of session.";
        }

        $check = array(
            'status' => @$stat,
            'note'   => @$note,
            'downloadurl'   => @$downloadurl
        );

        // echo "<pre>";print_r($asd);exit();

        $this->template->title = "Download Record";
        $this->template->content->view('contents/opentok/checkrecord', $check);
        $this->template->publish();
    }


}
