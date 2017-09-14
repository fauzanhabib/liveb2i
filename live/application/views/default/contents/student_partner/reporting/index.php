<style>
    .table-session td, .table-sessions td{
        padding: 5px 0px !important;
    }
    .table-session, .table-sessions {
        padding: 0px !important;
    }
    .dataTables_wrapper .dataTables_filter {
        padding: 8px !important;
        overflow-x: scroll !important;
    }
    table.dataTable thead .sorting, table.dataTable thead .sorting_asc, table.dataTable thead .sorting_desc {
        background-image: none;
    }
    label {
        display: inline;
        font-size: 19px !important;
        font-weight: 600;
        color: #585858;   
    }
    label input {
        /*display: inline;*/
        font-size: 16px !important;
        font-weight: 100 !important;    
    }
    #large_wrapper{
        overflow-x: auto !important;
    }
</style>

<div class="heading text-cl-primary padding-l-20">
    <h1 class="margin0">Add Tokens</h1>
</div>


<div class="box clear-both">
    <div class="heading pure-g padding-t-30">
        <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
            <ul class="pure-menu-list">
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('student_partner/add_token/index');?>">Add Token per Student</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('student_partner/add_token/group_index');?>" >Add Token per Group</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('student_partner/refund_token');?>" >Refund Token</a></li>
            </ul>
        </div>
    </div>
    <div class="content padding-t-10">
       <div class="box">
            <div class="text-center">
                <ul class="coaching-info-big m-tb-0 padding-l-0 padding-t-25">
                    <li class="coaching-info-box-big margin-auto clearfix">
                        <div class="coaching-box-left-big">
                            <span>Undistributed Tokens</span>
                        </div>
                        <div class="coaching-box-right-big">
                            <div style="padding: 14px 0 !important;"><?php echo $token; ?></div>
                        </div>
                    </li>
                </ul>
            </div>   
            <!-- <div class="select-all">                             
                <div class="padding-r-5 m-t-2 left">
                    <input type="checkbox" id="checkbox-1-0" name="Region" value="Region-1" class="regular-checkbox checkAll" /><label class="" for="checkbox-1-0"></label>
                </div>
                <div class="">
                    <label class="font-12" style="color: #212121;">Select All</label>
                </div>
            </div> -->
            <script>
                $(document).ready(function() {
                    // $('#large_wrapper').hide();
                    // $('#large_wrapper').css('overflow-x','auto !important');

                    $('#large').DataTable( {
                      "bLengthChange": false,
                      "searching": true,
                      "userTable": false,
                      "bInfo" : false,
                      "bPaginate": true,
                      "pageLength": 10,
                      "scrollX": true
                    });

                    // $('#large_wrapper').addClass("asdf");
                    // $('#large_wrapper').css('overflow-x','auto !important');

                } );
            </script>
            <table id="large" class="display table-session tablesorter" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <!-- <th class="bg-secondary bg-none text-cl-white border-none" style="width:30px;"></th> -->
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Name</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Token</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Amount To Add</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;"></th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Group</th>               
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $a = 1;
                $no = 1;
                foreach ($data2 as $d) {
                    $get_subgroup = $this->db->select('s.name')
                    ->from('subgroup s')
                    ->join('user_profiles up','up.subgroup_id = s.id')
                    ->where('user_id',$d->id)
                    ->get()->result();
                    ?>
                    <tr onclick="myFunction(this)">
                        <!-- <td>
                            <input type="checkbox" id="checkbox-1-<?php echo $no;?>" name="check_list[]" value="<?php echo $d->id;?>" class="regular-checkbox" /><label for="checkbox-1-<?php echo $no;?>"></label>
                        </td> -->
                        <td class="">
                            <div class="margin-auto">
                                <a href="<?php echo site_url('student_partner/member_list/student_detail/'.$d->id);?>" class="text-cl-tertiary"><u><?php echo $d->fullname?></u></a>
                            </div>    
                        </td>
                        <td><?php echo $d->token_amount?></td>
                        <td>
                        <form name ="addtoken" action="<?php echo(site_url('student_partner/add_token/action_add_token'));?>" method="post" onsubmit="return confirm('This action can not be undo');">
                            <input class="width50perc bg-white-fff padding2 border-1-ccc padding3" style="border:none" type="text" name="single_token" value="" id="input<?php echo $no;?>" onkeypress="return isNumber(event)">
                        </td>
                        <td style="width:10%;">
                            <input type="hidden" name="std_id" value="<?php echo $d->id?>">
                            <input type="hidden" name="subgroup_id" value="<?php echo $subgroup_id?>">
                            <button class="pure-button btn-blue btn-small" id="buttonadd<?php echo $no;?>" style="display: none;">Add</button>
                            </form>
                        </td>
                        <td><?php echo $get_subgroup[0]->name?></td>
                    </tr>
                    <script type="text/javascript">

                        $('#input<?php echo $no;?>').keyup(function(){
                          var total = <?php echo $total_coach;?>;
                          var i = 0;
                          if($(this).val().length)
                            $('#buttonadd<?php echo $no;?>').show();
                           else
                            $('#buttonadd<?php echo $no;?>').hide();
                          
                        });
                    </script>
                    <?php $no++; $a++; } ?>
                </tbody>
            </table>
        </div>
    </div>
 
</div>

<script src="<?php echo base_url(); ?>assets/js/main.js"></script>
                       
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/__jquery.tablesorter.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/js/remodal.min.js"></script>

<script type="text/javascript">
    function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>
<script>
function myFunction(x) {
    console.log(x.rowIndex);
    
    $('input[id=input'+x.rowIndex+']').keyup(function(){
      if($(this).val().length){
        $('#buttonadd'+x.rowIndex).show();
      }
      else{
        $('#buttonadd'+x.rowIndex).hide();
      }
    });
}
</script>
<!-- <script type="text/javascript">

    $('input[name=single_token]').keyup(function(){
      var total = <?php echo $total_coach;?>;
      var i = 0;
      if($(this).val().length)
        $('#buttonadd'+i).show();
       else
        $('#buttonadd'+i).hide();
      
    });
</script> -->

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