<a href="<?php echo site_url('account/identity/'); ?>">Back</a>

<form name="schedul_emeeting"  id="schedule_meeting" method="POST" action="https://<?php echo @$webex->subdomain_webex;?>.webex.com/<?php echo @$webex->subdomain_webex;?>/m.php">
    <?php $date = explode('-', @$meeting->date);
    $time = explode(':', @$meeting->start_time);?>
    
    <input type="hidden" name="MN" value="Scheduling Meeting">
    <input type="hidden" name="MT" value="<?php echo @$meeting_type;?>">
    <input type="hidden" name="NA" value="<?php echo @$number_attendance;?>">
    <input type="hidden" name="YE" value="<?php echo $date[0] ?>">
    <input type="hidden" name="MO" value="<?php echo $date[1] ?>">
    <input type="hidden" name="DA" value="<?php echo $date[2] ?>">
    <input type="hidden" name="HO" value="<?php echo $time[0] ?>">
    <input type="hidden" name="MI" value="<?php echo $time[1] ?>">
    <input type="hidden" name="PW" value="<?php echo @$password ?>">
    <input type="hidden" name="TZ" value="<?php echo @$webex->timezone;?>">
    <input type="hidden" name="AutoDeleteAfterMeeting" value="on">
    <input type="hidden" name="BM" value="1">
    <input type="hidden" name="RemiderTime" value="1">
    <input type="hidden" name="CE" value="1">
    <input type="hidden" name="DU" value="10">
    <input type="hidden" name="TC" value="5">
    <input type="hidden" name="AT" value="SM">
    <input type="hidden" name="BU" tabindex="14" value="<?php echo site_url('webex/schedule_meeting').'/'.@$host_id.'/'.@$meeting_identifier .'/';?>">        
</form>  

<script type="text/javascript">
    document.getElementById("schedule_meeting").submit();
</script>