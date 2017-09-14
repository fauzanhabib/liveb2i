<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo('<br>'.$title.'<br><br>');
?>
<img src="<?php echo base_url().@$data[0]->profile_picture ; ?>" width="150" height="200"/>
<table>
    <tr>
        <td>Full Name</td>
        <td>:</td>
        <td><?php echo @$data[0]->fullname;?></td>
    </tr>
    <tr>
        <td>Nickname</td>
        <td>:</td>
        <td><?php echo @$data[0]->nickname;?></td>
    </tr>
    <tr>
        <td>Email</td>
        <td>:</td>
        <td><?php echo @$email->email;?></td>
    </tr>
    <tr>
        <td>Gender</td>
        <td>:</td>
        <td><?php echo @$data[0]->gender;?></td>
    </tr>
    <tr>
        <td>Date of Birth</td>
        <td>:</td>
        <td><?php echo @$data[0]->date_of_birth;?></td>
    </tr>
    <tr>
        <td>Phone Number</td>
        <td>:</td>
        <td><?php echo @$data[0]->phone;?></td>
    </tr>
    <tr>
        <td>Skype Account</td>
        <td>:</td>
        <td><?php echo @$data[0]->skype_id;?></td>
    </tr>
    <tr>
        <td>Dyned Pro ID</td>
        <td>:</td>
        <td><?php echo @$data[0]->dyned_pro_id;?></td>
    </tr>

    <tr>
        <td><a href="<?php echo site_url('account/identity/edit/profile/'.@$data[0]->id ); ?>">Edit</a></td>
    </tr>
</table>