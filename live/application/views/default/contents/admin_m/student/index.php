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
                <li id="breadcrum-home"><a href="#">
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
                    <a href="<?php echo site_url($role_link.'/region/detail/'.$partner->admin_regional_id);?>">
                        <?php echo @$this->common_function->get_info_region($partner->admin_regional_id)[0]->region_id;?>
                    </a>
                </li>
                <?php } ?>
                <?php if($role_link == 'admin'){ ?>
                    <li><a href="<?php echo site_url('admin_m/manage_partner');?>">Affiliate</a></li>
                <?php } ?>
                <li><a href="<?php echo site_url($role_link.'/manage_partner/detail/'.$partner_id);?>"><?php echo $partner->name;?></a></li>
               
                <?php if($role_link == 'superadmin'){?>
                <li><a href="<?php echo site_url($role_link.'/manage_partner/list_partner/student/'.$partner_id.'/'.$region_id);?>">
                <?php  }else if($role_link == 'admin') {?>
                <li><a href="<?php echo site_url($role_link.'/manage_partner/list_partner/student/'.$partner_id);?>">
                    <?php } 
                    
                        // foreach ($subgroup as $gsb) {
                        //      // if($gsb->subgroup_id == $this->uri->segment(5)){
                        //         echo ucfirst(@$gsb->name);
                        //      // }
                        //  } 
                        echo $subgroup[0]->name;
                    ?>
                    </a>
                </li>
                <li>
                    <form action="" autocomplete="on" class="search-box">
                        <div id="src__sign">Search..</div>
                      <input id="search" name="search" type="text" placeholder="Type here.."><input id="search_submit" value="Rechercher" type="submit">
                    </form>
                </li>
            </ul>
        </div>
    </div>  

    <div class="profilePic-top thumb-small left img-circle-big" style="border: 1px solid #fff;">
        <img src="<?php echo base_url($partner->profile_picture);?>" width="150" class="pure-img fit-cover-top" />
    </div>

    <div class="tag margint30">
        <h1 class="padding-l-15 text-cl-secondary font-semi-bold">&nbsp;&nbsp;<?php echo $partner->name;?> <em class="font-26">(Student Group)</em></h1>
        <span class="padding-l-18 text-cl-secondary font-18 font-semi-bold"><?php echo @$this->common_function->get_partner_type($partner->id); ?></span><br>
        <span class="padding-l-18 font-14 text-cl-secondary"><?php echo $partner->city.", ".$partner->state;?></span>
    </div>
    <!-- <form action="<?php echo site_url('admin_m/manage_partner/student_action/active/'.$subgroup_id.'/'.$partner_id);?>" method="POST"> -->

    <div class="left-tabs pure-menu pure-menu-horizontal padding-t-17 padding-l-170">
        <ul class="pure-menu-list padding-t-18 right">
            <li class="pure-menu-item padding-tb-9 no-hover">
                <?php
                if($this->auth_manager->role() == 'ADM' && @$status == 'active'){ ?>
             <!--    <button class="btn-small border-none bg-red text-cl-white height-32" type="submit" name="__submit" value="deactive_student">
                    <span>Deactive</span>
                </button> -->
                <?php } elseif($this->auth_manager->role() == 'ADM' && @$status == 'deactive') { ?>
                <button class="btn-small border-none bg-green text-cl-white height-32" type="submit" name="__submit" value="active_student">
                    <span>Active</span>
                </button>
                <?php } ?>
            </li>
            <li class="pure-menu-item padding-tb-9 no-hover">
                <a href="<?php echo $back;?>">
                    <button type="button" class="btn-small border-1-blue bg-white-fff text-cl-tertiary">
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
            <li class="pure-menu-item padding-tb-9 no-hover">
            <?php  if($status_set_setting == 4) { ?>
            <a href="<?php echo site_url('admin_m/partner_setting/setting_partner/coach');?>">
                <button class="btn-small border-1-grey bg-white-fff text-cl-grey" type="button">

                    <div class="right padding-l-5">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve" class="width15 path-grey">
                        <g id="Setting">
                            <g>
                                <g>
                                    <g>
                                        <g>
                                            <circle cx="20.001" cy="20" r="2.198"/>
                                            <path style="fill:none;stroke:#000000;stroke-miterlimit:10;" d="M30.554,18.121l-2.65-0.497
                                                c-0.161-0.536-0.372-1.05-0.634-1.534l1.533-2.242c0.142-0.207,0.112-0.52-0.066-0.697l-1.891-1.891
                                                c-0.178-0.178-0.49-0.207-0.696-0.065l-2.243,1.534c-0.498-0.268-1.028-0.484-1.581-0.646l-0.494-2.637
                                                c-0.046-0.247-0.287-0.447-0.539-0.447H18.62c-0.252,0-0.492,0.2-0.539,0.447l-0.5,2.662c-0.53,0.162-1.038,0.373-1.517,0.633
                                                l-2.214-1.515c-0.207-0.142-0.52-0.113-0.697,0.065l-1.891,1.891c-0.178,0.178-0.207,0.49-0.065,0.697l1.524,2.228
                                                c-0.257,0.479-0.465,0.987-0.624,1.516l-2.65,0.496C9.2,18.168,9,18.408,9,18.66v2.674c0,0.252,0.2,0.492,0.447,0.54
                                                l2.648,0.496c0.162,0.541,0.375,1.059,0.638,1.547l-1.507,2.203c-0.142,0.208-0.113,0.52,0.065,0.697l1.891,1.891
                                                c0.178,0.178,0.49,0.207,0.697,0.066l2.202-1.508c0.474,0.256,0.977,0.465,1.501,0.626l0.499,2.663
                                                c0.047,0.247,0.288,0.446,0.539,0.446h2.674c0.252,0,0.492-0.199,0.539-0.446l0.495-2.637c0.546-0.161,1.071-0.374,1.564-0.638
                                                l2.23,1.526c0.208,0.142,0.52,0.113,0.697-0.066l1.89-1.89c0.179-0.178,0.207-0.489,0.066-0.697l-1.518-2.217
                                                c0.268-0.493,0.483-1.018,0.648-1.565l2.648-0.496C30.8,21.827,31,21.586,31,21.335v-2.674
                                                C31.002,18.408,30.801,18.168,30.554,18.121z M20.001,24.589c-2.534,0-4.588-2.055-4.588-4.59c0-2.533,2.055-4.588,4.588-4.588
                                                c2.535,0,4.59,2.055,4.59,4.588C24.591,22.535,22.535,24.589,20.001,24.589z"/>
                                        </g>
                                    </g>
                                </g>
                                <g id="XMLID_3_">
                                    <path style="fill-rule:evenodd;clip-rule:evenodd;" d="M20,0c11.046,0,20,8.954,20,20s-8.954,20-20,20S0,31.046,0,20
                                        S8.954,0,20,0z M37.002,20c0-9.39-7.612-17.002-17.002-17.002C10.611,2.998,2.998,10.61,2.998,20
                                        c0,9.389,7.613,17.002,17.002,17.002C29.39,37.002,37.002,29.389,37.002,20z"/>
                                </g>
                            </g>
                        </g>
                        <g id="Layer_1">
                        </g>
                        </svg>
                    </div>
                    Student Group Setting
                </button>
                </a>
                <?php } ?>
            </li>
        </ul>
    </div>
</div>

<div class="box clear-both padding-t-20">
    <div class="heading pure-g">
   <!--      <div class="delete-add-btn padding-l-10">
            <div class="btn-noborder btn-normal bg-white-fff left"><a href="<?php echo site_url($role_link.'/manage_partner/add_partner_member/'.$partner_id);?>"><img src="<?php echo base_url('assets/img/iconmonstr-plus-6-16.png');?>" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-tertiary">Add Student To Group</em></a></div>
            <button class="btn-noborder btn-normal bg-white-fff" type="submit" value="delete_student" name="__submit"><img src="<?php echo base_url('assets/img/iconmonstr-x-mark-7-16.png');?>" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-red">Delete Student From Group</em></button>
        </div> -->

   
    <div class="left-list-tabs pure-menu pure-menu-horizontal padding-l-20">
        <ul class="pure-menu-list">
            <?php if($this->auth_manager->role() == 'ADM' && @$status == 'active'){ ?>
            <!-- <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('admin_m/manage_partner/member_of_student/active/'.$subgroup_id.'/'.$partner_id);?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 active-tabs-blue">Active Regions</a></li> -->
            <!-- <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('admin_m/manage_partner/member_of_student/deactive/'.$subgroup_id.'/'.$partner_id);?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">Inactive Regions</a></li> -->
            <?php } elseif($this->auth_manager->role() == 'ADM' && @$status == 'deactive'){ ?>
            <!-- <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('admin_m/manage_partner/member_of_student/active/'.$subgroup_id.'/'.$partner_id);?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">Active Regions</a></li> -->
            <!-- <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('admin_m/manage_partner/member_of_student/deactive/'.$subgroup_id.'/'.$partner_id);?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 active-tabs-blue">Inactive Regions</a></li> -->
            <?php } ?>
              <li class="pure-menu-item pure-menu-selected no-hover">
                    <a href="<?php echo site_url($role_link.'/manage_partner/partner/'.$type.'/'.$partner_id.'/'.@$partner->admin_regional_id);?>" class="pure-menu-link padding-t-b-5 font-14 font-semi-bold">
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

                    <a href="<?php echo site_url($role_link.'/manage_partner/list_partner/'.$type.'/'.$partner_id.'/'.@$partner->admin_regional_id);?>" class="pure-menu-link padding-t-b-5 font-14 font-semi-bold active-tabs-blue">
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



    </div>

    <div class="content padding-t-10">
        <div class="box">
        <form class="pure-g pure-u-md-24-24 pure-u-sm-24-24 pure-u-lg-24-24" action="<?php echo site_url('admin_m/manage_partner/delete_student/'.$this->uri->segment(6).'/'.$this->uri->segment(6));?>" method="POST">

                
                <table id="large" class="display table-session tablesorter" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">No</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Name</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Phone</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Email</th>               
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 2;
                        $a = $number_page;
                        $ph = 0;
                        foreach ($students as $student) { 
                          if($student->subgroup_id == $subgroup_id){
                    ?>
                    
                    <tr>
                        
                        
                        <td><?php echo $a?></td>
                        <td><a href="<?php echo site_url($role_link.'/manage_partner/student_detail/'.$partner_id.'/'.$student->id);?>" class="status-disable bg-green text-cl-white"><?php echo $student->fullname?></a></td>
                        <td><?php echo $student->dial_code.$student->phone;?></td>
                        <td><?php echo $student->email;?></td>
                    </tr>
                    <?php $no++; $a++; $ph++; } } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php echo $pagination;?>
</div>
</form>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/__jquery.tablesorter.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/js/remodal.min.js"></script>

<script type="text/javascript">
            $(function() {
                $("table").tablesorter({debug: true});
            });
</script>

<script>
    $(".checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });
</script>
<script type="text/javascript">
    $(function(){
        $('.list-people .box-info').css({'min-height':'110px'});
    })
</script>