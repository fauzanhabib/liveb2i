<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Region Settings</h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-1 tab-list tab-link">
            <ul>
                <li class="current"><a href="" class="active">Specific Setting</a></li>
                <li><a href="">Default Setting</a></li>
            </ul>
        </div>    
    </div>
    <div class="content padding0">
        <div class="box list-regions">
        	<div class="b-pad">
            <table id="tab2" class="table-session">
                <thead>
                    <tr>
                        <th class="padding15">REGION</th>
                        <th class="padding15">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $d){ ?>
                        <tr>
                            <td class="padding15" data-label="REGION">
                                <span class="text-cl-secondary"><?php echo($d->name);?></span>
                            </td>
                            <td class="padding15">
                                    <div class="rw">
                                            <div class="b-50">
                                                <a onclick="confirmation('<?php echo site_url('admin/region_setting/edit_spesific_setting/'.$d->id);?>', 'group', 'Edit Region Setting', 'list-regions', 'region-setting');" class="pure-button btn-small btn-white region-setting">EDIT</a>
                                            </div>
                                            <div class="b-50">
                                                <a <?php echo(!$d->setting_id ? 'disabled':'');?> onclick="confirmation('<?php echo(site_url('admin/region_setting/reset_setting/'.$d->setting_id));?>', 'group', 'Reset Region Setting', 'list-regions', 'region-setting');" class="pure-button btn-small btn-white <?php echo(!$d->setting_id ? '':'region-setting');?>">RESET SETTING</a>
                                            </div>
                                    </div>
                            </td>
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
		$('.region-setting').click(function(){
			return false
		})
		$('.reset-setting').click(function(){
			return false
		})
	});
</script>