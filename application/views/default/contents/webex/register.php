<div>
    <form name="newuser" METHOD="POST" id="form_register" action="https://apidemoeu.webex.com/apidemoeu/p.php">
        <input type="hidden" name="PID" value="<?php echo @$PID?>">
        <input type="hidden" name="FN" value="<?php echo @$FN?>">
        <input type="hidden" name="LN" value="<?php echo @$LN?>">
        <input type="hidden" name="EM" value="<?php echo @$EM?>">
        <input type="hidden" name="WID" value="<?php echo @$WID?>">
        <input type="hidden" name="PW" value="<?php echo @$PW?>">
        <input type="hidden" name="TimeZone" value="<?php echo @$timezone?>">
        <input type="hidden" name="SiteID" value="<?php echo @$SiteID?>">
        <input type="hidden" name="SiteName" value="<?php echo @$SiteName?>">
        <input type="hidden" name="AT" value="SU">
        <input type="hidden" name="BU" value="http://idbuild.id.dyned.com/live/index.php/partner/webex/register/">
    </form>
</div>

<script type="text/javascript">
    document.getElementById("form_register").submit();
</script>
