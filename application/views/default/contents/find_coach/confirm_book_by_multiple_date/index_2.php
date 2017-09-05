<div class="heading text-cl-primary padding15">
	<h1 class="margin0">Booking Summary</small></h1>
</div>

<div class="box">

	<div class="content">
		<div class="box pure-g">

			<div class="accordion">
				<?php
                                $i = 1;
				foreach($data as $d){
				$a = 1;
				$open = ($a==$i) ? "open":"";
                                $booking_index = $i++;
				?>
				<div class="accordion-section">
					<div class="accordion-link" data-acc="accordion-<?php echo $booking_index;?>">
						<div class="title">
							BOOKING <?php echo $booking_index;?>
						</div>
						<div class="icon">
							<i class="accordion-up"></i>
							<i class="accordion-down"></i>
							<i class="accordion-close"></i>
						</div>
					</div>
					<div id="accordion-<?php echo $booking_index;?>" class="accordion-content <?=$open?>">
						<table class="table-no-border2" style="border-collapse: separate;border-spacing: 0px 10px;padding:0">
							<tr>
								<td>Name</td>
								<td><?php echo($id_to_name[$d->coach_id]);?></td>
							</tr>
							<tr>
								<td>Date</td>
								<td><?php echo(date('l jS \of F Y', strtotime($d->date)));?></td>
							</tr>
							<tr>
								<td>Start Time</td>
								<td><?php echo($d->start_time);?></td>
							</tr>
							<tr>
								<td>End Time</td>
								<td><?php echo($d->end_time);?></td>
							</tr>
							<tr>
								<td>Call Method</td>
								<td>Skype / Webex</td>
							</tr>
							<tr>
								<td>Token Cost</td>
								<td><?php echo($token_cost[$d->coach_id]);?></td>
							</tr>
						</table>
					</div>
				</div>
				<?php
				}
				?>
				<div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;margin-top:15px;">
		        	<div class="label">
                                <a class="pure-button btn-small btn-primary" href="<?php echo site_url('student/find_coaches/confirm_book/');?>" onclick=" return confirm('Are you sure?');">Confirm</a>
		            </div>
		            <div class="input">
		            	<button class="pure-button btn-small btn-white">CANCEL</button>
		            </div>
		        </div>

			</div>

			
		</div>
	</div>		
</div>
<script type="text/javascript">
	$(function() {

		function close_accordion() {
			$('.accordion-link').removeClass('active');
			$('.accordion-content').slideUp(300).removeClass('open');
			return false;
		}


		$('.accordion-link').click(function(e){
			var currentValue = $(this).attr('data-acc');

			if($(e.target).is('.active')) {
				close_accordion();
				return false;
			}
			else {
				close_accordion();
				$(this).addClass('active');
				$('.accordion #' + currentValue).slideDown(300).addClass('open');
			}
			
			e.preventDefault();
		});
	})
</script>