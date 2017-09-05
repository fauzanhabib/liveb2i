<?php
if (!@$availability) {
    echo('Not Available');
} else {
    ?>
<table class="table-reschedule">
    <thead>
        <tr>
            <th class="radio"></th>
            <th class="text-center time">Start Time</th>
            <th class="text-center endtime">End time</th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count(@$availability); $i++) {
            ?>

            <tr>
                <td class="text-center"><input type="radio" name="radion" value="<?php echo(@$availability[$i]['start_time'].','.@$availability[$i]['end_time']); ?>"></td>
                <td class="text-center"><?php echo(date('H:i',strtotime(@$availability[$i]['start_time']))); ?></td>
                <td class="text-center"><?php echo(date('H:i',strtotime(@$availability[$i]['end_time']))); ?></td>
            </tr>

        <?php } ?>

    </tbody>
</table>
<?php 
}
exit; ?>