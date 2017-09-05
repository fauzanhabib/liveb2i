<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Student Progress Report</h1>
</div>
<div class="box">
    <div class="content">
        <?php foreach ($students_identity as $value) { ?>
            <div class="accordion-container" style="margin-bottom:20px;" value="<?php echo $value->user_id;?>">
                <div class="accordion-toggle padding-tb-10" data-id="<?php echo $value->user_id;?>">
                    <?php echo $value->fullname;?>
                    <span class="toggle-icon">
                        <i class="icon icon-arrowdown"></i>
                    </span>
                </div>
                <div class="accordion-content content_vrm_<?php echo $value->user_id;?>" style="width:100%">
                    <div id="result_<?php echo $value->user_id;?>" class="result">
                        <img src='<?php echo base_url(); ?>images/small-loading.gif' alt='loading...' style="display:none;" class="schedule-loading"/>
                    </div>
                </div>


                <!-- <div class="content_vrm_<?php //echo $value->user_id;?>"></div> -->
            </div>
            <!-- <div class="content_vrm_<?php //echo $value->user_id;?>">
                <div id="result_<?php //echo $value->user_id;?>">
                    <img src='<?php //echo base_url(); ?>images/small-loading.gif' alt='loading...' style="display:none;" id="schedule-loading"/>
                </div>
            </div> -->
        <?php } ?>
    </div>
</div>

<script type="text/javascript">

$(function () {

    $('.accordion-content').css({'display':'none','opacity':'1','height':'auto','padding':'20px 0'});
    var imageLoading = '<img src="<?php echo base_url(); ?>images/small-loading.gif" alt="loading..." style="display:none;" class="schedule-loading"/>';
    function close_accordion() {
        $('.accordion-toggle').removeClass('active');
        $('.accordion-content').css({'display':'none'});
        $('.toggle-icon').html('<i class="icon icon-arrowdown"></i>&nbsp;&nbsp;');
        $('.result').removeClass('active').empty().append(imageLoading);
        return false;
    }

    $('.accordion-toggle').on('click', function (event) {
        event.preventDefault();
        var accordion = $(this);
        var accordionContent = accordion.next('.accordion-content');
        var accordionResult = $(this).find('.result')
        var accordionToggleIcon = $(this).find('.toggle-icon');

        if ($(event.target).is('.active')) {
            close_accordion();
            accordionToggleIcon.html('<i class="icon icon-arrowdown"></i>&nbsp;&nbsp;');
            return false;
        }
        else {
            close_accordion();
            accordion.addClass("active");
            accordionContent.css({'display':'block','height':'auto'});
            accordionResult.addClass('active');
            accordionToggleIcon.html('<i class="icon icon-arrowup"></i>&nbsp;&nbsp;');

            var loadUrl = "<?php echo site_url('partner/coach_vrm/student_vrm'); ?>"+ "/" +$(this).data("id");
            if($(this).data("id") != ''){
                $(".schedule-loading").show();
                $("#result_"+$(this).data("id")).load(loadUrl, function () {
                    $(".schedule-loading").hide();
                });
            }
        }

    });

})
</script>