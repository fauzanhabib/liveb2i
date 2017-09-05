<?php
    echo("<br> Confirm Multiple Book<br><br>");
?>
<?php echo anchor(base_url().'index.php/student/find_coaches/book_by_multiple_date_index/1/', 'Back');?>
<table border="1">
    <tr>
    <th> Coach </th>
    <th> Date </th>
    <th> Start Time </th>
    <th> End Time </th>
    <th> Token Cost</th>
    <th> Action </th>
    </tr>
    <?php $total_token_cost_temp = 0; 
    foreach($data as $d){ 
        $total_token_cost_temp += $token_cost[$d->coach_id];
        ?>
        <tr>
            <td><?php echo($id_to_name[$d->coach_id]);?></td>
            <td> <?php echo(date('l jS \of F Y', strtotime($d->date)));?> </td>
            <td> <?php echo($d->start_time);?> </td>
            <td> <?php echo($d->end_time);?> </td>
            <td> <?php echo($token_cost[$d->coach_id]);?> </td>
            <td> <a href="<?php echo site_url('student/find_coaches/delete_temporary_appointment/'.$d->id); ?>" onclick=" return confirm('Are you sure delete this appointment?');"> Delete </a> </td>
            
        </tr>
    <?php
    }?>
        <tr>
            <td>Total</td>
            <td></td>
            <td></td>
            <td></td>
            <td> <?php echo($total_token_cost_temp);?> </td>
            <td></td>
        </tr>
</table>
<a href="<?php echo site_url('student/find_coaches/confirm_book/');?>" onclick=" return confirm('Are you sure?');">Confirm</a>
