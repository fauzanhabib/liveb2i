<style>

    form {
        background: #fff;
        padding: 20px 20px 50px 20px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        border: 1px solid #c1c4d6;
    }

</style>


<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<div class="row">

    <div class="col-xs-4 col-xs-offset-4" style="text-align: center; padding-top: 80px;">
        <h3 style="color: #4a4f70;">Login to DynEd Live</h3>
    </div>

    <div class="col-xs-4 col-xs-offset-4">

        <div id="login-form">

            <form role="form">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Password">
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox"> Remember me
                    </label>
                </div>
                <input type="submit" class="btn btn-success col-xs-12" value="Sign In" />
            </form>

        </div>

    </div>

    <div class="col-xs-4 col-xs-offset-4" style="text-align: center;">
        <p><?php echo anchor('register', "Doesn't have an account?"); ?> | <?php echo anchor('reset', 'Forgot password?'); ?></p>
    </div>

</div>
