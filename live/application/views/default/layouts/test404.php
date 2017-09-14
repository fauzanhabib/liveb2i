<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	<title><?php echo $this->template->title; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/pure/pure.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/pure/grids-responsive.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
	<script src="//use.typekit.net/koh8puv.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>
</head>
<body class="back404">
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
							<i class="menu-icon"></i>
						</a>
					</div>
				</div>
			</header>
                    

			<section id="page-404">
				<div class="content">
					<div class="box404">
						<span>404</span>
					</div>

					<p class="txt-h1" style="display: inline-block;margin-left: 10px;">
						<span class="txt-primary">Sorry, but nothing exist here.</span>
					</p>
				</div>
		</div>
	</div>		
</body>
<script type="text/javascript">
$(function () {
	$('.toggle-nav').click(function () {
    	toggleNav();
	});

	bgSize();

	$( window ).resize(function() {
	  bgSize();
	});



});

function bgSize() {
	if($(document).width() <= 1024) {
		$('.back404').css({'background-size':'contain'});
	}
	else{
		$('.back404').css({'background-size':'cover'});
	}
}


function toggleNav() {
	if ($('#site-wrapper').hasClass('show-nav')) {
		$('#site-wrapper').removeClass('show-nav');
	} else {
		$('#site-wrapper').addClass('show-nav');
	}
}
</script>
</html>