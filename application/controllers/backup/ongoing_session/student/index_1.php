<script language="JavaScript">
    TargetDate = "<?php echo date('m/d/Y', strtotime(@$data->date)) . ' ' . @$data->end_time; ?>"; //"12/31/2020 15:00:00";
    BackColor = "palegreen";
    ForeColor = "navy";
    CountActive = true;
    CountStepper = -1;
    LeadingZero = true;
    DisplayFormat = "%%D%% Days, %%H%% Hours, %%M%% Minutes, %%S%% Seconds.";
    FinishMessage = "Session has been done";
</script>
<script language="JavaScript" src="http://scripts.hashemian.com/js/countdown.js"></script>
<br>
<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
echo("<br> Ongoing Session <br><br>");
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Skype Call</th>
        <th>WebEx Call</th>
        <th>Coach</th>
        <th>Date</th>
        <th>Start Time</th>
        <th>End Time</th>
    </tr>
    <?php $i = 1; ?>
    <tr>
        <td><?php echo($i++); ?></td>
        <td>
            <div id="SkypeButton_Call_ponel_apache_1">
                <script type="text/javascript">
                   Skype.ui({
                       "name": "dropdown",
                       "element": "SkypeButton_Call_ponel_apache_1",
                       "participants": ["<?php echo(@$coach_name->skype_id);?>"],
                       "imageSize": 20
                   });
                </script>
            </div>
        </td>
        <td>
            <form name="joinmeeting" ACTION = "https://<?php echo @$site_url?>.webex.com/<?php echo @$site_url?>/w.php" METHOD = "POST">
                <INPUT TYPE="HIDDEN" NAME="AT" VALUE="JO">
                <INPUT TYPE="HIDDEN" NAME="MK" VALUE="<?php echo @$data[0]->webex_meeting_number;?>">
                <INPUT TYPE="submit" name="B1" value = "WebEx" <?php echo (@$data[0]->webex_meeting_number)? '' : 'disabled' ?> >
            </form>
        </td>
        <td> <a href="<?php echo site_url('student/upcoming_session/coach_detail/' . @$data[0]->coach_id); ?>"><?php echo @$coach_name->fullname; ?></a> </td>
        <td><?php echo(@$data[0]->date); ?></td>
        <td><?php echo(@$data[0]->start_time); ?></td>
        <td><?php echo(@$data[0]->end_time); ?></td>

    </tr>

</table>

<script>
    $(function () {
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: new Date()
        });
    });
</script>