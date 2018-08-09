<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
echo("<br>Manage Partner Member<br><br>");
?>
<table border="1">
    <tr>
        <th>No</th>
        <th>Name</th>
        <th>Gender</th>
        <th>Date of Birth</th>
        <th>Action</th>
    </tr>

    <?php
    if ($users) {
        $i = 1;
        foreach (@$users as $user) {
            ?>
            <tr>
                <td><?php echo($i++); ?></td>
                <td><?php echo $user->fullname; ?></td>
                <td><?php echo $user->gender; ?></td>
                <td><?php echo $user->date_of_birth; ?></td>
                <td><a href="<?php echo site_url('admin_m/manage_partner/delete_partner_member/' . $user->id); ?>" onclick=" return confirm('Delete Partner Member?');"> Delete</a></td>
            </tr>
        <?php }
    } ?>
</table>
