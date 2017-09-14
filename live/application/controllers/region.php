<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class region extends MY_Controller {

	// Constructor
	public function __construct()
	{
		parent::__construct();
	}

	// Index
	public function index()
	{
            $this->template->title = 'REGION';

            $this->template->content->view('default/contents/region/index1');
            //$this->template->content->view('default/contents/approve_user/index', $vars);
            $this->template->publish();
	}
        
        public function page1(){
            $this->template->title = 'REGION';
            $this->template->content->view('default/contents/region/index1');
            $this->template->publish();
        }
        public function page2(){
            $this->template->title = 'REGION';
            $this->template->content->view('default/contents/region/index2');
            $this->template->publish();
        }
        public function page3(){
            $this->template->title = 'REGION';
            $this->template->content->view('default/contents/region/index3');
            $this->template->publish();
        }
        public function page4(){
            $this->template->title = 'REGION';
            $this->template->content->view('default/contents/region/index4');
            $this->template->publish();
        }
        public function page5(){
            $this->template->title = 'REGION';
            $this->template->content->view('default/contents/region/index5');
            $this->template->publish();
        }
        public function page6(){
            $this->template->title = 'REGION';
            $this->template->content->view('default/contents/region/index6');
            $this->template->publish();
        }
        public function page7(){
            $this->template->title = 'REGION';
            $this->template->content->view('default/contents/region/index7');
            $this->template->publish();
        }
        public function page8(){
            $this->template->title = 'REGION';
            $this->template->content->view('default/contents/region/index8');
            $this->template->publish();
        }
        public function page9(){
            $this->template->title = 'REGION';
            $this->template->content->view('default/contents/region/index9');
            $this->template->publish();
        }
        public function page10(){
            $this->template->title = 'REGION';
            $this->template->content->view('default/contents/region/index10');
            $this->template->publish();
        }
        
}

/* End of file region.php */
/* Location: ./application/controllers/region.php */