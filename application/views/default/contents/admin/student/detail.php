<?php if($this->auth_manager->role() == 'RAD') {
    $role_link = "superadmin";
} else {
    $role_link = "admin";
}

?>
<div class="heading text-cl-primary padding15">

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
                <li><a href="<?php echo site_url($role_link.'/region/index/active');?>">Regions</a></li>
                <li><a href="<?php echo site_url($role_link.'/manage_partner/detail/'.$partner_id); ?>"><?php echo $partner->name;?></a></li>
                <li><a href="#">Student</a></li>
<!--                 <li>
                    <form action="" autocomplete="on" class="search-box">
                      <input id="search" name="search" type="text" placeholder="Type here.."><input id="search_submit" value="Rechercher" type="submit">
                    </form>
                </li> -->
            </ul>
        </div>
    </div>

    <h1 class="margin0 left padding-r-10"><?php echo(@$student[0]->fullname); ?> <em class="font-26">(Student Session)</em></h1>

    <div class="btn-goBack padding-t-5">
        <button class="btn-small border-1-blue bg-white-fff" onclick="goBack()">
            <div class="left padding-r-5">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve" class="width15">
                        <g id="back-one-page">
                            <g>
                                <g id="XMLID_13_">
                                    <g>
                                        <path style="fill-rule:evenodd;clip-rule:evenodd;fill:#51B3E8;" d="M20,0c11.046,0,20,8.954,20,20s-8.954,20-20,20
                                            S0,31.046,0,20S8.954,0,20,0z M37.002,20c0-9.39-7.612-17.002-17.002-17.002C10.611,2.998,2.998,10.61,2.998,20
                                            c0,9.389,7.613,17.002,17.002,17.002C29.39,37.002,37.002,29.389,37.002,20z"></path>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <g>
                                            <path style="fill:#51B3E8;" d="M27.734,22.141H13.636c-1.182,0-2.141-0.958-2.141-2.141s0.959-2.141,2.141-2.141h14.098
                                                c1.182,0,2.141,0.958,2.141,2.141S28.916,22.141,27.734,22.141z"></path>
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <g>
                                                <path style="fill:#51B3E8;" d="M19.465,24.27l-2.611-2.822c-0.756-0.818-0.756-2.08,0-2.897l2.611-2.822
                                                    c1.264-1.366,0.295-3.582-1.566-3.582h-0.353c-0.595,0-1.162,0.248-1.566,0.685l-5.288,5.719
                                                    c-0.756,0.817-0.756,2.079,0,2.896l5.288,5.719c0.404,0.437,0.971,0.685,1.566,0.685h0.353
                                                    C19.76,27.852,20.729,25.636,19.465,24.27z"></path>
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
    </div>
</div>
    <div class="box">
        <div class="content">
            <div class="box pure-g">

                <!-- block photo -->
            <div class="pure-u-6-24 text-center profile-image">
                <div class="border-none">
                        <img src="<?php echo base_url(@$student[0]->profile_picture);?>" width="200" height="200" class="pure-img fit-cover img-circle-big" />
                        <h4 class="text-cl-tertiary line-height0"><?php echo @$student[0]->fullname?></h4>
                        <h5 class="font-12"><!-- Bandung, Indonesia --></h5>
                </div>
                <div class="padding-t-10 m-b-15 text-center">
                    <div class="padding10" style="width:85%;margin:0 auto">
                        <!-- <a href="<?php echo site_url('student_partner/vrm/single_student/'.@$student[0]->id);?>" class="pure-button btn-small btn-white m-t-10">VIEW REPORT</a> -->
                        <?php if($this->auth_manager->role() == 'CCH'){

                    }else{ ?>
                        <a href="<?php echo site_url($role_link.'/student_upcoming_session/one_to_one_session/'.@$student[0]->id);?>" class="pure-button btn-small btn-white m-t-10">SESSIONS</a>
                    <?php } ?>
                        <!-- <a href="<?php echo site_url('student_partner/member_list/add_token/'.@$student[0]->id).'/'.$subgroup_id;?>" class="pure-button btn-small btn-white m-t-10">ADD TOKEN</a> -->
                    </div>
                </div>
            </div>           
                <!-- end block photo -->


                <div class="pure-u-18-24 profile-detail prelative">
                    <?php echo form_open($role_link.'/manage_partner/update_partner/'.$partner_id, 'role="form" id="form_info" data-parsley-validate' );?>
                    <table class="table-no-border2"> 
                        <tbody>
                            <tr>
                                <td class="pad15">Name</td>
                                <td>
                                    <span class=""><?php echo @$student[0]->fullname?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="pad15">Email Address</td>
                                <td>
                                    <span class=""><?php echo @$student[0]->email;?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="pad15">Birthdate</td>
                                <td>
                                    <span class=""><?php echo @$student[0]->date_of_birth;?></span>
                                </td>
                            </tr>

                            <tr>
                                <td class="pad15">Spoken Language</td>
                                <td>
                                    <span class=""><?php echo str_replace('#', ', ', @$student[0]->spoken_language, $multiplier) ; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="pad15">Gender</td>
                                <td>
                                    <span class=""><?php echo @$student[0]->gender;?></span>

                                </td>
                            </tr>

                        </tbody>    
                    </table>              
                </div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>

<?php echo form_open('admin/manage_partner/update_partner/'.$partner_id, 'role="form" id="form_info" data-parsley-validate' );?>
    <div class="box clearfix">
        <div class="content-title m-lr-20">
            More Info
        </div>

        <div class="content">
            <div class="pure-u-24-24 prelative">
                <table class="table-no-border2"> 
                    <tbody>
                        <tr>
                            <td class="pad15">Tokens</td>
                            <td><span class=""><?php echo @$student[0]->token_amount;?></span></td>
                        </tr>
                        <tr>
                            <td class="pad15">Skype ID</td>
                            <td><span class=""><?php echo @$student[0]->skype_id;?></span></td>
                        </tr>
                        <tr>
                            <td class="pad15">Certification Goal</td>
                            <td><span class=""><?php echo @$student[0]->language_goal;?></span></td>
                        </tr>
                        <tr>
                            <td class="pad15">Hobbies</td>
                            <td><span class=""><?php echo @$student[0]->hobby;?></span></span></td>
                        </tr>
                        <tr>
                            <td class="pad15">Likes</td>
                            <td><span class=""><?php echo @$student[0]->like;?></span></td>
                        </tr>
                    </tbody>    
                </table>
            </div>  
        </div>   
    </div>
    <?php echo form_close();?>


