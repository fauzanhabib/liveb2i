<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo form_open('partner/manage_coach_token_cost/'.$form_action, 'role="form"');
    echo('<br> Edit Coach Token Cost <br><br>');
?>

<table>
    <tr>
        <td>Name</td>
        <td>:</td>
        <td>
            <?php echo @$data[0]->fullname; ?>
        </td>
    </tr>
    <tr>
        <td>Email</td>
        <td>:</td>
        <td>
            <?php echo @$data[0]->email; ?>
        </td>
    </tr>
    <tr>
        <td>Phone</td>
        <td>:</td>
        <td>
            <?php echo @$data[0]->phone; ?>
        </td>
    </tr>
    <tr>
        <td>Token Cost for Student</td>
        <td>:</td>
        <td>
            <?php echo form_error('token_for_student', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('token_for_student', set_value('token_for_student', @$data[0]->token_for_student), 'id="token_for_student"') ?>
        </td>
    </tr>
    <tr>
        <td>Token Cost for Group</td>
        <td>:</td>
        <td>
            <?php echo form_error('token_for_group', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('token_for_group', set_value('token_for_group', @$data[0]->token_for_group), 'id="token_for_group"') ?>
        </td>
    </tr>
   
    <tr>
        <td><?php echo form_submit('__submit', @$data[0]->id ? 'Update' : 'Submit'); ?></td>
    </tr>
</table>
<?php echo form_close(); ?>