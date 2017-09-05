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
                            "<div class='alert $type'>
                                <div class='pure-g'>
                                    <div class='pure-u-1-2'>
                                        <h3>$type</h3>
                                        <p>$message</p>
                                    </div>
                                <div class='pure-u-1-2 close'>
                                    <i class='icon icon-close btn-close'></i>
                                </div>
                                </div>
                            </div>";
			}
		}
	}
}

/* End of file messages_widget.php */
/* Location: ./application/widgets/messages_widget.php */