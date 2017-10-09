<script src="<?php echo base_url(); ?>assets/js/main.js"></script>
<style type="text/css">
  .tabling{
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    transition: all 0.3s ease;
  }
  .tabling:hover {
    -webkit-box-shadow: 0px 0px 14px 0px rgba(0,0,0,0.75);
    -moz-box-shadow: 0px 0px 14px 0px rgba(0,0,0,0.75);
    box-shadow: 0px 0px 14px 0px rgba(0,0,0,0.75);
  }
  input:focus { 
    outline: none !important;
    border-bottom:1px solid #39aae2 !important;

    -webkit-transition: all 0.9s ease;
    -moz-transition: all 0.9s ease;
    -o-transition: all 0.9s ease;
    -ms-transition: all 0.9s ease;
    transition: all 0.9s ease;
}
.title-cm{
  font-weight: 400;
  padding: 10px;
  font-size: 20px;
  letter-spacing: 7px;
  text-transform: uppercase;
  background: #2b89b9;
  color: white;
}
</style>
<div class="heading text-cl-primary border-b-1 padding15">
  <h1 class="margin0">Coach Materials</h1>
  <div class="delete-add-btn right">
    <div class="btn-noborder btn-normal bg-white-fff left" id="btn_addnew">
      <a href="<?php echo site_url('superadmin/coach_script/index');?>">
        <em class="textDec-none text-cl-green">List Materials</em>
      </a>
    </div>
    <div class="btn-noborder btn-normal bg-white-fff left" id="btn_addnew">
      <a href="<?php echo site_url('superadmin/coach_script/add_new');?>">
        <em class="textDec-none text-cl-tertiary" style="font-weight: 600;border-bottom: solid;">Add Materials</em>
      </a>
    </div>
  </div>
</div>

<div class="heading pure-g">

</div>

<div class="content" style="background-color: #f6f6f6;">
    <div class="pure-g">
        <div class="pure-u-15-24 profile-detail prelative" style="width: 95% !important;padding: 20px;margin: auto;">
            <table class="table-no-border2 add-form tabling" style="background-color: #fff;"> 
                <tbody>
                    <tr>
                      <td class="add-form-noborder title-cm" style="padding: 10px;">
                        <center><font>Content Editor</font></center>
                      </td>
                    </tr>
                    <tr>
                        <td class="add-form-noborder" style="padding: 10px;">
                            <h3 style="font-weight: 900;">Certification Level</h3>
                            <select name="certlev" id="certlev" class="width100perc bg-white-fff border-1-ccc padding3" style="margin-top: 5.5px;">
                                <option selected="true" disabled="disabled">Select Certification</option>
                              <?php foreach($allcert as $ac) {?>
                                <option value="<?php echo $ac; ?>"><?php echo $ac; ?></option>
                              <?php } ?>
                            </select>
                            <a id="other" class="hidden" style="font-weight: 600;color: #144d80;font-size: 12px;">Choose Another</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="add-form-noborder" style="padding: 10px;">
                            <div class="hidden" style="margin-top: 15px;" id="addunitarea">
                              <h3 style="font-weight: 900;">Unit List</h3>
                              <font style="font-weight: 600;">Add Unit</font>
                              <input type="hidden" name="certunit" id="certunit">
                              <input type="text" id="unittitleinput" class="width50perc bg-white-fff padding2 padding3" style="margin-left: 15px;border-bottom:1px solid #ccc;color: black;" placeholder="Insert Unit Title...">
                              <a id="addunitbtn" class="pure-button btn-blue btn-small" style="margin-top: -3px;">
                                Add
                              </a>
                            </div>
                            <div id="unitexist" class="hidden" style="cursor: pointer;background: #fffad3;padding: 10px;color: #988f48;font-weight: 600;margin-top: 5px;border-left: solid 5px #988f48;">Unit is already exist</div>
                            <div id="unitemp" class="hidden" style="cursor: pointer;background: #fffad3;padding: 10px;color: #988f48;font-weight: 600;margin-top: 5px;border-left: solid 5px #988f48;">
                              You can't add an empty Unit Title
                            </div>
                            <div id="unitaddsuccess" class="hidden" style="cursor: pointer;background: #d3ffe6;padding: 10px;color: #419c68;font-weight: 600;margin-top: 5px;border-left: solid 5px #4fa574;">
                              Unit has been added. Click this notification to reload.
                            </div>
                            <div id="unitlist"></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="add-form-noborder" style="padding: 10px;">
                            <div id="unitonly"></div>
                            <div id="contentlist"></div>
                        </td>
                    </tr>
                </tbody>    
            </table>

        </div>


        <div class="pure-u-15-24 profile-detail prelative" style="width: 95% !important;padding: 20px;margin: auto;">
          <table class="table-no-border2 add-form tabling" style="background-color: #fff;"> 
            <tbody>
              <tr>
                <td class="add-form-noborder title-cm" style="padding: 10px;">
                  <center><font>File Uploader</font></center>
                </td>
              </tr>
              <tr>
                <td class="add-form-noborder" style="padding: 10px;">
                  <center><font>Soon, you will be able to customize the ppt files.</font></center>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
    </div>
</div>

<div id="unit_list"></div>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/main.js"></script>


<script>
var certlev;
  $(document).ready(function(){ 
    $("#certlev").change(function(){
      certlev = $(this).val();
      $("#certlev").attr('disabled', true);
      $("#other").removeClass("hidden");

      $.ajax({
        type:"POST",
        url:"<?php echo site_url('superadmin/coach_script/unit_list_preview');?>",    
        data: {'cert':certlev},        
        success: function(data){ 
          $("#unit_list").html(data);
          $("#addunitarea").removeClass("hidden");
          // console.log(data);
          // var certpull = data.substring(0, 2);
          document.getElementById("certunit").value = certlev;
        }  
       });

      $.post("<?php echo site_url('superadmin/coach_script/pull_unit');?>", { 
        'cert' : certlev },
        function(data) {
          $("#unitlist").html(data); 
        });

      $.post("<?php echo site_url('superadmin/coach_script/pull_unitonly');?>", { 
        'cert' : certlev },
        function(data) {
          $("#unitonly").html(data); 
        });

    });
  });

  $('#other').click(function(){
    $("#certlev").attr('disabled', false);
    $("#unitlist").attr('disabled', false);
    $("#contentlist").empty();
    $("#unitonly").empty();
    $("#addunitarea").addClass("hidden");
    $("#other").addClass("hidden");
    $("#other2").addClass("hidden");
    var sel = $("#unitlist");
    sel.empty();
  });
</script>
<script>
  $('#addunitbtn').click(function(){
    var certpullunit = document.getElementById("certunit").value;
    var unittitleinput = document.getElementById("unittitleinput").value;
    
    if($.trim(unittitleinput).length > 0){
      
      if (unittitleinput.indexOf('&') > -1)
      {
        unittitleinput = unittitleinput.replace(/&/g,"&amp;")
        // console.log(unittile);
      }
      $.post("<?php echo site_url('superadmin/coach_script/add_unit_action');?>", { 
      'unittitleinput' : $.trim(unittitleinput), 'certpullunit':certpullunit },
      function(data) {
        if(data == "Unit is already exist"){
          $("#unitexist").removeClass("hidden");
        }
        else{
          $("#unitaddsuccess").removeClass("hidden");
        }
      });
    }
    else{
      $("#unitemp").removeClass("hidden");
    }
  });
</script>
<script>
  $('#unitexist').click(function(){
    $("#unitexist").addClass("hidden");
  });
  $('#unitemp').click(function(){
    $("#unitemp").addClass("hidden");
  });
  $('#unitaddsuccess').click(function(){
  	  $("#unittitleinput").val("");
      $("#unitaddsuccess").addClass("hidden");
      $.ajax({
        type:"POST",
        url:"<?php echo site_url('superadmin/coach_script/unit_list_preview');?>",    
        data: {'cert':certlev},        
        success: function(data){ 
          $("#unit_list").html(data);
          $("#addunitarea").removeClass("hidden");
          // console.log(data);
          // var certpull = data.substring(0, 2);
          document.getElementById("certunit").value = certlev;
        }  
       });

      $.post("<?php echo site_url('superadmin/coach_script/pull_unit');?>", { 
        'cert' : certlev },
        function(data) {
          $("#unitlist").html(data); 
        });

      $.post("<?php echo site_url('superadmin/coach_script/pull_unitonly');?>", { 
        'cert' : certlev },
        function(data) {
          $("#unitonly").html(data); 
        });
  });
</script>