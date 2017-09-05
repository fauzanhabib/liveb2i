<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo("<br>Approve User<br><br>");
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Picture</th>
        <th>Name</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Remark</th>
        <th>Status</th>
        
        <th>Action</th>
    </tr>
    <?php $i =1; foreach(@$data as $d){ ?>
	<tr>
            <td><?php echo($i++);?></td>
            <td><?php echo $d->profile_picture; ?></td>
            <td><?php echo $d->fullname; ?></td>
            <td><?php echo $d->start_date; ?></a> </td>
            <td><?php echo $d->end_date; ?></td>
            <td><?php echo $d->remark; ?></a> </td>
            <td><?php echo $d->status; ?></td>
            <td> <a href="<?php echo site_url('partner/approve_coach_day_off/approve/'.@$d->id ); ?>" onclick=" return confirm('Approve User?');"> Approve </a> | <a href="<?php echo site_url('partner/approve_coach_day_off/decline/'.@$d->id ); ?>" onclick=" return confirm('Decline User?');"> Decline </a></td>
        </tr>
    <?php } ?>
</table>
