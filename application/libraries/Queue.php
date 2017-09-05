<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

/**
 * @name		CodeIgniter Message Queue Library using Beanstalkd
 * @author		Jogi Silalahi
 * @link		http://jogisilalahi.com
 *
 * This message queue library is a wrapper CodeIgniter library using Beanstalkd
 */

require_once __DIR__ . '/../third_party/beanstalkd/vendor/autoload.php';

use Pheanstalk\Pheanstalk;

class Queue {

    /**
     * Confirguration
     * Default configuration intialized from queue config file
     */
    var $host = '';
    var $port = '';

    var $priority = '';

    /**
     * Connection
     */
    var $pheanstalk = null;



    /**
     * Constructor with configuration array
     *
     * @param array $config
     */
    public function __construct($config=array()) {

        // Configuration
        if ( ! empty($config) ) {
            $this->initialize($config);
        }

        // Connecting to message server
        // $this->pheanstalk = new Pheanstalk($this->host, $this->port);
        $this->pheanstalk = new Pheanstalk('127.0.0.1');

    }

    /**
     * Initialize with configuration array
     *
     * @param array $config
     */
    public function initialize($config=array()) {
        foreach ($config as $key=>$value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Queuing new message
     *
     * @param string $tube
     * @param array $payload
     * @param steing $route
     */
    public function push($tube, $payload=array(), $route) {
        
        $payload['route'] = $route;
        $payload = json_encode($payload);

        $this->pheanstalk->useTube($tube)->put($payload);

    }


    /**
     * Queuing scheduled message
     *
     * @param integer $delay
     * @param array $tube
     * @param array $payload
     * @param string $route
     */
    public function later($delay, $tube, $payload=array(), $route) {

        $payload['route'] = $route;
        $payload = json_encode($payload);

        $this->pheanstalk->useTube($tube)->put($payload, $this->priority, $delay);

    }


}

/* End of file queue.php */
/* Location: ./application/libraries/queue.php */