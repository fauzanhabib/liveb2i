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

            $get_endtime = date('H:i',strtotime(@$availability[$i]['end_time']));

            // $date = date("Y-m-d H:i:s");
            $time = strtotime($get_endtime);
            $time = $time - (5 * 60);
            $endtime = date("H:i", $time);
            ?>

            <tr>
                <td class="text-center">
                    <label class="radio">
                        <input type="radio" name="radion" value="<?php echo(@$availability[$i]['start_time'].','.@$availability[$i]['end_time']); ?>">
                        <span class="outer" style="margin: 0px 12px 0px 2px;"><span class="inner"></span></span>
                    </label>
                </td>
                <td class="text-center"><?php echo(date('H:i',strtotime(@$availability[$i]['start_time']))); ?></td>
                <td class="text-center"><?php echo($endtime); ?></td>
            </tr>

        <?php } ?>

    </tbody>
</table>
<?php 
}
exit; ?>