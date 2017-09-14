<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Session Histories</h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
            <ul class="pure-menu-list">
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('student/ongoing_session'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Current Sessions</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('student/upcoming_session'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Upcoming Sessions</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('student/histories'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue">Session Histories</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('student/histories/class_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Class Session Histories</a></li>
            </ul>
        </div>
    </div>
            <div class="content">
                <div class="box">
                    <div class="pure-u-1 text-center m-t-20">
                        <?php 
                        echo form_open('student/histories/search/one_to_one', 'class="pure-form filter-form" style="border:none"'); 
                        ?>
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_from" class="datepicker frm-date margin0" type="text" readonly="" placeholder="Start Date">  
                            <span class="icon icon-date"></span>
                        </div>
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_to" class="datepicker2 frm-date margin0" type="text" readonly="" placeholder="End Date">  
                            <span class="icon icon-date"></span>
                        </div>
                        <?php echo form_submit('__submit', 'Go','class="pure-button btn-small btn-tertiary border-rounded height-32" style="margin:0px 10px;"'); ?>
                        
                        <?php echo form_close(); ?>
                    
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
                    <?php
                    if(!@$histories){
                        echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
                    }
                    else {
                    ?>

                    <table id="userTable" class="display table-session" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-cl-tertiary font-light font-16 border-none">TRANSACTION</th>
                                <th class="text-cl-tertiary font-light font-16 border-none">COACH</th>
                                <th class="text-cl-tertiary font-light font-16 border-none">SESSION DATE</th>
                                <th class="text-cl-tertiary font-light font-16 border-none">TIME</th>
                                <th class="text-cl-tertiary font-light font-16 border-none">STUDENT ATTENDANCE</th>
                                <th class="text-cl-tertiary font-light font-16 border-none">COACH ATTENDANCE</th>
                                <th class="text-cl-tertiary font-light font-16 border-none">SESSION RECORDED</th>               
                            </tr>
                        </thead>
                        <tbody>
                    <?php foreach (@$histories as $history) { ?>

                            <tr>
                                <td><?php echo date("F, j Y  H:i:s", $history->dupd); ?></td>
                                <td>
                                    <div class="status-disable bg-tertiary m-l-20">
                                        <span>
                                            <a class="text-cl-white" href="<?php echo site_url('student/upcoming_session/coach_detail/' . $history->coach_id); ?>"><?php echo($history->coach_name); ?></a>
                                        </span>
                                    </div>
                                </td>
                                <td><?php echo date('F, j Y', strtotime($history->date)); ?></td>
                                <td>
                                    <div class="status-disable bg-green m-l-20">
                                        <span class="text-cl-white">
                                            <?php
                                                $defaultstart  = strtotime($history->start_time);
                                                $usertimestart = $defaultstart+(60*$minutes);
                                                $hourattstart  = date("H:i", $usertimestart);
                                                echo $hourattstart; 
                                            ?> 
                                            -
                                            <?php
                                                $defaultend  = strtotime($history->end_time);
                                                $usertimeend = $defaultend+(60*$minutes);
                                                $hourattend  = date("H:i", $usertimeend);
                                                echo $hourattend; 
                                            ?>
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="status-disable m-l-20">
                                        <span class="">
                                            <?php
                                                $defaultstd  = strtotime($history->std_attend);
                                                $usertimestd = $defaultstd+(60*$minutes);
                                                $hourattstd  = date("H:i:s", $usertimestd); 
                                                echo $hourattstd; 
                                            ?>    
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="status-disable m-l-20">
                                        <span class="">
                                            <?php
                                                $defaultcch  = strtotime($history->cch_attend);
                                                $usertimecch = $defaultcch+(60*$minutes);
                                                $hourattcch  = date("H:i:s", $usertimecch); 
                                                echo $hourattstd; 
                                            ?>    
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <?php if (@$history->downloadurl){?>
                                    <form name ="sessiondone" action="<?php echo(site_url('opentok/checkrecord/'));?>" method="post">
                                        <input type="hidden" name="sessionid" value="<?php echo @$history->session; ?>">
                                        <input type="submit" class="pure-button btn-tiny btn-expand-tiny btn-white" value="Check Availibility">
                                    </form>    
                                    <?php } else{
                                        echo "Not Available";
                                    }?>
                                </td>
                            </tr>
                    <?php } ?>
    
                        </tbody>
                    </table>
                </div>
                <?php } ?>
            </div>  
</div>
<?php echo @$pagination?>

<script type="text/javascript">
    $(function () {

    $(".load_searched_session_histories").click(function () {
        var load_url_search = "<?php echo site_url('student/histories/search'); ?>" + "/" + document.getElementById('date_from').value + "/" + document.getElementById('date_to').value;
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