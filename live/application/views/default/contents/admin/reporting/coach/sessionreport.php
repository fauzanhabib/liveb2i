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
</style>
<style type="text/css" src="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"></style>
<style type="text/css" src="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css"></style>

<div class="heading text-cl-primary padding-l-20">
    <h1 class="margin0">Coach Reporting</h1>
</div>

<div class="box clear-both">

    <div class="content padding-t-10">
       <div class="box">
            <font style="color: black">Coach Group:</font><br>
            <!-- <input type="text" name="stugroup"> -->
            <form action="<?php echo site_url('admin/reporting/coach/coachreport');?>" method="POST">
                <span class="r-only rersre"></span>
                <select name="spoken_lang" id="td_value_1_2" class="e-only multiple-select" multiple="multiple" style="width:100%" required required data-parsley-required-message="Please select at least 1 coach group">
                    <?php foreach($list_sp as $ls){ ?>
                        <optgroup id="asdf" label="<?php echo $ls->name; ?>">
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
                <input class="pure-button btn-small btn-secondary height-32" type="submit" name="submit" value="Coach Summary">
                <input class="pure-button btn-small btn-tertiary height-32" type="submit" name="submit" value="Rating Summary">
                <input class="pure-button btn-small btn-green height-32" type="submit" name="submit" value="Session Report">
            </form>

        </div>
    </div>

</div>

<hr style="width: 96%;">

<div class="heading text-cl-primary padding-l-20">
    <h3 class="margin0" style="font-size: 28px;font-weight: 600;color: #2b89b9;">Session Report</h3>
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

            <form action="<?php echo site_url('admin/reporting/coach/export_session');?>" method="POST" target="_blank">
                <input type="hidden" name="subgrouplist" value="<?php echo $subgrouplist;?>">
                <input type="hidden" name="date_from" value="<?php echo @$date_from;?>">
                <input type="hidden" name="date_to" value="<?php echo @$date_to;?>">
                <input type="hidden" name="partner_id" value="<?php echo @$partner_id;?>">
                <input class="pure-button btn-small btn-tertiary height-32" type="submit" name="submit" value="Download Report">
            </form>

            <script>
                $(document).ready(function() {
                    var t = $('#large').DataTable( {
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
                        "order": [[ 7, "desc" ]]
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
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">#</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Affiliate</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Group Name</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Coach Name</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Rating</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Student Name</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Student Email</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Goal Level</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Session Date</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Session Time</th>
                        <!-- <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Student Attendance</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Coach Attendance</th> -->
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Length of Session</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $a = 1;
                $no = 1;
                foreach ($ses_rpt as $d) {
                    $std_name = $this->db->select('up.fullname, us.email, up.cert_studying')
                                        ->from('user_profiles up')
                                        ->join('users us','us.id = up.user_id')
                                        ->where('up.user_id',$d->student_id)
                                        ->get()->result();


                    $getrating = $this->db->select('rate')
                                        ->from('coach_ratings')
                                        ->where_in('appointment_id',$d->id)
                                        ->where('status', 'rated')
                                        ->get()->result();

                    if(@$getrating != NULL){
                        $ratingsess = $getrating[0]->rate;
                    }else{
                        $ratingsess = '<span class="labels tooltip-bottom" data-tooltip="Not rated" style="color:#000 !important;font-size:14px;"></span>';
                    }

                    $std_attend = @$d->std_attend;
                    $cch_attend = @$d->cch_attend;
                    $convend   = strtotime(@$d->end_time) - (5 * 60);
                    $end_time  = date("H:i:s", $convend);

                    if(!$std_attend || !$cch_attend){
                        $length = '0';
                    }else{
                        if($cch_attend > $std_attend){
                            $actstart  = $cch_attend;
                            $compare   = @$convend - strtotime(@$actstart);
                            // $length    = $convend;
                            $length    = date("i:s", $compare);
                        }else if($std_attend > $cch_attend){
                            $actstart  = $std_attend;
                            $compare   = @$convend - strtotime(@$actstart);
                            $length    = date("i:s", $compare);
                        }
                    }
                    // echo "<pre>";
                    // print_r($getrating);
                    // exit();
                    $start_conv = strtotime(@$d->start_time) + ($spr_tz * 60);
                    $start_conv_real = date("H:i", $start_conv);

                    $end_conv = strtotime(@$d->end_time) + ($spr_tz * 60);
                    $end_conv_real = date("H:i:s", $end_conv);
                    $endmin   = strtotime(@$end_conv_real) - (5 * 60);
                    $endmin_real  = date("H:i", $endmin);

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
                        <td><?php echo $partname; ?></td>
                        <td><?php echo $d->name; ?></td>
                        <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->fullname; ?></td>
                        <td><?php echo $ratingsess; ?></td>
                        <td style="text-align: left;padding-left: 10px !important;"><?php echo $std_name[0]->fullname; ?></td>
                        <td style="text-align: left;padding-left: 5px !important;"><?php echo $std_name[0]->email; ?></td>
                        <td><?php echo $std_name[0]->cert_studying; ?></td>
                        <td style="text-align: left;padding-left: 10px !important;"><?php
                            $minutes_to_add = $spr_tz;

                            $time = new DateTime($d->date." ".$d->start_time);
                            $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));

                            $stamp = $time->format('Y-m-d');

                            echo $stamp;
                            // echo $d->date;
                        ?></td>
                        <td style="text-align: center;padding-left: 0px !important;"><?php
                            // echo $d->start_time." - ".$end_time."<br>";
                            echo $start_conv_real." - ".$endmin_real;
                        ?></td>
                        <!-- <td><?php
                            if(!@$std_attend){
                                echo '<span class="labels tooltip-bottom" data-tooltip="Student did not attend" style="color:#000 !important;font-size:14px;">-</span>';
                            }else{
                                $std_conv = strtotime(@$std_attend) + ($spr_tz * 60);
                                $std_conv_real = date("H:i:s", $std_conv);
                                echo $std_conv_real;
                            }
                        ?></td>
                        <td><?php
                            if(!@$cch_attend){
                                echo '<span class="labels tooltip-bottom" data-tooltip="Coach did not attend" style="color:#000 !important;font-size:14px;">-</span>';
                            }else{
                                $cch_conv = strtotime(@$cch_attend) + ($spr_tz * 60);
                                $cch_conv_real = date("H:i:s", $cch_conv);
                                echo $cch_conv_real;
                            }
                        ?></td> -->
                        <td><?php echo @$length; ?></td>
                    </tr>
                    <?php $no++; $a++; } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>


<script>
    $('.multiple-select').select2({
         placeholder: "Coach Group Lists"
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
