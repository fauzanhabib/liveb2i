<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo("<br>Manage Partner<br><br>");
?>
<a href="<?php echo site_url('admin/manage_partner/add_partner'); ?>"> Add Partner </a>
<table border="1">
    <tr>
        <th>No</th>
        <th>Name</th>
        <th>Country</th>
        <th>Action</th>
    </tr>
    <?php $i =1; foreach(@$partner as $p){ ?>
	<tr>
            <td><?php echo($i++);?></td>
            <td><?php echo $p->name; ?></a> </td>
            <td><?php echo $p->country; ?></td>
            <td> <a href="<?php echo site_url('admin/manage_partner/add_partner_member/'. $p->id); ?>"> Add Member </a> | <a href="<?php echo site_url('admin/manage_partner/edit_partner/'.@$p->id ); ?>" > Edit Partner </a> | <a href="<?php echo site_url('admin/manage_partner/delete_partner/'.@$p->id ); ?>" onclick=" return confirm('Delete Partner?');"> Delete Partner </a></td>
        </tr>
    <?php } ?>
</table>
