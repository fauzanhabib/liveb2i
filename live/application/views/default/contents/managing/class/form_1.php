<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
echo form_open('student_partner/managing/' . $form_action, 'role="form"');
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

<div class="heading text-cl-primary padding15">
	<h1 class="margin0">Edit Class</h1>
</div>

<div class="box">
	<div class="heading pure-g"></div>

	<div class="content">
		<div class="box">
                            <?php echo form_open('student_partner/managing/' . $form_action, 'role="form" class="pure-form pure-form-aligned"');?>
			    <fieldset>
			        <div class="pure-control-group">
			        	<div class="label">
			            	<label for="class">Class Name</label>
			            </div>
			            <div class="input">
                                        <?php echo form_input('class_name', set_value('class_name', @$data->class_name), 'id="class_name" class="pure-input-1"') ?>
			            </div>
			        </div>

			        <div class="pure-control-group">
			        	<div class="label">
			            	<label for="student">Student Amount</label>
			            </div>	
			            <div class="input">
                                        <?php echo form_input('student_amount', set_value('student_amount', @$data->student_amount), 'id="student_amount" class="pure-input-1-2"') ?>
			            </div>
			        </div>

			        <div class="pure-control-group">
			        	<div class="label">
			            	<label for="date">Start Date</label>
			            </div>
			            <div class="input">
                                        <?php echo form_input('start_date', set_value('start_date', @$data->start_date), 'id="start_date" class="datepicker frm-date pure-input-1-2" readonly ') ?>
			            </div>
			        </div>

			        <div class="pure-control-group">
			        	<div class="label">
			            	<label for="date2">End Date</label>
			            </div>
			            <div class="input">
                                        <?php echo form_input('end_date', set_value('end_date', @$data->end_date), 'id="end_date" class="datepicker frm-date pure-input-1-2" readonly ') ?>
			            </div>
			        </div>
			        <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
			        	<div class="label">
                                        <?php echo form_submit('__submit', @$data->id ? 'Update' : 'Submit', 'class="pure-button btn-small btn-primary"'); ?>
			            </div>
			        </div>
			    </fieldset>
			<?php echo form_close(); ?>      
		</div>
	</div>
</div>				

<script>
$(function () {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd'
        });
    });
</script>