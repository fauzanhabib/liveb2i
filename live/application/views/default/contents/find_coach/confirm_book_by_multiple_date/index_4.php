<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Booking Summary</small></h1>
</div>

<div class="box">

    <div class="content">
        <div class="box pure-g">

            <?php
            $i = 1;
            foreach ($data as $d) {
                $a = 1;
                $open = ($a == $i) ? " open" : "";
                $active = ($a == $i) ? " active" : "";
                $booking_index = $i++;
                ?>
                <!--
                <div class="accordion-section">
                        <div class="accordion-link" data-acc="accordion-<?php echo $booking_index; ?>">
                                <div class="title">
                                        BOOKING <?php echo $booking_index; ?>
                                </div>
                                <div class="icon">
                                        <i class="accordion-up"></i>
                                        <i class="accordion-down"></i>
                                        <i class="accordion-close"></i>
                                </div>
                        </div>
                        <div id="accordion-<?php echo $booking_index; ?>" class="accordion-content <?= $open ?>">
                                <table class="table-no-border2" style="border-collapse: separate;border-spacing: 0px 10px;padding:0">
                                        <tr>
                                                <td>Name</td>
                                                <td><?php echo($id_to_name[$d->coach_id]); ?></td>
                                        </tr>
                                        <tr>
                                                <td>Date</td>
                                                <td><?php echo(date('l jS \of F Y', strtotime($d->date))); ?></td>
                                        </tr>
                                        <tr>
                                                <td>Start Time</td>
                                                <td><?php echo($d->start_time); ?></td>
                                        </tr>
                                        <tr>
                                                <td>End Time</td>
                                                <td><?php echo($d->end_time); ?></td>
                                        </tr>
                                        <tr>
                                                <td>Call Method</td>
                                                <td>Skype / Webex</td>
                                        </tr>
                                        <tr>
                                                <td>Token Cost</td>
                                                <td><?php echo($token_cost[$d->coach_id]); ?></td>
                                        </tr>
                                </table>
                        </div>
                </div>
                -->

                <div class="accordion-container">
                    <div class="accordion-toggle<?= $active ?>">
                        BOOKING <?php echo $booking_index; ?>
                        <span class="toggle-icon arrow">
                            <i class="icon icon-arrowdown"></i>&nbsp;&nbsp;
                        </span>
                        <span class="toggle-icon">
                            <div onclick="return confirm('Approve User?');" onclick="location.href='<?php echo site_url('student/find_coaches/delete_temporary_appointment/'.$d->id); ?>';"><i class="icon icon-close"></i></div>
                        </span>
                    </div>
                    <div class="accordion-content<?= $open ?>">
                        <table class="table-no-border2" style="border-collapse: separate;border-spacing: 0px 10px;padding:0">
                            <tr>
                                <td class="no-pad">Name</td>
                                <td class="no-pad"><?php echo($id_to_name[$d->coach_id]); ?></td>
                            </tr>
                            <tr>
                                <td>Date</td>
                                <td><?php echo(date('l jS \of F Y', strtotime($d->date))); ?></td>
                            </tr>
                            <tr>
                                <td>Start Time</td>
                                <td><?php echo($d->start_time); ?></td>
                            </tr>
                            <tr>
                                <td>End Time</td>
                                <td><?php echo($d->end_time); ?></td>
                            </tr>
                            <tr>
                                <td>Call Method</td>
                                <td>Skype / Webex</td>
                            </tr>
                            <tr>
                                <td>Token Cost</td>
                                <td><?php echo($token_cost[$d->coach_id]); ?></td>
                            </tr>		
                        </table>
                    </div>
                </div>

                <?php
            }
            ?>

            <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;margin-top:15px;">
                <div class="label">
                    <a class="pure-button btn-normal btn-primary" href="<?php echo site_url('student/find_coaches/confirm_book/'); ?>" onclick=" return confirm('Are you sure?');">CONFIRM</a>
                </div>
                <div class="input">
                    <a class="pure-button btn-normal btn-white" href="<?php echo site_url('student/find_coaches/multiple_date'); ?>">CANCEL</a>
                </div>
            </div>



        </div>
    </div>		
</div>
<script type="text/javascript">

    $(function () {

        var $active = $('.active').length;
        if ($active > 0) {
            $('.active').children('.arrow').html('<i class="icon icon-arrowup"></i>&nbsp;&nbsp;');
        }

        function close_accordion() {
            $('.accordion-toggle').removeClass('active');
            $('.accordion-content').removeClass('open');
            $('.arrow').html('<i class="icon icon-arrowdown"></i>&nbsp;&nbsp;');
            return false;
        }

        $('.accordion-toggle').on('click', function (event) {
            event.preventDefault();
            var accordion = $(this);
            var accordionContent = accordion.next('.accordion-content');
            var accordionToggleIcon = $(this).children('.arrow');

            if ($(event.target).is('.active')) {
                close_accordion();
                accordionToggleIcon.html('<i class="icon icon-arrowdown"></i>&nbsp;&nbsp;');
                return false;
            }
            else {
                close_accordion();
                accordion.addClass("active");
                accordionContent.addClass('open');
                accordionToggleIcon.html('<i class="icon icon-arrowup"></i>&nbsp;&nbsp;');
            }

        });

    })
</script>