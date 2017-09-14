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

            <?php
                echo form_open('student_partner/adding/create_multiple_student2/'.$subgroup_id, 'role="form" class="pure-form pure-form-aligned" name="import" method="post" enctype="multipart/form-data"');
            ?>
                <fieldset>
                    <div class="pure-control-group">
                        <div class="input">
                            <input type="file" name="file" class="pure-input-1-2" />
                            <br>
                            <br>
                            <input type="submit" name="preview" value="PREVIEW" class="pure-button btn-small btn-primary" target="_blank"/>
                            <!-- <input type="submit" name="preview" value="PREVIEW" class="pure-button btn-small btn-primary" formtarget="_blank"/> -->

                            <p>Follow excel file template given by the Admin of DynEd Live. <a href="<?php echo base_url();?>uploads/template_multiple_student.xlsx">Download here</a></p>
                        </div>
                    </div>
                    <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                        <div class="label">
                            <!-- <input type="submit" name="submit" value="SUBMIT" class="pure-button btn-small btn-primary" /> -->


                            <!-- <a href="<?php echo site_url('student_partner/subgroup/list_student/'.$subgroup_id); ?>" class="pure-button btnpure-button btn-red btn-small">CANCEL</a>                  -->
                        </div>    
                    </div>
                </fieldset>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>				


<!-- Modal -->
<div class="remodal" >
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div id="pt">
        loading...
    </div>
</div>
<!-- /.modal -->

 <div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title" id="myModalLabel"> API CODE </h4>
       </div>
       <div class="modal-body" id="getCode" style="overflow-x: scroll;">
          //ajax success content here.
       </div>
    </div>
   </div>
 </div>


<script src="<?php echo base_url(); ?>assets/js/remodal.min.js"></script>
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

<style>
.preview{
    cursor: pointer;
}
</style>

