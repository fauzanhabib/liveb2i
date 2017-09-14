<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php echo form_open('account/identity/'.$form_action, 'role="form"'); 
    echo('<br>'.$title.'<br><br>');
?>
    <table>
        <tr>
            <td>Country</td>
            <td>:</td>
            <td><?php echo form_input('country', set_value('country', @$data->country), 'id="country"') ?></td>
        </tr>
        <tr>
            <td>State</td>
            <td>:</td>
            <td><?php echo form_input('state', set_value('state', @$data->state), 'id="state"') ?></td>
        </tr>
        <tr>
            <td>City</td>
            <td>:</td>
            <td><?php echo form_input('city', set_value('city', @$data->city), 'id="city"') ?></td>
        </tr>
        <tr>
            <td>Zip</td>
            <td>:</td>
            <td><?php echo form_input('zip', set_value('zip', @$data->zip), 'id="zip"') ?></td>
        </tr>
        <tr>
            <td><?php echo form_submit('__submit', @$data->id ? 'Update' : 'Submit'); ?></td>
        </tr>
    </table>
<?php echo form_close(); ?>