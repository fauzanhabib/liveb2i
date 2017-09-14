<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Book a Coach</small></h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-12-24">
            <div class="h3 font-normal text-cl-secondary" style="padding: 10px 15px;">
                <?php echo form_open('student/find_coaches/search/country', 'class="pure-form search-b-2"'); ?>
                <?php echo form_input('search_key', set_value('search_key'), 'class="search-input" type="text" style="font-size:14px" id="search_key"'); ?>
                <?php echo form_submit('__submit', @$this->auth_manager->userid() ? '' : 'Search', 'class="pure-button search-button"'); ?>
                <?php echo form_close(); ?> 
            </div>
        </div>
        <!-- block edit -->
        <div class="pure-u-12-24 edit">
            <a href="<?php echo site_url('student/find_coaches/single_date'); ?>">Date</a>
            <a href="<?php echo site_url('student/find_coaches/search/name'); ?>">Name</a>
            <a href="<?php echo site_url('student/find_coaches/search/country'); ?>" class="active">Country</a>
        </div>
        <!-- end block edit -->
    </div>
    <?php
    if (!$coaches) {
        echo('&nbsp;&nbsp;&nbsp;&nbsp;No Coach Found');
    }
    ?>
    <div class="content">
        <div class="box pure-g">
            <?php
            foreach ($coaches as $c) {
                ?>
                <div class="pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">

                    <div class="list-coach-box">
                        <div class="pure-g">

                            <div class="pure-u-2-5 photo">
                                <img src="<?php echo base_url(); ?>images/BookCoach.jpg">
                            </div>

                            <div class="pure-u-3-5 detail">
                                <span class="name"><?php echo($c->fullname); ?></span>
                                <div class="rating">
                                    <i class="stars_full"></i>
                                    <i class="stars_full"></i>
                                    <i class="stars_full"></i>
                                    <i class="stars_full"></i>
                                    <i class="stars_full"></i>
                                </div>
                                <table>
    <!--								<tr>
                                                <td>Certification</td>
                                                <td> : D7071</td>
                                        </tr>
                                        <tr>
                                                <td>PT Level</td>
                                                <td> : 0.5</td>
                                        </tr>-->
                                    <tr>
                                        <td>Token Cost</td>
                                        <td>:</td>
                                        <td><?php echo($c->token_for_student); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Country</td>
                                        <td>:</td>
                                        <td><?php echo($c->country); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="more pure-u-1">
                                <span class="click arrow">View Schedule</span>
                            </div>

                        </div>
                    </div>
                    <div class="view-schedule" style="display:none">
                        <div class="pure-form">
                            <div class="date" value="<?php echo($c->id);?>">
                                <input class="date_available datepicker frm-date" type="text" name="<?php echo($c->id);?>" readonly> <span><button class="weekly_schedule" value="<?php echo($c->id);?>" style="background:none;border:none;" class="link">Weekly Schedule</button></span>
                            </div>
<!--                            <button class="loadbasic" value="<?php //echo($c->id);?>">Load</button>-->
                            <div class="list-schedule">
                                <div id="result_<?php echo($c->id);?>">
                                    <img src='<?php echo base_url(); ?>images/small-loading.gif' alt='loading...' style="display:none;" id="schedule-loading"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>		
</div>

<script type="text/javascript">
    $(function () {
        $(document).ready(function () {

            $('.list').each(function () {
                var $dropdown = $(this);

                $(".click", $dropdown).click(function (e) {
                    e.preventDefault();
                    $div = $(".view-schedule", $dropdown);
                    $div.toggle();
                    $(".view-schedule").not($div).hide();
                    return false;
                });
            });

            $('html').click(function () {
                //$(".view-schedule").hide();
            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });


        });

        // ajax
        // don't cache ajax or content won't be fresh
        $.ajaxSetup({
            cache: false
        });

        // load() functions
//        var loadUrl = "<?php //echo site_url('student/find_coaches/availability/2/2015-06-13'); ?>";
//        $(".loadbasic").click(function () {
//            //var index = document.getElementById("loadbasic").value;
//            //alert(this.value);
//            $("#schedule-loading").show();
//            $("#result_"+this.value).load(loadUrl, function () {
//                $("#schedule-loading").hide();
//            });
//        });
            
        $(".date_available").on('change', function() {
            //alert(this.name);
            var loadUrl = "<?php echo site_url('student/find_coaches/availability/country'); ?>"+ "/" +this.name+ "/" +$(this).val();
            //alert(loadUrl);
            if($(this).val() != ''){
                $("#schedule-loading").show();
                $("#result_"+this.name).load(loadUrl, function () {
                    $("#schedule-loading").hide();
                });
            }
            
        });
        
        $(".weekly_schedule").click(function () {
            //alert(this.name);
            var loadUrl = "<?php echo site_url('student/find_coaches/schedule_detail'); ?>"+ "/" +this.value;
            //alert(loadUrl);
            if(this.value != ''){
                $("#schedule-loading").show();
                $("#result_"+this.value).load(loadUrl, function () {
                    $("#schedule-loading").hide();
                });
            }
            
        });

    })



</script>
