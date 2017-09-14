<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo("<br>Set Meeting Time ".@$title." Class <br>");
    echo(date('l j M Y', $date).'<br><br>');
    echo(@$id_to_name[@$coach_id].' Availability');
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Book</th>
    </tr>
        <?php 
            for($i=0 ; $i<count(@$availability) ; $i++){
        ?>
        <tr>
            <td><?php echo($i+1); ?></td>
            <td id = "" name=""><?php echo(@$availability[$i]['start_time']); ?></td>
            <td id = "" name=""><?php echo(@$availability[$i]['end_time']); ?></td>
            <td> <a href="<?php echo site_url('student_partner/managing/create_meeting_day/' .$class_id. '/' .$coach_id. '/' .date('Y-m-d', $date). '/' .@$availability[$i]['start_time']. '/' .@$availability[$i]['end_time']); ?>" onclick="alert('Are you sure?');">Set meeting time</a> </td>
        </tr>
        <?php
            }
        ?>
        
</table>