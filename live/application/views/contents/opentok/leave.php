<script type="text/javascript">
  window.onbeforeunload = function() {
  return "If you close this page, you will not get a token from this session.";
};
</script>

<style type="text/css">
#cke_1_contents{
    height: 100px !important;
    border-bottom: solid 1px #f2f2f2 !important;
    border-right: solid 1px #f2f2f2 !important;
    border-left: solid 1px #f2f2f2 !important;
  }

    div.stars {
      width: 285px;
      display: inline-block;
    }

    input.star { display: none; }

    label.star {
      float: right;
      padding: 10px;
      font-size: 36px;
      /*color: #444;*/
      transition: all .2s;
    }

    input.star:checked ~ label.star:before {
      content: '\f005';
      color: #FD4;
      transition: all .25s;
    }

    input.star-5:checked ~ label.star:before {
      color: #FE7;
      text-shadow: 0 0 20px #952;
    }

    input.star-1:checked ~ label.star:before { color: #F62; }

    label.star:hover { transform: rotate(-15deg) scale(1.3); }

    label.star:before {
      content: '\f006';
      font-family: FontAwesome;
    }

    @media only screen and (max-width: 500px) {
      div.stars {
        width: auto
      }

      #thetable tbody tr {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
      }
    }
</style>

<script>
$(document).ready(function(){

    $('#ratecoach').click(function(){
       var star = $('input:radio[name=star]:checked').val();
       var coach_id = '<?php echo $user->coach_id; ?>';
       var appointment_id = '<?php echo $appointment_id ?>';
        if ( !$('#star-1').is(':checked') && !$('#star-2').is(':checked') && !$('#star-3').is(':checked') && 
            !$('#star-4').is(':checked') && !$('#star-5').is(':checked') )
            {
            alert("Zero star? We don't have it.");
            return false;
            }
        else{
            $.ajax({
              type:"POST",
              url:"<?php echo site_url('opentok/leavesession/rate_coach');?>",    
              data: {
                'star' : star, 
                'coach_id' : coach_id, 
                'appointment_id': appointment_id
                },        
              success: function(data){
                window.onbeforeunload = null;    
                alert('Rating: ' + star + ' out of 5');
                // window.location.href = "<?php echo site_url('student/dashboard');?>";
              }  
             });
            }
      });

    $('.exitbtn').click(function(){
      var coach_id = '<?php echo $user->coach_id; ?>';
      var appointment_id = '<?php echo $appointment_id ?>';
      $.ajax({
        type:"POST",
        url:"<?php echo site_url('opentok/leavesession/check_rate');?>",    
        data: {
          'coach_id' : coach_id, 
          'appointment_id': appointment_id
          },        
        success: function(data){
          if(data != "exit"){
            alert(data);
          }else if(data = "exit"){
            window.location.href = "<?php echo site_url('student/dashboard');?>";
          }
        }  
      });
    });
    
    $('.exitcch').click(function(){
      window.location.href = "<?php echo site_url('coach/dashboard');?>";
    });

});
</script>

<!-- COACH NOTE STARTS -->
<script>    
$(document).ready(function(){

    $('#save_note').click(function(){
       var cch_note = CKEDITOR.instances.cch_note.getData();
       var appointment_id = '<?php echo $appointment_id ?>';
        if ( !cch_note )
        {
          alert("You can't send an empty note");
          return false;
        }else{
          // alert( appointment_id );
          $.ajax({
            type:"POST",
            url:"<?php echo site_url('opentok/leavesession/save_cchnote');?>",    
            data: {
              'cch_note' : cch_note,
              'appointment_id' : appointment_id
              },        
              success: function(data){  
                window.onbeforeunload = null;  
                alert('Notes Updated');
                // window.location.href = "<?php echo site_url('coach/dashboard');?>";
              }
          });
        }
      });

});
</script>
<!-- COACH NOTE ENDS -->

<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Session Summaries</h1>
</div>

<div class="box b-f3-1">
  <div class="content padding15">
    <div class="col-md-12">
      <p>Your session with <b><?php echo $user->fullname;?></b> has ended.</p>

            <table id="thetable" class="table-sessions" style="border-top:none"> 
                <thead>
                    <tr bgcolor="#144d80">
                        <th class="padding15 sm-12 tb-ses-up"><font color="white">DATE</font></th>
                        <th class="padding15 md-none"><font color="white">START TIME</font></th>
                        <th class="padding15 md-none"><font color="white">END TIME</font></th>
                        <th class="padding15 md-none">
                        <?php if($role=='CCH'){ ?>
                        <font color="white">STUDENT</font>
                        <?php } else{?>
                        <font color="white">COACH</font>
                        <?php }?>
                        </th>
                        <th style="text-align: center;" class="padding15 sm-12"><font color="white">YOU JOINED AT</font></th>
                        <th style="text-align: center;" class="padding15 sm-12"><font color="white">
                        <?php if($role=='CCH'){ ?>
                        <font color="white">YOUR STUDENT</font>
                        <?php } else{?>
                        <font color="white">YOUR COACH</font>
                        <?php }?> JOINED AT
                        </font></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="padding-10-15 sm-12 tb-ses-up">
                            <?php echo(date('F d, Y', strtotime(@$user->date))); ?>
                            <span class="text-cl-green lg-none"><?php echo(date('H:i',strtotime(@$user->start_time)));?> - <?php echo(date('H:i',strtotime(@$user->start_time)));?></span>
                            <span class="lg-none">
                                Student :<br>
                                <a href="<?php echo site_url('coach/upcoming_session/student_detail/' . $user->student_id); ?>" class="text-cl-secondary">
                                    <?php echo @$user->student_name; ?>
                                </a>
                            </span>
                        </td>
                        <?php

                          $id = $this->auth_manager->userid();
                          // $utz = $this->db->select('user_timezone')
                          //         ->from('user_profiles')
                          //         ->where('user_id', $id)
                          //         ->get()->result();
                          // $idutz = $utz[0]->user_timezone;
                          $tz = $this->db->select('*')
                                  ->from('user_timezones')
                                  ->where('user_id', $id)
                                  ->get()->result();
                          
                          $minutes = $tz[0]->minutes_val;
                          //User Hour
                          date_default_timezone_set('UTC');
                          $date     = @$user->start_time;
                          $default  = strtotime($date);
                          $usertime = $default+(60*$minutes);
                          $start  = date("H:i:s", $usertime);

                          $date2     = @$user->end_time;
                          $default2  = strtotime($date2);
                          $usertime2 = $default2+(60*$minutes)-300;
                          $end  = date("H:i:s", $usertime2);
                         // print_r($end);
                         //  exit();
                        ?>
                        <td class="time padding-10-15 md-none text-cl-green"><?php echo @$start;?></td>
                        <td class="time padding-10-15 md-none text-cl-green"><?php echo @$end;?></td>
                        <td class="padding-10-15 md-none">
                            <?php echo @$user->fullname; ?>
                        </td>
                        <td class="padding-10-15 sm-12" align="center">
                            <?php 
                              if(@$user->cch_attend == NULL){
                                @$cch_att_conv = "Coach didn't attend the session.";
                              }else{
                                $date3     = @$user->cch_attend;
                                $default3  = strtotime($date3);
                                $usertime3 = $default3+(60*$minutes);
                                $cch_att_conv = date("H:i:s", $usertime3);
                              }

                              if(@$user->std_attend == NULL){
                                @$std_att_conv = "Student didn't attend the session.";
                              }else{
                                $date4     = @$user->std_attend;
                                $default4  = strtotime($date4);
                                $usertime4 = $default4+(60*$minutes);
                                $std_att_conv = date("H:i:s", $usertime4);
                              }

                              if($role=='CCH'){
                                echo @$cch_att_conv;
                              }else if($role=='STD'){
                                echo @$std_att_conv;
                              }
                            ?>                 
                        </td>
                        <td class="padding-10-15 sm-12" align="center">
                            <?php 
                            // if(@$cch_att_conv == NULL){
                            //   @$cch_att_conv = "Coach didn't attend the session.";
                            // }else if(@$std_att_conv == NULL){
                            //   @$std_att_conv = "Coach didn't attend the session.";
                            // }

                              if($role=='STD'){
                                echo @$cch_att_conv;
                              }else if($role=='CCH'){
                                echo @$std_att_conv;
                              }
                            ?>             
                        </td>
                    </tr>
                </tbody>
            </table><br>
            <?php
                // echo "<pre>";
                // print_r($role);
                // exit();
                if($role=='STD'){ 
            ?>
            <table id="thetable" class="table-sessions" style="border-top:none"> 
                <thead>
                    <tr bgcolor="#2b89b9">
                        <th align="center" class="padding15 sm-12 tb-ses-up" style="width:30%" ><font color="white">Rate Your Coach</font></th>
                        <th class="padding15 md-none"></th>
                        <th class="padding15 md-none"><font color="white">Coach Name</font></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="padding-10-15 inlineBl-resp">
                            <div class="stars">
                                <input class="star star-5" id="star-5" value="5" type="radio" name="star"/>
                                <label class="star star-5" for="star-5"></label>
                                <input class="star star-4" id="star-4" value="4" type="radio" name="star"/>
                                <label class="star star-4" for="star-4"></label>
                                <input class="star star-3" id="star-3" value="3" type="radio" name="star"/>
                                <label class="star star-3" for="star-3"></label>
                                <input class="star star-2" id="star-2" value="2" type="radio" name="star"/>
                                <label class="star star-2" for="star-2"></label>
                                <input class="star star-1" id="star-1" value="1" type="radio" name="star"/>
                                <label class="star star-1" for="star-1"></label>
                            </div>
                        </td>
                        <td class="padding-10-15 inlineBl-resp">
                            <input type="submit" id="ratecoach" value="RATE" class="pure-button btn-small btn-white">
                        </td>
                        <td class="padding-10-15 md-none">
                            <?php echo @$user->fullname; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php } else if($role=='CCH'){ ?>
            <table id="thetable" class="table-sessions" style="border-top:none; width: 600px;"> 
                <thead>
                    <tr bgcolor="#2b89b9">
                     <th align="center" class="padding15 sm-12 tb-ses-up" style="width:30%" >
                        <font color="white">Your Notes (You can edit and add more notes here)</font>
                     </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="padding-10-15 md-none sm-12 text-right" style="padding: 0px !important;">
                            <textarea name="cch_note" id="cch_note" cols="70" rows="3" ... ><?php echo $cch_notes; ?></textarea>
                            <br>
                            <script>
                              CKEDITOR.replace( 'cch_note', {
                                customConfig: '<?php echo base_url();?>assets/ckeditor/config.js'
                              });
                            </script>
                            <input type="hidden" id="appoint_id_cch" value="<?php echo $appointment_id ?>">
                            <input type="submit" id="save_note" class="pure-button btn-blue btn-small" value="Update">
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php } ?>
            <p><b>IMPORTANT NOTES:</b></p>
            <p style="color: #484848;">Download the recorded session in Session History.</p>
            <p style="color: #484848;">Recording will be ready in 2 minutes.</p>
            <p style="color: #484848;">Recording is available for 72 hours after end of session.</p>
            <p></p>
            <?php if($role == "STD"){?>
              <button class="pure-button btn-tertiary btn-expand-tiny height-30 exitbtn">Exit</button>
            <?php }else if($role == "CCH"){ ?>
              <button class="pure-button btn-tertiary btn-expand-tiny height-30 exitcch">Exit</button>
            <?php } ?>
    </div>
  </div>
</div>
<!-- <script type="text/javascript">
    window.onload = function() {
        window.setTimeout(setDisabled, 90000);
    }
    function setDisabled() {
        document.getElementById('downloadbutton').disabled = false;
    }
</script>
<script>
var upgradeTime = 89;
var seconds = upgradeTime;
function timer() {
    var days        = Math.floor(seconds/24/60/60);
    var hoursLeft   = Math.floor((seconds) - (days*86400));
    var hours       = Math.floor(hoursLeft/3600);
    var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
    var minutes     = Math.floor(minutesLeft/60);
    var remainingSeconds = seconds % 60;
    if (remainingSeconds < 11) {
        remainingSeconds = "0" + remainingSeconds; 
    }
    document.getElementById('countdown').innerHTML = minutes + ":" + remainingSeconds;
    if (seconds == 0) {
        clearInterval(countdownTimer);
        document.getElementById('countdown').innerHTML = "Ready";
    } else {
        seconds--;
    }
}
var countdownTimer = setInterval('timer()', 1000);
</script> -->