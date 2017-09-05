<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class appointment_history_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class settings_model extends MY_Model {

    // Table name in database
    var $table = 'specific_settings';
    // Validation rules
    var $validate = array();

    /**
     * @desc    Get session history for student with defined time period; maximum two months 
     * @param   (int)(student_id)
     * @param   (timestamp)(date_from)
     * @param   (timestamp)(date_to)
     */

    function update_setting($id, $setting){
        $this->db->where('user_id', $id);
        $this->db->update('admin_settings', $setting); 
    }

}

/* End of file appointment_history_model.php */
/* Location: ./application/models/appointment_history_model.php */
