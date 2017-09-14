<div>
    <form name="login" METHOD="POST" id="form_login" action="https://apidemoeu.webex.com/apidemoeu/p.php">
        <input type="hidden" name="SDW" value="<?php echo @$SDW?>">
        <input type="hidden" name="WID" value="<?php echo @$WID?>">
        <input type="hidden" name="PW" value="<?php echo @$PW?>">
        <input type="hidden" name="SiteID" value="<?php echo @$SiteID?>">
        <input type="hidden" name="SiteName" value="<?php echo @$SiteName?>">
        <input type="hidden" name="MU" value="GoBack">
        <input type="hidden" name="AT" value="LI">
        <input type="hidden" name="BU" value="http://idbuild.id.dyned.com/live/index.php/partner/webex/login/">
    </form>
</div>

<script type="text/javascript">
    document.getElementById("form_login").submit();
</script>
