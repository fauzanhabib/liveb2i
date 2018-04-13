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
  // echo "<pre>";print_r($token);exit();
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/js/script.js"></script>
<script src="<?php echo base_url();?>assets/js/AgoraRTCSDK-2.1.1.js"></script>
<!-- <script src="<?php echo base_url();?>assets/js/AgoraRTCSDK-2.2.0.js"></script> -->

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
                $('#tabs-content1').show();
                $('.tabs2').show();
                $content.show();

                // Prevent the anchor's default click action
                e.preventDefault();
            });
        });
    });
</script>

<!-- <script src='//static.opentok.com/v2/js/opentok.min.js'></script> -->
<!-- <script charset="utf-8">
    var apiKey = '<?php echo $apiKey ?>';
    var sessionId = '<?php echo $sessionId ?>';
    var token = '<?php echo $token ?>';
    var session = OT.initSession(apiKey, sessionId);
    var ffWhitelistVersion = '36';
    var extensionId = 'efiofhlccdnkddnjdagljnhgoibifhki';
    var extensionId2 = 'hkdnamkhchfoobjlmpfkpcchcpmhfiol';
    var publisher;
    var checkcamera;
    //Self
        session.connect(token, function(error) {
            var publisherproperties = {insertMode: 'append',
                                  width: '100%',
                                  resolution: "320x240",
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
                                        resolution: "320x240",
                                        frameRate:15,
                                        height: '100%', name: "<?php echo $student_name;?>"};
            var subscriber = session.subscribe(event.stream,
            'subscriberContainer',
            subscriberProperties,
            function (error) {
              if (error) {
                console.log(error);
              } else {
                console.log('Subscriber added.');
              }
              });
        });

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


    OT.registerScreenSharingExtension('chrome', extensionId, 2);

    OT.checkScreenSharingCapability(function(response){
      if (response.extensionInstalled === false) {
        OT.registerScreenSharingExtension('chrome', extensionId2, 2);
        extensionId = extensionId2;
        // alert(extensionId2);
      }
    });
    // OT.registerScreenSharingExtension('chrome', extensionId2, 2);

    function screenshare() {
      OT.checkScreenSharingCapability(function(response) {
      // console.log(response.extensionInstalled);
      // console.info(response);
      if (!response.supported || response.extensionRegistered === false) {
        alert('This browser does not support screen sharing.');
      } else if (response.extensionInstalled === false
        && (response.extensionRequired || !ffWhitelistVersion)) {
        alert('Please install the screen-sharing extension and load this page over HTTPS.');
      } else if (ffWhitelistVersion && navigator.userAgent.match(/Firefox/)
        && navigator.userAgent.match(/Firefox\/(\d+)/)[1] < ffWhitelistVersion) {
        alert('For screen sharing, please update your version of Firefox to '
          + ffWhitelistVersion + '.');
      } else {

        // Screen sharing is available. Publish the screen.
        // Create an element, but do not display it in the HTML DOM:
        var screenContainerElement = document.createElement('div');
        var screenSharingPublisher = OT.initPublisher(
        screenContainerElement,
        { videoSource : 'screen' },
        function(error) {
          if (error) {
            // console.log(error);
          alert('Something went wrong: ' + error.message);
          } else {
          session.publish(
            screenSharingPublisher,
            function(error) {
            if (error) {
              alert('Something went wrong: ' + error.message);
            }
            });
          $("#sharescreenava").hide();
          console.log("started");
          }
        });
        screenSharingPublisher.on('mediaStopped', function(event) {
          $("#sharescreenava").show();
          // console.log("ended");
        });
        }
      });
    }


</script> -->
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
     var sendername;

    function tampildata(){
       $.ajax({
        type:"POST",
        url:"<?php echo site_url('opentok/live/ambil_pesan');?>",
        success: function(data){
          $('#isi_chat').html(data);
          checkmsg = data.length;
          sendername = data;
        }
       });
    }


     $('#kirim').click(function(){
      // console.log('chat');
       var pesan = $('#pesan').val();
       var user = $('#user').val();
       $('#pesan').val('');
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
                countmsg = data.length;
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
           $('#pesan').val('');
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
      // console.log(checkmsg);
      setInterval(
        function(){
          tampildata();
          if (countmsg !== checkmsg && checkmsg !== 0){
            countmsg = checkmsg;
            document.getElementById('chat_audio').play();
            $('#tabsss').addClass('reddot_notif');
            // console.log(sendername);
          }
        },1000);
    });
</script>

<!-- COACH NOTE STARTS -->
<script>
$(document).ready(function(){

    $('#save_note').click(function(){
       // var cch_note = $("#cch_note").val();
       var cch_note = CKEDITOR.instances.cch_note.getData();
       var appointment_id = '<?php echo $appointment_id ?>';
        if ( !cch_note )
        {
          alert(appointment_id);
          return false;
        }else{
          // alert( appointment_id );
          $.ajax({
            type:"POST",
            url:"<?php echo site_url('opentok/live/save_cchnote');?>",
            data: {
              'cch_note' : cch_note,
              'appointment_id' : appointment_id
              },
            success: function(data){
              $("#coachnoteupdated").removeClass("hidden");
            }
          });
        }
      });

});
</script>
<!-- COACH NOTE ENDS -->

<!-- COACH SCRIPT STARTS -->
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

<!-- COACH SCRIPT ENDS -->

<style>
  #isi_chat{
    height:277px;
    overflow-y: scroll;
  }
</style>

<style>
    .OT_publisher .OT_video-element, .OT_subscriber .OT_video-element{
      position: relative;
    }
    .OT_widget-container{
      position: relative;
    }
    .hidden { display: none; }
    .publisher {
      position: absolute;
      bottom: 44px;
      right: 14px;
      width: 30%;
      z-index: 200;
      border: 1px solid white;
      border-radius: 1px;
      display: none;
    }
    .subscriber {
      top: 0;
      left: 0;
      width: 100%;
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
    .btn-red:hover {
    opacity: 1 !important;
    }
    .btn-green:hover {
    opacity: 1 !important;
    }
@media only screen and (max-width: 768px) {
    .publisher {
        display: none;
        width: 50%;
        bottom: 10em;
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

<style type="text/css">
div.accordion {
    background-color: #eee;
    color: #444;
    cursor: pointer;
    padding: 5px;
    width: 100%;
    border-top: 2px solid #fff;
    outline: none;
    transition: 0.4s;
}

/* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
div.accordion.active, div.accordion:hover {
    background-color: #3baae3;
    border-top: 2px solid #fff;
    color:#fff;
    opacity: 1 !important;
}

/* Style the accordion panel. Note: hidden by default */
div.panel {
    background-color: white;
    display: none;
}

/* The "show" class is added to the accordion panel when the user clicks on one of the buttons. This will show the panel content */
div.panel.show {
    display: block !important;
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

  .agora_css :nth-child(3) {
    display: none;
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
<style>
  .cke_editable{
    line-height: 0 !important;
  }
  #cke_1_top{
    background: #eeeeee !important;
  }
  #cke_1_contents{
    height: 100px !important;
    border-bottom: solid 1px #f2f2f2 !important;
    border-right: solid 1px #f2f2f2 !important;
    border-left: solid 1px #f2f2f2 !important;
  }
</style>

<style>
.localAgora{
  position: absolute;
  z-index: 10;
  width: 150px;
  height: 150px;
}
#video{
  min-height: 300px;
}
</style>
<!-- modal -->
<audio id="chat_audio" src="<?php echo base_url();?>assets/sound/chat.mp3" preload="auto"></audio>
  <div class="sess-details-modal remodal max-width900" data-remodal-id="modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
      <div class="pure-g">
          <div class="profile-detail text-left prelative width75perc">
              <h4 class="border-b-1 font-semi-bold text-cl-grey">Session Information</h4>
              <table class="table-no-border2">
                  <tbody>
                      <tr>
                          <td class="">Student Name</td>
                          <td class="border-none">
                              <span class=""><?php echo @$user_extract->fullname?></span>
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
                  <p><?php echo $notes_c;?></p><br>
                  <span>Notes :</span>
                  <table style="width:100%">
                    <tr>
                      <td style="width: 3%" valign="top">1.</td>
                      <td style="width: 90%">If a student is late by less than 10 minutes to a session, he/she can participate in the session, but only for up to the session limit (No extended time).</td>
                    </tr>
<!--                     <tr>
                      <td style="width: 3%" valign="top">2.</td>
                      <td style="width: 90%">If a student is late by more than 10 minutes to a session, he/she can't join the session.</td>
                    </tr> -->
                  </table>
                  <br>
                  <div>
                  <?php
                  $duration   = $different_val;

                  if($duration <= 300){
                    // echo "<pre>";
                    //   print_r($different_val);
                    //   exit();
                  ?>
                    <a data-remodal-action="confirm" class="pure-button btn-small btn-white" id="closemodal">Join Session</a>
                  <?php
                    }else if($duration > 300){
                      $livesession = array(
                        'user_extract'   => $user_extract,
                        'appointment_id' => $appointment_id
                      );
                      $appoint_id = $appointment_id;
                      // echo "<pre>";
                      // print_r($appoint_id);
                      // exit();
                  ?>
                    <a data-remodal-action="confirm" class="pure-button btn-small btn-white" id="closemodal">Join Session</a>
                  <?php
                    }
                  ?>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- modal -->

    <div class="heading" id="heading1" style="background: #d3ffe6;border-left: solid 5px #4fa574">
      <div id="waiting" style="color: #419c68;font-weight: 400;">
        Waiting for <b><?php echo $student_name; ?></b> to join the session. Remain in the session until the end to receive your tokens.
        <!-- <?php if($total_sec <= 300) {?>
        If <b><?php echo $student_name; ?></b> isn't connected, <br>you have to wait until the end of session in case <b><?php echo $student_name; ?></b> shows up. And you will get your token at the end of session.
        <?php } else{ ?>
          Remain in the session until the end to receive your tokens.
        <?php } ?> -->
      </div>
    </div>
    <div class="heading hidden" id="heading2" style="background: #ffe9e9;border-left: solid 5px #c87373;">
      <div id="disconnect" class="hidden" style="color: #c36969;font-weight: 400;">
        <b><?php echo $student_name; ?></b> is disconnected, wait for <b><?php echo $student_name; ?></b> to reconnect.
      </div>
    </div>
    <div class="heading hidden" id="sessionalert" style="background: #ffe9e9;border-left: solid 5px #c87373;">
      <div style="color: #c36969;font-weight: 400;">
        Your session will end in 5 minutes</span></b>
        <button style="float:right;color: #c87373; background:none;border:none;margin:0;padding:0;" id="closesessionalert">
        X</button>
      </div>
    </div>
    <div class="heading hidden" id="coachnoteupdated" style="background: #d3ffe6;border-left: solid 5px #4fa574">
      <div style="color: #419c68;font-weight: 400;">
        Your notes have been saved</span></b>
        <button style="float:right;color: #419c68; background:none;border:none;margin:0;padding:0;" id="closenotealert">
        X</button>
      </div>
    </div>
    <div class="heading hidden" id="coachsciptupdated" style="background: #d3ffe6;border-left: solid 5px #4fa574">
      <div style="color: #419c68;font-weight: 400;">
        Scripts Updated</span></b>
        <button style="float:right;color: #419c68; background:none;border:none;margin:0;padding:0;" id="closecoachscript">
        X</button>
      </div>
    </div>

    <div class="heading objblink hidden" id="camerablocked" style="background: #ffe9e9;border-left: solid 5px #c87373;">
      <div style="color: #c36969;font-weight: 400;">
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


    <div class="box pure-g">
        <div class="content padding-r-0 width52perc pure-u-md-6-12">
            <div class="video-conference">
              <!-- =============== -->
              <div class="box b-f3-1" style="position:relative;">

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
                <h3 style="color:white;">Remaining Time: <span id="countdown" class="timer"></span></h3>
                <div class="content padding15" style="height:350px;">
                  <div id="video" style="margin:0 auto;">
                     <div id="agora_local" class="localAgora"></div>
                  </div>
                  <button class="hidden" id="sharescreench_ava">Share your screen</button>
                  <a href="https://chrome.google.com/webstore/detail/agora-web-screensharing/bgjblkhpjchbmfeipbclmfnpohcpjcpn/" target="_blank" class="pure-button btn-small btn-tertiary w3-animate-opacity hidden" id="sharescreench_nava">Install Screen Sharing</a>
                  <!-- <button id="videooff" class="pure-button btn-small btn-green w3-animate-opacity" onclick="javascript:toggleOff();" data-tooltip="Click to Turn Off Your Camera">Camera is On</button>
                  <button id="videoon" class="pure-button btn-small btn-red w3-animate-opacity hidden" onclick="javascript:toggleOn();" data-tooltip="Click to Turn On Your Camera">Camera is Off</button> -->

                  <form name ="leaving" action="<?php echo(site_url('opentok/leavesession/'));?>" method="post">
                      <?php $appoint_id = $appointment_id; ?>
                      <input type="hidden" name="appoint_id" value="<?php echo $appoint_id ?>">
                      <input type="submit" value="Leave Session" class="pure-button btn-small btn-red cancel hidden">
                  </form>

                  <div id="demo"></div>
                </div>
              </div>
              <!-- =============== -->
            </div>
            <ul class='tabs padding-l-0 clearfix'>
                <li><a href='#tab3'>Coach's Note</a></li>
                <li><a href='#tab4' id="tabsss" class="">Chat</a></li>
            </ul>

            <div id='tab3' class="w3-animate-opacity" style="max-height: 325px;">
                <div style="height: 325px !important;">
                    <?php
                      $cn = $this->db->select('*')
                          ->from('appointments')
                          ->where('id', $appointment_id)
                          ->get()->result();

                      $curnotes = @$cn[0]->cch_notes;
                    ?>
                    <div class="comment-form padding-b-10 clearfix">
                        <div class="comment-form-textarea">
                            <textarea style="margin-left: 5px;box-shadow: 0 0 5px #144d80;" name="cch_note" id="cch_note" cols="70" rows="3" ... ><?php echo $curnotes; ?></textarea>
                        </div>
                        <script>
                          CKEDITOR.replace( 'cch_note', {
                            customConfig: '<?php echo base_url();?>assets/ckeditor/config.js'
                          });
                        </script>
                        <div class="save-cancel-btn right" style="width:100%;">
                            <input type="hidden" id="appoint_id_cch" value="<?php echo $appointment_id ?>">
                            <input type="submit" id="save_note" class="pure-button btn-tertiary btn-small" value="Save" style="width:100%;">
                        </div>
                    </div>
                      <div class="box-capsule width20perc font-14">
                          <span>Previous Notes</span>
                      </div>
                      <div style="overflow: auto;max-height: 125px;">
                      <?php
                        // $pull = $user_extract->coach_id;
                        if($user_extract){
                          $cch_id = $user_extract->coach_id;
                          $std_id = $user_extract->student_id;
                        }
                        else
                        {
                          $cch_id = $user_extract2->coach_id;
                          $std_id = $user_extract2->student_id;
                        }

                        $pn = $this->db->select('*')
                            ->from('appointments a')
                            ->join('user_profiles up', 'up.user_id = a.coach_id')
                            ->order_by('a.date', 'DESC')
                            ->like('a.cch_notes', ' ')
                            ->where('a.student_id', $std_id)
                            ->where('a.status', 'completed')
                            ->limit(3)
                            ->get()->result();

                        // echo "<pre>";
                        // print_r($pn);
                        // exit();
                        foreach($pn as $n){
                      ?>
                      <div class="comment-message clearfix">
                          <div class="padding-t-5">
                              <span><?php echo $n->fullname; ?> Wrote on <?php echo $n->date; ?> :</span>
                              <p><?php echo $n->cch_notes; ?></p>
                          </div>
                      </div>
                      <?php } ?>
                    </div>
                </div>
            </div>
            <div id='tab4' class="w3-animate-opacity">
                <!-- ========== -->
                <div class="col-md-12">
                      <div class="col-md-2">
                          <input placeholder="Please write here..." type="text" id="pesan" class="form-control" style="width: 82%;">
                          <input placeholder="<?php echo $this->auth_manager->get_name();?>" type="hidden" id="user"
                              style="width: 15%;float: right;"
                              class="form-control" value="<?php echo $this->auth_manager->get_name();?>" disabled>
                          <input type="button" value="Send" id="kirim" class="pure-button btn-small btn-tertiary"
                              style="width: 15%;float: right;height: 26px;">
                          <ul id="isi_chat" style="list-style-type:none;padding-left: 5px;padding-right: 5px;border: 0.5px solid #cccccc;padding-bottom: 5px;padding-top: 1px;border-radius: 5px;margin-top: 3px;"> <ul>
                      </div>
                </div>
                <!-- ========== -->
            </div>
        </div>
        <div class="sidebar-right padding-l-10 pure-u-md-5-12">
            <ul class='tabs padding0 clearfix'>
              <li class="width33perc"><a href='#tab-right1' class="width87perc">Scripts</a></li>
              <li class="width33perc"><a href='#tab-right2' class="width87perc">Guides</a></li>
              <li class="width33perc"><a href='#tab-right3' class="width87perc">Bag of Tricks</a></li>
            </ul>
            <div id="reloading2">
                <center>
                  <img src="<?php echo base_url();?>assets/icon/spin.gif" width="50" class="small-preload2"><br>
                  If it takes more than 30 seconds, click<br>
                  <a class="text-cl-secondary" id="reloadajax2">Reload</a>
                </center>
            </div>
            <div id="ajaxscript"></div>
        </div>
        <?php
          if(@$gsp->data->certification_level){
          // echo "<pre>";print_r(@$gsp->data->certification_level);exit();
        ?>
          <div class="study__dashboard__top">
              <!-- achievement goal step -->
              <div class="progress__step">
                  <div class="donut__progress">
                      <div class="outter--circle circle"
                           data-thickness="15"
                           data-size="140">
                          <div class="inner--circle circle"
                               data-thickness="15"
                               data-size="110">
                              <div class="donut__progress__info">
                                  <div class="point__progress__info"><?php echo @$gsp->data->certification_level;?></div>
                              </div>
                          </div>
                      </div>

                      <div class="progress__info__label"><?php echo @$gsp->data->total_points_until_today;?> / <?php echo @$gsp->data->total_points_to_pass;?></div>
                  </div>

                  <div class="progress__achievement">
                      <div class="study__progress__achievement">
                          <!-- <div class="bullet__achievement bg-blue-gradient"></div>
                          <div class="bullet__achievement bg-blue-gradient"></div>
                          <div class="bullet__achievement bg-blue-gradient"></div>
                          <div class="bullet__achievement bg-red-gradient"></div>
                          <div class="bullet__achievement"></div>
                          <div class="bullet__achievement"></div> -->
                          <?php
                          for($l=1;$l<=@$max_buletan_student;$l++){ ?>
                            <div class="bullet__achievement <?php echo @$mt_color['mt'.$l];?>"></div>
                          <?php
                            }
                          ?>

                          <div class="achievement__point__info">
                              <h5><?php echo @$gsp->data->study->points_until_today;?></h5>
                              <h3>Study</h3>
                          </div>
                      </div>
                      <div class="coach__progress__achievement">
                          <!-- <div class="bullet__achievement bg-green-gradient"></div>
                          <div class="bullet__achievement bg-green-gradient"></div>
                          <div class="bullet__achievement bg-green-gradient"></div>
                          <div class="bullet__achievement bg-green-gradient"></div>
                          <div class="bullet__achievement bg-red-gradient"></div>
                          <div class="bullet__achievement"></div>
                          <div class="bullet__achievement"></div>
                          <div class="bullet__achievement"></div>
                          <div class="bullet__achievement"></div>
                          <div class="bullet__achievement"></div>
                          <div class="bullet__achievement"></div>
                          <div class="bullet__achievement"></div>
                          <div class="bullet__achievement"></div>
                          <div class="bullet__achievement"></div>
                          <div class="bullet__achievement"></div>
                          <div class="bullet__achievement"></div>
                          <div class="bullet__achievement"></div>
                          <div class="bullet__achievement"></div> -->
                          <?php
                            for($i=1;$i<=$max_buletan;$i++){ ?>
                              <!-- <div class="bullet__achievement <?php echo "a";?>"></div> -->
                              <div class="bullet__achievement <?php echo @$ct_color['cc'.$i];?>"></div>
                            <?php
                              }
                            ?>

                          <div class="achievement__point__info">
                              <h5><?php echo @$gsp->data->coach->points_until_today;?></h5>
                              <h3>Coach</h3>
                          </div>
                      </div>
                  </div>
              </div>
              <!-- end achievement goal step -->

              <!-- top point graph -->
              <div class="progress__point__top">
                  <h5>Points</h5>
                  <div class="graph__wrap">
                      <div class="bar__graph">
                         <?php
                          $bar_now = ( @$gwp->data[0]->points_goal == 0 ? 0 : @$gwp->data[0]->points / @$gwp->data[0]->points_goal) * 100;
                          $bar_w1  = ( @$gwp->data[1]->points_goal == 0 ? 0 : @$gwp->data[1]->points / @$gwp->data[1]->points_goal) * 100;
                          $bar_w2  = ( @$gwp->data[2]->points_goal == 0 ? 0 : @$gwp->data[2]->points / @$gwp->data[2]->points_goal) * 100;
                          $bar_w3  = ( @$gwp->data[3]->points_goal == 0 ? 0 : @$gwp->data[3]->points / @$gwp->data[3]->points_goal) * 100;
                          ?>
                          <ul class="graph b2">
                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php echo $bar_now; ?>">
                                  <span class="graph__legend">Now</span>
                                  <label><?php echo @$gwp->data[0]->points;?></label>
                                  </li>
                              </span>

                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php echo $bar_w1; ?>">
                                  <span class="graph__legend">w -1</span>
                                  <label><?php echo @$gwp->data[1]->points;?></label>
                                  </li>
                              </span>

                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php echo $bar_w2; ?>">
                                  <span class="graph__legend">w -2</span>
                                  <label><?php echo @$gwp->data[2]->points;?></label>
                                  </li>
                              </span>

                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php echo $bar_w3; ?>">
                                  <span class="graph__legend">w -3</span>
                                  <label><?php echo @$gwp->data[3]->points;?></label>
                                  </li>
                              </span>

                              <div class="v__bar">
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                              </div>
                          </ul>
                      </div>
                  </div>
              </div>
              <!-- end top point graph -->

              <!-- daily progress donut graph -->
              <div class="progress__step">
                  <h5>Progress to goals</h5>
                  <div class="donut__progress">
                      <div class="step--circle circle"
                           data-thickness="15"
                           data-size="150">
                          <svg>
                              <path stroke-linecap="round" id="arc1" fill="none" stroke="transparent" stroke-width="10" />
                          </svg>
                          <svg>
                              <path stroke-linecap="round" id="arc2" fill="none" stroke="#fafafa" stroke-width="20" />
                          </svg>
                          <div class="step__progress__info">
                              <div class="step__info__label"><?php echo @$gsp->data->total_points_to_pass;?></div>
                          </div>
                      </div>
                  </div>
                  <h4><font><?php echo (@$gsp->data->total_points_to_pass - @$gsp->data->total_points_until_today);?></font> points left</h4>
              </div>
              <!-- end daily progress donut graph -->
          </div>
          <div class="study__dashboard__bottom">
              <!-- comprehension graph -->
              <div class="study__progress__graph">
                  <h5>Comprehension</h5>
                  <div class="graph__wrap">
                      <div class="bar__graph">
                          <ul class="graph b2">
                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php if($gwp->data[0]->comprehension_grammar >125){echo "125";}else{echo $gwp->data[0]->comprehension_grammar;}?>">
                                  <span class="graph__legend">Now</span>
                                  <label><?php echo strtok(@$gwp->data[0]->comprehension_grammar, '.');?></label>
                                  </li>
                              </span>

                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php if($gwp->data[1]->comprehension_grammar >125){echo "125";}else{echo $gwp->data[1]->comprehension_grammar;}?>">
                                  <span class="graph__legend">w -1</span>
                                  <label><?php echo strtok(@$gwp->data[1]->comprehension_grammar, '.');?></label>
                                  </li>
                              </span>

                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php if($gwp->data[2]->comprehension_grammar >125){echo "125";}else{echo $gwp->data[2]->comprehension_grammar;}?>">
                                  <span class="graph__legend">w -2</span>
                                  <label><?php echo strtok(@$gwp->data[2]->comprehension_grammar, '.');?></label>
                                  </li>
                              </span>

                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php if($gwp->data[3]->comprehension_grammar >125){echo "125";}else{echo $gwp->data[3]->comprehension_grammar;}?>">
                                  <span class="graph__legend">w -3</span>
                                  <label><?php echo strtok(@$gwp->data[3]->comprehension_grammar, '.');?></label>
                                  </li>
                              </span>

                              <div class="v__bar">
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                              </div>
                          </ul>
                      </div>
                  </div>
              </div>
              <!-- end comprehension graph -->

              <!-- pronunciation graph -->
              <div class="study__progress__graph">
                  <h5>Pronunciation</h5>
                  <div class="graph__wrap">
                      <div class="bar__graph">
                          <ul class="graph b2">
                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php if($gwp->data[0]->pronunciation >125){echo "125";}else{echo $gwp->data[0]->pronunciation;}?>">
                                  <span class="graph__legend">Now</span>
                                  <label><?php echo strtok(@$gwp->data[0]->pronunciation, '.');?></label>
                                  </li>
                              </span>

                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php if($gwp->data[1]->pronunciation >125){echo "125";}else{echo $gwp->data[1]->pronunciation;}?>">
                                  <span class="graph__legend">w -1</span>
                                  <label><?php echo strtok(@$gwp->data[1]->pronunciation, '.');?></label>
                                  </li>
                              </span>

                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php if($gwp->data[2]->pronunciation >125){echo "125";}else{echo $gwp->data[2]->pronunciation;}?>">
                                  <span class="graph__legend">w -2</span>
                                  <label><?php echo strtok(@$gwp->data[2]->pronunciation, '.');?></label>
                                  </li>
                              </span>

                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php if($gwp->data[3]->pronunciation >125){echo "125";}else{echo $gwp->data[3]->pronunciation;}?>">
                                  <span class="graph__legend">w -3</span>
                                  <label><?php echo strtok(@$gwp->data[3]->pronunciation, '.');?></label>
                                  </li>
                              </span>

                              <div class="v__bar">
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                              </div>
                          </ul>
                      </div>
                  </div>
              </div>
              <!-- end pronunciation graph -->

              <!-- listening graph -->
              <div class="study__progress__graph">
                  <h5>Listening</h5>
                  <div class="graph__wrap">
                      <div class="bar__graph">
                          <ul class="graph b2">
                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php if($gwp->data[0]->listening >125){echo "125";}else{echo $gwp->data[0]->listening;}?>">
                                  <span class="graph__legend">Now</span>
                                  <label><?php echo strtok(@$gwp->data[0]->listening, '.');?></label>
                                  </li>
                              </span>

                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php if($gwp->data[1]->listening >125){echo "125";}else{echo $gwp->data[1]->listening;}?>">
                                  <span class="graph__legend">w -1</span>
                                  <label><?php echo strtok(@$gwp->data[1]->listening, '.');?></label>
                                  </li>
                              </span>

                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php if($gwp->data[2]->listening >125){echo "125";}else{echo $gwp->data[2]->listening;}?>">
                                  <span class="graph__legend">w -2</span>
                                  <label><?php echo strtok(@$gwp->data[2]->listening, '.');?></label>
                                  </li>
                              </span>

                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php if($gwp->data[3]->listening >125){echo "125";}else{echo $gwp->data[3]->listening;}?>">
                                  <span class="graph__legend">w -3</span>
                                  <label><?php echo strtok(@$gwp->data[3]->listening, '.');?></label>
                                  </li>
                              </span>

                              <div class="v__bar">
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                              </div>
                          </ul>
                      </div>
                  </div>
              </div>
              <!-- end listening graph -->

              <!-- speaking graph -->
              <div class="study__progress__graph">
                  <h5>Speaking</h5>
                  <div class="graph__wrap">
                      <div class="bar__graph">
                          <ul class="graph b2">
                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php if($gwp->data[0]->speaking >125){echo "125";}else{echo $gwp->data[0]->speaking;}?>">
                                  <span class="graph__legend">Now</span>
                                  <label><?php echo strtok(@$gwp->data[0]->speaking, '.');?></label>
                                  </li>
                              </span>

                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php if($gwp->data[1]->speaking >125){echo "125";}else{echo $gwp->data[1]->speaking;}?>">
                                  <span class="graph__legend">w -1</span>
                                  <label><?php echo strtok(@$gwp->data[1]->speaking, '.');?></label>
                                  </li>
                              </span>

                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php if($gwp->data[2]->speaking >125){echo "125";}else{echo $gwp->data[2]->speaking;}?>">
                                  <span class="graph__legend">w -2</span>
                                  <label><?php echo strtok(@$gwp->data[2]->speaking, '.');?></label>
                                  </li>
                              </span>

                              <span class="graph__bar__cont">
                                  <li class="graph__bar__each" data-value="<?php if($gwp->data[3]->speaking >125){echo "125";}else{echo $gwp->data[3]->speaking;}?>">
                                  <span class="graph__legend">w -3</span>
                                  <label><?php echo strtok(@$gwp->data[3]->speaking, '.');?></label>
                                  </li>
                              </span>

                              <div class="v__bar">
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                                  <div class="v__line"></div>
                              </div>
                          </ul>
                      </div>
                  </div>
              </div>
              <!-- end speaking graph -->
          </div>
        <?php } else {?>
          <div style="margin: auto;color: #144c80;font-weight: 600;">
              Cannot Access Right Now
          </div>
        <?php } ?>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/remodal.min.js"></script>

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

    loadscript();
  });
</script>

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
  var std_id = "<?php echo $std_id; ?>";
  // console.log(std_id);
   function loadscript() {
    $.post("<?php echo site_url('opentok/call_script/call_ajax');?>", { 'std_id': std_id },function(data) {
     $("#reloading2").hide();
     $("#ajaxscript").html(data);
     // alert(data);
     });

    var appointment_id = "<?php echo $appointment_id; ?>";
    $.post("<?php echo site_url('opentok/live/store_session');?>", { 'appointment_id': appointment_id },function(data) {
    });

  }

  //ON REFRESH -----------------------
  $("#reloadajax").click(function() {
    $("#ajaxcall").hide();

    $.post("<?php echo site_url('opentok/call_loader/call_ajax');?>", { 'std_id': std_id },function(data) {
     $("#ajaxcall").show();
     $("#ajaxcall").html(data);
     // alert(data);
     });

  } );


  $("#reloadajax2").click(function() {
    $("#ajaxscript").hide();

    $.post("<?php echo site_url('opentok/call_script/call_ajax');?>", { 'std_id': std_id },function(data) {
     $("#ajaxscript").show();
     $("#ajaxscript").html(data);
     $("#reloading2").hide();
     // alert(data);
     });

  } );

</script>

<script type="text/javascript">
  $('#closesessionalert').click(function(){
    $("#sessionalert").hide();
  });
</script>

<script type="text/javascript">
  $('#closenotealert').click(function(){
    $("#coachnoteupdated").addClass("hidden");
});
</script>

<script type="text/javascript">
  $('#closecoachscript').click(function(){
    $("#coachsciptupdated").addClass("hidden");
});
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
          seconds--;
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
<script>
    $(document).ready(function() {
        $('#tabsss').click(function() {
            $('a').removeClass('reddot_notif');
        })
    })
</script>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/main.js"></script>
<script type="text/javascript">
    $(".checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });
</script>

<!--<script src="<?php //echo base_url(); ?>assets/js/vrm.js"></script>-->
<script type="text/javascript" src="<?php echo base_url();?>assets/js/circle-progress.js"></script>
<script>
  var innerupcoach   = '<?php echo @$gsp->data->coach->points_until_today;?>';
  var innerdowncoach = '<?php echo @$gsp->data->coach->points_to_pass;?>';
  var innerperccoach = innerupcoach / innerdowncoach;

    var outter = $('.outter--circle.circle');
    outter.circleProgress({
        startAngle: -Math.PI / 2,
        value: innerperccoach,
        lineCap: 'round',
        fill: '#59ba82'
    });

    var inner = $('.inner--circle.circle');
    var innerup   = '<?php echo @$gsp->data->study->points_until_today;?>';
    var innerdown = '<?php echo @$gsp->data->total_points_to_pass;?>';
    var innerperc = innerup / innerdown;
    inner.circleProgress({
        startAngle: -Math.PI / 2,
        value: innerperc,
        lineCap: 'round',
        fill: '#3baae3'
    });

    // daily step progress
    var stepVal = '<?php echo @$gsp->data->percentage_points;?>';

    var titikVal = '<?php echo @$gsp->data->percentage_days;?>';

    var newstepVal = stepVal/100;
    var newtitikVal = titikVal/100;
    var step = $('.step--circle.circle');
    var stepVal = 0.6 // circle step value
    step.circleProgress({
        startAngle: -Math.PI / 2,
        value: newstepVal,
        lineCap: 'round',
        fill: '#e1e1e1'
    });
    // circle step value condition to meet the goal
    if(newstepVal >= newtitikVal) {
        step.circleProgress({
            fill: '#59ba82'
        });
    }

    // Source https://jsbin.com/yaqaxotete/edit?html,css,js,output
    function polarToCartesian(centerX, centerY, radius, angleInDegrees) {
      var angleInRadians = (angleInDegrees + 90);
      var dailyGoal = (angleInRadians + 360 / 100 * titikVal) * Math.PI / 180.0; //where to put the goal value
      return {
        x: centerX + (radius * Math.cos(dailyGoal)),
        y: centerY + (radius * Math.sin(dailyGoal))
      };
    }

    function describeArc(x, y, radius, startAngle, endAngle){
        var start = polarToCartesian(x, y, radius, endAngle);
        var end = polarToCartesian(x, y, radius, startAngle);
        var arcSweep = endAngle - startAngle <= 180 ? "0" : "1";
        var d = [
            "M", start.x, start.y,
            "A", radius, radius, 0, arcSweep, 0, end.x, end.y
        ].join(" ");
        return d;
    }

    window.onload = function() {
      document.getElementById("arc1").setAttribute("d", describeArc(80, 80, 67, 180, 539));
      document.getElementById("arc2").setAttribute("d", describeArc(80, 80, 67, 180, 180));

    };

    // GRAPH BAR IN STUDY DASHBOARD
    $(".graph__bar__each").each(function() {
        var dataWidth = $(this).data('value') / 125 * 100;
        $(this).css("width", dataWidth + "%");

        if ($(this).data('value') >= 100) {
            $(this).css('background', '#59ba82');
        }
    });

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
  // console.log(AgoraRTC.Logger.setLogLevel(AgoraRTC.Logger.DEBUG)+"===============");

  /* simulated data to proof setLogLevel() */
  AgoraRTC.Logger.error('this is error');
  AgoraRTC.Logger.warning('this is warning');
  AgoraRTC.Logger.info('this is info');
  AgoraRTC.Logger.debug('this is debug');

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
      console.log("AgoraRTC client initialized");
      client.join(channel_key, channel_name, null, function(uid) {
        // console.log("User " + channel_key + " join channel successfully");
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
            localStream.setVideoProfile('480P_1');

          }

          // The user has granted access to the camera and mic.
          localStream.on("accessAllowed", function() {
            console.log("accessAllowed");
          });

          // The user has denied access to the camera and mic.
          localStream.on("accessDenied", function() {
            console.log("accessDenied");
          });

          localStream.init(function() {
            console.log("getUserMedia successfully");
            localStream.play('agora_local');

            client.publish(localStream, function (err) {
              console.log("Publish local stream error: " + err);
            });

            client.on('stream-published', function (evt) {
              console.log("Publish local stream successfully");
            });
          }, function (err) {
            console.log("getUserMedia failed", err);
          });
        }
      }, function(err) {
        console.log("Join channel failed", err);
      });
    }, function (err) {
      console.log("AgoraRTC client init failed", err);
    });

    channelKey = "";
    client.on('error', function(err) {
      console.log("Got error msg:", err.reason);
      if (err.reason === 'DYNAMIC_KEY_TIMEOUT') {
        client.renewChannelKey(channelKey, function(){
          console.log("Renew channel key successfully");
        }, function(err){
          console.log("Renew channel key failed: ", err);
        });
      }
    });


    client.on('stream-added', function (evt) {
      var stream = evt.stream;
      console.log("New stream added: " + stream.getId());
      console.log("Subscribe ", stream);
      client.subscribe(stream, function (err) {
        console.log("Subscribe stream failed", err);
      });
    });

    client.on('stream-subscribed', function (evt) {
      var stream = evt.stream;
      console.log("Subscribe remote stream successfully: " + stream.getId());
      if ($('div#video #agora_remote'+stream.getId()).length === 0) {
        $('div#video').append('<div class="agora_css" id="agora_remote'+stream.getId()+'" style="width:100%;height:330px;"></div>');
        // $('video#video'+stream.getId()).addClass('subscriber_video');
        $('video#video'+stream.getId()).hide();
        // var video = document.getElementsByTagName("video")[0];
        console.log($('video#video'+stream.getId()));
      }
      $("#heading1").hide();
      stream.play('agora_remote' + stream.getId());
    });

    client.on('stream-removed', function (evt) {
      var stream = evt.stream;
      stream.stop();
      $('#agora_remote' + stream.getId()).remove();
      $("#heading2").hide();
      console.log("Remote stream is removed " + stream.getId());
    });

    client.on('peer-leave', function (evt) {
      var stream = evt.stream;
      if (stream) {
        stream.stop();
        $('#agora_remote' + stream.getId()).remove();
        console.log(evt.uid + " leaved from this channel");
      }
    });
  // }

  function leave() {
    // document.getElementById("leave").disabled = true;
    client.leave(function () {
      console.log("Leavel channel successfully");
    }, function (err) {
      console.log("Leave channel failed");
    });
  }

  function publish() {
    // document.getElementById("publish").disabled = true;
    // document.getElementById("unpublish").disabled = false;
    client.publish(localStream, function (err) {
      console.log("Publish local stream error: " + err);
    });
  }

  function unpublish() {
    // document.getElementById("publish").disabled = false;
    // document.getElementById("unpublish").disabled = true;
    client.unpublish(localStream, function (err) {
      console.log("Unpublish local stream failed" + err);
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
          console.log('Some other kind of source/device: ', device);
        }
      }
    });
  }

  $('#sharescreench_ava').click(function(){
    // unpublish();

    client = AgoraRTC.createClient({mode: 'h264_interop'});
    client.init(app_id, function () {
      console.log("AgoraRTC client initialized");
      client.join(channel_key, channel_name, null, function(uid) {
        // console.log("User " + channel_key + " join channel successfully");
        // console.log("=====================================");
        // console.log("Channel Key = " + channel_key);
        // console.log("Channel Value = " + channel.value);
        // console.log("UID = " + uid);
        // console.log("Ch Name = " + channel_name);
        // console.log("=====================================");

        if (document.getElementById("video").checked) {
          camera = videoSource.value;
          microphone = audioSource.value;
          client.unpublish(localStream, function (err) {
            console.log("Unpublish local stream failed" + err);
          });
          // localStream = AgoraRTC.createStream({streamID: uid, audio: true, cameraId: camera, microphoneId: microphone, video: document.getElementById("video").checked, screen: false});
          localStream = AgoraRTC.createStream({streamID: uid, audio:false, video:false, screen:true, extensionId:"bgjblkhpjchbmfeipbclmfnpohcpjcpn"});

          //localStream = AgoraRTC.createStream({streamID: uid, audio: false, cameraId: camera, microphoneId: microphone, video: false, screen: true, extensionId: 'minllpmhdgpndnkomcoccfekfegnlikg'});
          if (document.getElementById("video").checked) {
            localStream.setVideoProfile('720p_3');

          }

          // The user has granted access to the camera and mic.
          localStream.on("accessAllowed", function() {
            console.log("accessAllowed");
          });

          // The user has denied access to the camera and mic.
          localStream.on("accessDenied", function() {
            console.log("accessDenied");
          });

          localStream.init(function() {
            console.log("getUserMedia successfully");
            // localStream.play('agora_local');

            client.publish(localStream, function (err) {
              console.log("Publish local stream error: " + err);
            });

            client.on('stream-published', function (evt) {
              console.log("Publish local stream successfully");
            });
          }, function (err) {
            console.log("getUserMedia failed", err);
          });
        }
      }, function(err) {
        console.log("Join channel failed", err);
      });
    }, function (err) {
      console.log("AgoraRTC client init failed", err);
    });

  });
  //audioSelect.onchange = getDevices;
  //videoSelect.onchange = getDevices;
  getDevices();
</script>

<script type="text/javascript">
  // console.log(extensionId);

  extensionId = "bgjblkhpjchbmfeipbclmfnpohcpjcpn";
  var isFirefox = typeof InstallTrigger !== 'undefined';

  var isChrome = !!window.chrome && !!window.chrome.webstore;

  if(isChrome == true && isFirefox == false){
    // console.log('==============================');
    // console.log(isFirefox);
    // console.log(isChrome);
    // console.log('==============================');
    $("#sharescreench_ava").removeClass("hidden");
    $("#sharescreench_nava").removeClass("hidden");

    console.log('a');
    $(document).ready(function(){
      // $("#sharescreenff").hide();
      // $("#sharescreenff").addClass("hidden");
    });
    // console.log('a');
    setInterval(function(){
      function IsExist(extensionId,callback){
      // console.log(extensionId+"-----top");
       chrome.runtime.sendMessage(extensionId, { message: "installed" },
         function (reply) {
          if (reply) {
            // console.log(reply + 'aa');
           callback(true);
          }else{
            // console.log(reply + 'bb');
           callback(false);
          }
       });
      }
      //check online extension - chrome webstore
      IsExist(extensionId,function(installed){
       if(!installed){
         // console.log(extensionId);
         // console.log('not');
        // $("#sharescreench_nava").removeClass("hidden");
        // $("#sharescreench_ava").addClass("hidden");
       }
       else{
         // console.log(extensionId);
         // console.log('ins')
        // $("#sharescreench_ava").removeClass("hidden");
        // $("#sharescreench_nava").addClass("hidden");
       }
      });
      // check offline extension - .crx file

    },1000);
  }else if(isChrome == false && isFirefox == true){
    // console.log('as');
    // console.log('as');
    // $("#sharescreenff").removeClass("hidden");
    // $("#sharescreench_ava").css("display","none !important");
    // $("#sharescreench_ava").addClass("hidden");
    // $("#sharescreench_nava").addClass("hidden");
  }
</script>
