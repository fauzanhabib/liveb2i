<?php
echo form_open('partner/schedule/' . $form_action . '/' . @$student_id, 'role="form"');
echo('<br>Student\'s Appoinment <br><br>');
?>

<table>
    <tr>
        <td>Coach Name</td>
        <td>:</td>
        <td>
            <?php echo form_error('coach_id', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_dropdown('coach_id', @$coaches, set_value('coach_id', @$coach), 'id="coach_id" onChange="dropdown_on_change(this.id, \'by_coach\')"') ?>
        </td>
    </tr>
    <tr>
        <td>Date</td>
        <td>:</td>
        <td>
            <?php echo form_error('date', '<small class="pull-right req">', '</small>');
            echo form_dropdown('date', @$dates, set_value('date', @$date), 'id="date" onChange="dropdown_on_change(this.id, \'by_date\')"');?>
        </td>
    </tr>
    <tr> 
        <td>Time</td>
        <td>:</td>
        <td>
            <?php echo form_error('time', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_dropdown('time', @$availabilities, set_value('time', @$availability), 'id="time"'); //print_r($availability); exit; ?>
        </td>
    </tr>
    <tr>
        <td><?php echo form_submit('__submit', @$student_appointment->id ? 'Reschedule' : 'Submit'); ?></td>
        <td><?php echo form_hidden('student_id', set_value('student_id', @$student_id)); ?></td>
        <td><?php echo form_hidden('student_appointment_id', set_value('student_appointment_id', @$student_appointment->id)); ?></td>
    </tr>
</table>
<?php echo form_close(); ?>

<script type="text/javascript">
    function dropdown_on_change(id, param){
        if(document.getElementById(id).value === ''){
            window.location.href = "<?php echo base_url(); ?>index.php/partner/schedule";
        }else if(param === 'by_coach'){
            window.location.href = "<?php echo base_url(); ?>index.php/partner/schedule/by_coach/"+<?php echo @$student_id?>+"/"+document.getElementById(id).value;
        }else if(param === 'by_date'){
            window.location.href = "<?php echo base_url(); ?>index.php/partner/schedule/by_date/"+<?php echo @$student_id?>+"/"+document.getElementById('coach_id').value+"/"+document.getElementById(id).value;
        }
    }
</script>
