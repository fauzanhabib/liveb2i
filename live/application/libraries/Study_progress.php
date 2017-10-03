<?php
class Study_progress {
    var $gcp;
    var $gsp;
    var $gwp;

    // var $tokenresult;
    function __construct() {}

    public function GenerateToken(){
      $useraccount = json_encode(array(
          'username'=>'tobbysembiring@gmail.com',
          'password'=>'password'
      ));
      // Preparing API URL
      $rt = curl_init();
      curl_setopt_array($rt, array(
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_URL => 'https://b2ctest.dyned.com/api/v1/jwt/token-request',
          CURLOPT_HTTPHEADER => array(
            'Content-Type' => 'application/json'),
          CURLOPT_POSTFIELDS => $useraccount
      ));
      $tokenconnect = curl_exec($rt) ;
      $pulltr = json_decode($tokenconnect);
      $tokenresult = @$pulltr->token;
      return $tokenresult;
    }

    public function GetCurrentProgress($tokenresult) {
        // $useraccount = json_encode(array(
        //     'username'=>'tobbysembiring@gmail.com',
        //     'password'=>'password'
        // ));
        // // Preparing API URL
        // $rt = curl_init();
        // curl_setopt_array($rt, array(
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_URL => 'https://b2ctest.dyned.com/api/v1/jwt/token-request',
        //     CURLOPT_HTTPHEADER => array(
        //       'Content-Type' => 'application/json'),
        //     CURLOPT_POSTFIELDS => $useraccount
        // ));
        // $tokenconnect = curl_exec($rt) ;
        // $pulltr = json_decode($tokenconnect);
        // // $tokenresult = $pulltr->token;
        // $tokenresult = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE1MDQ4NzQ3NjcsImV4cGlyYXRpb24iOjE1MDQ4NzQ3NjcsInVzZXJuYW1lIjoidG9iYnlzZW1iaXJpbmdAZ21haWwuY29tIiwidXVpZCI6IjI3NzIxNjAwNjYwMjU1NTM5MyJ9.nQPQpc3mkivgFKgbUBB9lCyQKtJeqmC73O_zZqe7TcvJUw7szuZiyoMpg5kG4QHpi5zSoy4xxPjwPmyWqJIv1e3rO_cBGaA4DepYi9BO-9nBtLCyjejGCKfHO-DUl0RAvzLfJxjg-dxvGrZj5N028FGysagLLRyaggM2LTZcnAi-qH58Rd-4GgBSeWhyLloHX6REzyiQv9xSMGF8RBJNL1iwfCYCcVcZHNIwCd-hR_ktpBxL9PqiUzSLxwhQxBud8mE-t68XspfhA-tu42Y2SSXXe1z2_GEraTvRr3tVqVWZ3ulvUpEGFjGjC_MRY0R6kTVu6VFiwk4qaVmph8EhRw';
        // echo('<pre>');print_r($asdf->token); exit;
        // $data_string = json_encode($data);
        // $url = "https://b2ctest.dyned.com/api/v1/dsa/v1/study-record/tobbysembiring@gmail.com/current";
        // $curl = curl_init($url);
        //
        // curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        //
        // curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        // 'Content-Type: application/json',
        // 'Content-Length: ' . strlen($data_string))
        // );
        //
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // Make it so the data coming back is put into a string
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);  // Insert the data
        //
        // // Send the request
        // $result = curl_exec($curl);
        //
        // // Free up the resources $curl is using
        // curl_close($curl);

        $ch = curl_init('https://b2ctest.dyned.com/api/v1/dsa/v1/study-record/tobbysembiring@gmail.com/current');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // true or false
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2 );
        curl_setopt($ch, CURLOPT_POST,0);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        	'Content-Type: application/json',
          'X-Dyned-Tkn: ' . $tokenresult)
        );

        $gcp = curl_exec($ch);
        curl_close($ch);
        // $this->gcp = json_decode($gcp);
        return $gcp;
        // $gcp = curl_exec($ch);
        // curl_close($ch);
        //
        // $this->gcp = json_decode($this->gcp);
        // return $this->gcp;
        // echo $result;
    }
    public function GetStudyProgress($tokenresult) {
        $ch = curl_init('https://b2ctest.dyned.com/api/v1/dsa/v1/study-record/tobbysembiring@gmail.com/progress');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // true or false
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2 );
        curl_setopt($ch, CURLOPT_POST,0);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        	'Content-Type: application/json',
          'X-Dyned-Tkn: ' . $tokenresult)
        );

        $resp = curl_exec($ch);

        // close the connection, release resources used
        curl_close($ch);

        return $resp;

        // echo $result;
    }
    public function GetWeeklyProgress($tokenresult) {
        $ch = curl_init('https://b2ctest.dyned.com/api/v1/dsa/v1/study-record/tobbysembiring@gmail.com');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // true or false
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2 );
        curl_setopt($ch, CURLOPT_POST,0);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        	'Content-Type: application/json',
          'X-Dyned-Tkn: ' . $tokenresult)
        );

        $resp = curl_exec($ch);

        // close the connection, release resources used
        curl_close($ch);

        return $resp;

        // echo $result;
    }
}


?>
