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
                <li><a href="#">Group</a></li>
				<li>
                    <form action="<?php echo site_url('student_partner/subgroup');?>" method="POST" autocomplete="on" class="search-box">
                        Search..
                      <input id="search" name="search_subgroup" type="text" placeholder="Type here.."><input id="search_submit" value="Rechercher" type="submit">
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <h1 class="margin0 left">Group</h1>

   <!--  <div class="padding-l-10 padding-t-5 left">
        <button class="btn-small border-none bg-green text-cl-white height-32">
            <img src="<?php echo base_url();?>assets/img/iconmonstr-download-12-16.png" class="padding-r-5 left"><span>Export Report To CSV</span>
        </button>
    </div> 
 -->
</div>

<div class="box clear-both">

    <div class="heading heading-total-session pure-g prelative">
        <form action="<?php echo site_url('student_partner/subgroup/enable_subgroup');?>" method="POST" class="pure-g pure-u-md-24-24 pure-u-sm-24-24 pure-u-lg-24-24">
        <div class="delete-add-btn padding-l-10 padding-t-65 pure-u-md-8-24 pure-u-lg-8-24">
            <!-- <div class="btn-noborder btn-normal bg-white-fff left"><a href="<?php echo site_url('student_partner/subgroup/add_subgroup');?>"><img src="<?php echo base_url();?>assets/img/iconmonstr-plus-6-16.png" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-tertiary">Add Group</em></a></div> -->
            <button class="btn-noborder btn-normal bg-white-fff" type="submit" name="__submit" value="enable_subgroup" onclick="return confirm('Are you sure you want to enable group?')"><img src="<?php echo base_url();?>assets/img/iconmonstr-x-mark-7-16.png" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-red">Enable Group</em></button>
        </div>

        <div class="pure-menu-horizontal pure-u-md-10-24 pure-u-lg-10-24 padding-r-10 right">
            <ul class="text-cl-grey">
                <li class="pure-menu-item no-hover no-hover">
                    <div class="total-box border-1-ccc width125 height70">
                        <div class="heading-total text-center border-b-1-ccc">
                            <span class="font-12">Session Total</span>
                        </div>
                        <div class="content-total text-center padding-t-10">
                            <span class="font-semi-bold font-26"><?php echo $total_sess_val; ?></span>
                        </div>
                    </div>
                </li>
                <li class="pure-menu-item no-hover">
                    <div class="total-box border-1-ccc width125 height70">
                        <div class="heading-total text-center border-b-1-ccc">
                            <span class="font-12">Student Total</span>
                        </div>
                        <div class="content-total text-center padding-t-10">
                            <span class="font-semi-bold font-26"><?php echo count($data2); ?></span>
                        </div>
                    </div>
                </li>
                <li class="pure-menu-item no-hover">
                    <div class="total-box border-1-ccc width125 height70">
                        <div class="heading-total text-center border-b-1-ccc">
                            <span class="font-12">Token Total</span>
                        </div>
                        <div class="content-total text-center padding-t-10">
                            <span class="font-semi-bold font-26"><?php $sum=0; foreach ($data2 as $d) { $sum += $d->token_amount; } ?><?php echo $sum; ?></span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

    </div>
    <div class="heading pure-g padding-t-30">
        <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
            <ul class="pure-menu-list">
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('student_partner/subgroup/');?>">Active Groups</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('student_partner/subgroup/index_disable/');?>" >Disabled Groups</a></li>
            </ul>
        </div>
    </div>

    <div class="content padding-t-0">
       <div class="box">   
            <div class="select-all">                             
                <div class="padding-r-5 m-t-2 left">
                    <input type="checkbox" id="checkbox-1-0" name="Region" value="Region-1" class="regular-checkbox checkAll" /><label class="" for="checkbox-1-0"></label>
                </div>
                <div class="">
                    <label class="font-14">Select All</label>
                </div>
            </div>
            <table id="large" class="display table-session tablesorter" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="bg-secondary bg-none text-cl-white border-none" style=""></th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">No</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Group</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Type</th>
                        </tr>
                </thead>
                <tbody>
    <?php
    $no = 2;
                $a = 1;
                foreach ($data as $p) {
                    ?>
                    <tr>
                        <td class="text-left">
                    <input type="checkbox" id="checkbox-1-<?php echo $no;?>" name="check_list[]" value="<?php echo $p->id;?>" class="regular-checkbox" /><label for="checkbox-1-<?php echo $no;?>"></label>
                        </td>
                        <td><?php echo $a; ?></td>
                        <td><a href="<?php echo site_url('student_partner/subgroup/list_disable_student/'. $p->id); ?>" class="text-cl-tertiary"><u><?php echo $p->name?><u></a></td>
                        <td><?php echo ucfirst($p->type); ?> Affiliate</td>
                                </tr>
                    <?php $no++; $a++; } ?>
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


<script type="text/javascript">
    $(".checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });
</script>