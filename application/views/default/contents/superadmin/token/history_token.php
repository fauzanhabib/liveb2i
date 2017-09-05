<div class="heading text-cl-primary padding15">

    <h1 class="margin0">Token History</h1>
</div>

<div class="box">
    <div class="heading pure-g">

        <div class="left-list-tabs pure-menu pure-menu-horizontal">
            <ul class="pure-menu-list m-l-20">
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('superadmin/manage_partner/token');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">Token Requests</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('superadmin/manage_partner/history_token');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 active-tabs-blue">Token Histories</a></li>
            </ul>
        </div>

    </div>

    <div class="content">
        <div class="box">

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


        <table id="userTable" class="display table-session" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="bg-secondary text-cl-white border-none text-center">Regions</th>
                    <th class="bg-secondary text-cl-white border-none text-center">Token Request</th>
                    <th class="bg-secondary text-cl-white border-none text-center">Status</th>               
                    <th class="bg-secondary text-cl-white border-none text-center">Create</th>               
                    <th class="bg-secondary text-cl-white border-none text-center">Update</th>           
                </tr>
            </thead>

            <tbody>
                <?php $i =1; foreach(@$data as $d){ 
                    $userid = $this->auth_manager->userid();
                    $gmt_user = $this->identity_model->new_get_gmt($userid);
                    $status = ['approved' => 'Book', 'cancelled' => 'Resc', 'declined' => 'Deli','added' => 'added','given' => 'added']; //ditambah
                    $tooltip = ['approved' => 'Token Request has been approved', 'cancelled' => 'Token Requests has been cancelled', 'declined' => 'Token Request has been declined', 'requested' => 'Token has been requested', 'added' => 'Token has been added', 'given' => 'Token has been given'];
                    
                        date_default_timezone_set('UTC');
                        $dcrea     = date('H:i:s',$d->dcrea);
                        $default_dcrea  = strtotime($dcrea);
                        $usertime_dcrea = $default_dcrea+(60*@$gmt_user[0]->minutes);
                        $hour_dcrea = date("H:i:s", $usertime_dcrea); 


                        $date_dcrea     = date('Y-m-d',$d->dcrea);
                 
                       

                        $dupd     = date('H:i:s',$d->dupd);
                        $default_dupd  = strtotime($dupd);
                        $usertime_dupd = $default_dupd+(60*@$gmt_user[0]->minutes);
                        $hour_dupd = date("H:i:s", $usertime_dupd); 


                        $date_dupd     = date('Y-m-d',$d->dupd);
                 
                        

                    ?>
                <tr class="request text-center">
                    <td><?php echo $d->region_id; ?></td>
                    <td>
                        <div class="status-disable bg-grey-ddd">
                            <span class="text-cl-white"><?php echo $d->token_amount; ?></span>
                        </div>
                    </td>

                    <td>
                            <span class="labels <?php echo $status[$d->status];?> tooltip-bottom" data-tooltip="<?php echo $tooltip[$d->status];?>" style="width:75px"><?php echo($d->status); ?></span>    
                    </td>
                    <td><?php echo $date_dcrea." ".$hour_dcrea; ?></td>
                    <td><?php echo $date_dupd." ".$hour_dupd; ?></td>


                </tr>
                <?php } ?>

            </tbody>
        </table>

        <?php } ?>
        </div>
    </div>  
</div>

<script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/js/remodal.min.js"></script>

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