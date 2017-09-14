<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Side Navigation Widget
 * @author 		Jogi Silalahi <jogi@pistarlabs.com>
 * @version 	1.0
 */
class Side_nav_widget extends Widget {
    public function display($args = array())
    {

        $role = $this->auth_manager->role();
        $this->load->view('widgets/side_nav', array('role'=>$role));

    }
}

/* End of file side_nav_widget.php */
/* Location: ./application/widgets/side_nav_widget.php */