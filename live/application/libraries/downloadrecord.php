<?php
/**
 * Call1 API
 * DynEd Pro account Call1 wrapper
 * @author Irawan Dwihastomo <idwihastomo@dyned.com>
 * @version 0.1.0
 */
class Downloadrecord {

    public function init($apiKey, $secret) {
      // echo $apiKey;exit();

        // Preparing API URL

        $this->host = 'https://api.opentok.com/v2/partner/'.$apiKey.'/archive';
        $ch = curl_init($this->host);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('X-TB-PARTNER-AUTH:'.$apiKey.':'.$secret));
        $result = curl_exec($ch);
        curl_close($ch);

        $this->result = json_decode($result);
        return $this->result;
    }

}


?>
