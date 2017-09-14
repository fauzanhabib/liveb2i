<div class="heading text-cl-primary padding15">
    <h1 class="margin0">List Of Admin Affiliate</h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <!-- block edit -->
        <div class="pure-u-1 edit no-left">
            <a href="<?php echo site_url('superadmin/manage_partner/add_partner_member');?>" class="add"><i class="icon icon-add"></i>Add Admin Affiliate</a>
            <!-- <a href="<?php echo site_url('admin/partner_setting');?>" class="add"><i class="icon icon-setting"></i>Partner Setting</a> -->
        </div>
        <!-- end block edit -->
    </div>

    <div class="heading pure-g frm-s">
            <!-- block edit -->
            <div class="pure-u-23-24 tl tab-list tab-link">
                <ul>
                    <li><a href="<?php echo site_url('student/find_coaches/search/partner'); ?>">Affiliate</a></li>
                    <li><a href="<?php echo site_url('student/find_coaches/search/region'); ?>">Region</a></li>
                </ul>
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
                                <a href="<?php echo site_url('admin/manage_partner/detail/'. $p->id); ?>">
                                    <img src="<?php echo base_url().'/'.$p->profile_picture;?>" class="list-cover">
                                </a>    
                            </div>
                            <div class="detail">
                                <a class="name"><?php echo $p->fullname?></a>

                                <table class="margint25">
                                    <tr>
                                        <td>Partner</td>
                                        <td>:</td>
                                        <td><?php 
                                                echo $p->name_partner;
                                        ?></td>
                                    </tr>

                                    <tr>
                                        <td>Phone</td>
                                        <td>:</td>
                                        <td><?php 
                                                echo $p->phone;
                                        ?></td>
                                    </tr> 

                                    <tr>
                                        <td>Skype</td>
                                        <td>:</td>
                                        <td><?php 
                                                echo $p->skype_id;
                                        ?></td>
                                    </tr> 

                                    <tr>
                                        <td>Region</td>
                                        <td>:</td>
                                        <td><?php 
                                            foreach ($group_region as $gr) {
                                                    if($p->admin_regional_id == $gr->user_id){
                                                        echo $gr->region_name;
                                                    }
                                                }
                                        ?></td>
                                    </tr>
 
                                </table>
                            </div>
                            <div class="more pure-u-1 padding-t-15">


                                <a href="<?php echo site_url('superadmin/manage_coach_partner/edit_coach_partner/'. $p->id); ?>" class="pure-button btn-small btn-white btn-48"> 
                                    EDIT
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