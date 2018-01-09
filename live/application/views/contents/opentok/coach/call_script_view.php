<html>
<body>
<script src="<?php echo base_url();?>assets/js/script.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/main.js"></script>
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
<style>
  #refreshScript{
    border: solid 1px #2e89b8;
  }
  #refreshScript:hover{
   cursor: pointer;
   color: #2e89b8;
   background: white;
  }
</style>

<div id='tab-right1'>
  <ul class="accordion_book">
    <!-- First Time Loaded-->
    <?php if(!@$script){ ?>
      <p style="color: black;text-align: center;">
          It's the first time for student to attend a session. Scripts are just stored and you can now refresh to load the scripts.
      </p>

      <div class="box-capsule width20perc font-14" style="margin: auto;border-radius: 50px;" id="refreshScript">
          <span>Refresh</span>
      </div>
    <?php } ?>
    <!-- HISTORY SCRIPTS STARTS-->
    <?php
    if(@$script_hist){
        foreach ($script_hist as $sh) {

          $pull_mt1 = $this->db->select('*')
                      ->from('b2c_script')
                      ->where('unit', $sh->unit)
                      ->get()->result();

          $has_mt1 = $pull_mt1[0]->has_mt;

          if(@$has_mt1){
    ?>
      <div class="bxhistory__devider">
          <div class="flex__column">
              <span><?php echo $has_mt1; ?></span>
          </div>
      </div>
    <?php } ?>
      <li class="accordion-item">
          <div class="bxhistory__boxnotifstatus bg-paleYellow">
              <div class="flex__column">
                  <label><?php echo $sh->unit; ?></label>
              </div>

              <i class="icon icon-arrow-down narrow-click"></i>
          </div>

          <div class="accordion__panel--history" style="display: none;">
          <?php
           $wm1 = $sh->unit;

           $av = $this->db->select('*')
                       ->from('b2c_script s')
                       ->join('b2c_script_student cs', 's.id = cs.script_id')
                       ->where('s.unit', $wm1)
                       ->where('s.certificate_plan', $std_cert)
                       ->where('cs.user_id', $std_id_for_cert)
                       ->get()->result();

           $no = 2;
          //  echo "<pre>";print_r($av);exit();
           foreach ($av as $v){
            //  $status_script = $v->status;
          ?>
              <div class="bxhistory__boxstatus">
                <?php
                 if($v->script_type == 0){
                 if($v->status == 0){ ?>
                   <div class="checkbox_input">
                     <input class="checkbox_trigger" type="checkbox" value="1">
                 </div>
                <?php } else if($v->status == 1){ ?>
                  <div class="checkbox_input">
                      <input class="checkbox_trigger" type="checkbox" value="1" checked="checked" disabled>
                  </div>
                <?php } ?>
                  <!-- MODAL -->
                  <div class="modal-wrapper">
                      <div class="modal__checkbox">
                          <div class="modal__content">
                              <div>Are you sure?</div>
                              <span><a class="checked__checkbox" idscript="<?php echo $v->id; ?>">Yes</a></span>
                              <span><a class="span-close">No</a></span>
                          </div>
                      </div>
                  </div>
                  <!-- MODAL -->

                  <label><?php echo $v->script; ?></label>
                <?php } else{?>
                  <label><?php echo $v->script; ?></label>
                <?php } ?>
              </div>
          <?php } ?>
          </div>

      </li>
    <?php } } ?>
    <!-- HISTORY SCRIPTS ENDS-->

    <!-- CURRENT SCRIPTS STARTS-->
    <?php
    if(@$script_curr){
        foreach ($script_curr as $sc) {

          $pull_mt2 = $this->db->select('*')
                      ->from('b2c_script')
                      ->where('unit', $sc->unit)
                      ->get()->result();

          $has_mt2 = $pull_mt2[0]->has_mt;

          if(@$has_mt2){
    ?>
      <div class="bxhistory__devider">
          <div class="flex__column">
              <span><?php echo $has_mt2; ?></span>
          </div>
      </div>
    <?php } ?>
      <li class="accordion-item">
          <div class="bxhistory__boxnotifstatus bg-yellow">
              <div class="flex__column">
                  <label><?php echo $sc->unit; ?></label>
              </div>

              <i class="icon icon-arrow-down narrow-click"></i>
          </div>

          <div class="accordion__panel--history" style="display: none;">
          <?php
           $wm = $sc->unit;

           $bv = $this->db->select('*')
                       ->from('b2c_script s')
                       ->join('b2c_script_student cs', 's.id = cs.script_id')
                       ->where('s.unit', $wm)
                       ->where('s.certificate_plan', $std_cert)
                       ->where('cs.user_id', $std_id_for_cert)
                       ->get()->result();

           $no = 2;
          //  echo "<pre>";print_r($bv);exit();
           foreach ($bv as $v){
            //  $status_script = $v->status;
          ?>
            <div class="bxhistory__boxstatus">
              <?php
               if($v->script_type == 0){
               if($v->status == 0){ ?>
                 <div class="checkbox_input">
                     <input class="checkbox_trigger" type="checkbox" value="1">
                 </div>
              <?php } else if($v->status == 1){ ?>
                <div class="checkbox_input">
                    <input class="checkbox_trigger" type="checkbox" value="1" checked="checked" disabled>
                </div>
              <?php } ?>
                <!-- MODAL -->
                <div class="modal-wrapper">
                    <div class="modal__checkbox">
                        <div class="modal__content">
                            <div>Are you sure?</div>
                            <span><a class="checked__checkbox" idscript="<?php echo $v->id; ?>">Yes</a></span>
                            <span><a class="span-close">No</a></span>
                        </div>
                    </div>
                </div>
                <!-- MODAL -->

                <label><?php echo $v->script; ?></label>
              <?php } else{?>
                <label><?php echo $v->script; ?></label>
              <?php } ?>
            </div>
          <?php } ?>
          </div>

      </li>
    <?php } } ?>
    <!-- CURRENT SCRIPTS END -->

    <!-- NEXT SCRIPTS STARTS -->
    <div class="next__coachingScript">
        <div class="toggleScript">
            <?php if(@$script_next){ ?>
            <span>click to see next coaching script</span>
            <i class="icon icon-arrow-down"></i>
          <?php }else {?>
            <!-- <span>end of coaching scripts</span> -->
          <?php } ?>
        </div>

        <div class="disable__coachingScript" style="display:none;">
          <?php
          if(@$script_next){
              foreach ($script_next as $sn) {

                $pull_mt3 = $this->db->select('*')
                            ->from('b2c_script')
                            ->where('unit', $sn->unit)
                            ->get()->result();

                $has_mt3 = $pull_mt3[0]->has_mt;

                if(@$has_mt3){
          ?>
            <div class="bxhistory__devider">
                <div class="flex__column">
                    <span><?php echo $has_mt3; ?></span>
                </div>
            </div>
          <?php } ?>
            <li class="accordion-item">
                <div class="bxhistory__boxnotifstatus bg-lightGrey">
                    <div class="flex__column">
                        <label><?php echo $sn->unit; ?></label>
                    </div>

                    <i class="icon icon-arrow-down narrow-click"></i>
                </div>

                <div class="accordion__panel--history" style="display: none;">
                <?php
                 $wm3 = $sn->unit;

                 $cv = $this->db->select('*')
                             ->from('b2c_script s')
                             ->join('b2c_script_student cs', 's.id = cs.script_id')
                             ->where('s.unit', $wm3)
                             ->where('s.certificate_plan', $std_cert)
                             ->where('cs.user_id', $std_id_for_cert)
                             ->get()->result();

                 $no = 2;
                //  echo "<pre>";print_r($cv);exit();
                 foreach ($cv as $v){
                  //  $status_script = $v->status;
                ?>
                    <div class="bxhistory__boxstatus">
                      <?php if($v->status == 0){ ?>
                        <div class="checkbox_input">
                          <input class="checkbox_trigger" type="checkbox" value="1">
                        </div>
                      <?php } else if($v->status == 1){ ?>
                        <div class="checkbox_input">
                            <input class="checkbox_trigger" type="checkbox" value="1" checked="checked" disabled>
                        </div>
                      <?php } ?>
                        <!-- MODAL -->
                        <div class="modal-wrapper">
                            <div class="modal__checkbox">
                                <div class="modal__content">
                                    <div>Are you sure?</div>
                                    <span><a class="checked__checkbox" idscript="<?php echo $v->id; ?>">Yes</a></span>
                                    <span><a class="span-close">No</a></span>
                                </div>
                            </div>
                        </div>
                        <!-- MODAL -->

                        <label><?php echo $v->script; ?></label>
                    </div>
                <?php } ?>
                </div>

            </li>
          <?php } } ?>

        </div>
    </div>
    <!-- NEXT SCRIPTS ENDS -->

  </ul>
</div>

<script>
    // MODAL
    $('.checkbox_trigger').each(function() {
        $(this).click(function() {
          // console.log($(this).parent().next());
            $(this).parent().next().addClass('open');
            return false;
        });
    });
    $('a.btn-close').click(function() {
        $(this).parents('.modal-wrapper').removeClass('open');
    });
    $('a.span-close').click(function() {
        $(this).parents('.modal-wrapper').removeClass('open');
    });

    $('.checked__checkbox').each(function() {
        $(this).click(function() {
          // console.log($(this).parents('.bxhistory__boxstatus').find('.checkbox_trigger'));
            var script_id = $(this).attr('idscript');
            // console.log(script_id);
            $.ajax({
              type:"POST",
              url:"<?php echo site_url('opentok/call_script/update_script');?>",
              data: {
                'std_id' : <?php echo $std_id_for_cert; ?>,
                'script_id' : script_id
                },
              success: function(data){
                // console.log(data);

              }
            });
            $(this).parents('.bxhistory__boxstatus').find('.checkbox_trigger').prop("checked", true);
            $(this).parents('.bxhistory__boxstatus').find('.checkbox_trigger').attr('disabled', true);
            $(this).parent().parent().parent().parent().removeClass('open');

        })
    })
</script>
<script>
    $('.bxhistory__boxnotifstatus').click( function() {
      $(this).next(".accordion__panel--history").slideToggle();
    });

    $('.toggleScript').click(function() {
        $('.disable__coachingScript').slideToggle();
    });
</script>


<!-- <script type="text/javascript">
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
</script> -->

<script>
$("#refreshScript").click(function() {
    var std_id = "<?php echo $std_id_for_cert; ?>";
    $("#reloading2").show();
    // console.log('asdsafasf');
    $("#ajaxscript").hide();
    $.post("<?php echo site_url('opentok/call_script/call_ajax');?>", { 'std_id': std_id },function(data) {
      $("#reloading2").hide();
      $("#ajaxscript").show();
      $("#ajaxscript").html(data);
     });

} );
</script>
</body>
</html>
