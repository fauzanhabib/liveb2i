<?php
    $role = array(
        'PRT' => 'Coach Partner',
        'SPR' => 'Student Partner',
    );
?>
<div class="heading text-cl-primary padding15">
    <h1 class="margin0"><?php echo @$partner->name?>-Company Administrators</h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <!-- block edit -->
        <div class="pure-u-1 edit no-left">
            <a href="<?php echo site_url('admin_m/manage_partner/add_partner_member'); ?>" class="add"><i class="icon icon-add"></i>Add Admin</a>
        </div>
        <!-- end block edit -->
    </div>

    <div class="content">
        <div class="box">
            <div class="pure-g list-partner-member">
                <?php
                foreach (@$users as $user) {
                    ?>
                    <div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">

                        <div class="box-info">

                            <div class="list-people-action text-center">
                                <a class="pure-button btn-small btn-transparent m-t-50 partner-member" onclick="confirmation('<?php echo site_url('admin_m/manage_partner/delete_partner_member/' . $user->id); ?>', 'group', 'Delete Partner Member', 'list-partner-member', 'partner-member');">DELETE
                                </a>
                            </div>

                            <div class="image">
                                <img src="<?php echo base_url() . '/' . $user->profile_picture; ?>" class="list-cover">
                            </div>
                            <div class="detail">
                                <span class="name"><?php echo $user->fullname; ?></span>

                                <table class="margint25">
                                    <tr>
                                        <td>Role</td>
                                        <td>:</td>
                                        <td><?php echo $role[$user->role];?></td>
                                    </tr>
                                    <tr>
                                        <td>Gender</td>
                                        <td>:</td>
                                        <td><?php echo $user->gender; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Birthdate</td>
                                        <td>:</td>
                                        <td><?php echo date('d-M-Y',strtotime($user->date_of_birth)); ?></td>
                                    </tr>
                                </table>
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

        $('.partner-member').click(function(){
            return false;
        });



    })
</script>