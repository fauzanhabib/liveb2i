<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	<title>DynEd Live</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/pure/pure.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/pure/grids-responsive.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/icon-font/front/styles.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
	<script src="//use.typekit.net/koh8puv.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>
</head>
<body>
<div id="site-wrapper">
	<div id="site-canvas">

		<div id="site-menu">
       		<div class="pure-menu">
			    <ul class="pure-menu-list menu-left">
			      <li class="pure-menu-item pure-menu-selected"><a href="<?php echo site_url(); ?>" class="pure-menu-link">Home</a></li>
            <li class="pure-menu-item"><a href="<?php echo site_url('about'); ?>" class="pure-menu-link">About Us</a></li>
						<li class="pure-menu-item"><a href="<?php echo site_url('contact_us'); ?>" class="pure-menu-link">Contact Us</a></li>
						<li class="pure-menu-item"><a href="<?php echo site_url('login'); ?>" class="pure-menu-link">Sign In</a></li>
			    </ul>
			</div>
     	</div>
		<div id="test"></div>
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
					<div class="toggle-nav">
						<i class="icon icon-menu"></i>
					</div>
				</div>
			</div>
		</header>

		<section id="welcome-screen" class="text-center">
			<div class="container content">
				<h2>Welcome to DynEd Live.</h2>
			</div>	
		</section>

		<footer>
			<div class="footer">
				<div class="container pure-g">
					<div class="pure-u-3-5 copyright" style="width:100%">
						<p>DynEd Live &copy; <?php echo date('Y');?>. DynEd International, Inc. All rights reserved</p>
						<p style="right: 15px;top: 0px;position: absolute;">Version 1.0</p>
					</div>

					
					
				</div>
			</div>
		</footer>

	</div>
</div>

	<script>
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
		$('.toggle-nav').click(function () {
	    	toggleNav();
		});
	});
	</script>

</body>
</html>