<html>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/js/script.js"></script>
<script src="<?php echo base_url(); ?>assets/js/main.js"></script>
<style type="text/css">
  .del-button{
    background-color: white;
      border: solid 1px #ea5656;
      color: #ea5656;
      font-weight: 600;
      font-size: 12px !important;

      -webkit-transition: all 0.4s ease-out;
    -moz-transition: all 0.4s ease-out;
    -o-transition: all 0.4s ease-out;
    -ms-transition: all 0.4s ease-out;
    transition: all 0.4s ease-out;
  }
  .del-button:hover{
    background-color: #ea5656;
    color: #fff !important;

    -webkit-transition: all 0.4s ease-in;
    -moz-transition: all 0.4s ease-in;
    -o-transition: all 0.4s ease-in;
    -ms-transition: all 0.4s ease-in;
    transition: all 0.4s ease-in;
  }
  .order-button{
    background-color: white;
    border: solid 1px #6dc291;
    color: #6dc291;
    font-weight: 600;
    padding: 5px;

    -webkit-transition: all 0.4s ease-out;
    -moz-transition: all 0.4s ease-out;
    -o-transition: all 0.4s ease-out;
    -ms-transition: all 0.4s ease-out;
    transition: all 0.4s ease-out;
  }
  .order-button:hover{
    background-color: #6dc291;
    color: #eee;

    -webkit-transition: all 0.4s ease-in;
    -moz-transition: all 0.4s ease-in;
    -o-transition: all 0.4s ease-in;
    -ms-transition: all 0.4s ease-in;
    transition: all 0.4s ease-in;
  }
	.reset-button{
    background-color: white;
    border: solid 1px #ea5656;
    color: #ea5656;
    font-weight: 600;
    padding: 5px;

    -webkit-transition: all 0.4s ease-out;
    -moz-transition: all 0.4s ease-out;
    -o-transition: all 0.4s ease-out;
    -ms-transition: all 0.4s ease-out;
    transition: all 0.4s ease-out;
  }
  .reset-button:hover{
    background-color: #ea5656;
    color: #eee;

    -webkit-transition: all 0.4s ease-in;
    -moz-transition: all 0.4s ease-in;
    -o-transition: all 0.4s ease-in;
    -ms-transition: all 0.4s ease-in;
    transition: all 0.4s ease-in;
  }
	.listunit{
		color: black;
    font-weight: 400;

	  -webkit-transition: background-color 0.4s ease-out;
		-moz-transition: background-color 0.4s ease-out;
		-o-transition: background-color 0.4s ease-out;
		-ms-transition: background-color 0.4s ease-out;
		transition: background-color 0.4s ease-out;
	}
	.listunit:hover{
	  background-color: #f1f1f1;
	  color: #144d80;
    font-weight: 600;

	  -webkit-transition: background-color 0.4s ease-in;
	  -moz-transition: background-color 0.4s ease-in;
	  -o-transition: background-color 0.4s ease-in;
	  -ms-transition: background-color 0.4s ease-in;
	  transition: background-color 0.4s ease-in;
	}
  .up, .down{
    border:solid 1px;
    padding-right: 5px;
    padding-left: 5px;
    padding-top: 2px;
    padding-bottom: 2px;
    font-weight: 600;
  }
  thead, tbody tr {
    display:table !important;
    width:100% !important;
    table-layout:fixed !important;
  }
  .table-no-border2 td:nth-child(2):not(.add-form-noborder){
    border-bottom: none;
  }
</style>

<div class="content">
    <div class="box">
    	  <div style="border: solid 1px #cccccc;margin-top: 15px;margin-bottom: 14px;">
          <table id="unitTable"> 
            <thead style="font-weight: 600;margin-bottom: 5px;background: #39aae2;color: white;">
              <tr>
                <td style="width:3%;text-align:center;padding: 5px;">No</td>
                <td style="width:60%;text-align:center;padding: 5px;">Unit Title</td>
                <td style="width:15%;text-align:center;padding: 5px;">Move Order</td>
                <td style="width:15%;text-align:center;padding: 5px;"></td>
              </tr>
            </thead>
            <tbody id="sites" style="display:block;height:300px;overflow:auto;">
            <?php
              
              $n = 0;
              $property_types = array();
              foreach($pull_contents2 as $pc){ 
                $n++;
            ?>
              <tr class="listunit">
                <td style="width:3%;text-align:center;padding: 5px;">
                  <font class="order" style="font-weight: 400;"><?php echo $n; ?></font>
                </td>
                <td id="sel<?php echo $n; ?>" style="width:60%;padding: 5px;">
                  <font><?php echo $pc->unit; ?></font>
                </td>
                <td style="width:15%; text-align:center;padding: 5px;">
                  <a class="up">&uarr;</a>
                  <a class="down">&darr;</a>
                </td>
                <td style="width:15%;text-align:center;padding: 5px;">
                  <a style="color:#ea5656;" id="del<?php echo $n; ?>">Delete?</a>
                  <a style="color:#ea5656;" class="pure-button btn-red btn-small text-cl-white hidden" id="sure<?php echo $n; ?>">Yes</a>
                  <a style="color:#ea5656;" class="pure-button del-button hidden" id="nosure<?php echo $n; ?>">Cancel</a>
                </td>
                <input type="hidden" id="iddelunit" value="">
                <script>
                  $("#del<?php echo $n; ?>").click(function(){
                    document.getElementById("iddelunit").value = '<?php echo $pc->unit; ?>';
                    $("#del<?php echo $n; ?>").hide();
                    $("#sure<?php echo $n; ?>").removeClass('hidden');
                    $("#nosure<?php echo $n; ?>").removeClass('hidden');
                  });

                  $("#nosure<?php echo $n; ?>").click(function(){
                    $("#del<?php echo $n; ?>").show();
                    $("#sure<?php echo $n; ?>").addClass('hidden');
                    $("#nosure<?php echo $n; ?>").addClass('hidden');
                  });

                  $("#sure<?php echo $n; ?>").click(function(){
                    $.post("<?php echo site_url('superadmin/coach_script/del_unit_action');?>", { 
                    'cert' : '<?php echo $pc->unit; ?>' },
                    function(data) {
                      var certlev = '<?php echo $pull_contents[0]->certificate_plan; ?>'
                      $.post("<?php echo site_url('superadmin/coach_script/pull_unit');?>", { 
                      'cert' : certlev },
                      function(data) {
                        $("#unitlist").html(data); 
                      });

                    });
                  });

                </script>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
        <div id="ordersuccess" class="hidden" style="cursor: pointer;background: #d3ffe6;padding: 10px;color: #419c68;font-weight: 600;margin-bottom: 17px;border-left: solid 5px #4fa574;">Unit order has been updated. Click this notification to reload.
        </div>
        <!-- <a id="other2" class="hidden" style="font-weight: 600;color: #144d80;font-size: 12px;">Choose Another</a> -->
        <a id="saveorder" class="order-button">Save Order</a>
        <a id="resetorder" class="reset-button">Reset Order</a>
        <br>
    </div>
</div>

<script>
$("#saveorder").click(function(){

  var TableData = new Array();
      
  $('#unitTable tr').each(function(row, tr){
      var unit = $(tr).find('td:eq(1)').text();
      if (unit.indexOf('&') > -1)
      {
        unit = unit.replace(/&/g,"&amp;")
        // console.log(unittile);
      }

      TableData[row]={
          "orderNo" : $(tr).find('td:eq(0)').text(),
          "unit"    : unit
      }
  }); 
  TableData.shift();

  $.post("<?php echo site_url('superadmin/coach_script/unit_order');?>", { 
  'TableData' : JSON.stringify(TableData) },
  function(data) {
    $("#ordersuccess").removeClass("hidden");
    // console.log(data);
  });

  // console.log(TableData);
});
</script>

<script>
  $(document).ready(function () {
  $(".up, .down").click(function () {
    var $element = this;
    var row = $($element).parents("tr:first");

    if($(this).is('.up')){
      row.insertBefore(row.prev());
    }
    else{
      row.insertAfter(row.next());
    }
    row.siblings().andSelf().each(function(i, el){
      $(el).find('.order').text(i + 1);
    });
  });
});
</script>


<script>
  $(document).ready(function(){ 
    $("#unitlist").change(function(){
      var unittile = $(this).val();

      if (unittile.indexOf('&') > -1)
      {
        unittile = unittile.replace(/&/g,"&amp;")
        // console.log(unittile);
      }

      $("#unitlist").attr('disabled', true);
      $("#other2").removeClass("hidden");

      $.post("<?php echo site_url('superadmin/coach_script/pull_content');?>", { 
        'cert' : unittile },
        function(data) {
          $("#contentlist").html(data); 
        });

    });
  });

  $('#other2').click(function(){
    $("#contentlist").empty();
    $("#unitlist").attr('disabled', false);
    $("#other2").addClass("hidden");
  });
</script>
<script>

	$("#ordersuccess").click(function(){
    	var unit = '<?php echo @$pull_contents[0]->certificate_plan; ?>';
    	$.post("<?php echo site_url('superadmin/coach_script/pull_unit');?>", { 
        'cert' : unit },
        function(data) {
          $("#unitlist").html(data); 
        });
    });
    $("#resetorder").click(function(){
    	var unit = '<?php echo @$pull_contents[0]->certificate_plan; ?>';
      $.post("<?php echo site_url('superadmin/coach_script/pull_unit');?>", { 
        'cert' : unit },
        function(data) {
          $("#unitlist").html(data); 
        });
    });

</script>

</body>
</html>