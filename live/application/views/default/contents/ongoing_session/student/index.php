<div class="alert success">
    <h3>SUCCESS</h3>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima, placeat.</p>
</div>

<div class="alert error">
    <h3>error</h3>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima, placeat.</p>
</div>

<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Sessions</h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-1 tab-list tab-link">
            <a href="<?php echo site_url('student/ongoing_session');?>" class="active" data-tab="tab-0">Ongoing Session</a>
            <a href="<?php echo site_url('student/upcoming_session');?>" data-tab="tab-1">Upcoming Session</a>
            <a href="<?php echo site_url('student/histories');?>" data-tab="tab-1">Session History</a>
        </div>
    </div>

    <div class="content tab-content" style="margin-top: -18px">
        <div id="tab0" class="tab active">
            <table class="table-session"> 
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>START TIME</th>
                        <th>END TIME</th>
                        <th>COACH</th>
                        <th colspan="2">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (@$data) {
                        foreach ($data as $d) {
                            ?>
                            <tr>
                                <td><?php echo date('F, j Y', strtotime($d->date)); ?></td>
                                <td class="session"><?php echo $d->start_time ?></td>
                                <td class="session"><?php echo $d->end_time ?></td>
                                <td class="coach"><a href=""><?php echo @$coach_name->fullname ?></a></td>
                                <td class="reschedule"><a href="">Re Schedule</a></td>
                                <td class="cancle"><a href="">Cancel</a></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            <a href="" class="more">More</a>
        </div>
    </div>
</div>