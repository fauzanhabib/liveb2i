<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Lang_switch extends CI_Controller
{
    public function __construct() {
        parent::__construct();
    }

    function switch_language($language = "") {
        $language = ($language != "") ? $language : "english";
        $this->session->set_userdata('site_lang', $language);
        
        redirect('account/identity/detail/profile');
    }
}


