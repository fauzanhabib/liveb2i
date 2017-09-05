<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo("<br>Student Profile Detail<br><br>");
?>
<table>
    <tr>
        <td>Profile Picture</td>
        <td>:</td>
        <td><?php echo @$data[0]->profile_picture;?></td>
    </tr>
    <tr>
        <td>Name</td>
        <td>:</td>
        <td><?php echo @$data[0]->fullname;?></td>
    </tr>
    <tr>
        <td>Email</td>
        <td>:</td>
        <td><?php echo @$data[0]->email;?></td>
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
        <td>Skype ID</td>
        <td>:</td>
        <td><?php echo @$data[0]->skype_id;?></td>
    </tr>
    <tr>
        <td>Dyned Pro ID</td>
        <td>:</td>
        <td><?php echo @$data[0]->dyned_pro_id;?></td>
    </tr>
<!--    <tr>
        <td>Partner</td>
        <td>:</td>
        <td><?php //echo @$data[0]->partner_id;?></td>
    </tr>-->
    
</table>