<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Edit Student</h1>
</div>

<div class="box">
    <div class="heading pure-g"></div>

    <div class="content">
        <div class="box">
            <?php echo form_open('student_partner/member_list/'.$form_action, 'role="form" class="pure-form pure-form-aligned" data-parsley-validate'); ?>
                <fieldset>
                    
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="full_name">Full Name</label>
                        </div>  
                        <div class="input">
                            <?php echo form_input('fullname', set_value('fullname', @$data[0]->fullname), 'id="fullname" class="pure-input-1-2" required data-parsley-required-message="Please input student name"') ?>
                        </div>
                    </div>

                   

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="date_of_birth">Birthdate</label>
                        </div>
                        <div class="input">
                            <div class="frm-date">
                                <?php echo form_input('date_of_birth', set_value('date_of_birth', @$data[0]->date_of_birth), 'id="date_of_birth" class="datepicker pure-input-1-2" readonly required data-parsley-required-message="Plase pick student birthdate"') ?>
                                <span class="icon icon-date" style="left: 180px;"></span>
                            </div>
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="gender">Gender</label>
                        </div>
                        <div class="input">
                            <?php echo form_dropdown('gender', $this->auth_manager->gender(), trim(@$data[0]->gender), 'id="sex" class="pure-input-1-2" required data-parsley-required-message="Please select student gender"') ?>
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="phone">Phone Number</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('phone', set_value('phone', @$data[0]->phone), 'id="phone" class="pure-input-1-2" data-parsley-type="digits" required data-parsley-required-message="Please input student phone number" data-parsley-type-message="Please input numbers only"') ?>
                        </div>
                    </div>
                    <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                        <div class="label">
                            <?php echo form_submit('__submit', @$data[0]->id ? 'UPDATE' : 'UPDATE', 'class="pure-button btn-small btn-primary"'); ?>
                             <a href="<?php echo site_url('student_partner/member_list/student'); ?>" class="pure-button btn-small btn-red">CANCEL</a>

                        </div>
                    </div>
                </fieldset>
            <?php echo form_close();?> 
        </div>
    </div>
</div>              

<script>
    $(function (){
        $(".datepicker").datepicker({
            format: 'yyyy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: '-100:+0',
            defaultDate: new Date(1990, 00, 01),
            endDate: "now"
        });
    });
</script>