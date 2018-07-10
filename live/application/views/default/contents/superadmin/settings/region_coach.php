<div class="heading text-cl-primary padding15">

    <h1 class="margin0">Global Region Settings</h1>
</div>

<div class="box">
    <div class="heading pure-g">

        <div class="left-list-tabs pure-menu pure-menu-horizontal">
            <ul class="pure-menu-list margin-left70">
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('superadmin/settings/region/student');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 ">Student Affiliate</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('superadmin/settings/region/coach');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 active-tabs-blue">Coach Affiliate</a></li>
            </ul>
        </div>

    </div>

    <div class="content list-frm">
         <?php echo form_open_multipart('superadmin/settings/update_setting/region', 'id="form" role="form" class="pure-form pure-form-aligned padding15" data-parsley-validate'); ?>

        <div class="box">
            <div class="edit action-icon padding-r-40">
                <i class="icon icon-edit edit_click" title="Edit"></i>
            </div>
            <form action="#" method="POST" class="text-right text-cl-grey">
            <div class="sess-duration pure-g border-b-1 width90perc margin-auto">
                <div class="grids box__setting font-semi-bold">
                    Duration per Session
                </div>
                <div class="grids box__setting">
                    <span class="r-only"><?php echo(@$data[0]->session_duration); ?> Minutes</span>
                    <div class="pure-g e-only margin-t-min7">
                        <div class="m-b-5 padding-r-10 left">
                            <label class="radio d-i-block m-b-15">
                                <input type="radio" name="session_duration" value="15" <?php echo(@$data[0]->session_duration == 15 ? 'checked="true"' : '');?>>
                                <span class="outer m-r-10"><span class="inner"></span></span>
                                15 Minutes
                            </label>
                        </div>
                        <div class="">
                            <label class="radio d-i-block m-b-15">
                                <input type="radio" name="session_duration" value="25" <?php echo(@$data[0]->session_duration == 25 ? 'checked="true"' : '');?>>
                                <span class="outer m-r-10"><span class="inner"></span></span>
                                25 Minutes
                            </label>
                        </div>
                    </div>    
                </div>
            </div>
            <div class="margin-auto width90perc font-semi-bold padding-tb-10 font-18 text-cl-tertiary">
                <!-- <div class="grids pure-u-md-1-24"></div> -->
                Token Cost : 
            </div>
            <div class="sess-duration pure-g margin0 width90perc margin-auto">
                <div class="grids box__setting font-semi-bold">
                    Coach Cost 
                </div>
                <div class="grids box__setting">
                    <span class="r-only"><?php echo(@$data[0]->standard_coach_cost); ?> Tokens</span>
                    <input name="standard_coach_cost" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->standard_coach_cost); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                           
                </div>
            </div>
            <div class="sess-duration pure-g margin0 border-b-1 width90perc margin-auto">
                <div class="grids box__setting font-semi-bold">
                    Elite Coach Cost 
                </div>
                <div class="grids box__setting">
                    <span class="r-only"><?php echo(@$data[0]->elite_coach_cost); ?> Tokens</span>
                    <input name="elite_coach_cost" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->elite_coach_cost); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                           
                </div>
            </div>

        </div>
        <div class="save-cancel-btn text-right padding-t-20 padding-r-53">
            <button type="submit" name="__submit" value="region_coach" class="pure-button btn-blue btn-expand btn-small save_click">Save</button>
            <button class="pure-button btn-red btn-expand btn-small close_click" type="button"><a href="<?php echo site_url('superadmin/settings/region/');?>">Cancel</a></button>
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