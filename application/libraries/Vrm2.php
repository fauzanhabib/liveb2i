<?php  if (!defined("BASEPATH")) exit("No direct script access allowed");

class Vrm2 {

    private $host;
    private $params;
    public $dataJson;
    public $dataObj;
    // All time
    var $all_time_wss = array();
    var $all_time_days = array();
    var $all_time_hours = array();

    public function __construct() {}

    public function init($server, $username, $password){
        
        $this->host = "https://" . $server . ".records.dyned.com/lms.php";

        $params = array(
            'commandName' => 'QueryStudents',
            'studentLoginId' => $username,
            'studentActivity' => true,
            'studentPassword' => $password,
            'token' => 'EBDB2769-019F-4F8D-9D6D-0B9DDEBF8089'
        );
        $this->params = json_encode($params);

        $conn = curl_init($this->host);
        curl_setopt($conn, CURLOPT_COOKIESESSION, true);
        curl_setopt($conn, CURLOPT_POST, 1);
        curl_setopt($conn, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($conn, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($conn, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($conn, CURLOPT_POSTFIELDS, $this->params);

        $this->dataJson = curl_exec($conn);
        $this->dataObj = json_decode($this->dataJson);

        curl_close($conn);
        $this->all_time();
    }
    
    public function getDataJson() {
        return $this->dataJson;
    }

    public function getdataObj() {
        return $this->dataObj;
    }

    public function getDataRadar() {
        $result = array(
            'studentName' => $this->dataObj->studentName,
            'classroomName' => $this->dataObj->classroomName,
            'WSS' => mt_rand(0, 12),
            'SR' => mt_rand(0, 12),
            'sentencesSpoken' => mt_rand(0, 12),
            'sentencesHeard' => mt_rand(0, 12),
            'avgDaysPerWeek' => mt_rand(0, 12),
            'masteryTest' => mt_rand(0, 12),
            'wssMin' => -12,
            'wssMax' => 12,
            'srMin' => 0,
            'srMax' => 12,
            'sentencesSpokenMin' => 0,
            'sentencesSpokenMax' => 12,
            'sentencesHeardMin' => 0,
            'sentencesHeardMax' => 12,
            'avgDaysPerWeekMin' => 0,
            'avgDaysPerWeekMax' => 12,
            'masteryTestMin' => 0,
            'masteryTestMax' => 12,
        );
        return json_encode($result);
    }

    public function getThisWeek() {
        return json_encode($this->dataObj->weeks[0]);
    }

    public function getDial() {
        $result = array(
            'studentName' => $this->dataObj->studentName,
            'classroomName' => $this->dataObj->classroomName,
            'WSS' => 10,
            'SR' => 10,
            'sentencesSpoken' => 10,
            'sentencesHeard' => 10,
            'avgDaysPerWeek' => 10,
            'masteryTest' => 10
        );
        return json_encode($result);
    }

    public function weeks() {
        return $this->dataObj->weeks;
    }

    public function all_time() {
        $weeks = $this->weeks();
        $count = count($weeks);

        for ($i = $count - 1; $i >= 0; $i--) {
            $date = $this->human_to_unix(@$weeks[$i]->periodStart) * 1000;
            $this->all_time_wss[] = array($date, (float) @$weeks[$i]->totalAverageWeightedStudyScore);
            $this->all_time_days[] = array($date, (int) @$weeks[$i]->totalStudyDays);
            $this->all_time_hours[] = array($date, (int) number_format(@$weeks[$i]->totalStudyTime / 60, 0));
        }
    }

    private function human_to_unix($datestr = '') {
        if ($datestr == '') {
            return FALSE;
        }

        $datestr = trim($datestr);
        $datestr = preg_replace("/\040+/", ' ', $datestr);

        if (!preg_match('/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}(?::[0-9]{1,2})?(?:\s[AP]M)?$/i', $datestr)) {
            return FALSE;
        }

        $split = explode(' ', $datestr);

        $ex = explode("-", $split['0']);

        $year = (strlen($ex['0']) == 2) ? '20' . $ex['0'] : $ex['0'];
        $month = (strlen($ex['1']) == 1) ? '0' . $ex['1'] : $ex['1'];
        $day = (strlen($ex['2']) == 1) ? '0' . $ex['2'] : $ex['2'];

        $ex = explode(":", $split['1']);

        $hour = (strlen($ex['0']) == 1) ? '0' . $ex['0'] : $ex['0'];
        $min = (strlen($ex['1']) == 1) ? '0' . $ex['1'] : $ex['1'];

        if (isset($ex['2']) && preg_match('/[0-9]{1,2}/', $ex['2'])) {
            $sec = (strlen($ex['2']) == 1) ? '0' . $ex['2'] : $ex['2'];
        } else {
            // Unless specified, seconds get set to zero.
            $sec = '00';
        }

        if (isset($split['2'])) {
            $ampm = strtolower($split['2']);

            if (substr($ampm, 0, 1) == 'p' AND $hour < 12)
                $hour = $hour + 12;

            if (substr($ampm, 0, 1) == 'a' AND $hour == 12)
                $hour = '00';

            if (strlen($hour) == 1)
                $hour = '0' . $hour;
        }

        return mktime($hour, $min, $sec, $month, $day, $year);
    }

}

//$vrm = new VRM("id2", "suci@demo.com", "");
//s$vrm->all_time();
?>