<?php if($this->auth_manager->role() == 'RAD') {
    $role_link = "superadmin";
    $id = $id;
} else {
    $role_link = "admin";
    $id = @$data->id;
}

?>

<div class="heading text-cl-primary padding0">

    <div class="breadcrumb-tabs pure-g">
        <div class="left-breadcrumb">
            <ul class="breadcrumb toolbar padding-l-20">
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
                <?php if($role_link == 'superadmin') { ?>
                <li><a href="<?php echo site_url('superadmin/region/index');?>">Regions</a></li>
                <li><a href="#"><?php echo @$region[0]->region_id?></a></li>
                <?php } else { ?>
                <li><a href="<?php echo site_url('admin/manage_partner');?>">Partner</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <h1 class="margin0 padding-l-30 left">Add Partner &nbsp;</h1>

    <div class="btn-goBack padding-l-210 padding-t-5">
         <?php if($role_link == 'superadmin'){ ?>
        <a href="<?php echo site_url($role_link.'/region/detail/'.$this->uri->segment(4)) ?>">
            <?php } else if($role_link == 'admin'){ ?>
        <a href="<?php echo site_url($role_link.'/manage_partner') ?>">
            <?php } ?>
        <button class="btn-small border-1-blue bg-white-fff">
            <div class="left padding-r-5">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve" class="width15">
                <g id="back-one-page">
                    <g>
                        <g id="XMLID_13_">
                            <path style="fill-rule:evenodd;clip-rule:evenodd;" d="M20,0c11.046,0,20,8.954,20,20s-8.954,20-20,20S0,31.046,0,20
                                S8.954,0,20,0z M37.002,20c0-9.39-7.612-17.002-17.002-17.002C10.611,2.998,2.998,10.61,2.998,20
                                c0,9.389,7.613,17.002,17.002,17.002C29.39,37.002,37.002,29.389,37.002,20z"/>
                        </g>
                        <g>
                            <g>
                                <path style="fill:#231F20;" d="M27.734,22.141H13.636c-1.182,0-2.141-0.958-2.141-2.141s0.959-2.141,2.141-2.141h14.098
                                    c1.182,0,2.141,0.958,2.141,2.141S28.916,22.141,27.734,22.141z"/>
                            </g>
                            <g>
                                <g>
                                    <path style="fill:#231F20;" d="M19.465,24.27l-2.611-2.822c-0.756-0.818-0.756-2.08,0-2.897l2.611-2.822
                                        c1.264-1.366,0.295-3.582-1.566-3.582h-0.353c-0.595,0-1.162,0.248-1.566,0.685l-5.288,5.719c-0.756,0.817-0.756,2.079,0,2.896
                                        l5.288,5.719c0.404,0.437,0.971,0.685,1.566,0.685h0.353C19.76,27.852,20.729,25.636,19.465,24.27z"/>
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
    </div>
</div>

<div class="box clear-both">

    <div class="content">
        <div class="pure-g">
        <?php echo form_open_multipart($role_link.'/manage_partner/' . @$form_action . '/' . @$id, 'role="form" class="pure-form pure-form-aligned" style="width:100%" data-parsley-validate'); ?>           
            <div class="pure-u-15-24 profile-detail prelative padding-t-10 padding-l-25">
                <table class="table-no-border2 add-form"> 
                    <tbody>
                        <tr>
                            <td class="pad15">Name</td>

                            <td class="add-form-noborder">
                                <?php echo form_input('name', set_value('name', @$data->name), 'class="width50perc bg-white-fff padding2 border-1-ccc padding3" style="border:none" required data-parsley-required-message="Please input partner’s Name"') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Address</td>

                            <td class="add-form-noborder">
                                <?php echo form_input('address', set_value('address', @$data->address),'class="width50perc bg-white-fff padding2 border-1-ccc padding3" style="border:none" required data-parsley-required-message="Please input partner’s Address"') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">City</td>

                            <td class="add-form-noborder">
                               <?php echo form_input('city', set_value('city', @$data->city), 'class="width50perc bg-white-fff padding2 border-1-ccc padding3" style="border:none" required data-parsley-required-message="Please input partner’s City"') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">State</td>

                            <td class="add-form-noborder">
                                <?php echo form_input('state', set_value('state', @$data->state), 'class="width50perc bg-white-fff padding2 border-1-ccc padding3" style="border:none" required data-parsley-required-message="Please input partner’s State"') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Zip</td>

                            <td class="add-form-noborder">
                                <input class="width50perc bg-white-fff padding2 border-1-ccc padding3" type="text" name="zip" value="<?php echo @$data->zip ;?>" data-parsley-type="digits" data-parsley-trigger="keyup" required data-parsley-required-message="Please input partner’s ZIP code" data-parsley-type-message="Please input numbers only">
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Country</td>

                            <td class="add-form-noborder">
                                <?php
                                    $country = array_column($option_country, 'name', 'name');
                                    $newoptions = array('' => '') + $country;
                                    echo form_dropdown('country', $newoptions, @$data[0]->country, 'class="width50perc bg-white-fff padding2 border-1-ccc padding3" required data-parsley-required-message="Please select partner’s Country"'); 
                                ?>
                            </td>

                        </tr>
                    </tbody>    
                </table>

                <div class="save-cancel-btn text-left padding-t-40">
                    <button type="submit" name="__submit" value="SUBMIT" class="pure-button btn-blue btn-small">Save</button>
                    <?php if($role_link == 'superadmin'){ ?>
                    <a href="<?php echo site_url($role_link.'/region/detail/'.$this->uri->segment(4)) ?>"><button class="pure-button btn-red btn-small" type="button">Cancel</button></a>
                    <?php } else if($role_link == 'admin'){ ?>
                    <a href="<?php echo site_url($role_link.'/manage_partner') ?>"><button class="pure-button btn-red btn-small" type="button">Cancel</button></a>
                    <?php } ?>
                </div>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>   		

<script>

    $(function(){

        $('input').css({'border':'none'});
        
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