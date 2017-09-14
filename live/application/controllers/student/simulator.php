<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'vendor/autoload.php';

use OpenTok\OpenTok;
use OpenTok\ArchiveMode;
use OpenTok\MediaMode;
use OpenTok\Session;
use OpenTok\Role;


class Simulator extends MY_Site_Controller {
    // Constructor
    public function __construct() {
        parent::__construct();
        /* if ($this->auth_manager->role() != 'STD' || $this->auth_manager->role() != 'CCH') {
            redirect('home');
        } */
    }
    
    // Index
    public function index(){
        $this->template->title = "Live Session Simulator";

        $opentok    = new OpenTok($this->config->item('opentok_key'), $this->config->item('opentok_secret'));
        $session    = $opentok->createSession(array('mediaMode' => MediaMode::ROUTED));

        $apiKey     = $this->config->item('opentok_key');
        $sessionId  = $session->getSessionId();
        $token      = $opentok->generateToken($sessionId);

        $var_simulator = array(
            'sessionId'  => @$sessionId,
            'token'      => @$token,
            'apiKey'     => @$apiKey,
        );

        // echo "<pre>";print_r($var_simulator);exit();

        $this->load->view('default/contents/student/simulator/index.php', $var_simulator);
        // $this->template->content->view('default/contents/coach/simulator/index.php');
        // $this->template->publish();     
    }

   
}
