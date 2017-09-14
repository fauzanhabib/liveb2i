<style>

    form {
        background: #fff;
        padding: 20px 20px 20px 20px;
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    }

</style>



<div class="row">

    <div class="col-xs-4 col-xs-offset-4" style="text-align: center; padding-top: 50px;">
        <img src="<?php echo base_url(); ?>assets/img/logo.png" alt="DynEd Live"/>
    </div>

    <div class="col-xs-4 col-xs-offset-4" style="text-align: center; padding-top: 0px;">
        <h4 style="color: #4a4f70;">Sign in to continue to DynEd Live</h4>
    </div>

    <div class="col-xs-4 col-xs-offset-4">

        <div id="login-form">

            <?php echo form_open('login'); ?>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox">  Stay signed in
                    </label>
                </div>

                <input type="submit" class="btn btn-success col-xs-12 btn-lg" value="Sign In" name="__submit"/>

                <div style="margin-top: 10px; text-align: center;">
                    <?php echo anchor('reset', 'Forgot your password?'); ?>
                </div>

            <?php echo form_close(); ?>

        </div>

    </div>

    <div class="col-xs-4 col-xs-offset-4" style="text-align: center; padding-top: 10px;">
        <p><?php echo anchor('register', "Create an account"); ?></p>
    </div>

</div>
