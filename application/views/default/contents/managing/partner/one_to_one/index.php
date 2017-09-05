<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/raty/jquery.raty.css">
<script src="<?php echo base_url(); ?>assets/vendor/raty/jquery.raty.js"></script>
<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Coach List</h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <?php //echo("&nbsp;&nbsp;&nbsp;&nbsp;Find coaches available at " . date('l jS \of F Y', strtotime(@$date))); ?>
    </div>
    <?php
    $gmt_user = $this->identity_model->new_get_gmt($student_id);
    $new_gmt = 0; 
    if($gmt_user[0]->gmt > 0){
    $new_gmt = '+'.$gmt_user[0]->gmt;
    }else{
    $new_gmt = $gmt_user[0]->gmt;    
    }?>
    <p class="padding-l-15 text-cl-primary">Find coaches for student available at <?php echo date('l jS \of F Y', $date); ?><br>Schedule time based on student's timezone (UTC <?php echo $new_gmt; ?>)</p>
    <div class="content bg-white">
        <div class="box">
            <div class="pure-g">
                <?php
                foreach (@$data as $d) {
                    ?>
                    <div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">
                        <div class="box-of-info text-center padding-b-10">
                            <div class="thumb-medium padding-t-20">
                                <img src="<?php echo base_url().$d['profile_picture'];?>" class="img-circle-medium-big">
                            </div>
                                <h5><a class="text-cl-tertiary font-18" href="#"><?php echo($d['fullname']); ?></a></h5>
                                <?php 


                                    $allrate = $this->db->select('rate')
                                                    ->from('coach_ratings')
                                                    ->where('coach_id', $coach_id)
                                                    ->get()->result();

                                    $temp = array();
                                    foreach($allrate as $in)
                                    {
                                        $temp[] = $in->rate;
                                    }

                                    $sumrate   = array_sum($temp);
                                    $countrate = count((array)$allrate);

                                    if($sumrate != null && $countrate != null){
                                        $classrate = $sumrate / $countrate * 20;
                                        $tooltip   = $sumrate / $countrate;
                                    }else{
                                        $classrate = 0;
                                        $tooltip   = 0;
                                    }


                                    $coach_cost_old = '';
                                    if($coach_type_id == 1){
                                        $coach_cost_old = $standard_coach_cost;
                                    } else if($coach_type_id == 2){ 
                                        $coach_cost_old = $elite_coach_cost;
                                    }

                                    $coach_cost_new = '';
                                    if($d['coach_type_id'] == 1){
                                        $coach_cost_new = $standard_coach_cost;
                                    } else if($d['coach_type_id'] == 2){ 
                                        $coach_cost_new = $elite_coach_cost;
                                    }

        
                                ?>
                                <div class="m-b-10" data-tooltip="<?php echo number_format($classrate);?>% (<?php echo(round($tooltip,1));?> of 5 Stars)">
                                    <div class="star-rating">
                                        <span style="width:<?php echo $classrate; ?>%"></span>
                                    </div>
                                </div>
        
                                    <?php echo $coach_cost_new;?>
                                    Tokens
                                </h5>
                                <h5><?php echo($d['country']); ?></h5>
                                <div class="more pure-u-1">
                                    <span class="click arrow font-12">View Schedule <i class="icon icon-arrow-down font-10"></i></span>
                                </div>

                            </div>
                        <div class="view-schedule hide">
                            <div class="box-schedule-max">
                                <form class="pure-form">
                                    <div class="list-schedule list-schedule-max">
                                        <table class="tbl-booking">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">START TIME</th>
                                                    <th class="text-center">END TIME</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="manage-session">
                                                <?php
                                                if(@$action == 're'){
                                                    $function = 'do_reschedule_session';
                                                    $label = 'Reschedule';
                                                }else{
                                                    $function = 'create_meeting_day';
                                                    $label = 'Set Meeting Time';
                                                }
                                                foreach ($d['availability'] as $av) {
                                                    $get_end_time = date('H:i', strtotime(@$av['end_time']));
                                                    $time = strtotime($get_end_time);
                                                    $new_time = $time - (5*60);
                                                    $new_end_time = date('H:i', $new_time);
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo(date('H:i', strtotime($av['start_time']))); ?></td>
                                                        <td class="text-center"><?php echo($new_end_time); ?></td>
                                                        <td>
                                                            <a class="pure-button btn-small btn-white reschedule-session">
                                                                <span onclick="confirmation('<?php echo site_url('partner/managing/reschedule_booking/' . @$student_id . '/' . @$appointment_id . '/' .@$d['coach_id']. '/' . @date('Y-m-d', $date) . '/' . $av['start_time'] . '/' . $av['end_time'].'/'. @$coach_cost_old.'/'.@$coach_cost_new); ?>', 'group', 'Reschedule Session', 'manage-session', 'reschedule-session');">RESCHEDULE</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>			
                                </form>
                            </div>
                        </div>
                    </div>

           
                <?php } ?>
            </div>
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

                    $schedule = $(".view-schedule", $dropdown);
                    $span = $("span", $dropdown);
                    $icon = $("span .icon", $dropdown);

                    if ($($schedule).hasClass("show")) {
                        $($schedule).addClass('hide');
                        $($schedule).removeClass('show');
                        $($span).removeClass('active-schedule');
                        $($icon).removeClass('icon-flips');
                    }
                    else {
                        $($schedule).addClass('show');
                        $($schedule).removeClass('hide');
                        $($span).addClass('active-schedule');
                        $($icon).addClass('icon-flips');
                    }

                    $(".view-schedule").not($schedule).addClass('hide');
                    $(".view-schedule").not($schedule).removeClass('show');
                    $("span").not($span).removeClass('active-schedule');
                    $("span .icon").not($icon).removeClass('icon-flips');

                    return false;
                });
            });
            $('.rating').raty({
                readOnly: true,
                starHalf: 'icon icon-star-half',
                starOff: 'icon icon-star-full color-grey',
                starOn: 'icon icon-star-full',
                starType: 'i',
                score: function () {
                    return $(this).attr('data-score');
                }
            });

        });

    })
</script>

<script type="text/javascript">
    $(document).ready(function() {
        if (navigator.appVersion.indexOf("Win")!=-1) 
        {
          $('.star-ratings-css').css('width','83px');
        } else {
          $('.star-ratings-css').css('width','100px'); // this will style body for other OS (Linux/Mac)
        }
    });
</script>