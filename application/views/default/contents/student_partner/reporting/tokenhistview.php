<div class="heading text-cl-primary border-b-1 padding15">
    <h2 class="margin0"><?php echo $title; ?></h2>
</div>

<div class="box clear-both">
	<div class="content padding-t-10">
        <div class="box">
			<table id="large" class="display table-session tablesorter" cellspacing="0" width="100%" style="margin-top: 10px;">
			    <thead>
			        <tr>
			        	<th rowspan="2" class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">#</th>
			            <th rowspan="2" class="bg-secondary uncek text-cl-white border-none">Booking Date</th>
			            <th rowspan="2" class="bg-secondary uncek text-cl-white border-none">Descroption</th>
			            <th rowspan="2" class="bg-secondary uncek text-cl-white border-none">Status</th>
			            <th colspan="3" class="bg-secondary uncek text-cl-white border-none">Token</th>
			            <!-- <th class="bg-secondary uncek text-cl-white border-none">BALANCE</th>                -->
			        </tr>
			        <tr>
			            <th class="bg-secondary uncek text-cl-white border-none">Used</th>
			            <th class="bg-secondary uncek text-cl-white border-none">Refunded</th>
			            <th class="bg-secondary uncek text-cl-white border-none">Balance</th>
			        </tr>
			    </thead>
			    <tbody>
                    <?php $detcount = 0; foreach (@$histories as $history) { 
                        $gmt_user = $this->identity_model->new_get_gmt($this->auth_manager->userid());
                        $new_gmt = 0;
                            if($gmt_user[0]->gmt > 0){
                                $new_gmt = '+'.$gmt_user[0]->gmt;
                            }else{
                                $new_gmt = $gmt_user[0]->gmt;    
                            }
                        $a = '';
                        if(substr($history->status, 0,4) == 'Refu'){
                            $a = 'Deli';
                        }
                        ?>
                    <tr>
                    	<td></td>
                        <td>
                            <span>
                                <?php 
                                date_default_timezone_set('UTC');
                                $dt     = date('H:i:s',$history->transaction_date);
                                $default_dt  = strtotime($dt);
                                $usertime = $default_dt+(60*$minutes);
                                $hour = date("H:i:s", $usertime); 


                                $date     = date('m-d-Y',$history->transaction_date);
                         		// echo $hour;exit();
                                echo $date." ".$hour;
                                // echo $date;
                                ?>
                            </span>
                        </td>
                        <td style="text-align: left;">
                           <span>
                            <?php  
                                // echo $history->description;
                                $des = explode(" ",$history->description);
                                if($des[0] == 'Session'){
                                $t = count($des);
                                
                                $f_dt_at = $t-3;
                                $f_dt_until = $t-1;
                                if($des[$t-6] == 'on'){
                                    $f_date = $t-5;
                                }else{
                                    $f_date = $t-4;
                                }
                                $CI =& get_instance();
                                $CI->load->library('schedule_function');
                                $convert = $CI->schedule_function->convert_book_schedule(($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes), strtotime($des[$f_date]), $des[$f_dt_at], $des[$f_dt_until]);
                                $date_token = $convert['date'];
                                $dateconvert = date('Y-m-d', $date_token);
                                

                                    $default_dt_at  = strtotime($des[$f_dt_at]);
                                    $usertime_at = $default_dt_at+(60*$minutes);
                                    $at = date("H:i", $usertime_at);
                                    
                                    $default_dt_until  = strtotime($des[$f_dt_until]);
                                    $usertime_until = $default_dt_until+(60*$minutes);
                                    $usertime_until_diff = $usertime_until-(60*5);
                                    $until = date("H:i", $usertime_until_diff);

                                   
                                        $des[$f_dt_at] = $at;
                                        $des[$f_dt_until] = $until;
                                        $des[$f_date] = $dateconvert;
                                   
                                    
                                    for($a=0; $a<count($des); $a++){
                                        echo $des[$a]." ";
                                    } echo '(UTC '.$new_gmt.')';
                                } else {
                                    echo $history->status_description;
                                }

                                // if(@$history->status == 'Refund'){
                                //     echo ' <a id="detail'.$detcount.'" class="detailsbtn">Details</a>';
                                //     $detcount++;
                                // }
                            ?>
                            </span>
                        </td>
                        <td>
                            <?php
                            if($history->status == 'Booked'){
                            ?>
                            <div class="status-disable bg-green m-l-20">
                                <span class="text-cl-white <?php echo $a;?> <?php echo substr($history->status, 0,4)?> tooltip-bottom" data-tooltip="<?php echo($history->status_description); ?>" style="width:75px"><?php echo($history->status); ?></span>
                            </div>
                            <?php }
                            else{
                            ?>
                            <div class="status-disable bg-tertiary m-l-20">
                                <span class="text-cl-white <?php echo $a;?> <?php echo substr($history->status, 0,4)?> tooltip-bottom" data-tooltip="<?php echo($history->status_description); ?>" style="width:75px"><?php echo($history->status); ?></span>
                            </div>
                            <?php } ?>
                        </td>
                        <td>
                            <!-- <font class="lable-debit">Debit: </font> -->
                            <?php if(@$history->status !== 'Refund'){ ?>
                                <span><?php echo($history->token_amount); ?></span>
                            <?php } else{ ?>
                                <span>-</span>
                            <?php } ?>
                        </td>
                        <td>
                            <!-- <font class="lable-credit">Credit: </font> -->
                                <?php if(@$history->status == 'Refund'){ ?>
                            <span><?php echo($history->token_amount); ?></span>
                            <?php } else{ ?>
                                <span>-</span>
                            <?php } ?>
                        </td>
                        <td><font class="lable-balance">Balance: </font><?php echo($history->balance); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
			</table>
		</div>
	</div>
</div>

<script>
    $(document).ready(function() {
        var t = $('#large').DataTable( {
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'desc' ]],
            "bLengthChange": false,
            "searching": true,
            "userTable": false,
            "bInfo" : false,
            "bPaginate": true,
            "pageLength": 10
        });

        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

    } );
</script>