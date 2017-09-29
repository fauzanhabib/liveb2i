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
            <th rowspan="2" style="cursor:pointer;">#</th>
            <th rowspan="2" style="cursor:pointer;">Affiliate</th>
            <th rowspan="2" style="cursor:pointer;">Group</th>
            <th rowspan="2" style="cursor:pointer;">Coach</th>
            <th rowspan="2" style="cursor:pointer;">Email</th>
            <th colspan="8" style="cursor:pointer;">Weeks ago</th>
        </tr>
        <tr>
            <th>8</th>
            <th>7</th>
            <th>6</th>
            <th>5</th>
            <th>4</th>
            <th>3</th>
            <th>2</th>
            <th>1</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $a = 1;
    $no = 1;
    foreach ($listcoach as $d) {
        $calc8 = date('Y-m-d', strtotime("-8 week"));
        $calc7 = date('Y-m-d', strtotime("-7 week"));
        $calc6 = date('Y-m-d', strtotime("-6 week"));
        $calc5 = date('Y-m-d', strtotime("-5 week"));
        $calc4 = date('Y-m-d', strtotime("-4 week"));
        $calc3 = date('Y-m-d', strtotime("-3 week"));
        $calc2 = date('Y-m-d', strtotime("-2 week"));
        $calc1 = date('Y-m-d', strtotime("-1 week"));

        $appid8 = $this->db->select('*')
                 ->from('user_profiles')
                 ->join('users','users.id = user_profiles.user_id')
                 ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                 ->join('appointments','appointments.coach_id = user_profiles.user_id')
                 // ->join('coach_ratings','coach_ratings.appointment_id = appointments.id')
                 ->order_by('appointments.date', 'DESC')
                 ->where('appointments.date >=',$calc8)
                 ->where('appointments.date <=',$calc7)
                 ->where_in('appointments.coach_id',$d->user_id)
                 ->get()->result();

        $app_id8="";
        foreach($appid8 as $ap8){
            $app_id8.= $ap8->id.",";
        }
        $appidlist8=rtrim($app_id8,", ");
        $idforquery8=explode(",", $appidlist8);
        $getrating8 = $this->db->select('rate')
                                ->from('coach_ratings')
                                ->where_in('appointment_id',$idforquery8)
                                ->where('status', 'rated')
                                ->get()->result();

        $rate_raw8 = 0;
        foreach($getrating8 as $gr8){
        if(isset($gr8->rate))
             $rate_raw8 += $gr8->rate;
        }
        if(count($getrating8) != 0){
            $rateaverage8 = $rate_raw8/count($getrating8);
        }else{
            $rateaverage8 = "-";
        }

        $appid7 = $this->db->select('*')
                 ->from('user_profiles')
                 ->join('users','users.id = user_profiles.user_id')
                 ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                 ->join('appointments','appointments.coach_id = user_profiles.user_id')
                 // ->join('coach_ratings','coach_ratings.appointment_id = appointments.id')
                 ->order_by('appointments.date', 'DESC')
                 ->where('appointments.date >=',$calc7)
                 ->where('appointments.date <=',$calc6)
                 ->where_in('appointments.coach_id',$d->user_id)
                 ->get()->result();

        $app_id7="";
        foreach($appid7 as $ap7){
            $app_id7.= $ap7->id.",";
        }
        $appidlist7=rtrim($app_id7,", ");
        $idforquery7=explode(",", $appidlist7);
        $getrating7 = $this->db->select('rate')
                                ->from('coach_ratings')
                                ->where_in('appointment_id',$idforquery7)
                                ->where('status', 'rated')
                                ->get()->result();

        $rate_raw7 = 0;
        foreach($getrating7 as $gr7){
        if(isset($gr7->rate))
             $rate_raw7 += $gr7->rate;
        }
        if(count($getrating7) != 0){
            $rateaverage7 = $rate_raw7/count($getrating7);
        }else{
            $rateaverage7 = "-";
        }

        $appid6 = $this->db->select('*')
                 ->from('user_profiles')
                 ->join('users','users.id = user_profiles.user_id')
                 ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                 ->join('appointments','appointments.coach_id = user_profiles.user_id')
                 // ->join('coach_ratings','coach_ratings.appointment_id = appointments.id')
                 ->order_by('appointments.date', 'DESC')
                 ->where('appointments.date >=',$calc6)
                 ->where('appointments.date <=',$calc5)
                 ->where_in('appointments.coach_id',$d->user_id)
                 ->get()->result();

        $app_id6="";
        foreach($appid6 as $ap6){
            $app_id6.= $ap6->id.",";
        }
        $appidlist6=rtrim($app_id6,", ");
        $idforquery6=explode(",", $appidlist6);
        $getrating6 = $this->db->select('rate')
                                ->from('coach_ratings')
                                ->where_in('appointment_id',$idforquery6)
                                ->where('status', 'rated')
                                ->get()->result();

        $rate_raw6 = 0;
        foreach($getrating6 as $gr6){
        if(isset($gr6->rate))
             $rate_raw6 += $gr6->rate;
        }
        if(count($getrating6) != 0){
            $rateaverage6 = $rate_raw6/count($getrating6);
        }else{
            $rateaverage6 = "-";
        }

        $appid5 = $this->db->select('*')
                 ->from('user_profiles')
                 ->join('users','users.id = user_profiles.user_id')
                 ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                 ->join('appointments','appointments.coach_id = user_profiles.user_id')
                 // ->join('coach_ratings','coach_ratings.appointment_id = appointments.id')
                 ->order_by('appointments.date', 'DESC')
                 ->where('appointments.date >=',$calc5)
                 ->where('appointments.date <=',$calc4)
                 ->where_in('appointments.coach_id',$d->user_id)
                 ->get()->result();

        $app_id5="";
        foreach($appid5 as $ap5){
            $app_id5.= $ap5->id.",";
        }
        $appidlist5=rtrim($app_id5,", ");
        $idforquery5=explode(",", $appidlist5);
        $getrating5 = $this->db->select('rate')
                                ->from('coach_ratings')
                                ->where_in('appointment_id',$idforquery5)
                                ->where('status', 'rated')
                                ->get()->result();

        $rate_raw5 = 0;
        foreach($getrating5 as $gr5){
        if(isset($gr5->rate))
             $rate_raw5 += $gr5->rate;
        }
        if(count($getrating5) != 0){
            $rateaverage5 = $rate_raw5/count($getrating5);
        }else{
            $rateaverage5 = "-";
        }

        $appid4 = $this->db->select('*')
                 ->from('user_profiles')
                 ->join('users','users.id = user_profiles.user_id')
                 ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                 ->join('appointments','appointments.coach_id = user_profiles.user_id')
                 // ->join('coach_ratings','coach_ratings.appointment_id = appointments.id')
                 ->order_by('appointments.date', 'DESC')
                 ->where('appointments.date >=',$calc4)
                 ->where('appointments.date <=',$calc3)
                 ->where_in('appointments.coach_id',$d->user_id)
                 ->get()->result();

        $app_id4="";
        foreach($appid4 as $ap4){
            $app_id4.= $ap4->id.",";
        }
        $appidlist4=rtrim($app_id4,", ");
        $idforquery4=explode(",", $appidlist4);
        $getrating4 = $this->db->select('rate')
                                ->from('coach_ratings')
                                ->where_in('appointment_id',$idforquery4)
                                ->where('status', 'rated')
                                ->get()->result();

        $rate_raw4 = 0;
        foreach($getrating4 as $gr4){
        if(isset($gr4->rate))
             $rate_raw4 += $gr4->rate;
        }
        if(count($getrating4) != 0){
            $rateaverage4 = $rate_raw4/count($getrating4);
        }else{
            $rateaverage4 = "-";
        }

        $appid3 = $this->db->select('*')
                 ->from('user_profiles')
                 ->join('users','users.id = user_profiles.user_id')
                 ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                 ->join('appointments','appointments.coach_id = user_profiles.user_id')
                 // ->join('coach_ratings','coach_ratings.appointment_id = appointments.id')
                 ->order_by('appointments.date', 'DESC')
                 ->where('appointments.date >=',$calc3)
                 ->where('appointments.date <=',$calc2)
                 ->where_in('appointments.coach_id',$d->user_id)
                 ->get()->result();

        $app_id3="";
        foreach($appid3 as $ap3){
            $app_id3.= $ap3->id.",";
        }
        $appidlist3=rtrim($app_id3,", ");
        $idforquery3=explode(",", $appidlist3);
        $getrating3 = $this->db->select('rate')
                                ->from('coach_ratings')
                                ->where_in('appointment_id',$idforquery3)
                                ->where('status', 'rated')
                                ->get()->result();

        $rate_raw3 = 0;
        foreach($getrating3 as $gr3){
        if(isset($gr3->rate))
             $rate_raw3 += $gr3->rate;
        }
        if(count($getrating3) != 0){
            $rateaverage3 = $rate_raw3/count($getrating3);
        }else{
            $rateaverage3 = "-";
        }

        $appid2 = $this->db->select('*')
                 ->from('user_profiles')
                 ->join('users','users.id = user_profiles.user_id')
                 ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                 ->join('appointments','appointments.coach_id = user_profiles.user_id')
                 // ->join('coach_ratings','coach_ratings.appointment_id = appointments.id')
                 ->order_by('appointments.date', 'DESC')
                 ->where('appointments.date >=',$calc2)
                 ->where('appointments.date <=',$calc1)
                 ->where_in('appointments.coach_id',$d->user_id)
                 ->get()->result();

        $app_id2="";
        foreach($appid2 as $ap2){
            $app_id2.= $ap2->id.",";
        }
        $appidlist2=rtrim($app_id2,", ");
        $idforquery2=explode(",", $appidlist2);
        $getrating2 = $this->db->select('rate')
                                ->from('coach_ratings')
                                ->where_in('appointment_id',$idforquery2)
                                ->where('status', 'rated')
                                ->get()->result();

        $rate_raw2 = 0;
        foreach($getrating2 as $gr2){
        if(isset($gr2->rate))
             $rate_raw2 += $gr2->rate;
        }
        if(count($getrating2) != 0){
            $rateaverage2 = $rate_raw2/count($getrating2);
        }else{
            $rateaverage2 = "-";
        }

        $appid1 = $this->db->select('*')
                 ->from('user_profiles')
                 ->join('users','users.id = user_profiles.user_id')
                 ->join('subgroup','subgroup.id = user_profiles.subgroup_id')
                 ->join('appointments','appointments.coach_id = user_profiles.user_id')
                 // ->join('coach_ratings','coach_ratings.appointment_id = appointments.id')
                 ->order_by('appointments.date', 'DESC')
                 ->where('appointments.date >=',$calc1)
                 ->where_in('appointments.coach_id',$d->user_id)
                 ->get()->result();

        $app_id1="";
        foreach($appid1 as $ap1){
            $app_id1.= $ap1->id.",";
        }
        $appidlist1=rtrim($app_id1,", ");
        $idforquery1=explode(",", $appidlist1);
        $getrating1 = $this->db->select('rate')
                                ->from('coach_ratings')
                                ->where_in('appointment_id',$idforquery1)
                                ->where('status', 'rated')
                                ->get()->result();

        $rate_raw1 = 0;
        foreach($getrating1 as $gr1){
        if(isset($gr1->rate))
             $rate_raw1 += $gr1->rate;
        }
        if(count($getrating1) != 0){
            $rateaverage1 = $rate_raw1/count($getrating1);
        }else{
            $rateaverage1 = "-";
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

    ?>
        <tr>
            <td></td>
            <td style="text-align: left;padding-left: 5px !important;"><?php echo $partname; ?></td>
            <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->name; ?></td>
            <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->fullname; ?></td>
            <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->email; ?></td>
            <td><?php echo $rateaverage8; ?></td>
            <td><?php echo $rateaverage7; ?></td>
            <td><?php echo $rateaverage6; ?></td>
            <td><?php echo $rateaverage5; ?></td>
            <td><?php echo $rateaverage4; ?></td>
            <td><?php echo $rateaverage3; ?></td>
            <td><?php echo $rateaverage2; ?></td>
            <td><?php echo $rateaverage1; ?></td>
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
                    title: 'Coach Rating Summary'
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    title: 'Coach Rating Summary'
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
