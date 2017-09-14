<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
echo("<br> Upcoming Session <br><br>");
?>

<?php echo form_open('coach/upcoming_session/search/', 'role="form"'); ?>
<div>    
    Filter : From <input name ="date_from" type="text" class="datepicker"> To <input name="date_to" type="text" class="datepicker">
    <?php echo form_submit('__submit', 'Go'); ?>
</div>
<br>
<?php echo form_close(); ?>

<?php
echo ((@$start_date || @$end_date) ? ('from ' . @$start_date . ' to ' . @$end_date) : '');
?>
<table border="1">
    <tr>
        <th>No</th>
        <th><?php echo(''); ?></th>
        <th>Date</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>WebEx</th>
    </tr>
    <?php
    $i = 1;if($data){
        foreach ($data as $d) {
            $link = site_url('webex/available_host/' . @$d->id);
            ?>
            <tr>
                <td><?php echo($i++); ?></td>
                <td> <a href="<?php echo site_url('coach/upcoming_session/student_detail/' . $d->student_id); ?>"><?php echo @$id_to_name[@$d->student_id]; ?></a> </td>
                <td><?php echo(@$d->date); ?></td>
                <td><?php echo(@$d->start_time); ?></td>
                <td><?php echo(@$d->end_time); ?></td>
                <td><?php echo (@$d->webex_status == "SCHE") ? "SCHEDULED" : "<a href='$link'>SETUP SCHEDULE</a>" ?></td>
            </tr>
            <?php
        }
    }
    ?>
    <br>    
    <?php
    $j = 1;
    if($data_class){
        foreach ($data_class as $d) {
            $link = site_url('webex/available_host/c' . @$d->id);
            ?>
            <tr>
                <td><?php echo($j++); ?></td>
                <td> <?php echo @$d->id; ?> </td>
                <td><?php echo(@$d->date); ?></td>
                <td><?php echo(@$d->start_time); ?></td>
                <td><?php echo(@$d->end_time); ?></td>
                <td><?php echo(@$d->webex_status == "SCHE") ? "SCHEDULED" : "<a href='$link'>SETUP SCHEDULE</a>"?></td>
            </tr>
            <?php
        }
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