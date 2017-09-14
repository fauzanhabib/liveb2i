<div class="heading text-cl-primary padding15">
    <h1 class="margin0">WebEx User</h1>
</div>
<div class="box">
    <div class="heading pure-g">
        <!-- block edit -->
        <div class="pure-u-1 edit tab-list tab-link">
            <ul>
                <li class="current"><a href="<?php echo site_url('partner/webex/list_host');?>" class="active">List of WebEx Users</a></li>
                <li><a href="<?php echo site_url('partner/webex/login');?>" >Record WebEx Account</a></li>
            </ul>    
        </div>
        <!-- end block edit -->
    </div>
    <div class="content tab-content" >
        <div id="tab1" class="tab active" style="margin-top: -18px">
            <?php
            if (@$webex_host == null) {
               echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
            }
            else {
            ?>
            <div class="b-pad">
            <table class="table-session margint15"> 
                <thead>
                    <tr>
                        <th>SUBDOMAIN WEBEX</th>
                        <th>WEBEX ID</th>
                        <th>ACTION</th>
                    </tr>

                </thead>
                <?php foreach(@$webex_host as $host){?>
                <tbody>
                    <tr>
                        <td data-label="SUBDOMAIN WEBEX"><?php echo @$host->subdomain_webex ?></td>
                        <td data-label="WEBEX ID"><?php echo @$host->webex_id ?></td>
                        <td class="list_webex t-center">
                            <a onclick=" return confirmation('<?php echo site_url('partner/webex/delete_host/'.@$host->id);?>', 'group', 'Delete WeBex Host', 'list_webex', 'delete_host');" class="delete_host pure-button btn-small btn-white"> DELETE </a>
                        </td>
                    </tr>
                </tbody>
                <?php }?>
            </table>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php echo @$pagination;?>
<script type="text/javascript">
    $(".timezones").prop("disabled", true);
    $(function(){
        windowsize = $(window).width();
        if(windowsize < 720){
            $('.tab-list ul').css("width","200px")
        }
        
        $('.delete_host').click(function(){
           return false; 
        });
    });  
</script>