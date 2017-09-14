<html>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/js/script.js"></script>
<script src="<?php echo base_url(); ?>assets/js/main.js"></script>
<script src="<?php echo base_url();?>assets/ckeditor/ckeditor.js"></script>

<h3 style="font-weight: 900;">Content List</h3>

<select name="unitonly" id="unitonly" class="width100perc bg-white-fff border-1-ccc padding3" style="margin-top: 5.5px;">
    <option selected="true" disabled="disabled">Select Unit</option>
  <?php 
  	foreach($pull_contents as $pc) {
  ?>
    <option value="<?php echo $pc->unit; ?>"><?php echo $pc->unit; ?></option>
  <?php } ?>
</select>

<script>

    $("#unitonly").change(function(){
      var unitonly = $('#unitonly :selected').text();

      if (unitonly.indexOf('&') > -1 || unitonly.indexOf('–') > -1)
      {
        unitonly = unitonly.replace(/&/g,"&amp;").replace(/–/g,"&ndash;")
        // console.log(unitonly);
      }


      // console.log(unitonly);
      // $("#unitonly").hide();
      $.post("<?php echo site_url('superadmin/coach_script/pull_content');?>", { 
        'cert' : unitonly },
        function(data) {
          // console.log(unitonly);
          $("#contentlist").html(data); 
        });
    });

</script>

</body>
</html>