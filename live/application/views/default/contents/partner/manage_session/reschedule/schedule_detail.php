<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Reschedule Session</h1>
</div>

<div class="box">
    <div class="heading pure-g"></div>

    <div class="content">
        <div class="box">
            <div class="pure-form pure-form-aligned">
                <fieldset>
                    <div class="pure-control-group">
                        <div class="label">
                            <label>Coach Name</label>
                        </div>
                        <div class="input">
                            <label><?php echo($coach_name); ?></label>
                        </div>
                    </div>

                    <div class="pure-control-group">
                        <div class="label">
                            <label for="date">Select Date</label>
                        </div>
                        <div class="input">
                            <div class="frm-date" style="display:inline-block">
                                <input id="date" class="datepicker frm-date margin0" type="text" readonly="">
                                <span class="icon icon-date"></span>
                            </div>
                            <span><button class="weekly_schedule text-cl-green" value="11" style="background:none;border:none;">Weekly Schedule</button></span>
                        </div>
                    </div>
                    <div id="result">
                        <img src='<?php echo base_url(); ?>images/small-loading.gif' alt='loading...' style="display:none;" id="schedule-loading"/>
                    </div>

                    <div class="pure-control-group" style="border-top:2px solid #f3f3f3;padding: 15px 0px;">
                        <button class="confirm_button pure-button btn-small btn-primary">CONFIRM</button>
                        <button class="cancel_button pure-button btn-small btn-red" style="margin-left:10px;">CANCEL</button>
                    </div>
                </fieldset>
            </div>        
        </div>
    </div>
</div>				

<script type="text/javascript">
    $(function () {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $.ajaxSetup({
            cache: false
        });
        
        $(".datepicker").on('change', function() {
            //alert(this.name);
            var loadUrl = "<?php echo site_url('partner/manage_session/reschedule_session/'.$appointment_id.'/'.$coach_id); ?>"+ "/" +$(this).val();
            //alert(loadUrl);
            if($(this).val() != ''){
                $("#schedule-loading").show();
                $("#result").css({"width":"285px","height":"300px","overflow-x":"hidden"});
                $("#result").load(loadUrl, function () {
                    $("#schedule-loading").hide();
                });
            }
            
        });
        
        $(".weekly_schedule").click(function () {
            //alert(this.name);
            var loadUrl = "<?php echo site_url('partner/schedule/schedule_detail'); ?>" + "/" + this.value;
            //alert(loadUrl);
            if (this.value != '') {
                $("#schedule-loading").show();
                $("#confirm_button").hide();
                $("#result").css({"width":"285px","height":"300px","overflow-x":"hidden"});
                $("#result").load(loadUrl, function () {
                    $("#schedule-loading").hide();
                    //$("#confirm_button").show();
                    //alert(data);
                });
            }

        });
        
        $(".confirm_button").click(function(){
            var myarr = $('input[name="radion"]:checked').val().split(",");
            window.location = '<?php echo site_url('partner/manage_session/reschedule_booking/'.$student_id.'/'.$appointment_id.'/'.$coach_id.'/')?>'+'/'+$(".datepicker").val()+'/'+myarr[0]+'/'+myarr[1];
        });

        $(".cancel_button").click(function(){
            window.location = '<?php echo site_url('partner/schedule/manage/'.$student_id)?>';
        });
    });

</script>