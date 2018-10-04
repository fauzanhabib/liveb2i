<html>
<head>
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-2.2.4/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/b-1.3.1/b-colvis-1.3.1/b-flash-1.3.1/b-html5-1.3.1/b-print-1.3.1/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-2.2.4/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/b-1.3.1/b-colvis-1.3.1/b-flash-1.3.1/b-html5-1.3.1/b-print-1.3.1/datatables.min.js"></script>

<style>
    .textcent{
        text-align: center;
    }
    button.dt-button, div.dt-button, a.dt-button {
        background-image: none !important;
        background: white !important;
        border: 1px solid #000;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 14px;

        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        -o-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;

    }
    .dt-button:hover{
        background-image: none !important;
        background: #000 !important;
        border: 1px solid #000;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 14px;
        color: white;
    }
</style>

</head>
<body>

<h1>Export to:</h1>

<table id="large" class="display table-session tablesorter" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="bg-secondary uncek text-cl-white border-none">#</th>
            <th class="bg-secondary uncek text-cl-white border-none">Group</th>
            <th class="bg-secondary uncek text-cl-white border-none">Coach</th>
            <th class="bg-secondary uncek text-cl-white border-none">Email</th>
            <th class="bg-secondary uncek text-cl-white border-none">Start Balance</th>
            <th class="bg-secondary uncek text-cl-white border-none">Earned Tokens</th>
            <th class="bg-secondary uncek text-cl-white border-none">Refunded Tokens</th>               
            <th class="bg-secondary uncek text-cl-white border-none">Token Balance</th>               
            <th class="bg-secondary uncek text-cl-white border-none">Completed Sessions</th>               
            <th class="bg-secondary uncek text-cl-white border-none">Late Session</th>      
            <th class="bg-secondary uncek text-cl-white border-none">Absent Session</th>         
            <th class="bg-secondary uncek text-cl-white border-none">Rating Average</th>               
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th class="thfoot"></th>
            <th class="thfoot">Total</th>
            <th class="thfoot"></th>
            <th class="thfoot"></th>
            <th class="thfoot"></th>
            <th class="thfoot"></th>
            <th class="thfoot"></th>
            <th class="thfoot"></th>
            <th class="thfoot"></th>
            <th class="thfoot"></th>
            <th class="thfoot"></th>
            <th class="thfoot"></th>
        </tr>
    </tfoot>
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

            $getlateses = $this->db->select('th.id, th.appointment_id')
                                ->from('token_histories_coach th')
                                ->join('appointments a', 'th.appointment_id = a.id')
                                ->where('TIME_TO_SEC(a.cch_attend) - TIME_TO_SEC(a.start_time)>300')
                                ->where_in('a.id',$idforquery)
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

            $getlateses = $this->db->select('th.id, th.appointment_id')
                                ->from('token_histories_coach th')
                                ->join('appointments a', 'th.appointment_id = a.id')
                                ->where('TIME_TO_SEC(a.cch_attend) - TIME_TO_SEC(a.start_time)>300')
                                ->where_in('a.id',$idforquery)
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

            $getlateses = $this->db->select('th.id, th.appointment_id')
                                ->from('token_histories_coach th')
                                ->join('appointments a', 'th.appointment_id = a.id')
                                ->where('TIME_TO_SEC(a.cch_attend) - TIME_TO_SEC(a.start_time)>300')
                                ->where_in('a.id',$idforquery)
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
        $totallate = count($getlateses);

        $pullcoachprof = $this->db->select('coach_type_id')
                        ->from('user_profiles')
                        ->where('user_id',$d->user_id)
                        ->get()->result();
        
        $coachtype = $pullcoachprof[0]->coach_type_id;

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
        ?>
        <tr>
            <td></td>
            <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->name; ?></td>
            <td style="text-align: left;padding-left: 5px !important;"><a href="<?php echo site_url('partner_monitor/reporting/detail/'.$d->user_id);?>" class="text-cl-tertiary" target="_blank"><?php echo $d->fullname; ?></a></td>
            <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->email; ?></td>
            <td class="textcent"><?php echo $sum; ?></td>
            <td class="textcent">
                <?php if($sum2 != 0){ ?>
                <?php echo $sum2; ?>
                <?php }else{ echo "0"; }?>
            </td>
            <td class="textcent">
                <?php 
                    if($sum3 != 0){
                    echo $sum3;
                    }else{ echo "0"; }
                ?>
            </td>
            <td class="textcent"><?php echo @$currbal; ?></td>
            <td class="textcent">
                <?php echo count($earned_tokens); ?>
            </td>
            <td class="textcent"><?php 
                    echo @$totallate;
            ?></td>
            <td class="textcent"><?php 
                    echo @$totalrfd - @$totallate;
            ?></td>
            <td class="textcent"><?php 
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
                    title: 'Coach Summary'
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    title: 'Coach Summary'
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
            "pageLength": 10,
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
     
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
     
                // Total over all pages
                startb = api.column( 4 ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                earned = api.column( 5 ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                refund = api.column( 6 ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                balanc = api.column( 7 ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                comses = api.column( 8 ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                latses = api.column( 9 ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                abses = api.column( 10 ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                rating = api.column( 11 ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
     
                // Update footer
                $( api.column( 4 ).footer() ).html(startb);
                $( api.column( 5 ).footer() ).html(earned);
                $( api.column( 6 ).footer() ).html(refund);
                $( api.column( 7 ).footer() ).html(balanc);
                $( api.column( 8 ).footer() ).html(comses);
                $( api.column( 9 ).footer() ).html(latses);
                $( api.column( 10 ).footer() ).html(latses);
                // $( api.column( 11 ).footer() ).html(rating);

                // console.log(api.column( 8 ).data());
            },


            "fixedHeader": {
                // "header": true,
                "footer": true
            },
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