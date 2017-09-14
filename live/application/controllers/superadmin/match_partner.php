<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class match_partner extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('partner_model');
        $this->load->model('class_matchmaking_model');
        $this->load->model('student_supplier_relation_model');
        $this->load->model('coach_supplier_relation_model');
        $this->load->model('student_group_relation_model');
        $this->load->model('coach_group_relation_model');
        $this->load->model('creator_member_model');
        // for messaging action and timing
        $this->load->library('queue');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'RAD') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = 'Affiliate Matching';
        
        $data = array();
        foreach($this->class_matchmaking_model->get_all() as $d){
            
            $data[$d->id] = array(
                'id' => $d->id,
                'student_supplier_data' => $this->class_matchmaking_model->get_student_supplier($d->id),
                'coach_supplier_data' => $this->class_matchmaking_model->get_coach_supplier($d->id),
                'student_group_data' => $this->class_matchmaking_model->get_student_group($d->id),
                'coach_group_data' => $this->class_matchmaking_model->get_coach_group($d->id),
            );
        }
        $vars = array(
            'data' => $data,
        );
        
//        echo('<pre>');
//        print_r($vars); exit;
        
        $this->template->content->view('default/contents/match_partner/index', $vars);

        //publish template
        $this->template->publish();
    }

    public function student_preview(){
    $post = $this->input->post('stu_sup_id');
        if($post){
            $data_student_group = $this->partner_model->get_student_group($post);
            echo json_encode($data_student_group);
        }else{
            echo '';
        }
    }

    public function coach_preview(){
    $post = $this->input->post('coa_sup_id');
        if($post){
            $data_coach_group = $this->partner_model->get_coach_group($post);
            echo json_encode($data_coach_group);
        }else{
            echo '';
        }
    }
    
    public function add(){
        $this->template->title = 'Add Affiliate Match';

        $data_student_supplier = $this->partner_model->get_student_supplier();
        $data_coach_supplier = $this->partner_model->get_coach_supplier();

        $data_student_group = $this->partner_model->get_student_group();
        $data_coach_group = $this->partner_model->get_coach_group();
//        echo('<pre>');
//        print_r($data_student_supplier); 
//        print_r($data_coach_supplier);exit;
        $vars = array(
            'action' => 'create',
            'data_student_supplier' => $data_student_supplier,
            'data_coach_supplier' => $data_coach_supplier,
            'data_student_group' => $data_student_group,
            'data_coach_group' => $data_coach_group,
        );
        $this->template->content->view('default/contents/match_partner/form', $vars);

        //publish template
        $this->template->publish();
    }
    
    public function create(){
        if(!$this->input->post('__submit')){
           $this->messages->add('An error has occured, please try again.', 'warning');
           redirect('superadmin/match_partner/'); 
        }
        
        if($this->input->post('student_supplier_id') && $this->input->post('coach_supplier_id')){
            // explode multiple supplier id
            $student = $_POST['stu_sup'];
            $coach = $this->input->post('coa_sup');
            $student_group = $this->input->post('stu_gru');
            $coach_group = $this->input->post('coa_gru');
            // echo "<pre>";
            // print_r($student_group);
            // echo "<br>";
            // print_r($coach_group);
            // echo "<pre>";
            // print_r($student);
            // echo "<br>";
            // print_r($coach);
            // exit();
            // die();
            $status = 0;
            // foreach($student as $s){
            //     if($this->student_supplier_relation_model->where('student_supplier_id', $s)->get()){
            //         $this->messages->add('Student Affiliate Has Already Has a Match', 'warning');
            //         $status = 1;
            //         redirect('superadmin/match_partner/');
            //     }
            // }
            // foreach ($student_group as $sg) {
            //     if($this->student_group_relation_model->where('subgroup_id', $sg)->get()){
            //         $this->messages->add('Student Group Has Already Has a Match', 'warning');
            //         $status = 0;
            //         redirect('superadmin/match_partner/');
            //     }
            // }

            
            if($status == 0){
                $data_class_matchmaking = array();
                $class_matchmaking_id = $this->class_matchmaking_model->insert($data_class_matchmaking);
                foreach($student as $s){
                    $data_student_supplier = array(
                        'student_supplier_id' => $s,
                        'class_matchmaking_id' => $class_matchmaking_id,
                    );
                    $this->student_supplier_relation_model->insert($data_student_supplier);
                }

                foreach($coach as $c){
                    $data_coach_supplier = array(
                        'coach_supplier_id' => $c,
                        'class_matchmaking_id' => $class_matchmaking_id,
                    );
                    $this->coach_supplier_relation_model->insert($data_coach_supplier);
                }
     
                foreach($student_group as $sg){
                    if($sg != ''){
                    $data_student_group = array(
                        'subgroup_id' => $sg,
                        'class_matchmaking_id' => $class_matchmaking_id,
                    );
                    $this->student_group_relation_model->insert($data_student_group);
                    }
                }
                    
                foreach($coach_group as $cg){
                    if($cg != ''){
                    $data_coach_group = array(
                        'subgroup_id' => $cg,
                        'class_matchmaking_id' => $class_matchmaking_id,
                    );
                    $this->coach_group_relation_model->insert($data_coach_group);
                    }
                }
                    
                $this->messages->add('Matchmaking created', 'success');
                redirect('superadmin/match_partner/'); 
            }
            else{
                $this->messages->add('Invalid Action', 'warning');
                redirect('superadmin/match_partner/'); 
            }
        }
        else{
            $this->messages->add('Invalid Action', 'warning');
            redirect('superadmin/match_partner/'); 
        } 
    }
    
    public function edit($class_matchmaking_id = ''){
        $this->template->title = 'Edit Affiliate Match';
        $data_student_supplier = $this->partner_model->get_student_supplier();
        $data_coach_supplier = $this->partner_model->get_coach_supplier();
        $data_student_group = $this->partner_model->get_student_group();
        $data_coach_group = $this->partner_model->get_coach_group();
//        echo('<pre>');
//        print_r($data_student_supplier); 
//        print_r($data_coach_supplier);exit;
        $selected_student_supplier = $this->class_matchmaking_model->get_student_supplier($class_matchmaking_id);
        $student_temp = array();
        foreach($selected_student_supplier as $s){
            $student_temp[] = array(
                'id' => $s->id,
                'name' => $s->name,
            );
        }
        $selected_coach_supplier = $this->class_matchmaking_model->get_coach_supplier($class_matchmaking_id);
        $coach_temp = array();
        foreach($selected_coach_supplier as $s){
            $coach_temp[] = array(
                'id' => $s->id,
                'name' => $s->name,
            );
        }
        $selected_student_group = $this->class_matchmaking_model->get_student_group($class_matchmaking_id);
        $studentgroup_temp = array();
        foreach($selected_student_group as $s){
            $studentgroup_temp[] = array(
                'id' => $s->id,
                'name' => $s->name,
            );
            // $data_student_group = $this->partner_model->get_student_group($s->partner_id);
        }
        $selected_coach_group = $this->class_matchmaking_model->get_coach_group($class_matchmaking_id);
        $coachgroup_temp = array();
        foreach($selected_coach_group as $s){
            $coachgroup_temp[] = array(
                'id' => $s->id,
                'name' => $s->name,
            );
            // $data_coach_group = $this->partner_model->get_coach_group($s->partner_id);
        }
        $vars = array(
            'action' => 'update',
            'class_matchmaking_id' => $class_matchmaking_id,
            'selected_student_supplier' => $student_temp,
            'selected_coach_supplier' => $coach_temp,
            'data_student_supplier' => $data_student_supplier,
            'data_coach_supplier' => $data_coach_supplier,
            'selected_student_group' => $studentgroup_temp,
            'selstusup' => $selected_student_supplier,
            'selstugru' => $selected_student_group,
            'selcoasup' => $selected_coach_supplier,
            'selcoagru' => $selected_coach_group,
            'selected_coach_group' => $coachgroup_temp,
            'data_student_group' => $data_student_group,
            'data_coach_group' => $data_coach_group,
            );
        
       // echo('<pre>');
       // print_r($vars); exit(); die();
        $this->template->content->view('default/contents/match_partner/form', $vars);

        //publish template
        $this->template->publish();
    }
    
    public function update(){
//        echo('<pre>');
//        print_r($this->input->post()); exit;
        //print_r ($this->student_supplier_relation_model->select('student_supplier_id')->get_all()); exit;
//        [class_matchmaking_id] => 23
//        [student_supplier_id] => 5
//        [coach_supplier_id] => 3,5
        if(!$this->input->post('__submit')){
            $this->messages->add('Invalid Action', 'warning');
            redirect('superadmin/match_partner/'); 
        }
        
        // echo $this->input->post('class_matchmaking_id');
        // exit();
        // die();
        if($this->input->post('class_matchmaking_id') && $this->input->post('student_supplier_id') && $this->input->post('coach_supplier_id')){
            
            // explode multiple supplier id
            $student = $this->input->post('stu_sup');
            $coach = $this->input->post('coa_sup');
            $student_group = $this->input->post('stu_gru');
            $coach_group = $this->input->post('coa_gru');
            
            $status = 0;
            // foreach($student as $s){
            //     if($this->student_supplier_relation_model->where_not_in('class_matchmaking_id', $this->input->post('class_matchmaking_id'))->where('student_supplier_id', $s)->get()){
            //         $this->messages->add('Student Affiliate Has Already Has a Match', 'warning');
            //         $status = 1;
            //     }
            // }
            foreach($student_group as $sg){
                if($this->student_group_relation_model->where_not_in('class_matchmaking_id', $this->input->post('class_matchmaking_id'))->where('subgroup_id', $sg)->get()){
                    $this->messages->add('Student Group Has Already Has a Match', 'warning');
                    $status = 1;
                }
            }
            if($status == 0){
                $this->student_supplier_relation_model->where('class_matchmaking_id', $this->input->post('class_matchmaking_id'))->delete();
                // creating new student supplier
                foreach($student as $s){
                    $data_student_supplier = array(
                        'student_supplier_id' => $s,
                        'class_matchmaking_id' => $this->input->post('class_matchmaking_id'),
                    );
                    $this->student_supplier_relation_model->insert($data_student_supplier);
                }

                $this->coach_supplier_relation_model->where('class_matchmaking_id', $this->input->post('class_matchmaking_id'))->delete();
                foreach($coach as $c){
                    // creating new coach supplier
                    $data_coach_supplier = array(
                        'coach_supplier_id' => $c,
                        'class_matchmaking_id' => $this->input->post('class_matchmaking_id'),
                    );
                    $this->coach_supplier_relation_model->insert($data_coach_supplier);
                }

                $this->student_group_relation_model->where('class_matchmaking_id', $this->input->post('class_matchmaking_id'))->delete();
                foreach($student_group as $sg){
                    if($sg != ''){
                    $data_student_group = array(
                        'subgroup_id' => $sg,
                        'class_matchmaking_id' => $this->input->post('class_matchmaking_id'),
                    );
                    $this->student_group_relation_model->insert($data_student_group);
                    }
                }
                
                $this->coach_group_relation_model->where('class_matchmaking_id', $this->input->post('class_matchmaking_id'))->delete();   
                foreach($coach_group as $cg){
                    if($cg != ''){
                    $data_coach_group = array(
                        'subgroup_id' => $cg,
                        'class_matchmaking_id' => $this->input->post('class_matchmaking_id'),
                    );
                    $this->coach_group_relation_model->insert($data_coach_group);
                    }
                }
                $this->messages->add('Matchmaking Updated', 'success');
                redirect('superadmin/match_partner/');
            }
            else{
                $this->messages->add('Invalid Action', 'warning');
                redirect('superadmin/match_partner/');
            }
        }
        else{
            $this->messages->add('Invalid Action', 'warning');
            redirect('superadmin/match_partner/'); 
        } 
        
        
    }
    
    public function delete($class_matchmaking_id = ''){
        $this->class_matchmaking_model->delete($class_matchmaking_id);
        // delete table student_supplier_relation_model
        $this->student_supplier_relation_model->where('class_matchmaking_id', $class_matchmaking_id)->delete();
        $this->student_group_relation_model->where('class_matchmaking_id', $class_matchmaking_id)->delete();
            
	    $this->messages->add('Delete Succeeded', 'success');
        redirect('superadmin/match_partner/'); 
    }

    public function index_group() {
        $this->template->title = 'Affiliate Matching';
        
        $data = array();
        foreach($this->class_matchmaking_model->get_all() as $d){
            
            $data[$d->id] = array(
                'id' => $d->id,
                'student_supplier_data' => $this->class_matchmaking_model->get_student_group($d->id),
                'coach_supplier_data' => $this->class_matchmaking_model->get_coach_group($d->id),
            );
        }
        $vars = array(
            'data' => $data,
        );
        
//        echo('<pre>');
//        print_r($vars); exit;
        
        $this->template->content->view('default/contents/match_partner/group_index', $vars);

        //publish template
        $this->template->publish();
    }
    
    public function add_group(){
        $this->template->title = 'Add Affiliate Match';
        $data_student_supplier = $this->partner_model->get_student_group();
        $data_coach_supplier = $this->partner_model->get_coach_group();
//        echo('<pre>');
//        print_r($data_student_supplier); 
//        print_r($data_coach_supplier);exit;
        $vars = array(
            'action' => 'create',
            'data_student_supplier' => $data_student_supplier,
            'data_coach_supplier' => $data_coach_supplier,
        );
        $this->template->content->view('default/contents/match_partner/group_form', $vars);

        //publish template
        $this->template->publish();
    }

    public function create_group(){
        if(!$this->input->post('__submit')){
           $this->messages->add('An error has occured, please try again.', 'warning');
           redirect('superadmin/match_partner/'); 
        }
        
        if($this->input->post('student_supplier_id') && $this->input->post('coach_supplier_id')){
            // explode multiple supplier id
            $student = explode(',' , $this->input->post('student_supplier_id'));
            $coach = explode(',' , $this->input->post('coach_supplier_id'));
            $status = 0;
            foreach($student as $s){
                if($this->student_supplier_relation_model->where('student_supplier_id', $s)->get()){
                    $this->messages->add('Student Affiliate Has Already Has a Match', 'warning');
                    $status = 1;
                    redirect('superadmin/match_partner/');
                }
            }
            
            if($status == 0){
                $data_class_matchmaking = array();
                $class_matchmaking_id = $this->class_matchmaking_model->insert($data_class_matchmaking);
                foreach($student as $s){
                    //$this->db->trans_begin();
                    if($this->student_supplier_relation_model->where('student_supplier_id', $s)->get()){

                    }
                    $data_student_supplier = array(
                        'student_supplier_id' => $s,
                        'class_matchmaking_id' => $class_matchmaking_id,
                    );
                    $this->student_supplier_relation_model->insert($data_student_supplier);
                }

                foreach($coach as $c){
                    $data_coach_supplier = array(
                        'coach_supplier_id' => $c,
                        'class_matchmaking_id' => $class_matchmaking_id,
                    );
                    $this->coach_supplier_relation_model->insert($data_coach_supplier);
                }
                
                $this->messages->add('Matchmaking created', 'success');
                redirect('superadmin/match_partner/'); 
            }
            else{
                $this->messages->add('Invalid Action', 'warning');
                redirect('superadmin/match_partner/'); 
            }
        }
        else{
            $this->messages->add('Invalid Action', 'warning');
            redirect('superadmin/match_partner/'); 
        } 
    }


}
