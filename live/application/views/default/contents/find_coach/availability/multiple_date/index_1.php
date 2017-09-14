<?php
    echo("<br>Find Coaches<br><br>");
?>

Search By
<?php
    echo anchor(base_url().'index.php/student/find_coaches/search/name', 'Name | ');
    echo anchor(base_url().'index.php/student/find_coaches/search/country', 'Country | ');
    echo anchor(base_url().'index.php/student/find_coaches/single_date', 'Availability');
    echo('<br>');
    
    echo (form_radio('availability_type', 'single_date', false)).' Single Date ';
    echo (form_radio('availability_type', 'multiple_date', true)).' Multiple Date';
    
    echo('<div class="my-form">');
    
    if($temporary_booking > 0){
        echo anchor(base_url().'index.php/student/find_coaches/confirm_book_by_multiple_date/', 'Temporary Booking ( '.$temporary_booking.' )');
    }
    
    echo form_open('student/find_coaches/book_by_multiple_date');
    echo('<p class="text-box">');
    echo 'Insert Date<br>'.form_input('date1', set_value('date1'), 'class="datepicker" id="date1"');
    echo('<a class="add-field" href="#">Add More</a>');
    echo('</p>');
    echo '<br>'.form_submit('__submit', @$this->auth_manager->userid() ? 'Search' : 'Search');
    echo form_close();
    
    echo('</div>');
?>

<script>
$(document).ready(function(){
    datepicker_add ();
});
    
    function datepicker_add () {
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
    }

</script>

<script type="text/javascript">
$(document).ready(function(){
    $('input:radio[name="availability_type"]').change(function() {
        //alert($(this).val());
        if($(this).val() == 'single_date' || $(this).val() == 'multiple_date'){
            window.location.href="<?php echo base_url(); ?>index.php/student/find_coaches/"+$(this).val();
        }
    });
    
    datepicker ();
});

    function datepicker () {
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
    }
</script>

<script type="text/javascript">
jQuery(document).ready(function($){
    $('.my-form .add-field').click(function(){
        // to keep unique index of date field
        arguments.callee.count = ++arguments.callee.count || 1
        var n = $('.text-box').length + 1;
        if( n > 5 ) {
            alert('Only 5 Date');
            return false;
        }
        var box_html = $('<p class="text-box"> <input type="text" name="date' + (arguments.callee.count+1) + '"value="" id="date' + (arguments.callee.count+1) + ' " class="datepicker" onmousemove= "datepicker()" /> <a href="#" class="remove_field">Remove</a></p>');
        
        box_html.hide();
        $('.my-form p.text-box:last').after(box_html);
        box_html.fadeIn('slow');
        
        
        return false;
    });
    $('.my-form').on('click', '.remove_field', function(){
        $(this).parent().fadeOut("slow", function() {
            $(this).remove();
        });
        return false;
    });
});
</script>