<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Sessions</h1>
</div>

<div class="box">
    <div class="heading pure-g"> 
        <div class="pure-u-1 tab-list tab-link">
            <ul>
                <li class="current"><a href="<?php echo site_url('student/ongoing_session'); ?>" class="active">Ongoing Session</a></li>
                <li><a href="<?php echo site_url('student/upcoming_session'); ?>">Upcoming Session</a></li>
                <li><a href="<?php echo site_url('student/histories'); ?>">History Session</a></li>
            </ul>
        </div>
    </div>

    <div class="content tab-content" style="margin-top: -18px">
        <div id="tab0" class="tab active">
            <?php if ($data) {?>
                <table class="table-session"> 
                    <thead>
                        <tr>
                            <th class="sm-12">DATE</th>
                            <th class="md-none">START TIME</th>
                            <th class="md-none">END TIME</th>
                            <th class="md-none">COACH</th>
                            <th class="sm-12"colspan="2">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="sm-12">
                                <?php echo date('F, j Y', strtotime($data[0]->date)); ?><br>
                                <span class="lg-none text-cl-green"><?php echo $data[0]->start_time ?> - <?php echo $data[0]->end_time ?></span>
                                <span class="lg-none">Coach <a href=""><?php echo @$coach_name->fullname ?></span>
                            </td>
                            <td class="session md-none"><?php echo $data[0]->start_time ?></td>
                            <td class="session md-none"><?php echo $data[0]->end_time ?></td>
                            <td class="coach md-none"><a href=""><?php echo @$coach_name->fullname ?></a></td>
                            <td id="skype-img">
                                <div id="SkypeButton_Call_fsfesfse">
                                    <script type="text/javascript">
                                        Skype.ui({
                                            "name": "dropdown",
                                            "element": "skype-img",
                                            "participants": ["<?php echo(@$coach_name->skype_id); ?>"],
                                            "imageSize": 32
                                        });
                                    </script>
                                </div>
                            </td>
                            <td>
                                <form name="joinmeeting" ACTION = "https://<?php echo @$webex->subdomain_webex; ?>.webex.com/<?php echo @$webex->subdomain_webex; ?>/m.php" METHOD = "POST">
                                    <INPUT TYPE="HIDDEN" NAME="AT" VALUE="JM">
                                    <INPUT TYPE="HIDDEN" NAME="BU" VALUE="<?php echo site_url('student/ongoing_session/webex'); ?>">
                                    <INPUT TYPE="HIDDEN" NAME="MK" VALUE="<?php echo @$data[0]->webex_meeting_number; ?>">
                                    <INPUT TYPE="HIDDEN" NAME="AN" VALUE="<?php echo $this->auth_manager->get_name(); ?>">
                                    <INPUT TYPE="HIDDEN" NAME="AE" VALUE="<?php echo $this->auth_manager->user_email(); ?>">
                                    <INPUT TYPE="HIDDEN" NAME="PW" VALUE="<?php echo $data[0]->session_password; ?>">
                                    <INPUT TYPE="submit" name="B1" value = "WebEx" <?php echo (@$data[0]->webex_meeting_number) ? '' : 'disabled' ?> >
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php }else if ($data_class) {?>
                <table class="table-session"> 
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th>START TIME</th>
                            <th>END TIME</th>
                            <th>CLASS NAME</th>
                            <th colspan="2">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo date('F, j Y', strtotime($data_class[0]->date)); ?></td>
                            <td class="session"><?php echo $data_class[0]->start_time ?></td>
                            <td class="session"><?php echo $data_class[0]->end_time ?></td>
                            <td class="coach"><a href=""><?php echo $data_class[0]->class_name ?></a></td>
                            <td id="skype-img">
                                <div id="SkypeButton_Call_fsfesfse">
                                    <script type="text/javascript">
                                        Skype.ui({
                                            "name": "dropdown",
                                            "element": "skype-img",
                                            "participants": ["<?php echo(@$coach_name->skype_id); ?>"],
                                            "imageSize": 32
                                        });
                                    </script>
                                </div>
                            </td>
                            <td>
                                <form name="joinmeeting" ACTION = "https://<?php echo @$webex_class->subdomain_webex; ?>.webex.com/<?php echo @$webex_class->subdomain_webex; ?>/w.php" METHOD = "POST">
                                    <INPUT TYPE="HIDDEN" NAME="AT" VALUE="JO">
                                    <INPUT TYPE="HIDDEN" NAME="BU" VALUE="<?php echo site_url('student/ongoing_session/webex'); ?>">
                                    <INPUT TYPE="HIDDEN" NAME="MK" VALUE="<?php echo @$data_class[0]->webex_meeting_number; ?>">
                                    <INPUT TYPE="submit" name="B1" value = "WebEx" <?php echo (@$data_class[0]->webex_meeting_number) ? '' : 'disabled' ?> >
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php } else{
                    echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
                } ?>
            </div>
        </div>
    </div>


<script type="text/javascript" src="<?php echo base_url('assets/js/skype-uri.js'); ?>"></script>
    
