<?php
//print_r('test'); exit;
if (!@$availability) {
    echo('Not Available');
} else {
    ?>
    <div class="selected-date"><?php echo(date('l, F d, Y', @$date_title)); ?></div>
    <table class="tbl-booking">
        <thead>
            <tr>
                <th class="text-center">START TIME</th>
                <th class="text-center">END TIME</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="bodytr">
            <?php
            // echo "<pre>";print_r($availability);exit('a');
            for ($i = 0; $i < count(@$availability); $i++) {
                    $get_endtime = date('H:i',strtotime(@$availability[$i]['end_time']));

                    // $date = date("Y-m-d H:i:s");
                    $time = strtotime($get_endtime);
                    $time = $time - (5 * 60);
                    $endtime = date("H:i", $time);
                    // echo "<pre>";print_r($endtime);exit('a');
                    // xxxxxxxxxxxx
                    $CheckInX = explode("-", date('Y-m-d',$date_title));
                    $CheckOutX =  explode("-", date('Y-m-d'));
                    $date1 =  mktime(0, 0, 0, $CheckInX[1],$CheckInX[2],$CheckInX[0]);
                    $date2 =  mktime(0, 0, 0, $CheckOutX[1],$CheckOutX[2],$CheckOutX[0]);
                    $interval =($date2 - $date1)/(3600*24);

                    // xxxxxxxxxxxx
                    $gmt_user = $this->db->select("minutes_val as minutes, gmt_val as gmt")
                                         ->from('user_timezones')
                                         ->where('user_id', $this->auth_manager->userid())
                                         ->get()->result();

                    // xxxxxxxxxxxx
                    $adate = date('Y-m-d',$date_title);
                    @date_default_timezone_set('Etc/GMT'.$gmt_user[0]->gmt*(1));
                    $bdate = date('Y-m-d');
                    $res = (int)$gmt_user[0]->gmt;

                    if($adate < $bdate){

                        @date_default_timezone_set('Etc/GMT+0');

                        $dt = date('H:i:s');
                        $default_dt  = strtotime($dt);
                        $usertime = $default_dt+(60*$gmt_user[0]->minutes);
                        $hour = date("H:i:s", $usertime);

                        $selesai=date('H:i:s',strtotime(@$availability[$i]['start_time']));
                        $mulai=$hour;

                        list($jam,$menit,$detik)=explode(':',$mulai);
                        $buatWaktuMulai=mktime($jam,$menit,$detik,1,1,1);

                        list($jam,$menit,$detik)=explode(':',$selesai);
                        $buatWaktuSelesai=mktime($jam,$menit,$detik,1,1,1);
                        $selisihDetik=$buatWaktuSelesai-$buatWaktuMulai;

                            if($selisihDetik > 3599){


                ?>
                <tr>
                    <td class="text-center"><?php echo(date('H:i',strtotime(@$availability[$i]['start_time']))); ?></td>
                    <td class="text-center"><?php echo $endtime; ?></td>
                    <td>
                    <?php if(($this->auth_manager->role() != "PRT") && ($this->auth_manager->role() != 'ADM') && ($this->auth_manager->role() != 'RAD')){ ?>
                        <a href="<?php echo site_url('student/manage_appointments/summary_book/'.$search_by.'/' . $coach_id . '/' . strtotime(@$adate) . '/' . @$availability[$i]['start_time'] . '/' . @$availability[$i]['end_time']); ?>" class="pure-button btn-small btn-white">Book</a>
                    <?php } ?>
                    </td>
                </tr>
                <?php
                    } //penutup dari if selisihdetik
                } else if (($adate == $bdate) && ($res > 0)){
                @date_default_timezone_set('Etc/GMT+0');

                        $dt = date('H:i:s');
                        $default_dt  = strtotime($dt);
                        $usertime = $default_dt+(60*$gmt_user[0]->minutes);
                        $hour = date("H:i:s", $usertime);

                        $selesai=date('H:i:s',strtotime(@$availability[$i]['start_time']));
                        $mulai=$hour;

                        list($jam,$menit,$detik)=explode(':',$mulai);
                        $buatWaktuMulai=mktime($jam,$menit,$detik,1,1,1);

                        list($jam,$menit,$detik)=explode(':',$selesai);
                        $buatWaktuSelesai=mktime($jam,$menit,$detik,1,1,1);
                        $selisihDetik=$buatWaktuSelesai-$buatWaktuMulai;

                            if($selisihDetik > 3599){


                ?>
                <tr>
                    <td class="text-center"><?php echo(date('H:i',strtotime(@$availability[$i]['start_time']))); ?></td>
                    <td class="text-center"><?php echo $endtime; ?></td>
                    <td>
                    <?php if(($this->auth_manager->role() != "PRT") && ($this->auth_manager->role() != 'ADM') && ($this->auth_manager->role() != 'RAD')){ ?>
                        <a href="<?php echo site_url('student/manage_appointments/summary_book/'.$search_by.'/' . $coach_id . '/' . strtotime(@$adate) . '/' . @$availability[$i]['start_time'] . '/' . @$availability[$i]['end_time']); ?>" class="pure-button btn-small btn-white">Book</a>
                    <?php } ?>
                    </td>
                </tr>
                <?php
                    }
                } else if (($adate == $bdate) && ($res < 0)){
                    @date_default_timezone_set('Etc/GMT+0');

                        $dt = date('H:i:s');
                        $default_dt  = strtotime($dt);
                        $usertime = $default_dt+(60*$gmt_user[0]->minutes);
                        $hour = date("H:i:s", $usertime);

                        $selesai=date('H:i:s',strtotime(@$availability[$i]['start_time']));
                        $mulai=$hour;

                        list($jam,$menit,$detik)=explode(':',$mulai);
                        $buatWaktuMulai=mktime($jam,$menit,$detik,1,1,1);

                        list($jam,$menit,$detik)=explode(':',$selesai);
                        $buatWaktuSelesai=mktime($jam,$menit,$detik,1,1,1);
                        $selisihDetik=$buatWaktuSelesai-$buatWaktuMulai;

                            if($selisihDetik > 3599){


                ?>
                <tr>
                    <td class="text-center"><?php echo(date('H:i',strtotime(@$availability[$i]['start_time']))); ?></td>
                    <td class="text-center"><?php echo $endtime; ?></td>
                    <td>
                    <?php if(($this->auth_manager->role() != "PRT") && ($this->auth_manager->role() != 'ADM') && ($this->auth_manager->role() != 'RAD')){ ?>
                        <a href="<?php echo site_url('student/manage_appointments/summary_book/'.$search_by.'/' . $coach_id . '/' . strtotime(@$adate) . '/' . @$availability[$i]['start_time'] . '/' . @$availability[$i]['end_time']); ?>" class="pure-button btn-small btn-white">Book</a>
                    <?php } ?>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    @date_default_timezone_set('Etc/GMT+0');
                    ?>
                    <tr>
                        <?php
                            $x =(date('H:i',strtotime(@$availability[$i]['start_time'])));
                            // if($x == '00:00'){
                            //   $x = '23:59';
                            // }
                            // echo "<pre>";print_r($start_hour_);exit('a');
                            // if($x > $start_hour_){
                        ?>

                        <td class="text-center"><?php echo $x; ?></td>

                        <td class="text-center"><?php echo $endtime; ?></td>

                        <td>
                        <?php if(($this->auth_manager->role() != "PRT") && ($this->auth_manager->role() != 'ADM') && ($this->auth_manager->role() != 'RAD')){ ?>
                            <a href="<?php echo site_url('student/manage_appointments/summary_book/'.$search_by.'/' . $coach_id . '/' . strtotime(@$adate) . '/' . @$availability[$i]['start_time'] . '/' . @$availability[$i]['end_time']); ?>" class="pure-button btn-small btn-white">Book</a>
                        <?php } ?>
                        </td>
                        <?php
                          // }
                        ?>
                    </tr>
                <?php } //penutup dari if adate <= $bdate
            }
            ?>
        </tbody>
    </table>
    <?php
}
if ((@$adate == @$bdate) && (@$res > 0)){
?>
<script type="text/javascript">
$('#bodytr tr:first').remove();
</script>
<?php } exit; ?>
