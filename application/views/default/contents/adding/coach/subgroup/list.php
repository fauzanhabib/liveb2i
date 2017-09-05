<div class="heading text-cl-primary padding15">
    <h1 class="margin0">List Of Subgroup</h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <!-- block edit -->
        <div class="pure-u-1 edit no-left">
            <a href="<?php echo site_url('partner/subgroup/add_subgroup');?>" class="add"><i class="icon icon-add"></i>Add Subgroup</a>
        </div>
        <!-- end block edit -->
    </div>

    <div class="content">
        <div class="box">
            <div class="pure-g list-partner">
                <?php
                foreach ($data as $p) {
                    ?>
                    <div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">

                        <div class="box-info">
                            <div class="image">
                                <a href="<?php echo site_url('superadmin/region/detail/'. $p->id); ?>">
                                    <img src="<?php echo base_url().'/'.$p->profile_picture;?>" class="list-cover">
                                </a>    
                            </div>
                            <div class="detail">
                                <a href="<?php echo site_url('superadmin/region/detail/'. $p->id); ?>" class="name"><?php echo $p->region_id?></a>

                                <table class="margint25">
                                    <tr>
                                        <td>Admin</td>
                                        <td>:</td>
                                        <td><?php 
                                                echo $p->fullname;
                                        ?></td> 
                                    </tr>

                                    <tr>

                                        <td>Email</td>
                                        <td>:</td>
                                        <td><?php 
                                                echo $p->email;
                                        ?></td>
                                    </tr>
 
                                </table>
                            </div>
                            <div class="more pure-u-1 padding-t-15">


                                <a href="<?php echo site_url('superadmin/region/detail/'. $p->id); ?>" class="pure-button btn-small btn-white btn-48"> 
                                    VIEW
                                </a>
                                
                                <a class="pure-button btn-small btn-white btn-48 delete-partner" onclick="confirmation('<?php echo site_url('admin/manage_partner/delete_admin/'.@$p->id ); ?>', 'group', 'Delete Partner', 'list-partner', 'delete-partner');">
                                    DELETE
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>  
</div>
<?php echo @$pagination;?>

<script type="text/javascript">
    $(function () {

        var width = $(".list-people .box-info").width();
        var height = $(".list-people .box-info").height();


        $('.list-people-action').css('width', width);
        $('.list-people-action').css('height', height);

        $('.list-people .box-info .detail table td:first-child').css({'width':'20%'});

        $(window).resize(function () {
            var width = $(".list-people .box-info").width();
            var height = $(".list-people .box-info").height();

            $('.list-people-action').css('width', width);
            $('.list-people-action').css('height', height);
        });


        $(document).on('mouseenter', '.list-people', function () {
            $(this).find(".list-people-action").css({'opacity':'1','visibility':'inherit'});
        }).on('mouseleave', '.list-people', function () {
            $(this).find(".list-people-action").css({'opacity':'0','visibility':'collapse'});
        });


        $('.edit-partner').click(function(){
            return false;
        });

        $('.delete-partner').click(function(){
            return false;
        });


    })
</script>