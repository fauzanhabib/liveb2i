<?php
/**
 * Call1 API
 * DynEd Pro account Call1 wrapper
 * @author Irawan Dwihastomo <idwihastomo@dyned.com>
 * @version 0.1.0
 */
class Downloadrecord {

    public function init() {

        // Preparing API URL

        $this->host = 'https://api.opentok.com/v2/partner/45489992/archive';
        $ch = curl_init($this->host);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('X-TB-PARTNER-AUTH: 45489992:7aeb87a21f3074b991f02e1ffdd6dc882a911f10'));
        $result = curl_exec($ch);
        curl_close($ch);

        $this->result = json_decode($result);
        return $this->result;
    }

}


?>
