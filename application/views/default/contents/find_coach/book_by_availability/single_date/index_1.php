<?php
    echo("<br>Find Coaches available at ".date('l jS \of F Y', strtotime(@$date)).'<br><br>');
?>

Search By
<?php
    echo anchor(base_url().'index.php/student/find_coaches/search/name', 'Name | ');
    echo anchor(base_url().'index.php/student/find_coaches/search/country', 'Country | ');
    echo anchor(base_url().'index.php/student/find_coaches/single_date', 'Availability');
    echo('<br>');
    
    echo (form_radio('availability_type', 'single_date', true)).' Single Date ';
    echo (form_radio('availability_type', 'multiple_date', false)).' Multiple Date';
    
    $attribut = array('id' => 'date_value', 'role' => 'form');
    echo form_open('student/find_coaches/book_by_single_date/'.@$date, $attribut);
    echo '<br>'.form_input('date', @$date, 'class="datepicker" id="date"');
    echo '<br>'.form_submit('__submit', @$this->auth_manager->userid() ? 'Search' : 'Search');
    echo form_close();
    
    
?>


<?php

foreach($data as $d){
    echo('<br>'.$id_to_name[$d['coach_id']].'<br>');
?>    
<table border="1">
    <tr>
    <th> Start Time </th>
    <th> End Time </th>
    <th> Action </th>
    </tr>
    <?php foreach($d['availability'] as $d2){ ?>
        <tr>
            <td> <?php echo($d2['start_time']);?> </td>
            <td> <?php echo($d2['end_time']);?> </td>
            <td> <a href="<?php echo site_url('student/find_coaches/book_single_coach/'.$d['coach_id'].'/'.strtotime(@$date).'/'.$d2['start_time'].'/'.$d2['end_time']); ?>" onclick=" return confirm('Are you sure?');">Book</a> </td>
        </tr>
    <?php
    }?>
</table>
 
<?php
}
?>




<script>
    $(function () {
        start_date = new Date();
        start_date.setDate(start_date.getDate()+2);
        //available = new Date(now.getFullYear(), now.getMonth());
        end_date = new Date();
        end_date.setMonth(end_date.getMonth()+5);
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: start_date,
            maxDate: end_date, 
        });
    });

</script>

<script type="text/javascript">
$(document).ready(function(){
    $('input:radio[name="availability_type"]').change(function() {
        //alert($(this).val());
        if($(this).val() == 'single_date' || $(this).val() == 'multiple_date'){
            window.location.href="<?php echo base_url(); ?>index.php/student/find_coaches/"+$(this).val();
        }
    });
    
    
});

document.getElementById("date").onchange = function() {
        var newurl = "<?php echo site_url('student/find_coaches/book_by_single_date'); ?>"+"/"+this.value;
        $('#date_value').attr('action', newurl);
    };
</script>
