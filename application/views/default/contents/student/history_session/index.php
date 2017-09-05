<?php if($this->auth_manager->role() == 'RAD') {
    $role_link = "superadmin";
} else {
    $role_link = "admin";
}
?>
<div class="heading text-cl-primary border-b-1 padding15">
    <h1 class="margin0">Session History</h1>
</div>

<div class="box">
    <div class="heading pure-g padding-t-30">
        <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
            <ul class="pure-menu-list">
            <?php if($this->auth_manager->role()=='ADM' && @$user=='coach'){?>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('admin/coach_upcoming_session/one_to_one_session');?>">Upcoming Session</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('admin/coach_upcoming_session/class_session');?>" >Class Session</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('admin/coach_histories');?>">Session History</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('admin/coach_histories/class_session');?>">Class Session History</a></li>
                <?php }
                elseif($this->auth_manager->role()=='SPR'){?>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('student_partner/student_upcoming_session/one_to_one_session/'.@$student_id);?>">Upcoming Session</a></li>
                    <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('student_partner/student_upcoming_session/class_session/'.@$student_id);?>" >Class Session</a></li> -->
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('student_partner/student_histories/index/'.@$student_id);?>">Session History</a></li>
                    <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('student_partner/student_histories/class_session/'.@$student_id);?>">Class Session History</a></li> -->
                <?php }
                elseif($this->auth_manager->role()=='RAD' && @$user=='coach'){?>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('superadmin/coach_upcoming_session/one_to_one_session/'.@$coach);?>">Upcoming Session</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('superadmin/coach_upcoming_session/class_session/'.@$coach);?>" >Class Session</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('superadmin/coach_histories/index/'.@$coach);?>">Session History</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('superadmin/coach_histories/class_session/'.@$coach);?>">Class Session History</a></li>
                <?php }
                elseif($this->auth_manager->role()=='ADM' && $user=='student'){ ?>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('admin/student_upcoming_session/one_to_one_session/'.@$student_id);?>">Upcoming Session</a></li>
                    <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('admin/student_upcoming_session/class_session/'.@$student_id);?>" >Class Session</a></li> -->
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('admin/student_histories/index/'.@$student_id);?>">Session History</a></li>
                    <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('admin/student_histories/class_session/'.@$student_id);?>">Class Session History</a></li> -->
                <?php }
                elseif($this->auth_manager->role()=='RAD' && $user=='student'){ ?>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('superadmin/student_upcoming_session/one_to_one_session/'.@$student_id);?>">Upcoming Session</a></li>
                    <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('superadmin/student_upcoming_session/class_session/'.@$student_id);?>" >Class Session</a></li> -->
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('superadmin/student_histories/index/'.@$student_id);?>">Session History</a></li>
                    <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('superadminadmin/student_histories/class_session/'.@$student_id);?>">Class Session History</a></li> -->
                <?php }
                elseif($this->auth_manager->role()=='PRT'){?>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('partner/coach_upcoming_session/one_to_one_session/'.@$coach_id);?>">Upcoming Session</a></li>
                    <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('partner/coach_upcoming_session/class_session');?>" >Class Session</a></li> -->
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('partner/coach_histories/index/'.@$coach_id);?>">Session History</a></li>
                    <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('partner/coach_histories/class_session');?>" class="active">Class Session History</a></li> -->
                <?php }elseif ($this->auth_manager->role() == 'CCH') {?>
                    <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('coach/ongoing_session');?>">Current Session</a></li> -->
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('coach/upcoming_session');?>">Upcoming Sessions</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('coach/histories');?>">Session History</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('coach/histories/class_session');?>" class="active">Class Session History</a></li>
                <?php }elseif ($this->auth_manager->role() == 'STD') {?>
                    <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('coach/ongoing_session');?>">Current Session</a></li> -->
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('student/upcoming_session');?>">Upcoming Sessions</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('student/histories');?>">Session History</a></li>
                    <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('coach/histories/class_session');?>" class="active">Class Session History</a></li> -->
                <?php } ?>
            </ul>
        </div>
    </div>
            <div class="margin0 padding15">
                <a href="#" class="link-filter">Please select date to filter <i class="icon icon-arrow-down"></i></a>
                <?php 
                if($this->auth_manager->role() == 'PRT'){
                    echo form_open('partner/coach_histories/search/one_to_one/'.@$coach_id, 'class="pure-form filter-form" style="border:none"'); 
                }elseif($this->auth_manager->role() == 'SPR'){
                    echo form_open('student_partner/student_histories/search/one_to_one/'.@$student_id, 'class="pure-form filter-form" style="border:none"'); 
                }elseif($this->auth_manager->role() == 'RAD' && @$user == 'coach'){
                    echo form_open('superadmin/coach_histories/search/one_to_one', 'class="pure-form filter-form" style="border:none"'); 
                }elseif($this->auth_manager->role() == 'ADM' && @$user == 'coach'){
                    echo form_open('admin/coach_histories/search/one_to_one', 'class="pure-form filter-form" style="border:none"'); 
                }elseif($this->auth_manager->role() == 'ADM' && @$user == 'student'){
                    echo form_open('admin/student_histories/search/one_to_one/'.@$student_id, 'class="pure-form filter-form" style="border:none"'); 
                }elseif($this->auth_manager->role() == 'RAD' && @$user == 'student'){
                    echo form_open('superadmin/student_histories/search/one_to_one/'.@$student_id, 'class="pure-form filter-form" style="border:none"'); 
                }elseif ($this->auth_manager->role() == 'CCH') {
                    echo form_open('coach/histories/search/one_to_one', 'class="pure-form filter-form" style="border:none"'); 
                }elseif ($this->auth_manager->role() == 'STD') {
                    echo form_open('student/histories/search/one_to_one', 'class="pure-form filter-form" style="border:none"'); 
                }
                
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
                    if(!@$histories){
                        echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
                    }
                    else {
                    ?>

                    <table id="userTable" class="display table-session" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-cl-tertiary font-light font-16 border-none">TRANSACTION</th>
                                <th class="text-cl-tertiary font-light font-16 border-none">COACH</th>
                                <th class="text-cl-tertiary font-light font-16 border-none">SESSION DATE</th>
                                <?php if($this->auth_manager->role() == 'STD'){ ?>
                                <th class="text-cl-tertiary font-light font-16 border-none">TIME</th>
                                <?php }else{ ?>
                                <th class="text-cl-tertiary font-light font-16 border-none">TIME<br>(Based on Student's Timezone)</th>
                                <?php } ?>
                                <!--<th class="text-cl-tertiary font-light font-16 border-none">STUDENT ATTENDANCE</th>
                                <th class="text-cl-tertiary font-light font-16 border-none">COACH ATTENDANCE</th>-->
                                <th class="text-cl-tertiary font-light font-16 border-none">RECORDED SESSIONS</th>               
                            </tr>
                        </thead>
                        <tbody>
                    <?php foreach (@$histories as $history) { 
                        $gmt_student = $this->identity_model->new_get_gmt($student_id);
                        $gmt_user = $this->identity_model->new_get_gmt($this->auth_manager->userid());

                            $new_gmt = 0;
                            $new_minutes = 0;
                        if($this->auth_manager->role() == 'STD'){
                                if($gmt_user[0]->gmt > 0){
                                $new_gmt = '+'.$gmt_user[0]->gmt;
                                }else{
                                $new_gmt = $gmt_user[0]->gmt;
                                }
                                $new_minutes = $gmt_user[0]->minutes;
                        }
                            // if($gmt_user[0]->gmt > 0){
                            //     $new_gmt = '+'.$gmt_user[0]->gmt;
                            // }else{
                            //     $new_gmt = $gmt_user[0]->gmt;    
                            // }
                        else{
                            if(@!$gmt_student){
                                if($gmt_user[0]->gmt > 0){
                                $new_gmt = '+'.$gmt_user[0]->gmt;
                                }else{
                                $new_gmt = $gmt_user[0]->gmt;    
                                }
                            }else if($gmt_student[0]->gmt > 0){
                                $new_gmt = '+'.$gmt_student[0]->gmt;
                            }else{
                                $new_gmt = $gmt_student[0]->gmt;
                            }

                            if(@!$gmt_student){
                                $new_minutes = $gmt_user[0]->minutes;
                            }else{
                                $new_minutes = $gmt_student[0]->minutes;
                            }
                        }
                    ?>
                            <tr>
                                <!-- <td><?php echo date("M j Y  H:i:s", $history->dupd); ?></td> -->
                                <td>
                                    <?php 
                                    date_default_timezone_set('UTC');
                                    $dt     = date('H:i:s',$history->dupd);
                                    $default_dt  = strtotime($dt);
                                    $usertime = $default_dt+(60*$new_minutes);
                                    $hour = date("H:i:s", $usertime); 


                                    $date     = date('M j Y',$history->dupd);
                             
                                    echo $date." ".$hour;
                                    ?>
                                </td>
                                <td>
                                    <div class="rounded-box bg-tertiary">
                                        <span class="text-cl-white">
                                            <?php echo($history->coach_name); ?></a>
                                        </span>
                                    </div>
                                </td>
                                <td><?php echo date('M j Y', strtotime($history->date)); ?></td>
                                <td>
                                    <div class="rounded-box bg-green">
                                        <span class="text-cl-white">
                                            <?php
                                                $defaultstart  = strtotime($history->start_time);
                                                $hourattstart  = date("H:i", $defaultstart);
                                                echo $hourattstart; 
                                            ?> 
                                            -
                                            <?php
                                                $defaultend  = strtotime($history->end_time);
                                                $endsession = $defaultend-(5*60);
                                                $hourattend  = date("H:i", $endsession);
                                                echo $hourattend . " (UTC " . $new_gmt .")"; 
                                            ?>
                                        </span>
                                    </div>
                                </td>
                                <!--
                                <td>
                                    <div class="status-disable">
                                        <span class="">
                                            <?php
                                                $defaultstd  = strtotime($history->std_attend);
                                                $usertimestd = $defaultstd+(60*$minutes);
                                                $hourattstd  = date("H:i:s", $usertimestd); 
                                                echo $hourattstd; 
                                            ?>    
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="status-disable">
                                        <span class="">
                                            <?php
                                                $defaultcch  = strtotime($history->cch_attend);
                                                $usertimecch = $defaultcch+(60*$minutes);
                                                $hourattcch  = date("H:i:s", $usertimecch); 
                                                echo $hourattcch; 
                                            ?>    
                                        </span>
                                    </div>
                                </td>-->

                                <td>
                                    <?php

                                    // $sessid = $history->session;
                                    // $this->load->library('downloadrecord');
                                    // @$apirecord = $this->downloadrecord->init();

                                    // @$items = @$apirecord->items;
                                    // foreach(@$items as $a){
                                    //     $sessions  = $a->sessionId;
                                    //     $url       = $a->url;

                                    //     if($sessions == @$sessid){
                                    //         $download = $url;
                                    //     }
                                    // }

                                    // print_r($download);
                                    // exit();

                                    ?>
                                    <form name ="sessiondone" action="<?php echo(site_url('opentok/checkrecord/'));?>" method="post">
                                        <input type="hidden" name="sessionid" value="<?php echo @$history->session; ?>">
                                        <input type="submit" class="pure-button btn-tiny btn-expand-tiny btn-white" value="Check Availibility">
                                    </form>
                                </td>
                            </tr>
                    <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
<?php echo @$pagination; ?>

<script type="text/javascript">
    $(function () {

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
        endDate: "now",
        autoclose:true
    });

    $('.datepicker').change(function(){
        var dates = $(this).val();
        removeDatepicker();
        $('.datepicker2').datepicker({
            format: 'yyyy-mm-dd',
            startDate: getDate(dates),
            endDate: "now",
            autoclose: true
        });
    });

    });

</script>