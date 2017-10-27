<div class="heading text-cl-primary padding15">
    <h2 class="margin0 padding-r-20 left"><?php echo @$student[0]->fullname?></h2>

<!--     <div class="btn-goBack">
        <a href="<?php echo $back;?>">
            <button class="btn-small border-1-blue bg-white-fff">
                <img src="<?php echo base_url();?>assets/img/back-one-page.svg" width="15px" class=" padding-t-1 padding-r-5 left">
                <label>Go Back One Page</label>
            </button>
        </a>
    </div> -->
</div>

<?php echo form_open('student_partner/member_list_neo/update_student/'.$student_id, 'id="form_more_info" role="form" class="pure-form-aligned" data-parsley-validate'); ?>
<div class="box">

    <div class="content">
        <div class="content-title m-lr-20 clear-both">
            <span class="left">Basic Info</span>

            <div class="edit action-icon">
                <button id="btn_save_info" name="__submit" value="SUBMIT" type="submit" class="pure-button btn-tiny btn-white-tertinary m-b-15 save_click asd">SAVE</button>
                <i class="icon icon-close close_click" title="Cancel"></i>
                <i class="icon icon-edit edit_click" title="Edit"></i>
            </div>
        </div>
        <div class="pure-g padding-t-20">

            <div class="pure-u-8-24 profile-image text-center divider-right">
                <div class="thumb-small">
                    <img src="<?php echo base_url(@$student[0]->profile_picture);?>" class="pure-img fit-cover img-circle-big preview-image m-b-10">
                    <span class="text-cl-secondary font-14">Student</span><br>
                    <span class="font-12"><?php echo @$student[0]->city .', '. @$student[0]->country ?></span>
                </div>
                <div class="padding-t-10 m-b-15 text-center">
                    <div class="padding10" style="width:85%;margin:0 auto">
                        <a href="<?php echo site_url('student_partner/vrm/single_student/'.@$student[0]->id);?>" class="pure-button btn-small btn-white m-t-10">VIEW REPORT</a>
                        <a href="<?php echo site_url('student_partner/student_upcoming_session/one_to_one_session/'.@$student[0]->id);?>" class="pure-button btn-small btn-white m-t-10">SESSIONS</a>
                    </div>
                </div>
            </div>

            <div class="pure-u-15-24 profile-detail prelative">

                <div class="heading m-b-15">
                </div>


                <table class="table-no-border2">
                    <tbody>
                       <tr>
                            <td class="pad15">Subgroup</td>
                            <td>
                                <span class="r-only"><?php echo(@$subgroup[0]->subgroupname); ?></span>
                                <?php echo form_dropdown('subgroup', $listsubgroup, trim(@$subgroup[0]->subgroupid), 'class="e-only" required data-parsley-required-message="Please select your subgroup"') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Full Name</td>
                            <td>
                                <span class="r-only"><?php echo @$student[0]->fullname;?></span>
                                <input name="fullname" type="text" value="<?php echo @$student[0]->fullname; ?>" class="e-only" required data-parsley-required-message='Please input student name'>
                            </td>
                        </tr>
						<tr>
                            <td class="pad15">Nick Name</td>
                            <td>
                                <span class="r-only"><?php echo @$student[0]->nickname;?></span>
                                <input name="nickname" type="text" value="<?php echo @$student[0]->nickname; ?>" class="e-only" required data-parsley-required-message='Please input student nickname'>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Birthdate</td>
                            <td>
                                <span class="r-only"><?php echo @$student[0]->date_of_birth;?></span>
                                <input name="date_of_birth" type="text" value="<?php echo @$student[0]->date_of_birth; ?>" class="e-only datepicker" readonly required data-parsley-required-message='Please input student birthdate'>
                            </td>
                        </tr>
     <!--                    <tr>
                            <td class="pad15">Preferred Language</td>
                            <td>
                                <?php echo str_replace('#', ', ', @$student[0]->spoken_language, $multiplier) ; ?>
                            </td>
                        </tr> -->
                        <tr class="no-inline">
                            <td class="pad15">Phone</td>
                            <td class="flex width100perc">
                                <span class="r-only"> <?php echo  @$student[0]->dial_code;?></span>
                                <span class="r-only"><?php echo @$student[0]->phone;?></span>
                                <!-- <input type="text" name="dial_code" value="<?php echo @$student[0]->dial_code;?>" class="pure-input-1-20 e-only" style="margin-right:1px;" readonly> -->
                                <input name="phone" type="text" value="<?php echo @$student[0]->phone; ?>" class="e-only" style="width:80%" data-parsley-type='digits' required data-parsley-required-message="Please input student phone number" data-parsley-type-message="Please input numbers only">
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Gender</td>
                            <td>
                                <span class="r-only"><?php echo @$student[0]->gender;?></span>
                                <?php echo form_dropdown('gender', $this->auth_manager->gender(), trim(@$student[0]->gender), 'class="e-only" required data-parsley-required-message="Please select student gender"') ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>


        </div>

    </div>

</div>
<?php echo form_close();?>


<div class="box">

    <div class="heading">
        <div class="pure-u-1">
            <h3 class="h3 font-normal padding15 text-cl-secondary">MORE INFO</h3>
        </div>
    </div>

    <div class="content">
        <table class="table-no-border2">
            <tbody>
                <tr>
                    <td class="pad15">Email</td>
                    <td>
                        <?php echo @$student[0]->email;?>
                    </td>
                </tr>
                <tr>
                    <td class="pad15">Tokens</td>
                    <td>
                        <?php echo @$student[0]->token_amount;?>
                    </td>
                </tr>
                <tr>
                    <td class="pad15">Skype ID</td>
                    <td>
                        <?php echo @$student[0]->skype_id;?>
                    </td>
                </tr>
                <tr>
                    <td class="pad15">Certification Goal</td>
                    <td>
                        <?php echo @$student[0]->language_goal;?>
                    </td>
                </tr>
                <tr>
                    <td class="pad15">Hobies</td>
                    <td>
                        <?php echo @$student[0]->hobby;?>
                    </td>
                </tr>
                <tr>
                    <td class="pad15">Likes</td>
                    <td>
                        <?php echo @$student[0]->like;?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(function() {

        $('.datepicker').datepicker({
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

        $('.select2-container--default .select2-selection--multiple').css("cssText", "border: none !important;");

        $(".multiple-select").on('change',function(){
            document.getElementById('spoken_language').value = $(".multiple-select").val();
        });

        $('.save_click').on('click',function(){
            document.getElementById('spoken_language').value = $(".multiple-select").val();
        });

    })
</script>
