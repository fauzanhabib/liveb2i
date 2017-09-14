<?php
    echo("<br>Availability<br>". date('l jS \of F Y', @$date));
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Cost (Token)</th>
        <th>Book</th>
    </tr>
        <?php 
            for($i=0 ; $i<count(@$availability) ; $i++){
        ?>
        <tr>
            <td><?php echo($i+1); ?></td>
            <td><?php echo(@$availability[$i]['start_time']); ?></td>
            <td><?php echo(@$availability[$i]['end_time']); ?></td>
            <td><?php echo(@$cost->token_for_student); ?></td>
            <td> <a href="<?php echo site_url('student/find_coaches/booking/'.$coach_id.'/'.$date.'/'.@$availability[$i]['start_time'].'/'.@$availability[$i]['end_time']); ?>" onclick="alert('Are you sure?');">Book</a> </td>
        </tr>
        <?php
            }
        ?>
        
</table>