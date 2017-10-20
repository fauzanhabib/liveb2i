<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Book a Coach</h1>
</div>

<div class="box b-f3-2">
    <!-- <div class="heading pure-g frm-s">
        <div class="pure-u-12-24">
            <div class="h3 font-normal text-cl-secondary" style="padding: 10px 15px;">
                <?php //echo form_open('student/find_coaches/search/name', 'class="pure-form search-b-2"'); ?>
                <?php //echo form_input('search_key', set_value('search_key'), 'class="search-input" type="text" style="font-size:14px" id="search_key"'); ?>
                <?php //echo form_submit('__submit', @$this->auth_manager->userid() ? '' : 'Search', 'class="pure-button search-button" style="height:20px;"'); ?>
                <?php //echo form_close(); ?> 
            </div>
        </div>
    </div> -->
    <?php
    if (!$coaches) {
        echo '<div class="padding15"><div class="no-result">No Data</div></div>';
    }
    ?>
    <div class="content">
        <div class="box">
            <div class="pure-g">
            <?php
            //foreach ($coaches as $c) {
            //echo('<pre>');
            //print_r($coaches); exit;
            for($i=0;$i<count($coaches);$i++){
                ?>

                <div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">
                    <div class="box-info">
                        <div class="image">
                            <img src="<?php echo base_url().$coaches[$i]->profile_picture;?>" style="width:125px;height:129px" class="list-cover">
                        </div>
                        <div class="detail">
                            <span class="name"><?php echo($coaches[$i]->fullname); ?></span>
                            <div class="rating">
                                <i class="stars_full"></i>
                                <i class="stars_full"></i>
                                <i class="stars_full"></i>
                                <i class="stars_full"></i>
                                <i class="stars_full"></i>
                            </div>
                            <table>
                                <tr>
                                    <td><!--Token--> Cost</td>
                                    <td>:</td>
                                    <td><?php echo($coaches[$i]->token_for_student); ?></td>
                                </tr>
                                <tr>
                                    <td>Country</td>
                                    <td>:</td>
                                    <td><?php echo($coaches[$i]->country); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="more pure-u-1">
                            <span class="click arrow">View Schedule <i class="icon icon-arrow-down"></i></span>
                        </div>
                    </div>

                    
                    <div class="view-schedule hide">
                        <div class="box-schedule">
                            <div class="date pure-form pure-g" value="<?php echo($coaches[$i]->id);?>">
                                <div class="pure-u-2-5">
                                    <div class="frm-date">
                                    <input class="date_available datepicker frm-date" style="width:125px;" type="text" name="<?php echo($coaches[$i]->id);?>" readonly> 
                                    <span class="icon icon-date" style="left: 85px;"></span>
                                    </div>    
                                </div>
                                <div class="pure-u-3-5 text-right">
                                    <button class="weekly_schedule" value="<?php echo($coaches[$i]->id);?>">WEEKLY SCHEDULE</button>
                                </div>
                            </div>
                            
                            <form class="pure-form">
                                <div class="list-schedule">
                                    <p class="txt text-cl-primary">Please supply a date on provided textbox availability of the coach or click the weekly schedule</p>
                                    <div id="result_<?php echo($coaches[$i]->id);?>">
                                        <img src='<?php echo base_url(); ?>images/small-loading.gif' alt='loading...' style="display:none;" id="schedule-loading"/>
                                    </div>
                                </div>          
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="height-200"></div>
            </div>
        </div>
    </div>		
</div>

<script type="text/javascript">
    $(function () {
        $(document).ready(function () {

           $('.list').each(function() {
                var $dropdown = $(this);

                $(".click", $dropdown).click(function(e) {
                    e.preventDefault();

                    $schedule = $(".view-schedule", $dropdown);
                    $span = $("span", $dropdown);
                    $icon = $("span .icon", $dropdown);

                    if($($schedule).hasClass("show")) {
                        $($schedule).addClass('hide');
                        $($schedule).removeClass('show');
                        $($span).removeClass('active-schedule');
                        $($icon).removeClass('icon-flips');
                    }
                    else {
                        $($schedule).addClass('show');
                        $($schedule).removeClass('hide');
                        $($span).addClass('active-schedule');
                        $($icon).addClass('icon-flips');
                    }

                    $(".view-schedule").not($schedule).addClass('hide');
                    $(".view-schedule").not($schedule).removeClass('show');
                    $("span").not($span).removeClass('active-schedule');
                    $("span .icon").not($icon).removeClass('icon-flips');

                    return false;
                });
            });


        });

        // ajax
        // don't cache ajax or content won't be fresh
        $.ajaxSetup({
            cache: false
        });

        // load() functions
//        var loadUrl = "<?php //echo site_url('student/find_coaches/availability/2/2015-06-13'); ?>";
//        $(".loadbasic").click(function () {
//            //var index = document.getElementById("loadbasic").value;
//            //alert(this.value);
//            $("#schedule-loading").show();
//            $("#result_"+this.value).load(loadUrl, function () {
//                $("#schedule-loading").hide();
//            });
//        });
            
        $(".date_available").on('change', function() {
            //alert(this.name);
            var loadUrl = "<?php echo site_url('student_partner/schedule/availability/name').'/'.@$student_id; ?>"+ "/" +this.name+ "/" +$(this).val();
            //alert(loadUrl);
            if($(this).val() != ''){
                $("#schedule-loading").show();
                $(".txt").hide();
                $("#result_"+this.name).load(loadUrl, function () {
                    $("#schedule-loading").hide();
                });
            }
            
        });
        
        $(".weekly_schedule").click(function () {
            //alert(this.name);
            var loadUrl = "<?php echo site_url('student_partner/schedule/schedule_detail'); ?>"+ "/" +this.value;
            //alert(loadUrl);
            if(this.value != ''){
                $("#schedule-loading").show();
                $(".txt").hide();
                $("#result_"+this.value).load(loadUrl, function () {
                    $("#schedule-loading").hide();
                });
            }
            
        });

        var now = new Date();
        var day = ("0" + (now.getDate() + 2)).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);

        $('.datepicker').datepicker({
            startDate: resultDate,
            format: 'yyyy-mm-dd',
            autoclose: true,
        });

    })



</script>
