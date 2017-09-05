<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo('<br>'.$title.'<br><br>');
?>
<table border="1">
    <tr>
        <th>No</th>
        <th>Description</th>
        <th>Received</th>
    </tr>
    <?php $i =1; foreach($data as $d){ ?>
	<tr>
            <td><?php echo($i++);?></td>
            <td> <?php echo $d->description; ?> </td>
<!--            <td><?php //echo (@$d->status == 1 ? ('Unread') : 'Read'); ?></td>-->
            <td><?php echo($received_time[$d->id]); ?></td>
        </tr>
    <?php } ?>
</table>