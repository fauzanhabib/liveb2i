

<div class="heading text-cl-primary border-b-1 padding15">

    <h2 class="margin0">Upcoming Sessions</h2>

</div>

<div class="box clear-both">
    <div class="heading pure-g padding-t-30">

        <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
            <ul class="pure-menu-list">
                <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('student/ongoing_session'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Current Sessions</a></li> -->
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('student/upcoming_session'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue">Upcoming Sessions</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('student/histories'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Session History</a></li>
                <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('student/histories/class_session'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Class Session History</a></li> -->
            </ul>
        </div>

    </div>

    <div class="content">
        <div class="box">
            <?php echo form_open('student/upcoming_session/search', 'class="pure-form filter-form" role="form"'); ?>
            <div class="pure-u-1 text-center m-t-20">
                <div class="frm-date" style="display:inline-block">
                    <input name="date_from" class="datepicker frm-date margin0" type="text" readonly="" placeholder="Start Date">
                    <span class="icon dyned-icon-coach-schedules"></span>
                </div>
                <div class="frm-date" style="display:inline-block">
                    <input name="date_to" class="datepicker2 frm-date margin0" type="text" readonly="" placeholder="End Date">
                    <span class="icon dyned-icon-coach-schedules"></span>
                </div>
                <input type="submit" name="__submit" value="Go" class="pure-button btn-small btn-tertiary border-rounded height-32" style="margin:0px 10px;" />
            </div>
            <?php echo form_close(); ?>

            <div class="content-title padding-t-25">
                One-To-One-Sessions
            </div>


            <?php
            $arr_mess = @$this->session->flashdata('booking_message');
            if($arr_mess){ ?>
              <div class="content-title padding-t-25">
                <?php foreach ($arr_mess as $key_mess) {
                    echo "- ".$key_mess."<br />";
                } ?>
              </div>
            <?php }?>


            <script>
                $(document).ready(function() {
                    $('#userTable').DataTable( {
                      "bLengthChange": false,
                      "searching": false,
                      "userTable": false,
                      "bInfo" : false,
                      "bPaginate": false
                  });
                } );
            </script>
            <?php
            if(!@$data) {
                echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
            }
            ?>
            <?php if ($data) { ?>
            <table id="userTable" class="display table-session" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-cl-tertiary font-light font-16 border-none">TRANSACTION</th>
                        <th class="text-cl-tertiary font-light font-16 border-none">COACH</th>
                        <th class="text-cl-tertiary font-light font-16 border-none">SESSION DATE</th>
                        <th class="text-cl-tertiary font-light font-16 border-none">TIME</th>
                        <th class="text-cl-tertiary font-light font-16 border-none">ACTION</th>

                        <!-- <th class="text-cl-tertiary font-light font-16 border-none">SESSION RECORDED</th>                -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data as $d) {
                        $gmt_user = $this->identity_model->new_get_gmt($this->auth_manager->userid());
                        $new_gmt = 0;
                        if($gmt_user[0]->gmt > 0){
                            $new_gmt = '+'.$gmt_user[0]->gmt;
                        }else{
                            $new_gmt = $gmt_user[0]->gmt;
                        }
                        ?>
                        <tr>
                            <!-- <td><?php echo date('M j Y H:i:s', $d->dupd); ?></td> -->
                            <td>
                                <?php
                                date_default_timezone_set('UTC');
                                $dt     = date('H:i:s',$d->dupd);
                                $default_dt  = strtotime($dt);
                                $usertime = $default_dt+(60*$minutes);
                                $hour = date("H:i:s", $usertime);


                                $date     = date('M j Y',$d->dupd);

                                echo $date." ".$hour;
                                ?>
                            </td>
                            <td>
                                <div class="rounded-box bg-tertiary">
                                    <span><a href="<?php echo site_url('student/session/coach_detail/' . $d->coach_id); ?>" class="text-cl-white"><?php echo $d->coach_fullname ?></span>
                                    </div>
                                </td>
                                <td><?php echo date('M j Y', strtotime($d->date)); ?></td>
                                <td>

                                    <div class="rounded-box bg-green">
                                        <?php
                                        $get_endtime = date('H:i',strtotime($d->end_time));

                                        // $date = date("Y-m-d H:i:s");
                                        $time = strtotime($get_endtime);
                                        $time = $time - (5 * 60);
                                        $new_endtime = date("H:i", $time);
                                        ?>
                                        <span class="text-cl-white"><?php echo(date('H:i',strtotime($d->start_time)));?> - <?php echo($new_endtime);?> (UTC <?php echo $new_gmt;?>)</span>
                                    </div>

                                </td>
                                <?php
                              // jam sekarang
                                date_default_timezone_set('UTC');
                                $hours     = date('H:i:s');
                                $default_hours  = strtotime($hours);
                                $usertime_hours = $default_hours+(60*$minutes);
                                $hour_now = date("Y-m-d H:i:s", $usertime_hours);

                                $user_end_date = date('Y-m-d', strtotime($d->date));
                                $user_end_time = date('H:i:s',strtotime($d->start_time));
                                $user_time_final = $user_end_date." ".$user_end_time;
                              // =====

                                    // $date1 = date('Y-m-d H:i', strtotime($d->date));
                                    // $date2 = date('Y-m-d H:i');

                                $datetime1 = new DateTime($hour_now);
                                $datetime2 = new DateTime($user_time_final);
                                $difference = $datetime1->diff($datetime2);

                                $p1 = strtotime($hour_now);
                                $p2 = strtotime($user_time_final);

                                $h = abs($p2-$p1)/(60*60);

                                      // if(($difference->days > 0) && ($hour_now > date('H:i',strtotime($d->start_time))) ){
                                if($h > 24){
                                    ?>
                                    <td class="reschedule margin-auto padding-10-15 sm-12">
                                        <?php

                                        $dat = date('Y-m-d', strtotime(@$d->date));
                                        $dat_now = date('Y-m-d');

                                        if($dat > $dat_now){

                                            $appointmen_id = $d->id;
                                            $sqla = $this->db->select('id')
                                            ->from('appointment_reschedules')
                                            ->where('appointment_id',$appointmen_id)
                                            ->get()->result();
                                            if(!$sqla){

                                                ?>
                                                <a class="pure-button btn-medium btn-white rescheduled" onclick="confirmation('<?php echo(site_url('student/manage_appointments/reschedule/'.$d->id.'/'.$d->coach_id));?>', 'single', 'Reschedule', '', 'rescheduled');">Reschedule</a>
                                                <?php
                                            } else {
                                                ?>
                                                <a class="reschedule-session text-cl-green">Already Rescheduled</a>
                                                <?php } } ?>
                                            </td>
                                            <?php } else {  ?>

                                            <td><a class="pure-button btn-medium btn-grey rescheduled" style="cursor:not-allowed">Reschedule</a></td>
                                            <!-- <td>Not Available</td> -->
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                                <?php } ?>
                                <?php echo @$pagination; ?>
                                <!-- <div class="content-title padding-t-25"> -->
                                    <!-- Class Sessions -->
                                    <!-- </div> -->

                                    <?php
                                    if(!@$data_class) {
                    // echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
                                    }
                                    ?>
                                    <?php if($data_class){ ?>
                <!-- <table id="userTable2" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="text-cl-tertiary font-light font-16 border-none">TRANSACTION</th>
                            <th class="text-cl-tertiary font-light font-16 border-none">CLASS NAME</th>
                            <th class="text-cl-tertiary font-light font-16 border-none">SESSION DATE</th>
                            <th class="text-cl-tertiary font-light font-16 border-none">TIME</th>
                            <th class="text-cl-tertiary font-light font-16 border-none">SESSION RECORDED</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($data_class as $d) {
                                ?>
                        <tr>
                            <td><?php echo date('F j, Y H:i:s', $d->dupd); ?></td>
                            <td>
                                <div class="status-disable bg-tertiary m-l-20">
                                    <span><a href="<?php echo site_url('student/class_detail/schedule/'.$d->class_id);?>" class="text-cl-white"><?php echo $d->class_name ?></a></span>
                                </div>
                            </td>
                            <td><?php echo date('F j, Y', strtotime($d->date)); ?></td>
                            <td>
                                <div class="status-disable bg-green m-l-20">
                                    <span class="text-cl-white"><?php echo(date('H:i',strtotime($d->start_time)));?> - <?php echo(date('H:i',strtotime($d->end_time)));?></span>
                                </div>
                            </td>
                            <td>Not Available</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table> -->
                <?php } ?>
                <!-- <div class="height-plus"></div> -->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    // ajax
    // don't cache ajax or content won't be fresh
    $.ajaxSetup({
        cache: false
    });

    // load() functions
    var loadUrl = "<?php echo site_url('student/histories/token'); ?>";
    $(".load_session_histories").click(function () {
        //var index = document.getElementById("loadbasic").value;
        //alert(this.value);
        $("#tab2").load(loadUrl, function () {
            $("#schedule-loading").hide();
        });
    });

    // load() functions
    // var from = document.getElementById("#date_from").value;

    $(".load_searched_session_histories").click(function () {
        var load_url_search = "<?php echo site_url('student/histories/search'); ?>" + "/" + document.getElementById('date_from').value + "/" + document.getElementById('date_to').value;
        $("#tab2").load(load_url_search, function () {
            $("#schedule-loading").hide();
        });
    });

    function date_from(val) {
        $("#date_from").val = val;
    }

    function date_to(val) {
        $("#date_to").val = val;
    }

    function getDate(dates){
        var now = new Date(dates);
        var day = ("0" + (now.getDate() + 1)).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);
        return resultDate;
    }

    function removeDatepicker(){
        $('.datepicker2').datepicker('remove');
    }

    // datepicker
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        startDate: "now",
        autoclose:true
    });

    $('.datepicker').change(function(){
        var dates = $(this).val();
        removeDatepicker();
        $('.datepicker2').datepicker({
            format: 'yyyy-mm-dd',
            startDate: getDate(dates),
            autoclose: true
        });
    });

    $('.height-plus').css({'height':'50px'});


</script>

<script type="text/javascript" src="http://tablesorter.com/__jquery.tablesorter.min.js"></script>
<script type="text/javascript">
    $(function() {
        $("table").tablesorter({debug: true});
    });
</script>
