<div class="heading text-cl-primary padding15">
    <h1 class="margin0">List of Students</small></h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-1 edit no-left">
            <a href="<?php echo site_url('student_partner/adding/student');?>" class="add"><i class="icon icon-add"></i> Add New Student</a>
            <a href="<?php echo site_url('student_partner/adding/multiple_student');?>" class="add"><i class="icon icon-add"></i> Add Multiple Students</a>
        </div>
        <!-- end block edit -->
    </div>
    

    <div class="content">
        <div class="box">
            <div class="pure-g list-students">
                <?php $i = 1;
                foreach ($data as $d) { ?>
                    <div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">

                        <div class="box-info">

                            <div class="image">
                                <img src="<?php echo base_url($d->profile_picture); ?>" style="width:125px;height:129px" class="list-cover">
                            </div>
                            <div class="detail">

                                <span class="name"><a href="<?php echo site_url('student_partner/member_list/student_detail/'.$d->id);?>"><?php echo $d->fullname; ?></a></span>
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
                                <a href="<?php echo site_url('student_partner/member_list/student_detail/'.$d->id);?>" class="pure-button btn-small btn-white btn-48">
                                    VIEW
                                </a>
                                <a class="pure-button btn-small btn-white delete-student btn-48" onclick="confirmation('<?php echo site_url('student_partner/member_list/delete_student/' . @$d->id); ?>', 'group', 'Delete Student', 'list-students', 'delete-student');">
                                    DELETE
                                </a>
                            </div>

                        </div>

                    </div>
                <?php } ?>
            </div>
            <?php echo @$pagination;?>
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

        if($(document).width() <=430){
        $('a.add').css({'font-size':'12px'});
        $('.icon-add').css({'font-size':'8px'});
        }
        
        $('.delete-student').click(function(){
            return false;
        })


    })
</script>