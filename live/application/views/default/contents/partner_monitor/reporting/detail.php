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

<hr style="width: 96%;">

<div class="heading text-cl-primary padding-l-20">
    <h3 class="margin0" style="font-size: 28px;font-weight: 600;color: #2b89b9;">
    <?php echo 'Session Detail of '.$cch_name; ?>
    </h3>
</div>


<div class="box clear-both">

    <div class="content padding-t-10">
        <div class="box">

            <form action="<?php echo site_url('student_partner_monitor/reporting/export_rpt');?>" method="POST" target="_blank">
                <input type="hidden" name="subgrouplist" value="<?php echo @$subgrouplist;?>">
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
                        "order": [[ 3, "desc" ]]
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
                        <th class="bg-secondary uncek text-cl-white border-none">Booking Date</th>
                        <th class="bg-secondary uncek text-cl-white border-none">Booking Time</th>
                        <th class="bg-secondary uncek text-cl-white border-none">Session Date</th>
                        <th class="bg-secondary uncek text-cl-white border-none">Session Time</th>
                        <th class="bg-secondary uncek text-cl-white border-none">Student</th>
                        <th class="bg-secondary uncek text-cl-white border-none">Rating</th>
                        <th class="bg-secondary uncek text-cl-white border-none">Status</th>
                        <th class="bg-secondary uncek text-cl-white border-none">Start Balance</th>
                        <th class="bg-secondary uncek text-cl-white border-none">Earned Tokens</th>
                        <th class="bg-secondary uncek text-cl-white border-none">Refunded Tokens</th>               
                        <th class="bg-secondary uncek text-cl-white border-none">Token Balance</th>              
                    </tr>
                </thead>
                </thead>
                <tbody>
                <?php
                $a = 1;
                $no = 1;
                foreach ($pullses as $d) {

                        $nowdate  = date("Y-m-d");

                        $getrating = $this->db->select('rate')
                                        ->from('coach_ratings')
                                        ->where('appointment_id',$d->id)
                                        ->where('status', 'rated')
                                        ->get()->result();

                        $rate = @$getrating[0]->rate;

                        $bookdateinfo_pull = @$d->dcrea + ($spr_tz * 60);
                        $bookdate = date("m-d-Y", $bookdateinfo_pull);
                        $booktime = date("H:i", $bookdateinfo_pull);

                        $sessdate = date('m-d-Y', strtotime($d->date));

                        $start_conv = strtotime(@$d->start_time) + ($spr_tz * 60);
                        $start_conv_real = date("H:i", $start_conv);

                        $end_conv = strtotime(@$d->end_time) + ($spr_tz * 60);
                        $end_conv_real = date("H:i:s", $end_conv);
                        $endmin   = strtotime(@$end_conv_real) - (5 * 60);
                        $endmin_real  = date("H:i", $endmin);


                        $getstatus = $this->db->select('*')
                                    ->from('token_histories_coach')
                                    ->where('appointment_id',$d->id)
                                    ->get()->result();

                        $status = @$getstatus[0]->flag;

                        $getses = $this->db->select('*')
                                ->from('appointments')
                                ->where('id',$d->id)
                                ->get()->result();

                        $start_b    = @$getstatus[0]->upd_token - @$getstatus[0]->token_amount;
                        $token_val  = @$getstatus[0]->token_amount;

                        if($status == 1){
                            $note = "Success";
                        }else if($status ==2){
                            if(!@$getses[0]->cch_attend && !@$getses[0]->std_attend){
                                $note = "Both No Show";
                            }else if(!@$getses[0]->cch_attend && @$getses[0]->std_attend){
                                $note = "Coach No Show";
                            }else if(@$getses[0]->cch_attend && !@$getses[0]->std_attend){
                                $note = "Student No Show";
                            }
                        }
                        // echo "<pre>";print_r($status);exit();


                    ?>
                    <tr>
                        <td></td>
                        <td style="text-align: left;padding-left: 5px !important;"><?php echo $bookdate; ?></td>
                        <td><?php echo $booktime; ?></td>
                        <td><?php echo $sessdate; ?></td>
                        <td><?php echo $start_conv_real." - ".$endmin_real;  ?></td>
                        <td><?php echo $d->fullname; ?></td>
                        <td><?php
                            if($rate != 0){
                            echo $rate;
                            }else{ echo "-"; }
                        ?></td>
                        <td><?php echo @$note ?></td>
                        <td><?php echo @$start_b ?></td>
                        <td><?php if(@$status == 1){ echo @$tokencost; }else{ echo "-";} ?></td>
                        <td><?php if(@$status == 2){ echo @$tokencost; }else{ echo "-";} ?></td>
                        <td><?php echo @$getstatus[0]->upd_token; ?></td>
                    </tr>
                    <?php $no++; $a++; } ?>
                </tbody>
            </table>
        </div>
    </div>
 
</div>
                       
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
