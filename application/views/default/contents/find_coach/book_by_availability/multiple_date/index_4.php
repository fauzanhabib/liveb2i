<div class="heading text-cl-primary padding15">
	<h1 class="margin0">Book a Coach</small></h1>
</div>

<div class="box">
	<div class="heading pure-g">
		<!-- block edit -->
			<div class="pure-u-1 edit" style="margin: 0px 0px 15px;">
                        <a href="<?php echo site_url('student/find_coaches/single_date'); ?>" class="active">Date</a>
                        <a href="<?php echo site_url('student/find_coaches/search/name'); ?>">Name</a>
                        <a href="<?php echo site_url('student/find_coaches/search/country'); ?>">Country</a>
		</div>
		<!-- end block edit -->
	</div>

	<div class="content">
		<div class="box">

			<div class="tab-session tab-edited">
				<div class="pure-g">
					<div class="pure-u-2-5">
						<div class="date">
							<?php echo (date('l jS \of F Y', strtotime(@$this->session->userdata('date_' . $index))));?>
						</div>
						<div class="cart">
							<div class="number">5</div>
							<div class="image">
								<img src="assets/icon/cart.png">
							</div>
							<div class="dropdown-cart-box">
								<div class="dropdown-cart-header">
									YOUR BOOKINGS
								</div>
								<div class="dropdown-cart-list">
									Your session will be started at Thursday,June 2,2015 from 19.00 until 19.30 with coach steve
								</div>
								<div class="dropdown-cart-list">
									Your session will be started at Thursday,June 2,2015 from 19.00 until 19.30 with coach steve
								</div>
								<div class="dropdown-cart-button">
									<button class="pure-button btn-small btn-white">VIEW DETAILS</button>
								</div>
							</div>
						</div>
					</div>
					<div class="pure-u-3-5 text-right">
                        <?php
                        if (@$this->session->userdata('date_' . ($index - 1)) && ($index) > 1) {
                            $url = base_url() . 'index.php/student/find_coaches/book_by_multiple_date_index/' . ($index - 1);
                            echo '<a href="'.$url.'" class="pure-button btn-small btn-expand btn-primary-border">BACK</a>';
                            //echo anchor(base_url() . 'index.php/student/find_coaches/book_by_multiple_date_index/' . ($index - 1), 'Back | ');
                        }

                        if (@$this->session->userdata('date_' . ($index + 1)) == '' || ($index + 1) > 5) {
                            $url = base_url() . 'index.php/student/find_coaches/confirm_book_by_multiple_date/';
                            echo '<a href="'.$url.'" class="pure-button btn-small btn-expand btn-primary-border">CONFIRM</a>';
                            //echo anchor(base_url() . 'index.php/student/find_coaches/confirm_book_by_multiple_date/', 'Confirm ');
                        } else {
                            $url = base_url() . 'index.php/student/find_coaches/book_by_multiple_date_index/' . ($index + 1);
                            echo '<a href="'.$url.'" class="pure-button btn-small btn-expand btn-primary-border">NEXT DATE</a>';
                            //echo anchor(base_url() . 'index.php/student/find_coaches/book_by_multiple_date_index/' . ($index + 1), 'Next ');
                        }
                        ?>
							
					</div>
				</div>
			</div>
			<div class="pure-g">
				<?php
				foreach ($data as $d) {
				?>
				<div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">
					<div class="box-info">
						<div class="image">
							<img src="<?php echo base_url().$d['profile_picture']; ?>" style="width:125px;height:129px">
						</div>
						<div class="detail">
							<span class="name"><?php echo($d['fullname']); ?></span>
							<div class="rating">
								<i class="stars_full"></i>
								<i class="stars_full"></i>
								<i class="stars_full"></i>
								<i class="stars_full"></i>
								<i class="stars_full"></i>
							</div>
							<table>
								<tr>
									<td>Token Cost</td>
									<td>:</td>
									<td><?php echo($d['token_for_student']); ?></td>
								</tr>
								<tr>
									<td>Country</td>
									<td>:</td>
									<td><?php echo($d['country']); ?></td>
								</tr>
							</table>
						</div>
						<div class="more pure-u-1">
							<span class="click arrow">View Shedule <i class="icon icon-arrow-down"></i></span>
						</div>
					</div>
					<div class="view-schedule hide">
						<div class="box-schedule">
						<!--<form class="pure-form">-->
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
										<?php
										foreach ($d['availability'] as $av) {
										?>
										<tr>
                                                                                        <td class="text-center"><?php echo(date('H:i',strtotime(@$av['start_time']))); ?></td>
                                                                                        <td class="text-center"><?php echo(date('H:i',strtotime(@$av['end_time']))); ?></td>
											<td><a href="<?php echo site_url('student/find_coaches/book_multiple_coach/' . $d['coach_id'] . '/' . strtotime(@$this->session->userdata('date_' . $index)) . '/' . $av['start_time'] . '/' . $av['end_time']. '/'. $index); ?>" class="pure-button btn-small btn-white booking">Book</a></td>
                                                                                        
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>			
						<!--</form>-->
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

		/**

		<?php
		for ($i=1; $i <= 9 ; $i++) { 
		?>
		$('.click_<?=$i?>').click(function(){
			$( ".view_schedule_<?=$i?>" ).toggle();
		});
		<?php 
		}
		?>

		**/

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
		    
//		    $('.booking').click(function(){
//		    	alertify.set('notifier','position', 'top-right');
// 				alertify.success('Current position : ' + alertify.get('notifier','position'));
//		    });
		});

	})
</script>