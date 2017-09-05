<div class="heading text-cl-primary padding15">
    <h1 class="margin0"><?php echo @$class_data->class_name; ?></h1>
</div>

<div class="box">
    <div class="content">
        <div class="box">

            <table class="no-border padding15 tbl-schedule">
                <tbody>
                    <tr>
                        <td>Max Student</td>
                        <td>:</td>
                        <td><?php echo @$class_data->student_amount; ?></td>
                    </tr>
                    <tr>
                        <td>Start date</td>
                        <td>:</td>
                        <td><?php echo date('M d, Y', strtotime(@$class_data->start_date)); ?></td>
                    </tr>
                    <tr>
                        <td>End date</td>
                        <td>:</td>
                        <td><?php echo date('M d, Y', strtotime(@$class_data->end_date)); ?></td>
                    </tr>
                </tbody>
            </table>
            
            <div class="pure-g" style="border-top: 2px solid #f3f3f3;">
                <div class="pure-u-12-24 pure-form left m-tb-15">

                    <select id="week" style="widht:100px;">
                        <?php
                        for ($i = 0; $i < count($week); $i++) {
                            ?>
                            <option data-selected="week-<?= $i + 1 ?>">WEEK <?= $i + 1 ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="pure-u-12-24 edit m-tb-15">
                    <button class="pure-button btn-small btn-primary" onclick="location.href = '<?php echo site_url('student_partner/managing/class_member/' . @$class_data->id); ?>'">VIEW MEMBER</button>
                </div>
            </div>

            <table class="table-sessions" style="border-top:2px solid #f3f3f3;border-bottom:0">
                <thead>
                    <tr>
                        <th class="padding15 md-12">DATE</th>
                        <th class="padding15 text-center md-none">START TIME</th>
                        <th class="padding15 text-center md-none">END TIME</th>
                        <th class="padding15 md-none">ASSIGNED COACH</th>
                        <th class="padding15">MANAGE</th>
                    </tr>
                </thead>
                <tbody class="manage-schedule">
                    <?php
                    for ($i = 0; $i < count($week); $i++) {
                        foreach ($week[$i] as $w => $value) {
                            //echo($value);
                            if (count(@$schedule[$w]) > 0) {
                            	for ($j = 0; $j < count(@$schedule[$w]); $j++) {
                                    //echo ($schedule[$w][$j]->start_time).'<=>';
                                    //echo ($schedule[$w][$j]->end_time);
                                    ?>
                                    <tr data-link="week-<?php echo $i + 1; ?>" class="week-<?php echo $i + 1;
                                        echo ($i > 0 ? ' hide' : ''); ?>">
                                        <?php if ($j == 0) { ?>
                                            <td class="padding15 md-12" rowspan=<?php echo (count(@$schedule[$w])); ?>>
                                                <?php echo $value; ?><br>
                                                <div class="lg-none">
                                                    <span class="text-cl-green">
                                                        <?php echo(date('H:i', strtotime(@($schedule[$w][$j]->start_time)))); ?> - 
                                                        <?php echo(date('H:i', strtotime(@($schedule[$w][$j]->end_time)))); ?>
                                                    </span><br>
                                                    Coach : <span class="text-cl-primary"><?php echo @$id_to_name[@($schedule[$w][$j]->coach_id)]; ?></span>
                                                </div>
                                            </td>
                                            <?php } 
                                        ?>
                                        <td class="padding15 text-center md-none text-cl-green"><?php echo(date('H:i', strtotime(@($schedule[$w][$j]->start_time)))); ?></td>
                                        <td class="padding15 text-center md-none text-cl-green"><?php echo(date('H:i', strtotime(@($schedule[$w][$j]->end_time)))); ?></td>
                                        <td class="padding15 md-none"><?php echo @$id_to_name[@($schedule[$w][$j]->coach_id)]; ?></td>
                                        <td class="padding15 schedule md-12">
                                            <a class="padding-r-5 text-cl-secondary reschedule-session">
                                                <span onclick="confirmation('<?php echo site_url('student_partner/managing/set_class_schedule/' . @$class_id . '/' . @$w . '/re' . '/' . $schedule[$w][$j]->id); ?>', 'group', 'Reschedule Session', 'manage-schedule', 'reschedule-session');">Reschedule</span>
                                            </a>
                                            
                                            <a class="padding-r-5 text-cl-primary cancel-session">
                                                <span onclick="confirmation('<?php echo site_url('student_partner/managing/cancel_session/' . @$class_id . '/' . @$schedule[$w][$j]->id ); ?>', 'group', 'Cancel Session', 'manage-schedule', 'cancel-session');">Cancel</span>
                                            </a> 
                                            
                                            <?php
                                            if ($j == (count(@$schedule[$w])-1)){ ?>
                                             <a class="padding-r-5 addmore text-cl-green" href="<?php echo site_url('student_partner/managing/set_class_schedule/' . @$class_id . '/' . @$w . '/set'); ?>">Add More Schedule</a>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else { 
                                ?>
                                <tr data-link="week-<?php echo $i + 1; ?>" class="week-<?php echo $i + 1;
                                    echo ($i > 0 ? ' hide' : ''); ?>">
                                    <td class="padding15 md-12">
                                        <?php echo $value; ?><br>
                                        <div class="lg-none">
                                            <i>No Schedule</i>
                                        </div>
                                    </td>
                                    <td class="padding15 text-center md-none"></td>
                                    <td class="padding15 text-center md-none"></td>
                                    <td class="padding15 md-none"></td>
                                    <td class="padding15 schedule md-12"><a href="<?php echo site_url('student_partner/managing/set_class_schedule/' . @$class_id . '/' . @$w . '/set'); ?>">SET SCHEDULE</a></td>
                                </tr>
                                <?php
                            } 
                        }
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>		
</div>

<script type="text/javascript">
    $('.cancel-session').click(function(){
        return false;
    });

    $('.reschedule-session').click(function(){
        return false;
    });
    
    $(function () {
        $('.table-sessions tr').css({'border-bottom': '1px solid #f3f3f3'});
        $('.table-sessions td').css({'vertical-align':'top','border-bottom': '1px solid #f3f3f3'});
        $('.addmore').css({'font-size':'11px','margin-top':'10px','display':'block'});

        $("#week").change(function () {

            var selected = $(this).find(":selected").attr("data-selected");
            if (selected) {
                $("[data-link]").addClass("hide");
                $("[data-link=" + selected + "]").removeClass("hide");
                animationClick('[data-link]', 'fadeIn');
            }
        });
    })
</script>
