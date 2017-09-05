<!--         <script>
        $(document).ready(function()
        {
            alert('<?php echo $sentence.''.$different.'. '.$notes;?>');
        });
        </script> -->
        

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>

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

<script src='//static.opentok.com/v2/js/opentok.min.js'></script> 
<script charset="utf-8">
    var apiKey = '<?php echo $apiKey ?>';
    var sessionId = '<?php echo $sessionId ?>';
    var token = '<?php echo $token ?>';
    var session = OT.initSession(apiKey, sessionId);
	var ffWhitelistVersion; // = '36';
	var extensionId = 'efiofhlccdnkddnjdagljnhgoibifhki';
    //Self
        session.connect(token, function(error) {
            var publisher = OT.initPublisher('myPublisherElementId',
                                 {insertMode: 'append',
                                  width: '100%',
                                  height: 150, name: "<?php echo $this->auth_manager->get_name();?>"});
            session.publish(publisher);
		});
    //Other
        session.on('streamCreated', function(event) {
            var subscriberProperties = {insertMode: 'append',
                                        width: '100%',
                                        height: 350, name: "<?php echo $this->auth_manager->get_name();?>"};
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
		
		OT.registerScreenSharingExtension('chrome', extensionId, 2);
		
		function screenshare() {
		  OT.checkScreenSharingCapability(function(response) {
			console.info(response);
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
					alert('Something went wrong: ' + error.message);
				  } else {
					session.publish(
					  screenSharingPublisher,
					  function(error) {
						if (error) {
						  alert('Something went wrong: ' + error.message);
						}
					  });
				  }
				});
			  }
			});
		}
</script>
<script>
var txt = "";
txt += "<p>Browser CodeName: " + navigator.appCodeName + "</p>";

document.getElementById("demo").innerHTML = txt;
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
         
        function tampildata(){
           $.ajax({
            type:"POST",
            url:"<?php echo site_url('opentok/live/ambil_pesan');?>",    
            success: function(data){
                     $('#isi_chat').html(data);
			      }  
           });
        }
   
   
         $('#kirim').click(function(){
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
           
          setInterval(function(){
                     tampildata();},1000);
        });
    </script>
    <style>
      #isi_chat{height:75px;
	  overflow-y: scroll;}
    </style>

<style>
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
  height: 500px !important;
}

#fullarea:-moz-full-screen {
  width: 100% !important;
 height: 500px !important;
}

#fullarea:-ms-full-screen {
  width: 100% !important;
 height: 500px !important;
}

#fullarea:-o-full-screen {
  width: 100% !important;
  height: 500px !important;
}

#fullarea:full-screen {
  width: 100% !important;
 height: 500px !important;
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
                          <td class="">Student Name</td>
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
                  <p><?php echo $notes_c;?></p><br>
                  <span>Notes :</span>
                  <table style="width:100%">
                    <tr>
                      <td style="width: 3%" valign="top">1.</td>
                      <td style="width: 90%">In order to use Share Screen, you <b>have to</b> install this extension:<br>
                        <a class="pure-button btn-small btn-white" href="https://chrome.google.com/webstore/detail/dyned-opentok-screen-shar/efiofhlccdnkddnjdagljnhgoibifhki/" target="_blank">Install Screen Sharing Extension</a>
                      </td> 
                    </tr>
                    <tr>
                      <td style="width: 3%" valign="top">2.</td>
                      <td style="width: 90%">If a student is late by less than 10 minutes to a session, he/she can participate in the session, but only for up to the session limit (20 minutes coaching session).</td>
                    </tr>
                    <tr>
                      <td style="width: 3%" valign="top">3.</td>
                      <td style="width: 90%">If a student is late by more than 10 minutes to a session, he/she can't join the session.</td>
                    </tr>
                  </table>
                  <br>
                  <div>
                  <?php 
                  $duration   = $different_val; 
                  
                  if($duration < 10){
                    // echo "<pre>";
                    //   print_r($different_val);
                    //   exit();
                  ?>
                    <a data-remodal-action="confirm" class="pure-button btn-small btn-white" id="closemodal">Join Session</a>
                  <?
                    }else if($duration > 10){
                      $livesession = array(
                        'user_extract'   => $user_extract,
                        'appointment_id' => $appointment_id
                      );
                      $appoint_id = $appointment_id;
                      // echo "<pre>";
                      // print_r($appoint_id);
                      // exit();
                  ?>
                    <form name ="cancelling" action="<?php echo(site_url('opentok/cancelsession/'));?>" method="post">
                        <input type="hidden" name="appoint_id" value="<?php echo $appoint_id ?>">
                        <input type="submit" value="Leave Session" class="pure-button btn-small btn-red">
                    </form>
                  <?php
                    }
                  ?>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- modal -->



<div class="pure-u-lg-20-24 pure-u-md-24-24 pure-u-sm-24-24 content-center">

    <div class="heading">
    </div>

    <div class="box pure-g">
        <div class="content padding-r-0 width52perc pure-u-md-6-12">
            <div class="video-conference">
              <!-- =============== -->
              <div class="box b-f3-1" style="position:relative;">
              <h3>Remaining Time: <span id="countdown" class="timer"></span></h3>
                <div class="content padding15">
                 <div class="fullscreenarea" id="fullarea">
                      <div class="subscriber" id="subscriberContainer"><div class="publisher" id="myPublisherElementId"></div></div>
                      <a onclick="makeFullScreen(fullarea)" style="position: absolute; bottom: 52px; left: 25px;">
                        <img class="fs-icon" src="<?php echo base_url();?>assets/icon/expand2x.png"></img>
                      </a>
                  </div>
                  
                  <button onclick="javascript:screenshare();">Share your screen</button>
                  
                  <a class="pure-button btn-small btn-red" onclick="if (confirm('Are You Sure Want to Leave Session? Your chat history will be deleted')) 
                    window.location='<?php echo site_url('opentok/leavesession/');?>'; 
                    return false"
                    style="float:right;">Leave Session</a>
                    
                  <div id="demo"></div>
                </div>
              </div>
              <!-- =============== -->
            </div>
            <ul class='tabs padding-l-0 clearfix'>
                <li><a href='#tab1'>Academic Plan</a></li>
                <li><a href='#tab2'>Study Data</a></li>
                <li><a href='#tab3'>Teacher's Note</a></li>
                <li><a href='#tab4'>Chat</a></li>
            </ul>
            <div id='tab1'>
                <div class="coaching-info">
                    <div class="coaching-info-box display-inline-block width100 clearfix">
                        <div class="coaching-box-left">
                            <span>WSS</span>
                        </div>
                        <div class="coaching-box-right">
                            <div>2</div>
                        </div>
                    </div>
                    <div class="coaching-info-box display-inline-block width156 clearfix">
                        <div class="coaching-box-left padding-l-10">
                            <span>Certification Plan</span>
                        </div>
                        <div class="coaching-box-right">
                            <div>C2</div>
                        </div>
                    </div>
                </div>
                <div class="tabs-two tabs2">
                    <a href='#tabs-content1' class="square-tabs">
                        <h5 class="m-b-5 font-semi-bold">EF5</h5>
                        <h5 class="margin0 padding-b-5 font-10">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </a>
                    <a href='#tabs-content2' class="square-tabs">
                        <h5 class="m-b-5 font-semi-bold">RFS</h5>
                        <h5 class="margin0 padding-b-5 font-10">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </a>
                    <a href='#tabs-content3' class="square-tabs">
                        <h5 class="m-b-5 font-semi-bold">EBTN</h5>
                        <h5 class="margin0 padding-b-5 font-10">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </a>
                    <a href='#tabs-content4' class="square-tabs">
                        <h5 class="m-b-5 font-semi-bold">NBE</h5>
                        <h5 class="margin0 padding-b-5 font-10">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </a>
                    <a href='#tabs-content5' class="square-tabs">
                        <h5 class="m-b-5 font-semi-bold">AL</h5>
                        <h5 class="margin0 padding-b-5 font-10">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </a>
                    <a href='#tabs-content6' class="square-tabs">
                        <h5 class="m-b-5 font-semi-bold">ALG</h5>
                        <h5 class="margin0 padding-b-5 font-10">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </a>
                </div>

                <div id="tabs-content1" class="tabs-c">
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 1</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 2</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 3</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 4</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 5</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 6</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 7</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>   
                </div>

                <div id="tabs-content2" class="tabs-c">
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 1</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 2</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 3</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 4</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 5</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 6</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                </div>

                <div id="tabs-content3" class="tabs-c">
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 1</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 2</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 3</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 4</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 5</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                </div>

                <div id="tabs-content4" class="tabs-c">
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 1</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 2</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 3</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                </div>

                <div id="tabs-content5" class="tabs-c">
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 1</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 2</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 3</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 4</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 5</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 6</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                </div>

                <div id="tabs-content6" class="tabs-c">
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 1</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                    <div class="square-tabs bg-forthblue">
                        <h5 class="m-b-5 font-semi-bold text-cl-white">Unit 2</h5>
                        <h5 class="margin0 padding-b-5 font-10 text-cl-white">80%</h5>
                        <div class="prog-bar">
                            <div class="prog-bar-inside"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id='tab2'>
                <div class="box-capsule margin-auto width30perc text-cl-tertiary font-14">
                    <span>Study Data Average</span>
                </div>
                <div class="coaching-info">
                    <div class="coaching-info-box bg-tertiary clearfix">
                        <div class="coaching-box-left padding-8-0-0-6 text-cl-white">
                            <span>Last PT</span>
                        </div>
                        <div class="coaching-box-right bg-white">
                            <div>2.7</div>
                        </div>
                    </div>
                    <div class="coaching-info-box bg-tertiary clearfix">
                        <div class="coaching-box-left padding-8-0-0-6 text-cl-white">
                            <span>Hours/Week</span>
                        </div>
                        <div class="coaching-box-right bg-white">
                            <div>0</div>
                        </div>
                    </div>
                    <div class="coaching-info-box bg-tertiary clearfix">
                        <div class="coaching-box-left padding-8-0-0-6 text-cl-white">
                            <span>Level Study</span>
                        </div>
                        <div class="coaching-box-right bg-white">
                            <div>8.8</div>
                        </div>
                    </div>
                    <div class="coaching-info-box bg-tertiary clearfix">
                        <div class="coaching-box-left padding-8-0-0-6 text-cl-white">
                            <span>PT 1</span>
                        </div>
                        <div class="coaching-box-right bg-white">
                            <div>1</div>
                        </div>
                    </div>
                </div>
            </div>
            <div id='tab3'>
                <div class="comment-section">
                    <div class="comment-form padding-b-30 clearfix">
                        <div class="comment-form-textarea">
                            <textarea name="Text1" cols="70" rows="3" ... ></textarea>
                        </div>
                        <div class="save-cancel-btn right">
                            <button class="pure-button btn-blue btn-small"><a href="">Post</a></button>
                        </div>
                    </div>

                    <div class="box-capsule width20perc text-cl-tertiary font-14">
                        <span>Previous Notes</span>
                    </div>

                    <div class="comment-message clearfix">
                        <div class="padding-t-5">
                            <span>Andrew Wrote 17/05/2016 :</span>
                            <p>You'll need to teach more vocabulary, and it's only a dummy when it's called a dummy it means really what it is, this project b2c only dummy mockup.</p>
                        </div>
                    </div>
                    <div class="comment-message padding-t-10 clearfix">
                        <div class="padding-t-5">
                            <span>Andrew Wrote 17/05/2016 :</span>
                            <p>You'll need to teach more vocabulary, and it's only a dummy when it's called a dummy it means really what it is, this project b2c only dummy mockup.</p>
                        </div>
                    </div>
                    <div class="comment-message padding-t-10 clearfix">
                        <div class="padding-t-5">
                            <span>Andrew Wrote 17/05/2016 :</span>
                            <p>You'll need to teach more vocabulary, and it's only a dummy when it's called a dummy it means really what it is, this project b2c only dummy mockup.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div id='tab4'>
                <!-- ========== -->
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="col-md-2">
                                <input placeholder="Please input your messages here..." type="text" id="pesan" class="form-control"  style="width: 82%;">
                                <input placeholder="<?php echo $this->auth_manager->get_name();?>" type="hidden" id="user" 
                                    style="width: 15%;
                                           float: right;" 
                                    class="form-control" value="<?php echo $this->auth_manager->get_name();?>" disabled>
                                <input type="button" value="Send" id="kirim" class="pure-button btn-small btn-white"
                                    style="
                                    width: 15%;
                                    float: right;
                                    height: 26px;"
                                >
                                <ul id="isi_chat" style="list-style-type:none;padding-left: 5px;padding-right: 5px;border: 0.5px solid #cccccc;padding-bottom: 5px;padding-top: 1px;border-radius: 5px;margin-top: 3px;"> <ul>
                            </div>
                        </div>
                  </div>
                <!-- ========== -->
            </div>
        </div>
        <div class="sidebar-right padding-l-10 pure-u-md-5-12">
            <ul class='tabs padding0 clearfix'>
                <li><a href='#tab-right1' class="width136">Scripts</a></li>
                <li><a href='#tab-right2' class="width136">Guides</a></li>
                <li><a href='#tab-right3' class="width136">Bags of Tricks</a></li>
            </ul>
            <div id='tab-right1'>
                <div class="centang font-12">
                    <input type="checkbox" name="words" value="this">
                    <label>You'll need to teach more vocabulary, and its only a dummy when it really means this is 
                    just a dummy text that we've been using for DynEd Development Progress, You'll need 
                    to teach more vocabulary, and its only a dummy when it really means this is just a 
                    dummy text that we've been using for DynEd Development Progress.</label>
                </div>
            </div>
            <div id='tab-right2' class="font-12">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce suscipit bibendum nisi, a rutrum erat gravida at. Cras sed varius lacus, vel vestibulum eros. Sed non purus mauris. Proin maximus placerat nisi. Duis vestibulum magna magna, vel semper tortor vehicula ut. Sed condimentum augue in lorem convallis, quis venenatis turpis posuere. Ut quis sem pulvinar, interdum libero eu, sagittis dui. Suspendisse potenti. Morbi sed dui sit amet diam lobortis elementum. Nunc nec dui neque.</p>
            </div>
            <div id='tab-right3' class="font-12">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce suscipit bibendum nisi, a rutrum erat gravida at. Cras sed varius lacus, vel vestibulum eros. Sed non purus mauris. Proin maximus placerat nisi. Duis vestibulum magna magna, vel semper tortor vehicula ut. Sed condimentum augue in lorem convallis, quis venenatis turpis posuere. Ut quis sem pulvinar, interdum libero eu, sagittis dui. Suspendisse potenti. Morbi sed dui sit amet diam lobortis elementum. Nunc nec dui neque.</p>
            </div>
        </div>
              
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
    } else {
        seconds--;
    }
}
var countdownTimer = setInterval('timer()', 1000);
</script>

<script>

    //RADAR
    $('[data-tooltip]:after').css({'width':'115px'});

    var student_vrm = <?php echo $student_vrm; ?>;

    function Value(value, metadata){
        this.value= value;
        this.metadata = metadata;
    }

    Value.prototype.toString = function(){
        return this.value;
    }

    var wss = new Value(student_vrm.wss.percent_to_goal, {
        tooltipx : student_vrm.wss.raw_value
    })

    var repeat = new Value(student_vrm.repeats.percent_to_goal, {
        tooltipx : student_vrm.repeats.raw_value
    })

    var mic = new Value(student_vrm.mic.percent_to_goal, {
        tooltipx : student_vrm.mic.raw_value
    })

    var headphone = new Value(student_vrm.headphone.percent_to_goal, {
        tooltipx : student_vrm.headphone.raw_value
    })
    var sr = new Value(student_vrm.sr.percent_to_goal, {
        tooltipx : student_vrm.sr.raw_value
    })

    var mt = new Value(student_vrm.mt.percent_to_goal, {
        tooltipx : student_vrm.mt.raw_value
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
            scaleSteps: 4,
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
            scaleSteps: 4,
            scaleStepWidth: 28,
            scaleStartValue: 0,
            scaleLineColor : "#ededed",
        });
    }

    var legendHolder = document.createElement('div');
    legendHolder.innerHTML = bar.generateLegend();

    document.getElementById('legend').appendChild(legendHolder.firstChild);

</script>