<div class="heading text-cl-primary padding15">

    <h1 class="margin0">Global Affiliate Settings</h1>
</div>

<div class="box">
    <div class="heading pure-g">

        <div class="left-list-tabs pure-menu pure-menu-horizontal">
            <ul class="pure-menu-list margin-left70">
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('superadmin/settings/partner/student');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 active-tabs-blue">Student Affiliate</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('superadmin/settings/partner/coach');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">Coach Affiliate</a></li>
            </ul>
        </div>

    </div>

    <div class="content list-frm">
         <?php echo form_open_multipart('superadmin/settings/update_setting/partner', 'id="form" role="form" class="pure-form pure-form-aligned padding15" data-parsley-validate'); ?>

        <div class="box">
            <div class="edit action-icon padding-r-40">
                <i class="icon icon-edit edit_click" title="Edit"></i>
            </div>
            <form action="#" method="POST" class="text-right text-cl-grey">
            <div class="sess-duration pure-g width90perc margin-auto">
                <div class="grids box__setting font-semi-bold">
                    Maximum Tokens for Student Affiliate
                </div>
                <div class="grids box__setting">
                    <span class="r-only"><?php echo(@$data[0]->max_token); ?></span>
                    <input name="max_token" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_token); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                </div>
            </div>

            <div class="sess-duration pure-g margin0 width90perc margin-auto">
                <div class="grids box__setting font-semi-bold">
                    Set Maximum Token Per Student in Student Affiliate 
                </div>
                <div class="grids box__setting">
                    <span class="r-only"><?php echo(@$data[0]->max_token_for_student); ?></span>
                    <input name="max_token_for_student" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_token_for_student); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                           
                </div>
            </div>

            <div class="sess-duration pure-g margin0 width90perc margin-auto">
                <div class="grids box__setting font-semi-bold">
                    Maximum Student Per Class 
                </div>
                <div class="grids box__setting">
                        <span class="r-only"><?php echo(@$data[0]->max_student_class); ?></span>
                        <input name="max_student_class" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_student_class); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                </div>
            </div>

            <div class="sess-duration pure-g margin0 width90perc margin-auto">
                <div class="grids box__setting font-semi-bold">
                    Maximum Student for Student Affiliate
                </div>
                <div class="grids box__setting">
                    <span class="r-only"><?php echo(@$data[0]->max_student_supplier); ?></span>
                    <input name="max_student_supplier" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_student_supplier); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                </div>
            </div>

            <!-- <div class="sess-duration pure-g margin0 width90perc margin-auto">
                <div class="grids pure-u-md-1-24"></div>
                <div class="grids pure-u-md-10-24 padding-t-12 font-semi-bold">
                    Maximum Day Per Week 
                </div>
                <div class="grids pure-u-md-8-24 padding-t-12">
                       <span class="r-only"><?php echo(@$data[0]->max_day_per_week); ?></span>
                        <input name="max_day_per_week" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_day_per_week); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                </div>
                <div class="grids pure-u-md-5-24"></div>
            </div> -->

            <div class="sess-duration pure-g margin0 width90perc margin-auto border-b-1">
                <div class="grids box__setting font-semi-bold">
                    Maximum Sessions Per Day 
                </div>
                <div class="grids box__setting">
                    <span class="r-only"><?php echo(@$data[0]->max_session_per_day); ?></span>
                    <input name="max_session_per_day" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_session_per_day); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                </div>
            </div>
            
            <div class="margin-auto width90perc font-semi-bold padding-tb-10 font-18 text-cl-tertiary">
                <!-- <div class="grids pure-u-md-1-24"></div> -->
                Maximum Sessions for Student : 
            </div>
            <div class="sess-duration pure-g border-b-1 width90perc margin-auto">
                <div class="grids box__setting font-semi-bold">
                    Set Maximum Sessions for Student
                </div>
                <div class="grids box__setting">
                    <span class="r-only"><?php echo(@$data[0]->set_max_session); ?></span>
                    <div class="pure-g e-only margin-t-min7">
                        <!-- <div class="m-b-5 padding-r-10 left">
                            <label class="radio d-i-block m-b-15">
                                <input type="radio" name="set_max_session" value="Per X Number of Days" <?php echo(@$data[0]->set_max_session == 'Per X Number of Days' ? 'checked="true"' : '');?>>
                                <span class="outer m-r-10"><span class="inner"></span></span>
                                Per X Number of Days
                            </label>
                        </div> -->
                        <div class="">
                            <label class="radio d-i-block m-b-15">
                                <input type="radio" name="set_max_session" value="Per Week" <?php echo(@$data[0]->set_max_session == 'Per Week' ? 'checked="true"' : '');?>>
                                <span class="outer m-r-10"><span class="inner"></span></span>
                                Per Week
                            </label>
                        </div>
                    </div>    
                </div>
            </div>
            <!-- <div class="sess-duration pure-g margin0 width90perc margin-auto border-b-1 xday">
                <div class="grids pure-u-md-1-24"></div>
                <div class="grids pure-u-md-10-24 padding-t-12 font-semi-bold">
                    Maximum Sessions Per X Day 
                </div>
                <div class="grids pure-u-md-8-24 padding-t-12">
                    <span class="r-only"><?php echo(@$data[0]->max_session_per_x_day); ?></span>
                    <input name="max_session_per_x_day" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_session_per_x_day); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                </div>
                <div class="grids pure-u-md-5-24"></div>
            </div>
            
            <div class="sess-duration pure-g margin0 width90perc margin-auto border-b-1 xday">
                <div class="grids pure-u-md-1-24"></div>
                <div class="grids pure-u-md-10-24 padding-t-12 font-semi-bold">
                    X Day 
                </div>
                <div class="grids pure-u-md-8-24 padding-t-12">
                    <span class="r-only"><?php echo(@$data[0]->x_day); ?></span>
                    <input name="x_day" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->x_day); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                </div>
                <div class="grids pure-u-md-5-24"></div>
            </div> -->

            <div class="sess-duration pure-g margin0 width90perc margin-auto border-b-1 week">
                <div class="grids box__setting font-semi-bold">
                    Maximum Day Per Week 
                </div>
                <div class="grids box__setting">
                       <span class="r-only"><?php echo(@$data[0]->max_day_per_week); ?></span>
                        <input name="max_day_per_week" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_day_per_week); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                </div>
            </div>
        </div>
        <div class="save-cancel-btn text-right padding-t-20 padding-r-53">
            <button type="submit" name="__submit" value="partner_student" class="pure-button btn-blue btn-expand btn-small save_click">Save</button>
            <button class="pure-button btn-red btn-expand btn-small close_click" type="button"><a href="<?php echo site_url('superadmin/settings/partner/student');?>">Cancel</a></button>
        </div>
    </div>  
</div>



        <script type="text/javascript">
            $(function(){

                $('.list-frm').each(function(){

                    var _each = $(this);

                    var _close = $('.close_click', _each);
                    var _save = $('.save_click', _each);
                    var _edit = $('.edit_click', _each);
                    var _eonly = $('.e-only', _each);
                    var _ronly = $('.r-only', _each);
                    var _xday = $('.xday', _each);
                    var _week = $('.week', _each);

                    _eonly.hide();
                    _close.hide();
                    _save.hide();

                    var a = $('input[type=radio][name=set_max_session]:checked').val();
                    console.log(a);
                        if (a == 'Per X Number of Days') {
                            _week.hide();
                            _xday.show();
                            }else{
                            _xday.hide();
                            _week.show();
                            };

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

                        $('input[type=radio][name=set_max_session]').click(function(){
                        var b = $('input[type=radio][name=set_max_session]:checked').val();
                        console.log(b);
                        }).change(function(){
                            var b = $('input[type=radio][name=set_max_session]:checked').val();
                            if (b == 'Per X Number of Days') {
                            _week.hide();
                            _xday.show();
                            }else{
                            _xday.hide();
                            _week.show();
                            };
                        });

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