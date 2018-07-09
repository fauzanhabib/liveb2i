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

<table id="large" class="display tablesorter" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Pro ID</th>
            <th>Student Affiliate</th>
            <th>Student Group</th>
            <th>Goal</th>
            <th>Start Balance</th>
            <th>Added Tokens</th>
            <th>Used Tokens</th>
            <th>Token Balance</th>
            <th>Completed Sessions</th>
            <th>Last Session</th>
            <th>Next Session</th>
            <th>Avg. Coach Rating</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $a = 1;
    $no = 1;
    $nowdate  = date("Y-m-d");

    foreach ($stu_rpt as $d) {

        if(!@$date_from){
            $tokenbal = $d->token_amount;
            $token_usage = $this->db->select('token_amount')
                            ->from('token_histories')
                            ->where('user_id',$d->user_id)
                            ->where('token_status_id',1)
                            ->get()->result();

            $token_balance = $this->db->select('token_amount')
                            ->from('token_histories')
                            ->where('user_id',$d->user_id)
                            ->get()->result();

            $token_added = $this->db->select('token_amount')
                            ->from('token_histories')
                            ->where('user_id',$d->user_id)
                            ->where('token_status_id',15)
                            ->get()->result();

            $total_ses = $this->db->select('id')
                        ->from('appointments')
                        ->where('student_id',$d->user_id)
                        ->get();

            $last_ses = $this->db->select('date')
                                ->from('appointments')
                                ->where('student_id',$d->user_id)
                                // ->where('status','comleted')
                                ->where('date <', $nowdate)
                                ->order_by('date','DESC')
                                ->get()->result();

            $next_ses = $this->db->select('date')
                                ->from('appointments')
                                ->where('student_id',$d->user_id)
                                // ->where('status','active')
                                ->where('date >', $nowdate)
                                ->order_by('date','ASC')
                                ->get()->result();

            $appid = $this->db->select('id')
                                ->from('appointments')
                                ->where('student_id',$d->user_id)
                                ->where('date <', $nowdate)
                                ->get()->result();
        }else if(@$date_from && !@$date_to){
            $from = strtotime(@$date_from);
            $tokenbal = $d->token_amount;

            $token_usage = $this->db->select('*')
                            ->from('token_histories')
                            ->where('transaction_date >=',$from)
                            ->where('user_id',$d->user_id)
                            ->where('token_status_id',1)
                            ->order_by('transaction_date','DESC')
                            ->get()->result();

            $token_balance = $this->db->select('*')
                            ->from('token_histories')
                            ->where('transaction_date <=',$from)
                            ->where('user_id',$d->user_id)
                            ->order_by('transaction_date','DESC')
                            ->get()->result();

            $token_added = $this->db->select('token_amount')
                            ->from('token_histories')
                            ->where('transaction_date >=',$from)
                            ->where('user_id',$d->user_id)
                            ->where('token_status_id',15)
                            ->get()->result();

            $total_ses = $this->db->select('id')
                        ->from('appointments')
                        ->where('student_id',$d->user_id)
                        ->where('date >=',$date_from)
                        ->get();

            $last_ses = $this->db->select('date')
                                ->from('appointments')
                                ->where('student_id',$d->user_id)
                                // ->where('status','comleted')
                                ->where('date <', $date_from)
                                ->order_by('date','DESC')
                                ->get()->result();

            $next_ses = $this->db->select('date')
                                ->from('appointments')
                                ->where('student_id',$d->user_id)
                                // ->where('status','active')
                                ->where('date >', $nowdate)
                                ->order_by('date','ASC')
                                ->get()->result();

            $appid = $this->db->select('id')
                                ->from('appointments')
                                ->where('student_id',$d->user_id)
                                ->where('date >=', $nowdate)
                                ->get()->result();
        }else if(@$date_from && @$date_to){
            $from = strtotime(@$date_from);
            $to   = strtotime(@$date_to);

            $token_usage = $this->db->select('*')
                            ->from('token_histories')
                            ->where('transaction_date >=',$from)
                            ->where('transaction_date <=',$to)
                            ->where('user_id',$d->user_id)
                            ->where('token_status_id',1)
                            ->order_by('transaction_date','DESC')
                            ->get()->result();

            $token_balance = $this->db->select('*')
                            ->from('token_histories')
                            ->where('transaction_date <=',$from)
                            ->where('user_id',$d->user_id)
                            ->order_by('transaction_date','DESC')
                            ->get()->result();

            $token_balance2 = $this->db->select('*')
                            ->from('token_histories')
                            ->where('transaction_date <=',$to)
                            ->where('user_id',$d->user_id)
                            ->order_by('transaction_date','DESC')
                            ->get()->result();

            $tokenbal = @$token_balance2[0]->balance;

            $token_added = $this->db->select('token_amount')
                            ->from('token_histories')
                            ->where('transaction_date >=',$from)
                            ->where('transaction_date <=',$to)
                            ->where('user_id',$d->user_id)
                            ->where('token_status_id',15)
                            ->get()->result();

            $total_ses = $this->db->select('id')
                        ->from('appointments')
                        ->where('student_id',$d->user_id)
                        ->where('date >=',$date_from)
                        ->where('date <=',$date_to)
                        ->get();

            $last_ses = $this->db->select('date')
                                ->from('appointments')
                                ->where('student_id',$d->user_id)
                                // ->where('status','comleted')
                                ->where('date <', $date_from)
                                ->order_by('date','DESC')
                                ->get()->result();

            $next_ses = $this->db->select('date')
                                ->from('appointments')
                                ->where('student_id',$d->user_id)
                                // ->where('status','active')
                                ->where('date >', $date_to)
                                ->order_by('date','ASC')
                                ->get()->result();

            $appid = $this->db->select('id')
                                ->from('appointments')
                                ->where('student_id',$d->user_id)
                                ->where('date >=', $nowdate)
                                ->get()->result();
        }



        $sum = 0;
        foreach($token_usage as $key=>$value){
          if(isset($value->token_amount))
             $sum += $value->token_amount;
        }


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

        $start_b = @$token_balance[0]->balance;
        // echo $rateaverage;

        $addeds = 0;
        foreach($token_added as $key=>$value){
          if(isset($value->token_amount))
             $addeds += $value->token_amount;
        }

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

        // echo "<pre>";print_r($pullregname);exit();
        ?>
        <tr>
            <td></td>
            <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->fullname; ?></td>
            <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->email; ?></td>
            <td style="padding: 0px 10px !important;"><?php echo $d->dyned_pro_id; ?></td>
            <td style="text-align: left;padding-left: 5px !important;"><?php echo $partname; ?></td>
            <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->name; ?></td>
            <td><?php echo $d->cert_studying; ?></td>
            <td><?php if(@$start_b){echo @$start_b;}else{echo '0';} ?></td>
            <td><?php if(@$addeds){echo @$addeds;}else{echo '0';} ?></td>
            <td>
                <?php if($sum != 0){ ?>
                <?php echo $sum; ?>
                <?php }else{ echo $sum; }?>
            </td>
            <td><?php echo @$tokenbal; ?></td>
            <td>
                <?php if($total_ses->num_rows() != 0){ ?>
                    <?php echo $total_ses->num_rows(); ?>
                <?php }else{ echo $total_ses->num_rows(); }?>
            </td>
            <td><?php
                if(!@$last_ses){
                    echo '<span class="labels tooltip-bottom" data-tooltip="No Session" style="color:#000 !important;font-size:14px;">-</span>';
                }
                echo @$last_ses[0]->date;
            ?></td>
            <td><?php
                if(!@$next_ses){
                    echo '<span class="labels tooltip-bottom" data-tooltip="No Session" style="color:#000 !important;font-size:14px;">-</span>';
                }else{
                    echo @$next_ses[0]->date;
                }
            ?></td>
            <td><?php
                if(@$rateaverage == 0){
                    echo '';
                }else{
                    echo $rateaverage;
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
                    title: 'Student Report'
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    title: 'Student Report'
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
