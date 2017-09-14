<div class="heading text-cl-primary padding15">
    <h1 class="margin0">List Of Admins</h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <!-- block edit -->
        <div class="pure-u-1 edit no-left">
            <a href="<?php echo site_url('superadmin/manage_region/add_region');?>" class="add"><i class="icon icon-add"></i>Add Region</a>
            <!-- <a href="<?php echo site_url('admin/partner_setting');?>" class="add"><i class="icon icon-setting"></i>Partner Setting</a> -->
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

                            <div class="detail">
                                <table class="margint25">
                                    <tr>
                                        <td>Region</td>
                                        <td>:</td>
                                        <td><?php echo $p->name?></td>
                                    </tr>
                                    <!-- <tr>
                                        <td>State</td>
                                        <td>:</td>
                                        <td><?php echo $p->state ?></td>
                                    </tr> -->
<!--                                     <tr>
                                        <td>Type</td>
                                        <td>:</td>
                                        <td><?php echo @$this->common_function->get_partner_type($p->id); ?></td>
                                    </tr> -->
                                </table>
                            </div>
                            <div class="more pure-u-1 padding-t-15">
                                <!-- <a class="pure-button btn-small btn-white btn-48 edit-partner" onclick="confirmation('<?php //echo site_url('admin/manage_partner/edit_partner/'. $p->id); ?>', 'group', 'Edit Partner', 'list-partner', 'edit-partner');"> 
                                    EDIT
                                </a> -->

                                <a href="<?php echo site_url('superadmin/manage_admin/detail/'. $p->id); ?>" class="pure-button btn-small btn-white btn-48"> 
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