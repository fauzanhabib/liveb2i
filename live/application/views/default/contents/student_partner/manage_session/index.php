<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo("<br>Manage Appointment<br><br>");
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Coach</th>
        <th>Date</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Action</th>
    </tr>
        <?php 
            for($i=0 ; $i<count(@$appointment) ; $i++){
        ?>
        <tr>
            <td><?php echo($i+1); ?></td>
            <td><?php echo(@$appointment[$i]->coach_id); ?></td>
            <td><?php echo(@$appointment[$i]->date); ?></td>
            <td><?php echo(@$appointment[$i]->start_time); ?></td>
            <td><?php echo(@$appointment[$i]->end_time); ?></td>
            <td> 
                <a href="<?php echo site_url('student/manage_appointments/reschedule/'.@$appointment[$i]->id);?>" onclick=" return confirm('Are you sure?');">Reschedule</a> | 
                <a href="<?php echo site_url('student/manage_appointments/cancel/'.@$appointment[$i]->id);?>" onclick="return confirm('Are you sure?');">Cancel</a> 
            </td>
        </tr>
        <?php
            }
        ?>
        
</table>