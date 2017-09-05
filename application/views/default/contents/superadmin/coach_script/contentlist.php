<html>
<body>
<script src="<?php echo base_url();?>assets/js/script.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/main.js"></script>
<script src="<?php echo base_url();?>assets/ckeditor/ckeditor.js"></script>

<style type="text/css">
	#cke_contentaddition{
		/*display: none;*/
		border: solid 1px #f2f2f2 !important;
	}
	.del-btnconf{
		background-color: white;
	    color: #ea5656;
	    font-weight: 600;
	}
	.del-btn{
		background-color: white;
	    border: solid 1px #ea5656;
	    color: #ea5656;
	    font-weight: 600;
	}
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
	  color: #eee;

	  -webkit-transition: all 0.4s ease-in;
	  -moz-transition: all 0.4s ease-in;
	  -o-transition: all 0.4s ease-in;
	  -ms-transition: all 0.4s ease-in;
	  transition: all 0.4s ease-in;
	}
	.edit-button{
		background-color: white;
	    border: solid 1px #59ba82;
	    color: #59ba82;
	    font-weight: 600;
	    font-size: 12px !important;

	    -webkit-transition: all 0.4s ease-out;
		-moz-transition: all 0.4s ease-out;
		-o-transition: all 0.4s ease-out;
		-ms-transition: all 0.4s ease-out;
		transition: all 0.4s ease-out;
	}
	.edit-button:hover{
	  background-color: #59ba82;
	  color: #eee;

	  -webkit-transition: all 0.4s ease-in;
	  -moz-transition: all 0.4s ease-in;
	  -o-transition: all 0.4s ease-in;
	  -ms-transition: all 0.4s ease-in;
	  transition: all 0.4s ease-in;
	}
</style>

<div class="content" style="margin-top: 20px;">
    <div class="box">
    	<div>
          <font style="font-weight: 600;" id="addcontent">Add Content</font>
          <textarea name="contentaddition" id="contentaddition" style="width: 100%;resize: none;border: solid 1px #cccccc;"></textarea>
	      <div id="notifadd" class="hidden" style="cursor: pointer;background: #d3ffe6;padding: 10px;color: #419c68;font-weight: 600;margin-top: 5px;border-left: solid 5px #4fa574;">Content has been added. Click this notification to reload.
	      </div>
	      <div id="notifemp" class="hidden" style="cursor: pointer;background: #fffad3;padding: 10px;color: #988f48;font-weight: 600;margin-top: 5px;border-left: solid 5px #988f48;">You can't insert an empty content.
	      </div>
          <a id="btnadd" class="pure-button btn-blue btn-small" style="margin-top: 5px;">
            Add
          </a>
        </div>
    	<div id="contenteditsuccess" class="hidden" style="cursor: pointer;background: #d3ffe6;padding: 10px;color: #419c68;font-weight: 600;margin-top: 5px;border-left: solid 5px #4fa574;">
          	Content has been edited.
        </div>
	    <table style="margin-top: 15px;">
	    <?php
	    	$n = 0;
	    	foreach($pull_contents as $pc){
	    	$select = 'editor'.$pc->id.''.$pc->certificate_plan;
	    	$n++;
	    ?>
		    <tr style="margin-top:5px;">
		    	<td style="width:3%;text-align:center;"><?php echo $n.'.'; ?></td>
		    	<td style="width:75%">
		    		<div id="<?php echo $select; ?>"><?php echo $pc->script; ?></div>
		    	</td>
		    	<input type="hidden" id="delval" value="">
		    	<td style="width:20%; text-align:center;">
		    		<a id="edit<?php echo $pc->id; ?>" class="pure-button edit-button">Edit</a>
		    		<a id="del<?php echo $pc->id; ?>" class="pure-button del-button">Delete</a>
		    		<font id="delconf<?php echo $pc->id; ?>" class="del-btnconf hidden" style="margin-bottom: 5px;">
		    			Are You Sure?
		    		</font>
		    		<br>
		    		<a id="save<?php echo $pc->id; ?>" class="pure-button edit-button hidden">
                      Save
                    </a>
                    <a id="can<?php echo $pc->id; ?>" class="pure-button del-button hidden">
                      Cancel
                    </a>
		    		<a id="delyes<?php echo $pc->id; ?>" class="pure-button btn-red btn-small text-cl-white hidden">Yes</a>
		    		<a id="delno<?php echo $pc->id; ?>" class="pure-button del-button hidden" style="padding: 0.33em 0.6em;">No</a>
		    	</td>
		    	<input type="hidden" id="ckval2" value="">
		    	<script>
		    		$("#edit<?php echo $pc->id; ?>").click(function(){
		    			document.getElementById("ckval2").value = '<?php echo $select?>';
                    	ckfunc();
                    	$("#del<?php echo $pc->id; ?>").addClass('hidden'); 
                    	$("#edit<?php echo $pc->id; ?>").addClass('hidden'); 
                    	$("#save<?php echo $pc->id; ?>").removeClass('hidden'); 
                    	$("#can<?php echo $pc->id; ?>").removeClass('hidden');
                    });
                    $("#can<?php echo $pc->id; ?>").click(function(){
                    	clear();
                    	$("#can<?php echo $pc->id; ?>").addClass('hidden'); 
                    	$("#save<?php echo $pc->id; ?>").addClass('hidden'); 
                    	$("#edit<?php echo $pc->id; ?>").removeClass('hidden'); 
                    	$("#del<?php echo $pc->id; ?>").removeClass('hidden');
                    });
                    $("#save<?php echo $pc->id; ?>").click(function(){
                    	var pureval = CKEDITOR.instances.<?php echo $select;?>.getData();
                        var pureval2 = pureval.replace("<p>", "");
                        var textbox  = pureval2.replace("</p>", "");
                        var id = <?php echo $pc->id;?>;
                        var cr = '<?php echo $pc->certificate_plan;?>';
                        // console.log(cr);
                        $.ajax({
                          type:"POST",
                          url:"<?php echo site_url('superadmin/coach_script/update_coaching_inside');?>",    
                          data: {'textbox':textbox, 'id':id, 'cr':cr},        
                          success: function(data){ 
                          	$("#contenteditsuccess").removeClass('hidden');
                          	clear();
	                    	$("#can<?php echo $pc->id; ?>").addClass('hidden'); 
	                    	$("#save<?php echo $pc->id; ?>").addClass('hidden'); 
	                    	$("#edit<?php echo $pc->id; ?>").removeClass('hidden'); 
	                    	$("#del<?php echo $pc->id; ?>").removeClass('hidden');
                          }  
                        });
                    });
		    		$("#del<?php echo $pc->id; ?>").click(function(){
                    	$("#del<?php echo $pc->id; ?>").addClass('hidden'); 
                    	$("#edit<?php echo $pc->id; ?>").addClass('hidden'); 
                    	$("#delconf<?php echo $pc->id; ?>").removeClass('hidden'); 
                    	$("#delyes<?php echo $pc->id; ?>").removeClass('hidden'); 
                    	$("#delno<?php echo $pc->id; ?>").removeClass('hidden'); 
                    });
                    $("#delyes<?php echo $pc->id; ?>").click(function(){
                    	document.getElementById("delval").value = '<?php echo $pc->id;?>';
                    	delfunc();
                    });
                    $("#delno<?php echo $pc->id; ?>").click(function(){
                    	$("#del<?php echo $pc->id; ?>").removeClass('hidden'); 
                    	$("#edit<?php echo $pc->id; ?>").removeClass('hidden'); 
                    	$("#delconf<?php echo $pc->id; ?>").addClass('hidden'); 
                    	$("#delyes<?php echo $pc->id; ?>").addClass('hidden'); 
                    	$("#delno<?php echo $pc->id; ?>").addClass('hidden'); 
                    });
		    	</script>
		    </tr>
		<?php } ?>
		</table>
    </div>
</div>
<script>
	$("#contentaddition").click(function(){
    	CKEDITOR.replace( 'contentaddition', {
		  customConfig: '<?php echo base_url();?>assets/ckeditor/config.js'
		});
    });
	
</script>
<script type="text/javascript" id="ckeditor">
	function delfunc(){
    	var selector = document.getElementById('delval').value;
    	var unit = '<?php echo $pull_contents[0]->unit; ?>';
    	// console.log(selector);
    	$.post("<?php echo site_url('superadmin/coach_script/del_content_action');?>", { 
        'selector' : selector },
        function(data) {
          $.post("<?php echo site_url('superadmin/coach_script/pull_content');?>", { 
	        'cert' : unit },
	        function(data) {
	          	var unit = '<?php echo $pull_contents[0]->unit; ?>';
		    	$.post("<?php echo site_url('superadmin/coach_script/pull_content');?>", { 
		        'cert' : unit },
		        function(data) {
		          $("#contentlist").html(data); 
		        });
	        }); 
        });
	}
</script>
<script type="text/javascript" id="ckeditor2">
	function ckfunc(){
    var selector2 = document.getElementById('ckval2').value;
		// console.log(selector2);
	  CKEDITOR.replace( selector2, {
      customConfig: '<?php echo base_url();?>assets/ckeditor/config.js'
    });
	}
  function clear(){
    var selectors2 = document.getElementById('ckval2').value;
    CKEDITOR.instances[selectors2].destroy();
  }
</script>
<script>
	$("#btnadd").click(function(){
    	var content = CKEDITOR.instances.contentaddition.getData();
    	var unit_order = '<?php echo $pull_contents[0]->unit_order; ?>';
    	var unit = '<?php echo $pull_contents[0]->unit; ?>';
    	var cert = '<?php echo $pull_contents[0]->certificate_plan; ?>';

    	if($.trim(content).length > 0){
    		$.post("<?php echo site_url('superadmin/coach_script/add_content_action');?>", { 
	        'content' : $.trim(content), 'unit' : unit, 'cert' : cert, 'unit_order':unit_order },
	        function(data) {
	        	$("#notifadd").removeClass('hidden'); 
	        	// console.log(data);
	        });
    	}else{
    		$("#notifemp").removeClass('hidden');
	    }
    });


	$("#notifadd").click(function(){
    	var unit = '<?php echo $pull_contents[0]->unit; ?>';
    	$.post("<?php echo site_url('superadmin/coach_script/pull_content');?>", { 
        'cert' : unit },
        function(data) {
          $("#contentlist").html(data); 
        });
    });
    $("#notifemp").click(function(){
    	$("#notifemp").addClass('hidden'); 
    });

    $("#addcontent").click(function(){
    	$("#cke_contentaddition").show(); 
    	$("#btnadd").show(); 
    });
</script>

</body>
</html>