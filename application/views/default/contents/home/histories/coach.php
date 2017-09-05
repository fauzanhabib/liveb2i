<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<br>
<div>
    <table border="1">
        <tr>
            <th>Coach</th>
        </tr>
        <?php foreach (@$coaches as $coach) { ?>
            <tr>
                <td><a href="<?php echo site_url()?>/admin/histories/session/<?php echo $coach->id?>"><?php echo($coach->fullname); ?></a></td>
            </tr>
        <?php } ?>        
    </table>
</div>