<html>
  <head>
    <script type="text/javascript" src="https://static.opentok.com/v2/js/opentok.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <link href="<?php echo base_url();?>assets/simulator/styles.css" rel="stylesheet">
  </head>
  <body>
<style>
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 99; /* Sit on top */
    padding-top: 100px; /* Location of the box */
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

  <style>
  .hover{
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    transition: all 0.3s ease;
  }
  .hover:hover{
    -webkit-box-shadow: 0px 0px 18px 0px rgba(0,0,0,0.75);
    -moz-box-shadow: 0px 0px 18px 0px rgba(0,0,0,0.75);
    box-shadow: 0px 0px 18px 0px rgba(0,0,0,0.75);
  }
  .resultps{
    height: 150px;
    overflow-y: scroll;
    background: #f6f6f6;
    color: #666666;
    padding:10px;
    text-align: left !important;
    font-size: 14px;
  }
  .resultcont{
    width: 40%;
    display: inline-block;
  }
  #status_container{
    margin-right: 10px;
    vertical-align: top;
    width: 47% !important;
    display: inline-block !important;
    height: 500px;
    transform: translateY(8px);
  }
  #bwresult{
    padding: 15px 15px;
    height: 400px;
    background: #fff;
    width: 47%;
    display: inline-block;
    margin-top: 40px;
  }
  #liststat{
    height: 200px;
    padding: 10px;
    background-color: #f6f6f6;
    margin-top: 35px;
    text-align: left;
    font-size: 16px;
    overflow: auto;
  }
  #allowcamera{
    margin-top: 25px;
    text-align: left;
    margin-bottom: -15px;
    cursor: pointer;
    display: none;
  }
  </style>

  <h1>DynEd Live Session <small>Network test</small></h1>
  <div style="margin-top: 420px;text-align: center;">
    <div id="status_container" class="prettyBox hover">
      <h2>Testing stream capabilities</h2>
      <p>Acquiring camera</p>
      <img src="<?php echo base_url();?>assets/simulator/spinner.gif" id='loadingspinner'>
      <div id="allowcamera">How to Allow Camera on Chrome</div>
      <div id="liststat"></div>
    </div>

    <div id="bwresult" class="hover">
      <div style="width: 100%;text-align: left;margin-bottom: 20px;">
      	<h5 style="margin: 0px !important;">Browser Info:</h5>
      	<p style="font-size: 14px;"><b id="browinfo"></b></p>
      	<p style="font-size: 14px;" id="browmess"></p>
      </div>

      <h5 style="margin: 0px !important;">Your Bandwidth:</h5>

      <div style="text-align: center;">
        <div class="resultcont">
          <h5>Video:</h5>
          <div id="resultvideo" class="resultps"></div>
        </div>

        <div class="resultcont">
          <h5>Audio:</h5>
          <div id="resultaudio" class="resultps"></div>
        </div>
      </div>

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
              <td style="width: 40%">Choose "Always allow...", then choose the appropriate camera and microphone in your device. Click "Done", and <b>reload the page.</b></td>
            </tr>
          </table>
      </div>
    </div>
<script>
var modal = document.getElementById('myModal');
var btn   = document.getElementById("allowcamera");
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

<script>
  var objDiv = document.getElementById("resultvideo");
  objDiv.scrollTop = objDiv.scrollHeight;
</script>
<script>
	/**
* Replace these with your OpenTok API key, a session ID for a routed OpenTok session,
* and a token that has the publish role:
*/
var API_KEY = '<?php echo $apiKey; ?>';
var SESSION_ID = '<?php echo $sessionId; ?>';
var TOKEN = '<?php echo $token; ?>';

var TEST_TIMEOUT_MS = 15000; // 15 seconds

var publisherEl = document.createElement('div');
var subscriberEl = document.createElement('div');

var session;
var publisher;
var subscriber;
var statusContainerEl;
var statusMessageEl;
var statusIconEl;

var bwps;

var testStreamingCapability = function(subscriber, callback) {
  performQualityTest({subscriber: subscriber, timeout: TEST_TIMEOUT_MS}, function(error, results) {
    console.log('Test concluded', results);

    var audioVideoSupported = results.video.bitsPerSecond > 250000 &&
      results.video.packetLossRatioPerSecond < 0.03 &&
      results.audio.bitsPerSecond > 25000 &&
      results.audio.packetLossRatioPerSecond < 0.05;

    if (audioVideoSupported) {
      $('#liststat').append('<p style="font-size:15px;color:#666666;"><img src="<?php echo base_url();?>assets/icon/simulator/Audio_videoready.svg"><br>Your bandwidth is sufficient for both video and audio <a style="color: #58ba84;">&#x2714;</a></p>');
      return callback(false, {
        text: 'You\'re all set!',
        icon: '<?php echo base_url();?>assets/icon/simulator/success.svg'
      });
    }

    if (results.audio.packetLossRatioPerSecond < 0.05) {
      return callback(false, {
        text: 'Your bandwidth can support audio only',
        icon: '<?php echo base_url();?>assets/icon/simulator/error.svg'
      });
    }

    // try audio only to see if it reduces the packet loss
    setText(
      statusMessageEl,
     'Trying audio only'
   );

    publisher.publishVideo(false);

    performQualityTest({subscriber: subscriber, timeout: 5000}, function(error, results) {
      var audioSupported = results.audio.bitsPerSecond > 25000 &&
          results.audio.packetLossRatioPerSecond < 0.05;

      if (audioSupported) {
        return callback(false, {
          text: 'Your bandwidth can support audio only',
          icon: '<?php echo base_url();?>assets/simulator/icon_warning.svg'
        });
      }

      return callback(false, {
        text: 'Your bandwidth is too low for audio',
        icon: '<?php echo base_url();?>assets/simulator/icon_error.svg'
      });
    });
  });
};

var callbacks = {
  onInitPublisher: function onInitPublisher(error) {
    if (error) {
      $('#liststat').append('<p style="font-size:15px;color:#666666;">Your browser is blocking your camera.</p>');
      $('#allowcamera').show();
      setText(statusMessageEl, 'Could not acquire your camera');
      return;
    }
    $('#liststat').append('<p style="font-size:15px;color:#666666;"><img src="<?php echo base_url();?>assets/icon/simulator/camera.svg"><br>Camera is connected <a style="color: #58ba84;">&#x2714;</a></p>');
    setText(statusMessageEl, 'Connecting to session');
  },

  onPublish: function onPublish(error) {
    if (error) {
      // handle publishing errors here
      setText(
        statusMessageEl,
        'Could not publish video'
      );
      return;
    }
    $('#liststat').append('<p style="font-size:15px;color:#666666;"><img src="<?php echo base_url();?>assets/icon/simulator/Publish_Video.svg"><br>You can publish a video <a style="color: #58ba84;">&#x2714;</a></p>');
    setText(
      statusMessageEl,
      'Subscribing to video'
    );

    subscriber = session.subscribe(
      publisher.stream,
      subscriberEl,
      {
        audioVolume: 0,
        testNetwork: true
      },
      callbacks.onSubscribe
    );
  },

  cleanup: function() {
    // session.unsubscribe(subscriber);
    // session.unpublish(publisher);
  },

  onSubscribe: function onSubscribe(error, subscriber) {
    if (error) {
      setText(statusMessageEl, 'Could not subscribe to video');
      return;
    }
    $('#liststat').append('<p style="font-size:15px;color:#666666;"><img src="<?php echo base_url();?>assets/icon/simulator/Video_Subscribe.svg"><br>You can subscribe to video <a style="color: #58ba84;">&#x2714;</a></p>');
    setText(statusMessageEl, 'Checking your available bandwidth');

    testStreamingCapability(subscriber, function(error, message) {
      setText(statusMessageEl, message.text);
      statusIconEl.src = message.icon;
      callbacks.cleanup();
    });
  },

  onConnect: function onConnect(error) {
    if (error) {
      setText(statusMessageEl, 'Could not connect to OpenTok. Please check your connection and reload this page.');
      $('#loadingspinner').hide();
    }
  }
};

compositeOfCallbacks(
  callbacks,
  ['onInitPublisher', 'onConnect'],
  function(error) {
    if (error) {
      return;
    }

    setText(statusMessageEl, 'Publishing video');
    session.publish(publisher, callbacks.onPublish);
  }
);

document.addEventListener('DOMContentLoaded', function() {
  var container = document.createElement('div');
  container.className = 'container';

  container.appendChild(publisherEl);
  container.appendChild(subscriberEl);
  document.body.appendChild(container);

  // This publisher uses the default resolution (640x480 pixels) and frame rate (30fps).
  // For other resoultions you may need to adjust the bandwidth conditions in
  // testStreamingCapability().
  
  publisher = OT.initPublisher(publisherEl, {}, callbacks.onInitPublisher);

  session = OT.initSession(API_KEY, SESSION_ID);
  session.connect(TOKEN, callbacks.onConnect);
  statusContainerEl = document.getElementById('status_container');
  statusMessageEl = statusContainerEl.querySelector('p');
  statusIconEl = statusContainerEl.querySelector('img');
});

// Helpers
function setText(el, text) {
  if (!el) {
    return;
  }

  if (el.textContent) {
    el.textContent = text;
  }

  if (el.innerText) {
    el.innerText = text;
  }
}

function pluck(arr, propertName) {
  return arr.map(function(value) {
    return value[propertName];
  });
}

function sum(arr, propertyName) {
  if (typeof propertyName !== 'undefined') {
    arr = pluck(arr, propertyName);
  }

  return arr.reduce(function(previous, current) {
    return previous + current;
  }, 0);
}

function max(arr) {
  return Math.max.apply(undefined, arr);
}

function min(arr) {
  return Math.min.apply(undefined, arr);
}

function calculatePerSecondStats(statsBuffer, seconds) {
  var stats = {};
  ['video', 'audio'].forEach(function(type) {
    stats[type] = {
      packetsPerSecond: sum(pluck(statsBuffer, type), 'packetsReceived') / seconds,
      bitsPerSecond: (sum(pluck(statsBuffer, type), 'bytesReceived') * 8) / seconds,
      packetsLostPerSecond: sum(pluck(statsBuffer, type), 'packetsLost') / seconds
    };
    stats[type].packetLossRatioPerSecond = (
      stats[type].packetsLostPerSecond / stats[type].packetsPerSecond
    );
  });

  stats.windowSize = seconds;
  return stats;
}

function getSampleWindowSize(samples) {
  var times = pluck(samples, 'timestamp');
  return (max(times) - min(times)) / 1000;
}

if (!Array.prototype.forEach) {
  Array.prototype.forEach = function(fn, scope) {
    for (var i = 0, len = this.length; i < len; ++i) {
      fn.call(scope, this[i], i, this);
    }
  };
}

function compositeOfCallbacks(obj, fns, callback) {
  var results = {};
  var hasError = false;

  var checkDone = function checkDone() {
    if (Object.keys(results).length === fns.length) {
      callback(hasError, results);
      callback = function() {};
    }
  };

  fns.forEach(function(key) {
    var originalCallback = obj[key];

    obj[key] = function(error) {
      results[key] = {
        error: error,
        args: Array.prototype.slice.call(arguments, 1)
      };

      if (error) {
        hasError = true;
      }

      originalCallback.apply(obj, arguments);
      checkDone();
    };
  });
}

function bandwidthCalculatorObj(config) {
  var intervalId;

  config.pollingInterval = config.pollingInterval || 500;
  config.windowSize = config.windowSize || 2000;
  config.subscriber = config.subscriber || undefined;

  return {
    start: function(reportFunction) {
      var statsBuffer = [];
      var last = {
        audio: {},
        video: {}
      };

      intervalId = window.setInterval(function() {
        config.subscriber.getStats(function(error, stats) {
          var snapshot = {};
          var nowMs = new Date().getTime();
          var sampleWindowSize;

          ['audio', 'video'].forEach(function(type) {
            snapshot[type] = Object.keys(stats[type]).reduce(function(result, key) {
              result[key] = stats[type][key] - (last[type][key] || 0);
              last[type][key] = stats[type][key];
              return result;
            }, {});
          });

          // get a snapshot of now, and keep the last values for next round
          snapshot.timestamp = stats.timestamp;

          statsBuffer.push(snapshot);
          statsBuffer = statsBuffer.filter(function(value) {
            return nowMs - value.timestamp < config.windowSize;
          });

          sampleWindowSize = getSampleWindowSize(statsBuffer);

          if (sampleWindowSize !== 0) {
            reportFunction(calculatePerSecondStats(
              statsBuffer,
              sampleWindowSize
            ));
          }
        });
      }, config.pollingInterval);
    },

    stop: function() {
      window.clearInterval(intervalId);
    }
  };
}

function performQualityTest(config, callback) {
  var startMs = new Date().getTime();
  var testTimeout;
  var currentStats;

  var bandwidthCalculator = bandwidthCalculatorObj({
    subscriber: config.subscriber
  });

  var cleanupAndReport = function() {
    currentStats.elapsedTimeMs = new Date().getTime() - startMs;
    callback(undefined, currentStats);

    window.clearTimeout(testTimeout);
    bandwidthCalculator.stop();

    callback = function() {};
  };

  // bail out of the test after 30 seconds
  window.setTimeout(cleanupAndReport, config.timeout);

  bandwidthCalculator.start(function(stats) {
    // console.log(stats);
    bwps = stats;
    fbwps();
    // you could do something smart here like determine if the bandwidth is
    // stable or acceptable and exit early
    currentStats = stats;
  });
}

function fbwps(){
  bpsaudio_raw = bwps['audio']['bitsPerSecond'];
  bpsvideo_raw = bwps['video']['bitsPerSecond'];

  bpsaudio = bpsaudio_raw / 1024;
  bpsvideo = bpsvideo_raw / 1024;

  plvideo = bwps['video']['packetLossRatioPerSecond'].toFixed(3);
  plaudio = bwps['audio']['packetLossRatioPerSecond'].toFixed(3);

  abps = Math.round(bpsaudio).toFixed(0)+' kbps';
  vbps = Math.round(bpsvideo).toFixed(0)+' kbps';

  // console.log(vbps);
  if(vbps != 0){
    $('#resultvideo').append('<tr><td style="width: 50%;"><p style="margin: 5px !important">'+vbps+'</p></td><td style="width: 50%;">'+plvideo+' packet loss</td</tr>');
    $('#resultvideo').animate({"scrollTop": $('#resultvideo')[0].scrollHeight}, "fast");
  }
  $('#resultaudio').append('<tr><td style="width: 50%;"><p style="margin: 5px !important">'+abps+'</p></td><td style="width: 50%;">'+plaudio+' packet loss</td</tr>');
  $('#resultaudio').animate({"scrollTop": $('#resultaudio')[0].scrollHeight}, "fast");

}
</script>
<script>
	function get_browser() {
	    var ua=navigator.userAgent,tem,M=ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || []; 
	    if(/trident/i.test(M[1])){
	        tem=/\brv[ :]+(\d+)/g.exec(ua) || []; 
	        return {name:'IE',version:(tem[1]||'')};
	        }   
	    if(M[1]==='Chrome'){
	        tem=ua.match(/\bOPR|Edge\/(\d+)/)
	        if(tem!=null)   {return {name:'Opera', version:tem[1]};}
	        }   
	    M=M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
	    if((tem=ua.match(/version\/(\d+)/i))!=null) {M.splice(1,1,tem[1]);}
	    return {
	      name: M[0],
	      version: M[1]
	    };
	}

	var browser=get_browser();
	// console.log(browser);
	if(browser.name == "Chrome" || browser.name == "Firefox"){
		$('#browinfo').text(browser.name + ' v.' + browser.version);
		if(browser.name == "Chrome" && browser.version < 59){
			$('#browmess').append('Your Chrome browser is <font style="color:#ba3a3a;font-weight:600;">outdated</font>. Please update by clicking <a href="https://www.google.com/chrome/browser/desktop/index.html" style="text-decoration: underline;">this link</a> then run this page again after you have updated.');
		}else if(browser.name == "Chrome" && browser.version >= 59){
			$('#browmess').append('Your Chrome is able to run session <a style="color: #58ba84;">&#x2714;</a>');
		}else if(browser.name == "Firefox" && browser.version < 53){
			$('#browmess').append('Your Firefox browser is <font style="color:#ba3a3a;font-weight:600;">outdated</font>. Please update by clicking <a href="https://www.mozilla.org/en-US/firefox/new/" style="text-decoration: underline;">this link</a> then run this page again after you have updated.');
		}else if(browser.name == "Firefox" && browser.version >= 53){
			$('#browmess').append('Your Firefox is able to run session <a style="color: #58ba84;">&#x2714;</a>');
		}
	}else{
		$('#browinfo').text(browser.name + ' v.' + browser.version);
		$('#browmess').append('Your browser <font style="color:#ba3a3a;font-weight:600;">does NOT</font> support LIVE Session, please only use latest version of <a href="https://www.google.com/chrome/browser/desktop/index.html" style="text-decoration: underline;">Chrome</a> or <a href="https://www.mozilla.org/en-US/firefox/new/" style="text-decoration: underline;">Firefox</a>');
	}
</script>
</body>
</html>