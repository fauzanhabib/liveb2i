<?php

require_once __DIR__ . './../vendor/autoload.php';

// Hopefully you're using Composer autoloading.

use Pheanstalk\Pheanstalk;

$pheanstalk = new Pheanstalk('127.0.0.1');

// ----------------------------------------
// producer (queues jobs)

//$pheanstalk
//  ->useTube('testtube')
//  ->put("job payload goes here\n");

// ----------------------------------------
// worker (performs jobs)
while(1){
    $job = $pheanstalk
      ->watch('testtube')
      ->ignore('default')
      ->reserve();

    $data = json_decode($job->getData());
    
    //echo $job->getData();
    print_r($data);

    
    $pheanstalk->delete($job);    
}


// ----------------------------------------
// check server availability

echo $pheanstalk->getConnection()->isServiceListening() . "\n"; // true or false
