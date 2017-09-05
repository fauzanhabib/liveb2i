<?php if($this->auth_manager->role() == 'RAD') {
    $role_link = "superadmin";
} else {
    $role_link = "admin";
}

?>

<div class="heading text-cl-primary padding-l-20">

    <div class="breadcrumb-tabs pure-g">
        <div class="left-breadcrumb">
            <ul class="breadcrumb toolbar padding-l-0">
                <li id="breadcrum-home"><a href="#">
                    <div id="home-icon">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">
                        <g>
                            <path d="M2.7,14.1c0,0,0,0.3,0.3,0.3c0.4,0,3.7,0,3.7,0l0-3c0,0-0.1-0.5,0.4-0.5h1.5c0.6,0,0.5,0.5,0.5,0.5l0,3
                                c0,0,3.1,0,3.6,0c0.4,0,0.4-0.4,0.4-0.4V8.5L8.1,4L2.7,8.5L2.7,14.1z"/>
                            <path d="M0.7,8.1c0,0,0.5,0.8,1.5,0l5.9-5l5.6,5c1.2,0.8,1.6,0,1.6,0L8.1,1.5L0.7,8.1z"/>
                            <polygon points="13.6,3 12.1,3 12.1,4.8 13.6,6  "/>
                        </g>
                        </svg>
                    </div>
                </a></li>
                <?php if($role_link == 'superadmin'){ ?>
                <li><a href="<?php echo site_url($role_link.'/region/index/active');?>">Regions</a></li>

                <li>
                    <a href="<?php echo site_url($role_link.'/region/detail/'.$region_id);?>">
                        <?php echo @$this->common_function->get_info_region($region_id)[0]->region_id;?>
                    </a>
                </li>
                <?php } ?>
                <?php if($role_link == 'admin'){ ?>
                    <li><a href="<?php echo site_url('admin/manage_partner');?>">Partner</a></li>

                <?php } ?>
                <li><a href="#"><?php echo $partner->name;?></a></li>

            </ul>
        </div>
    </div>

    <!-- <div class="profilePic-top thumb-small left img-circle-big" style="border: 1px solid #fff;">
        <img src="<?php echo base_url(@$partner->profile_picture); ?>" width="150" class="pure-img fit-cover-top" />
    </div> -->
    <div class="text-center profile-image padding-r-20 left">
        <div class="thumb-small">
            <form action="<?php echo site_url('admin/manage_partner/upload_profile_picture/'.$partner_id);?>" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">                       
            <img src="<?php echo base_url($partner->profile_picture);?>" width="150" height="200" class="pure-img fit-cover img-circle-big">
            <?php if ($role_link == "admin"){ ?>
            <div class="caption" style="opacity: 0;">
                EDIT
            </div>
            <?php } ?>
            <div class="dropdown-form-photo text-center">
                <input id="profile_picture" type="file" accept="image/*" name="profile_picture" style="width:144px;margin-bottom:5px;" class=" button-small btn-book btn-save pure-button">
                <input type="submit" name="__submit" value="Save" id="btn_upload" class="pure-button btn-small btn-tertiary" disabled="">                            
                <button type="reset" class="pure-button btn-red btn-small cancel-upload">Cancel</button>
            </div>
            </form>                    
        </div>
    </div>

    <div class="tag">
        <h1 class="padding-l-15 text-cl-secondary font-semi-bold">&nbsp;&nbsp;<?php echo @$partner->name; ?></h1>
        <span class="padding-l-18 text-cl-secondary font-18 font-semi-bold"><?php echo @$this->common_function->get_partner_type($partner->id); ?></span><br>
        <span class="padding-l-18 font-14 text-cl-secondary"><?php echo @$partner->state;?>, <?php echo @$partner->country;?></span>
    </div>

    <div class="left-tabs pure-menu pure-menu-horizontal padding-t-17">
        <ul class="pure-menu-list padding-t-18 left">
            <li class="pure-menu-item padding-tb-9 bg-primary font-semi-bold"><a href="<?php echo site_url($role_link.'/manage_partner/detail/'.$partner_id);?>" class="pure-menu-link font-14">Partner Profile</a></li>
            <li class="pure-menu-item padding-tb-9 bg-tertiary font-semi-bold"><a href="<?php echo site_url($role_link.'/manage_partner/partner/student/'.$partner_id.'/'.$region_id);?>" class="pure-menu-link font-14">Student Partner</a></li>
            <li class="pure-menu-item padding-tb-9 bg-tertiary font-semi-bold"><a href="<?php echo site_url($role_link.'/manage_partner/partner/coach/'.$partner_id.'/'.$region_id);?>" class="pure-menu-link font-14">Coach Partner</a></li>
            <li class="pure-menu-item padding-tb-9 no-hover">
                <?php if($role_link == 'superadmin'){ ?>
                <a href="<?php echo site_url($role_link.'/region/detail/'.$region_id);?>">
                    <button class="btn-small border-1-blue bg-white-fff text-cl-tertiary height38" >
                    <div class="left padding-r-5">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve" class="width15">
                        <g id="back-one-page">
                            <g>
                                <g id="XMLID_13_">
                                    <g>
                                        <path style="fill-rule:evenodd;clip-rule:evenodd;fill:#51B3E8;" d="M20,0c11.046,0,20,8.954,20,20s-8.954,20-20,20
                                            S0,31.046,0,20S8.954,0,20,0z M37.002,20c0-9.39-7.612-17.002-17.002-17.002C10.611,2.998,2.998,10.61,2.998,20
                                            c0,9.389,7.613,17.002,17.002,17.002C29.39,37.002,37.002,29.389,37.002,20z"/>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <g>
                                            <path style="fill:#51B3E8;" d="M27.734,22.141H13.636c-1.182,0-2.141-0.958-2.141-2.141s0.959-2.141,2.141-2.141h14.098
                                                c1.182,0,2.141,0.958,2.141,2.141S28.916,22.141,27.734,22.141z"/>
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <g>
                                                <path style="fill:#51B3E8;" d="M19.465,24.27l-2.611-2.822c-0.756-0.818-0.756-2.08,0-2.897l2.611-2.822
                                                    c1.264-1.366,0.295-3.582-1.566-3.582h-0.353c-0.595,0-1.162,0.248-1.566,0.685l-5.288,5.719
                                                    c-0.756,0.817-0.756,2.079,0,2.896l5.288,5.719c0.404,0.437,0.971,0.685,1.566,0.685h0.353
                                                    C19.76,27.852,20.729,25.636,19.465,24.27z"/>
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
                <?php } ?>
            </li>
            <?php if($status_set_setting == 1){ ?>
            <!-- <li class="pure-menu-item padding-tb-9 no-hover left-200">
                <a href="<?php echo site_url($role_link.'/manage_partner/setting/'.$partner_id);?>">
                <button class="btn-small border-1-grey bg-white-fff text-cl-grey height38">
                    <div class="right padding-l-5">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve" class="width15 path-grey">
                        <g id="Setting">
                            <g>
                                <g>
                                    <g>
                                        <g>
                                            <circle cx="20.001" cy="20" r="2.198"/>
                                            <path style="fill:none;stroke:#000000;stroke-miterlimit:10;" d="M30.554,18.121l-2.65-0.497
                                                c-0.161-0.536-0.372-1.05-0.634-1.534l1.533-2.242c0.142-0.207,0.112-0.52-0.066-0.697l-1.891-1.891
                                                c-0.178-0.178-0.49-0.207-0.696-0.065l-2.243,1.534c-0.498-0.268-1.028-0.484-1.581-0.646l-0.494-2.637
                                                c-0.046-0.247-0.287-0.447-0.539-0.447H18.62c-0.252,0-0.492,0.2-0.539,0.447l-0.5,2.662c-0.53,0.162-1.038,0.373-1.517,0.633
                                                l-2.214-1.515c-0.207-0.142-0.52-0.113-0.697,0.065l-1.891,1.891c-0.178,0.178-0.207,0.49-0.065,0.697l1.524,2.228
                                                c-0.257,0.479-0.465,0.987-0.624,1.516l-2.65,0.496C9.2,18.168,9,18.408,9,18.66v2.674c0,0.252,0.2,0.492,0.447,0.54
                                                l2.648,0.496c0.162,0.541,0.375,1.059,0.638,1.547l-1.507,2.203c-0.142,0.208-0.113,0.52,0.065,0.697l1.891,1.891
                                                c0.178,0.178,0.49,0.207,0.697,0.066l2.202-1.508c0.474,0.256,0.977,0.465,1.501,0.626l0.499,2.663
                                                c0.047,0.247,0.288,0.446,0.539,0.446h2.674c0.252,0,0.492-0.199,0.539-0.446l0.495-2.637c0.546-0.161,1.071-0.374,1.564-0.638
                                                l2.23,1.526c0.208,0.142,0.52,0.113,0.697-0.066l1.89-1.89c0.179-0.178,0.207-0.489,0.066-0.697l-1.518-2.217
                                                c0.268-0.493,0.483-1.018,0.648-1.565l2.648-0.496C30.8,21.827,31,21.586,31,21.335v-2.674
                                                C31.002,18.408,30.801,18.168,30.554,18.121z M20.001,24.589c-2.534,0-4.588-2.055-4.588-4.59c0-2.533,2.055-4.588,4.588-4.588
                                                c2.535,0,4.59,2.055,4.59,4.588C24.591,22.535,22.535,24.589,20.001,24.589z"/>
                                        </g>
                                    </g>
                                </g>
                                <g id="XMLID_3_">
                                    <path style="fill-rule:evenodd;clip-rule:evenodd;" d="M20,0c11.046,0,20,8.954,20,20s-8.954,20-20,20S0,31.046,0,20
                                        S8.954,0,20,0z M37.002,20c0-9.39-7.612-17.002-17.002-17.002C10.611,2.998,2.998,10.61,2.998,20
                                        c0,9.389,7.613,17.002,17.002,17.002C29.39,37.002,37.002,29.389,37.002,20z"/>
                                </g>
                            </g>
                        </g>
                        <g id="Layer_1">
                        </g>
                        </svg>
                    </div>
                    Partner Settings
                </button>
                </a>
            </li> -->
            <?php } ?>
        </ul>

        <?php if($status_set_setting == 1){ ?>
        <div class="pure-menu-item padding-tb-9 m-t-18 font-semi-bold no-hover right">
            <a href="<?php echo site_url($role_link.'/manage_partner/setting/'.$partner_id);?>">
            <button class="btn-small border-1-grey bg-white-fff text-cl-grey height38">
                <div class="right padding-l-5">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve" class="width15 path-grey">
                    <g id="Setting">
                        <g>
                            <g>
                                <g>
                                    <g>
                                        <circle cx="20.001" cy="20" r="2.198"/>
                                        <path style="fill:none;stroke:#000000;stroke-miterlimit:10;" d="M30.554,18.121l-2.65-0.497
                                            c-0.161-0.536-0.372-1.05-0.634-1.534l1.533-2.242c0.142-0.207,0.112-0.52-0.066-0.697l-1.891-1.891
                                            c-0.178-0.178-0.49-0.207-0.696-0.065l-2.243,1.534c-0.498-0.268-1.028-0.484-1.581-0.646l-0.494-2.637
                                            c-0.046-0.247-0.287-0.447-0.539-0.447H18.62c-0.252,0-0.492,0.2-0.539,0.447l-0.5,2.662c-0.53,0.162-1.038,0.373-1.517,0.633
                                            l-2.214-1.515c-0.207-0.142-0.52-0.113-0.697,0.065l-1.891,1.891c-0.178,0.178-0.207,0.49-0.065,0.697l1.524,2.228
                                            c-0.257,0.479-0.465,0.987-0.624,1.516l-2.65,0.496C9.2,18.168,9,18.408,9,18.66v2.674c0,0.252,0.2,0.492,0.447,0.54
                                            l2.648,0.496c0.162,0.541,0.375,1.059,0.638,1.547l-1.507,2.203c-0.142,0.208-0.113,0.52,0.065,0.697l1.891,1.891
                                            c0.178,0.178,0.49,0.207,0.697,0.066l2.202-1.508c0.474,0.256,0.977,0.465,1.501,0.626l0.499,2.663
                                            c0.047,0.247,0.288,0.446,0.539,0.446h2.674c0.252,0,0.492-0.199,0.539-0.446l0.495-2.637c0.546-0.161,1.071-0.374,1.564-0.638
                                            l2.23,1.526c0.208,0.142,0.52,0.113,0.697-0.066l1.89-1.89c0.179-0.178,0.207-0.489,0.066-0.697l-1.518-2.217
                                            c0.268-0.493,0.483-1.018,0.648-1.565l2.648-0.496C30.8,21.827,31,21.586,31,21.335v-2.674
                                            C31.002,18.408,30.801,18.168,30.554,18.121z M20.001,24.589c-2.534,0-4.588-2.055-4.588-4.59c0-2.533,2.055-4.588,4.588-4.588
                                            c2.535,0,4.59,2.055,4.59,4.588C24.591,22.535,22.535,24.589,20.001,24.589z"/>
                                    </g>
                                </g>
                            </g>
                            <g id="XMLID_3_">
                                <path style="fill-rule:evenodd;clip-rule:evenodd;" d="M20,0c11.046,0,20,8.954,20,20s-8.954,20-20,20S0,31.046,0,20
                                    S8.954,0,20,0z M37.002,20c0-9.39-7.612-17.002-17.002-17.002C10.611,2.998,2.998,10.61,2.998,20
                                    c0,9.389,7.613,17.002,17.002,17.002C29.39,37.002,37.002,29.389,37.002,20z"/>
                            </g>
                        </g>
                    </g>
                    <g id="Layer_1">
                    </g>
                    </svg>
                </div>
                Partner Settings
            </button>
            </a>
        </div>
        <?php } ?>
    </div>

</div>

<div class="box">
    <div class="heading pure-g border-none">

        <div class="edit-icon grids pure-u-1 pure-u-md-1-3 list">
            
        </div>
    </div>
    <div class="content border-none list-frm">
        <div class="content-title clear-both">
            <span class="left">Basic Info Setting</span>
            <?php echo form_open($role_link.'/manage_partner/update_partner/'.$partner_id, 'role="form" id="form_info" data-parsley-validate' );?>

            <div class="edit action-icon box">
                <button id="btn_save_info" name="__submit" type="submit" class="pure-button btn-tiny btn-white-tertinary m-b-15 save_click asd">SAVE</button>
                <i class="icon icon-close close_click" title="Cancel"></i>
                <i class="icon icon-edit edit_click" title="Edit"></i>
            </div>
        </div>
        <div class="pure-g padding-t-10">            
            <div class="pure-u-19-24 profile-detail prelative padding-tl-20">
                <table class="table-no-border2"> 
                    <tbody>
                        <tr>
                            <td class="pad15">Name</td>
                            <td>
                                <span class="r-only"><?php echo @$partner->name; ?></span>
                                <input name="name" type="text" value="<?php echo  @$partner->name; ?>" id="td_value_1_0" class="e-only" required data-parsley-required-message="Please input partner’s Name">
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Address</td>
                            <td>
                                <span class="r-only"><?php echo @$partner->address; ?></span>
                                <input name="address" type="text" value="<?php echo  @$partner->address; ?>" class="e-only" required data-parsley-required-message="Please input partner’s Address">
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">City</td>
                            <td>
                                <span class="r-only"><?php echo @$partner->city; ?></span>
                                <input name="city" type="text" value="<?php echo  @$partner->city; ?>" id="td_value_1_2" class="e-only" required data-parsley-required-message="Please input partner’s City">
                            </td>
                        </tr>

                        <tr>
                            <td class="pad15">State</td>
                            <td>
                                <span class="r-only"><?php echo @$partner->state; ?></span>
                                <input name="state" type="text" value="<?php echo  @$partner->state; ?>" id="td_value_1_3" class="e-only" required data-parsley-required-message="Please input partner’s State">
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">ZIP</td>
                            <td>
                                <span class="r-only"><?php echo @$partner->zip; ?></span>
                                <input name="zip" type="text" value="<?php echo  @$partner->zip; ?>" id="td_value_1_4" class="e-only" data-parsley-type="digits" required data-parsley-required-message="Please input partner’s ZIP code" data-parsley-type-message="Please input numbers only">
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Country</td>
                            <td>
                                <span class="r-only"><?php echo @$partner->country; ?></span>
                                <?php
                                    $country = array_column($option_country, 'name', 'name');
                                    $newoptions = array('' => '') + $country;
                                    echo form_dropdown('country', $newoptions, @$partner->country, ' id="td_value_2_3" class="e-only" required data-parsley-required-message="Please select partner’s Country"'); 
                                ?>
                            </td>
                        </tr>
                    </tbody>    
                </table>
            </div>
            <?php echo form_close();?>
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
                });

                $('#btn_save_info').click(function () {
                    $('#form_info').submit();
                });

                $('#profile_picture').change(function () {
                    $('#btn_upload').attr('disabled', false);
                });



                $('.e-only').bind('keypress', function (e) {
                    var code = e.keyCode || e.which;
                    if (code === 13) {
                        $('#form_info').submit();
                    }
                });

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
    })

</script>