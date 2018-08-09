<?php if($this->auth_manager->role() == 'RAD') {
    $role_link = "superadmin";
} else {
    $role_link = "admin";
}

?>

<div class="heading text-cl-primary border-b-1 padding15">
    <h1 class="margin0">Coach Sessions</h1>
</div>

    <div class="box clear-both">
    <div class="heading pure-g padding-t-30">
        <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
            <ul class="pure-menu-list">
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url($role_link.'/coach_upcoming_session/one_to_one_session/'.@$coach_id);?>">Upcoming Session</a></li>
                <!-- <li><a href="<?php echo site_url('admin_m/student_upcoming_session/class_session/'.@$student_id);?>" >Class Session</a></li> -->
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url($role_link.'/coach_histories/index/'.@$coach_id);?>" >Session Histories</a></li>
                <!-- <li><a href="<?php echo site_url('admin_m/student_histories/class_session/'.@$student_id);?>" >Class Session Histories</a></li> -->
            </ul>
        </div>
    </div>


            <div class="margin0 padding15">
                <a href="#" class="link-filter">Please select date to filter <i class="icon icon-arrow-down"></i></a>
                <?php 
                echo form_open($role_link.'/coach_upcoming_session/search/one_to_one/'.@$coach_id, 'class="pure-form filter-form" style="border:none"'); 
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
            <table id="userTable" class="table-session" style="border-top:1px solid #f3f3f3"> 
                <thead>
                <tr>
                    <th class="text-cl-tertiary font-light font-16 border-none">DATE</th>
                        <th class="text-cl-tertiary font-light font-16 border-none">TIME<br>(Based on Coach's Timezone)</th>
                        <th class="text-cl-tertiary font-light font-16 border-none">STUDENT</th>
                        <th class="text-cl-tertiary font-light font-16 border-none">ACTION</th>               
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
                <tr>
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
                    <td>
                        <a href="<?php echo site_url($role_link.'/vrm/single_student/'. $d->student_id); ?>" class="pure-button btn-expand-tiny btn-primary-border">Progress <div class="md-none" style='display:inline-block'>Report</div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php } else {
                echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
            } ?>
        </div>
    </div>
    <?php echo @$pagination;?>  
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.tablescroll.js"></script>

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
    
    $(function() {
        $('#thetable').tableScroll({height:200});
        $('#thetable2').tableScroll({height:200})
    });
</script>