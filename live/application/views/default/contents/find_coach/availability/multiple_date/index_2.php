<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Book a Coach</small></h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-12-24">
            <h3 class="h3 font-normal padding15 text-cl-secondary">PICK</h3>
        </div>
        <!-- block edit -->
        <div class="pure-u-12-24 edit">
            <a href="<?php echo site_url('student/find_coaches/search/availability'); ?>" class="active">Date</a>
            <a href="<?php echo site_url('student/find_coaches/search/name'); ?>">Name</a>
            <a href="<?php echo site_url('student/find_coaches/search/country'); ?>">Country</a>
        </div>
        <!-- end block edit -->
    </div>

    <div class="content">
        <div class="box">
            <div class="pure-g">
                <div class="tab-session">
                    <a href="<?php echo site_url('student/find_coaches/single_date'); ?>">One Session</a>
                    <a href="<?php echo site_url('student/find_coaches/multiple_date'); ?>" class="active">Multiple Sessions</a>
                </div>
                <div class="my-form">
                    <form id="multipleform" class="pure-g pure-form multiple-session" method="post" action="book_by_multiple_date">
                        <div class="text-box">
                        <div class="form-search-session" style="width:100%">
                            <p>Select Date</p>
                            <input class="datepicker frm-date" type="text" id="date1" name="date1" readonly>
                        </div>
                        </div>
                        <div class="pure-g">
                            <div class="pure-u-1 pure-u-md-3-5 pure-u-sm-3-5 email-form">
                            </div>
                            <div class="pure-u-1 pure-u-md-2-5 pure-u-sm-2-5 text-right">
                                <a class="add-field pure-button btn-small btn-white btn-expand addmultiple" href="#">Add More</a>
                            </div>
                        </div>
                        
                        <div class="pure-g button">
                            <button type="submit" class="pure-button btn-small btn-white btn-expand addmultiple" name="__submit">SEARCH</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>		
</div>
<script type="text/javascript">
    $(function () {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: new Date()
        });
    });
    
    function datepicker_select () {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: new Date()
        });
    }
</script>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: new Date()
        });
        
        $('.my-form .add-field').click(function () {
            // to keep unique index of date field
            arguments.callee.count = ++arguments.callee.count || 1
            var n = $('.text-box').length + 1;
            if (n > 5) {
                alert('Only 5 Date');
                return false;
            }
            //var box_html = $('<div class="text-box"> <input type="text" name="date' + (arguments.callee.count + 1) + '"value="" id="date' + (arguments.callee.count + 1) + ' " class="datepicker" onmousemove= "datepicker()" /> <a href="#" class="remove_field">Remove</a></div>');
            var box_html = $('<div class="text-box"><div class="form-search-session" style="width:100%"><p>Select Date</p><input class="datepicker frm-date" type="text" id="date' +(arguments.callee.count+1)+ '" name="date' +(arguments.callee.count+1)+ '"onmousemove= "datepicker_select()" readonly><a href="#" class="remove_field">Remove</a></div></div>');
            box_html.hide();
            $('.my-form .text-box:last').after(box_html);
            box_html.fadeIn('slow');


            return false;
        });
        $('.my-form').on('click', '.remove_field', function () {
            $(this).parent().parent().fadeOut("slow", function () {
                $(this).remove();
            });
            return false;
        });
    });
</script>