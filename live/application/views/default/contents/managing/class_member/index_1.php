<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
/**
 * Link
 * @var array
 */
echo("<br>Add ".@$title." Class Member<br><br>");
?>
<table border="1">
    <tr>
        <th>No</th>
        <th>Full Name</th>
        <th>Manage</th>
    </tr>
    <?php $i = 1;
    foreach (@$class_members as $c) { ?>
        <tr>
            <td><?php echo($i++); ?></td>
            <td><?php echo $c->fullname; ?> </td>
            <td> <a href="<?php echo site_url('student_partner/managing/create_class_member/' .@$class_id. '/' .@$c->id); ?>" onclick=" return confirm('<?php echo('Add as '.@$title.' Class Member')?>');"> Add </a></td>
        </tr>
    <?php } ?>
</table>