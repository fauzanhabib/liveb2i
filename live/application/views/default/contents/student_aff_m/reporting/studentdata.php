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
            <form action="<?php echo site_url('student_aff_m/reporting/studentreport');?>" method="POST">
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
                <input class="pure-button btn-small btn-secondary height-32" type="submit" name="submit" value="Student Report">
                <input class="pure-button btn-small btn-tertiary height-32" type="submit" name="submit" value="Session Report">
                <input class="pure-button btn-small btn-green height-32" type="submit" name="submit" value="Student Data">
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

            <form action="<?php echo site_url('student_aff_m/reporting/export_data');?>" method="POST" target="_blank">
                <input type="hidden" name="subgrouplist" value="<?php echo $subgrouplist;?>">
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
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Group Name</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Name</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Email</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Join Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $a = 1;
                $no = 1;
                $nowdate  = date("Y-m-d");

                foreach ($stu_dat as $d) {
                    $join_date = gmdate("Y-m-d", $d->dcrea);

                    $pt_name = $this->db->select('cl_name')
                               ->from('dsa_cert_levels')
                               ->where('cl_id',$d->cl_id)
                               ->get()->result();

                    // echo "<pre>";print_r(gmdate("Y-m-d", $d->dcrea));exit();
                    ?>
                    <tr>
                        <td></td>
                        <td><?php echo $d->name; ?></td>
                        <td><?php echo $d->fullname; ?></td>
                        <td><?php echo $d->email; ?></td>
                        <td><?php echo $join_date; ?></td>
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
