<?php

if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

require 'vendor/autoload.php';

use Smsglobal\RestApiClient\ApiKey;
use Smsglobal\RestApiClient\Resource\Sms;
use Smsglobal\RestApiClient\RestApiClient;

/**
 * Class email_structure
 * Class library for common functions like generate random string, gender, etc
 * @author      Ponel Panjaitan <ponel@pistarlabs.co.id>
 */
class send_sms {

	var $tube = 'com.live.sms';
    /**
     * var $ci
     * CodeIgniter Instance
     */
    private $CI;
    
    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->library('queue');
    }

    function testing($phone = '', $fullname = '', $email = ''){

    	$apiKey = new ApiKey('965c4ede83eddde2f255160362bc45e6', '6efb9c02479036523b61effb6721d1a6');

    	$rest = new RestApiClient($apiKey);

    	$sms = new Sms();

    	$testing = array(
            'apiKey' => $apiKey,
            'rest' => $rest,
            'sms' => $sms,
        );
		$sms->setDestination($phone)
		    ->setOrigin('DynEd')
		    ->setMessage('Hi '.$fullname.', you have been created with email '.$email);
		// And save them
		$rest->save($sms);
		// When a new object is saved, the ID gets populated (it was null before)
		//echo $sms->getId(); // integer

		$this->CI->queue->push($this->tube, $testing, 'sms.send_sms');
	}

    function student_reminder($phone = '', $fullname = '', $coach = '', $date = '', $start = '', $end = ''){

        $apiKey = new ApiKey('965c4ede83eddde2f255160362bc45e6', '6efb9c02479036523b61effb6721d1a6');

        $rest = new RestApiClient($apiKey);

        $sms = new Sms();

        $student_reminder = array(
            'apiKey' => $apiKey,
            'rest' => $rest,
            'sms' => $sms,
        );
        $sms->setDestination($phone)
            ->setOrigin('DynEd')
            ->setMessage('Hi '.$fullname.', you have live session booked with '.$coach.' on '.$date.' from '.$start.' to '.$end.' .Please remember!');
        // And save them
        $rest->save($sms);
        // When a new object is saved, the ID gets populated (it was null before)
        //echo $sms->getId(); // integer

        $this->CI->queue->push($this->tube, $student_reminder, 'sms.send_sms');
    }

    function coach_reminder($phone = '', $fullname = '', $coach = '', $date = '', $start = '', $end = ''){

        $apiKey = new ApiKey('965c4ede83eddde2f255160362bc45e6', '6efb9c02479036523b61effb6721d1a6');

        $rest = new RestApiClient($apiKey);

        $sms = new Sms();

        $coach_reminder = array(
            'apiKey' => $apiKey,
            'rest' => $rest,
            'sms' => $sms,
        );
        $sms->setDestination($phone)
            ->setOrigin('DynEd')
            ->setMessage('Hi '.$coach.', you have live session booked with '.$fullname.' on '.$date.' from '.$start.' to '.$end.' .Please remember!');
        // And save them
        $rest->save($sms);
        // When a new object is saved, the ID gets populated (it was null before)
        //echo $sms->getId(); // integer

        $this->CI->queue->push($this->tube, $coach_reminder, 'sms.send_sms');
    }
}

?>