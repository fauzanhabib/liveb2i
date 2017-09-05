<div class="heading text-cl-primary padding15">

    <h1 class="margin0">Token Request Approval</h1>
</div>

<div class="box">
    <div class="heading pure-g">

<div class="left-list-tabs pure-menu pure-menu-horizontal">
            <ul class="pure-menu-list m-l-20">
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('superadmin/manage_partner/token');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 active-tabs-blue">Token Requests</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('superadmin/manage_partner/history_token');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">Token Histories</a></li>
            </ul>
        </div>

    </div>

    <div class="content">
        <div class="box">
        <script>
            $(document).ready(function() {
                $('#userTable').DataTable( {
                  "bLengthChange": false,
                  "searching": false,
                  "userTable": false,
                  "bInfo" : false
                });
            } );
        </script>

            <?php
                if(!@$data){
                    ?>  
                    <div class="padding15">    
                        <div class="no-result">
                            No Data
                        </div>
                    </div>

                    <?php
                }
                else{
            ?>

        <table id="userTable" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="bg-secondary text-cl-white border-none">Regions</th>
                    <th class="bg-secondary text-cl-white border-none">Token Amount</th>
                    <th class="bg-secondary text-cl-white border-none">Action</th>               
                </tr>
            </thead>

            <tbody>
                <?php $i =1; foreach(@$data as $d){ ?>
                <tr class="request">
                    <td><?php echo $d->region_id; ?></td>
                    <td>
                        <div class="status-disable m-l-20">
                            <span class="status-disable bg-grey-ddd text-cl-white"><?php echo $d->token_amount; ?></span>
                        </div>
                    </td>

                    <td>
                        <div class="blue-red-btn">
                            <div class="pure-button btn-blue btn-small">
                                <a class="approve-token" onclick="confirmation('<?php echo site_url('superadmin/manage_partner/approve_token/'.@$d->id ); ?>', 'group', 'Approve Token Request', 'request', 'approve-token');"> APPROVE </a>
                            </div>
                            <div class="pure-button btn-red btn-small">
                                <a onclick="confirmation('<?php echo site_url('superadmin/manage_partner/decline_token/'.@$d->id ); ?>', 'group', 'Decline Token Request', 'request', 'decline-token');" class="decline-token"> DECLINE </a>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php } ?>

            </tbody>
        </table>

        <?php } ?>
        </div>
    </div>  
</div>

<script type="text/javascript">
    $(function () {
        $('.approve-token').click(function(){
            return false;
        })
        $('.decline-token').click(function(){
            return false;
        })
    })
</script>