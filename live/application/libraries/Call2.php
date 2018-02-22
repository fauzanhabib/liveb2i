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

		$call2_api_url = "https://" . $server . ".records.dyned.com/api/v1/call2/";
		// $call2_api_url = getenv("CALL2_URL");

        $this->host = $call2_api_url . $email ."/summary";
        $conn = curl_init($this->host);
        curl_setopt(
            $conn,
            CURLOPT_USERPWD,
            getenv("CALL2_BASIC_AUTH_USER") . ":" . getenv("CALL2_BASIC_AUTH_PASSWORD")
        );
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
