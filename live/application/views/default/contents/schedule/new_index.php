<?php
    $day = array(
        'Monday' => 'monday',
        'Tuesday' => 'tuesday',
        'Wednesday' =>'wednesday',
        'Thursday' => 'thursday',
        'Friday' => 'friday',
        'Saturday' => 'saturday',
        'Sunday' => 'sunday'
    );
?>
<style>
.schedbt:hover{
  cursor: pointer;
  text-decoration: underline;
}
.block-date{
  margin-top: 5px;
}
</style>

<div class="heading text-cl-primary border-b-1 padding15">

    <h2 class="margin0">Schedules</h2>

</div>

<div class="box clear-both">

  <div id="alert_sched">

  </div>


  <div class="heading pure-g padding-t-30">
    <div class="left-list-tabs pure-menu pure-menu-horizontal margin0">
        <ul class="pure-menu-list">
            <li class="pure-menu-item pure-menu-selected text-center width150 no-hover"><a href="<?php echo site_url('coach/schedule');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue">Schedules</a></li>
            <li class="pure-menu-item pure-menu-selected text-center width150 no-hover"><a href="<?php echo site_url('coach/day_off');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Days Off</a></li>
        </ul>
    </div>
  </div>

  <div class="content">
    <div class="box">
      <div class="pure-g hdr-table">
          <div class="pure-u-5-24">Days</div>
          <div class="pure-u-8-24">Schedules</div>
          <div class="pure-u-3-24">Timezone</div>
          <div class="pure-u-6-24 text-center">Action</div>
      </div>
      <div class="box schedule-box">
        <?php foreach($day as $d => $value){ ?>
          <div class="pure-g list">
            <div class="pure-u-5-24 day"><?php echo ($d);?></div>
            <div class="pure-u-8-24 date parent<?php echo ($d);?>">
              <?php
              $i = 0;
              array_multisort($schedules);
              foreach($schedules as $sc){
                if($sc['s_day'] == $d){
                  $i++;
              ?>
              <div class="block-date disable <?php echo $d;?>" style="width: 100%; display: block;">
                <span class="text sched<?php echo ($d.$sc['s_block']);?>">Schedule <?php echo $i; ?></span>
                <span class="time"><?php echo $sc['s_start_time']?></span>
                <span>to</span>
                <span class="time"><?php echo $sc['s_end_time']?></span>
                <span class="text edbt schedbt edrem_btn<?php echo ($d);?>" sel_parent="parent<?php echo ($d);?>" sel_edit="<?php echo $d;?>" s_val="<?php echo $sc['s_start_time']?>" id_val="<?php echo $sc['id']?>" b_val="<?php echo $sc['s_block']; ?>" csched="sched<?php echo ($d.$sc['s_block']);?>" style="display:none;">Edit</span>
                <span class="text rembt schedbt edrem_btn<?php echo ($d);?>" b_val="<?php echo $sc['s_block']; ?>" style="display:none;">Remove</span>
              </div>
            <?php } } ?>
              <span class="addmore add<?php echo ($d);?>" dayval="<?php echo ($d);?>" style="display: none;">Add more..</span>
            </div>
            <div class="pure-u-3-24 nw-timezone">
                <?php echo "UTC ".$gmt_val;?>
            </div>
            <div class="pure-u-6-24 edit addbtn" style="display: inline-block;">
                <span class="link-edit editbtn" openedit="add<?php echo ($d);?>" cancel="canceledit<?php echo ($d);?>"; edrem="edrem_btn<?php echo ($d);?>">Edit</span>
                <span class="link-edit cancel canceledit<?php echo ($d);?>" style="display:none;">Cancel</span>
            </div>
          </div>
          <?php } ?>
      </div>

  	</div>
	</div>
</div>


<form id="submit_sched" action="<?php echo(site_url('coach/new_schedule/add_schedule'));?>" method="post">
  <input id="inp_day" name="inp_day" type="hidden">
  <input id="inp_start" name="inp_start" type="hidden">
  <input id="inp_end" name="inp_end" type="hidden">
</form>

<form id="edit_sched" action="<?php echo(site_url('coach/new_schedule/update_schedule'));?>" method="post">
  <input id="edit_day" name="edit_day" type="hidden">
  <input id="edit_start" name="edit_start" type="hidden">
  <input id="edit_end" name="edit_end" type="hidden">
  <input id="edit_block" name="edit_block" type="hidden">
  <input id="edit_id" name="edit_id" type="hidden">
</form>

<form id="del_sched" action="<?php echo(site_url('coach/new_schedule/delete_schedule'));?>" method="post">
  <input id="del_block" name="del_block" type="hidden">
</form>

<script type="text/javascript">

  // Functiod Remove Schedule Start ==============================================
  $('.rembt').click(function(){
    if (confirm('Are you sure you want to REMOVE this schedule?')) {
      b_val = $(this).attr('b_val');
      $('#del_block').val(b_val);
      // return false;
      $('#del_sched').submit();
    } else {

    }
  });
  // Functiod Remove Schedule End   ==============================================


  // Functiod Edit Schedule Start ==============================================
  $('.editbtn').click(function(){
    $('.editbtn').hide();
    openedit = $(this).attr('openedit');
    cancel   = $(this).attr('cancel');
    edrem    = $(this).attr('edrem');
    $('.'+cancel).show();
    $('.'+openedit).show();
    $('.'+edrem).show();
  });

  $('.edbt').click(function(){
    $('.editbtn').hide();
    sel_parent = '.'+$(this).attr('sel_parent');
    sel_edit   = $(this).attr('sel_edit');
    s_val      = $(this).attr('s_val');
    b_val      = $(this).attr('b_val');
    id_val     = $(this).attr('id_val');
    csched     = '.'+$(this).attr('csched');

    sched_count = $(csched).text();

    $('.'+sel_edit).remove('');
    $('.addmore').hide();

    function timepicker_select() {
        $('.timepicker').timepicker({
            defaultTime:s_val,
            minuteStep:set_coach_session_duration,
            showMeridian:false,
            disableFocus:true,
            showInputs: false,
            disableMousewheel: true
        });
    }

    $(sel_parent).append(sched_count+'<div class="block-date" style="width:100%;margin-top:10px;"><span>Start Time</span><div class="select-time frm-time">\n\
    <input type="text" value="'+s_val+'" class="timepickeredbt start_t" onmousemove="timepicker_select()" />\n\
    <span class="icon icon-time"></span></div><span> <span class="remove-field cancel_sched" style="display:inline-block"> Cancel</span><span class="remove-field set_time" style="display:inline-block"> Confirm</span></div></span>');

    $('.timepickeredbt').timepicker({
      defaultTime:s_val,
      minuteStep:set_coach_session_duration,
      showMeridian:false,
      disableFocus:true,
      showInputs: false,
      disableMousewheel: true
    });

    $('.cancel_sched').click(function(){
      location.reload();
    })

    $(".set_time").click(function() {
      $(this).hide();
      st_val = $('.start_t', sel_parent).val();
      console.log(st_val);

      $(sel_parent).append('<div class="block-date" style="width:100%;margin-top:10px;"><span>End Time</span><div class="select-time frm-time">\n\
      <input type="text" value="'+st_val+'" class="timepicker end_t" onmousemove="timepicker_select()" id="start_time_1" />\n\
      <span class="icon icon-time"></span></div><span></span><span class="remove-field cancel_sched" style="display:inline-block"> Cancel</span><span class="remove-field add_sched" style="display:inline-block"> Update Schedule</span>');

      $('.cancel_sched').click(function(){
        location.reload();
      })

      $(".add_sched").click(function() {
        $('#edit_day').val(sel_edit);

        st_val = $('.start_t', sel_parent).val();
        et_val = $('.end_t', sel_parent).val();

        st_com = st_val.slice(0, st_val.indexOf(":"));
        et_com = et_val.slice(0, et_val.indexOf(":"));

        if(st_com.length == '1'){
          st_com = '0'+st_com;
        }
        if(et_com.length == '1'){
          et_com = '0'+et_com;
        }

        st_com = st_com+':00';
        et_com = et_com+':00';

        var timeStart = new Date("01/01/2007 " + st_com).getHours();
        var timeEnd = new Date("01/01/2007 " + et_com).getHours();

        // console.log(st_com.length);
        // console.log(et_com.length);
        // console.log(et_val);

        if(timeEnd <= timeStart){
          // console.log(timeStart);
          // console.log(timeEnd);
          // return false;
          $('#alert_sched').html('<div class="alert warning"><div class="pure-g"><div class="pure-u-1-2"><h3>Warning</h3><p>Invalid Date Order (End Time may not less than or equal to Start Time)</p></div><div class="pure-u-1-2 close"><i class="icon btn-close"></i></div></div></div>');
          // return false;
          $('.btn-close').click(function(){
            $('.warning').remove();
          })
        }else{
          $('#edit_start').val(st_val);
          $('#edit_end').val(et_val);
          $('#edit_block').val(b_val);
          $('#edit_id').val(id_val);

          // return false;
          $('#edit_sched').submit();
        }
        // console.log($('.start_t', $dropdown).val());
        // console.log($('.end_t', $dropdown).val());
      });

    });

  });
  // Functiod Edit Schedule End =================================================

    $('.cancel').click(function(){
      location.reload();
    })

    $('#close_warning').click(function(){
      $('#s_warning').hide();
    });

    var coach_session_duration = '<?php echo $session_duration;?>';
    var convert_coach_session_duration = parseInt(coach_session_duration);


    var set_coach_session_duration = convert_coach_session_duration + 5;

    function timepicker_select() {
        $('.timepicker').timepicker({
            defaultTime:false,
            minuteStep:set_coach_session_duration,
            showMeridian:false,
            disableFocus:true,
            showInputs: false,
            disableMousewheel: true
        });
    }


  // Functiod Add Schedule =====================================================
  $(document).ready(function(){

      $('.timepicker').timepicker({
        defaultTime:false,
        minuteStep:set_coach_session_duration,
        showMeridian:false,
        disableFocus:true,
        showInputs: false,
        disableMousewheel: true
      });

      $(".addmore").click(function() {
        $('.schedbt').hide();
        var $dropdown = $(this).parent();
        var i = $('.block-date', $dropdown).length;
        var ii = i+1;

        day_click = $(this).attr('dayval');
        $('#inp_day').val(day_click);

        $('.addmore').hide();

        $($dropdown).append('Schedule '+ii+'<div class="block-date" style="width:100%;margin-top:10px;"><span>Start Time</span><div class="select-time frm-time">\n\
        <input type="text" name="start_time_'+ii+'" value="0:00" class="timepicker start_t" onmousemove="timepicker_select()" id="start_time_1" />\n\
        <span class="icon icon-time"></span></div><span> <span class="remove-field cancel_sched" style="display:inline-block"> Cancel</span><span class="remove-field set_time" style="display:inline-block"> Confirm</span></div></span>');

        $('.cancel_sched').click(function(){
          location.reload();
        })

        $(".set_time").click(function() {
          $(this).hide();
          st_val = $('.start_t', $dropdown).val();
          console.log(st_val);

          $($dropdown).append('<div class="block-date" style="width:100%;margin-top:10px;"><span>End Time</span><div class="select-time frm-time">\n\
          <input type="text" name="start_time_'+ii+'" value="'+st_val+'" class="timepicker end_t" onmousemove="timepicker_select()" id="start_time_1" />\n\
          <span class="icon icon-time"></span></div><span></span><span class="remove-field cancel_sched" style="display:inline-block"> Cancel</span><span class="remove-field add_sched" style="display:inline-block"> Add Schedule</span>');

          $('.cancel_sched').click(function(){
            location.reload();
          })

          $(".add_sched").click(function() {
            st_val = $('.start_t', $dropdown).val();
            et_val = $('.end_t', $dropdown).val();

            st_com = st_val.slice(0, st_val.indexOf(":"));
            et_com = et_val.slice(0, et_val.indexOf(":"));

            if(st_com.length == '1'){
              st_com = '0'+st_com;
            }
            if(et_com.length == '1'){
              et_com = '0'+et_com;
            }

            st_com = st_com+':00';
            et_com = et_com+':00';

            var timeStart = new Date("01/01/2007 " + st_com).getHours();
            var timeEnd = new Date("01/01/2007 " + et_com).getHours();

            // console.log(st_com.length);
            // console.log(et_com.length);
            // console.log(et_val);

            if(timeEnd <= timeStart){
              // console.log(timeStart);
              // console.log(timeEnd);
              // return false;
              $('#alert_sched').html('<div class="alert warning"><div class="pure-g"><div class="pure-u-1-2"><h3>Warning</h3><p>Invalid Date Order (End Time may not less than or equal to Start Time)</p></div><div class="pure-u-1-2 close"><i class="icon btn-close"></i></div></div></div>');
              // return false;
              $('.btn-close').click(function(){
                $('.warning').remove();
              })
            }else{
              $('#inp_start').val(st_val);
              $('#inp_end').val(et_val);

              $('#submit_sched').submit();
            }
            // console.log($('.start_t', $dropdown).val());
            // console.log($('.end_t', $dropdown).val());
          });
        });

      });

  })
  // Functiod Add Schedule =====================================================


</script>
<!-- <script type="text/javascript">
    $(document).ready(function(){
        $(".listTop > a").prepend($("<span/>"));
        $(".listBottom > a").prepend($("<span/>"));
        $(".listTop, .listBottom").click(function(event){
         event.stopPropagation();
         $(this).children("ul").slideToggle();
       });
    });
</script> -->
<!--        <a href="https://idbuild.id.dyned.com/live_v20/index.php/lang_switch/switch_language/english">English</a>
        <a href="https://idbuild.id.dyned.com/live_v20/index.php/lang_switch/switch_language/traditional-chinese">Chinese</a>-->
