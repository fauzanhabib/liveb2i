<?php
if(@$action == 'HM'){
    $back_url = site_url('webex/host_meeting').'/'.@$host_id.'/'.$meeting_identifier;
}else if(@$action == 'SM'){
    $back_url = site_url('webex/schedule_meeting').'/'.@$host_id.'/'.$meeting_identifier;
}else if(@$action == 'KM'){
    if($this->session->userdata('is_safe_to_start_new_session') == 'TRUE'){
        $back_url = site_url('webex/host_session').'/'.@$host_id.'/'.$meeting_identifier;
    }else{
        $back_url = site_url('webex/delete_session').'/'.@$host_id.'/'.$meeting_identifier;
    }
}  
?>

<div>
    <form name="login" METHOD="POST" id ="form_auto_login" action="https://<?php echo @$webex_host->subdomain_webex;?>.webex.com/<?php echo @$webex_host->subdomain_webex;?>/p.php">
        <input type="hidden" name="WID" value="<?php echo @$account_host->webex_id?>">
        <input type="hidden" name="PW" value="<?php echo @$account_host->password?>">
        <input type="hidden" name="MU" value="GoBack">
        <input type="hidden" name="AT" value="LI">
        <input type="hidden" name="BU" value="<?php echo $back_url;?>">
    </form> 
</div> 

<script type="text/javascript">
    document.getElementById("form_auto_login").submit();
</script> 