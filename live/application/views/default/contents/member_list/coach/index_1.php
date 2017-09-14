<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo('<br>'.$title.'<br><br>');
?>
<table border="1">
    <tr>
        <th>No</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php $i =1; foreach($data as $d){ ?>
	<tr>
            <td><?php echo($i++);?></td>
            <td> <?php echo $d->fullname; ?> </td>
            <td> <?php echo $d->email; ?> </td>
            <td> <?php echo $d->phone; ?> </td>
            <td><?php echo $d->status; ?></td>
            <td> <a href="<?php echo site_url('partner/member_list/edit_coach/'.@$d->id ); ?>" onclick=" return confirm('Edit Coach?');"> Edit </a> | <a href="<?php echo site_url('partner/member_list/delete_coach/'.@$d->id ); ?>" onclick=" return confirm('Delete Coach?');"> Delete </a> </td>
        </tr>
    <?php } ?>
</table>