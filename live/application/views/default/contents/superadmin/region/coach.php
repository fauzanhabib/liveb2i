<?php if($this->auth_manager->role() == 'RAD') {
    $role_link = "superadmin";
} else {
    $role_link = "admin";
}

?>

<div class="heading text-cl-primary padding15">
    <?php if($this->uri->segment(2) == 'manage_partner'){ ?>
        <h1 class="margin0">Affiliate Settings</h1>
    <?php } else { ?>
        <h1 class="margin0">Region Settings</h1>
    <?php } ?>

</div>

<div class="box">
    <div class="heading pure-g">

        <div class="left-list-tabs pure-menu pure-menu-horizontal">
            <ul class="pure-menu-list margin-left70">
                <?php if($role_link == "superadmin") { ?>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('superadmin/'.$this->uri->segment(2).'/setting/'.@$this->uri->segment(4).'/student');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 ">Student Affiliate</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('superadmin/'.$this->uri->segment(2).'/setting/'.@$this->uri->segment(4).'/coach');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 active-tabs-blue">Coach Affiliate</a></li>
                <?php } else { ?>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('admin/manage_partner/setting/'.$id.'/student');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 ">Student Affiliate</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('admin/manage_partner/setting/'.$id.'/coach');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 active-tabs-blue">Coach Affiliate</a></li>
                <?php } ?>
            </ul>

            <ul class="pure-menu-list m-r-10 right">
                <li class="pure-menu-item pure-menu-selected no-hover">
                    <a href="<?php echo $back;?>">
                        <button type="button" class="btn-small border-1-blue bg-white-fff text-cl-tertiary">
                        <div class="left padding-r-5">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve" class="width15">
                            <g id="back-one-page">
                                <g>
                                    <g id="XMLID_13_">
                                        <g>
                                            <path style="fill-rule:evenodd;clip-rule:evenodd;fill:#51B3E8;" d="M20,0c11.046,0,20,8.954,20,20s-8.954,20-20,20
                                                S0,31.046,0,20S8.954,0,20,0z M37.002,20c0-9.39-7.612-17.002-17.002-17.002C10.611,2.998,2.998,10.61,2.998,20
                                                c0,9.389,7.613,17.002,17.002,17.002C29.39,37.002,37.002,29.389,37.002,20z"></path>
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <g>
                                                <path style="fill:#51B3E8;" d="M27.734,22.141H13.636c-1.182,0-2.141-0.958-2.141-2.141s0.959-2.141,2.141-2.141h14.098
                                                    c1.182,0,2.141,0.958,2.141,2.141S28.916,22.141,27.734,22.141z"></path>
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <g>
                                                    <path style="fill:#51B3E8;" d="M19.465,24.27l-2.611-2.822c-0.756-0.818-0.756-2.08,0-2.897l2.611-2.822
                                                        c1.264-1.366,0.295-3.582-1.566-3.582h-0.353c-0.595,0-1.162,0.248-1.566,0.685l-5.288,5.719
                                                        c-0.756,0.817-0.756,2.079,0,2.896l5.288,5.719c0.404,0.437,0.971,0.685,1.566,0.685h0.353
                                                        C19.76,27.852,20.729,25.636,19.465,24.27z"></path>
                                                </g>
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
                    </a>
                </li>
            </ul>
        </div>

    </div>

    <div class="content list-frm">
         <?php echo form_open_multipart($role_link.'/'.$this->uri->segment(2).'/update_setting/'.$id, 'id="form" role="form" class="pure-form pure-form-aligned padding15" data-parsley-validate'); ?>

        <div class="box">
            <div class="edit action-icon padding-r-40">
                <i class="icon icon-edit edit_click" title="Edit"></i>
            </div>
            <form action="#" method="POST" class="text-right text-cl-grey">
            <div class="sess-duration pure-g border-b-1 width90perc margin-auto">
                <div class="grids pure-u-md-1-24"></div>
                <div class="grids pure-u-md-10-24 padding-t-12 font-semi-bold">
                    Duration per Session
                </div>
                <div class="grids pure-u-md-8-24 padding-t-12">
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
                <div class="grids pure-u-md-5-24"></div>
            </div>
            <div class="margin-auto width90perc font-semi-bold padding-tb-10 font-18 text-cl-tertiary">
                <!-- <div class="grids pure-u-md-1-24"></div> -->
                Token Cost : 
            </div>
            <div class="sess-duration pure-g margin0 width90perc margin-auto">
                <div class="grids pure-u-md-1-24"></div>
                <div class="grids pure-u-md-10-24 padding-t-12 font-semi-bold">
                    Coach Cost 
                </div>
                <div class="grids pure-u-md-11-24 padding-t-12">
                    <?php if($this->auth_manager->role() == 'ADM' || $this->uri->segment(2) == 'manage_partner') { ?>
                    <span class="r-only"><?php echo(@$data[0]->standard_coach_cost); ?> Tokens</span><span class="right">&nbsp;(Max : <?php echo($standard_coach_cost); ?>)</span>
                    <?php }else{ ?>
                    <span class="r-only"><?php echo(@$data[0]->standard_coach_cost); ?> Tokens</span>
                    <?php } ?>
                    <input name="standard_coach_cost" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->standard_coach_cost); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                           
                </div>
                <!-- <div class="grids pure-u-md-5-24"></div> -->
            </div>
            <div class="sess-duration pure-g margin0 border-b-1 width90perc margin-auto">
                <div class="grids pure-u-md-1-24"></div>
                <div class="grids pure-u-md-10-24 padding-t-12 font-semi-bold">
                    Elite Coach Cost 
                </div>
                <div class="grids pure-u-md-11-24 padding-t-12">
                    <?php if($this->auth_manager->role() == 'ADM' || $this->uri->segment(2) == 'manage_partner') { ?>
                    <span class="r-only"><?php echo(@$data[0]->elite_coach_cost); ?> Tokens</span><span class="right">&nbsp;(Max : <?php echo($elite_coach_cost); ?>)</span>
                    <?php }else{ ?>
                    <span class="r-only"><?php echo(@$data[0]->elite_coach_cost); ?> Tokens</span>
                    <?php } ?>
                    <input name="elite_coach_cost" type="text" class="pure-input-1-2 e-only" value="<?php echo(@$data[0]->elite_coach_cost); ?>" data-parsley-type="digits" required data-parsley-required-message="This field can’t be blank" data-parsley-type-message="Please input numbers only">
                           
                </div>
                <!-- <div class="grids pure-u-md-5-24"></div> -->
            </div>

        </div>
        <div class="save-cancel-btn text-right padding-t-20 padding-r-53">
            <button type="submit" name="__submit" value="region_coach" class="pure-button btn-blue btn-expand btn-small save_click">Save</button>
            <button class="pure-button btn-red btn-expand btn-small close_click" type="button">
                <a href="<?php echo site_url($role_link.'/'.$this->uri->segment(2).'/setting/'.$id.'/coach');?>">Cancel</a>
            </button>
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