<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class user_role_model
 * @author      Apriwin Pangaribuan <apriwin@pistarlabs.com>
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 */
class coach_token_cost_model extends MY_Model {

	// Table name in database
	var $table = 'coach_token_costs';

	// Validation rules
	var $validate = array(
                            // array('field'=>'token_for_student', 'label' => 'Token cost for student', 'rules'=>'trim|required|xss_clean'),
                            //array('field'=>'token_for_group', 'label' => 'Token cost for group', 'rules'=>'trim|required|xss_clean'),
			);
          
}
/* End of file coach_token_cost_model.php */
/* Location: ./application/models/coach_token_cost_model.php */
