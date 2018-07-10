<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Create Session Between Student and Coach</h1>
</div>

<div class="box">
    <div class="heading pure-g"></div>

    <div class="content padding0">
        <div class="box">
            <?php if($user_profiles){ ?>  
            <table class="table-session"> 
                <thead>
                    <tr>
                        <th style="width: 4%;" class="text-center padding-10-15">NO</th>
                        <th class="padding-10-15" style="width: 16%;">Student's Name</th>
                        <th class="padding-10-15">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;foreach ($user_profiles as $user_profile){ ?>
                    <tr>
                        <td style="width: 4%;vertical-align: middle;" class="text-center padding-10-15"><?php echo($i++); ?></td>
                        <td class="padding-10-15" style="width: 16%;vertical-align: middle;"><?php echo $user_profile->fullname; ?></td>
                        <td class="padding-10-15" style="vertical-align: middle;"> <a href="<?php echo site_url('partner_monitor/schedule/manage/'.@$user_profile->id );?>" class="pure-button btn-small btn-white">MANAGE</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php }
             else { ?>
            <div class="no-result padding-10-15">
                No Data
            </div>
            <?php } ?>  
        </div>
    </div>    
</div>