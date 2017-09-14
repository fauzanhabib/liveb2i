<div class="heading text-cl-primary padding-l-20">

    <div class="breadcrumb-tabs pure-g">
        <div class="left-breadcrumb">
            <ul class="breadcrumb toolbar padding-l-0">
                <li id="breadcrum-home"><a href="<?php echo base_url();?>index.php/account/identity/detail/profile">
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
				<li><a href="<?php echo site_url('partner/subgroup') ?>">Subgroup</a></li>
                <li><a href="#">Add Subgroup</a></li>
            </ul>
        </div>
    </div>
    <h1 class="margin0"><?php echo((@$form_action == 'create_subgroup') ? 'Add' : 'Add'); ?> Subgroup</h1>
</div>

<div class="box">
    <div class="heading pure-g"></div>

    <div class="content">
        <div class="box pure-g">
            <?php echo form_open_multipart('partner/subgroup/create_subgroup', 'role="form" class="pure-form pure-form-aligned" style="width:100%" data-parsley-validate'); ?>
   
            <div class="pure-u-18-24 profile-detail prelative">

                <table class="table-no-border2"> 
                    <tbody>
                       <div class="pure-control-group">
                        <div class="label">
                            <label for="name">Name</label>
                        </div>
                        <div class="input">
                            <input type="name" name="name" data-parsley-trigger="change" value="<?php echo @$data->name;?>" id="name" class="pure-input-1-2" required data-parsley-required-message="Please input region" data-parsley-type-message="Please input valid e-mail address">
                        </div>
                    </div>
						
                    </tbody>    
                </table>
            </div>
            <div class="pure-u-1" style="border-top:1px solid #f3f3f3;padding: 15px 0px;margin-top:15px;">
                <div class="label">
                    <?php echo form_submit('__submit', 'SUBMIT', 'class="pure-button btn-small btn-blue"'); ?>
                    <a href="<?php echo site_url('partner/subgroup') ?>" class="pure-button btn-small btn-red text-cl-white">CANCEL</a>
                </div>
            </div>
            <?php echo form_close(); ?> 
        </div>
    </div>
</div>			

<script>

    $(function(){

        // $('input').css({'border':'none'});
        
        parsley_float();

        $('tr').each(function(e){
            var inputs = $(this);

            $('input',inputs).on('blur', function () {
                $('td',inputs).removeClass('inline').addClass('no-inline');
            }).on('focus', function () {
                $('td',inputs).removeClass('no-inline').addClass('inline');
            });

            $('td',inputs).css({'position':'relative'});

        })

        $('.upload-btn').click(function(){
            $('.img-upload').click();
        });

        $('#profile_picture').change(function(){

            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = $('.preview-image');
            var reader = new FileReader();

            if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
                reader.onload = function (e) {
                    $(image_holder).attr('src',e.target.result); 
                }
                reader.readAsDataURL($(this)[0].files[0]);
                $(".dropdown-form-photo").hide();
            }
            else {
                alert("Please upload images only");
            }
        });

         $('.caption').click(function () {
            $(".dropdown-form-photo").show();
        });

        $('.cancel-upload').click(function () {
            $(".dropdown-form-photo").hide();
        });
    })
</script>