<?php
    echo("<br>Coach Profile Detail<br><br>");
?>
<table>
    <tr>
        <td>Profile Picture</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->profile_picture;?></td>
    </tr>
    <tr>
        <td>Name</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->fullname;?></td>
    </tr>
    <tr>
        <td>Email</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->email;?></td>
    </tr>
    <tr>
        <td>Gender</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->gender;?></td>
    </tr>
    <tr>
        <td>Date of Birth</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->date_of_birth;?></td>
    </tr>
    <tr>
        <td>Phone Number</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->phone;?></td>
    </tr>
    <tr>
        <td>Skype ID</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->skype_id;?></td>
    </tr>
    <tr>
        <td>Dyned Pro ID</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->dyned_pro_id;?></td>
    </tr>
    <tr>
        <td>Affiliate</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->partner_id;?></td>
    </tr>
    <tr>
        <td>Teaching Credential</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->teaching_credential;?></td>
    </tr>
    <tr>
        <td>DynEd Certification Level</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->dyned_certification_level;?></td>
    </tr>
    <tr>
        <td>Year Experience</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->year_experience;?></td>
    </tr>
    <tr>
        <td>Special English Skill</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->special_english_skill;?></td>
    </tr>
    <tr>
        <td>City</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->city;?></td>
    </tr>
    <tr>
        <td>State</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->state;?></td>
    </tr>
    <tr>
        <td>ZIP</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->zip;?></td>
    </tr>
    <tr>
        <td>Country</td>
        <td>:</td>
        <td><?php echo @$coaches[0]->country;?></td>
    </tr>
</table>

<?php echo anchor(base_url().'index.php/student/find_coaches/schedule_detail/'.@$coaches[0]->id, 'Detail Schedule');?>