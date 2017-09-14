<div class="heading text-cl-primary padding15">
    <h1 class="margin0"><small>Coach Shedule</small></h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-6-24">
            <h3 class="h3 font-normal padding15 text-cl-secondary">SCHEDULE SESSION</h3>
        </div>
        <div class="pure-u-18-24 edit tab-link">
            <a href="#tab1" class="active">Upcoming Session</a>
            <a href="#tab2" class="load_upcoming">Ongoing Session</a>
            <a href="#tab3" class="load_histories">History Session</a>
        </div>
    </div>

    <div class="content tab-content" style="margin-top: -18px">
        <div id="tab1" class="tab active">
            
            <?php
            if(!$data && !$data_class){
                echo "There is no upcoming session";
            }
            
            $i = 1;
            if ($data) {
                ?>
                <table class="table-session">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Student Name</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>WebEx</th>
                        </tr>
                    </thead><?php
                    foreach ($data as $d) {
                        $link = site_url('webex/available_host/' . @$d->id);
                        ?>
                        <tbody>
                            <tr>
                                <td><?php echo($i++); ?></td>
                                <td class="student"> <a href="<?php echo site_url('coach/upcoming_session/student_detail/' . $d->student_id); ?>"><?php echo @$student_name->fullname; ?></a> </td>
                                <td><?php echo(@$d->date); ?></td>
                                <td class="time"><?php echo(@$d->start_time); ?></td>
                                <td class="time"><?php echo(@$d->end_time); ?></td>
                                <td><?php echo (@$d->webex_status == "SCHE") ? "SCHEDULED" : "<a href='$link'>SETUP SCHEDULE</a>" ?></td>
                            </tr>
                        </tbody>
                    <?php }
                    ?>
                </table>
                <?php
            }
            ?>
            <br>    
            <?php
            $j = 1;
            if ($data_class) {
                ?>
                <table class="table-session">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Class Name</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>WebEx</th>
                        </tr>
                    </thead>
                    <?php
                    foreach ($data_class as $d) {
                        $link = site_url('webex/available_host/c' . @$d->id);
                        ?>
                        <tbody>
                            <tr>
                                <td><?php echo($j++); ?></td>
                                <td><?php echo @$d->class_name; ?> </td>
                                <td><?php echo(@$d->date); ?></td>
                                <td><?php echo(@$d->start_time); ?></td>
                                <td><?php echo(@$d->end_time); ?></td>
                                <td><?php echo(@$d->webex_status == "SCHE") ? "SCHEDULED" : "<a href='$link'>SETUP SCHEDULE</a>" ?></td>
                            </tr>
                        </tbody>
                        <?php }
                    ?>
                    <table class="table-session">
                    <?php
                    }
                    ?>
                </table>
        </div>
        <div id="tab2" class="tab">
            <div id="result">
                <img src='<?php echo base_url(); ?>images/small-loading.gif' alt='loading...' style="display:none;" id="schedule-loading"/>
            </div>
        </div>
        <div id="tab3" class="tab">
            <table class="table-session"> 
                <thead>
                    <tr>
                        <th>BOOKED DATE</th>
                        <th>STUDENT NAME</th>
                        <th>SESSION DATE</th>
                        <th>SESSION START TIME</th>
                        <th>SESSION END TIME</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>January, 24 2017</td>
                        <td class="student"><a href="">Dalley Blind</a></td>
                        <td>January, 24 2017</td>
                        <td class="time">12.30 </td>
                        <td class="time">14.00</td>
                    </tr>
                    <tr>
                        <td>January, 24 2017</td>
                        <td class="student"><a href="">Dalley Blind</a></td>
                        <td>January, 24 2017</td>
                        <td class="time">12.30 </td>
                        <td class="time">14.00</td>
                    </tr>
                    <tr>
                        <td>January, 24 2017</td>
                        <td class="student"><a href="">Dalley Blind</a></td>
                        <td>January, 24 2017</td>
                        <td class="time">12.30 </td>
                        <td class="time">14.00</td>
                    </tr>
                </tbody>
            </table>
            <a href="" class="more">More</a>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.list').each(function () {
            var $dropdown = $(this);

            $removef = $('.remove-field', $dropdown);

            $(".link-edit", $dropdown).click(function (e) {
                e.preventDefault();
                $(".edit", $dropdown).hide();
                $(".update", $dropdown).show();
                $(".addmore", $dropdown).show();


                return false;
            });

            var max_field = 2;
            var addmore = '.addmore';
            var i = 1;

            $(addmore, $dropdown).click(function (e) {
                e.preventDefault();
                $wrapper = $(".date", $dropdown);
                if (i < max_field) {
                    i++;
                    $($wrapper).append('<div class="block-date"><span>Shedule 2</span><input type="text"><span>to</span><input type="text"><span class="remove-field">Remove</span></div>');
                }
                $(".remove-field", $dropdown).show();
                //$(".addmore", $dropdown).hide();
            });

            $("body").on("click", ".remove-field", function (e) {
                e.preventDefault();
                //$(".addmore", $dropdown).show();
                $(this).parent("div").remove();
                i--;
            });
        });
        $('.box .tab-link a').click(function (e) {
            var currentValue = $(this).attr('href');

            $('.box .tab-link a').removeClass('active');
            $('.tab').removeClass('active');

            $(this).addClass('active');
            $(currentValue).addClass('active');

            e.preventDefault();

        })

    })

</script>

<!--<script type="text/javascript" src="<?php echo base_url('assets/js/skype-uri.js'); ?>"></script>
<div id="SkypeButton_Call_fsfesfse">
    <script type="text/javascript">
    Skype.ui({
        "name": "dropdown",
        "element": "skype-img",
        "participants": ["<?php //echo(@$student_name->skype_id);       ?>"],
        "imageSize": 32
    });
    </script>
</div>-->

<script>
    // ajax
    // don't cache ajax or content won't be fresh
    $.ajaxSetup({
        cache: false
    });

    // load() functions
    var loadUrl = "<?php echo site_url('coach/ongoing_session'); ?>";
    $(".load_upcoming").click(function () {
        $("#result").load(loadUrl);
    });
</script>
