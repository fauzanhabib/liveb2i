<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php echo form_open('account/identity/'.$form_action, 'role="form"'); 
    echo('<br>'.$title.'<br><br>');
?>
    <table>
    <tr>
        <td>Teaching Credential</td>
        <td>:</td>
        <td>
            <?php echo form_error('teaching_credential', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('teaching_credential', set_value('teaching_credential', @$data->teaching_credential), 'id="teaching_credential"') ?>
        </td>
    </tr>
    <tr>
        <td>DynEd Certification Level</td>
        <td>:</td>
        <td><?php echo form_input('dyned_certification_level', set_value('dyned_certification_level', @$data->dyned_certification_level), 'id="dyned_certification_level"') ?></td>
    </tr>
    <tr>
        <td>Year Experience</td>
        <td>:</td>
        <td>
            <?php echo form_error('year_experience', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('year_experience', set_value('year_experience', @$data->year_experience), 'id="year_experience"') ?>
        </td>
    </tr>
    <tr>
        <td>Special English Skill</td>
        <td>:</td>
        <td>
            <?php echo form_error('special_english_skill', '<small class="pull-right req">', '</small>') ?>
            <?php echo form_input('special_english_skill', set_value('special_english_skill', @$data->special_english_skill), 'id="special_english_skill"') ?>
        </td>
    </tr>
    <tr>
        <td><?php echo form_submit('__submit', @$data->id ? 'Update' : 'Submit'); ?></td>
    </tr>
</table>
<?php echo form_close(); ?>