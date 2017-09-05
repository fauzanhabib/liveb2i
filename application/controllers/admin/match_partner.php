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
        $this->load->model('creator_member_model');
        // for messaging action and timing
        $this->load->library('queue');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'ADM') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = 'Partner Matching';
        $id = $this->auth_manager->userid();
        
        $data = array();
        foreach($this->class_matchmaking_model->get_all() as $d){
            $datacoach = $this->class_matchmaking_model->get_coach_supplier($d->id,$id);
            if($datacoach){
                $data[$d->id] = array(
                    'id' => $d->id,
                    'student_supplier_data' => $this->class_matchmaking_model->get_student_supplier($d->id),
                    'coach_supplier_data' => $this->class_matchmaking_model->get_coach_supplier($d->id,$id),
                    // 'coach_supplier_data' => $this->class_matchmaking_model->get_coach_supplier($d->id,$id),
                );
            }else{
                $data[$d->id] = array(
                    'id' => $d->id,
                    'student_supplier_data' => $this->class_matchmaking_model->get_student_supplier($d->id,$id),
                    'coach_supplier_data' => $this->class_matchmaking_model->get_coach_supplier($d->id),
                    // 'coach_supplier_data' => $this->class_matchmaking_model->get_coach_supplier($d->id,$id),
                );
            }
        }
        $vars = array(
            'data' => $data,
        );
        
       // echo('<pre>');
       // print_r($a); 
       // exit;
        
        $this->template->content->view('default/contents/match_partner/index', $vars);

        //publish template
        $this->template->publish();
    }
    
    public function add(){
        $id = $this->auth_manager->userid();

        $this->template->title = 'Add Partner Match';
        $data_student_supplier = $this->partner_model->get_student_supplier($id);
        $data_coach_supplier = $this->partner_model->get_coach_supplier($id);
       // echo('<pre>');
       //print_r($data_student_supplier); 
       // print_r($data_coach_supplier);
       // exit;
        $vars = array(
            'action' => 'create',
            'data_student_supplier' => $data_student_supplier,
            'data_coach_supplier' => $data_coach_supplier,
        );
        $this->template->content->view('default/contents/match_partner/form', $vars);

        //publish template
        $this->template->publish();
    }
    
    public function create(){
        if(!$this->input->post('__submit')){
           $this->messages->add('An error has occured, please try again.', 'warning');
           redirect('admin/match_partner/'); 
        }
        
        if($this->input->post('student_supplier_id') && $this->input->post('coach_supplier_id')){
            // explode multiple supplier id
            $student = explode(',' , $this->input->post('student_supplier_id'));
            $coach = explode(',' , $this->input->post('coach_supplier_id'));
            $status = 0;
            foreach($student as $s){
                if($this->student_supplier_relation_model->where('student_supplier_id', $s)->get()){
                    $this->messages->add('Student Partner Has Already Has a Match', 'warning');
                    $status = 1;
                    redirect('admin/match_partner/');
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
                redirect('admin/match_partner/'); 
            }
            else{
                $this->messages->add('Invalid Action', 'warning');
                redirect('admin/match_partner/'); 
            }
        }
        else{
            $this->messages->add('Invalid Action', 'warning');
            redirect('admin/match_partner/'); 
        } 
    }
    
    public function edit($class_matchmaking_id = ''){
        $this->template->title = 'Edit Partner Match';
        $data_student_supplier = $this->partner_model->get_student_supplier();
        $data_coach_supplier = $this->partner_model->get_coach_supplier();
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
        $vars = array(
            'action' => 'update',
            'class_matchmaking_id' => $class_matchmaking_id,
            'selected_student_supplier' => $student_temp,
            'selected_coach_supplier' => $coach_temp,
            'data_student_supplier' => $data_student_supplier,
            'data_coach_supplier' => $data_coach_supplier,
            );
        
//        echo('<pre>');
//        print_r($vars); exit;
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
            redirect('admin/match_partner/'); 
        }
        
        if($this->input->post('class_matchmaking_id') && $this->input->post('student_supplier_id') && $this->input->post('coach_supplier_id')){
            
            // explode multiple supplier id
            $student = array_filter(explode(',' , $this->input->post('student_supplier_id')));
            $coach = array_filter(explode(',' , $this->input->post('coach_supplier_id')));
            
            $status = 0;
            foreach($student as $s){
                if($this->student_supplier_relation_model->where_not_in('class_matchmaking_id', $this->input->post('class_matchmaking_id'))->where('student_supplier_id', $s)->get()){
                    $this->messages->add('Student Partner Has Already Has a Match', 'warning');
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
                $this->messages->add('Matchmaking Updated', 'success');
                redirect('admin/match_partner/');
            }
            else{
                $this->messages->add('Invalid Action', 'warning');
                redirect('admin/match_partner/');
            }
        }
        else{
            $this->messages->add('Invalid Action', 'warning');
            redirect('admin/match_partner/'); 
        } 
        
        
    }
    
    public function delete($class_matchmaking_id = ''){
        $this->class_matchmaking_model->delete($class_matchmaking_id);
        // delete table student_supplier_relation_model
        $this->student_supplier_relation_model->where('class_matchmaking_id', $class_matchmaking_id)->delete();
            
	    $this->messages->add('Delete Succeeded', 'success');
        redirect('admin/match_partner/'); 
    }


}
