
<?php
    echo form_open('student_partner/request_token/'.$form_action, 'role="form"');
?>
<table>
    <tr>
        <td>Token Amount</td>
        <td>:</td>
        <td>
            <?php echo form_error('token_amount', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('token_amount', set_value('token_amount', @$data->email), 'id="token_amount"') ?>
        </td>
    </tr>
    <tr>
        <td><?php echo form_submit('__submit', @$data->id ? 'Update' : 'Submit'); ?></td>
    </tr>
</table>
<?php echo form_close(); ?>