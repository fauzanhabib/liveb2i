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
                        <li class="border-none"><a href="<?php echo site_url('student/find_coaches/single_date'); ?>">Date</a></li>
                        <div class="book-date">
                            <form data-parsley-validate id="multipleform" class="pure-g pure-form multiple-session" method="post" action="book_by_multiple_date" style="width: 100%;">
                                <div class="form-search-session">
                                    <p>Select a Date</p>
                                    <div class="frm-date">
                                        <input id="date1" name="date1" class="datepicker frm-date" type="text" required data-parsley-no-focus readonly>
                                        <span class="icon dyned-icon-coach-schedules"></span>
                                    </div>
                                </div>
                                <div class="frm-multiple-remove" style="width:100%"></div>
                                <div class="form-search-session frm-multiple fade">
                                    <p>Select a Date</p>
                                    <div class="pure-g">
                            
                                        <div class="left">
                                            <div class="frm-date">
                                                <input id="date2" name="date2" class="datepicker frm-date" type="text" required data-parsley-no-focus readonly>
                                                <span class="icon dyned-icon-coach-schedules"></span>
                                            </div>
                                        </div>

                                        <div class="left">
                                            
                                            <a href="#" class="addmultiple text-cl-green"><i class="icon icon-add"></i> Add More</a>

                                            
                                        </div>

                                    </div>
                                </div>
                                <div class="pure-g button">
                                    <button type="submit" class="pure-button btn-tertiary btn-expand-tiny searchmultiple" name="__submit">SEARCH</button>
                                </div>

                            </form>
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
    var now = new Date();
    var day = ("0" + (now.getDate() + 1)).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);

    function datepicker_select() {
        $('.dateavailable').datepicker({
            startDate: resultDate,
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
    }

    jQuery(document).ready(function ($) {
        $('.datepicker').datepicker({
            startDate: resultDate,
            format: 'yyyy-mm-dd',
            autoclose: true,
        });


        function getdate() {
            $('.datepicker').change(function () {
                var_date = $(this).val();
            })
        }

        var var_date = getdate();

        $('.searchmultiple').click(function () {

        });

        $('.addmultiple').click(function (e) {

            if ($('#date2').val() == '' || $('#date1').val() == '') {
                alert('Select a Date!');
                return false;
            }

            e.preventDefault;
            arguments.callee.count = ++arguments.callee.count || 1
            var n = $('.frm-multiple').length + 2;
            if (n > 5) {
                $('#date2').val(var_date);
                alert('Only 5 Date');
                return false;
            }
            else {
                $('#date2').val('');
            }

            $('.frm-multiple-remove').append('<div class="form-search-session frm-multiple"><p> Select a Date </p><div class="pure-g"><div class="left"><div class="frm-date"><input id="date' + (n + arguments.callee.count) + '" name="date' + (n + arguments.callee.count) + '" class="dateavailable datepicker frm-date" value="' + var_date + '" type="text" onmousemove="datepicker_select()" readonly><span class="icon icon-date"></span></div></div><div class="left"><a class="remove-multiple"><i class="icon icon-close"></i> Remove</a></div></div></div>');

            animationClick('.fade', 'fadeIn');

            var_date = '';

            return false;
        });


        $("#multipleform").on('click', '.remove-multiple', function (e) {

            $(this).parent().parent().parent().fadeOut("slow", function () {
                $(this).remove();
            });

        });

        $('.form-search-session').each(function () {

            var $dropdown = $(this);

            $(".datepicker", $dropdown).click(function (e) {
                $date = $('.datepicker', $dropdown);
                $date.parsley().reset();
            });

        });
    });
</script>