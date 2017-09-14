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
                        <li class="border-none"><a href="<?php echo site_url('student/find_coaches/single_date'); ?>">Date</a></li>
                        <div class="book-date">
                            <form data-parsley-validate id="multipleform" class="pure-g pure-form multiple-session" method="post" action="book_by_multiple_date" style="width: 100%;">
                                <div class="form-search-session">
                                    <p>Select a Date</p>
                                    <div class="frm-date">
                                        <input id="date1" name="date1" class="datepicker frm-date" type="text" required data-parsley-no-focus readonly>
                                        <span class="icon icon-date"></span>
                                    </div>
                                </div>
                                <div class="frm-multiple-remove" style="width:100%"></div>
                                <div class="form-search-session frm-multiple fade">
                                    <p>Select a Date</p>
                                    <div class="pure-g">
                            
                                        <div class="left">
                                            <div class="frm-date">
                                                <input id="date2" name="date2" class="datepicker frm-date" type="text" required data-parsley-no-focus readonly>
                                                <span class="icon icon-date"></span>
                                            </div>
                                        </div>

                                        <div class="left">
                                            
                                            <a href="#" class="addmultiple text-cl-green"><i class="icon icon-add"></i> Add More</a>

                                            
                                        </div>

                                    </div>
                                </div>
                                <div class="pure-g button">
                                    <button type="submit" class="pure-button btn-tertiary btn-expand-med searchmultiple" name="__submit">SEARCH</button>
                                </div>

                            </form>
                        </div>
                        <li><a href="<?php echo site_url('student/find_coaches/search/name'); ?>">Name</a></li>
                        <li><a href="<?php echo site_url('student/find_coaches/search/country'); ?>">Country</a></li>
                        <li><a href="<?php echo site_url('student/find_coaches/search/spoken_language'); ?>">Languages Spoken</a></li>
                    </ul>
                </div>
            </div>  
        </div>
        <div class="pure-u-1 pure-u-sm-24-24 pure-u-md-18-24 pure-u-lg-18-24">
            <div class="sort-right">
                <div class="pure-g border-b-1-fa">
                    <h3 class="font-semi-bold padding-l-20">Coach Available on</h3>
                </div>
                <div class="pure-g bg-white clearfix">
                     <?php
                        if(!@$data){
                            ?>
                            <div class="no-result">
                                No Data
                            </div>
                        <?php    
                        }

                    foreach ($data as $d) {
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
                            <h5><?php echo($d['token_for_student']); ?> Tokens</h5>
                            <h5></i><?php echo($d['country']); ?></h5>
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
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo(date('H:i',strtotime(@$av['start_time']))); ?></td>
                                                        <td class="text-center"><?php echo(date('H:i',strtotime(@$av['end_time']))); ?></td>
                                                        <td><a href="<?php echo site_url('student/find_coaches/summary_book/single_date/' . $d['coach_id'] . '/' . strtotime(@$date) . '/' . $av['start_time'] . '/' . $av['end_time']); ?>" class="pure-button btn-small btn-white" style="margin:0">Book</a></td>
                                                    </tr>
                                                    <?php
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

		  	// $('.datepicker').datepicker({
		   //    	format: 'mm-dd-yyyy'
		   //  });

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
             
		    
//		    $('.booking').click(function(){
//		    	alertify.set('notifier','position', 'top-right');
// 				alertify.success('Current position : ' + alertify.get('notifier','position'));
//		    });
		});

	})
</script>