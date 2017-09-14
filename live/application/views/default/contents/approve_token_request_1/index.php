<table border="1">
    <tr>
        <th>No</th>
        <th>Name</th>
        <th>Token Request</th>
        
        <th>Action</th>
    </tr>
    <?php $i =1; foreach(@$data as $d){ ?>
	<tr>
            <td><?php echo($i++);?></td>
            <td><?php echo $d->fullname; ?></td>
            <td><?php echo $d->token_amount; ?></a> </td>
            <td> <a href="<?php echo site_url('partner/approve_token_requests/approve/'.@$d->id ); ?>" onclick=" return confirm('Approve User?');"> Approve </a> | <a href="<?php echo site_url('partner/approve_token_requests/decline/'.@$d->id ); ?>" onclick=" return confirm('Decline User?');"> Decline </a></td>
        </tr>
    <?php } ?>
</table>
