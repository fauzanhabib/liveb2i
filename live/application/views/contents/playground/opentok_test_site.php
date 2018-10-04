<html>
<head>
    <title> DynEd - Opentok Test Site </title>
    <script src="https://static.opentok.com/v2/js/opentok.min.js"></script>
    <style>
      body, html {
        background-color: gray;
        height: 100%;
      }

      #videos {
        position: relative;
        width: 100%;
        height: 100%;
        margin-left: auto;
        margin-right: auto;
      }

      #subscriber {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
      }

      #publisher {
        position: absolute;
        width: 360px;
        height: 240px;
        bottom: 10px;
        left: 10px;
        z-index: 100;
        border: 3px solid white;
        border-radius: 3px;
      }

      .btn_screenshare{
        position: absolute;
        left: 30%;
        bottom: 10%;
        z-index: 999;
      }

    </style>
</head>
<body>

    <div id="videos">
        <div id="subscriber"></div>
        <div id="publisher"></div>
    </div>
    <button class="btn_screenshare" onclick="javascript:screenshare();">Share your screen</button>

    <script type="text/javascript">
      // replace these values with those generated in your TokBox Account
      var apiKey = "<?php echo $apiKey; ?>";
      var sessionId = "<?php echo $sessionId; ?>";
      var token = "<?php echo $token; ?>";
      var session;
      // Handling all of our errors here by alerting them
      function handleError(error) {
        if (error) {
          alert(error.message);
        }
      }

      // (optional) add server code here
      initializeSession();

      function initializeSession() {
        session = OT.initSession(apiKey, sessionId);

        // Subscribe to a newly created stream
        session.on('streamCreated', function(event) {
          session.subscribe(event.stream, 'subscriber', {
            insertMode: 'append',
            width: '100%',
            height: '100%'
          }, handleError);
        });

        // Create a publisher
        var publisher = OT.initPublisher('publisher', {
          insertMode: 'append',
          width: '100%',
          height: '100%'
        }, handleError);

        // Connect to the session
        session.connect(token, function(error) {
          // If the connection is successful, initialize a publisher and publish to the session
          if (error) {
            handleError(error);
          } else {
            session.publish(publisher, handleError);
          }
        });
      }

      OT.registerScreenSharingExtension('chrome', 'efiofhlccdnkddnjdagljnhgoibifhki', 2);

      function screenshare() {
        OT.checkScreenSharingCapability(function(response) {
          if (!response.supported || response.extensionRegistered === false) {
            alert('This browser does not support screen sharing.');
          } else if (response.extensionInstalled === false) {
            alert('Please install the screen sharing extension and load your app over https.');
          } else {
            // Screen sharing is available. Publish the screen.
            var screenSharingPublisher = OT.initPublisher('screen-preview', {videoSource: 'screen'});
            session.publish(screenSharingPublisher, function(error) {
              if (error) {
                alert('Could not share the screen: ' + error.message);
              }
            });
          }
        });
      }

    </script>

</body>
</html>
