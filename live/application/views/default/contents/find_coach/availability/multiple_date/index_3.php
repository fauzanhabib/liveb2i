<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Book a Coach</small></h1>
</div>

<div class="box">
    <div class="heading pure-g">

        <!-- block edit -->
        <div class="pure-u-1 edit" style="margin: 0px 0px 15px;">
            <a href="<?php echo site_url('student/find_coaches/single_date'); ?>" class="active">Date</a>
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
                    <div class="cart">
                        <div class="number">5</div>
                        <div class="image">
                            <img src="assets/icon/cart.png">
                        </div>
                        <div class="dropdown-cart-box">
                            <div class="dropdown-cart-header">
                                YOUR BOOKINGS
                            </div>
                            <div class="dropdown-cart-list">
                                Your session will be started at Thursday,June 2,2015 from 19.00 until 19.30 with coach steve
                            </div>
                            <div class="dropdown-cart-list">
                                Your session will be started at Thursday,June 2,2015 from 19.00 until 19.30 with coach steve
                            </div>
                            <div class="dropdown-cart-button">
                                <button class="pure-button btn-small btn-white">VIEW DETAILS</button>
                            </div>
                        </div>
                    </div>
                </div>
                <form data-parsley-validate id="multipleform" class="pure-g pure-form multiple-session" method="post" action="book_by_multiple_date">
                    <div class="form-search-session">
                        <p>Select Date</p>
                        <input id="date1" name="date1" class="datepicker frm-date" type="text" required data-parsley-no-focus readonly>
                    </div>
                    <div class="frm-multiple-remove" style="width:100%"></div>
                    <div class="form-search-session frm-multiple">
                        <p>Select Date</p>
                        <div class="pure-g asdf">
                            <div class="pure-u-1 pure-u-md-3-5 pure-u-sm-3-5 email-form">
                                <input id="date2" name="date2" class="datepicker frm-date" type="text" required data-parsley-no-focus readonly>
                            </div>
                            <div class="pure-u-1 pure-u-md-2-5 pure-u-sm-2-5 text-right">
                                <button type="button" class="pure-button btn-small btn-white btn-expand addmultiple">
                                    <i class="icon icon-add"></i> Add More
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="pure-g button">
                        <button type="submit" class="pure-button btn-small btn-primary btn-expand searchmultiple" name="__submit">SEARCH</button>
                    </div>
                </form>
            </div>	
        </div>
    </div>		
</div>

<script type="text/javascript">
    var now = new Date();
    var day = ("0" + (now.getDate() + 2)).slice(-2);
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
                alert('Insert Date!');
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

            $('.frm-multiple-remove').append('<div class="form-search-session frm-multiple"><p> Select Date </p><div class="pure-g asdf"><div class="pure-u-1 pure-u-md-3-5 pure-u-sm-3-5 email-form"><input id="date' + (n + arguments.callee.count) + '" name="date' + (n + arguments.callee.count) + '" class="dateavailable datepicker frm-date" value="' + var_date + '" type="text" onmousemove="datepicker_select()" readonly></div><div class="pure-u-1 pure-u-md-2-5 pure-u-sm-2-5 text-right"><button type="button" class="remove-multiple pure-button btn-small btn-white btn-expand"><i class="icon icon-close"></i> REMOVE</button></div></div></div>');

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