<form name="HostMeetingForm" id="host_meeting" ACTION = "https://<?php echo @$webex_host->subdomain_webex;?>.webex.com/<?php echo @$webex_host->subdomain_webex;?>/m.php" METHOD = "POST">
    <INPUT TYPE="HIDDEN" NAME="AT" VALUE="HM">
    <INPUT TYPE="HIDDEN" NAME="MK" VALUE="<?php echo @$webex->webex_meeting_number; ?>">
    <INPUT TYPE="HIDDEN" NAME="BU" VALUE = "<?php echo site_url('webex/host_meeting').'/'.@$host_id.'/'.@$session.@$meeting_identifier;?>">
</form>

<script type="text/javascript">
    document.getElementById("host_meeting").submit();
</script> 