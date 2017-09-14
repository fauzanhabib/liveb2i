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
            <p class="marg0auto text-center" style="color: black;">Hi <?php echo $prof[0]->fullname.', ' ;?>it appears that the verification code we have sent to your number is expired:<br>(Please be sure your phone is active)</p>
        </div>

        <div class="box pure-g">
            <font class="phtext marg0auto"><?php echo $prof[0]->dial_code.' '.$prof[0]->phone;?></font>
        </div>

        <div class="box pure-g">
          <!-- <form action="<?php echo site_url('account/verification/testing');?>" method="POST"> -->
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $prof[0]->fullname;?>">
            <button class="pure-button btn-small btn-tertiary height-32 rqcode marg0auto" id="rqcbtn">Request Again</button>
            <font class="pure-button btn-small btn-green height-32 rqcode marg0auto" style="cursor: default !important;display: none;" id="codesent">Code Sent</font>
        </div>

    </div>
</div>

<script>
$( "#rqcbtn" ).click(function() {
    var user_id  = $('#user_id').val();
    $('#rqcbtn').hide();
    $('#codesent').show();
    $( "#inputcode" ).fadeIn( "slow", function() {});

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