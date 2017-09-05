<script type="text/javascript">
    $(function () {
        $('.e-only').bind('keypress', function (e) {
            var code = e.keyCode || e.which;
            if (code === 13) {
                $('#form_info').submit();
            }
        });

        $('#btn_save_info').click(function () {
            $('#form_info').submit();
        });

        $('#profile_picture').change(function () {
            $('#btn_upload').attr('disabled', false);
        });
    });
</script>

<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Profile</h1>
</div>
<?php if ($this->auth_manager->role() != 'ADM') { ?>
    <div class="box">
        <div class="heading pure-g">
            <div class="pure-u-12-24">
                <h3 class="h3 font-normal padding15 text-cl-secondary">BASIC INFO</h3>
            </div>
            <!-- block edit -->
            <div class="pure-u-12-24">
                <div class="edit action-icon">

                    <!-- button save, close, edit-->
                    <button id="btn_save_info" name="__submit" type="submit" class="pure-button btn-tiny btn-white-tertinary m-b-15 save_click asd">SAVE</button>
                    <?php //echo form_submit('__submit', 'Save', "class='button-small btn-book btn-save pure-button save_click_1'"); ?>
                    <i class="icon icon-close close_click" title="Cancel"></i>
                    <i class="icon icon-edit edit_click" title="Edit"></i>
                    <!-- end button save, close, edit -->

                </div>
            </div>

            <!-- end block edit -->
        </div>

        <div class="content">
            <div class="box pure-g">

                <!-- block photo -->
                <div class="pure-u-6-24 text-center profile-image">
                    <div class="thumb-small">
                        <?php echo form_open_multipart('account/identity/' . @$form_action . '/profile_picture', 'role="form"'); ?>
                        <img src="<?php echo base_url() . @$data[0]->profile_picture; ?>" width="150" height="200" class="pure-img fit-cover" />
                        <div class="caption">
                            EDIT
                        </div>
                        <div class="dropdown-form-photo text-center">
                            <input id = "profile_picture" type= "file" accept="image/*" name="profile_picture" style="width:144px;margin-bottom:5px;" class=" button-small btn-book btn-save pure-button">
                            <?php echo form_submit('__submit', 'Save', "id='btn_upload' class='pure-button btn-small btn-tertiary' disabled"); ?>
                            <button type="reset" class="pure-button btn-small btn-tertinary cancel-upload">Cancel</button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <!-- end block photo -->


                <div class="pure-u-18-24 profile-detail prelative">
                    <?php echo form_open('account/identity/' . @$form_action . '/info', 'role="form" id="form_info"'); ?>
                    <table class="table-no-border2"> 
                        <tbody>
                            <?php if ($this->auth_manager->role() == 'PRT') { ?>

                            <tr>
                                <td>Firstname</td>
                                <td>
                                    <span class="r-only"><?php echo @$data[0]->fullname; ?></span>
                                    <input name="firstname" type="text" value="<?php echo @$data[0]->fullname; ?>" id="td_value_1_0" class="e-only" required>
                                </td>
                            </tr>

                            <tr>
                                <td>Surename</td>
                                <td>
                                    <span class="r-only"><?php echo @$data[0]->fullname; ?></span>
                                    <input name="surename" type="text" value="<?php echo @$data[0]->fullname; ?>" id="td_value_1_5" class="e-only" required>
                                </td>
                            </tr>

                            <tr>
                                <td>Nickname</td>
                                <td>
                                    <span class="r-only"><?php echo @$data[0]->fullname; ?></span>
                                    <input name="nickname" type="text" value="<?php echo @$data[0]->fullname; ?>" id="td_value_1_6" class="e-only" required>
                                </td>
                            </tr>

                            <?php } else { ?>
                            <tr>
                                <td>Name</td>
                                <td>
                                    <span class="r-only"><?php echo @$data[0]->fullname; ?></span>
                                    <input name="fullname" type="text" value="<?php echo @$data[0]->fullname; ?>" id="td_value_1_0" class="e-only" required>
                                </td>
                            </tr>
                            <?php } ?>
                            
                            <tr>
                                <td>Birthdate</td>
                                <td>
                                    <span class="r-only"><?php echo date('d-M-Y', strtotime(@$data[0]->date_of_birth)); ?></span>
                                    <input name="date_of_birth" type="text" value="<?php echo @$data[0]->date_of_birth; ?>" id="td_value_1_1" class="e-only datepicker">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php echo ($this->auth_manager->role() == 'STD') ? 'Preferred Language':'Spoken Languages';?>
                                </td>
                                <td>
                                    <?php $selected = explode('#', $data[0]->spoken_language);?>
                                    <span class="r-only rersre"><?php echo str_replace('#', ', ', @$data[0]->spoken_language, $multiplier) ; ?></span>
                                    <?php echo form_multiselect('spoken_lang', $this->common_function->language(), $selected, 'id="td_value_1_2" class="e-only multiple-select" multiple="multiple" style="width:100%"') ?>
                                    <input name="spoken_language" type="hidden" id="spoken_language" value="<?php echo str_replace('#', ', ', @$data[0]->spoken_language, $multiplier) ; ?>">
                                </td>
                            </tr>

                            <tr>
                                <td>Gender</td>
                                <td>
                                    <span class="r-only"><?php echo @$data[0]->gender; ?></span>
                                    <?php echo form_dropdown('gender', $this->auth_manager->gender(), trim(@$data[0]->gender), 'id="td_value_1_4" class="e-only"') ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php
if ($this->auth_manager->role() == 'STD' || $this->auth_manager->role() == 'CCH') {
    echo form_open_multipart('account/identity/' . @$form_action . '/more_info', 'role="form"');
    ?>
    <div class="box">
        <div class="heading pure-g">
            <div class="pure-u-12-24">
                <h3 class="h3 font-normal padding15 text-cl-secondary">MORE INFO</h3>
            </div>
            <!-- block edit -->
            <div class="pure-u-12-24">
                <div class="edit action-icon">
                    <!-- button save, close, edit-->
                    <?php echo form_submit('__submit', 'SAVE', "class='pure-button btn-tiny btn-white-tertinary m-b-15 save_click'"); ?>
                    <i class="icon icon-close close_click"  title="Cancel"></i>
                    <i class="icon icon-edit edit_click" title="Edit"></i>
                    <!-- end button save, close, edit -->
                </div>
            </div>
            <!-- end block edit -->
        </div>

        <div class="content">
            <div class="pure-u-24-24 prelative">
                <table class="table-no-border2"> 
                    <tbody>
                        <?php if ($this->auth_manager->role() == 'STD') { ?>
                            <tr class="no-inline">
                                <td class="pad15">Tokens</td>
                                <td>
                                    <span><?php echo @$data[0]->token_amount; ?></span>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr id="active-2-0" class="no-inline">
                            <td class="pad15">Skype ID</td>
                            <td>
                                <span class="r-only"><?php echo @$data[0]->skype_id; ?></span>
                                <input name="skype_id" type="text" value="<?php echo @$data[0]->skype_id; ?>" id="td_value_2_0" class="e-only">
                            </td>
                        </tr>
                        <tr id="active-2-3" class="no-inline">
                            <td class="pad15">Country</td>
                            <td>
                                <span class="r-only"><?php echo @$data[0]->country; ?></span>
                                <input name="country" type="text" value="<?php echo @$data[0]->country; ?>" id="td_value_2_3" class="e-only">
                            </td>
                        </tr>
                        <tr id="active-2-2" class="no-inline">
                            <td class="pad15">State</td>
                            <td>
                                <span class="r-only"><?php echo @$data[0]->state; ?></span>
                                <input name="state" type="text" value="<?php echo @$data[0]->state; ?>" id="td_value_2_2" class="e-only">
                            </td>
                        </tr>
                        <tr id="active-2-1" class="no-inline">
                            <td class="pad15">City</td>
                            <td>
                                <span class="r-only"><?php echo @$data[0]->city; ?></span>
                                <input name="city" type="text" value="<?php echo @$data[0]->city; ?>" id="td_value_2_1" class="e-only">
                            </td>
                        </tr>
                        <tr id="active-1-3" class="no-inline">
                            <td>Phone</td>
                            <td>
                                <span style="inline-block">+62</span>
                                <span class="r-only"> <?php echo @$data[0]->phone; ?></span>
                                <input name="phone" type="text" value="<?php echo @$data[0]->phone; ?>" id="td_value_1_3" class="e-only" style="width:80%">
                            </td>
                        </tr>
                        
                        
                        
                        <?php if ($this->auth_manager->role() == 'STD') { ?>
                            <tr id="active-2-4" class="no-inline">
                                <td class="pad15">Certification Goal</td>
                                <td>
                                    <span class="r-only"><?php echo @$data[0]->language_goal; ?></span>
                                    <input name="language_goal" type="text" value="<?php echo @$data[0]->language_goal; ?>" id="td_value_2_4" class="e-only">
                                </td>
                            </tr>
                            <tr id="active-2-5" class="no-inline">
                                <td class="pad15">Hobbies</td>
                                <td>
                                    <span class="r-only"><?php echo @$data[0]->hobby; ?></span>
                                    <textarea name="hobby" class="e-only" style="width:100%"><?php echo @$data[0]->hobby; ?></textarea>
                                </td>
                            </tr>
                            <tr id="active-2-6" class="no-inline">
                                <td class="pad15">Likes</td>
                                <td>
                                    <span class="r-only"><?php echo @$data[0]->like; ?></span>
                                    <textarea name="like" class="e-only" style="width:100%"><?php echo @$data[0]->like; ?></textarea>
                                </td>
                            </tr>
                            <tr id="active-2-7" class="no-inline">
                                <td class="pad15">Dislikes</td>
                                <td>
                                    <span class="r-only"><?php echo @$data[0]->dislike; ?></span>
                                    <input name="dislike" type="text" value="<?php echo @$data[0]->dislike; ?>" id="td_value_2_7" class="e-only">
                                </td>
                            </tr>
                        <?php } ?>
                        <tr id="active-2-8" class="no-inline">
                            <td class="pad15">Timezone</td>
                            <td>
                                <span class="r-only"><?php echo @$timezones[@$data[0]->user_timezone]; ?></span>
                                <?php echo form_dropdown('user_timezone', @$timezones, @$data[0]->user_timezone, 'id="td_value_2_8" class="e-only" style="width:100%"')?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>  
        </div>      
    </div>
    <?php echo form_close();
}
?>

<?php if ($this->auth_manager->role() == 'CCH') { ?>
    <?php echo form_open_multipart('account/identity/' . @$form_action . '/education', 'role="form"'); ?>
    <div class="box">
        <div class="heading pure-g">
            <div class="pure-u-12-24">
                <h3 class="h3 font-normal padding15 text-cl-secondary">EXPERIENCE</h3>
            </div>
            <!-- block edit -->
            <div class="pure-u-12-24">
                <div class="edit action-icon">
                    <!-- button save, close, edit-->
    <?php echo form_submit('__submit', 'SAVE', "class='pure-button btn-tiny btn-white-tertinary m-b-15 save_click'"); ?>
                    <i class="icon icon-close close_click" title="Cancel"></i>
                    <i class="icon icon-edit edit_click" title="Edit"></i>
                    <!-- end button save, close, edit -->
                </div>
            </div>
            <!-- end block edit -->
        </div>

        <div class="content">
            <div class="pure-u-24-24 prelative">
                <table class="table-no-border2"> 
                    <tbody>
                        <tr id="active-3-0" class="no-inline">
                            <td class="pad15">Higher Education</td>
                            <td class="pad15">
                                <span class="r-only block-school">School</span>
                                <span class="r-only block-school">Dates Attend</span>
                                <input name="teaching_credential" type="text" id="td_value_3_0" class="e-only block-school" placeholder="School">
                                <span class="input-daterange">
                                <input name="teaching_credential" type="text" class="e-only block-school-date" placeholder="Start">
                                <span class="e-only block-school-to">to</span>
                                <input name="teaching_credential" type="text" class="e-only block-school-date" placeholder="End">
                                </span>
                            </td>
                        </tr>
                        <tr id="active-3-0" class="no-inline">
                            <td class="pad15">Undergraduate</td>
                            <td class="pad15">
                                <span class="r-only block-school">School</span>
                                <span class="r-only block-school">Dates Attend</span>
                                <input name="teaching_credential" type="text" id="td_value_3_0" class="e-only block-school" placeholder="School">
                                <span class="input-daterange">
                                <input name="teaching_credential" type="text" class="e-only block-school-date" placeholder="Start">
                                <span class="e-only block-school-to">to</span>
                                <input name="teaching_credential" type="text" class="e-only block-school-date" placeholder="End">
                                </span>
                            </td>
                        </tr>
                        <tr id="active-3-0" class="no-inline">
                            <td class="pad15">Masters</td>
                            <td class="pad15">
                                <span class="r-only block-school">School</span>
                                <span class="r-only block-school">Dates Attend</span>
                                <input name="teaching_credential" type="text" id="td_value_3_0" class="e-only block-school" placeholder="School">
                                <span class="input-daterange">
                                <input name="teaching_credential" type="text" class="e-only block-school-date" placeholder="Start">
                                <span class="e-only block-school-to">to</span>
                                <input name="teaching_credential" type="text" class="e-only block-school-date" placeholder="End">
                                </span>
                            </td>
                        </tr>
                        <tr id="active-3-0" class="no-inline">
                            <td class="pad15">Phd</td>
                            <td class="pad15">
                                <span class="r-only block-school">School</span>
                                <span class="r-only block-school">Dates Attend</span>
                                <input name="teaching_credential" type="text" id="td_value_3_0" class="e-only block-school" placeholder="School">
                                <span class="input-daterange">
                                <input name="teaching_credential" type="text" class="e-only block-school-date" placeholder="Start">
                                <span class="e-only block-school-to">to</span>
                                <input name="teaching_credential" type="text" class="e-only block-school-date" placeholder="End">
                                </span>
                            </td>
                        </tr>
                        <tr id="active-3-0" class="no-inline">
                            <td class="pad15">Teaching Credential</td>

                            <td>
                                <span class="r-only"><?php echo @$data[0]->teaching_credential; ?></span>
                                <input name="teaching_credential" type="text" value="<?php echo @$data[0]->teaching_credential; ?>" id="td_value_3_0" class="e-only">
                            </td>
                        </tr>
                        <tr id="active-3-1" class="no-inline">
                            <td class="pad15">Dyned English Certification</td>

                            <td>
                                <span class="r-only"><?php echo @$data[0]->dyned_certification_level; ?></span>
                                <input name="dyned_certification_level" type="text" value="<?php echo @$data[0]->dyned_certification_level; ?>" id="td_value_3_1" class="e-only">
                            </td>
                        </tr>
                        <tr id="active-3-2" class="no-inline">
                            <td class="pad15">Year of Experience</td>

                            <td>
                                <span class="r-only"><?php echo @$data[0]->year_experience; ?></span>
                                <input name="year_experience" type="text" value="<?php echo @$data[0]->year_experience; ?>" id="td_value_3_2" class="e-only">
                            </td>
                        </tr>

                        <tr id="active-3-3" class="no-inline">
                            <td class="pad15">Specialization</td>

                            <td>
                                <span class="r-only"><?php echo @$data[0]->special_english_skill; ?></span>
                                <input name="special_english_skill" type="text" value="<?php echo @$data[0]->special_english_skill; ?>" id="td_value_3_3" class="e-only">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    echo form_close();
}
?>


<?php echo form_open_multipart('account/identity/' . @$form_action . '/account', 'role="form"'); ?>
<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-12-24">
            <h3 class="h3 font-normal padding15 text-cl-secondary">ACCOUNT INFO</h3>
        </div>
        <!-- block edit -->
        <div class="pure-u-12-24">
            <div class="edit action-icon">

                <!-- button save, close, edit-->
                <?php echo form_submit('__submit', 'SAVE', "class='pure-button btn-tiny btn-white-tertinary m-b-15 save_click'"); ?>
                <i class="icon icon-close close_click" title="Cancel"></i>
                <i class="icon icon-edit edit_click" title="Edit"></i>
                <!-- end button save, close, edit -->

            </div>
        </div>
        <!-- end block edit -->
    </div>

    <div class="content">
        <div class="pure-u-24-24 prelative">
            <table class="table-no-border2"> 
                <tbody>
                    <tr class="no-inline">
                        <td class="pad15">Email</td>

                        <td>
                            <span><?php echo @$data[0]->email; ?></span>
                        </td>
                    </tr>
                    <tr id="active-4-0" class="no-inline">
                        <td class="pad15">Old Password</td>

                        <td>
                            <span class="r-only">•••••••</span>
                            <input name="old_password" type="password" id="td_value_4_0" class="e-only">
                        </td>
                    </tr>
                    <tr id="active-4-1" class="no-inline">
                        <td class="pad15">New Password</td>

                        <td>
                            <span class="r-only">•••••••</span>
                            <input name="new_password" type="password" id="td_value_4_1" class="e-only">
                        </td>
                    </tr>
                    <tr id="active-4-1" class="no-inline">
                        <td class="pad15">Confirm Password</td>

                        <td>
                            <span class="r-only">•••••••</span>
                            <input name="confirm_password" type="password" id="td_value_4_1" class="e-only">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>  
    </div>      
</div>
<?php echo form_close(); ?>


<?php //if (@$data[0]->dyned_pro_id) { 
if ($this->auth_manager->role() == 'STD') {
?>
    <div class="box">
        <div class="heading pure-g">
            <div class="pure-u-12-24">
                <h3 class="h3 font-normal padding15 text-cl-secondary">DYNED PRO INFO</h3>

            </div>
             <!-- block edit -->
            <div class="pure-u-12-24">
                <div class="edit action-icon">

                    <?php echo form_submit('__submit', 'SAVE', "class='pure-button btn-tiny btn-white-tertinary m-b-15 save_click'"); ?>
                    <i class="icon icon-close close_click" title="Cancel"></i>
                    <i class="icon icon-edit edit_click" title="Edit"></i>

                </div>
            </div>
            <!-- end block edit -->
        </div>

        <div class="content">
            <div class="pure-u-24-24 prelative">

                <table class="table-no-border2"> 
                    <tbody>
                        <tr id="active-5-0" class="no-inline">
                            <td class="pad15">DynEd PRO ID</td>

                            <td>
                                <!-- <span class="r-only"><?php //echo @$data[0]->dyned_pro_id; ?> DynEd Pro Id</span> -->

                                <span class="r-only">DynEd Pro ID</span>
                                <input name="" type="text" id="td_value_5_0" class="e-only">
                            </td>
                        </tr>
                        <tr id="active-5-0" class="no-inline">
                            <td class="pad15">Password</td>

                            <td>
                                <span class="r-only">•••••••</span>
                                <input name="" type="password" id="td_value_5_1" class="e-only">
                            </td>
                        </tr>
                        <tr id="active-5-0" class="no-inline">
                            <td class="pad15">Server</td>

                            <td>
                                <span class="r-only">USA</span>
                                <select id="td_value_5_2" class="e-only">
                                    <option>Select Server</option>
                                    <option>Indonesia</option>
                                    <option>USA</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>  
        </div>  
        <?php echo form_close(); ?> 
    </div>
<?php } ?>
 <script>
//     $(function () {
// <?php for ($i = 0; $i <= 5; $i++) { ?>
//             //hide btn save, close, inline input
//             $('.e-only-<?= $i ?>').hide();
//             $('.close_click_<?= $i ?>').hide();
//             $('.save_click_<?= $i ?>').hide();

//             //btn edit click show inlieinput
//             $('.edit_click_<?= $i ?>').click(function () {
//                 $('.e-only-<?= $i ?>').show();
//                 $('.r-only-<?= $i ?>').hide();
//                 $('.close_click_<?= $i ?>').show();
//                 $('.save_click_<?= $i ?>').show();
//                 $('.edit_click_<?= $i ?>').hide();
//                 $("#td_value_<?= $i ?>_0").focus();
//                 animationClick('.close_click_<?= $i ?>', 'fadeIn');
//                 animationClick('.save_click_<?= $i ?>', 'fadeIn');
//                 <?php 
//                 //if($i==1){
//                     //echo "$('.caption').show();";
//                 //}
//                 ?>
//             });

//             //add style focus input
//     <?php for ($j = 0; $j <= 8; $j++) { ?>

//                 $('#td_value_<?= $i . "_" . $j ?>').on('blur', function () {
//                     $('#active-<?= $i . "-" . $j ?>').removeClass('inline').addClass('no-inline');
//                 }).on('focus', function () {
//                     $('#active-<?= $i . "-" . $j ?>').removeClass('no-inline').addClass('inline');
//                 });

//     <?php } ?>

//             //btn close/cancel
//             $('.close_click_<?= $i ?>').click(function () {
//                 $('.close_click_<?= $i ?>').hide();
//                 $('.save_click_<?= $i ?>').hide();
//                 $('.edit_click_<?= $i ?>').show();
//                 $('.r-only-<?= $i ?>').show();
//                 $('.e-only-<?= $i ?>').hide();
//                 animationClick('.edit_click_<?= $i ?>', 'fadeIn');
//                 <?php 
//                 // if($i==1){
//                 //     echo "$('.caption').hide();";
//                 //     echo "$('.dropdown-form-photo').hide();";
//                 // }
//                 ?>
//             });

//             $('.save_click_<?= $i ?>').click(function () {
//              $('.save_click_<?= $i ?>').text('SAVING...');
//                 $('.close_click_<?= $i ?>').hide();
//                 $("#load_<?= $i ?>").show().delay(3000).queue(function (next) {
//                     $(this).hide();
//                     $('.save_click_<?= $i ?>').text('SAVE');
//                     $('.save_click_<?= $i ?>').hide();
//                     $('.edit_click_<?= $i ?>').show();
//                     $('.r-only-<?= $i ?>').show();
//                     $('.e-only-<?= $i ?>').hide();
//                     next();
//                 });
//             });

//             $('.caption').click(function () {
//                 $(".dropdown-form-photo").show();
//                 //$(".caption").hide();
//             });

//             $('.cancel-upload').click(function () {
//                 $(".dropdown-form-photo").hide();
//                 //$(".caption").show();
//             });

//             $('body').on('click', '.upload-click', function () {
//                 $('#profile_picture').click();
//             });
// <?php } ?>
//     });

$(function() {
    $('.e-only').hide();
    $('.close_click').hide();
    $('.save_click').hide();

    $('.box').each(function(){

        var _each = $(this);
        //var asd = $(".multiple-select", _each).val();


        $('.edit_click', _each).click(function () {
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
           // $("#td_value_1_1").focus();
            animationClick(_close, 'fadeIn');
            animationClick(_save, 'fadeIn');
        });

        $('.close_click', _each).click(function () {
            $('.close_click', _each).hide();
            $('.save_click', _each).hide();
            $('.edit_click', _each).show();
            $('.r-only', _each).show();
            $('.e-only', _each).hide();
            _edit = $('.edit_click', _each);
            animationClick(_edit, 'fadeIn');
        });

        $('.save_click', _each).click(function () {

            //_f = _each.find('#spoken_language');



            //alert($(".multiple-select").val());
           
            $('.save_click', _each).text('SAVING...');
            $('.close_click', _each).hide();
            $(".select2", _each).removeClass("e-only");
            $("#load", _each).show().delay(3000).queue(function (next) {
                $(this).hide();
                $('.save_click', _each).text('SAVE');
                $('.save_click', _each).hide();
                $('.edit_click', _each).show();
                $('.r-only', _each).show();
                $('.e-only', _each).hide();
                next();
                
            });
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
    })

   

    


    $('.caption').click(function () {
        $(".dropdown-form-photo").show();
        //$(".caption").hide();
    });

    $('.cancel-upload').click(function () {
        $(".dropdown-form-photo").hide();
        //$(".caption").show();
    });

    $('body').on('click', '.upload-click', function () {
        $('#profile_picture').click();
    });

})


</script>
<script type="text/javascript">
    $(function () {
        $(".multiple-select").select2({ placeholder: "Select Language"});
        //$($(".multiple-select").select2("container")).addClass("e-only");
        //$(".multiple-select").select2({ containerCssClass : "e-only" });
        //$(".multiple-select").select2("container");
        $(".select2").addClass("e-only");
        $(".e-only").hide();
        $(".datepicker").datepicker({
            format: 'yyyy-mm-dd',
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(1990, 00, 01)
        });

        $('.input-daterange').datepicker({
            format: "yyyy",
            startView: 2,
            minViewMode: 2
        });

        $(".multiple-select").change(function(){
            document.getElementById('spoken_language').value = $(".multiple-select").val();
        });

        $('.save_click').click(function(){
            document.getElementById('spoken_language').value = $(".multiple-select").val();
        });
    });
    
</script> 
