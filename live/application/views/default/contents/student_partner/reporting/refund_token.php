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
                <li class="pure-menu-item pure-menu-selected text-center no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('student_partner/add_token/index');?>">Add Token per Student</a></li>
                <li class="pure-menu-item pure-menu-selected text-center no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('student_partner/add_token/group_index');?>" >Add Token per Group</a></li>
                <li class="pure-menu-item pure-menu-selected text-center no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('student_partner/refund_token');?>" >Refund Token</a></li>
            </ul>
        </div>
    </div>
    <div class="content padding-t-10">
       <div class="box">
            <div class="text-center">
                <ul class="coaching-info-big m-tb-0 padding-l-0 padding-t-25">
                    <li class="coaching-info-box-big margin-auto">
                        <div class="coaching-box-left-big">
                            <span>Undistributed Tokens</span>
                        </div>
                        <div class="coaching-box-right-big">
                            <div style="padding: 14px 0 !important;"><?php echo $token; ?></div>
                        </div>
                    </li>
                </ul>
            </div>   

            <script>
                $(document).ready(function() {


                    $('#large').DataTable( {
                      "bLengthChange": false,
                      "searching": true,
                      "userTable": false,
                      "bInfo" : false,
                      "bPaginate": true,
                      "pageLength": 10,
                      "scrollX": true
                    });


                } );
            </script>
            <table id="large" class="display table-session tablesorter" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Name</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Token Refund</th>
                        <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Action</th>
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
                       
                        <td class="">
                            <div class="margin-auto">
                                <a href="<?php echo site_url('student_partner/member_list/student_detail/'.$d->id);?>" class="text-cl-tertiary"><u><?php echo $d->fullname?></u></a>
                            </div>    
                        </td>

                        <?php
                        // ============
                        $data_token_student = $this->db->select('token_amount')->from('user_tokens')->where('user_id', $d->id)->get()->result();

                        $data_token_refund = $this->db->select('token_amount, balance')
                                                  ->from('token_histories')
                                                  ->where('user_id', $d->id)
                                                  ->where('token_status_id',15)
                                                  ->order_by('id','desc')
                                                  ->get()->result();
                  
                        $token_student = @$data_token_student[0]->token_amount;

                        $token_amount = @$data_token_refund[0]->token_amount;
                        $balance = @$data_token_refund[0]->balance;

                        $status = '';
                        if($token_student == $balance){
                            $status = 1;
                        } 
                        if($token_student != $balance) {
                            $status = 0;
                        }
                        if(!$data_token_refund){
                            $status = 3;
                        }
                        // ============
                        ?>

                        
                        <?php 
                        if($status == 1){ ?>
                        <td>
                            <form action="<?php echo $link?>" method="POST">
                            <input type="hidden" name="token_refund" value="<?php echo $token_amount?>">
                            <input type="hidden" name="student_id" value="<?php echo $d->id?>">
                            <?php echo $token_amount?>
                        </td>
                        <td> 
                            <button type="submit" class="pure-button btn-blue btn-small" id="buttonadd<?php echo $no;?>" value="<?php echo $d->token_amount;?>">Refund</button> 
                            </form>
                        </td>
                            
                        <?php } else { ?>
                        <td>
                            <?php if($token_amount){
                                echo $token_amount." (already refunded)";
                            } else {
                                echo "0";
                            }
                            ?> 
                        </td>
                        <td> <span class="labels danger" id="buttonadd<?php echo $no;?>" style="width: 120px;">Can't refund tokens</span> </td>
                        <?php } ?>
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