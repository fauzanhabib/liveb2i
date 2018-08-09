<?php if($this->auth_manager->role() == 'RAD') {
    $role_link = "superadmin";
} else {
    $role_link = "admin";
}

?>
<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Upcoming Session</h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <!--
        <div class="pure-u-12-24">
            <h3 class="h3 font-normal padding15 text-cl-secondary">SCHEDULE SESSION</h3>
        </div>
        -->
        <div class="pure-u-1 tab-list tab-link">
            <ul>
                <li><a href="<?php echo site_url('admin_m/student_upcoming_session/one_to_one_session/'.@$student_id);?>">One to One Session</a></li>
                <li class="current"><a class="active" href="<?php echo site_url('admin_m/student_upcoming_session/class_session/'.@$student_id);?>" >Class Session</a></li>
                <li><a href="<?php echo site_url('admin_m/student_histories/index/'.@$student_id);?>" >Session Histories</a></li>
                <li><a href="<?php echo site_url('admin_m/student_histories/class_session/'.@$student_id);?>" >Class Session Histories</a></li>
            </ul>
        </div>
    </div>

    <div class="content tab-content padding0">
        <div id="tab1" class="tab active">

            <div class="tab-edited margin0 padding15">
                <a href="#" class="link-filter">Please select date to filter <i class="icon icon-arrow-down"></i></a>
                <?php 
                echo form_open('admin_m/student_upcoming_session/search/class/'.@$student_id, 'class="pure-form filter-form" style="border:none"'); 
                ?>
                <div class="pure-g">
                    <div class="pure-u-1">
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_from" class="datepicker frm-date margin0" type="text" readonly="">  
                            <span class="icon icon-date"></span>
                        </div>
                        <span style="font-size: 16px;margin:0px 10px;">to</span>  
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_to" class="datepicker2 frm-date margin0" type="text" readonly="">  
                            <span class="icon icon-date"></span>
                        </div>
                        <?php echo form_submit('__submit', 'Go','class="pure-button btn-small btn-primary" style="margin:0px 10px;"'); ?>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            
            <?php
            $j = 1;
            if (@$data_class) {
            ?>    
            <div class="b-pad">
            <table class="table-session" style="border-top:1px solid #f3f3f3"> 
                <thead>
                    <tr>
                        <th class="padding15tb-ses-up">DATE</th>
                        <th class="padding15">START TIME</th>
                        <th class="padding15">END TIME</th>
                        <th class="padding15">CLASS</th>
                        <th class="padding15">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data_class as $d) {
                    $link = site_url('webex/available_host/c' . @$d->id);
                    ?>
                    
                    <tr>
                        <td class="padding-10-15 tb-ses-up" data-label="DATE">
                            <?php echo(date('F d, Y', strtotime(@$d->date))); ?>
                        </td>
                        <td class="padding-10-15" data-label="START TIME"><span class="text-cl-green"><?php echo(date('H:i',strtotime(@$d->start_time)));?></span></td>
                        <td class="padding-10-15" data-label="END TIME"><span class="text-cl-green"><?php echo(date('H:i',strtotime(@$d->end_time)));?></span></td>
                        <td class="padding-10-15" data-label="CLASS"><?php echo @$d->class_name; ?></td>
                        <td class="padding-10-15 t-center">
                            <a href="<?php echo site_url('admin_m/vrm/multiple_student/'. @$d->class_id); ?>" class="pure-button btn-medium btn-expand-tiny btn-primary-border">Progress Report</a>
                        </td>
                    </tr>

                    <?php } ?>
                </tbody>
            </table>
            </div>
            <?php echo @$pagination;?>
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

<script>
    // ajax
    // don't cache ajax or content won't be fresh
    $.ajaxSetup({
        cache: false
    });

    <?php date_default_timezone_set('Etc/GMT'.(-$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60));?>

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