
<div class="heading text-cl-primary padding15">
	<h1 class="margin0">Coach List</h1>
</div>

<div class="box">
	<div class="heading pure-g">
		<?php //echo("&nbsp;&nbsp;&nbsp;&nbsp;Find coaches available at " . date('l jS \of F Y', strtotime(@$date))); ?>
	</div>
<?php echo("&nbsp;&nbsp;&nbsp;&nbsp;Find coaches for class available at ". date('l jS \of F Y',$date)); ?>
	<div class="content">
		<div class="box">
			<div class="pure-g">
				<?php
				foreach(@$data as $d){
				?>
				<div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">
					<div class="box-info">
						<div class="image">
							<img src="<?php echo base_url(); ?>images/BookCoach.jpg">
						</div>
						<div class="detail">
							<span class="name"><?php echo $d['fullname']; ?></span>
							<div class="rating">
								<i class="stars_full"></i>
								<i class="stars_full"></i>
								<i class="stars_full"></i>
								<i class="stars_full"></i>
								<i class="stars_full"></i>
							</div>
							<table>
								<tr>
									<td>Country</td>
									<td>:</td>
									<td><?php echo $d['country']; ?></td>
								</tr>
                                                                <tr>
									<td>Phone Number</td>
									<td>:</td>
									<td><?php echo $d['phone']; ?></td>
								</tr>
							</table>
						</div>
						<div class="more pure-u-1">
							<span class="click click_<?=$d['coach_id'];?> arrow">View Shedule <i class="icon icon-arrow-down"></i></span>
						</div>
					</div>
					<div class="view-schedule hide">
						<div class="box-schedule-max">
							<form class="pure-form">
								<div class="list-schedule">
									<table class="tbl-booking">
										<thead>
											<tr>
												<th class="text-center">START TIME</th>
												<th class="text-center">END TIME</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach($d['availability'] as $av) { 
											?>
											<tr>
												<td class="text-center"><?php echo(date('H:i',strtotime($av['start_time'])));?></td>
												<td class="text-center"><?php echo(date('H:i',strtotime($av['end_time'])));?></td>
												<td><a href="<?php echo site_url('student_partner/managing/create_meeting_day/'. @$class_id. '/' .@$d['coach_id']. '/' .@date('Y-m-d', $date)). '/' .$av['start_time']. '/' .$av['end_time']; ?>" onclick=" return confirm('Set Meeting Time?');" type="submit" class="pure-button btn-small btn-white" style="margin:0">Set Meeting Time</a></td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>			
							</form>
						</div>
					</div>
				</div>
				<?php } ?>
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
		      		$icon = $("span .icon", $dropdown);

		      		if($($schedule).hasClass("show")) {
		      			$($schedule).addClass('hide');
		      			$($schedule).removeClass('show');
		      			$($span).removeClass('active-schedule');
		      			$($icon).removeClass('icon-flips');
		      		}
		      		else {
		      			$($schedule).addClass('show');
		      			$($schedule).removeClass('hide');
		      			$($span).addClass('active-schedule');
		      			$($icon).addClass('icon-flips');
		      		}

		      		$(".view-schedule").not($schedule).addClass('hide');
		      		$(".view-schedule").not($schedule).removeClass('show');
		      		$("span").not($span).removeClass('active-schedule');
		      		$("span .icon").not($icon).removeClass('icon-flips');

		      		return false;
		    	});
			});
		    
		  	$('html').click(function(){
		    	//$(".view-schedule").hide();
		  	});

		  	$('.datepicker').datepicker({
		      	format: 'mm-dd-yyyy'
		    });
		     
		});

	})
</script>