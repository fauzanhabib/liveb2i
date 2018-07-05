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

    function testing(){
        $name = "culun";
        $token = 100;
        $from = "DynEd";
        $phone = 6289694695012;
        $message = "hai $name you get token $token";

 
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://api.infobip.com/sms/1/text/single",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "{ \"from\":\"$from\", \"to\":\"$phone\", \"text\":\"$message\" }",
          CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "authorization: App b6f2eb70d347a8ea437631f5defc77cc-760008e6-c2b7-4019-b43d-590c24d38905",
                    "content-type: application/json"
                  ),
                ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            //echo $response;
          // $a = json_decode($response, true);
          // //$b = $a['results'][0]['status']['name'];
          // echo "<pre>";
          // print_r($a);
          // exit();
        }

    }

    function session_reminder_student($student_phone = '', $start_hour = ''){
      $message = 'Your neo LIVE session will begin at '.$start_hour.'. Please do not be late';
      $from = 'neo';
      $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://api.infobip.com/sms/1/text/single",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "{ \"from\":\"$from\", \"to\":\"$student_phone\", \"text\":\"$message\" }",
          CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "authorization: App b6f2eb70d347a8ea437631f5defc77cc-760008e6-c2b7-4019-b43d-590c24d38905",
                    "content-type: application/json"
                  ),
                ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            //echo $response;
          // $a = json_decode($response, true);
          // //$b = $a['results'][0]['status']['name'];
          // echo "<pre>";
          // print_r($a);
          // exit();
        }
    }

    function session_reminder_coach($coach_phone = '', $start_hour_coach = ''){
      $message = 'Your neo LIVE session will begin at '.$start_hour_coach.'. Please do not be late';
      $from = 'neo';
      $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://api.infobip.com/sms/1/text/single",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "{ \"from\":\"$from\", \"to\":\"$coach_phone\", \"text\":\"$message\" }",
          CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "authorization: App b6f2eb70d347a8ea437631f5defc77cc-760008e6-c2b7-4019-b43d-590c24d38905",
                    "content-type: application/json"
                  ),
                ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            //echo $response;
          // $a = json_decode($response, true);
          // //$b = $a['results'][0]['status']['name'];
          // echo "<pre>";
          // print_r($a);
          // exit();
        }
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