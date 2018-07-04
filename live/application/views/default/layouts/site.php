<?php
    $role = array(
        'STD' => 'Student',
        'CCH' => 'Coach',
        'PRT' => 'Coach Affiliate',
        'ADM' => 'Admin Region',
        'SPR' => 'Student Affiliate',
        'RAD' => 'Super Admin',
        'SPN' => 'Student Affiliate Neo',
        'SAM' => 'Student Affiliate Monitor',
    );
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/> <!--320-->
        <title><?php echo $this->template->title->append(' - DynEd Live') ?></title>
        <link rel="icon" href="<?php echo base_url();?>assets/icon/dynedlive-01-01.png" type="image/png" sizes="256x256" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/styles/pure.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/styles/grids-responsive.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/styles/dashboard.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/pace.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/styles/styles.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/styles/bootstrap-datepicker.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/styles/alertify.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/styles/jquery.tablescroll.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/styles/bootstrap-timepicker.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/styles/select2.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/styles/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/styles/icon-font/dashboard/styles.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/styles/remodal-default-theme.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/styles/remodal.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/styles/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/styles/jquery.navgoco.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/styles/icon-font/dashboard/styles.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/styles/dyned-live-icons/styles.css">

        <script src="<?php echo base_url();?>assets/js/jquery.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap-datepicker.js"></script>

        <script src="<?php echo base_url();?>assets/js/parsley.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/alertify.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/modal.js"></script>
        <script src="<?php echo base_url();?>assets/js/bootstrap-timepicker.js"></script>
        <script src="<?php echo base_url();?>assets/js/menu.js"></script>
        <script src="<?php echo base_url();?>assets/js/select2.min.js"></script>

        <link rel="stylesheet" href="<?php echo base_url();?>assets/styles/style-menu.css">
        <script src="<?php echo base_url();?>assets/js/script.js"></script>

        <script src="<?php echo base_url();?>assets/ckeditor/ckeditor.js"></script>

       <script>
            var satu = '/*';
            var dua = '*/';
            document.body.innerHTML = document.body.innerHTML.replace(satu, ' ');
            document.body.innerHTML = document.body.innerHTML.replace(dua, ' ');
        </script>

        <script type="text/javascript">
        (function(d) {
            var tkTimeout=3000;
            if(window.sessionStorage){if(sessionStorage.getItem('useTypekit')==='false'){tkTimeout=0;}}
            var config = {
                kitId: 'koh8puv',
                scriptTimeout: tkTimeout
            },
            h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+"wf-inactive";if(window.sessionStorage){sessionStorage.setItem("useTypekit","false")}},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+="wf-loading";tk.src='//use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
        })(document);
        </script>
    </head>
    <body>
        <div id="site-wrapper" class="container">
            <div id="site-canvas">

                <!-- menu for smartphone & tablet -->
                <div id="site-menu">
                    <div class="pure-menu">
                        <ul class="nav-dropdown-top account-mobile">
                            <!-- account !-->
                            <li class="account clearfix">
                                <div class="img left">
                                    <img height="36" width="36" src="<?php echo base_url().'/'.($this->auth_manager->get_avatar()); ?>" style="object-fit:cover">
                                </div>
                                <div class="detail">
                                    <span class="name"><?php echo $this->auth_manager->get_name();?></span>
                                    <span class="status"><?php echo($role[$this->auth_manager->role()]);?></span>
                                </div>
                            </li>
                        </ul>

                        <div class="menu-drop">
                            <a href="<?php echo site_url('account/identity/detail/profile'); ?>"><i class="icon icon-edit-profile"></i></a>
                            <a href="<?php echo site_url('logout'); ?>"><i class="icon icon-logout"></i></a>
                        </div>
                        <ul class="nav-side padding0">
                            <?php if ($this->auth_manager->role() == 'STD') { ?>
                                <li class="pure-menu-item std-dashboard">
                                    <a href="<?php echo site_url('student/dashboard'); ?>" class="pure-menu-link icon dyned-icon-dashboard">Dashboard</a>
                                </li>

                                <li class="pure-menu-item std-profile">
                                    <a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="pure-menu-link icon dyned-icon-profile"><?php echo $this->auth_manager->lang('lbl_profile');?></a>
                                </li>

                                <li class="pure-menu-item std-book">
                                    <a href="<?php echo site_url('student/find_coaches/single_date'); ?>" class="pure-menu-link icon dyned-icon-request-token">Book a Coach</a>
                                </li>
                                <li class="pure-menu-item std-sdashboard">
                                    <a href="<?php echo site_url('student/student_vrm/single_student'); ?>" class="pure-menu-link icon icon-study-dashboard">Study Dashboard</a>
                                </li>
                                <li class="pure-menu-item std-session">
                                    <a href="<?php echo site_url('student/upcoming_session'); ?>" class="pure-menu-link icon dyned-icon-session">Sessions</a>
                                </li>
                                <!-- <li class="pure-menu-item std-class"><a href="<?php echo site_url('student/class_detail'); ?>" class="pure-menu-link icon icon-class">Classes</a></li> -->
                                <li class="pure-menu-item std-token">
                                    <a href="<?php echo site_url('student/token_requests'); ?>" class="pure-menu-link icon dyned-icon-token">Tokens</a>
                                </li>
                                <li class="pure-menu-item">
                                    <a target="_blank" href="<?php echo site_url('student/simulator'); ?>" class="pure-menu-link icon icon-star-i">Session Simulator</a>
                                </li>
                                <li class="pure-menu-item std-rate">
                                    <!-- <a href="<?php echo site_url('student/rate_coaches'); ?>" class="pure-menu-link icon icon-star-i">Rate a Coach</a> -->
                                </li>

                           <!--      <li class="pure-menu-item std-rate">
                                    <a href="<?php echo site_url('opentok/live'); ?>" class="pure-menu-link icon icon-star-i">Live Session</a>
                                </li> -->

                            <?php } else if ($this->auth_manager->role() == 'CCH') { ?>
                                <li class="pure-menu-item cch-dashboard">
                                    <a href="<?php echo site_url('coach/dashboard'); ?>" class="pure-menu-link icon dyned-icon-dashboard">Dashboard</a>
                                </li>

                                <li class="pure-menu-item std-profile"><a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="pure-menu-link icon dyned-icon-profile"><?php echo $this->auth_manager->lang('lbl_profile');?></a></li>
                                <li id="dropdown" class="pure-menu-item dropdown cch-scd">
                                    <a class="pure-menu-link icon dyned-icon-coach-schedules" data-toggle="dropdown">Coach Schedules
                                        <span></span>
                                    </a>
                                    <ul class="menu-dropdown">
                                        <li class="pure-menu-item cch-sch-ins"><a href="<?php echo site_url('coach/schedule'); ?>" class="pure-menu-link icon dyned-icon-date">Schedules</a></li>
                                        <li class="pure-menu-item cch-ses"><a href="<?php echo site_url('coach/upcoming_session'); ?>" class="pure-menu-link icon dyned-icon-session">Sessions</a></li>
                                    </ul>
                                </li>
                                <li class="pure-menu-item">
                                    <a target="_blank" href="<?php echo site_url('coach/simulator'); ?>" class="pure-menu-link icon icon-star-i">Session Simulator</a>
                                </li>
                                <!-- <li class="pure-menu-item std-profile"><a href="<?php echo site_url('opentok/live'); ?>" class="pure-menu-link icon icon-user">Live Session</a></li> -->

                             <?php } else if ($this->auth_manager->role() == 'PRT') { ?>
                                <li class="pure-menu-item std-profile"><a href="<?php echo site_url('account/identity/detail/profile');?>" class="pure-menu-link menu-profile icon dyned-icon-profile"><?php echo $this->auth_manager->lang('lbl_profile');?></a></li>
                                <!-- <li class="pure-menu-item prt-mem"><a href="<?php echo site_url('partner/member_list/subgroup');?>" class="pure-menu-link menu-profile icon icon-member">Coaches</a></li> -->
                                <li class="pure-menu-item prt-doff"><a href="<?php echo site_url('partner/approve_coach_day_off');?>" class="pure-menu-link menu-create-session icon dyned-icon-day-off">Day-off Approvals</a></li>
                                <!-- <li class="pure-menu-item prt-webex"><a href="<?php echo site_url('partner/webex/login');?>" class="pure-menu-link menu-create-session icon icon-webex">WebEx</a></li> -->
                                <li class="pure-menu-item prt-cgroup"><a href="<?php echo site_url('partner/subgroup');?>" class="pure-menu-link menu-create-session icon dyned-icon-subgroup">Coach Groups</a></li>
                                <li class="pure-menu-item prt-reporting">
                                    <a href="<?php echo site_url('partner/reporting/'); ?>" class="pure-menu-link icon dyned-icon-coach-approval">Reporting</a>
                                </li>
                            <?php } else if ($this->auth_manager->role() == 'SPR') { ?>
                                <li class="pure-menu-item std-profile"><a href="<?php echo site_url('account/identity/detail/profile');?>" class="pure-menu-link icon dyned-icon-profile"><?php echo $this->auth_manager->lang('lbl_profile');?></a></li>
                                <!-- <li class="pure-menu-item spr-ses"><a href="<?php echo site_url('student_partner/schedule/subgroup');?>" class="pure-menu-link menu-create-session icon icon-create-session">Manage Sessions</a></li> -->
                                <!-- <li class="pure-menu-item spr-manage"><a href="<?php echo site_url('student_partner/managing');?>" class="pure-menu-link icon icon-manage-class">Manage Classes</a></li> -->
                                <li class="pure-menu-item spr-atoken"><a href="<?php echo site_url('student_partner/approve_token_requests');?>" class="pure-menu-link menu-create-session icon dyned-icon-token-approval">Approve Tokens</a></li>
                                <li class="pure-menu-item spr-trequest"><a href="<?php echo site_url('student_partner/request_token');?>" class="pure-menu-link menu-create-session icon dyned-icon-token-request">Token Requests</a></li>
                                <li class="pure-menu-item spr-sgroup"><a href="<?php echo site_url('student_partner/subgroup');?>" class="pure-menu-link menu-create-session icon dyned-icon-subgroup">Student Groups</a></li>
                                <li class="pure-menu-item spr-addtoken"><a href="<?php echo site_url('student_partner/add_token');?>" class="pure-menu-link menu-create-session icon dyned-icon-add">Add Tokens</a></li>
                                <li class="pure-menu-item prt-reporting">
                                    <a href="<?php echo site_url('student_partner/reporting/'); ?>" class="pure-menu-link icon dyned-icon-coach-approval">Reporting</a>
                                </li>
                                <li class="pure-menu-item prt-reporting">
                                    <a href="<?php echo site_url('student_partner/monitor/'); ?>" class="pure-menu-link icon dyned-icon-coach-approval">Monitor Accounts</a>
                                </li>

                            <?php } else if ($this->auth_manager->role() == 'SPN') { ?>
                                <li class="pure-menu-item std-profile"><a href="<?php echo site_url('account/identity/detail/profile');?>" class="pure-menu-link icon dyned-icon-profile"><?php echo $this->auth_manager->lang('lbl_profile');?></a></li>
                                <li class="pure-menu-item spr-sgroup"><a href="<?php echo site_url('student_partner_neo/subgroup');?>" class="pure-menu-link menu-create-session icon dyned-icon-subgroup">Student Groups</a></li>
                                <li class="pure-menu-item prt-reporting">
                                    <a href="<?php echo site_url('student_partner_neo/reporting/'); ?>" class="pure-menu-link icon dyned-icon-coach-approval">Reporting</a>
                                </li>

                            <?php } else if ($this->auth_manager->role() == 'SAM') { ?>
                                <li class="pure-menu-item std-profile"><a href="<?php echo site_url('account/identity/detail/profile');?>" class="pure-menu-link icon dyned-icon-profile"><?php echo $this->auth_manager->lang('lbl_profile');?></a></li>
                                <li class="pure-menu-item spr-sgroup"><a href="<?php echo site_url('student_aff_m/subgroup');?>" class="pure-menu-link menu-create-session icon dyned-icon-subgroup">Student Groups</a></li>
                                <li class="pure-menu-item prt-reporting">
                                    <a href="<?php echo site_url('student_aff_m/reporting/'); ?>" class="pure-menu-link icon dyned-icon-coach-approval">Reporting</a>
                                </li>

                            <?php } else if ($this->auth_manager->role() == 'ADM') { ?>
                                <li class="pure-menu-item std-profile">
                                    <a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="pure-menu-link icon dyned-icon-profile">Profile</a>
                                </li>

                                <li class="pure-menu-item adm-partner">
                                    <a href="<?php echo site_url('admin/manage_partner'); ?>" class="pure-menu-link icon dyned-icon-partner">Affiliate</a>
                                </li>

                                <li class="pure-menu-item adm-pmatches">
                                    <a href="<?php echo site_url('admin/match_partner'); ?>" class="pure-menu-link icon dyned-icon-partners-matching">Affiliate Matches</a>
                                </li>

                                <li class="pure-menu-item adm-capproval">
                                    <a href="<?php echo site_url('admin/approve_user/index/coach'); ?>" class="pure-menu-link icon dyned-icon-coach-approval">Coach Approvals</a>
                                </li>

                                <li id="dropdown" class="pure-menu-item dropdown">
                                    <a class="pure-menu-link icon dyned-icon-token" data-toggle="dropdown">Token
                                        <span></span>
                                    </a>
                                    <ul class="menu-dropdown">
                                        <li class="pure-menu-item adm-rtoken">
                                            <a href="<?php echo site_url('admin/token'); ?>" class="pure-menu-link icon dyned-icon-token-approval">Request Token</a>
                                       </li>
                                        <li class="pure-menu-item adm-tapproval">
                                            <a href="<?php echo site_url('admin/manage_partner/token'); ?>" class="pure-menu-link icon dyned-icon-token-request">Token Approval</a>
                                        </li>
                                    </ul>
                                </li>

                                <li id="dropdown" class="pure-menu-item dropdown">
                                    <a class="pure-menu-link icon icon-study-dashboard" data-toggle="dropdown">Reporting
                                        <span></span>
                                    </a>
                                    <ul class="menu-dropdown">
                                        <li class="pure-menu-item adm-rtoken">
                                            <a href="<?php echo site_url('admin/reporting/coach'); ?>" class="pure-menu-link icon icon-study-dashboard">Coach</a>
                                       </li>
                                        <li class="pure-menu-item adm-tapproval">
                                            <a href="<?php echo site_url('admin/reporting/student'); ?>" class="pure-menu-link icon icon-study-dashboard">Student</a>
                                        </li>
                                    </ul>
                                </li>
                            <?php } else if ($this->auth_manager->role() == 'RAD') { ?>
                                <li class="pure-menu-item std-profile"><a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="pure-menu-link icon dyned-icon-profile"><?php echo $this->auth_manager->lang('lbl_profile');?></a></li>
                                <li class="pure-menu-item rad-region"><a href="<?php echo site_url('superadmin/region/index/active'); ?>" class="pure-menu-link icon dyned-icon-region">Region</a></li>

                                <li id="dropdown" class="pure-menu-item dropdown">
                                    <a class="pure-menu-link icon dyned-icon-partner" data-toggle="dropdown">Affiliate
                                        <span></span>
                                    </a>
                                    <ul class="menu-dropdown">
                                        <li class="pure-menu-item rad-papproval"><a href="<?php echo site_url('superadmin/manage_partner/approve_coach'); ?>" class="pure-menu-link icon dyned-icon-coach-approval">Affiliate Approval</a></li>
                                        <li class="pure-menu-item rad-trapproval"><a href="<?php echo site_url('superadmin/manage_partner/token'); ?>" class="pure-menu-link icon dyned-icon-token-approval">Token Request Approval</a></li>
                                    </ul>
                                </li>

                                <li class="pure-menu-item rad-pmatches"><a href="<?php echo site_url('superadmin/match_partner'); ?>" class="pure-menu-link icon dyned-icon-partners-matching">Affiliate Matches</a></li>

                                <li id="dropdown" class="pure-menu-item dropdown">
                                    <a class="pure-menu-link icon dyned-icon-setting" data-toggle="dropdown">Settings
                                        <span></span>
                                    </a>
                                    <ul class="menu-dropdown">
                                        <li class="pure-menu-item rad-grsetting"><a href="<?php echo site_url('superadmin/settings/region'); ?>" class="pure-menu-link icon dyned-icon-region-setting">Global Region Settings</a></li>
                                        <li class="pure-menu-item rad-gpsetting"><a href="<?php echo site_url('superadmin/settings/partner'); ?>" class="pure-menu-link icon dyned-icon-partner-setting">Global Affiliate Settings</a></li>
                                    </ul>
                                </li>
                                <li class="pure-menu-item rad-cmaterials"><a href="<?php echo site_url('superadmin/coach_script'); ?>" class="pure-menu-link icon icon-study-dashboard">Coach Materials</a></li>
                            <?php } ?>
                        </ul>
                        <p style="left: 10px;bottom: 0px;position: absolute; color:#fff">Version 1.0</p>
                    </div>
                </div>

                <!-- end menu -->

                <!-- header -->
                <header class="top-bar">
                    <!-- ========= -->
                    <div class="alert-timezone">
                        <h5 class="timezone_confirm">
                                <?php
                                    $id = $this->auth_manager->userid();
                                    $this->load->library('common_function');
                                    $minutes = @$this->common_function->get_usertimezone($id);


                                    date_default_timezone_set('UTC');
                                    $timenow     = date('H:i:s');
                                    $default_timenow  = strtotime($timenow);
                                    $usertime_timenow = $default_timenow+(60*$minutes);
                                    @$hour_timenow = date("H:i", $usertime_timenow);
                                    @$m_timenow = date('i',$usertime_timenow);
                                    ?>

                                    <script>
                                        var today = new Date(),
                                        h = today.getHours(),
                                        m = today.getMinutes();


                                        if(h.toString().length < 2){
                                            h = "0"+h;
                                        }

                                        if(m.toString().length < 2){
                                            m = "0"+m;
                                        }
                                         var client_time = h+":"+m;

                                         var m_timenow = "<?php echo $m_timenow;?>";

                                         var selisih_menit = m_timenow-m;

                                         if(selisih_menit < 0){
                                            selisih_menit = m-m_timenow;
                                         }


                                        var user_time = "<?php echo $hour_timenow;?>";
                                        // console.log(client_time+" "+user_time);
                                        if((user_time != client_time) && (selisih_menit > 6)){
                                            $('.alert-timezone').addClass('error');
                                            $('.timezone_confirm').html('Your timezone ('+ user_time +') doesn’t match your system time ('+ client_time +')');
                                        }
                                    </script>

                        </h5>

                    </div>
                    <div class="pure-g">

                        <!-- mobile menu icon -->
                        <div class="grids toggle-nav menu-mobile">
                            <div class="menu-mobile-icon">
                                <i class="icon icon-menu"></i>
                            </div>
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
                                                    <li><a href="<?php echo site_url('logout'); ?>"><i class="icon icon-logout"></i> Sign Out</a></li>
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
                                            <div class="icon icon-notification" onclick="updatenotif()"></div>
                                            <?php echo ($this->auth_manager->new_notification()['notification'] > 0 ? '<span class="label-notif" id="numnotif">'.$this->auth_manager->new_notification()['notification'].'</span>' : '');  ?>
                                        </div>
                                    </a>
                                    <div class="dropdown-notif-box" style="display:none">
                                        <div class="dropdown-notification">

                                            <?php
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
                                <li class="faq">
                                    <a>
                                        <div class="prelative tooltip-bottom" data-tooltip="FAQs coming soon">
                                            <img src="<?php echo base_url(); ?>assets/img/faq.svg">
                                        </div>
                                    </a>
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
                                <ul class="nav-side padding0">
                                    <?php if ($this->auth_manager->role() == 'STD') { ?>
                                        <li class="pure-menu-item std-dashboard">
                                            <a href="<?php echo site_url('student/dashboard'); ?>" class="pure-menu-link icon dyned-icon-dashboard">Dashboard</a>
                                        </li>

                                        <li class="pure-menu-item std-profile">
                                            <a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="pure-menu-link icon dyned-icon-profile"><?php echo $this->auth_manager->lang('lbl_profile');?></a>
                                        </li>

                                        <li class="pure-menu-item std-book">
                                            <a href="<?php echo site_url('student/find_coaches/single_date'); ?>" class="pure-menu-link icon dyned-icon-request-token">Book a Coach</a>
                                        </li>
                                        <li class="pure-menu-item std-sdashboard">
                                            <a href="<?php echo site_url('student/student_vrm/single_student'); ?>" class="pure-menu-link icon icon-study-dashboard">Study Dashboard</a>
                                        </li>
                                        <li class="pure-menu-item std-session">
                                            <a href="<?php echo site_url('student/upcoming_session'); ?>" class="pure-menu-link icon dyned-icon-session">Sessions</a>
                                        </li>
                                        <!-- <li class="pure-menu-item std-class"><a href="<?php echo site_url('student/class_detail'); ?>" class="pure-menu-link icon icon-class">Classes</a></li> -->
                                        <li class="pure-menu-item std-token">
                                            <a href="<?php echo site_url('student/token_requests'); ?>" class="pure-menu-link icon dyned-icon-token">Tokens</a>
                                        </li>
                                        <li class="pure-menu-item">
                                            <a target="_blank" href="<?php echo site_url('student/simulator'); ?>" class="pure-menu-link icon icon-star-i">Session Simulator</a>
                                        </li>
                                        <li class="pure-menu-item std-rate">
                                            <!-- <a href="<?php echo site_url('student/rate_coaches'); ?>" class="pure-menu-link icon icon-star-i">Rate a Coach</a> -->
                                        </li>

                                        <!-- <li class="pure-menu-item std-rate">
                                            <a href="<?php echo site_url('opentok/live'); ?>" class="pure-menu-link icon icon-star-i">Live Session</a>
                                        </li> -->
                                    <?php } else if ($this->auth_manager->role() == 'CCH') { ?>

                                        <li class="pure-menu-item cch-dashboard">
                                            <a href="<?php echo site_url('coach/dashboard'); ?>" class="pure-menu-link icon dyned-icon-dashboard">Dashboard</a>
                                        </li>

                                        <li class="pure-menu-item std-profile"><a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="pure-menu-link icon dyned-icon-profile"><?php echo $this->auth_manager->lang('lbl_profile');?></a></li>
                                        <li id="dropdown" class="pure-menu-item dropdown cch-scd">
                                            <a class="pure-menu-link icon dyned-icon-coach-schedules" data-toggle="dropdown">Coach Schedules
                                                <span></span>
                                            </a>
                                            <ul class="menu-dropdown">
                                                <li class="pure-menu-item cch-sch-ins"><a href="<?php echo site_url('coach/schedule'); ?>" class="pure-menu-link icon dyned-icon-date">Schedules</a></li>
                                                <li class="pure-menu-item cch-ses"><a href="<?php echo site_url('coach/upcoming_session'); ?>" class="pure-menu-link icon dyned-icon-session">Sessions</a></li>
                                            </ul>
                                        </li>
                                        <li class="pure-menu-item">
                                            <a target="_blank" href="<?php echo site_url('coach/simulator'); ?>" class="pure-menu-link icon icon-star-i">Session Simulator</a>
                                        </li>
                                        <!-- <li class="pure-menu-item std-profile"><a href="<?php echo site_url('opentok/live'); ?>" class="pure-menu-link icon icon-user">Live Session</a></li> -->


                                    <?php } else if ($this->auth_manager->role() == 'PRT') { ?>
                                        <li class="pure-menu-item std-profile"><a href="<?php echo site_url('account/identity/detail/profile');?>" class="pure-menu-link menu-profile icon dyned-icon-profile"><?php echo $this->auth_manager->lang('lbl_profile');?></a></li>
                                        <!-- <li class="pure-menu-item prt-mem"><a href="<?php echo site_url('partner/member_list/subgroup');?>" class="pure-menu-link menu-profile icon icon-member">Coaches</a></li> -->
                                        <li class="pure-menu-item prt-doff"><a href="<?php echo site_url('partner/approve_coach_day_off');?>" class="pure-menu-link menu-create-session icon dyned-icon-day-off">Day-off Approvals</a></li>
                                        <!-- <li class="pure-menu-item prt-webex"><a href="<?php echo site_url('partner/webex/login');?>" class="pure-menu-link menu-create-session icon icon-webex">WebEx</a></li> -->
                                        <li class="pure-menu-item prt-cgroup"><a href="<?php echo site_url('partner/subgroup');?>" class="pure-menu-link menu-create-session icon dyned-icon-subgroup">Coach Groups</a></li>
                                        <li class="pure-menu-item prt-reporting">
                                            <a href="<?php echo site_url('partner/reporting/'); ?>" class="pure-menu-link icon dyned-icon-coach-approval">Reporting</a>
                                        </li>
                                    <?php } else if ($this->auth_manager->role() == 'SPR') { ?>
                                        <li class="pure-menu-item std-profile"><a href="<?php echo site_url('account/identity/detail/profile');?>" class="pure-menu-link icon dyned-icon-profile"><?php echo $this->auth_manager->lang('lbl_profile');?></a></li>
                                        <!-- <li class="pure-menu-item spr-ses"><a href="<?php echo site_url('student_partner/schedule/subgroup');?>" class="pure-menu-link menu-create-session icon icon-create-session">Manage Sessions</a></li> -->
                                        <!-- <li class="pure-menu-item spr-manage"><a href="<?php echo site_url('student_partner/managing');?>" class="pure-menu-link icon icon-manage-class">Manage Classes</a></li> -->
                                        <li class="pure-menu-item spr-atoken"><a href="<?php echo site_url('student_partner/approve_token_requests');?>" class="pure-menu-link menu-create-session icon dyned-icon-token-approval">Approve Tokens</a></li>
                                        <li class="pure-menu-item spr-trequest"><a href="<?php echo site_url('student_partner/request_token');?>" class="pure-menu-link menu-create-session icon dyned-icon-token-request">Token Requests</a></li>
                                        <li class="pure-menu-item spr-sgroup"><a href="<?php echo site_url('student_partner/subgroup');?>" class="pure-menu-link menu-create-session icon dyned-icon-subgroup">Student Groups</a></li>
                                        <li class="pure-menu-item spr-addtoken"><a href="<?php echo site_url('student_partner/add_token');?>" class="pure-menu-link menu-create-session icon dyned-icon-add">Add Tokens</a></li>
                                        <li class="pure-menu-item prt-reporting"><a href="<?php echo site_url('student_partner/reporting/'); ?>" class="pure-menu-link icon dyned-icon-coach-approval">Reporting</a></li>
                                        <li class="pure-menu-item prt-reporting">
                                            <a href="<?php echo site_url('student_partner/monitor/'); ?>" class="pure-menu-link icon dyned-icon-coach-approval">Monitor Accounts</a>
                                        </li>
                                    <?php } else if ($this->auth_manager->role() == 'SPN') { ?>
                                        <li class="pure-menu-item std-profile"><a href="<?php echo site_url('account/identity/detail/profile');?>" class="pure-menu-link icon dyned-icon-profile"><?php echo $this->auth_manager->lang('lbl_profile');?></a></li>
                                        <li class="pure-menu-item spr-sgroup"><a href="<?php echo site_url('student_partner_neo/subgroup');?>" class="pure-menu-link menu-create-session icon dyned-icon-subgroup">Student Groups</a></li>
                                        <li class="pure-menu-item prt-reporting">
                                            <a href="<?php echo site_url('student_partner_neo/reporting/'); ?>" class="pure-menu-link icon dyned-icon-coach-approval">Reporting</a>
                                        </li>


                                    <?php } else if ($this->auth_manager->role() == 'SAM') { ?>
                                        <li class="pure-menu-item std-profile"><a href="<?php echo site_url('account/identity/detail/profile');?>" class="pure-menu-link icon dyned-icon-profile"><?php echo $this->auth_manager->lang('lbl_profile');?></a></li>
                                        <li class="pure-menu-item spr-sgroup"><a href="<?php echo site_url('student_aff_m/subgroup');?>" class="pure-menu-link menu-create-session icon dyned-icon-subgroup">Student Groups</a></li>
                                        <li class="pure-menu-item prt-reporting">
                                            <a href="<?php echo site_url('student_aff_m/reporting/'); ?>" class="pure-menu-link icon dyned-icon-coach-approval">Reporting</a>
                                        </li>
                                    <?php } else if ($this->auth_manager->role() == 'ADM') { ?>
                                       <!--  <li class="pure-menu-item std-profile"><a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="pure-menu-link icon icon-user"><?php echo $this->auth_manager->lang('lbl_profile');?></a></li>
                                        <li class="pure-menu-item adm-partner"><a href="<?php echo site_url('admin/manage_partner'); ?>" class="pure-menu-link icon icon-coachpartner-list">Partners</a></li>
                                        <li class="pure-menu-item adm-matching"><a href="<?php echo site_url('admin/match_partner'); ?>" class="pure-menu-link icon icon-matching">Partner Matchings</a></li>
                                        <li class="pure-menu-item adm-approve"><a href="<?php echo site_url('admin/approve_user/index/coach'); ?>" class="pure-menu-link icon icon-approve-dayoff">Coach Approvals</a></li>
                                        <li class="pure-menu-item adm-approve"><a href="<?php echo site_url('admin/token'); ?>" class="pure-menu-link icon icon-approve-dayoff">Token</a></li>
                                        <li class="pure-menu-item adm-approve"><a href="<?php echo site_url('admin/manage_partner/token'); ?>" class="pure-menu-link icon icon-approve-dayoff">Token Request(SS)</a></li>
                                        -->

                                        <li class="pure-menu-item std-profile">
                                            <a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="pure-menu-link icon dyned-icon-profile">Profile</a>
                                        </li>

                                        <li class="pure-menu-item adm-partner">
                                            <a href="<?php echo site_url('admin/manage_partner'); ?>" class="pure-menu-link icon dyned-icon-partner">Affiliate</a>
                                        </li>

                                        <li class="pure-menu-item adm-pmatches">
                                            <a href="<?php echo site_url('admin/match_partner'); ?>" class="pure-menu-link icon dyned-icon-partners-matching">Affiliate Matches</a>
                                        </li>

                                        <li class="pure-menu-item adm-capproval">
                                            <a href="<?php echo site_url('admin/approve_user/index/coach'); ?>" class="pure-menu-link icon dyned-icon-coach-approval">Coach Approvals</a>
                                        </li>

                                        <li id="dropdown" class="pure-menu-item adm-token dropdown">
                                            <a class="pure-menu-link icon dyned-icon-token" data-toggle="dropdown">Token
                                                <span></span>
                                            </a>
                                            <ul class="menu-dropdown">
                                                <li class="pure-menu-item adm-rtoken"><a href="<?php echo site_url('admin/token'); ?>" class="pure-menu-link icon dyned-icon-token-approval">Request Token</a></li>
                                                <li class="pure-menu-item adm-tapproval"><a href="<?php echo site_url('admin/manage_partner/token'); ?>" class="pure-menu-link icon dyned-icon-token-request">Token Approval</a></li>
                                            </ul>
                                        </li>

                                        <li id="dropdown" class="pure-menu-item dropdown">
                                            <a class="pure-menu-link icon icon-study-dashboard" data-toggle="dropdown">Reporting
                                                <span></span>
                                            </a>
                                            <ul class="menu-dropdown">
                                                <li class="pure-menu-item adm-rtoken">
                                                    <a href="<?php echo site_url('admin/reporting/coach'); ?>" class="pure-menu-link icon icon-study-dashboard">Coach</a>
                                               </li>
                                                <li class="pure-menu-item adm-tapproval">
                                                    <a href="<?php echo site_url('admin/reporting/student'); ?>" class="pure-menu-link icon icon-study-dashboard">Student</a>
                                                </li>
                                            </ul>
                                        </li>

                                    <?php } else if ($this->auth_manager->role() == 'RAD') { ?>
                                        <li class="pure-menu-item std-profile"><a href="<?php echo site_url('account/identity/detail/profile'); ?>" class="pure-menu-link icon dyned-icon-profile"><?php echo $this->auth_manager->lang('lbl_profile');?></a></li>
                                        <li class="pure-menu-item rad-region"><a href="<?php echo site_url('superadmin/region/index/active'); ?>" class="pure-menu-link icon dyned-icon-region">Region</a></li>
                                        <li id="dropdown" class="pure-menu-item rad-partner dropdown">
                                            <a class="pure-menu-link icon dyned-icon-partner" data-toggle="dropdown">Affiliate
                                                <span></span>
                                            </a>
                                            <ul class="menu-dropdown part">
                                                <li class="pure-menu-item rad-papproval"><a href="<?php echo site_url('superadmin/manage_partner/approve_coach'); ?>" class="pure-menu-link icon dyned-icon-coach-approval">Affiliate Approval</a></li>
                                                <li class="pure-menu-item rad-trapproval"><a href="<?php echo site_url('superadmin/manage_partner/token'); ?>" class="pure-menu-link icon dyned-icon-token-approval">Token Request Approval</a></li>
                                            </ul>
                                        </li>
                                        <li class="pure-menu-item rad-pmatches"><a href="<?php echo site_url('superadmin/match_partner'); ?>" class="pure-menu-link icon dyned-icon-partners-matching">Affiliate Matches</a></li>
                                        <li id="dropdown" class="pure-menu-item rad-setting dropdown">
                                            <a class="pure-menu-link icon dyned-icon-setting" data-toggle="dropdown">Settings
                                                <span></span>
                                            </a>
                                            <ul class="menu-dropdown sett">
                                                <li class="pure-menu-item rad-grsetting"><a href="<?php echo site_url('superadmin/settings/region'); ?>" class="pure-menu-link icon dyned-icon-region-setting">Global Region Settings</a></li>
                                                <li class="pure-menu-item rad-gpsetting"><a href="<?php echo site_url('superadmin/settings/partner'); ?>" class="pure-menu-link icon dyned-icon-partner-setting">Global Affiliate Settings</a></li>
                                            </ul>
                                        </li>
                                        <li class="pure-menu-item rad-cmaterials"><a href="<?php echo site_url('superadmin/coach_script'); ?>" class="pure-menu-link icon icon-study-dashboard">Coach Materials</a></li>

                                        <!--
                                        <li class="pure-menu-item adm-admin-partner"><a href="<?php echo site_url('superadmin/manage_admin_partner'); ?>" class="pure-menu-link icon icon-coachpartner-list">Manage Admin Partner</a></li>
                                        <li class="pure-menu-item adm-coach-partner"><a href="<?php echo site_url('superadmin/manage_coach_partner'); ?>" class="pure-menu-link icon icon-coachpartner-list">Manage Coach Partner</a></li>
                                        <li class="pure-menu-item adm-coach-partner"><a href="<?php echo site_url('superadmin/manage_student_partner'); ?>" class="pure-menu-link icon icon-coachpartner-list">Manage Student Partner</a></li>
                                        <li class="pure-menu-item adm-matching"><a href="<?php echo site_url('superadmin/manage_partner'); ?>" class="pure-menu-link icon icon-matching">Manage Partner</a></li> -->
                                    <?php } ?>
                                </ul>
                                <p style="left: 10px;bottom: 0px;position: absolute; color:#fff">
                                    Version 1.0
                                </p>

                            </div>
                        </div>
                        <!-- end menu-->

                        <!-- content-->
                        <div class="pure-u-lg-20-24 pure-u-md-24-24 pure-u-sm-24-24 content-center">

                            <?php
                            echo $this->template->partial->widget('messages_widget', '', true);
                            echo $content;
                            ?>

                        </div>
                </section>
            </div>
        </div>

        <?php if ($this->auth_manager->role() == 'CCH' || $this->auth_manager->role() == 'STD') { ?>

            <!-- <script type="text/javascript">
            window.__lc = window.__lc || {};
            window.__lc.license = 8633729;
            (function() {
              var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
              lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
              var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
            })();
            </script> -->

        <?php } ?>

        <!-- UPDATE TIMEZONE -->
        <script src="<?php echo base_url(); ?>assets/js/main.js"></script>

        <script type="text/javascript">
            var d = new Date()
            var n = d.getTimezoneOffset();



            $.post("<?php echo site_url('timezone_convert');?>", { 'n': n },function(data) {
                // console.log(data);
             });
        </script>

        <!-- UPDATE TIMEZONE -->

        <script type="text/javascript">
            function updatenotif(){
                $("#numnotif").addClass("hide");

               var id = '<?php echo $this->auth_manager->userid(); ?>';
                $.ajax({
                  type:"POST",
                  url:"<?php echo site_url('account/notification/ajax_update');?>",
                  data: {'id':id},
                  success: function(data){
                      console.log(id);
                    }
                 });

            }
        </script>
        <script type="text/javascript">
            // $(document).ready(function(){
            // $(".listTop, .listBottom").children("ul").hide();
            //    $(".listTop, .listBottom").click(function(event){
            //      event.stopPropagation();
            //      $(this).children("ul").slideToggle();

            //    });
            // });
        </script>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".listTop > a").prepend($("<span/>"));
                $(".listBottom > a").prepend($("<span/>"));
                $(".listTop, .listBottom").click(function(event){
                 event.stopPropagation();
                 $(this).children("ul").slideToggle();
               });
            });
        </script>
<!--        <a href="<?php echo site_url('lang_switch/switch_language/english'); ?>">English</a>
        <a href="<?php echo site_url('lang_switch/switch_language/traditional-chinese'); ?>">Chinese</a>-->

        <script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.js"></script>
        <script type="text/javascript">
            $(".checkAll").change(function () {
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });
        </script>

        <script>
        function goBack() {
            window.history.back();

        }
        </script>

        <script>
       $("#breadcrum-home").click(function(){
             window.location.replace("<?php echo site_url('account/identity/detail/profile');?>");

        });
        </script>

        <script id="pacepace" data-pace-options='{ "elements": { "selectors": [".selector"] }, "startOnPageLoad": false, "ajax": false }' src="<?php echo base_url();?>assets/js/pace.min.js"></script>
        <script id="pacepace2">
        var current_page = location.href;
        var leavesess    = '<?php echo site_url('opentok/leavesession');?>';
        var opentok      = '<?php echo site_url('opentok/live');?>';
        var opentok2     = '<?php echo site_url('opentok/live#');?>';
        var exceldl      = '<?php echo site_url('partner/reporting');?>';
        var tempdl       = '<?php echo site_url('student_partner/adding/multiple_student');?>';

        if (current_page.match(leavesess) || current_page.match(opentok) || current_page.match(opentok2) || current_page.match(exceldl) || current_page.match(tempdl) ) {
            $(window).unbind('beforeunload', function(){
              Pace.restart();
            });
        }else{
        $(window).bind('beforeunload', function(){
              Pace.restart();
            });
        }
        </script>

        <script>
            var current_page = location.href;
            var opentok      = '<?php echo site_url('opentok/live');?>';
            var opentok2     = '<?php echo site_url('opentok/live#');?>';
            var exceldl      = '<?php echo site_url('partner/reporting');?>';
            var tempdl       = '<?php echo site_url('student_partner/adding/multiple_student');?>';

            if ( current_page.match(opentok) || current_page.match(opentok2) || current_page.match(exceldl) || current_page.match(tempdl) ) {
                //$("script[src='../js/pace.js']").remove();
                $('script').remove('#pacepace, #pacepace2');
            }
        </script>


        <script type="text/javascript">

            // (function() {
            //     $('.btn-save-del').attr('disabled','disabled');
            //     $('td > input').keyup(function() {

            //         var empty = false;
            //         $('td > input').each(function() {
            //             if ($(this).val() == '') {
            //                 empty = true;
            //             }
            //         });

            //         if (empty) {
            //             $('.btn-save-del').attr('disabled','disabled');
            //         } else {
            //             $('.btn-save-del').removeAttr('disabled');
            //         }
            //     });
            // })()

        </script>

        <script>
        function Reload() {
        try {
        var headElement = document.getElementsByTagName("head")[0];
        if (headElement && headElement.innerHTML)
        headElement.innerHTML += "<meta http-equiv=\"refresh\" content=\"1\">";
        }
        catch (e) {}
        }

        if ((/iphone|ipod|ipad.*os 5/gi).test(navigator.appVersion)) {
            window.onpageshow = function(evt) {
                if (evt.persisted) {
                document.body.style.display = "none";
                location.reload();
                }
            };
        }
        </script>

        <script>
        $(document).ready(function(){
            $.ajax({
                url: "<?php echo site_url('home/check_login');?>",
                type: 'GET',
                dataType: 'html',
                success: function(msg){
                    if(msg == 0){
                        window.location = "<?php echo site_url('logout');?>";
                    }
                }
            });
        });
        </script>

        <script>
            var isFirefox = typeof InstallTrigger !== 'undefined';

            if (isFirefox) {
                $('.breadcrumb li a').css('padding-top', '13px')
            }

            $('#search').focus(function() {
                $('#src__sign').hide();
            })
            $('#search').focusout(function() {
                $('#src__sign').show();
            })
        </script>
        <script>
            // $('body').html($('body').html().replace('/*',' '));
            // $('body').html($('body').html().replace('*/',' '));
            // var satu = '/*';
            // var dua = '*/';
            // document.body.innerHTML = document.body.innerHTML.replace(satu, ' ');
            // document.body.innerHTML = document.body.innerHTML.replace(dua, ' ');

        </script>

        <!-- Start of LiveChat (www.livechatinc.com) code -->

<!-- End of LiveChat code -->

    <script>
    window.onbeforeunload = function() {
        location.reload();
    };
    </script>
    </body>
</html>
