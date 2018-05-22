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
<link rel="chrome-webstore-item" href="https://chrome.google.com/webstore/detail/efiofhlccdnkddnjdagljnhgoibifhki">
<script src='https://static.opentok.com/v2/js/opentok.min.js'></script>
<script src="<?php echo base_url();?>assets/js/script.js"></script>
<script charset="utf-8">
    var apiKey = '<?php echo $apiKey ?>';
    var sessionId = '<?php echo $sessionId ?>';
    var token = '<?php echo $token ?>';
    var session = OT.initSession(apiKey, sessionId);
    var publisher;
    var checkcamera;
    initializeSession();
    //Self
    function initializeSession() {

        session.connect(token, function(error) {
            var publisherproperties = {insertMode: 'append',
                                  facingMode: 'user',
                                  width: '100%',
                                  resolution: "640x480",
                                  frameRate:15,
                                  publishVideo: true,
                                  height: 150, name: "<?php echo $this->auth_manager->get_name();?>"};

            publisher = OT.initPublisher('myPublisherElementId',publisherproperties, function (error) {
              if (error) {
                // console.log(error);
                $("#camerablocked").removeClass("hidden");
              } else {
                checkcamera = 0;
              }
            });
            session.publish(publisher);

        });
    //Other
        session.on('streamCreated', function(event) {
            var subscriberProperties = {insertMode: 'append',
                                        width: '100%',
                                        resolution: "640x480",
                                        frameRate:15, name: "<?php echo $user_extract->fullname?>"};
            subscriber = session.subscribe(event.stream,
            'subscriberContainer',
            subscriberProperties,{testNetwork: true},
            function (error) {
              if (error) {
                console.log(error);
              } else {
                // console.log('Subscriber added.');
              }
            });
        });
    }

    function toggleOff(){
      $("#videooff").hide();
      $("#videoon").removeClass("hidden");
      publisher.publishVideo(false);
    }
    function toggleOn(){
      $("#videooff").show();
      $("#videoon").addClass("hidden");
      publisher.publishVideo(true);
    }

    var connectionCount;
        session.on({
          connectionCreated: function (event) {
            connectionCount++;
            if (event.connection.connectionId != session.connection.connectionId) {
              $("#waiting").hide();
              $("#heading1").hide();
              $("#heading2").hide();
              $("#connecting").hide();
              $("#disconnect").addClass("hidden");
            }
          },
          connectionDestroyed: function connectionDestroyedHandler(event) {
            connectionCount--;
              $("#heading2").show();
              $("#connecting").show();
              $("#disconnect").removeClass("hidden");
              $("#heading2").removeClass("hidden");
            console.log('A client disconnected.');
          }
        });
</script>
<script>
    function makeFullScreen(divId){
        document.getElementById("fullarea").webkitRequestFullScreen() //example for Chrome
        var element = document.getElementById("fullarea");
        var fullScreenFunction = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullScreen || element.oRequestFullScreen;

        if (fullScreenFunction) {
            fullScreenFunction.apply(element);
        } else {
            alert("don't know the prefix for this browser");
        }
    }
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
    .OT_subscriber{
      height: 100% !important;
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
    <div class="heading" id="heading1" style="background: #d3ffe6;border-bottom: solid 1px #a3f5c7;border-top: solid 1px #a3f5c7;">
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
    <div class="heading hidden" id="heading2" style="background: #ffe9e9;border-bottom: solid 1px #ffbaba;border-top: solid 1px #ffbaba;">
      <div id="disconnect" class="hidden" style="color: #c36969;font-weight: 400;">
        <b><?php echo $student_name; ?></b> is disconnected, wait for him/her to reconnect. <!-- <span id="countdown2" class="timer2"></span> -->
      </div>
    </div>
    <div class="heading hidden" id="sessionalert" style="background: #ffe9e9;border-left: solid 5px #c87373;">
      <div style="color: #c36969;font-weight: 400;">
        Your session will end in 5 minutes</span></b>
        <button style="float:right;color: #c87373; background:none;border:none;margin:0;padding:0;" id="closesessionalert">
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

        <div class="video-conference width100perc">
          <div class="fullscreenarea" id="fullarea">
              <div class="preloader2" id="connecting">
                <img src="<?php echo base_url();?>assets/images/dyned-circle.png" width="200" class="small-preload2">
              </div>
              <div class="subscriber" id="subscriberContainer"><div class="publisher" id="myPublisherElementId"></div></div>
              <a onclick="makeFullScreen(fullarea)" style="position: absolute; bottom: 30px; right: 35px;">
                <img class="fs-icon" src="<?php echo base_url();?>assets/icon/expand2x.png"></img>
              </a>
              <button id="videooff" class="pure-button btn-small btn-green w3-animate-opacity" onclick="javascript:toggleOff();" data-tooltip="Click to Turn Off Your Camera">Camera is On</button>
              <button id="videoon" class="pure-button btn-small btn-red w3-animate-opacity hidden" onclick="javascript:toggleOn();" data-tooltip="Click to Turn On Your Camera">Camera is Off</button>
          </div>
        </div>
      </div>
    </div>

        <div class="content-bottom padding-l-20 pure-g">
            <div class="pure-u-md-12-24 pure-u-sm-1">
            <div id="reloading">
                <center>
                  <img src="<?php echo base_url();?>assets/icon/spin.gif" width="50" class="small-preload2"><br>
                  If it takes more than 30 seconds, click<br>
                  <a class="text-cl-secondary" id="reloadajax">Reload</a>
                </center>
            </div>

            <div id="ajaxcall"></div>
            </div>
            <div class="coach-comment pure-u-md-12-24 pure-u-sm-1">

                <div class="comment-message padding-t-10 clearfix">
                      <div style="padding-top: 5px;">
                        <div class="live--chat">
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

  // console.log(isSafari)

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
<!-- <script>
var upgradeTime2 = 180;
var seconds2 = upgradeTime2;
function timer2() {
    var days2        = Math.floor(seconds2/24/60/60);
    var hoursLeft2   = Math.floor((seconds2) - (days2*86400));
    var hours2       = Math.floor(hoursLeft2/3600);
    var minutesLeft2 = Math.floor((hoursLeft2) - (hours2*3600));
    var minutes2     = Math.floor(minutesLeft2/60);
    var remainingSeconds2 = seconds2 % 60;
    if (remainingSeconds2 < 10) {
        remainingSeconds2 = "0" + remainingSeconds2;
    }
    document.getElementById('countdown2').innerHTML = minutes2 + ":" + remainingSeconds2;
    if (seconds2 == 0) {
        clearInterval(countdownTimer2);
        document.getElementById('countdown2').innerHTML = "Completed";
    } else {
        seconds2--;
    }
}
var countdownTimer2 = setInterval('timer2()', 1000);
</script> -->
<!-- <script>
var dbsec = '<?php echo $total_sec ?>';
var upgradeTime3 = dbsec - 900;
// var upgradeTime3 = 5;
var seconds3 = upgradeTime3;
function timer3() {
    var days3        = Math.floor(seconds3/24/60/60);
    var hoursLeft3   = Math.floor((seconds3) - (days3*86400));
    var hours3       = Math.floor(hoursLeft3/3600);
    var minutesLeft3 = Math.floor((hoursLeft3) - (hours3*3600));
    var minutes3     = Math.floor(minutesLeft3/60);
    var remainingSeconds3 = seconds3 % 60;
    if (remainingSeconds3 < 10) {
        remainingSeconds3 = "0" + remainingSeconds3;
    }
    document.getElementById('countdown3').innerHTML = minutes3 + ":" + remainingSeconds3;
    if (seconds3 == 0) {
        clearInterval(countdownTimer3);
        document.getElementById('countdown2').innerHTML = "Countdown Ends";
    } else {
        seconds3--;
    }
}
var countdownTimer3 = setInterval('timer3()', 1000);
</script> -->
<script type="text/javascript">
  var secfromdb  = '<?php echo $total_sec ?>';
  var milisecond = secfromdb * 1000;
  window.setTimeout(function() {
    alert(" Your session has ended ");
    window.onbeforeunload = null;
    document.forms['leaving'].submit();
}, milisecond);
</script>
