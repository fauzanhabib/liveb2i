<div class="heading text-cl-primary padding15">

    <div class="breadcrumb-tabs pure-g">
        <div class="left-breadcrumb">
            <ul class="breadcrumb toolbar padding-l-0">
                <li id="breadcrum-home"><a href="<?php echo base_url();?>index.php/account/identity/detail/profile">
                    <div id="home-icon">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">
                        <g>
                            <path d="M2.7,14.1c0,0,0,0.3,0.3,0.3c0.4,0,3.7,0,3.7,0l0-3c0,0-0.1-0.5,0.4-0.5h1.5c0.6,0,0.5,0.5,0.5,0.5l0,3
                                c0,0,3.1,0,3.6,0c0.4,0,0.4-0.4,0.4-0.4V8.5L8.1,4L2.7,8.5L2.7,14.1z"/>
                            <path d="M0.7,8.1c0,0,0.5,0.8,1.5,0l5.9-5l5.6,5c1.2,0.8,1.6,0,1.6,0L8.1,1.5L0.7,8.1z"/>
                            <polygon points="13.6,3 12.1,3 12.1,4.8 13.6,6  "/>
                        </g>
                        </svg>
                    </div>
                </a></li>
                <li><a href="<?php echo site_url('partner/subgroup') ?>">Group</a></li>
				<li><a href="<?php echo site_url('partner/subgroup/edit_subgroup/'. $data3[0]->id); ?>"><?php echo $data3[0]->name; ?></a></li>
				<li><a href="#"><?php echo $data3[0]->name; ?> Settings</a></li>
            </ul>
        </div>
    </div>

    <h1 class="margin0">Group Specific Setting</h1>
</div>
        <div class="heading pure-g">
            <div class="pure-u-18-24">
                <h3 class="h3 font-normal padding15 text-cl-secondary" style="float:left"><a href="<?php echo site_url('partner/subgroup/edit_subgroup/'. $data3[0]->id); ?>">BASIC INFO</a> | 
                    Group Setting
                </h3> 
            </div>
            
        </div>

<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Group Specific Setting</h1>
</div>
<div class="box b-f3-2">

    <div class="content padding0">
        
        <div class="list-frm">
            <?php echo form_open_multipart('partner/'.$this->uri->segment(2).'/update_setting/'.$data[0]->user_id, 'id="form" role="form" class="pure-form pure-form-aligned padding15" data-parsley-validate'); ?>
            <div class="heading pure-g">
                <div class="pure-u-12-24">
                    <h3 class="h3 font-normal padding-tb-15 text-cl-secondary">COACH AFFILIATE SETTING</h3>
                </div>
                <div class="pure-u-12-24">
                    <div class="edit action-icon">
                        <?php echo form_submit('__submit', 'SAVE', 'class="pure-button btn-tiny btn-white-tertinary m-b-10 save_click"'); ?>
                        <i class="icon icon-close close_click" title="Cancel"></i>
                        <i class="icon icon-edit edit_click" title="Edit"></i>

                    </div>
                </div>
            </div>

			<div class="heading pure-g">
                
            </div>
            <?php //echo form_open_multipart('admin/partner_setting/update_setting/coach', 'role="form"'); ?>
                
                    <div class="pure-control-group" style="margin: 10px 0px 15px;">
                        <div class="label" style="vertical-align:top">
                            <label>Duration per Session</label>
                        </div>
                        <div class="input">
                            <span class="r-only"><?php echo(@$data[0]->session_duration); ?> Minutes</span>
                            <div class="pure-g e-only">
                                <div class="pure-u-1 m-b-5">
                                    <label class="radio d-i-block m-b-15">
                                        <input type="radio" name="session_duration" value="15" <?php echo(@$data[0]->session_duration == 15 ? 'checked="true"' : '');?>>
                                        <span class="outer m-r-10"><span class="inner"></span></span>
                                        15 Minutes
                                    </label>
                                </div>
                                <div class="pure-u-1">
                                    <label class="radio d-i-block m-b-15">
                                        <input type="radio" name="session_duration" value="25" <?php echo(@$data[0]->session_duration == 25 ? 'checked="true"' : '');?>>
                                        <span class="outer m-r-10"><span class="inner"></span></span>
                                        25 Minutes
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if($this->uri->segment(2) == 'partner') { ?>
                    <div class="b-f3-1"></div>

                    
                            </div>
                        </div>
                    </div>
                    <div class="b-f3-1"></div>
                    <?php } ?>

                </fieldset>
            <?php echo form_close(); ?>        
            
        </div>
        
    </div>
</div>

<script type="text/javascript">
    $(function(){

    	$('h4').css({'color':'#434343'});
        $('.outer').css({'width':'10px','height':'10px','margin':'1px 12px 0px 3px'});
        $('.inner').css({'width':'6px','height':'6px'});

    	if($(document).width()<=600) {
    		$('.pure-control-group .input').css({'width':'8em'});
    	}

        $('.list-frm').each(function(){

            var _each = $(this);

            var _close = $('.close_click', _each);
            var _save = $('.save_click', _each);
            var _edit = $('.edit_click', _each);
            var _eonly = $('.e-only', _each);
            var _ronly = $('.r-only', _each)

            _eonly.hide();
            _close.hide();
            _save.hide();

            $(_edit).click(function () {
                _eonly.show();
                _ronly.hide();
                _close.show();
                _save.show();
                _edit.hide();
               // $("#td_value_1_1").focus();

                $('.e-only').not(_eonly).hide();
                $('.r-only').not(_ronly).show();
                $('.close_click').not(_close).hide();
                $('.save_click').not(_save).hide();
                $('.edit_click').not(_edit).show();

                animationClick(_close, 'fadeIn');
                animationClick(_save, 'fadeIn');
            });

            $(_close).click(function () {
                _close.hide();
                _save.hide();
                _edit.show();
                _ronly.show();
                _eonly.hide();
                animationClick(_edit, 'fadeIn');
                $('#form').parsley().reset();
            });

            $(_save).click(function () {
                //_save.text('SAVING...');
                if ( $('#form').parsley().isValid() ) {
                    _close.hide();       
                }
                $("#load", _each).show().delay(3000).queue(function (next) {
                    $(this).hide();
                    _save.text('SAVE').hide;
                    //$('.save_click', _each).hide();
                    _edit.show();
                    _ronly.show();
                    _eonly.hide();
                    next();
                });
            });
        })

        

    })
</script>