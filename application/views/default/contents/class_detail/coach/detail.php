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
                
            </div>

            <table class="table-session" style="border-top:2px solid #f3f3f3;border-bottom:0">
                <thead>
                    <tr>
                        <th class="padding15 md-12">DATE</th>
                        <th class="padding15 md-12 text-center">START TIME</th>
                        <th class="padding15 md-12 text-center">END TIME</th>
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
    $(function () {

        $('.table-session tr').css({'border-bottom': '1px solid #f3f3f3'});
        $('.table-session td').css({'vertical-align':'top'});
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

<div class="box">

    <div class="heading">
        <div class="pure-u-12-24">
            <h3 class="h3 font-normal padding15 text-cl-secondary">CLASSMATES</h3>
        </div>
        <div class="pure-u-12-24 edit" style="margin: 15px -10px;">
            <a href="<?php echo site_url('coach/class_detail/member/'.$class_id);?>" class="pure-button btn-small btn-primary availability">VIEW ALL</a>
        </div>
    </div>

    <div class="content">
        <div class="pure-g list-partner-member">

            <?php 
            foreach(@$class_member as $m){ ?>
                <div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">
                    <div class="box-info">
                        <div class="image">
                            <img src="<?php echo base_url().$m->profile_picture; ?>" class="list-cover">
                        </div>
                        <div class="detail">
                            <span class="name"><?php echo $m->fullname; ?></span>

                            <table class="margint25">
                                <tbody>
                                    <tr>
                                        <td>Birthday</td>
                                        <td>:</td>
                                        <td><?php echo $m->date_of_birth; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>:</td>
                                        <td><?php echo $m->phone; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Gender</td>
                                        <td>:</td>
                                        <td><?php echo $m->gender; ?></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>    
    </div>

</div>