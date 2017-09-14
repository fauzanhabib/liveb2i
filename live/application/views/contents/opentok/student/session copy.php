<link rel="chrome-webstore-item" href="https://chrome.google.com/webstore/detail/efiofhlccdnkddnjdagljnhgoibifhki">
<script src='//static.opentok.com/v2/js/opentok.min.js'></script> 
<script charset="utf-8">
    var apiKey = '<?php echo $apiKey ?>';
    var sessionId = '<?php echo $sessionId ?>';
    var token = '<?php echo $token ?>';
    var session = OT.initSession(apiKey, sessionId);
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
                                        height: 400, name: "<?php echo $this->auth_manager->get_name();?>"};
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

          setInterval(function(){
              tampildata();
            },1000);

          });
    </script>
    <script type="text/javascript">
      $('input:text').focus(
      function(){
          $(this).val('');
      });
    </script>
    <style>
    #isi_chat{
    height: 125px;
    overflow-y: scroll;}
    </style>

<style>
   .publisher {
    position: absolute;
    bottom: 13.5em;
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
<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Live Session - Student</h1>
 
    <a class="pure-button btn-small btn-red" onclick="if (confirm('Are You Sure Want to Leave Session? Your chat history will be deleted')) 
    window.location='<?php echo site_url('opentok/leavesession/');?>'; 
    return false">Leave Session</a>
   
</div>

<div class="box b-f3-1">
  <div class="content padding15">
    <div class="fullscreenarea" id="fullarea">
        <div class="subscriber" id="subscriberContainer"><div class="publisher" id="myPublisherElementId"></div></div>
        <a onclick="makeFullScreen(fullarea)" style="position: absolute; top: 450px; right: 30px;">
          <img class="fs-icon" src="<?php echo base_url();?>assets/icon/expand2x.png"></img>
        </a>
    </div>
    
    
    <div class="col-md-12">
      <div style="padding-top: 5px;float: left; width: 50%;">
        <div class="col-md-2">
          <input placeholder="Please input your messages here..." type="text" id="pesan" class="form-control" style="width: 84%">
          <input placeholder="<?php echo $this->auth_manager->get_name();?>" type="hidden" id="user" class="form-control" value="<?php echo $this->auth_manager->get_name();?>" disabled>
          <audio id="chat_audio" src="<?php echo base_url();?>assets/sound/chat.mp3" preload="auto"></audio>
          <input type="submit" value="Send" id="kirim" class="pure-button btn-small btn-white" style="width: 14%;float: right;height:26px;">
        </div>
        <div class="panel-body" style="padding-left: 10px;padding-right: 10px;border: 1px solid #cccccc;border-radius: 5px;margin-top: 3px;">
            <ul id="isi_chat" style="list-style-type:none;padding-left: 0px;"> <ul>
        </div>
      </div>
    </div>
  </div>
</div>