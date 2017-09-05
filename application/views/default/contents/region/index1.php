<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Regional Admins</h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-1 edit no-left">
            <a href="" class="add"><i class="icon icon-add"></i> Add Regional Admin</a>
            <a href="" class="add"><i class="icon icon-add"></i> Manage Admin</a>
        </div>
        <!-- end block edit -->
    </div>
    

    <div class="content">
        <div class="box">
            <div class="pure-g list-admins">
                <?php
                for ($i=0; $i < 9; $i++) { 
                ?> 
                    <div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">

                        <div class="box-info">

                            <div class="image">
                                <img src="http://idbuild.id.dyned.com/live/uploads/images/AlexBW_1_200.jpg" style="width:125px;height:129px" class="list-cover">
                            </div>
                            <div class="detail">

                                <span class="name"><a href="">Ponel Panjaitan</a></span>
                                <table class="margint25">
                                    <tr>
                                        <td>Regional</td>
                                        <td>:</td>
                                        <td>Asia</td>
                                    </tr>
                                    <tr>
                                        <td>Country</td>
                                        <td>:</td>
                                        <td>China</td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>:</td>
                                        <td>0867316273</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="more pure-u-1 padding-t-15">
                                <a href="" class="pure-button btn-small btn-white btn-48">
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