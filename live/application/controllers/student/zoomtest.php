<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Zoomtest extends MY_Site_Controller {
    
    private $api_key = '0l33tM3BTHmK6UdiO8ttAw';
    private $api_secret = '5rUgf4XtJ9QgL6gzAupbgt4OcQFgRLN7trk7';
    private $api_url = 'https://api.zoom.us/v1/';
    private $host_id = 'jjban5hSR1SFZj1GDKM36w';

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->library('Auth_manager');
        // Checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD') {
            $this->messages->add('ERROR');
            redirect('home');
        }
    }
    
    public function index($page=''){
        $this->template->title = 'Zoom Test';
        
        // $this->createAMeeting();
        // exit();
        // echo "<pre>";
        // print_r($vars);
        // exit();
        // exit();
        
        $this->template->content->view('default/contents/student/zoomtest/index');
        $this->template->publish();
    }

    public function sendRequest($calledFunction, $data){
        /*Creates the endpoint URL*/
        $request_url = $this->api_url.$calledFunction;

        /*Adds the Key, Secret, & Datatype to the passed array*/
        $data['api_key'] = $this->api_key;
        $data['api_secret'] = $this->api_secret;
        $data['data_type'] = 'JSON';

        $postFields = http_build_query($data);
        /*Check to see queried fields*/
        /*Used for troubleshooting/debugging*/
        // echo $postFields;

        /*Preparing Query...*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);

        /*Check for any errors*/
        $errorMessage = curl_exec($ch);
        // echo $errorMessage;
        curl_close($ch);
        // echo "<pre>";
        /*Will print back the response from the call*/
        /*Used for troubleshooting/debugging        */
        // echo $request_url;
        // print_r($data);
        if(!$response){
            return false;
        }
        /*Return the data in JSON format*/
        $jsonval = $response;
        $jsonval = json_decode($jsonval, true);
        // echo "<pre>";
        // print_r($jsonval);
        // echo "<pre>";print_r($jsonval);exit();
        if(@$calledFunction == "meeting/create"){
	        $start_url  = @$jsonval['join_url'];
          $start_date = @$data['start_date'];
          $start_time = @$data['start_time'];
          // $end_time   = @$data['end_time'];
	        // $end_time   = @$data['end_time'];
	        $duration   = @$jsonval['duration'];
          $status     = @$jsonval['status'];
          $meeting_id = @$jsonval['id'];
	        $host_id = @$jsonval['host_id'];
	        
          $std_id     = $this->auth_manager->userid();
          $cch_id     = 2023;

          $getstd = $this->db->select('fullname')
                  ->from('user_profiles')
                  ->where('user_id', $std_id)
                  ->get()->result();
          $getcch = $this->db->select('fullname')
                  ->from('user_profiles')
                  ->where('user_id', $cch_id)
                  ->get()->result();

	        $std_name   = @$getstd[0]->fullname;
	        $cch_name   = @$getcch[0]->fullname;

	        $std_url    = @$start_url.'?uname='.str_replace(' ', '%20', @$std_name);
	        $cch_url    = @$start_url.'?uname='.str_replace(' ', '%20', @$cch_name);

	        $vars = array(
            'std_id' => $std_id,
            'cch_id' => $cch_id,
	          'start_url' => $start_url,
            'date' => $start_date,
            'start_time' => $start_time,
            'meeting_id' => $meeting_id,
            'host_id' => $host_id,
	          // 'end_time' => $end_time,
            // 'duration' => 25,
	          'duration' => $duration,
	          'status' => $status,
	          'std_url' => $std_url,
	          'cch_url' => $cch_url
	        );
          // echo "<pre>";
          // print_r($vars);
          // exit();

	        $this->template->content->view('default/contents/student/zoomtest/success', $vars);
	        $this->template->publish();
	    }else if(@$calledFunction == "meeting/list"){
	    	echo "<pre>";
     		print_r($jsonval);
      	exit();
	    }else{
        echo "<pre>";
        print_r($jsonval);
        print_r($calledFunction);
        exit();
      }
    }

    /*Functions for management of users*/
    function createAUser(){     
        $createAUserArray = array();
        $createAUserArray['email'] = $_POST['userEmail'];
        $createAUserArray['type'] = $_POST['userType'];
        return $this->sendRequest('user/create', $createAUserArray);
    }   
    function autoCreateAUser(){
      $autoCreateAUserArray = array();
      $autoCreateAUserArray['email'] = $_POST['userEmail'];
      $autoCreateAUserArray['type'] = $_POST['userType'];
      $autoCreateAUserArray['password'] = $_POST['userPassword'];
      return $this->sendRequest('user/autocreate', $autoCreateAUserArray);
    }
    function custCreateAUser(){
      $custCreateAUserArray = array();
      $custCreateAUserArray['email'] = $_POST['userEmail'];
      $custCreateAUserArray['type'] = $_POST['userType'];
      return $this->sendRequest('user/custcreate', $custCreateAUserArray);
    }  
    function deleteAUser(){
      $deleteAUserArray = array();
      $deleteAUserArray['id'] = $_POST['userId'];
      return $this->sendRequest('user/delete', $deleteUserArray);
    }     
    function listUsers(){
      $listUsersArray = array();
      return $this->sendRequest('user/list', $listUsersArray);
    }   
    function listPendingUsers(){
      $listPendingUsersArray = array();
      return $this->sendRequest('user/pending', $listPendingUsersArray);
    }    
    function getUserInfo(){
      $getUserInfoArray = array();
      $getUserInfoArray['id'] = $_POST['userId'];
      return $this->sendRequest('user/get',$getUserInfoArray);
    }   
    function getUserInfoByEmail(){
      $getUserInfoByEmailArray = array();
      $getUserInfoByEmailArray['email'] = $_POST['userEmail'];
      $getUserInfoByEmailArray['login_type'] = $_POST['userLoginType'];
      return $this->sendRequest('user/getbyemail',$getUserInfoByEmailArray);
    }  
    function updateUserInfo(){
      $updateUserInfoArray = array();
      $updateUserInfoArray['id'] = $_POST['userId'];
      return $this->sendRequest('user/update',$updateUserInfoArray);
    }  
    function updateUserPassword(){
      $updateUserPasswordArray = array();
      $updateUserPasswordArray['id'] = $_POST['userId'];
      $updateUserPasswordArray['password'] = $_POST['userNewPassword'];
      return $this->sendRequest('user/updatepassword', $updateUserPasswordArray);
    }      
    function setUserAssistant(){
      $setUserAssistantArray = array();
      $setUserAssistantArray['id'] = $_POST['userId'];
      $setUserAssistantArray['host_email'] = $_POST['userEmail'];
      $setUserAssistantArray['assistant_email'] = $_POST['assistantEmail'];
      return $this->sendRequest('user/assistant/set', $setUserAssistantArray);
    }     
    function deleteUserAssistant(){
      $deleteUserAssistantArray = array();
      $deleteUserAssistantArray['id'] = $_POST['userId'];
      $deleteUserAssistantArray['host_email'] = $_POST['userEmail'];
      $deleteUserAssistantArray['assistant_email'] = $_POST['assistantEmail'];
      return $this->sendRequest('user/assistant/delete',$deleteUserAssistantArray);
    }   
    function revokeSSOToken(){
      $revokeSSOTokenArray = array();
      $revokeSSOTokenArray['id'] = $_POST['userId'];
      $revokeSSOTokenArray['email'] = $_POST['userEmail'];
      return $this->sendRequest('user/revoketoken', $revokeSSOTokenArray);
    }      
    function deleteUserPermanently(){
      $deleteUserPermanentlyArray = array();
      $deleteUserPermanentlyArray['id'] = $_POST['userId'];
      $deleteUserPermanentlyArray['email'] = $_POST['userEmail'];
      return $this->sendRequest('user/permanentdelete', $deleteUserPermanentlyArray);
    }               
    /*Functions for management of meetings*/
    function createAMeeting(){
      $createAMeetingArray = array();
      $createAMeetingArray['host_id'] = $this->host_id;
      $createAMeetingArray['topic'] = 'test';
      $createAMeetingArray['type'] = 2;
      $createAMeetingArray['option_jbh'] = 'true';

      $createAMeetingArray['duration'] = $_POST['duration'];
      $createAMeetingArray['start_date'] = $_POST['start_date'];
      $createAMeetingArray['start_time'] = $_POST['start_time'];
      $createAMeetingArray['std_name'] = $_POST['std_name'];
      $createAMeetingArray['cch_name'] = $_POST['cch_name'];
      $createAMeetingArray['timezone'] = 'Etc/Greenwich';
      return $this->sendRequest('meeting/create', $createAMeetingArray);
    }
    function deleteAMeeting(){
      $deleteAMeetingArray = array();
      $deleteAMeetingArray['id'] = $_POST['meetingId'];
      $deleteAMeetingArray['host_id'] = $_POST['userId'];
      return $this->sendRequest('meeting/delete', $deleteAMeetingArray);
    }
    function listMeetings(){
      $listMeetingsArray = array();
      $listMeetingsArray['host_id'] = $this->host_id;
      return $this->sendRequest('meeting/list',$listMeetingsArray);
    }
    function getMeetingInfo(){
      $getMeetingInfoArray = array();
      $getMeetingInfoArray['id'] = $_POST['meetingId'];
      $getMeetingInfoArray['host_id'] = $_POST['userId'];
      return $this->sendRequest('meeting/get', $getMeetingInfoArray);
    }
    function updateMeetingInfo(){
      $updateMeetingInfoArray = array();
      $updateMeetingInfoArray['id'] = $_POST['meetingId'];
      $updateMeetingInfoArray['host_id'] = $_POST['userId'];
      return $this->sendRequest('meeting/update', $updateMeetingInfoArray);
    }
    function endAMeeting(){
      $endAMeetingArray = array();
      $endAMeetingArray['id'] = $_POST['meetingId'];
      $endAMeetingArray['host_id'] = $_POST['userId'];
      return $this->sendRequest('meeting/end', $endAMeetingArray);
    }
    function listRecording(){
      $listRecordingArray = array();
      $listRecordingArray['host_id'] = $_POST['userId'];
      return $this->sendRequest('recording/list', $listRecordingArray);
    }
    /*Functions for management of reports*/
    function getDailyReport(){
      $getDailyReportArray = array();
      $getDailyReportArray['year'] = $_POST['year'];
      $getDailyReportArray['month'] = $_POST['month'];
      return $this->sendRequest('report/getdailyreport', $getDailyReportArray);
    }
    function getAccountReport(){
      $getAccountReportArray = array();
      $getAccountReportArray['from'] = $_POST['from'];
      $getAccountReportArray['to'] = $_POST['to'];
      return $this->sendRequest('report/getaccountreport', $getAccountReportArray);
    }
    function getUserReport(){
      $getUserReportArray = array();
      $getUserReportArray['user_id'] = $_POST['userId'];
      $getUserReportArray['from'] = $_POST['from'];
      $getUserReportArray['to'] = $_POST['to'];
      return $this->sendRequest('report/getuserreport', $getUserReportArray);
    }
    /*Functions for management of webinars*/
    function createAWebinar(){
      $createAWebinarArray = array();
      $createAWebinarArray['host_id'] = $_POST['userId'];
      $createAWebinarArray['topic'] = $_POST['topic'];
      return $this->sendRequest('webinar/create',$createAWebinarArray);
    }
    function deleteAWebinar(){
      $deleteAWebinarArray = array();
      $deleteAWebinarArray['id'] = $_POST['webinarId'];
      $deleteAWebinarArray['host_id'] = $_POST['userId'];
      return $this->sendRequest('webinar/delete',$deleteAWebinarArray);
    }
    function listWebinars(){
      $listWebinarsArray = array();
      $listWebinarsArray['host_id'] = $_POST['userId'];
      return $this->sendRequest('webinar/list',$listWebinarsArray);
    }
    function getWebinarInfo(){
      $getWebinarInfoArray = array();
      $getWebinarInfoArray['id'] = $_POST['webinarId'];
      $getWebinarInfoArray['host_id'] = $_POST['userId'];
      return $this->sendRequest('webinar/get',$getWebinarInfoArray);
    }
    function updateWebinarInfo(){
      $updateWebinarInfoArray = array();
      $updateWebinarInfoArray['id'] = $_POST['webinarId'];
      $updateWebinarInfoArray['host_id'] = $_POST['userId'];
      return $this->sendRequest('webinar/update',$updateWebinarInfoArray);
    }
    function endAWebinar(){
      $endAWebinarArray = array();
      $endAWebinarArray['id'] = $_POST['webinarId'];
      $endAWebinarArray['host_id'] = $_POST['userId'];
      return $this->sendRequest('webinar/end',$endAWebinarArray);
    }

}
