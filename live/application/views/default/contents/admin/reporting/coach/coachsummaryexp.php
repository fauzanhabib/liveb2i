<html>
<head>
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-2.2.4/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/b-1.3.1/b-colvis-1.3.1/b-flash-1.3.1/b-html5-1.3.1/b-print-1.3.1/datatables.min.css"/>

<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-2.2.4/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/b-1.3.1/b-colvis-1.3.1/b-flash-1.3.1/b-html5-1.3.1/b-print-1.3.1/datatables.min.js"></script>

<style>
    .textcent{
        text-align: center !important;
    }
    button.dt-button, div.dt-button, a.dt-button {
        background-image: none !important;
        background: white !important;
        border: 1px solid #000;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 14px;
    }
</style>

</head>
<body>

<h1>Export to:</h1>

<table id="large" class="display table-session tablesorter" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Affiliate</th>
            <th>Group</th>
            <th>Coach</th>
            <th>Email</th>
            <th>Start Balance</th>
            <th>Earned Tokens</th>
            <th>Refunded Tokens</th>
            <th>Token Balance</th>
            <th>Completed Sessions</th>
            <th>Late Session</th>
            <th>Rating Average</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $a = 1;
    $no = 1;
    foreach ($cch_sum as $d) {
        if(!@$date_from){
            $start_balance = "";

            $nowdate  = date("Y-m-d");
            $earned_tokens = $this->db->select('token_amount')
                                ->from('token_histories_coach')
                                ->where('flag',1)
                                ->where('coach_id',$d->user_id)
                                ->where('date <=',$nowdate)
                                ->get()->result();

            $rfd_tokens = $this->db->select('token_amount')
                                ->from('token_histories_coach')
                                ->where('flag',2)
                                ->where('coach_id',$d->user_id)
                                ->where('date <=',$nowdate)
                                ->get()->result();

            $total_ses = $this->db->select('id')
                        ->from('appointments')
                        ->where('coach_id',$d->user_id)
                        ->get();

            $appid = $this->db->select('id')
                    ->from('appointments')
                    ->where('coach_id',$d->user_id)
                    ->where('date <', $nowdate)
                    ->get()->result();

            $app_id="";
            foreach($appid as $ap){
                $app_id.= $ap->id.",";
            }
            $appidlist=rtrim($app_id,", ");
            $idforquery=explode(",", $appidlist);

            $getrating = $this->db->select('rate')
                            ->from('coach_ratings')
                            ->where_in('appointment_id',$idforquery)
                            ->where('status', 'rated')
                            ->get()->result();

        }else if(@$date_from && !@$date_to){
            $start_balance = $this->db->select('token_amount')
                                ->from('token_histories_coach')
                                ->where('coach_id',$d->user_id)
                                ->where('flag',1)
                                ->where('date <=',@$date_from)
                                ->get()->result();

            $nowdate  = date("Y-m-d");
            $earned_tokens = $this->db->select('token_amount')
                                ->from('token_histories_coach')
                                ->where('flag',1)
                                ->where('coach_id',$d->user_id)
                                ->where('date >=',$date_from)
                                ->where('date <=',$nowdate)
                                ->get()->result();

            $rfd_tokens = $this->db->select('token_amount')
                                ->from('token_histories_coach')
                                ->where('flag',2)
                                ->where('coach_id',$d->user_id)
                                ->where('date >=',$date_from)
                                ->where('date <=',$nowdate)
                                ->get()->result();

            $total_ses = $this->db->select('id')
                        ->from('appointments')
                        ->where('coach_id',$d->user_id)
                        ->where('date >=',$date_from)
                        ->where('date <=',$nowdate)
                        ->get();

            $appid = $this->db->select('id')
                    ->from('appointments')
                    ->where('coach_id',$d->user_id)
                    ->where('date >=',$date_from)
                    ->where('date <=',$nowdate)
                    ->get()->result();

            $app_id="";
            foreach($appid as $ap){
                $app_id.= $ap->id.",";
            }
            $appidlist=rtrim($app_id,", ");
            $idforquery=explode(",", $appidlist);

            $getrating = $this->db->select('rate')
                            ->from('coach_ratings')
                            ->where_in('appointment_id',$idforquery)
                            ->where('status', 'rated')
                            ->get()->result();

        }else if(@$date_from && @$date_to){
            $start_balance = $this->db->select('token_amount')
                                ->from('token_histories_coach')
                                ->where('coach_id',$d->user_id)
                                ->where('flag',1)
                                ->where('date <=',@$date_from)
                                ->get()->result();

            $earned_tokens = $this->db->select('token_amount')
                                ->from('token_histories_coach')
                                ->where('flag',1)
                                ->where('coach_id',$d->user_id)
                                ->where('date >=',$date_from)
                                ->where('date <=',$date_to)
                                ->get()->result();

            $rfd_tokens = $this->db->select('token_amount')
                                ->from('token_histories_coach')
                                ->where('flag',2)
                                ->where('coach_id',$d->user_id)
                                ->where('date >=',$date_from)
                                ->where('date <=',$date_to)
                                ->get()->result();

            $total_ses = $this->db->select('id')
                        ->from('appointments')
                        ->where('coach_id',$d->user_id)
                        ->where('date >=',$date_from)
                        ->where('date <=',$date_to)
                        ->get();

            $appid = $this->db->select('id')
                    ->from('appointments')
                    ->where('coach_id',$d->user_id)
                    ->where('date >=',$date_from)
                    ->where('date <=',$date_to)
                    ->get()->result();

            $app_id="";
            foreach($appid as $ap){
                $app_id.= $ap->id.",";
            }
            $appidlist=rtrim($app_id,", ");
            $idforquery=explode(",", $appidlist);

            $getrating = $this->db->select('rate')
                            ->from('coach_ratings')
                            ->where_in('appointment_id',$idforquery)
                            ->where('status', 'rated')
                            ->get()->result();
        }

        // $rfd_tokens = $this->db->select('')
        //             ->from('token_histories_coach')
        //             ->where('flag',2)
        //             ->where('coach_id',$d->user_id)
        //             ->where('date >=',$date_from)
        //             ->where('date <=',$date_to)
        //             ->get()->result();

        if(@$start_balance[0]->token_amount){
            $sum = 0;
            foreach($start_balance as $key=>$value){
              if(isset($value->token_amount))
                 $sum += $value->token_amount;
            }
        }else{
            $sum = 0;
        }

        $sum2 = 0;
        foreach($earned_tokens as $key=>$value){
          if(isset($value->token_amount))
             $sum2 += $value->token_amount;
        }

        $totalrfd = count($rfd_tokens);

        $pullcoachprof = $this->db->select('coach_type_id')
                        ->from('user_profiles')
                        ->where('user_id',$d->user_id)
                        ->get()->result();

        $coachtype = $pullcoachprof[0]->coach_type_id;

        $setting = $this->db->select('standard_coach_cost,elite_coach_cost')->from('specific_settings')->where('partner_id',$d->partner_id)->get()->result();
        $standard_coach_cost = $setting[0]->standard_coach_cost;
        $elite_coach_cost = $setting[0]->elite_coach_cost;

        if($coachtype == 1){
            $tokencost = $standard_coach_cost;
        }else if($coachtype == 2){
            $tokencost = $elite_coach_cost;
        }

        $sum3 = $totalrfd * $tokencost;
        // echo "<pre>";
        // print_r($getrating);exit();

        // $getrating = $this->db->select('rate')
        //                     ->from('coach_ratings')
        //                     ->where('coach_id',$d->user_id)
        //                     ->where('status', 'rated')
        //                     ->get()->result();

        $rate_raw = 0;
        foreach($getrating as $gr){
        if(isset($gr->rate))
             $rate_raw += $gr->rate;
        }
        if(count($getrating) != 0){
            $rateaverage = $rate_raw/count($getrating);
        }else{
            $rateaverage = 0;
        }

        $pullbalance = $this->db->select('token_amount')
                        ->from('user_tokens')
                        ->where('user_id',$d->user_id)
                        ->get()->result();

        $currbal = @$pullbalance[0]->token_amount;

        // echo $rateaverage;
        // echo "<pre>";
        // print_r($pullcoachprof[0]->coach_type_id);
        // exit();

        $pullpartid = $this->db->select('partner_id')
                                ->from('user_profiles')
                                ->where('user_id', $d->user_id)
                                ->get()->result();

        $pullpartname = $this->db->select('name')
                                ->from('partners')
                                ->where('id', $pullpartid[0]->partner_id)
                                ->get()->result();

        // $pullregname = $this->db->select('region_id')
        //                         ->from('user_profiles')
        //                         ->where('user_id', $pullregid[0]->admin_regional_id)
        //                         ->get()->result();

        $partname = $pullpartname[0]->name;
        ?>
        <tr>
            <td></td>
            <td style="text-align: left;padding-left: 5px !important;"><?php echo $partname; ?></td>
            <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->name; ?></td>
            <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->fullname; ?></td>
            <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->email; ?></td>
            <td><?php echo $sum; ?></td>
            <td>
                <?php if($sum2 != 0){ ?>
                <?php echo $sum2; ?>
                <?php }else{ echo "0"; }?>
            </td>
            <td>
                <?php
                    if($sum3 != 0){
                    echo $sum3;
                    }else{ echo "0"; }
                ?>
            </td>
            <td><?php echo @$currbal; ?></td>
            <td>
                <?php echo count($earned_tokens); ?>
            </td>
            <td><?php
                    echo @$totalrfd;
            ?></td>
            <td><?php
                if(@$rateaverage == 0){
                    echo '';
                }else{
                    echo round($rateaverage, 2);
                }
            ?></td>
        </tr>
        <?php $no++; $a++; } ?>
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function() {
        var t = $('#large').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Coach Summary Report'
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    title: 'Coach Summary Report'
                },
                {
                    extend: 'print',
                    text: 'Print'
                }

            ],
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'asc' ]],
            "bLengthChange": false,
            "searching": true,
            "bInfo" : true,
            "bPaginate": true,
            "pageLength": 10
        } );

        t.on( 'order.dt search.dt', function () {
           t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = i + 1;
              t.cell(cell).invalidate('dom');
           } );
        } ).draw();

    } );
</script>

</body>
