<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Session Summaries</h1>
</div>

<div class="box b-f3-1">
  <div class="content padding15">
    <div class="col-md-12">
        <p>Your Session with <b><?php echo $user->fullname;?></b> has been cancelled.</p>
        <table id="thetable" class="table-sessions" style="border-top:1px solid #f3f3f3"> 
            <thead>
                <tr>
                    <th class="padding15 sm-12 tb-ses-up">DATE</th>
                    <th class="padding15 md-none">START TIME</th>
                    <th class="padding15 md-none">END TIME</th>
                    <th class="padding15 md-none">
                    <?php if($role=='CCH'){ ?>
                    STUDENT
                    <?php } else{?>
                    COACH
                    <?php }?>
                    </th>
                    <th class="padding15 sm-12">STATUS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="padding-10-15 sm-12 tb-ses-up">
                        <?php echo(date('F d, Y', strtotime(@$user->date))); ?>
                        <span class="text-cl-green lg-none"><?php echo(date('H:i',strtotime(@$user->start_time)));?> - <?php echo(date('H:i',strtotime(@$user->start_time)));?></span>
                        <span class="lg-none">
                            Student :<br>
                            <a href="<?php echo site_url('coach/upcoming_session/student_detail/' . $user->student_id); ?>" class="text-cl-secondary">
                                <?php echo @$user->student_name; ?>
                            </a>
                        </span>
                    </td>
                    <td class="time padding-10-15 md-none text-cl-green"><?php echo(date('H:i',strtotime(@$user->start_time)));?></td>
                    <td class="time padding-10-15 md-none text-cl-green"><?php echo(date('H:i',strtotime(@$user->end_time)));?></td>
                    <td class="padding-10-15 md-none">
                        <?php echo @$user->fullname; ?>
                    </td>
                    <td class="padding-10-15 sm-12">
                        SESSION CANCELED
                    </td>
                </tr>
            </tbody>
        </table>
            <br>
        <table>
            <tr>
                <td>Student Attends At</td>
                <td>: <?php echo $user->std_attend; ?></td>
            </tr>
            <tr>
                <td>Coach Attends At</td>
                <td>: <?php echo $user->cch_attend; ?></td>
            </tr>
            <tr>
                <td style="width:16%;">Notice</td>
                <td style="width:70%;">: <?php echo $notice;?></td>
            </tr>
        </table>
        <?php 
            $appoint_id = $appoint_id;
            if($role=='STD' && $flag = 1){ ?>
            <form name ="cancelling" action="<?php echo(site_url('opentok/reclaimtoken/'));?>" method="post">
                <input type="hidden" name="appoint_id" value="<?php echo $appoint_id ?>"><br>
                <input type="submit" value="Reclaim Token" class="pure-button btn-small btn-white">
            </form>
        <?php } ?>
    </div>
  </div>
</div>