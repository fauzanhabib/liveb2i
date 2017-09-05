<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php
/**
 * Link
 * @var array
 */
echo("<br>Managing " .@$title. " Class Schedule<br><br>");
?>

<?php
    for($i=0; $i<count($week); $i++){
        echo('Week ' .($i+1). '<br>');
?>
    <table border="1">
        <tr>
            <th>Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Assigned Coach</th>
            <th>Manage</th>
        </tr>
        <?php
            $rowspan = 1;
            foreach($week[$i] as $w => $value){
        ?>
        
            <tr>
                <?php
                    if(@$schedule[$w]==0){
                        $rowspan = 2;
                    }
                    else if(@$schedule[$w]){
                        $rowspan = 1;
                    }
                ?>
                <td rowspan=<?php echo (count(@$schedule[$w])+$rowspan);?>><?php echo $value; ?> </td>
            </tr>
            <?php
            if(count(@$schedule[$w])>0){
            for($j=0; $j<count(@$schedule[$w]); $j++){
            ?>
            <tr>
                <td><?php echo @($schedule[$w][$j]->start_time); ?> </td>
                <td><?php echo @($schedule[$w][$j]->end_time); ?> </td>
                <td><?php echo @$id_to_name[@($schedule[$w][$j]->coach_id)]; ?> </td>
                <?php if($j==0){?>
                <td rowspan=<?php echo (count(@$schedule[$w]))?>> <a href="<?php echo site_url('student_partner/managing/set_class_schedule/' .@$class_id. '/' .@$w); ?>" onclick=" return confirm('Set Class Schedule');"> Set Class Schedule </a>  </td>
                <?php }?>
            </tr>
            <?php
            }
            }
            else{
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td> <a href="<?php echo site_url('student_partner/managing/set_class_schedule/' .@$class_id. '/' .@$w); ?>" onclick=" return confirm('Set Class Schedule');"> Set Class Schedule </a> </td>
            </tr>
            
        <?php } 
            }?>
    </table>
<?php } ?>
