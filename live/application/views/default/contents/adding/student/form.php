<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Add a Student</h1>
</div>

<div class="box b-f3-2">

    <div class="content">
        <div class="box">
            <?php
            echo form_open('student_partner/adding/'.$form_action.'/'.@$subgroup_id, 'role="form" class="pure-form pure-form-aligned" data-parsley-validate');
            ?>
                <fieldset>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="subgroup">Subgroup</label>
                        </div>
                          <div class="input">
                            <!-- tadaaaa <?php echo form_dropdown('subgroup', $subgroup, trim(@$data->subgroup), 'id="subgroup" class="pure-input-1-2" required data-parsley-required-message="Please select subgroup"') ?> -->
                            <input type="text" name="subgroup" data-parsley-trigger="change" value="<?php echo $subgroup?>" id="subgroup" class="pure-input-1-2" style="color:#000" required data-parsley-required-message="Please input subgroup" data-parsley-type-message="Please input subgroup" disabled>

                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="fullname">Full Name</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('fullname', set_value('fullname', @$data->fullname), 'id="fullname" class="pure-input-1-2" required data-parsley-required-message="Please input student name"') ?>
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="email">Email</label>
                        </div>
                        <div class="input">
                            <input type="email" name="email" data-parsley-trigger="change" value="<?php echo @$data->email?>" id="email" class="pure-input-1-2" required data-parsley-required-message="Please input student e-mail address" data-parsley-type-message="Please input valid e-mail address">
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="nickname">Nick Name</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('nickname', set_value('nickname', @$data->nickname), 'id="nickname" class="pure-input-1-2" required data-parsley-required-message="Please input student nick name"') ?>
                        </div>
                    </div>

                      <div class="pure-control-group">
                        <div class="label">
                            <label for="email">DynEd Pro ID (Email)</label>
                        </div>
                        <div class="input">
                            <input type="email" name="email_pro_id" data-parsley-trigger="change" value="<?php echo @$data->email_pro_id?>" id="email_pro_id" class="pure-input-1-2" required data-parsley-required-message="Please input student e-mail Pro ID address" data-parsley-type-message="Please input valid e-mail Pro ID address">
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
                            <label for="ptsocre">Certification Goal</label>
                        </div>
                        <div class="input cert_studying">
                            <input type="text" name="cert_studying" data-parsley-trigger="change" id="cert_studying" class="pure-input-1-2" readonly>
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="ptsocre">PT Score</label>
                        </div>
                        <div class="input cert_studying">
                            <input type="text" name="pt_score" data-parsley-trigger="change" id="pt_score" class="pure-input-1-2" readonly>
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="date_of_birth">Birthdate</label>
                        </div>
                        <div class="input">
                            <div class="frm-date">
                            <?php echo form_input('date_of_birth', set_value('date_of_birth', @$data->date_of_birth), 'id="date_of_birth" class="datepicker pure-input-1-2" readonly required data-parsley-required-message="Plase pick student birthdate"') ?>
                            <span class="icon icon-date" style="left: 180px;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="gender">Gender</label>
                        </div>
                        <div class="input">
                            <?php echo form_dropdown('gender', $this->auth_manager->gender(), set_value('gender', trim(@$data->gender)), 'id="sex" class="pure-input-1-2" required data-parsley-required-message="Please select student gender"') ?>
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
                    <!-- <div class="pure-control-group">
                        <div class="label">
                            <label for="dial_code">Country Code</label>
                        </div>
                        <div class="input">
                            <input type="text" name="dial_code" data-parsley-trigger="change" value="<?php echo @$dial_code; ?>" id="dial_code" class="pure-input-1-2" readonly>
                        </div>
                    </div> -->
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="dial_code"></label>
                            <label for="phone">Phone Number</label>
                        </div>
                        <div class="input">
                            <div class="flex">
                                <input type="text" name="dial_code" data-parsley-trigger="change" value="<?php echo @$dial_code; ?>" id="dial_code" class="pure-input-1-8" style="margin-right:1px;" readonly>
                                <?php echo form_input('phone', set_value('phone', @$data->phone), 'id="phone" class="pure-input-1-2" data-parsley-type="digits" required data-parsley-required-message="Please input student phone number" data-parsley-type-message="Please input numbers only"') ?>
                            </div>
                        </div>
                    </div>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="token_amount">Token Amount</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('token_amount', set_value('token_amount', @$data->token_amount), 'id="token_amount" class="pure-input-1-2" required data-parsley-type="digits" data-parsley-required-message="Please input student token" data-parsley-type-message="Please input numbers only"') ?>
                        </div>
                    </div>

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
                            <a href="<?php echo site_url('student_partner/subgroup/list_student/'.$subgroup_id);?>" class="pure-button btn-red btn-small approve-user delete_match">CANCEL</a>
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
    });
</script>


<script>
    $( "#server_dyned_pro" ).change(function() {
      var email = $('#email_pro_id').val();
      var server = $(this).val();
        if((server != '') && (email != '')){
           $('#cert_studying').addClass('loadinggif');
           $('#pt_score').addClass('loadinggif');
            $.ajax({
                url: "<?php echo site_url('student_partner/adding/cert_studying');?>",
                type: "POST",
                data: { email : email, server: server },
                dataType: "html",
                success: function (response) {
                  $('#cert_studying').removeClass('loadinggif');
                  $('#pt_score').removeClass('loadinggif');

                   var compile_rsp = response.split("\n");
                   var json_rsp    = JSON.parse(compile_rsp[3]);

                   var data_cert_studying = json_rsp[0].cert_studying;
                   var data_pt_score      = json_rsp[0].pt_score;
                   // console.log(json_rsp[0].cert_studying);
                   $('input[name=cert_studying]').val(data_cert_studying);
                   $('input[name=pt_score]').val(data_pt_score);
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
            $('#cert_studying').addClass('loadinggif');
            $('#pt_score').addClass('loadinggif');
            $.ajax({
                url: "<?php echo site_url('student_partner/adding/cert_studying');?>",
                type: "POST",
                data: { email : email, server: server },
                dataType: "html",
                success: function (response) {
                  $('#cert_studying').removeClass('loadinggif');
                  $('#pt_score').removeClass('loadinggif');

                   var compile_rsp = response.split("\n");
                   var json_rsp    = JSON.parse(compile_rsp[3]);

                   var data_cert_studying = json_rsp[0].cert_studying;
                   var data_pt_score      = json_rsp[0].pt_score;
                   // console.log(json_rsp[0].cert_studying);
                   $('input[name=cert_studying]').val(data_cert_studying);
                   $('input[name=pt_score]').val(data_pt_score);
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
                url: "<?php echo site_url('student_partner/subgroup/dial_code');?>",
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
