<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
/**
 * Link
 * @var array
 */
echo("<br>Managing Class<br><br>");
?>
<?php echo anchor(base_url() . 'index.php/student_partner/managing/add_class', "Add Class"); ?>
<table border="1">
    <tr>
        <th>No</th>
        <th>Class Name</th>
        <th>Max Student Amount</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Manage</th>
    </tr>
    <?php $i = 1;
    foreach (@$classes as $c) { ?>
        <tr>
            <td><?php echo($i++); ?></td>
            <td><?php echo $c->class_name; ?> </td>
            <td><?php echo $c->student_amount; ?> </td>
            <td><?php echo $c->start_date; ?> </td>
            <td><?php echo $c->end_date; ?> </td>
            <td> <a href="<?php echo site_url('student_partner/managing/edit_class/' . @$c->id); ?>" onclick=" return confirm('Edit Class');"> Edit Class </a> | <a href="<?php echo site_url('student_partner/managing/delete_class/' . @$c->id); ?>" onclick=" return confirm('Delete Class');"> Delete Class </a> | <a href="<?php echo site_url('student_partner/managing/class_member/' . @$c->id); ?>" onclick=" return confirm('Manage Class Member');"> Class Member </a> | <a href="<?php echo site_url('student_partner/managing/class_schedule/' . @$c->id); ?>" onclick=" return confirm('Manage Class Schedule');">  Class Schedule </a> </td>
        </tr>
    <?php } ?>
</table>