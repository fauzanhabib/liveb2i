<div class="heading text-cl-primary border-b-1 padding15">

    <h2 class="margin0">Upcoming Sessions</h2>

</div>

<div class="box clear-both">
    <div class="heading pure-g padding-t-30">

        <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
            <ul class="pure-menu-list">
                <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('coach/ongoing_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Current Sessions</a></li> -->
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('coach/upcoming_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue">Upcoming Sessions</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('coach/histories');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Session History</a></li>
                <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('coach/histories/class_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Class Session History</a></li> -->
            </ul>
        </div>

    </div>

    <div class="content">
        <div class="box">
            <?php 
                echo form_open('coach/upcoming_session/search', 'class="pure-form filter-form" style="border:none"'); 
                ?>
            <div class="pure-u-1 text-center m-t-20">
                <div class="frm-date" style="display:inline-block">
                    <input name="date_from" class="datepicker frm-date margin0" type="text" readonly="" placeholder="Start Date">  
                    <span class="icon dyned-icon-coach-schedules"></span>
                </div>
                <div class="frm-date" style="display:inline-block">
                    <input name="date_to" class="datepicker2 frm-date margin0" type="text" readonly="" placeholder="End Date">  
                    <span class="icon dyned-icon-coach-schedules"></span>
                </div>
                <input type="submit" name="__submit" value="Go" class="pure-button btn-small btn-tertiary border-rounded height-32" style="margin:0px 10px;" />
            </div>
<?php echo form_close(); ?>
            <div class="content-title padding-t-25">
                One-To-One-Sessions
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
            $i = 1;
            if (@$data) {
            ?>
            <table id="userTable" class="display table-session" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-cl-tertiary font-light font-16 border-none">DATE</th>
                        <th class="text-cl-tertiary font-light font-16 border-none">SESSION TIME</th>
                        <th class="text-cl-tertiary font-light font-16 border-none">STUDENT</th>
                        <th class="text-cl-tertiary font-light font-16 border-none">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data as $d) {
                    //$gmt_student = $this->identity_model->new_get_gmt($d->student_id);
                    $gmt_user = $this->identity_model->new_get_gmt($this->auth_manager->userid());
                    $new_gmt = 0;
                    if($gmt_user[0]->gmt > 0){
                        $new_gmt = '+'.$gmt_user[0]->gmt;
                    }else{
                        $new_gmt = $gmt_user[0]->gmt;    
                    }
                    // if(@!$gmt_student){
                    //     if($gmt_user[0]->gmt > 0){
                    //     $new_gmt = '+'.$gmt_user[0]->gmt;
                    //     }else{
                    //     $new_gmt = $gmt_user[0]->gmt;    
                    //     }
                    // }else if($gmt_student[0]->gmt > 0){
                    //     $new_gmt = '+'.$gmt_student[0]->gmt;
                    // }else{
                    //     $new_gmt = $gmt_student[0]->gmt;
                    // }
                    $link = site_url('webex/available_host/' . @$d->id);
                    ?>
                    <tr>
                        <td class="padding-10-15 sm-12 tb-ses-up">
                             <?php echo date("M j Y", strtotime(@$d->date)); ?>
                            <span class="text-cl-green lg-none"><?php echo(date('H:i',strtotime(@$d->start_time)));?> - <?php echo(date('H:i',strtotime(@$d->start_time)));?></span>
                            <span class="lg-none">
                                Student :<br>
                                <a href="<?php echo site_url('coach/upcoming_session/student_detail/' . $d->student_id); ?>" class="text-cl-secondary">
                                    <?php echo @$d->student_name; ?>
                                </a>
                            </span>
                        </td>
                        <td>
                            <div class="rounded-box bg-green">
                                <span class="text-cl-white">
                                <?php
                                    $defaultstart  = strtotime($d->start_time);
                                    echo date("H:i", $defaultstart); 
                                ?> 
                                -
                                <?php
                                    $defaultend  = strtotime($d->end_time);
                                    $endsession = $defaultend-(5*60);
                                    echo date("H:i", $endsession) . " (UTC " . $new_gmt . " )"; 
                                ?>
                                </span>
                            </div>
                        </td>
                        <td class="padding-10-15 md-none">
                            <div class="rounded-box bg-tertiary">
                                <span>
                            <a href="<?php echo site_url('coach/upcoming_session/student_detail/' . $d->student_id); ?>"class="text-cl-white"> 
                                <?php echo @$d->student_name; ?>
                            </a>
                                </span>
                            </div>
                        </td>
                        <td class="padding-10-15 sm-12">
                            <a href="<?php echo site_url('coach/coach_vrm/single_student/'. $d->student_id); ?>" class="pure-button btn-medium btn-expand-tiny btn-white">Progress <div class="md-none" style='display:inline-block'>Report</div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else {
                echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
            } ?>
            <?php echo @$pagination; ?>
          <!--   <div class="content-title padding-t-25">
                Class Sessions
            </div> -->
            <script>
                $(document).ready(function() {
                    $('#userTable2').DataTable( {
                      "bLengthChange": false,
                      "searching": false,
                      "userTable": false,
                      "bInfo" : false,
                      "bPaginate": false
                    });
                } );
            </script>
            <?php
            $j = 1;
            if (@$data_class) {
            ?>    
          <!--   <table id="userTable" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-cl-tertiary font-light font-16 border-none">DATE</th>
                        <th class="text-cl-tertiary font-light font-16 border-none">CLASS TIME</th>
                        <th class="text-cl-tertiary font-light font-16 border-none">CLASS NAME</th>
                        <th class="text-cl-tertiary font-light font-16 border-none">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data_class as $d) {
                    $link = site_url('webex/available_host/c' . @$d->id);
                    ?>
                    
                    <tr>
                        <td>
                            <?php echo date("F, j Y", strtotime(@$d->date)); ?>
                            <span class="text-cl-green lg-none"><?php echo(date('H:i',strtotime(@$d->start_time)));?> - <?php echo(date('H:i',strtotime(@$d->start_time)));?></span>
                            <span class="lg-none">
                                Student :<br>
                                <a href="#" class="text-cl-secondary">
                                    <?php echo @$d->class_name; ?>
                                </a>
                            </span>
                        </td>
                        <td>
                            <div class="status-disable bg-green">
                                <span class="text-cl-white">
                                    <?php echo(date('H:i',strtotime(@$d->start_time)));?> - <?php echo(date('H:i',strtotime(@$d->end_time)));?>
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="status-disable bg-tertiary">
                                <span>
                                    <a href="<?php echo site_url('coach/class_detail/schedule/'.@$d->class_id);?>" class="text-cl-white"><?php echo @$d->class_name; ?></a></td>
                                </span>
                            </div>
                        <td>
                            <a href="<?php echo site_url('coach/coach_vrm/multiple_student/'. @$d->class_id); ?>" class="pure-button btn-small btn-white">Progress Report</a>
                        </td>
                    </tr>

                    <?php } ?>
                </tbody>
            </table> -->
            <?php } else {
                // echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
            } ?>
            <div class="height-plus"></div>
        </div>
        <div id="tab2" class="tab">
            <div id="result">
                <img src='<?php echo base_url(); ?>images/small-loading.gif' alt='loading...' style="display:none;" id="schedule-loading"/>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.tablescroll.js"></script>

<script>
    // ajax
    // don't cache ajax or content won't be fresh
    $.ajaxSetup({
        cache: false
    });

    // load() functions
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
                startDate: "now",
                autoclose:true
            });

            $('.datepicker').change(function(){
                var dates = $(this).val();
                removeDatepicker();
                $('.datepicker2').datepicker({
                    format: 'yyyy-mm-dd',
                    startDate: getDate(dates),
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