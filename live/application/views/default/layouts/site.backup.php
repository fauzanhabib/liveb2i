<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="favicon.ico">

        <title> <?php echo $this->template->title->append(' - DynEd Live'); ?></title>

        <!-- Stylesheet -->
        <?php echo $this->template->stylesheet; ?>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="/resources/demos/style.css">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/css/imgareaselect-default.css">

        <!-- Javascript -->
        <?php echo $this->template->javascript; ?>
        
        <script type="text/javascript" src="http://www.skypeassets.com/i/scom/js/skype-uri.js"></script>
        <script type="text/javascript" src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.imgareaselect.pack.js"></script>
    </head>

    <body>

        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="#" class="navbar-brand">DynEd Live</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><?php echo anchor('account/identity', 'Home'); ?></li>
                        <li><?php echo anchor('account/notification', (@$this->auth_manager->new_notification() > 0 ? ('<span style="color:#AFA;">' . @$this->auth_manager->new_notification() . ' New ') : '') . 'Notifications'); ?></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Contact</a></li>
                        <?php if (!$this->auth_manager->userid()) { ?>
                            <li><?php echo anchor('login', 'Sign In'); ?></li>
                        <?php } else { ?>
                            <li><?php echo anchor('logout', 'Sign Out'); ?></li>
                        <?php } ?>
                    </ul>
                </div>

            </div>
        </nav>
        <?php
        echo $this->template->partial->widget('messages_widget', '', true);
        ?>
        <div class="container">
            <?php echo $content; ?>

        </div>

        <div class="footer">
            <div class="container">
                <p class="text-muted">

                    Copyright &copy; 2014 DynEd International Inc,.

                </p>
            </div>
        </div>
    </body>
</html>
