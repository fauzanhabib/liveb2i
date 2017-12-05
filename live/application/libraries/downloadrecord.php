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

        $this->host = 'https://api.opentok.com/v2/partner/45992642/archive';
        $ch = curl_init($this->host);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('X-TB-PARTNER-AUTH: 45992642:c071e1e9cb983752fea257416b17e03209796a12'));
        $result = curl_exec($ch);
        curl_close($ch);

        $this->result = json_decode($result);
        return $this->result;
    }

}


?>
