<?php if($this->auth_manager->role() == 'RAD') {
    $role_link = "superadmin";
} else {
    $role_link = "admin";
}

?>


<?php
    $role = array(
        'PRT' => 'Coach Partner',
        'SPR' => 'Student Partner',
    );
?>
<div class="heading text-cl-primary padding15">
    <h1 class="margin0"><?php echo @$partner->name.'\'s Admin';?></h1>
</div>


<div class="box">
    <div class="heading">
        <div class="pure-u-1 edit no-left">
            <a href="<?php echo site_url($role_link.'/manage_partner/add_partner_member/'. @$partner->id); ?>" class="add"><i class="icon icon-add"></i>Add Admin</a>
        </div>
    </div>

    <?php 
    if ($users == null) {
        echo '<div class="padding15"><div class="no-result">No Data</div></div>';
    }
    ?>

    <?php foreach (@$users as $user){?>
    <div class="content" style="border-bottom:1px solid #f3f3f3">
        <div class="pure-g">
            <div class="pure-u-8-24 profile-image text-center divider-right">
                <div class="thumb-small">
                    <img src="<?php echo base_url($user->profile_picture); ?>" class="pure-img fit-cover preview-image m-b-10">
                    <span class="font-14"><?php echo strtok(strtoupper(@$user->fullname), " ");?></span><br>
                    <span class="text-cl-secondary font-12"><?php echo strtoupper($role[$user->role]);?></span>
                </div>
                <div class="more pure-u-1 padding-t-10 list-partner">
                    <a class="pure-button btn-small btn-white btn-small delete-partner" onclick="confirmation('<?php echo site_url('admin/manage_partner_admin/delete/'.@$user->id); ?>', 'group', 'Delete Partner', 'list-partner', 'delete-partner');">
                        DELETE
                    </a>
                </div>
            </div>

            <div class="pure-u-16-24 profile-detail prelative">

                <form action="<?php echo site_url("admin/manage_partner_admin/update/".@$partner->id."/".@$user->id); ?>" method="POST" id="form-admin" data-parsley-validate>
                
                    <div class="heading m-b-15">
                        <div class="pure-u-12-24">
                            <h3 class="h3 font-normal text-cl-secondary">BASIC INFO</h3>
                        </div>
                        <div class="pure-u-12-24" style="width:48%">
                            <div class="edit action-icon margin0">
                                <button id="btn_save_info" name="__submit" type="submit" class="pure-button btn-tiny btn-white-tertinary m-b-15 save_click asd">SAVE</button>
                                <i class="icon icon-close close_click" title="Cancel"></i>
                                <i class="icon icon-edit edit_click" title="Edit"></i>
                            </div>
                        </div>
                    </div>
                    
                    <table class="table-no-border2">                        
                        <tbody>
                            <tr>
                                <td class="pad15">Email</td>
                                <td>
                                    <span><?php echo @$user->email; ?></span>
                                </td>
                            </tr>
                            <td class="pad15">Birthdate</td>
                                <td>
                                    <span class="r-only"><?php echo @$user->date_of_birth; ?></span>
                                    <input name="date_of_birth" type="text" value="<?php echo @$user->date_of_birth; ?>" class="e-only datepicker" required readonly>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="pad15">Phone</td>
                                <td>
                                    <span class="r-only"><?php echo @$user->phone; ?></span>
                                    <input name="phone" type="text" value="<?php echo @$user->phone; ?>" class="e-only" data-parsley-type="digits" required data-parsley-required-message="Please input affiliate`s admin phone number" data-parsley-type-message="Please input numbers only">
                                </td>
                            </tr>
                            <tr>
                                <td class="pad15">Skype ID</td>
                                <td>
                                    <span class="r-only"><?php echo @$user->skype_id; ?></span>
                                    <input name="skype_id" type="text" value="<?php echo @$user->skype_id; ?>" class="e-only" required data-parsley-required-message="Please input affiliate`s admin Skype-ID">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<script type="text/javascript">
    $('.edit-partner').click(function(){
        return false;
    });

    $('.delete-partner').click(function(){
        return false;
    });

    $('.e-only').hide();
    $('.close_click').hide();
    $('.save_click').hide();

    $(function(){
        parsley_float();

        $(".datepicker").datepicker({
            format: 'yyyy-mm-dd',
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(1990, 00, 01)
        })

    })

    $('.content').each(function(){

        var _each = $(this);


        $('.edit_click', _each).click(function () {
            $('.e-only', _each).show();
            $('.r-only', _each).hide();
            $('.close_click', _each).show();
            $('.save_click', _each).show();
            $('.edit_click', _each).hide();

            $('.e-only').not($('.e-only', _each)).hide();
            $('.r-only').not($('.r-only', _each)).show();
            $('.close_click').not($('.close_click', _each)).hide();
            $('.save_click').not($('.save_click', _each)).hide();
            $('.edit_click').not($('.edit_click', _each)).show();
           
            _close = $('.close_click', _each);
            _save = $('.save_click', _each);
            animationClick(_close, 'fadeIn');
            animationClick(_save, 'fadeIn');
            $('#form-admin').parsley().reset();
        });

        $('.close_click', _each).click(function () {
            $('.close_click', _each).hide();
            $('.save_click', _each).hide();
            $('.edit_click', _each).show();
            $('.r-only', _each).show();
            $('.e-only', _each).hide();
            _edit = $('.edit_click', _each);
            animationClick(_edit, 'fadeIn');
            $('#form-admin').parsley().reset();
        });

        $('.save_click', _each).click(function () {
           
            if($('#form-admin').parsley().isValid()) {
                $('.close_click', _each).hide();
                $('.save_click', _each).text('SAVING...');
            }

            $("#load", _each).show().delay(3000).queue(function (next) {
                $(this).hide();
                $('.save_click', _each).text('SAVE');
                $('.save_click', _each).hide();
                $('.edit_click', _each).show();
                $('.r-only', _each).show();
                $('.e-only', _each).hide();
                next();
                
            });
        });

    });

    $('tr').each(function(e){
        var inputs = $(this);

        $('input',inputs).on('blur', function () {
            $('td',inputs).removeClass('inline').addClass('no-inline');
        }).on('focus', function () {
            $('td',inputs).removeClass('no-inline').addClass('inline');
        });

        $('textarea',inputs).on('blur', function () {
            $('td',inputs).removeClass('inline').addClass('no-inline');
        }).on('focus', function () {
            $('td',inputs).removeClass('no-inline').addClass('inline');
        });

        $('td',inputs).css({'position':'relative'});
    });
    
    $('.e-only').bind('keypress', function (e) {
        var code = e.keyCode || e.which;
        if (code === 13) {
            $('#form-admin').submit();
        }
    });

    $('#btn_save_info').click(function () {
        $('#form-admin').submit();
    });
</script>
