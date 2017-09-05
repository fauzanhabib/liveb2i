<?php
echo("<br>Find Coaches available at " . date('l jS \of F Y', strtotime(@$this->session->userdata('date_' . $index))) . '<br><br>');
?>

Search By
<?php
echo anchor(base_url() . 'index.php/student/find_coaches/search/name', 'Name | ');
echo anchor(base_url() . 'index.php/student/find_coaches/search/country', 'Country | ');
echo anchor(base_url() . 'index.php/student/find_coaches/single_date', 'Availability');
echo('<br>');

echo (form_radio('availability_type', 'single_date', false)) . ' Single Date ';
echo (form_radio('availability_type', 'multiple_date', true)) . ' Multiple Date';

if ($temporary_booking > 0) {
    echo anchor(base_url() . 'index.php/student/find_coaches/confirm_book_by_multiple_date/', '<br>Temporary Booking ( ' . $temporary_booking . ' )');
}
?>


<?php
echo('<br>');
if (@$this->session->userdata('date_' . ($index - 1)) && ($index) > 1) {
    echo anchor(base_url() . 'index.php/student/find_coaches/book_by_multiple_date_index/' . ($index - 1), 'Back | ');
}

if (@$this->session->userdata('date_' . ($index + 1)) == '' || ($index + 1) > 5) {
    echo anchor(base_url() . 'index.php/student/find_coaches/confirm_book_by_multiple_date/', 'Confirm ');
} else {
    echo anchor(base_url() . 'index.php/student/find_coaches/book_by_multiple_date_index/' . ($index + 1), 'Next ');
}



foreach ($data as $d) {
    echo('<br>' . $id_to_name[$d['coach_id']] . '<br>');
    ?>    
    <table border="1">
        <tr>
            <th> No </th>
            <th> Start Time </th> 
            <th> End Time </th>
            <th> Cost Token </th>
            <th> Action </th>
        </tr>
    <?php $i = 1;foreach ($d['availability'] as $d2) { ?>
            <tr>
                <td> <?php echo $i++;?> </td>
                <td> <?php echo($d2['start_time']); ?> </td>
                <td> <?php echo($d2['end_time']); ?> </td>
                <td> <?php echo($id_to_token_cost[$d['coach_id']]); ?> </td>
                <td> <a href="<?php echo site_url('student/find_coaches/book_multiple_coach/' . $d['coach_id'] . '/' . strtotime(@$this->session->userdata('date_' . $index)) . '/' . $d2['start_time'] . '/' . $d2['end_time'] . '/' . $index); ?>" onclick=" return confirm('Are you sure?');">Temporary Book</a> </td>
            </tr>
        <?php }
    ?>
    </table>

        <?php
    }
    ?>




<script>
    $(function () {
        start_date = new Date();
        start_date.setDate(start_date.getDate() + 2);
        //available = new Date(now.getFullYear(), now.getMonth());
        end_date = new Date();
        end_date.setMonth(end_date.getMonth() + 5);
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: start_date,
            maxDate: end_date,
        });
    });

</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('input:radio[name="availability_type"]').change(function () {
            //alert($(this).val());
            if ($(this).val() == 'single_date' || $(this).val() == 'multiple_date') {
                window.location.href = "<?php echo base_url(); ?>index.php/student/find_coaches/" + $(this).val();
            }
        });


    });

    document.getElementById("date").onchange = function () {
        var newurl = "<?php echo site_url('student/find_coaches/book_by_single_date'); ?>" + "/" + this.value;
        $('#date_value').attr('action', newurl);
    };
</script>
