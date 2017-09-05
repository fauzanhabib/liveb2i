<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/raty/jquery.raty.css">
<script src="<?php echo base_url(); ?>assets/vendor/raty/jquery.raty.js"></script>


    <div class="box pure-g clear-both">
        <div class="sort-left pure-u-md-6-24 pure-u-lg-6-24">
            <div class="pure-g border-b-1-fa">
                <h3 class="text-center margin-auto font-semi-bold">Book a Coach</h3>
            </div>

            <div class="content padding-lr-0">
                <div class="box">
                    <div class="box-capsule bg-tertiary padding-tb-8 text-cl-white margin-auto font-14 width190">
                        <span>Sort By</span>
                    </div>
                    <ul class="sort-by padding-l-0">
                        <li><a href="<?php echo site_url('student/find_coaches/single_date'); ?>">Date</a></li>
                        <li><a href="<?php echo site_url('student/find_coaches/search/name'); ?>">Name</a></li>
                        <li class="border-none"><a href="#">Country</a></li>
                        <div class="h3 font-normal text-cl-secondary" style="padding: 10px 15px;">
                                <?php echo form_open('student/find_coaches/search/country', 'class="pure-form search-b-2 margin-auto"'); ?>
                                <?php //echo form_input('search_key', set_value('search_key'), 'class="search-input" type="text" style="font-size:14px" id="search_key"'); ?>
                                <?php 
                                    $country = array_column($this->common_function->country_code, 'name', 'name');
                                    $newoptions = array('' => '') + $country;
                                    echo form_dropdown('search_key', $newoptions, set_value('search_key'), 'style="font-size:14px;width: 100%;" id="search_key"'); 
                                ?>
                                <?php echo form_submit('__submit', @$this->auth_manager->userid() ? '' : 'Search', 'class="pure-button search-button" style="height:20px;"'); ?>
                                <?php echo form_close(); ?> 
                                
                                <script type="text/javascript">
                                $(function(){

                                    var $select2Elm = $('#search_key');

                                    $select2Elm.select2({
                                        placeholder: 'Search Here',
                                        width: 'resolve',
                                        //allowClear: true
                                        
                                    });
                                    //var select2 = $select2Elm.data('select2'),
                                    //$select2Input = $('.select2-input', select2.searchContainer),
                                    //$tagToRemove = $('li', select2.selection).eq(0);
                                    $('.select2-selection__arrow').hide();
                                    $('.select2-container--default .select2-selection--single').css({'border':'0','border-radius':'0','background':'none'});
                                    $('.select2-container--open .select2-dropdown').css({'left':'-1px'});
                                    $('.select2-dropdown').css({'border':'1px solid #d3d3d3'});
                                    //$('.select2-container--default .select2-selection--single').css({'background':'none'})
                                });
                            </script>
                        </div>
                        <li><a href="<?php echo site_url('student/find_coaches/search/spoken_language'); ?>">Languages Spoken</a></li>
                    </ul>
                </div>
            </div>  
        </div>
        <!-- ====== -->
        <div class="pure-u-1 pure-u-sm-24-24 pure-u-md-18-24 pure-u-lg-18-24">
            <div class="sort-right">
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
                    <?php } ?>           
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


            });

            // ajax
            // don't cache ajax or content won't be fresh
            $.ajaxSetup({
                cache: false
            });

            // load() functions
            //        var loadUrl = "<?php //echo site_url('student/find_coaches/availability/2/2015-06-13');  ?>";
            //        $(".loadbasic").click(function () {
            //            //var index = document.getElementById("loadbasic").value;
            //            //alert(this.value);
            //            $("#schedule-loading").show();
            //            $("#result_"+this.value).load(loadUrl, function () {
            //                $("#schedule-loading").hide();
            //            });
            //        });

            $(".date_available").on('change', function () {
                //alert(this.name);
                var loadUrl = "<?php echo site_url('student/find_coaches/availability/country'); ?>" + "/" + this.name + "/" + $(this).val();
                var m = $('[id^=result_]').html($('[id^=result_]').val());
                //alert(loadUrl);
                if ($(this).val() != '') {
                    $("#schedule-loading").show();
                    $(".txt").hide();
                    $("#result_" + this.name).load(loadUrl, function () {
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
                var loadUrl = "<?php echo site_url('student/find_coaches/schedule_detail'); ?>" + "/" + this.value;
                var m = $('[id^=result_]').html($('[id^=result_]').val());
                //alert(loadUrl);
                if (this.value != '') {
                    $("#schedule-loading").show();
                    $(".txt").hide();
                    $("#result_" + this.value).load(loadUrl, function () {
                        for(i=0; i<m.length; i++){
                            $('#'+m[i].id).html($('#'+m[i].id).html().replace('/*',' '));
                            $('#'+m[i].id).html($('#'+m[i].id).html().replace('*/',' '));
                        }
                        $("#schedule-loading").hide();
                    });
                }

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
