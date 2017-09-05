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
    <h3 class="margin0" style="font-size: 28px;font-weight: 600;color: #2b89b9;">Rating Summary</h3>
    <?php 
    if(!@$date_from){  
        echo "Data without date range";
    }else if(@$date_from && !@$date_to){ 
        echo "Data From: <strong>".$date_from."</strong> To: <strong>Today</strong>";
    }else if(@$date_from && @$date_to){ 
        echo "Data From: <strong>".$date_from."</strong> To: <strong>".$date_to."</strong>";
    } 
    ?>
</div>


<div class="box clear-both">

    <div class="content padding-t-10">
        <div class="box">

            <form action="<?php echo site_url('partner/reporting/export_rating');?>" method="POST" target="_blank">
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
                        <th rowspan="2" class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">#</th>
                        <th rowspan="2" class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Group</th>
                        <th rowspan="2" class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Coach</th>
                        <th rowspan="2" class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Email</th>
                        <th colspan="8" class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Weeks ago</th>              
                    </tr>
                    <tr>
                        <th class="bg-secondary uncek text-cl-white border-none">8</th>
                        <th class="bg-secondary uncek text-cl-white border-none">7</th>
                        <th class="bg-secondary uncek text-cl-white border-none">6</th>
                        <th class="bg-secondary uncek text-cl-white border-none">5</th>
                        <th class="bg-secondary uncek text-cl-white border-none">4</th>
                        <th class="bg-secondary uncek text-cl-white border-none">3</th>
                        <th class="bg-secondary uncek text-cl-white border-none">2</th>
                        <th class="bg-secondary uncek text-cl-white border-none">1</th>
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

                ?>
                    <tr>
                        <td></td>
                        <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->name; ?></td>
                        <td style="text-align: left;padding-left: 5px !important;"><a href="<?php echo site_url('student_partner/member_list/student_detail/'.$d->user_id);?>" class="text-cl-tertiary" target="_blank"><?php echo $d->fullname; ?></a></td>
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