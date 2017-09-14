<div class="heading text-cl-primary padding15">
    <h1 class="margin0"><?php echo($form_action == 'create_class') ? 'Add a Class' : 'Edit Class' ?></h1>
</div>

<div class="box">
    <div class="heading pure-g"></div>

    <div class="content">
        <div class="box">
            <?php echo form_open('student_partner/managing/' . $form_action, 'role="form" class="pure-form pure-form-aligned" data-parsley-validate'); ?>
            <fieldset>
    <!--             <div class="pure-control-group">
                        <div class="label">
                            <label for="subgroup">Subgroup</label>
                        </div>  
                          <div class="input">
                            <?php echo form_dropdown('subgroup', $subgroup, trim(@$data->subgroup), 'id="subgroup" class="pure-input-1-2" required data-parsley-required-message="Please select subgroup"') ?>
                        </div>
                </div>  -->
                    
                <div class="pure-control-group">
                    <div class="label">
                        <label for="class">Class Name</label>
                    </div>
                    <div class="input">
                        <?php echo form_input('class_name', set_value('class_name', @$data->class_name), 'id="class_name" class="pure-input-1-2" required data-parsley-required-message="Please input class name"') ?>
                    </div>
                </div>

                <div class="pure-control-group">
                    <div class="label">
                        <label for="student">Student Amount</label>
                    </div>	
                    <div class="input">
                        <?php echo form_input('student_amount', set_value('student_amount', @$max_student_class), 'id="student_amount" class="pure-input-1-2" data-parsley-type="digits" required data-parsley-required-message="Please input student amount" data-parsley-type-message="Please input number only" disabled') ?>
                    </div>
                </div>

                <div class="pure-control-group">
                    <div class="label">
                        <label for="date">Start Date</label>
                    </div>
                    <div class="input">
                        <div class="frm-date">
                            <?php echo form_input('start_date', set_value('start_date', @$data->start_date), 'id="start_date" class="datepicker datepicker-from frm-date pure-input-1-2" readonly required data-parsley-required-message="Please pick start date"') ?>
                            <span class="icon icon-date" style="left: 180px;"></span>
                        </div>
                    </div>

                </div>

                <div class="pure-control-group">
                    <div class="label">
                        <label for="date2">End Date</label>
                    </div>
                    <div class="input">

                        <div class="frm-date">
                            <?php echo form_input('end_date', set_value('end_date', @$data->end_date), 'id="end_date" class="datepicker datepicker-to frm-date pure-input-1-2" readonly required data-parsley-required-message="Please pick end date"') ?>
                            <span class="icon icon-date" style="left: 180px;"></span>
                        </div>
                    </div>
                </div>

                <?php if ($form_action == 'create_class') { ?>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="token_cost">Token Cost</label>
                        </div>
                        <div class="input">
                            <?php echo form_input('token_cost', set_value('token_cost', @$data->token_cost), 'id="token_cost" class="pure-input-1-2" data-parsley-type="digits" required data-parsley-required-message="Please input token cost" data-parsley-type-message="Please input number only"') ?>
                        </div>
                    </div>
                    <?php
                }
                ?>


                <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                    <div class="label">
                        <?php echo form_submit('__submit', @$data->id ? 'UPDATE' : 'SUBMIT', 'class="pure-button btn-small btn-primary"'); ?>
                        <a href="<?php echo site_url('student_partner/managing'); ?>" class="pure-button btn-red btn-small">CANCEL</a>
                    </div>
                </div>
            </fieldset>
            <?php echo form_close(); ?>      
        </div>
    </div>
</div>				

<script>
    $(function () {

        <?php 
        if(@$start_date != "") {
        ?>
        $('.datepicker-from').datepicker({
            format: 'yyyy-mm-dd',
            startDate: "<?php echo @$data->start_date; ?>",
            endDate: "<?php echo @$data->start_date ?>",
            autoclose: true
        });

        $('.datepicker-to').datepicker({
            format: 'yyyy-mm-dd',
            startDate: "<?php echo @$data->end_date ?>",
            autoclose: true
        });
        <?php    
        }
        else {
        ?>
        function getDate(dates){
            var now = new Date(dates);
            var day = ("0" + (now.getDate() + 1)).slice(-2);
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);
            return resultDate;
        }

        function removeDatepicker(){
            $('.datepicker-to').datepicker('remove');
        }

        var now = new Date();
        var day = ("0" + (now.getDate() + 1)).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);

        // datepicker
        $('.datepicker-from').datepicker({
            format: 'yyyy-mm-dd',
            startDate: resultDate,
            autoclose:true
        });

        $('.datepicker-from').change(function(){
            var dates = $(this).val();
            removeDatepicker();
            $('.datepicker-to').datepicker({
            format: 'yyyy-mm-dd',
            startDate: getDate(dates),
            autoclose: true
            });
        });

        $('#start_date').change(function(){
            $('#start_date').parsley().reset();
        });

        $('#end_date').change(function(){
            $('#end_date').parsley().reset();
        });

        <?php } ?>

        
        if ($(document).width() <= 321) {
            $('.icon-date').css({"left": "170px"})
        }





        



    });
</script>