<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Messages Widget
 * @author 		Jogi Silalahi <jogi@pistarlabs.com>
 * @version 	0.0.1
 */
class messages_widget_login extends Widget {
	public function display($args = array()) 
	{
		$all = $this->messages->get();
		foreach($all as $type=>$messages) {
			foreach($messages as $message) {
                            echo "<p class='error'><i class='icon icon-warning' style='top: 2px;position: relative;'></i> $message</p>";
			}
		}
	}
    
}

/* End of file messages_widget_login.php */
/* Location: ./application/widgets/messages_widget_login.php */