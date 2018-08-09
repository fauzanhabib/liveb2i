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
    }
</style>

</head>
<body>

<h1>Export to:</h1>

<table id="large" class="display table-session tablesorter" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Session Date</th>
            <th>Session Time</th>
            <th>Student</th>
            <th>Student Affiliate</th>
            <th>Student Group</th>
            <th>Email</th>
            <th>Pro ID</th>
            <th>Goal</th>
            <th>Coach</th>
            <th>Coach Affiliate</th>
            <th>Rating</th>
            <!-- <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Student Attendance</th>
            <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Coach Attendance</th> -->
            <th>Length of Session</th>
            <th>Session Status</th>
            <th>Tokens Paid</th>
            <th>Refund to Student</th>
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

            if($cchattenddiff > "05:00"){
                $status = 'C. late';
                $tokstat = 1;
            }else if ($cchattenddiff < "05:00"){
                $status = 'Success';
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
            <td style="text-align: left;padding-left: 5px !important;"><a href="<?php echo site_url('student_partner/member_list/student_detail/'.$d->user_id);?>" class="text-cl-tertiary" target="_blank"><?php echo $d->fullname; ?></a></td>
            <td><?php echo $partname; ?></td>
            <td><?php echo $d->name; ?></td>
            <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->email; ?></td>
            <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->dyned_pro_id; ?></td>
            <td><?php echo $d->cert_studying; ?></td>
            <td style="text-align: left;padding-left: 10px !important;"><?php echo $cch_name[0]->fullname; ?></td>
            <td style="text-align: left;padding-left: 10px !important;"><?php echo $cchaffname; ?></td>
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

<script type="text/javascript">
    $(document).ready(function() {
        var t = $('#large').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Session Report'
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    title: 'Session Report'
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