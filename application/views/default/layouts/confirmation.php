<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/> <!--320-->
        <title>Detail Profile - DynEd Live</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/styles/pure.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/styles/grids-responsive.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/styles/dashboard.css">
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

<div class="box clearfix">

    <div class=""><a href="#modal" class="text-cl-tertiary font-semi-bold"></a></div>

    <!-- modal-->
    <style>
        .remodal-overlay {
            background: #000;
        }
    </style>
    <div class="remodal" data-remodal-id="modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="closeOnOutsideClick: false, closeOnEscape: false">
        <h5>You haven’t logged out since your last login. Do you want to re-login with this session?
        </h5>
        <button class="pure-button btn-green btn-expand-tiny" value="yes">Yes</button>
        <button class="pure-button btn-red btn-expand-tiny" data-remodal-action="close" value="no">No</button>
    </div>
<!-- modal -->  
</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/main.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/remodal.min.js"></script>
<script src="<?php echo base_url();?>assets/js/main.js"></script>

<script>
    $(function(){ 
        var inst = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];
        inst.open();
    });

    $(".pure-button").on('click', function() {
        var confirm = $(this).val();
        if(confirm == 'no'){
            window.location = "<?php echo site_url('logout');?>";
        } else {
            var session_user_id = "<?php echo $this->session->userdata('user_id_session');?>";   
                $.ajax({
                    url: "<?php echo site_url('login/update_login');?>",
                    type: 'POST',
                    dataType: 'html',
                    data: {user_id: session_user_id},
                    success: function(msg){
                        console.log(msg);
                            if(msg == 1){
                                var role = "<?php echo $this->auth_manager->role();?>";
                                if(role == 'STD'){
                                    window.location = "<?php echo site_url('student/dashboard');?>";
                                } else if(role == 'CCH'){
                                    window.location = "<?php echo site_url('coach/dashboard');?>";
                                } else {
                                    window.location = "<?php echo site_url('account/identity/detail/profile');?>";
                                }
                            } else {
                                window.location = "<?php echo site_url('logout');?>";
                            }
                        }

                    });
                }
            });

    //     }
    // });

</script>



