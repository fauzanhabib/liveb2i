<?php if($this->auth_manager->role() == 'RAD') {
    $role_link = "superadmin";

} else {
    $role_link = "admin";
    $id = @$data->id;
}

?>

<div class="heading text-cl-primary padding0">



    <h1 class="margin0 padding-l-30 left">Add Token &nbsp;</h1>



<div class="box clear-both">

    <div class="content">
        <div class="pure-g">
        <?php echo form_open_multipart($link, 'role="form" class="pure-form pure-form-aligned" style="width:100%" data-parsley-validate'); ?>           
            <div class="pure-u-15-24 profile-detail prelative padding-t-10 padding-l-25">
                <table class="table-no-border2 add-form"> 
                    <tbody>
                        <?php if($role_link != "superadmin") { ?>
                        <tr>
                            <td class="pad15">My Token's</td>

                            <td class="add-form-noborder">
                                <input type="text" class="width50perc bg-white-fff padding2 border-1-ccc padding3" style="border:none" required data-parsley-required-message="Token" value="<?php echo $token;?>" disabled>
                            </td>
                        </tr>
                        <?php } ?>
                         <tr>
                            <td class="pad15">Token</td>

                            <td class="add-form-noborder">
                                <input type="text" name="token" class="width50perc bg-white-fff padding2 border-1-ccc padding3" style="border:none" required data-parsley-required-message="Token">
                            </td>
                        </tr>

                    </tbody>    
                </table>

                <div class="save-cancel-btn text-left padding-t-40">
                    <button type="submit" name="__submit" value="SUBMIT" class="pure-button btn-blue btn-small">Save</button>

                    <a href="<?php echo $cancel; ?>"><button class="pure-button btn-red btn-small" type="button">Cancel</button></a>
                   
                </div>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>   		

<script>

    $(function(){

        $('input').css({'border':'none'});
        
        parsley_float();

        $('tr').each(function(e){
            var inputs = $(this);

            $('input',inputs).on('blur', function () {
                $('td',inputs).removeClass('inline').addClass('no-inline');
            }).on('focus', function () {
                $('td',inputs).removeClass('no-inline').addClass('inline');
            });

            $('td',inputs).css({'position':'relative'});

        })



    })
</script>   