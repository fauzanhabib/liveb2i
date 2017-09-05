<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo('<br>'.$title.'<br><br>');
?>
<table>
    <tr>
        <td>Token Amount</td>
        <td>:</td>
        <td><?php echo @$data[0]->token_amount;?></td>
    </tr>
</table>