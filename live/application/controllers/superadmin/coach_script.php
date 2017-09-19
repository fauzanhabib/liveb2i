<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Coach_script extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('user_profile_model');
        $this->load->model('identity_model');
        $this->load->library('common_function');


        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'RAD') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }
    // Index
    public function index() {

        $this->template->title = 'List Materials';
        $id = $this->auth_manager->userid();

        $a1 = $this->db->distinct()
            ->select('certificate_plan')
            ->from('script')
            ->get()->result();

        $vars = array(
            'a1' => @$a1
        );

        $this->template->content->view('default/contents/superadmin/coach_script/index',$vars);

        $this->template->publish();
    }

    public function unit_list(){
        $cert = $this->input->post("cert");

        $scripts = $this->db->distinct()
            ->select('unit')
            ->from('script')
            ->where('certificate_plan', $cert)
            ->get()->result();

        $vars = array(
            'a1' => @$scripts,
            'cert' => @$cert
        );
        // echo $cert;
        $this->load->view('default/contents/superadmin/coach_script/certif',$vars);
    }

    public function unit_list_preview(){
        $cert = $this->input->post("cert");

        $scripts = $this->db->distinct()
            ->select('unit')
            ->from('script')
            ->where('certificate_plan', $cert)
            ->get()->result();

        $vars = array(
            'a1' => @$scripts,
            'cert' => @$cert
        );
        // echo $cert;
        $this->load->view('default/contents/superadmin/coach_script/certif_preview',$vars);
    }

    public function add_new(){
        $this->template->title = 'Add Materials';

        $scripts = $this->db->distinct()
            ->select('certificate_plan')
            ->from('script')
            ->get()->result();

        $allcert = array("A1", "A2", "B1", "B2", "C1", "C2");

        // $result = !empty(array_intersect($allcert, $scripts));

        foreach($scripts as $sc){
            $new[] = $sc->certificate_plan;
        }

        $result = array_intersect($allcert, $new);

        // echo "<pre>";print_r($result);exit();

        $vars = array(
            'existcert' => @$scripts,
            'result'    => @$result,
            'allcert'   => @$allcert
        );

        $this->template->content->view('default/contents/superadmin/coach_script/add_new',$vars);

        $this->template->publish();
    }
    public function pull_unit(){
        $cert = $this->input->post("cert");

        $scripts = $this->db->distinct()
            ->select('unit,certificate_plan,id')
            ->from('script')
            ->order_by('unit_order', 'ASC')
            ->where('certificate_plan', $cert)
            ->get()->result();

        $scripts2 = $this->db->distinct()
            ->select('unit')
            ->from('script')
            ->order_by('unit_order', 'ASC')
            ->where('certificate_plan', $cert)
            ->get()->result();

        $vars = array(
            'pull_contents' => @$scripts,
            'pull_contents2' => @$scripts2,
            'cert'          => $cert
        );

        $this->load->view('default/contents/superadmin/coach_script/unitlist',$vars);

    }
    public function add_unit_action(){
        $cert = $this->input->post("certpullunit");
        $unit = $this->input->post("unittitleinput");

        $scripts = $this->db->distinct()
            ->select('unit')
            ->from('script')
            ->where('certificate_plan', $cert)
            ->get()->result();

        $total_unit = count($scripts);
        $new_order  = $total_unit+1;

        foreach($scripts as $sc){
            if($sc->unit == $unit){
                echo "Unit is already exist";
                exit();
            }
        }

        $newcontent = array(
           'certificate_plan' => $cert,
           'unit_order' => $new_order,
           'script'  => 'First script of '.$unit.'. It is automatically added if you add new Unit.',
           'unit'  => $unit
        );

        $this->db->insert('script', $newcontent);

    }

    public function del_unit_action(){
        $cert = $this->input->post("cert");

        $deletecontent = $this->db->delete('script', array('unit' => $cert));

    }

    public function pull_unitonly(){
        $cert = $this->input->post("cert");

        $scripts = $this->db->distinct()
            ->select('unit')
            ->from('script')
            ->order_by('unit_order', 'ASC')
            ->where('certificate_plan', $cert)
            ->get()->result();

        $vars = array(
            'pull_contents' => @$scripts
        );

        $this->load->view('default/contents/superadmin/coach_script/unitonly',$vars);

    }
    public function unit_order(){
        $tableData = stripcslashes($_POST['TableData']);

        $TableData = json_decode($tableData,TRUE);

        $td_total = count($TableData);
        $n = 0;
        for($i=0; $i < $td_total; $i++) {
            $updorder = array(
                'unit_order'   => trim($TableData[$n]['orderNo']),
                'unit' => trim($TableData[$n]['unit'])
            );

            // print_r($updorder);
            $update = $this->db->where('unit', $updorder['unit'])
                                ->update('script', $updorder);

            $n++;
        }

        // echo "<pre>";print_r($updorder);

    }
    public function pull_content(){
        $cert = $this->input->post("cert");

        $scripts = $this->db->select('script, unit, id, certificate_plan, unit_order')
                ->from('script')
                ->where('unit', $cert)
                ->get()->result();


        $vars = array(
            'pull_contents' => @$scripts
        );

        $this->load->view('default/contents/superadmin/coach_script/contentlist',$vars);

    }
    public function add_content_action(){
        $content = $this->input->post("content");
        $unit    = $this->input->post("unit");
        $cert    = $this->input->post("cert");
        $unit_order    = $this->input->post("unit_order");

        $newcontent = array(
           'certificate_plan' => $cert,
           'unit_order' => $unit_order,
           'script'  => $content,
           'unit'  => $unit
        );

        $this->db->insert('script', $newcontent);

        // $this->load->view('default/contents/superadmin/coach_script/contentlist',$vars);

    }
    public function del_content_action(){
        $selector = $this->input->post("selector");

        $deletecontent = $this->db->delete('script', array('id' => $selector));

        // $this->load->view('default/contents/superadmin/coach_script/contentlist',$vars);

    }

    public function update_coaching(){
    	$textbox	= $this->input->post("textbox");
    	$script_id 	= $this->input->post("id");
    	$certificate_plan = $this->input->post("cr");

    	$upd_coach = array(
         	'script' => $textbox
      	);

      	$this->db->where('id', $script_id);
      	$this->db->where('certificate_plan', $certificate_plan);
      	$this->db->update('script', $upd_coach);

        $this->messages->add('Update Successful', 'success');
        redirect('superadmin/coach_script/index');
    }
    public function update_coaching_inside(){
        $textbox    = $this->input->post("textbox");
        $script_id  = $this->input->post("id");
        $certificate_plan = $this->input->post("cr");

        $upd_coach = array(
            'script' => $textbox
        );

        $this->db->where('id', $script_id);
        $this->db->where('certificate_plan', $certificate_plan);
        $this->db->update('script', $upd_coach);
    }

}