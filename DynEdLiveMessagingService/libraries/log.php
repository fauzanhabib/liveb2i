<?php
/**
 * Logging Class
 *
 * @package     Core
 * @author      Jogi Silalahi <jogi@pistarlabs.com>
 * @copyright   Copyright (c) 2014 DynEd International, Inc
 * @link        http://www.dyned.com
 * @since       1.0
 */

class Log {

    /**
     * Date Format
     * TODO: Integrate with config class
     * @var string
     */
    var $date_format = "Y-m-d H:i:s";


    /**
     * Constructor
     */
    public function __construct($config=array()) {

    }

    /**
     * Writing log message
     * @param string $level
     * @param string $message
     * @return void
     */
    public function write($level='error', $message) {

        $level = strtoupper($level);

        $message = "[$level] " . date($this->date_format) . " - " . $message . PHP_EOL;
		echo $message;
    }


}

// END Log Class
/* End of file Log.php */

