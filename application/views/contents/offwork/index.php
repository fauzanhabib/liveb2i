<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<div id="main">
    <h3>Add or Remove Schedule</h3>
    <div class="my-form">
        <a class="add-field" href="#">Add More</a>
        <form role="form" method="post">
            <p class="text-box">
                <label for="schedule_1">Schedule 1</label>
                <?php echo form_input('start_time_1', set_value('start_time_1', @$data->start_time), 'id="start_time_1"') ?>
                <?php echo form_input('end_time_1', set_value('end_time_1', @$data->end_time), 'id="end_time_1"') ?>
                <a class="remove_field" href="#">Remove</a>
            </p>
            
            <input type="submit" value="Submit" />
        </form>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($){
    $('.my-form .add-field').click(function(){
        var n = $('.text-box').length + 1;
        if( 3 < n ) {
            alert('Only 3 schedule');
            return false;
        }
        var box_html = $('<p class="text-box"><label for="schedule_' + n + '">Schedule <span class="box-number">' + n + '</span></label> <input type="text" name="start_time_' + n + '"value="" id="start_time_' + n + '" /> <input type="text" name="end_time_' + n + '"value="" id="end_time_' + n + '" />  <a href="#" class="remove_field">Remove</a></p>');
        
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