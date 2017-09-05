<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class manage_region extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('region_model');


        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'RAD') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function add_region() {
        $this->template->title = 'Add Admin Region';
        $vars = array(
            'option_country' => $this->common_function->country_code,
            'form_action' => 'create_admin_region'
        );
        $this->template->content->view('default/contents/superadmin/manage_admin/add_admin/form', $vars);
        $this->template->publish();
    
    }

}