<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/raty/jquery.raty.css">
<script src="<?php echo base_url(); ?>assets/vendor/raty/jquery.raty.js"></script>

    <div class="box pure-g clear-both">
        
        <!-- ====== -->
        <div class="pure-u-1 pure-u-sm-24-24 pure-u-md-18-24 pure-u-lg-18-24">
            <div class="sort-right">
                <div class="content-title padding-t-25">
                    <?php
                    $arr_mess = @$this->session->flashdata('booking_message');
                    if($arr_mess){
                        foreach ($arr_mess as $key_mess) {
                            echo "- ".$key_mess."<br />";
                        }
                    }
                    ?>
                </div>
                
                <div class="pure-g border-b-1-fa">
                    <h3 class="font-semi-bold padding-l-20">Meet your coaches</h3>
                </div>
                <div class="pure-g bg-white clearfix">
                     <?php
                        if(!@$coaches){
                            ?>
                            <div class="no-result">
                                No coaches available
                            </div>
                        <?php    
                        }

                    for($i=0;$i<count($coaches);$i++){
                        if($coaches[$i]->id != $old_coach_id){

                    ?>
                    <div class="grids list-people pure-u-1 pure-u-sm-24-24 pure-u-md-12-24 pure-u-lg-8-24 list">

                        <div class="box-of-info text-center padding-b-10">
                            <div class="thumb-medium padding-t-20">
                                <img src="<?php echo base_url().$coaches[$i]->profile_picture;?>" class="img-circle-medium-big">
                            </div>
                            <h5><a class="text-cl-tertiary font-18" href="<?php echo site_url('student/session/coach_detail/'.$coaches[$i]->id); ?>"><?php echo($coaches[$i]->fullname); ?></a></h5>
                            <?php 
                                $id = $coaches[$i]->id;

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

    
                            ?>
                            <div data-tooltip="<?php echo number_format($classrate);?>% (<?php echo(round($tooltip,1));?> of 5 Stars)">
                                <div class="star-rating">
                                    <span style="width:<?php echo $classrate; ?>%"></span>
                                </div>
                            </div>

                            <h5>
                                <?php 
                                if($coaches[$i]->coach_type_id == 1){
                                    echo $standard_coach_cost;
                                } else if($coaches[$i]->coach_type_id == 2){
                                    echo $elite_coach_cost; 
                                }
                                ?>

                                Tokens
                            </h5>
                            <h5><?php echo($coaches[$i]->country); ?></h5>
                            <div class="more pure-u-1">
                                <span class="click arrow font-12">View Schedule <i class="icon icon-arrow-down font-10"></i></span>
                            </div>

                        </div>
                        <!-- ======== -->
                        <div class="view-schedule hide">
                            <div class="box-schedule">
                                <div class="date pure-form pure-g" value="<?php echo($coaches[$i]->id);?>">
                                    <div class="pure-u-2-5">
                                        <div class="frm-date" style="display:inline-block">
                                            <input class="date_available datepicker frm-date" type="text" name="<?php echo($coaches[$i]->id);?>" readonly style="width:125px;">
                                            <span class="icon icon-date" style="left: 85px;"></span>
                                        </div>
                                    </div>
                                    <div class="pure-u-3-5 text-right">
                                        <button class="weekly_schedule pure-button btn-green" value="<?php echo($coaches[$i]->id);?>">WEEKLY</button>
                                    </div>
                                </div>
                                
                                <form class="pure-form">
                                    <div class="list-schedule">
                                        <p class="txt text-cl-primary">Click on the green button to see if your coach is available</p>
                                        <div id="result_<?php echo($coaches[$i]->id);?>">
                                            <img src='<?php echo base_url(); ?>images/small-loading.gif' alt='loading...' style="display:none;" id="schedule-loading"/>
                                        </div>
                                    </div>          
                                </form>
                            </div>
                        </div>
                        <!-- ======== -->
                    </div>
                    <?php } }?>
                    <div class="height-200"></div>
                    <?php echo @$pagination?>
                </div>
            </div>
        </div>
        <!-- ====== -->
    </div>

</div>


<script type="text/javascript">
    $(function () {
        $(document).ready(function () {
            //alert(new Date());
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


        });

        // ajax
        // don't cache ajax or content won't be fresh
        $.ajaxSetup({
            cache: false
        });


        $(".date_available").on('change', function() {
            //alert(this.name);
            var loadUrl = "<?php echo site_url('student/manage_appointments/availability/name'); ?>"+ "/" +this.name+ "/" +$(this).val();
            var m = $('[id^=result_]').html($('[id^=result_]').val());
            //alert(loadUrl);
            if($(this).val() != ''){
                $("#schedule-loading").show();
                $(".txt").hide();
                $("#result_"+this.name).load(loadUrl, function () {
                    for(i=0; i<m.length; i++){
                        $('#'+m[i].id).html($('#'+m[i].id).html().replace('/*',' '));
                        $('#'+m[i].id).html($('#'+m[i].id).html().replace('*/',' '));
                    }   
                    $("#schedule-loading").hide();
                });
            }
            
        });
        
        $(".weekly_schedule").click(function () {
            //alert(this.name);
            var loadUrl = "<?php echo site_url('student/manage_appointments/schedule_detail'); ?>"+ "/" +this.value;
            var m = $('[id^=result_]').html($('[id^=result_]').val());
            //alert(loadUrl);
            if(this.value != ''){
                $("#schedule-loading").show();
                $(".txt").hide();
                $("#result_"+this.value).load(loadUrl, function () {
                    for(i=0; i<m.length; i++){
                        $('#'+m[i].id).html($('#'+m[i].id).html().replace('/*',' '));
                        $('#'+m[i].id).html($('#'+m[i].id).html().replace('*/',' '));
                    }   
                    $("#schedule-loading").hide();
                });
            }
            
        });
        
        // creating date picker starter date
        var now = new Date();
        var day = ("0" + (now.getDate() + 1)).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);

        $('.datepicker').datepicker({
            //alert(new Date());
            startDate: '<?php echo $start_date;?>',
            endDate: '<?php echo $end_date;?>',
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

    })



</script>
