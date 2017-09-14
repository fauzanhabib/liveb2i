<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class test_send_email extends MY_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // for messaging action and timing
        $this->load->library('queue');
        $this->load->library('email_structure');
    }

    public function index()
    {
        echo "hai";
    }

    function tes(){

        $tube = 'com.live.email';

        $tesworker = array(
            'subject' => 'Test Worker',
            'email' => 'tobbysembiring@gmail.com',
        );

        $tesworker['content'] = $this->email_structure->header()
                .$this->email_structure->title('TES')
                .$this->email_structure->content('TES CONTENT')
                .$this->email_structure->footer('');

        $this->queue->push($tube, $tesworker, 'email.send_email');
    }
}
?>