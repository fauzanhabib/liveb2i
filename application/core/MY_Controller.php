<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Extended Default Core Controller
 * @author 		Jogi Silalahi <jogi@pistarlabs.com>
 * @version 	1.0.0
 */
class MY_Controller extends CI_Controller {
    
    /**
     * Template default URL
     * @var string
     */
    var $layout = 'default/layouts/site';

    /**
     * Stylesheet default URL
     * @var array
     */
    var $styles = array(
        'assets/css/bootstrap.css',
        'assets/css/style.css'
    );

    /**
     * Javascripts default URL
     * @var array
     */
    var $scripts = array(
        'assets/js/jquery.min.js',
        'assets/js/bootstrap.min.js'
    );
    
    /**
     * Constructor
     *
     * Loading all assets and template for site controllers
     */
    public function __construct() {
        parent::__construct();
        
                // Template
        $this->_prep_template();


        // Styles
        $this->_prep_styles();

        // Scripts
        $this->_prep_scripts();
    }
    
        /**
     * Set default template
     */
    public function _prep_template() {
        $this->template->set_template($this->layout);
    }

    /**
     * Set default styleshet
     */
    public function _prep_styles() {
        foreach ($this->styles as $style) {
            $this->template->stylesheet->add($style);
        }
    }

    /**
     * Set default javascript
     */
    public function _prep_scripts() {
        foreach ($this->scripts as $script) {
            $this->template->javascript->add($script);
        }
    }

}

// Site Controller
class MY_Site_Controller extends MY_Controller {
    public function __construct() {
        parent::__construct();
        // Checking
        $this->check_privileges();
    }

    // Checking for user privileges
    private function check_privileges() {
        $this->load->library('auth');

        if (!$this->auth->loggedin()) {
            redirect('login');
        }
    }
}
