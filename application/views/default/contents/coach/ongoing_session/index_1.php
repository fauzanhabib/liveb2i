<script src="http://www.datejs.com/build/date.js" type="text/javascript"></script>

<style type="text/css">
    #countup p {
        display: inline-block;
        padding: 1px;
        //background: #FFA500;
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
            upTime("<?php echo ($this->session->userdata('duration_begin')); ?>", "<?php echo ($this->session->userdata('current_server_time')); ?>");
            $('#button_start').hide();
            $('#button_end').hide();
            //$('#countup').hide();
    <?php
} else {
    ?>
            $('#button_end').hide();
            $('#countup').hide()
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
            else{
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
<button onclick="startTimer()" id="button_start">Start</button>
<button onclick="stopTimer()" id = "button_end">Stop</button>

<div id="countup">
    Duration
    <br>
    <p id="hours">00</p>
    <p class="timeRefHours">Hours</p>
    <p id="minutes">00</p>
    <p class="timeRefMinutes">Minutes</p>
    <p id="seconds">00</p>
    <p class="timeRefSeconds">Seconds</p>
</div>




<script type="text/javascript" src="<?php echo base_url('assets/js/skype-uri.js'); ?>"></script>
<div class="heading text-cl-primary padding15">
    <h1 class="margin0"><small>Coach Shedule</small></h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-6-24">
            <h3 class="h3 font-normal padding15 text-cl-secondary">SCHEDULE SESSION</h3>
        </div>
        <div class="pure-u-18-24 edit tab-link">
            <a href="<?php echo site_url('coach/upcoming_session'); ?>">Upcoming Session</a>
            <a href="<?php echo site_url('coach/ongoing_session'); ?>" class="active">Ongoing Session</a>
            <a href="<?php echo site_url('coach/histories');?>">History Session</a>
        </div>
    </div>

    <div class="content tab-content" style="margin-top: -18px">
        <div id="tab1" class="tab active">

            <?php
            if ($data_class) {
                ?>
                <table class="table-session">
                    <thead>
                        <tr>
                            <th>Class Name</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Skype Call</th>
                            <th>WebEx Call</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo(@$data_class[0]->class_name); ?></td>
                            <td><?php echo(@$data_class[0]->date); ?></td>
                            <td><?php echo(@$data_class[0]->start_time); ?></td>
                            <td><?php echo(@$data_class[0]->end_time); ?></td>
                            <td>
                                <a href="skype:<?php echo @$skype_id_list; ?>?call"><img src='<?php echo base_url(); ?>assets/icon/skype-icn.png'/></a> 
                            </td>
                            <td>
                                <?php if (@$data_class[0]->host_id) { ?>
                                    <a href="<?php echo site_url('webex/host_meeting') . '/' . @$data_class[0]->host_id . '/c' . @$data_class[0]->id; ?>">WebEx Call</a>
                                    <?php
                                } else {
                                    echo "WebEx Call";
                                }
                                ?>    
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php
            } else if ($data) {
                $link = site_url('webex/available_host/' . @$data[0]->id);
                ?>
                <table class="table-session">
                    <thead>
                        <tr>
                            <th>STUDENT</th>
                            <th>DATE</th>
                            <th>START TIME</th>
                            <th>END TIME</th>
                            <th>SKYPE</th>
                            <th>WEBEX</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td> <a onclick="webex()" id="webex_start" href="<?php echo site_url('coach/upcoming_session/student_detail/' . @$data[0]->student_id); ?>"><?php echo @$student_name->fullname; ?></a> </td>
                            <td><?php echo(@$data[0]->date); ?></td>
                            <td><?php echo(@$data[0]->start_time); ?></td>
                            <td><?php echo(@$data[0]->end_time); ?></td>
                            <td id="skype-img">
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
                            </td>
                            <td>
                                <?php if (@$data[0]->host_id) { ?>
                                    <a href="<?php echo site_url('webex/host_meeting') . '/' . @$data[0]->host_id . '/' . @$data[0]->id; ?>">WebEx Call</a>
                                    <?php
                                } else {
                                    echo "WebEx Call";
                                }
                                ?>    
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</div>