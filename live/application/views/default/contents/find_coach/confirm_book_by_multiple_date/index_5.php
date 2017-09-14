{elapsed_time} secs with {memory_usage}
<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Booking Summary</small></h1>
</div>
<div>
    <img src='<?php echo base_url(); ?>images/small-loading.gif' alt='loading...' style="display:none;" id="schedule-loading"/>
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
                                                    <td><?php echo(date('l jS \of F Y', $d['data']['date'])); ?></td>
                                            </tr>
                                            <tr>
                                                    <td>Start Time</td>
                                                    <td><?php echo($d['data']['start_time']); ?></td>
                                            </tr>
                                            <tr>
                                                    <td>End Time</td>
                                                    <td><?php echo($d['data']['end_time']); ?></td>
                                            </tr>
                                            <tr>
                                                    <td>Call Method</td>
                                                    <td>Skype / Webex</td>
                                            </tr>
                                            <tr>
                                                    <td>Token Cost</td>
                                                    <td><?php echo($token_cost[$d['coach_id']]); ?></td>
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
                                <a class="confirm-multiple-booking" onclick="confirmation('<?php echo site_url('student/find_coaches/delete_temporary_appointment/' . $d['id']); ?>', 'single', 'Booking schedule', '', 'confirm-multiple-booking');">
                                    <i class="icon icon-close"></i>
                                </a>
                            </span>
                        </div>
                        <div class="accordion-content<?= $open ?>">
                            <table class="table-no-border2" style="border-collapse: separate;border-spacing: 0px 10px;padding: 10px 0">
                                <tr>
                                    <td class="no-pad">Name</td>
                                    <td class="no-pad"><?php echo($id_to_name[$d['coach_id']]); ?></td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td><?php echo(date('l jS \of F Y', $d['data']['date'])); ?></td>
                                </tr>
                                <tr>
                                    <td>Start Time</td>
                                    <td><?php echo($d['data']['start_time']); ?></td>
                                </tr>
                                <tr>
                                    <td>End Time</td>
                                    <td><?php echo($d['data']['end_time']); ?></td>
                                </tr>
                                <tr>
                                    <td>Call Method</td>
                                    <td>Skype / Webex</td>
                                </tr>
                                <tr>
                                    <td>Token Cost</td>
                                    <td><?php echo($token_cost[$d['coach_id']]); ?></td>
                                </tr>		
                            </table>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;margin-top:15px;width:100%">
                    <div class="label" style="width:auto;margin-right: 10px;">
                        <a class="pure-button btn-normal btn-primary confirm-multiple-booking" onclick="confirmation_s('<?php echo site_url('student/find_coaches/confirm_book/') ?>', 'single', 'Booking schedule', '', 'confirm-multiple-booking');">
                            CONFIRM BOOKING
                        </a>
                    </div>
                    <div class="label" style="width:auto;">
                        <a class="pure-button btn-normal btn-white" href="<?php echo site_url('student/find_coaches/multiple_date'); ?>">CANCEL</a>
                    </div>
                </div>



            </div>
        </div>		
    </div>
</div>
<script src="<?php echo base_url(); ?>assets/js/jquery.blockUI.js"></script>
<script type="text/javascript">

    $(function () {

        if($(document).width() <= 420) {
            $('.open').css({'height':'370px'});
        }
        else {
            $('.open').css({'height':'310px'});
        }

        var $active = $('.active').length;

        if ($active > 0) {
            $('.active').children('.arrow').html('<i class="icon icon-arrowup"></i>&nbsp;&nbsp;');
        }

        function close_accordion() {
            $('.accordion-toggle').removeClass('active');
            $('.accordion-content').removeClass('open').css({'height':'0'});
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
                 if($(document).width() <= 420) {
                   accordionContent.addClass('open').css({'height':'370px'});
                }
                else {
                    accordionContent.addClass('open').css({'height':'310px'});
                }     
                //animationClick(accordionContent,'fadeOutDown');
                accordionToggleIcon.html('<i class="icon icon-arrowup"></i>&nbsp;&nbsp;');
            }

        });

    })

    function loading() {
        $.blockUI({ css: { 
            border: 'none', 
            padding: '10px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
        //$("#schedule-loading").show();
        $.post("<?php echo site_url('student/find_coaches/confirm_book'); ?>", function (result) {
            if (result.substring(0, 7) == 'Success') {
                var response = result.split("#");
                alert(response);
                window.location = "<?php echo site_url('student/find_coaches/multiple_date');?>";
            }
            //$("#schedule-loading").hide();
            $.unblockUI;
        });
        return false;
    };
    
    function confirmation_s(url, type, title, class_group_name, class_name){
        if(type === 'group'){
            $('.'+class_group_name).each(function () {
                var dropdown = $(this);
                $('.'+class_name, dropdown).click(function (event) {
                    event.preventDefault();
                    var href = url;
                    alertify.dialog('confirm').set({
                        'title': title
                    });
                    alertify.confirm("Are you sure?", function (e) {
                        if (e) {
                            window.location.href = href;
                        }
                    });
                });
            });
        }else if(type==='single'){
            $('.'+class_name).click(function (event) {
                event.preventDefault();
                var href = url;
                alertify.dialog('confirm').set({
                    'title': title
                });
                alertify.confirm("Are you sure?", function (e) {
                    if (e) {
                        loading();
                    }
                });
            });
        }
    }
    
</script>
