<style>
  .tr_grey{
    background: #e2e2e2;
  }
</style>
<div class="heading text-cl-primary padding-l-20">

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
                <li><a href="<?php echo site_url('student_aff_m/subgroup') ?>">Group</a></li>
				<li><a href="#"><?php foreach ($data as $ds) {
                    if($ds->id == $subgroup_id){
                        echo $ds->name;
                    }
                } ?></a></li>
            </ul>
        </div>
    </div>
    <h1 class="margin0">Student Group Details</h1>


    <div class="profilePic-top thumb-small left img-circle-big" style="border: 1px solid #fff;">
        <img src="<?php echo base_url();?>index.php/account/identity/detail/profile" width="150" class="pure-img fit-cover-top" />
    </div>

    <div class="tag">
        <h1 class="padding-l-15 text-cl-secondary font-semi-bold">&nbsp;&nbsp;<?php foreach ($data as $ds) {
                    if($ds->id == $subgroup_id){
                        echo $ds->name;
                    }
                } ?>
            </h1>
        <span class="padding-l-18 text-cl-secondary font-18 font-semi-bold"><?php echo ucfirst($data[0]->type); ?> Affiliate</span><br>
        <!--<span class="padding-l-18 font-14 text-cl-secondary">Bangkok, Thailand</span>-->
    </div>

    <div class="left-tabs pure-menu pure-menu-horizontal padding-t-17 padding-l-170">
        <ul class="pure-menu-list padding-t-18 right">
            <li class="pure-menu-item padding-tb-9 no-hover">
                <button class="btn-small border-1-blue bg-white-fff height38">
				<a href="<?php echo site_url('student_aff_m/subgroup') ?>" class="text-cl-tertiary">
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
                </button></a>
            </li>

        </ul>
    </div>
</div>


<div class="box clear-both">

    <div class="heading heading-total-session pure-g prelative">
        <form class="pure-g pure-u-md-24-24 pure-u-sm-24-24 pure-u-lg-24-24" action="<?php echo site_url('student_aff_m/subgroup/delete_student/'.$this->uri->segment(4));?>" method="POST">

        <div class="delete-add-btn padding-l-10 padding-t-65 pure-u-md-8-24 pure-u-lg-10-24">
            <!-- <div class="btn-noborder btn-normal bg-white-fff left"><a href="<?php echo site_url('student_aff_m/subgroup/student/'.@$subgroup_id ); ?>"><img src="<?php echo base_url();?>assets/img/iconmonstr-plus-6-16.png" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-tertiary">Add Student To Group</em></a></div>
            <div class="btn-noborder btn-normal bg-white-fff left"><a href="<?php echo site_url('student_aff_m/adding/multiple_student/'.@$subgroup_id ); ?>"><img src="<?php echo base_url();?>assets/img/iconmonstr-plus-6-16.png" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-tertiary">Add Multiple Student To Group</em></a></div> -->
            <!-- <button class="btn-noborder btn-normal bg-white-fff" name="_submit" type="submit" onclick="return confirm('Are you sure you want to delete?')"><a><img src="<?php echo base_url();?>assets/img/iconmonstr-x-mark-7-16.png" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-red">Delete Student From Group</em></a></button>
            <button class="btn-noborder btn-normal bg-white-fff" name="_submit" type="submit" onclick="return confirm('Are you sure you want to enable?')"><a><img src="<?php echo base_url();?>assets/img/iconmonstr-plus-6-16.png" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-tertiary">Enable Student From Group</em></a></button> -->
        </div>

        <div class="pure-menu-horizontal pure-u-md-10-24 pure-u-lg-10-24 padding-r-10 right">
            <ul class="text-cl-grey">
                <li class="pure-menu-item no-hover no-hover">
                    <div class="total-box border-1-ccc width125 height70">
                        <div class="heading-total text-center border-b-1-ccc">
                            <span class="font-12">Total Sessions</span>
                        </div>
                        <div class="content-total text-center padding-t-10">
                            <span class="font-semi-bold font-26"><?php echo $total_sess_val; ?></span>
                        </div>
                    </div>
                </li>
                <li class="pure-menu-item no-hover">
                    <div class="total-box border-1-ccc width125 height70">
                        <div class="heading-total text-center border-b-1-ccc">
                            <span class="font-12">Total Students</span>
                        </div>
                        <div class="content-total text-center padding-t-10">
                            <span class="font-semi-bold font-26"><?php echo count($data2); ?></span>
                        </div>
                    </div>
                </li>
                <li class="pure-menu-item no-hover">
                    <div class="total-box border-1-ccc width125 height70">
                        <div class="heading-total text-center border-b-1-ccc">
                            <span class="font-12">Total Tokens</span>
                        </div>
                        <div class="content-total text-center padding-t-10">
                            <span class="font-semi-bold font-26"><?php $sum=0; foreach ($data2 as $d) { $sum += $d->token_amount; } ?><?php echo $sum; ?></span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

    </div>

    <div class="content padding-t-10">
       <div class="box">
            <div class="flex justify_content-sb padding-b-5">
                <div class="select-all flex">
                    <div class="padding-r-5 m-t-2">
                        <input type="checkbox" id="checkbox-1-0" name="Region" value="Region-1" class="regular-checkbox checkAll" /><label class="" for="checkbox-1-0"></label>
                    </div>
                    <div class="">
                        <label class="font-12">Select All</label>
                    </div>
                </div>
                <input type="text" id="myInput" class="font-12" onkeyup="myFunction()" placeholder="Search for names..">
            </div>
            <style type="text/css">
                div.pager {
                    text-align: center;
                    margin: 1em 0;
                }

                div.pager span {
                    display: inline-block;
                    width: 1.8em;
                    height: 1.8em;
                    line-height: 1.8;
                    text-align: center;
                    cursor: pointer;
                    /*background: #000;*/
                    color: #939393;
                    margin-right: 0.5em;
                    font-size: 14px;
                }

                div.pager span.active {
                    color: #3baae3;
                    /*background: #c00;*/
                }
            </style>

            <table id="large" class="display table-session tablesorter paginated" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="bg-secondary bg-none text-cl-white border-none" style="width:30px;"></th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">No</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Name</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Phone</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Email</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $a = $number_page;
                $no = 2;
                foreach ($data2 as $d) {
                  if($d->status == 'disable'){
                    $tr_class = 'tr_grey';
                  }else{
                    $tr_class = '';
                  }
                    ?>
                    <tr class="<?php echo $tr_class; ?>">
                        <td>
                            <input type="checkbox" id="checkbox-1-<?php echo $no;?>" name="check_list[]" value="<?php echo $d->id;?>" class="regular-checkbox" /><label for="checkbox-1-<?php echo $no;?>"></label>
                        </td>
                        <td><?php echo $a?></td>
                        <td><a href="<?php echo site_url('student_aff_m/member_list/student_detail/'.$d->id);?>" class="text-cl-tertiary"><u><?php echo $d->fullname?></u></a></td>
                        <td><?php echo $d->dial_code.$d->phone?></td>
                        <td><?php echo $d->email?></td>
                        <td><?php echo $d->status?></td>
                    </tr>
                    <?php $no++; $a++; } ?>
                </tbody>
            </table>
        </div>
    </div>

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
function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("large");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      if (td.innerText.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

$('#myInput').keypress(function(event){

    if (event.keyCode === 10 || event.keyCode === 13)
        event.preventDefault();

  });
</script>
<script>
$('td', 'table').each(function(i) {
    $(this).text();
});



$('table.paginated').each(function() {
    var currentPage = 0;
    var numPerPage = 10;
    var $table = $(this);
    $table.bind('repaginate', function() {
        $table.find('tbody tr').hide().slice(currentPage * numPerPage, (currentPage + 1) * numPerPage).show();
    });
    $table.trigger('repaginate');
    var numRows = $table.find('tbody tr').length;
    var numPages = Math.ceil(numRows / numPerPage);
    var $pager = $('<div class="pager"></div>');
    for (var page = 0; page < numPages; page++) {
        $('<span class="page-number"></span>').text(page + 1).bind('click', {
            newPage: page
        }, function(event) {
            currentPage = event.data['newPage'];
            $table.trigger('repaginate');
            $(this).addClass('active').siblings().removeClass('active');
        }).appendTo($pager).addClass('clickable');
    }
    $pager.insertAfter($table).find('span.page-number:first').addClass('active');
});
</script>
