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
        <th>Token for Student</th>
        <th>Token for Group</th>
        <th>Action</th>
    </tr>
    <?php $i =1; foreach(@$data as $d){ ?>
	<tr>
            <td><?php echo($i++);?></td>
            <td> <?php echo @$d->fullname; ?> </td>
            <td> <?php echo @$d->email; ?> </td>
            <td> <?php echo @$d->phone; ?> </td>
            <td> <?php echo @$d->token_for_student; ?> </td>
            <td> <?php echo @$d->token_for_group; ?> </td>
            <td> <a href="<?php echo site_url('partner/manage_coach_token_cost/edit/'.@$d->id ); ?>" onclick=" return confirm('Edit Token Cost?');"> Edit </a> </td>
        </tr>
    <?php } ?>
</table>