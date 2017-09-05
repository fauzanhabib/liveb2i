<table class="table-reschedule">
    <thead>
    <tr>
        <th>Day</th>
        <th>Start Time</th>
        <th>End Time</th>
    </tr>
    </thead>
    <tbody>
        <?php 
            for($i=0; $i<count(@$schedule['monday']); $i++){
                $get_endtime = date('H:i',strtotime(@$schedule['monday'][$i]['end_time']));
                $time = strtotime($get_endtime);
                $time = $time - (5 * 60);
                $endtime = date("H:i", $time);
        ?>
        <tr>
            <?php
            if($i == 0){ 
                ?>
            <td rowspan=<?php echo (count(@$schedule['monday']))?>>Monday</td>
            <?php
            }
            ?>

            <?php
            if((date('H:i',strtotime(@$schedule['monday'][$i]['start_time']))) == '00:00' && ($endtime == '23:55')){ ?>
                <td class="text-left">Not Available</td>
                <td class="text-left">Not Available</td>
            <?php } else {
            ?>

                <td><?php echo(date('H:i',strtotime(@$schedule['monday'][$i]['start_time']))); ?></td>
                <td class="text-left"><?php echo $endtime; ?></td>

            <?php } ?>
        </tr>
        <?php
            }
        ?>
        <?php 
            for($i=0; $i<count(@$schedule['tuesday']); $i++){
                $get_endtime = date('H:i',strtotime(@$schedule['tuesday'][$i]['end_time']));
                $time = strtotime($get_endtime);
                $time = $time - (5 * 60);
                $endtime = date("H:i", $time);
        ?>
        <tr>
            <?php
            if($i == 0){ ?>
            <td rowspan=<?php echo (count(@$schedule['tuesday']))?>>Tuesday</td>
            <?php
            }
            ?>

            <?php
            if((date('H:i',strtotime(@$schedule['tuesday'][$i]['start_time']))) == '00:00' && ($endtime == '23:55')){ ?>
                <td class="text-left">Not Available</td>
                <td class="text-left">Not Available</td>
            <?php } else {
            ?>
            <td><?php echo(date('H:i',strtotime(@$schedule['tuesday'][$i]['start_time']))); ?></td>
            <td class="text-left"><?php echo $endtime; ?></td>
            <?php } ?>
        </tr>
        <?php
            }
        ?>
        <?php 
            for($i=0; $i<count(@$schedule['wednesday']); $i++){
                $get_endtime = date('H:i',strtotime(@$schedule['wednesday'][$i]['end_time']));
                $time = strtotime($get_endtime);
                $time = $time - (5 * 60);
                $endtime = date("H:i", $time);
        ?>
        <tr>
            <?php
            if($i == 0){ ?>
            <td rowspan=<?php echo (count(@$schedule['wednesday']))?>>Wednesday</td>
            <?php
            }
            ?>

            <?php
            if((date('H:i',strtotime(@$schedule['wednesday'][$i]['start_time']))) == '00:00' && ($endtime == '23:55')){ ?>
                <td class="text-left">Not Available</td>
                <td class="text-left">Not Available</td>
            <?php } else {
            ?>
            <td><?php echo(date('H:i',strtotime(@$schedule['wednesday'][$i]['start_time']))); ?></td>
            <td class="text-left"><?php echo $endtime; ?></td>
            <?php } ?>
        </tr>
        <?php
            }
        ?>
        <?php 
            for($i=0; $i<count(@$schedule['thursday']); $i++){
                $get_endtime = date('H:i',strtotime(@$schedule['thursday'][$i]['end_time']));
                $time = strtotime($get_endtime);
                $time = $time - (5 * 60);
                $endtime = date("H:i", $time);
        ?>
        <tr>
            <?php
            if($i == 0){ ?>
            <td rowspan=<?php echo (count(@$schedule['thursday']))?>>Thursday</td>
            <?php
            }
            ?>

            <?php
            if((date('H:i',strtotime(@$schedule['thursday'][$i]['start_time']))) == '00:00' && ($endtime == '23:55')){ ?>
                <td class="text-left">Not Available</td>
                <td class="text-left">Not Available</td>
            <?php } else {
            ?>

            <td><?php echo(date('H:i',strtotime(@$schedule['thursday'][$i]['start_time']))); ?></td>
            <td class="text-left"><?php echo $endtime; ?></td>
            <?php } ?>
        </tr>
        <?php
            }
        ?>
        <?php 
            for($i=0; $i<count(@$schedule['friday']); $i++){
                $get_endtime = date('H:i',strtotime(@$schedule['friday'][$i]['end_time']));
                $time = strtotime($get_endtime);
                $time = $time - (5 * 60);
                $endtime = date("H:i", $time);
        ?>
        <tr>
            <?php
            if($i == 0){ ?>
            <td rowspan=<?php echo (count(@$schedule['friday']))?>>Friday</td>
            <?php
            }
            ?>

            <?php
            if((date('H:i',strtotime(@$schedule['friday'][$i]['start_time']))) == '00:00' && ($endtime == '23:55')){ ?>
                <td class="text-left">Not Available</td>
                <td class="text-left">Not Available</td>
            <?php } else {
            ?>
            <td><?php echo(date('H:i',strtotime(@$schedule['friday'][$i]['start_time']))); ?></td>
            <td class="text-left"><?php echo $endtime; ?></td>

            <?php } ?>
        </tr>
        <?php
            }
        ?>
        <?php 
            for($i=0; $i<count(@$schedule['saturday']); $i++){
                $get_endtime = date('H:i',strtotime(@$schedule['saturday'][$i]['end_time']));
                $time = strtotime($get_endtime);
                $time = $time - (5 * 60);
                $endtime = date("H:i", $time);
        ?>
        <tr>
            <?php
            if($i == 0){ ?>
            <td rowspan=<?php echo (count(@$schedule['saturday']))?>>Saturday</td>
            <?php
            }
            ?>

            <?php
            if((date('H:i',strtotime(@$schedule['saturday'][$i]['start_time']))) == '00:00' && ($endtime == '23:55')){ ?>
                <td class="text-left">Not Available</td>
                <td class="text-left">Not Available</td>
            <?php } else {
            ?>

            <td><?php echo(date('H:i',strtotime(@$schedule['saturday'][$i]['start_time']))); ?></td>
            <td class="text-left"><?php echo $endtime; ?></td>

            <?php } ?>
        </tr>
        <?php
            }
        ?>
        <?php 
            for($i=0; $i<count(@$schedule['sunday']); $i++){
                $get_endtime = date('H:i',strtotime(@$schedule['sunday'][$i]['end_time']));
                $time = strtotime($get_endtime);
                $time = $time - (5 * 60);
                $endtime = date("H:i", $time);
        ?>
        <tr>
            <?php
            if($i == 0){ ?>
            <td rowspan=<?php echo (count(@$schedule['sunday']))?>>Sunday</td>
            <?php
            }
            ?>

            <?php
            if((date('H:i',strtotime(@$schedule['sunday'][$i]['start_time']))) == '00:00' && ($endtime == '23:55')){ ?>
                <td class="text-left">Not Available</td>
                <td class="text-left">Not Available</td>
            <?php } else {
            ?>

            <td><?php echo(date('H:i',strtotime(@$schedule['sunday'][$i]['start_time']))); ?></td>
            <td class="text-left"><?php echo $endtime; ?></td>

            <?php } ?>
        </tr>
        <?php
            }
        ?>
     </tbody>   
</table>
<?php exit;?>