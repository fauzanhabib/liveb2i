<div class="heading text-cl-primary border-b-1 padding15">

    <h2 class="margin0">Zoom</h2>

</div>

<div class="box clear-both">
    <div class="content">
        <div class="box">
            <?php if(@$start_url){?>
            <div>
                <p>Status: <?php echo @$status; ?></p>
                <p>Start Time: <?php echo @$start_time; ?></p>
                <p>Duration: <?php echo @$duration; ?></p>
                <p>Student Url: <?php echo @$std_url; ?></p>
                <p>Coach Url: <?php echo @$cch_url; ?></p>
                <p>host id: <?php echo @$host_id; ?></p>
                <p>meeting id: <?php echo @$meeting_id; ?></p>
                <!-- <a href="<?php echo $start_url; ?>" target="_blank">Join</a> -->
            </div>
            <?php } ?>
        </div>
        <a href="<?php echo site_url('student/zoomtest'); ?>">back creating</a>
        <form action="<?php echo site_url('student/zoomtest/endAMeeting'); ?>" method="POST">
            <font>meeting id:</font><br>
            <input type="text" name="meetingId" value="<?php echo @$meeting_id; ?>"><br>
            <font>host id:</font><br>
            <input type="text" name="userId" value="<?php echo @$host_id; ?>"><br>
            <input type="submit" value="Create">
        </form>
    </div>  
</div>