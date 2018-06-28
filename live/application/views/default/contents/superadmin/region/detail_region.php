
<div class="heading text-cl-primary padding15">

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
                <li><a href="<?php echo site_url('superadmin/region/index');?>">Regions</a></li>
                <li><a href="#"><?php echo $data_admin[0]->region_id;?></a></li>
                <li>
                    <form action="<?php echo site_url('superadmin/region/detail/'.$id_regional);?>" autocomplete="on" class="search-box" method="post">
                        <div id="src__sign">Search..</div>
                      <input id="search" name="search_region" type="text" placeholder="Type here.."><input id="search_submit" value="Rechercher" type="submit">
                    </form>
                </li>

            </ul>
        </div>
    </div>

    <h1 class="margin0">Region of <?php echo $data_admin[0]->region_id;?></h1>

    <div class="btn-setting right">

    

        <a href="<?php echo site_url('superadmin/region/add_token/'.$data_admin[0]->id);?>">
            <button class="btn-small border-1-grey bg-white-fff">
                Add Token
            </button>
        </a>

        <a href="<?php echo site_url('superadmin/region/refund_token/'.$data_admin[0]->id);?>">
            <button class="btn-small border-1-grey bg-white-fff">
                Refund Token
            </button>
        </a>
        <?php if($status_set_setting == 1){ ?>
        <a href="<?php echo site_url('superadmin/region/setting/'.$data_admin[0]->id);?>">
            <button class="btn-small border-1-grey bg-white-fff">
                <span>Region Settings</span>
            </button>
        </a>
    <?php } ?>
    </div>
    
</div>

<div class="box">

    <div class="content">

        <div class="content-title clear-both">
            <span class="">Basic Info</span>
            <form action="<?php echo site_url('superadmin/region/detail/'.$data_admin[0]->id);?>" method="POST">
            <div class="edit action-icon">
                <button id="btn_save_info" name="_submit" value="SAVE" type="submit" class="pure-button btn-tiny btn-white-tertinary m-b-15 save_click asd">SAVE</button>
                <i class="icon icon-close close_click" title="Cancel"></i>
                <i class="icon icon-edit edit_click" title="Edit"></i>
            </div>
        </div>

        <div class="pure-g padding-b-20">           
            <div class="profile-detail prelative padding-t-20 width100perc">
                <table class="table-no-border2"> 
                    <tbody>
                        <tr>
                            <td class="pad15">Region Name</td>
                            <td>
                                <span class="r-only"><?php echo $data_admin[0]->region_id;?></span>
                                <input name="region" type="text" id="td_value_4_0" value="<?php echo @$data_admin[0]->region_id; ?>" class="e-only" required data-parsley-required-message="Admin name can’t be blank">
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Admin Name</td>
                            <td  class="r-only">
                                <span><?php echo @$data_admin[0]->fullname; ?></span>
                            </td>
                            <td class="e-only" style="cursor:not-allowed;background: #ebebeb;color: #939393;padding-left: 5px;">
                                <span><?php echo @$data_admin[0]->fullname; ?></span>
                            </td>
                        </tr>
                         <tr>
                            <td class="pad15">Email</td>
                            <td>
                                <span class="r-only"><?php echo @$data_admin[0]->email; ?></span>
                                <span>
                                    <input name="email" type="text" id="td_value_4_0" value="<?php echo @$data_admin[0]->email; ?>" class="e-only" required data-parsley-required-message="Email name can’t be blank">
                                </span>
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="pad15">Tokens</td>
                            <td  class="r-only">
                                <span><?php echo @$token->token_amount; ?></span>
                            </td>
                            <td class="e-only" style="cursor:not-allowed;background: #ebebeb;color: #939393;padding-left: 5px;">
                                <span><?php echo @$token->token_amount; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Individual Settings</td>
                            <td>
                                <span class="">
                                    <select name="changet_status" id="changet_status">
                                        <?php 
                                            if($status_set_setting == 1){ ?>
                                              <option value="1">Allow</option>  
                                              <option value="0">Disallow</option>  
                                        <?php  } elseif($status_set_setting == 0){ ?>
                                              <option value="0">Disallow</option>
                                              <option value="1">Allow</option>  
                                        <?php  }
                                        ?>
                                    </select>
                                    
                                </span>
                            </td>
                        </tr>
                    </tbody>    
                </table>
            </div>
        </form>
        </div>
    </form>

<!--         <div class="left-list-tabs pure-menu pure-menu-horizontal">
            <ul class="pure-menu-list">
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="#" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 active-tabs-blue">Active Regions</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="#" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">Inactive Regions</a></li>
            </ul>
        </div> -->
        <form action="<?php echo site_url('superadmin/region/delete_partner/'.$this->uri->segment(4));?>" method="POST">
            <div class="box bg-white">

                <div class="delete-add-btn right">
                <?php if($this->auth_manager->role() == 'RAD') {
                    $role_link = "superadmin";
                } else {
                    $role_link = "admin";
                }

                ?>


                    <div class="btn-noborder btn-normal bg-white left"><a href="<?php echo site_url($role_link.'/manage_partner/add_partner/'.$data_admin[0]->id);?>"><img src="<?php echo base_url();?>assets/img/iconmonstr-plus-6-16.png" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-tertiary">Add Affiliate</em></a></div>
                    <button type="submit" name="_submit" value="delete" class="btn-noborder btn-normal bg-white" onclick="return confirm('Are you sure you want to delete?')"><a><img src="<?php echo base_url();?>assets/img/iconmonstr-x-mark-7-16.png" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-red">Delete Affiliate</em></a></button>
                    <?php if($this->auth_manager->role() == 'RAD'){ ?>
                    <button type="submit" name="_submit" value="move" class="btn-noborder btn-normal bg-white" onclick="return confirm('Are you sure you want to move?')"><a><img src="<?php echo base_url();?>assets/img/iconmonstr-arrow-62-24.png" width="16" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-green">Move Affiliate</em></a></button>
                    <?php } ?>
                </div>

                <div class="padding-t-20">
                    <div class="checkbox-selectAll padding-b-20 padding-t-5">
                        <input type="checkbox" id="checkbox-1-1" name="check_list[]" value="Region-1" class="regular-checkbox checkAll" /><label for="checkbox-1-1"></label><em>&nbsp;&nbsp;Select All</em>
                    </div>
                    <div class="box-lists pure-g">
                        <?php
                        $no = 2;
                         foreach ($partner as $p) {
                            
                        ?>
                        <div class="box-info-list-checkbox grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 padding-b-20">
                            <input type="checkbox" id="checkbox-1-<?php echo $no;?>" name="check_list[]" value="<?php echo $p->id;?>" class="regular-checkbox" /><label for="checkbox-1-<?php echo $no;?>"></label>
                            <div class="box-info-list">
                                <div class="list-profile-pic left">
                                    <a href="<?php echo site_url('superadmin/manage_partner/detail/'.$p->id);?>" class="list-profile-pic-pic">
                                        <img src="<?php echo base_url()."/".$p->profile_picture;?>" class="img-circle">
                                    </a>
                                </div>
                                
                                <div class="list-name-details margin-left70 width206 text-center">
                                    <a href="<?php echo site_url('superadmin/manage_partner/detail/'.$p->id);?>" class="font-semi-bold font-18" style="color:#939393">
                                    <br>    
                                        <span class="font-semi-bold font-18">
                                                <?php echo $p->name;?>
                                            
                                        </span>
                                       
                                        <h5 class="margin0"><?php echo @$this->common_function->get_partner_type($p->id); ?></h5>
                                    </a>
                                </div>
                                
                            </div>
                        </div>

                        <?php $no++; } ?>
                    </div>
                </div>
            </div>
        </form>
    </div>  
</div>

<?php echo @$pagination;?>

        <script type="text/javascript" src="<?php echo base_url();?>assets/js/main.js"></script>
        <script type="text/javascript">
            $(".checkAll").change(function () {
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });
        </script>

        <script type="text/javascript" src="<?php echo base_url();?>assets/js/main.js"></script>

        <script>
        $('#changet_status').change(function(){
            var selectedValue = this.value;
            var idValue = '<?php echo $this->uri->segment(4);?>';
            var url = '<?php echo site_url("superadmin/region/change_status");?>';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {option : selectedValue, id: idValue},
                    success: function(data) {
                        location.reload();
                    }
                });

        });
        </script>

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
         placeholder: "<?php echo ($this->auth_manager->role() == 'STD') ? 'Preferred Languages':'Region';?>"
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
        endDate: "now"
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