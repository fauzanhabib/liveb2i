        <div class="pure-u-lg-20-24 pure-u-md-24-24 pure-u-sm-24-24 content-center">

                <div class="heading text-cl-primary border-b-1 padding15">

                    <h2 class="margin0">Class Session Histories</h2>

                </div>

                <div class="box clear-both">
                    <div class="heading pure-g padding-t-30">

                        <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
                            <ul class="pure-menu-list">
                                <?php if($this->auth_manager->role()=='ADM'){?>
                                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('admin/coach_upcoming_session/one_to_one_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">One to One Session</a></li>
                                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('admin/coach_upcoming_session/class_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Class Session</a></li>
                                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('admin/coach_histories');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Session Histories</a></li>
                                <?php }
                                elseif($this->auth_manager->role()=='PRT'){?>
                                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('partner/coach_upcoming_session/one_to_one_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">One to One Session</a></li>
                                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('partner/coach_upcoming_session/class_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Class Session</a></li>
                                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('partner/coach_histories');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Session Histories</a></li>
                                <?php }elseif ($this->auth_manager->role() == 'STD') {?>
                                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('student/ongoing_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Current Sessions</a></li>
                                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('student/upcoming_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Upcoming Sessions</a></li>
                                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('student/histories');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Session Histories</a></li>
                                    <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('student/histories/class_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue">Class Session Histories</a></li>
                                <?php } ?>

                               
                            </ul>
                        </div>

                    </div>

                    <div class="content">
                        <div class="box">
                            <div class="pure-u-1 text-center m-t-20">
                                <?php 
                                    if($this->auth_manager->role() == 'PRT'){
                                        echo form_open('partner/coach_histories/search', 'class="pure-form filter-form" style="border:none"'); 
                                    }
                                    elseif($this->auth_manager->role() == 'ADM'){
                                        echo form_open('admin/coach_histories/search', 'class="pure-form filter-form" style="border:none"'); 
                                    }elseif ($this->auth_manager->role() == 'CCH') {
                                        echo form_open('coach/histories/search/class', 'class="pure-form filter-form" style="border:none"'); 
                                    }elseif ($this->auth_manager->role() == 'STD') {
                                        echo form_open('student/histories/search/class', 'class="pure-form filter-form" style="border:none"'); 
                                    }
                                    
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

                            <table id="userTable" class="display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-cl-tertiary font-light font-16 border-none">TRANSACTION</th>
                                        <th class="text-cl-tertiary font-light font-16 border-none">COACH</th>
                                        <th class="text-cl-tertiary font-light font-16 border-none">SESSION DATE</th>
                                        <th class="text-cl-tertiary font-light font-16 border-none">TIME</th>
                                        <th class="text-cl-tertiary font-light font-16 border-none">SESSION RECORDED</th>               
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (@$histories as $history) { ?>
                                    <tr>
                                        <td><?php echo date("F, j Y  H:i:s", $history->dupd); ?></td>
                                        <td>
                                            <div class="status-disable bg-tertiary m-l-20">
                                                <span class="text-cl-white">
                                                    <?php echo($history->class_name); ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td><?php echo date('F, j Y', strtotime($history->date)); ?></td>
                                        <td>
                                            <div class="status-disable bg-green m-l-20">
                                                <span class="text-cl-white"><?php echo (date('H:i',strtotime($history->start_time))); ?> - <?php echo (date('H:i',strtotime($history->end_time))); ?></span>
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
                        </div>
                    <?php } ?>
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

    $('.submit').click(function(){
            //alert('<?php //echo site_url('coach/report/create/'.$history->id.'/');?>'+'/'+$('#note').val());
            //alert($('#score').val() + ' ' + this.value);
            //alert('<?php //echo site_url('student/rate_coaches/update_rate');?>'+'/'+this.value+'/'+$('#score').val());
            //window.location = '<?php //echo site_url('coach/report/create/'.$history->id.'/');?>'+'/'+$('#note').val();
            if ($('#form').parsley().isValid()) {
                window.location = "<?php echo site_url('coach/report/create/'.@$history->id.'/');?>/"+$('#note').val();
            }
    });
    
</script>