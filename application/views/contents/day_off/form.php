<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo form_open('coach/day_off/'.$form_action, 'role="form"');
    echo('<br> Request Day Off<br><br>');
?>

<table>
    <tr>
        <td>Start Date</td>
        <td>:</td>
        <td>
            <?php echo form_error('start_date', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('start_date', set_value('start_date', @$data->start_date), 'id="start_date" class="datepicker"') ?>
        </td>
    </tr>
    <tr>
        <td>End Date</td>
        <td>:</td>
        <td>
            <?php echo form_error('end_date', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('end_date', set_value('end_date', @$data->end_date), 'id="end_date" class="datepicker"') ?>
        </td>
    </tr>
    <tr>
        <td>Remark</td>
        <td>:</td>
        <td>
            <?php echo form_input('remark', set_value('remark', @$data->remark), 'id="remark"') ?>
        </td>
    </tr>
    <tr>
        <td><?php echo form_submit('__submit', @$data->id ? 'Update' : 'Submit'); ?></td>
    </tr>
</table>
<?php echo form_close(); ?>

<script>
    $(function () {
        $(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>