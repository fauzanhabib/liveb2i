
    <div class="heading text-cl-primary border-b-1 padding15">

        <h2 class="margin0">Coach Profile</h2>

    </div>
    <div class="box">
        <div class="content">
            <div class="box pure-g">

                <!-- block photo -->
                <div class="pure-u-6-24 text-center profile-image">
                    <div class="border-none">
                        <img src="<?php echo base_url() . @$data[0]->profile_picture; ?>" width="200" height="200" class="pure-img fit-cover img-circle-big" />
                        <h4 class="text-cl-tertiary line-height0"><?php echo(@$data[0]->fullname); ?></h4>
                        <h5 class="font-12"><!-- Bandung, Indonesia --></h5>
                    </div>

                    <div class="padding-t-10 m-b-15 text-center">
                        <div class="padding10" style="width:85%;margin:0 auto">
                            <!-- <a href="<?php echo site_url('student_partner_monitor/vrm/single_student/'.@$student[0]->id);?>" class="pure-button btn-small btn-white m-t-10">VIEW REPORT</a> -->
                            <a href="<?php echo site_url('partner_monitor/coach_upcoming_session/one_to_one_session/'.@$coach_id);?>" class="pure-button btn-small btn-white m-t-10" >SESSIONS</a>
                            <!-- <a href="<?php echo site_url('student_partner_monitor/member_list/add_token/'.@$student[0]->id).'/'.$subgroup_id;?>" class="pure-button btn-small btn-white m-t-10">ADD TOKEN</a> -->
                        </div>
                    </div>

                </div>
                <!-- end block photo -->


                <div class="pure-u-18-24 profile-detail prelative">
                    <div class="heading m-b-15">
                        <div class="pure-u-1">
                            <h3 class="h3 font-normal padding-b-15 text-cl-tertiary">Schedule</h3>
                        </div>
                    </div>

                    <div class="date pure-form pure-g" style="border-bottom:1px solid #f3f3f3;padding-bottom:5px;">
                        <div class="pure-u-2-5">
                            <div class="frm-date" style="display:inline-block">
                                <input class="date_available datepicker frm-date" type="text" name="<?php echo @$data[0]->id?>" readonly="" style="width:125px;">
                                <span class="icon dyned-icon-coach-schedules active-schedule"></span>
                            </div>
    <!--                        <div class="selected-date margin0 text-cl-secondary" style="display:inline-block">Tuesday, August 25, 2015</div>-->
                        </div>
                        <div class="pure-u-3-5 text-right">
                            <button class="weekly_schedule pure-button btn-green btn-expand btn-small padding0" value="<?php echo(@$data[0]->id); ?>">WEEKLY SCHEDULE</button>
                        </div>
                    </div>

                    <form class="pure-form">
                        <div class="list-schedule" style="color:#939393;height: 150px;overflow-y: auto;margin-top:5px;">
                            <p class="txt text-cl-primary">Click in the box for calendar or on Weekly Schedule to see your coach’s availability</p>
                            <div id="result_<?php echo(@$data[0]->id); ?>" style="width: 80%;">
                                <img src='<?php echo base_url(); ?>assets/images/small-loading.gif' alt='loading...' style="display:none;" id="schedule-loading"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<form action="<?php echo site_url('account/identity/update/more_info');?>" id="form_more_info" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <div class="box clearfix">
        <div class="content-title m-lr-20">
            More Info
        </div>

        <div class="content">
            <div class="pure-u-24-24 prelative">
                <table class="table-no-border2">
                    <tbody>
<!--                         <tr>
                            <td class="pad15">Skype ID</td>
                            <td>
                                <?php echo(@$data[0]->skype_id); ?>
                            </td>
                        </tr> -->
                        <tr>
                            <td class="pad15">Fullname</td>
                            <td>
                                <?php echo(@$data[0]->fullname); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Timezone</td>
                            <td>
                                <?php echo(@$user_tz); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Spoken Language</td>
                            <td>
                                <?php echo(str_replace('#', ', ', @$data[0]->spoken_language)); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </form>
 <!--    <div class="box clearfix padding-t-40">
        <div class="content-title m-lr-20">
            Education
        </div>
        <div class="content">
            <div class="pure-u-24-24 prelative">
                <table class="table-no-border2">
                    <tbody>
                        <tr>
                            <td class="pad15">Higher Education</td>
                            <td class="pad15">
                                <span class="r-only block-school"><?php echo(@$data[0]->higher_education); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Undergraduate</td>
                            <td class="pad15">
                                <span class="r-only block-school"><?php echo(@$data[0]->undergraduate); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Masters</td>
                            <td class="pad15">
                                <span class="r-only block-school"><?php echo(@$data[0]->masters); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Phd</td>
                            <td class="pad15">
                                <span class="r-only block-school"><?php echo(@$data[0]->phd); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Teaching Credintial</td>
                            <td>
                                <?php echo(@$data[0]->teaching_credential); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Dyned English Certification</td>
                            <td>
                                <?php echo(@$data[0]->dyned_certification_level); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Year of Experience</td>
                            <td>
                                <?php echo(@$data[0]->year_experience); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Specialization</td>
                            <td>
                                <?php echo(@$data[0]->special_english_skill); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div> -->

<!-- ======================= -->
<!-- <div class="heading text-cl-primary pad-t-10">

    <h1 class="margin0 padding-l-30 left">Profile</h1>

</div> -->

<script type="text/javascript">
    $(function () {
        $(document).ready(function () {

            $('.list').each(function () {
                var $dropdown = $(this);

                $(".click", $dropdown).click(function (e) {
                    e.preventDefault();

                    $schedule = $(".view-schedule", $dropdown);
                    $span = $("span", $dropdown);
                    $icon = $("i", $dropdown);

                    if ($($schedule).hasClass("show")) {
                        $($schedule).addClass('hide');
                        $($schedule).removeClass('show');
                        $($span).removeClass('active-schedule');
                        $($icon).addClass('icon-arrow-down');
                        $($icon).removeClass('icon-arrow-up');
                    }
                    else {
                        $($schedule).addClass('show');
                        $($schedule).removeClass('hide');
                        $($span).addClass('active-schedule');
                        $($icon).addClass('icon-arrow-up');
                        $($icon).removeClass('icon-arrow-down');
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
//        var loadUrl = "<?php //echo site_url('student/find_coaches/availability/2/2015-06-13');  ?>";
//        $(".loadbasic").click(function () {
//            //var index = document.getElementById("loadbasic").value;
//            //alert(this.value);
//            $("#schedule-loading").show();
//            $("#result_"+this.value).load(loadUrl, function () {
//                $("#schedule-loading").hide();
//            });
//        });

        $(".date_available").on('change', function () {
            //alert(this.name);
            var loadUrl = "<?php echo site_url('partner_monitor/find_coaches/availability/name'); ?>" + "/" + this.name + "/" + $(this).val();
                var m = $('[id^=result_]').html($('[id^=result_]').val());

            if ($(this).val() != '') {
                $("#schedule-loading").show();
                $(".txt").hide();
                $("#result_" + this.name).load(loadUrl, function () {
                    for(i=0; i<m.length; i++){
                        $('#'+m[i].id).html($('#'+m[i].id).html().replace('/*',' '));
                        $('#'+m[i].id).html($('#'+m[i].id).html().replace('*/',' '));
                    }
                    $("#schedule-loading").hide();
                    $('.selected-date').css({'margin-top':'-5px'});
                });
            }

        });

        $(".weekly_schedule").click(function () {
            //alert(this.name);
            var loadUrl = "<?php echo site_url('partner_monitor/find_coaches/schedule_detail'); ?>" + "/" + this.value;
            var m = $('[id^=result_]').html($('[id^=result_]').val());

            //alert(loadUrl);
            if (this.value != '') {
                $("#schedule-loading").show();
                $(".txt").hide();
                $("#result_" + this.value).load(loadUrl, function () {
                    for(i=0; i<m.length; i++){
                        $('#'+m[i].id).html($('#'+m[i].id).html().replace('/*',' '));
                        $('#'+m[i].id).html($('#'+m[i].id).html().replace('*/',' '));
                    }
                    $("#schedule-loading").hide();
                    $('.table-reschedule th').css({'border-top':'none'});
                });
            }

        });

        var now = new Date();
        var day = ("0" + (now.getDate() + 1)).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);

        $('.datepicker').datepicker({
            startDate: resultDate,
            format: 'yyyy-mm-dd',
            autoclose: true,
        });

        // $('.rating').raty({
        //     readOnly: true,
        //     starHalf: 'icon icon-star-half',
        //     starOff: 'icon icon-star-full color-grey',
        //     starOn: 'icon icon-star-full',
        //     starType: 'i',
        //     score: function () {
        //         return $(this).attr('data-score');
        //     }
        // });



    })



</script>
