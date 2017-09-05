<div class="heading text-cl-primary padding-b-0">
    <h1 class="margin0"><?php echo($data[0]->fullname);?></h1>
</div>

<div class="box">
    <div class="heading">
        
    </div>
    <div class="content">
        <div class="pure-g">
            <div class="pure-u-8-24 profile-image text-center divider-right">
                <div class="thumb-small">
                    <?php echo form_open_multipart(site_url('admin/manage_region/update_region_admin/'), 'role="form"'); ?>
                        <img src="<?php echo base_url($data[0]->profile_picture);?>" width="150" height="200" class="pure-img fit-cover" />
						<!--
						<div class="caption">
                            EDIT
                        </div>
                        -->
                        <div class="dropdown-form-photo text-center">
                            <input id = "profile_picture" type= "file" accept="image/*" name="profile_picture" style="width:144px;margin-bottom:5px;" class=" button-small btn-book btn-save pure-button">
                            <?php echo form_submit('__submit', 'Save', "id='btn_upload' class='pure-button btn-small btn-tertiary' disabled"); ?>
                            <button type="reset" class="pure-button btn-small btn-tertinary cancel-upload">Cancel</button>
                        </div>
                        <?php echo form_close(); ?>
                </div>
                <?php
                    if(@$students && @$coaches){
                        $type = "Student and Coach Supplier";
                    }elseif(@$students){
                        $type = "Student Supplier";
                    }elseif(@$coaches){
                        $type = "Coach Supplier";
                    }
                ?>
                <div class="tag">
                    <span class="text-cl-secondary font-14">Regional Admin</span><br>
                    <span class="font-12"><?php echo($data[0]->region_name);?></span>
                </div>
                <!-- <div class="padding-t-10 text-center view-admin">
                    <div class="b-f3-1 padding10" style="width:85%;margin:0 auto">
                        <a href=""><button class="pure-button btn-small btn-white m-t-10">View Admin</button></a>
                    </div>
                </div> -->
            </div>
            
            <div class="pure-u-16-24 profile-detail prelative">
                <?php echo form_open(site_url('admin/manage_region/update_region_admin/'.$data[0]->id), 'role="form" id="form_info" data-parsley-validate' );?>
                <div class="heading m-b-15">
                    <div class="pure-u-12-24">
                        <h3 class="h3 font-normal padding-tb-15 text-cl-secondary">BASIC INFO</h3>
                    </div>
                    <div class="pure-u-12-24" style="width:48%">
                        <div class="edit action-icon" style="margin: 16px 0 0 0;">
                            <button id="btn_save_info" name="__submit" type="submit" value="__submit" class="pure-button btn-tiny btn-white-tertinary m-b-15 save_click asd">SAVE</button>
                            <i class="icon icon-close close_click" title="Cancel"></i>
                            <i class="icon icon-edit edit_click" title="Edit"></i>
                        </div>
                    </div>
                </div>
                

                <table class="table-no-border2"> 
                    <tbody>
                        <tr>
                            <td class="pad15">Email</td>
                            <td>
                                <?php echo($data[0]->email);?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Name</td>
                            <td>
                                <span class="r-only"><?php echo($data[0]->fullname);?></span>
                                <input name="fullname" type="text" value="<?php echo($data[0]->fullname);?>" id="td_value_1_0" class="e-only" required data-parsley-required-message="Please input partner’s Name">
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Region</td>
                            <td>
                                <span class="r-only"><?php echo($data[0]->region_name);?></span>
                                    <?php echo form_dropdown('region_id', @$region, @$data[0]->region_id, 'id = "region_id" class="pure-input-1-2 e-only" required'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Gender</td>
                            <td>
                                <span class="r-only"><?php echo(@$data[0]->gender);?></span>
                                    <?php echo form_dropdown('gender', $this->auth_manager->gender(), trim(@$data[0]->gender), 'id="gender" class="pure-input-1-2 e-only" required') ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="pad15">Skype ID</td>
                            <td>
                                <span class="r-only"><?php echo($data[0]->skype_id);?></span>
                                <input name="skype_id" type="text" value="<?php echo($data[0]->skype_id);?>" id="td_value_1_4" class="e-only" required data-parsley-required-message="Please input Skype Id">
                            </td>
                        </tr>

                        <tr>
                            <td class="pad15">Phone Number</td>
                            <td>
                                <span class="r-only"><?php echo($data[0]->phone);?></span>
                                <input name="phone" type="text" value="<?php echo($data[0]->phone);?>" id="td_value_1_5" class="e-only" required data-parsley-required-message="Please input partner’s State">
                            </td>
                        </tr>
                        
                    </tbody>    
                </table>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<div class="box">
    <div class="heading">
        <div class="pure-u-12-24">
            <h3 class="h3 font-normal padding15 text-cl-secondary">PARTNER</h3>
        </div>
        <div class="pure-u-12-24 text-right" style="margin: 15px -10px;">
            <a href="<?php echo site_url('admin/manage_region/detail_region_partner/'.$region_id.'/1');?>" class="pure-button btn-small btn-white availability"> VIEW ALL</a>
        </div>
    </div>

    <div class="content">
        <div class="pure-g list-partner-member">
			<?php
			foreach(@$partner as $p){
			?>
                <div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">
                    <div class="box-info">
                        <div class="image">
                            <img src="<?php echo(base_url($p->profile_picture));?>" class="list-cover">
                        </div>
                        <div class="detail">
                            <span class="name">
<!--                                <a href="">-->
                                <?php echo($p->name);?>
<!--                                </a>-->
                            </span>

                            <table class="margint25">
                                <tbody>
                                    <tr>
                                        <td>Country</td>
                                        <td>:</td>
                                        <td><?php echo($p->country);?></td>
                                    </tr>
                                    <tr>
                                        <td>State</td>
                                        <td>:</td>
                                        <td><?php echo($p->state);?></td>
                                    </tr>
                                    <tr>
                                        <td>Type</td>
                                        <td>:</td>
                                        <td><?php echo @$this->common_function->get_partner_type($p->id); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
			<?php } ?>
        </div>    
    </div>
</div>


<!--<div class="box">
    <div class="heading">
        <div class="pure-u-12-24">
            <h3 class="h3 font-normal padding15 text-cl-secondary">COACHES SUPLIER</h3>
        </div>
        <div class="pure-u-12-24 text-right" style="margin: 15px -10px;">
            <a href="" class="pure-button btn-small btn-white availability">VIEW ALL</a>
        </div>
    </div>

    <div class="content">
        <div class="pure-g list-partner-member">
			<?php
			for ($i=0; $i < 3; $i++) { 
			?>
                <div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">
                    <div class="box-info">
                        <div class="image">
                            <img src="http://idbuild.id.dyned.com/live/uploads/images/image_(1).png" class="list-cover">
                        </div>
                        <div class="detail">
                            <span class="name"><a href="">WEBI</a></span>

                            <table class="margint25">
                                <tbody>
                                    <tr>
                                        <td>Country</td>
                                        <td>:</td>
                                        <td>China</td>
                                    </tr>
                                    <tr>
                                        <td>State</td>
                                        <td>:</td>
                                        <td>China</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
			<?php } ?>
        </div>    
    </div>-->
</div>

<script type="text/javascript">
    
    $(function(){
        
        if($(document).width() <=500){
            $('.profile-image').css({'margin-bottom':'0px'});
        }

        $('.thumb-small').css({'border':'1px solid #f3f3f3'});
        $('td').css({'position':'relative'});

        parsley_float();

        $('.list-people .box-info').css({'min-height':'110px'});

        $('.caption').css({'margin-bottom':'0'});
        $('textarea').css({'border':'none','width':'100%','outline':'none'});

        $('.caption').click(function () {
            $(".dropdown-form-photo").show();
            $('.tag').hide();
            $('.view-admin').hide();
            $(".caption").hide();
        });

        $('.cancel-upload').click(function () {
            $(".dropdown-form-photo").hide();
            $('.tag').show();
            $('.view-admin').show();            
            $(".caption").show();
        });


        $('.e-only').hide();
        $('.close_click').hide();
        $('.save_click').hide();

        var arrText= new Array();
        var arrTextarea= new Array();
        var arrSelect= new Array();

        $('.box').each(function(){

            var _each = $(this);


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
                $("#td_value_1_0").focus();
                animationClick(_close, 'fadeIn');
                animationClick(_save, 'fadeIn');

                $('input[type=text]', _each).each(function(){
                    arrText.push($(this).val());
                });

                $('textarea', _each).each(function(){
                    arrTextarea.push($(this).val());
                });

                $('select', _each).each(function(){
                    arrSelect.push($(this).val());
                });

            });

            $('.close_click', _each).click(function () {

                $('#form_info').parsley().reset();

                $('.close_click', _each).hide();
                $('.save_click', _each).hide();
                $('.edit_click', _each).show();
                $('.r-only', _each).show();
                $('.e-only', _each).hide();
                _edit = $('.edit_click', _each);
                animationClick(_edit, 'fadeIn');
                var input = $('input[type=text]', _each);

                for(i = 0; i < input.length; i++) {
                  input[i].value = arrText[i];
                }

                var textarea = $('textarea', _each);

                for(i = 0; i < textarea.length; i++) {
                  textarea[i].value = arrTextarea[i];
                }

                var select = $('select', _each);

	            for(i = 0; i < select.length; i++) {
	              select[i].value = arrSelect[i];
	            }

                arrText = [];
                arrTextarea = [];
                arrSelect = [];
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
    });

</script>