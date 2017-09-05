<style>
    .bordered{
        border: 1px solid #f3f3f3 !important;
    }
    .detailsbtn{
        padding: 2px;
        background: #59ba82;
        color: white;
        font-size: 11px;
        border-radius: 4px;
    }
</style>

    <div class="heading text-cl-primary border-b-1 padding15">

        <h2 class="margin0">Token History</h2>

    </div>

    <div class="box clear-both">
        <div class="heading pure-g padding-t-30">

            <div class="left-list-tabs pure-menu pure-menu-horizontal padding-l-25 margin0">
                <ul class="pure-menu-list">
                    <li class="pure-menu-item pure-menu-selected text-center width150 no-hover"><a href="<?php echo site_url('student/token'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue">Token History</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width150 no-hover"><a href="<?php echo site_url('student/token_requests'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Token Request</a></li>
                </ul>
            </div>

        </div>

        <div class="content">
            <div class="box">
                 <?php 
                    echo form_open('student/token/search', 'class="pure-form filter-form" style="border:none"'); 
                    ?>
                <div class="pure-u-1 text-center m-t-20">
                    <div class="frm-date" style="display:inline-block">
                        <input name="date_from" class="datepicker frm-date margin0" type="text" readonly="" placeholder="Start Date">  
                        <span class="icon icon-date"></span>
                    </div>
                    <div class="frm-date" style="display:inline-block">
                        <input name="date_to" class="datepicker2 frm-date margin0" type="text" readonly="" placeholder="End Date">  
                        <span class="icon icon-date"></span>
                    </div>
                    <input type="submit" name="__submit" value="Go" class="pure-button btn-small btn-tertiary border-rounded height-32" style="margin:0px 10px;" />
                </div>

                <script>
                    $(document).ready(function() {
                        $('#userTable').DataTable( {
                          "bLengthChange": false,
                          "searching": false,
                          "userTable": false,
                          "bInfo" : false,
                          "bPaginate": false
                        });
                    } );
                </script>
                <?php echo form_close(); ?>
                <?php
                if(!@$histories){
                    echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
                }
                else {
                ?>
                <table id="" class="display table-session" cellspacing="0" width="100%" style="margin-top: 10px;">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-cl-tertiary bordered font-light font-16 border-none text-center">TRANSACTION</th>
                            <th rowspan="2" class="text-cl-tertiary bordered font-light font-16 border-none text-center">DESCRIPTION</th>
                            <th rowspan="2" class="text-cl-tertiary bordered font-light font-16 border-none text-center">STATUS</th>
                            <th colspan="3" class="text-cl-tertiary bordered font-light font-16 border-none text-center">TOKENS</th>
                            <!-- <th class="text-cl-tertiary bordered font-light font-16 border-none text-center">BALANCE</th>                -->
                        </tr>
                        <tr>
                            <td class="text-cl-tertiary bordered font-light font-16 border-none text-center">DEBIT</td>
                            <td class="text-cl-tertiary bordered font-light font-16 border-none text-center">CREDIT</td>
                            <td class="text-cl-tertiary bordered font-light font-16 border-none text-center">BALANCE</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $detcount = 0; foreach (@$histories as $history) { 
                            $gmt_user = $this->identity_model->new_get_gmt($this->auth_manager->userid());
                            $new_gmt = 0;
                                if($gmt_user[0]->gmt > 0){
                                    $new_gmt = '+'.$gmt_user[0]->gmt;
                                }else{
                                    $new_gmt = $gmt_user[0]->gmt;    
                                }
                            $a = '';
                            if(substr($history->status, 0,4) == 'Refu'){
                                $a = 'Deli';
                            }
                            ?>
                        <tr class="text-center">
                            <td>
                                <span>
                                    <?php 
                                    date_default_timezone_set('UTC');
                                    $dt     = date('H:i:s',$history->transaction_date);
                                    $default_dt  = strtotime($dt);
                                    $usertime = $default_dt+(60*$minutes);
                                    $hour = date("H:i:s", $usertime); 


                                    $date     = date('F d, Y',$history->transaction_date);
                             
                                    echo $date." ".$hour;
                                    ?>
                                </span>
                            </td>
                            <td>
                               <span>
                                <?php  
                                    // echo $history->description;
                                    $des = explode(" ",$history->description);
                                    if($des[0] == 'Session'){
                                    $t = count($des);
                                    
                                    $f_dt_at = $t-3;
                                    $f_dt_until = $t-1;
                                    if($des[$t-6] == 'on'){
                                        $f_date = $t-5;
                                    }else{
                                        $f_date = $t-4;
                                    }
                                    $CI =& get_instance();
                                    $CI->load->library('schedule_function');
                                    $convert = $CI->schedule_function->convert_book_schedule(($this->identity_model->new_get_gmt($this->auth_manager->userid())[0]->minutes), strtotime($des[$f_date]), $des[$f_dt_at], $des[$f_dt_until]);
                                    $date_token = $convert['date'];
                                    $dateconvert = date('Y-m-d', $date_token);
                                    

                                        $default_dt_at  = strtotime($des[$f_dt_at]);
                                        $usertime_at = $default_dt_at+(60*$minutes);
                                        $at = date("H:i", $usertime_at);
                                        
                                        $default_dt_until  = strtotime($des[$f_dt_until]);
                                        $usertime_until = $default_dt_until+(60*$minutes);
                                        $usertime_until_diff = $usertime_until-(60*5);
                                        $until = date("H:i", $usertime_until_diff);

                                       
                                            $des[$f_dt_at] = $at;
                                            $des[$f_dt_until] = $until;
                                            $des[$f_date] = $dateconvert;
                                       
                                        
                                        for($a=0; $a<count($des); $a++){
                                            echo $des[$a]." ";
                                        } echo '(UTC '.$new_gmt.')';
                                    } else {
                                        echo $history->status_description;
                                    }

                                    // if(@$history->status == 'Refund'){
                                    //     echo ' <a id="detail'.$detcount.'" class="detailsbtn">Details</a>';
                                    //     $detcount++;
                                    // }
                                ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                if($history->status == 'Booked'){
                                ?>
                                <div class="status-disable bg-green m-l-20">
                                    <span class="text-cl-white <?php echo $a;?> <?php echo substr($history->status, 0,4)?> tooltip-bottom" data-tooltip="<?php echo($history->status_description); ?>" style="width:75px"><?php echo($history->status); ?></span>
                                </div>
                                <?php }
                                else{
                                ?>
                                <div class="status-disable bg-tertiary m-l-20">
                                    <span class="text-cl-white <?php echo $a;?> <?php echo substr($history->status, 0,4)?> tooltip-bottom" data-tooltip="<?php echo($history->status_description); ?>" style="width:75px"><?php echo($history->status); ?></span>
                                </div>
                                <?php } ?>
                            </td>
                            <td>
                                <font class="lable-debit">Debit: </font>
                                <?php if(@$history->status !== 'Refund'){ ?>
                                    <span><?php echo($history->token_amount); ?></span>
                                <?php } else{ ?>
                                    <span>-</span>
                                <?php } ?>
                            </td>
                            <td>
                                <font class="lable-credit">Credit: </font>
                                    <?php if(@$history->status == 'Refund'){ ?>
                                <span><?php echo($history->token_amount); ?></span>
                                <?php } else{ ?>
                                    <span>-</span>
                                <?php } ?>
                            </td>
                            <td><font class="lable-balance">Balance: </font><?php echo($history->balance); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>
            <?php echo @$pagination;?>
            <div class="height-plus"></div>
        </div>  
    </div>

        <script type="text/javascript">
            $(function () {

            $(".load_searched_session_histories").click(function () {
                var load_url_search = "https://idbuild.id.dyned.com/live_v20/index.php/student/histories/search" + "/" + document.getElementById('date_from').value + "/" + document.getElementById('date_to').value;
                $("#tab2").load(load_url_search, function () {
                    $("#schedule-loading").hide();
                });
            });

            function date_from(val) {
                $("#date_from").val = val;
            }

            function date_to(val) {
                $("#date_to").val = val;
            }

            function getDate(dates){
                var now = new Date(dates);
                var day = ("0" + (now.getDate() + 1)).slice(-2);
                var month = ("0" + (now.getMonth() + 1)).slice(-2);
                var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);
                return resultDate;
            }

            function removeDatepicker(){
                $('.datepicker2').datepicker('remove');
            }

            // datepicker
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                endDate: "now",
                autoclose:true
            });

            $('.datepicker').change(function(){
                var dates = $(this).val();
                removeDatepicker();
                $('.datepicker2').datepicker({
                    format: 'yyyy-mm-dd',
                    startDate: getDate(dates),
                    endDate: "now",
                    autoclose: true
                });
            });

            });

        </script>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".listTop > a").prepend($("<span/>"));
                $(".listBottom > a").prepend($("<span/>"));
                $(".listTop, .listBottom").click(function(event){
                 event.stopPropagation(); 
                 $(this).children("ul").slideToggle();
               });
            });
        </script>
<!--        <a href="https://idbuild.id.dyned.com/live_v20/index.php/lang_switch/switch_language/english">English</a>
        <a href="https://idbuild.id.dyned.com/live_v20/index.php/lang_switch/switch_language/traditional-chinese">Chinese</a>-->
        <script type="text/javascript">
            $(".checkAll").change(function () {
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });
        </script>

        <script>
        function goBack() {
            window.history.back();

        }
        </script>

        <script>
       $("#breadcrum-home").click(function(){
                         var site = '<?php echo base_url();?>/index.php/account/identity/detail/profile';
             window.location.replace(site);
        });
        </script>

        <script>
            var acc = document.getElementsByClassName("accordion");
            var i;

            for (i = 0; i < acc.length; i++) {
                acc[i].onclick = function(){
                    this.classList.toggle("active");
                    this.nextElementSibling.classList.toggle("show");
              }
            }
        </script>

        <script type="text/javascript">

            // (function() {
            //     $('.btn-save-del').attr('disabled','disabled');
            //     $('td > input').keyup(function() {

            //         var empty = false;
            //         $('td > input').each(function() {
            //             if ($(this).val() == '') {
            //                 empty = true;
            //             }
            //         });

            //         if (empty) {
            //             $('.btn-save-del').attr('disabled','disabled');
            //         } else {
            //             $('.btn-save-del').removeAttr('disabled');
            //         }
            //     });
            // })()

        </script>


<script type="text/javascript">
//Responsive to display Debit, Credit, & Balance

$(function(){
    $(window).bind("resize",function(){
        if($(this).width() < 860){
            $('.lable-balance').show();
            $('.lable-debit').show();
            $('.lable-credit').show();
          }
        else{
            $('.lable-balance').hide();
            $('.lable-debit').hide();
            $('.lable-credit').hide();
        }
    })
})
$(function(){
    var width = screen.width;
    if(width < 860){
        $('.lable-balance').show();
        $('.lable-debit').show();
        $('.lable-credit').show();
    }
    else{
        $('.lable-balance').hide();
        $('.lable-debit').hide();
        $('.lable-credit').hide();
    }
})
</script>