<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Add Student</h1>
</div>

<div class="box">
    <div class="heading pure-g"></div>

    <div class="content">
        <div class="box">
            <?php 
            echo form_open('student_partner/adding/'.$form_action, 'role="form" class="pure-form pure-form-aligned"');
            ?>
                <fieldset>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="email">Email</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('email', set_value('email', @$data->email), 'id="email" class="pure-input-1-2"') ?>
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="fullname">Full Name</label>
                        </div>	
                        <div class="input">
                            <?php echo form_input('fullname', set_value('fullname', @$data->fullname), 'id="fullname" class="pure-input-1-2"') ?>
                        </div>
                    </div>

<!--                    <div class="pure-control-group">
                        <div class="label">
                            <label for="country">Nickname</label>
                        </div>
                        <div class="input">
                            <?php //echo form_input('nickname', set_value('nickname', @$data->nickname), 'id="nickname" class="pure-input-1-2"') ?>
                        </div>
                    </div>-->

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="gender">Gender</label>
                        </div>
                        <div class="input">
                            <?php echo form_dropdown('gender', $this->auth_manager->gender(), trim(@$data->gender), 'id="sex" class="pure-input-1-2"') ?>
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="date_of_birth">Date of Birth</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('date_of_birth', set_value('date_of_birth', @$data->date_of_birth), 'id="date_of_birth" class="datepicker pure-input-1-2" readonly') ?>
                        </div>
                    </div>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="phone">Phone Number</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('phone', set_value('phone', @$data->phone), 'id="phone" class="pure-input-1-2"') ?>
                        </div>
                    </div>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="token_amount">Token Amount</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('token_amount', set_value('token_amount', @$data->token_amount), 'id="token_amount" class="pure-input-1-2"') ?>
                        </div>
                    </div>
                    <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                        <div class="label">
                            <?php echo form_submit('__submit', @$data->id ? 'Update' : 'Submit', 'class="pure-button btn-small btn-primary"'); ?>
                            <a href="<?php echo site_url('student_partner/member_list/student'); ?>" class="pure-button btn-red btn-small approve-user">CANCEL</a>
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