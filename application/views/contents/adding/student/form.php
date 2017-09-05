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
                            <?php echo form_dropdown('subgroup', $subgroup, trim(@$data->subgroup), 'id="subgroup" class="pure-input-1-2" required data-parsley-required-message="Please select subgroup"') ?>
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
                            <label for="nickname">Nick Name</label>
                        </div>  
                        <div class="input">
                            <?php echo form_input('nickname', set_value('nickname', @$data->nickname), 'id="nickname" class="pure-input-1-2" required data-parsley-required-message="Please input student name"') ?>
                        </div>
                    </div>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="email">Email</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('email', set_value('email', @$data->email), 'id="email" class="pure-input-1-2" data-parsley-trigger="change" required data-parsley-required-message="Please input student e-mail" data-parsley-type-message="Invalid e-mail address"') ?>
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
                            <label for="phone">Phone Number</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('phone', set_value('phone', @$data->phone), 'id="phone" class="pure-input-1-2" data-parsley-type="digits" required data-parsley-required-message="Please input student phone number" data-parsley-type-message="Please input numbers only"') ?>
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
                    <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                        <div class="label">
                            <?php echo form_submit('__submit', @$data->id ? 'Update' : 'SUBMIT', 'class="pure-button btn-small btn-blue"'); ?>
                            <a href="<?php echo site_url('student_partner/subgroup/list_student/'.$subgroup_id);?>" class="pure-button btn-red btn-small approve-user delete_match text-cl-white">CANCEL</a>
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