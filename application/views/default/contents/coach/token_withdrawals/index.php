<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Token Withdrawals</h1>
</div>
<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-1 tab-list tab-link">
            <ul>
                <li><a href="<?php echo site_url('coach/token'); ?>" >Token History</a></li>
                <li class="current"><a href="<?php echo site_url('coach/token_withdrawals'); ?>" class="active">Token Withdrawal</a></li>
            </ul>
        </div>
    </div>
    <div class="content tab-content padding0">
        <div id="tab1" class="tab active">
            <?php
            if(!@$balance){
                echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
            }
            else {
            ?>
            <div class="b-pad">
            <table id="tab2" class="table-session">
                <thead>
                    <tr>
                        <th class="padding15">BALANCE</th>
                        <th class="padding15">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="padding15" data-label="BALANCE">
                        	<span><?php echo($balance->token_amount); ?></span>
                        </td>
                        <td class="padding15" data-label="ACTION">
                            <a href="#" class="pure-button btn-small btn-white" data-toggle="modal" data-target="#divModal"> Withdraw Tokens </a>
                        </td>
                           
                    </tr>
                </tbody>
            </table>
            </div>
            <?php } ?>
            <?php echo @$pagination;?>
            <div class="height-plus"></div>
        </div>
    </div>
</div>

<div class="modal hide fade" id="divModal" tabindex="-1" role="dialog" aria-labelledby="divModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon icon-close"></i></button>
                <h3 class="modal-title text-cl-primary" id="myModalLabel">Withdraw Tokens</h3>
            </div>
            <div class="modal-body">
                <p>This feature is not available yet. Since we need to convert Tokens to Real Money</p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
     $(document).ready(function() {

        $('#divModal').appendTo("body");
        $('#divModal').removeClass("hide");
        
    })
</script>