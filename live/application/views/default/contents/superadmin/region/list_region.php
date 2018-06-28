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

    <h1 class="margin0">List Of Regions</h1>
</div>

    <div class="box">
        <div class="heading pure-g">


            <form action="<?php echo base_url();?>index.php/superadmin/region/region_delete" method="post">


            <div class="delete-add-btn">
                <div class="btn-noborder btn-normal bg-white-fff left"><a href="<?php echo site_url('superadmin/region/add_region');?>"><img src="<?php echo base_url();?>assets/img/iconmonstr-plus-6-16.png" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-tertiary">Add Region</em></a></div>
                <button class="btn-noborder btn-normal bg-white-fff"><a href=""><img src="<?php echo base_url();?>assets/img/iconmonstr-x-mark-7-16.png" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-red">Delete Region</em></a></button>
            </div>
            

        </div>

        <div class="content">
            <div class="box">
                <div class="padding-l-70">
                    <div class="checkbox-selectAll padding-b-20 padding-t-5">
                        <input type="checkbox" id="checkbox-1-1" name="Region" value="Region-1" class="regular-checkbox checkAll" /><label class="m-l-min35" for="checkbox-1-1"></label><em>&nbsp;&nbsp;Select All</em>
                    </div>
                    <div class="box-lists pure-g">
                    <?php
                    $no = 2;
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
                                        <?php 
                                            echo $p->region_id;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php $no++;  } ?>

                    </div>
                </div>
            </div>
        </div> 
        </form> 
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