<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Region <?php echo $partner[0]->region_name?> Affiliate</h1>
</div>

<div class="box">
   
    <div class="content">
        <div class="box">
            <div class="pure-g list-partner">
                <?php
                foreach ($partner as $p) {
                    ?>
                    <div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">

                        <div class="box-info" style="min-height: 110px;">
                            <div class="image">
<!--                                <a href="<?php echo site_url('admin/manage_partner/detail/'. $p->id); ?>">-->
                                    <img src="<?php echo base_url().'/'.$p->profile_picture;?>" class="list-cover">
<!--                                </a>    -->
                            </div>
                            <div class="detail">
<!--                                <a href="<?php echo site_url('admin/manage_partner/detail/'. $p->id); ?>" class="name">-->
                                    <span class="name"><?php echo $p->name?></span>
<!--                                </a>-->

                                <table class="margint25">
                                    <tr>
                                        <td>Country</td>
                                        <td>:</td>
                                        <td><?php echo $p->country?></td>
                                    </tr>
                                    <tr>
                                        <td>State</td>
                                        <td>:</td>
                                        <td><?php echo $p->state ?></td>
                                    </tr>
                                    <tr>
                                        <td>Type</td>
                                        <td>:</td>
                                        <td><?php echo @$this->common_function->get_partner_type($p->id); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <!-- <div class="more pure-u-1 padding-t-15">
                                <a class="pure-button btn-small btn-white btn-48 edit-partner" onclick="confirmation('<?php //echo site_url('admin/manage_partner/edit_partner/'. $p->id); ?>', 'group', 'Edit Partner', 'list-partner', 'edit-partner');"> 
                                    EDIT
                                </a>
                            </div> -->
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