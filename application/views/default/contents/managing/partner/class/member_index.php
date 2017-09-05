<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Member of Class <?php echo @$title; ?></h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-12-24">
            <div class="h3 font-normal text-cl-secondary" style="padding: 10px 15px;">
                <!--				<form class="pure-form search-b-2" method="POST" action="site.php?role=student&page=student-book-by-name">
                                                        <input class="search-input" type="text" placeholder="Search by class name" style="font-size:14px;">
                                                        <button class="pure-button search-button"></button>
                                                </form> -->
            </div>
        </div>
        <!-- block edit -->
        <div class="pure-u-12-24 edit no-left">
            <a href="<?php echo site_url('student_partner/managing/add_class_member/' . @$class_id); ?>" class="add"><i class="icon icon-add"></i> Add Member</a>
        </div>
        <!-- end block edit -->
    </div>

    <div class="content">
        <div class="box">
            <div class="pure-g remove-member-class">
                <?php
                foreach (@$members as $m) {
                    ?>
                    <div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">

                        <div class="box-info">

                            <div class="image">
                                <img src="<?php echo base_url().$m->profile_picture; ?>" style="width:125px; height:129px" class="list-cover">
                            </div>
                            <div class="detail">
                                <span class="name"><?php echo $m->fullname; ?></span>

                                <table class="margint25">
                                    <tr>
                                        <td>Gender</td>
                                        <td>:</td>
                                        <td><?php echo $m->gender; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>:</td>
                                        <td><?php echo $m->phone; ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="more pure-u-1 padding-t-15">
                                <a class="pure-button btn-small btn-white remove-member" style="width:100%">
                                    <div onclick="confirmation('<?php echo site_url('student_partner/managing/delete_class_member/' . @$class_id . '/' . @$m->id); ?>', 'group', 'Remove this Class Member', 'remove-member-class', 'remove-member');">REMOVE FROM CLASS</div>
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