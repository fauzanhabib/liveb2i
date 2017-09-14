<html>
<body>
<script src="<?php echo base_url();?>assets/js/script.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/main.js"></script>



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
             </table>
             <?php $no++; } ?>
            </div>
          <?php } ?>
        </div>

    </div>
</div>

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