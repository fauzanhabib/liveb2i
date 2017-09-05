<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
/**
 * Link
 * @var array
 */
echo(@$title." Class Member<br><br>");
?>
<?php echo anchor(base_url() . 'index.php/student_partner/managing/add_class_member/'. $class_id, "Add Member"); ?>
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
            <td> <a href="<?php echo site_url('student_partner/managing/delete_class_member/' . @$class_id. '/' .@$m->id); ?>" onclick=" return confirm('Manage Class');"> Delete </a></td>
        </tr>
    <?php } ?>
</table>