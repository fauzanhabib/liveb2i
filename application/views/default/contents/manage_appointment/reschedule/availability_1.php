<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo("<br>Reschedule Session<br><br>");
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Reschedule</th>
    </tr>
        <?php 
            for($i=0 ; $i<count(@$availability) ; $i++){
        ?>
        <tr>
            <td><?php echo($i+1); ?></td>
            <td id = "" name=""><?php echo(@$availability[$i]['start_time']); ?></td>
            <td id = "" name=""><?php echo(@$availability[$i]['end_time']); ?></td>
            <td> <a href="<?php echo site_url('student/manage_appointments/reschedule_booking/'.@$schedule_id.'/'.$appointment_id.'/'.$coach_id.'/'.$date.'/'.@$availability[$i]['start_time'].'/'.@$availability[$i]['end_time']); ?>" onclick="return confirm('Are you sure?');">Reschedule</a> </td>
        </tr>
        <?php
            }
        ?>
        
</table>