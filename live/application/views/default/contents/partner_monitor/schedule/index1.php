<h3>Partner Schedule</h3><br>
<div>
    <table border="1">
        <tr>
            <th>Student Name</th>
            <th>Action</th>
        </tr>
            <?php foreach (@$user_profiles as $user_profile){ ?>
            <tr>
                <td><?php echo($user_profile->fullname); ?></td>
                <td> <a href="<?php echo site_url('partner_monitor/schedule/manage/'.@$user_profile->id);?>">Manage</a> </td>
            </tr>
            <?php } ?>        
    </table>
</div>