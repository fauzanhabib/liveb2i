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
                <li><a href="#">Regions</a></li>
                <li><a href="#">Indonesia</a></li>
                <li><a href="#">Development Solutions</a></li>
                <li><a href="#">Couch Group List</a></li>
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
    <form action="<?php echo site_url('admin/manage_partner/student_action/deactive/'.$subgroup_id.'/'.$partner_id);?>" method="POST">

    <div class="left-tabs pure-menu pure-menu-horizontal padding-t-17 padding-l-170">
        <ul class="pure-menu-list padding-t-18 right">
            <li class="pure-menu-item padding-tb-9 no-hover">
                <?php
                if($this->auth_manager->role() == 'ADM' && @$status == 'active'){ ?>
                <!-- <button class="btn-small border-none bg-red text-cl-white height-32" type="submit" name="__submit" value="deactive_student">
                    <span>Deactive</span>
                </button> -->
                <?php } elseif($this->auth_manager->role() == 'ADM' && @$status == 'deactive') { ?>
        <!--         <button class="btn-small border-none bg-green text-cl-white height-32" type="submit" name="__submit" value="active_student">
                    <span>Active</span>
                </button> -->
                <?php } ?>
            </li>
            <li class="pure-menu-item padding-tb-9 no-hover">
                <button class="btn-small border-1-blue bg-white-fff text-cl-tertiary" onclick="goBack()">
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
            </li>
            <li class="pure-menu-item padding-tb-9 no-hover">
                <button class="btn-small border-1-grey bg-white-fff text-cl-grey">
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
            </li>
        </ul>
    </div>
</div>

<div class="box clear-both padding-t-20">
    <div class="heading pure-g">
        <div class="delete-add-btn padding-l-10">
            <div class="btn-noborder btn-normal bg-white-fff left"><a href="<?php echo site_url($role_link.'/manage_partner/add_partner_member/'.$partner_id);?>"><img src="<?php echo base_url('assets/img/iconmonstr-plus-6-16.png');?>" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-tertiary">Add Student To Group</em></a></div>
            <button class="btn-noborder btn-normal bg-white-fff" type="submit" value="delete_student" name="__submit"><img src="<?php echo base_url('assets/img/iconmonstr-x-mark-7-16.png');?>" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-red">Delete Student From Group</em></button>
        </div>

   
    <div class="left-list-tabs pure-menu pure-menu-horizontal padding-l-20">
        <ul class="pure-menu-list">
            <?php if($this->auth_manager->role() == 'ADM' && @$status == 'active'){ ?>
            <!-- <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('admin/manage_partner/member_of_student/active/'.$subgroup_id.'/'.$partner_id);?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 active-tabs-blue">Active Regions</a></li> -->
            <!-- <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('admin/manage_partner/member_of_student/deactive/'.$subgroup_id.'/'.$partner_id);?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">Inactive Regions</a></li> -->
            <?php } elseif($this->auth_manager->role() == 'ADM' && @$status == 'deactive'){ ?>
            <!-- <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('admin/manage_partner/member_of_student/active/'.$subgroup_id.'/'.$partner_id);?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">Active Regions</a></li> -->
            <!-- <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('admin/manage_partner/member_of_student/deactive/'.$subgroup_id.'/'.$partner_id);?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 active-tabs-blue">Inactive Regions</a></li> -->
            <?php } ?>
            
        </ul>
    </div>

<!--         <div class="delete-add-btn">
            <div class="btn-noborder btn-normal bg-white-fff left"><a href=""><img src="<?php echo base_url('assets/img/iconmonstr-plus-6-16.png');?>" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-tertiary">Add Student To Group</em></a></div>
            <button class="btn-noborder btn-normal bg-white-fff"><a href=""><img src="<?php echo base_url('assets/img/iconmonstr-x-mark-7-16.png');?>" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-red">Delete Student From Group</em></a></button>
        </div> -->

    </div>

    <div class="content">
        <div class="box">
            <div class="padding-l-40">
                <div class="checkbox-selectAll padding-b-20 padding-t-5">
                    <!-- <input type="checkbox" id="checkbox-1-1" name="Region" value="Region-1" class="regular-checkbox checkAll" /><label class="m-l-min35" for="checkbox-1-1"></label><em>&nbsp;&nbsp;Select All</em> -->
                </div>
                <div class="box-lists pure-g">
                    <?php 
                        $no = 2;
                        foreach ($students as $student) { 
                          if($student->subgroup_id == $subgroup_id){
                    ?>
                    
                    <div class="box-info-list-checkbox grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 padding-b-20">
                        <!-- <input type="checkbox" id="checkbox-1-<?php echo $no;?>" name="check_list[]" value="<?php echo $student->id;?>" class="regular-checkbox" /><label class="left m-l-min35" for="checkbox-1-<?php echo $no;?>"></label> -->
                        <div class="box-info-list">
                            <div class="list-profile-pic left">
                                <a href="<?php echo site_url($role_link.'/manage_partner/student_detail/'.$partner_id.'/'.$student->id);?>" class="list-profile-pic-pic">
                                    <img src="<?php echo base_url($student->profile_picture);?>" class="img-circle">
                                </a>
                            </div>

                            <div class="list-name-details margin-left70 width206 text-center">
                                <br>
                                <a href="<?php echo site_url($role_link.'/manage_partner/student_detail/'.$partner_id.'/'.$student->id);?>" class="text-grey-nodecoration">
                                <span class="font-semi-bold font-18"><?php echo $student->fullname;?></span>
                                <h5 class="margin0"><?php echo $student->email;?></h5>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php $no++; } } ?>

                </form>
                   
                </div>
            </div>
        </div>
    </div>  
</div>
<?php echo @$pagination;?>

<script type="text/javascript">
    $(function(){
        $('.list-people .box-info').css({'min-height':'110px'});
    })
</script>