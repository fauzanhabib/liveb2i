<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class notification extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('user_notification_model');
    }

    // Index
    public function index() {
        //echo($this->auth_manager->new_notification()['notification']); 
        if(@$this->auth_manager->new_notification()['notification'] >0){
            $this->user_notification_model->update_status1();
        } //exit;
        
        $data = $this->user_notification_model->select('id, description, status, dcrea')->where('user_id', $this->auth_manager->userid())->order_by('dcrea', 'desc')->get_all();
        $received_time = array();
        foreach($data as $d){
            $received_time[$d->id] =  $this->human_timing(date('Y-m-d H:i:s' ,$d->dcrea));
        }
        
        
        $vars = array(
            'title' => 'Your Notifications',
            'data' => $data,
            'received_time' => $received_time,
        );
        
        // echo('<pre>');
        // print_r($vars); exit;
        $this->template->title = "Notification";
        $this->template->content->view('default/contents/notification/index', $vars);
        $this->template->publish();
    }
    /**
    * function human_timing
    * Converting time to human timing
    *
    * @param (string)session_time ('Y-m-d H:i:s')
    */
    function human_timing($session_time = '') {
       if (DateTime::createFromFormat('Y-m-d H:i:s', trim($session_time)) != FALSE) {
           $time = time() - strtotime($session_time);
           $tokens = array(
               31536000 => 'year',
               2592000 => 'month',
               604800 => 'week',
               86400 => 'day',
               3600 => 'hour',
               60 => 'minute',
               1 => 'second'
           );

           foreach ($tokens as $unit => $text) {
               if ($time < $unit) {
                   continue;
               }
               $numberOfUnits = floor($time / $unit);
               if(trim($numberOfUnits.$text) == '1second'){
                   return "Just now";
               }else{
                   return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . ' ago';
               }
           }
       }
       else{
           return FALSE;
       }
   }

   public function ajax_update(){
      $id_post = $this->input->post("id");

      $upd_notif = array(
            'status' => 1
        );
        
        $this->db->where('user_id', $id_post);
        $this->db->update('user_notifications', $upd_notif);

   }
    
}