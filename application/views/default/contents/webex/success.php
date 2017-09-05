<form name="HostMeetingForm" ACTION = "https://<?php echo @$site_url?>.webex.com/<?php echo @$site_url?>/m.php" METHOD = "POST">
    <INPUT TYPE="HIDDEN" NAME="AT" VALUE="HM">
    <INPUT TYPE="HIDDEN" NAME="MK" VALUE="<?php echo @$meeting_key?>">
    <INPUT TYPE="HIDDEN" NAME="BU" VALUE = "http://idbuild.id.dyned.com/live/index.php/webex/">
    <INPUT TYPE="submit" name="btnHostMeeting" value = "Start">
</form>