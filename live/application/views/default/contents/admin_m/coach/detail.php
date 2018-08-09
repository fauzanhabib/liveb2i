<?php if($this->auth_manager->role() == 'RAD') {
    $role_link = "superadmin";
} else {
    $role_link = "admin";
}

?>

<div class="heading text-cl-primary padding15">

    <div class="breadcrumb-tabs pure-g">
        <div class="left-breadcrumb">
            <ul class="breadcrumb toolbar padding-l-0">
                <li id="breadcrum-home"><a href="#">
                    <div id="home-icon">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">
                        <g>
                            <path d="M2.7,14.1c0,0,0,0.3,0.3,0.3c0.4,0,3.7,0,3.7,0l0-3c0,0-0.1-0.5,0.4-0.5h1.5c0.6,0,0.5,0.5,0.5,0.5l0,3
                                c0,0,3.1,0,3.6,0c0.4,0,0.4-0.4,0.4-0.4V8.5L8.1,4L2.7,8.5L2.7,14.1z"/>
                            <path d="M0.7,8.1c0,0,0.5,0.8,1.5,0l5.9-5l5.6,5c1.2,0.8,1.6,0,1.6,0L8.1,1.5L0.7,8.1z"/>
                            <polygon points="13.6,3 12.1,3 12.1,4.8 13.6,6  "/>
                        </g>
                        </svg>
                    </div>
                </a></li>
                <?php if ($role_link=="superadmin"){ ?>
                <li><a href="<?php echo site_url($role_link.'/region/index/active');?>">Regions</a></li>
                <?php }else{ ?>
                <li><a href="<?php echo site_url($role_link.'/manage_partner');?>">Affiliates</a></li>
                <?php } ?>
                <li><a href="<?php echo site_url($role_link.'/manage_partner/detail/'.$partner_id); ?>"><?php echo $partner->name;?></a></li>
                <li><a href="#">Coach</a></li>
<!--                 <li>
                    <form action="" autocomplete="on" class="search-box">
                      <input id="search" name="search" type="text" placeholder="Type here.."><input id="search_submit" value="Rechercher" type="submit">
                    </form>
                </li> -->
            </ul>
        </div>
    </div>

    <h1 class="margin0 left padding-r-10"><?php echo(@$data[0]->fullname); ?> <em class="font-26">(Coach Session)</em></h1>

    <div class="btn-goBack padding-t-5">
        <button class="btn-small border-1-blue bg-white-fff" onclick="goBack()">
            <div class="left padding-r-5">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve" class="width15">
                <g id="back-one-page">
                    <g>
                        <g id="XMLID_13_">
                            <path style="fill-rule:evenodd;clip-rule:evenodd;" d="M20,0c11.046,0,20,8.954,20,20s-8.954,20-20,20S0,31.046,0,20
                                S8.954,0,20,0z M37.002,20c0-9.39-7.612-17.002-17.002-17.002C10.611,2.998,2.998,10.61,2.998,20
                                c0,9.389,7.613,17.002,17.002,17.002C29.39,37.002,37.002,29.389,37.002,20z"/>
                        </g>
                        <g>
                            <g>
                                <path style="fill:#231F20;" d="M27.734,22.141H13.636c-1.182,0-2.141-0.958-2.141-2.141s0.959-2.141,2.141-2.141h14.098
                                    c1.182,0,2.141,0.958,2.141,2.141S28.916,22.141,27.734,22.141z"/>
                            </g>
                            <g>
                                <g>
                                    <path style="fill:#231F20;" d="M19.465,24.27l-2.611-2.822c-0.756-0.818-0.756-2.08,0-2.897l2.611-2.822
                                        c1.264-1.366,0.295-3.582-1.566-3.582h-0.353c-0.595,0-1.162,0.248-1.566,0.685l-5.288,5.719c-0.756,0.817-0.756,2.079,0,2.896
                                        l5.288,5.719c0.404,0.437,0.971,0.685,1.566,0.685h0.353C19.76,27.852,20.729,25.636,19.465,24.27z"/>
                                </g>
                            </g>
                        </g>
                    </g>
                </g>
                <g id="Layer_1">
                </g>
                </svg>
            </div>
            Go Back One Page
        </button>
    </div>
</div>

<div class="box clear-both">
    <div class="heading pure-g border-none">

    </div>

    <div class="content border-none">

        <div class="pure-u-6-24 text-center profile-image">
                <img src="<?php echo base_url() . @$data[0]->profile_picture; ?>" width="200" height="200" class="pure-img fit-cover" />

                <div class="save-cancel-btn">
                    <a href="<?php echo site_url($role_link.'/coach_upcoming_session/one_to_one_session/'.$coach_id);?>">
                    <!-- <a href="#"> -->
                        <button class="pure-button btn-tertiary btn-small-long">Sessions</button>
                    </a>
                    <?php if($this->auth_manager->role() == 'ADM') { ;?>
                        <!-- <button class="pure-button btn-green btn-small width170 m-t-5"><a href="">Token Received</a></button>
                        <button class="pure-button btn-tertiary btn-small width170"><a href="" class="text-cl-white">Teaching Hours</a></button> -->
                    <?php } ?>
                </div>
        </div>

        <div class="pure-u-18-24 profile-detail prelative">

            <div class="date pure-form pure-g padding-t-b-5" style="border-bottom:1px solid #f3f3f3;padding-bottom:5px;">
                <div class="pure-u-2-5">
                    <div class="frm-date" style="display:inline-block">
                        <input class="date_available datepicker frm-date" type="text" name="<?php echo @$data[0]->id?>" readonly="">
                        <span class="icon dyned-icon-coach-schedules active-schedule"></span>
                    </div>
                </div>
                <div class="pure-u-3-5 text-right">
                    <button class="weekly_schedule btn-green btn-small" value="<?php echo(@$data[0]->id); ?>">WEEKLY SCHEDULE</button>
                </div>
            </div>

            <form class="pure-form">
                <div class="list-schedule" style="color:#939393;height: 150px;overflow-y: auto;margin-top:5px;">
                    <p class="txt text-cl-primary">Click in the box for calendar or on Weekly Schedule to see your coach’s availability</p>
                    <div id="result_<?php echo(@$data[0]->id); ?>">
                        <img src='<?php echo base_url(); ?>assets/images/small-loading.gif' alt='loading...' style="display:none;" id="schedule-loading"/>
                    </div>
                </div>
            </form>

        </div>

        <div class="content-title padding-t-25">
            <span>More Info</span>
        </div>
        <div class="change-pass-form padding-t-20">
            <div class="profile-detail prelative padding-tr-15">
                <table class="table-no-border2"> 
                    <tbody>
                        <tr>
                            <td class="pad15">Skype ID</td>
                            <td><span class=""> <?php echo(@$data[0]->skype_id); ?></span></td>
                        </tr>
                        <tr>
                            <td class="pad15">Email</td>
                            <td><span class=""><?php echo(@$data[0]->email); ?></span></td>
                        </tr>
                        <tr>
                            <td class="pad15">Timezone</td>
                            <td><span class=""><?php echo(@$user_tz); ?></span></td>
                        </tr>
                        <tr>
                            <td class="pad15">Spoken Language</td>
                            <td><span class=""><?php echo(str_replace('#', ', ', @$data[0]->spoken_language)); ?></span></td>
                        </tr>
                    </tbody>    
                </table>
            </div>
        </div>

        <div class="content-title padding-t-25">
            <span>Education</span>
        </div>
        <div class="change-pass-form padding-t-20">
            <div class="profile-detail prelative padding-tr-15">
                <table class="table-no-border2"> 
                    <tbody>
                        <tr>
                            <td class="pad15">Higher Education</td>
                            <td><span class=""><?php echo(@$data[0]->higher_education); ?></span></td>
                        </tr>
                        <tr>
                            <td class="pad15">Undergraduate</td>
                            <td><span class=""><?php echo(@$data[0]->undergraduate); ?></span></td>
                        </tr>
                        <tr>
                            <td class="pad15">Masters</td>
                            <td><span class=""><?php echo(@$data[0]->masters); ?></span></td>
                        </tr>
                        <tr>
                            <td class="pad15">PhD</td>
                            <td><span class=""><?php echo(@$data[0]->phd); ?></span></td>
                        </tr>
                        <tr>
                            <td class="pad15">Teaching Credentials</td>
                            <td><span class=""><?php echo(@$data[0]->teaching_credential); ?></span></td>
                        </tr>
                        <tr>
                            <td class="pad15">DynEd English Certification</td>
                            <td><span class=""><?php echo(@$data[0]->dyned_certification_level); ?></span></td>
                        </tr>
                        <tr>
                            <td class="pad15">Years Of Experience</td>
                            <td><span class=""><?php echo(@$data[0]->year_experience); ?></span></td>
                        </tr>
                        <tr>
                            <td class="pad15">Specialization</td>
                            <td><span class=""><?php echo(@$data[0]->special_english_skill); ?></span></td>
                        </tr>
                    </tbody>    
                </table>
            </div>
        </div>
    </div>
</div>


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

        $(".date_available").on('change', function () {
            //alert(this.name);
            
            var loadUrl = "<?php echo site_url($role_link.'/find_coaches/availability/name'); ?>" + "/" + this.name + "/" + $(this).val();
            if ($(this).val() !== '') {
                //alert(loadUrl);
                $("#schedule-loading").show();
                $(".txt").hide();
                $("#result_" + this.name).load(loadUrl, function () {
                    //alert('test');
                    $("#schedule-loading").hide();
                    $('.selected-date').css({'margin-top':'-5px'});
                });
            }
        });

        $(".weekly_schedule").click(function () {
            var loadUrl = "<?php echo site_url($role_link.'/find_coaches/schedule_detail'); ?>" + "/" + this.value;
            if (this.value !== '') {
                $("#schedule-loading").show();
                $(".txt").hide();
                $("#result_" + this.value).load(loadUrl, function () {
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
            autoclose: true
        });
    });

</script>
