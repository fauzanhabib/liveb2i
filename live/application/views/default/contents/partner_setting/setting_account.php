<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Settings</h1>
</div>

<div class="box">
<div class="heading pure-g">
    <div class="pure-u-1 tab-list tab-link">
        <ul>
            <li class="current"><a href="#" class="active">Account Info</a></li>
            <li><a href="#">Session Duration</a></li>
            <li><a href="#">Affiliate Setting</a></li>
        </ul>
    </div>
</div>
<div class="content tab-content padding0">
    <div class="box">
    <div class="heading pure-g">
        <div class="pure-u-12-24">
            <h3 class="h3 font-normal padding15 text-cl-secondary">ACCOUNT INFO</h3>
        </div>
        <div class="pure-u-12-24">
            <div class="edit action-icon">

                <button0 class="pure-button btn-tiny btn-white-tertinary m-b-10 save_click_1">SAVE</button0>
                <i class="icon icon-close close_click_1" title="Cancel"></i>
                <i class="icon icon-edit edit_click_1" title="Edit"></i>

            </div>
        </div>
    </div>

    <div class="content">
        <div class="pure-u-24-24 prelative">
            <table class="table-no-border2"> 
                <tbody>
                    <tr>
                        <td class="pad15">Email</td>

                        <td>
                            <span>dynedadm1@gmail.com</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="pad15">Old Password</td>

                        <td>
                            <span class="r-only-1">•••••••</span>
                            <input name="old_password" type="password" id="td_value_1_1" class="e-only-1" style="display: none;">
                        </td>
                    </tr>
                    <tr>
                        <td class="pad15">New Password</td>

                        <td>
                            <span class="r-only-1">•••••••</span>
                            <input name="new_password" type="password" id="td_value_1_2" class="e-only-1" style="display: none;">
                        </td>
                    </tr>
                    <tr>
                        <td class="pad15">Confirm Password</td>

                        <td>
                            <span class="r-only-1">•••••••</span>
                            <input name="confirm_password" type="password" id="td_value_1_3" class="e-only-1" style="display: none;">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>  
    </div>      
</div>
</div>
<script type="text/javascript">
    $(function(){

        $('.e-only-1').hide();
        $('.close_click_1').hide();
        $('.save_click_1').hide();

        $('.edit_click_1').click(function () {
            $('.e-only-1').show();
            $('.r-only-1').hide();
            $('.close_click_1').show();
            $('.save_click_1').show();
            $('.edit_click_1').hide();
           // $("#td_value_1_1").focus();
            animationClick('.close_click_1', 'fadeIn');
            animationClick('.save_click_1', 'fadeIn');
        });

        $('.close_click_1').click(function () {
            $('.close_click_1').hide();
            $('.save_click_1').hide();
            $('.edit_click_1').show();
            $('.r-only-1').show();
            $('.e-only-1').hide();
            animationClick('.edit_click_1', 'fadeIn');
        });

        $('.save_click_1').click(function () {
            $('.save_click_1').text('SAVING...');
            $('.close_click_1').hide();
            $("#load_1").show().delay(3000).queue(function (next) {
                $(this).hide();
                $('.save_click_1').text('SAVE');
                $('.save_click_1').hide();
                $('.edit_click_1').show();
                $('.r-only-1').show();
                $('.e-only-1').hide();
                next();
            });
        });

        $('tr').each(function(e){
            var inputs = $(this);

            $('input',inputs).on('blur', function () {
                $('td',inputs).removeClass('inline').addClass('no-inline');
            }).on('focus', function () {
                $('td',inputs).removeClass('no-inline').addClass('inline');
            });
        })
        

    })
</script>