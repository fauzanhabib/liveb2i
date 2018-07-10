<h3>Manage Student's Appointments</h3>
<h4>Student's Name : <?php echo @$appointments[0]->student_fullname; ?></h4>
<br>
<div>
    <a href="<?php echo site_url('partner_monitor/schedule/create/' . @$student_id); ?>">Add</a>
</div>
<div>
    <table border="1">
        <tr>
            <th>Coach Name</th>
            <th>Date</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php if ($appointments) {
            foreach (@$appointments as $appointment) {
                ?>
                <tr>
                    <td><?php echo($appointment->coach_fullname); ?></td>
                    <td><?php echo($appointment->date); ?></td>
                    <td><?php echo($appointment->start_time); ?></td>
                    <td><?php echo($appointment->end_time); ?></td>
                    <td><?php echo($appointment->status); ?></td>
                    <td> <a href="<?php echo site_url('partner_monitor/manage_session/reschedule/' . @$student_id . '/' . @$appointment->id); ?>">Reschedule</a> | <a href="<?php echo site_url('partner_monitor/manage_session/cancel/' . @$student_id . '/' . @$appointment->id); ?>" onclick="return confirm('Are you sure?');">Cancel</a> </td>
                </tr>
            <?php }
        } ?>        
    </table>
</div>