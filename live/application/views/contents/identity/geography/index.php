<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo('<br>'.$title.'<br><br>');
?>
<table>
    <tr>
        <td>Country</td>
        <td>:</td>
        <td><?php echo @$data[0]->country;?></td>
    </tr>
    <tr>
        <td>State</td>
        <td>:</td>
        <td><?php echo @$data[0]->state;?></td>
    </tr>
    <tr>
        <td>city</td>
        <td>:</td>
        <td><?php echo @$data[0]->city;?></td>
    </tr>
    <tr>
        <td>Zip</td>
        <td>:</td>
        <td><?php echo @$data[0]->zip;?></td>
    </tr>

    <tr>
        <td><a href="<?php echo site_url('account/identity/edit/geography/'.@$data[0]->id); ?>">Edit</a></td>
    </tr>
</table>