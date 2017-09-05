<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
echo form_open('student_partner/adding/' . $form_action, 'role="form"');
echo('<br> Adding Class <br><br>');
?>

<table>
    <tr>
        <td>Class Name</td>
        <td>:</td>
        <td>
            <?php echo form_error('class_name', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('class_name', set_value('class_name', @$data->class_name), 'id="class_name"') ?>
        </td>
    </tr>
    <tr>
        <td>Student Amount</td>
        <td>:</td>
        <td>
            <?php echo form_error('student_amount', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('student_amount', set_value('student_amount', @$data->student_amount), 'id="student_amount"') ?>
        </td>
    </tr>
    <tr>
        <td>Start Date</td>
        <td>:</td>
        <td>
            <?php echo form_error('start_date', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('start_date', set_value('start_date', @$data->start_date), 'id="start_date"') ?>
        </td>
    </tr>
    <tr>
        <td>End Date</td>
        <td>:</td>
        <td>
            <?php echo form_error('end_date', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('end_date', set_value('end_date', @$data->end_date), 'id="end_date"') ?>
        </td>
    </tr>
    <tr>
        <td><?php echo form_submit('__submit', @$data->id ? 'Update' : 'Submit'); ?></td>
    </tr>
</table>
<?php echo form_close(); ?>