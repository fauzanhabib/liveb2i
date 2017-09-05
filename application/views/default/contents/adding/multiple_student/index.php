<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Add Multiple Students (Max <?php echo(@$max_student);?>)</h1>
</div>

<div class="box b-f3-2">
    <!-- <div class="heading pure-g">
        <div class="pure-u-1 edit no-left">
            <a href="<?php //echo site_url('student_partner/adding/student');?>" class="add"><i class="icon icon-add"></i> Add a New Student</a>
            <a href="<?php //echo site_url('student_partner/adding/multiple_student');?>" class="add active"><i class="icon icon-add"></i> Add Multiple Students</a>
        </div>
    </div> -->

    <div class="content">
        <div class="box">
            <div class="download">
                <a href="<?php echo base_url();?>uploads/template_multiple_student.xlsx">Download Template Multiple Student</a>
            </div>

            <?php
                echo form_open('student_partner/adding/create_multiple_student/'.$subgroup_id, 'role="form" class="pure-form pure-form-aligned" name="import" method="post" enctype="multipart/form-data"');
            ?>
                <fieldset>
                    <div class="pure-control-group">
                        <div class="label" style="vertical-align: top;">
                            <label for="email">File</label>
                        </div>
                        <div class="input">
                            <input type="file" name="file" class="pure-input-1-2" />
                             <p>Follow excel file template given by the Admin of DynEd Live</p>
                        </div>
                    </div>
                    <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                        <div class="label">
                            <input type="submit" name="submit" value="SUBMIT" class="pure-button btn-small btn-primary" />
                            <a href="<?php echo site_url('student_partner/subgroup/list_student/'.$subgroup_id); ?>" class="pure-button btnpure-button btn-red btn-small">CANCEL</a>                 
                        </div>    
                    </div>
                </fieldset>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>				

<script>
    $(function (){
        var now = new Date();
        var day = ("0" + (now.getDate())).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);
        
        $('.datepicker').datepicker({
            endDate: resultDate,
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
        $(".datepicker").datepicker("setDate", '1990-01-01');

        if($(document).width() <= 480) {
            $('.input').css({'width':'100%'});
            $('.label').css({'width':'100%'});
        }

    });
</script>