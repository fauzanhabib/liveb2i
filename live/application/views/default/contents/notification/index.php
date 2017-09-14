<div class="heading text-cl-primary padding15">
	<h1 class="margin0">Notification</small></h1>
</div>

<div class="box" style="border-top: 2px solid #f3f3f3;">
	<div class="content">
		<div class="box">
			<?php
			if($data){
			foreach($data as $d){ 
			?>
			<div class="list-notification">
				<div class="text">
				<?php echo $d->description; ?>
				</div>
				<div class="time">
					<?php echo($received_time[$d->id]); ?>
				</div>
			</div>
			<?php } 
		} else {

			echo "<div class='padding15'><div class='no-result'>No Data</div></div>";

		}
			?>
		</div>
	</div>		
</div>

