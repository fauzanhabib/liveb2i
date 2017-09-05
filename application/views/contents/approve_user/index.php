<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo("<br>Approve User<br><br>");
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Username</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php $i =1; foreach(@$users as $u){ ?>
	<tr>
            <td><?php echo($i++);?></td>
            <td><?php echo $u->email; ?></a> </td>
            <td><?php echo $u->status; ?></td>
            <td> <a href="<?php echo site_url('admin/approve_user/approve/'.@$u->id ); ?>" onclick=" return confirm('Approve User?');"> Approve </a></td>
        </tr>
    <?php } ?>
</table>
