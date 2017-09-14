<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
class Webex extends MY_Site_Controller {
    
    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('webex_host_model');
        
        $this->load->library('webex_function');
        
        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'PRT') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    public function index(){
        echo "WebEx";
    }
    
    /**
     * @Function WebEx pre_register new partner
     */
    public function pre_register(){
        $this->session->set_userdata('register', 'new');
        
        $timezones = Array('0'=>'UM12', '1'=>'UM11', '2'=>'UM10', '3'=>'UM9', '4'=>'UM8', '5'=>'UM7', '7'=>'UM6', '10'=>'UM5', '13'=>'UM4', '15'=>'UM25', '16'=>'UM3', '18'=>'UM2', '19'=>'UM1', '20'=>'UTC', '22'=>'UP1', '26'=>'UP2', '32'=>'UP3', '35'=>'UP25', '36'=>'UP4', '38'=>'UP35', '39'=>'UP5', '41'=>'UP45', '42'=>'UP6', '44'=>'UP7', '45'=>'UP8', '49'=>'UP9', '51'=>'UP85', '54'=>'UP10', '59'=>'UP11', '61'=>'UP12');
        $timezone = array_search($this->input->post('timezones'), $timezones);
        $vars = Array(
            'SDW' => $this->input->post('subdomain_webex'),
            'PID' => $this->input->post('partner_id'),
            'FN' => $this->input->post('first_name'),
            'LN' => $this->input->post('last_name'),
            'EM' => $this->input->post('email_address'),
            'WID' => $this->input->post('webex_id'),
            'PW' => $this->input->post('password'),
            'TZ' => 44,
            'SITE_ID' => $this->input->post('site_id'),
            'SITE_NAME' => $this->input->post('site_name')
        );
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules($this->webex_host_model->validate);

        if (!$this->form_validation->run()) {
            $this->messages->add(validation_errors(), 'danger');
            redirect('partner/webex/login');
        }
        
        $this->session->set_userdata($vars);
        $this->template->content->view('default/contents/webex/partner/register', $vars);
        $this->template->publish();
    }
    
    /**
     * @Function WebEx register new partner
     */
    public function register(){
        $this->session->set_userdata('new', 'new');
        if($this->input->get('AT') == 'SU'){
            if($this->input->get('ST') == 'SUCCESS'){
                $vars = Array(
                    'subdomain_webex' => $this->session->userdata('SDW'),
                    'user_id' => $this->auth_manager->userid(),
                    'partner_id' => $this->session->userdata('PID'),
                    'first_name' => $this->session->userdata('FN'),
                    'last_name' => $this->session->userdata('LN'),
                    'email_address' => $this->session->userdata('EM'),
                    'webex_id' => $this->session->userdata('WID'),
                    'password' => $this->session->userdata('PW'),
                    'timezones' => $this->session->userdata('TZ'),
                    'site_id' => $this->session->userdata('SITE_ID'),
                    'site_name' => $this->session->userdata('SITE_NAME')
                );
                if($this->webex_host_model->insert($vars)){
                    $this->messages->add($this->input->get('RS'), 'error');
                    redirect('partner/webex/login');
                }else{
                    $this->messages->add(validation_errors(), 'danger');
                    $this->messages->add('Failed registering host', 'error');
                    redirect('partner/webex/login');
                }
            }else{
                $this->messages->add($this->input->get('RS'), 'error');
                $this->messages->add('Bad password or webex id', 'error');
                redirect('partner/webex/login');
            }
        }
        
        $this->template->content->view('default/contents/webex/form');
        $this->template->publish();
    }
    
    /**
     * @Function WebEx pre_register new partner
     */
    public function pre_login(){
        $this->session->set_userdata('register', 'exist');
        $vars = Array(
            'SDW' => $this->input->post('subdomain_webex'),
            'WID' => $this->input->post('webex_id'),
            'PID' => $this->input->post('partner_id'),
            'PW' => $this->input->post('password'),
            'SITE_ID' => $this->input->post('site_id'),
            'SITE_NAME' => $this->input->post('site_name'),
            'EMAIL' => $this->input->post('email_address'),
            'TZ' => 44
        );
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules($this->webex_host_model->validate);

        if (!$this->form_validation->run()) {
            $this->messages->add(validation_errors(), 'danger');
            redirect('partner/webex/login');
        }
        
        $this->session->set_userdata($vars);
        $this->template->content->view('default/contents/webex/partner/login', $vars);
        $this->template->publish();
    }
    
    /**
     * @Function WebEx register new partner
     */
    public function login(){
        $this->template->title = 'WebEx Login';
        if($this->input->get('AT') == 'LI'){
            if($this->input->get('ST') == 'SUCCESS'){
                $vars = Array(
                    'subdomain_webex' => $this->session->userdata('SDW'),
                    'user_id' => $this->auth_manager->userid(),
                    'webex_id' => $this->session->userdata('WID'),
                    'partner_id' => $this->session->userdata('PID'),
                    'password' => $this->session->userdata('PW'),
                    'site_id' => $this->session->userdata('SITE_ID'),
                    'site_name' => $this->session->userdata('SITE_NAME'),
                    'email_address' => $this->session->userdata('EMAIL'),
                    'timezones' => $this->session->userdata('TZ')
                );
                $webex_exsist = $this->webex_host_model->select('webex_id')->where(Array('user_id' => $this->auth_manager->userid(), 'webex_id' => $this->session->userdata('WID')))->get();
                
                if(!$webex_exsist){
                    if($this->webex_host_model->insert($vars)){
                        $this->messages->add('Host has been registered successfully', 'success');
                        redirect('partner/webex/login');
                    }  else {
                        $this->messages->add(validation_errors(), 'danger');
                        $this->messages->add('Host failed registered', 'success');
                        redirect('partner/webex/login');
                    }
                    
                }else{
                    $this->messages->add('Host had been registered', 'error');
                    redirect('partner/webex/login');
                }
            }elseif($this->input->get('RS') == 'AlreadyLogon'){
                $this->auto_logout();
            }else{
                $this->messages->add($this->input->get('RS'), 'error'); 
            }
        }
        $this->template->content->view('default/contents/webex/partner/form');
        $this->template->publish();
    }
    
    /**
     * @Function WebEx logout
     */
    public function logout(){
        if($this->input->get('AT') == 'LO'){
            if($this->input->get('ST') == 'SUCCESS'){
                $vars = Array(
                    'SDW' => $this->session->userdata('SDW'),
                    'WID' => $this->session->userdata('WID'),
                    'PW' => $this->session->userdata('PW'),
                    'SITE_ID' => $this->session->userdata('SiteID'),
                    'SITE_NAME' => $this->session->userdata('SiteName')
                );
                $this->template->content->view('default/contents/webex/partner/login', $vars);
                $this->template->publish();
            }
        }
    }
    
    /**
     * @Function WebEx auto logout
     */
    public function auto_logout(){
        $this->template->content->view('default/contents/webex/partner/auto_logout');
        $this->template->publish();
    }
    
    public function get_user(){
        $subdomain_webex = $this->input->post('SDW');
        $webex_id = $this->input->post('WID');
        $password = $this->input->post('PW');
        $site_id = $this->input->post('SiteID');
        $partner_id = $this->input->post('PID');
        $email = $this->input->post('Email');
        
        $XML_SITE = $subdomain_webex . ".webex.com";
        $XML_PORT = "443";

        $d["XML"] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <serv:message xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
                <header>
                    <securityContext>
                        <webExID>{$webex_id}</webExID>
                        <password>{$password}</password>
                        <siteID>{$site_id}</siteID>
                        <partnerID>{$partner_id}</partnerID>
                        <email>{$email}</email>
                    </securityContext>
                </header>
                <body>
                    <bodyContent xsi:type=\"java:com.webex.service.binding.user.GetUser\">
                        <webExId>{$webex_id}</webExId>
                    </bodyContent>
                </body>
                </serv:message>";

        $URL = "http://{$XML_SITE}/WBXService/XMLService";
        $result = $this->post_it($d, $URL, $XML_PORT);
        
        // Clean xml
        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
        $simple_xml = simplexml_load_string($clean_result);
    }
    
    /**
     * @function to post xml to webex api
     */
    public function post_it($data_stream, $URL_, $port) {
        //  Strip http:// from the URL if present
        $URL__ = preg_replace("^http://^", "", $URL_);
        //  Separate into Host and URI
        $host = substr($URL__, 0, strpos($URL__, "/"));

        //  Form the request body
        $req_body = "";
        while (list($key, $val) = each($data_stream)) {
            if ($req_body) {
                $req_body.= "&";
            }
            $req_body.= $key . "=" . urlencode($val);
        }
        $xml = $data_stream['XML'];
        $URL = $host;
        $fp = @fsockopen('ssl://' . $URL, $port, $errno, $errstr);

        $Post = "POST /WBXService/XMLService HTTP/1.0\n";
        $Post .= "Host: $URL\n";
        $Post .= "Content-Type: application/xml\n";
        $Post .= "Content-Length: " . strlen($xml) . "\n\n";
        $Post .= "$xml\n";
        if ($fp) {
            fwrite($fp, $Post);
            $response = "";
            while (!feof($fp)) {
                $response .= fgets($fp);
            }
            fclose($fp);
            return $response;
        } else {
            echo "$errstr ($errno)<br />\n";
            return false;
        }
    }
    
    /**
     * @function list host
     */
    public function list_host($page=''){
        $this->template->title = 'List of WebEx User';
        $offset = 0;
        $per_page = 5;
        $uri_segment = 4;
        $pagination = $this->common_function->create_link_pagination($page, $offset, site_url('partner/webex/list_host'), count($this->webex_host_model->get_host_per_partner()), $per_page, $uri_segment);
        $this->user_model->select('id, email, role_id, status')->where('status', 'disable')->order_by('dcrea', 'desc')->get_all();
        
        $vars = Array(
            'webex_host' => $this->webex_host_model->get_host_per_partner($per_page, $offset),
            'pagination' => @$pagination
                
        );
//        echo('<pre>');
//        print_r($vars); exit;
        $this->template->content->view('default/contents/webex/partner/list_host', $vars);
        $this->template->publish();
    }
    
    public function delete_host($host_id = ''){
        if(!$host_id){
            $this->messages->add('Invalid Action', 'warning');
            redirect('partner/webex/list_host/'); 
        }
        $webex = $this->webex_model->where('host_id', $host_id)->get_all();
        $webex_class = $this->webex_class_model->where('host_id', $host_id)->get_all();
        if($webex || $webex_class){
            $this->messages->add('This host cannot be deleted. There are '. (count($webex) + count($webex_class)) . ' session/s that using this host', 'warning');
            redirect('partner/webex/list_host/'); 
        }
        if(!$this->webex_host_model->delete($host_id)){
            $this->messages->add('Invalid Action', 'warning');
            redirect('partner/webex/list_host/'); 
        }
        
        $this->messages->add('Delete Succeded', 'success');
        redirect('partner/webex/list_host/'); 
    }
    
    public function register_host_to_live(){
        $XML_SITE = $this->input->post('subdomain_webex') . ".webex.com";
        $XML_PORT = "443";

        $rules = array(
            array('field'=>'webex_id', 'label' => 'WebEx ID', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'password', 'label' => 'Password', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'subdomain_webex', 'label' => 'Subdomain WebEx', 'rules'=>'trim|required|xss_clean'),
            array('field'=>'partner_id', 'label' => 'Partner ID', 'rules'=>'trim|required|xss_clean')
        );

        if (!$this->common_function->run_validation($rules)) {
            $this->messages->add(validation_errors(), 'warning');
            $this->login();
            return;
        }
        
        // Set calling user information
        $d["UID"] = $this->input->post('webex_id'); // WebEx username
        $d["PWD"] = $this->input->post('password'); // WebEx password
        $d["SNM"] = $this->input->post('subdomain_webex'); //Demo Site SiteName
        $d["PID"] = $this->input->post('partner_id'); //Demo Site PartnerID

        $d["XML"] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <serv:message xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
                <header>
                    <securityContext>
                        <webExID>{$d["UID"]}</webExID>
                        <password>{$d["PWD"]}</password>
                        <siteName>{$d["SNM"]}</siteName>
                        <partnerID>{$d["PID"]}</partnerID>
                    </securityContext>
                </header>
                <body>
                    <bodyContent xsi:type=\"java:com.webex.service.binding.user.GetUser\">
                        <webExId>{$d["UID"]}</webExId>
                    </bodyContent>
                </body>
                </serv:message>";

        $URL = "http://{$XML_SITE}/WBXService/XMLService";
        $result = $this->post_it($d, $URL, $XML_PORT);
        if(!$result){
            $this->messages->add('Incorrect Subdomain or Partner ID', 'warning');
            redirect('partner/webex/login');
        }
        // Clean xml
        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
        $simple_xml = simplexml_load_string($clean_result);
        
        if ($simple_xml === false) {
            $this->messages->add('Bad XML', 'error');
            redirect('partner/webex/login');
        } else {
            if(($simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->result == 'SUCCESS')){
                $meeting_type_arr = json_decode(json_encode($simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('use', true)->meetingTypes));
                if(is_array($meeting_type_arr->meetingType)){
                    $highest_meeting_type_id = end($meeting_type_arr->meetingType);
                }else{
                    $highest_meeting_type_id = $meeting_type_arr->meetingType;
                }
                
                $list_meeting_type = $this->webex_function->get_list_meeting_type($highest_meeting_type_id, $d["UID"], $d["PWD"], $d["SNM"], $d["PID"]);
                $meeting_center = $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('use', true)->supportedServices->children('use', true)->meetingCenter;
                $training_center = $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('use', true)->supportedServices->children('use', true)->trainingCenter;
                $voip = $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('use', true)->privilege->children('use', true)->voiceOverIp;
                
                $voip = json_decode(json_encode($voip), true);
                if($voip[0] == "true"){
                    $voip = TRUE;
                }else{
                    $voip = FALSE;
                }
                if($training_center){
                    $highest_meeting_type = "TRAINING";
                }else if($meeting_center){
                    $highest_meeting_type = "MEETING";
                }else{
                    $highest_meeting_type = "NOT SUPPORTED";
                }
                
                if($highest_meeting_type == "NOT SUPPORTED"){
                    $this->messages->add('Your account Webex not supported to Dyned Live', 'error');
                    redirect('partner/webex/login');
                }
                
                $vars = Array(
                    'subdomain_webex' => $d["SNM"],
                    'user_id' => $this->auth_manager->userid(),
                    'webex_id' => $d["UID"],
                    'partner_id' => $d["PID"],
                    'password' => $d["PWD"],
                    'highest_meeting_type_id' => $highest_meeting_type_id,
                    'highest_meeting_type' => $highest_meeting_type,
                    'voip' => $voip,
                    'max_user' => $list_meeting_type['max_user'],
                    'max_duration' => $list_meeting_type['max_duration']
                );
                $webex_exsist = $this->webex_host_model->select('webex_id')->where(Array('subdomain_webex' => $d["SNM"], 'webex_id' => $d["UID"]))->get();
                
                if(!$webex_exsist){
                    if($this->webex_host_model->insert($vars)){
                        $this->messages->add('Host has been registered successfully', 'success');
                        redirect('partner/webex/login');
                    }  else {
                        $this->messages->add(validation_errors(), 'error');
                        $this->messages->add('Host failed registered', 'error');
                        redirect('partner/webex/login');
                    }
                }else{
                    $this->messages->add('Host had been registered', 'warning');
                    redirect('partner/webex/login');
                }
            }else{
                $this->messages->add('Invalid Password or Webex ID', 'error');
                redirect('partner/webex/login');
            }
        }
    }
    
    private function get_meeting_type($meeting_type_id=''){
        $XML_SITE = $this->input->post('subdomain_webex') . ".webex.com";
        $XML_PORT = "443";

        // Set calling user information
        $d["UID"] = $this->input->post('webex_id'); // WebEx username
        $d["PWD"] = $this->input->post('password'); // WebEx password
        $d["SNM"] = $this->input->post('subdomain_webex'); //Demo Site SiteName
        $d["PID"] = $this->input->post('partner_id'); //Demo Site PartnerID

        $d["XML"] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <serv:message xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
                <header>
                    <securityContext>
                        <webExID>{$d["UID"]}</webExID>
                        <password>{$d["PWD"]}</password>
                        <siteName>{$d["SNM"]}</siteName>
                        <partnerID>{$d["PID"]}</partnerID>
                    </securityContext>
                </header>
                <body>
                    <bodyContent xsi:type=\"java:com.webex.service.binding.meetingtype.LstMeetingType\">
                        <meetingTypeID>{$meeting_type_id}</meetingTypeID>
                    </bodyContent>
                </body>
                </serv:message>";

        $URL = "http://{$XML_SITE}/WBXService/XMLService";
        $result = $this->post_it($d, $URL, $XML_PORT);
        
        // Clean xml
        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
        $simple_xml = simplexml_load_string($clean_result);
        
        if ($simple_xml === false) {
            die('Bad XML!');
        } else {
            // not done yet
            if(($simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->result == 'SUCCESS')){
                
            }else{
                echo "Error"; exit;
            }
        }
    }
}

/* End of file webex.php */
/* Location: ./application/controllers/webex.php */