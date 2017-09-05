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
            <a href="<?php echo site_url('student/find_coaches/single_date'); ?>" class="active">Date</a>
            <a href="<?php echo site_url('student/find_coaches/search/name'); ?>">Name</a>
            <a href="<?php echo site_url('student/find_coaches/search/country'); ?>">Country</a>
        </div>
        <!-- end block edit -->
    </div>

    <div class="content">
        <div class="box pure-g">
            <div class="tab-session">
                <a href="<?php echo site_url('student/find_coaches/single_date'); ?>" class="active">One Session</a>
                <a href="<?php echo site_url('student/find_coaches/multiple_date'); ?>">Multiple Sessions</a>
            </div>


            <div class="form-search-session" style="width:100%" >
                <p>Insert Single Date</p>
                <?php
                $attribut = array('id' => 'date_value', 'role' => 'form', 'class' => 'pure-g pure-form');
                echo form_open('student/find_coaches/book_by_single_date', $attribut);
                ?>
                <div class="pure-u-1 pure-u-md-4-5 pure-u-sm-4-5 email-form">
                    <?php echo form_input('date', set_value('date'), 'class="datepicker frm-date" id="date"'); ?>
<!--                    <input class="datepicker frm-date" type="text">-->
                </div>
                <div class="pure-u-1 pure-u-md-1-5 pure-u-sm-1-5 text-right">
                    <?php echo form_submit('__submit', @$this->auth_manager->userid() ? 'Search' : 'Search', 'class="pure-button frm-small btn-blue btn-padding-s" style="margin:0"'); ?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>		
</div>
<script type="text/javascript">
    $(function () {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd'
        });
    });
    
    document.getElementById("date").onchange = function() {
        var newurl = "<?php echo site_url('student/find_coaches/book_by_single_date'); ?>"+"/"+this.value;
        $('#date_value').attr('action', newurl);
    };
</script>
