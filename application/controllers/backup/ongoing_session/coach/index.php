<?php //$this->session->unset_userdata('timer'); exit;?>
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
<?php if (@$this->session->userdata('timer') == 'begin') { ?>
            $('#button1').hide();
            upTime('may,05,2015,16:30:00');
    <?php
} else if(@$this->session->userdata('timer') == 'finished') {
    ?>
            //upTime('may,05,2015,16:30:00');
            $('#button1').hide();
            $('#button2').hide();
            $('#countup').hide();
    <?php
} else{
?>
        $('#button2').hide();
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
        upTime('may,05,2015,16:30:00');
        $('#countup').show();
        $('#button1').hide();
        $('#button2').show();
        <?php $this->session->set_userdata('timer', 'begin'); ?>
    }

    // function to end the timer
    function stopTimer()
    {
        if (confirm('Are you sure?')) {
            <?php //$this->session->set_userdata('timer', 'finished'); ?>
            return (
                    $('#countup').hide(),
                    $('#button2').hide()
                    );
        }
    }



    function upTime(countTo) {
        now = new Date();
        countTo = new Date(countTo);
        difference = (now - countTo);

        hours = Math.floor((difference % (60 * 60 * 1000 * 24)) / (60 * 60 * 1000) * 1);
        mins = Math.floor(((difference % (60 * 60 * 1000 * 24)) % (60 * 60 * 1000)) / (60 * 1000) * 1);
        secs = Math.floor((((difference % (60 * 60 * 1000 * 24)) % (60 * 60 * 1000)) % (60 * 1000)) / 1000 * 1);

        document.getElementById('hours').firstChild.nodeValue = hours;
        document.getElementById('minutes').firstChild.nodeValue = mins;
        document.getElementById('seconds').firstChild.nodeValue = secs;

        clearTimeout(upTime.to);
        upTime.to = setTimeout(function () {
            upTime(countTo);
        }, 1000);
    }
</script>

<button onclick="startTimer()" id="button1">Start</button>
<button onclick="stopTimer()" id = "button2">Stop</button>

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
        <td> <a href="<?php echo site_url('coach/upcoming_session/student_detail/' . @$data->student_id); ?>"><?php echo @$student_name->fullname; ?></a> </td>
        <td><?php echo(@$data->date); ?></td>
        <td><?php echo(@$data->start_time); ?></td>
        <td><?php echo(@$data->end_time); ?></td>

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