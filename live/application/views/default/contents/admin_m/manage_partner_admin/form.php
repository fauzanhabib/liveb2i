<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Edit Affiliate Admin</h1>
</div>

<div class="box">
    <div class="heading pure-g"></div>

    <div class="content">
        <div class="box">
            <?php echo form_open('admin_m/manage_partner_admin_m/'.@$form_action.'/'.@$partner_id.'/'.@$data[0]->id, 'role="form" class="pure-form pure-form-aligned"');?>
                <fieldset>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="role">Role</label>
                        </div>	
                        <div class="input">
                            <?php echo form_dropdown('role_id', @$partner_type, @$selected, 'id = "partner_id" class="pure-input-1-2"'); ?>
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="email">Email</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('email', set_value('email', @$data[0]->email), 'id="email" class="pure-input-1-2"') ?>
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="fulname">Full name</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('fullname', set_value('fullname', @$data[0]->fullname), 'id="fullname" class="pure-input-1-2"') ?>
                        </div>
                    </div>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="gender">Gender</label>
                        </div>
                        <div class="input">
                            <?php echo form_dropdown('gender', $this->auth_manager->gender(), trim(@$data[0]->gender), 'id="sex" class="pure-input-1-2"') ?>
                        </div>
                    </div>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="date_of_birth">Date of Birth</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('date_of_birth', set_value('date_of_birth', @$data[0]->date_of_birth), 'id="date_of_birth" class="pure-input-1-2 datepicker"') ?>
                        </div>
                    </div>
                    
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="phone">Phone Number</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('phone', set_value('phone', @$data[0]->phone), 'id="phone" class="pure-input-1-2"') ?>
                        </div>
                    </div>
                    
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="skype_id">Skype ID</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('skype_id', set_value('skype_id', @$data[0]->skype_id), 'id="skype_id" class="pure-input-1-2"') ?>
                        </div>
                    </div>
                    
                    <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                        <div class="label">
                            <?php echo form_submit('__submit', 'SUBMIT', 'class="pure-button btn-small btn-primary"'); ?>
                            <a href="<?php echo site_url('admin_m/manage_partner/list_partner_member/'.$selected) ?>" class="pure-button btn-red btn-small">CANCEL</a>
                        </div>
                    </div>
                </fieldset>
            <?php echo form_close();?>        
        </div>
    </div>
</div>			

<script type="text/javascript">
    $(function () {
        $(".datepicker").datepicker({
            format: 'yyyy-mm-dd',
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(1990, 00, 01)
        });
    });
</script>