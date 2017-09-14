<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo form_open('partner/adding/'.$form_action, 'role="form"');
    echo('<br> Coach Identity <br><br>');
?>

<table>
    <tr>
        <td>Email</td>
        <td>:</td>
        <td>
            <?php echo form_error('email', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('email', set_value('email', @$data->email), 'id="email"') ?>
        </td>
    </tr>
    <tr>
        <td>Token Cost For Student</td>
        <td>:</td>
        <td>
            <?php echo form_error('token_for_student', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('token_for_student', set_value('token_for_student', @$data->token_for_student), 'id="token_for_student"') ?>
        </td>
    </tr>
    <tr>
        <td>Token Cost For Group</td>
        <td>:</td>
        <td>
            <?php echo form_error('token_for_group', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('token_for_group', set_value('token_for_group', @$data->token_for_group), 'id="token_for_group"') ?>
        </td>
    </tr>
    <tr>
        <td>Full Name</td>
        <td>:</td>
        <td>
            <?php echo form_error('fullname', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('fullname', set_value('fullname', @$data->fullname), 'id="fullname"') ?>
        </td>
    </tr>
    <tr>
        <td>Nickname</td>
        <td>:</td>
        <td>
            <?php echo form_input('nickname', set_value('nickname', @$data->nickname), 'id="nickname"') ?>
        </td>
    </tr>
    <tr>
        <td>gender</td>
        <td>:</td>
        <td><?php echo form_dropdown('gender', $this->auth_manager->gender(), trim(@$data->gender), 'id="sex"') ?></td>
    </tr>
    <tr>
        <td>Date of Birth</td>
        <td>:</td>
        <td><?php echo form_input('date_of_birth', set_value('date_of_birth', @$data->date_of_birth), 'id="date_of_birth" class="datepicker"') ?></td>
    </tr>
    <tr>
        <td>Phone Number</td>
        <td>:</td>
        <td><?php echo form_input('phone', set_value('phone', @$data->phone), 'id="phone"') ?></td>
    </tr>
    
<!--    <tr>
        <td><?php //echo('<br> Home Town <br><br>'); ?></td>
    </tr>
    
        <tr>
            <td>Country</td>
            <td>:</td>
            <td><?php //echo form_input('country', set_value('country', @$data->country), 'id="country"') ?></td>
        </tr>
        <tr>
            <td>State</td>
            <td>:</td>
            <td><?php //echo form_input('state', set_value('state', @$data->state), 'id="state"') ?></td>
        </tr>
        <tr>
            <td>City</td>
            <td>:</td>
            <td><?php //echo form_input('city', set_value('city', @$data->city), 'id="city"') ?></td>
        </tr>
        <tr>
            <td>Zip</td>
            <td>:</td>
            <td><?php //echo form_input('zip', set_value('zip', @$data->zip), 'id="zip"') ?></td>
        </tr>
        
        <tr>
            <td><?php //echo('<br> Education <br><br>'); ?></td>
        </tr>
        
    <tr>
        <td>Teaching Credential</td>
        <td>:</td>
        <td>
            <?php //echo form_error('teaching_credential', '<small class="pull-right req">', '</small>') ?>
            <?php //echo form_input('teaching_credential', set_value('teaching_credential', @$data->teaching_credential), 'id="teaching_credential"') ?>
        </td>
    </tr>
    <tr>
        <td>DynEd Certification Level</td>
        <td>:</td>
        <td><?php //echo form_input('dyned_certification_level', set_value('dyned_certification_level', @$data->dyned_certification_level), 'id="dyned_certification_level"') ?></td>
    </tr>
    <tr>
        <td>Year Experience</td>
        <td>:</td>
        <td>
            <?php //echo form_error('year_experience', '<small class="pull-right req">', '</small>') ?>
            <?php //echo form_input('year_experience', set_value('year_experience', @$data->year_experience), 'id="year_experience"') ?>
        </td>
    </tr>
    <tr>
        <td>Special English Skill</td>
        <td>:</td>
        <td>
            <?php //echo form_error('special_english_skill', '<small class="pull-right req">', '</small>') ?>
            <?php //echo form_input('special_english_skill', set_value('special_english_skill', @$data->special_english_skill), 'id="special_english_skill"') ?>
        </td>
    </tr>-->
    <tr>
        <td><?php echo form_submit('__submit', @$data->id ? 'Update' : 'Submit'); ?></td>
    </tr>
</table>
<?php echo form_close(); ?>

<script>
    $(function (){
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: '-100:+0',
            defaultDate: new Date(1990, 00, 01)
        });
    });
</script>