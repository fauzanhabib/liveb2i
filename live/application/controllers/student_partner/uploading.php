<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class uploading extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // load model for student partner
        $this->load->model('user_model');
        //
        $this->load->model('class_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('class_member_model');
        $this->load->model('class_schedule_model');
        $this->load->model('class_week_model');

        //uploading student
        $this->load->model('creator_member_model');
        $this->load->model('identity_model');
        $this->load->model('user_token_model');
        $this->load->model('student_detail_profile_model');
        $this->load->library('phpass');
        $this->load->library('queue');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'SPR') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = 'Uploading';
        $vars = array(
            'classes' => $this->class_model->select('id, class_name, student_amount, start_date, end_date')->where('student_partner_id', $this->auth_manager->userid())->get_all(),
        );
        $this->template->content->view('default/contents/uploading/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function create() {
        if(isset($_POST["submit"]))
	{
		$file = $_FILES['file']['tmp_name'];
		$handle = fopen($file, "r");
		$c = 0;
                echo('<pre>');
		while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
		{
			$name = $filesop[0];
			$email = $filesop[1];
                        if($c != 0){
                           print_r($filesop); 
                        }
                            
			
			$c = $c + 1;
		}
                exit;
		
			if($sql){
				echo "You database has imported successfully. You have inserted ". $c ." recoreds";
			}else{
				echo "Sorry! There is some problem.";
			}

	}
    }

}
