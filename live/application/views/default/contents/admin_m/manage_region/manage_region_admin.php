<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Manage Admin</h1>
</div>

<div class="box">
    <div class="heading pure-g"></div>

    <div class="content padding0">
        <div class="box list-admins">
        	<div class="b-pad">
            <table id="tab2" class="table-session">
                <thead>
                    <tr>
                        <th class="padding15">USERNAME</th>
                        <th class="padding15">REGION</th>
                        <th class="padding15">STATUS</th>
                        <th class="padding15">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $d){ ?>
                    <tr>
                        <td class="padding15" data-label="fullname">
                            <span class="text-cl-secondary"><?php echo($d->email);?></span>
                        </td>
                        <td class="padding15" data-label="region">
                            <?php echo($d->region_name);?>
                        </td>
                        <?php
                            if($d->status == 'active'){ ?>
                                <td class="padding15" data-label="STATUS">
                                    <span class="labels active" style="width:75px">Activate</span>
                                </td>
                                <td class="padding15">
                                    <div class="rw">
                                        <a onclick="confirmation('<?php echo(site_url('admin_m/manage_region/deactivated_region_admin_m/'.$d->id));?>', 'group', 'Deactivated Admin', 'list-admins', 'admin-deactivated');" class="pure-button btn-small btn-white b-100 admin-deactivated" style="width:85px">Deactivated</a>
                                    </div>
                                </td>
                        <?php
                            }
                            else{ ?>
                                <td class="padding15" data-label="status">
                                    <span class="labels pending" style="width:75px">Deactivate</span>
                                </td>
                                <td class="padding15">
                                    <div class="rw">
                                        <a onclick="confirmation('<?php echo(site_url('admin_m/manage_region/activated_region_admin_m/'.$d->id));?>', 'group', 'Activate Admin', 'list-admins', 'admin-active');" class="pure-button btn-small btn-white b-100 admin-active" style="width:85px">Activated</a>
                                    </div>
                                </td>
                        <?php
                            }
                        ?>
                    </tr>
                    <?php
                    }?> 
                </tbody>
            </table>
            </div>    
        </div>
    </div>
</div>

<script type="text/javascript">
	$(function(){
		$('.admin-active').click(function(){
			return false
		})
		$('.admin-deactivated').click(function(){
			return false
		})
	});
</script>