<style>
    .table-session td, .table-sessions td{
        padding: 5px 0px !important;
    }
    .table-session, .table-sessions {
        padding: 0px !important;
    }
    .dataTables_wrapper .dataTables_filter {
        padding: 8px !important;
    }
    /*table.dataTable thead .sorting, table.dataTable thead .sorting_asc, table.dataTable thead .sorting_desc {
        background-image: none;
    }*/
    label {
        display: inline;
        font-size: 19px !important;
        font-weight: 600;
        color: #585858;   
    }
    label input {
        /*display: inline;*/
        font-size: 16px !important;
        font-weight: 100 !important;    
    }
    td{
        font-weight: 400 !important;
    }
    th{
        cursor:pointer;
    }
    .thfoot{
        border-top: solid 3px #f3f3f3 !important
    }
</style>
<script type="text/javascript" src="<https://code.jquery.com/jquery-1.12.4.js"></script>
<style type="text/css" src="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"></style>
<style type="text/css" src="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css"></style>
<!-- <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.15/api/sum().js"></script> -->

<div class="heading text-cl-primary padding-l-20">
    <h1 class="margin0">Reporting</h1>
</div>

<div class="box clear-both">

    <div class="content padding-t-10">
       <div class="box">
            <font style="color: black">Coach Group:</font><br>
            <!-- <input type="text" name="stugroup"> -->
            <form action="<?php echo site_url('partner/reporting/coachreport');?>" method="POST">
                <span class="r-only rersre"></span>
                <select name="defaultlist" id="td_value_1_2" class="e-only multiple-select" multiple="multiple" style="width:100%" required required data-parsley-required-message="Please select at least 1 coach group">
                    <?php 
                        if(@$selected){ 
                        foreach($selected as $sl){
                    ?>
                        <option value="<?php echo $sl->id; ?>" selected><?php echo $sl->name; ?></option>
                    <?php }  ?>
                    <?php foreach($noselect as $ls){ ?>
                        <option value="<?php echo $ls->id; ?>"><?php echo $ls->name; ?></option>
                    <?php } }?>
                </select>
                <input name="subgrouplist" type="hidden" id="subgrouplist" value="">

                <div class="pure-g">
                    <div class="pure-u-1 text-center m-t-20" style="text-align: left !important;">
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_from" class="datepicker frm-date margin0" type="text" readonly="" placeholder="Start Date">  
                            <span class="icon dyned-icon-coach-schedules"></span>
                        </div>
                        <span style="font-size: 16px;margin:0px 10px;">to</span>  
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_to" class="datepicker2 frm-date margin0" type="text" readonly="" placeholder="End Date">  
                            <span class="icon dyned-icon-coach-schedules"></span>
                        </div>
                    </div>
                </div><br>
                <input class="pure-button btn-small btn-secondary height-32" type="submit" name="submit" value="Coach Summary">
                <input class="pure-button btn-small btn-tertiary height-32" type="submit" name="submit" value="Rating Summary">
                <input class="pure-button btn-small btn-green height-32" type="submit" name="submit" value="Session Report">
            </form>

        </div>   
    </div>   
 
</div>

<hr style="width: 96%;">

<div class="heading text-cl-primary padding-l-20">
    <h3 class="margin0" style="font-size: 28px;font-weight: 600;color: #2b89b9;">Coach Summary</h3>
    <?php 
    if(!@$date_from){  
        echo "Data without date range";
    }else if(@$date_from && !@$date_to){ 
        echo "Data From: <strong>".$date_from1."</strong> To: <strong>Today</strong>";
    }else if(@$date_from && @$date_to){ 
        echo "Data From: <strong>".$date_from1."</strong> To: <strong>".$date_to1."</strong>";
    } 
    ?>
</div>


<div class="box clear-both">

    <div class="content padding-t-10">
        <div class="box">

            <form action="<?php echo site_url('partner/reporting/export_summary');?>" method="POST" target="_blank">
                <input type="hidden" name="subgrouplist" value="<?php echo $subgrouplist;?>">
                <input type="hidden" name="date_from" value="<?php echo @$date_from;?>">
                <input type="hidden" name="date_to" value="<?php echo @$date_to;?>">
                <input type="hidden" name="partner_id" value="<?php echo @$partner_id;?>">
                <input class="pure-button btn-small btn-tertiary height-32" type="submit" name="submit" value="Download Report">
            </form>

            <script>
                $(document).ready(function() {
                    var t = $('#large').DataTable( {

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
                            rating = api.column( 10 ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                 
                            // Update footer
                            $( api.column( 4 ).footer() ).html(startb);
                            $( api.column( 5 ).footer() ).html(earned);
                            $( api.column( 6 ).footer() ).html(refund);
                            $( api.column( 7 ).footer() ).html(balanc);
                            $( api.column( 8 ).footer() ).html(comses);
                            $( api.column( 9 ).footer() ).html(latses);
                            $( api.column( 10 ).footer() ).html(rating);

                            // console.log(api.column( 8 ).data());
                        },


                        "fixedHeader": {
                            // "header": true,
                            "footer": true
                        },
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        } ],
                        "order": [[ 1, 'asc' ]],
                        "bLengthChange": false,
                        "searching": true,
                        "userTable": false,
                        "bInfo" : false,
                        "bPaginate": true,
                        "pageLength": 10,
                    });

                    t.on( 'order.dt search.dt', function () {
                        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();

                } );
            </script>
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
                        <td style="text-align: left;padding-left: 5px !important;"><a href="<?php echo site_url('partner/reporting/detail/'.$d->user_id);?>" class="text-cl-tertiary" target="_blank"><?php echo $d->fullname; ?></a></td>
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
                                echo $rateaverage;
                            }
                        ?></td>
                    </tr>
                    <?php $no++; $a++; } ?>
                </tbody>
            </table>
        </div>
    </div>
 
</div>

<script>
    $('.multiple-select').select2({
         placeholder: "Student Group Lists"
    });
    $(".multiple-select").on('change',function(){
        document.getElementById('subgrouplist').value = $(".multiple-select").val();
    });

    function date_from(val) {
        $("#date_from").val = val;
    }

    function date_to(val) {
        $("#date_to").val = val;
    }

    function getDate(dates){
        var now = new Date(dates);
        var day = ("0" + (now.getDate() + 1)).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);
        return resultDate;
    }

    function removeDatepicker(){
        $('.datepicker2').datepicker('remove');
    }

    // datepicker
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        endDate: "now",
        autoclose:true
    });

    $('.datepicker').change(function(){
        var dates = $(this).val();
        removeDatepicker();
        $('.datepicker2').datepicker({
            format: 'yyyy-mm-dd',
            startDate: getDate(dates),
            endDate: "now",
            autoclose: true
        });
    });
</script>
                       
<!-- <script type="text/javascript" src="<https://code.jquery.com/jquery-1.12.4.js"></script> -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/__jquery.tablesorter.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/remodal.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.2/js/dataTables.fixedHeader.min.js"></script>

<script>
    jQuery.fn.DataTable.Api.register( 'sum()', function ( ) {
    return this.flatten().reduce( function ( a, b ) {
        if ( typeof a === 'string' ) {
            a = a.replace(/[^\d.-]/g, '') * 1;
        }
        if ( typeof b === 'string' ) {
            b = b.replace(/[^\d.-]/g, '') * 1;
        }
 
        return a + b;
    }, 0 );
} );
</script>