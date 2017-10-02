#!/usr/bin/php
<?php
error_reporting(E_ALL);
ini_set("display_startup_errors",1);
ini_set("display_errors",1);


require_once __DIR__ . './../vendor/autoload.php';
require_once __DIR__ . './../libraries/db.php';
require_once __DIR__ . './../libraries/log.php';

use Pheanstalk\Pheanstalk;

// addition function construct
// Koneksi ke server Beanstalkd
// Dalam kasus ini, koneksi ke localhost dengan IP 127.0.0.1
$pheanstalk = new Pheanstalk('127.0.0.1');

// Tentukan ke channel / tube mana dia akan listening
$pheanstalk->watch('com.live.database');

// instansiasi database
$db = new MysqliDb('localhost', 'live', 'Pl0.uhi#', 'live');
$log = new Log();

try{
    // Loop dan lakukan jobs
    while($job = $pheanstalk->reserve()) {

            // Ambil data
            // Disini data masih dalam format JSON, sehingga perlu di encode menjadi$
            $data = json_decode($job->getData());

            //$pheanstalk->delete($job);

            // Kondisional dari routing
            // Simple action berdasarkan routing
            if($data != null){
                    if( $data->route == 'database.insert'){
			try{ 
                       if($db->insert($data->table, json_decode(json_encode($data->content), true))) $log->write("info", "Inserting data into ".$data->table." succeed");
                       else $log->write("error", "Inserting data into ".$data->table." failed! ". $db->getLastError());
			}
			catch(Exception $e){
			$log->write("error", "Failed to insert data to database");
			}
                    }
                    if( $data->route == 'database.update'){
                        try{
			  foreach($data->filters as $key => $value){
                          $db->where($key, $value);
                          }
                       if($db->update($data->table, json_decode(json_encode($data->content), true))) $log->write("info", "Updating table ".$data->table." succeed");
                       else $log->write("error", "Updating table ".$data->table." failed! ". $db->getLastError());
			}
			catch(Exception $e){
			$log->write("error", "Failed to update data to database");
			}
                    }
                    if( $data->route == 'database.delete'){
				try{
                            $db->where('id', $data->id);
                            if($db->delete($data->table)) echo "Deleting table ".$data->table." succeed\n" . PHP_EOL;
                            else $log->write("error", "Deleting table ".$data->table." failed! ". $db->getLastError());
				}
				catch(Exception $e){
				$log->write("error", "Failed to delete data from database");
				}
                    }
                    else if($data->route == 'database.insert_while_appointment_still_valid'){
                            if($data->appointment_id){
					try{
                                    $db->where("id", $data->appointment_id);
                                    $status = $db->getone("appointments");
                                    if(($status["status"] == "active") && (count($db->rawQuery("SHOW TABLES LIKE '" . $data->table . "'")) > 0)){
                                        if($db->insert($data->table, json_decode(json_encode($data->content), true))) $log->write("info", "Inserting data into table ".$data->table." succeed");
                                        else $log->write("error", "Inserting data into ".$data->table." failed! ". $db->getLastError());
                                    }else{
                                       $log->write("warning", "Appointment is not active or the table ".$data->table." is not exsist");
                                    }
					}
					catch(Exception $e){
					$log->write("error", "Failed to insert data to database");
					}
                            }
                    }
                    else if($data->route == 'database.update_when_end_time_not_divined'){
                            if($data->appointment_id){
				try{
                            $db->where("appointment_id", $data->appointment_id);
                                    $history = $db->getone("appointment_histories");
                                    if(($history["dcrea"] == $history["dupd"]) && (count($db->rawQuery("SHOW TABLES LIKE '" . $data->table . "'")) > 0)){
                                            $db->where("appointment_id", $data->appointment_id);
                                            if($db->update($data->table, json_decode(json_encode($data->content), true)))
                                                    $log->write("info", "Updating data into table ".$data->table. "succeed");
                                            else
                                                    $log->write("error", "updating table ".$data->table." failed! ". $db->getLastError());
                                    }else{
                                            $log->write("info", "End time appointment histories have been updated by application  or the table ".$data->table." is not exsist");
                                    }
				}
				catch(Exception $e){
				$log->write("error", "Failed to update data to database");
				}

                            }
                    }
            }
            // Hapus job kalau sudah selesai. Jika tidak di hapus maka jobs akan terus aktif
            $pheanstalk->delete($job);
    }
}
catch(Exception $e){
        $log->write("error", "Failed to modify database");
}
