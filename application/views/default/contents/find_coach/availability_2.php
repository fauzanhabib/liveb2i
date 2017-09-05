<?php
if (!@$availability) {
    echo('Not Available');
} else {
    ?>
    <table>
        <thead>
        <th colspan="3"><?php echo(date('l jS \of F Y', @$date)); ?></th>
    </thead>
    <thead>
        <tr>
            <th>Start Time</th>
            <th colspan="2">End Time</th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < count(@$availability); $i++) {
            ?>
            <tr>
                <td><?php echo(date('H:i',strtotime(@$availability[$i]['start_time']))); ?></td>
                <td><?php echo(date('H:i',strtotime(@$availability[$i]['end_time']))); ?></td>
                <td><a href="<?php echo site_url('student/find_coaches/summary_book/'.$search_by.'/' . $coach_id . '/' . $date . '/' . @$availability[$i]['start_time'] . '/' . @$availability[$i]['end_time']); ?>" class="pure-button frm-xsmall btn-secondary" style="margin:0">Book</a></td>
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