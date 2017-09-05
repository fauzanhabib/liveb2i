<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ongoing_session extends MY_Site_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('appointment_model');
        $this->load->model('appointment_history_model');
        $this->load->model('class_member_model');
        $this->load->model('webex_host_model');
        $this->load->model('identity_model'); //penambahan

        $this->load->library('webex_function');

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'STD') {
            $this->messages->add('ERROR');
            redirect('home');
        }
    }

    // Index
    public function index() {
        $this->template->title = "Ongoing Session";
        // penambahan
        $gmt = $this->identity_model->get_gmt($this->auth_manager->userid())[0]->code;
        // batas penambahan
        $data = $this->appointment_model->get_appointment_for_ongoing_session('student_id',$gmt);


        if ($data) {
            $data_schedule = $this->convertBookSchedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($data[0]->date), $data[0]->start_time, $data[0]->end_time);
            $data[0]->date = date('Y-m-d', $data_schedule['date']);
            $data[0]->start_time = $data[0]->start_time;
            $data[0]->end_time = $data[0]->end_time;
        //     echo "a";
        // echo "<pre>";
        // print_r($data_schedule);
        // exit();
        }

        $data_class = $this->class_member_model->get_appointment_for_ongoing_session();
        if ($data_class) {
            $data_class_schedule = $this->convertBookSchedule($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes, strtotime($data_class[0]->date), $data_class[0]->start_time, $data_class[0]->end_time);
            $data_class[0]->date = date('Y-m-d', $data_class_schedule['date']);
            $data_class[0]->start_time = $data_class_schedule['start_time'];
            $data_class[0]->end_time = $data_class_schedule['end_time'];
        }
        $duration = $this->appointment_history_model->where('appointment_id', @$data[0]->id)->order_by('start_time', 'desc')->get();

        if ($data) {
            $webex = $this->webex_host_model->select('subdomain_webex')->where('id', $data[0]->host_id)->get();
            $coach_name = $this->identity_model->get_identity('profile')->select('user_id, fullname, skype_id')->where('user_id', @$data[0]->coach_id)->get();
            
            if ($data[0]->webex_meeting_number && $webex && $coach_name) {
                $session_status = $this->is_session_inprogress($webex->subdomain_webex, $data[0]->webex_id, $data[0]->partner_id, $data[0]->host_password, $data[0]->session_password, $data[0]->webex_meeting_number);
                $join_url = $this->get_join_url($webex->subdomain_webex, $data[0]->webex_id, $data[0]->partner_id, $data[0]->host_password, $this->auth_manager->get_name(), $this->auth_manager->user_email(), $data[0]->session_password, $data[0]->webex_meeting_number);
            } else {
                $media = "SKYPE";
            }
        }
        if ($data_class) {
            $webex_class = $this->webex_host_model->select('subdomain_webex')->where('id', $data_class[0]->host_id)->get();
            $coach_name_class = $this->identity_model->get_identity('profile')->select('fullname, skype_id')->where('user_id', @$data_class[0]->coach_id)->get();
            if ($data_class[0]->webex_meeting_number && $webex_class && $coach_name_class) {
                $session_status = $this->is_session_inprogress($webex_class->subdomain_webex, $data_class[0]->webex_id, $data_class[0]->partner_id, $data_class[0]->host_password, $data_class[0]->session_password, $data_class[0]->webex_meeting_number);
                $join_url = $this->get_join_url($webex_class->subdomain_webex, $data_class[0]->webex_id, $data_class[0]->partner_id, $data_class[0]->host_password, $this->auth_manager->get_name(), $this->auth_manager->user_email(), $data_class[0]->session_password, $data_class[0]->webex_meeting_number);
            } else {
                $media = "SKYPE";
            }
        }

        // echo "<pre>";
        // print_r($data);
        // exit();

        $vars = array(
            'title' => 'Ongoing Session',
            'data' => @$data,
            'webex' => @$webex,
            'duration' => @$duration,
            'coach_name' => @$coach_name,
            'coach_name_class' => @$coach_name_class,
            'data_class' => @$data_class,
            'webex_class' => @$webex_class,
            'join_url' => @$join_url,
            'session_status' => @$session_status,
            'media' => @$media
        );

        $this->template->content->view('default/contents/student/ongoing_session/index', $vars);
        $this->template->publish();
    }

    public function webex() {
        if ($this->input->get('AT') == 'JM') {
            if ($this->input->get('ST') == 'SUCCESS') {
                redirect('student/ongoing_session');
            } else {
                $this->messages->add('The host not started yet', 'danger');
                redirect('student/ongoing_session');
            }
        } else {
            
        }
    }

    private function get_join_url($subdomain = '', $webex_id = '', $partner_id = '', $host_password = '', $attendee_name = '', $attendee_email = '', $session_password = '', $meeting_number = '') {

        $XML_SITE = $subdomain . ".webex.com";
        $XML_PORT = "443";

        $d["XML"] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <serv:message xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
                xmlns:serv=\"http://www.webex.com/schemas/2002/06/service\">
                <header>
                    <securityContext>
                        <webExID>{$webex_id}</webExID>
                        <password>{$host_password}</password>
                        <siteName>{$subdomain}</siteName>
                        <partnerID>{$partner_id}</partnerID>
                    </securityContext>
                </header>
                <body>
                    <bodyContent xsi:type=\"java:com.webex.service.binding.meeting.GetjoinurlMeeting\">
                      <sessionKey>{$meeting_number}</sessionKey>
                      <attendeeName>{$attendee_name}</attendeeName>
                      <attendeeEmail>{$attendee_email}</attendeeEmail>
                      <meetingPW>{$session_password}</meetingPW>
                     </bodyContent>
                  </body>
                </serv:message>";

        $URL = "http://{$XML_SITE}/WBXService/XMLService";
        $result = $this->webex_function->post_it($d, $URL, $XML_PORT);

        // Clean xml
        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
        $simple_xml = simplexml_load_string($clean_result);
        if ($simple_xml === false) {
            return false;
        } else {
            if ($simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->result == 'SUCCESS') {
                $join_url = $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('meet', true)->joinMeetingURL;
                return $join_url;
            } else {
                return false;
            }
        }
    }

    private function is_session_inprogress($subdomain = '', $webex_id = '', $partner_id = '', $host_password = '', $session_password = '', $meeting_number = '') {

        $XML_SITE = $subdomain . ".webex.com";
        $XML_PORT = "443";

        $d["XML"] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <serv:message xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
                xmlns:serv=\"http://www.webex.com/schemas/2002/06/service\">
                <header>
                    <securityContext>
                        <webExID>{$webex_id}</webExID>
                        <password>{$host_password}</password>
                        <siteName>{$subdomain}</siteName>
                        <partnerID>{$partner_id}</partnerID>
                    </securityContext>
                </header>
                <body>
                    <bodyContent xsi:type=\"java:com.webex.service.binding.ep.GetSessionInfo\">
                        <sessionKey>{$meeting_number}</sessionKey>
                        <sessionPassword>{$session_password}</sessionPassword>
                    </bodyContent>
                </body>
                </serv:message>";

        $URL = "http://{$XML_SITE}/WBXService/XMLService";
        $result = $this->webex_function->post_it($d, $URL, $XML_PORT);

        // Clean xml
        $clean_result = htmlspecialchars_decode(strstr(htmlspecialchars($result), htmlspecialchars("<?xml version")));
        $simple_xml = simplexml_load_string($clean_result);
        if ($simple_xml === false) {
            return false;
        } else {
            if ($simple_xml->children('serv', true)->header->children('serv', true)->response->children('serv', true)->result == 'SUCCESS') {
                $status = $simple_xml->children('serv', true)->body->children('serv', true)->bodyContent->children('ep', true)->status;
                if ($status == 'INPROGRESS') {
                    return true;
                }
            } else {
                return false;
            }
        }
    }

    private function convertBookSchedule($minutes = '', $date = '', $start_time = '', $end_time = '') {
        // variable to get schedule out of date
        if ($minutes > 0) {
            if (strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)))) > strtotime($end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00') {
                $date = $date;
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))));
            } else if (strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)))) < strtotime($start_time)) {
                $date = strtotime('+ 1days' . date('Y-m-d', $date));
                //$tomorrow = date('m-d-Y',strtotime($date . "+1 days"));
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)));

//                $date2 = strtotime('+ 1days'.date('Y-m-d',$date));
//                //$tomorrow = date('m-d-Y',strtotime($date . "+1 days"));
//                $start_time2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
//                $end_time2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)));
            }
        } else if ($minutes < 0) {
            if (strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)))) < strtotime($start_time)) {
                $date = $date;
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)));
            } else if (strtotime(date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time)))) > strtotime($end_time) || date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00') {
                $date = strtotime('- 1days' . date('Y-m-d', $date));
                $start_time = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
                $end_time = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))));

//                $date2 = strtotime('- 1days'.date('Y-m-d',$date));
//                $start_time2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($start_time)));
//                $end_time2 = (date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))) == '00:00:00' ? '23:59:59' : date("H:i:s", strtotime($minutes . 'minutes', strtotime($end_time))));
            }
        }

        return array(
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
//            'date2' => @$date2,
//            'start_time2' => @$start_time2,
//            'end_time2' => @$end_time2,
        );
    }

}
