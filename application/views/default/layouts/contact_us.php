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
</head>
<body class="no-background ">
	<div id="site-wrapper">
	
		<div id="site-canvas">
			<div id="site-menu">
		      	<div class="pure-menu">
				    <ul class="pure-menu-list menu-left">
				    	<li class="pure-menu-item"><a href="<?php echo site_url(); ?>" class="pure-menu-link">Home</a></li>
	            		<li class="pure-menu-item"><a href="<?php echo site_url('about'); ?>" class="pure-menu-link">About Us</a></li>
						<li class="pure-menu-item pure-menu-selected"><a href="<?php echo site_url('contact_us'); ?>" class="pure-menu-link">Contact Us</a></li>
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
								<li><a href="<?php echo site_url('contact_us'); ?>" class="active">Contact Us</a></li>
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
					<h2 class="title"><span style="color:#585858">Contact Us</span></h2>
					<form class="pure-form pure-form-stacked" data-parsley-validate>
					    <fieldset>
					        <legend>Do you have a question? Fill in the form below and we will get back to you within 24 hours:</legend>
					        <div class="pure-g">
					            <div class="pure-u-1 pure-u-md-12-24">
					            	<div class="p10">
				                		<input class="pure-u-1" placeholder="Name" type="text" required data-parsley-required-message="Please input your name"	>
				                	</div>
					            </div>

					            <div class="pure-u-1 pure-u-md-12-24">
					            	<div class="p10">
				                		<input class="pure-u-1" placeholder="Email" type="email" required data-parsley-required-message="Please input your e-mail address" data-parsley-type-message="Invalid e-mail address">
				                	</div>
					            </div>

					            <div class="pure-u-1 pure-u-md-12-24">
					            	<div class="p10">
				                		<input class="pure-u-1" placeholder="Country" type="text" required data-parsley-required-message="Please input your Country">
				                	</div>
					            </div>

					            <div class="pure-u-1 pure-u-md-12-24">
					            	<div class="p10">
				                		<input class="pure-u-1" placeholder="City" type="text" required data-parsley-required-message="Please input your City">
				                	</div>	
					            </div>

					            <div class="pure-u-1">
					            	<div class="p10">
				                		<textarea class="pure-u-1" placeholder="Messages" style="height: 150px;resize: none;" required data-parsley-required-message="Please input your message"></textarea>
				                	</div>
					            </div>
					        </div>
							<div class="text-right p10">
					        	<button type="submit" class="pure-button btn-primary">SEND</button>
					        </div>
					    </fieldset>
					</form>
				</div>
				
			</section>

			<footer>
				<div class="footer w-map">
					<div class="pure-g">
						<div class="pure-u-1 pure-u-sm-8-24 pure-u-md-8-24 info-blue">
							<div>
								<h3>DynEd International, Inc.</h3>
								1350 Bayshore Highway, Suite 850<br>
								Burlingame, CA 94010<br>
								USA<br>
								Phone: +1-650-375-7011
							</div>
						</div>
						<div class="pure-u-1 pure-u-sm-16-24 pure-u-md-16-24 info-blue-dark">
							<iframe width='100%' height='180px' frameBorder='0' src='https://a.tiles.mapbox.com/v4/djiepanji.n3c1a3hj/attribution,zoompan,zoomwheel,geocoder,share.html?access_token=pk.eyJ1IjoiZGppZXBhbmppIiwiYSI6InNXakYwcUUifQ.f1sLn25sWb-DA3VNMQlKJw'></iframe>
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