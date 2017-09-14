<?php
if(@$user_extract2){
    $student_name = $user_extract2->fullname;
  }else{
    $student_name = $user_extract->fullname;
  }
?>

<script type="text/javascript">
  window.onbeforeunload = function() {
  return "Data will be lost if you leave the page, are you sure?";
};
</script>
<link rel="chrome-webstore-item" href="https://chrome.google.com/webstore/detail/efiofhlccdnkddnjdagljnhgoibifhki">
<script src='//static.opentok.com/v2/js/opentok.min.js'></script> 
<script src="<?php echo base_url();?>assets/js/script.js"></script>
<script charset="utf-8">
    var apiKey = '<?php echo $apiKey ?>';
    var sessionId = '<?php echo $sessionId ?>';
    var token = '<?php echo $token ?>';
    var session = OT.initSession(apiKey, sessionId);
    var publisher;
    //Self
        session.connect(token, function(error) {
            publisher = OT.initPublisher('myPublisherElementId',
                                 {insertMode: 'append',
                                  width: '100%',
                                  height: 150, name: "<?php echo $this->auth_manager->get_name();?>"});
            session.publish(publisher);
    });
    //Other
        session.on('streamCreated', function(event) {
            var subscriberProperties = {insertMode: 'append',
                                        width: '100%',
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
                console.log(countmsg);
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
    bottom: 35.2em;
    right: 5.8em;
    width: 15%;
    z-index: 200;
    border: 2px solid white;
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
                  
                  <p><?php echo $sentence.''.$different;?></p>
                  <p><?php echo $notes_s;?></p><br>
                  <span>Notes :</span>
                  <table style="width:100%">
                    <tr>
                      <td style="width: 3%" valign="top">1.</td>
                      <td style="width: 90%">If your coach is late by less than 10 minutes to a session, he/she needs to coach for 30 minutes.
                     You may ask for reschedule or refund.</td> 
                    </tr>
                    <tr>
                      <td style="width: 3%" valign="top">2.</td>
                      <td style="width: 90%">If a coach is late by more than 10 minutes, you'll get refund and opportunity to reschedule.</td>
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
        Waiting <b><?php echo $student_name; ?></b> to join the session. 
        <?php if($total_sec <= 300) {?>
        If in <span id="countdown3" class="timer3"></span> <b><?php echo $student_name; ?></b> isn't connected, you have to wait until the end of the session to get your token refunded.
        <?php }else{ ?>
        You have to wait until the end of the session to get your token refunded.

        <?php } $appoint_id = $appointment_id; ?>
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
        <b><?php echo $student_name; ?></b> is disconnected, wait him/her to reconnect. <span id="countdown2" class="timer2"></span>
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

    <div class="box">
        <div class="content padding-t-0">
        <h3>Remaining Time: <span id="countdown" class="timer"></span></h3>
        <?php $appoint_id = $appointment_id; ?>
        <form name ="leaving" onsubmit="return confirm('Are you sure you want to submit this form?');" action="<?php echo(site_url('opentok/leavesession/'));?>" method="post">
            <input type="hidden" name="appoint_id" value="<?php echo $appoint_id ?>">
            <input type="submit" value="Leave Session" class="pure-button btn-small btn-red hidden">
        </form>
        
            <div class="video-conference width100perc">
              <div class="fullscreenarea" id="fullarea">
                  <div class="preloader2" id="connecting">
                    <img src="http://idbuild.id.dyned.com/live_v20/uploads/images/dyned-circle.png" width="200" class="small-preload2">
                  </div>
                  <div class="subscriber" id="subscriberContainer"><div class="publisher" id="myPublisherElementId"></div></div>
                  <a onclick="makeFullScreen(fullarea)" style="position: absolute; bottom: 35.2em; right: 35px;">
                    <img class="fs-icon" src="<?php echo base_url();?>assets/icon/expand2x.png"></img>
                  </a>
                  <button id="videooff" class="pure-button btn-small btn-green w3-animate-opacity" onclick="javascript:toggleOff();" data-tooltip="Click to Turn Off Your Camera">Camera is On</button>
                  <button id="videoon" class="pure-button btn-small btn-red w3-animate-opacity hidden" onclick="javascript:toggleOn();" data-tooltip="Click to Turn On Your Camera">Camera is Off</button>
              </div>
            </div>
        </div>

        <div class="content-bottom padding-l-20 pure-g">
            <div class="pure-u-md-12-24">
                <ul class='tabs padding-l-0 clearfix'>
                    <li><a href='#tab1'>Academic Plan</a></li>
                    <li><a href='#tab2'>Study Data</a></li>
                </ul>
                <div id='tab1' class="w3-animate-opacity" style="height: 461.391px;"><?php if($allmodule){?>
                    <div class="coaching-info">
                        <div class="coaching-info-box display-inline-block width100 clearfix">
                            <div class="coaching-box-left">
                                <span><b>WSS</b></span>
                            </div>
                            <div class="coaching-box-right">
                                <div><b><?php echo @$student_vrm->wss->raw_value ?></b></div>
                            </div>
                        </div>
                        <div class="coaching-info-box display-inline-block width180 clearfix">
                            <div class="coaching-box-left padding-l-10">
                                <span><b>Certification Plan</b></span>
                            </div>
                            <div class="coaching-box-right">
                                <div><b><?php echo @$student_vrm->cert_studying ?></b></div>
                            </div>
                        </div>
                    </div>
                    <div class="tabs-two tabs2 padding-t-20 padding-b-5 clearfix">
                <?php
                $nde = @$allmodule['nde'];
                $fe  = @$allmodule['fe'];
                $fib = @$allmodule['fib'];
                $tls = @$allmodule['tls'];
                $dlg = @$allmodule['dlg'];
                $dbe = @$allmodule['dbe'];
                $als = @$allmodule['als'];
                $efs = @$allmodule['efs'];
                $rfs = @$allmodule['rfs'];
                $ebn = @$allmodule['ebn'];

                if(@$nde){
                  $ndecheck = implode($nde);
                }else{ $ndecheck = "";}
                
                if(@$fe){
                  $fecheck = implode($fe);
                }else{ $fecheck = "";}

                if(@$fib){
                  $fibcheck = implode($fib);
                }else{ $fibcheck = "";}

                if(@$tls){
                  $tlscheck = implode($tls);
                }else{ $tlscheck = "";}

                if(@$dlg){
                  $dlgcheck = implode($dlg);
                }else{ $dlgcheck = "";}

                if(@$dbe){
                  $dbecheck = implode($dbe);
                }else{ $dbecheck = "";}

                if(@$als){
                  $alscheck = implode($als);
                }else{ $alscheck = "";}

                if(@$efs){
                  $efscheck = implode($efs);
                }else{ $efscheck = "";}

                if(@$rfs){
                  $rfscheck = implode($rfs);
                }else{ $rfscheck = "";}

                if(@$ebn){
                  $ebncheck = implode($ebn);
                }else{ $ebncheck = "";}
                
                
                // $ebncheck = implode($ebn);
                // $num = 6;
                // $qwert = array_slice($rfs, 0, 20);
                // echo "<pre>";
                // print_r($ebn);
                // exit();
                ?>  
                    <?php if($fecheck != NULL) {?>
                    <a href='#tabs-content1' class="square-tabs">
                        <h5 class="m-t-20"><?php echo @$fe['titleFe']; ?></h5>
                    </a>
                    <?php } else{} 
                    if($ndecheck != NULL) {?>
                    <a href='#tabs-content2' class="square-tabs">
                        <h5 class="m-t-13"><?php echo @$nde['titleNde']; ?></h5>
                    </a>
                    <?php } else{} 
                    if($efscheck != NULL) {?>
                    <a href='#tabs-content3' class="square-tabs">
                        <h5 class="m-t-13"><?php echo @$efs['titleEfs']; ?></h5>
                    </a>
                    <?php } else{} 
                    if($rfscheck != NULL) {?>
                    <a href='#tabs-content4' class="square-tabs">
                        <h5 class="m-t-13"><?php echo @$rfs['titleRfs']; ?></h5>
                    </a>
                    <?php } else{} 
                    if($dbecheck != NULL) {?>
                    <a href='#tabs-content5' class="square-tabs">
                        <h5 class="m-t-5"><?php echo @$dbe['titleDbe']; ?></h5>
                    </a>
                    <?php } else{} 
                    if($tlscheck != NULL) {?>
                    <a href='#tabs-content6' class="square-tabs">
                        <h5 class="m-t-20"><?php echo @$tls['titleTls']; ?></h5>
                    </a>
                    <?php } else{} 
                    if($ebncheck != NULL) {?>
                    <a href='#tabs-content7' class="square-tabs">
                        <h5 class="m-t-13"><?php echo @$ebn['titleEbn']; ?></h5>
                    </a>
                    <?php } else{} 
                    if($fibcheck != NULL) {?>
                    <a href='#tabs-content8' class="square-tabs">
                        <h5 class="m-t-13"><?php echo @$fib['titleFib']; ?></h5>
                    </a>
                    <?php } else{} 
                    if($alscheck != NULL) {?>
                    <a href='#tabs-content9' class="square-tabs">
                        <h5 class="m-t-20"><?php echo @$als['titleAls']; ?></h5>
                    </a>
                    <?php } else{} 
                    if($dlgcheck != NULL) {?>
                    <a href='#tabs-content10' class="square-tabs">
                        <h5 class="m-t-20"><?php echo @$dlg['titleDlg']; ?></h5>
                    </a>
                    <?php } else{}?>
                </div>

                <?php if($fecheck != NULL) {?>
                <div id="tabs-content1" class="tabs-c clearfix">
                    <?php if(@$fe['fe1'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                         <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$fe['pcfe1']; ?>%</h5>
                         <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$fe['fe1']; ?></h5>
                    </div>
                    <?php }else{ } if(@$fe['fe2'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$fe['pcfe2']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$fe['fe2']; ?></h5>
                    </div>
                    <?php }else{ } if(@$fe['fe4'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$fe['pcfe3']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$fe['fe3']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$fe['fe4'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$fe['pcfe4']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$fe['fe4']; ?></h5>
                        
                    </div>
                <?php }else{ }?>                  
                </div>
                <?php } else {} if($ndecheck != NULL) { ?>
                <div id="tabs-content2" class="tabs-c clearfix">
                    <?php if(@$nde['nde1'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$nde['pcnde1']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$nde['nde1']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$nde['nde2'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$nde['pcnde2']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$nde['nde2']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$nde['nde3'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$nde['pcnde3']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$nde['nde3']; ?></h5>
                        
                    </div>
                   <?php }else{ } if(@$nde['nde4'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$nde['pcnde4']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$nde['nde4']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$nde['nde5'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$nde['pcnde5']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$nde['nde5']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$nde['nde6'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$nde['pcnde6']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$nde['nde6']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$nde['nde7'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$nde['pcnde7']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$nde['nde7']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$nde['nde8'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$nde['pcnde8']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$nde['nde8']; ?></h5>
                        
                    </div>
                    <?php }else{ }?>
                </div>
                <?php } else {} if($efscheck != NULL) { ?>
                <div id="tabs-content3" class="tabs-c clearfix">
                    <?php if(@$efs['efs1'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs1']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs1']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$efs['efs2'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs2']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs2']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$efs['efs3'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs3']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs3']; ?></h5>
                        
                    </div>
                   <?php }else{ } if(@$efs['efs4'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs4']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs4']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$efs['efs5'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs5']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs5']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$efs['efs6'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs6']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs6']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$efs['efs7'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs7']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs7']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$efs['efs8'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs8']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs8']; ?></h5>
                        
                    </div>
                    <?php }else{ }?>

                    <?php if(@$efs['efs9'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs9']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs9']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$efs['efs10'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcef10']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs10']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$efs['efs11'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs11']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs11']; ?></h5>
                    </div>
                   <?php }else{ } if(@$efs['efs12'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs12']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs12']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$efs['efs13'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs13']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs13']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$efs['efs14'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs14']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs14']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$efs['efs15'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs15']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs15']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$efs['efs16'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs16']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs16']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$efs['efs17'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs17']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs17']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$efs['efs18'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs18']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs18']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$efs['efs19'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs19']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs19']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$efs['efs20'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs20']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs20']; ?></h5>
                        
                    </div>
                    <?php }else{ }?>
                </div>
                <?php } else {} if($rfscheck != NULL) { ?>
                <div id="tabs-content4" class="tabs-c clearfix">
                    <?php if(@$rfs['rfs1'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs1']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs1']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$rfs['rfs2'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs2']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs2']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$rfs['rfs3'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs3']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs3']; ?></h5>
                        
                    </div>
                   <?php }else{ } if(@$rfs['rfs4'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs4']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs4']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$rfs['rfs5'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs5']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs5']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$rfs['rfs6'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs6']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs6']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$rfs['rfs7'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs7']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs7']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$rfs['rfs8'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs8']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs8']; ?></h5>
                        
                    </div>
                    <?php }else{ }?>

                    <?php if(@$rfs['rfs9'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs9']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs9']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$rfs['rfs10'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcef10']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs10']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$rfs['rfs11'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs11']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs11']; ?></h5>
                        
                    </div>
                   <?php }else{ } if(@$rfs['rfs12'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs12']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs12']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$rfs['rfs13'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs13']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs13']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$rfs['rfs14'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs14']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs14']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$rfs['rfs15'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs15']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs15']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$rfs['rfs16'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs16']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs16']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$rfs['rfs17'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs17']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs17']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$rfs['rfs18'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs18']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs18']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$rfs['rfs19'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs19']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs19']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$rfs['rfs20'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs20']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs20']; ?></h5>
                        
                    </div>
                    <?php }else{ }?>
                </div>
                <?php } else {} if($dbecheck != NULL) { ?>
                <div id="tabs-content5" class="tabs-c clearfix">
                    <?php if(@$dbe['dbe1'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dbe['pcdbe1']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dbe['dbe1']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$dbe['dbe2'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dbe['pcdbe2']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dbe['dbe2']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$dbe['dbe3'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dbe['pcdbe3']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dbe['dbe3']; ?></h5>
                        
                    </div>
                   <?php }else{ } if(@$dbe['dbe4'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dbe['pcdbe4']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dbe['dbe4']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$dbe['dbe5'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dbe['pcdbe5']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dbe['dbe5']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$dbe['dbe6'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dbe['pcdbe6']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dbe['dbe6']; ?></h5>
                        
                    </div>
                    <?php }else{ }?>
                </div>
                <?php } else {} if($tlscheck != NULL) { ?>
                <div id="tabs-content6" class="tabs-c clearfix">
                    <?php if(@$tls['tls1'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls1']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls1']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$tls['tls2'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls2']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls2']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$tls['tls3'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls3']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls3']; ?></h5>
                        
                    </div>
                   <?php }else{ } if(@$tls['tls4'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls4']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls4']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$tls['tls5'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls5']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls5']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$tls['tls6'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls6']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls6']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$tls['tls7'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls7']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls7']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$tls['tls8'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls8']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls8']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$tls['tls9'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls9']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls9']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$tls['tls10'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls10']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls10']; ?></h5>
                        
                    </div>
                    <?php }else{ } ?>
                </div>
                <?php } else {} if($ebncheck != NULL) { ?>
                <div id="tabs-content7" class="tabs-c clearfix">
                    <?php if(@$ebn['ebn1'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$ebn['pcebn1']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$ebn['ebn1']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$ebn['ebn2'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$ebn['pcebn2']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$ebn['ebn2']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$ebn['ebn3'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$ebn['pcebn3']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$ebn['ebn3']; ?></h5>
                        
                    </div>
                   <?php }else{ } if(@$ebn['ebn4'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$ebn['pcebn4']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$ebn['ebn4']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$ebn['ebn5'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$ebn['pcebn5']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$ebn['ebn5']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$ebn['ebn6'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$ebn['pcebn6']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$ebn['ebn6']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$ebn['ebn7'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$ebn['pcebn7']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$ebn['ebn7']; ?></h5>
                        
                    </div>
                    <?php }else{ } ?>
                </div>
                <?php } else {} if($fibcheck != NULL) { ?>
                <div id="tabs-content8" class="tabs-c clearfix">
                    <?php if(@$fib['fib1'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$fib['pcfib1']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$fib['fib1']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$fib['fib2'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$fib['pcfib2']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$fib['fib2']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$fib['fib3'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$fib['pcfib3']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$fib['fib3']; ?></h5>
                        
                    </div>
                   <?php }else{ } if(@$fib['fib4'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$fib['pcfib4']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$fib['fib4']; ?></h5>
                        
                    </div>
                    <?php }else{ } ?>
                </div>
                <?php } else {} if($alscheck != NULL) { ?>
                <div id="tabs-content9" class="tabs-c clearfix">
                    <?php if(@$als['als1'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals1']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als1']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$als['als2'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals2']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als2']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$als['als3'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals3']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als3']; ?></h5>
                        
                    </div>
                   <?php }else{ } if(@$als['als4'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals4']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als4']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$als['als5'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals5']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als5']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$als['als6'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals6']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als6']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$als['als7'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals7']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als7']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$als['als8'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals8']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als8']; ?></h5>
                        
                    </div>
                    <?php }else{ }?>

                    <?php if(@$als['als9'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals9']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als9']; ?></h5>
                        
                    </div>
                    <?php }else{ }?>
                </div>
                <?php } else {} if($dlgcheck != NULL) { ?>
                <div id="tabs-content10" class="tabs-c clearfix">
                    <?php if(@$dlg['dlg1'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dlg['pcdlg1']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dlg['dlg1']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$dlg['dlg2'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dlg['pcdlg2']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dlg['dlg2']; ?></h5>
                        
                    </div>
                    <?php }else{ } if(@$dlg['dlg3'] != null){ ?>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dlg['pcdlg3']; ?>%</h5>
                        <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dlg['dlg3']; ?></h5>
                        
                    </div>
                   <?php }else{ } ?>
                </div>
                <?php } else {}?>
            <?php } else{ ?>
                  You have not connected the DynEd Pro ID
                <?php } ?>

                </div>
                <?php
                // echo "<pre>";
                //   $student_vrm;
                //   print_r($student_vrm);
                  $wss_raw_value = @$student_vrm->wss->raw_value;
                  $hpw_raw_value = @$student_vrm->hours_per_week->raw_value;
                  $lpt_raw_value = @$student_vrm->last_pt_score;
                  $lst_raw_value = @$student_vrm->study_level;
                  $pt1_raw_value = @$student_vrm->initial_pt_score;
                  // exit();
                ?>
                <div id='tab2' class="pure-g clearfix w3-animate-opacity" style="height: 461.391px;">
                <?php if($allmodule){?>
                    <ul class="coaching-info margin-auto padding-l-0 padding-t-10">
                      <li class="coaching-info-box margin-auto clearfix">
                          <div class="coaching-box-left text-cl-white">
                              <span>Last PT</span>
                          </div>
                          <div class="coaching-box-right bg-white">
                              <div><?php echo $lpt_raw_value;?></div>
                          </div>
                      </li>
                      <li class="coaching-info-box margin-auto clearfix">
                          <div class="coaching-box-left text-cl-white">
                              <span>Hours/Week</span>
                          </div>
                          <div class="coaching-box-right bg-white">
                              <div><?php echo $hpw_raw_value;?></div>
                          </div>
                      </li>
                      <li class="coaching-info-box margin-auto clearfix">
                          <div class="coaching-box-left text-cl-white">
                              <span>Level Study</span>
                          </div>
                          <div class="coaching-box-right bg-white">
                              <div><?php echo $pt1_raw_value;?></div>
                          </div>
                      </li>
                      <li class="coaching-info-box margin-auto clearfix">
                          <div class="coaching-box-left text-cl-white">
                              <span>PT 1</span>
                          </div>
                          <div class="coaching-box-right bg-white">
                              <div><?php echo $lst_raw_value;?></div>
                          </div>
                      </li>
                  </ul>

                    <div class="spdr-graph">
                      <div id="chart-area" class="radar-ainner font-12">
                          <div class="hexagonal height-0 prelative">
                              <div class="hexagonBlue position-absolute"></div>
                              <div class="hexagonGreen position-absolute"></div>
                              <div class="hexagonYellow position-absolute"></div>
                              <div class="hexagonRed position-absolute"></div>
                          </div>
                          <canvas id="bar" class="radar" style="width: 200%;"></canvas>
                      </div>
                    </div>
                    <?php }else{ ?>
                      You have not connected the DynEd Pro ID
                    <?php } ?>
                </div>
            </div>

            <div class="coach-comment pure-u-md-12-24">

                <div class="comment-message padding-t-10 clearfix">
<!--                     <div class="padding-r-10 text-center left">
                        <img src="../img/ariana.jpeg" class="img-circle-medium">
                        <div class="font-semi-bold font-12">Agustinus Jewantoro</div>
                        <div class="font-12">Friday, 17/04/2016</div>
                    </div>
                    <div class="padding-t-5">
                        <span>Coach Commented :</span>
                        <p>You'll need to teach more vocabulary, and it's only a dummy when it's called a dummy it means really what it is, this project b2c only dummy mockup. You'll need to teach more vocabulary, and it's only a dummy when it's called a dummy it means really what it is, this project b2c only dummy mockup. You'll need to teach more vocabulary, and it's only a dummy when it's called a dummy it means really what it.</p>
                    </div> -->
                      <div style="padding-top: 5px;">
                        <div class="col-md-2">
                          <input placeholder="Please input your messages here..." type="text" id="pesan" class="form-control" style="width: 84%">
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
</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/Chart.Core.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/Chart.Radar.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/remodal.min.js"></script>

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
  });
</script>
<script type="text/javascript">
  $('#closesessionalert').click(function(){
    $("#sessionalert").hide();
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
    }else if ( seconds==300 ){
        $("#sessionalert").removeClass("hidden");
        seconds--;
    }else {
        seconds--;
    }
}
var countdownTimer = setInterval('timer()', 1000);
</script>
<script>
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
</script>
<script>
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
</script>
<script type="text/javascript">
  var secfromdb  = '<?php echo $total_sec ?>';
  var milisecond = secfromdb * 1000;
  window.setTimeout(function() {
    alert(" Your session is ended ");
    window.onbeforeunload = null;
    document.forms['leaving'].submit();
}, milisecond);
</script>

<script>

//RADAR

$('[data-tooltip]:after').css({'width':'115px'});

// var student_vrm = {"cert_level_completion": {"A1": 24, "A2": 10, "B1": 25, "B2": 56, "C1": 60, "C2": 33}, "cert_plan": 2, "headphone": {"percent_to_goal": 45, "raw_value": 213}, "hours_per_week": {"percent_to_goal": null, "raw_value": 0.7}, "initial_pt_score": 2.0, "last_pt_score": 2.0, "mic": {"percent_to_goal": 80, "raw_value": 397}, "mt": {"percent_to_goal": 60, "raw_value": 48}, "repeats": {"percent_to_goal": 102, "raw_value": 486}, "sr": {"percent_to_goal": 98, "raw_value": 73}, "study_level": 2.2, "wss": {"percent_to_goal": 87, "raw_value": 1.9}};
var student_vrm = <?php echo $student_vrm_json ?>;


function Value(value, metadata){
    this.value= value;
    this.metadata = metadata;
}

Value.prototype.toString = function(){
    return this.value;
}

var wss = new Value(student_vrm.wss.percent_to_goal, {
    tooltipx : student_vrm.wss.raw_value + ' | Weighted Study Score'
})

var repeat = new Value(student_vrm.repeats.percent_to_goal, {
    tooltipx : student_vrm.repeats.raw_value + ' | Repeat'
})

var mic = new Value(student_vrm.mic.percent_to_goal, {
    tooltipx : student_vrm.mic.raw_value + ' | Speaking (Microphone)'
})

var headphone = new Value(student_vrm.headphone.percent_to_goal, {
    tooltipx : student_vrm.headphone.raw_value + ' | Review (Headphone)'
})
var sr = new Value(student_vrm.sr.percent_to_goal, {
    tooltipx : student_vrm.sr.raw_value + ' | Speech Recognition'
})

var mt = new Value(student_vrm.mt.percent_to_goal, {
    tooltipx : student_vrm.mt.raw_value + ' | Mastery Test'
})

var valueData = function(point){
    return point.value.metadata.tooltipx;
}

$('.last-pt').append('<div>'+student_vrm.last_pt_score+'</div>');
$('.sl').append('<div>'+student_vrm.study_level+'</div>');
$('.pt').append('<div>'+student_vrm.initial_pt_score+'</div>');
$('.hw').append('<div>'+student_vrm.hours_per_week.raw_value+'</div>');

var helpers = Chart.helpers;
var canvas = document.getElementById('bar');

var data = {
    pointLabelFontFamily: "webFont",
    labels: ["WSS", "\ue031", "\ue02f", "\ue030", '\ue02e', "x MT"],
    datasets: [

        {
            label: "Progress",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: [wss, repeat, mic, headphone, sr, mt]
        }
    ]
};    

if($(document).width() < 490){
    var bar = new Chart(canvas.getContext('2d')).Radar(data, {
      
        tooltipTemplate : valueData,
        legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        responsive: true,
        pointLabelFontFamily : '"webFont"',
        pointLabelFontSize : 18,
        pointLabelFontColor : '#666',
        pointotRadius : 3,
        pointDotStrokeWidth : 1,
        pointHitDetectionRadius : 25,
        datasetStroke : true,
        datasetStrokeWidth : 2,
        datasetFill : true,
        scaleFontFamily: "'webFont'",
        pointDot:true,
        showtooltipx: true,
        scaleOverride: true,
        scaleSteps: 6,
        scaleEndValue: 110,
        scaleStepWidth: 28,
        scaleStartValue: 0,
        scaleLineColor : "#ededed",

    });
}
else {
    var bar = new Chart(canvas.getContext('2d')).Radar(data, {
      
        tooltipTemplate : valueData,
        legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        responsive: true,
        pointLabelFontFamily : '"webFont"',
        pointLabelFontSize : 14,
        pointLabelFontColor : '#666',
        pointotRadius : 3,
        pointDotStrokeWidth : 1,
        pointHitDetectionRadius : 25,
        datasetStroke : true,
        datasetStrokeWidth : 2,
        datasetFill : true,
        scaleFontFamily: "'webFont'",
        pointDot:true,
        showtooltipx: true,
        scaleOverride: true,
        scaleSteps: 6,
        scaleEndValue: 110,
        scaleStepWidth: 28,
        scaleStartValue: 0,
        scaleLineColor : "#ededed",
    });
}

var legendHolder = document.createElement('div');
legendHolder.innerHTML = bar.generateLegend();

document.getElementById('legend').appendChild(legendHolder.firstChild);
</script>