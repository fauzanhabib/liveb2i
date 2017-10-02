#!/usr/bin/php
<?php
error_reporting(E_ALL);
ini_set("display_startup_errors",1);
ini_set("display_errors",1);


require_once __DIR__ . './../vendor/autoload.php';
require_once __DIR__ . './../libraries/db.php';
require_once __DIR__ . './../libraries/log.php';

use Pheanstalk\Pheanstalk;

// addition function construct
// Koneksi ke server Beanstalkd
// Dalam kasus ini, koneksi ke localhost dengan IP 127.0.0.1
$pheanstalk = new Pheanstalk('127.0.0.1');

// Tentukan ke channel / tube mana dia akan listening
$pheanstalk->watch('com.live.download');

// instansiasi database
$db = new MysqliDb('localhost', 'live', 'Pl0.uhi#', 'live');
$log = new Log();

try{

// Loop dan lakukan jobs
while($job = $pheanstalk->reserve()) {

        // Ambil data
        // Disini data masih dalam format JSON, sehingga perlu di encode menjadi$
        
	// $pheanstalk->delete($job);exit;
	$data = json_decode($job->getData());
	print_r($data);//exit;
        // Kondisional dari routing
        // Simple action berdasarkan routing
        if($data != null){
                if( $data->route == 'download.webex'){
                        $XML_SITE = $data->subdomain_webex . ".webex.com";
                        $XML_PORT = "443";

                        // Set calling user information
                        $d["XML"] = "<?xml version='1.0' encoding='UTF-8'?> <serv:message xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'  xmlns:serv='http://www.webex.com/schemas/2002/06/service'>
                        <header>
                                <securityContext>
                                        <webExID>{$data->webex_id}</webExID>
                                        <password>{$data->password}</password>
                                        <siteName>{$data->subdomain_webex}</siteName>
                                </securityContext>
                        </header>
                        <body> 
                                <bodyContent xsi:type='java:com.webex.service.binding.ep.LstRecording'>
                                        <sessionKey>{$data->session_key}</sessionKey>
                                        <hostWebExID>{$data->webex_id}</hostWebExID>
                                        <returnSessionDetails>true</returnSessionDetails>
                                </bodyContent>
                        </body>
                        </serv:message>";

                        $URL = "http://{$XML_SITE}/WBXService/XMLService";
                        $result = post_it($d, $URL, $XML_PORT);

                        // Clean xml
                        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
                        $simple_xml = simplexml_load_string($clean_result);
                        if ($simple_xml === false) {
                                $log->write("error", "Bad XML!");
                        } else {
                                if ($simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->result == 'SUCCESS') {
                                        $file_url = $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('ep', true)->recording->children('ep', TRUE)->fileURL;
                                        $stream_url = $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('ep', true)->recording->children('ep', true)->streamURL;
                                        if($file_url && $stream_url){
                                                $stream_url = json_decode(json_encode($stream_url), true);
                                                $file_url = json_decode(json_encode($file_url), true);
                                                $data_update = Array("stream_url" => $stream_url[0], "file_url" => $file_url[0]);
						print_r($data_update);
                                                $db->where('id', $data->id);
                                                if($db->update($data->table, $data_update)){
                                                        $log->write("info", "Updating table webex  succeed\n" . PHP_EOL);
							echo "Berhasil\n";
						}
                                                else{ 
                                                        $log->write("error", "Updating table webex  failed! ". $db->getLastError());
							echo "Gagal\n";
                                                }
					}
                                }else{
                                        echo "File is not ready yet or meeting is not recorded\n";
                                }
                        }
                }
        }
        // Hapus job kalau sudah selesai. Jika tidak di hapus maka jobs akan terus aktif
        $pheanstalk->delete($job);
}
}
catch(Exception $e){
	$log->write("error", "[General] Failed to get stream url");
}

function post_it($data_stream, $URL_, $port) {
        //  Strip http:// from the URL if present
        $URL__ = preg_replace("^http://^", "", $URL_);
        //  Separate into Host and URI
        $host = substr($URL__, 0, strpos($URL__, "/"));
                //  Form the request body
        $req_body = "";
        while (list($key, $val) = each($data_stream)) {
            if ($req_body) {
                $req_body.= "&";
            }
            $req_body.= $key . "=" . urlencode($val);
        }
        $xml = $data_stream['XML'];
        $URL = $host;
        $fp = @fsockopen("ssl://".$URL, $port, $errno, $errstr);

        $Post = "POST /WBXService/XMLService HTTP/1.0\n";
        $Post .= "Host: $URL\n";
        $Post .= "Content-Type: application/xml\n";
        $Post .= "Content-Length: " . strlen($xml) . "\n\n";
        $Post .= "$xml\n";
        if ($fp) {
            fwrite($fp, $Post);
            $response = "";
            while (!feof($fp)) {
                $response .= fgets($fp);
            }
            fclose($fp);
            return $response;
                    } else {
            echo "$errstr ($errno)<br />\n";
            return false;
        }
}

function send($xml_request){
        $header = array(
            "Content-Type: text/xml; charset='utf-8'",
                        "Accept: application/soap+xml, application/dime, multipart/related",
                        "User-Agent: Axis/1.0",
                        "Host: 172.16.197.134:2001",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: ''",
                        "Content-Length: 613"
        );

        $soapCURL = curl_init();
        curl_setopt($soapCURL, CURLOPT_URL, "http://nsg1wss.webex.com/nbr/services/NBRStorageService?wsdl" );
        curl_setopt($soapCURL, CURLOPT_CONNECTTIMEOUT, 100);
        curl_setopt($soapCURL, CURLOPT_TIMEOUT,        1000);
        curl_setopt($soapCURL, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($soapCURL, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($soapCURL, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($soapCURL, CURLOPT_POST,           true );
        curl_setopt($soapCURL, CURLOPT_POSTFIELDS,     $xml_request);
        curl_setopt($soapCURL, CURLOPT_HTTPHEADER,     $header);
        //Executing Curl Here.
        $result = curl_exec($soapCURL);
        if($result === false) {
          $err = 'Curl error: ' . curl_error($soapCURL);
          $result = $err;
          //echo "This is text".$err;
        }
        curl_close($soapCURL);
        return $result;
}
