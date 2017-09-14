<div class="heading text-cl-primary padding15">
    <h1 class="margin0">List of Coaches (<?php echo count($data);?>)</h1>
</div>

<div class="box">
    <div class="heading pure-g"> 
        <!-- block edit -->
        <div class="pure-u-1 edit no-left">
<!--            <a href="<?php //echo site_url('partner/session_duration'); ?>" class="add"><i class="icon icon-time"></i>Set Duration</a>-->
            <a href="<?php echo site_url('partner/adding/coach'); ?>" class="add"><i class="icon icon-add"></i>Add New Coach</a>
        </div>
        <!-- end block edit -->
    </div>

    <div class="content">
        <div class="box pure-g list-coach">

            <?php
                if ($data == null) {
//                   echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
                }

            ?>

            <?php $i = 1;
            foreach ($data as $d) {
                ?>

                <div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">

                    <div class="box-info">

                        <div class="image">
                            <a href="<?php echo site_url('partner/member_list/detail/' . @$d->id); ?>"><img src="<?php echo base_url($d->profile_picture); ?>" style="width:125px;height:129px" class="list-cover"></a>
                        </div>
                        <div class="detail">
                            <a href="<?php echo site_url('partner/member_list/detail/' . @$d->id); ?>" class="name"><?php echo $d->fullname; ?></a>

                            <table class="margint25">
                                <tr>
                                    <td>Gender</td>
                                    <td>:</td>
                                    <td><?php echo $d->gender; ?></td>
                                </tr>
                                <tr>
                                    <td>Country</td>
                                    <td>:</td>
                                    <td><?php echo $d->country; ?></td>
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td>:</td>
                                    <td><?php echo $d->phone; ?></td>
                                </tr>
                            </table>
                        </div>

                        <div class="more pure-u-1 padding-t-15">
                            <a href="<?php echo site_url('partner/member_list/detail/' . @$d->id); ?>" class="pure-button btn-small btn-white btn-48 ">
                                VIEW
                            </a>
                            <a onclick="confirmation('<?php echo site_url('partner/member_list/delete_coach/' . @$d->id); ?>', 'group', 'Delete Coach', 'list-coach', 'delete-coach');" class="pure-button btn-small btn-white delete-coach btn-48 ">
                                DELETE
                            </a>
                        </div>
                    </div>


                </div>
<?php } ?>
        </div>
    </div>		
</div>

<?php echo @$pagination; ?>

<script type="text/javascript">
    $(function () {

        $('.delete-coach').click(function(){
            return false;
        })

        var width = $(".list-people .box-info").width();
        var height = $(".list-people .box-info").height();


        $('.list-people-action').css('width', width);
        $('.list-people-action').css('height', height);

        $(window).resize(function () {
            var width = $(".list-people .box-info").width();
            var height = $(".list-people .box-info").height();

            $('.list-people-action').css('width', width);
            $('.list-people-action').css('height', height);
        });


        $(document).on('mouseenter', '.list-people', function () {
            $(this).find(".list-people-action").css({'opacity': '1', 'visibility': 'inherit'});
        }).on('mouseleave', '.list-people', function () {
            $(this).find(".list-people-action").css({'opacity': '0', 'visibility': 'collapse'});
        });

    })
</script>
