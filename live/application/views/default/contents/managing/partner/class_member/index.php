<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Add Member of Class <?php echo @$title; ?></h1>
</div>

<div class="box">
    <div class="heading pure-g">

    </div>

    <div class="content">
        <div class="box">
            <div class="pure-g manage-class-member">
                <?php
                foreach (@$class_members as $c) {
                    ?>
                    <div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">

                        <div class="box-info">

                            <div class="image">
                                <img src="<?php echo base_url().$c->profile_picture; ?>" style="width:125px; height:129px" class="list-cover">
                            </div>
                            <div class="detail">
                                <span class="name"><?php echo $c->fullname; ?></span>

                                <table class="margint25">
                                    <tr>
                                        <td>Gender</td>
                                        <td>:</td>
                                        <td><?php echo $c->gender; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>:</td>
                                        <td><?php echo $c->phone; ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="more pure-u-1 padding-t-15">
                                <a class="pure-button btn-small btn-white add-to-session" style="width:100%">
                                    <div onclick="confirmation('<?php echo site_url('student_partner/managing/create_class_member/' . @$class_id . '/' . @$c->id); ?>', 'group', '<?php echo('Add as ' . @$title . ' Class Member') ?>', 'manage-class-member', 'add-to-session');">ADD TO CLASS</div>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>		
</div>

<script type="text/javascript">
    $(function () {

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
            $(this).find(".list-people-action").css({'opacity':'1','visibility':'inherit'});
        }).on('mouseleave', '.list-people', function () {
            $(this).find(".list-people-action").css({'opacity':'0','visibility':'collapse'});
        });



    })
</script>