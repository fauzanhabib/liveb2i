<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
<a href="<?php echo site_url('account/identity/'); ?>">Back</a>
<?php echo form_open('coach/schedule/'.$form_action, 'role="form"'); ?>
<div id="main">
    <h3><?php echo('Add or Remove '.ucfirst(strtolower($day)).' Schedule'); ?></h3>
    <div class="my-form">
        
        <form role="form" method="post">
            <?php 
            for($i=0; $i<count(@$schedule); $i++){
            ?>
            <p class="text-box">
                <?php echo('<label for="schedule_'.$i.'">Schedule '.($i+1).'</label>'); ?>
                <?php echo form_input('start_time_'.$i, set_value('start_time_'.$i, @$schedule[$i]['start_time']), "id= start_time_".$i) ?>
                <?php echo form_input('end_time_'.$i, set_value('end_time_'.$i, @$schedule[$i]['end_time']), "id= end_time_".$i) ?>
                <?php 
                    if ($i == 0)
                        echo('<a class="add-field" href="#">Add More</a>');
                    else if($i == 1)
                        echo('<a class="remove_field" href="#">Remove</a>');
                        
                ?>
            </p>
            
            <?php
            }
            ?>
            
            <?php echo form_submit('__submit', @$schedule[0]->day ? 'Update' : 'Update'); ?>
        </form>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
jQuery(document).ready(function($){
    $('.my-form .add-field').click(function(){
        var n = $('.text-box').length + 1;
        if( n > 2 ) {
            alert('Only 2 schedule');
            return false;
        }
        var box_html = $('<p class="text-box"><label for="schedule_' + (n-1) + '">Schedule <span class="box-number">' + n + '</span></label> <input type="text" name="start_time_' + (n-1) + '"value="" id="start_time_' + (n-1) + '" /> <input type="text" name="end_time_' + (n-1) + '"value="" id="end_time_' + (n-1) + '" />  <a href="#" class="remove_field">Remove</a></p>');
        
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

