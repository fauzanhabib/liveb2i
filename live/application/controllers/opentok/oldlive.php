<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'vendor/autoload.php';

use OpenTok\OpenTok;
use OpenTok\ArchiveMode;
use OpenTok\MediaMode;
use OpenTok\Session;
use OpenTok\Role;


class Live extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // load models for student info
        $this->load->model('user_model');
        /* if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD' || $this->auth_manager->role() != 'CCH') {
            $this->messages->add('Access Denied');
            redirect('home');
        } */
	}

    function index(){
        echo "hai";
    }
	
    // Index
    public function coach()
	{
		
		$this->template->title = "Live Session";
		$opentok = new OpenTok($this->config->item('opentok_key'), $this->config->item('opentok_secret'));
        $session = $opentok->createSession();
        $token = $session->generateToken(array(
            'role' => Role::MODERATOR
        ));
		//$sessionId = $session->getSessionId();
		//$archive = $opentok->startArchive($sessionId);
        $sess = $this->db->select('session , token')
                ->from('opentok')
                ->get()->result();
        
        
        $sessionId = $sess[0]->session;
        $token = $sess[0]->token;
        $data = array(
            'sessionId' => $sessionId,
            'token' => $token
        );        
	
		$this->template->content->view('contents/opentok/coach', $data);
		$this->template->publish();
	}
    
    public function student()
	{
		
		$this->template->title = "Live Session";
		$sess = $this->db->select('session , token2')
                ->from('opentok')
                ->get()->result();
        /*echo "<pre>";
        print_r($sess[0]->session);
        exit();*/
        
        $sessionId2 = $sess[0]->session;
        $token2 = $sess[0]->token2;
        $data2 = array(
            'sessionId' => $sessionId2,
            'token' => $token2
        );
	
		$this->template->content->view('contents/opentok/student', $data2);
		$this->template->publish();
	}
   
}
