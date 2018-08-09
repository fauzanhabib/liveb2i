<?php
if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
class manage_partner_admin extends MY_Site_Controller {

    var $upload_path = 'uploads/images/';
    
    // Constructor
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('partner_model');
        $this->load->model('user_model');
        $this->load->model('identity_model');
        $this->load->library('phpass');
        $this->load->library('schedule_function');
        $this->load->library('email_structure');

        // for messaging action and timing
        $this->load->library('queue');
        $this->load->library('send_email');
        
        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'RAM' && $this->auth_manager->role() != 'RAD') {
            $this->messages->add('Access Denied');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index($page='') {
        $this->template->title = 'Add Affiliate';
        
        $offset = 0;
        $per_page = 6;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('admin_m/manage_partner/index'), count($this->partner_model->select('id, profile_picture, name, address, country, state, city, zip')->where('name not like', 'No Partner')->order_by('name', 'asc')->get_all()), $per_page, $uri_segment);
        
        $vars = array(
            'partner' => $this->partner_model->select('id, profile_picture, name, address, country, state, city, zip')->where('name not like', 'No Partner')->order_by('name', 'asc')->limit($per_page)->offset($offset)->get_all(),
            'pagination' => @$pagination
        );
        $this->template->content->view('default/contents/manage_partner/index', $vars);

        //publish template
        $this->template->publish();
    }
    
    public function edit($partner_id='', $user_id=''){
        $this->template->title = 'Edit Affiliate Admin';
        $data = $this->user_model->get_partner_members($partner_id, $user_id);
        $partner = $this->partner_model->select('*')->where('id', $partner_id)->get();
        if(!$partner || !$data){
            $this->messages->add('Patner is not valid', 'warning');
            redirect('admin_m/manage_partner/list_partner_member/'.$partner_id);
        }
        
        $partner_type = Array('3'=>'Coach Partner', '5'=>'Student Partner');
        $vars = array(
            'selected' => $data[0]->partner_type,
            'partner' => $this->partner_model->where('name not like', 'No Partner')->dropdown('id', 'name'),
            'partner_type' => @$partner_type,
            'data' => $data,
            'form_action' => 'update',
            'partner_id' => $partner->id
        );
        $this->template->content->view('default/contents/admin_m/manage_partner_admin_m/form', $vars);
        $this->template->publish();
    }
    
    public function update($type='',$partner_id = '', $user_id='') {

        $role_link = '';
        if($this->auth_manager->role() == 'RAD') {
            $role_link = "superadmin";
        } else {
            $role_link = "admin";
        }

        $link_redirect = '';
        if($role_link == 'superadmin'){
            $regional_id = $this->db->select('admin_regional_id')->from('partners')->where('id',$partner_id)->get()->result();
            $admin_regional_id = $regional_id[0]->admin_regional_id;
            $link_redirect = 'superadmin_m/manage_partner/partner/'.$type.'/'.$partner_id.'/'.$admin_regional_id;
        } else if($role_link == 'admin'){
            $link_redirect = 'admin_m/manage_partner/partner/'.$type.'/'.$partner_id;
        }


        if(!$partner_id || !$user_id){
            $this->messages->add('Invalid partner or user', 'warning');
            redirect($link_redirect);
        }
        
        $rules = array(
            array('field'=>'email', 'label' => 'Email', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'date_of_birth', 'label' => 'Birthday', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'phone', 'label' => 'Phone', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'skype_id', 'label' => 'Skype', 'rules'=>'trim|required|xss_clean')
        );

        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            redirect($link_redirect);
        }
        
        //user profile data
        $profile = array(
            'user_id' => $user_id,
            'date_of_birth' => $this->input->post('date_of_birth'),
            'phone' => $this->input->post('phone'),
            'dial_code' => $this->input->post('dial_code'),
            'skype_id' => $this->input->post('skype_id')
        );

        $this->db->trans_begin();
        $user_profile = $this->identity_model->get_identity('profile')->select('id')->where('user_id', $user_id)->get();
        if(!$user_profile){
            $this->db->trans_rollback();
            $this->messages->add("Invalid User", 'warning');
            redirect($link_redirect);
        }
        
        // Updating and checking to profile table then storing it into users_profile table
        $profile_id = $this->identity_model->get_identity('profile')->update($user_profile->id, $profile, TRUE);
        if (!$profile_id) {
            $this->db->trans_rollback();
            redirect($link_redirect);
        }


        $this->db->trans_commit();

        $this->db->trans_begin();
        $users_email = $this->identity_model->get_identity('user')->select('email')->where('id', $user_id)->get();
        $old_email = $users_email->email;
        $new_email = $this->input->post('email');
        if($new_email != $old_email){
            $data = array(
                'email' => $new_email,
                );
            $this->db->where('id', $user_id);
            $this->db->update('users', $data);

            //$this->send_email->update_profile($new_email,$password,'created',$this->input->post('fullname'),$get_id_region->name);
            //$this->send_email->change_profile($old_email,$password,'created',$this->input->post('fullname'),$get_id_region->name);
        }

        $this->db->trans_commit();

        // $id_to_email_address = $this->user_model->dropdown('id', 'email');
        // Tube name for messaging sending email action
        // $tube = 'com.live.email';
        // Email's content to inform partner admin their DynEd Live account
        // $data_partner = array(
            // 'subject' => 'Updated Profile',
            // 'email' => $id_to_email_address[$user_id],
            //'content' => 'Dyned Admin just updated your profile, account information: Email = ' . $user['email'] . ' Password = ' . $password . ' You can login to DynEd Live as Partner Admin. For more information, please ask the administrator. Thank you',
        // );
        // $data_partner['content'] = $this->email_structure->header()
        //         .$this->email_structure->title('')
        //         .$this->email_structure->content('Dyned Admin just updated your profile, account information: Email = ' . $user['email'] . ' Password = ' . $password . ' You can login to DynEd Live as Partner Admin. For more information, please ask the administrator. Thank you')
        //         //.$this->email_structure->button('JOIN SESSION')
        //         .$this->email_structure->footer('');
        // $this->queue->push($tube, $data_partner, 'email.send_email');

        //messaging for notification
        // $database_tube = 'com.live.database';
        // $partner_notification = array(
        //     'user_id' => $user_id,
        //     'description' => 'Hi '.$profile['fullname'].'. Dyned Admin just updated your profile',
        //     'status' => 2,
        //     'dcrea' => time(),
        //     'dupd' => time(),
        // );
        // coach's data for reminder messaging
        // IMPORTANT : array index in content must be in mutual with table field in database
        // $data_partner = array(
        //     'table' => 'user_notifications',
        //     'content' => $partner_notification,
        // );

        // messaging inserting data notification
        // $this->queue->push($database_tube, $data_partner, 'database.insert');

        $this->messages->add('Updating Partner Member Successful', 'success');
        redirect($link_redirect);
    }
    
    private function is_valid_email($email = '') {
        if ($this->user_model->where('email', $email)->get_all()) {
            return false;
        } else {
            return true;
        }
    }
    
    private function is_email_changed($user_id='', $email=''){
        $user = $this->user_model->select('email')->where('id', $user_id)->get();
        if($user->email != $email){
            return TRUE;
        }
        return FALSE;
    }
    
    public function delete($type='',$user_id = '',$partner_id=''){
        $role_link = '';
        if($this->auth_manager->role() == 'RAD') {
            $role_link = "superadmin";
        } else {
            $role_link = "admin";
        }
        $status = array(
                    'status' => 'disable',
        );
        $check_appointment = $this->db->select('*')
                                    ->from('users')
                                    ->where('id', $user_id)
                                    ->get()->result();


        $this->db->where('id', $user_id);
        $this->db->update('users', $status);

        $check_status = $this->db->select('status')
                                    ->from('users')
                                    ->where('id',$user_id)
                                    ->get()->result();

        $partner = $this->identity_model->get_identity('profile')->where('user_id', $user_id)->get();
        if($this->identity_model->get_partner_identity($user_id, '', '', '')){
            if($check_status[0]->status == 'disable'){
                $this->messages->add('Delete Partner Member Successful', 'success');
                redirect($role_link.'/manage_partner/partner/'.$type.'/'.$partner_id);
            }
            else{
                $this->messages->add('Invalid Action', 'warning');
                redirect('account/identity/detail/profile');
            }
        }
        else{
            $this->messages->add('Invalid Action', 'warning');
            redirect('account/identity/detail/profile');
        }
        
    }
}
?>