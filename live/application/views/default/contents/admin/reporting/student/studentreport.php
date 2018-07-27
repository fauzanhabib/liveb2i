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
    #large_wrapper{
        overflow: auto;
        padding-bottom: 25px;
    }

    @media screen and (max-width: 425px) {
        .dataTables_wrapper .dataTables_filter {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: end;
            -ms-flex-pack: end;
                    justify-content: flex-end;
            padding-right: 0!important;
        }
    }
</style>
<style type="text/css" src="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"></style>
<style type="text/css" src="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css"></style>

<div class="heading text-cl-primary padding-l-20">
    <h1 class="margin0">Reporting</h1>
</div>

<div class="box clear-both">

    <div class="content padding-t-10">
       <div class="box">
            <font style="color: black">Student Group:</font><br>
            <!-- <input type="text" name="stugroup"> -->
            <form action="<?php echo site_url('admin/reporting/student/studentreport');?>" method="POST">
                <span class="r-only rersre"></span>
                <select name="spoken_lang" id="td_value_1_2" class="e-only multiple-select" multiple="multiple" style="width:100%" required required data-parsley-required-message="Please select at least 1 student group">
                    <?php foreach($list_sp as $ls){ ?>
                        <optgroup id="asdf" label="<?php echo 'Partner: '.$ls->name; ?>">
                        <?php foreach($list_sg as $ls){ ?>
                            <option value="<?php echo $ls->id; ?>"><?php echo $ls->name; ?></option>
                        <?php } ?>
                    <?php } ?>
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
                <input class="pure-button btn-small btn-tertiary height-32" type="submit" name="submit" value="Student Report">
                <input class="pure-button btn-small btn-green height-32" type="submit" name="submit" value="Session Report">
            </form>

        </div>
    </div>

</div>

<hr style="width: 96%;">

<div class="heading text-cl-primary padding-l-20">
    <h3 class="margin0" style="font-size: 28px;font-weight: 600;color: #2b89b9;">Student Report</h3>
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

            <form action="<?php echo site_url('admin/reporting/student/export_rpt');?>" method="POST" target="_blank">
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
                            startb = api.column( 7 ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                            addeds = api.column( 8 ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                            usedto = api.column( 9 ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                            tokbal = api.column( 10 ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                            comses = api.column( 11 ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );

                            // Update footer
                            $( api.column( 7 ).footer() ).html(startb);
                            $( api.column( 8 ).footer() ).html(addeds);
                            $( api.column( 9 ).footer() ).html(usedto);
                            $( api.column( 10 ).footer() ).html(tokbal);
                            $( api.column( 11 ).footer() ).html(comses);

                            // String target = usedto.replaceAll("<a.*?</a>", "");
                            // console.log(target);
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
                        "pageLength": 10
                    });

                    t.on( 'order.dt search.dt', function () {
                        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();
                    var asd = t.column( 3 ).data().average();console.log(asd);
                } );
            </script>
            <table id="large" class="display table-session tablesorter" cellspacing="0" width="100%" style="overflow: auto;">
                <thead>
                    <tr>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">#</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Name</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Email</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Pro ID</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Student Affiliate</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Student Group</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Goal</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Start Balance</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Added Tokens</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Used Tokens</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Token Balance</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Completed Sessions</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Last Session</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Next Session</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Avg. Coach Rating</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="thfoot resp__hide"></th>
                        <th class="thfoot">Total</th>
                        <th class="thfoot"></th>
                        <th class="thfoot resp__hide"></th>
                        <th class="thfoot resp__hide"></th>
                        <th class="thfoot resp__hide"></th>
                        <th class="thfoot resp__hide"></th>
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
                        <td><a href="<?php echo site_url('student_partner/reporting/tokenhist/'.$d->user_id);?>" class="text-cl-tertiary" target="_blank"><?php echo $d->fullname; ?></a></td>
                        <td><?php echo $d->email; ?></td>
                        <td style="padding: 0px 10px !important;"><?php echo $d->dyned_pro_id; ?></td>
                        <td><?php echo $partname; ?></td>
                        <td><?php echo $d->name; ?></td>
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
                                echo round($rateaverage, 2);
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
<!-- <script src="paste"></script> -->
