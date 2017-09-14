<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Coachnote extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('class_member_model');

        //checking user role and giving action
    }

    // Index
    public function index() {
        $cch_note       = $this->input->post("cch_note");
        $appoint_id_cch = $this->input->post("appoint_id_cch");
       

        $ins_cch_note = array(
            'cch_notes'       => $cch_note
        );

        $this->db->where('id', $appoint_id_cch);
        $this->db->update('appointments', $ins_cch_note);

        $this->messages->add('Successfully Posted Notes', 'success');

        redirect('opentok/live');
    }
    

}
