<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Book a Coach</small></h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-12-24">
            <div class="h3 font-normal text-cl-secondary" style="padding: 10px 15px; font-size:14px;">
                <?php
                $attribut = array('id' => 'date_value', 'role' => 'form', 'class' => 'pure-g pure-form');
                echo form_open('student/find_coaches/book_by_single_date', $attribut);
                ?>
                <div class="pure-u-1 pure-u-md-2-5 pure-u-sm-4-5 email-form">
                    <?php echo form_input('date', set_value('date'), 'class="datepicker frm-date" id="date"'); ?>
                </div>
                <div class="pure-u-1 pure-u-md-1-5 pure-u-sm-1-5 text-left">
                    <?php echo form_submit('__submit', @$this->auth_manager->userid() ? 'Search' : 'Search', 'class="pure-button frm-small btn-blue btn-padding-s" style="margin:0"'); ?>
                </div>
                <?php echo form_close(); ?> 
            </div>
        </div>
        <!-- block edit -->
        <div class="pure-u-12-24 edit">
            <a href="<?php echo site_url('student/find_coaches/search/availability'); ?>" class="active">Date</a>
            <a href="<?php echo site_url('student/find_coaches/search/name'); ?>">Name</a>
            <a href="<?php echo site_url('student/find_coaches/search/country'); ?>">Country</a>
        </div>
        <!-- end block edit -->
    </div>
    <?php echo("&nbsp;&nbsp;&nbsp;&nbsp;Find coaches available at " . date('l jS \of F Y', strtotime(@$date))); ?>
    <div class="content">
        <div class="box pure-g">
            <?php
            foreach ($data as $d) {
                ?>
                <div class="pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">

                    <div class="list-coach-box">
                        <div class="pure-g">

                            <div class="pure-u-2-5 photo">
                                <img src="<?php echo base_url(); ?>images/BookCoach.jpg">
                            </div>

                            <div class="pure-u-3-5 detail">
                                <span class="name"><?php echo($d['fullname']); ?></span>
                                <div class="rating">
                                    <i class="stars_full"></i>
                                    <i class="stars_full"></i>
                                    <i class="stars_full"></i>
                                    <i class="stars_full"></i>
                                    <i class="stars_full"></i>
                                </div>
                                <table>
    <!--								<tr>
                                                <td>Certification</td>
                                                <td> : D7071</td>
                                        </tr>-->
    <!--								<tr>
                                                <td>PT Level</td>
                                                <td> : 0.5</td>
                                        </tr>-->
                                    <tr>
                                        <td>Token Cost</td>
                                        <td> : </td>
                                        <td><?php echo($d['token_for_student']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Country</td>
                                        <td> : </td>
                                        <td><?php echo($d['country']); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="more pure-u-1">
                                <span class="click arrow">View Schedule</span>
                            </div>

                        </div>
                    </div>
                    <div class="view-schedule" style="display:none">
                        <form class="pure-form">
                            <div class="list-schedule">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>START TIME</th>
                                            <th colspan="2">END TIME</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($d['availability'] as $av) {
                                            ?>
                                            <tr>
                                                <td><?php echo(date('H:i',strtotime(@$av['start_time']))); ?></td>
                                                <td><?php echo(date('H:i',strtotime(@$av['end_time']))); ?></td>
<!--                                                <td><a href="<?php //echo site_url('student/find_coaches/book_single_coach/' . $d['coach_id'] . '/' . strtotime(@$date) . '/' . $av['start_time'] . '/' . $av['end_time']); ?>" class="pure-button frm-xsmall btn-secondary" style="margin:0">Book</a></td>     -->
                                                <td><a href="<?php echo site_url('student/find_coaches/summary_book/single_date/' . $d['coach_id'] . '/' . strtotime(@$date) . '/' . $av['start_time'] . '/' . $av['end_time']); ?>" class="pure-button frm-xsmall btn-secondary" style="margin:0">Book</a></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>		
                                <!--
                                <table>
                                        <tbody>
                                <?php
                                for ($o = 1; $o < 10; $o++) {
                                    ?>
                                                            <tr>
                                                                    <td style="width:10%"><input type="checkbox"></td>
                                                                    <td>19.00</td>
                                                                    <td style="text-align:left">19.30</td>
                                                            </tr>
                                <?php } ?>
                                        </tbody>
                                </table>
                                -->
                            </div>
                            <!--
                            <div class="margint25">
                                    <button type="submit" class="pure-button btn-small btn-primary">CLEAR</button>
                                    <button type="submit" class="pure-button btn-small btn-primary">BOOK</button>
                            </div>
                            -->
                        </form>
                    </div>
                    <!--
                    <div class="list-teacher-box">
                            <div class="list-teacher">
                                    <div class="photo">
                                            <img src="images/bookcoach.jpg" style="width:124px;height:127px;">
                                    </div>
                                    <div class="detail">
                                            <span class="name">James Willson</span>
                                            <table>
                                                    <tr>
                                                            <td>Certification</td>
                                                            <td> : D7071</td>
                                                    </tr>
                                                    <tr>
                                                            <td>PT Level</td>
                                                            <td> : 0.5</td>
                                                    </tr>
                                                    <tr>
                                                            <td>Token</td>
                                                            <td> : 50</td>
                                                    </tr>
                                                    <tr>
                                                            <td>Country</td>
                                                            <td> : USA</td>
                                                    </tr>
                                            </table>
                                    </div>
                                    <div class="more">
                                            <a href="">View Shedule</a>
                                    </div>
                            </div>
                    </div>
                    !-->

                </div>
            <?php } ?>
        </div>
    </div>		
</div>

<script type="text/javascript">
    $(function () {
        $(document).ready(function () {

            $('.list').each(function () {
                var $dropdown = $(this);

                $(".click", $dropdown).click(function (e) {
                    e.preventDefault();
                    $div = $(".view-schedule", $dropdown);
                    $div.toggle();
                    $(".view-schedule").not($div).hide();
                    return false;
                });
            });

            $('html').click(function () {
                //$(".view-schedule").hide();
            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });

        });

    })

    document.getElementById("date").onchange = function () {
        var newurl = "<?php echo site_url('student/find_coaches/book_by_single_date'); ?>" + "/" + this.value;
        $('#date_value').attr('action', newurl);
    };
</script>
