<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo("<br>Manage Day Off<br><br>");
?>
<a href="<?php echo site_url('coach/day_off/add'); ?>"> Add Day Off </a>
<table border="1">
    <tr>
        <th>No</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php $i =1; foreach(@$data as $d){ ?>
	<tr>
            <td><?php echo($i++);?></td>
            <td><?php echo $d->start_date; ?></a> </td>
            <td><?php echo $d->end_date; ?></td>
            <td><?php echo $d->status; ?></td>
            <td> <a href="<?php echo site_url('coach/day_off/edit/'.@$d->id ); ?>" onclick=" return confirm('Edit Day Off?');"> Edit Day Off </a> | <a href="<?php echo site_url('coach/day_off/delete/'.@$d->id ); ?>" onclick=" return confirm('Delete Day Off?');"> Delete Day Off </a> </td>
        </tr>
    <?php } ?>
</table>
