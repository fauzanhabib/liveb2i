<?php
//print_r('test'); exit;
if (!@$availability) {
    echo('Not Available');
} else {
    ?>
    <div class="selected-date"><?php echo(date('l, F d, Y', @$date)); ?></div>
    <table class="tbl-booking">
        <thead>
            <tr>
                <th class="text-center">START TIME</th>
                <th class="text-center">END TIME</th>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($i = 0; $i < count(@$availability); $i++) {
                ?>
                <tr>
                    <td class="text-center"><?php echo(date('H:i',strtotime(@$availability[$i]['start_time']))); ?></td>
                    <td class="text-center"><?php echo(date('H:i',strtotime(@$availability[$i]['end_time']))); ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
}
exit;
?>