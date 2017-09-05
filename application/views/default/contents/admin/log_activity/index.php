<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Log Activity</h1>
</div>

<div class="box b-f3-2">
	<div class="content padding0">
		<div class="b-pad">
			<table class="table-session">
				<thead>
					<tr>
						<th class="padding15">DATE</th>
						<th class="padding15">USER</th>
						<th class="padding15">DESCRIPTION</th>
					</tr>
				</thead>
				<tbody>
                                    <?php foreach($data as $d){ ?>
                                        <tr>
						<td class="padding15" data-label="DATE"><?php echo(date('l jS \of F Y', $d->dcrea));?></td>
						<td class="padding15" data-label="USER"><span class="text-cl-secondary"><?php echo($d->fullname);?></span></td>
						<td class="padding15" data-label="DESCRIPTION">
							<span><?php echo($d->description);?></span>
						</td>
					</tr>
                                    <?php
                                    }?>
				</tbody>
			</table>
                    <?php echo(@$pagination);?>
		</div>
	</div>
</div>