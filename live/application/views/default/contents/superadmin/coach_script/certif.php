<html>
<body>
<script src="<?php echo base_url();?>assets/js/script.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/main.js"></script>

<style>
  .editcontent{
    /*color: #2b89b9;*/
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    transition: all 0.3s ease;
  }
  .editcontent:hover{
    color: #2b89b9;
  }
  .savecontent{
    background: white;
    border: solid 1px #2b89b9;
    color: #2b89b9;
    border-radius: 15px;
    /*color: #2b89b9;*/
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    transition: all 0.3s ease;
  }
  .savecontent:hover{
    color: #fff;
    background: #2b89b9;
  }
  .delcontent{
    background: white;
    border: solid 1px #ea5656;
    color: #ea5656;
    border-radius: 15px;
    /*color: #ea5656;*/
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    transition: all 0.3s ease;
  }
  .delcontent:hover{
    color: #fff;
    background: #ea5656;
  }
</style>

<div class="content">
    <div class="box">
        <div class="pure-u-1 text-center m-t-20 w3-animate-bottom" style="margin-bottom: 20px;">
            <div class="pure-button btn-small btn-tertiary height-32">Cerification Plan: <?php echo $cert; ?></div>
        </div>

        <?php foreach ($a1 as $as) { ?>
        <div class="accordion w3-animate-opacity text-center"><?php echo $as->unit; ?></div>
            <div class="panel"> 
             <?php
                $wm = $as->unit;

                $av = $this->db->select('*')
                            ->from('script s')
                            ->where('s.unit', $wm)
                            ->where('s.certificate_plan', $cert)
                            ->get()->result();

                $no = 2;
                foreach ($av as $v){
             ?>
             <table style="width: 100%;">
             <?php

             $wm = 'editor'.$v->id.''.$v->certificate_plan;
             $id = $v->id;
             $cr = $v->certificate_plan;
             ?>

                <td style="width: 90%;border-bottom: 1px solid #eee;">
                    <p style="font-size: 14px; color:black;" id="cur<?php echo $wm;?>">
                      <?php 
                          echo $v->script;
                      ?>
                    </p>
                    <div class="hidden" id="upd<?php echo $wm;?>">
                        <textarea name="<?php echo $wm;?>" id="<?php echo $wm;?>" style="width: 100%;resize: none;"><?php echo $v->script;?></textarea>
                    </div>
                </td>
                <td class="text-center" style="width: 8%;border-bottom: 1px solid #eee;">
                    <a id="editck<?php echo $wm;?>" class="editcontent"><i class="icon icon-edit edit_click" title="Edit" style="display: inline;"></i>
                    </a>
                    <a id="saveck<?php echo $wm;?>" class="pure-button btn-small savecontent hidden">
                      &#10003;
                    </a>
                    <a id="canck<?php echo $wm;?>" class="pure-button btn-small delcontent hidden">
                      &#10005;
                    </a>
                </td>
                <input type="hidden" id="ckval" value="">
                <script>
                    $("#editck<?php echo $wm;?>").click(function(){
                    	document.getElementById("ckval").value = '<?php echo $wm;?>';
                    	ckfunc();
                        $("#ckeditor").hide();
                        $("#cur<?php echo $wm;?>").hide();
                        $("#editck<?php echo $wm;?>").hide();
                        $("#upd<?php echo $wm;?>").removeClass('hidden');
                        $("#saveck<?php echo $wm;?>").removeClass('hidden');
                        $("#canck<?php echo $wm;?>").removeClass('hidden');
                    });

                    $("#canck<?php echo $wm;?>").click(function(){
                        clear();
                        $("#cur<?php echo $wm;?>").show();
                        $("#editck<?php echo $wm;?>").show();
                        $("#upd<?php echo $wm;?>").addClass('hidden');
                        $("#saveck<?php echo $wm;?>").addClass('hidden');
                        $("#canck<?php echo $wm;?>").addClass('hidden');
                    });

                    $("#saveck<?php echo $wm;?>").click(function(){
                        var pureval = CKEDITOR.instances.<?php echo $wm;?>.getData();
                        var pureval2 = pureval.replace("<p>", "");
                        var textbox  = pureval2.replace("</p>", "");
                        var id = <?php echo $id?>;
                        var cr = '<?php echo $cr?>';
                        clear();
                        // console.log(textbox);
                        $.ajax({
                          type:"POST",
                          url:"<?php echo site_url('superadmin/coach_script/update_coaching');?>",    
                          data: {'textbox':textbox, 'id':id, 'cr':cr},        
                          success: function(data){    
                            $("#cur<?php echo $wm;?>").show();
                            $("#editck<?php echo $wm;?>").show();
                            $("#upd<?php echo $wm;?>").addClass('hidden');
                            $("#saveck<?php echo $wm;?>").addClass('hidden');
                            $("#canck<?php echo $wm;?>").addClass('hidden');
                            $("#coachnoteupdated").removeClass('hidden');
                          }  
                         });
                    });
                </script>
             </table>
             <?php $no++; } ?>
            </div>
          <?php } ?>
        </div>

    </div>
</div>

<script src="<?php echo base_url();?>assets/ckeditor/ckeditor.js"></script>

<script type="text/javascript" id="ckeditor">
	function ckfunc(){
    var selector = document.getElementById('ckval').value;
		// console.log(selector);
		CKEDITOR.replace( selector, {
      customConfig: '<?php echo base_url();?>assets/ckeditor/config.js'
    });
	}
  function clear(){
    var selectors = document.getElementById('ckval').value;
    CKEDITOR.instances[selectors].destroy();
  }
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

</body>
</html>