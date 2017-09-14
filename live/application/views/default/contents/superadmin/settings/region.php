<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Global Region Settings</h1>
</div>
<div class="box b-f3-2">
    <!-- <div class="heading pure-g">
        <div class="pure-u-1 tab-list tab-link">
            <ul>
                <li><a href="#">Account Info</a></li>
                <li class="current"><a href="#" class="active">Partner Setting</a></li>
            </ul>
        </div>
    </div> -->
    <div class="content padding0">
        
        <div class="list-frm">
            <?php echo form_open_multipart('superadmin/settings/update_setting/region_student', 'id="form" role="form" class="pure-form pure-form-aligned padding15" data-parsley-validate'); ?>
            <div class="heading pure-g">
                <div class="pure-u-12-24">
                    <h3 class="h3 font-normal padding-tb-15 text-cl-secondary">STUDENT AFFILIATE SETTING</h3>
                </div>
                <div class="pure-u-12-24">
                    <div class="edit action-icon">
                        <?php echo form_submit('__submit', 'SAVE', 'class="pure-button btn-tiny btn-white-tertinary m-b-10 save_click"'); ?>
                        <i class="icon icon-close close_click" title="Cancel"></i>
                        <i class="icon icon-edit edit_click" title="Edit"></i>

                    </div>
                </div>
            </div>

                <fieldset>
                    <div class="pure-control-group" style="margin-bottom:8px">
                        <h4 class="m-b-15">Set Maximum Token For Region</h4>
                        <div class="label">
                            <label for="student">Max Token</label>
                        </div>
                        <div class="input">
                            <span class="r-only"><?php echo(@$data[0]->max_token); ?></span>
                            <input name="max_token" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_token); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                        </div>
                    </div>
                    <div class="b-f3-1"></div>
                    <div class="pure-control-group" style="margin-bottom:8px">
                        <h4 class="m-b-15">Set Maximum Token per Student</h4>
                        <div class="label">
                            <label for="student">per Student</label>
                        </div>
                        <div class="input">
                            <span class="r-only"><?php echo(@$data[0]->max_token_for_student); ?></span>
                           <input name="max_token_for_student" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_token_for_student); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                             
                        </div>
                    </div>
                    <div class="b-f3-1"></div>
					
                    <div class="pure-control-group" style="margin-bottom:8px">
                        <h4 class="m-b-15">Set Maximum Student per Class</h4>
                        <div class="label">
                            <label for="student">Max Student per Class</label>
                        </div>
                        <div class="input">
                            <span class="r-only"><?php echo(@$data[0]->max_student_class); ?></span>
                            <input name="max_student_class" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_student_class); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                        </div>
                    </div>
                    <div class="b-f3-1"></div>
					 <div class="pure-control-group" style="margin-bottom:8px">
                        <h4 class="m-b-15">Set Maximum Student per Affiliate</h4>
                        <div class="label">
                            <label for="student">Max Student per Affiliate</label>
                        </div>
                        <div class="input">
                            <span class="r-only"><?php echo(@$data[0]->max_student_supplier); ?></span>
                            <input name="max_student_supplier" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_student_supplier); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                        </div>
                    </div>
                    <div class="b-f3-1"></div>
                    <div class="pure-control-group" style="margin-bottom:8px">
                        <h4 class="m-b-15">Set Maximum Day per Week</h4>
                        <div class="label">
                            <label for="student">Max Day</label>
                        </div>
                        <div class="input">
                            <span class="r-only"><?php echo(@$data[0]->max_day_per_week); ?></span>
                            <input name="max_day_per_week" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_day_per_week); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                        </div>
                    </div>
                    <div class="b-f3-1"></div>
					<div class="pure-control-group" style="margin-bottom:8px">
                        <h4 class="m-b-15">Set Maximum Session per Day</h4>
                        <div class="label">
                            <label for="student">Max Session</label>
                        </div>
                        <div class="input">
                            <span class="r-only"><?php echo(@$data[0]->max_session_per_day); ?></span>
                            <input name="max_session_per_day" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_session_per_day); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                        </div>
                    </div>
                    <div class="b-f3-1"></div>

        
            <div class="heading pure-g">
                <div class="pure-u-12-24">
                    <h3 class="h3 font-normal padding-tb-15 text-cl-secondary">COACH AFFILIATE SETTING</h3>
                </div>
                
            </div>
            <?php //echo form_open_multipart('admin/partner_setting/update_setting/coach', 'role="form"'); ?>
                
                    <div class="pure-control-group" style="margin: 10px 0px 15px;">
                        <div class="label" style="vertical-align:top">
                            <label>Duration per Session</label>
                        </div>
                        <div class="input">
                            <span class="r-only"><?php echo(@$data[0]->session_duration); ?> Minutes</span>
                            <div class="pure-g e-only">
                                <div class="pure-u-1 m-b-5">
                                    <label class="radio d-i-block m-b-15">
                                        <input type="radio" name="session_duration" value="15" <?php echo(@$data[0]->session_duration == 15 ? 'checked="true"' : '');?>>
                                        <span class="outer m-r-10"><span class="inner"></span></span>
                                        15 Minutes
                                    </label>
                                </div>
                                <div class="pure-u-1">
                                    <label class="radio d-i-block m-b-15">
                                        <input type="radio" name="session_duration" value="25" <?php echo(@$data[0]->session_duration == 25 ? 'checked="true"' : '');?>>
                                        <span class="outer m-r-10"><span class="inner"></span></span>
                                        25 Minutes
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="b-f3-1"></div>

                    <div class="heading pure-g">
                        <div class="pure-u-12-24">
                            <h3 class="h3 font-normal padding-tb-15 text-cl-secondary">REGION SETTING</h3>
                        </div>
                        
                    </div>

                    <div class="pure-control-group" style="margin: 10px 0px 15px;">
                        <div class="label" style="vertical-align:top">
                            <label>Status set Setting</label>
                        </div>
                        <?php $status_setting = '';
                            if(@$data[0]->status_set_setting == 0){
                                $status_setting = 'Unavailable';
                            } else if(@$data[0]->status_set_setting == 1){
                                $status_setting = 'Available';
                            }

                        ?>
                        <div class="input">
                            <span class="r-only"><?php echo @$status_setting; ?></span>
                            <div class="pure-g e-only">
                                <div class="pure-u-1 m-b-5">
                                    <label class="radio d-i-block m-b-15">
                                        <input type="radio" name="status_set_setting" value="0" <?php echo(@$data[0]->status_set_setting == 0 ? 'checked="true"' : '');?>>
                                        <span class="outer m-r-10"><span class="inner"></span></span>
                                        Available
                                    </label>
                                </div>
                                <div class="pure-u-1">
                                    <label class="radio d-i-block m-b-15">
                                        <input type="radio" name="status_set_setting" value="1" <?php echo(@$data[0]->status_set_setting == 1 ? 'checked="true"' : '');?>>
                                        <span class="outer m-r-10"><span class="inner"></span></span>
                                        Unavailable
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="b-f3-1"></div>

                </fieldset>
            <?php echo form_close(); ?>
        </div>
        
    </div>
</div>

<script type="text/javascript">
    $(function(){

    	$('h4').css({'color':'#434343'});
        $('.outer').css({'width':'10px','height':'10px','margin':'1px 12px 0px 3px'});
        $('.inner').css({'width':'6px','height':'6px'});

    	if($(document).width()<=600) {
    		$('.pure-control-group .input').css({'width':'8em'});
    	}

        $('.list-frm').each(function(){

            var _each = $(this);

            var _close = $('.close_click', _each);
            var _save = $('.save_click', _each);
            var _edit = $('.edit_click', _each);
            var _eonly = $('.e-only', _each);
            var _ronly = $('.r-only', _each)

            _eonly.hide();
            _close.hide();
            _save.hide();

            $(_edit).click(function () {
                _eonly.show();
                _ronly.hide();
                _close.show();
                _save.show();
                _edit.hide();
               // $("#td_value_1_1").focus();

                $('.e-only').not(_eonly).hide();
                $('.r-only').not(_ronly).show();
                $('.close_click').not(_close).hide();
                $('.save_click').not(_save).hide();
                $('.edit_click').not(_edit).show();

                animationClick(_close, 'fadeIn');
                animationClick(_save, 'fadeIn');
            });

            $(_close).click(function () {
                _close.hide();
                _save.hide();
                _edit.show();
                _ronly.show();
                _eonly.hide();
                animationClick(_edit, 'fadeIn');
                $('#form').parsley().reset();
            });

            $(_save).click(function () {
                //_save.text('SAVING...');
                if ( $('#form').parsley().isValid() ) {
                    _close.hide();       
                }
                $("#load", _each).show().delay(3000).queue(function (next) {
                    $(this).hide();
                    _save.text('SAVE').hide;
                    //$('.save_click', _each).hide();
                    _edit.show();
                    _ronly.show();
                    _eonly.hide();
                    next();
                });
            });
        })

        

    })
</script>