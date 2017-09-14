<?php 
class my404 extends MY_Site_Controller
{
    public function __construct() 
    {
        parent::__construct(); 
    } 

    public function index() 
    { 
        $this->output->set_status_header('404'); 
        $data['content'] = 'error_404'; // View name 
        $this->template->content->view('default/contents/upcoming_session/coach_detail/index', $data);
        $this->template->publish();
    } 
} 
?> 