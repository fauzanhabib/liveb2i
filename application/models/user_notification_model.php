<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class user_notification_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class user_notification_model extends MY_Model {

	// Table name in database
	var $table = 'user_notifications';

	// Validation rules
	var $validate = array(
			);
        
        
        // update all notifications status from 2 to 1. 2 means the notification never been opened before
        function update_status1(){
            $data = array(
                    'status' => 1,
            );
            $this->db->where('status', 2);
            if($this->db->update('user_notifications', $data, 'user_id = '.$this->auth_manager->userid())){
                return true;
            }
            else{
                return false;
            }
        }
        
        // update all notifications status from 1 to 0. 1 means the notification has been opened before but when the specific notification opened, the status will change to 0. 0 means the notification has been read
        function update_status2($id){
            $data = array(
                    'status' => 0,
            );
            $this->db->where('user_id', $this->auth_manager->userid());
            if($this->db->update('user_notifications', $data, 'id = '.$id)){
                return true;
            }
            else{
                return false;
            }
        }
          
}
/* End of file user_notification_model.php */
/* Location: ./application/models/user_notification_model.php */
