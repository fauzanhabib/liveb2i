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
        //$('#countup').hide();

<?php if (!$this->session->userdata('timer')) { ?>
            $('#button_start').show();
    <?php
}
?>

<?php if (@$duration->start_time && @$duration->end_time == '00:00:00') { ?>
            $.post("<?php echo(base_url() . '/index.php/third_party/set_session/current_server_time'); ?>", function (data) {
                upTime("<?php echo(strtolower(date('M', strtotime(@$data[0]->date))).date(',d,Y,',strtotime(@$data[0]->date))).@$duration->start_time;?>", data);
                //alert("<?php //echo(strtolower(date('M', strtotime(@$data[0]->date))).date(',d,Y,',strtotime(@$data[0]->date))).@$duration->start_time;?>");
                $('#countup').show();
            });
    <?php
} else if (@$duration->start_time && @$duration->end_time != '00:00:00') {
    ?>
            upTime("<?php echo(strtolower(date('M', strtotime(@$data[0]->date))).date(',d,Y,',strtotime(@$data[0]->date))).@$duration->start_time;?>", "<?php echo(strtolower(date('M', strtotime(@$data[0]->date))).date(',d,Y,',strtotime(@$data[0]->date))).@$duration->end_time;?>");
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
        <?php if (@$duration->start_time && @$duration->end_time == '00:00:00') { ?>
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
if($data){?>
<table border="1">
    <tr>
        <th>No</th>
        <th>Skype Call</th>
        <th>WebEx Call</th>
        <th>Coach</th>
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
            <form name="joinmeeting" ACTION = "https://<?php echo @$webex->subdomain_webex?>.webex.com/<?php echo @$webex->subdomain_webex?>/w.php" METHOD = "POST">
                <INPUT TYPE="HIDDEN" NAME="AT" VALUE="JO">
                <INPUT TYPE="HIDDEN" NAME="BU" VALUE="<?php echo site_url('student/ongoing_session/webex');?>">
                <INPUT TYPE="HIDDEN" NAME="MK" VALUE="<?php echo @$data[0]->webex_meeting_number;?>">
                <INPUT TYPE="submit" name="B1" value = "WebEx" <?php echo (@$data[0]->webex_meeting_number)? '' : 'disabled' ?> >
            </form>
        </td>
        <td> <a href="<?php echo site_url('student/upcoming_session/coach_detail/' . @$data[0]->coach_id); ?>"><?php echo @$coach_name->fullname; ?></a> </td>
        <td><?php echo(@$data[0]->date); ?></td>
        <td><?php echo(@$data[0]->start_time); ?></td>
        <td><?php echo(@$data[0]->end_time); ?></td>
    </tr>
</table>
<?php }
if($data_class){?>
<table border="1">
    <tr>
        <th>No</th>
        <th>Skype Call</th>
        <th>WebEx Call</th>
        <th>Class Name</th>
        <th>Coach</th>
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
            <form name="joinmeeting" ACTION = "https://<?php echo @$webex_class->subdomain_webex;?>.webex.com/<?php echo @$webex_class->subdomain_webex;?>/w.php" METHOD = "POST">
                <INPUT TYPE="HIDDEN" NAME="AT" VALUE="JO">
                <INPUT TYPE="HIDDEN" NAME="BU" VALUE="<?php echo site_url('student/ongoing_session/webex');?>">
                <INPUT TYPE="HIDDEN" NAME="MK" VALUE="<?php echo @$data_class[0]->webex_meeting_number;?>">
                <INPUT TYPE="submit" name="B1" value = "WebEx" <?php echo (@$data_class[0]->webex_meeting_number)? '' : 'disabled' ?> >
            </form>
        </td>
        <td><?php echo(@$data_class[0]->class_name); ?></td>
        <td> <a href="<?php echo site_url('student/upcoming_session/coach_detail/' . @$data_class[0]->coach_id); ?>"><?php echo @$coach_name_class->fullname; ?></a> </td>
        <td><?php echo(@$data_class[0]->date); ?></td>
        <td><?php echo(@$data_class[0]->start_time); ?></td>
        <td><?php echo(@$data_class[0]->end_time); ?></td>
    </tr>
</table>
<?php }?>
<script>
    //action for skype
    Skype.ui({
        "name": "dropdown",
        "element": "SkypeButton_Call",
        "participants": ["<?php echo(@$coach_name->skype_id); ?>"],
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