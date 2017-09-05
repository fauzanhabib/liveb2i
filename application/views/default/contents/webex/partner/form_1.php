<div class="heading text-cl-primary padding15">
    <h1 class="margin0"><small>Webex User</small></h1>
</div>
<div class="box">
    <div class="heading pure-g">
        <!-- block edit -->
        <div class="pure-u-1 edit no-left tab-link">
            <a href="<?php echo site_url('partner/webex/list_host');?>" >List of Webex Users</a>
            <a href="<?php echo site_url('partner/webex/login');?>" class="active">Register Webex Users</a>
        </div>
        <!-- end block edit -->
    </div>

    <div class="content tab-content" >
        <div id="tab2" style="margin-top:10px;">
            <div class="accordion-container" style="margin-bottom:5px">
                <a id="accordian-login" href="#" class="accordion-toggle active">
                    EXISTING WEBEX USER
                    <span class="toggle-icon">
                        <i class="icon icon-arrowup"></i>
                    </span>
                </a>
                <div id="accordian-exist-content" class="accordion-content open">
                    <?php echo form_open('partner/webex/pre_login', 'role="form" class="pure-form pure-form-aligned"'); ?>
                        <fieldset>
                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="subdomain">Subdomain Webex</label>
                                </div>
                                <div class="input">
                                    <input name="subdomain_webex" id="subdomain" type="text" class="pure-input-1-2">
                                    <?php 
                                    //echo form_input('subdomain_webex','', 'class="pure-input-1-2" id="subdomain"');
                                    //echo form_error('subdomain_webex', '<small class="pull-right req">', '</small>');
                                    ?>
                                </div>
                            </div>

                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="partner">Partner ID</label>
                                </div>	
                                <div class="input">
                                    <input name="partner_id" id="partner" type="text" class="pure-input-1-2">
                                </div>
                            </div>
                            
                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="webexid">Webex ID</label>
                                </div>	
                                <div class="input">
                                    <input name="webex_id" id="webexid" type="text" class="pure-input-1-2">
                                </div>
                            </div>

                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="password">Password</label>
                                </div>
                                <div class="input">
                                    <input name="password" id="password" class="pure-input-1-2" type="password">
                                </div>
                            </div>

                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="siteid">Site ID</label>
                                </div>
                                <div class="input">
                                    <input name="site_id" id="siteid" class="pure-input-1-2" type="text">
                                </div>
                            </div>

                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="sitename">Site Name</label>
                                </div>
                                <div class="input">
                                    <input name="site_name" id="sitename" class="pure-input-1-2" type="text">
                                </div>
                            </div>
                            
                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="siteid">Email</label>
                                </div>
                                <div class="input">
                                    <input name="email_address" id="email" class="pure-input-1-2" type="text">
                                </div>
                            </div>
                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="timezones">Timezone</label>
                                </div>	
                                <div class="input">
                                    <?php echo timezone_menu('UP7', 'pure-input-1-2 timezones');?>
                                </div>
                            </div>
                            <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                                <div class="label">
                                    <button type="submit" class="pure-button btn-small btn-primary">SUBMIT</button>
                                    <button type="reset" class="pure-button btn-small btn-white">RESET</button>
                                </div>
                            </div>
                        </fieldset>
                    <?php echo form_close();?>   
                </div>
            </div>
            <div class="accordion-container">
                <a id="accordian-register" href="#" class="accordion-toggle">
                    NEW WEBEX USER
                    <span class="toggle-icon">
                        <i class="icon icon-arrowdown"></i>
                    </span>
                </a>
                <div id="accordian-register-content" class="accordion-content">
                    <?php echo form_open('partner/webex/pre_register', 'role="form" class="pure-form pure-form-aligned"'); ?>
                        <fieldset>
                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="subdomain">Subdomain Webex</label>
                                </div>
                                <div class="input">
                                    <input name="subdomain_webex" id="subdomain" type="text" class="pure-input-1-2">
                                </div>
                            </div>

                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="partner">Partner ID</label>
                                </div>	
                                <div class="input">
                                    <input name="partner_id" id="partner" type="text" class="pure-input-1-2">
                                </div>
                            </div>

                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="firstname">First Name</label>
                                </div>
                                <div class="input">
                                    <input name="first_name" id="firstname" class="pure-input-1-2" type="text">
                                </div>
                            </div>

                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="lastname">Last Name</label>
                                </div>
                                <div class="input">
                                    <input name="last_name" id="lastname" class="pure-input-1-2" type="text">
                                </div>
                            </div>

                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="email">Email Address</label>
                                </div>
                                <div class="input">
                                    <input name="email_address" id="email" class="pure-input-1-2" type="text">
                                </div>
                            </div>

                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="webexid">Webex ID</label>
                                </div>	
                                <div class="input">
                                    <input name="webex_id" id="webexid" type="text" class="pure-input-1-2">
                                </div>
                            </div>

                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="password">Password</label>
                                </div>
                                <div class="input">
                                    <input name="password" id="password" class="pure-input-1-2" type="password">
                                </div>
                            </div>

                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="timezone">Timezone</label>
                                </div>	
                                <div class="input">
                                    <?php echo timezone_menu('UP7', 'pure-input-1-2 timezones');?>
                                </div>
                            </div>

                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="siteid">Site ID</label>
                                </div>
                                <div class="input">
                                    <input name="site_id" id="siteid" class="pure-input-1-2" type="text">
                                </div>
                            </div>

                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="sitename">Site Name</label>
                                </div>
                                <div class="input">
                                    <input name="site_name" id="sitename" class="pure-input-1-2" type="text">
                                </div>
                            </div>
                            <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                                <div class="label">
                                    <button type="submit" class="pure-button btn-small btn-primary">SUBMIT</button>
                                    <button type="reset" class="pure-button btn-small btn-white">RESET</button>
                                </div>
                            </div>
                        </fieldset>
                    <?php echo form_close();?> 
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.list').each(function () {
            var $dropdown = $(this);

            $removef = $('.remove-field', $dropdown);

            $(".link-edit", $dropdown).click(function (e) {
                e.preventDefault();
                $(".edit", $dropdown).hide();
                $(".update", $dropdown).show();
                $(".addmore", $dropdown).show();
                $(".block-date input", $dropdown).addClass('tm')

                return false;
            });

            $(".btn-book", $dropdown).click(function (e) {
                e.preventDefault();
                $(".edit", $dropdown).show();
                $(".update", $dropdown).hide();
                $(".addmore", $dropdown).hide();
                $(".block-date input", $dropdown).removeClass('tm')

                return false;
            });

            var max_field = 2;
            var addmore = '.addmore';
            var i = 1;

            $(addmore, $dropdown).click(function (e) {
                e.preventDefault();
                $wrapper = $(".date", $dropdown);
                if (i < max_field) {
                    i++;
                    $($wrapper).append('<div class="block-date"><span>Shedule 2</span><input type="text" class="tm"><span>to</span><input type="text" class="tm"><span class="remove-field">Remove</span></div>');
                }
                $(".remove-field", $dropdown).show();
                //$(".addmore", $dropdown).hide();
            });

            $("body").on("click", ".remove-field", function (e) {
                e.preventDefault();
                //$(".addmore", $dropdown).show();
                $(this).parent("div").remove();
                i--;
            });
        });
        function close_accordion() {
            $('.accordion-toggle').removeClass('active');
            $('.accordion-content').removeClass('open');
            $('.toggle-icon').html('<i class="icon icon-arrowdown"></i>');
            return false;
        }

        $('.accordion-toggle').on('click', function (event) {
            event.preventDefault();
            var accordion = $(this);
            var accordionContent = accordion.next('.accordion-content');
            var accordionToggleIcon = $(this).children('.toggle-icon');

            if ($(event.target).is('.active')) {
                close_accordion();
                accordionToggleIcon.html('<i class="icon icon-arrowdown"></i>');
                return false;
            }
            else {
                close_accordion();
                accordion.addClass("active");
                accordionContent.addClass('open');
                accordionToggleIcon.html('<i class="icon icon-arrowup"></i>');
            }
        });
        <?php 
        if($this->session->userdata('register')=='new'){ ?>
            $("#accordian-register-content").addClass('open');
            $("#accordian-exist-content").removeClass('open');
        <?php }
        else { ?>
            $("#accordian-register-content").removeClass('open');
            $("#accordian-exist-content").addClass('open');
        <?php } ?>
    });
    
    $('.timezones').attr('disabled', 'disabled');
</script>
