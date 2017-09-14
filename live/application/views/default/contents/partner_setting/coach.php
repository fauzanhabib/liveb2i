<div class="heading text-cl-primary padding15">

    <h1 class="margin0">Affiliate Setting</h1>
</div>

<div class="box">
    <div class="heading pure-g">

        <div class="left-list-tabs pure-menu pure-menu-horizontal">
            <ul class="pure-menu-list margin-left70">
Affiliate                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('admin/partner_setting/setting_partner/student');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 ">Student Affiliate</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('admin/partner_setting/setting_partner/coach');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 active-tabs-blue">Coach Affiliate</a></li>
            </ul>
        </div>

    </div>

    <div class="content list-frm">
         <?php echo form_open_multipart('admin/partner_setting/update_setting/coach', 'id="form" role="form" class="pure-form pure-form-aligned padding15" data-parsley-validate'); ?>

        <div class="box text-center">
            <div class="edit action-icon padding-r-40">
                <!-- <i class="icon icon-edit edit_click" title="Edit"></i> -->
            </div>
            <form action="#" method="POST" class="text-right text-cl-grey">
                No Data
        </div>
        <div class="save-cancel-btn text-right padding-t-20 padding-r-53">
            <button type="submit" name="__submit" value="SAVE" class="pure-button btn-blue btn-expand btn-small save_click">Save</button>
            <button class="pure-button btn-red btn-expand btn-small close_click"><a href="<?php echo site_url('admin/partner_setting/setting_partner/');?>">Cancel</a></button>
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