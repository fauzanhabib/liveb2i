<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class schedule extends MY_Site_Controller {

    var $day_index = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('schedule_model');
        $this->load->model('offwork_model');
        $this->load->model('identity_model');
        $this->load->model('partner_model');
        $this->load->model('partner_setting_model');
        $this->load->model('specific_settings_model');


        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CCH') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        //date_default_timezone_set('Etc/GMT'.($this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60 : $this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60));
//        $today = date("F j, Y H:i:s");
//        echo($today.'<br>');
//        date_default_timezone_set('Etc/GMT'.(-$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60));
//        $today = date("F j, Y H:i:s");
//        echo($today.'<br>');
//        date_default_timezone_set('Etc/GMT+1');
//        $today = date("F j, Y H:i:s");
//        echo($today.'<br>');
//        date_default_timezone_set('Etc/GMT+3');
//        $today = date("F j, Y H:i:s");
//        echo($today.'<br>');
//        date_default_timezone_set('Etc/GMT-4');
//        $today = date("F j, Y H:i:s");
//        echo($today.'<br>');
//        date_default_timezone_set('Etc/GMT+5');
//        $today = date("F j, Y H:i:s");
//        echo($today.'<br>');
//        date_default_timezone_set('Etc/GMT+6');
//        $today = date("F j, Y H:i:s");
//        echo($today.'<br>');
//        date_default_timezone_set('Etc/GMT-7');
//        $today = date("F j, Y H:i:s");
//        echo($today.'<br>');
//        date_default_timezone_set('Etc/GMT+8');
//        $today = date("F j, Y H:i:s");
//        echo($today.'<br>');
//        date_default_timezone_set('Etc/GMT+9');
//        $today = date("F j, Y H:i:s");
//        echo($today.'<br>');
//        date_default_timezone_set('Etc/GMT+10');
//        $today = date("F j, Y H:i:s");
//        echo($today.'<br>');
//        date_default_timezone_set('Etc/GMT+11');
//        $today = date("F j, Y H:i:s");
//        echo($today.'<br>');
//        date_default_timezone_set('Etc/GMT+12');
//        $today = date("F j, Y H:i:s");
//        echo($today.'<br>');
//        echo('Etc/GMT'.(-$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60));
//        
//        
//        exit;
        $this->template->title = 'Schedules';

        //add Irawan
        $id    = $this->auth_manager->userid();
        $tz = $this->db->select('*')
            ->from('user_timezones')
            ->where('user_id', $id)
            ->get()->result();
        $gmt_vals = $tz[0]->gmt_val;

        $wh = floor($gmt_vals);
        $fract = $gmt_vals - $wh;

        if($fract == 0){
          $gmt_val = $gmt_vals + 0;
        }else{
          $gmt_val = $gmt_vals;
        }

        if($gmt_val > 0){
            $gmt_val = "+".$gmt_val;
        }
        //add Irawan

        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id', $this->auth_manager->userid())->order_by('id', 'asc')->get_all();
        $minutes = @$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;
        // $minutes = 0;
        if (!$schedule_data) {
            redirect('account/identity/detail/profile');
        }
           
        $schedule = array();
        //foreach($schedule_data as $s){
        for ($i = 0; $i < count($schedule_data); $i++) {
            $schedule[$schedule_data[$i]->day] = $this->schedule_block($this->auth_manager->userid(), $schedule_data[$i]->day, $schedule_data[$i]->start_time, $schedule_data[$i]->end_time, $schedule_data[$this->convert_gmt($i, $minutes)]->day, $schedule_data[$this->convert_gmt($i, $minutes)]->start_time, $schedule_data[$this->convert_gmt($i, $minutes)]->end_time);
            if(!$schedule[$schedule_data[$i]->day]){
                $schedule[$schedule_data[$i]->day] = array(
                    array(
                        'start_time' => '00:00:00',
                        'end_time' => '00:00:00',
                    )   
                );
            }
            
        }

        // $setting = $this->partner_setting_model->get();
           $partner_id = $this->auth_manager->partner_id($this->auth_manager->userid());
        $setting = $this->session_duration($partner_id);
        $vars = array(
            'gmt_val' => $gmt_val,
            'schedule' => $schedule,
            'session_duration' => $setting[0]->session_duration,
        );

        // echo('<pre>');
        // print_r($setting); exit;

        $this->template->content->view('default/contents/schedule/index', $vars);
        //print_r($this->identity_model->get_gmt($this->auth_manager->userid())[0]);
        //exit;
        //publish template
        $this->template->publish();
    }
    
    private function session_duration($partner_id = ''){
//        $data =  ($this->partner_model->select('id, session_per_block_by_partner, session_per_block_by_admin')->where('id', $partner_id)->get());
//        if(!$data->session_per_block_by_admin){
//            return $data->session_per_block_by_partner;
//        }
//        else{
//            return $data->session_per_block_by_admin;
//        }
        // $setting = $this->partner_setting_model->get();
        $setting = $this->specific_settings_model->get_partner_settings($partner_id);

        // return $setting->session_duration;
        return $setting;
    }

    private function schedule_block($coach_id = '', $day1 = '', $start_time1 = '', $end_time1 = '', $day2 = '', $start_time2 = '', $end_time2 = '') {
        $schedule1 = $this->block($coach_id, $day1, $start_time1, $end_time1);
        $schedule2 = $this->block($coach_id, $day2, $start_time2, $end_time2);

        $schedule = array();
        $minutes = @$this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;

        $time = strtotime('00:00:00');
        $startTime = date("H:i:s", strtotime((-$minutes) . 'minutes', $time));
        $endTime = date("H:i:s", strtotime('+30 minutes', $time));
        //echo('<pre>'. $day1);
        //print_r($schedule1);
        //exit;

        if ($minutes == 0) {
            $schedule = $schedule1;
        } else if ($minutes > 0) {
            foreach ($schedule2 as $s2) {
                $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['start_time'])));
                $schedule_temp2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['end_time'])));
                if (strtotime($schedule_temp) < strtotime($s2['start_time'])) {
//                        break;
                    $s2['start_time'] = $schedule_temp;
                    $s2['end_time'] = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['end_time'])));
                    $schedule[] = array(
                        'start_time' => $this->convertTime($s2['start_time']),
                        'end_time' => $this->convertTime($s2['end_time']),
                    );
                } else if (strtotime($schedule_temp) > strtotime($s2['start_time']) && strtotime($schedule_temp2) < strtotime($s2['end_time'])) {
                    $schedule[] = array(
                        'start_time' => '00:00:00',
                        'end_time' => $this->convertTime($schedule_temp2),
                    );
                }
            }


            foreach ($schedule1 as $s1) {
                $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s1['start_time'])));
                if (strtotime($schedule_temp) < strtotime($s1['start_time']) || $schedule_temp == '00:00:00') {
                    break;
                } else {
                    $s1['start_time'] = $schedule_temp;
                    $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s1['end_time'])));
                    if (strtotime($schedule_temp) < strtotime($s1['end_time']) || $schedule_temp == '00:00:00') {
                        $s1['end_time'] = '23:59:59';
                    } else {
                        $s1['end_time'] = $schedule_temp;
                    }

                    $schedule[] = array(
                        'start_time' => $this->convertTime($s1['start_time']),
                        'end_time' => $this->convertTime($s1['end_time']),
                    );
                }
            }
        } else {
            foreach ($schedule1 as $s1) {
                $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s1['end_time'])));
                $schedule_temp2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s1['start_time'])));
                if (strtotime($schedule_temp) > strtotime($s1['end_time']) || $schedule_temp == '00:00:00') {
                    //break;
                } else if (strtotime($schedule_temp2) > strtotime($s1['start_time']) && strtotime($schedule_temp) < strtotime($s1['end_time'])) {
                    //$s1['start_time'] = '00:00:00';
                    $schedule[] = array(
                        'start_time' => '00:00:00',
                        'end_time' => $this->convertTime($schedule_temp),
                    );
                } else if (strtotime($schedule_temp2) <= strtotime($s1['start_time'])) {
                    $schedule[] = array(
                        'start_time' => $this->convertTime($schedule_temp2),
                        'end_time' => $this->convertTime($schedule_temp),
                    );
                }
            }

            foreach ($schedule2 as $s2) {
                $schedule_temp = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['start_time'])));
                $schedule_temp2 = date("H:i:s", strtotime($minutes . 'minutes', strtotime($s2['end_time'])));
                if (strtotime($schedule_temp) < strtotime($s2['start_time'])) {
                    break;
                } else if (strtotime($schedule_temp) > strtotime($s2['start_time']) && strtotime($schedule_temp2) > strtotime($s2['end_time'])) {
                    $schedule[] = array(
                        'start_time' => $this->convertTime($schedule_temp),
                        'end_time' => $this->convertTime($schedule_temp2),
                    );
                } else if (strtotime($schedule_temp) > strtotime($s2['start_time']) && strtotime($schedule_temp2) < strtotime($s2['end_time'])) {
                    $schedule[] = array(
                        'start_time' => $this->convertTime($schedule_temp),
                        'end_time' => '23:59:59',
                    );
                }
            }
        }
        //echo('<pre>');
        //print_r($this->joinTime($schedule)); //exit;
        //print_r($schedule); exit;
        //return $schedule;
        return $this->joinTime($schedule);
    }
    
    private function convertTime($time = ''){
        if(date("H:i", strtotime(1 . 'minutes', strtotime($time))) != '00:00' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '01' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '11' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '21' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '31' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '41' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '51' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '06' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '16' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '26' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '36' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '46' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '56'){
            return date("H:i", strtotime(1 . 'minutes', strtotime($time)));
        
        }
        else{
            return $time;
        }
    }
    
    private function joinTime($schedule = ''){
        //foreach($schedule as $s){
        $schedule_temp = array();
        if(count($schedule) > 1){
            for($i=0;$i<(count($schedule));$i++){
                if($schedule[$i]['start_time'] != $schedule[$i]['end_time']){
                    if($i<(count($schedule)-1) && strtotime($schedule[$i]['end_time']) == strtotime($schedule[$i+1]['start_time'])){
                        $schedule_temp[] = array(
                            'start_time' => $schedule[$i]['start_time'],
                            'end_time' => $schedule[$i+1]['end_time'],
                        );
                        $i++;
                    }
                    else{
                        $schedule_temp[] = array(
                            'start_time' => $schedule[$i]['start_time'],
                            'end_time' => $schedule[$i]['end_time'],
                        );
                    }
                }
            }
        }
        else if(count($schedule) == 1){
            if($schedule[0]['start_time'] != $schedule[0]['end_time']){
                $schedule_temp[] = array(
                    'start_time' => $schedule[0]['start_time'],
                    'end_time' => $schedule[0]['end_time'],
                );
            }
        }
        
        return $schedule_temp;
    }

    private function block($coach_id = '', $day = '', $start_time = '', $end_time = '') {
        $offwork = $this->offwork_model->get_offwork($coach_id, $day);
        $schedule_temp = array();
        if ($offwork) {
            //foreach($offwork as $o){
            for ($i = 0; $i <= count($offwork); $i++) {
                if ($i == 0) {
                    $schedule_temp[] = array(
                        'start_time' => $start_time,
                        'end_time' => $offwork[0]->start_time,
                    );
                } else if ($i > 0 && $i < (count($offwork))) {
                    $schedule_temp[] = array(
                        'start_time' => $offwork[$i - 1]->end_time,
                        'end_time' => $offwork[$i]->start_time,
                    );
                } else if ($i == (count($offwork))) {
                    $schedule_temp[] = array(
                        'start_time' => $offwork[$i - 1]->end_time,
                        'end_time' => $end_time,
                    );
                }
            }
        } else {
            $schedule_temp[] = array(
                'start_time' => $start_time,
                'end_time' => $end_time,
            );
        }

        return $schedule_temp;
    }

    private function convert_gmt($index = '', $minutes = '') {
        if ($minutes > 0) {
            return (($index - 1) >= 0 ? ($index - 1) : 6);
        } else {
            return (($index + 1) <= 6 ? ($index + 1) : 0);
        }
    }
    
    private function isValidSchedule($schedule){
        $status  = false;
        for($i=0;$i<count($schedule);$i++){
            if(strtotime($schedule[$i]['end_time']) > strtotime($schedule[$i]['start_time'])){
                if(!$i==0){
                    if(strtotime($schedule[$i]['start_time']) > strtotime($schedule[$i-1]['end_time'])){
                        $status = true;
                    }
                    else{
                        $status = false;
                    }
                }
                else{
                    $status = true;
                }
            }
            else if(count($schedule) == 1 && $schedule[$i]['start_time'] == '0:00' && $schedule[$i]['end_time'] == '0:00'){
                $status = true;
            }
            else{
                $status = false;
            }
            
        }
        return $status;
    }

    public function update($day = '') {
        
        $minutes = $this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes;

        $day1 = $day;
        $day2 = $this->day_index[$this->convert_gmt(array_search($day, $this->day_index), $minutes)];

        $schedule_gmt0_1 = $this->schedule_model->select('day, start_time, end_time')->where('day', $day1)->where('user_id', $this->auth_manager->userid())->get();
        $schedule_gmt0_2 = $this->schedule_model->select('day, start_time, end_time')->where('day', $day2)->where('user_id', $this->auth_manager->userid())->get();

        $schedule_gmt0_1 = $this->block($this->auth_manager->userid(), $schedule_gmt0_1->day, $schedule_gmt0_1->start_time, $schedule_gmt0_1->end_time);
        $schedule_gmt0_2 = $this->block($this->auth_manager->userid(), $schedule_gmt0_2->day, $schedule_gmt0_2->start_time, $schedule_gmt0_2->end_time);

//        echo('<pre>');
//        print_r($schedule_gmt0_1);
//        print_r($schedule_gmt0_2); exit;
        $schedule1 = array();
        $schedule2 = array();
        
        // divided schedule
        $schedule_block = $this->update_block($this->input->post());
        //echo('<pre>'); print_r($schedule_block);exit;
        if(!$this->isValidSchedule($schedule_block)){
            $this->messages->add('Invalid Schedule Order', 'warning');
            redirect('coach/schedule');
        }
        
        if($minutes == 0){
            $schedule_array1 = array();
            foreach ($schedule_block as $s) {
                $schedule_array1[] = array(
                    'start_time' => $s['start_time'],
                    'end_time' => $s['end_time'],
                );
            }
        }
        else if (-$minutes < 0) {
            foreach ($schedule_block as $s) {
                $schedule_temp = date("H:i:s", strtotime(-$minutes . 'minutes', strtotime($s['end_time'])));
                $schedule_temp2 = date("H:i:s", strtotime(-$minutes . 'minutes', strtotime($s['start_time'])));
                if (strtotime($schedule_temp) > strtotime($s['end_time']) || $schedule_temp == '00:00:00') {
                    $schedule2[] = array(
                        'start_time' => $schedule_temp2,
                        'end_time' => ($schedule_temp == '00:00:00' ? '23:59:59' : $schedule_temp)
                    );
                } else if (strtotime($schedule_temp2) > strtotime($s['start_time']) && strtotime($schedule_temp) < strtotime($s['end_time'])) {

                    $schedule2[] = array(
                        'start_time' => $schedule_temp2,
                        'end_time' => '23:59:59',
                    );

                    $schedule1[] = array(
                        'start_time' => '00:00:00',
                        'end_time' => $schedule_temp,
                    );
                } else if (strtotime($schedule_temp2) < strtotime($s['start_time'])) {
                    $schedule1[] = array(
                        'start_time' => $schedule_temp2,
                        'end_time' => $schedule_temp,
                    );
                }
            }

            $schedule_array1 = array();
            $schedule_array2 = array();


            $status1 = 0;
            for ($i = 0; $i < count($schedule_gmt0_1); $i++) {
                if ($schedule1) {
                    for ($j = 0; $j < count($schedule1); $j++) {
                        if (strtotime($schedule1[$j]['start_time']) >= strtotime($schedule_gmt0_1[$i]['start_time']) && strtotime($schedule1[$j]['end_time']) <= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                            $schedule_array1[] = array(
                                'start_time' => $schedule1[$j]['start_time'],
                                'end_time' => $schedule1[$j]['end_time'],
                            );

                            if (strtotime($schedule_gmt0_1[$i]['end_time']) > strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00')))) && $status1 == 0) {
                                $schedule_array1[] = array(
                                    'start_time' => date('H:i:s', strtotime(-($minutes) . 'minutes', strtotime('00:00:00'))),
                                    'end_time' => $schedule_gmt0_1[$i]['end_time'],
                                );
                                $status1 = 1;
                            }
                        } else if (strtotime($schedule1[$j]['end_time']) <= strtotime($schedule_gmt0_1[$i]['start_time']) && strtotime($schedule1[$j]['end_time']) <= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                            $schedule_array1[] = array(
                                'start_time' => $schedule1[$j]['start_time'],
                                'end_time' => $schedule1[$j]['end_time'],
                            );
                        }
                    }

                    if (strtotime($schedule_gmt0_1[$i]['start_time']) >= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                        $schedule_array1[] = array(
                            'start_time' => $schedule_gmt0_1[$i]['start_time'],
                            'end_time' => $schedule_gmt0_1[$i]['end_time'],
                        );
                    }
                } else {

                    if (strtotime($schedule_gmt0_1[$i]['end_time']) <= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                        //no action
                    } else if (strtotime($schedule_gmt0_1[$i]['start_time']) >= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                        $schedule_array1[] = array(
                            'start_time' => $schedule_gmt0_1[$i]['start_time'],
                            'end_time' => $schedule_gmt0_1[$i]['end_time'],
                        );
                    } else if (strtotime($schedule_gmt0_1[$i]['start_time']) <= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00')))) && strtotime($schedule_gmt0_1[$i]['end_time']) > strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                        $schedule_array1[] = array(
                            'start_time' => date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))),
                            'end_time' => $schedule_gmt0_1[$i]['end_time'],
                        );
                    }
                }
            }

            $status2 = 0;
            for ($i = 0; $i < count($schedule_gmt0_2); $i++) {
                if ($schedule2) {
                    for ($j = 0; $j < count($schedule2); $j++) {
                        if (strtotime($schedule2[$j]['start_time']) >= strtotime($schedule_gmt0_2[$i]['start_time']) && strtotime($schedule2[$j]['end_time']) <= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                            $schedule_array2[] = array(
                                'start_time' => $schedule2[$j]['start_time'],
                                'end_time' => $schedule2[$j]['end_time'],
                            );
                            break;
                        } else if (strtotime($schedule2[$j]['start_time']) >= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                            $schedule_array2[] = array(
                                'start_time' => $schedule2[$j]['start_time'],
                                'end_time' => $schedule2[$j]['end_time'],
                            );
                        }
                    }

                    if (strtotime($schedule_gmt0_2[$i]['end_time']) <= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                        $schedule_array2[] = array(
                            'start_time' => $schedule_gmt0_2[$i]['start_time'],
                            'end_time' => $schedule_gmt0_2[$i]['end_time'],
                        );
                    }
                } else {
                    if (strtotime($schedule_gmt0_2[$i]['start_time']) < strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00')))) && strtotime($schedule_gmt0_2[$i]['end_time']) >= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                        $schedule_array2[] = array(
                            'start_time' => $schedule_gmt0_2[$i]['start_time'],
                            'end_time' => date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))),
                        );
                    } else if (strtotime($schedule_gmt0_2[$i]['end_time']) < strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                        $schedule_array2[] = array(
                            'start_time' => $schedule_gmt0_2[$i]['start_time'],
                            'end_time' => $schedule_gmt0_2[$i]['end_time'],
                        );
                    }
                }
            }
        }
        else {
            foreach ($schedule_block as $s) {
                $schedule_temp = date("H:i:s", strtotime(-$minutes . 'minutes', strtotime($s['start_time'])));
                $schedule_temp2 = date("H:i:s", strtotime(-$minutes . 'minutes', strtotime($s['end_time'])));
                if (strtotime($schedule_temp) < strtotime($s['start_time'])) {
                    $schedule2[] = array(
                        'start_time' => $schedule_temp,
                        'end_time' => $schedule_temp2,
                    );
                } else if (strtotime($schedule_temp2) > strtotime($s['end_time']) || $schedule_temp2 == '00:00:00') {

                    $schedule1[] = array(
                        'start_time' => $schedule_temp,
                        'end_time' => ($schedule_temp2 == '00:00:00' ? '23:59:59' : $schedule_temp2),
                    );
                } else if (strtotime($schedule_temp) > strtotime($s['start_time']) && strtotime($schedule_temp2) < strtotime($s['end_time'])) {
                    $schedule1[] = array(
                        'start_time' => $schedule_temp,
                        'end_time' => '23:59:59',
                    );
                    $schedule2[] = array(
                        'start_time' => '00:00:00',
                        'end_time' => $schedule_temp2,
                    );
                }
            }
            $schedule_array1 = array();
            $schedule_array2 = array();


            $status1 = 0;
            for ($i = 0; $i < count($schedule_gmt0_1); $i++) {
                if ($schedule1) {
                    for ($j = 0; $j < count($schedule1); $j++) {
                        if (strtotime($schedule1[$j]['start_time']) >= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00')))) && strtotime($schedule1[$j]['end_time']) >= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                            $schedule_array1[] = array(
                                'start_time' => $schedule1[$j]['start_time'],
                                'end_time' => $schedule1[$j]['end_time'],
                            );

                            if (strtotime($schedule_gmt0_1[$i]['start_time']) <= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00')))) && strtotime($schedule_gmt0_1[$i]['end_time']) <= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                                $schedule_array1[] = array(
                                    'start_time' => $schedule_gmt0_1[$i]['start_time'],
                                    'end_time' => $schedule_gmt0_1[$i]['end_time'],
                                );
                            }
                            if (strtotime($schedule_gmt0_1[$i]['start_time']) <= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00')))) && strtotime($schedule_gmt0_1[$i]['end_time']) > strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                                $schedule_array1[] = array(
                                    'start_time' => $schedule_gmt0_1[$i]['start_time'],
                                    'end_time' => date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))),
                                );
                            }
                        } else if (strtotime($schedule1[$j]['start_time']) >= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00')))) && strtotime($schedule_gmt0_1[$i]['end_time']) > strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                            $schedule_array1[] = array(
                                'start_time' => $schedule1[$j]['start_time'],
                                'end_time' => date('H:i:s', strtotime(($minutes) . 'minutes', strtotime('00:00:00'))),
                            );

                            $schedule_array1[] = array(
                                'start_time' => date('H:i:s', strtotime(($minutes) . 'minutes', strtotime('00:00:00'))),
                                'end_time' => $schedule_gmt0_1[$i]['end_time'],
                            );
                        }

                        if (strtotime($schedule_gmt0_1[$i]['start_time']) <= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00')))) && strtotime($schedule_gmt0_1[$i]['end_time']) >= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                            $schedule_array1[] = array(
                                'start_time' => $schedule_gmt0_1[$i]['start_time'],
                                'end_time' => date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))),
                            );
                        }
                    }
                } else {
                    if (strtotime($schedule_gmt0_1[$i]['end_time']) <= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                        $schedule_array1[] = array(
                            'start_time' => $schedule_gmt0_1[$i]['start_time'],
                            'end_time' => $schedule_gmt0_1[$i]['end_time'],
                        );
                    } else if (strtotime($schedule_gmt0_1[$i]['start_time']) <= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00')))) && strtotime($schedule_gmt0_1[$i]['end_time']) > strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                        $schedule_array1[] = array(
                            'start_time' => $schedule_gmt0_1[$i]['start_time'],
                            'end_time' => date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))),
                        );
                    }
                }
            }

            $status2 = 0;
            for ($i = 0; $i < count($schedule_gmt0_2); $i++) {
                if ($schedule2) {
                    for ($j = 0; $j < count($schedule2); $j++) {
                        if (strtotime($schedule_gmt0_2[$i]['end_time']) <= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                            $schedule_array2[] = array(
                                'start_time' => $schedule2[$j]['start_time'],
                                'end_time' => $schedule2[$j]['end_time'],
                            );
                        } else if (strtotime($schedule_gmt0_2[$i]['start_time']) <= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00')))) && strtotime($schedule_gmt0_2[$i]['end_time']) > strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                            $schedule_array2[] = array(
                                'start_time' => $schedule2[$j]['start_time'],
                                'end_time' => $schedule2[$j]['end_time'],
                            );

                            $schedule_array2[] = array(
                                'start_time' => date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))),
                                'end_time' => $schedule_gmt0_2[$i]['end_time'],
                            );
                        } else if (strtotime($schedule_gmt0_2[$i]['start_time']) >= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                            $schedule_array2[] = array(
                                'start_time' => $schedule2[$j]['start_time'],
                                'end_time' => $schedule2[$j]['end_time'],
                            );

                            $schedule_array2[] = array(
                                'start_time' => $schedule_gmt0_2[$i]['start_time'],
                                'end_time' => $schedule_gmt0_2[$i]['end_time'],
                            );
                        }
                    }
                } else {
                    if (strtotime($schedule_gmt0_2[$i]['start_time']) <= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                        if (strtotime($schedule_gmt0_2[$i]['end_time']) > strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00')))) || $schedule_gmt0_2[$i]['end_time'] == '00:00:00') {
                            $schedule_array2[] = array(
                                'start_time' => date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))),
                                'end_time' => ($schedule_gmt0_2[$i]['end_time'] == '00:00:00' ? '23:59:59' : $schedule_gmt0_2[$i]['end_time']),
                            );
                        }
                    } else if (strtotime($schedule_gmt0_2[$i]['start_time']) >= strtotime(date("H:i:s", strtotime(-$minutes . 'minutes', strtotime('00:00:00'))))) {
                        $schedule_array2[] = array(
                            'start_time' => $schedule_gmt0_2[$i]['start_time'],
                            'end_time' => $schedule_gmt0_2[$i]['end_time'],
                        );
                    }
                }
            }

        }
        
        
        //echo('<pre>');
        


        if($minutes == 0){
            if($this->update_schedule($day1, $this->schedule_offwork_block(array_map("unserialize", array_unique(array_map("serialize", $schedule_array1)))))){
                $this->messages->add('Update Succeeded', 'success');
            }
        }
        else{
            if($this->update_schedule($day1, $this->schedule_offwork_block(array_map("unserialize", array_unique(array_map("serialize", $schedule_array1))))) && $this->update_schedule($day2, $this->schedule_offwork_block(array_map("unserialize", array_unique(array_map("serialize", $schedule_array2)))))){
                $this->messages->add('Update Succeeded', 'success');
            }
            else{
                $this->messages->add('Update failed', 'warning');
            }
        }
        
        redirect('coach/schedule');
    }

    private function update_block($time) {
        $schedule = array();
        for ($i = 0; $i < floor(count($time) / 2); $i++) {
            $schedule[] = array(
                'start_time' => $time['start_time_' . $i],
                'end_time' => $time['end_time_' . $i],
            );
        }
        return $schedule;
    }

    private function update_schedule_block() {
        
    }

    public function delete($day = '', $id = '') {
        //deleting data if in a day has more than one schedule
        if (count($this->schedule_model->where('user_id', $this->auth_manager->userid())->where('day', $day)->get_all()) > 1) {
            $this->schedule_model->where('user_id', $this->auth_manager->userid())->where('day', $day)->delete($id);
            $this->messages->add('Delete Succeeded', 'success');
            redirect('coach/schedule/edit/' . $day);
        }
        //one coach must has one schedule each day in database even if start_time and end_time null
        else if (count($this->schedule_model->where('user_id', $this->auth_manager->userid())->where('day', $day)->get_all() == 1)) {
            $schedule = array(
                'start_time' => null,
                'end_time' => null,
            );

            // Inserting and checking
            $id = $this->schedule_model->where('user_id', $this->auth_manager->userid())->where('day', $day)->get();
            if (!$this->schedule_model->update($id->id, $schedule)) {
                $this->messages->add(validation_errors(), 'danger');
                $this->edit($this->auth_manager->userid());
                return;
            }
            redirect('coach/schedule/');
        }
    }

    private function schedule_offwork_block($schedule_block = '') {
        $offwork = array();

        //sorting schedule
        if (!function_exists('sort_schedule')) {

            function sort_schedule($a, $b) {
                $t1 = strtotime($a['start_time']);
                $t2 = strtotime($b['start_time']);
                return $t1 - $t2;
            }

        }

        usort($schedule_block, 'sort_schedule');

        for ($i = 0; $i < (count($schedule_block) - 1); $i++) {
            if($schedule_block[$i]['end_time'] != $schedule_block[$i + 1]['start_time']){
                $offwork[] = array(
                    'start_time' => $schedule_block[$i]['end_time'],
                    'end_time' => $schedule_block[$i + 1]['start_time'],
                );
            }
        }

        $schedule = array(
            'start_time' => @$schedule_block[0]['start_time'],
            'end_time' => @$schedule_block[(count($schedule_block) - 1)]['end_time'],
        );

        return array(
            'schedule' => $schedule,
            'offwork' => $offwork,
        );
    }

    private function update_schedule($day = '', $schedule = '') {
        $id = $this->schedule_model->where('user_id', $this->auth_manager->userid())->where('day', $day)->get();
            //echo('test');
            //print_r($schedule['schedule']); exit;
        //$schedule['schedule']['user_id'] = $id->user_id;
        //echo('test');
        //print_r($schedule['schedule']); exit;
        if (!$this->schedule_model->update($id->id, $schedule['schedule'])) {
            $this->messages->add(validation_errors(), 'danger');
            $this->edit($this->auth_manager->userid());
            return;
        }

        $this->offwork_model->where('schedule_id', $id->id)->delete();

        foreach ($schedule['offwork'] as $s) {
            $s['schedule_id'] = $id->id;
            if (!$this->offwork_model->insert($s)) {
                $this->messages->add(validation_errors(), 'warning');
                $this->index();
                return;
            }
        }
        
        return true;
    }

    public function test() {
        $selectedTime = "9:15:00";
        $endTime = strtotime("+5 hours +15 minutes", strtotime($selectedTime));
        echo date('H:i:s', strtotime($selectedTime) + 900);
        exit;
    }

}
