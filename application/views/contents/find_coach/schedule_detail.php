<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
    echo("<br>Schedule<br><br>");
    
    echo form_dropdown('date', @$option_date, @$selected, 'id = "date"');
?>

<table border="1">
    <tr>
        <th>Day</th>
        <th>Start Time</th>
        <th>End Time</th>
    </tr>
    
        <tr>
            <td rowspan=<?php echo (count(@$schedule['monday'])+1)?>>Monday</td>
        </tr>
        <?php 
            for($i=0; $i<count(@$schedule['monday']); $i++){
        ?>
        <tr>
            <td><?php echo($schedule['monday'][$i]['start_time']); ?></td>
            <td><?php echo($schedule['monday'][$i]['end_time']); ?></td>
        </tr>
        <?php
            }
        ?>
        
        <tr>
            <td rowspan=<?php echo (count(@$schedule['tuesday'])+1)?>>Tuesday</td>
        </tr>
        <?php 
            for($i=0; $i<count(@$schedule['tuesday']); $i++){
        ?>
        <tr>
            <td><?php echo($schedule['tuesday'][$i]['start_time']); ?></td>
            <td><?php echo($schedule['tuesday'][$i]['end_time']); ?></td>
        </tr>
        <?php
            }
        ?>
        
        <tr>
            <td rowspan=<?php echo (count(@$schedule['wednesday'])+1)?>>Wednesday</td>
        </tr>
        <?php 
            for($i=0; $i<count(@$schedule['wednesday']); $i++){
        ?>
        <tr>
            <td><?php echo($schedule['wednesday'][$i]['start_time']); ?></td>
            <td><?php echo($schedule['wednesday'][$i]['end_time']); ?></td>
        </tr>
        <?php
            }
        ?>
        
        <tr>
            <td rowspan=<?php echo (count(@$schedule['thursday'])+1)?>>Thursday</td>
        </tr>
        <?php 
            for($i=0; $i<count(@$schedule['thursday']); $i++){
        ?>
        <tr>
            <td><?php echo($schedule['thursday'][$i]['start_time']); ?></td>
            <td><?php echo($schedule['thursday'][$i]['end_time']); ?></td>
        </tr>
        <?php
            }
        ?>
        
        <tr>
            <td rowspan=<?php echo (count(@$schedule['friday'])+1)?>>Friday</td>
        </tr>
        <?php 
            for($i=0; $i<count(@$schedule['friday']); $i++){
        ?>
        <tr>
            <td><?php echo($schedule['friday'][$i]['start_time']); ?></td>
            <td><?php echo($schedule['friday'][$i]['end_time']); ?></td>
        </tr>
        <?php
            }
        ?>
        
        <tr>
            <td rowspan=<?php echo (count(@$schedule['saturday'])+1)?>>Saturday</td>
        </tr>
        <?php 
            for($i=0; $i<count(@$schedule['saturday']); $i++){
        ?>
        <tr>
            <td><?php echo($schedule['saturday'][$i]['start_time']); ?></td>
            <td><?php echo($schedule['saturday'][$i]['end_time']); ?></td>
        </tr>
        <?php
            }
        ?>
        
        <tr>
            <td rowspan=<?php echo (count(@$schedule['sunday'])+1)?>>Sunday</td>
        </tr>
        <?php 
            for($i=0; $i<count(@$schedule['sunday']); $i++){
        ?>
        <tr>
            <td><?php echo($schedule['sunday'][$i]['start_time']); ?></td>
            <td><?php echo($schedule['sunday'][$i]['end_time']); ?></td>
        </tr>
        <?php
            }
        ?>
        
</table>

<script type="text/javascript">
    document.getElementById("date").onchange = function() {
            if(this.value == 0){
                window.location.href="<?php echo base_url(); ?>index.php/student/find_coaches";
            }
            else{
                window.location.href="<?php echo base_url(); ?>index.php/student/find_coaches/availability/<?php echo ($coach_id.'/')?>" + this.value;
            }
    };
</script>