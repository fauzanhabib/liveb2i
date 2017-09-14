<?php
if (@$data != $this->session->userdata('appointment_history_id')) {
    ?>
    <script>
        $.post("<?php echo(base_url() . '/index.php/third_party/set_session/destroy'); ?>", function (data) {
        });
    </script>
    <?php
}
?>
<script src="http://www.datejs.com/build/date.js" type="text/javascript"></script>

<style type="text/css">
    #countup p {
        display: inline-block;
        padding: 1px;
        margin: 0 0 1px;
    }
</style>


<script>
    /*
     * Basic Count Up from Date and Time
     * Author: @mrwigster / trulycode.com
     */
    //arguments.callee.count = 0;
    $(document).ready(function () {

<?php if (!$this->session->userdata('timer')) { ?>
            $('#button_start').show();
    <?php
}
?>

<?php if (@$this->session->userdata('timer') == 'begin') { ?>
            $('#button_start').hide();
            $.post("<?php echo(base_url() . '/index.php/third_party/set_session/current_server_time'); ?>", function (data) {
                upTime("<?php echo ($this->session->userdata('duration_begin')); ?>", data);
            });
    <?php
} else if (@$this->session->userdata('timer') == 'finished') {
    ?>
    <?php ?>
            upTime("<?php echo ($this->session->userdata('duration_begin')); ?>", "<?php echo ($this->session->userdata('current_server_time')); ?>");
            $('#button_start').hide();
            $('#button_end').hide();
            $('#countup').show();
            $('.stop').show();
            $('.start').hide();
            $('.stop-btn').remove();
    <?php
} else {
    ?>
            $('#button_end').hide();
            $('#countup').show();
    <?php
}
?>

    });

//    window.onload=function() {
//        // Month,Day,Year,Hour,Minute,Second
//        upTime('may,05,2015,16:30:00'); // ****** Change this line!
//    }

    // function to start the timer
    function startTimer()
    {
        $.post("<?php echo(base_url() . '/index.php/third_party/set_session/set_duration_time'); ?>", function (data) {
            //alert(data);
            upTime(data, data);
            // create appointment history with start duration inside
            $.post("<?php echo(base_url() . '/index.php/third_party/set_duration/create_duration/' . $data[0]->id); ?>", function (data) {
            });
        });

        $('#countup').show();
        $('#button_start').hide();
        $('#button_end').show();

    }

    // function to end the timer
    function stopTimer()
    {
        $.post("<?php echo(base_url() . '/index.php/third_party/set_session/current_server_time'); ?>", function (data) {
            if (confirm('Are you sure?')) {
                $.post("<?php echo(base_url() . '/index.php/third_party/set_session/end_duration'); ?>", function (data) {
                    $.post("<?php echo(base_url() . '/index.php/third_party/set_duration/update_duration/'); ?>", function (data) {
                    });
                });
                return (
                        //$('#countup').hide(),
                        $('#button_end').hide()
                        );
            }
            else {
                upTime("<?php echo ($this->session->userdata('duration_begin')); ?>", data);
            }

        });

    }


    // counting the difference between time and make the timer
    function upTime(countFrom, countTo) {
        // temporary value to store countTo
        temp_countTo = countTo;

        // adding 1 seconds and run in client side
        arguments.callee.count = ++arguments.callee.count || 1
        countToTemp = new Date(countTo);
        countToTemp.setSeconds(countToTemp.getSeconds() + arguments.callee.count);
        countToTemp.setSeconds(countToTemp.getSeconds() - 1);

        countFromTemp = new Date(countFrom);
        difference = (countToTemp - countFromTemp);




        hours = Math.floor((difference % (60 * 60 * 1000 * 24)) / (60 * 60 * 1000) * 1);
        mins = Math.floor(((difference % (60 * 60 * 1000 * 24)) % (60 * 60 * 1000)) / (60 * 1000) * 1);
        secs = Math.floor((((difference % (60 * 60 * 1000 * 24)) % (60 * 60 * 1000)) % (60 * 1000)) / 1000 * 1);

        document.getElementById('hours').firstChild.nodeValue = hours;
        document.getElementById('minutes').firstChild.nodeValue = mins;
        document.getElementById('seconds').firstChild.nodeValue = secs;


        var monthNames = [
            "january", "february", "march",
            "april", "may", "june", "july",
            "august", "september", "october",
            "november", "december"
        ];



        countFromTemp = monthNames[countFromTemp.getMonth()] + ',' + countFromTemp.getDate() + ',' + countFromTemp.getFullYear() + ',' + countFromTemp.getHours() + ':' + countFromTemp.getMinutes() + ':' + countFromTemp.getSeconds();
        countToTemp = monthNames[countToTemp.getMonth()] + ',' + countToTemp.getDate() + ',' + countToTemp.getFullYear() + ',' + countToTemp.getHours() + ':' + countToTemp.getMinutes() + ':' + countToTemp.getSeconds();
        //alert(countTo);

        // call the function again to count duration
<?php if (@$this->session->userdata('timer') != 'finished') { ?>
            clearTimeout(upTime.to);
            upTime.to = setTimeout(function () {
                upTime(countFromTemp, temp_countTo);
            }, 1000);

    <?php
}
?>

    }
</script>


<?php echo($this->session->userdata('sample')); ?>
<?php if (@$data) { ?>
<!--    <button onclick="startTimer()" id="button_start">Start</button>
    <button onclick="stopTimer()" id = "button_end">Stop</button>-->

    <div >
        <!--Duration-->
        
<!--        <p id="hours">00</p>
        <p class="timeRefHours">Hours</p>
        <p id="minutes">00</p>
        <p class="timeRefMinutes">Minutes</p>
        <p id="seconds">00</p>
        <p class="timeRefSeconds">Seconds</p>-->
    </div>
    <?php }
?>





<script type="text/javascript" src="<?php echo base_url('assets/js/skype-uri.js'); ?>"></script>
<div class="pure-u-lg-20-24 pure-u-md-24-24 pure-u-sm-24-24 content-center">

<div class="heading text-cl-primary border-b-1 padding15">

    <h2 class="margin0">Current Sessions</h2>

</div>

<div class="box clear-both">
    <div class="heading pure-g padding-t-30">

        <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
            <ul class="pure-menu-list">
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('coach/ongoing_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue">Current Session</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('coach/upcoming_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Upcoming Sessions</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('coach/histories');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Session Histories</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('coach/histories/class_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Class Session Histories</a></li>
            </ul>
        </div>
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
            if ($data_class) {
                ?>
                <table id="userTable" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="text-cl-tertiary font-light font-16 border-none">CLASS NAME</th>
                            <th class="text-cl-tertiary font-light font-16 border-none">DATE</th>
                            <th class="text-cl-tertiary font-light font-16 border-none">CLASS TIME</th>
                            <th class="text-cl-tertiary font-light font-16 border-none">MEDIA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (@$data_class as $d) { ?>
                        <tr>
                            <td>
                                <div class="status-disable bg-tertiary m-l-20">
                                    <span class="text-cl-white">
                                <a href="<?php echo site_url('coach/class_detail/schedule/'.@$d->class_id);?>" class="text-cl-primary"><?php echo(@$d->class_name); ?></a>
                                    </span>
                                </div>
                            </td>                            
                            <td><?php echo(@$d->date); ?></td>
                            <td>
                                <div class="status-disable bg-green m-l-20">
                                    <span class="text-cl-white">
                                <?php echo (date('H:i',strtotime($d->start_time))); ?> - <?php echo (date('H:i',strtotime($d->end_time))); ?>
                                    </span>
                                </div>
                            </td>
                            <td class="padding15">
                                <?php if (@$d->host_id) { ?>
                                    <a href="<?php echo site_url('webex/host_meeting') . '/' . @$d->host_id . '/c' . @$d->id; ?>"><img src="<?php echo base_url()?>/assets/icon/webex.png"></a>
                                    <?php
                                } else {?>
                                    <a href="skype:<?php echo @$skype_id_list; ?>?call"><img src='<?php echo base_url(); ?>assets/icon/skype-icn.png'/></a> 
                                <?php }?>    
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
                <?php
            } else if ($data) {
                $link = site_url('webex/available_host/' . @$data[0]->id);
                ?>
                <table id="userTable" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="text-cl-tertiary font-light font-16 border-none">STUDENT</th>
                            <th class="text-cl-tertiary font-light font-16 border-none">DATE</th>
                            <th class="text-cl-tertiary font-light font-16 border-none">SESSION TIME</th>
                            <th class="text-cl-tertiary font-light font-16 border-none">MEDIA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (@$data as $dt) { ?>
                        <tr>
                            <td>
                            <div class="status-disable bg-tertiary m-l-20">
                                <span>
                            <a onclick="webex()" id="webex_start" href="<?php echo site_url('coach/upcoming_session/student_detail/' . $dt->student_id); ?>"class="text-cl-white"> 
                                <?php echo @$student_name->fullname; ?>
                            </a>
                                </span>
                            </div>
                        </td>
                            <td><?php echo(@$dt->date); ?></td>
                            <td>
                                <div class="status-disable bg-green m-l-20">
                                    <span class="text-cl-white">
                                <?php echo (date('H:i',strtotime($dt->start_time)));?> - <?php echo (date('H:i',strtotime($dt->end_time)));?>
                                    </span>
                                </div>
                            </td>
                            <td id="skype-img">
                                <?php if (@$dt->host_id) { ?>
                                <a href="<?php echo site_url('webex/host_meeting') . '/' . @$dt->host_id . '/' . @$dt->id; ?>"><img src="<?php echo base_url()?>/assets/icon/webex.png"></a>
                                    <?php
                                } else {?>
                                    <div id="SkypeButton_Call_fsfesfse">
                                        <script type="text/javascript">
                                            Skype.ui({
                                                "name": "dropdown",
                                                "element": "skype-img",
                                                "participants": ["<?php echo(@$student_name->skype_id); ?>"],
                                                "imageSize": 32
                                            });
                                        </script>
                                    </div>
                                <?php }?>    
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            <?php }else{
                echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
            } ?>
        </div>
    </div>
</div>

<script>
$(function() {
    $('textarea').css({'resize': 'none'});
    $('.e-only').hide();
    $('.close_click').hide();
    $('.save_click').hide();

    var arrText= new Array();
    var arrTextarea= new Array();
    var arrSelect= new Array();
    var arrMultiple= new Array();

    var isEnabled = true;

    $('.e-only').bind('keypress', function (e) {
        var code = e.keyCode || e.which;
        if (code === 13) {
            $('#form_info').submit();
        }
    });

    $('#btn_save_info').click(function () {
        $('#form_info').submit();
    });

    $('#profile_picture').change(function () {
        $('#btn_upload').attr('disabled', false);
    });

    $('.box').each(function(){

        var _each = $(this);
        
        $('.edit_click', _each).click(function () {
            if (isEnabled == true) {

                isEnabled = false;

                $('.e-only', _each).show();
                $('.r-only', _each).hide();
                $('.close_click', _each).show();
                $('.save_click', _each).show();
                $('.edit_click', _each).hide();

                $('.e-only').not($('.e-only', _each)).hide();
                $('.r-only').not($('.r-only', _each)).show();
                $('.close_click').not($('.close_click', _each)).hide();
                $('.save_click').not($('.save_click', _each)).hide();
                $('.edit_click').not($('.edit_click', _each)).show();

                _close = $('.close_click', _each);
                _save = $('.save_click', _each);
                $('.e-only:first', _each).focus();
                animationClick(_close, 'fadeIn');
                animationClick(_save, 'fadeIn');

                $('#form_account').parsley().reset();
                                $('#form_info').parsley().reset();
                                    
                                $('#form_more_info').parsley().reset();
                
                $('input[type=text]', _each).each(function(){
                    arrText.push($(this).val());
                });

                $('select', _each).each(function(){
                    arrSelect.push($(this).val());
                });

                $('textarea', _each).each(function(){
                    arrTextarea.push($(this).val());
                });
                
                arrMultiple = $('.multiple-select').val();
            }    
        });

        $('.close_click', _each).click(function () {

            isEnabled = true;

            $('.close_click', _each).hide();
            $('.save_click', _each).hide();
            $('.edit_click', _each).show();
            $('.r-only', _each).show();
            $('.e-only', _each).hide();
            _edit = $('.edit_click', _each);
            animationClick(_edit, 'fadeIn');

            $('#form_account').parsley().reset();
                
                        $('#form_more_info').parsley().reset();
                                    $('#form_info').parsley().reset();
            
            var input = $('input[type=text]', _each);

            for(i = 0; i < input.length; i++) {
              input[i].value = arrText[i];
            }

            var select = $('select', _each);

            for(i = 0; i < select.length; i++) {
              select[i].value = arrSelect[i];
            }

            var textarea = $('textarea', _each);

            for(i = 0; i < textarea.length; i++) {
              textarea[i].value = arrTextarea[i];
            }

            var multiple = $('.multiple-select');
            multiple.val(arrMultiple).trigger("change");

            
            arrText = [];
            arrTextarea = [];
            arrSelect = [];
            arrMultiple = [];

            
        });


        $('.save_click', _each).click(function () {

        });

    });

    $('tr').each(function(e){
        var inputs = $(this);

        $('input',inputs).on('blur', function () {
            $('td',inputs).removeClass('inline').addClass('no-inline');
        }).on('focus', function () {
            $('td',inputs).removeClass('no-inline').addClass('inline');
        });

        $('textarea',inputs).on('blur', function () {
            $('td',inputs).removeClass('inline').addClass('no-inline');
        }).on('focus', function () {
            $('td',inputs).removeClass('no-inline').addClass('inline');
        });

        $('td',inputs).css({'position':'relative'});
    })


    $('.caption').click(function () {
        $(".dropdown-form-photo").show();
    });

    $('.cancel-upload').click(function () {
        $(".dropdown-form-photo").hide();
    });

    $('body').on('click', '.upload-click', function () {
        $('#profile_picture').click();
    });

    $('.parsley-errors-list').css({'position':'absolute','bottom':'0','right':'0','color':'#E9322D'});
    $('input.parsley-error').css({'border':'none'});
    $('select.parsley-error').css({'border':'none'});
    $('textarea.parsley-error').css({'border':'none'});

    $('.multiple-select').select2({
         placeholder: "Preferred Languages"
    });
    $('.multiple-select-certificate').select2({
         placeholder: "Select Certification Goal"
    });

    $('#form_info').parsley();
    $('#form_more_info').parsley();
    parsley_float();

    $('#parsley-id-multiple-spoken_lang').css({'position':'absolute','bottom':'75px','right':'0','color':'#E9322D'});

    $(".select2").addClass("e-only");
    $(".e-only").hide();
    $(".datepicker").datepicker({
        format: 'yyyy-mm-dd',
        changeMonth: true,
        changeYear: true,
        defaultDate: new Date(1990, 00, 01),
        endDate: "now"
    });

    $('.input-daterange').datepicker({
        format: "yyyy",
        startView: 2,
        minViewMode: 2
    });

    $('.select2-container--default .select2-selection--multiple').css("cssText", "border: none !important;");
    

    $(".multiple-select").on('change',function(){
        document.getElementById('spoken_language').value = $(".multiple-select").val();
    });

    $('.save_click').on('click',function(){
        document.getElementById('spoken_language').value = $(".multiple-select").val();
    });
    
    $('a.dc').click(function(){
            return false;
    });

    $('a.reschedule-session2').click(function(){
            return false;
    });
})

</script>
                        </div>
                </section>
            </div>
        </div>
        <script type="text/javascript">
            // $(document).ready(function(){    
            // $(".listTop, .listBottom").children("ul").hide();            
            //    $(".listTop, .listBottom").click(function(event){
            //      event.stopPropagation();
            //      $(this).children("ul").slideToggle();

            //    });
            // });
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