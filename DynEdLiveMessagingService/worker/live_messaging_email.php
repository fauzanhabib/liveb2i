#!/usr/bin/php
<?php
error_reporting(E_ALL);
ini_set("display_startup_errors",1);
ini_set("display_errors",1);


require_once __DIR__ . './../vendor/autoload.php';
//require_once __DIR__ . './../libraries/db.php';
require_once __DIR__ . './../libraries/log.php';


use Pheanstalk\Pheanstalk;

// addition function construct
// Koneksi ke server Beanstalkd
// Dalam kasus ini, koneksi ke localhost dengan IP 127.0.0.1
$pheanstalk = new Pheanstalk(getenv("QUEUE_HOST"));

// Tentukan ke channel / tube mana dia akan listening
$pheanstalk->watch('com.live.email');

// instansiasi database
//$db = new MysqliDb('localhost', 'live', 'Pl0.uhi#', 'live');
$log = new Log();

try{
        // Loop dan lakukan jobs
        while($job = $pheanstalk->reserve()) {

                // Ambil data
                // Disini data masih dalam format JSON, sehingga perlu di encode menjadi object
                $data = json_decode($job->getData());

                // Kondisional dari routing
                // Simple action berdasarkan routing
                if( $data->route == 'email.send_email')
                {
                    //PHPMailer Object
                    $mail = new PHPMailer;

                    //Enable SMTP debugging.
                    $mail->SMTPDebug = 2;
                    //Set PHPMailer to use SMTP.
                    $mail->isSMTP();
                    //Set SMTP host name
                    $mail->Host = "smtp.office365.com";
                    //Set this to true if SMTP host requires authentication to send email
                    $mail->SMTPAuth = true;
                    //Provide username and password
                    $mail->Username = "mobilesupport@dyned.com";
                    $mail->Password = "Courseware24";
                    //If SMTP requires TLS encryption then set it
                    $mail->SMTPSecure = "tls";
                    //Set TCP port to connect to
                    $mail->Port = 587;

                    $mail->From = "mobilesupport@dyned.com";
                    $mail->FromName = "DynEd Live";

                    //$mail->addAddress("$data->email", "Recepient Name");
                    $mail->addAddress("$data->email");

                    $mail->isHTML(true);

                    $mail->Subject = $data->subject;
                    $mail->Body = $data->content;
                    $mail->AltBody = "This is the plain text version of the email content";

                    if(!$mail->send())
                    {
                        //echo "Mailer Error: " . $mail->ErrorInfo;
                        $log->write('error', $mail->ErrorInfo);
                    }
                    else
                    {
                        //echo "Message has been sent successfully";
                        $log->write('info', "Email has been sent to ".$data->email);
                    }
                }
                // sending email if the status of the appointment still active
                else if($data->route == 'email.email_valid_appointment'){
//			try{
//                        if($data->appointment_id){
//                                $db->where("id", $data->appointment_id);
//                                $status = $db->getone("appointments");
//                                if($status["status"] == "active"){
//                                        if(mail($data->email, $data->subject, $data->content, "From: DynEd Live <no-reply@dyned.com> \r\n"."MIME-Version: 1.0\r\n"."Content-Type: text/html; charset=ISO-8859-1\r\n"))
//                                                $log->write('info', "Email has been sent to ".$data->email);
//                                        else $log('error', "Failed to send email to ". $data->email);
//                                }
//                        }
//
//			}
//			catch(Exception $e){
//			$log->write("error", "Failed to send email");
//			}
                }

                // Hapus job kalau sudah selesai. Jika tidak di hapus maka jobs akan terus di lakukan
                $pheanstalk->delete($job);

        }
}
catch(Exception $e){
        $log->write('error', "Failed to send email." );
}
