<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Manage Student Session</h1>
    <h1 class="margin0"><small>Name : <?php echo @$user->fullname; ?></small></h1>
</div>
    
<div class="box">
    <div class="heading pure-g">
        <!-- block edit -->
        <div class="pure-u-1 edit edit-no-right padding15">
            <a href="<?php echo site_url('partner_monitor/schedule/create/' . @$student_id); ?>" class="add"><i class="icon icon-add"></i>Add New Session</a>
        </div>
        <!-- end block edit -->
    </div>
    
    <div class="content padding0">
        <div class="box">
            <?php if($appointments){ ?>  
            <table class="table-session"> 
                <thead>
                    <tr>
                        <!--<th style="width: 4%;" class="text-center padding-10-15 sm-12">NO</th>-->
                        <th class="sm-12 padding15">DATE</th>
                        <th class="md-none padding15">COACH NAME</th>
                        <th class="md-none padding15">START DATE</th>
                        <th class="md-none padding15">END DATE</th>
                        <th class="md-none padding15">STATUS</th>
                        <th class="sm-12 padding15">ACTION</th>
                    </tr>
                </thead> 
                <tbody class="manage-schedule">
                    <?php $i = 1; foreach ($appointments as $appointment){ ?>
                    <tr>
                        <!--<td style="width: 4%;vertical-align: middle;" class="text-center padding-10-15 sm-12"><?php //echo($i++); ?></td>-->
                        <td class="sm-12 padding15">
                            <?php echo($appointment->date); ?><br>
                            <span class="lg-none">
                                <span class="text-cl-green"><?php echo($appointment->start_time); ?> - <?php echo($appointment->end_time); ?></span>
                                <span class="text-cl-secondary" style="text-decoration: underline;margin-bottom: 5px;display: block;"><?php echo($appointment->coach_fullname); ?></span>
                                <span class="labels <?php echo($appointment->status); ?>"><?php echo($appointment->status); ?></span>
                            </span>
                        </td>
                        <td class="md-none padding15"><?php echo($appointment->coach_fullname); ?></td>
                        <td class="md-none padding15"><?php echo($appointment->start_time); ?></td>
                        <td class="md-none padding15"><?php echo($appointment->end_time); ?></td>
                        <td class="md-none padding15"><span class="labels <?php echo($appointment->status); ?>"><?php echo($appointment->status); ?></span></td>
                        <td class="sm-12 padding15"> 
                            <a class="pure-button btn-small btn-white reschedule-session">
                                <span onclick="confirmation('<?php echo site_url('partner_monitor/manage_session/reschedule/' . @$student_id . '/' . @$appointment->id); ?>', 'group', 'Reschedule Session', 'manage-schedule', 'reschedule-session');">RESCHEDULE</span>
                            </a>  
                            <a class="pure-button btn-small btn-white cancel-session">
                                <span onclick="confirmation('<?php echo site_url('partner_monitor/manage_session/cancel/' . @$student_id . '/' . @$appointment->id); ?>', 'group', 'Cancel Session', 'manage-schedule', 'cancel-session');">CANCEL</span>
                            </a> 
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php }
             else { ?>
            <div class=" padding-10-15">
                <div class="no-result">
                    No Data
                </div>
            </div>
            <?php } ?>  
        </div>
    </div>    
</div>