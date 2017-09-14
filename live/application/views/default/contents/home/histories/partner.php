<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<br>
<div>
    <table border="1">
        <tr>
            <th>Affiliate</th>
            <th>Address</th>
        </tr>
        <?php foreach (@$partners as $partner) { ?>
            <tr>
                <td><a href="coach/<?php echo $partner->id?>" ><?php echo($partner->name); ?></a></td>
                <td><?php echo($partner->address); ?></td>
            </tr>
        <?php } ?>        
    </table>
</div>