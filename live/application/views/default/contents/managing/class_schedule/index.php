<div class="heading text-cl-primary border-b-1 padding15">

     <!-- <div class="breadcrumb-tabs pure-g">
        <div class="left-breadcrumb">
            <ul class="breadcrumb toolbar padding-l-0">
                <li id="breadcrum-home"><a href="#">
                    <div id="home-icon">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">
                        <g>
                            <path d="M2.7,14.1c0,0,0,0.3,0.3,0.3c0.4,0,3.7,0,3.7,0l0-3c0,0-0.1-0.5,0.4-0.5h1.5c0.6,0,0.5,0.5,0.5,0.5l0,3
                                c0,0,3.1,0,3.6,0c0.4,0,0.4-0.4,0.4-0.4V8.5L8.1,4L2.7,8.5L2.7,14.1z"/>
                            <path d="M0.7,8.1c0,0,0.5,0.8,1.5,0l5.9-5l5.6,5c1.2,0.8,1.6,0,1.6,0L8.1,1.5L0.7,8.1z"/>
                            <polygon points="13.6,3 12.1,3 12.1,4.8 13.6,6  "/>
                        </g>
                        </svg>
                    </div>
                </a></li>
                <li><a href="#">Regions</a></li>
                <li><a href="#">Indonesia</a></li>
                <li><a href="#">Development Solutions</a></li>
                <li><a href="#">Couch Group List</a></li>
                <li>
                    <form action="" autocomplete="on" class="search-box">
                      <input id="search" name="search" type="text" placeholder="Type here.."><input id="search_submit" value="Rechercher" type="submit">
                    </form>
                </li>
            </ul>
        </div>
    </div> -->

    <h1 class="margin0"><?php echo @$class_data->class_name; ?></h1>
</div>

<div class="box clear-both">
    <div class="heading pure-g m-r-10">

    </div>

    <div class="content padding-t-0 clear-both">
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

            <div class="pure-g border-t-1">
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
                    <button class="pure-button btn-small btn-green" onclick="location.href = '<?php echo site_url('student_partner/managing/class_member/' . @$class_data->id); ?>'">VIEW MEMBER</button>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    $('#userTable').DataTable( {
                      "bLengthChange": false,
                      "searching": false,
                      "userTable": false,
                      "bInfo" : false
                    });
                } );
            </script>
            <table id="userTable" class="display table-session" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="bg-secondary text-cl-white border-none">Date</th>
                        <th class="bg-secondary text-cl-white border-none">Time</th>
                        <th class="bg-secondary text-cl-white border-none">Assigned Coach</th>
                        <th class="bg-secondary text-cl-white border-none">Manage</th>               
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < count($week); $i++) {
                        foreach ($week[$i] as $w => $value) {
                            //echo($value);
                            if (count(@$schedule[$w]) > 0) {
                                for ($j = 0; $j < count(@$schedule[$w]); $j++) {
                                    //echo ($schedule[$w][$j]->start_time).'<=>';
                                    //echo ($schedule[$w][$j]->end_time);
                                    ?>
                    <tr data-link="week-<?php echo $i + 1; ?>" class="tr week-<?php echo $i + 1;
                                        echo ($i > 0 ? ' hide' : ''); ?>">
                                        <?php if ($j == 0) { ?>
                        <td class="padding15" rowspan=<?php echo (count(@$schedule[$w])); ?>>
                                                <?php echo $value; ?>
                                            </td>
                                            <?php } 
                                        ?>
                        <td class="padding15" data-label="TIME">
                                            <span class="text-cl-green">
                                                <?php echo(date('H:i', strtotime(@($schedule[$w][$j]->start_time)))); ?> - <?php echo(date('H:i', strtotime(@($schedule[$w][$j]->end_time)))); ?>
                                            </span>
                                        </td>
                        <td class="padding15" data-label="ASSIGNED COACH"><?php echo @$id_to_name[@($schedule[$w][$j]->coach_id)]; ?></td>
                        <td class="padding15">
                                            <div class="rw">
                                                <div class="b-50">
                                                    <a onclick="confirmation('<?php echo site_url('student_partner/managing/set_class_schedule/' . @$class_id . '/' . @$w . '/re' . '/' . $schedule[$w][$j]->id); ?>', 'group', 'Reschedule Session', 'manage-schedule', 'reschedule-session');" class="pure-button button-medium btn-white reschedule-session">
                                                        Reschedule
                                                    </a>
                                                </div>
                                                <div class="b-50">
                                                    <a onclick="confirmation('<?php echo site_url('student_partner/managing/cancel_session/' . @$class_id . '/' . @$schedule[$w][$j]->id ); ?>', 'group', 'Cancel Session', 'manage-schedule', 'cancel-session');" class="pure-button btn-red btn-small cancel-session">
                                                    Cancel
                                                    </a> 
                                                </div>

                                            </div>
                                            
                                            <?php
                                            if ($j == (count(@$schedule[$w])-1)){ ?>
                                             <a class="pure-button button-medium btn-white" href="<?php echo site_url('student_partner/managing/set_class_schedule/' . @$class_id . '/' . @$w . '/set'); ?>">Add More Schedule</a>
                                            <?php
                                            }
                                            ?>
                                        </td>
                    </tr>
                    <?php
                                }
                            } else { 
                                ?>
                                <tr data-link="week-<?php echo $i + 1; ?>" class="tr week-<?php echo $i + 1;
                                    echo ($i > 0 ? ' hide' : ''); ?>">
                                    <td class="padding15 no-rowspan">
                                        <?php echo $value; ?><br>
                                        <div class="lg-none">
                                            <i>No Schedule</i>
                                        </div>
                                    </td>
                                    <td class="padding15 text-center md-none"></td>
                                    <td class="padding15 text-center md-none"></td>
                                    <td class="padding15 md-12">
                                        <a href="<?php echo site_url('student_partner/managing/set_class_schedule/' . @$class_id . '/' . @$w . '/set'); ?>" class="pure-button button-medium btn-white">SET SCHEDULE</a>
                                    </td>
                                </tr>
                                <?php
                            } } }
                    ?>
                </tbody>
            </table>
            
        </div>
    </div>    
</div>

<?php echo @$pagination;?>

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
