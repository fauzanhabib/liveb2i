<?php
require_once __DIR__ . './../vendor/autoload.php';

// Hopefully you're using Composer autoloading.

use Pheanstalk\Pheanstalk;

$pheanstalk = new Pheanstalk('127.0.0.1');

// ----------------------------------------
// producer (queues jobs)
$data = array(
    'subject' => "Account Confirmation",
    'body' => "Account Confirmation"
);
$data = json_encode($data);

$pheanstalk
  ->useTube('testtube')
  ->put($data);

