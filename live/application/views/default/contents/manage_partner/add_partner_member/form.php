<?php if($this->auth_manager->role() == 'RAD') {
    $role_link = "superadmin";
} else {
    $role_link = "admin";
}

?>

<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Add Affiliate</h1>
</div>

<div class="box">
    <div class="heading pure-g"></div>

    <div class="content">
        <div class="box">
            <?php 
            $set_role_id = '';
            if(($this->uri->segment(5) == '') || ($this->uri->segment(5) != 'student')){
                $set_role_id = 'coach';
            } else if($this->uri->segment(5) == 'student'){
                $set_role_id = 'student';
            }
            echo form_open($role_link.'/manage_partner/'.$form_action.'/'.$this->uri->segment(4).'/'.$set_role_id, 'role="form" class="pure-form pure-form-aligned" data-parsley-validate');?>
                <fieldset>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="name">Affiliate's Name</label>
                        </div>
                        <div class="input">
                            <?php echo form_dropdown('partner_id', @$partner, @$selected[0]->id, 'id = "partner_id" class="pure-input-1-2" required'); ?>
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="role">Role</label>
                        </div>	

                        <div class="input">
                          <?php 
                                $uri_type = $this->uri->segment(5);
                                if($uri_type == 'coach'){
                                    echo form_dropdown('role_id', Array('3'=>'Coach Partner'), '', 'id = "partner_id" class="pure-input-1-2" required');
                                } else if($uri_type == 'student'){
                                    echo form_dropdown('role_id', Array('5'=>'Student Partner'), '', 'id = "partner_id" class="pure-input-1-2" required');
                                } else {
                                    echo form_dropdown('role_id', Array('3'=>'Coach Partner', '5'=>'Student Partner'), '', 'id = "partner_id" class="pure-input-1-2" required');
                                }
                            ?>
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="email">Email</label>
                        </div>
                        <div class="input">
                            <input type="email" name="email" data-parsley-trigger="change" value="<?php echo @$data->email;?>" id="email" class="pure-input-1-2" required data-parsley-required-message="Please input affiliate`s e-mail" data-parsley-type-message="Please input valid e-mail address">
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="fulname">Full name</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('fullname', set_value('fullname', @$data->fullname), 'id="fullname" class="pure-input-1-2" required data-parsley-required-message="Please input affiliate`s name"') ?>
                        </div>
                    </div>
                    <!-- <div class="pure-control-group">
                        <div class="label">
                            <label for="gender">Gender</label>
                        </div>
                        <div class="input">
                            <?php echo form_dropdown('gender', $this->auth_manager->gender(), trim(@$data->gender), 'id="sex" class="pure-input-1-2" required data-parsley-required-message="Please select affiliate`s gender"') ?>
                        </div>
                    </div>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="date_of_birth">Birthdate</label>
                        </div>
                        <div class="input">
                            <div class="frm-date">
                            <?php echo form_input('date_of_birth', set_value('date_of_birth', @$data->date_of_birth), 'id="date_of_birth" class="pure-input-1-2 datepicker" readonly required') ?>
                            <span class='icon icon-date' style="left: 180px;"></span>
                            </div>
                        </div>
                    </div> -->
                    
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="country">Country</label>
                        </div>
                        <div class="input">
                            <?php
                                    $country = array_column($option_country, 'name', 'name');
                                    $newoptions = $country;
                                    echo form_dropdown('country', $newoptions, $partner_country, ' id="country" class="width50perc bg-white-fff padding2 border-1-ccc padding3" required data-parsley-required-message="Please select Country"'); 
                                ?>
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="phone">Phone Number</label>
                        </div>
                        <div class="input">
                            <div class="flex">
                                <input type="text" name="dial_code" data-parsley-trigger="change" value="<?php echo $dial_code; ?>" id="dial_code" class="pure-input-1-8" style="margin-right:1px;" readonly> 
                                <?php echo form_input('phone', set_value('phone', @$data->phone), 'id="phone" class="pure-input-1-2" data-parsley-type="digits" required data-parsley-required-message="Please input affiliate`s phone number" data-parsley-type-message="Please input numbers only"') ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="skype_id">Skype ID</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('skype_id', set_value('skype_id', @$data->fullname), 'id="skype_id" class="pure-input-1-2" required data-parsley-required-message="Please input affiliate`s skype id"') ?>
                        </div>
                    </div>

                    <?php if($this->uri->segment(5) == 'student'){ ?>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="skype_id">Token</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('token_amount', set_value('token', ''), 'id="token" class="pure-input-1-2" required data-parsley-type="digits" data-parsley-required-message="Please input token"') ?>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                        <div class="label">
                            <?php echo form_submit('__submit', 'SUBMIT', 'class="pure-button btn-small btn-blue"'); ?>
                            <div class="btn-goBack pure-button btn-red btn-small"><a href="<?php echo site_url($role_link.'/manage_partner/partner/coach/'.$this->uri->segment(4).'/'.@$region_id);?>">CANCEL</a></div>
                        </div>
                    </div>
                </fieldset>
            <?php echo form_close();?>        
        </div>
    </div>
</div>		



<script type="text/javascript">
    $(function () {
         $('.datepicker').datepicker({
            // setDate: '1990-01-01',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
        $(".datepicker").datepicker("setDate", '1990-01-01');
    });
</script>
<script>
    var dial_code = $('#dial_code').val();
    var country = $('#country').val();

    $( "#country" ).change(function() {
      var dial_code = $('#dial_code').val();
      var country = $(this).val();
        if((dial_code != '') && (country != '')){
            $('#dial_code').addClass('loadinggif');
            $.ajax({
                url: "<?php echo site_url($role_link.'/manage_partner/dial_code');?>",
                type: "POST",
                data: { dial_code : dial_code, country : country },
                dataType: "html",
                success: function (response) {
                   console.log(response); 
                  $('#dial_code').removeClass('loadinggif'); 
                   var dial_code = response;

                   $('input[name=dial_code]').val(dial_code);          

                },
            });
        }

      });
    
</script>