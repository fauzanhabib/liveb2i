<?php //print_r($server_code);exit;?>
<div class="content b-f3-1">
    <div class="pure-g">
        <div class="pure-u-1">
            <div class="sign-in">
                <h3>Connect with your DynEd Pro ID to View Your Study Dashboard</h3>
                <div class="form">
                    <?php
                        echo form_open('student/student_vrm/connect_dyned_pro', 'role="form" class="pure-form" data-parsley-validate');
                    ?>
                        <input type="text" name="dyned_pro_id" placeholder="DynEd Pro ID" class="pure-u-1" required data-parsley-required-message="Please input your DynEd Pro ID">
                        <input type="password" name="password" placeholder="Password" class="pure-u-1 m-t-10" required data-parsley-required-message="Please input your password">
                        <?php 
                        $newoptions = array('' => 'Select Server') + $server_code;
                        echo form_dropdown('server_dyned_pro', $newoptions, @$data[0]->country, ' id="server_dyned_pro" class="pure-u-1 m-t-10" required'); 
                        ?>
                        
                        <input type="submit" name="__submit" value="SUBMIT" class="pure-button btn-primary btn-small btn-expand m-t-10">
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>    
</div>