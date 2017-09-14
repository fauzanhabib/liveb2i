<div class="heading text-cl-primary border-b-1 padding15">

    <h2 class="margin0">Zoom</h2>

</div>

<div class="box clear-both">
    <!-- <div class="heading pure-g padding-t-30">

        <div class="left-list-tabs pure-menu pure-menu-horizontal padding-l-25 margin0">
            <ul class="pure-menu-list">
                <li class="pure-menu-item pure-menu-selected text-center width150 no-hover"><a href="<?php echo site_url('student/token'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue">Token History</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width150 no-hover"><a href="<?php echo site_url('student/token_requests'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Token Request</a></li>
            </ul>
        </div>

    </div> -->

    <div class="content">
        <div class="box">
            <b>Create Meeting</b><br>
            <form action="<?php echo site_url('student/zoomtest/createAMeeting'); ?>" method="POST">
                <font>Student Name:</font><br>
                <input type="text" name="std_name"><br>
                <font>Coach Name:</font><br>
                <input type="text" name="cch_name"><br>
                <font>Duration:</font><br>
                <input type="text" name="duration"><br>
                <font>Start Date:</font><br>
                <input type="text" name="start_date"><br>
                <font>Start Time:</font><br>
                <input type="text" name="start_time"><br><br>
                <input type="submit" value="Create">
            </form>
            <form action="<?php echo site_url('student/zoomtest/listMeetings'); ?>" method="POST">
                <input type="submit" value="List of Meetings">
            </form>
        </div>
    </div>  
</div>