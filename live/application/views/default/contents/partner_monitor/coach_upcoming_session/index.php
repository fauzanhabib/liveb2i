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
                <li class="current"><a class="active" href="<?php echo site_url('coach/ongoing_session');?>">One to One Session</a></li>
                <li><a href="<?php echo site_url('coach/upcoming_session');?>" >Class Session</a></li>
            </ul>
        </div>
    </div>

    <div class="content tab-content padding0">
        <div id="tab1" class="tab active">

            <div class="tab-edited margin0 padding15">
                <a href="#" class="link-filter">Please select date to filter <i class="icon icon-arrow-down"></i></a>
                <?php 
                echo form_open('coach/upcoming_session/search', 'class="pure-form filter-form" style="border:none"'); 
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
            
            <h3 class="padding15">ONE-TO ONE SESSION</h3>
            
            <?php 
            $i = 1;
            if (@$data) {
            ?>
            <table id="thetable" class="table-session" style="border-top:1px solid #f3f3f3"> 
                <thead>
                    <tr>
                        <th class="padding15 sm-12 tb-ses-up">DATE</th>
                        <th class="padding15 md-none">START TIME</th>
                        <th class="padding15 md-none">END TIME</th>
                        <th class="padding15 md-none">STUDENT</th>
                        <th class="padding15 sm-12">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data as $d) {
                    $link = site_url('webex/available_host/' . @$d->id);
                    ?>
                    <tr>
                        <td class="padding-10-15 sm-12 tb-ses-up">
                            <?php echo(date('F d, Y', strtotime(@$d->date))); ?>
                            <span class="text-cl-green lg-none"><?php echo(date('H:i',strtotime(@$d->start_time)));?> - <?php echo(date('H:i',strtotime(@$d->start_time)));?></span>
                            <span class="lg-none">
                                Student :<br>
                                <a href="<?php echo site_url('coach/upcoming_session/student_detail/' . $d->student_id); ?>" class="text-cl-secondary">
                                    <?php echo @$d->student_name; ?>
                                </a>
                            </span>
                        </td>
                        <td class="time padding-10-15 md-none"><?php echo(date('H:i',strtotime(@$d->start_time)));?></td>
                        <td class="time padding-10-15 md-none"><?php echo(date('H:i',strtotime(@$d->end_time)));?></td>
                        <td class="padding-10-15 md-none">
                            <a href="<?php echo site_url('coach/upcoming_session/student_detail/' . $d->student_id); ?>">
                                <?php echo @$d->student_name; ?>
                            </a>
                        </td>
                        <td class="padding-10-15 sm-12">
                            <a href="<?php echo site_url('coach/coach_vrm/single_student/'. $d->id); ?>" class="pure-button btn-medium btn-primary-border">Progress <div class="md-none" style='display:inline-block'>Report</div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else {
                echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
            } ?>

            <h3 class="padding15">CLASS SESSION</h3>
            <?php
            $j = 1;
            if (@$data_class) {
            ?>    
            <table id="thetable2" class="table-session" style="border-top:1px solid #f3f3f3"> 
                <thead>
                    <tr>
                        <th class="padding15 sm-12 tb-ses-up">DATE</th>
                        <th class="padding15 md-none">START TIME</th>
                        <th class="padding15 md-none">END TIME</th>
                        <th class="padding15 md-none">CLASS</th>
                        <th class="padding15 sm-12">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data_class as $d) {
                    $link = site_url('webex/available_host/c' . @$d->id);
                    ?>
                    
                    <tr>
                        <td class="padding-10-15 tb-ses-up sm-12">
                            <?php echo(date('F d, Y', strtotime(@$d->date))); ?>
                            <span class="text-cl-green lg-none"><?php echo(date('H:i',strtotime(@$d->start_time)));?> - <?php echo(date('H:i',strtotime(@$d->start_time)));?></span>
                            <span class="lg-none">
                                Student :<br>
                                <a href="#" class="text-cl-secondary">
                                    <?php echo @$d->class_name; ?>
                                </a>
                            </span>
                        </td>
                        <td class="time padding-10-15 md-none"><?php echo(date('H:i',strtotime(@$d->start_time)));?></td>
                        <td class="time padding-10-15 md-none"><?php echo(date('H:i',strtotime(@$d->end_time)));?></td>
                        <td class="padding-10-15 md-none"><a href="#"><?php echo @$d->class_name; ?></a></td>
                        <td class="padding-10-15 sm-12">
                            <a href="<?php echo site_url('coach/coach_vrm/multiple_student/'. @$d->class_id); ?>" class="pure-button btn-medium btn-expand-tiny btn-primary-border">Progress <div class="md-none" style='display:inline-block'>Report</div></a>
                        </td>
                    </tr>

                    <?php } ?>
                </tbody>
            </table>
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