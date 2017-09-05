<?php

if (!defined("BASEPATH")){
    exit("No direct script access allowed");
}

/**
 * Class Webex_function
 * Class library for webex functions
 * @author      Ponel Panjaitan <ponel@pistarlabs.co.id>
 */
class Webex_function {

    /**
     * var $ci
     * CodeIgniter Instance
     */
    private $CI;

    public function __construct() {
        $this->CI = &get_instance();
        
        $this->CI->load->model('appointment_model');
        $this->CI->load->model('class_meeting_day_model');
        $this->CI->load->model('class_member_model');
        $this->CI->load->model('identity_model');
        $this->CI->load->model('webex_class_model');
        $this->CI->load->model('webex_host_model');
        $this->CI->load->model('webex_model');
        $this->CI->load->model('class_model');
        $this->CI->load->model('user_profile_model');
        $this->CI->load->model('user_model');
        
        $this->CI->load->library('schedule_function');
        $this->CI->load->library('queue');
    }
    
    /**
    * @function create_session
    * @param (int)host_id
    * @param (string)meeting_identifier prefix c for class 
    */ 
    public function create_session($host_id = '', $meeting_identifier = '') {
        $webex_host = $this->CI->webex_host_model->select('subdomain_webex, partner_id, webex_id, timezones, password, voip, max_user, max_duration')->where('id', $host_id)->get();

        if (!$webex_host || !$meeting_identifier || !$host_id) {
            $this->CI->db->trans_rollback();
            $this->CI->messages->add('Invalid Host ID or Meeting Identifier', 'warning');
            return FALSE;
        }

        $host_max_duration = ($webex_host->max_duration == 999999 ? 20 : $webex_host->max_duration);
        $host_max_user = ($webex_host->max_user == 999999 ? 20 : $webex_host->max_user);
        
        // Input attendance/s
        if (substr($meeting_identifier, 0, 1) == 'c') {
            $appointment = $this->CI->class_member_model->get_appointment_for_webex_invitation_xml(substr($meeting_identifier, 1));
            $meeting = $this->CI->class_meeting_day_model->select('*')->where('id', substr($meeting_identifier, 1))->get();
            
            if (!$appointment || !$meeting) {
                $this->CI->db->trans_rollback();
                $this->CI->messages->add('Invalid Meeting Identifier', 'warning');
                return FALSE;
            }
            
            $session_duration = ( strtotime($appointment[0]->end_time) - strtotime($appointment[0]->start_time) ) / 60;
            $class_member_amount = $appointment[0]->student_amount;
            if($host_max_duration < $session_duration || $host_max_user < $class_member_amount){
                $this->CI->db->trans_rollback();
                return FALSE;
            }
            
            foreach ($appointment as $a) {
                $attendance .= htmlspecialchars("<attendee><person><name>$a->student_fullname</name><email>$a->student_email</email></person><role>ATTENDEE</role><joinStatus>INVITE</joinStatus></attendee>");
            }
            $conf_name = "Class " . $appointment[0]->class_name . " on " . $appointment[0]->date . " at " . $appointment[0]->start_time . " " . $appointment[0]->end_time . " With Coach " . $appointment[0]->coach_fullname;
            $max_user = $class_member_amount;
            $duration = $session_duration;
        } else {
            $appointment = $this->CI->appointment_model->get_appointment_for_webex_invitation_xml($meeting_identifier);
            $meeting = $this->CI->appointment_model->select('*')->where('id', $meeting_identifier)->get();

            if (!$appointment) {
                $this->CI->db->trans_rollback();
                $this->CI->messages->add('Invalid Meeting Identifier', 'warning');
                return FALSE;
            }
            
            $session_duration = ( strtotime($appointment[0]->end_time) - strtotime($appointment[0]->start_time) ) / 60;
            if($host_max_duration < $session_duration){
                $this->CI->db->trans_rollback();
                return FALSE;
            }
            
            $attendance = htmlspecialchars("<attendee><person><name>{$appointment[0]->student_fullname}</name><email>{$appointment[0]->student_email}</email></person><role>ATTENDEE</role><joinStatus>INVITE</joinStatus></attendee>");
            $conf_name = "Student " . $appointment[0]->student_fullname . " on " . $appointment[0]->date . " at " . $appointment[0]->start_time . " " . $appointment[0]->end_time . " With Coach " . $appointment[0]->coach_fullname;
            $max_user = 1;
            $duration = $session_duration;
        }

        $attendance = htmlspecialchars_decode($attendance);
        $password = $this->CI->common_function->generate_random_string(6);
        $date = str_replace('-', '/', substr($appointment[0]->date, 5) . '-' . (substr($appointment[0]->date, 0, 4))) . ' ' . $appointment[0]->start_time;

        $XML_SITE = $webex_host->subdomain_webex . ".webex.com";
        $XML_PORT = "443";

        // Set calling user information
        $d["UID"] = $webex_host->webex_id; // WebEx username
        $d["PWD"] = $webex_host->password; // WebEx password
        $d["SNM"] = $webex_host->subdomain_webex; //Demo Site SiteName
        $d["PID"] = $webex_host->partner_id; //Demo Site PartnerID
        
        if($webex_host->voip){
            $voip = "true";
        }else{
            $voip = "false";
        }
        
        $d["XML"] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <serv:message xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
                xmlns:serv=\"http://www.webex.com/schemas/2002/06/service\">
                <header>
                    <securityContext>
                    <webExID>{$d["UID"]}</webExID>
                    <password>{$d["PWD"]}</password>
                    <siteName>{$d["SNM"]}</siteName>
                    <partnerID>{$d["PID"]}</partnerID>
                    </securityContext>
                </header>
                <body>
                <bodyContent xsi:type=\"java:com.webex.service.binding.meeting.CreateMeeting\"
                    xmlns:meet=\"http://www.webex.com/schemas/2002/06/service/meeting\">	
                    <accessControl>
                        <meetingPassword>{$password}</meetingPassword>
                    </accessControl>
                    <metaData>
                        <confName>{$conf_name}</confName>
                        <agenda>Test</agenda>
                    </metaData>
                    <participants>
                        <maxUserNumber>4</maxUserNumber>
                        <attendees>
                            {$attendance}
                        </attendees>
                    </participants>
                    <enableOptions>
                        <chat>true</chat>
                        <poll>true</poll>
                        <voip>{$voip}</voip>
                        <audioVideo>true</audioVideo>
                        <attendeeList>true</attendeeList>
                        <chatHost>true</chatHost>
                        <chatPresenter>true</chatPresenter>
                        <chatAllAttendees>true</chatAllAttendees>
                        <meetingRecord>true</meetingRecord>
                        <autoDeleteAfterMeetingEnd>false</autoDeleteAfterMeetingEnd>
                    </enableOptions>
                    <schedule>
                        <startDate>{$date}</startDate>
                        <joinTeleconfBeforeHost>false</joinTeleconfBeforeHost>
                        <duration>{$duration}</duration>
                        <timeZoneID>20</timeZoneID>
                    </schedule>
                    <telephony>
                        <telephonySupport>NONE</telephonySupport>
                    </telephony>
                <attendeeOptions>
                    <emailInvitations>false</emailInvitations>
                </attendeeOptions>
                </bodyContent>
                </body>
                </serv:message>";

        $URL = "http://{$XML_SITE}/WBXService/XMLService";
        $result = $this->post_it($d, $URL, $XML_PORT);
        if(!$result){
            return FALSE;
        }
        // Clean xml
        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
        $simple_xml = simplexml_load_string($clean_result);
        if ($simple_xml === false) {
            $this->CI->db->trans_rollback();
            return false;
        } else {
            if ($simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->result == 'SUCCESS') {
                $tube = 'com.live.database';
                if (substr($meeting_identifier, 0, 1) == 'c') {
                    $data = Array(
                        'class_meeting_id' => substr($meeting_identifier, 1),
                        'host_id' => $host_id,
                        'webex_meeting_number' => $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('meet', true)->meetingkey,
                        'status' => 'SCHE',
                        'password' => $password
                    );
                    if ($this->CI->webex_class_model->insert($data)) {
                        $student_emails = $this->CI->class_member_model->get_appointment_for_webex_invitation_xml(substr($meeting_identifier, 1));
                        $gmt = $this->CI->identity_model->get_gmt($student_emails[0]->student_id)[0]->timezone;
                        $converted_time = $this->CI->schedule_function->convert_book_schedule(-($this->CI->identity_model->get_gmt($student_emails[0]->student_id)[0]->minutes), strtotime($student_emails[0]->date), $student_emails[0]->start_time, $student_emails[0]->end_time);
                        if ($student_emails) {
                            foreach ($student_emails as $se) {
                                $student_notification [] = array(
                                    'user_id' => $se->student_id,
                                    'description' => 'Class '.$se->class_name.'. You have session with ' . $se->coach_name . ' on ' . date('Y-m-d', $converted_time['date']) . ' at ' . $converted_time['start_time'] . ' until ' . $converted_time['end_time'] .' '. $gmt . ' using WEBEX.'.' Check your email to see the detail invitation!',
                                    'status' => 2,
                                    'dcrea' => time(),
                                    'dupd' => time(),
                                );
                            }

                            // IMPORTANT : array index in content must be in mutual with table field in database
                            foreach ($student_notification as $sn) {
                                $data = array(
                                    'table' => 'user_notifications',
                                    'content' => $sn
                                );
                                // messaging inserting data notification
                                $this->CI->queue->push($tube, $data, 'database.insert');
                            }
                        }else{
                            $this->CI->db->trans_rollback();
                            return false;
                        }
                        return true;
                    }else{
                        $this->CI->db->trans_rollback();
                        return false;
                    }
                } else {
                    $data = Array(
                        'meeting_type' => '11', //Standard Meeting Center
                        'number_attendance' => '1',
                        'appointment_id' => $meeting_identifier,
                        'host_id' => $host_id,
                        'webex_meeting_number' => $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('meet', true)->meetingkey,
                        'status' => 'SCHE',
                        'password' => $password
                    );
                    if ($this->CI->webex_model->insert($data)) {
                        $student_emails = $this->CI->appointment_model->get_appointment_for_webex_invitation($meeting_identifier);
                        $gmt = $this->CI->identity_model->get_gmt($student_emails[0]->student_id)[0]->timezone;
                        $converted_time = $this->CI->schedule_function->convert_book_schedule(-($this->CI->identity_model->get_gmt($student_emails[0]->student_id)[0]->minutes), strtotime($student_emails[0]->date), $student_emails[0]->start_time, $student_emails[0]->end_time);
                        if ($student_emails) {
                            $student_notification = array(
                                'user_id' => $student_emails[0]->student_id,
                                'description' => 'You just invited by ' . $student_emails[0]->coach_name . ' to join a WebEx Meeting on ' . $converted_time['date'] . ' at ' . $converted_time['start_time'] . ' until ' . $converted_time['end_time'] .' '. $gmt . '. Check your email to see the detail invitation!',
                                'status' => 2,
                                'dcrea' => time(),
                                'dupd' => time(),
                            );
                            $data = array(
                                'table' => 'user_notifications',
                                'content' => $student_notification
                            );
                            // messaging inserting data notification
                            $this->CI->queue->push($tube, $data, 'database.insert');
                        }
                        return true;
                    }else{
                        $this->CI->db->trans_rollback();
                        return false;
                    }
                }
            } else {
                //$warning = $simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->reason;
                $this->CI->db->trans_rollback();
                return false;
            }
        }
    }
    
    
    /**
     * @function delete_session
     * @param (int)host_id
     * @param (string)meeting_identifier prefix c for class 
     */ 
    public function delete_session($host_id = '', $meeting_identifier = '') {
        if (!$host_id || !$meeting_identifier) {
            $this->CI->messages->add('Invalid Host ID or Meeting Identifier', 'warning');
            return FALSE;
        }
        $webex_host = $this->CI->webex_host_model->select('subdomain_webex, partner_id, webex_id, timezones, password')->where('id', $host_id)->get();
        if (substr($meeting_identifier, 0, 1) == 'c') {
            $session = $this->CI->webex_class_model->select('webex_meeting_number')->where('class_meeting_id', substr($meeting_identifier, 1))->get();
            if (!$session) {
                $this->CI->messages->add('Invalid Meeting Identifier 3', 'warning');
                return FALSE;
            }
        } else {
            $session = $this->CI->webex_model->select('webex_meeting_number')->where('appointment_id', $meeting_identifier)->get();
            if (!$session) {
                $this->CI->messages->add('Invalid Meeting Identifier 4', 'warning');
                return FALSE;
            }
        }

        $XML_SITE = $webex_host->subdomain_webex . ".webex.com";
        $XML_PORT = "443";

        // Set calling user information
        $d["UID"] = $webex_host->webex_id; // WebEx username
        $d["PWD"] = $webex_host->password; // WebEx password
        $d["SNM"] = $webex_host->subdomain_webex; //Demo Site SiteName
        $d["PID"] = $webex_host->partner_id; //Demo Site PartnerID

        $d["XML"] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <serv:message xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
                <header>
                    <securityContext>
                    <webExID>{$d["UID"]}</webExID>
                    <password>{$d["PWD"]}</password>
                    <siteName>{$d["SNM"]}</siteName>
                    <partnerID>{$d["PID"]}</partnerID>
                    </securityContext>
                </header>
                <body>
                    <bodyContent xsi:type=\"java:com.webex.service.binding.meeting.DelMeeting\">
                        <meetingKey>{$session->webex_meeting_number}</meetingKey>
                    </bodyContent>
                </body>
                </serv:message>";

        $URL = "http://{$XML_SITE}/WBXService/XMLService";
        $result = $this->post_it($d, $URL, $XML_PORT);
        if(!$result){
            return FALSE;
        }
        // Clean xml
        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
        $simple_xml = simplexml_load_string($clean_result);
        if ($simple_xml === false) {
            $this->CI->messages->add('Bad XML!', 'warning');
            return FALSE;
        } else {
            if ($simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->result == 'SUCCESS' || $simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->exceptionID == '060001') {
                return TRUE;
            }elseif($simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->exceptionID == '060002'){
                return "IN-PROGRESS";
            }
        }
        return FALSE;
    }
   
    /**
     * @function get_session_info
     * @param (int)host_id
     * @param (string)meeting_identifier prefix c for class
     * @return confid of session 
     */ 
    public function get_session_info($host_id = '', $meeting_identifier = '') {
        if (!$host_id || !$meeting_identifier) {
            $this->CI->messages->add('Invalid Host ID or Meeting Identifier', 'warning');
            return FALSE;
        }
        $webex_host = $this->CI->webex_host_model->select('subdomain_webex, partner_id, webex_id, timezones, password')->where('id', $host_id)->get();
        if (substr($meeting_identifier, 0, 1) == 'c') {
            $session = $this->CI->webex_class_model->select('webex_meeting_number, password')->where('class_meeting_id', substr($meeting_identifier, 1))->get();
            if (!$session) {
                $this->CI->messages->add('Invalid Meeting Identifier 3', 'warning');
                return FALSE;
            }
        } else {
            $session = $this->CI->webex_model->select('webex_meeting_number, password')->where('appointment_id', $meeting_identifier)->get();
            if (!$session) {
                $this->CI->messages->add('Invalid Meeting Identifier 4', 'warning');
                return FALSE;
            }
        }

        $XML_SITE = $webex_host->subdomain_webex . ".webex.com";
        $XML_PORT = "443";

        // Set calling user information
        $d["UID"] = $webex_host->webex_id; // WebEx username
        $d["PWD"] = $webex_host->password; // WebEx password
        $d["SNM"] = $webex_host->subdomain_webex; //Demo Site SiteName
        $d["PID"] = $webex_host->partner_id; //Demo Site PartnerID

        $d["XML"] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <serv:message xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
                <header>
                    <securityContext>
                    <webExID>{$d["UID"]}</webExID>
                    <password>{$d["PWD"]}</password>
                    <siteName>{$d["SNM"]}</siteName>
                    <partnerID>{$d["PID"]}</partnerID>
                    </securityContext>
                </header>
                <body>
                    <bodyContent xsi:type=\"java:com.webex.service.binding.ep.GetSessionInfo\">
                        <sessionPassword>{$session->password}</sessionPassword>
                        <sessionKey>{$session->webex_meeting_number}</sessionKey>
                    </bodyContent>
                </body>
                </serv:message>";

        $URL = "http://{$XML_SITE}/WBXService/XMLService";
        $result = $this->post_it($d, $URL, $XML_PORT);
        if(!$result){
            return FALSE;
        }
        // Clean xml
        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
        $simple_xml = simplexml_load_string($clean_result);
        if ($simple_xml === false) {
            $this->CI->messages->add('Bad XML!', 'warning');
            return FALSE;
        } else {
            if ($simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->result == 'SUCCESS') {
                $conf_id = $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('ep', true)->confID;
                return trim($conf_id);
            }else{
                return FALSE;
            }
        }
    }
    
    
    /**
     * @function get_list_meeting_type
     * @param (int)meeting_type_id
     * @param (string)webex_id
     * @param (string)password
     * @param (string)subdomain_webex
     * @param (string)partner_id
     * @return array maximum user, maximum duration of webex host
     */ 
    public function get_list_meeting_type($meeting_type_id, $webex_id, $password, $subdomain_webex, $partner_id) {
        
        $XML_SITE = $subdomain_webex . ".webex.com";
        $XML_PORT = "443";

        // Set calling user information
        $d["UID"] = $webex_id; // WebEx username
        $d["PWD"] = $password; // WebEx password
        $d["SNM"] = $subdomain_webex; //Demo Site SiteName
        $d["PID"] = $partner_id; //Demo Site PartnerID

        $d["XML"] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <serv:message xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
                <header>
                    <securityContext>
                    <webExID>{$d["UID"]}</webExID>
                    <password>{$d["PWD"]}</password>
                    <siteName>{$d["SNM"]}</siteName>
                    <partnerID>{$d["PID"]}</partnerID>
                    </securityContext>
                </header>
                <body>
                    <bodyContent xsi:type=\"java:com.webex.service.binding.meetingtype.LstMeetingType\">
                        <meetingTypeID>{$meeting_type_id}</meetingTypeID>
                    </bodyContent>
                </body>
                </serv:message>";

        $URL = "http://{$XML_SITE}/WBXService/XMLService";
        $result = $this->post_it($d, $URL, $XML_PORT);
        
        if(!$result){
            return FALSE;
        }
        
        // Clean xml
        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
        $simple_xml = simplexml_load_string($clean_result);
        if ($simple_xml === false) {
            $this->CI->messages->add('Bad XML!', 'warning');
            return FALSE;
        } else {
            if ($simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->result == 'SUCCESS') {
                $max_user = $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('mtgtype', true)->meetingType->children('mtgtype', true)->limits->children('mtgtype', true)->maxMeetingUser;
                $max_duration = $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('mtgtype', true)->meetingType->children('mtgtype', true)->limits->children('mtgtype', true)->maxMeetingDuration;
                $meeting_type = array(
                    'max_user' => trim($max_user),
                    'max_duration' => trim($max_duration)
                );
                return $meeting_type;
            }else{
                return FALSE;
            }
        }
    }
    
    /**
     * @function invite_to_session
     * @param (string)name
     * @param (string)email
     * @param (int)session_key
     * @param (string)webex_id
     * @param (string)password
     * @param (string)subdomain_webex
     * @param (string)partner_id
     * @return array maximum user, maximum duration of webex host
     */ 
    public function invite_to_session($name, $email, $session_key, $webex_id, $password, $subdomain_webex, $partner_id) {
        
        $XML_SITE = $subdomain_webex . ".webex.com";
        $XML_PORT = "443";

        // Set calling user information
        $d["UID"] = $webex_id; // WebEx username
        $d["PWD"] = $password; // WebEx password
        $d["SNM"] = $subdomain_webex; //Demo Site SiteName
        $d["PID"] = $partner_id; //Demo Site PartnerID

        $d["XML"] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <serv:message xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
                <header>
                    <securityContext>
                    <webExID>{$d["UID"]}</webExID>
                    <password>{$d["PWD"]}</password>
                    <siteName>{$d["SNM"]}</siteName>
                    <partnerID>{$d["PID"]}</partnerID>
                    </securityContext>
                </header>
                <body>
                    <bodyContent xsi:type=\"java:com.webex.service.binding.attendee.CreateMeetingAttendee\">
                        <person>
                            <name>{$name}</name>
                            <address>
                                <addressType>PERSONAL</addressType>
                            </address>
                            <email>{$email}</email>
                        </person>
                        <role>ATTENDEE</role>
                        <emailInvitations>FALSE</emailInvitations>
                        <sessionKey>{$session_key}</sessionKey>
                    </bodyContent>
                </body>
                </serv:message>";

        $URL = "http://{$XML_SITE}/WBXService/XMLService";
        $result = $this->post_it($d, $URL, $XML_PORT);
        
        if(!$result){
            return FALSE;
        }
        
        // Clean xml
        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
        $simple_xml = simplexml_load_string($clean_result);
        if ($simple_xml === false) {
            $this->CI->messages->add('Bad XML!', 'warning');
            return FALSE;
        } else {
            if ($simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->result == 'SUCCESS') {
                return TRUE;
            }else{
                return FALSE;
            }
        }
    }
    
    /**
     * @function to post xml to webex api
     */
    public function post_it($data_stream, $URL_, $port) {
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
        $fp = fsockopen('ssl://' . $URL, $port, $errno, $errstr);

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
}