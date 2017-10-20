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
</style>
<style type="text/css" src="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"></style>
<style type="text/css" src="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css"></style>

<div class="heading text-cl-primary padding-l-20">
    <h1 class="margin0"><?php echo $title ?></h1>
</div>

<div class="box clear-both">

    <div class="content padding-t-10">
        <div class="box">
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
                        "pageLength": 10
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
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Session Date</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Session Time</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Name</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Coach Name</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Student Attendance</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Coach Attendance</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Length of Session</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Coach Rating</th>
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

                    if(@$getrating != NULL){
                        $ratingsess = $getrating[0]->rate;
                    }else{
                        $ratingsess = '<span class="labels tooltip-bottom" data-tooltip="Not rated" style="color:#000 !important;font-size:14px;">-</span>';
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
                    ?>
                    <tr>
                        <td></td>
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
                        <td style="text-align: left;padding-left: 5px !important;"><a href="<?php echo site_url('student_partner_neo/member_list/student_detail/'.$d->user_id);?>" class="text-cl-tertiary" target="_blank"><?php echo $d->fullname; ?></a></td>
                        <td style="text-align: left;padding-left: 10px !important;"><?php echo $cch_name[0]->fullname; ?></td>
                        <td><?php
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
                        ?></td>
                        <td><?php echo @$length; ?></td>
                        <td><?php echo $ratingsess; ?></td>
                    </tr>
                    <?php $no++; $a++; } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
