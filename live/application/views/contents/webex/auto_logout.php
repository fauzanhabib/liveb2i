<?php 
if(@$action == 'inviting'){
    $back_url = site_url('webex/logout_after_inviting');
}elseif(@$action == 'hosting'){
    $back_url = site_url('webex/logout_after_hosting');
}
?>

<div>
    <form name="logout" METHOD="POST" id ="form_auto_logout" action="https://<?php echo @$webex_host->subdomain_webex?>.webex.com/<?php echo @$webex_host->subdomain_webex?>/p.php">
        <input type="hidden" name="AT" value="LO">
        <input type="hidden" name="BU" value="<?php echo $back_url;?>">
    </form>
</div>

<script type="text/javascript">
    document.getElementById("form_auto_logout").submit();
</script> 