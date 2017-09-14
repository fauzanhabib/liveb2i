<div class="heading text-cl-primary padding15">
    <h1 class="margin0">WebEx User</h1>
</div>
<div class="box">
    <div class="heading pure-g">
        <!-- block edit -->
        <div class="pure-u-1 edit tab-list tab-link">
            <ul>
                <li><a href="<?php echo site_url('partner/webex/list_host');?>">List of WebEx Users</a></li>
                <li class="current"><a href="<?php echo site_url('partner/webex/login');?>" class="active">Record WebEx Account</a></li>
            </ul>
        </div>
        <!-- end block edit -->
    </div>

    <div class="content tab-content padding0" >
        <div id="tab2" style="margin-top:10px;">
            <div class="accordion-container" style="margin-bottom:5px">
                <div id="accordian-exist-content" class="accordion-content open" style="padding:20px;height:auto">
                    <?php echo form_open('partner/webex/register_host_to_live', 'role="form" class="pure-form pure-form-aligned" data-parsley-validate'); ?>
                        <fieldset>
                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="subdomain">Subdomain WebEx</label>
                                </div>
                                <div class="input">
                                    <input name="subdomain_webex" id="subdomain" type="text" class="pure-input-1-2" required data-parsley-required-message="Please input WebEx domain">
                                </div>
                            </div>

                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="partner">Partner ID</label>
                                </div>	
                                <div class="input">
                                    <input name="partner_id" id="partner" type="text" class="pure-input-1-2" required data-parsley-required-message="Please input WebEx partner ID">
                                </div>
                            </div>
                            
                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="webexid">WebEx ID</label>
                                </div>	
                                <div class="input">
                                    <input name="webex_id" id="webexid" type="text" class="pure-input-1-2" required data-parsley-required-message="Please input WebEx ID">
                                </div>
                            </div>

                            <div class="pure-control-group">
                                <div class="label">
                                    <label for="password">Password</label>
                                </div>
                                <div class="input">
                                    <input name="password" id="password" class="pure-input-1-2" type="password" required data-parsley-required-message="Please input WebEx password">
                                </div>
                            </div>
                            <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                                <div class="label">
                                    <button type="submit" class="pure-button btn-small btn-primary margin0">SUBMIT</button>
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
        windowsize = $(window).width();
        
        if(windowsize < 720){
            $('.tab-list ul').css("width","200px")
        }
        
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
    
</script>
