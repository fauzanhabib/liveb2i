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
				echo '<div class="alert alert-dismissable alert-'. $type .'">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						'. $message .'
					</div>';
			}
		}
	}
}

/* End of file messages_widget.php */
/* Location: ./application/widgets/messages_widget.php */