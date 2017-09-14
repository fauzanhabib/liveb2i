<div class="heading text-cl-primary padding15">
	<h1 class="margin0">Coach Schedule</h1>
</div>

<div class="box">
	<div class="heading pure-g">
		<!-- block edit -->
		<div class="pure-u-1 tab-list">
			<a href="<?php echo site_url('coach/schedule');?>" class="active">My Schedule</a>
                        <a href="<?php echo site_url('coach/day_off');?>" >Manage Day Off</a>
		</div>	
		<!-- end block edit -->
	</div>

	<div class="content tab-content" style="margin-top: -18px">

		
		<div class="data-dayoff">
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
		</div>
                <?php
                
                ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
            
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });

		$('#form-add').hide();
		$('#form-edit').hide();
		$('.cancle-manage').hide();
		//$('.data-dayoff').hide();


		$('.add-manage').click(function(){
			$('.no-result').hide();
			$('.add-manage').hide();
			$('.cancle-manage').show();
			$('#form-add').show();
			$('.data-dayoff').hide();
		});

		$('.cancle-manage').click(function(){
			$('.add-manage').show();
			$('.no-result').show();
			$('.cancle-manage').hide();
			$('#form-add').hide();
			$('#form-edit').hide();
			$('.data-dayoff').show();
		});

//		$('.edit-manage').click(function(){
//			
//			$('#form-edit').show();
//			$('.cancle-manage').show();
//			$('.data-dayoff').hide();
//			$('.no-result').hide();
//			$('.add-manage').hide();
//		});

//		$('.delete-day-off').click(function() {
//			alertify.set('confirm','closable', false);
//			alertify.set('notifier','position', 'top-right');
//
//			alertify.dialog('confirm')
//			  .set({
//			  	'title':'Delete Day Off',
//			    'labels':{ok:'OK', cancel:'NO'},
//			    'position':'top-right',
//			    'message': 'Are you sure want to delete day off?' ,
//			    'onok': function(){ alertify.success('ok!'); },
//
//			  }).show();
//		});
                
                
$('.list-dayoff').each(function(){
	var dropdown = $(this);
	$('.delete-day-off', dropdown).click(function(event) {
		event.preventDefault();
	   var href = this.href;
	   alertify.confirm("Are you sure?", function (e) {
	       if (e) {
	           window.location.href = href;
	       }
	   });
	});
});	

	})
</script>





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

