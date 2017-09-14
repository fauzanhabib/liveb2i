<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
/**
 * Link
 * @var array
 */
echo(@$title->class_name." Class Member<br><br>");
?>
<?php echo anchor(base_url() . 'index.php/student_partner/adding/classes', "Add Member"); ?>
<table border="1">
    <tr>
        <th>No</th>
        <th>Student Name</th>
        <th>Action</th>
    </tr>
    <?php $i = 1;
    foreach (@$members as $m) { ?>
        <tr>
            <td><?php echo($i++); ?></td>
            <td><?php echo $m->fullname; ?> </td>
            <td> <a href="<?php echo site_url('student_partner/adding/class_member/' . @$c->id); ?>" onclick=" return confirm('Manage Class');"> Manage </a></td>
        </tr>
    <?php } ?>
</table>