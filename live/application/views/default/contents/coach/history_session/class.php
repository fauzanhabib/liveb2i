<?php if($this->auth_manager->role() == 'RAD') {
    $role_link = "superadmin";
} else {
    $role_link = "admin";
}

?>

<div class="heading text-cl-primary border-b-1 padding15">

    <h2 class="margin0">Class Session History</h2>

</div>

<div class="box clear-both">
    <div class="heading pure-g padding-t-30">

        <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
            <ul class="pure-menu-list">
               <?php if($this->auth_manager->role()=='ADM' && @$user=='coach'){?>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('admin/coach_upcoming_session/one_to_one_session');?>">One-to-One-Session</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('admin/coach_upcoming_session/class_session');?>" >Class Session</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('admin/coach_histories');?>">Session History</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('admin/coach_histories/class_session');?>">Class Session History</a></li>
                <?php }
                elseif($this->auth_manager->role()=='SPR'){?>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('student_partner/student_upcoming_session/one_to_one_session/'.@$student_id);?>">One-to-One-Session</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('student_partner/student_upcoming_session/class_session/'.@$student_id);?>" >Class Session</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('student_partner/student_histories/index/'.@$student_id);?>">Session History</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('student_partner/student_histories/class_session/'.@$student_id);?>">Class Session History</a></li>
                <?php }
                elseif($this->auth_manager->role()=='RAD' && @$user=='coach'){?>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('superadmin/coach_upcoming_session/one_to_one_session/'.@$coach);?>">One-to-One-Session</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('superadmin/coach_upcoming_session/class_session/'.@$coach);?>" >Class Session</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('superadmin/coach_histories/index/'.@$coach);?>">Session History</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('superadmin/coach_histories/class_session/'.@$coach);?>">Class Session History</a></li>
                <?php }
                elseif($this->auth_manager->role()=='ADM' && $user=='student'){ ?>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('admin/student_upcoming_session/one_to_one_session/'.@$student_id);?>">One-to-One-Session</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('admin/student_upcoming_session/class_session/'.@$student_id);?>" >Class Session</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('admin/student_histories/index/'.@$student_id);?>">Session History</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('admin/student_histories/class_session/'.@$student_id);?>">Class Session History</a></li>
                <?php }
                elseif($this->auth_manager->role()=='RAD' && $user=='student'){ ?>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('superadmin/student_upcoming_session/one_to_one_session/'.@$coach);?>">One-to-One-Session</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('superadmin/student_upcoming_session/class_session/'.@$coach);?>" >Class Session</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('superadmin/student_histories/index/'.@$coach);?>">Session History</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('superadminadmin/student_histories/class_session/'.@$coach);?>">Class Session History</a></li>
                <?php }
                elseif($this->auth_manager->role()=='PRT'){?>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('partner/coach_upcoming_session/one_to_one_session');?>">One-to-One-Session</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('partner/coach_upcoming_session/class_session');?>" >Class Session</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('partner/coach_histories');?>">Session History</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('partner/coach_histories/class_session');?>" class="active">Class Session History</a></li>
                <?php }elseif ($this->auth_manager->role() == 'CCH') {?>
                    <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('coach/ongoing_session');?>">Current Session</a></li> -->
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('coach/upcoming_session');?>">Upcoming Sessions</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey" href="<?php echo site_url('coach/histories');?>">Session History</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue" href="<?php echo site_url('coach/histories/class_session');?>" class="active">Class Session History</a></li>
                <?php } ?>
            </ul>
        </div>

    </div>


            <div class="margin0 padding15">
                <a href="#" class="link-filter">Please select date to filter <i class="icon icon-arrow-down"></i></a>
                <?php 
                $class_detail = '';
                if($this->auth_manager->role() == 'PRT'){
                    echo form_open('partner/coach_histories/search/class', 'class="pure-form filter-form" style="border:none"'); 
                }elseif($this->auth_manager->role() == 'SPR'){
                    echo form_open('student_partner/student_histories/search/class/'.@$student_id, 'class="pure-form filter-form" style="border:none"'); 
                }elseif($this->auth_manager->role() == 'ADM' && @$user == 'coach'){
                    echo form_open('admin/coach_histories/search/class', 'class="pure-form filter-form" style="border:none"'); 
                }elseif($this->auth_manager->role() == 'RAD' && @$user == 'coach'){
                    echo form_open('superadmin/coach_histories/search/class', 'class="pure-form filter-form" style="border:none"'); 
                }elseif($this->auth_manager->role() == 'ADM' && @$user == 'student'){
                    echo form_open('admin/student_histories/search/class/'.@$student_id, 'class="pure-form filter-form" style="border:none"'); 
                }elseif($this->auth_manager->role() == 'RAD' && @$user == 'student'){
                    echo form_open('superadmin/student_histories/search/class/'.@$student_id, 'class="pure-form filter-form" style="border:none"'); 
                }elseif ($this->auth_manager->role() == 'CCH') {
                    $class_detail = 'coach/class_detail/schedule/';
                    echo form_open('coach/histories/search/class', 'class="pure-form filter-form" style="border:none"'); 
                }
                
                ?>

                <div class="pure-g">
                    <div class="pure-u-1 text-center m-t-20">
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_from" class="datepicker frm-date margin0" type="text" readonly="" placeholder="Start Date">  
                    <span class="icon dyned-icon-coach-schedules"></span>
                        </div>
                        <span style="font-size: 16px;margin:0px 10px;">to</span>  
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_to" class="datepicker2 frm-date margin0" type="text" readonly="" placeholder="End Date">  
                    <span class="icon dyned-icon-coach-schedules"></span>
                        </div>
                        <?php echo form_submit('__submit', 'Go','class="pure-button btn-small btn-tertiary border-rounded height-32" style="margin:0px 10px;"'); ?>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>

    <div class="content">
        <div class="box">
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
            if (@$histories) {
        ?>

        <table id="userTable" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-cl-tertiary font-light font-16 border-none">TRANSACTION</th>
                    <th class="text-cl-tertiary font-light font-16 border-none">CLASS NAME</th>
                    <th class="text-cl-tertiary font-light font-16 border-none">SESSION DATE</th>
                    <th class="text-cl-tertiary font-light font-16 border-none">TIME</th>
                    <th class="text-cl-tertiary font-light font-16 border-none">RECORDED SESSIONS</th>

                </tr>
            </thead>
            <tbody>     
                <?php foreach (@$histories as $history) { ?>               
                 <tr>
                    <td> <?php echo date("M j Y  H:i:s", $history->dupd); ?></td>
                    <?php if(($this->auth_manager->role() == 'PRT') || ($this->auth_manager->role() == 'ADM') || ($this->auth_manager->role() == 'RAD')){ ?>
                        <td>
                            <div class="status-disable bg-tertiary m-l-20">
                                <span class="text-cl-white">
                                    <?php echo($history->class_name); ?>
                                </span>
                            </div>
                        </td>
                        <?php
                        }else{
                            ?>
                        <td>
                            <div class="status-disable bg-tertiary m-l-20">
                                <span>
                                    <a href="<?php echo site_url('student_partner/managing/class_schedule/'.$class_detail.$history->class_id);?>" class="text-cl-white"><?php echo($history->class_name); ?></a>
                                </span>
                            </div>
                        </td>
                        <?php
                        }?>

                    <td><?php echo date('M j Y', strtotime($history->date)); ?></td>
                    <td>
                        <div class="status-disable bg-green m-l-20">
                            <span class="text-cl-white">
                        <?php echo (date('H:i',strtotime($history->start_time))); ?> - <?php echo (date('H:i',strtotime($history->end_time))); ?>
                            </span>
                        </div>
                    </td>
                    <td>
                        <?php if (@$history->stream_url){?>
                            <a href="<?php echo @$history->stream_url;?>" target="_blank" class="pure-button btn-tiny btn-expand-tiny btn-white">
                                OPEN
                            </a>
                            <?php } else{
                                echo "Not Available";
                        }?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php } else {
                echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
            } ?>
        </div>
    </div>  
</div>
<?php echo @$pagination;?>



<!-- Modal -->
<div class="modal hide fade" id="divModal" tabindex="-1" role="dialog" aria-labelledby="divModalLabel" aria-hidden="true" data-backdrop="static">
    <form data-parsley-validate id="form">
        <div class="modal-dialog">
            
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon icon-close"></i></button>
                    <h3 class="modal-title text-cl-primary" id="myModalLabel">Session Report</h3>
                </div>
                <div class="modal-body text-center">
                    <div class="pure-form">
                        <textarea id="note" name="note" style="width: 300px;height: 80px;resize: none;" required data-required-message="Please insert your name"></textarea>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="pure-button btn-primary btn-small btn-expand submit">SUBMIT</button>
                </div>
            </div>
            
        </div>
    </form>
</div>


<script type="text/javascript">
    $(function () {

        /**
        $('.btn-next').hover(function () {
            $('.icon-next').css({"background": "url(assets/icon/arrow-right-blue.png)"});
        }, function () {
            $('.icon-next').css({"background": "url(assets/icon/arrow-right.png)"});
        });

        $('.btn-prev').hover(function () {
            $('.icon-prev').css({"background": "url(assets/icon/arrow-left-blue.png)"});
        }, function () {
            $('.icon-prev').css({"background": "url(assets/icon/arrow-left.png)"});
        });

        $('.box .tab-link a').click(function (e) {
            var currentValue = $(this).attr('href');

            $('.box .tab-link a').removeClass('active');
            $('.tab').removeClass('active');

            $(this).addClass('active');
            $(currentValue).addClass('active');

            e.preventDefault();

        });
        **/

        $('#divModal').appendTo("body");

        $('#divModal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget) // Button that triggered the modal
          var recipient = button.data('id') // Extract info from data-* attributes
          var modal = $(this)
          modal.find('.submit').val(recipient);
          $("html,body").css("overflow","hidden");
        });

    });

    // ajax
    // don't cache ajax or content won't be fresh
    $.ajaxSetup({
        cache: false
    });

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
             window.location.replace("https://idbuild.id.dyned.com/live_v20/index.php/account/identity/detail/profile");
           
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