<?php 
/**
 * Class    : Call2.php
 * Author   : Tobby Sembiring (tobby@pistarlabs.co.id)
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Call2 {

    private $host;
    private $params;
	
    public $dataJson;
    public $dataObj;

    public function __construct() {}

    public function init($server, $email){
		
        $this->host = "https://" . $server . ".records.dyned.com/api/v1/call2/". $email ."/summary";
        $conn = curl_init($this->host);
        curl_setopt($conn, CURLOPT_USERPWD, "no9o78ghk24do87:3CF9A167-3D3C-4445-B86B-9C70F6EF8093");
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
        $this->dataJson = curl_exec($conn);
        $this->dataObj = json_decode($this->dataJson);

        curl_close($conn); 
    }
    
    public function getDataJson() {
        return $this->dataJson;
    }

    public function getdataObj() {
        return $this->dataObj;
    }

}

//$call2 = new Call2();
//$call2->init("site11", "sutomo@dyned.com");
//echo header("Content-Type:application/json");
//print_r($call2->dataJson);
?>