<html>
<body>
<script src="<?php echo base_url();?>assets/js/script.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/main.js"></script>
<script>    
$(document).ready(function(){

    $('#coachscript').click(function(){
       var cch_script = $("input[name='check_list[]']:checked").map(function() { 
                        return this.value; 
                      }).get();
       var std_id = $('#std_id').val();
       var status_script = $('#status_script').val();
        if ( !cch_script )
        {
          alert("You can't send an empty note");
          return false;
        }else{
          // alert( Updated );
          $.ajax({
            type:"POST",
            url:"<?php echo site_url('opentok/live/coaching_script');?>",    
            data: {
              'std_id' : std_id,
              'check_list' : cch_script,
              'status_script' : status_script,
              },
            success: function(){
              // alert(" Scripts Updated ");
              $("#coachsciptupdated").removeClass("hidden");
            }
          });
        }
      });

});
</script>
<script>
    // Wait until the DOM has loaded before querying the document
    $(document).ready(function(){
        $('div.tabs2').each(function(){
            // For each set of tabs, we want to keep track of
            // which tab is active and its associated content
            var $active, $content, $links = $(this).find('a');

            // If the location.hash matches one of the links, use that as the active tab.
            // If no match is found, use the first link as the initial active tab.
            $active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
            $active.addClass('active');

            $content = $($active[0].hash);

            // Hide the remaining content
            $links.not($active).each(function () {
                $(this.hash).hide();
            });

            // Bind the click event handler
            $(this).on('click', 'a', function(e){
                // Make the old tab inactive.
                $active.removeClass('active');
                $content.hide();

                // Update the variables with the new link and content
                $active = $(this);
                $content = $(this.hash);

                // Make the tab active.
                $active.addClass('active');
                $content.show();

                // Prevent the anchor's default click action
                e.preventDefault();
            });
        });
    });
</script>
<script>
    // Wait until the DOM has loaded before querying the document
    $(document).ready(function(){
        $('ul.tabs').each(function(){
            // For each set of tabs, we want to keep track of
            // which tab is active and its associated content
            var $active, $content, $links = $(this).find('a');

            // If the location.hash matches one of the links, use that as the active tab.
            // If no match is found, use the first link as the initial active tab.
            $active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
            $active.addClass('active');

            $content = $($active[0].hash);

            // Hide the remaining content
            $links.not($active).each(function () {
                $(this.hash).hide();
            });

            // Bind the click event handler
            $(this).on('click', 'a', function(e){
                // Make the old tab inactive.
                $active.removeClass('active');
                $content.hide();

                // Update the variables with the new link and content
                $active = $(this);
                $content = $(this.hash);

                // Make the tab active.
                $active.addClass('active');
                $('.checkB').show();
                $('#tabs-content1').show();
                $('.tabs2').show();
                $content.show();

                // Prevent the anchor's default click action
                e.preventDefault();
            });
        });
    });
</script>

<div id='tab-right1'><?php if ($student_vrm){ ?>
    <div>
        <button class="pure-button btn-small btn-tertiary" type="submit" id="coachscript" name="submit" value="submit">
            <span>Save</span>
        </button>
        <div class="refresh">
            <img src="http://idbuild.id.dyned.com/live_v20/assets/images/reload-data.svg" id="reloadajax3">
        </div>
    </div> <br>
    <?php if(@$script){?>
    <div class="box-lists pure-g" style="overflow-y: auto;height:400px;">
    <?php 
        $std_cert = @$student_vrm->cert_studying;

        foreach ($script as $as) {
    ?>
        <div class="accordion"><?php echo $as->unit; ?></div>
        <div class="panel"> 
         <?php
            $wm = $as->unit;

            $av = $this->db->select('*')
                        ->from('script s')
                        ->join('coaching_scripts cs', 's.id = cs.script_id')
                        ->where('s.unit', $wm)
                        ->where('s.certificate_plan', $std_cert)
                        ->where('cs.user_id', $std_id_for_cert)
                        ->get()->result();

            $no = 2;
            foreach ($av as $v){
              $status_script = $v->status;
         ?>
         <table>
          <?php if($v->status == 0){ ?>
            <td style="
                width: 6%;
                width: 6%;
                border-bottom: 1px solid #eee;">
            
            <input type="checkbox" id="checkbox-1-<?php echo $no;?>" name="check_list[]" value="<?php echo $v->id;?>" class="centang font-12" /><label for="checkbox-1-<?php echo $no;?>"></label>
            <input type="hidden" id="std_id" name="std_id" value="<?php echo $std_id_for_cert; ?>">
            <input type="hidden" id="status_script" name="status_script" value="<?php echo $status_script; ?>">
            </td>
            <td style="
                width: 90%;
                border-bottom: 1px solid #eee;color:black;">
            <p>
              <?php 
                  echo $v->script;
              ?>
            </p>
            </td>
          <?php }else{ ?>
            <td style="
                width: 6%;
                width: 6%;
                border-bottom: 1px solid #eee;">
            <input type="checkbox" id="checkbox-1-<?php echo $no;?>" name="check_list[]" value="<?php echo $v->id;?>" class="centang font-12" checked/><label for="checkbox-1-<?php echo $no;?>"></label>
            <input type="hidden" id="std_id" name="std_id" value="<?php echo $std_id_for_cert; ?>">
            <input type="hidden" id="status_script" name="status_script" value="<?php echo $status_script; ?>">
            </td>
            <td style="
                width: 90%;
                border-bottom: 1px solid #eee;">
            <p>
              <?php 
                  echo $v->script;
              ?>
            </p>
            </td>
          <?php } ?>
          </table>
         <?php $no++; } ?>
        </div>
      <?php } }else{ ?> 
        It's the first time for student to attend a session. Scripts are just stored and you can now refresh to load the scripts.
      <?php } ?>
      </div>
<?php } else{ ?>
  Student has not connected DynEd Pro ID
<?php } ?>
</div>
<div id='tab-right2' class="font-12">
    <p>No Data Yet.</p>
</div>
<div id='tab-right3' class="font-12" style="overflow-y: auto;height:700px;">
    <?php 
    if ($student_vrm){
    echo $content;
    } else{ ?>
    Student has not connected DynEd Pro ID
    <?php } ?>
</div>


<script type="text/javascript">
    $(".checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });
</script>
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].onclick = function(){
        this.classList.toggle("active");
        this.nextElementSibling.classList.toggle("show");
    }
}
</script>

<script>
    $("#reloadajax3").click(function() {
        $("#reloading2").show();
        var std_id = "<?php echo $std_id_for_cert; ?>";
        // console.log('asdsafasf');
        $("#ajaxscript").hide();
       
        $.post("<?php echo site_url('opentok/call_loader_coach/call_ajax');?>", { 'std_id': std_id },function(data) {
         $("#reloading2").hide();
         $("#ajaxscript").show();
         $("#ajaxscript").html(data);
         // alert(data);
         });

    } );
 
</script>
</body>
</html>