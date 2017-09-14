<?php if($this->auth_manager->role() == 'RAD') {
    $role_link = "superadmin";

} else {
    $role_link = "admin";
    $id = @$data->id;
}

?>

<div class="heading text-cl-primary padding0">



    <h1 class="margin0 padding-l-30 left">Refund Token &nbsp;</h1>



<div class="box clear-both">

    <div class="content">
        <div class="pure-g">
        <?php echo form_open_multipart($link, 'role="form" class="pure-form pure-form-aligned" style="width:100%" data-parsley-validate'); ?>           
            <div class="pure-u-15-24 profile-detail prelative padding-t-10 padding-l-25">
                <table class="table-no-border2 add-form"> 
                    <tbody>
                         <tr>
                            <?php if($status == 1){ ?>
                            <td class="pad15">Token: <?php echo $token_amount;?></td>
                            <?php } if($status == 0){ ?>
                            <td class="pad15">Can't refund token or token has already refunded</td>
                            <?php } if($status == 3){ ?>
                            <td class="pad15">You don't have token for refund</td>
                            <?php } ?>
                        </tr>

                    </tbody>    
                </table>

                <div class="save-cancel-btn text-left padding-t-40">
                    <?php if($status == 1){ ?>        
                    <button type="submit" name="__submit" value="SUBMIT" class="pure-button btn-blue btn-small">Refund</button>
                    <?php } ?>

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