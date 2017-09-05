<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Edit Day Off</h1>
</div>

<div class="box">   
    <div class="content">
        <div class="box">
			<?php echo form_open('coach/day_off/'.$form_action, 'role="form" class="pure-form pure-form-aligned" data-parsley-validate'); ?>
				<div class="pure-control-group">
                    <div class="label">
                        <label for="start_date">Start Date</label>
                    </div>
                    <div class="input">
                        <div class="frm-date" style="display:inline-block">
                    	<?php echo form_input('start_date', set_value('start_date', @$data->start_date), 'id="start_date" class="datepicker" readonly required') ?>
                        <span class="icon icon-date"></span>
                        </div>
                    </div>
                </div>

                <div class="pure-control-group">
                    <div class="label">
                        <label for="end_date">End Date</label>
                    </div>
                    <div class="input">
                        <div class="frm-date" style="display:inline-block">
                    	<?php echo form_input('end_date', set_value('end_date', @$data->end_date), 'id="end_date" class="datepicker2" readonly required') ?>
                        <span class="icon icon-date"></span>
                        </div>
                    </div>
                </div>


                <div class="pure-control-group">
                    <div class="label">
                        <label for="remark">Remark</label>
                    </div>
                    <div class="input">
                        <div class="frm-date" style="display:inline-block">
                    	<?php echo form_input('remark', set_value('remark', @$data->remark), 'id="remark" required') ?>
                        </div>
                    </div>
                </div>

                <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                    <div class="label">
                        <?php echo form_submit('__submit', @$data->id ? 'UPDATE' : 'Submit','class="pure-button btn-small btn-primary"'); ?>
						<a href="<?php echo site_url('coach/day_off/'); ?>" class="pure-button btn-red btn-small">CANCEL</a>
                    </div>
                </div>
			<?php echo form_close(); ?>
        </div>
    </div>
</div>    		
<script>
    $(function () {
        $(".datepicker").datepicker({
        	format: 'yyyy-mm-dd',
            changeMonth: true,
            changeYear: true,
        	autoclose: true
        });
        
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
            startDate: '<?php echo @$data->start_date; ?>',
            changeMonth: true,
            changeYear: true,
            autoclose:true
        });

        $('.datepicker').change(function(){
            var dates = $(this).val();
            $('.datepicker2').val('');
            removeDatepicker();
            $('.datepicker2').datepicker({
                format: 'yyyy-mm-dd',
                startDate: dates,
                changeMonth: true,
                changeYear: true,
                autoclose: true
            });
        });
    });
</script>