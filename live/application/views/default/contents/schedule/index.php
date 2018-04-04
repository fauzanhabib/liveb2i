<?php
    $day = array(
        'Monday' => 'monday',
        'Tuesday' => 'tuesday',
        'Wednesday' =>'wednesday',
        'Thursday' => 'thursday',
        'Friday' => 'friday',
        'Saturday' => 'saturday',
        'Sunday' => 'sunday'
    );
?>


<div class="heading text-cl-primary border-b-1 padding15">

    <h2 class="margin0">Schedules</h2>

</div>

<div class="box clear-both">
    <div class="heading pure-g padding-t-30">

        <div class="left-list-tabs pure-menu pure-menu-horizontal margin0">
            <ul class="pure-menu-list">
                <li class="pure-menu-item pure-menu-selected text-center width150 no-hover"><a href="<?php echo site_url('coach/schedule');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue">Schedules</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width150 no-hover"><a href="<?php echo site_url('coach/day_off');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Days Off</a></li>
            </ul>
        </div>

    </div>

    <div class="content">
        <div class="box">

            <div class="pure-g hdr-table">
                <div class="pure-u-5-24">Days</div>
                <div class="pure-u-8-24">Schedules</div>
                <div class="pure-u-3-24">Timezone</div>
                <div class="pure-u-6-24 text-center">&nbsp;&nbsp;&nbsp;&nbsp;Action</div>
            </div>
            <div class="box schedule-box">
                <?php foreach($day as $d => $value){ ?>
                <div class="pure-g list">
                    
                    <?php echo form_open('coach/schedule/update/'.$value, 'role="form" style="width:100%" method="post" accept-charset="utf-8"'); ?>
                    <div class="pure-u-5-24 day"><?php echo ($d);?></div>
                    <div class="pure-u-8-24 date">
                        <?php
                    for($i=0; $i<count(@$schedule[$value]); $i++){ 
                    ?>
                        <div class="block-date disable" style="width: 100%; display: block;">
                            <span class="text">Schedule <?php echo($i+1);?></span>
                            <span class="time"><?php echo(date('H:i',strtotime($schedule[$value][$i]['start_time'])));?></span>
                            <span>to</span>
                            <span class="time"><?php echo(date('H:i',strtotime($schedule[$value][$i]['end_time'])));?></span>
                        </div>

                        <div class="block-date edited" style="display: none; width: 100%<?php echo($i == 0 ? '':';margin-top:10px;');?>">
                            <span class="text">Schedule <?php echo($i+1);?></span>
                            <div class="select-time frm-time">
                                <?php echo form_input('start_time_'.$i, set_value('start_time_'.$i, set_value('start_time_'.$i, @$schedule[$value][$i]['start_time'])), "class='timepicker' readonly id= start_time_".$i) ?>                           
                                <span class="icon icon-time"></span>
                            </div>
                            <span>to</span>
                            <div class="select-time frm-time">
                                <?php echo form_input('end_time_'.$i, set_value('end_time_'.$i, @$schedule[$value][$i]['end_time']), "class='timepicker' readonly id= end_time_".$i) ?>                           
                                <span class="icon icon-time"></span>
                            </div>
                            <?php echo($i == 0 ? '<span class="addmore">Add More</span>' : '<span class="remove-field" style="display:inline-block"> Remove</span>');?>                    
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="pure-u-3-24 nw-timezone">
                        <?php echo "UTC ".$gmt_val;?>
                    </div>
                    <div class="pure-u-6-24 edit aa" style="display: inline-block;">
                        <span class="link-edit">Edit</span>
                    </div>
                    <div class="pure-u-6-24 update" style="display: none;">
                        <?php echo form_submit('__submit', 'UPDATE','class="pure-button btn-small btn-tertiary update" style="display: none;"'); ?>                
                        <button type="button" class="pure-button btn-small btn-red cancel">CANCEL</button>
                    </div>
                    <?php echo form_close(); ?>  
                </div>
                <?php } ?>
                </div>
            	</div>
        	</div>
    	</div>
	</div> 
</div> 

        <script type="text/javascript">

                var coach_session_duration = '<?php echo $session_duration;?>';
                var convert_coach_session_duration = parseInt(coach_session_duration);


                var set_coach_session_duration = convert_coach_session_duration + 5;

            function timepicker_select() {
                $('.timepicker').timepicker({
                    defaultTime:false,
                    minuteStep:set_coach_session_duration,
                    showMeridian:false,
                    disableFocus:true,
                    showInputs: false,
                    disableMousewheel: true
                });
            }

            $(document).ready(function(){



                /**
                $('.select-time').each(function(){

                    var $dropdown = $(this);

                    $("input", $dropdown).click(function(){

                        var $time = $('.timepicker', $dropdown);

                        $time.timepicker({
                            defaultTime:false,
                            minuteStep:30,
                            showMeridian:false,
                            disableFocus:true,
                            showInputs: false,
                            disableMousewheel: true
                        });

                        $('.timepicker').not($time).timepicker({
                            disable:true
                        });


                    }); 
                        
                });**/

                $('.timepicker').timepicker({
                    defaultTime:false,
                    minuteStep:set_coach_session_duration,
                    showMeridian:false,
                    disableFocus:true,
                    showInputs: false,
                    disableMousewheel: true
                });

                var dvjQuery = null;
                var isEnabled = true;

                $('.list').each(function() {
                    var $dropdown = $(this);

                    $removef = $('.remove-field',$dropdown);
                    var parent = $(".block-date.disable", $dropdown).parent();
                    var st=$('#start_time_0', $dropdown).val();
                    var st_=$('#start_time_1', $dropdown).val();
                    var et=$('#end_time_0', $dropdown).val();
                    var et_=$('#end_time_1', $dropdown).val();

                    /**
                    $(".block-date input").bind('timepicker', function(event){ 
                        event.stopPropagation();
                        event.preventDefault();
                        $('.timepicker').timepicker({
                            defaultTime:false,
                            minuteStep:30,
                            showMeridian:false,
                            disableFocus:true,
                            showInputs: false,
                            disableMousewheel: true
                         });
                    });**/

                    /**

                    $('.select-time input', $dropdown).click(function(event){

                        if($(event.target).is('.timepicker')) {
                            $('.timepicker', $dropdown).timepicker({
                                defaultTime:false,
                                minuteStep:30,
                                showMeridian:false,
                                disableFocus:true,
                                showInputs: false,
                                disableMousewheel: true
                            });
                        }
                        else {
                            $('.timepicker', $dropdown).timepicker({
                                disableTimepicker:true,
                                template:'nope',
                            });
                            alert('no');
                        }

                    });

                    **/



                    $(".link-edit", $dropdown).click(function(e) {

                        //console.log(active);

                        if (isEnabled == true) {
                            e.preventDefault();

                            isEnabled = false;

                            var $edit=$(".edit", $dropdown),
                                $update = $(".update", $dropdown),
                                $addmore = $(".addmore", $dropdown),
                                $block_input = $(".block-date input", $dropdown),
                                $block_edit = $(".block-date.edited", $dropdown),
                                $block_disable = $(".block-date.disable", $dropdown),
                                $rmclass = $(".rm-class", $dropdown);
                      

                            $edit.hide();
                            $update.css('display','inline-block');
                            $addmore.css('display','inline-block');
                            $block_disable.hide();
                            $block_edit.show();
                            //$block_input.addClass('tm'); 
                            //$block_input.addClass('timepicker'); 
                            //$update.show();

                            $(".edit").not($edit).show();
                            $(".update").not($update).css('display','none');
                            $(".addmore").not($addmore).css('display','none')
                            $(".rm-class").not($rmclass).hide();
                            $(".block-date.edited").not($block_edit).hide();
                            $(".block-date.disable").not($block_disable).show();
                            //$(".block-date input").not($block_input).val('');

                            if($block_input.length > 2) {
                                $addmore.hide();
                            }

                            return false;
                        }

                        
                    });

                    $(".cancel", $dropdown).click(function(e) {


                        isEnabled = true;

                        var i = $('.block-date.disable', $dropdown).length;
                        var z = $('.block-date.edited', $dropdown).length;

                        //var st=$('#start_time_1', $dropdown).val();
                        //var et=$('#end_time_1', $dropdown).val();


                        console.log(i);

                        if (i > 1 ) {
                            parent.append(dvjQuery);

                            console.log($('#start_time_1', $dropdown).val());
                            
                            $('#start_time_0', $dropdown).val(st);
                            $('#end_time_0', $dropdown).val(et);
                            $('#start_time_1', $dropdown).val(st_);
                            $('#end_time_1', $dropdown).val(et_);
                        }
                        else {

                            if (z > 1) {
                                $('.block-date.edited', $dropdown).last().remove();
                            }

                            $('#start_time_0', $dropdown).val(st);
                            $('#end_time_0', $dropdown).val(et);

                            dvjQuery = null;
                        }

                        

                        e.preventDefault();

                        $(".edit", $dropdown).show();
                        $(".update", $dropdown).css('display','none'); 
                        $(".addmore", $dropdown).hide();
                        $(".block-date.edited", $dropdown).hide();
                        $('.block-date.disable', $dropdown).show();

                        return false;
                    });

                    var max_field = 3;
                    var addmore = '.addmore';

                    $(addmore, $dropdown).click(function(e) {
                        var i = $('.block-date.edited', $dropdown).length;
                        var ii = i+0;
                        $wrapper = $(".date", $dropdown);
                        
                        if (i >= max_field) {
                            $(addmore, $dropdown).hide();
                        e.preventDefault();
                            i++;
                            $($wrapper).append('<div class="block-date edited" style="width:100%;margin-top:10px;"><span>Schedule '+ii+' </span><div class="select-time frm-time">\n\
                            <input type="text" name="start_time_'+ii+'" value="0:00" class="timepicker" onmousemove="timepicker_select()"  readonly id= start_time_1 />\n\
                            <span class="icon icon-time"></span></div><span> to </span><div class="select-time frm-time">\n\
                            <input type="text" name="end_time_'+ii+'" value="0:00" class="timepicker" onmousemove="timepicker_select()"  readonly id= end_time_1 />\n\
                            <span class="icon icon-time"></span></div> <span class="remove-field" style="display:inline-block"> Remove</span></div>');
                        } else {
                            $($wrapper).append('<div class="block-date edited" style="width:100%;margin-top:10px;"><span>Schedule '+ii+' </span><div class="select-time frm-time">\n\
                            <input type="text" name="start_time_'+ii+'" value="0:00" class="timepicker" onmousemove="timepicker_select()"  readonly id= start_time_1 />\n\
                            <span class="icon icon-time"></span></div><span> to </span><div class="select-time frm-time">\n\
                            <input type="text" name="end_time_'+ii+'" value="0:00" class="timepicker" onmousemove="timepicker_select()"  readonly id= end_time_1 />\n\
                            <span class="icon icon-time"></span></div> <span class="remove-field" style="display:inline-block"> Remove</span></div>');
                        }
                        $(".block-date.edited", $dropdown).show();
                        $(".remove-field", $dropdown).show();
                    });

                    $("body").on("click", ".remove-field", function (e) {

                        //st = $('#start_time_1', $dropdown).val();
                        //et = $('#end_time_1', $dropdown).val();

                        $(addmore, $dropdown).show();
                        var i = $('.block-date.edited', $dropdown).length;
                        e.preventDefault();
                        dvjQuery = $(this).parent("div").detach();

                        

                        // console.log(st);
                        // console.log(et);

                        //dvjQuery = $('.vasd', _each).detach();
                        i--;
                    });
                });
                /**
                $('.box .tab-link a').click(function(e){
                    var currentValue = $(this).attr('href');
            
                    $('.box .tab-link a').removeClass('active');
                    $('.tab').removeClass('active');

                    $(this).addClass('active');
                    $(currentValue).addClass('active');

                    e.preventDefault();

                })
                **/

            })
        </script>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".listTop > a").prepend($("<span/>"));
                $(".listBottom > a").prepend($("<span/>"));
                $(".listTop, .listBottom").click(function(event){
                 event.stopPropagation(); 
                 $(this).children("ul").slideToggle();
               });
            });
        </script>
<!--        <a href="https://idbuild.id.dyned.com/live_v20/index.php/lang_switch/switch_language/english">English</a>
        <a href="https://idbuild.id.dyned.com/live_v20/index.php/lang_switch/switch_language/traditional-chinese">Chinese</a>-->
