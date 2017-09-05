<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo('<br>'.$title.'<br><br>');
?>
<table>
    <tr>
        <td>Teaching Credential</td>
        <td>:</td>
        <td><?php echo @$data[0]->teaching_credential;?></td>
    </tr>
    <tr>
        <td>DynEd Certification Level</td>
        <td>:</td>
        <td><?php echo @$data[0]->dyned_certification_level;?></td>
    </tr>
    <tr>
        <td>Year Experience</td>
        <td>:</td>
        <td><?php echo @$data[0]->year_experience;?></td>
    </tr>
    <tr>
        <td>Special English Skill</td>
        <td>:</td>
        <td><?php echo @$data[0]->special_english_skill;?></td>
    </tr>

    <tr>
        <td><a href="<?php echo site_url('account/identity/edit/education/'.@$data[0]->id); ?>">Edit</a></td>
    </tr>
</table>