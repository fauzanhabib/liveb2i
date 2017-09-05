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
	<script src="//use.typekit.net/koh8puv.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>
</head>
<body class="no-background ">
	<div id="site-wrapper">
	
		<div id="site-canvas">
			<div id="site-menu">
	      <div class="pure-menu">
			    <ul class="pure-menu-list menu-left">
			    	<li class="pure-menu-item"><a href="<?php echo site_url(); ?>" class="pure-menu-link">Home</a></li>
            <li class="pure-menu-item pure-menu-selected"><a href="<?php echo site_url('about'); ?>" class="pure-menu-link">About Us</a></li>
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
								<li><a href="<?php echo site_url('about'); ?>" class="active">About Us</a></li>
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
                    

			<section id="page-content">
				<div class="content">
					<h2 class="title"><span style="color:#585858">About Us</span></h2>
					<p><b>DynEd International, Inc.</b>, has the world's most comprehensive lineup of award-winning English Language Teaching (ELT/ESL) solutions.</p>
					<p>DynEd's courses cover all proficiency levels and include a range of age-appropriate courses, from kids in school to adults in university, corporate, aviation or other vocational settings. DynEd courses have been approved by Ministries of Education in several countries.</p>
					<p>With over 13 million active users, DynEd courses are designed to be used in a blended learning environment, along with teachers and classroom support.</p>
					<p>DynEd's headquarters and development center is in Burlingame, California – overlooking the San Francisco Bay. The company has sales and support offices around the world and additional development centers in Beijing and Jakarta.</p>
					<p>For a representative in your area, or to become a partner, please <a href="mailto:info@dyned.com">contact us</a>.</p>
				</div>
			</section>

			<footer>
				<div class="footer w-map">
					<div class="pure-g">
						<div class="pure-u-1 pure-u-sm-8-24 pure-u-md-8-24 info-blue">
							<div>
								<h3>Need Some Help?</h3>
								<span><a href="<?php echo site_url('contact_us'); ?>">Get In Touch</a></span>
							</div>
						</div>
						<div class="pure-u-1 pure-u-sm-8-24 pure-u-md-8-24 info-blue-dark">
							<div></div>
						</div>
						<div class="pure-u-1 pure-u-sm-8-24 pure-u-md-8-24 info-dyned">
							<div>
								<h3>DynEd</h3>
								International<br>
								Inc.
							</div>
						</div>
					</div>
					<div class="container pure-g">
						<div class="pure-u-3-5 copyright" style="width:100%">
							<p>DynEd Live &copy; <?php echo date('Y');?>. DynEd International, Inc. All rights reserved</p>
							<p style="right: 15px;top: 180px;position: absolute;">Version 1.0</p>
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
		if($('#page-content').width() >=900){
			$('.footer').css({'position':'relative'});
		}
	});
	</script>
</body>
</html>