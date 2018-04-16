<div class="heading text-cl-primary padding15">
    <h1 class="margin0"><small>Booking Summary</small></h1>
</div>

<div class="box">
    <div class="heading pure-g" style="border-top: 2px solid #f3f3f3;">
        <div class="pure-u-1">
            <h3 class="h3 font-normal padding15 text-cl-secondary">BOOKING 1 </h3>
        </div>
    </div>

    <div class="content">
        <div class="box pure-g">
            <table class="table-no-border2" style="border-collapse: separate;border-spacing: 0px 10px;padding:0">
                <tr>
                    <td>Coach Name</td>
                    <td><?php echo($data_coach[0]->fullname); ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo($data_coach[0]->email); ?></td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td><?php echo(date('l jS \of F Y', @$date)); ?></td>
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
                <!-- <tr>
                    <td>Token Cost</td>
                    <td>
                        <?php
                                $token = '';
                                if($data_coach[0]->coach_type_id == 1){
                                    $token = $standard_coach_cost;
                                } else if($data_coach[0]->coach_type_id == 2){
                                    $token = $elite_coach_cost; 
                                } ?>
                        <input type="text" name="token" value="<?php echo $token;?>" readonly>
                    </td>
                </tr> -->
                <tr>
                    <td style="border-top:1px solid #f3f3f3;display: table-cell;  width: auto !important;">
                        <a id="submit_summary" class="pure-button btn-small btn-secondary confirm-booking" onclick="confirmation('<?php echo $search_by == 'single_date' ? site_url('student/manage_appointments/book_single_coach/' . $data_coach[0]->id . '/' . $date . '/' . $start_time . '/' . $end_time.'/' . $token) : site_url('student/manage_appointments/booking/' . $data_coach[0]->id . '/' . $date . '/' . $start_time . '/' . $end_time.'/' . $token); ?>', 'single', 'Confirm Booking', '', 'confirm-booking');">
                        CONFIRM</a>
                    </td>
                    <td style="border-top:1px solid #f3f3f3;border-bottom:0;display: table-cell;  width: auto !important;">
                        <button type="submit" id="cancel_summary" class="pure-button btn-red btn-small" style="margin:0">BACK</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>
//dsf
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
            location.href = "<?php echo $search_by == 'single_date' ? site_url('student/upcoming_session') : site_url('student/upcoming_session'); ?>";
        })
    });

</script>