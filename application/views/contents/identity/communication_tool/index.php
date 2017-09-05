<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo('<br>'.$title.'<br><br>');
?>
<table border="1">
    <tr>
        <th>No</th>
        <th>Communication Tools</th>
        <th>Account ID</th>
    </tr>
    <?php $i =1; foreach($data as $d){ ?>
	<tr>
            <td><?php echo($i++);?></td>
            <td> <?php echo $id_to_name[$d->communication_tool_id]; ?> </td>
            <td><?php echo $d->account_id; ?></td>
        </tr>
    <?php } ?>
</table>