<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/main.js"></script>

<div class="heading text-cl-primary border-b-1 padding15">
    <h1 class="margin0">Sessions</h1>
</div>

<div class="box clear-both">
    <div class="heading pure-g padding-t-30">
        <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
            <ul class="pure-menu-list">
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('partner_monitor/coach_upcoming_session/one_to_one_session/'.@$coach_id);?>">Upcoming Session</a></li>
                <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('partner_monitor/coach_upcoming_session/class_session');?>" >Class Session</a></li> -->
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('partner_monitor/coach_histories/index/'.@$coach_id);?>" >Session Histories</a></li>
                <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('partner_monitor/coach_histories/class_session');?>" >Class Session Histories</a></li> -->
            </ul>
        </div>
    </div>

    <!-- <div class="content tab-content padding0">
        <div id="tab1" class="tab active"> -->

            <div class="margin0 padding15">
                <a href="#" class="link-filter">Please select date to filter <i class="icon icon-arrow-down"></i></a>
                <?php 
                echo form_open('partner_monitor/coach_upcoming_session/search/one_to_one/'.@$coach_id, 'class="pure-form filter-form" style="border:none"'); 
                ?>
                <div class="pure-g">
                    <div class="pure-u-1 text-center m-t-20">
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_from" class="datepicker frm-date margin0" type="text" readonly="" placeholder="Start Date">    
                                <span class="icon dyned-icon-coach-schedules"></span>
                        </div>
                        <span style="font-size: 16px;margin:0px 10px;">to</span>  
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_to" class="datepicker2 frm-date margin0" type="text" readonly="" placeholder="End Date">  
                    <span class="icon dyned-icon-coach-schedules"></span>
                        </div>
                        <?php echo form_submit('__submit', 'Go','class="pure-button btn-small btn-tertiary border-rounded height-32" style="margin:0px 10px;"'); ?>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
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
            $i = 1;
            if (@$data) {
            ?>
            <div class="b-pad">
            <table id="userTable" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-cl-tertiary font-light font-16 border-none">DATE</th>
                        <th class="text-cl-tertiary font-light font-16 border-none">TIME<br>(Based on Coach's Timezone)</th>
                        <th class="text-cl-tertiary font-light font-16 border-none">STUDENT</th>
                        <th class="text-cl-tertiary font-light font-16 border-none">ACTION</th>
                        <!-- <th class="padding15">ACTION</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data as $d) {
                    $gmt_coach = $this->identity_model->new_get_gmt($coach_id);
                    $gmt_user = $this->identity_model->new_get_gmt($this->auth_manager->userid());
                        $new_gmt = 0;
                    //             if($gmt_user[0]->gmt > 0){
                    //                 $new_gmt = '+'.$gmt_user[0]->gmt;
                    //             }else{
                    //                 $new_gmt = $gmt_user[0]->gmt;    
                    //             }
                    if(@!$gmt_coach){
                        if($gmt_user[0]->gmt > 0){
                        $new_gmt = '+'.$gmt_user[0]->gmt;
                        }else{
                        $new_gmt = $gmt_user[0]->gmt;    
                        }
                    }else if($gmt_coach[0]->gmt > 0){
                        $new_gmt = '+'.$gmt_coach[0]->gmt;
                    }else{
                        $new_gmt = $gmt_coach[0]->gmt;
                    }
                    $link = site_url('webex/available_host/' . @$d->id);
                    ?>
                    <tr class="list-session">
                        <td class="padding15" data-label="DATE">
                            <?php echo(date('F d, Y', strtotime(@$d->date))); ?>
                        </td>
                    <td>
                        <div class="status-disable bg-green">
                            <span class="text-cl-white">
                                <?php
                                    $defaultstart  = strtotime($d->start_time);
                                    echo date("H:i", $defaultstart); 
                                ?> 
                                -
                                <?php
                                    $defaultend  = strtotime($d->end_time);
                                    $endsession = $defaultend-(5*60);
                                    echo date("H:i", $endsession) . " (UTC " . $new_gmt . " )"; 
                                ?>
                            </span>
                        </div>
                    </td>
                        <td class="padding15" data-label="STUDENT">
                           <span class="text-cl-secondary"><?php echo @$d->student_name; ?></span>
                        </td>
                        <td class="padding15">
                            <div class="rw">

                                <?php

                                $dat = date('Y-m-d', strtotime(@$d->date));
                                $dat_now = date('Y-m-d');

                                // jam sekarang
                                $minutes = $this->identity_model->new_get_gmt($coach_id)[0]->minutes;

                                date_default_timezone_set('UTC');
                                $hours     = date('H:i:s');
                                $default_hours  = strtotime($hours);
                                $usertime_hours = $default_hours+(60*$minutes);
                                $hour_now = date("Y-m-d H:i:s", $usertime_hours); 
                                
                                $user_end_date = date('Y-m-d', strtotime($d->date));
                                $user_end_time = date('H:i:s',strtotime($d->start_time));
                                $user_time_final = $user_end_date." ".$user_end_time;

                                $p1 = strtotime($hour_now);
                                $p2 = strtotime($user_time_final); 

                                $h = abs($p2-$p1)/(60*60);
                                
                              // =====

                                // if($dat > $dat_now){
                                if($h > 1){

                                $appointmen_id = $d->id;
                                $sqla = $this->db->select('id')
                                                 ->from('appointment_reschedules')
                                                 ->where('appointment_id',$appointmen_id)
                                                 ->get()->result();
                                if(!$sqla){

                                ?>
                                <div class="b-50">
                                    <a onclick="confirmation('<?php echo site_url('partner_monitor/managing/reschedule/'. $d->id).'/'.@$coach_id; ?>', 'group', 'Reschedule', 'list-session', 'rescheduled');" class="pure-button btn-medium btn-white rescheduled">Reschedule</a>
                                </div>
                                <?php 
                                    } else {
                                ?>
                                <div class="b-50">
                                    <a class="reschedule-session text-cl-green">Already Rescheduled</a>
                                </div>
                                <?php } } else { ?>
                                <div class="b-50">
                                    <a class="pure-button btn-medium btn-grey rescheduled" style="cursor:not-allowed">Reschedule</a>
                                </div>
                                <?php } ?>
                              <!--   <div class="b-50">
                                    <a onclick="confirmation('<?php echo site_url('partner_monitor/managing/cancel/'. $d->student_id . '/' . $d->id); ?>', 'group', 'Cancel', 'list-session', 'cancel');" class="pure-button btn-medium btn-red cancel">CANCEL</a>
                                </div> -->
                            </div>
                            <!-- <div class="b-100">
                                <a href="<?php echo site_url('partner_monitor/vrm/single_student/'. $d->student_id); ?>" class="pure-button btn-medium btn-white">PROGRESS REPORT</a>
                            </div>   -->
                        </td> 
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php echo @$pagination; ?>
            </div>
            <?php } else {
                echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
            } ?>
            <div class="height-plus"></div>
        </div>
        <div id="tab2" class="tab">
            <div id="result">
                <img src='<?php echo base_url(); ?>images/small-loading.gif' alt='loading...' style="display:none;" id="schedule-loading"/>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.tablescroll.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/main.js"></script>
<script>
    // ajax
    // don't cache ajax or content won't be fresh
    $.ajaxSetup({
        cache: false
    });

    // load() functions
    var loadUrl = "<?php echo site_url('coach/ongoing_session'); ?>";
    $(".load_upcoming").click(function () {
        $("#result").load(loadUrl);
    });

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

    $('.rescheduled').click(function(){
        return false;
    });

    $('.cancel').click(function () {
        return false;
    });
    
    $(function() {
        $('#thetable').tableScroll({height:200});
        $('#thetable2').tableScroll({height:200})
    });
</script>