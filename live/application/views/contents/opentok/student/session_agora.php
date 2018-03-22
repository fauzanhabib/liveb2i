<?php
if(@$user_extract2){
    $student_name = $user_extract2->fullname;
    $std_id = $user_extract2->student_id;
  }else{
    $student_name = $user_extract->fullname;
    $std_id = $user_extract->student_id;
  }

  // echo "<pre>";print_r($apiKey);
  // echo "<pre>";print_r($sessionId);
  // echo "<pre>";print_r($student_name);exit();

?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/main.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/js/script.js"></script>
<!-- <script src="<?php echo base_url();?>assets/js/AgoraRTCSDK-2.1.1.js"></script> -->
<script src="<?php echo base_url();?>assets/js/AgoraRTCSDK-2.2.0.js"></script>
<script type="text/javascript">
  window.onbeforeunload = function() {
    var appointment_id = "<?php echo $appointment_id; ?>";

    setTimeout(function() {
      $.post("<?php echo site_url('opentok/live/session_stay');?>", { 'appointment_id': appointment_id },function(data) {
      });
    }, 1000);

    $.post("<?php echo site_url('opentok/live/session_leave');?>", { 'appointment_id': appointment_id },function(data) {
     });

    return "If you close this page, you will not get a token from this session.";
  };
</script>

  <script>
        $(document).ready(function(){
         var countmsg;
         var checkmsg;

        function tampildata(){
           $.ajax({
            type:"POST",
            url:"<?php echo site_url('opentok/live/ambil_pesan');?>",
            success: function(data){
                //document.getElementById('chat_audio').play();
                $('#isi_chat').html(data);
              }
           });
        }

         $('#kirim').click(function(){
           var pesan = $('#pesan').val();
           var user  = $('#user').val();
           var appointment_id = '<?php echo $appointment_id ?>';
           if (pesan == null || pesan == "") {
                alert("Oops, you can't send an empty chat");
                return false;
            }
            else{
             $.ajax({
              type:"POST",
              url:"<?php echo site_url('opentok/live/kirim_chat');?>",
              data: {'pesan':pesan,'user': user, 'appointment_id': appointment_id},
              success: function(data){
                $('#pesan').val('');
                $('#isi_chat').html(data);
              }
             });
            }
          });

          $('#pesan').keypress(function (e){
          if(e.keyCode == 13){
           var pesan = $('#pesan').val();
           var user = $('#user').val();
           var appointment_id = '<?php echo $appointment_id ?>';
           if (pesan == null || pesan == "") {
                alert("Oops, you can't send an empty chat");
                return false;
            }
            else{
               $.ajax({
                type:"POST",
                url:"<?php echo site_url('opentok/live/kirim_chat');?>",
                data: {'pesan':pesan,'user': user, 'appointment_id': appointment_id},
                success: function(data){
                  $('#pesan').val('');

                  $('#isi_chat').html(data);
                }
               });
            }
          }
          });

          setInterval(
            function(){
              tampildata();
              if (countmsg !== checkmsg){
                document.getElementById('chat_audio').play();
                // console.log(countmsg);
                countmsg = checkmsg;
              }
            },1000);

          });
    </script>
    <script type="text/javascript">
      $('input:text').focus(
      function(){
          $(this).val('');
      });
    </script>


    <!-- ======= -->
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
    <!-- ======= -->

    <style>
    #isi_chat{
    height: 125px;
    overflow-y: scroll;}
    </style>

<style>
   .OT_publisher .OT_video-element, .OT_subscriber .OT_video-element{
      position: relative;
    }
    .OT_widget-container{
      position: relative;
    }
   .publisher {
    position: absolute;
    bottom: 30px;
    right: 82px;
    width: 15%;
    z-index: 200;
    border: 1px solid white;
    border-radius: 1px;
    display: none;
    }
   .subscriber {
    margin: 0 auto;
    top: 0;
    left: 0;
    width: 85%;
    z-index: 100;
    }
    .fs-icon{
      opacity: 0.2;
      transition: opacity 1s;
      display: none;
    }
    .fs-icon:hover{
      opacity: 1;
    }
    .fullscreenarea{
      background-color: black;
    }
    .fullscreenarea:hover .publisher, .fullscreenarea:hover .fs-icon{
    display: block;
    }
    .cancel {
    display: none;
    }
    .btn-red:hover {
    opacity: 1 !important;
    }
    .btn-green:hover {
    opacity: 1 !important;
    }

@media only screen and (max-width: 768px) {
    .publisher {
        display: none;
        width: 25%;
        bottom: 8em;
        right: 5px;
        height: 100px;
    }
}
</style>

<style>
#fullarea:-webkit-full-screen {
  width: 100% !important;
  height: 100% !important;
}

#fullarea:-moz-full-screen {
  width: 100% !important;
 height: 100% !important;
}

#fullarea:-ms-full-screen {
  width: 100% !important;
 height: 100% !important;
}

#fullarea:-o-full-screen {
  width: 100% !important;
  height: 100% !important;
}

#fullarea:full-screen {
  width: 100% !important;
 height: 100% !important;
}
</style>

<style>
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 170px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.7); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    color: black;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 90%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
</style>
<style type="text/css">

@-webkit-keyframes blink {
    0% {
        opacity:1;
    }
    50% {
        background-color: transparent;
    }
    100% {
        opacity:1;
    }
}
@-moz-keyframes blink {
    0% {
        opacity:1;
    }
    50% {
        background-color: transparent;
    }
    100% {
        opacity:1;
    }
}
.objblink {
-webkit-transition: all 1s ease-in-out;
    -moz-transition: all 1s ease-in-out;
    -o-transition: all 1s ease-in-out;
    -ms-transition: all 1s ease-in-out;
    transition: all 1s ease-in-out;

    -webkit-animation-direction: normal;
    -webkit-animation-duration: 1s;
    -webkit-animation-iteration-count: infinite;
    -webkit-animation-name: blink;
    -webkit-animation-timing-function: ease-in-out;

-moz-animation-direction: normal;
    -moz-animation-duration: 1s;
    -moz-animation-iteration-count: infinite;
    -moz-animation-name: blink;
    -moz-animation-timing-function: ease-in-out;
}
.localAgora{
  position: absolute;
  z-index: 10;
  width: 200px;
  height: 150px;
  left: 7px;
}
#video{
  min-height: 300px;
}

/*best fit for student to see the coach*/
.agora_css :nth-child(1) {
  display: flex;
}
.agora_css :nth-child(1) > video {
  width: 61%!important;
  margin: auto;
  position: relative!important;
}
/*best fit for student to see the coach*/

.agora_css :nth-child(3) {
  display: none;
}

@media only screen and (max-width: 425px) {
  .agora_css :nth-child(1) > video {
    width: 100%!important;
    left: 0!important;
  }

  .localAgora {
    width: 100px;
    height: 80px;
  }
}

</style>

<!-- modal -->
  <div class="sess-details-modal remodal max-width900" data-remodal-id="modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
      <div class="pure-g">
          <div class="profile-detail text-left prelative width75perc">
              <h4 class="border-b-1 font-semi-bold text-cl-grey">Session Information</h4>
              <table class="table-no-border2">
                  <tbody>
                      <tr>
                          <td class="">Coach Name</td>
                          <td class="border-none">
                              <span class=""><?php echo $user_extract->fullname?></span>
                          </td>
                      </tr>
                  </tbody>
              </table>
          </div>
      </div>

      <div class="coach-comment width100perc">
          <div class="comment-message padding0 clearfix">
              <div class="text-left">

                  <p><?php echo $sentence.''.$different.' ago.';?></p>
                  <p><?php echo $notes_s;?></p><br>
                  <span>Notes :</span>
                  <table style="width:100%">
                    <tr>
                      <td style="width: 3%" valign="top">1.</td>
                      <td style="width: 90%">If your coach is less than 10 minutes late, your session will still take place. However, you may reschedule the session or request a refund but this should be done immediately upon entering.</td>
                    </tr>
                    <tr>
                      <td style="width: 3%" valign="top">2.</td>
                      <td style="width: 90%">If your coach is more than 10 minutes late, your tokens will be refunded to you and you'll be able to reschedule the session.</td>
                    </tr>
                  </table><br>
                  <div>
                    <a data-remodal-action="confirm" class="pure-button btn-small btn-white" id="closemodal">Join Session</a>
                  </div>

              </div>
          </div>
      </div>
  </div>
  <!-- modal -->
    <div class="heading" id="heading1" style="background: #d3ffe6;border-bottom: solid 1px #a3f5c7;
    border-top: solid 1px #a3f5c7;">
      <div id="waiting" style="color: #419c68;font-weight: 400;">
        Waiting for <b><?php echo $student_name; ?></b> to join the session. Remain in the session until the end in order to receive a refund of your tokens.
        <?php  $appoint_id = $appointment_id; ?>
        <form name ="cancelling" onsubmit="return confirm('Are you sure you want to submit this form?');" action="<?php echo(site_url('opentok/cancelsession/'));?>" method="post">
            <input type="hidden" name="appoint_id" value="<?php echo $appoint_id ?>">
            <input type="hidden" name="flag" value="student">
            <input type="submit" id="asd" value="Cancel Session" class="pure-button btn-small btn-red hidden">
        </form>

      </div>
    </div>
    <div class="heading hidden" id="heading2" style="background: #ffe9e9;border-bottom: solid 1px #ffbaba;
    border-top: solid 1px #ffbaba;">
      <div id="disconnect" class="hidden" style="color: #c36969;font-weight: 400;">
        <b><?php echo $student_name; ?></b> is disconnected, wait for him/her to reconnect. <!-- <span id="countdown2" class="timer2"></span> -->
      </div>
    </div>
    <div class="heading hidden" id="sessionalert" style="background: #ffe9e9;border-left: solid 5px #c87373;">
      <div style="color: #c36969;font-weight: 400;">
        Your session will end in 5 minutes</span></b>
        <button style="float:right;color: #c87373; background:none;
                       border:none;
                       margin:0;
                       padding:0;" id="closesessionalert">
        X</button>
      </div>
    </div>

    <div class="heading objblink hidden" id="camerablocked" style="background: #ffe9e9;border-left: solid 5px #c87373;">
      <div style="color: #c36969;font-weight: 400;opacity: 1 !important;">
        Your browser is blocking your camera, please enable it and then reload the page. See How To:
        <b><a id="myBtn">Chrome</a></b>
      </div>
    </div>
    <div id="myModal" class="modal">
      <div class="modal-content">
        <span class="close">×</span>
        <h2 style="text-align: center;">How To Enable Camera on Chrome:</h2>
          <table style="width:100%">
            <tr>
              <td style="text-align: center;width: 3%" valign="top">1.</td>
              <td style="width: 40%">
                <img src="<?php echo base_url(); ?>assets/img/step1.jpg" style="border: solid 1px black">
              </td>
              <td style="text-align: center;width: 3%" valign="top">2.</td>
              <td style="width: 40%">
                <img src="<?php echo base_url(); ?>assets/img/step2.jpg" style="border: solid 1px black">
              </td>
            </tr>
            <tr>
              <td style="width: 3%" valign="top"></td>
              <td style="width: 40%" valign="top">Click the camera icon at the upper-right<br></td>
              <td style="width: 3%" valign="top"></td>
              <td style="width: 40%">Choose "Always allow...", then choose the appropriate camera and microphone in your device. Click "Done", and reload the page.</td>
            </tr>
          </table>
      </div>
    </div>

    <div class="box">
      <div class="box b-f3-1" style="position:relative;">
        <h3>Remaining Time: <span id="countdown" class="timer"></span></h3>
        <?php $appoint_id = $appointment_id; ?>
        <form name ="leaving" onsubmit="return confirm('Are you sure you want to submit this form?');" action="<?php echo(site_url('opentok/leavesession/'));?>" method="post">
            <input type="hidden" name="appoint_id" value="<?php echo $appoint_id ?>">
            <input type="submit" value="Leave Session" class="pure-button btn-small btn-red hidden">
        </form>
        <div id="div_device" class="panel panel-default" style="display:none;">
            <div class="select">
            <label for="audioSource">Audio source: </label><select id="audioSource"></select>
            </div>
            <div class="select">
            <label for="videoSource">Video source: </label><select id="videoSource"></select>
            </div>
            <input id="channel" type="text" value="1000" size="4"></input>
            <input id="video" type="checkbox" checked></input>
        </div>
        <div class="video-conference width100perc">
          <div id="video" style="margin:0 auto;">
             <div id="agora_local" class="localAgora"></div>
          </div>
          <div id="demo"></div>
        </div>
      </div>
    </div>

    <div class="content-bottom padding-l-20 pure-g">
        <div class="pure-u-md-12-24 pure-u-sm-1">
        <div id="reloading">
            <center>
              <img src="http://idbuild.id.dyned.com/live_v20/assets/icon/spin.gif" width="50" class="small-preload2"><br>
              If it takes more than 30 seconds, click<br>
              <a class="text-cl-secondary" id="reloadajax">Reload</a>
            </center>
        </div>

        <div id="ajaxcall"></div>
        </div>
        <div class="coach-comment pure-u-md-12-24 pure-u-sm-1">

            <div class="comment-message padding-t-10 clearfix">
                  <div style="padding-top: 5px;">
                    <div class="col-md-2">
                      <input placeholder="Please write here..." type="text" id="pesan" class="form-control" style="width: 84%">
                      <input placeholder="<?php echo $this->auth_manager->get_name();?>" type="hidden" id="user" class="form-control" value="<?php echo $this->auth_manager->get_name();?>" disabled>
                      <audio id="chat_audio" src="<?php echo base_url();?>assets/sound/chat.mp3" preload="auto"></audio>
                      <input type="submit" value="Send" id="kirim" class="pure-button btn-small btn-tertiary" style="width: 14%;float: right;height:26px;">
                    </div>
                    <div class="panel-body" style="padding-left: 10px;padding-right: 10px;border: 1px solid #cccccc;border-radius: 5px;margin-top: 3px;">
                        <ul id="isi_chat" style="list-style-type:none;padding-left: 0px;"> <ul>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/Chart.Core.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/Chart.Radar.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/remodal.min.js"></script>

<script>
  var appointment_id = "<?php echo $appointment_id; ?>";
  var stat_check;

  var checksess = setInterval(function() {
    $.post("<?php echo site_url('opentok/live/check_sess');?>", { 'appointment_id': appointment_id },function(data) {
      stat_check = data;
      // console.log(stat_check);
      if (stat_check == 0 || stat_check == '') {
        closetab();
      }
    });
  }, 5000);

  function closetab() {
      clearInterval(checksess);
      window.onbeforeunload = null;
      alert("You're trying to open Live Session in multiple tabs/windows. This page will be closed.");
      window.location.href = "<?php echo site_url('opentok/multiple'); ?>";
    }
</script>

<script>
var modal = document.getElementById('myModal');
var btn   = document.getElementById("myBtn");
var span  = document.getElementsByClassName("close")[0];

btn.onclick = function() {
  modal.style.display = "block";
}

span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>

<script type="text/javascript">

  $( function() {
    var appointment_id = "<?php echo $appointment_id; ?>";
    var std_id = "<?php echo $std_id; ?>";
    // console.log(appointment_id);
                //alert($(this).attr('id'));
    $.post("<?php echo site_url('opentok/call_loader/call_ajax');?>", { 'std_id': std_id },function(data) {
     $("#reloading").hide();
     $("#ajaxcall").html(data);
     // alert(data);
     });

    $.post("<?php echo site_url('opentok/live/store_session');?>", { 'appointment_id': appointment_id },function(data) {
    });

  } );

</script>

<script>
    $(function(){
        var inst = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];
        inst.open();
    });

    $(document).ready(function () {
      $('.remodal-wrapper').unbind('click.remodal');

      $(document).unbind('keydown.remodal');
    });

</script>
<script type="text/javascript">
  var myBtn = document.getElementById('closemodal');

  //add event listener
  myBtn.addEventListener('click', function(event) {
    var inst = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];
        inst.close();
  });
</script>
<script type="text/javascript">
  $('#closesessionalert').click(function(){
    $("#sessionalert").hide();
  });
</script>
<script>
  var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || (typeof safari !== 'undefined' && safari.pushNotification));

  console.log(isSafari)

  if (isSafari == true) {
    $('#ajaxcall').height('100vh');

  } else {
    $('#ajaxcall').height('auto');
  }
</script>
<script>
var upgradeTime = '<?php echo $total_sec ?>';
var seconds = upgradeTime;
function timer() {
    var days        = Math.floor(seconds/24/60/60);
    var hoursLeft   = Math.floor((seconds) - (days*86400));
    var hours       = Math.floor(hoursLeft/3600);
    var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
    var minutes     = Math.floor(minutesLeft/60);
    var remainingSeconds = seconds % 60;
    if (remainingSeconds < 10) {
        remainingSeconds = "0" + remainingSeconds;
    }
    document.getElementById('countdown').innerHTML = minutes + ":" + remainingSeconds;
    if (seconds == 0) {
        clearInterval(countdownTimer);
        document.getElementById('countdown').innerHTML = "Completed";
    }else if ( seconds==300 ){
        $("#sessionalert").removeClass("hidden");
        seconds--;
    }else {
        seconds--;
    }
}
var countdownTimer = setInterval('timer()', 1000);
</script>
<script type="text/javascript">
  var secfromdb  = '<?php echo $total_sec ?>';
  var milisecond = secfromdb * 1000;
  window.setTimeout(function() {
    alert(" Your session has ended ");
    window.onbeforeunload = null;
    document.forms['leaving'].submit();
}, milisecond);
</script>
<script language="javascript">

  if(!AgoraRTC.checkSystemRequirements()) {
    alert("browser is no support webRTC");
  }

  /* select Log type */
  // AgoraRTC.Logger.setLogLevel(AgoraRTC.Logger.NONE);
  // AgoraRTC.Logger.setLogLevel(AgoraRTC.Logger.ERROR);
  // AgoraRTC.Logger.setLogLevel(AgoraRTC.Logger.WARNING);
  // AgoraRTC.Logger.setLogLevel(AgoraRTC.Logger.INFO);
  // AgoraRTC.Logger.setLogLevel(AgoraRTC.Logger.DEBUG);

  /* simulated data to proof setLogLevel() */
  // AgoraRTC.Logger.error('this is error');
  // AgoraRTC.Logger.warning('this is warning');
  // AgoraRTC.Logger.info('this is info');
  // AgoraRTC.Logger.debug('this is debug');

  var client, localStream, camera, microphone;

  var audioSelect = document.querySelector('select#audioSource');
  var videoSelect = document.querySelector('select#videoSource');

  // function join() {
    var app_id = 'ddb77ae4ffb344949b1f95a34b7f9ae0';
    // document.getElementById("join").disabled = true;
    document.getElementById("video").disabled = true;
    var channel_key  = null;
    var channel_name = "<?php echo $sessionId; ?>";
    // console.log("Ch Name = " + channel_name);
    // console.log("Init AgoraRTC client with vendor key: " + app_id);
    client = AgoraRTC.createClient({mode: 'h264_interop'});
    client.init(app_id, function () {
      // console.log("AgoraRTC client initialized");
      client.join(channel_key, channel_name, null, function(uid) {
        console.log("User " + channel_key + " join channel successfully");
        // console.log("=====================================");
        // console.log("Channel Key = " + channel_key);
        // console.log("Channel Value = " + channel.value);
        // console.log("UID = " + uid);
        // console.log("Ch Name = " + channel_name);
        // console.log("=====================================");

        if (document.getElementById("video").checked) {
          camera = videoSource.value;
          microphone = audioSource.value;
          localStream = AgoraRTC.createStream({streamID: uid, audio: true, cameraId: camera, microphoneId: microphone, video: document.getElementById("video").checked, screen: false});
          // localStream = AgoraRTC.createStream({streamID: uid, audio:false, video:false, screen:true, extensionId:"bgjblkhpjchbmfeipbclmfnpohcpjcpn"});

          //localStream = AgoraRTC.createStream({streamID: uid, audio: false, cameraId: camera, microphoneId: microphone, video: false, screen: true, extensionId: 'minllpmhdgpndnkomcoccfekfegnlikg'});
          if (document.getElementById("video").checked) {
            localStream.setVideoProfile('240P');

          }

          // The user has granted access to the camera and mic.
          localStream.on("accessAllowed", function() {
            // console.log("accessAllowed");
          });

          // The user has denied access to the camera and mic.
          localStream.on("accessDenied", function() {
            // console.log("accessDenied");
          });

          localStream.init(function() {
            // console.log("getUserMedia successfully");
            localStream.play('agora_local');

            client.publish(localStream, function (err) {
              // console.log("Publish local stream error: " + err);
            });

            client.on('stream-published', function (evt) {
              // console.log("Publish local stream successfully");
            });
          }, function (err) {
            // console.log("getUserMedia failed", err);
          });
        }
      }, function(err) {
        // console.log("Join channel failed", err);
      });
    }, function (err) {
      // console.log("AgoraRTC client init failed", err);
    });

    channelKey = "";
    client.on('error', function(err) {
      // console.log("Got error msg:", err.reason);
      if (err.reason === 'DYNAMIC_KEY_TIMEOUT') {
        client.renewChannelKey(channelKey, function(){
          // console.log("Renew channel key successfully");
        }, function(err){
          // console.log("Renew channel key failed: ", err);
        });
      }
    });


    client.on('stream-added', function (evt) {
      var stream = evt.stream;
      // console.log("New stream added: " + stream.getId());
      // console.log("Subscribe ", stream);
      client.subscribe(stream, function (err) {
        // console.log("Subscribe stream failed", err);
      });
    });

    client.on('stream-subscribed', function (evt) {
      var stream = evt.stream;
      // console.log("Subscribe remote stream successfully: " + stream.getId());
      if ($('div#video #agora_remote'+stream.getId()).length === 0) {
        $('div#video').append('<div class="agora_css" id="agora_remote'+stream.getId()+'" style="width:100%;height:500px;"></div>');
        // $('video#video'+stream.getId()).addClass('subscriber_video');
        // var video = document.getElementsByTagName("video")[0];
        console.log($('video#video'+stream.getId()));
      }
      $('video#video'+stream.getId()).css('display','none !important');
      $("#heading1").hide();
      stream.play('agora_remote' + stream.getId());
    });

    client.on('stream-removed', function (evt) {
      var stream = evt.stream;
      stream.stop();
      $('#agora_remote' + stream.getId()).remove();
      $("#heading2").hide();
      // console.log("Remote stream is removed " + stream.getId());
    });

    client.on('peer-leave', function (evt) {
      var stream = evt.stream;
      if (stream) {
        stream.stop();
        $('#agora_remote' + stream.getId()).remove();
        // console.log(evt.uid + " leaved from this channel");
      }
    });
  // }

  function leave() {
    // document.getElementById("leave").disabled = true;
    client.leave(function () {
      // console.log("Leavel channel successfully");
    }, function (err) {
      // console.log("Leave channel failed");
    });
  }

  function publish() {
    // document.getElementById("publish").disabled = true;
    // document.getElementById("unpublish").disabled = false;
    client.publish(localStream, function (err) {
      // console.log("Publish local stream error: " + err);
    });
  }

  function unpublish() {
    // document.getElementById("publish").disabled = false;
    // document.getElementById("unpublish").disabled = true;
    client.unpublish(localStream, function (err) {
      // console.log("Unpublish local stream failed" + err);
    });
  }

  function getDevices() {
    AgoraRTC.getDevices(function (devices) {
      for (var i = 0; i !== devices.length; ++i) {
        var device = devices[i];
        var option = document.createElement('option');
        option.value = device.deviceId;
        if (device.kind === 'audioinput') {
          option.text = device.label || 'microphone ' + (audioSelect.length + 1);
          audioSelect.appendChild(option);
        } else if (device.kind === 'videoinput') {
          option.text = device.label || 'camera ' + (videoSelect.length + 1);
          videoSelect.appendChild(option);
        } else {
          // console.log('Some other kind of source/device: ', device);
        }
      }
    });
  }
  //audioSelect.onchange = getDevices;
  //videoSelect.onchange = getDevices;
  getDevices();
</script>
