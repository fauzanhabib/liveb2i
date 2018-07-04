<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Create Session Between Student and Coach</h1>
</div>

<div class="box">
    <div class="heading pure-g"></div>

    <div class="content padding0">
        <div class="box">
            <?php if($user_profiles){ ?> 
            <div class="b-pad"> 
            <table class="table-session"> 
                <thead>
                    <tr>
                        <th style="width: 4%;" class="padding-10-15">NO</th>
                        <th class="padding-10-15 scd" style="width: 30%;">STUDENT'S NAME</th>
                        <th class="padding-10-15">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;foreach ($user_profiles as $user_profile){ ?>
                    <tr>
                        <td class="padding-10-15" data-label="NO"><?php echo($i++); ?></td>
                        <td class="padding-10-15" data-label="STUDENT'S NAME"><?php echo $user_profile->fullname; ?></td>
                        <td class="padding-10-15 t-center"><a href="<?php echo site_url('student_partner/schedule/manage/'.@$user_profile->id );?>" class="pure-button btn-small btn-white">MANAGE</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
            <?php }
             else { ?>
            <div class="no-result padding-10-15">
                No Data
            </div>
            <?php } ?>  
        </div>
    </div>    
</div>

<?php echo @$pagination;?>

<script type="text/javascript">
    $(function(){
        if ($(document).width()<=480){
            $('.scd').css({'width':'100%'});
        }
    })
</script>