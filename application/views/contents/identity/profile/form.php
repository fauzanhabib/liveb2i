<?php
echo form_open_multipart('account/identity/' . $form_action, 'role="form"');
echo('<br>' . $title . '<br><br>');
?>
<img src="<?php echo base_url() . @$data->profile_picture; ?> " width="150" height="200" id="photo"/>
<input type="file" name="profile_picture">
<table>
    <tr>
        <td>Full Name</td>
        <td>:</td>
        <td>
            <?php echo form_error('fullname', '<small class="pull-right req">', '</small>') ?>
<?php echo form_input('fullname', set_value('fullname', @$data->fullname), 'id="fullname"') ?>
        </td>
    </tr>
    <tr>
        <td>Nickname</td>
        <td>:</td>
        <td>
<?php echo form_input('nickname', set_value('nickname', @$data->nickname), 'id="nickname"') ?>
        </td>
    </tr>
    <tr>
        <td>gender</td>
        <td>:</td>
        <td><?php echo form_dropdown('gender', $this->auth_manager->gender(), trim(@$data->gender), 'id="sex"') ?></td>
    </tr>
    <tr>
        <td>Date of Birth</td>
        <td>:</td>
        <td><?php echo form_input('date_of_birth', set_value('date_of_birth', @$data->date_of_birth), 'id="date_of_birth" class="datepicker"') ?></td>
    </tr>
    <tr>
        <td>Phone Number</td>
        <td>:</td>
        <td><?php echo form_input('phone', set_value('phone', @$data->phone), 'id="phone"') ?></td>
    </tr>

    <tr>
        <td>Skype Account</td>
        <td>:</td>
        <td><?php echo form_input('skype_id', set_value('skype_id', @$data->skype_id), 'id="skype_id"') ?></td>
    </tr>

    <tr>
        <td>Dyned Pro ID</td>
        <td>:</td>
        <td><?php echo form_input('dyned_pro_id', set_value('dyned_pro_id', @$data->dyned_pro_id), 'id="dyned_pro_id"') ?></td>
    </tr>
    <tr>
        <td><?php echo form_submit('__submit', @$data->id ? 'Update' : 'Submit'); ?></td>
    </tr>
</table>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(function () {
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: '-100:+0',
            defaultDate: new Date(1990, 00, 01)
        });
    });
    $(document).ready(function () {
        $('#photo').imgAreaSelect({
            handles: true,
            aspectRatio: '3:4'
            //onSelectEnd: someFunction
        });
    });
</script>