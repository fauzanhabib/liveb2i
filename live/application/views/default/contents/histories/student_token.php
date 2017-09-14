<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<br>
<div>
    <?php echo form_open('student/histories/' . $form_action, 'role="form"'); ?>
    <div>    
        Filter : From <input name ="date_from" type="text" class="datepicker"> To <input name="date_to" type="text" class="datepicker">
        <?php echo form_submit('__submit', 'Go'); ?>
    </div>
    <br>
    <?php echo form_close(); ?>
    <table border="1">
        <tr>
            <th>Date of Transaction</th>
            <th>Session</th>
            <th>Token</th>
            <th>State</th>            
            <th>Balance</th>
        </tr>
        <?php foreach (@$histories as $history) { ?>
            <tr>
                <td><?php echo($history->dupd); ?></td>
                <td><?php echo("Session with " . $history->coach_fullname ." on " . $history->date . " at " . $history->start_time . " until " . $history->end_time); ?></td>
                <td><?php echo($history->cost); ?></td>
                <td><?php echo($history->status); ?></td>
                <td><?php echo($history->balance); ?></td>    
            </tr>
        <?php } ?>        
    </table>
</div>

<script>
    $(function () {
        var now = new Date();
        past = new Date(now.getFullYear(), now.getMonth() - 1, 1);
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd', 
            maxDate: new Date, 
            minDate: past
        });
    });
</script>