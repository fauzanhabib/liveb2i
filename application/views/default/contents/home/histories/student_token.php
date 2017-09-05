<table id="tab2" class="table-session">
    <thead>
        <tr>
            <th>TRANSACTION</th>
            <th>COACH</th>
            <th>SESSION DATE</th>
            <th>START TIME</th>
            <th>END TIME</th>
            <th>TOKEN</th>
            <th>STATE</th>
            <th>BALANCE</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach (@$histories as $history) { ?>
            <tr>
                <td class="session"><?php echo date("F, j Y  H:i:s", $history->dupd); ?></td>
                <td class="coach"><a href="#"><?php echo($history->coach_fullname); ?></a></td>
                <td class="session"><?php echo date('F, j Y', strtotime($history->date)); ?></td>
                <td class="session"><?php echo($history->start_time); ?></td>
                <td class="session"><?php echo($history->end_time); ?></td>
                <td class="session"><?php echo($history->cost); ?></td>
                <td class="session"><?php echo($history->status); ?></td>
                <td class="session"><?php echo($history->balance); ?></td>    
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php exit; ?>