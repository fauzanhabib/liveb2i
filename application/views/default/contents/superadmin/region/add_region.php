<div class="heading text-cl-primary padding0">

    <div class="breadcrumb-tabs pure-g">
        <div class="left-breadcrumb">
            <ul class="breadcrumb toolbar padding-l-20">
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
                <li><a href="<?php echo base_url();?>index.php/superadmin/region">Regions</a></li>
            </ul>
        </div>
    </div>

    <h1 class="margin0 padding-l-30 left">Add Region</h1>

    <div class="btn-goBack padding-l-210 padding-t-5">
        <a href="<?php echo base_url();?>index.php/superadmin/region">
            <button class="btn-small border-1-blue bg-white-fff">
                <div class="left padding-r-5">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve" class="width15">
                    <g id="back-one-page">
                        <g>
                            <g id="XMLID_13_">
                                <path style="fill-rule:evenodd;clip-rule:evenodd;" d="M20,0c11.046,0,20,8.954,20,20s-8.954,20-20,20S0,31.046,0,20
                                    S8.954,0,20,0z M37.002,20c0-9.39-7.612-17.002-17.002-17.002C10.611,2.998,2.998,10.61,2.998,20
                                    c0,9.389,7.613,17.002,17.002,17.002C29.39,37.002,37.002,29.389,37.002,20z"/>
                            </g>
                            <g>
                                <g>
                                    <path style="fill:#231F20;" d="M27.734,22.141H13.636c-1.182,0-2.141-0.958-2.141-2.141s0.959-2.141,2.141-2.141h14.098
                                        c1.182,0,2.141,0.958,2.141,2.141S28.916,22.141,27.734,22.141z"/>
                                </g>
                                <g>
                                    <g>
                                        <path style="fill:#231F20;" d="M19.465,24.27l-2.611-2.822c-0.756-0.818-0.756-2.08,0-2.897l2.611-2.822
                                            c1.264-1.366,0.295-3.582-1.566-3.582h-0.353c-0.595,0-1.162,0.248-1.566,0.685l-5.288,5.719c-0.756,0.817-0.756,2.079,0,2.896
                                            l5.288,5.719c0.404,0.437,0.971,0.685,1.566,0.685h0.353C19.76,27.852,20.729,25.636,19.465,24.27z"/>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                    <g id="Layer_1">
                    </g>
                    </svg>
                </div>
                Go Back One Page
            </button>
        </a>
    </div>
</div>

<div class="box clear-both">

    <div class="content">
        <div class="pure-g">            
            <div class=" profile-detail prelative padding-t-15 width78perc">
            <form action="<?php echo site_url('superadmin/region/' .$action); ?>" role="form" class="pure-form pure-form-aligned" method="post" accept-charset="utf-8" id="form-match">
                <table class="table-no-border2 add-form"> 
                    <tbody>
                        <tr>
                            <td class="pad15">Region Name</td>

                            <td class="add-form-noborder">
                                <?php echo form_input('region', set_value('region', @$data->region), 'id="region" class="width100perc bg-white-fff border-1-ccc padding3" required data-parsley-required-message="Please input region"') ?>
                                
                            </td>
                        </tr>
                        <tr>
                            <td class="pad15">Admin Name</td>

                            <td class="add-form-noborder">
                                <?php echo form_input('fullname', set_value('fullname', @$data->fullname), 'id="fullname" class="width100perc bg-white-fff border-1-ccc padding3" required data-parsley-required-message="Please input coach name"') ?>
                                
                            </td>
                        </tr>

                        <tr>
                            <td class="pad15">Admin Email</td>

                            <td class="add-form-noborder">
                                <input type="email" name="email" data-parsley-trigger="change" value="<?php echo @$data->email?>" id="email" class="width100perc bg-white-fff border-1-ccc padding3" required data-parsley-required-message="Please input coach e-mail address" data-parsley-type-message="Please input valid e-mail address">   
                            </td>
                        </tr>

                        <tr>
                            <td class="pad15">Token </td>

                            <td class="add-form-noborder">
                                <input type="token" name="token_amount" data-parsley-trigger="change" value="<?php echo @$data->token_amount?>" id="token" class="width100perc bg-white-fff border-1-ccc padding3" data-parsley-type="digits" required data-parsley-required-message="Please input numbers only">   
                            </td>
                        </tr>

                        <tr>
                            <td class="pad15">Individual Settings</td>

                            <td class="add-form-noborder">
                                <select name="status_set_setting" class="width100perc bg-white-fff border-1-ccc padding3">
                                    <option value="1">Allow</option>
                                    <option value="0">Disallow</option>
                                </select>   
                            </td>
                        </tr>

                    </tbody>    
                </table>

                <div class="save-cancel-btn text-right padding-t-20">
                    <button class="pure-button btn-red btn-small"><a href="<?php echo site_url('superadmin/region/index'); ?>">Cancel</a></button>
                    <input type="submit" name="__submit" value="Save" class="pure-button btn-blue btn-small" id="add">
                </div>
            </form>
            </div>

        </div>
    </div>
</div>   
<script type="text/javascript">
    $(document).ready(function() {

   $("input").bind("keydown", function(event) {
      // track enter key
      var keycode = (event.keyCode ? event.keyCode : (event.which ? event.which : event.charCode));
      if (keycode == 13) { // keycode for enter key
         // force the 'Enter Key' to implicitly click the Update button
         document.getElementById('add').click();
         return false;
      } else  {
         return true;
      }
   }); // end of function

}); // end of document ready
</script>

