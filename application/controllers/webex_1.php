<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Webex extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('webex_model');
        $this->load->model('webex_class_model');
        $this->load->model('webex_host_model');
        $this->load->model('appointment_model');
        $this->load->model('class_meeting_day_model');
        $this->load->model('class_member_model');

        $this->load->library('queue');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CCH') {
            $this->messages->add('ERROR');
            redirect('home');
        }
    }

    public function index() {
        echo "WebEx";
    }

    /**
     * @Function Inviting attendaces to meeting
     */
    public function invite($host_id = '', $meeting_identifier = '') {
        if (!$host_id || !$meeting_identifier) {
            $this->messages->add('Invalid host and/or meeting identifier while inviting stundets', 'error');
        }

        if ($this->input->get("AT") == "AA" && $this->input->get("ST") == "SUCCESS") {
            //send notification to students also coach
            $tube = 'com.live.database';
            if (substr($meeting_identifier, 0, 1) == 'c') {
                $student_emails = $this->class_member_model->get_appointment_for_webex_invitation($meeting_identifier);
                if ($student_emails) {
                    foreach ($student_emails as $se) {
                        $student_notification [] = array(
                            'user_id' => $se->student_id,
                            'description' => 'You just invited by ' . $se->coach_name . ' to join a WebEx Meeting on ' . $se->date . ' at ' . $se->start_time . ' until ' . $se->end_time . '. Check your email to see the detail invitation!',
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
                        $this->queue->push($tube, $data, 'database.insert');
                    }
                }
            } else {
                $student_emails = $this->appointment_model->get_appointment_for_webex_invitation($meeting_identifier);
                if ($student_emails) {
                    $student_notification = array(
                        'user_id' => $student_emails[0]->student_id,
                        'description' => 'You just invited by ' . $student_emails[0]->coach_name . ' to join a WebEx Meeting on ' . $student_emails[0]->date . ' at ' . $student_emails[0]->start_time . ' until ' . $student_emails[0]->end_time . '. Check your email to see the detail invitation!',
                        'status' => 2,
                        'dcrea' => time(),
                        'dupd' => time(),
                    );
                    $data = array(
                        'table' => 'user_notifications',
                        'content' => $student_notification
                    );
                    // messaging inserting data notification
                    $this->queue->push($tube, $data, 'database.insert');
                }
            }
            $this->auto_logout($host_id, 'inviting');
        } else {
            if (substr($meeting_identifier, 0, 1) == 'c') {
                $vars = Array(
                    'host_id' => $host_id,
                    'appointments' => $this->class_member_model->get_appointment_for_webex_invitation($meeting_identifier)
                );
                $this->template->content->view('default/contents/webex/invite_class', $vars);
            } else {
                $vars = Array(
                    'host_id' => $host_id,
                    'appointment' => $this->appointment_model->get_appointment_for_webex_invitation($meeting_identifier)
                );
                $this->template->content->view('default/contents/webex/invite', $vars);
            }
            $this->template->publish();
        }
    }

    /**
     * @Function WebEx Host Meeting
     */
    public function host_meeting($host_id = '', $meeting_identifier = '') {
        if ($this->input->get('AT') == 'LI') {
            if ($this->input->get('ST') == 'SUCCESS') {
                $webex_host = $this->webex_host_model->select('subdomain_webex')->where('id', $host_id)->get();
                if (substr($meeting_identifier, 0, 1) == 'c') {
                    $vars = Array(
                        'host_id' => $host_id,
                        'meeting_identifier' => substr($meeting_identifier, 1),
                        'webex' => $this->webex_class_model->select('webex_meeting_number')->where(Array('class_meeting_id' => substr($meeting_identifier, 1), 'host_id' => $host_id))->get(),
                        'webex_host' => $webex_host
                    );
                } else {
                    $vars = Array(
                        'host_id' => $host_id,
                        'meeting_identifier' => $meeting_identifier,
                        'webex' => $this->webex_model->select('webex_meeting_number')->where(Array('appointment_id' => $meeting_identifier, 'host_id' => $host_id))->get(),
                        'webex_host' => $webex_host
                    );
                }
                $this->template->content->view('default/contents/webex/host_meeting', $vars);
                $this->template->publish();
            }
        } else if ($this->input->get('AT') == 'HM') {
            if ($this->input->get('ST') == 'SUCCESS') {
                $this->auto_logout($host_id, 'hosting');
            }
        } else {
            $this->auto_login($host_id, $meeting_identifier, 'HM');
        }
    }

    /**
     * @Function WebEx schedule meeting
     */
    public function schedule_meeting($host_id = '', $meeting_identifier = '') {
        if ($this->input->get('AT') == 'LI') {
            if ($this->input->get('ST') == 'SUCCESS') {
                if (substr($meeting_identifier, 0, 1) == 'c') {
                    //$meeting_type = '11';
                    $number_attendance = '20';
                    $meeting = $this->class_meeting_day_model->select('*')->where('id', substr($meeting_identifier, 1))->get();
                } else {
                    //$meeting_type = '11';
                    $number_attendance = '1';
                    $meeting = $this->appointment_model->select('*')->where('id', $meeting_identifier)->get();
                }

                $vars = Array(
                    //'meeting_type' => $meeting_type, //Training Center
                    'number_attendance' => $number_attendance,
                    'host_id' => $host_id,
                    'meeting_identifier' => $meeting_identifier,
                    'webex' => $this->webex_host_model->select('subdomain_webex, timezone')->where('id', $host_id)->get(),
                    'password' => $this->common_function->generate_random_string(6),
                    'meeting' => $meeting
                );
                $this->template->content->view('default/contents/webex/schedule_meeting', $vars);
                $this->template->publish();
            }
        } else if ($this->input->get('AT') == 'SM') {
            if ($this->input->get('ST') == 'SUCCESS') {
                if (substr($meeting_identifier, 0, 1) == 'c') {
                    $data = Array(
                        'class_meeting_id' => substr($meeting_identifier, 1),
                        'host_id' => $host_id,
                        'webex_meeting_number' => $this->input->get('MK'),
                        'status' => 'SCHE'
                    );
                    if ($this->webex_class_model->insert($data)) {
                        $this->invite($host_id, $meeting_identifier);
                    }
                } else {
                    $data = Array(
                        'meeting_type' => '11', //Standard Meeting Center
                        'number_attendance' => '1',
                        'appointment_id' => $meeting_identifier,
                        'host_id' => $host_id,
                        'webex_meeting_number' => $this->input->get('MK'),
                        'status' => 'SCHE'
                    );
                    if ($this->webex_model->insert($data)) {
                        $this->invite($host_id, $meeting_identifier);
                    }
                }
            }
        } else {
            $this->auto_login($host_id, $meeting_identifier, $action = 'SM');
        }
    }

    /**
     * @Function WebEx logout after hosting session
     */
    public function logout_after_hosting() {
        if ($this->input->get('AT') == 'LO') {
            if ($this->input->get('ST') == 'SUCCESS') {
                redirect('coach/upcoming_session');
            }
        }
    }

    /**
     * @Function WebEx logout after scheduling meeting and inviting attendance/s
     */
    public function logout_after_inviting() {
        if ($this->input->get('AT') == 'LO') {
            if ($this->input->get('ST') == 'SUCCESS') {
                redirect('coach/upcoming_session');
            }
        }
    }

    /**
     * @Function WebEx logout after deleting meeting
     */
    public function logout_after_deleting() {
        if ($this->input->get('AT') == 'LO') {
            if ($this->input->get('ST') == 'SUCCESS') {
                redirect('account/identity');
            }
        }
    }

    /**
     * @Function WebEx auto login
     */
    public function auto_login($host_id, $appointment_id, $action = '') {
        $vars = Array(
            'account_host' => $this->webex_host_model->select('webex_id, password')->where('id', $host_id)->get(),
            'webex_host' => $this->webex_host_model->select('subdomain_webex')->where('id', $host_id)->get(),
            'host_id' => $host_id,
            'appointment_id' => $appointment_id,
            'action' => $action
        );

        $this->template->content->view('default/contents/webex/auto_login', $vars);
        $this->template->publish();
    }

    /**
     * @Function WebEx auto logout
     */
    public function auto_logout($host_id = '', $action_before_logout = '') {
        $vars = Array(
            'action' => $action_before_logout,
            'webex_host' => $this->webex_host_model->select('subdomain_webex')->where('id', $host_id)->get()
        );
        $this->template->content->view('default/contents/webex/auto_logout', $vars);
        $this->template->publish();
    }

    /**
     * @Function Show available host
     */
    public function available_host($meeting_identifier = '') {
        if (!$meeting_identifier) {
            $this->messages->add('Invalid ID', 'error');
            redirect('coach/upcoming_session');
        }

        if (substr($meeting_identifier, 0, 1) == 'c') {
            $date_time = $this->class_meeting_day_model->select('date, start_time, end_time')->where('id', substr($meeting_identifier, 1))->get();
        } else {
            $date_time = $this->appointment_model->select('date, start_time, end_time')->where('id', $meeting_identifier)->get();
        }

        if (!$date_time) {
            $this->messages->add('The appointment doesn\'t exsist', 'error');
            redirect('coach/upcoming_session');
        }

        $used_host = $this->webex_model->checking_host_wheather_used($date_time->date, $date_time->start_time, $date_time->end_time);
        $used_host_class = $this->webex_class_model->checking_host_wheather_used($date_time->date, $date_time->start_time, $date_time->end_time);
        if ($used_host) {
            foreach ($used_host as $host) {
                $this->webex_host_model->where('id !=', $host->host_id);
            }
        }
        if ($used_host_class) {
            foreach ($used_host_class as $host_class) {
                $this->webex_host_model->where('id !=', $host_class->host_id);
            }
        }
        $available_host = $this->webex_host_model->select('*')->get_all();
        if (!$available_host) {
            $this->messages->add('There is no any host available, you can use Skype!', 'error');
            redirect('coach/upcoming_session');
        }

        $vars = Array(
            'appointment_id' => $meeting_identifier,
            'available_host' => $available_host
        );

        $this->template->content->view('default/contents/webex/available_host', $vars);
        $this->template->publish();
    }

    public function get_time_remain($meeting_identifier = '') {
        if (substr($meeting_identifier, 0, 1) == 'c') {
            $appointment = $this->class_meeting_day_model->select('date, end_time')->where('id', substr($meeting_identifier, 1))->get();
            $timer = strtotime($appointment->date . ' ' . $appointment->end_time) - time('Y-m-d H:i:s');
        } else {
            $appointment = $this->appointment_model->select('date, end_time')->where('id', $meeting_identifier)->get();
            $timer = strtotime($appointment->date . ' ' . $appointment->end_time) - time('Y-m-d H:i:s');
        }
        echo $timer * 1000;
    }

    public function delete_meeting($host_id = '', $meeting_identifier = '') {
        if ($this->input->get('AT') == 'LI') {
            if ($this->input->get('ST') == 'SUCCESS') {
                $webex_host = $this->webex_host_model->select('subdomain_webex')->where('id', $host_id)->get();
                if (substr($meeting_identifier, 0, 1) == 'c') {
                    $vars = Array(
                        'host_id' => $host_id,
                        'meeting_identifier' => substr($meeting_identifier, 1),
                        'webex' => $this->webex_class_model->select('webex_meeting_number')->where(Array('class_meeting_id' => substr($meeting_identifier, 1), 'host_id' => $host_id))->get(),
                        'webex_host' => $webex_host
                    );
                } else {
                    $vars = Array(
                        'host_id' => $host_id,
                        'meeting_identifier' => $meeting_identifier,
                        'webex' => $this->webex_model->select('webex_meeting_number')->where(Array('appointment_id' => $meeting_identifier, 'host_id' => $host_id))->get(),
                        'webex_host' => $webex_host
                    );
                }
                $this->template->content->view('default/contents/webex/delete_meeting', $vars);
                $this->template->publish();
            }
        } else if ($this->input->get('AT') == 'KM') {
            if ($this->input->get('ST') == 'SUCCESS') {
                $this->auto_logout($host_id, 'deleting');
            }
        } else {
            $this->auto_login($host_id, $meeting_identifier, 'KM');
        }
    }

    public function create_meeting($host_id = '', $meeting_identifier = '') {
        $webex_host = $this->webex_host_model->select('subdomain_webex, partner_id, webex_id, timezone, password')->where('id', $host_id)->get();

        if (!$webex_host || !$meeting_identifier) {
            $this->messages->add('Invalid Host ID or Meeting Identifier', 'error');
            redirect('coach/upcoming_session');
        }

        // Input attendance/s
        if (substr($meeting_identifier, 0, 1) == 'c') {
            $appointment = $this->class_member_model->get_appointment_for_webex_invitation_xml($meeting_identifier);
            $meeting = $this->class_meeting_day_model->select('*')->where('id', substr($meeting_identifier, 1))->get();

            if (!$appointment || !$meeting) {
                $this->messages->add('Invalid Meeting Identifier', 'error');
                redirect('coach/upcoming_session');
            }
            foreach ($appointment as $a) {
                $attendance .= htmlspecialchars("<attendee><person><name>$a->student_name</name><email>$a->student_email</email></person><role>ATTENDEE</role></attendee>");
            }
        } else {
            $appointment = $this->appointment_model->get_appointment_for_webex_invitation_xml($meeting_identifier);
            $meeting = $this->appointment_model->select('*')->where('id', $meeting_identifier)->get();

            if (!$appointment) {
                $this->messages->add('Invalid Meeting Identifier', 'error');
                redirect('coach/upcoming_session');
            }
            $attendance = htmlspecialchars("<attendee><person><name>{$appointment[0]->student_name}</name><email>{$appointment[0]->student_email}</email></person><role>ATTENDEE</role></attendee>");
        }

        $attendance = htmlspecialchars_decode($attendance);
        $password = $this->common_function->generate_random_string(6);
        $date = str_replace('-', '/', substr($appointment[0]->date, 5) . '-' . (substr($appointment[0]->date, 0, 4))) . ' ' . $appointment[0]->start_time;

        $XML_SITE = $webex_host->subdomain_webex . ".webex.com";
        $XML_PORT = "443";

        // Set calling user information
        $d["UID"] = $webex_host->webex_id; // WebEx username
        $d["PWD"] = $webex_host->password; // WebEx password
        $d["SNM"] = $webex_host->subdomain_webex; //Demo Site SiteName
        $d["PID"] = $webex_host->partner_id; //Demo Site PartnerID

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
                        <confName>Sample Meeting</confName>
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
                        <audioVideo>true</audioVideo>
                    </enableOptions>
                    <schedule>
                        <startDate>{$date}</startDate>
                        <openTime>900</openTime>
                        <joinTeleconfBeforeHost>false</joinTeleconfBeforeHost>
                        <duration>20</duration>
                        <timeZoneID>{$webex_host->timezone}</timeZoneID>
                    </schedule>
                    <telephony>
                        <telephonySupport>CALLIN</telephonySupport>
                        <extTelephonyDescription> Call 1-800-555-1234, Passcode 98765 </extTelephonyDescription>
                    </telephony>
                <attendeeOptions>
                    <emailInvitations>TRUE</emailInvitations>
                </attendeeOptions>
                </bodyContent>
                </body>
                </serv:message>";

        $URL = "http://{$XML_SITE}/WBXService/XMLService";
        $result = $this->post_it($d, $URL, $XML_PORT);

        // Clean xml
        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
        $simple_xml = simplexml_load_string($clean_result);
        if ($simple_xml === false) {
            die('Bad XML!');
        } else {
            if ($simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->result == 'SUCCESS') {
                $tube = 'com.live.database';
                if (substr($meeting_identifier, 0, 1) == 'c') {
                    $data = Array(
                        'class_meeting_id' => substr($meeting_identifier, 1),
                        'host_id' => $host_id,
                        'webex_meeting_number' => $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('meet', true)->meetingkey,
                        'status' => 'SCHE'
                    );
                    if ($this->webex_class_model->insert($data)) {
                        $student_emails = $this->class_member_model->get_appointment_for_webex_invitation($meeting_identifier);
                        if ($student_emails) {
                            foreach ($student_emails as $se) {
                                $student_notification [] = array(
                                    'user_id' => $se->student_id,
                                    'description' => 'You just invited by ' . $se->coach_name . ' to join a WebEx Meeting on ' . $se->date . ' at ' . $se->start_time . ' until ' . $se->end_time . '. Check your email to see the detail invitation!',
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
                                $this->queue->push($tube, $data, 'database.insert');
                            }
                        }
                        $this->messages->add('Setup meeting succeed', 'success');
                        redirect('coach/upcoming_session');
                    }
                } else {
                    $data = Array(
                        'meeting_type' => '11', //Standard Meeting Center
                        'number_attendance' => '1',
                        'appointment_id' => $meeting_identifier,
                        'host_id' => $host_id,
                        'webex_meeting_number' => $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('meet', true)->meetingkey,
                        'status' => 'SCHE'
                    );
                    if ($this->webex_model->insert($data)) {
                        $student_emails = $this->appointment_model->get_appointment_for_webex_invitation($meeting_identifier);
                        if ($student_emails) {
                            $student_notification = array(
                                'user_id' => $student_emails[0]->student_id,
                                'description' => 'You just invited by ' . $student_emails[0]->coach_name . ' to join a WebEx Meeting on ' . $student_emails[0]->date . ' at ' . $student_emails[0]->start_time . ' until ' . $student_emails[0]->end_time . '. Check your email to see the detail invitation!',
                                'status' => 2,
                                'dcrea' => time(),
                                'dupd' => time(),
                            );
                            $data = array(
                                'table' => 'user_notifications',
                                'content' => $student_notification
                            );
                            // messaging inserting data notification
                            $this->queue->push($tube, $data, 'database.insert');
                        }
                        $this->messages->add('Setup meeting succeed', 'success');
                        redirect('coach/upcoming_session');
                    }
                }
            }
        }
    }

    public function create_session($host_id = '', $meeting_identifier = '') {
        if (!$this->input->post('__submit')) {
            $this->messages->add('Invalid action', 'danger');
            redirect('coach/upcoming_session');
        }

        foreach ($this->input->post() as $t => $value) {
            if ($t != "__submit") {
                $data[] = $value;
            }
        }

        //$timezone = array_search(trim($data[0]), $this->common_function->timezones());

        $webex_host = $this->webex_host_model->select('subdomain_webex, partner_id, webex_id, timezones, password')->where('id', $host_id)->get();

        //print_r($webex_host);exit;

        if (!$webex_host || !$meeting_identifier) {
            $this->messages->add('Invalid Host ID or Meeting Identifier', 'error');
            redirect('coach/upcoming_session');
        }

        // Input attendance/s
        if (substr($meeting_identifier, 0, 1) == 'c') {
            $appointment = $this->class_member_model->get_appointment_for_webex_invitation_xml($meeting_identifier);
            $meeting = $this->class_meeting_day_model->select('*')->where('id', substr($meeting_identifier, 1))->get();

            if (!$appointment || !$meeting) {
                $this->messages->add('Invalid Meeting Identifier or There is no Student in the Class yet ', 'error');
                redirect('coach/upcoming_session');
            }
            $attendance = '';
            foreach ($appointment as $a) {
                $attendance .= htmlspecialchars("<participant><person><type>VISITOR</type><name>$a->student_fullname</name><email>$a->student_email</email></person><role>ATTENDEE</role></participant>");
            }
            $conf_name = "Class " . $appointment[0]->class_name . " on " . $appointment[0]->date . " at " . $appointment[0]->start_time . " " . $appointment[0]->end_time . " With Coach " . $appointment[0]->coach_fullname;
            $max_user = 4;
            $duration = 20;
        } else {
            $appointment = $this->appointment_model->get_appointment_for_webex_invitation_xml($meeting_identifier);
            $meeting = $this->appointment_model->select('*')->where('id', $meeting_identifier)->get();

            if (!$appointment) {
                $this->messages->add('Invalid Meeting Identifier', 'error');
                redirect('coach/upcoming_session');
            }
            $attendance = htmlspecialchars("<participant><person><type>VISITOR</type><name>{$appointment[0]->student_fullname}</name><email>{$appointment[0]->student_email}</email></person><role>ATTENDEE</role></participant>");
            $conf_name = "Student " . $appointment[0]->student_fullname . " on " . $appointment[0]->date . " at " . $appointment[0]->start_time . " " . $appointment[0]->end_time . " With Coach " . $appointment[0]->coach_fullname;
            $max_user = 1;
            $duration = 20;
        }

        $attendance = htmlspecialchars_decode($attendance);
        $password = $this->common_function->generate_random_string(6);
        $date = str_replace('-', '/', substr($appointment[0]->date, 5) . '-' . (substr($appointment[0]->date, 0, 4))) . ' ' . $appointment[0]->start_time;

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
                <bodyContent xsi:type=\"java:com.webex.service.binding.training.CreateTrainingSession\" >
                    <accessControl>
                        <listing>PUBLIC</listing>
                        <sessionPassword>{$password}</sessionPassword>
                    </accessControl>
                    <metaData>
                        <defaultHighestMT>true</defaultHighestMT>
                        <confName>{$conf_name}</confName>
                    </metaData>
                    <attendees>
			<participants>
                            {$attendance}
                        </participants>
                    </attendees>
                    <enableOptions>
                        <nativeClient>true</nativeClient>
                        <chat>true</chat>
                        <poll>true</poll>
                        <voip>true</voip>
                        <multiVideo>false</multiVideo>
                        <supportUCFRichMedia>true</supportUCFRichMedia>
                        <audioVideo>true</audioVideo>
                        <trainingSessionRecord>true</trainingSessionRecord>
                        <attendeeRecordTrainingSession>true</attendeeRecordTrainingSession>
                        <autoDeleteAfterMeetingEnd>false</autoDeleteAfterMeetingEnd>
                    </enableOptions>
                    <schedule>
                        <startDate>{$date}</startDate>
                        <openTime>5</openTime>
                        <joinTeleconfBeforeHost>false</joinTeleconfBeforeHost>
                        <duration>{$duration}</duration>
                        <timeZoneID>44</timeZoneID>
                    </schedule>
                    <telephony>
                        <telephonySupport>NONE</telephonySupport>
                        <numPhoneLines>4</numPhoneLines>
                        <extTelephonyURL>String</extTelephonyURL>
                        <extTelephonyDescription>String</extTelephonyDescription>
                        <enableTSP>false</enableTSP>
                        <tspAccountIndex>1</tspAccountIndex>
                    </telephony>
                    <attendeeOptions>
                        <emailInvitations>TRUE</emailInvitations>
                        <participantLimit>1</participantLimit>
                    </attendeeOptions>
                </bodyContent>
                </body>
                </serv:message>";

        $URL = "http://{$XML_SITE}/WBXService/XMLService";
        $result = $this->post_it($d, $URL, $XML_PORT);

        // Clean xml
        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
        $simple_xml = simplexml_load_string($clean_result);

        if ($simple_xml === false) {
            die('Bad XML!');
        } else {
            if ($simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->result == 'SUCCESS') {
                $tube = 'com.live.database';
                if (substr($meeting_identifier, 0, 1) == 'c') {
                    $data = Array(
                        'class_meeting_id' => substr($meeting_identifier, 1),
                        'host_id' => $host_id,
                        'webex_meeting_number' => $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('train', true)->sessionkey,
                        'status' => 'SCHE'
                    );
                    if ($this->webex_class_model->insert($data)) {
                        $student_emails = $this->class_member_model->get_appointment_for_webex_invitation($meeting_identifier);
                        if ($student_emails) {
                            foreach ($student_emails as $se) {
                                $student_notification [] = array(
                                    'user_id' => $se->student_id,
                                    'description' => 'You just invited by ' . $se->coach_fullname . ' to join a WebEx Meeting on ' . $se->date . ' at ' . $se->start_time . ' until ' . $se->end_time . '. Check your email to see the detail invitation!',
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
                                $this->queue->push($tube, $data, 'database.insert');
                            }
                        }
                        $this->messages->add('Setup meeting succeed', 'success');
                        redirect('coach/upcoming_session');
                    }
                } else {
                    $data = Array(
                        'meeting_type' => '11', //Standard Meeting Center
                        'number_attendance' => '1',
                        'appointment_id' => $meeting_identifier,
                        'host_id' => $host_id,
                        'webex_meeting_number' => $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('train', true)->sessionkey,
                        'status' => 'SCHE'
                    );
                    if ($this->webex_model->insert($data)) {
                        $student_emails = $this->appointment_model->get_appointment_for_webex_invitation($meeting_identifier);
                        if ($student_emails) {
                            $student_notification = array(
                                'user_id' => $student_emails[0]->student_id,
                                'description' => 'You just invited by ' . $student_emails[0]->coach_fullname . ' to join a WebEx Meeting on ' . $student_emails[0]->date . ' at ' . $student_emails[0]->start_time . ' until ' . $student_emails[0]->end_time . '. Check your email to see the detail invitation!',
                                'status' => 2,
                                'dcrea' => time(),
                                'dupd' => time(),
                            );
                            $data = array(
                                'table' => 'user_notifications',
                                'content' => $student_notification
                            );
                            // messaging inserting data notification
                            $this->queue->push($tube, $data, 'database.insert');
                        }
                        $this->messages->add('Setup meeting succeed', 'success');
                        redirect('coach/upcoming_session');
                    }
                }
            } else {
                $error = $simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->reason;
                $this->messages->add($error, 'error');
                redirect('coach/upcoming_session');
            }
        }
    }
    
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

/* End of file webex.php */
/* Location: ./application/controllers/webex.php */