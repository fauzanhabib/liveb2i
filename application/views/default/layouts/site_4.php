<?php 
    $role = array(
        'STD' => 'Student',
        'CCH' => 'Coach',
        'PRT' => 'Coach Partner',
        'ADM' => 'Admin',
        'SPR' => 'Student Partner',
    );
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--<meta http-equiv="refresh" content="5">-->
        <title><?php echo $this->template->title->append(' - DynEd Live') ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/pure/pure.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/pure/grids-responsive.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/dashboard.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/icon-font/styles.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/alertifyjs/css/alertify.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery.tablescroll.css"/>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/bootstrap-timepicker/bootstrap-timepicker.css">
        <script src="<?php echo base_url(); ?>assets/vendor/parsleyjs/parsley.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/alertifyjs/alertify.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap-modal/modal.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap-timepicker/bootstrap-timepicker.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/menu.js"></script>
        <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
        <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
    </head>
    <body>
        <div id="site-wrapper" class="container">
            <div id="site-canvas">

                <!-- menu for smartphone & tablet -->
                <div id="site-menu">
                    <div class="pure-menu">
                        <ul class="nav-dropdown-top account-mobile">
                            <!-- narrow setting 
                            <li class="narrow">
                                <i class="icon icon-arrow-down narrow-click"></i>
                                <div class="dropdown-menu-box" style="display:none">
                                    <div class="dropdown-header">
                                        <div class="list-menu-dropdown">
                                            <ul>
                                                <li><a href="<?php echo site_url('account/identity/detail/profile'); ?>"><i class="icon icon-edit-profile"></i> Edit Profile</a></li>
                                                <li><a href="#"><i class="icon icon-setting"></i> Setting</a></li>
                                                <li><a href="<?php echo site_url('logout'); ?>"><i class="icon icon-logout"></i> Logout</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            -->

                            <!-- account !-->
                            <li class="account">
                                <div class="img">
                                    <img height="36" width="36" src="<?php echo base_url().'/'.($this->auth_manager->get_avatar()); ?>" style="object-fit:cover">
                                </div>
                                <div class="detail">
                                    <span class="name"><?php echo $this->auth_manager->get_name();?></span>
                                    <span class="status"><?php echo($role[$this->auth_manager->role()]);?></span>
                                </div>
                            </li>
                        </ul>
                        <div class="menu-drop">
                            <a href="#"><i class="icon icon-edit-profile"></i></a>
                            <a href="#"><i class="icon icon-setting"></i></a>
                            <a href="#"><i class="icon icon-logout"></i></a>
                        </div>
                        <ul class="pure-menu-list">
                            <?php if ($this->auth_manager->role() == 'STD') { ?>
                                <li class="pure-menu-item">
                                    <a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="pure-menu-link icon icon-user">Profile</a>
                                </li>
                                <li class="pure-menu-item">
                                    <a href="<?php echo site_url('student/find_coaches/single_date'); ?>" class="pure-menu-link icon icon-book-coach">Book A Coach</a>
                                </li>
                                <li class="pure-menu-item">
                                    <a href="<?php echo site_url('student/student_vrm/single_student'); ?>" class="pure-menu-link icon icon-study-dashboard">Study Dashboard</a>
                                </li>
                                <li class="pure-menu-item">
                                    <a href="<?php echo site_url('student/ongoing_session'); ?>" class="pure-menu-link icon icon-session">My Session</a>
                                </li>
                                <li class="pure-menu-item">
                                    <a href="<?php echo site_url('student/token'); ?>" class="pure-menu-link icon icon-token">My Token</a>
                                </li>
                                <li class="pure-menu-item">
                                    <a href="<?php echo site_url('student/rate_coaches'); ?>" class="pure-menu-link icon icon-star-i">Rate Coaches</a>
                                </li>
                                <!--
                                <li class="pure-menu-heading">OTHER APPS</li>
                                <li class="pure-menu-item">
                                    <a href="#" class="pure-menu-link menu-skype">Skype</a>
                                </li>
                                <li class="pure-menu-item">
                                    <a href="<?php //echo site_url('student/vrm'); ?>" class="pure-menu-link menu-dyned-pro">DynEd PRO</a>
                                </li>
                                -->
                            <?php } else if ($this->auth_manager->role() == 'CCH') { ?>

                                <li class="pure-menu-item"><a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="pure-menu-link icon icon-user">Profile</a></li>
                                <li id="dropdown" class="pure-menu-item dropdown">
                                    <a class="pure-menu-link icon icon-schedule" data-toggle="dropdown">Coach Schedule</a>
                                    <ul class="menu-dropdown">
                                        <li class="pure-menu-item"><a href="<?php echo site_url('coach/schedule'); ?>" class="pure-menu-link icon icon-sudent-schedule">My Schedule</a></li>
                                        <li class="pure-menu-item"><a href="<?php echo site_url('coach/ongoing_session'); ?>" class="pure-menu-link icon icon-session">My Session</a></li>
                                    </ul>
                                </li>
                                
                            <?php } else if ($this->auth_manager->role() == 'PRT') { ?>
                                <li class="pure-menu-item"><a href="<?php echo site_url('account/identity/detail/profile');?>" class="pure-menu-link menu-profile icon icon-user">Profile</a></li>
                                <li class="pure-menu-item"><a href="<?php echo site_url('partner/member_list/coach');?>" class="pure-menu-link menu-profile icon icon-member">Coach Member</a></li>
                                <li class="pure-menu-item"><a href="<?php echo site_url('partner/schedule');?>" class="pure-menu-link menu-create-session icon icon-create-session">Create Session</a></li>
                                <li class="pure-menu-item"><a href="<?php echo site_url('partner/approve_coach_day_off');?>" class="pure-menu-link menu-create-session icon icon-approve-dayoff">Coach Day Off</a></li>
                                <li class="pure-menu-item"><a href="<?php echo site_url('partner/webex/login');?>" class="pure-menu-link menu-create-session icon icon-webex">Webex</a></li>
                            <?php } else if ($this->auth_manager->role() == 'SPR') { ?>
                                <li class="pure-menu-item"><a href="<?php echo site_url('account/identity/detail/profile');?>" class="pure-menu-link icon icon-user">Profile</a></li>
                                <li class="pure-menu-item"><a href="<?php echo site_url('student_partner/member_list/student');?>" class="pure-menu-link icon icon-student-list">Student Member</a></li>
                                <li class="pure-menu-item"><a href="<?php echo site_url('student_partner/managing');?>" class="pure-menu-link icon icon-manage-class">Manage Class</a></li>
                                <li class="pure-menu-item"><a href="<?php echo site_url('student_partner/approve_token_requests');?>" class="pure-menu-link menu-create-session icon icon-token">Token Request</a></li>
                            <?php } else if ($this->auth_manager->role() == 'ADM') { ?>
                                <li class="pure-menu-item"><a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="pure-menu-link icon icon-user">Profile</a></li>
                                <li class="pure-menu-item"><a href="<?php echo site_url('admin/manage_partner'); ?>" class="pure-menu-link icon icon-coachpartner-list">Partner</a></li>
                                <li class="pure-menu-item"><a href="<?php echo site_url('admin/approve_user'); ?>" class="pure-menu-link icon icon-approve-dayoff">Approve User</a></li>
                            <?php } ?> 
                        </ul>
                    </div>
                </div>

                <!-- end menu -->

                <!-- header -->
                <header class="top-bar">
                    <div class="pure-g">
                        
                        <!-- mobile menu icon -->
                        <div class="grids toggle-nav menu-mobile">
                            <i class="icon icon-menu"></i>
                        </div>
                        
                        <!-- logo -->
                        <div class="grids logo">
                            <img src="<?php echo base_url(); ?>assets/images/logo.png">
                        </div>
                        <!-- end -->

                        <!-- right header -->
                        <div class="grids right-header">
                            <ul class="nav-dropdown-top">

                                <!-- narrow setting !-->
                                <li class="narrow">
                                    <i class="icon icon-arrow-down narrow-click"></i>
                                    <div class="dropdown-menu-box" style="display:none">
                                        <div class="dropdown-header">
                                            <div class="list-menu-dropdown">
                                                <ul>
                                                    <li><a href="<?php echo site_url('account/identity/detail/profile'); ?>"><i class="icon icon-edit-profile"></i> Edit Profile</a></li>
                                                    <li><a href="#"><i class="icon icon-setting"></i> Setting</a></li>
                                                    <li><a href="<?php echo site_url('logout'); ?>"><i class="icon icon-logout"></i> Logout</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <!-- narrow setting -->

                                <!-- account !-->
                                <li class="account">
                                    <div class="img">
                                        <img height="36" width="36" src="<?php echo base_url().'/'.($this->auth_manager->get_avatar()); ?>" style="object-fit:cover">
                                    </div>
                                    <div class="detail">
                                        <span class="name"><?php echo $this->auth_manager->get_name();?></span>
                                        <span class="status"><?php echo($role[$this->auth_manager->role()]);?></span>
                                    </div>
                                </li>
                                <!-- end account -->

                                <!-- narrow setting !-->
                                <li class="notification">
                                    <a>
                                        <div class="prelative">
                                            <div class="icon icon-notification"></div>
                                            <?php echo ($this->auth_manager->new_notification()['notification'] > 0 ? '<span class="label-notif">'.$this->auth_manager->new_notification()['notification'].'</span>' : '');  ?>
                                        </div>
                                    </a>
                                    <div class="dropdown-notif-box" style="display:none">
                                        <div class="dropdown-notification">

                                            <?php 
                                            //$received_time = $this->auth_manager->new_notification()['received_time'];
                                            foreach($this->auth_manager->new_notification()['data_notification'] as $d){
                                            ?>
                                            <div class="list-notification">
                                                <a href="<?php echo site_url('account/notification'); ?>">
                                                <div class="text"><?php echo($d->description);?></div>
                                                <div class="time"><?php echo($this->auth_manager->new_notification()['received_time'][$d->id]);?></div>
                                                </a>
                                            </div>
                                            <?php } ?> 

                                            <div class="see-all">
                                                <a href="<?php echo site_url('account/notification'); ?>">SEE ALL</a>
                                            </div>  
                                        </div>
                                    </div>
                                </li>
                                <!-- end notification -->

                            </ul>
                        </div>
                        <!-- end right header -->
                    </div>
                </header>
                <!-- end header -->

                <!-- start for content -->
                <section id="content">
                    <div class="pure-g ctn">
                        <!-- sidebar menu-->
                        <div class="pure-u-4-24 menu-left">
                            <div class="pure-menu">
                                <ul class="pure-menu-list">
                                    <?php if ($this->auth_manager->role() == 'STD') { ?>
                                        <li class="pure-menu-item std-profile">
                                            <a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="pure-menu-link icon icon-user">Profile</a>
                                        </li>
                                        <li class="pure-menu-item std-book">
                                            <a href="<?php echo site_url('student/find_coaches/single_date'); ?>" class="pure-menu-link icon icon-book-coach">Book A Coach</a>
                                        </li>
                                        <li class="pure-menu-item std-vrm">
                                            <a href="<?php echo site_url('student/student_vrm/single_student'); ?>" class="pure-menu-link icon icon-study-dashboard">Study Dashboard</a>
                                        </li>
                                        <li class="pure-menu-item std-session">
                                            <a href="<?php echo site_url('student/ongoing_session'); ?>" class="pure-menu-link icon icon-session">My Session</a>
                                        </li>
                                        <li class="pure-menu-item std-token">
                                            <a href="<?php echo site_url('student/token'); ?>" class="pure-menu-link icon icon-token">My Token</a>
                                        </li>
                                        <li class="pure-menu-item std-rate">
                                            <a href="<?php echo site_url('student/rate_coaches'); ?>" class="pure-menu-link icon icon-star-i">Rate Coaches</a>
                                        </li>
                                        <!--
                                        <li class="pure-menu-heading">OTHER APPS</li>
                                        <li class="pure-menu-item">
                                            <a href="#" class="pure-menu-link menu-skype">Skype</a>
                                        </li>
                                        <li class="pure-menu-item">
                                            <a href="<?php //echo site_url('student/vrm'); ?>" class="pure-menu-link menu-dyned-pro">DynEd PRO</a>
                                        </li>
                                        -->
                                    <?php } else if ($this->auth_manager->role() == 'CCH') { ?>

                                        <li class="pure-menu-item"><a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="pure-menu-link icon icon-user">Profile</a></li>
                                        <li id="dropdown" class="pure-menu-item dropdown cch-scd">
                                            <a class="pure-menu-link icon icon-schedule" data-toggle="dropdown">Coach Schedule</a>
                                            <ul class="menu-dropdown">
                                                <li class="pure-menu-item sch"><a href="<?php echo site_url('coach/schedule'); ?>" class="pure-menu-link icon icon-sudent-schedule">My Schedule</a></li>
                                                <li class="pure-menu-item ses"><a href="<?php echo site_url('coach/ongoing_session'); ?>" class="pure-menu-link icon icon-session">My Session</a></li>
                                            </ul>
                                        </li>
                                        
                                    <?php } else if ($this->auth_manager->role() == 'PRT') { ?>
                                        <li class="pure-menu-item"><a href="<?php echo site_url('account/identity/detail/profile');?>" class="pure-menu-link menu-profile icon icon-user">Profile</a></li>
                                        <li class="pure-menu-item"><a href="<?php echo site_url('partner/member_list/coach');?>" class="pure-menu-link menu-profile icon icon-member">Coach Member</a></li>
                                        <li class="pure-menu-item"><a href="<?php echo site_url('partner/schedule');?>" class="pure-menu-link menu-create-session icon icon-create-session">Create Session</a></li>
                                        <li class="pure-menu-item"><a href="<?php echo site_url('partner/approve_coach_day_off');?>" class="pure-menu-link menu-create-session icon icon-approve-dayoff">Coach Day Off</a></li>
                                        <li class="pure-menu-item"><a href="<?php echo site_url('partner/webex/login');?>" class="pure-menu-link menu-create-session icon icon-webex">Webex</a></li>
                                    <?php } else if ($this->auth_manager->role() == 'SPR') { ?>
                                        <li class="pure-menu-item"><a href="<?php echo site_url('account/identity/detail/profile');?>" class="pure-menu-link icon icon-user">Profile</a></li>
                                        <li class="pure-menu-item"><a href="<?php echo site_url('student_partner/member_list/student');?>" class="pure-menu-link icon icon-student-list">Student Member</a></li>
                                        <li class="pure-menu-item"><a href="<?php echo site_url('student_partner/managing');?>" class="pure-menu-link icon icon-manage-class">Manage Class</a></li>
                                        <li class="pure-menu-item"><a href="<?php echo site_url('student_partner/approve_token_requests');?>" class="pure-menu-link menu-create-session icon icon-token">Token Request</a></li>
                                    <?php } else if ($this->auth_manager->role() == 'ADM') { ?>
                                        <li class="pure-menu-item"><a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="pure-menu-link icon icon-user">Profile</a></li>
                                        <li class="pure-menu-item"><a href="<?php echo site_url('admin/manage_partner'); ?>" class="pure-menu-link icon icon-coachpartner-list">Partner</a></li>
                                        <li class="pure-menu-item"><a href="<?php echo site_url('admin/approve_user'); ?>" class="pure-menu-link icon icon-approve-dayoff">Approve User</a></li>
                                    <?php } ?> 
                                </ul>
                            </div>
                        </div>
                        <!-- end menu-->

                        <!-- content-->
                        <div class="pure-u-lg-20-24 pure-u-md-24-24 pure-u-sm-24-24 content-center" >

                            <?php
//                            $message = $this->template->partial->widget('messages_widget', '', true);
//                            echo "<script type='text/javascript'>alert('$message');</script>";
                            echo $this->template->partial->widget('messages_widget', '', true);
                            echo $content;
                            ?>

                        </div>
                </section>
            </div>
        </div>
        <script type="text/javascript">

            function tinynav(con) {

                //$("<div />").addClass('frm-select').appendTo(con);
                //$("#foo").append("<div>hello world</div>")

                //$("<div /><select />").appendTo(con);

                var html =  '<div class="frm-select">' + 
                            '<select>' +
                            '</select>' +   
                            '</div>';
                $(con).append(html);


                $(".tab-link a").each(function() {

                var el = $(this);
                
                $("<option />", {
                   "value"   : el.attr("href"),
                   "text"    : el.text(),
                }).appendTo(con+" select");

                });
              
                $(con+" select").change(function() {
                    window.location = $(this).find("option:selected").val();
                });
            }

            $(function(){
                tinynav(".tab-link");
            });

            //small menu
            function toggleNav() {
                if ($('#site-wrapper').hasClass('show-nav')) {
                    $('#site-wrapper').removeClass('show-nav');
                     $('.menu-mobile .icon.icon-close').removeClass('icon icon-close').addClass('icon icon-menu');

                } else {
                    $('#site-wrapper').addClass('show-nav');
                    $('.menu-mobile .icon.icon-menu').removeClass('icon icon-menu').addClass('icon icon-close');
                }
            }

            $(function() {
                $('.toggle-nav').click(function () {
                    toggleNav();
                });
            });

            //thumbnail for profile
            $('.thumb-small').hover(function(){
                $(this).find('.caption').css('opacity','1');
            }, function(){
                $(this).find('.caption').css('opacity','0');
            });

            $('.thumb-large').hover(function(){
                $(this).find('.caption').css('opacity','1');
            }, function(){
                $(this).find('.caption').css('opacity','0');
            });


            //menu mobile hover
           /**
            $('.menu-mobile').hover(function(){
                $(this).find('.menu-icon').css('background','url(<?php echo base_url() ?>/assets/icon/menu-strip-white.png)');
            }, function(){
                $(this).find('.menu-icon').css('background','url(<?php echo base_url() ?>/assets/icon/menu-strip.png)');
            });**/

            //narrow setting
            $('.narrow').click(function(e){
                e.stopPropagation();
        
                $('.dropdown-menu-box').show();

                if($('.dropdown-menu-box').hasClass("selected")) {
                    $('.dropdown-menu-box').removeClass('selected');
                    $('.dropdown-menu-box').hide();
                }
                else{
                    $('.dropdown-menu-box').addClass('selected');
                }

            });


            //notification
            $('.notification').click(function(e){
                e.stopPropagation();
                
                $('.dropdown-notif-box').show();
                

                if($('.dropdown-notif-box').hasClass("selected")) {
                    $('.dropdown-notif-box').removeClass('selected');
                    $('.dropdown-notif-box').hide();
                    $('.label-notif').show();
                }
                else{
                    $('.dropdown-notif-box').addClass('selected');
                    $('.label-notif').hide();
                }
            });

            $(document).click(function(e) {
                var target = e.target;
                if (!$(target).is('.nav-dropdown-top li.notification') && !$(target).parents().is('.nav-dropdown-top li.notification')) {
                    $('.dropdown-notif-box').hide();
                    $('.label-notif').show();
                    $('.dropdown-notif-box').removeClass('selected');
                }
                
                if (!$(target).is('.narrow') && !$(target).parents().is('.narrow')) {
                    $('.dropdown-menu-box').hide();
                    $('.dropdown-menu-box').removeClass('selected');
                }
            });

            //cart
            $('.cart').click(function(){
                $( ".dropdown-cart-box" ).fadeToggle(300);
            });

            $('html').click(function(){
                //$('.dropdown-notif-box').hide();
            });

            $('.dropdown-notif-box').click(function(){
                //return false;
            });
            
//            $('.see-all').click(function(){
//                return true;
//            });

            // menu dropdown
            var dropdown = document.querySelectorAll('.dropdown');
            var dropdownArray = Array.prototype.slice.call(dropdown, 0);
            dropdownArray.forEach(function (el) {
                var button = el.querySelector('a[data-toggle="dropdown"]'), 
                    menu = el.querySelector('.menu-dropdown');
                

                $(button).click(function(e){
             

                    if($('.menu-dropdown').hasClass("show")) {
                        menu.classList.add('hide');
                        menu.classList.remove('show');
                        $('#dropdown').removeClass('pure-menu-selected');
                    }
                    else {
                        menu.classList.add('show');
                        menu.classList.remove('hide');
                        $('#dropdown').addClass('pure-menu-selected');
                    }
                    
                });
            });
            
            function confirmation(url, type, title, class_group_name, class_name){
                if(type === 'group'){
                    $('.'+class_group_name).each(function () {
                        var dropdown = $(this);
                        $('.'+class_name, dropdown).click(function (event) {
                            event.preventDefault();
                            var href = url;
                            alertify.dialog('confirm').set({
                                'title': title
                            });
                            alertify.confirm("Are you sure?", function (e) {
                                if (e) {
                                    window.location.href = href;
                                }
                            });
                        });
                    });
                }else if(type==='single'){
                    $('.'+class_name).click(function (event) {
                        event.preventDefault();
                        var href = url;
                        alertify.dialog('confirm').set({
                            'title': title
                        });
                        alertify.confirm("Are you sure?", function (e) {
                            if (e) {
                                window.location.href = href;
                            }
                        });
                    });
                }
            }
            
            
            /**
             * Message
             * */
            $('.btn-close').click(function(){
                $(".alert").fadeOut(1000, function(){
                    $(".alert").remove();
                });
                
            });

            $(".alert.success").delay(5000).fadeOut(1000, function() {
               $(".alert").remove();
            });
        </script>
    </body>
</html>