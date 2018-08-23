<div class="heading text-cl-primary padding15">
    <?php if($recuring > 1){ ?>
        <h1 class="margin0"><small>Multiple Booking Summary</small></h1>
    <?php }else{ ?>
        <h1 class="margin0"><small>Booking Summary</small></h1>
    <?php } ?>
</div>

<div class="box">
    <div class="heading pure-g" style="border-top: 2px solid #f3f3f3;">
        <div class="pure-u-1">
            <h3 class="h3 font-normal padding15 text-cl-secondary">BOOKING 1 </h3>
        </div>
    </div>

    <div class="content">
        <div class="box pure-g">
            <table class="table-no-border2" style="border-collapse: separate;border-spacing: 0px 10px;padding:10px">
                <tr>
                    <td>Coach Name</td>
                    <td><?php echo($data_coach[0]->fullname); ?></td>
                </tr>
                <!-- <tr>
                    <td>Email</td>
                    <td><?php echo($data_coach[0]->email); ?></td>
                </tr> -->
                <tr>
                    <td>Date</td>
                    <?php if($recuring > 1){
                        $temp = $date; ?>
                        <td><?php foreach($frequency as $f){
                            $temp = strtotime("+".$f." day", $temp);
                            echo(date('l jS \of F Y', @$temp)).'<br> '; }?>
                        </td>
                    <?php }else{ ?>
                        <td>
                            <?php echo(date('l jS \of F Y', @$date)); ?>
                        </td>
                    <?php } ?>
                </tr>
                <tr>
                    <td>Start Time</td>
                    <td><?php echo($start_time); ?></td>
                </tr>
                <tr>
                    <td>End Time</td>
                    <td><?php
                    $currentDate = strtotime($end_time);
                    $futureDate = $currentDate-(60*5);
                    $endtime = date("H:i:s", $futureDate);

                    echo($endtime); ?></td>
                </tr>
<!--                <tr>
                    <td>Call Method</td>
                    <td>Skype / Webex</td>
                </tr>-->
                <tr>
                    <td>Token Cost</td>
                    <td>
                        <?php
                        $partner_id = $this->auth_manager->partner_id($data_coach[0]->id);
                        $region_id = $this->auth_manager->region_id($partner_id);

                        // check status setting region
                        $setting_region = $this->db->select('status_set_setting')->from('specific_settings')->where('user_id',$region_id)->get()->result();

                        // $setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('global_settings')->where('type','partner')->get()->result();

                        // jika 0 / disallow
                        // if($setting_region[0]->status_set_setting == 0){
                        //     $setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('global_settings')->where('type','partner')->get()->result();
                        //     $standard_coach_cost = @$setting[0]->standard_coach_cost;
                        //     $elite_coach_cost = @$setting[0]->elite_coach_cost;
                        // } else {
                        //     $setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();
                        //     $standard_coach_cost = @$setting[0]->standard_coach_cost;
                        //     $elite_coach_cost = @$setting[0]->elite_coach_cost;
                        // }

                                $token = '';
                                if($data_coach[0]->coach_type_id == 1){
                                    $token = $standard_coach_cost;
                                    $show = $token * $recuring;
                                } else if($data_coach[0]->coach_type_id == 2){
                                    $token = $elite_coach_cost;
                                    $show = $token * $recuring;
                                } ?>
                        <input type="text" name="token" value="<?php echo $show;?>" readonly>
                    </td>
                </tr>
                <tr>
                    <td>Device / Browser</td>
                    <td id="textBrowser">
                      <?php
                        echo $user_device;
                        if ($user_device == "Mobile"){
                          echo " (".@$user_d_type.")";
                        }
                      ?>
                    </td>
                    <input type="hidden" id="d_os" value="<?php echo $user_d_type; ?>"/>
                    <input type="hidden" id="d_type" value="<?php echo $user_device; ?>"/>
                    <input type="hidden" id="d_browser" value=""/>
                </tr>
                <tr id="ch_browser" style="display:none;">
                    <td>Choose Browser:</td>
                    <td><select class="choose_browser" id="sel_browser">
                      <option value="1">Choose Your Browser</option>
                      <option value="Chrome">Chrome</option>
                      <option value="Firefox">Firefox</option>
                      <option value="Safari">Safari</option>
                    </select></td>
                </tr>
                <tr>
                    <td style="display: table-cell;  width: auto !important;">
                        <a id="submit_summary" class="pure-button btn-small btn-secondary confirm-booking" style="cursor: pointer;">
                        CONFIRM</a>
                    </td>
                    <td style="border-bottom:0;display: table-cell;  width: auto !important; margin:0 5px;">
                        <button type="submit" id="cancel_summary" class="pure-button btn-red btn-small" style="cursor: pointer;margin:0">BACK</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById("submit_summary").onclick = function () {
       location.href = "<?php echo $search_by == 'single_date' ? site_url('student/find_coaches/book_single_coach/' . $data_coach[0]->id . '/' . $date . '/' . $start_time . '/' . $end_time) : site_url('student/find_coaches/booking/' . $data_coach[0]->id . '/' . $date . '/' . $start_time . '/' . $end_time); ?>";
    };

    document.getElementById("cancel_summary").onclick = function () {
        location.href = "<?php echo $search_by == 'single_date' ? site_url('student/find_coaches/' . $search_by) : site_url('student/find_coaches/search/' . $search_by); ?>";
    };
</script>

<script>

    var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;

    // Firefox 1.0+
    var isFirefox = typeof InstallTrigger !== 'undefined';

    // Safari 3.0+ "[object HTMLElementConstructor]"
    var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || (typeof safari !== 'undefined' && safari.pushNotification));

    // Internet Explorer 6-11
    var isIE = /*@cc_on!@*/false || !!document.documentMode;

    // Edge 20+
    var isEdge = !isIE && !!window.StyleMedia;

    // Chrome 1+
    var isChrome = !!window.chrome && !!window.chrome.webstore;

    // Blink engine detection
    var isBlink = (isChrome || isOpera) && !!window.CSS;
    // detect_browser
    if(isOpera === true){
      detect_browser = 'Opera';
      // console.log(detect_browser)
    }
    if(isIE === true){
      detect_browser = 'Internet Explorer';
      // console.log(detect_browser)
    }
    if(isSafari === true){
      detect_browser = 'Safari';
      // console.log(detect_browser)
    }
    if(isChrome === true){
      detect_browser = 'Chrome';
      // console.log(detect_browser)
    }
    if(isFirefox === true){
      detect_browser = 'Firefox';
      // console.log(detect_browser)
    }

    if(detect_browser == null){
      detect_browser = '';
      // console.log(detect_browser)
    }

    // detect_browser = '';
    // console.log(navigator.sayswho);
    console.log(detect_browser);
    if(!detect_browser){
      // console.log('detect_browser');
      $('#ch_browser').show();

      $('#sel_browser').change(function(){
        detect_browser = $(this).val();
        textContent = '<?php
            echo $user_device;
            if ($user_device == "Mobile"){
              echo " (".@$user_d_type.")";
            }
          ?>';

        // $('#textBrowser').html(new_content);
        $("#d_browser").val(detect_browser);

        if(detect_browser == '1'){
          detect_browser = '';
        }

        new_content = textContent+' / '+detect_browser

        document.getElementById("textBrowser").innerHTML = new_content;
      })
    }else{
      document.getElementById("textBrowser").innerHTML += ' / '+detect_browser;
      $("#d_browser").val(detect_browser);
    }


    $(document).on('click touchstart', 'a#submit_summary', function () {
        browser_type = $("#d_browser").val();
        device_type  = $("#d_type").val();
        device_os    = $("#d_os").val();

        if(!device_os){
          device_os = "none"
        }
        if(!device_type){
          device_type = "none"
        }
        if(!browser_type){
          browser_type = "none"
        }

        if(browser_type == '1' || browser_type == 'none'){
          alert("Please choose your browser");
          return false;
        }

        href = "<?php echo site_url('student/find_coaches/book_single_coach/' . $data_coach[0]->id . '/' . $date . '/' . $start_time . '/' . $end_time.'/' . $token) ?>";
        // return true;

        href += '/'+browser_type+'/'+device_type+'/'+device_os;

        // console.log(href);

        location.href = href;
    });
    $(document).on('touchstart click', '#cancel_summary', function () {
        location.href = "<?php echo $search_by == 'single_date' ? site_url('student/find_coaches/book_by_single_date/'.date('Y-m-d', @$date)) : site_url('student/find_coaches/search/' . $search_by); ?>";
    });

    // document.getElementById("textBrowser").innerHTML += ' / '+detect_browser;
    // $("#d_browser").val(detect_browser);
</script>
