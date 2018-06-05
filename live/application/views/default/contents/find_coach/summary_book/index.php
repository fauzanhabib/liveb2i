<div class="heading text-cl-primary padding15">
    <?php if($recuring > 1){ ?>
        <h1 class="margin0"><small>Multiple Booking Summary</small></h1>
    <?php }else{ ?>
        <h1 class="margin0"><small>Booking Summary</small></h1>
    <?php } ?>
</div>

<div class="box">
    <div class="heading pure-g" style="border-top: 2px solid #f3f3f3;">
        <div class="pure-u-1">
            <h3 class="h3 font-normal padding15 text-cl-secondary">BOOKING 1 </h3>
        </div>
    </div>

    <div class="content">
        <div class="box pure-g">
            <table class="table-no-border2" style="border-collapse: separate;border-spacing: 0px 10px;padding:10px">
                <tr>
                    <td>Coach Name</td>
                    <td><?php echo($data_coach[0]->fullname); ?></td>
                </tr>
                <!-- <tr>
                    <td>Email</td>
                    <td><?php echo($data_coach[0]->email); ?></td>
                </tr> -->
                <tr>
                    <td>Date</td>
                    <?php if($recuring > 1){ ?>
                        <td><?php foreach($frequency as $f){
                            $temp = $date;
                            $temp = strtotime("+".$f." day", $temp);
                            echo(date('l jS \of F Y', @$temp)).'<br> '; }?> 
                        </td>
                    <?php }else{ ?>
                        <td>
                            <?php echo(date('l jS \of F Y', @$date)); ?>
                        </td>
                    <?php } ?>
                </tr>
                <tr>
                    <td>Start Time</td>
                    <td><?php echo($start_time); ?></td>
                </tr>
                <tr>
                    <td>End Time</td>
                    <td><?php 
                    $currentDate = strtotime($end_time);
                    $futureDate = $currentDate-(60*5);
                    $endtime = date("H:i:s", $futureDate);

                    echo($endtime); ?></td>
                </tr>
<!--                <tr>
                    <td>Call Method</td>
                    <td>Skype / Webex</td>
                </tr>-->
                <tr>
                    <td>Token Cost</td>
                    <td>
                        <?php
                        $partner_id = $this->auth_manager->partner_id($data_coach[0]->id);
                        $region_id = $this->auth_manager->region_id($partner_id);

                        // check status setting region
                        $setting_region = $this->db->select('status_set_setting')->from('specific_settings')->where('user_id',$region_id)->get()->result();
                        
                        // $setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('global_settings')->where('type','partner')->get()->result();
                        
                        // jika 0 / disallow
                        // if($setting_region[0]->status_set_setting == 0){
                        //     $setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('global_settings')->where('type','partner')->get()->result();
                        //     $standard_coach_cost = @$setting[0]->standard_coach_cost;
                        //     $elite_coach_cost = @$setting[0]->elite_coach_cost;
                        // } else {
                        //     $setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();
                        //     $standard_coach_cost = @$setting[0]->standard_coach_cost;
                        //     $elite_coach_cost = @$setting[0]->elite_coach_cost;
                        // }

                                $token = '';
                                if($data_coach[0]->coach_type_id == 1){
                                    $token = $standard_coach_cost;
                                    $show = $token * $recuring;
                                } else if($data_coach[0]->coach_type_id == 2){
                                    $token = $elite_coach_cost; 
                                    $show = $token * $recuring;
                                } ?>
                        <input type="text" name="token" value="<?php echo $show;?>" readonly>
                    </td>
                </tr>
                <tr>
                    <td style="display: table-cell;  width: auto !important;">
                        <a id="submit_summary" class="pure-button btn-small btn-secondary confirm-booking" onclick="confirmation('<?php echo $search_by == 'single_date' ? site_url('student/find_coaches/book_single_coach/' . $data_coach[0]->id . '/' . $date . '/' . $start_time . '/' . $end_time.'/' . $token) : site_url('student/find_coaches/book_single_coach/' . $data_coach[0]->id . '/' . $date . '/' . $start_time . '/' . $end_time.'/' . $token); ?>', 'single', 'Confirm Booking', '', 'confirm-booking');">
                        CONFIRM</a>
                    </td>
                    <td style="border-bottom:0;display: table-cell;  width: auto !important; margin:0 5px;">
                        <button type="submit" id="cancel_summary" class="pure-button btn-red btn-small" style="margin:0">BACK</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>
//    document.getElementById("submit_summary").onclick = function () {
//        location.href = "<?php //echo $search_by == 'single_date' ? site_url('student/find_coaches/book_single_coach/' . $data_coach[0]->id . '/' . $date . '/' . $start_time . '/' . $end_time) : site_url('student/find_coaches/booking/' . $data_coach[0]->id . '/' . $date . '/' . $start_time . '/' . $end_time); ?>";
//    };

    // document.getElementById("cancel_summary").onclick = function () {
    //     location.href = "<?php echo $search_by == 'single_date' ? site_url('student/find_coaches/' . $search_by) : site_url('student/find_coaches/search/' . $search_by); ?>";
    // };

    $(function(){
        $('a#submit_summary').click(function(){
            return true;
        });
        $('#cancel_summary').click(function() {
            location.href = "<?php echo $search_by == 'single_date' ? site_url('student/find_coaches/book_by_single_date/'.date('Y-m-d', @$date)) : site_url('student/find_coaches/search/' . $search_by); ?>";
        })
    });

</script>