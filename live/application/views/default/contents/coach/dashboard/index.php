<style type="text/css">
    .refbtn{

    }
    .refbtn:hover{
        cursor: pointer;
        color: #144d80;
    }
</style>
<div class="box">
        <div class="content bg-white-fff">
            <div class="box-lists clearfix pure-g">
                <div class="box-list-icon2 grids pure-u-sm-1-2 pure-u-md-6-12 pure-u-lg-4-24">
                    <div class="box-icon">
                        <a href="<?php echo site_url('coach/upcoming_session');?>">
                           <img src="<?php echo base_url();?>assets/img/MenuMain-Coaching-Sessions.svg">
                        </a>
                    </div>
                    <a href="<?php echo site_url('coach/upcoming_session');?>" class="text-cl-grey"><span>Upcoming Sessions</span></a>
                </div>
                <div class="box-list-icon2 grids pure-u-sm-1-2 pure-u-md-6-12 pure-u-lg-4-24">
                    <div class="box-icon">
                        <a href="<?php echo site_url('coach/token');?>">
                           <img src="<?php echo base_url();?>assets/img/MenuMain-Token-Finance.svg">
                        </a>
                    </div>
                    <a href="<?php echo site_url('coach/token');?>" class="text-cl-grey"><span>Tokens / Credit</span></a>
                </div>
                <div class="box-list-icon2 grids pure-u-sm-1-2 pure-u-md-6-12 pure-u-lg-4-24">
                    <div class="box-icon">
                        <a href="<?php echo site_url('coach/schedule');?>">
                           <img src="<?php echo base_url();?>assets/img/MenuMain-Scheduling.svg">
                        </a>
                    </div>
                    <a href="<?php echo site_url('coach/schedule');?>" class="text-cl-grey"><span>Scheduling</span></a>
                </div>
                <div class="box-list-icon2 grids pure-u-sm-1-2 pure-u-md-6-12 pure-u-lg-4-24">
                    <div class="box-icon">
                        <a href="<?php echo site_url('coach/coach_material');?>">
                           <img src="<?php echo base_url();?>assets/img/MenuMain-Teacher-Materials.svg">
                        </a>
                    </div>
                    <a href="<?php echo site_url('coach/coach_material');?>" class="text-cl-grey"><span>Coach Materials</span></a>
                </div>
                <div class="box-list-icon2 grids pure-u-sm-24-24 pure-u-md-6-12 pure-u-lg-4-24">
                    <div class="box-icon">
                        <a href="<?php echo site_url('coach/help');?>">
                           <img src="<?php echo base_url();?>assets/img/MenuMain-Help.svg">
                        </a>
                    </div>
                    <a href="<?php echo site_url('coach/help');?>" class="text-cl-grey"><span>Help</span></a>
                </div>
            </div>
        </div>

        <div class="content-bottom padding-l-65 m-t-25 bg-white pure-g">
            <?php
            // $compare = $nowd.' '.$nowh;
            // print_r(strtotime($countdown));
            // print_r(strtotime($compare));
            // exit();

            //&& strtotime($countdown) <= strtotime($nowc) && $nowh <= $hourend && $nowh >= $hourstart

            if($wm != NULL && strtotime($countdown) <= strtotime($nowc) && $nowh <= $hourend && $nowh >= $hourstart){

            ?>
                <?php if(@$statuscheck == 0){ ?>
                <div class="pure-u-lg-10-24">
                    <span class="session-title-left float-none">Live Session</span>
                    <div class="start-session m-t-10">
                        <div class="icon-left">
                            <form name ="livesession" action="<?php echo(site_url('opentok/live/'));?>" method="post">
                                <input type="hidden" name="appoint_id" value="<?php echo $wm_id ?>">
                                <input class="refbtn" type="image" src="<?php echo base_url();?>assets/img/Play-After.svg" alt="Submit" width="100" height="100" style="margin-top: 14px;margin-left: 15px;">
                            </form>
                        </div>
                        <div class="info-right">
                            <div id="clockdiv">
                                <div class="info-right text-cl-tertiary font-22">You Have a Live Session</div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } else if(@$statuscheck == 1){?>
                <div class="pure-u-lg-10-24">
                    <span class="session-title-left float-none">Live Session</span>
                    <div class="start-session m-t-10">
                        <div class="info-right">
                            <div id="clockdiv">
                                <div class="info-right text-cl-tertiary font-22">
                                    You Have Opened Live Session<br>
                                    <font id="clearlive" class="refbtn" style="font-size: 16px;">Not Yet Open? Click Here</font>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

            <?php } else if($wm == NULL){?>
                <div class="pure-u-lg-10-24">
                    <span class="session-title-left float-none">No Session</span>
                    <div class="start-session m-t-10">
                        <div class="icon-left">
                            <img src="<?php echo base_url();?>assets/img/Play-Before.svg">
                        </div>
                       <div class="info-right font-22">
                            You Have No Sessions Today
                        </div>
                    </div>
                </div>
            <?php } else{?>
                <div class="pure-u-lg-10-24">
                <span class="session-title-left float-none">Next Session</span>

                <div class="start-session m-t-20" id="nosess">
                    <div class="icon-left">
                        <img src="<?php echo base_url();?>assets/img/Play-Before.svg">
                    </div>
                    <div class="info-right" id="clockarea">
                        <div id="clockdiv" class="">
                            <div><span class="hours"></span><div class="smalltext">Hours</div></div>
                            <div class="border-lr-1-grey padding-lr-7"><span class="minutes"></span><div class="smalltext">Mins</div></div>
                            <div><span class="seconds"></span><div class="smalltext">Secs</div></div>
                            <h5 class="padding0">Until Next Session</h5>
                        </div>
                    </div>
                </div>
                <div class="start-session m-t-20 hide" id="sess">
                    <div class="icon-left">
                        <form name ="livesession" action="<?php echo(site_url('opentok/live/'));?>" method="post">
                            <input type="hidden" name="appoint_id2" id="get_id_ajax" value="">
                            <input class="refbtn" type="image" src="<?php echo base_url();?>assets/img/Play-After.svg" alt="Submit" width="100" height="100" style="margin-top: 14px;margin-left: 15px;">
                        </form>
                    </div>
                    <div class="info-right">
                        <div class="info-right font-22 text-cl-tertiary">
                            <!-- <a class="text-cl-tertiary"> -->
                                Please join your session now
                            <!-- </a> -->
                        </div>
                    </div>
                </div>

            </div>
            <?php } ?>

            <div class="pure-u-lg-13-24">
                <div class="view-session">
                    <div class="session-title padding-l-0 margin0 clearfix">
                        <div class="session-title-left">Today's Sessions</div>
                        <div class=""><a href="<?php echo site_url('coach/upcoming_session');?>" class="session-title-right text-cl-grey font-semi-bold">View Upcoming</a></div>
                    </div>
                    <?php
                        foreach ($data as $d) {
                    ?>
                    <ul class="view-session-details">
                        <li class="box-green session-details-time clearfix">
                            <?php echo(date('H:i',strtotime($d->start_time)));?> <?php echo "(UTC ".$gmt_val.")"?>
                        </li>
                        <li class="session-details-date"><?php echo date('D, j F  Y', strtotime($d->date)); ?></li>
                        <li class="session-details-view hover-this prelative padding-r-5" id="viewstudent" idstudent="<?php echo $d->student_id;?>"><a href="#modal" class="text-cl-tertiary font-semi-bold">Student Info</a>
                           <!--  <div class="session-info-hover bg-white-fff">
                                <div class="_7lh" style="transform: translate(21px, -9px) rotate(-45deg);"></div>
                                <img src="" class="padding-t-15">
                                <h5>Ariana Ditya</h5>
                                <div class="font-30 padding-t-10">
                                    <a href="#modal" class="icon dyned-icon-add text-cl-tertiary"></a>
                                    <a href="" class="icon dyned-icon-delete text-cl-grey"></a>
                                </div>
                            </div> -->
                        </li>
                    </ul>

                    <?php } ?>

                    <!-- modal-->

                        <div class="sess-details-modal remodal max-width900" data-remodal-id="modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
                            <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
                            <div class="pure-g">
                                <div class="thumb-large height-170 padding15" style="border: none">
                                    <img src="" width="200" height="200" class="pure-img img-circle-big fit-cover profile_picturecoach" />
                                </div>
                                <div class="profile-detail text-left prelative width75perc">
                                    <h4 class="border-b-1 font-semi-bold text-cl-grey">Contact Information</h4>
                                    <table class="table-no-border2">
                                        <tbody>
                                            <tr>
                                                <td class="padding4 width30perc font-light">Name</td>
                                                <td class="padding4 border-none">
                                                    <span class="namecoach"> </span>
                                                </td>
                                            </tr>
                                            <!-- <tr>
                                                <td class="padding4 width30perc font-light">Email Address</td>
                                                <td class="padding4 border-none">
                                                    <span class="emailcoach"></span>
                                                </td>
                                            </tr> -->
                                            <tr>
                                                <td class="padding4 width30perc font-light">Birthdate</td>
                                                <td class="padding4 border-none">
                                                    <span class="birthdatecoach"></span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="padding4 width30perc font-light">Spoken Language</td>
                                                <td class="padding4 border-none">
                                                    <span class="spoken_languagecoach"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="padding4 width30perc font-light">Gender</td>
                                                <td class="padding4 border-none">
                                                    <span class="gendercoach"></span>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="padding4 width30perc font-light">Timezone</td>
                                                <td class="padding4 border-none">
                                                    <span class="timezonecoach"></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    <!-- modal -->
                </div>
            </div>

        </div>


</div>



<script type="text/javascript" src="<?php echo base_url();?>assets/js/remodal.min.js"></script>

<script>
    var userid = "<?php echo $userid; ?>";
    $("#clearlive").click(function() {
        console.log('a');
        $.post("<?php echo site_url('coach/dashboard/clear_live');?>", { 'id': userid },function(data) {
            window.location.href = "<?php echo site_url('coach/dashboard'); ?>";
        });
    });
</script>

<script type="text/javascript">
  $("ul li#viewstudent").click(function() {

      var student_id = $(this).attr('idstudent');

      $.ajax({
          url: "<?php echo site_url('coach/dashboard/student_detail');?>",
              type: 'POST',
              dataType: 'json',
              data: {student_id : student_id},
              success: function(data) {
                  // console.log(data);
                  var name = data[0].name;
                  // var email = data[0].email;
                  var birthdate = data[0].birthdate;
                  var spoken_language = data[0].spoken_language;
                  var gender = data[0].gender;
                  var timezone = data[0].timezone;
                  var profile_picture = data[0].profile_picture;

                  $('.namecoach').text(': '+name);
                  // $('.emailcoach').text(': '+email);
                  $('.birthdatecoach').text(': '+birthdate);
                  $('.gendercoach').text(': '+gender);
                  $('.timezonecoach').text(': '+timezone);
                  $('.profile_picturecoach').attr('src','<?php echo base_url();?>'+profile_picture);

              }
      });
  });

</script>

<script>
    $(function(){
        var inst = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];
    });
</script>


<script type="text/javascript">
    $(document).ready(function () {
        $(".hover-this").hover(
      function () {
         $('.session-info-hover').fadeIn('medium');
      },
      function () {
         $('.session-info-hover').fadeOut('medium');
      }
    );

    });
</script>
<script type="text/javascript">
    // var deadline = '2016-08-25 18:20:00';

    var end = '<?php echo $hourend; ?>';
    var deadline = '<?php echo $countdown; ?>';
    function time_remaining(endtime){
        // var t = Date.parse(endtime) - Date.parse(new Date());
        // var t = Date.parse(endtime) - Date.parse("<?php echo $nowc ?>");
        var first = endtime.replace("-","/");
        var datetime = first.replace("-","/");
        var year = new Date(datetime).getFullYear();
        var day = new Date(datetime).getDate();
        if(day<=9 && day>0) {
            day = "0" + day;
        }else{
            day = day;
        }
        var month = new Date(datetime).getMonth()+1;
        var hours = new Date(datetime).getHours();
        if(hours<=9 && hours>=0) {
            hours = "0" + hours;
        }else{
            hours = hours;
        }
        var minutes = new Date(datetime).getMinutes();
        if(minutes<=9 && minutes>=0) {
            minutes = "0" + minutes;
        }else{
            minutes = minutes;
        }
        var seconds = new Date(datetime).getSeconds();
        if(seconds<=9 && seconds>=0) {
            seconds = "0" + seconds;
        }else{
            seconds = seconds;
        }
        var realendtime = month + "/" + day + "/" + year + " " + hours + ":" + minutes + ":" + seconds;
        var t = Date.parse(realendtime) - Date.parse(new Date());
        var seconds = Math.floor( (t/1000) % 60 );
        var minutes = Math.floor( (t/1000/60) % 60 );
        var hours = Math.floor( (t/(1000*60*60)) % 24 );
        return {'total':t, 'hours':hours, 'minutes':minutes, 'seconds':seconds};
    }
    function run_clock(id,endtime){
        var clock = document.getElementById(id);

        // get spans where our clock numbers are held
        var hours_span = clock.querySelector('.hours');
        var minutes_span = clock.querySelector('.minutes');
        var seconds_span = clock.querySelector('.seconds');

        function update_clock(){
            var trigdate = new Date;

            var trig_s = trigdate.getSeconds();
            var sn = trig_s.toString().length;
            if (sn == 1){
                trig_s = '0'+trig_s;
            }

            var trig_m = trigdate.getMinutes();
            var mn = trig_m.toString().length;
            if (mn == 1){
                trig_m = '0'+trig_m;
            }

            var trig_h = trigdate.getHours();
            var hn = trig_h.toString().length;
            if (hn == 1){
                trig_h = '0'+trig_h;
            }

            var now = trig_h+':'+trig_m+':'+trig_s;

            var t = time_remaining(endtime);

            // update the numbers in each part of the clock
            hours_span.innerHTML = ('0' + t.hours).slice(-2);
            minutes_span.innerHTML = ('0' + t.minutes).slice(-2);
            seconds_span.innerHTML = ('0' + t.seconds).slice(-2);

            // console.log(end);
            if(t.total<=0){
                if (now < end){
                    clearInterval(timeinterval);
                    $("#clockdiv").hide();
                    $("#nosess").hide();
                    $("#sess").removeClass("hide");
                    $.get("<?php echo site_url('coach/dashboard/get_id');?>",function(data) {
                        var val_id = data;
                        console.log(val_id);
                        document.getElementById('get_id_ajax').value = val_id;
                        // $("#clockdiv").show();
                        // $("#clockarea").show();
                        $("#sess").show();
                        // alert(document.getElementById("get_id_ajax").value);
                    });
                }
                else if(now > end){
                    $("#clockdiv").show();
                    $("#clockarea").show();
                    $("#sess").hide();
                }
            }
        }
        update_clock();
        var timeinterval = setInterval(update_clock,1000);
    }
    run_clock('clockdiv',deadline);
</script>

<script>
    var isFirefox = typeof InstallTrigger !== 'undefined';

    if (isFirefox) {
        $('.box-lists').css('text-align', 'center');
        $('.box-list-icon2').css('padding', '0 10px')
    }
</script>
