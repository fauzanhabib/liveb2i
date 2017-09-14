<style>
    .phtext{
        margin-top: 10px !important;   
        padding: 15px;
        border: solid 2px #2b89b9;
        color: #2b89b9;
        border-radius: 10px;
        font-size: 20px;
        font-weight: 600;
        letter-spacing: 1px;
    }
    .rqcode{
        margin-top: 10px !important;
    }
    .loader{
        height: 30px;
        width: 30px;
        margin: auto;
        margin-top: 10px;
    }
    .marg0auto{
        margin: auto;
    }
    .codeinput{
        color: #144d80;
        margin: 0 auto;
    }

    input[type="text"]{
      color: #2b89b9;
      display: block;
      margin: 0 auto;
      border: none;
      padding: 0;
      width: 5.9ch;
      background: repeating-linear-gradient(90deg, #2b89b9 0, #2b89b9 1ch, transparent 0, transparent 1.5ch) 0 100%/ 10ch 2px no-repeat;
      font: 5ch droid sans mono, consolas, monospace;
      letter-spacing: 0.5ch;
    }
    input[type="text"]:focus {
      outline: none;
      color: #2b89b9;
    }

</style>

<div class="heading text-cl-primary border-b-1 padding15">
    <h1 class="margin0">Phone Number Verification</h1>
</div>

<div class="box">
    <div class="content">
        <div class="box pure-g">
            <p class="marg0auto text-center" style="color: black;">Hi <?php echo $prof[0]->fullname.', ' ;?>we have sent verification code to your number:<br>(Please be sure your phone is active)</p>
        </div>

        <div class="box pure-g">
            <font class="phtext marg0auto"><?php echo $prof[0]->dial_code.' '.$prof[0]->phone;?></font>
        </div>

        <div class="box pure-g">
            <input type="hidden" name="phonenum" id="phonenum" value="<?php echo $prof[0]->phone_verif;?>">
            <input type="hidden" name="fullname" id="fullname" value="<?php echo $prof[0]->fullname;?>">
            <font class="pure-button btn-small btn-green height-32 rqcode marg0auto" style="cursor: default !important;" id="codesent">Code Sent</font>
        </div>

        <p class="marg0auto text-center" style="color: black;margin-top: 10px;">
          Not getting the code? You can resend in
        </p>
        <p class="marg0auto text-center" style="color: black;">
          <strong><span id="countdown" class="timer"></span></strong>
          <button class="pure-button btn-small btn-tertiary height-32 rqcode marg0auto" id="rqagain" style="display: none;">Request Again</button>
        </p>

        <div id="inputcode">
          <hr style="width: 50%;margin-top: 10px;">
          <p class="marg0auto text-center" style="color: black;height: 20px;">
            Code will be expired at: 
          </p>
          <p class="marg0auto text-center" style="color: black;" id="expiryuntil">
            <strong><?php echo $hour; ?></strong>
          </p>
          <div class="box pure-g">
              <input type="text" id="codesubmit" maxlength='4'/>
          </div>
          <div class="box pure-g">
              <input type="hidden" name="userid" id="userid" value="<?php echo $prof[0]->user_id;?>">
              <button class="pure-button btn-small btn-tertiary rqcode marg0auto" id="verifbutton">Submit</button>
          </div>
          <div id="numnot"></div>
        </div>

    </div>
</div>

<script>
$( "#rqagain" ).click(function() {
    var user_id  = $('#user_id').val();

    $.ajax({
      type:"POST",
      url:"<?php echo site_url('account/verification/request_again');?>",    
      data: {'user_id':user_id},        
      success: function(data){
        window.location.href = '<?php echo site_url('account/verification'); ?>';
      }  
    });
});
</script>
<script>
$( "#verifbutton" ).click(function() {
  var userid  = $('#userid').val();
  var codesubmit  = $('#codesubmit').val();
  $("#sucnot").remove();
  $("#errnot").remove();
  // console.log(codesubmit);

  $.ajax({
    type:"POST",
    url:"<?php echo site_url('account/verification/verifynumb');?>",    
    data: {'codesubmit':codesubmit},        
    success: function(data){
      console.log(data);
      // 1=success, 2=not match, 3=match but expired
      if(data == '1'){
        window.location.href = '<?php echo site_url('account/verification');?>';
      }else if(data == '2'){
        $("#numnot").append('<p id="errnot" class="marg0auto text-center" style="color: #ea5656;margin-top: 5px;">Invalid Code</p>');
      }else if(data == '3'){
        window.location.href = '<?php echo site_url('account/verification');?>';
      }
    }  

  });

});
</script>
<script>
var upgradeTime = '<?php echo $total_sec ?>';

if(upgradeTime ==0){
  $("#countdown").hide();
  $("#rqagain").show();
}else{
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
          $("#countdown").hide();
          $("#rqagain").show();
      }else {
          seconds--;
      }
  }
  var countdownTimer = setInterval('timer()', 1000);
}
</script>