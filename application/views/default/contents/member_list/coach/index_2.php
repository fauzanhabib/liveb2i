<div class="heading text-cl-primary padding15">
	<h1 class="margin0">Coach List</small></h1>
</div>

<div class="box">
	<div class="heading pure-g">
		<!-- block edit -->
		<div class="pure-u-1 edit">
			<a href="<?php echo site_url('partner/adding/coach'); ?>" class="add"><i class="icon icon-add"></i> Add New Coach</a>
		</div>
		<!-- end block edit -->
	</div>

	<div class="content">
		<div class="box pure-g">
			<?php $i =1; foreach($data as $d){ ?>
			<div class="pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list list-coach-hover">
				<div class="list-coach-action">
					<div>
						<a href="<?php echo site_url('partner/member_list/edit_coach/'.@$d->id ); ?>" onclick=" return confirm('Edit Coach?');" type="button" class="pure-button btn-small btn-transparent">EDIT</a>
						<a href="<?php echo site_url('partner/member_list/delete_coach/'.@$d->id ); ?>" onclick=" return confirm('Delete Coach?');" type="button" class="pure-button btn-small btn-transparent">DELETE</a>
					</div>
				</div>
				<div class="list-coach-box">
					<div class="pure-g">
                                            //<?php
//                                                $im = new Imagick();
//                                                $im->setResolution(144,144);
//                                                $im->readImage(base_url().'images/BookCoach.jpg');
//                                                $im->setImageFormat("png");
//                                                header("Content-Type: image/png");
//                                                echo $im;
//                                            ?>

						<div class="pure-u-2-5 photo">
							<img src="<?php echo base_url(); ?>images/BookCoach.jpg">
						</div>

						<div class="pure-u-3-5 detail">
							<span class="name"><?php echo $d->fullname; ?></span>
							<table style="margin-top:20px;">
<!--								<tr>
									<td>Student ID</td>
                                                                        <td>:</td>
									<td>D7071</td>
								</tr>-->
<!--								<tr>
									<td>PT Level</td>
                                                                        <td>:</td>
									<td>0.5</td>
								</tr>-->
                                                                <tr>
									<td>Gender</td>
                                                                        <td>:</td>
									<td><?php echo $d->gender; ?></td>
								</tr>
								<tr>
									<td>Country</td>
                                                                        <td>:</td>
									<td><?php echo $d->country; ?></td>
								</tr>
                                                                <tr>
									<td>Phone Number</td>
                                                                        <td>:</td>
									<td><?php echo $d->phone; ?></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>		
</div>

<script type="text/javascript">
$(document).ready(function () {
    $(document).on('mouseenter', '.list-coach-hover', function () {
        $(this).find(".list-coach-action").show();
    }).on('mouseleave', '.list-coach-hover', function () {
        $(this).find(".list-coach-action").hide();
    });
});
</script>
