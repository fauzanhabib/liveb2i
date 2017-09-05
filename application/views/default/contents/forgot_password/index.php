<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	<title><?php echo $this->template->title; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/pure/pure.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/pure/grids-responsive.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/icon-font/front/styles.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/parsleyjs/parsley.min.js"></script>
	<script src="//use.typekit.net/koh8puv.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>
	   <script>
            var satu = '/*';
            var dua = '*/';
            document.body.innerHTML = document.body.innerHTML.replace(satu, ' ');
            document.body.innerHTML = document.body.innerHTML.replace(dua, ' ');
        </script>
</head>
<body class="backlogin">
	<div id="site-wrapper">
	
		<div id="site-canvas">
			<div id="site-menu">
	       		<div class="pure-menu">
				    <ul class="pure-menu-list menu-left">
				    	<li class="pure-menu-item"><a href="<?php echo site_url(); ?>" class="pure-menu-link">Home</a></li>
	                    <li class="pure-menu-item"><a href="<?php echo site_url('about'); ?>" class="pure-menu-link">About Us</a></li>
						<li class="pure-menu-item"><a href="<?php echo site_url('contact_us'); ?>" class="pure-menu-link">Contact Us</a></li>
						<li class="pure-menu-item"><a href="<?php echo site_url('login'); ?>" class="pure-menu-link">Sign In</a></li>
				    </ul>
				</div>
	     	</div>

			<header id="header">
				<div class="container pure-g">
					<div class="pure-u-1-5">
						<a href="<?php echo site_url(); ?>">
							<img src="<?php echo base_url(); ?>assets/images/logo.png" class="logo">
						</a>
					</div>
					<div class="pure-u-4-5 menu">
						<nav id="menu">
							<ul>
								<li><a href="<?php echo site_url('about'); ?>">About Us</a></li>
								<li><a href="<?php echo site_url('contact_us'); ?>">Contact Us</a></li>
								<li class="btn-menu"><a href="<?php echo site_url('login'); ?>">Sign In</a></li>
							</ul>
						</nav>
					</div>
					<div class="pure-u-4-5 menu-mobile">
						<a href="#" class="toggle-nav">
							<i class="icon icon-menu"></i>
						</a>
					</div>
				</div>
			</header>

			<section id="login-page">
				<div class="content">
					<p class="txt-h1">
						<span class="txt-primary">Forgot your password ?</span>
					</p>
					<div class="bg-frame">
						<?php
                            echo form_open('forgot_password/send_password', 'role="form" class="pure-form" data-parsley-validate');
                        ?>
            			<?php
					
							echo @$this->template->partial->widget('messages_widget_login', '', true);
			              	echo @$content;
			            ?>
							<label class="lbl">Insert your email address</label>
						 	<div class="frm-icon" style="margin-bottom:10px">
								<input type="email" name="email" id="email" placeholder="E-mail Address" required data-parsley-required-message="Please input your e-mail address" data-parsley-type-message="Invalid e-mail address" style="margin-bottom:0">
								<span class="icon icon-mail">
							</div>
							<button type="submit" class="btn-primary pure-button" value="SUBMIT" name="__submit">SUBMIT</button>
				        <?php echo form_close(); ?>					
				    </div>
				</div>
			</section>

			<footer>
				<div class="footer">
					<div class="container pure-g">
						<div class="pure-u-3-5 copyright">
							<p>DynEd Live &copy; 2015. DynEd International, Inc. All rights reserved</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>		
</body>
<script type="text/javascript">
	$(function () {
		$('.toggle-nav').click(function () {
	    	toggleNav();
		});

		$('.lbl').css({'color':'#939393','margin-bottom':'15px','display':'block','font-size':'14px'})
	});
	function toggleNav() {
		if ($('#site-wrapper').hasClass('show-nav')) {
			$('#site-wrapper').removeClass('show-nav');
			$('.menu-mobile .icon.icon-close').removeClass('icon icon-close').addClass('icon icon-menu');
		} else {
			$('#site-wrapper').addClass('show-nav');
			$('.menu-mobile .icon.icon-menu').removeClass('icon icon-menu').addClass('icon icon-close');
		}
	}
</script>
</html>