<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Verification extends MY_Site_Controller {
    // Constructor
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->library('queue');
        $this->load->library('Auth_manager');
        $this->load->library('send_sms');
        $this->load->library('common_function');
    }
    // Index
    public function index() {
        $this->template->title = "Phone Verification";
        
        $id = $this->auth_manager->userid();

        $prof   = $this->db->select('*')
                ->from('user_profiles up')
                ->join('verified_numbers vn', 'up.user_id = vn.user_id')
                ->where('up.user_id', $id)
                ->get()->result();

        $usertz = $this->common_function->get_usertimezone($id);
        $default  = strtotime($prof[0]->expiration);
        $usertime = $default+(60*$usertz);
        $hour  = date('H:i:s \o\n l \- F\, jS Y', $usertime);

        $codeexpiry = $prof[0]->expiration;
        $currtime = date("Y-m-d H:i:s");

        $datetime1 = new DateTime($currtime);
        $datetime2 = new DateTime($codeexpiry);
        $diff_end  = $datetime1->diff($datetime2);
        $different_hour = $diff_end->format('%h');
        $different_min = $diff_end->format('%i');
        $different_sec = $diff_end->format('%s');
        $min_to_sec    = $different_min * 60;

        if($min_to_sec == 0){
            $min_to_sec = $different_hour *3600;
        }

        $total_sec = $different_sec + $min_to_sec - 3000;

        $vars = array(
            'prof' => @$prof,
            'hour' => @$hour,
            'total_sec' => @$total_sec
        );

        // echo "<pre>";
        // print_r($vars);
        // exit();
        if($prof[0]->status == "verified") {
            $this->template->content->view('default/contents/identity/vVerified',$vars);
            $this->template->publish();
        }else if ($prof[0]->status == "Not Verified") {
            $this->template->content->view('default/contents/identity/vVerification',$vars);
            $this->template->publish();
        }else if($prof[0]->status == "sent"){
            if($codeexpiry > $currtime){
                $this->template->content->view('default/contents/identity/vVerification2',$vars);
                $this->template->publish();
            }else if($codeexpiry < $currtime){
                $this->template->content->view('default/contents/identity/vVerifexpired',$vars);
                $this->template->publish();
            }
        }
    }

    public function request_code(){
        $id = $this->auth_manager->userid();
        $fullname   = $_POST['fullname'];
        $phone_get  = $_POST['phonenum'];

        $getcode    = $this->db->select('*')
                    ->from('verified_numbers')
                    ->where('user_id', $id)
                    ->get()->result();

        $codeverif = $getcode[0]->code;
        $phone = substr($phone_get, 1);

        $this->send_sms->send_verif($phone, $fullname, $codeverif);

        $expiration = date("Y-m-d H:i:s", strtotime('+1 hours'));

        $data = array(
           'expiration' => $expiration,
           'status' => 'sent'
        );

        $this->db->where('user_id', $id);
        $this->db->update('verified_numbers', $data);

        $usertz = $this->common_function->get_usertimezone($id);

        $default  = strtotime($expiration);
        $usertime = $default+(60*$usertz);
        $hour  = date('H:i:s \o\n l \- F\, jS Y', $usertime);

        echo $phone;
        // echo "string";
    }

    public function request_again(){
        $id = $this->auth_manager->userid();

        $fullname   = $_POST['user_id'];

        $codeverif = mt_rand(1000,9999);
        $expiration = date("Y-m-d H:i:s", strtotime('+1 hours'));

        $getcode    = $this->db->select('*')
                    ->from('verified_numbers')
                    ->where('user_id', $id)
                    ->get()->result();

        $phone_get = $getcode[0]->phone_verif;

        $phone = substr($phone_get, 1);

        $this->send_sms->send_verif($phone, $fullname, $codeverif);

        $data = array(
           'expiration' => $expiration,
           'code' => $codeverif,
           'status' => 'sent'
        );

        $this->db->where('user_id', $id);
        $this->db->update('verified_numbers', $data);

        $usertz = $this->common_function->get_usertimezone($id);

        $default  = strtotime($expiration);
        $usertime = $default+(60*$usertz);
        $hour  = date('H:i:s \o\n l \- F\, jS Y', $usertime);

        echo $hour;
    }

    public function verifynumb(){
        $id = $this->auth_manager->userid();
        $getcode  = $this->db->select('*')
                  ->from('verified_numbers')
                  ->where('user_id', $id)
                  ->get()->result();

        $codedb = $getcode[0]->code;
        
        $codesubmit = $_POST['codesubmit'];
        $currtime = date("Y-m-d H:i:s");
        $codeexpiry = $getcode[0]->expiration;

        if($codedb == $codesubmit && $codeexpiry > $currtime){
            $success = array(
               'status' => 'verified'
            );

            $this->db->where('user_id', $id);
            $this->db->update('verified_numbers', $success);

            echo "1";
        }else if($codedb != $codesubmit){
            echo "2";
        }else if($codedb == $codesubmit && $codeexpiry < $currtime){
            echo "3";
        }
    }

}

// public function send_verif($phone = '', $fullname = '', $codeverif = ''){
//     $apiKey = new ApiKey('965c4ede83eddde2f255160362bc45e6', '6efb9c02479036523b61effb6721d1a6');

//     $rest = new RestApiClient($apiKey);

//     $sms = new Sms();

//     $send_verif = array(
//         'apiKey' => $apiKey,
//         'rest' => $rest,
//         'sms' => $sms,
//     );

    

//     $sms->setDestination($phone)
//         ->setOrigin('DynEd')
//         ->setMessage('Hi '.$fullname.', your verification code is: '.$codeverif.'. Valid for 6 hours.');
//     // And save them

//     // echo "<pre>";
//     // print_r($sms);
//     // exit();
//     $rest->save($sms);

//     $this->CI->queue->push($this->tube, $send_verif, 'sms.send_sms');
// }
