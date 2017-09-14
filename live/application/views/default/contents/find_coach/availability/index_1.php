<?php
    echo("<br>Find Coaches<br><br>");
?>

Search By
<?php
    echo anchor(base_url().'index.php/student/find_coaches/search/name', 'Name | ');
    echo anchor(base_url().'index.php/student/find_coaches/search/country', 'Country | ');
    echo anchor(base_url().'index.php/student/find_coaches/search/availability', 'Availability');
    echo('<br>');
    
    echo (form_radio('availability_type', 'single_date', true)).' Single Date ';
    echo (form_radio('availability_type', 'multiple_date', false)).' Multiple Date';
    echo '<br>'.form_input('date', set_value('date'), 'class="datepicker" id="date"');
?>

<script>
    $(function () {
        start_date = new Date();
        start_date.setDate(start_date.getDate()+2);
        //available = new Date(now.getFullYear(), now.getMonth());
        end_date = new Date();
        end_date.setMonth(end_date.getMonth()+5);
        $(".datepicker").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: start_date,
            maxDate: end_date, 
        });
    });

</script>

<script type="text/javascript">
//    document.getElementsByName("availability_type").onclick = function() {
//        alert(this.value);
//    };
$(document).ready(function(){
    $('input:radio[name="availability_type"]').change(function() {
        //alert($(this).val());
        if($(this).val() == 'single_date' || $(this).val() == 'multiple_date'){
            window.location.href="<?php echo base_url(); ?>index.php/student/find_coaches/single_date"+$(this).val();
        }
    });
});
</script>
