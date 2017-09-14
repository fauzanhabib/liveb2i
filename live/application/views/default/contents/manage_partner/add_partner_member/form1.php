<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo form_open('admin/manage_partner/'.$form_action, 'role="form"');
    echo('<br> Add Partner Member <br><br>');
?>

<table>
    <tr>
        <td>Partner</td>
        <td>:</td>
        <td>
            <?php echo form_dropdown('partner_id', @$partner, @$selected, 'id = "partner_id"'); ?>
        </td>
    </tr>
    <tr>
        <td>Partner Type</td>
        <td>:</td>
        <td>
            <?php echo form_dropdown('role_id', Array('3'=>'Caoch Partner', '5'=>'Academic Partner'), @$selected, 'id = "partner_id"'); ?>
        </td>
    </tr>
    <tr>
        <td>Email</td>
        <td>:</td>
        <td>
            <?php echo form_error('email', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('email', set_value('email', @$data->email), 'id="email"') ?>
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
        <td><?php echo form_input('date_of_birth', set_value('date_of_birth', @$data->date_of_birth), 'id="date_of_birth"') ?></td>
    </tr>
    <tr>
        <td>Phone Number</td>
        <td>:</td>
        <td><?php echo form_input('phone', set_value('phone', @$data->phone), 'id="phone"') ?></td>
    </tr>
    
    <tr>
        <td>Skype Account</td>
        <td>:</td>
        <td><?php echo form_input('skype_id', set_value('skype_id', @$data->skype_id), 'id="skype_id"') ?></td>
    </tr>
    
    <tr>
        <td>Dyned Pro ID</td>
        <td>:</td>
        <td><?php echo form_input('dyned_pro_id', set_value('dyned_pro_id', @$data->dyned_pro_id), 'id="dyned_pro_id"') ?></td>
    </tr>
    <tr>
        <td><?php echo form_submit('__submit', @$data->id ? 'Update' : 'Submit'); ?></td>
    </tr>
</table>
<?php echo form_close(); ?>