    <div class="box pure-g clear-both">
        <div class="sort-left pure-u-md-6-24 pure-u-lg-6-24">
            <div class="pure-g border-b-1-fa">
                <h3 class="text-center margin-auto font-semi-bold">Book a Coach</h3>
            </div>

            <div class="content padding-lr-0">
                <!-- search icon on country dropdown -->
                <style>
                    .search-b-2:before {
                        content: " ";
                    }
                </style>
                <!-- end search icon on country dropdown -->

                <!-- rendy book a coach baru -->
                <div class="box">
                    <?php
                            echo form_open('student/find_coaches/book_by_single_date', 'id="date_value" role="form" class="pure-g pure-form"');        
                        ?>
                    <div class="width100perc" style="padding: 0 15px;">
                        <div class='border-2-primary border-rounded-5'>
                            <span class='custom-dropdown'>
                                <select class="width100perc" name="selector" id="selector">
                                    <option disabled selected>Booking Type</option>
                                    <option value="single-book">Single Book</option>
                                    <option value="multiple-book">Recurring Book</option>
                                </select>
                            </span>
                        </div>
                    </div>
                    <div class="width100perc" id="multi-book2" style="padding: 10px 15px 0;">
                        <div class='border-2-primary border-rounded-5'>
                            <span class='custom-dropdown'>
                                <select name="type_booking" style="width:100%;">
                                    <option value="2" selected>2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                            </span>
                        </div>
                    </div>
                    <ul class="sort-by padding-l-0 width100perc">
                        <div class="text-right book-date" style="padding: 1px 15px;">
                            <div class="width100perc">
                                <div class="frm-date">
                                    <input type="text" name="date" value="" class="dateavailable datepicker frm-date width100perc border-2-primary border-rounded-5 text-left" id="date" data-parsley-no-focus="" required="" readonly="" data-parsley-id="8951" placeholder="Date" data-parsley-required-message="Please click for date.">
                                </div>
                            </div>
                        </div>
                        <div class="" style="padding: 5px 15px;">

                            <?php echo form_submit('__submit', @$this->auth_manager->userid() ? 'SEARCH' : 'SEARCH', 'class="pure-button btn-primary border-rounded-5 width100perc"'); ?>
                        </div>
                        <li class="text-center" style="padding: 5px 15px;">
                            <a href="<?php echo site_url('student/find_coaches/search/name'); ?>">
                                NAME
                            </a>
                        </li>
                        <li class="text-center" style="padding: 5px 15px;">
                            <a href="<?php echo site_url('student/find_coaches/search/country'); ?>">
                                COUNTRY
                            </a>
                        </li>
                        <li class="text-center" style="padding: 5px 15px;">
                            <a href="<?php echo site_url('student/find_coaches/search/spoken_language'); ?>">
                                LANGUAGE SPOKEN
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- end rendy book a coach baru -->
            </div>  
        </div>
     
    </div>

</div>

<script>
            $(function(){
                $('#multi-book2').hide(); 
                $('#selector').change(function(){
                    if($('#selector').val() == 'multiple-book') {
                        $('#multi-book2').show(); 
                    } else {
                        $('#multi-book2').hide(); 
                    } 
                });
            });
        </script>

        <script type="text/javascript">
            $(function(){

                var $select2Elm = $('#search_key');

                $select2Elm.select2({
                    placeholder: 'Country',
                    width: 'resolve'
                    
                });
                $('.select2-selection__arrow').hide();
                $('.select2-container--default .select2-selection--single').css({'border':'0','border-radius':'0','background':'none'});
                $('.select2-container--open .select2-dropdown').css({'left':'-1px'});
                $('.select2-dropdown').css({'border':'1px solid #d3d3d3'});
            });
        </script>
        <script type="text/javascript">
            $(function(){

                var $select2Elm = $('#search_key2');

                $select2Elm.select2({
                    placeholder: 'Spoken Language',
                    width: 'resolve'
                    
                });
                $('.select2-selection__arrow').hide();
                $('.select2-container--default .select2-selection--single').css({'border':'0','border-radius':'0','background':'none'});
                $('.select2-container--open .select2-dropdown').css({'left':'-1px'});
                $('.select2-dropdown').css({'border':'1px solid #d3d3d3'});
            });
        </script>
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

