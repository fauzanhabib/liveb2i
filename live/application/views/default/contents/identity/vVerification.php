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

    .rsnd{
      color: #3baae3;
      text-decoration: underline;
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
            <p class="marg0auto text-center" style="color: black;">Hi <?php echo $prof[0]->fullname.', ' ;?>you're about to verify your number:<br>(Please be sure your phone is active)</p>
        </div>

        <div class="box pure-g">
            <font class="phtext marg0auto"><?php echo $prof[0]->dial_code.' '.$prof[0]->phone;?></font>
        </div>

        <div class="box pure-g">
          <!-- <form action="<?php echo site_url('account/verification/testing');?>" method="POST"> -->
            <input type="hidden" name="phonenum" id="phonenum" value="<?php echo $prof[0]->phone_verif;?>">
            <input type="hidden" name="fullname" id="fullname" value="<?php echo $prof[0]->fullname;?>">
            <button class="pure-button btn-small btn-tertiary height-32 rqcode marg0auto" id="rqcbtn">Request Code</button>
            <font class="pure-button btn-small btn-green height-32 rqcode marg0auto" style="cursor: default !important;display: none;" id="codesent">Code Sent</font>
        </div>

        <p class="marg0auto text-center" style="color: black;margin-top: 10px;display: none;" id="resend">
          Not getting the code?<br>You can <a class="rsnd" id="rsnd">Resend</a> code after 10 minutes
        </p>

        <div id="inputcode" style="display: none;">
          <hr style="width: 50%;margin-top: 30px;">
          <p class="marg0auto text-center" style="color: black;height: 20px;">
            Code that was sent to your number will be expired at: 
          </p>
          <p class="marg0auto text-center" style="color: black;" id="expiryuntil">

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
$( "#rqcbtn" ).click(function() {
    var phonenum  = $('#phonenum').val();
    var fullname  = $('#fullname').val();

    $('#rqcbtn').hide();
    $('#codesent').show();
    $('#resend').show();
    $( "#inputcode" ).fadeIn( "slow", function() {});

    $.ajax({
      type:"POST",
      url:"<?php echo site_url('account/verification/request_code');?>",    
      data: {'fullname':fullname, 'phonenum':phonenum},
      success: function(data){
        console.log(data);
        $("#expiryuntil").append( "<strong>"+data+"</strong>" );
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
$( "#rsnd" ).click(function() {
  window.location.href = '<?php echo site_url('account/verification'); ?>';
});
</script>