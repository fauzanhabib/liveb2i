<head>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
  <script src="<?php echo base_url();?>assets/js/script.js"></script>
  <script src="<?php echo base_url();?>assets/js/AgoraRTCSDK-2.4.0.js"></script>
  <style>
    .localAgora{
      position: absolute;
      z-index: 10;
      width: 150px;
      height: 150px;
    }
</style>
</head>
<body>
  <div id="div_device" class="panel panel-default">
    <div class="select">
    <label for="audioSource">Audio source: </label><select id="audioSource"></select>
    </div>
    <div class="select">
    <label for="videoSource">Video source: </label><select id="videoSource"></select>
    </div>
    <input id="channel" type="text" value="1000" size="4"></input>
    <input id="video" type="checkbox" checked></input>
    <button id="sharescreench_ava">Share your screen</button>
  </div>

  <div id="video" style="margin:0 auto;">
     <div id="agora_local" class="localAgora"></div>
  </div>

  <script language="javascript">

    if(!AgoraRTC.checkSystemRequirements()) {
      alert("browser is no support webRTC");
    }

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
      var channel_name = "khususderry";
      // console.log("Ch Name = " + channel_name);
      // console.log("Init AgoraRTC client with vendor key: " + app_id);
      var key_type = "1";
      if(key_type == '1'){
        client = AgoraRTC.createClient({mode: "live", codec: "vp8"})
      }else{
        client = AgoraRTC.createClient({mode: "live", codec: "h264"})
      }
      client.init(app_id, function () {
        // console.log("AgoraRTC client initialized");
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
              // console.log("accessAllowed");
            });

            // The user has denied access to the camera and mic.
            localStream.on("accessDenied", function() {
              // console.log("accessDenied");
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

      var key_type = "1";
      if(key_type == '1'){
        client = AgoraRTC.createClient({mode: "live", codec: "vp8"})
      }else{
        client = AgoraRTC.createClient({mode: "live", codec: "h264"})
      }
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

          // if (document.getElementById("video").checked) {
            camera = videoSource.value;
            microphone = audioSource.value;
            client.unpublish(localStream, function (err) {
              console.log("Unpublish local stream failed" + err);
            });
            // localStream = AgoraRTC.createStream({streamID: uid, audio: true, cameraId: camera, microphoneId: microphone, video: document.getElementById("video").checked, screen: false});
            localStream = AgoraRTC.createStream({
              streamID: uid,
              audio:false,
              video:false,
              screen:true,
              extensionId:"bgjblkhpjchbmfeipbclmfnpohcpjcpn"
            });

            localStream.init(function() {
              // Play the stream
              localStream.play('Screen');
              // Publish the stream
              client.publish(localStream);

              // Listen to the 'stream-added' event
              client.on('stream-added', function(evt) {
              var stream = evt.stream;
              var uid = stream.getId()

              // Check if the stream is a local uid
              if(!localStreams.includes(uid)) {
                  console.log('subscribe stream:' + uid);
                  // Subscribe to the stream
                  client.subscribe(stream);
                  }
              })

            }, function (err) {
              console.log(err);
            });

            //localStream = AgoraRTC.createStream({streamID: uid, audio: false, cameraId: camera, microphoneId: microphone, video: false, screen: true, extensionId: 'minllpmhdgpndnkomcoccfekfegnlikg'});
            // if (document.getElementById("video").checked) {
            localStream.setVideoProfile('480_P1');

            // }

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
          // }
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
</body>
