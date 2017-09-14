<?php
    $day = array(
        'Monday' => 'monday',
        'Tuesday' => 'tuesday',
        'Wednesday' =>'wednesday',
        'Thursday' => 'thursday',
        'Friday' => 'friday',
        'Saturday' => 'saturday',
        'Sunday' => 'sunday'
    );
?>
<div class="heading text-cl-primary">
	<h1 class="margin0">My Schedule</small></h1>
</div>

<div class="box">
	<div class="heading pure-g">
		<div class="pure-u-12-24">
			<h3 class="h3 font-normal padding15 text-cl-secondary">SET SCHEDULE</h3>
		</div>
		<div class="pure-u-12-24 tab-list">
			<a href="<?php echo site_url('coach/schedule');?>" class="active">My Schedule</a>
                        <a href="<?php echo site_url('coach/day_off');?>" >Manage Day Off</a>
		</div>
	</div>

	<div class="content">
		<div class="box schedule-box">
                    <?php foreach($day as $d => $value){ ?>
                        <div class="pure-g list">
                            <?php echo form_open('coach/schedule/update/'.$value, 'role="form"'); ?>
				<div class="pure-u-5-24 day">
					<?php echo ($d);?>
				</div>
				<div class="pure-u-12-24 date">
                                    <?php
                                    for($i=0; $i<count(@$schedule[$value]); $i++){ ?>
                                        <div class="block-date disable" style="width:100%">
						<span>Schedule <?php echo($i+1);?></span>
						<?php echo(date('H:i',strtotime($schedule[$value][$i]['start_time'])));?>
						<span>to</span>
						<?php echo(date('H:i',strtotime($schedule[$value][$i]['end_time'])));?>
					</div>

					<div class="block-date edited" style="width:100%">
						<span>Schedule <?php echo($i+1);?></span>
						<div class="select-time">
                                                        <?php echo form_input('start_time_'.$i, set_value('start_time_'.$i, '25:00'), "class='timepicker tm' readonly id= start_time_".$i) ?>
						</div>
						<span>to</span>
						<div class="select-time">
							<?php echo form_input('end_time_'.$i, set_value('end_time_'.$i, @$schedule[$value][$i]['end_time']), "class='timepicker tm' readonly id= end_time_".$i) ?>
						</div>
                                                <?php echo($i == 0 ? '<span class="addmore">Add More</span>' : '<span class="remove-field" style="display:inline-block"> Remove</span>');?>
					</div>
                                    <?php
                                    }
                                    ?>

					
				</div>
				<div class="pure-u-7-24 edit aa">
					<span class="link-edit">Edit</span>
				</div>
				<div class="pure-u-7-24 update">
					
                                        <?php echo form_submit('__submit', 'UPDATE','class="pure-button btn-small btn-white update"'); ?>
					<button type="button" class="pure-button btn-small btn-book cancel">CANCEL</button>
				</div>
                            <?php echo form_close(); ?>
			</div>
                    <?php
                    }?>
		</div>
	</div>		
</div>

<script type="text/javascript">

	function timepicker_select() {
	    $('.timepicker').timepicker({
	        defaultTime:false,
	 		minuteStep:30,
	 		showMeridian:false,
	 		disableFocus:true,
	 		showInputs: false,
	 		disableMousewheel: true
	    });
	}

	$(document).ready(function(){



		/**
		$('.select-time').each(function(){

		    var $dropdown = $(this);

		    $("input", $dropdown).click(function(){

		    	var $time = $('.timepicker', $dropdown);

		    	$time.timepicker({
				 	defaultTime:false,
				 	minuteStep:30,
				 	showMeridian:false,
				 	disableFocus:true,
				 	showInputs: false,
				 	disableMousewheel: true
				});

				$('.timepicker').not($time).timepicker({
					disable:true
				});


			});	
				
		});**/

		

		$('.timepicker').timepicker({
		 	defaultTime:false,
		 	minuteStep:30,
		 	showMeridian:false,
		 	disableFocus:true,
		 	showInputs: false,
		 	disableMousewheel: true
		});

		 $('.list').each(function() {
	    	var $dropdown = $(this);

	    	$removef = $('.remove-field',$dropdown);


	    	/**
	    	$(".block-date input").bind('timepicker', function(event){ 
				event.stopPropagation();
				event.preventDefault();
		        $('.timepicker').timepicker({
				 	defaultTime:false,
				 	minuteStep:30,
				 	showMeridian:false,
				 	disableFocus:true,
				 	showInputs: false,
				 	disableMousewheel: true
				 });
		    });**/

			/**

			$('.select-time input', $dropdown).click(function(event){

				if($(event.target).is('.timepicker')) {
					$('.timepicker', $dropdown).timepicker({
					 	defaultTime:false,
					 	minuteStep:30,
					 	showMeridian:false,
					 	disableFocus:true,
					 	showInputs: false,
					 	disableMousewheel: true
					});
				}
				else {
					$('.timepicker', $dropdown).timepicker({
						disableTimepicker:true,
						template:'nope',
					});
					alert('no');
				}

			});

			**/



	    	$(".link-edit", $dropdown).click(function(e) {
	      		e.preventDefault();

	      		var $edit=$(".edit", $dropdown),
	      		    $update = $(".update", $dropdown),
	      		    $addmore = $(".addmore", $dropdown),
	      		    $block_input = $(".block-date input", $dropdown),
	      		    $block_edit = $(".block-date.edited", $dropdown),
	      		    $block_disable = $(".block-date.disable", $dropdown),
	      		    $rmclass = $(".rm-class", $dropdown);
	      

	      		$edit.hide();
	      		$update.css('display','inline-block');
	      		$addmore.show();
	      		$block_disable.hide();
	      		$block_edit.show();
	      		//$block_input.addClass('tm'); 
	      		//$block_input.addClass('timepicker'); 
	      		//$update.show();

	      		$(".edit").not($edit).show();
	      		$(".update").not($update).css('display','none');
	      		$(".addmore").not($addmore).hide();
	      		$(".rm-class").not($rmclass).hide();
	      		$(".block-date.edited").not($block_edit).hide();
	      		$(".block-date.disable").not($block_disable).show();
	      		//$(".block-date input").not($block_input).val('');

	      		return false;
	    	});

	    	$(".cancel", $dropdown).click(function(e) {

	      		e.preventDefault();

	      		$(".edit", $dropdown).show();
	      		$(".update", $dropdown).css('display','none'); 
	      		$(".addmore", $dropdown).hide();
	      		$(".block-date.edited", $dropdown).hide();
	      		$('.block-date.disable', $dropdown).show();
	      		
	      		return false;
	    	});

	    	var max_field = 2;
		 	var addmore = '.addmore';

	    	$(addmore, $dropdown).click(function(e) {
	    		var i = $('.block-date.edited', $dropdown).length;
	      		e.preventDefault();
	      		$wrapper = $(".date", $dropdown);
	      		if (i < max_field) {
	      			i++;
	      			$($wrapper).append('<div class="block-date edited" style="width:100%;margin-top:10px;"><span>Schedule 2 </span><div class="select-time">\n\
                    <?php echo form_input("start_time_1", set_value("start_time_1", "0:00"), 'class="timepicker tm" onmousemove="timepicker_select()"  readonly id= start_time_1') ?>\n\
                    </div><span> to </span><div class="select-time">\n\
                    <?php echo form_input("end_time_1", set_value("end_time_1", "0:00"), 'class="timepicker tm" onmousemove="timepicker_select()"  readonly id= end_time_1') ?>\n\
                    </div> l<span class="remove-field" style="display:inline-block"> Remove</span></div>');
	      		}
	      		$(".block-date.edited", $dropdown).show();
	      		$(".remove-field", $dropdown).show();
	      	});

	      	$("body").on("click", ".remove-field", function (e) {
	      		var i = $('.block-date.edited', $dropdown).length;
	      		e.preventDefault();
				$(this).parent("div").remove();
				i--;
			});
		});
 		$('.box .tab-link a').click(function(e){
			var currentValue = $(this).attr('href');
	
			$('.box .tab-link a').removeClass('active');
			$('.tab').removeClass('active');

			$(this).addClass('active');
			$(currentValue).addClass('active');

			e.preventDefault();

		})

	})
</script>

