<script src='//static.opentok.com/v2/js/opentok.min.js'></script> 
<script charset="utf-8">
    var apiKey = '45585812';
    var sessionId = '<?php echo $sessionId ?>';
    var token = '<?php echo $token ?>';
    var session = OT.initSession(apiKey, sessionId);
    //Self
        session.connect(token, function(error) {
            var publisher = OT.initPublisher('myPublisherElementId',
                                 {insertMode: 'append',
                                  width: '100%',
                                  height: 200, name: "<?php echo $this->auth_manager->get_name();?>"});
            session.publish(publisher);
    });
    //Other
        session.on('streamCreated', function(event) {
            var subscriberProperties = {insertMode: 'append',
                                        width: '100%',
                                        height: 500, name: "<?php echo $this->auth_manager->get_name();?>"};
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
</script>
<script>
    function makeFullScreen(divId){
        document.getElementById("subscriberContainer").webkitRequestFullScreen() //example for Chrome
        var element = document.getElementById("subscriberContainer");
        var fullScreenFunction = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullScreen || element.oRequestFullScreen;

        if (fullScreenFunction) {
            fullScreenFunction.apply(element);
        } else {
            alert("don't know the prefix for this browser");
        }
    }
</script>

<style>
  .publisher {
      position: absolute;
    }
  .subscriber {
  top: 0;
  left: 0;
  width: 100%;
  z-index: 100;
}
.publisher {
  bottom: 3.4em;
  right: 0.9em;
  width: 20%;
  z-index: 200;
  border: 2px solid white;
  border-radius: 1px;
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
#subscriberContainer:-webkit-full-screen {
  width: 100% !important;
  height: 500px !important;
}

#subscriberContainer:-moz-full-screen {
  width: 100% !important;
 height: 500px !important;
}

#subscriberContainer:-ms-full-screen {
  width: 100% !important;
 height: 500px !important;
}

#subscriberContainer:-o-full-screen {
  width: 100% !important;
  height: 500px !important;
}

#subscriberContainer:full-screen {
  width: 100% !important;
 height: 500px !important;
}
</style>
<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Live Session</h1>
</div>

<div class="box b-f3-1">
  <div class="content padding15">
    <h4>Hei, <?php echo $this->auth_manager->get_name();?></h4>
    <h2>You Have a Live Session a</h2>
    <hr>
    <div class="subscriber" id="subscriberContainer"></div>
    <div class="publisher" id="myPublisherElementId"></div>
    <a class="pure-button btn-small btn-white" onclick="makeFullScreen(subscriberContainer)">Fullscreen</a>
  </div>
</div>