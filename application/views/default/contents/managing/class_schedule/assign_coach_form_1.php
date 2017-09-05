<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo('Assigned coach for ' .@$title. ' Class on ' .date('l j M Y', $date));
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Profile Picture</th>
        <th>Name</th>
        <th>Country</th>
        <th>Phone</th>
        <th>Action</th>
    </tr>
    <?php $i =1; foreach(@$data as $d){ ?>
	<tr>
            <td><?php echo($i++);?></td>
            <td> <a href="<?php echo site_url('student/find_coaches/detail/'.$d->id); ?>"><?php echo $d->profile_picture; ?></a> </td>
            <td> <a href="<?php echo site_url('student/find_coaches/detail/'.$d->id); ?>"><?php echo $d->fullname; ?></a> </td>
            <td><?php echo $d->country; ?></td>
            <td><?php echo $d->phone; ?></td>
            <td> <a href="<?php echo site_url('student_partner/managing/set_meeting_time/'. @$class_id. '/' .@$d->id. '/' .@date('Y-m-d', $date)); ?>" onclick=" return confirm('View Availability Details');"> View Availability Details </a> </td>
        </tr>
    <?php } ?>
</table>