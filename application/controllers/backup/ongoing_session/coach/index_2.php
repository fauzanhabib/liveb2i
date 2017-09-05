<?php
//echo(strtolower(date('M')).date(',d,Y,H:i:s')); exit;
//may,05,2015,16:30:00
//may,06,2015,22:24:39
$this->session->unset_userdata('timer');
//$this->session->unset_userdata('test_index');
$this->session->unset_userdata('duration_begin');
$this->session->unset_userdata('duration_end');
$this->session->unset_userdata('current_server_time');
$this->session->unset_userdata('duration_begin_time');
$this->session->unset_userdata('duration_end_time');
//echo(@$this->session->userdata('sample'));
//echo($this->session->userdata('timer'));
//echo($this->session->userdata('duration_begin'));
//print_r($this->session->userdata);
?>
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
            $('#countup').hide();
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
            $.post("<?php echo(base_url() . '/index.php/third_party/set_duration/create_duration/'.$data[0]->id); ?>", function (data) {});
        });

        $('#countup').show();
        $('#button_start').hide();
        $('#button_end').show();

    }

    // function to end the timer
    function stopTimer()
    {
        if (confirm('Are you sure?')) {
            $.post("<?php echo(base_url() . '/index.php/third_party/set_session/end_duration'); ?>", function (data) {
                upTime("<?php echo ($this->session->userdata('duration_begin')); ?>", data);
                $.post("<?php echo(base_url() . '/index.php/third_party/set_duration/update_duration/'); ?>", function (data) {});
            });
            return (
                    $('#countup').hide(),
                    $('#button_end').hide()
                    );
        }
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
        <?php
        if(@$this->session->userdata('timer') != 'finished'){ ?>
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

<br>
<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
echo("<br> Ongoing Session <br><br>");
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Skype Call</th>
        <th>WebEx Call</th>
        <th>Student</th>
        <th>Date</th>
        <th>Start Time</th>
        <th>End Time</th>
    </tr>
    <?php $i = 1; ?>
    <tr>
        <td><?php echo($i++); ?></td>
        <td>
            <div id="SkypeButton_Call"></div>
        </td>
        <td>
            <form name="HostMeetingForm" ACTION = "https://<?php echo @$site_url?>.webex.com/<?php echo @$site_url?>/m.php" METHOD = "POST">
                <INPUT TYPE="HIDDEN" NAME="AT" VALUE="HM">
                <INPUT TYPE="HIDDEN" NAME="MK" VALUE="<?php echo @$data[0]->webex_meeting_number;?>">
                <INPUT TYPE="HIDDEN" NAME="BU" VALUE = "http://idbuild.id.dyned.com/live/index.php/account/identity">
                <INPUT TYPE="submit" name="btnHostMeeting" value = "WebEx" <?php echo (@$data[0]->webex_meeting_number)? '' : 'disabled' ?> >
            </form>
        </td>
        <td> <a href="<?php echo site_url('coach/upcoming_session/student_detail/' . @$data[0]->student_id); ?>"><?php echo @$student_name->fullname; ?></a> </td>
        <td><?php echo(@$data[0]->date); ?></td>
        <td><?php echo(@$data[0]->start_time); ?></td>
        <td><?php echo(@$data[0]->end_time); ?></td>

    </tr>

</table>

<script>
    //action for skype
    Skype.ui({
        "name": "dropdown",
        "element": "SkypeButton_Call",
        "participants": ["<?php echo(@$student_name->skype_id); ?>"],
        "imageSize": 20
    });

    // to insert date picker
    $(function () {
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: new Date()
        });
    });
</script>