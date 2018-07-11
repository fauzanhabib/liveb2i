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
    }
    @media screen and (max-width: 425px) {
        .dataTables_wrapper .dataTables_filter {
            padding-right: 0!important;
            text-align: right;
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
            <form action="<?php echo site_url('student_partner/reporting/studentreport');?>" method="POST">
                <span class="r-only rersre"></span>
                <select name="defaultlist" id="td_value_1_2" class="e-only multiple-select" multiple="multiple" style="width:100%" required required data-parsley-required-message="Please select at least 1 student group">
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
                <input class="pure-button btn-small btn-tertiary height-32" type="submit" name="submit" value="Student Report">
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

            <form action="<?php echo site_url('student_partner/reporting/export_ses');?>" method="POST" target="_blank">
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
            <table id="large" class="display table-session tablesorter" cellspacing="0" width="100%" style="overflow: auto;">
                <thead>
                    <tr>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">#</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Session Date</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Session Time</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Student</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Group Name</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Email</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Pro ID</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Goal</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Coach</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Coach Affiliate</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Rating</th>
                        <!-- <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Student Attendance</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Coach Attendance</th> -->
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Length of Session</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Session Status</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Tokens Paid</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Refund to Student</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $a = 1;
                $no = 1;
                foreach ($ses_rpt as $d) {
                    $cch_name = $this->db->select('fullname')
                                        ->from('user_profiles')
                                        ->where('user_id',$d->coach_id)
                                        ->get()->result();

                    $getrating = $this->db->select('rate')
                                        ->from('coach_ratings')
                                        ->where_in('appointment_id',$d->id)
                                        ->where('status', 'rated')
                                        ->get()->result();

                    $getcchaff = $this->db->select('partner_id')
                                        ->from('user_profiles')
                                        ->where_in('user_id',$d->coach_id)
                                        ->get()->result();

                    $cchaffid = $getcchaff[0]->partner_id;

                    $getaffname = $this->db->select('name')
                                        ->from('partners')
                                        ->where_in('id',$cchaffid)
                                        ->get()->result();

                    $cchaffname = $getaffname[0]->name;

                    $tokenmaster = $this->db->select('*')
                                    ->from('token_histories_coach')
                                    ->where('appointment_id',$d->id)
                                    ->get()->result();

                    $type_coach = $this->db->select('coach_type_id')
                            ->from('user_profiles')
                            ->where('user_id', $d->coach_id)
                            ->get()->result();

                    $type_id = $type_coach[0]->coach_type_id;


                    //----------
                    $partner_id = $this->auth_manager->partner_id($d->coach_id);

                    $setting = $this->db->select('standard_coach_cost,elite_coach_cost')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();
                    $standard_coach_cost = $setting[0]->standard_coach_cost;
                    $elite_coach_cost = $setting[0]->elite_coach_cost;

                    $cost = '';
                    if($type_id == 1){
                        $cost = $standard_coach_cost;
                    }else if($type_id == 2){
                        $cost = $elite_coach_cost;
                    }

                    if(@$getrating != NULL){
                        $ratingsess = $getrating[0]->rate;
                    }else{
                        $ratingsess = '<span class="labels tooltip-bottom" data-tooltip="Not rated" style="color:#000 !important;font-size:14px;"></span>';
                    }

                    $std_attend = @$d->std_attend;
                    $cch_attend = @$d->cch_attend;
                    $convend   = strtotime(@$d->end_time) - (5 * 60);
                    $end_time  = date("H:i:s", $convend);

                    //status ------
                    if(!$std_attend && !$cch_attend){
                        $status = 'S. & C. no show';
                        $tokstat = 1;
                    }else if($cch_attend != NULL){
                        $cchattenddiff_raw = strtotime($cch_attend) - strtotime($d->start_time);
                        $cchattenddiff = date("i:s", $cchattenddiff_raw);

                        $stdattenddiff_raw = strtotime($std_attend) - strtotime($d->start_time);
                        $stdattenddiff = date("i:s", $stdattenddiff_raw);

                        if($cchattenddiff > '30:00'){
                          $cchattenddiff = '00:00';
                        }

                        if($cchattenddiff > "05:00"){
                            $status = 'C. late';
                            $tokstat = 1;
                        }else if ($cchattenddiff < "05:00" && @$std_attend != ""){
                            $status = 'Success';
                            $tokstat = 0;
                        }else if ($cchattenddiff < "05:00" && @!$std_attend){
                            $status = 'Success but S. No Show';
                            $tokstat = 0;
                        }
                        // $status = 'C. late';
                    }else if($cch_attend == NULL){
                        $status = 'C. no show';
                        $tokstat = 1;
                    }

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
                    ?>
                    <tr>
                        <td></td>
                        <td><?php
                            $minutes_to_add = $spr_tz;

                            $time = new DateTime($d->date." ".$d->start_time);
                            $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));

                            $stamp = $time->format('Y-m-d');

                            echo $stamp;
                            // echo $d->date;
                        ?></td>
                        <td><?php
                            // echo $d->start_time." - ".$end_time."<br>";
                            echo $start_conv_real." - ".$endmin_real;
                        ?></td>
                        <td><a href="<?php echo site_url('student_partner/member_list/student_detail/'.$d->user_id);?>" class="text-cl-tertiary" target="_blank"><?php echo $d->fullname; ?></a></td>
                        <td><?php echo $d->name; ?></td>
                        <td><?php echo $d->email; ?></td>
                        <td><?php echo $d->dyned_pro_id; ?></td>
                        <td><?php echo $d->cert_studying; ?></td>
                        <td><?php echo $cch_name[0]->fullname; ?></td>
                        <td><?php echo $cchaffname; ?></td>
                        <td><?php echo $ratingsess; ?></td>
                        <!-- td><?php
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
                        <td><?php echo @$status; ?></td>
                        <td><?php if($tokstat == 0){ echo $cost; } ?></td>
                        <td><?php if($tokstat == 1){ echo $cost; } ?></td>
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
