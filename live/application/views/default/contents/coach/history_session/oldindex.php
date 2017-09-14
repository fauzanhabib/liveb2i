<?php if($this->auth_manager->role() == 'RAD') {
    $role_link = "superadmin";
} else {
    $role_link = "admin";
}

?>

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
                <li><a href="#">Regions</a></li>
                <li><a href="#">Indonesia</a></li>
                <li><a href="#">Development Solutions</a></li>
                <li><a href="#">Couch Group List</a></li>
                <li>
                    <form action="" autocomplete="on" class="search-box">
                      <input id="search" name="search" type="text" placeholder="Type here.."><input id="search_submit" value="Rechercher" type="submit">
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <h1 class="margin0 left"><?php echo $histories[0]->coach_name;?> </h1>

    <div class="btn-goBack padding-l-500 padding-t-5">
        <button class="btn-small border-1-blue bg-white-fff">
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
</div>

<div class="box clear-both">
    <div class="heading pure-g">

        <div class="left-list-tabs pure-menu pure-menu-horizontal margin0">
            <ul class="pure-menu-list padding-l-20">
                               <?php if($this->auth_manager->role()=='ADM' && @$user=='coach'){?>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20" href="<?php echo site_url('admin/coach_upcoming_session/one_to_one_session');?>">One to One Session</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20" href="<?php echo site_url('admin/coach_upcoming_session/class_session');?>" >Class Session</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20 active-tabs-blue" href="<?php echo site_url('admin/coach_histories');?>">Session Histories</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover" ><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20 current" class="active" href="<?php echo site_url('admin/coach_histories/class_session');?>">Class Session Histories</a></li>
                <?php }
                elseif($this->auth_manager->role()=='SPR'){?>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20" href="<?php echo site_url('student_partner/student_upcoming_session/one_to_one_session/'.@$student_id);?>">One to One Session</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20" href="<?php echo site_url('student_partner/student_upcoming_session/class_session/'.@$student_id);?>" >Class Session</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20 active-tabs-blue" href="<?php echo site_url('student_partner/student_histories/index/'.@$student_id);?>">Session Histories</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover" ><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20 current" class="active" href="<?php echo site_url('student_partner/student_histories/class_session/'.@$student_id);?>">Class Session Histories</a></li>
                <?php }
                elseif($this->auth_manager->role()=='RAD' && @$user=='coach'){?>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20" href="<?php echo site_url('superadmin/coach_upcoming_session/one_to_one_session/'.@$coach);?>">One to One Session</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20" href="<?php echo site_url('superadmin/coach_upcoming_session/class_session/'.@$coach);?>" >Class Session</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20 active-tabs-blue" href="<?php echo site_url('superadmin/coach_histories/index/'.@$coach);?>">Session Histories</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover" ><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20 current" class="active" href="<?php echo site_url('superadmin/coach_histories/class_session/'.@$coach);?>">Class Session Histories</a></li>
                <?php }
                elseif($this->auth_manager->role()=='ADM' && $user=='student'){ ?>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20" href="<?php echo site_url('admin/student_upcoming_session/one_to_one_session/'.@$student_id);?>">One to One Session</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20" href="<?php echo site_url('admin/student_upcoming_session/class_session/'.@$student_id);?>" >Class Session</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20 active-tabs-blue" href="<?php echo site_url('admin/student_histories/index/'.@$student_id);?>">Session Histories</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover" ><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20 current" class="active" href="<?php echo site_url('admin/student_histories/class_session/'.@$student_id);?>">Class Session Histories</a></li>
                <?php }
                elseif($this->auth_manager->role()=='RAD' && $user=='student'){ ?>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20" href="<?php echo site_url('superadmin/student_upcoming_session/one_to_one_session/'.@$coach);?>">One to One Session</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20" href="<?php echo site_url('superadmin/student_upcoming_session/class_session/'.@$coach);?>" >Class Session</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20 active-tabs-blue" href="<?php echo site_url('superadmin/student_histories/index/'.@$coach);?>">Session Histories</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover" ><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20 current" class="active" href="<?php echo site_url('superadminadmin/student_histories/class_session/'.@$coach);?>">Class Session Histories</a></li>
                <?php }
                elseif($this->auth_manager->role()=='PRT'){?>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20" href="<?php echo site_url('partner/coach_upcoming_session/one_to_one_session');?>">One to One Session</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20" href="<?php echo site_url('partner/coach_upcoming_session/class_session');?>" >Class Session</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20 active-tabs-blue" href="<?php echo site_url('partner/coach_histories');?>">Session Histories</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover" ><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20 current" href="<?php echo site_url('partner/coach_histories/class_session');?>" class="active">Class Session Histories</a></li>
                <?php }elseif ($this->auth_manager->role() == 'CCH') {?>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20" href="<?php echo site_url('coach/ongoing_session');?>">Current Session</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20" href="<?php echo site_url('coach/upcoming_session');?>">Upcoming Sessions</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover"><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20 active-tabs-blue" href="<?php echo site_url('coach/histories');?>">Session Histories</a></li>
                    <li class="pure-menu-item pure-menu-selected no-hover" ><a class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20 current" href="<?php echo site_url('coach/histories/class_session');?>" class="active">Class Session Histories</a></li>
                <?php } ?>
            </ul>
        </div>

    </div>


            <div class="margin0 padding15">
                <a href="#" class="link-filter">Please select date to filter <i class="icon icon-arrow-down"></i></a>
                <?php 
                if($this->auth_manager->role() == 'PRT'){
                    echo form_open('partner/coach_histories/search/one_to_one', 'class="pure-form filter-form" style="border:none"'); 
                }elseif($this->auth_manager->role() == 'SPR'){
                    echo form_open('student_partner/student_histories/search/one_to_one/'.@$student_id, 'class="pure-form filter-form" style="border:none"'); 
                }elseif($this->auth_manager->role() == 'RAD' && @$user == 'coach'){
                    echo form_open('superadmin/coach_histories/search/one_to_one', 'class="pure-form filter-form" style="border:none"'); 
                }elseif($this->auth_manager->role() == 'ADM' && @$user == 'coach'){
                    echo form_open('admin/coach_histories/search/one_to_one', 'class="pure-form filter-form" style="border:none"'); 
                }elseif($this->auth_manager->role() == 'ADM' && @$user == 'student'){
                    echo form_open('admin/student_histories/search/one_to_one/'.@$student_id, 'class="pure-form filter-form" style="border:none"'); 
                }elseif($this->auth_manager->role() == 'RAD' && @$user == 'student'){
                    echo form_open('superadmin/student_histories/search/one_to_one/'.@$student_id, 'class="pure-form filter-form" style="border:none"'); 
                }elseif ($this->auth_manager->role() == 'CCH') {
                    echo form_open('coach/histories/search/one_to_one', 'class="pure-form filter-form" style="border:none"'); 
                }
                
                ?>

                <div class="pure-g">
                    <div class="pure-u-1">
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_from" class="datepicker frm-date margin0" type="text" readonly="">  
                            <span class="icon icon-date"></span>
                        </div>
                        <span style="font-size: 16px;margin:0px 10px;">to</span>  
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_to" class="datepicker2 frm-date margin0" type="text" readonly="">  
                            <span class="icon icon-date"></span>
                        </div>
                        <?php echo form_submit('__submit', 'Go','class="pure-button btn-small btn-primary" style="margin:0px 10px;"'); ?>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>

    <div class="content">
        <div class="box border-t-1">
        <script>
            $(document).ready(function() {
                $('#userTable').DataTable( {
                  "bLengthChange": false,
                  "searching": false,
                  "userTable": false,
                  "bInfo" : false
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
                    <th class="text-cl-grey border-none">TRANSACTION</th>
                    <th class="text-cl-grey border-none">STUDENT</th>
                    <th class="text-cl-grey border-none">SESSION DATE</th>
                    <th class="text-cl-grey border-none">TIME</th>
                    <th class="text-cl-grey border-none">SESSION</th>
                    <?php if($this->auth_manager->role()!='ADM' && $this->auth_manager->role()!='SPR'){?>
                        <th class="text-cl-grey border-none">ACTION</th>
                    <?php } ?>
                    <?php if($this->auth_manager->role()!='RAD' && $this->auth_manager->role()!='SPR'){?>
                        <th class="text-cl-grey border-none">ACTION</th>
                    <?php } ?>             
                </tr>
            </thead>
            <tbody>     
                <?php foreach (@$histories as $history) { ?>               
                 <tr>
                    <td> <?php echo date("F, j Y  H:i:s", $history->dupd); ?></td>
                    <td>                                   
                        <?php if(($this->auth_manager->role()=='ADM') ||(($this->auth_manager->role()=='RAD'))){?>
                            <a href="<?php echo site_url('admin/manage_partner/student_detail/3/'.$history->student_id)?>"><?php echo($history->student_name); ?></a>
                        <?php }elseif($this->auth_manager->role()=='SPR'){ ?>
                            <a href="<?php echo site_url('student_partner/member_list/student_detail/'.$history->student_id)?>"><?php echo($history->student_name); ?></a>
                        <?php } else{
                            echo($history->student_name);
                        } ?>
                    </td>
                    <td><?php echo date('F, j Y', strtotime($history->date)); ?></td>
                    <td><?php echo (date('H:i',strtotime($history->start_time))); ?> - <?php echo (date('H:i',strtotime($history->end_time))); ?></td>
                   
                    <td>
                        <?php if (@$history->stream_url){?>
                        <a href="<?php echo @$history->stream_url;?>" target="_blank" class="pure-button btn-tiny btn-expand-tiny btn-white">
                            OPEN
                        </a>
                        <?php } else{
                            echo "Not Available";
                        }?>
                    </td>

                    <td>
                        <?php
                        if($this->auth_manager->role() == 'CCH'){
                            if(!@$history->note){ ?>
                            <div class="t-center"><a class="pure-button btn-medium btn-white" data-toggle="modal" data-target="#divModal" data-note="<?php echo @$history->note;?>" data-id="<?php echo(@$history->id);?>">
                                SESSION REPORT
                            </a></div>
                            <?php
                            }else{
                                echo 'Reported';
                            }
                        }elseif($this->auth_manager->role() == 'PRT')
                            if(@$history->note){ ?>
                                <div class="t-center">
                                    <a class="pure-button btn-medium btn-white" data-toggle="modal" data-target="#divModal" data-note="<?php echo @$history->note;?>" data-id="<?php echo(@$history->id);?>">
                                        SESSION >REPORT
                                    </a>
                                </div>
                            <?php
                            }else{
                                echo 'No Report';
                            }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php } else {
                echo "<div class='padding15'><div class='no-result'>No Dataa</div></div>";
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
                <?php if($this->auth_manager->role()=='CCH'){?>
                    <div class="modal-body text-center">
                        <div class="pure-form">
                            <textarea id="note" name="note" style="width: 80%;height: 80px;resize: none;"></textarea>
                            <ul class="parsley-errors-list filled"><li class="parsley-required">Please input your comment.</li></ul>
                        </div>
                    </div>
                    <div class="modal-footer text-center">
                        <button type="button" class="pure-button btn-primary btn-small btn-expand submit">SUBMIT</button>
                    </div>
                <?php }elseif($this->auth_manager->role()=='PRT'){ ?>
                    <div class="modal-body text-center">
                        <div class="pure-form">
                            <span id="fill-note"></span>
                        </div>
                    </div>
                <?php }?>
                
            </div>
            
        </div>
    </form>
</div>


<script type="text/javascript">
    $(function () {
        $('#divModal').appendTo("body");

        $('#divModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var recipient = button.data('id'); // Extract info from data-* attributes
            var note = button.data('note'); // Extract info from data-* attributes
            var modal = $(this);
            $("#fill-note").text(note);
            modal.find('.submit').val(recipient);
            $("html,body").css("overflow","hidden");
        });

    });

    // ajax
    // don't cache ajax or content won't be fresh
    $.ajaxSetup({
        cache: false
    });

    // load() functions
    var loadUrl = "<?php echo site_url('student/histories/token'); ?>";
    $(".load_session_histories").click(function () {
        //var index = document.getElementById("loadbasic").value;
        //alert(this.value);
        $("#tab2").load(loadUrl, function () {
            $("#schedule-loading").hide();
        });
    });

    // load() functions
    // var from = document.getElementById("#date_from").value;

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
        <?php date_default_timezone_set('Etc/GMT'.(-$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60 >= 0 ? '+'.-$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60 : -$this->identity_model->get_gmt($this->auth_manager->userid())[0]->minutes/60));?>
        endDate: "<?php echo date('Y-m-d')?>",
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
    

    //$('.modal-content').parsley();
    //$('#note').parsley('validate');

    $('.parsley-errors-list').hide();

    $('.submit').click(function(){
            if ($.trim($('#note').val()) != '') {
                window.location = "<?php echo site_url('coach/report/create/'.@$history->id.'/');?>/"+$('#note').val();
            }
            else {
                $('.parsley-errors-list').show();
            }
    });
    
</script>