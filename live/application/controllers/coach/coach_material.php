<?php

/**
 * Class    : Histories.php
 * Author   : Ponel Panjaitan (ponel@pistarlabs.co.id)
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Coach_material extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();

        // Checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CCH') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    public function index(){
        $this->template->title = 'Coach Materials';
        $id = $this->auth_manager->userid();

        $a1 = $this->db->distinct()
            ->select('unit')
            ->from('script')
            ->where('certificate_plan', 'A1')
            ->get()->result();

        $a2 = $this->db->distinct()
            ->select('unit')
            ->from('script')
            ->where('certificate_plan', 'A2')
            ->get()->result();

        $b1 = $this->db->distinct()
            ->select('unit')
            ->from('script')
            ->where('certificate_plan', 'B1')
            ->get()->result();

        $b2 = $this->db->distinct()
            ->select('unit')
            ->from('script')
            ->where('certificate_plan', 'B2')
            ->get()->result();

        $c1 = $this->db->distinct()
            ->select('unit')
            ->from('script')
            ->where('certificate_plan', 'C1')
            ->get()->result();

        $c2 = $this->db->distinct()
            ->select('unit')
            ->from('script')
            ->where('certificate_plan', 'C2')
            ->get()->result();
        // echo "<pre>";
        // print_r($a1);
        // exit();
        $vars = array(
            'a1' => @$a1,
            'a2' => @$a2,
            'b1' => @$b1,
            'b2' => @$b2,
            'c1' => @$c1,
            'c2' => @$c2
        );

        $this->template->content->view('default/contents/coach/coach_material/index', $vars);
        $this->template->publish();
    }

    public function bc(){
        $this->template->title = 'Coach Materials';
        $id = $this->auth_manager->userid();

        $a1 = $this->db->distinct()
            ->select('unit')
            ->from('b2c_script')
            ->where('certificate_plan', 'A1')
            ->get()->result();

        $a2 = $this->db->distinct()
            ->select('unit')
            ->from('b2c_script')
            ->where('certificate_plan', 'A2')
            ->get()->result();

        $b1 = $this->db->distinct()
            ->select('unit')
            ->from('b2c_script')
            ->where('certificate_plan', 'B1')
            ->get()->result();

        $b2 = $this->db->distinct()
            ->select('unit')
            ->from('b2c_script')
            ->where('certificate_plan', 'B2')
            ->get()->result();

        $c1 = $this->db->distinct()
            ->select('unit')
            ->from('b2c_script')
            ->where('certificate_plan', 'C1')
            ->get()->result();

        $c2 = $this->db->distinct()
            ->select('unit')
            ->from('b2c_script')
            ->where('certificate_plan', 'C2')
            ->get()->result();

        $vars = array(
            'a1' => @$a1,
            'a2' => @$a2,
            'b1' => @$b1,
            'b2' => @$b2,
            'c1' => @$c1,
            'c2' => @$c2
        );
        // echo "<pre>";print_r($vars);exit();
        $this->template->content->view('default/contents/coach/coach_material/index_bc', $vars);
        $this->template->publish();
    }

    public function neo_dashboard(){

      $this->template->content->view('default/contents/coach/coach_material/neo_dashboard');
      $this->template->publish();
    }

}
