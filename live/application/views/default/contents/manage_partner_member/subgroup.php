<?php if($this->auth_manager->role() == 'RAD') {
    $role_link = "superadmin";
} else {
    $role_link = "admin";
}

?>

<div class="heading text-cl-primary padding-l-20">

    <div class="breadcrumb-tabs pure-g">
        <div class="left-breadcrumb">
            <ul class="breadcrumb toolbar padding-l-0">
                <li id="breadcrum-home"><a>
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
                <?php if($role_link == 'superadmin'){ ?>
                <li><a href="<?php echo site_url($role_link.'/region/index/active');?>">Regions</a></li>

                <li>
                    <a href="<?php echo site_url($role_link.'/region/detail/'.$region_id);?>">
                        <?php echo @$this->common_function->get_info_region($region_id)[0]->region_id;?>
                    </a>
                </li>
                <?php } ?>
                <?php if($role_link == 'admin'){ ?>
                    <li><a href="<?php echo site_url('admin/manage_partner');?>">Affiliate</a></li>
                <?php } ?>
                <li><a href="#"><?php echo $partner->name;?></a></li>

            </ul>
        </div>
    </div>

    <div class="profilePic-top thumb-small left img-circle-big" style="border: 1px solid #fff;">
        <img src="<?php echo base_url(@$partner->profile_picture); ?>" width="150" class="pure-img fit-cover-top" />
    </div>

    <div class="tag">
        <h1 class="padding-l-15 text-cl-secondary font-semi-bold">&nbsp;&nbsp;<?php echo @$partner->name; ?></h1>
        <span class="padding-l-18 text-cl-secondary font-18 font-semi-bold"><?php echo @$this->common_function->get_partner_type($partner->id); ?></span><br>
        <span class="padding-l-18 font-14 text-cl-secondary"><?php echo @$partner->state;?>, <?php echo @$partner->country;?></span>
    </div>

    <div class="left-tabs pure-menu pure-menu-horizontal padding-t-17 padding-l-170">
        <ul class="pure-menu-list padding-t-18">
            <li class="pure-menu-item padding-tb-9 bg-tertiary font-semi-bold"><a href="<?php echo site_url($role_link.'/manage_partner/detail/'.$partner_id.'/'.@$region_id);?>" class="pure-menu-link font-14">Affiliate Profile</a></li>
            <?php if($type == "student") { ?>
            <li class="pure-menu-item padding-tb-9 bg-primary font-semi-bold"><a href="<?php echo site_url($role_link.'/manage_partner/partner/student/'.$partner_id.'/'.@$region_id);?>" class="pure-menu-link font-14">Student Affiliate</a></li>
            <li class="pure-menu-item padding-tb-9 bg-tertiary font-semi-bold"><a href="<?php echo site_url($role_link.'/manage_partner/partner/coach/'.$partner_id.'/'.@$region_id);?>" class="pure-menu-link font-14">Coach Affiliate</a></li>
            <?php } else if ($type == "coach") { ?>
            <li class="pure-menu-item padding-tb-9 bg-tertiary font-semi-bold"><a href="<?php echo site_url($role_link.'/manage_partner/partner/student/'.$partner_id.'/'.@$region_id);?>" class="pure-menu-link font-14">Student Affiliate</a></li>
            <li class="pure-menu-item padding-tb-9 bg-primary font-semi-bold"><a href="<?php echo site_url($role_link.'/manage_partner/partner/coach/'.$partner_id.'/'.@$region_id);?>" class="pure-menu-link font-14">Coach Affiliate</a></li>
            <?php } ?>
            <li class="pure-menu-item padding-tb-9 no-hover">
                <a href="<?php echo $back;?>">
                    <button class="btn-small border-1-blue bg-white-fff text-cl-tertiary height38">
                        <div class="left padding-r-5">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve" class="width15">
                            <g id="back-one-page">
                                <g>
                                    <g id="XMLID_13_">
                                        <g>
                                            <path style="fill-rule:evenodd;clip-rule:evenodd;fill:#51B3E8;" d="M20,0c11.046,0,20,8.954,20,20s-8.954,20-20,20
                                                S0,31.046,0,20S8.954,0,20,0z M37.002,20c0-9.39-7.612-17.002-17.002-17.002C10.611,2.998,2.998,10.61,2.998,20
                                                c0,9.389,7.613,17.002,17.002,17.002C29.39,37.002,37.002,29.389,37.002,20z"/>
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <g>
                                                <path style="fill:#51B3E8;" d="M27.734,22.141H13.636c-1.182,0-2.141-0.958-2.141-2.141s0.959-2.141,2.141-2.141h14.098
                                                    c1.182,0,2.141,0.958,2.141,2.141S28.916,22.141,27.734,22.141z"/>
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <g>
                                                    <path style="fill:#51B3E8;" d="M19.465,24.27l-2.611-2.822c-0.756-0.818-0.756-2.08,0-2.897l2.611-2.822
                                                        c1.264-1.366,0.295-3.582-1.566-3.582h-0.353c-0.595,0-1.162,0.248-1.566,0.685l-5.288,5.719
                                                        c-0.756,0.817-0.756,2.079,0,2.896l5.288,5.719c0.404,0.437,0.971,0.685,1.566,0.685h0.353
                                                        C19.76,27.852,20.729,25.636,19.465,24.27z"/>
                                                </g>
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
            </li>
        </ul>
    </div>
</div>

<div class="box clear-both">

    <div class="content">
        <div class="left-list-tabs pure-menu pure-menu-horizontal width50perc padding-t-10 left">
            <ul class="pure-menu-list">
                               <li class="pure-menu-item pure-menu-selected no-hover">
                    <a href="<?php echo site_url($role_link.'/manage_partner/partner/'.$type.'/'.$partner_id.'/'.@$region_id);?>" class="pure-menu-link padding-t-b-5 font-14 font-semi-bold">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve" class="width15">
                    <g id="tab-basic">
                        <g>
                            <g>
                                <g>
                                    <path d="M24.39,28.744h-1.216c-0.631,0-1.143-0.51-1.143-1.141c0-0.233,0.094-0.459,0.261-0.621
                                        c0.708-0.687,1.302-1.612,1.733-2.61c0.088,0.064,0.182,0.111,0.288,0.111c0.683,0,1.487-1.509,1.487-2.538
                                        c0-1.029-0.095-1.863-0.78-1.863c-0.081,0-0.166,0.014-0.254,0.036c-0.049-2.79-0.753-6.27-5.013-6.27
                                        c-4.444,0-4.963,3.474-5.013,6.26c-0.063-0.013-0.125-0.027-0.183-0.027c-0.684,0-0.778,0.834-0.778,1.863
                                        c0,1.029,0.802,2.538,1.486,2.538c0.085,0,0.163-0.023,0.236-0.066c0.429,0.982,1.016,1.888,1.713,2.569
                                        c0.168,0.163,0.261,0.385,0.261,0.62c0,0.631-0.512,1.143-1.143,1.143h-1.218c-2.602,0-4.711,2.109-4.711,4.711v1.306
                                        c0,0.82,0.665,1.487,1.487,1.487h15.721c0.82,0,1.486-0.667,1.486-1.487V33.46C29.101,30.855,26.992,28.744,24.39,28.744z"/>
                                    <path d="M38.494,15.821H25.311c0.38,0.842,0.653,1.879,0.766,3.161c0.282,0.174,0.493,0.454,0.665,0.792
                                        h11.752c0.611,0,1.102-0.493,1.102-1.102v-1.749C39.596,16.316,39.104,15.821,38.494,15.821z"/>
                                    <path d="M38.494,23.024H27.011c-0.312,1.177-1.079,2.409-2.142,2.738c-0.031,0.058-0.067,0.108-0.1,0.166
                                        v1.049h13.723c0.609,0,1.102-0.493,1.102-1.104v-1.747C39.596,23.517,39.104,23.024,38.494,23.024z"/>
                                    <path d="M38.494,30.227H29.53c0.592,0.936,0.939,2.04,0.939,3.228v0.725h8.025
                                        c0.611,0,1.102-0.493,1.102-1.102v-1.749C39.596,30.72,39.104,30.227,38.494,30.227z"/>
                                    <path d="M43.231,4.832H6.771C3.031,4.832,0,7.862,0,11.602v26.889c0,3.742,3.031,6.771,6.771,6.771h36.46
                                        c3.74,0,6.769-3.031,6.769-6.771V11.602C50,7.862,46.971,4.832,43.231,4.832z M46.805,38.491c0,1.971-1.603,3.574-3.574,3.574
                                        H6.771c-1.971,0-3.574-1.603-3.574-3.574V11.602c0-1.971,1.603-3.574,3.574-3.574h36.46c1.971,0,3.574,1.603,3.574,3.574V38.491
                                        z"/>
                                </g>
                            </g>
                        </g>
                    </g>
                    <g id="Layer_1">
                    </g>
                    </svg>
                    <?php if($type == "coach") { ?>
                    Coach Affiliate's Admin
                    <?php } elseif($type == "student") { ?>
                    Student Affiliate's Admin
                    <?php } ?>
                </a>
                </li>
                <li class="pure-menu-item pure-menu-selected no-hover">

                    <a href="<?php echo site_url($role_link.'/manage_partner/list_partner/'.$type.'/'.$partner_id.'/'.@$region_id);?>" class="pure-menu-link padding-t-b-5 font-14 font-semi-bold active-tabs-blue">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve" class="width15">
                    <g id="tab-coach">
                        <g>
                            <path d="M43.564,32h-8.825h-8.825c0,0-0.046,0-0.071,0c-2.791,0-5.435-1.589-7.047-3.956
                                c-1.27-1.862-1.772-4.293-1.399-6.54c0.136-0.822,0.106-1.753-0.122-2.614c-0.299-1.132-0.897-2.129-1.674-2.849l0.038-0.029
                                l-1.747-6.612c-0.226-0.854-1.077-1.36-1.902-1.127l-1.077,0.305C10.517,8.69,10.18,8.962,9.977,9.331
                                C9.773,9.7,9.719,10.137,9.828,10.549l1.101,4.166c-0.079,0.018-0.158,0.038-0.237,0.061c-2.831,0.805-4.474,3.921-3.669,6.962
                                c0.289,1.096,0.859,2.032,1.598,2.744c1.017,4.361,3.576,9.752,8.751,13.539c1.788,1.309,3.331,2.983,4.5,4.902
                                c0.003,0.007,0.008-0.009,0.011-0.002L22.176,50H49V38.72C49,34.981,46.829,32,43.564,32z"/>
                            <ellipse cx="34.738" cy="22.844" rx="8.471" ry="8.601"/>
                            <path style="fill:#919090;" d="M49.04,8H17.714c-0.522,0-0.943,0.188-0.943,0.727L16.77,9.221c0,0.541,0.422,0.779,0.943,0.779
                                H49.04C49.562,10,50,9.708,50,9.169V8.477C50,7.936,49.559,8,49.04,8z"/>
                            <path d="M49.054,0.047L1.038,0.048C0.464,0.048,0,0.531,0,1.124v0.594C0,2.258,0.422,3,0.943,3h48.111
                                C49.576,3,50,2.258,50,1.718V1.026C50,0.486,49.576,0.047,49.054,0.047z"/>
                        </g>
                    </g>
                    <g id="Layer_1">
                    </g>
                    </svg>
                    <?php if($type == "coach") { ?>
                    Coach Group List
                    <?php } elseif($type == "student") { ?>
                    Student Group List
                    <?php } ?>
                </a>
            </li>
            </ul>
        </div>
        <?php if($role_link == "admin"){?>
        <br><br>
        <?php } ?>
        <form action="<?php echo site_url($role_link.'/manage_partner/subgroup_action/'.$type.'/'.$partner_id.'/'.@$region_id);?>" method="POST">
        <?php if($role_link == "superadmin"){?>
        <div class="delete-add-btn right">
            <!-- <div class="btn-noborder btn-normal bg-white-fff left"><a href="<?php echo site_url($role_link.'/manage_partner/move_supplier/'.$type.'/'.$partner_id.'/'.@$region_id);?>"><img src="<?php echo base_url('assets/img/iconmonstr-plus-6-16.png');?>" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-tertiary">Move Group</em></a></div> -->
            <!-- <button type="submit" name="_submit" value="subgroup_delete" class="btn-noborder btn-normal bg-white-fff" onclick="return confirm('Are you sure you want to delete?')"><a href=""><img src="<?php echo base_url('assets/img/iconmonstr-x-mark-7-16.png');?>" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-red">Delete Group</em></a></button> -->
        </div>
        <?php } ?>
        
        <div class="content padding-t-0">
            <div class="box">
                <?php if($role_link == "superadmin"){?>
     <!--            <div class="select-all">                             
                    <div class="padding-r-5 m-t-2 left">
                        <input type="checkbox" id="checkbox-1-0" name="Region" value="Region-1" class="regular-checkbox checkAll" /><label class="" for="checkbox-1-0"></label>
                    </div>
                    <div class="">
                        <label class="font-12">Select All</label>
                    </div>
                </div> -->
                <?php } ?>
                <table id="large" class="display table-session tablesorter" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                             <?php if($this->auth_manager->role() == 'RAD') { ?>
                            <!-- <th class="bg-secondary bg-none text-cl-white border-none" style=""></th> -->
                            <?php } ?>
                            <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">No</th>
                            <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Group</th>
                            </tr>
                    </thead>
                    <tbody>
                        
                        <?php $no = 2; $a=1; foreach ($subgroup as $s) { ?>
                        <div class="box-info-list-checkbox grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 padding-b-20">
                            <tr>
                            <?php if($this->auth_manager->role() == 'RAD') { ?>
                            
                  <!--               <td class="text-left">
                                    <input type="checkbox" id="checkbox-1-<?php echo $no;?>" name="check_list[]" value="<?php echo $s->id;?>" class="regular-checkbox" /><label for="checkbox-1-<?php echo $no;?>"></label>
                                </td> -->
                            <?php } ?>
                            <td><?php echo $a; ?></td>
                            <td><a href="<?php echo site_url($role_link.'/manage_partner/member_of_'.$type.'/active/'.$s->id.'/'.$partner_id);?>" class="text-cl-tertiary"><u><?php echo $s->name?></u></a></td>
                                   
                         </tr>
                        <?php $no++; $a++; } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php $uri_move = $this->uri->segment(3); 
            if($uri_move == 'move_supplier') {
        ?>

        <div class="choose-regions-partners left-tabs pure-menu pure-menu-horizontal padding-t-10 text-center">
            <ul class="pure-menu-list">
                <li class="pure-menu-item bg-tertiary box-but-noBtn padding-t-b-5 border-l-5 no-hover">
                    Choose Regions &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <select id="region" class="region pure-input-1-2 text-cl-grey font-14">
                        <option value="">Select Region</option>
                        <?php foreach ($get_region as $gr) {
                            echo "<option value=".$gr->id.">".$gr->region_id."</option>";
                        }
                        ?>
                    </select>
                </li>
                <li class="pure-menu-item bg-green box-but-noBtn padding-t-b-5 border-l-5 no-hover">
                    Choose Affiliates &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <select id="state" name="move_partner" class="get_partner pure-input-1-2 text-cl-grey font-14">
                        <option value="">Select Affiliate</option>
                    </select>
                </li>

                    <button type="submit" name="_submit" value="subgroup_move" class="pure-button btn-red btn-small text-cl-white font-14">Move</button>
            </ul>
        </div>
        <?php } ?>
            </form>
        </div>  
    </div>
</div>


<?php echo $pagination;?>


<script type="text/javascript">

$(document).ready(function(){
    $("select.region").change(function(){
        var selectedRegion = $(".region option:selected").val();
        $.ajax({
            url: "<?php echo site_url($role_link.'/manage_partner/get_partner_from_select');?>",
            type: "POST",
            data: { id : selectedRegion },
            dataType: "html",
            success: function (response) {
                $('.get_partner').html(response);
               // console.log(response);             

            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            }
        });



    });

});
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/__jquery.tablesorter.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/js/remodal.min.js"></script>

<script type="text/javascript">
            $(function() {
                $("table").tablesorter({debug: true});
            });
</script>


<script type="text/javascript">
    $(".checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });
</script>