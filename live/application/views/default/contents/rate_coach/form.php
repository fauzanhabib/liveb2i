<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo form_open('student/rate_coaches/'.$form_action, 'role="form"');
    echo('<br> Rate Coach ' .$coach_name. '<br>');
    echo('Rating Skala 1 - 5 <br><br>');
    
?>

<table>
    <tr>
        <td>Rating</td>
        <td>:</td>
        <td>
            <?php echo form_error('rate', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('rate', set_value('rate', @$data->rate), 'id="rate"') ?>
        </td>
    </tr>
    
    <tr>
        <td>Impression</td>
        <td>:</td>
        <td>
            <?php echo form_error('description', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('description', set_value('description', @$data->description), 'id="description"') ?>
        </td>
    </tr>

    <tr>
        <td><?php echo form_submit('__submit', @$data->id ? 'Update' : 'Submit'); ?></td>
    </tr>
</table>
<?php echo form_close(); ?>