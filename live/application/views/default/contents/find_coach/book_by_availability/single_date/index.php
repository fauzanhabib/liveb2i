<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/raty/jquery.raty.css">
<script src="<?php echo base_url(); ?>assets/vendor/raty/jquery.raty.js"></script>

    <div class="box pure-g clear-both">
        <div class="sort-left pure-u-md-6-24 pure-u-lg-6-24">
            <div class="pure-g border-b-1-fa">
                <h3 class="text-center margin-auto font-semi-bold">Book a Coach <?php echo $cert_studying;?></h3>
            </div>

            <div class="content padding-lr-0">
                <!-- search icon on country dropdown -->
                <style>
                    .search-b-2:before {
                        content: " ";
                    }
                </style>
                <!-- end search icon on country dropdown -->

                <!-- rendy book a coach baru -->
                <div class="box">
                    <?php
                            echo form_open('student/find_coaches/book_by_single_date', 'id="date_value" role="form" class="pure-g pure-form"');        
                        ?>
                    <div class="width100perc" style="padding: 0 15px;">
                        <div class='border-2-primary border-rounded-5' style="padding: 0 6px;">
                            <span class='custom-dropdown'>
                                <select name="selector" id="selector">
                                    <option disabled selected>Booking Type</option>
                                    <option value="single-book">Single Book</option>
                                    <option value="multiple-book">Recurring Book</option>
                                </select>
                            </span>
                        </div>
                    </div>
                    <div class="width100perc" id="multi-book2" style="padding: 10px 15px 0;">
                        <div class='border-2-primary border-rounded-5' style="padding: 0 6px;">
                            <span class='custom-dropdown'>
                                <select name="type_booking">
                                    <option value="2" selected>2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                            </span>
                        </div>
                    </div>
                    <ul class="sort-by padding-l-0 width100perc">
                        <div class="text-right book-date" style="padding: 1px 15px;">
                            <div class="width100perc">
                                <div class="frm-date">
                                    <input type="text" name="date" value="" class="dateavailable datepicker frm-date width100perc border-2-primary border-rounded-5 text-left" id="date" data-parsley-no-focus="" required="" readonly="" data-parsley-id="8951" placeholder="Date" data-parsley-required-message="Please click for date." style="padding: 1.02em 0.5em;">
                                </div>
                            </div>
                        </div>
                        <div class="" style="padding: 5px 15px;">

                            <?php echo form_submit('__submit', @$this->auth_manager->userid() ? 'SEARCH' : 'SEARCH', 'class="pure-button btn-primary border-rounded-5 width100perc"'); ?>
                        </div>
                        <li class="text-center" style="padding: 5px 15px;">
                            <a href="<?php echo site_url('student/find_coaches/search/name'); ?>">
                                NAME
                            </a>
                        </li>
                        <li class="text-center" style="padding: 5px 15px;">
                            <a href="<?php echo site_url('student/find_coaches/search/country'); ?>">
                                COUNTRY
                            </a>
                        </li>
                        <li class="text-center" style="padding: 5px 15px;">
                            <a href="<?php echo site_url('student/find_coaches/search/spoken_language'); ?>">
                                LANGUAGE SPOKEN
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- end rendy book a coach baru -->
            </div>  
            <!-- <div class="content padding-t-0 padding-lr-0">
                <div class="box">
                    <ul class="sort-by padding-l-0">
                        <div class="text-right book-date" style="padding: 1px 15px;">
                            <div class="width100perc">
                                <div class="frm-date">
                                    <input type="text" name="date" value="" class="dateavailable datepicker frm-date width100perc border-2-primary border-rounded-5 text-left" id="date" data-parsley-no-focus="" required="" readonly="" data-parsley-id="8951" placeholder="Date" data-parsley-required-message="Please click for date." style="padding: 1.02em 0.5em;">
                                </div>
                            </div>
                        </div>
                        <div class="" style="padding: 5px 15px;">

                            <?php echo form_submit('__submit', @$this->auth_manager->userid() ? 'SEARCH' : 'SEARCH', 'class="pure-button btn-primary border-rounded-5 width100perc"'); ?>
                        </div>
                        <li><a href="<?php echo site_url('student/find_coaches/search/name'); ?>">NAME</a></li>
                        <li><a href="<?php echo site_url('student/find_coaches/search/country'); ?>">COUNTRY</a></li>
                        <li><a href="<?php echo site_url('student/find_coaches/search/spoken_language'); ?>">LANGUAGE SPOKEN</a></li>
                    </ul>
                </div>
            </div>   -->
        </div>
        <div class="pure-u-1 pure-u-sm-24-24 pure-u-md-18-24 pure-u-lg-18-24">
            <div class="sort-right">
                <div class="pure-g border-b-1-fa">
                    <h3 class="font-semi-bold padding-l-20">
                        Pick your coach 
                    </h3>
                </div>
                <div class="pure-g bg-white clearfix">
                     <?php
                        if(!@$data){
                            ?>
                            <div class="no-result">
                                No coaches available
                            </div>
                        <?php    
                        }

                    foreach($data as $d){
                        $partner_id = $this->auth_manager->partner_id($coaches[$i]->id);
                        $region_id = $this->auth_manager->region_id($partner_id);

                        // check status setting region
                        $setting_region = $this->db->select('status_set_setting')->from('specific_settings')->where('user_id',$region_id)->get()->result();
                        
                        // $setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('global_settings')->where('type','partner')->get()->result();
                        
                        // jika 0 / disallow
                        if($setting_region[0]->status_set_setting == 0){
                            $setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('global_settings')->where('type','partner')->get()->result();
                            $standard_coach_cost = @$setting[0]->standard_coach_cost;
                            $elite_coach_cost = @$setting[0]->elite_coach_cost;
                        } else {
                            $setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();
                            $standard_coach_cost = @$setting[0]->standard_coach_cost;
                            $elite_coach_cost = @$setting[0]->elite_coach_cost;
                        }
                        
                        // $setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('specific_settings')->where('partner_id',$partner_id)->get()->result();
                        // $region_setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('specific_settings')->where('user_id',$region_id)->get()->result();
                        // $global_setting = $this->db->select('standard_coach_cost,elite_coach_cost, session_duration')->from('global_settings')->where('type','partner')->get()->result();



                        // $standard_coach_cost = @$setting[0]->standard_coach_cost;
                        // if(!$standard_coach_cost || $standard_coach_cost == 0){
                        //     $standard_coach_cost_region = @$region_setting[0]->standard_coach_cost;
                        //     $standard_coach_cost = $standard_coach_cost_region;
                        //     if(!$standard_coach_cost_region || $standard_coach_cost_region == 0){
                        //         $standard_coach_cost_global = @$global_setting[0]->standard_coach_cost;
                        //         $standard_coach_cost = $standard_coach_cost_global;
                        //     }
                        // }

                        // $elite_coach_cost = @$setting[0]->elite_coach_cost;
                        // if(!$elite_coach_cost || $elite_coach_cost == 0){
                        //     $elite_coach_cost_region = @$region_setting[0]->elite_coach_cost;
                        //     $elite_coach_cost = $elite_coach_cost_region;
                        //     if(!$elite_coach_cost_region || $elite_coach_cost_region == 0){
                        //         $elite_coach_cost_global = @$global_setting[0]->elite_coach_cost;
                        //         $elite_coach_cost = $elite_coach_cost_global;
                        //     }
                        // }

                        $session_duration = @$setting[0]->session_duration;
                        if(!$session_duration || $session_duration == 0){
                            $session_duration_region = @$region_setting[0]->session_duration;
                            $session_duration = $session_duration_region;
                            if(!$session_duration_region || $session_duration_region == 0){
                                $session_duration_global = @$global_setting[0]->session_duration;
                                $session_duration = $session_duration_global;
                            }
                        }
                    ?>
                    <div class="grids list-people pure-u-1 pure-u-sm-24-24 pure-u-md-12-24 pure-u-lg-8-24 list">

                        <div class="box-of-info text-center padding-b-10">
                            <div class="thumb-medium padding-t-20">
                                <img src="<?php echo base_url().$d['profile_picture']; ?>" class="img-circle-medium-big">
                            </div>
                            <h5><a class="text-cl-tertiary font-18" href="<?php echo site_url('student/session/coach_detail/'.$d['coach_id']); ?>"><?php echo($d['fullname']); ?></a></h5>
                            <?php 
                                $id = $d['coach_id'];

                                $allrate = $this->db->select('rate')
                                                ->from('coach_ratings')
                                                ->where('coach_id', $id)
                                                ->get()->result();

                                $temp = array();
                                foreach($allrate as $in)
                                {
                                    $temp[] = $in->rate;
                                }

                                $sumrate   = array_sum($temp);
                                $countrate = count((array)$allrate);

                                if($sumrate != null && $countrate != null){
                                    $classrate = $sumrate / $countrate * 20;
                                    $tooltip   = $sumrate / $countrate;
                                }else{
                                    $classrate = 0;
                                    $tooltip   = 0;
                                }

                                // echo "<pre>";
                                // print_r($tooltip);
                                // exit();
                            ?>
                            <div data-tooltip="<?php echo number_format($classrate);?>% (<?php echo(round($tooltip,1));?> of 5 Stars)">
                                <div class="star-rating">
                                    <span style="width:<?php echo $classrate; ?>%"></span>
                                </div>
                            </div>
                            <h5>
                                <?php if($d['coach_type_id'] == 1){
                                    echo $standard_coach_cost;
                                } else if($d['coach_type_id'] == 2){
                                    echo $elite_coach_cost; 
                                }?>

                                Tokens
                            </h5>
                            <h5><?php echo($d['country']); ?></h5>
                            <div class="more pure-u-1">
                                <span class="click arrow font-12">View Schedule <i class="icon icon-arrow-down font-10"></i></span>
                            </div>

                        </div>
                        <!-- ======== -->
                        <div class="view-schedule hide">
                            <div class="box-schedule">
                                <form class="pure-form">
                                    <div class="list-schedule list-schedule-max">
                                        <table class="tbl-booking">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">START TIME</th>
                                                    <th class="text-center">END TIME</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tbody>
                                                    <?php
                                                    foreach ($d['availability'] as $av) {
                                    
                                                            $get_endtime = date('H:i:s',strtotime(@$av['end_time']));

                                                            // $date = date("Y-m-d H:i:s");
                                                            $time = strtotime($get_endtime);
                                                            $time = $time - (5 * 60);
                                                            $endtime = date("H:i", $time);

                                                            // xxxxxxxxxxxx
                                                            $CheckInX = explode("-", $date);
                                                            $CheckOutX =  explode("-", date('Y-m-d'));
                                                            $date1 =  mktime(0, 0, 0, $CheckInX[1],$CheckInX[2],$CheckInX[0]);
                                                            $date2 =  mktime(0, 0, 0, $CheckOutX[1],$CheckOutX[2],$CheckOutX[0]);
                                                            $interval =($date2 - $date1)/(3600*24);
                                                            
                                                            // xxxxxxxxxxxx
                                                            $new_gmt_val_user = $gmt_val_user*1;
                                                            @date_default_timezone_set('Etc/GMT'.$gmt_val_user*(-1));      
                                                            $adate = $date;
                                                            $bdate = date('Y-m-d');
                                                            // echo $adate." + ".$bdate;
                                                            // exit();
                                                            $res = (int)$gmt_val_user;
                                                            if($adate < $bdate){

                                                                    @date_default_timezone_set('Etc/GMT+0');
                                                                    $dt = date('H:i:s');
                                                                    $default_dt  = strtotime($dt);
                                                                    $usertime = $default_dt+(60*$gmt_user);
                                                                    $hour = date("H:i:s", $usertime);

                                                                    $selesai=date('H:i:s',strtotime(@$av['start_time']));
                                                                    $mulai=$hour;
                                                                    list($jam,$menit,$detik)=explode(':',$mulai);
                                                                    $buatWaktuMulai=mktime($jam,$menit,$detik,1,1,1);

                                                                    list($jam,$menit,$detik)=explode(':',$selesai);
                                                                    $buatWaktuSelesai=mktime($jam,$menit,$detik,1,1,1);
                                                                    $selisihDetik=$buatWaktuSelesai-$buatWaktuMulai;
                                                                    
                                                                            if($selisihDetik > 3599){

                                                                                ?>
                                                                                <tr>
                                                                                    <td class="text-center"><?php echo(date('H:i',strtotime(@$av['start_time']))); ?></td>
                                                                                    <td class="text-center"><?php echo $endtime; ?></td>
                                                                                    <td><a href="<?php echo site_url('student/find_coaches/summary_book/single_date/' . $d['coach_id'] . '/' . strtotime(@$adate) . '/' . $av['start_time'] . '/' . $av['end_time']); ?>" class="pure-button btn-small btn-white" style="margin:0">Book</a></td>
                                                                                </tr>
                                                                                <?php
                                                                                    
                                                                                    
                                                                                }
                                                            }  else if (($adate == $bdate) && ($res > 0 )){  
                                                                @date_default_timezone_set('Etc/GMT+0');
                                                                    $dt = date('H:i:s');
                                                                    $default_dt  = strtotime($dt);
                                                                    $usertime = $default_dt+(60*$gmt_user);
                                                                    $hour = date("H:i:s", $usertime);

                                                                    $selesai=date('H:i:s',strtotime(@$av['start_time']));
                                                                    $mulai=$hour;
                                                                    list($jam,$menit,$detik)=explode(':',$mulai);
                                                                    $buatWaktuMulai=mktime($jam,$menit,$detik,1,1,1);

                                                                    list($jam,$menit,$detik)=explode(':',$selesai);
                                                                    $buatWaktuSelesai=mktime($jam,$menit,$detik,1,1,1);
                                                                    $selisihDetik=$buatWaktuSelesai-$buatWaktuMulai;
                                                                    
                                                                            if($selisihDetik > 3599){

                                                                                ?>
                                                                                <tr>
                                                                                    <td class="text-center"><?php echo(date('H:i',strtotime(@$av['start_time']))); ?></td>
                                                                                    <td class="text-center"><?php echo $endtime; ?></td>
                                                                                    <td><a href="<?php echo site_url('student/find_coaches/summary_book/single_date/' . $d['coach_id'] . '/' . strtotime(@$adate) . '/' . $av['start_time'] . '/' . $av['end_time']); ?>" class="pure-button btn-small btn-white" style="margin:0">Book</a></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                            }  else if (($adate == $bdate) && ($res < 0 )){  
                                                                @date_default_timezone_set('Etc/GMT+0');
                                                                    $dt = date('H:i:s');
                                                                    $default_dt  = strtotime($dt);
                                                                    $usertime = $default_dt+(60*$gmt_user);
                                                                    $hour = date("H:i:s", $usertime);

                                                                    $selesai=date('H:i:s',strtotime(@$av['start_time']));
                                                                    $mulai=$hour;
                                                                    list($jam,$menit,$detik)=explode(':',$mulai);
                                                                    $buatWaktuMulai=mktime($jam,$menit,$detik,1,1,1);

                                                                    list($jam,$menit,$detik)=explode(':',$selesai);
                                                                    $buatWaktuSelesai=mktime($jam,$menit,$detik,1,1,1);
                                                                    $selisihDetik=$buatWaktuSelesai-$buatWaktuMulai;
                                                                    
                                                                            if($selisihDetik > 3599){

                                                                                ?>
                                                                                <tr>
                                                                                    <td class="text-center"><?php echo(date('H:i',strtotime(@$av['start_time']))); ?></td>
                                                                                    <td class="text-center"><?php echo $endtime; ?></td>
                                                                                    <td><a href="<?php echo site_url('student/find_coaches/summary_book/single_date/' . $d['coach_id'] . '/' . strtotime(@$adate) . '/' . $av['start_time'] . '/' . $av['end_time']); ?>" class="pure-button btn-small btn-white" style="margin:0">Book</a></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                            }  else {  

                                                                        @date_default_timezone_set('Etc/GMT+0');
                                                                         
                                                                        ?>
                                                                    <tr>
                                                                        <td class="text-center"><?php echo(date('H:i',strtotime(@$av['start_time'])));?></td>
                                                                        <td class="text-center"><?php echo $endtime; ?></td>
                                                                        <td><a href="<?php echo site_url('student/find_coaches/summary_book/single_date/' . $d['coach_id'] . '/' . strtotime(@$adate) . '/' . $av['start_time'] . '/' . $av['end_time']); ?>" class="pure-button btn-small btn-white" style="margin:0">Book</a></td>
                                                                    </tr>
                                                        <?php }
                                                    }
                                                    ?>
                                                </tbody>
                       
                                        </table>
                                    </div>          
                                </form>
                            </div>
                        </div>
                        <!-- ======== -->
                    </div>
                    <?php } ?>
                    <div class="height-200"></div>
                    <?php echo @$pagination?>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
        $(function(){
                $('#multi-book2').hide(); 
                $('#selector').change(function(){
                    if($('#selector').val() == 'multiple-book') {
                        $('#multi-book2').show(); 
                    } else {
                        $('#multi-book2').hide(); 
                    } 
                });
            });
</script>

<script type="text/javascript">
    $(function(){

        $(document).ready(function(){



            $('.list').each(function() {
                var $dropdown = $(this);

                $(".click", $dropdown).click(function(e) {
                    e.preventDefault();

                    $schedule = $(".view-schedule", $dropdown);
                    $span = $("span", $dropdown);
                    $icon = $("i", $dropdown);

                    if($($schedule).hasClass("show")) {
                        $($schedule).addClass('hide');
                        $($schedule).removeClass('show');
                        $($span).removeClass('active-schedule');
                        $($icon).addClass('icon-arrow-down');
                        $($icon).removeClass('icon-arrow-up');
                    }
                    else {
                        $($schedule).addClass('show');
                        $($schedule).removeClass('hide');
                        $($span).addClass('active-schedule');
                        $($icon).addClass('icon-arrow-up');
                        $($icon).removeClass('icon-arrow-down');
                    }

                    $(".view-schedule").not($schedule).addClass('hide');
                    $(".view-schedule").not($schedule).removeClass('show');
                    $("span").not($span).removeClass('active-schedule');
                    $("i").not($icon).removeClass('icon-arrow-up').addClass('icon-arrow-down');

                    return false;
                });
            });

            var now = new Date();
            var day = ("0" + (now.getDate() + 1)).slice(-2);
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);

            $('.datepicker').datepicker({
                startDate: resultDate,
                format: 'yyyy-mm-dd',
                autoclose: true,
            });

            $('.rating').raty({
                readOnly   : true,
                starHalf: 'icon icon-star-half',
                starOff: 'icon icon-star-full color-grey',
                starOn: 'icon icon-star-full',
                starType: 'i',
                score: function() {
                return $(this).attr('data-score');
                }
            });
             
        });

        document.getElementById("date").onchange = function () {
            var newurl = "<?php echo site_url('student/find_coaches/book_by_single_date'); ?>" + "/" + this.value;
            $('#date_value').attr('action', newurl);
        };

    })
</script>