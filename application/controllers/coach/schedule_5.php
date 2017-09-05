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

        //checking user role and giving action
        if (!$this->auth_manager->role() || $this->auth_manager->role() != 'CCH') {
            $this->messages->add('ERROR');
            redirect('account/identity/detail/profile');
        }
    }

    // Index
    public function index() {
        $this->template->title = 'Schedule';
        $schedule_data = $this->schedule_model->select('id, user_id, day, start_time, end_time, dcrea, dupd')->where('user_id', $this->auth_manager->userid())->order_by('id', 'asc')->get_all();
        $minutes = $this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes;
        if (!$schedule_data) {
            redirect('account/identity/detail/profile');
        }
//            echo('<pre>');
//            print_r($schedule_data); exit;
        $schedule = array();
        //foreach($schedule_data as $s){
        for ($i = 0; $i < count($schedule_data); $i++) {
            $schedule[$schedule_data[$i]->day] = $this->schedule_block($schedule_data[$i]->day, $schedule_data[$i]->start_time, $schedule_data[$i]->end_time, $schedule_data[$this->convert_gmt($i, $minutes)]->day, $schedule_data[$this->convert_gmt($i, $minutes)]->start_time, $schedule_data[$this->convert_gmt($i, $minutes)]->end_time);
            if(!$schedule[$schedule_data[$i]->day]){
                $schedule[$schedule_data[$i]->day] = array(
                    array(
                        'start_time' => '00:00:00',
                        'end_time' => '00:00:00',
                    )   
                );
            }
            
        }

        $vars = array(
            'schedule' => $schedule,
        );
        $this->template->content->view('default/contents/schedule/index', $vars);
        //print_r($this->identity_model->get_gmt($this->auth_manager->userid())[0]);
        //exit;
        //publish template
        $this->template->publish();
    }

    private function schedule_block($day1 = '', $start_time1 = '', $end_time1 = '', $day2 = '', $start_time2 = '', $end_time2 = '') {
        $schedule1 = $this->block($day1, $start_time1, $end_time1);
        $schedule2 = $this->block($day2, $start_time2, $end_time2);

        $schedule = array();
        $minutes = $this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes;

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
        if(date("H:i", strtotime(1 . 'minutes', strtotime($time))) != '00:00' && date("i", strtotime(1 . 'minutes', strtotime($time))) != '01'){
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

    private function block($day = '', $start_time = '', $end_time = '') {
        $offwork = $this->offwork_model->get_offwork($this->auth_manager->userid(), $day);
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

    public function update($day = '') {
        $minutes = $this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes;

        $day1 = $day;
        $day2 = $this->day_index[$this->convert_gmt(array_search($day, $this->day_index), $minutes)];

        $schedule_gmt0_1 = $this->schedule_model->select('day, start_time, end_time')->where('day', $day1)->where('user_id', $this->auth_manager->userid())->get();
        $schedule_gmt0_2 = $this->schedule_model->select('day, start_time, end_time')->where('day', $day2)->where('user_id', $this->auth_manager->userid())->get();

        $schedule_gmt0_1 = $this->block($schedule_gmt0_1->day, $schedule_gmt0_1->start_time, $schedule_gmt0_1->end_time);
        $schedule_gmt0_2 = $this->block($schedule_gmt0_2->day, $schedule_gmt0_2->start_time, $schedule_gmt0_2->end_time);

        $schedule1 = array();
        $schedule2 = array();
        if (-$minutes < 0) {
            foreach ($this->update_block($this->input->post()) as $s) {
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
            foreach ($this->update_block($this->input->post()) as $s) {
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
        

       
        
        if($this->update_schedule($day1, $this->schedule_offwork_block(array_map("unserialize", array_unique(array_map("serialize", $schedule_array1))))) && $this->update_schedule($day2, $this->schedule_offwork_block(array_map("unserialize", array_unique(array_map("serialize", $schedule_array2)))))){
            $this->messages->add('Update Succeeded', 'success');
        }
        else{
            $this->messages->add('Update failed', 'warning');
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
