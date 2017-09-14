<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Queue Server Configuration
| -------------------------------------------------------------------------
| This determine your message broker (server) configuration
|
*/

//$config['host'] = '127.0.0.1';
//// $config['port'] = '11300';
//$config['port'] = '80';


$config['host'] = getenv("QUEUE_HOST");
$config['port'] = getenv("QUEUE_PORT");

$config['priority'] = 1024;


/* End of file queue.php */
/* Location: ./application/config/queue.php */