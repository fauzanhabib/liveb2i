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
                                <input id="date" class="datepicker frm-date margin0" type="text" readonly>
                                <span class="icon icon-date"></span>
                            </div>
                            <span><button class="weekly_schedule text-cl-green" value="<?php echo($coach_id); ?>" style="background:none;border:none;">Weekly Schedule</button></span>
                            <ul class="parsley-errors-list filled" style="display: block;"><li class="parsley-required">Please pick date.</li></ul>
                        </div>
                       
                    </div>
                    <div id="result">
                        <img src='<?php echo base_url(); ?>images/small-loading.gif' alt='loading...' style="display:none;" id="schedule-loading"/>
                    </div>

                    <div class="pure-control-group" style="border-top:2px solid #f3f3f3;padding: 15px 0px;">
                        <button class="confirm_button pure-button btn-small btn-primary">CONFIRM</button>
                        <a href="<?php echo site_url('student/upcoming_session'); ?>" class="pure-button btn-small btn-white" style="margin-left:10px;">CANCEL</a>
                    </div>
                </fieldset>



            </div>        
        </div>
    </div>
</div>				

<script type="text/javascript">
    $(function () {
        var now = new Date();
        var day = ("0" + (now.getDate() + 2)).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);
        $('.datepicker').datepicker({
            startDate: resultDate,
            format: 'yyyy-mm-dd',
            autoclose: true,
        });

        $('.parsley-errors-list').hide();



        $.ajaxSetup({
            cache: false
        });
        
        $(".datepicker").on('change', function() {
            //alert(this.name);
            var loadUrl = "<?php echo site_url('student/manage_appointments/reschedule_session/'.$appointment_id.'/'.$coach_id); ?>"+ "/" +$(this).val();
            //alert(loadUrl);
            if($(this).val() != ''){
                $("#schedule-loading").show();
                $("#result").css({"width":"310px","height":"300px","overflow":"auto"});
                $("#result").load(loadUrl, function () {
                    $("#schedule-loading").hide();
                });
            }
            
        });
        
        $(".weekly_schedule").click(function () {
            //alert(this.name);
            var loadUrl = "<?php echo site_url('student/find_coaches/schedule_detail'); ?>" + "/" + this.value;
//            alert(loadUrl);
            //alert(loadUrl);
            if (this.value != '') {
                $("#schedule-loading").show();
                $("#confirm_button").hide();
                $("#result").css({"width":"310px","height":"300px","overflow":"auto"});
                $("#result").load(loadUrl, function () {
                    $("#schedule-loading").hide();
                    //$("#confirm_button").show();
                    //alert(data);
                });
            }

        });
        
        $(".confirm_button").click(function(){
            //alert($('input[name="radion"]:checked').val());
        
        //alert($(".datepicker").val());
        //var myvar = myarr[1] + ":" + myarr[2];
        //alert(myarr[0]);
        if ($.trim($('#date').val()) != '') {
            var myarr = $('input[name="radion"]:checked').val().split(",");
            window.location = '<?php echo site_url('student/manage_appointments/reschedule_booking/'.$appointment_id.'/'.$coach_id.'/')?>'+'/'+$(".datepicker").val()+'/'+myarr[0]+'/'+myarr[1];
        }
        else {
            $('.parsley-errors-list').show();
        }
        //alert(test);
        });

        $('#date').change(function() {
            $('.parsley-errors-list').hide();
        })

        if($(document).width() <= 420) {
            $('.content-center').height(580);
        }

    })

</script>