<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Manage Student Session</h1>
    <h1 class="margin0"><small>Name : <?php echo @$user->fullname; ?></small></h1>
</div>
    
<div class="box">
    <div class="heading pure-g">
        <!-- block edit -->
        <div class="pure-u-1 edit edit-no-right padding15">
            <a href="<?php echo site_url('student_partner/schedule/create/' . @$student_id); ?>" class="add"><i class="icon icon-add"></i>Add New Session</a>
        </div>
        <!-- end block edit -->
    </div>
    
    <div class="content padding0">
        <div class="box">
            <?php if($appointments){ ?>  
            <div class="b-pad">
            <table class="table-session"> 
                <thead>
                    <tr>
                        <th class="padding15">DATE</th>
                        <th class="padding15">COACH NAME</th>
                        <th class="padding15">START DATE</th>
                        <th class="padding15">END DATE</th>
                        <th class="padding15">STATUS</th>
                        <th class="padding15">ACTION</th>
                    </tr>
                </thead> 
                <tbody class="manage-schedule">
                    <?php $i = 1; foreach ($appointments as $appointment){ ?>
                    <tr>
                        <td class="padding15" data-label="DATE">
                            <?php echo($appointment->date); ?>
                        </td>
                        <td class="padding15" data-label="COACH NAME"><span class="text-cl-secondary"><?php echo($appointment->coach_fullname); ?></span></td>
                        <td class="padding15" data-label="START DATE"><span class="text-cl-green"><?php echo($appointment->start_time); ?></span></td>
                        <td class="padding15" data-label="END DATE"><span class="text-cl-green"><?php echo($appointment->end_time); ?></span></td>
                        <td class="padding15" data-label="STATUS"><span class="labels <?php echo($appointment->status); ?>" style="width:92px"><?php echo($appointment->status); ?></span></td>
                        <td class="sm-12 padding15 t-center"> 
                            <div class="rw">
                                <div class="b-50">
                                    <a class="pure-button btn-small btn-white reschedule-session" onclick="confirmation('<?php echo site_url('student_partner/manage_session/reschedule/' . @$student_id . '/' . @$appointment->id); ?>', 'group', 'Reschedule Session', 'manage-schedule', 'reschedule-session');">
                                        RESCHEDULE
                                    </a>  
                                </div>
                                <div class="b-50">    
                                    <a class="pure-button btn-small btn-white cancel-session" onclick="confirmation('<?php echo site_url('student_partner/manage_session/cancel/' . @$student_id . '/' . @$appointment->id); ?>', 'group', 'Cancel Session', 'manage-schedule', 'cancel-session');">
                                        CANCEL
                                    </a> 
                                </div>    
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
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

<script type="text/javascript">
    $(function(){
        $('.cancel-session').click(function(){
            return false;
        });
        $('.reschedule-session').click(function(){
            return false;
        });
    })
</script>