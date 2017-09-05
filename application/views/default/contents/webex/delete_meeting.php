<form name="DeletingForm" id="delete_meeting" ACTION = "https://<?php echo @$webex_host->subdomain_webex;?>.webex.com/<?php echo @$webex_host->subdomain_webex;?>/w.php" METHOD = "POST">
    <INPUT TYPE="HIDDEN" NAME="AT" VALUE="KM">
    <INPUT TYPE="HIDDEN" NAME="MK" VALUE="<?php echo @$meeting_number_in_progress; ?>">
    <INPUT TYPE="HIDDEN" NAME="BU" VALUE = "<?php echo site_url('webex/delete_session').'/'.@$host_id.'/'.@$meeting_identifier;?>">
</form>

<script type="text/javascript">
    document.getElementById("delete_meeting").submit();
</script> 