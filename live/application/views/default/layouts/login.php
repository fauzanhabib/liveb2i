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
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/parsleyjs/parsley.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/FuckAdBlock-master/fuckadblock.js"></script>
	<script src="//use.typekit.net/koh8puv.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>

	<style>
		.alert-login {
			height: 0;
		}
		.alert-login.error {
			z-index: 999;
			position: absolute;
			width: 100%;
			height: 100%;
			color: #f6f6f6;
		    background: rgba(0, 0, 0, 0.8);
		    display: table;
		}
		#alert-login-confirm {
			display: table-cell;
    		vertical-align: middle;
			text-align: center;
		}
		#alert-login-reload {
			display: none;
		}
	</style>
</head>

<body class="backlogin">
	<div class="alert-login">
		<div id="alert-login-confirm">
			<h3 id="alert-login-title"></h3>
			<button id="alert-login-reload" class="btn-primary pure-button">Continue</button>
		</div>
		<!-- pervent adblock -->
		<script>
			function adBlockDetected() {
				$('.alert-login').addClass('error');
				$('#alert-login-title').html('Please disable AdBlock browser extension.<br> Once you have disabled AdBlock browser extension click button below').css("padding", "5px");
				$('#alert-login-reload').show();
			}
			if(typeof fuckAdBlock === 'undefined') {
				adBlockDetected();
			} else {
				fuckAdBlock.onDetected(adBlockDetected);
				fuckAdBlock.onNotDetected(adBlockNotDetected);
				// and|or
				fuckAdBlock.on(true, adBlockDetected);
				fuckAdBlock.on(false, adBlockNotDetected);
				// and|or
				fuckAdBlock.on(true, adBlockDetected).onNotDetected(adBlockNotDetected);
			}
		</script>
		<!-- pervent adblock -->
	</div>

	<div id="site-wrapper">
	
		<div id="site-canvas">
			<div id="site-menu">
     			<div class="pure-menu">
				    <ul class="pure-menu-list menu-left">
				    	<li class="pure-menu-item"><a href="<?php echo site_url(); ?>" class="pure-menu-link">Home</a></li>
	            		<li class="pure-menu-item"><a href="<?php echo site_url('about'); ?>" class="pure-menu-link">About Us</a></li>
						<li class="pure-menu-item"><a href="<?php echo site_url('contact_us'); ?>" class="pure-menu-link">Contact Us</a></li>
						<li class="pure-menu-item pure-menu-selected"><a href="<?php echo site_url('login'); ?>" class="pure-menu-link">Sign In</a></li>
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
						<span class="txt-primary">Sign in to your DynEd Live account</span>
					</p>
					<div class="bg-frame">
						<?php
					
							echo @$this->template->partial->widget('messages_widget_login', '', true);
			              	echo @$content;
			            ?>
						<form id="login-form" class="pure-form" action="login" method="POST">
						 	<div class="frm-icon" style="margin-bottom:10px">
								<input type="email" placeholder="E-mail Address" name="email" data-parsley-trigger="change" required data-parsley-required-message="Please input your e-mail address" data-parsley-type-message="Invalid e-mail address" style="margin:0">
								<span class="icon icon-mail">
							</div>
							<div class="frm-icon" style="margin-bottom:15px">
								<input type="password" placeholder="Password" name="password" data-parsley-trigger="change" required data-parsley-required-message="Please input your password" style="margin:0">
								<span class="icon icon-lock">
							</div>

							<div class="frm-icon" style="margin-bottom:15px">
								<input type="hidden" name="min_raw" id="min_raw" style="margin:0">
								
							</div>
							<button type="submit" class="btn-primary pure-button" value="Sign In" name="__submit">SIGN IN</button>
              				<span><a href="<?php echo site_url('forgot_password');?>" class="forgot">Forgot Password ?</a></span>
				    	</form>
					</div>
				</div>
			</section>
			<footer>
				<div class="footer">
					<div class="container pure-g">
						<div class="pure-u-3-5 copyright">
							<p>DynEd Live &copy; <?php echo date('Y');?>. DynEd International, Inc. All rights reserved</p>
							<p style="right: 15px;top: 0px;position: absolute;">Version 1.0</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>		
	<script>
		$('#alert-login-reload').click(function() {
		    location.reload();
		});
	</script>
	<script type="text/javascript">
	function toggleNav() {
		if ($('#site-wrapper').hasClass('show-nav')) {
			$('#site-wrapper').removeClass('show-nav');
			$('.menu-mobile .icon.icon-close').removeClass('icon icon-close').addClass('icon icon-menu');
		} else {
			$('#site-wrapper').addClass('show-nav');
			$('.menu-mobile .icon.icon-menu').removeClass('icon icon-menu').addClass('icon icon-close');
		}
	}
	$(function () {

		$('#login-form').parsley();

		$('.toggle-nav').click(function () {
	    	toggleNav();
		});

		// if($('body').width() >=768){
		// 	if ($('body').height() >=720) {
		// 		/*$('.footer').css({'position':'absolute'});*/
		// 		$('.footer').css({'background':'#000'});
		// 	}
		// 	else{
				
		// 	}
			
		// }
		// else {
		// 	$('.footer').css({'background':'#000'});
		// }

		if($('body').width() >= 699) {
			if($('body').height() <= 393) {
				$('.content').css({'position':'relative','padding-top':'200px'});
				$('.footer').css({'position':'relative'});
			}
		}

	});
	</script>

        <script type="text/javascript">
            var d = new Date()
            var n = d.getTimezoneOffset();

            $('#min_raw').val(n);
        </script>

</body>
</html>