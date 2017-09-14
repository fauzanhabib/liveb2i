<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Messages Widget
 * @author 		Jogi Silalahi <jogi@pistarlabs.com>
 * @version 	0.0.1
 */
class messages_widget extends Widget {
	public function display($args = array()) 
	{
		$all = $this->messages->get();
		foreach($all as $type=>$messages) {
			foreach($messages as $message) {
                            echo 
                            "  <div class='dashboard__notif $type'>
                                <span>$type $message</span>
                                <i class='fa fa-times'></i>
                            </div>";
			}
		}
	}
}

/* End of file messages_widget.php */
/* Location: ./application/widgets/messages_widget.php */