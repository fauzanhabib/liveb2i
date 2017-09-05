<div class="heading text-cl-primary padding15">
	<h1 class="margin0">Manage Class</h1>
</div>

<div class="box">
	<div class="heading pure-g">
		<div class="pure-u-12-24">
<!--			<div class="h3 font-normal text-cl-secondary" style="padding: 10px 15px;">
				<form class="pure-form search-b-2" method="POST" action="site.php?role=student&page=student-book-by-name">
					<input class="search-input" type="text" placeholder="Search by class name" style="font-size:14px;">
					<button class="pure-button search-button"></button>
				</form> 
			</div>-->
		</div>
		<!-- block edit -->
		<div class="pure-u-12-24 edit">
			<a href="<?php echo site_url('student_partner/adding/classes'); ?>" class="add"><i class="icon icon-add"></i> Add Class</a>
		</div>
		<!-- end block edit -->
	</div>

	<div class="content padding0">
		<div class="box">
			<table class="table-session tbl-manage-class">
				<thead>
					<tr>
						<th class="padding15">CLASS NAME</th>
						<th class="padding15 text-center">MAX STUDENT</th>
						<th class="padding15">START DATE</th>
						<th class="padding15">END DATE</th>
						<th colspan="2" class="padding15">ACTION</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1;
                                        foreach (@$classes as $c) { ?>
					
					<tr>
						<td class="padding15"><?php echo $c->class_name; ?></td>
						<td class="padding15 text-center"><?php echo $c->student_amount; ?></td>
						<td class="padding15"><?php echo date('M d, Y',strtotime($c->start_date)); ?></td>
						<td class="padding15"><?php echo date('M d, Y',strtotime($c->end_date)); ?></td>
						<td class="padding15"><a href="<?php echo site_url('student_partner/managing/edit_class/' . @$c->id); ?>" onclick=" return confirm('Edit Class?');">Edit</a></td>
						<td class="padding15 delete"><a href="<?php echo site_url('student_partner/managing/delete_class/' . @$c->id); ?>" onclick=" return confirm('Delete Class');">Delete</a></td>
					</tr>

					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>		
</div>