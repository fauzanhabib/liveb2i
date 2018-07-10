<?php
if (!@$availability) {
    echo('Not Available');
} else {
    ?>
    <div class="selected-date"><?php echo(date('l jS \of F Y', @$date)); ?></div>
    <table class="tbl-booking">
        </thead>
            <tr>
                <th class="text-center">START TIME</th>
                <th class="text-center">END TIME</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($i = 0; $i < count(@$availability); $i++) {
                ?>
                <tr>
                    <td class="text-center"><?php echo(date('H:i',strtotime(@$availability[$i]['start_time']))); ?></td>
                    <td class="text-center"><?php echo(date('H:i',strtotime(@$availability[$i]['end_time']))); ?></td>
                    <td>
                        <a href="<?php echo site_url('student/find_coaches/summary_book/'.$search_by.'/' . $coach_id . '/' . $date . '/' . @$availability[$i]['start_time'] . '/' . @$availability[$i]['end_time']); ?>" class="pure-button btn-small btn-white">Book</a>
                    </td>
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