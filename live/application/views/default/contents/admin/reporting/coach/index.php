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
                        <?php
                        $list_sg = $this->db->select('*')
                                         ->from('subgroup')
                                         ->where('partner_id',$ls->id)
                                         ->where('type','coach')
                                         ->get()->result();

                          foreach($list_sg as $lsg){
                        ?>
                            <option value="<?php echo $lsg->id; ?>"><?php echo $lsg->name; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                <input name="subgrouplist" type="hidden" id="subgrouplist" value="">
                <input name="partnerlistid" type="hidden" value="<?php echo $partnerlistid; ?>">

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

        </div>
    </div>

</div>



<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/__jquery.tablesorter.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/js/remodal.min.js"></script>

<script>
    $('.multiple-select').select2({
         placeholder: "Coach Group Lists"
    });
    $(".multiple-select").on('change',function(){
        document.getElementById('subgrouplist').value = $(".multiple-select").val();
    });

    $(".select2-results__group").on("click", function() {
        // $(this).children("option").prop("selected", "selected");
        // $(this).next().children("option").prop("selected", false);
        // $(this).prev().children("option").prop("selected", false);

        console.log('a');
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
