<div class="heading text-cl-primary padding15">
    <h1 class="margin0"><?php echo $this->auth_manager->lang('lbl_profile');?></h1>
</div>

<?php if (($this->auth_manager->role() != 'ADM') && ($this->auth_manager->role() != 'RAD')){ ?>
    <div class="box">


        <div class="content-title m-lr-20 clear-both">
            <span class="left">Basic Info</span>

            <div class="edit action-icon">
                <button id="btn_save_info" name="__submit" type="submit" class="pure-button btn-tiny btn-white-tertinary m-b-15 save_click asd" style="display: none;">SAVE</button>
                <i class="icon icon-close close_click" title="Cancel" style="display: none;"></i>
                <i class="icon icon-edit edit_click" title="Edit"></i>
            </div>
        </div>

        <div class="content">
            <div class="box pure-g">

                <!-- block photo -->
                <div class="pure-u-6-24 text-center profile-image">
                    <div class="thumb-small">
                        <?php echo form_open_multipart('account/identity/' . @$form_action . '/profile_picture', 'role="form"'); ?>
                        <img src="<?php echo base_url() . @$data[0]->profile_picture; ?>" width="150" height="200" class="pure-img fit-cover img-circle-big" />
                        <div class="caption">
                            EDIT
                        </div>
                        <div class="dropdown-form-photo text-center">
                            <input id = "profile_picture" type= "file" accept="image/*" name="profile_picture" style="width:144px;margin-bottom:5px;" class=" button-small btn-book btn-save pure-button">
                            <?php echo form_submit('__submit', 'Save', "id='btn_upload' class='pure-button btn-small btn-tertiary' disabled"); ?>
                            <button type="reset" class="pure-button btn-red btn-small cancel-upload">Cancel</button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <!-- end block photo -->


                <div class="pure-u-18-24 profile-detail prelative">
                    <?php echo form_open('account/identity/' . @$form_action . '/info', 'role="form" id="form_info"'); ?>
                    <table class="table-no-border2"> 
                        <tbody>

            <!--                 <tr>
                                <td class="pad15">Region</td>
                                <td  class="r-only">
                                    <span><?php echo @$name_region[0]->region_id; ?></span>
                                </td>
                                <td class="e-only" style="cursor:not-allowed;background: #ebebeb;color: #939393;padding-left: 5px;">
                                    <span><?php echo @$name_region[0]->region_id; ?></span>
                                </td>
                            </tr> -->
                            
                            <tr>
                                <td>Full Name</td>
                                <td>
                                    <span class="r-only"><?php echo @$data[0]->fullname; ?></span>
                                    <input name="fullname" type="text" value="<?php echo @$data[0]->fullname; ?>" id="td_value_1_0" class="e-only" required data-parsley-required-message='Please input your name'>
                                </td>
                            </tr>

                            <tr>
                                <td class="pad15">Email</td>
                                <td  class="r-only">
                                    <span><?php echo @$data[0]->email; ?></span>
                                </td>
                                <td class="e-only" style="cursor:not-allowed;background: #ebebeb;color: #939393;padding-left: 5px;">
                                    <span><?php echo @$data[0]->email; ?></span>
                                </td>
                            </tr>
                            
<!--                            <tr>
                                <td>Nick Name</td>
                                <td>
                                    <span class="r-only"><?php echo @$data[0]->nickname; ?></span>
                                    <input name="nickname" type="text" value="<?php echo @$data[0]->nickname; ?>" id="td_value_1_0" class="e-only" >
                                </td>
                            </tr> -->
                         <?php if($this->auth_manager->role() != 'PRT' && $this->auth_manager->role() != 'SPR'){ ?>
                            <tr>
                                <td>Date of Birth</td>
                                <td>
                                    <span class="r-only"><?php echo date('d-M-Y', strtotime(@$data[0]->date_of_birth)); ?></span>
                                    <input name="date_of_birth" type="text" value="<?php echo @$data[0]->date_of_birth; ?>" id="td_value_1_1" class="e-only datepicker" readonly required required data-parsley-required-message='Please input your birthdate'>
                                </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td>
                            <?php echo ($this->auth_manager->role() == 'STD') ? 'Home Language':'Spoken Languages';?>
                                </td>
                                <td>
                                    <?php $selected = explode('#', $data[0]->spoken_language);?>
                                    <span class="r-only rersre"><?php echo str_replace('#', ', ', @$data[0]->spoken_language, $multiplier) ; ?></span>
                                    <?php echo form_multiselect('spoken_lang', $this->common_function->language(), $selected, 'id="td_value_1_2" class="e-only multiple-select" multiple="multiple" style="width:100%" required required data-parsley-required-message="Please select your spoken language"') ?>
                                    <input name="spoken_language" type="hidden" id="spoken_language" value="<?php echo str_replace('#', ',', @$data[0]->spoken_language, $multiplier) ; ?>">
                                </td>
                            </tr>

                            <tr>
                                <td>Gender</td>
                                <td>
                                    <span class="r-only"><?php echo @$data[0]->gender; ?></span>
                                    <?php echo form_dropdown('gender', $this->auth_manager->gender(), trim(@$data[0]->gender), 'id="td_value_1_4" class="e-only" required required data-parsley-required-message="Please select your gender"') ?>
                                </td>
                            </tr>
                            
                            <?php if($this->auth_manager->role() == 'STD' || $this->auth_manager->role() == 'CCH'){ ?>
                            <tr id="active-1-3" class="no-inline">
	                            <td>Phone</td>
	                            <td class="flex width100perc">
	                                <span class="r-only"> <?php echo @$country_code; ?></span>
	                                <span class="r-only"> <?php echo @$data[0]->phone; ?></span>
	                                <input type="text" name="dial_code" data-parsley-trigger="change" value="<?php echo @$country_code; ?>" id="dial_code" class="pure-input-1-20 e-only" style="margin-right:1px;" readonly>
	                                <input name="phone" type="number" value="<?php echo @$data[0]->phone; ?>" id="td_value_1_3" class="e-only" data-parsley-type='digits' data-parsley-type-message="Please input numbers only">
	                            </td>
	                        </tr>
	                        <tr id="active-1-4" class="no-inline">
	                            <td></td>
	                            <td class="flex width100perc" style="border-bottom: none !important;">
	                            	<form action="<?php echo site_url('account/identityverifynumber');?>" method="POST">
		                            	<input type="hidden" name="phoneverif" id="verifnumber">
		                                <button name="__submit" type="submit" class="pure-button btn-tiny btn-white-tertinary e-only" id="verifbutton">Send Verification</button>
		                                <!-- <input name="__submit" type="submit" class="pure-button btn-tiny btn-white-tertinary e-only" value="Send Verification"> -->
	                                </form>
	                            </td>
	                        </tr>
	                        <?php } ?>

                            <?php if($this->auth_manager->role() == 'PRT' || $this->auth_manager->role() == 'SPR'){ ?>
                                <tr id="active-2-8" class="no-inline">
                                    <td class="pad15">Timezone</td>
                                    <!-- <td>
                                        <span class="r-only"><?php echo @$timezones[@$data[0]->user_timezone]; ?></span>
                                        <?php echo form_dropdown('user_timezone', @$timezones, @$data[0]->user_timezone, 'id="td_value_2_8" class="e-only" style="width:100%" required required data-parsley-required-message="Please select your timezone"')?>
                                    </td> -->
                                    <td class="r-only">
                                        <span><?php echo @$user_tz; ?></span>
                                    </td>
                                    <td class="e-only" style="cursor:not-allowed;background: #ebebeb;color: #939393;">
                                        <span><?php echo @$user_tz; ?></span>
                                    </td>
                                </tr>
                            <?php
                            }?>
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
    echo form_open_multipart('account/identity/' . @$form_action . '/more_info', 'id="form_more_info" role="form"');
    ?>
    <div class="box clear-both">
        <div class="content-title m-lr-20">
            <span class="left">Additional Info</span>
            <div class="edit action-icon">
                <?php echo form_submit('__submit', 'SAVE', "class='pure-button btn-tiny btn-white-tertinary m-b-15 save_click'"); ?>
                <i class="icon icon-close close_click"  title="Cancel"></i>
                <i class="icon icon-edit edit_click" title="Edit"></i>
            </div>
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
                                <!-- <input name="country" type="text" value="<?php //echo @$data[0]->country; ?>" id="td_value_2_3" class="e-only"> -->
                                <?php
                                    $country = array_column($option_country, 'name', 'name');
                                    $newoptions = $country;
                                    echo form_dropdown('country', $newoptions, @$data[0]->country, ' id="td_value_2_3" class="e-only" required data-parsley-required-message="Please select your country"'); 
                                ?>

                            </td>
                        </tr>
                        <tr id="active-2-1" class="no-inline">
                            <td class="pad15">City</td>
                            <td>
                                <span class="r-only"><?php echo @$data[0]->city; ?></span>
                                <input name="city" type="text" value="<?php echo @$data[0]->city; ?>" id="td_value_2_1" class="e-only">
                            </td>
                        </tr>
                        
                        <tr id="active-2-2" class="no-inline">
                            <td class="pad15">State/Province</td>
                            <td>
                                <span class="r-only"><?php echo @$data[0]->state; ?></span>
                                <input name="state" type="text" value="<?php echo @$data[0]->state; ?>" id="td_value_2_2" class="e-only">
                            </td>
                        </tr>
                        <tr class="no-inline">
                            <td class="pad15">Address</td>
                            <td>
                                <span class="r-only"><?php echo @$data[0]->address; ?></span>
                                <textarea name="address" class="e-only"><?php echo @$data[0]->address; ?></textarea>
                            </td>
                        </tr>

                        <!-- <tr id="active-1-3" class="no-inline">
                            <td>Phone</td>
                            <td>
                                <span class="r-only"> <?php echo @$country_code; ?></span>
                                <span class="r-only"> <?php echo @$data[0]->phone; ?></span>
                                <input type="text" name="dial_code" data-parsley-trigger="change" value="<?php echo @$country_code; ?>" id="dial_code" class="pure-input-1-8 e-only" style="margin-right:1px;" readonly>
                                <input name="phone" type="text" value="<?php echo @$data[0]->phone; ?>" id="td_value_1_3" class="e-only" style="width:80%" data-parsley-type='digits' data-parsley-type-message="Please input numbers only">
                            </td>
                        </tr> -->
                        
                        <?php if ($this->auth_manager->role() == 'STD') { ?>
                            <tr id="active-2-4" class="no-inline">
                                <td class="pad15">Certification Goal</td>
                                
                                <?php //$selected = explode(',', $data[0]->language_goal);
                                $language_goal = Array('A1', 'A1+','A2', 'A2+','B1', 'B1+','B2', 'B2+','C1','C2');?>
                                <td>
                                    <!-- <span class="r-only"><?php echo @$data[0]->language_goal?></span> -->
                                    <span class="r-only"><?php 
                                    echo (@$data[0]->cert_studying == '' ? 'NULL' : @$data[0]->cert_studying); ?>
                                    </span> 

                                    <span class="e-only"><?php 
                                    echo (@$data[0]->cert_studying == '' ? 'NULL' : @$data[0]->cert_studying); ?>
                                    </span>

           <!--                          <select name="language_goal" class="e-only" required data-parsley-required-message="Please select your certification plan">
                                        <option disabled>Select Certification Goal</option>

                                        <?php
                                        foreach ($language_goal as $value) {
                                            if($value == @$data[0]->language_goal) {
                                                echo "<option selected>".$value."</option>";
                                            }
                                            else {
                                                echo "<option>".$value."</option>";
                                            }
                                        }
                                        ?>
                                    </select> -->

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
                            <td class="pad15">Time Zone</td>
                            <!-- <td>
                                <span class="r-only"><?php echo @$timezones[@$data[0]->user_timezone]; ?></span>
                                <?php echo form_dropdown('user_timezone', @$timezones, @$data[0]->user_timezone, 'id="td_value_2_8" class="e-only" style="width:100%" required data-parsley-required-message="Please select your timezone"')?>
                            </td> -->
                            <td class="r-only">
                                <span><?php echo @$user_tz; ?></span>
                                
                            </td>
                            <td class="e-only" style="cursor:not-allowed;background: #ebebeb;color: #939393;">
                                        <span><?php echo @$user_tz; ?></span>
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
    <?php echo form_open_multipart('account/identity/' . @$form_action . '/education', 'id="form_coach" role="form" data-parsley-validate'); ?>
    <div class="box clearfix padding-t-40">
        <div class="content-title m-lr-20">
            <span class="left">Experience</span>
            <div class="edit action-icon">
                <button id="btn_save_info" name="__submit" type="submit" class="pure-button btn-tiny btn-white-tertinary m-b-15 save_click asd">SAVE</button>
                <i class="icon icon-close close_click" title="Cancel"></i>
                <i class="icon icon-edit edit_click" title="Edit"></i>
            </div>
        </div>
        <div class="content">
            <div class="pure-u-24-24 prelative">
                <table class="table-no-border2"> 
                    <tbody>
                        <tr class="no-inline">
                            <td class="pad15">Higher Education</td>
                            <td class="pad15">
                                <span class="r-only"><?php echo @$data[0]->higher_education; ?></span>
                                <input name="higher_education" value="<?php echo @$data[0]->higher_education;?>" type="text" id="td_value_3_0" class="e-only block-school" placeholder="School" required data-parsley-errors-container="#required1" data-parsley-required-message="Please input your higher education">
                                <span id="required1"></span>
                            </td>
                        </tr>
                        <tr class="no-inline">
                            <td class="pad15">Undergraduate</td>
                            <td class="pad15">
                                <span class="r-only"><?php echo @$data[0]->undergraduate; ?></span>
                                <input name="undergraduate" value="<?php echo @$data[0]->undergraduate;?>" type="text" id="td_value_3_0" class="e-only block-school" placeholder="Major" required data-parsley-errors-container="#required2" data-parsley-required-message="Please input your undergraduate">
                                <span id="required2"></span>
                            </td>
                        </tr>
                        <tr class="no-inline">
                            <td class="pad15">Master's</td>
                            <td class="pad15 no-ul">
                                <span class="r-only"><?php echo @$data[0]->masters; ?></span>
                                <input name="masters" value="<?php echo @$data[0]->masters;?>" type="text" class="e-only block-school" placeholder="Major" data-parsley-required="false">
                            </td>
                        </tr>
                        <tr class="no-inline">
                            <td class="pad15">Ph.D.</td>
                            <td class="pad15  no-ul">
                                <span class="r-only"><?php echo @$data[0]->phd; ?></span>
                                <input name="phd" value="<?php echo @$data[0]->phd;?>" type="text" class="e-only block-school" placeholder="School" data-parsley-required="false">
                            </td>
                        </tr>
                        <tr class="no-inline">
                            <td class="pad15">Credentials</td>
                            <td>
                                <span class="r-only"><?php echo @$data[0]->teaching_credential; ?></span>
                                <input name="teaching_credential" type="text" value="<?php echo @$data[0]->teaching_credential; ?>" id="td_value_3_0" class="e-only" required data-parsley-required-message="Please input your teaching credential">
                            </td>
                        </tr>
                        <tr id="active-3-1" class="no-inline">
                            <td class="pad15">DynEd English Certification</td>

                            <td>
                                <span class="r-only"><?php echo @$data[0]->dyned_certification_level; ?></span>
                                <input name="dyned_certification_level" type="text" value="<?php echo @$data[0]->dyned_certification_level; ?>" id="td_value_3_1" class="e-only" required data-parsley-required-message="Please input your DynEd english certification">
                            </td>
                        </tr>
                        <tr id="active-3-2" class="no-inline">
                            <td class="pad15">Year of (Teaching) Experience</td>

                            <td>
                                <span class="r-only"><?php echo @$data[0]->year_experience; ?></span>
                                <input name="year_experience" type="text" value="<?php echo @$data[0]->year_experience; ?>" id="td_value_3_2" class="e-only" data-parsley-type='digits' required data-parsley-type-message="Please input numbers only" data-parsley-required-message="Please input your year of experience">
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





<!-- ======================= -->
<!-- <div class="heading text-cl-primary pad-t-10">

    <h1 class="margin0 padding-l-30 left">Profile</h1>

</div> -->

<div class="box clear-both">

    <div class="content border-none list-frm">
        <?php if($this->auth_manager->role() == 'RAD' || $this->auth_manager->role() == 'ADM'){ ?>
        <div class="content-title clear-both">
            <span class="left">Basic Info Settings</span>
            <form action="<?php echo site_url('account/identity/update/account');?>" method="POST">
            <div class="edit action-icon">
                <button id="btn_save_info" name="_submit" value="SAVE" type="submit" class="pure-button btn-tiny btn-white-tertinary m-b-15 save_click asd">SAVE</button>
                <i class="icon icon-close close_click" title="Cancel"></i>
                <i class="icon icon-edit edit_click" title="Edit"></i>
            </div>
        </div>
        <div class="pure-g">           
            <div class="profile-detail prelative padding-t-20 width100perc">
                
                <table class="table-no-border2"> 
                    <tbody>
                        <tr>
                            <td class="pad15">Full Name</td>
                            <td>
                                <span class="r-only"><?php echo @$data[0]->fullname; ?></span>
                                <input name="fullname" type="text" id="td_value_4_0" value="<?php echo @$data[0]->fullname; ?>" class="e-only" required data-parsley-required-message="Fullname can’t be blank">
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Email</td>
                            <td  class="r-only">
                                <span><?php echo @$data[0]->email; ?></span>
                            </td>
                            <td class="e-only" style="cursor:not-allowed;background: #ebebeb;color: #939393;padding-left: 5px;">
                                <span><?php echo @$data[0]->email; ?></span>
                            </td>
                        </tr>
                        <tr id="active-2-8" class="no-inline">
                            <td class="pad15">Timezone</td>
                            <!-- <td>
                                <span class="r-only"><?php echo @$timezones[@$data[0]->user_timezone]; ?></span>
                                <?php echo form_dropdown('user_timezone', @$timezones, @$data[0]->user_timezone, 'id="td_value_2_8" class="e-only" style="width:100%" required required data-parsley-required-message="Please select your timezone"')?>
                            </td> -->
                            <td class="r-only">
                                <span><?php echo @$user_tz; ?></span>
                              
                            </td>
                            <td class="e-only" style="cursor:not-allowed;background: #ebebeb;color: #939393;">
                                <span><?php echo @$user_tz; ?></span>
                            </td>
                        </tr>
                    </tbody>    
                </table>
            </div>
            <?php echo form_close();?>
        </div>
        <?php } ?>
        <div class="content-title padding-t-25">
            <span>Change Password</span>
        </div>
        <div class="change-pass-form padding-t-20 padding-l-0">
            <div class="profile-detail prelative padding-tr-15">
            <form action="<?php echo site_url('account/identity/update/account');?>" method="POST">            
                <table class="table-no-border2"> 
                    <tbody>
                        <tr>
                            <td class="pad15">Current Password</td>
                            <td>
                                <input name="old_password" type="password" value="" id="td_value_1_0" class="border-none" required data-parsley-required-message="Please input Old Password">
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">New Password</td>
                            <td>
                                <input name="new_password" type="password" value="" class="border-none" required data-parsley-required-message="Please input New Password">
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Confirm Password</td>
                            <td>
                                <input name="confirm_password" type="password" value="" id="td_value_1_2" class="border-none" required data-parsley-required-message="Please Confirm Password">
                            </td>
                        </tr>
                    </tbody>    
                </table>
            </div>
            <div class="save-cancel-btn text-right padding-t-25">
                <button type="submit" name="_submit" value="SUBMITPASS" class="pure-button btn-blue btn-expand btn-small btn-save-del">Save</button>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>


<?php //if (@$data[0]->dyned_pro_id) { 
if ($this->auth_manager->role() == 'STD') {
?>
    <div class="box">
            <div class="content-title padding-t-25 m-lr-20">
                DynEd Pro Info
            </div>
             <!-- block edit -->
  <!--           <div class="pure-u-12-24">
                <div class="edit action-icon">

                    <?php echo form_submit('__submit', 'SAVE', "class='pure-button btn-tiny btn-white-tertinary m-b-15 save_click'"); ?>

                </div>
            </div> -->
            <!-- end block edit -->
        

        <div class="content">
            <div class="pure-u-24-24 prelative">

                <?php
                if(@$data[0]->dyned_pro_id && @$data[0]->server_dyned_pro){ ?>

                <table class="table-no-border2"> 
                    <tbody>
                        <tr id="active-5-0" class="no-inline">
                            <td class="pad15">DynEd PRO ID</td>

                            <td>
                                <span><?php echo @$data[0]->dyned_pro_id; ?></span>
                            </td>
                        </tr>
                        <tr id="active-5-0" class="no-inline">
                            <td class="pad15">Server</td>

                            <td>
                                <span><?php echo @$server_dyned_pro[@$data[0]->server_dyned_pro]; ?></span>
                            </td>
                        </tr>
                    </tbody>
                </table>        
                <a class="pure-button btn-small btn-white m-t-20 dc" onclick="confirmation('<?php echo(site_url('account/identity/disconnect_to_dyned_pro'));?>', 'single', 'Disconnect From DynEd PRO', '', 'dc');">Disconnect From DynEd PRO</a>
                <?php
                }
                else{ ?>

                    <div class="padding15"><div class="no-result"><a class="pure-button btn-small btn-white" onclick="location.href='<?php echo(site_url('student/student_vrm/single_student'));?>'">Connect To DynEd PRO</a></div></div>

                <?php
                }
                ?>
            </div>  
        </div>  
        <?php echo form_close(); ?> 
    </div>
<?php } ?>
<script>
$(function() {
    $('textarea').css({'resize': 'none'});
    $('.e-only').hide();
    $('.close_click').hide();
    $('.save_click').hide();

    var arrText= new Array();
    var arrTextarea= new Array();
    var arrSelect= new Array();
    var arrMultiple= new Array();

    var isEnabled = true;

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

                $('#form_account').parsley().reset();
                <?php if ($this->auth_manager->role() != 'ADM') { ?>
                $('#form_info').parsley().reset();
                <?php } ?>
                <?php if ($this->auth_manager->role() == 'CCH') { ?>
                $('#form_coach').parsley().reset();
                <?php } ?>    
                <?php if ($this->auth_manager->role() == 'CCH' || $this->auth_manager->role() == 'STD') { ?>
                $('#form_more_info').parsley().reset();
                <?php } ?>

                $('input[type=text]', _each).each(function(){
                    arrText.push($(this).val());
                });

                $('select', _each).each(function(){
                    arrSelect.push($(this).val());
                });

                $('textarea', _each).each(function(){
                    arrTextarea.push($(this).val());
                });
                
                arrMultiple = $('.multiple-select').val();
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

            $('#form_account').parsley().reset();
            <?php if ($this->auth_manager->role() == 'CCH') { ?>
            $('#form_coach').parsley().reset();
            <?php } ?>    
            <?php if ($this->auth_manager->role() == 'CCH' || $this->auth_manager->role() == 'STD') { ?>
            $('#form_more_info').parsley().reset();
            <?php } ?>
            <?php if ($this->auth_manager->role() != 'ADM') { ?>
            $('#form_info').parsley().reset();
            <?php } ?>

            var input = $('input[type=text]', _each);

            for(i = 0; i < input.length; i++) {
              input[i].value = arrText[i];
            }

            var select = $('select', _each);

            for(i = 0; i < select.length; i++) {
              select[i].value = arrSelect[i];
            }

            var textarea = $('textarea', _each);

            for(i = 0; i < textarea.length; i++) {
              textarea[i].value = arrTextarea[i];
            }

            var multiple = $('.multiple-select');
            multiple.val(arrMultiple).trigger("change");

            
            arrText = [];
            arrTextarea = [];
            arrSelect = [];
            arrMultiple = [];

            
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
    })


    $('.caption').click(function () {
        $(".dropdown-form-photo").show();
    });

    $('.cancel-upload').click(function () {
        $(".dropdown-form-photo").hide();
    });

    $('body').on('click', '.upload-click', function () {
        $('#profile_picture').click();
    });

    $('.parsley-errors-list').css({'position':'absolute','bottom':'0','right':'0','color':'#E9322D'});
    $('input.parsley-error').css({'border':'none'});
    $('select.parsley-error').css({'border':'none'});
    $('textarea.parsley-error').css({'border':'none'});

    $('.multiple-select').select2({
         placeholder: "<?php echo ($this->auth_manager->role() == 'STD') ? 'Preferred Languages':'Spoken Languages';?>"
    });
    $('.multiple-select-certificate').select2({
         placeholder: "Select Certification Goal"
    });

    $('#form_info').parsley();
    $('#form_more_info').parsley();
    parsley_float();

    $('#parsley-id-multiple-spoken_lang').css({'position':'absolute','bottom':'75px','right':'0','color':'#E9322D'});

    $(".select2").addClass("e-only");
    $(".e-only").hide();
    $(".datepicker").datepicker({
        format: 'yyyy-mm-dd',
        changeMonth: true,
        changeYear: true,
        defaultDate: new Date(1990, 00, 01),
        endDate: "2010,00,01"
    });

    $('.input-daterange').datepicker({
        format: "yyyy",
        startView: 2,
        minViewMode: 2
    });

    $('.select2-container--default .select2-selection--multiple').css("cssText", "border: none !important;");
    

    $(".multiple-select").on('change',function(){
        document.getElementById('spoken_language').value = $(".multiple-select").val();
    });

    $('.save_click').on('click',function(){
        document.getElementById('spoken_language').value = $(".multiple-select").val();
    });
    
    $('a.dc').click(function(){
            return false;
    });

    $('a.reschedule-session2').click(function(){
            return false;
    });
})

</script>

<script>
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].onclick = function(){
            this.classList.toggle("active");
            this.nextElementSibling.classList.toggle("show");
      }
    }
</script>

<script>
var dial_code = $('#dial_code').val();
var country = $('#td_value_2_3').val();

$( "#td_value_2_3" ).change(function() {
  var dial_code = $('#dial_code').val();
  var country = $(this).val();
    if((dial_code != '') && (country != '')){
        $('#dial_code').addClass('loadinggif');
        $.ajax({
            url: "<?php echo site_url('account/identity/dial_code');?>",
            type: "POST",
            data: { dial_code : dial_code, country : country },
            dataType: "html",
            success: function (response) {
               //console.log(response); 
              $('#dial_code').removeClass('loadinggif'); 
               var dial_code = response;

               $('input[name=dial_code]').val(dial_code);          

            },
        });
    }

  });
    
</script>