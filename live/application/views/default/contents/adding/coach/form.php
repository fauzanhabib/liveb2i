<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Add a Coach</h1>
</div>

<div class="box">
    <div class="heading pure-g"></div>

    <div class="content">
        <div class="box">
            <?php
            echo form_open('partner/adding/'.$form_action.'/'.@$subgroup_id, 'role="form" class="pure-form pure-form-aligned" data-parsley-validate');
            ?>
                <fieldset>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="subgroup">Subgroup</label>
                        </div>
                          <div class="input">
                            <input type="text" name="subgroup" data-parsley-trigger="change" value="<?php echo $subgroup?>" id="subgroup" class="pure-input-1-2" style="color:#000" required data-parsley-required-message="Please input subgroup" data-parsley-type-message="Please input subgroup" disabled>
                          </div>
                    </div>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="full_name">Full Name</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('fullname', set_value('fullname', @$data->fullname), 'id="fullname" class="pure-input-1-2" required data-parsley-required-message="Please input coach name"') ?>
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="email">Email</label>
                        </div>
                        <div class="input">
                            <input type="email" name="email" data-parsley-trigger="change" value="<?php echo @$data->email?>" id="email" class="pure-input-1-2" required data-parsley-required-message="Please input coach e-mail address" data-parsley-type-message="Please input valid e-mail address">
                        </div>
                    </div>


                    <div class="pure-control-group">
                        <div class="label">
                            <label for="DynEd Pro ID">DynEd Pro ID (Email)</label>
                        </div>
                        <div class="input">
                            <input type="email" name="email_pro_id" data-parsley-trigger="change" value="<?php echo @$data->email_pro_id?>" id="email_pro_id" class="pure-input-1-2" required data-parsley-required-message="Please input coach e-mail Pro ID address" data-parsley-type-message="Please input valid e-mail Pro ID address">
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="server">Server</label>
                        </div>
                        <div class="input">

                        <?php
                        $newoptions = array('' => 'Select Server') + $server_code;
                        echo form_dropdown('server_dyned_pro', $newoptions, @$data[0]->country, ' id="server_dyned_pro" class="pure-u-1 m-t-10" required');
                        ?>

                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="ptsocre">PT Score</label>
                        </div>
                        <div class="input ptscore">
                            <input type="number" name="ptscore" data-parsley-trigger="change" id="ptscore" class="pure-input-1-2" readonly>
                        </div>
                    </div>


                     <div class="pure-control-group">
                        <div class="label">
                            <label for="ptsocre">Coach Type</label>
                        </div>
                        <div class="input coachtype">
                            <select name="coach_type_id">
                                <?php
                                foreach ($coach_type->result() as $ct) { ?>
                                    <option value="<?php echo $ct->id;?>"><?php echo $ct->type;?></option>
                                <?php }
                                ?>
                            </select>
                        </div>
                    </div>


                    <div class="pure-control-group">
                        <div class="label">
                            <label for="gender">Gender</label>
                        </div>
                        <div class="input">
                            <?php echo form_dropdown('gender', $this->auth_manager->gender(), trim(@$data->gender), 'id="sex" class="pure-input-1-2" required data-parsley-required-message="Please select coach gender"') ?>
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="date_of_birth">Birthdate</label>
                        </div>
                        <div class="input">
                            <div class="frm-date">
                            <?php echo form_input('date_of_birth', set_value('date_of_birth', @$data->date_of_birth), 'id="date_of_birth" class="pure-input-1-2 datepicker" readonly required data-parsley-required-message="Please pick coach birthdate"') ?>
                            <span class="icon icon-date" style="left: 185px;"></span>
                            </div>
                        </div>
                    </div>
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
                            <label for="dial_code"></label>
                            <label for="phone">Phone Number</label>
                        </div>
                        <div class="input">
                            <div class="flex">
                                <input type="text" name="dial_code" data-parsley-trigger="change" value="<?php echo @$dial_code; ?>" id="dial_code" class="pure-input-1-8" style="margin-right:1px;" readonly>
                                <?php echo form_input('phone', set_value('phone', @$data->phone), 'id="phone" class="pure-input-1-2" data-parsley-type="digits" required data-parsley-required-message="Please input coach phone number" data-parsley-type-message="Please input numbers only"') ?>
                            </div>
                        </div>
                    </div>
<!--                     <div class="pure-control-group">
                        <div class="label">
                            <label for="token_cost_for_student">Token Cost For Student</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('token_for_student', set_value('token_for_student', @$data->token_for_student), 'id="token_for_student" class="pure-input-1-2" data-parsley-type="digits" required data-parsley-required-message="Please input token cost for student" data-parsley-type-message="Please input numbers only"') ?>
                        </div>
                    </div> -->

                    <!-- <div class="pure-control-group">
                        <div class="label">
                            <label for="timezone">Timezone</label>
                        </div>
                        <div class="input">
                            <?php echo form_dropdown('user_timezone', @$timezones, @$data[0]->user_timezone, 'id="td_value_2_8" class="pure-input-1-2" style="width:100%" required required data-parsley-required-message="Please select your timezone"')?>

                        </div>
                    </div> -->

                    <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                        <div class="label">
                            <?php echo form_submit('__submit', @$data->id ? 'Update' : 'SUBMIT', 'class="pure-button btn-small btn-blue"'); ?>
                             <a onClick="goBack()" class="pure-button btn-red btn-small approve-user delete_match">CANCEL</a>
                        </div>
                    </div>
                </fieldset>
            <?php echo form_close();?>
        </div>
    </div>
</div>

<script>
    $(function (){
        var now = new Date();
        var day = ("0" + (now.getDate())).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);

        $('.datepicker').datepicker({
            endDate: resultDate,
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
        $(".datepicker").datepicker("setDate", '1990-01-01');
        if ($(document).width() <= 321) {
            $('.icon-date').css({"left": "170px"})
        }
    });
</script>

<script>
    $( "#server_dyned_pro" ).change(function() {
      var email = $('#email_pro_id').val();
      var server = $(this).val();
        if((server != '') && (email != '')){
           $('#ptscore').addClass('loadinggif');
            $.ajax({
                url: "<?php echo site_url('partner/adding/ptscore');?>",
                type: "POST",
                data: { email : email, server: server },
                dataType: "html",
                success: function (response) {
                   console.log(response);
                   if(response.indexOf("/**/") >= 0) {
                     var response = response.replace("/**/", "");
                     console.log(response);
                   }
                   $('input[name=ptscore]').val(response);
                   console.log(response+'  bawah');
                },
            });
        }

      });
</script>

<script>
    $( "#email_pro_id" ).change(function() {
      var server = $('#server_dyned_pro').val();
      var email = $(this).val();
        if((server != '') && (email != '')){
            $('#ptscore').addClass('loadinggif');
            $.ajax({
                url: "<?php echo site_url('partner/adding/ptscore');?>",
                type: "POST",
                data: { email : email, server: server },
                dataType: "html",
                success: function (response) {
                   console.log(response);
                   if(response.indexOf("/**/") >= 0) {
                     var response = response.replace("/**/", "");
                     console.log(response);
                   }
                   $('input[name=ptscore]').val(response);
                   console.log(response+'  bawah');
                },
            });
        }

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
                url: "<?php echo site_url('partner/subgroup/dial_code');?>",
                type: "POST",
                data: { dial_code : dial_code, country : country },
                dataType: "html",
                success: function (response) {
                   //console.log(response);
                  $('#dial_code').removeClass('loadinggif');
                   var dial_code = response;

                   $('input[name=dial_code]').val(dial_code);

                },
            });
        }

      });

</script>
