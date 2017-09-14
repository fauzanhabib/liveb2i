<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
echo("<br> Available Host <br><br>");
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Host Name</th>
        <th>Action</th>
    </tr>
    <?php $i = 1;
    foreach ($available_host as $host) { ?>
        <tr>
            <td><?php echo($i++); ?></td>
            <td><?php echo $host->webex_id; ?></td>
            <td> <a href="<?php echo site_url('webex/schedule_meeting').'/'.@$host->id.'/'.@$appointment_id;?>"> Use </a> </td>
        </tr>
        <?php
    }
    ?> 
</table>

<script>
    $(function () {
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: new Date()
        });
    });
</script>