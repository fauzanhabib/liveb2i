    <div class="box pure-g clear-both">
        <div class="sort-left pure-u-md-6-24 pure-u-lg-6-24">
            <div class="pure-g border-b-1-fa">
                <h3 class="text-center margin-auto font-semi-bold">Book a Coach</h3>
            </div>

            <div class="content padding-lr-0">
                <div class="box">
                    <div class="box-capsule bg-tertiary padding-tb-8 text-cl-white margin-auto font-14 width190">
                        <span>Sort By</span>
                    </div>
                    <ul class="sort-by padding-l-0">
                        <li class="border-none"><a href="#">Date</a></li>
                        <div class="text-center book-date">
                            <?php
                                 echo form_open('student/find_coaches/book_by_single_date', 'id="date_value" role="form" class="pure-g pure-form" data-parsley-validate');
                            ?>        
                                <div class="">
                                    <div class="frm-date">
                                        <?php echo form_input('date', set_value('date'), 'class="dateavailable datepicker frm-date" id="date"  data-parsley-no-focus required readonly data-parsley-required-message="Please click for date."'); ?>
                                            <ul class="parsley-errors-list" id="parsley-id-8951"></ul>                
                                        <span class="icon dyned-icon-coach-schedules"></span>
                                    </div>
                                </div>  
                                <div class="">
                                    <?php echo form_submit('__submit', @$this->auth_manager->userid() ? 'SEARCH' : 'SEARCH', 'class="pure-button btn-tertiary btn-expand-tiny height-30"'); ?>                    
                                </div>
                            <?php echo form_close(); ?>
                            
                            <!-- <a href="<?php echo site_url('student/find_coaches/multiple_date'); ?>" class="addmultiple text-cl-green font-14"><i class="icon icon-add font-10"></i> Add More Sessions</a> -->
                        </div>
                            <li><a href="<?php echo site_url('student/find_coaches/search/name'); ?>">Name</a></li>
                            <li><a href="<?php echo site_url('student/find_coaches/search/country'); ?>">Country</a></li>
                            <li><a href="<?php echo site_url('student/find_coaches/search/spoken_language'); ?>">Languages Spoken</a></li>
                    </ul>
                </div>
            </div>  
        </div>
     
    </div>

</div>
<script type="text/javascript">
    $(function () {
        var now = new Date();
        var day = ("0" + (now.getDate() + 1)).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);
        $('.datepicker').datepicker({
            startDate: resultDate,
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
        $('.dateavailable').change(function(){
            $('.dateavailable').parsley().reset();
        });
    });
    
    document.getElementById("date").onchange = function() {
        var newurl = "<?php echo site_url('student/find_coaches/book_by_single_date'); ?>"+"/"+this.value;
        $('#date_value').attr('action', newurl);
    };

</script>

