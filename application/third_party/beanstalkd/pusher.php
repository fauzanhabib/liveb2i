<?php

require_once __DIR__ . '/vendor/autoload.php';

use Pheanstalk\Pheanstalk;

$pheanstalk = new Pheanstalk('162.243.138.127', 11300);

$pheanstalk->useTube('test')->put('GE unlocked!');

?>
