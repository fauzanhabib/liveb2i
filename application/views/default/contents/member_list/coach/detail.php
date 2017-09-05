<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Coach Details</h1>
</div>


<div class="box b-f3-1">

    <div class="content">

        <div class="pure-g">

            <div class="pure-u-8-24 profile-image text-center divider-right">
                <div class="thumb-small">
                    <img src="<?php echo base_url() . @$data[0]->profile_picture; ?>" class="pure-img fit-cover preview-image m-b-10">
                    <span class="text-cl-secondary font-14"><?php echo(@$data[0]->fullname); ?></span><br>
                    <span class="font-12"><?php echo(@$data[0]->city . ', ' . @$data[0]->country); ?></span>
                </div>
                <div class="padding-t-10 text-center">
                    <div class="b-f3-1 padding10" style="width:85%;margin:0 auto">
                        <a href="<?php echo site_url('partner/coach_upcoming_session/one_to_one_session');?>" class="pure-button btn-small btn-white m-t-10">SESSIONS</a>
                    </div>
                </div>
            </div>

            <div class="pure-u-16-24 profile-detail prelative">

                <div class="heading m-b-15">
                    <div class="pure-u-1">
                        <h3 class="h3 font-normal padding-b-15 text-cl-secondary">Schedule</h3>
                    </div>
                </div>

                <div class="date pure-form pure-g" style="border-bottom:1px solid #f3f3f3;padding-bottom:5px;">
                    <div class="pure-u-2-5" style="width:52%">
                        <div class="frm-date" style="display:inline-block">
                            <input class="date_available datepicker frm-date" type="text" name="<?php echo @$data[0]->id?>" readonly="" style="width:125px;">
                            <span class="icon icon-date active-schedule" style="left: 99px;"></span>
                        </div>
                    </div>
                    <div class="pure-u-3-5 text-right" style="width:48%">
                        <button class="weekly_schedule padding0" value="<?php echo(@$data[0]->id); ?>">WEEKLY SCHEDULE</button>
                    </div>
                </div>

                <form class="pure-form">
                    <div class="list-schedule" style="color:#939393;height: 150px;overflow-y: auto;margin-top:5px;">
                        <p class="txt text-cl-primary">Click in the box for calendar or on Weekly Schedule to see your coach’s availability</p>
                        <div id="result_<?php echo(@$data[0]->id); ?>">
                            <img src='<?php echo base_url(); ?>images/small-loading.gif' alt='loading...' style="display:none;" id="schedule-loading"/>
                        </div>
                    </div>
                </form>

            </div>

        </div>

    </div>

</div>

<?php echo form_open('partner/member_list/update_coach/', 'id="form_more_info" role="form" class="pure-form pure-form-aligned" data-parsley-validate'); ?>
<div class="box">

    <div class="pure-g">

        <div class="pure-u-1">
            <div class="heading pure-g">
                <div class="pure-u-12-24" style="width:48%">
                    <h3 class="h3 font-normal padding15 text-cl-secondary">BASIC INFO</h3>
                </div>
                
                <div class="pure-u-12-24">
                    <div class="edit action-icon">
                        <button id="btn_save_info" name="__submit" value="submit" type="submit" class="pure-button btn-tiny btn-white-tertinary m-b-15 save_click asd">SAVE</button>
                        <i class="icon icon-close close_click" title="Cancel"></i>
                        <i class="icon icon-edit edit_click" title="Edit"></i>
                    </div>
                </div>
            </div>

            <div class="content">
                <table class="table-no-border2"> 
                    <tbody>
                        <tr>
                            <td class="pad15">Subgroup</td>
                            <td>
                                <span class="r-only"><?php echo(@$subgroup->subgroupname); ?></span>
                                <?php echo form_dropdown('subgroup', $listsubgroup, trim(@$subgroup->subgroupname), 'class="e-only" required data-parsley-required-message="Please select your subgroup"') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Fullname</td>
                            <td>
                                <span class="r-only"><?php echo(@$data[0]->fullname); ?></span>
                                <input name="fullname" value ="<?php echo @$data[0]->fullname;?>" type="text" class="e-only" required data-parsley-required-message="Please input coach name">
                            </td>
                        </tr> 
                        <tr>
                            <td class="pad15">Gender</td>
                            <td>
                                <span class="r-only"><?php echo @$data[0]->gender; ?></span>
                                <?php echo form_dropdown('gender', $this->auth_manager->gender(), trim(@$data[0]->gender), 'class="e-only" required data-parsley-required-message="Please select your gender"') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Birthdate</td>
                            <td>
                                <span class="r-only"><?php echo date('d-M-Y', strtotime(@$data[0]->date_of_birth)); ?></span>
                                <input name="date_of_birth" type="text" value="<?php echo @$data[0]->date_of_birth; ?>" class="e-only datepicker2" readonly required data-parsley-required-message='Please input your birthdate'>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Phone Number</td>
                            <td>
                                <span style="inline-block"><?php //echo $dial_code;?></span>
                                <span class="r-only"><?php echo @$data[0]->phone; ?></span>
                                <input name="phone" type="text" value="<?php echo @$data[0]->phone; ?>" class="e-only" style="width:80%" data-parsley-type='digits' required data-parsley-required-message="Please input your phone number" data-parsley-type-message="Please input numbers only">
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Token Cost for Student</td>
                            <td>
                                <span class="r-only"><?php echo(str_replace('#', ', ', @$data[0]->token_for_student)); ?></span>
                                <input type="text" name="token_for_student" value="<?php echo @$data[0]->token_for_student; ?>" class="e-only" data-parsley-type="digits" required data-parsley-required-message="Please input token cost for student" data-parsley-type-message="Please input numbers only">
                            </td>
                        </tr>
<!--                        <tr>
                            <td class="pad15">Token Cost for Class</td>
                            <td>
                                <span class="r-only"><?php// echo(str_replace('#', ', ', @$data[0]->spoken_language)); ?></span>
                                <input type="text" name="token_for_group" class="e-only" data-parsley-type="digits" required data-parsley-required-message="Please input token cost for Class" data-parsley-type-message="Please input numbers only">
                            </td>
                        </tr>-->
                    </tbody>    
                </table>
            </div>
        </div> 
    </div>
</div> 
<?php echo form_close();?>

<div class="box">

    <div class="pure-g">

        <div class="pure-u-1">
            <div class="heading">
                <div class="pure-u-1">
                    <h3 class="h3 font-normal padding15 text-cl-secondary">MORE INFO</h3>
                </div>
            </div>

            <div class="content">
                <table class="table-no-border2"> 
                    <tbody>
                        <tr>
                            <td class="pad15">Skype ID</td>
                            <td>
                                <?php echo(@$data[0]->skype_id); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Email</td>
                            <td>
                                <?php echo(@$data[0]->email); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Timezone</td>
                            <td>
                                <?php echo(@$data[0]->timezone); ?>
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
</div>        

<div class="box">

    <div class="pure-g">        

        <div class="pure-u-1">
            <div class="heading">
                <div class="pure-u-1">
                    <h3 class="h3 font-normal padding15 text-cl-secondary">EDUCATION</h3>
                </div>
            </div>

            <div class="content">
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
                            <td class="pad15">PhD</td>
                            <td class="pad15">
                                <span class="r-only block-school"><?php echo(@$data[0]->phd); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Teaching Credential</td>
                            <td>
                                <?php echo(@$data[0]->teaching_credential); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">DynEd English Certification</td>
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
            var loadUrl = "<?php echo site_url('partner/member_list/availability/name'); ?>" + "/" + this.name + "/" + $(this).val();
            if ($(this).val() !== '') {
                $("#schedule-loading").show();
                $(".txt").hide();
                $("#result_" + this.name).load(loadUrl, function () {
                    $("#schedule-loading").hide();
                    $('.selected-date').css({'margin-top':'-5px'});
                });
            }
        });

        $(".weekly_schedule").click(function () {
            var loadUrl = "<?php echo site_url('partner/member_list/schedule_detail'); ?>" + "/" + this.value;
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
        var day = ("0" + (now.getDate() + 2)).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);

        $('.datepicker').datepicker({
            startDate: resultDate,
            format: 'yyyy-mm-dd',
            autoclose: true
        });
        $('.datepicker2').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $('.e-only').hide();
        $('.close_click').hide();
        $('.save_click').hide();

        var arrText= new Array();
        var arrSelect= new Array();

        var isEnabled = true;

        $('.box').each(function(){

            var _each = $(this);
            
            $('.edit_click', _each).click(function () {
                if (isEnabled == true) {

                    isEnabled = false;

                    $('.e-only', _each).show();
                    $('.r-only', _each).hide();
                    $('.close_click', _each).show();
                    $('.save_click', _each).show();
                    $('.edit_click', _each).hide();

                    $('.e-only').not($('.e-only', _each)).hide();
                    $('.r-only').not($('.r-only', _each)).show();
                    $('.close_click').not($('.close_click', _each)).hide();
                    $('.save_click').not($('.save_click', _each)).hide();
                    $('.edit_click').not($('.edit_click', _each)).show();

                    _close = $('.close_click', _each);
                    _save = $('.save_click', _each);
                    $('.e-only:first', _each).focus();
                    animationClick(_close, 'fadeIn');
                    animationClick(_save, 'fadeIn');

                    $('#form_more_info').parsley().reset();

                    $('input[type=text]', _each).each(function(){
                        arrText.push($(this).val());
                    });

                    $('select', _each).each(function(){
                        arrSelect.push($(this).val());
                    });

                }    
            });

            $('.close_click', _each).click(function () {

                isEnabled = true;

                $('.close_click', _each).hide();
                $('.save_click', _each).hide();
                $('.edit_click', _each).show();
                $('.r-only', _each).show();
                $('.e-only', _each).hide();
                _edit = $('.edit_click', _each);
                animationClick(_edit, 'fadeIn');

                $('#form_more_info').parsley().reset();

                var input = $('input[type=text]', _each);

                for(i = 0; i < input.length; i++) {
                  input[i].value = arrText[i];
                }

                var select = $('select', _each);

                for(i = 0; i < select.length; i++) {
                  select[i].value = arrSelect[i];
                }
                
                arrText = [];
                arrSelect = [];

                
            });


            $('.save_click', _each).click(function () {

            });

        });

        $('tr').each(function(e){
            var inputs = $(this);

            $('input',inputs).on('blur', function () {
                $('td',inputs).removeClass('inline').addClass('no-inline');
            }).on('focus', function () {
                $('td',inputs).removeClass('no-inline').addClass('inline');
            });

            $('textarea',inputs).on('blur', function () {
                $('td',inputs).removeClass('inline').addClass('no-inline');
            }).on('focus', function () {
                $('td',inputs).removeClass('no-inline').addClass('inline');
            });

            $('td',inputs).css({'position':'relative'});
        });

        $('.parsley-errors-list').css({'position':'absolute','bottom':'0','right':'0','color':'#E9322D'});
        $('input.parsley-error').css({'border':'none'});
        $('select.parsley-error').css({'border':'none'});

        parsley_float();


    });

</script>
