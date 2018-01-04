<?php
/**
 * Call1 API
 * DynEd Pro account Call1 wrapper
 * @author Jogi Silalahi <jogi@pistarlabs.com>
 * @version 0.1.0
 */
class Call1 {

    /**
     * @var string
     * API host URL
     */
    private $host;
    private $token = 'EBDB2769-019F-4F8D-9D6D-0B9DDEBF8089';
    private $command = 'QueryStudents';
    private $param;
    public $result;

    public function __construct() {}

    /**
     * Initialize and execute Call1
     * @param string
     * @param string
     * @param string
     */
    public function init($login, $password, $server) {

        // Preparing API URL
        if(!$server) {
            return false;
        }

        //$this->host = 'https://'. $server .'.records.dyned.com/lms.php';
        $this->host = getenv("CALL1_URL");


        // Parameter query of student
        $this->param = json_encode(array(
            'commandName'=>$this->command,
            'studentLoginId'=>$login,
            'studentActivity'=>true,
            'studentPassword'=>$password,
            'token'=>$this->token
        ));

        $curl_connection = curl_init($this->host);
        curl_setopt($curl_connection, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl_connection, CURLOPT_POST,1);
        curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl_connection, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $this->param);
        $result = curl_exec($curl_connection);
        curl_close($curl_connection);

        $this->result = json_decode($result);
        if(@$this->result->message == 'Invalid studentLoginId'){
            return false;
        }else{
            return $this->result;
        }
    }

    /**
     * Is valid DynEd Pro
     * @return bool
     */
    public function is_valid()
    {
        return @$this->result->kind != 'error';
    }
    public function callOneJson() {
        return $this->result;
    }
    function halo(){
        return "hai";
    }

}


?>
