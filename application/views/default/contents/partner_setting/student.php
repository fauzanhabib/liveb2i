<div class="heading text-cl-primary padding15">

    <h1 class="margin0">Partner Setting</h1>
</div>

<div class="box">
    <div class="heading pure-g">

        <div class="left-list-tabs pure-menu pure-menu-horizontal">
            <ul class="pure-menu-list margin-left70">
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('admin/partner_setting/setting_partner/student');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 active-tabs-blue">Student Supplier</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('admin/partner_setting/setting_partner/coach');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">Coach Supplier</a></li>
            </ul>
        </div>

    </div>

    <div class="content list-frm">
         <?php echo form_open_multipart('admin/partner_setting/update_setting/student', 'id="form" role="form" class="pure-form pure-form-aligned padding15" data-parsley-validate'); ?>

        <div class="box">
            <div class="edit action-icon padding-r-40">
                <i class="icon icon-edit edit_click" title="Edit"></i>
            </div>
            <form action="#" method="POST" class="text-right text-cl-grey">
            <div class="sess-duration pure-g width90perc margin-auto">
                <div class="grids pure-u-md-1-24"></div>
                <div class="grids pure-u-md-10-24 padding-t-12 font-semi-bold">
                    Maximum Tokens for Student Supplier
                </div>
                <div class="grids pure-u-md-8-24 padding-t-12">
                    <span class="r-only"><?php echo(@$data[0]->max_token); ?></span>
                    <input name="max_token" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_token); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                </div>
                <div class="grids pure-u-md-5-24"></div>
            </div>

            <div class="sess-duration pure-g margin0 width90perc margin-auto">
                <div class="grids pure-u-md-1-24"></div>
                <div class="grids pure-u-md-10-24 padding-t-12 font-semi-bold">
                    Set Maximum Token Per Student in Student Suppliers 
                </div>
                <div class="grids pure-u-md-8-24 padding-t-12">
                    <span class="r-only"><?php echo(@$data[0]->max_token_for_student); ?></span>
                    <input name="max_token_for_student" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_token_for_student); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                           
                </div>
                <div class="grids pure-u-md-5-24"></div>
            </div>

            <div class="sess-duration pure-g margin0 width90perc margin-auto">
                <div class="grids pure-u-md-1-24"></div>
                <div class="grids pure-u-md-10-24 padding-t-12 font-semi-bold">
                    Maximum Student Per Class 
                </div>
                <div class="grids pure-u-md-8-24 padding-t-12">
                        <span class="r-only"><?php echo(@$data[0]->max_student_class); ?></span>
                        <input name="max_student_class" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_student_class); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                </div>
                <div class="grids pure-u-md-5-24"></div>
            </div>

            <div class="sess-duration pure-g margin0 width90perc margin-auto">
                <div class="grids pure-u-md-1-24"></div>
                <div class="grids pure-u-md-10-24 padding-t-12 font-semi-bold">
                    Maximum Student for Student Supplier
                </div>
                <div class="grids pure-u-md-8-24 padding-t-12">
                    <span class="r-only"><?php echo(@$data[0]->max_student_supplier); ?></span>
                    <input name="max_student_supplier" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_student_supplier); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                </div>
                <div class="grids pure-u-md-5-24"></div>
            </div>

            <div class="sess-duration pure-g margin0 width90perc margin-auto">
                <div class="grids pure-u-md-1-24"></div>
                <div class="grids pure-u-md-10-24 padding-t-12 font-semi-bold">
                    Maximum Day Per Week 
                </div>
                <div class="grids pure-u-md-8-24 padding-t-12">
                       <span class="r-only"><?php echo(@$data[0]->max_day_per_week); ?></span>
                        <input name="max_day_per_week" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_day_per_week); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                </div>
                <div class="grids pure-u-md-5-24"></div>
            </div>

            <div class="sess-duration pure-g margin0 width90perc margin-auto border-b-1">
                <div class="grids pure-u-md-1-24"></div>
                <div class="grids pure-u-md-10-24 padding-t-12 font-semi-bold">
                    Maximum Sessions Per Day 
                </div>
                <div class="grids pure-u-md-8-24 padding-t-12">
                    <span class="r-only"><?php echo(@$data[0]->max_session_per_day); ?></span>
                    <input name="max_session_per_day" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->max_session_per_day); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                </div>
                <div class="grids pure-u-md-5-24"></div>
            </div>
        </div>
        <div class="save-cancel-btn text-right padding-t-20 padding-r-53">
            <button type="submit" name="__submit" value="region_student" class="pure-button btn-blue btn-expand btn-small save_click">Save</button>
            <button class="pure-button btn-red btn-expand btn-small close_click" type="button"><a href="<?php echo site_url('admin/partner_setting/setting_partner/student');?>">Cancel</a></button>
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