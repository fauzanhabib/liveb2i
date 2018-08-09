<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Super Admins</h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-1 edit no-left">
            <a href="<?php echo site_url('admin_m/manage_region/add_region_admin');?>" class="add"><i class="icon icon-add"></i> Add Region Admin</a>
            <a href="<?php echo site_url('admin_m/manage_region/manage_region_admin');?>" class="add"><i class="icon icon-setting"></i> Manage Admin</a>
        </div>
        <!-- end block edit -->
    </div>
    

    <div class="content">
        <div class="box">
            <div class="pure-g list-admins">
                <?php
                foreach ($data as $d) { 
                ?> 
                    <div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">

                        <div class="box-info">

                            <div class="image">
                                <img src="<?php echo base_url($d->profile_picture);?>" style="width:125px;height:129px" class="list-cover">
                            </div>
                            <div class="detail">

                                <span class="name"><a href="<?php echo site_url('admin_m/manage_region/detail_region_admin_m/'.$d->id);?>"><?php echo($d->fullname);?></a></span>
                                <table class="margint25">
                                    <tr>
                                        <td>Region</td>
                                        <td>:</td>
                                        <td><?php echo($d->region_name);?></td>
                                    </tr>
<!--                                    <tr>
                                        <td>Country</td>
                                        <td>:</td>
                                        <td>China</td>
                                    </tr>-->
                                    <tr>
                                        <td>Phone</td>
                                        <td>:</td>
                                        <td><?php echo($d->phone);?></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="more pure-u-1 padding-t-15">
                                <a href="<?php echo site_url('admin_m/manage_region/detail_region_admin_m/'.$d->id);?>" class="pure-button btn-small btn-white btn-48">
                                    VIEW
                                </a>
                                <a class="pure-button btn-small btn-white delete-admin btn-48" onclick="confirmation('', 'group', 'Delete Admin', 'list-admins', 'delete-admin');">
                                    DELETE
                                </a>
                            </div>

                        </div>

                    </div>
                <?php } ?>
            </div>
            <?php //echo @$pagination;?>
        </div>
    </div>		
</div>
<script type="text/javascript">
	$(function(){
		$('.delete-admin').click(function(){
			return false
		})
	});
</script>