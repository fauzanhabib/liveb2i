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
                <li><a href="<?php echo base_url();?>index.php/superadmin/region/index">Regions</a></li>
                <li>
                    <form action="" autocomplete="on" class="search-box" method="post">
                        <div id="src__sign">Search..</div>
                      <input id="search" name="search_region" type="text" placeholder="Type here.."><input id="search_submit" value="Rechercher" type="submit">
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <h1 class="margin0 left">List Of Regions</h1>

    <div class="padding-l-240 padding-t-5">
        <div class="btn-goBack left">
            <button class="btn-small border-1-blue bg-white-fff" onclick="goBack()">
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
        </div>
        <form action="<?php echo base_url();?>index.php/superadmin/region/region_inactive" method="post">
        <div class="padding-l-10 left">
            <button class="btn-small border-none bg-green text-cl-white height-32" type="submit" name="_submit" value="deactive" onclick="return confirm('Are you sure you want to active?')">
                <span>Active</span>
            </button>
        </div> 


        <div class="delete-add-btn right">
                <div class="btn-noborder btn-normal bg-white-fff left"><a href="<?php echo site_url('superadmin/region/add_region');?>"><img src="<?php echo base_url();?>assets/img/iconmonstr-plus-6-16.png" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-tertiary">Add Region</em></a></div>
                <button type="submit" class="btn-noborder btn-normal bg-white-fff" name="_submit" value="delete" onclick="return confirm('Are you sure you want to delete?')"><img src="<?php echo base_url();?>assets/img/iconmonstr-x-mark-7-16.png" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-red">Delete Region</em></button>
        </div>
    </div>
    
</div>

<div class="box clear-both padding-t-10">
    <div class="heading pure-g border-none clearfix">

        <div class="left-list-tabs pure-menu pure-menu-horizontal padding-l-40 left">
            <ul class="pure-menu-list">
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('superadmin/region/index/active');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">Active Regions</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('superadmin/region/index/deactivate');?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 active-tabs-blue">Deactive Regions</a></li>
            </ul>
        </div>
        
    </div>

    <div class="content">
        <div class="box">
            <div class="padding-l-40">
                <div class="checkbox-selectAll padding-b-20 padding-t-5">
                    <input type="checkbox" id="checkbox-1-1" name="Region" value="Region-1" class="regular-checkbox checkAll" /><label class="m-l-min35" for="checkbox-1-1"></label><em>&nbsp;&nbsp;Select All</em>
                </div>
             
                   <?php
                        if(!@$data){  ?>  
                            <div class="padding15">    
                                <div class="no-result">
                                    No Data
                                </div>
                            </div> 
                    <?php }   ?>

                <div class="box-lists pure-g">

      


                        
                    <?php $no = 2;
                     foreach ($data as $p) {
                        
                    ?>
                    <div class="box-info-list-checkbox grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 padding-b-20">
                        <input type="checkbox" id="checkbox-1-<?php echo $no;?>" name="check_list[]" value="<?php echo $p->id;?>" class="regular-checkbox" /><label class="left m-l-min35" for="checkbox-1-<?php echo $no;?>"></label>
                        <div class="box-info-list">
                            <div class="list-profile-pic left">
                                <a href="<?php echo site_url('superadmin/region/detail/'. $p->id); ?>" class="list-profile-pic-pic">
                                    <img src="<?php echo base_url().'/'.$p->profile_picture;?>" class="img-circle">
                                </a>
                            </div>

                            <div class="list-name-details margin-left70 width206">
                                <div class="list-name-details-region font-semi-bold font-18">
                                    <a href="<?php echo site_url('superadmin/region/detail/'. $p->id); ?>" class="text-grey-nodecoration">
                                        <?php 
                                            echo $p->region_id;
                                        ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php $no++; } ?>
                </div>

            </form>
            </div>
        </div>
    </div>  
</div>

<?php echo @$pagination;?>

        <script type="text/javascript" src="<?php echo base_url();?>assets/js/main.js"></script>
        <script type="text/javascript">
            $(".checkAll").change(function () {
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });
        </script>

        <script src="<?php echo base_url(); ?>assets/js/main.js"></script>

        <script type="text/javascript">
            $(function () {

                var width = $(".list-people .box-info").width();
                var height = $(".list-people .box-info").height();


                $('.list-people-action').css('width', width);
                $('.list-people-action').css('height', height);

                $('.list-people .box-info .detail table td:first-child').css({'width':'20%'});

                $(window).resize(function () {
                    var width = $(".list-people .box-info").width();
                    var height = $(".list-people .box-info").height();

                    $('.list-people-action').css('width', width);
                    $('.list-people-action').css('height', height);
                });


                $(document).on('mouseenter', '.list-people', function () {
                    $(this).find(".list-people-action").css({'opacity':'1','visibility':'inherit'});
                }).on('mouseleave', '.list-people', function () {
                    $(this).find(".list-people-action").css({'opacity':'0','visibility':'collapse'});
                });


                $('.edit-partner').click(function(){
                    return false;
                });

                $('.delete-partner').click(function(){
                    return false;
                });


            })
    </script>