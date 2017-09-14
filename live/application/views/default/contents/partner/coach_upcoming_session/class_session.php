<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Sessions</h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-1 tab-list tab-link">
            <ul>
                <li><a href="<?php echo site_url('partner/coach_upcoming_session/one_to_one_session');?>">One to One Session</a></li>
                <li class="current"><a class="active" href="<?php echo site_url('partner/coach_upcoming_session/class_session');?>" >Class Session</a></li>
                <li><a href="<?php echo site_url('partner/coach_histories');?>" >Session Histories</a></li>
                <li><a href="<?php echo site_url('partner/coach_histories/class_session');?>" >Class Session Histories</a></li>
            </ul>
        </div>
    </div>

    <div class="content tab-content padding0">
        <div id="tab1" class="tab active">

            <div class="tab-edited margin0 padding15">
                <a href="#" class="link-filter">Please select date to filter <i class="icon icon-arrow-down"></i></a>
                <?php 
                echo form_open('partner/coach_upcoming_session/search/class', 'class="pure-form filter-form" style="border:none"'); 
                ?>
                <div class="pure-g">
                    <div class="pure-u-1">
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_from" class="datepicker frm-date margin0" type="text" readonly="" placeholder="Start Date">  
                            <span class="icon icon-date"></span>
                        </div>
                        <span class="to" style="font-size: 16px;margin:0px 10px;">to</span>  
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_to" class="datepicker2 frm-date margin0" type="text" readonly="" placeholder="End Date">  
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
            <table class="table-session"> 
                <thead>
                    <tr>
                        <th class="padding15">DATE</th>
                        <th class="padding15">TIME</th>
                        <th class="padding15">CLASS</th>
                        <th class="padding15">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data_class as $d) {
                    $link = site_url('webex/available_host/c' . @$d->id);
                    ?>
                    
                    <tr class="list-session">
                        <td class="padding15" data-label="DATE">
                            <?php echo(date('F d, Y', strtotime(@$d->date))); ?>
                        </td>
                        <td class="padding15" data-label="TIME">
                            <span class="time"><?php echo(date('H:i',strtotime(@$d->start_time)));?> - <?php echo(date('H:i',strtotime(@$d->end_time)));?></span>
                        </td>
                        <td class="padding15" data-label="CLASS"><?php echo @$d->class_name; ?></td>
                        <td class="padding15 ">
                            <div class="rw">
                                <div class="b-50">
                                    <a onclick="confirmation('<?php echo site_url('partner/managing/set_class_schedule/'.@$d->class_id.'/'.@$d->date.'/re/'.@$d->id);?>', 'group', 'Reschedule', 'list-session', 'rescheduled');" class="pure-button btn-medium btn-white rescheduled">Reschedule</a>
                                </div>    
                                <div class="b-50">
                                    <a onclick="confirmation('<?php echo site_url('partner/managing/cancel_session/'.@$d->class_id.'/'.@$d->id);?>', 'group', 'Cancel', 'list-session', 'cancel');" class="pure-button btn-medium btn-red cancel">Cancel</a>
                                </div>
                            </div>
                            <div class="b-100">        
                                <a href="<?php echo site_url('partner/vrm/multiple_student/'. @$d->class_id); ?>" class="pure-button btn-medium btn-white">Progress Report</a>
                            </div>
                        </td>
                    </tr>

                    <?php } ?>
                </tbody>
            </table>
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
<?php echo @$pagination;?>
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

    $('.rescheduled').click(function(){
        return false;
    });

    $('.cancel').click(function () {
        return false;
    });

    $('.height-plus').css({'height':'50px'});
    
    $(function() {
        $('#thetable').tableScroll({height:200});
        $('#thetable2').tableScroll({height:200})
    });
</script>