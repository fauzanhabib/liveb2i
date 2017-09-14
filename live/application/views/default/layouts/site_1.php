<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Document</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/pure/pure.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/pure/grids-responsive.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/dashboard.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.css">

        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.js"></script>

    </head>
    <body>

        <div id="site-wrapper" class="container">
            <div id="site-canvas">

                <!-- menu for smartphone & tablet -->
                <div id="site-menu">
                    <div class="pure-menu">
                        <ul class="pure-menu-list">
                            <?php if ($this->auth_manager->role() == 'STD') { ?>
                                <li class="pure-menu-heading">Menu</li>
                                <li class="pure-menu-item pure-menu-selected"><a href="#" class="pure-menu-link menu-profile">Profile</a></li>
                                <li class="pure-menu-item"><a href="<?php echo site_url('student/find_coaches/single_date'); ?>" class="pure-menu-link menu-book">Book A Coach</a></li>
                                <li class="pure-menu-item pure-menu-selected"><a href="#" class="pure-menu-link menu-study">Study Dashboard</a></li>
                                <li class="pure-menu-item"><a href="<?php echo site_url('student/ongoing_session'); ?>" class="pure-menu-link menu-study">Session</a></li>
                                <li class="pure-menu-heading">OTHER APPS</li>
                                <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-skype">Skypes</a></li>
                                <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-dyned-pro">DynEd PRO</a></li>
                            <?php } else if ($this->auth_manager->role() == 'CCH') { ?>
                                <li class="pure-menu-heading">Menu</li>
                                <li class="pure-menu-item pure-menu-selected"><a href="#" class="pure-menu-link menu-profile">Profile</a></li>
                                <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-shedule">Coach Shedule</a></li>
                                <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-student-list">Student List</a></li>
                                <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-progress-report">Student Progress Report</a></li>
                                <li class="pure-menu-heading">OTHER APPS</li>
                                <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-skype">Skype</a></li>
                                <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-dyned-pro">DynEd PRO</a></li>
                            <?php } else if ($this->auth_manager->role() == 'PRT') { ?>
                                <li class="pure-menu-heading">Menu</li>
                                <li class="pure-menu-item pure-menu-selected"><a href="#" class="pure-menu-link menu-profile">Profile</a></li>
                                <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-create-session">Create Session</a></li>
                                <li id="ooo" class="pure-menu-item dropdown">
                                    <a href="#" class="pure-menu-link menu-add-coach" data-toggle="dropdown">Member</a>
                                    <ul class="menu-dropdown">
                                        <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-create-session">Coach List</a></li>
                                        <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-create-session">Student List</a></li>
                                    </ul>
                                </li>
                                <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-create-session">Approve Day Off</a></li>
                            <?php } else if ($this->auth_manager->role() == 'SPR') { ?>
                                <li>On progress</li>
                            <?php } else if ($this->auth_manager->role() == 'ADM') { ?>
                                <li class="pure-menu-heading">Menu</li>
                                <li class="pure-menu-item pure-menu-selected"><a href="#" class="pure-menu-link menu-profile">Profile</a></li>
                                <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-coach-list">Coach List</a></li>
                                <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-student-list">Student List</a></li>
                                <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-coach-history">Coach Session History</a></li>
                                <li class="pure-menu-heading">OTHER APPS</li>
                                <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-skype">Skype</a></li>
                                <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-dyned-pro">DynEd PRO</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

                <!-- end menu -->


                <!-- header for desktop-->
                <header id="header">
                    <div class="pure-g large">
                        <div class="pure-u-4-24 logo text-center">
                            <img src="<?php echo base_url(); ?>assets/images/logo.png">
                        </div>
                        <div class="pure-u-15-24 search" style="width: 65%;">
                        </div>
                        <div class="pure-u-1-24 setting-notification">
                            <a href="<?php echo site_url('account/notification'); ?>"><div class="prelative">
                                <div class="notif"></div>
                                <?php if(@$this->auth_manager->new_notification()){ ?>
                                <span class="label-notif"><?php echo $this->auth_manager->new_notification(); ?></span>
                                <?php } ?>
                            </div></a>
                        </div>
                        <div class="pure-u-4-24 account" style="width: 14%;">

                            <div class="profile-pic">
                                <div class="img" style="">
                                    <img height="36" width="36" src="<?php echo base_url(); ?>images/reid-avatar.png">
                                </div>
                                <div class="detail">
                                    <span class="name">King Eric</span>
                                    <span class="status">Sudents</span>
                                </div>
                            </div>

                            <div class="narrow">
                                <img src="<?php echo base_url(); ?>assets/icon/dropdown-btn.png" class="narrow-click">
                            </div>
                            <div class="dropdown-menu-box" style="display:none">
                                <div class="dropdown-header">
                                    <div class="list-menu-dropdown">
                                        <ul>
                                            <li><a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="profile">Edit Profile</a></li>
                                            <li><a href="" class="setting">Setting</a></li>
                                            <li><a href="<?php echo site_url('logout'); ?>" class="logout">Logout</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- end header -->

                <!-- header for tablet -->
                <header id="medium-header">
                    <div class="pure-g medium">
                        <div class="pure-u-2-24 menu-mobile text-center prelative toggle-nav">
                            <a href="#">
                                <i class="menu-icon"></i>
                            </a>
                        </div>
                        <div class="pure-u-5-24 logo text-center">
                            <img src="<?php echo base_url(); ?>assets/images/logo.png">
                        </div>

                        <div class="pure-u-9-24 search text-right" style="width: 39.8%;">

                        </div>

                        <div class="pure-u-2-24 setting-notification" style="  width: 6%;">
                            <div class="prelative">
                                <div class="notif"></div>
                                <span class="label-notif">2</span>
                            </div>
                        </div>
                        <div class="pure-u-6-24 account">

                            <div class="profile-pic">
                                <div class="img">
                                    <img height="36" width="36" src="<?php echo base_url(); ?>images/reid-avatar.png">
                                </div>
                                <div class="detail">
                                    <span class="name">King Eric</span>
                                    <span class="status">Sudents</span>
                                </div>
                            </div>

                            <div class="narrow">
                                <img src="<?php echo base_url(); ?>assets/icon/dropdown-btn.png" class="narrow-click">
                            </div>
                            <div class="dropdown-menu-box" style="display:none">
                                <div class="dropdown-header">
                                    <div class="list-menu-dropdown">
                                        <ul>
                                            <li><a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="profile">Edit Profile</a></li>
                                            <li><a href="" class="setting">Setting</a></li>
                                            <li><a href="" class="logout">Logout</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- end header -->

                <!-- header for smartphone -->
                <header id="small-header">
                    <div class="pure-g">
                        <div class="pure-u-3-24 menu-mobile text-center">
                            <a href="#" class="toggle-nav">
                                <i class="menu-icon"></i>
                            </a>
                        </div>
                        <div class="pure-u-18-24 logo text-center">
                            <img src="<?php echo base_url(); ?>assets/images/logo.png">
                        </div>

                        <div class="pure-u-3-24 setting-notification">
                            <div class="prelative">
                                <div class="notif"></div>
                                <span class="label-notif">2</span>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- end header -->

                <!-- start for content -->
                <section id="content">
                    <div class="pure-g">
                        <!-- sidebar menu-->
                        <div class="pure-u-4-24 menu-left">

                            <div class="pure-menu">
                                <ul class="pure-menu-list">
                                    <li class="pure-menu-heading">Menu</li>
                                    <li class="pure-menu-item pure-menu-selected"><a href="#" class="pure-menu-link menu-profile">Profile</a></li>
                                    <li class="pure-menu-item"><a href="<?php echo site_url('student/find_coaches/single_date'); ?>" class="pure-menu-link menu-book">Book A Coach</a></li>
                                    <li class="pure-menu-item"><a href="" class="pure-menu-link menu-study">Study Dashboard</a></li>
                                    <li class="pure-menu-item"><a href="<?php echo site_url('student/ongoing_session'); ?>" class="pure-menu-link menu-study">Session</a></li>
                                    <li class="pure-menu-heading">OTHER APPS</li>
                                    <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-skype">Skypes</a></li>
                                    <li class="pure-menu-item"><a href="#" class="pure-menu-link menu-dyned-pro">DynEd PRO</a></li>
                                </ul>
                            </div>

                        </div>
                        <!-- end menu-->

                        <!-- content-->
                        <div class="pure-u-lg-20-24 pure-u-md-24-24 pure-u-sm-24-24 content-center" >
                            
                            <?php 
                            echo $this->template->partial->widget('messages_widget', '', true);
                            echo $content; 
                            ?>

                        </div>
                </section>
            </div>
        </div>
        <script type="text/javascript">
            $(function () {
                $('.toggle-nav').click(function () {
                    toggleNav();
                });
            })

            $('.thumb-small').hover(function () {
                $(this).find('.caption').css('opacity', '1');
            }, function () {
                $(this).find('.caption').css('opacity', '0');
            });

            $('.thumb-large').hover(function () {
                $(this).find('.caption').css('opacity', '1');
            }, function () {
                $(this).find('.caption').css('opacity', '0');
            });


            $('.menu-mobile').hover(function () {
                $(this).find('.menu-icon').css('background', 'url(assets/icon/menu-strip-white.png)');
            }, function () {
                $(this).find('.menu-icon').css('background', 'url(assets/icon/menu-strip.png)');
            });

            function toggleNav() {
                if ($('#site-wrapper').hasClass('show-nav')) {
                    $('#site-wrapper').removeClass('show-nav');
                } else {
                    $('#site-wrapper').addClass('show-nav');
                }
            }
            $('.narrow-click').click(function () {
                $(".dropdown-menu-box").toggle();
            });

            var dropdown = document.querySelectorAll('.dropdown');
            var dropdownArray = Array.prototype.slice.call(dropdown, 0);
            dropdownArray.forEach(function (el) {
                var button = el.querySelector('a[data-toggle="dropdown"]'),
                        menu = el.querySelector('.menu-dropdown');


                $(button).click(function (e) {


                    if ($('.menu-dropdown').hasClass("show")) {
                        menu.classList.add('hide');
                        menu.classList.remove('show');
                        $('#ooo').removeClass('pure-menu-selected');
                    }
                    else {
                        menu.classList.add('show');
                        menu.classList.remove('hide');
                        $('#ooo').addClass('pure-menu-selected');
                    }

                });
            });
        </script>
    </body>
</html>