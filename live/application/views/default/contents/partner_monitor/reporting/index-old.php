<div class="heading text-cl-primary border-b-1 padding15">

    <h2 class="margin0">Reporting</h2>

</div>

<div class="box clearfix">
    <div class="heading pure-g padding-t-30">
        <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
            <ul class="pure-menu-list">
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="#" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue">Coach Late</a></li>
                <!-- <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="#" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Upcoming Sessions</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="#" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Session Histories</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="#" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Class Session Histories</a></li> -->
            </ul>
        </div>
    </div>
    <div class="content pure-g">
        <div class="margin0 padding15 pure-u-1">
            <a href="#" class="link-filter">Please select date to filter <i class="icon icon-arrow-down"></i></a>
            <?php 
            echo form_open('partner_monitor/reporting/search/', 'class="pure-form filter-form" style="border:none"'); 
            ?>
            <div class="pure-g">
                <div class="pure-u-1 text-center m-t-20">
                    <div class="frm-date" style="display:inline-block">
                        <input name="date_from" id="date_from" class="datepicker frm-date margin0" type="text" readonly="" placeholder="Start Date">    
                            <span class="icon dyned-icon-coach-schedules"></span>
                    </div>
                    <span style="font-size: 16px;margin:0px 10px;">to</span>  
                    <div class="frm-date" style="display:inline-block">
                        <input name="date_to" id="date_to" class="datepicker2 frm-date margin0" type="text" readonly="" placeholder="End Date">  
                <span class="icon dyned-icon-coach-schedules"></span>
                    </div>
                    <?php echo form_submit('__submit', 'Search','class="pure-button btn-small btn-tertiary height-34 m-b-3" style="margin:0px 10px;"'); ?>
                    </div>
            </div>
        </div>
        <div class="pure-u-1 text-center">
            <a class="pure-button btn-small btn-green" style="margin:0px 10px;" href="<?php echo site_url('partner_monitor/reporting/download/'.@$startdate.'/'.@$enddate);?>">Download</a>
        </div>
         <?php echo form_close(); ?>
        <table id="" class="display table-session tablesorter m-t-20" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Coach Name</th>
                        <th>Subgroup</th>
                        <th>Appointment Date</th>
                        <th>Start Time</th>
                        <th>Coach Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                foreach(@$coach as $c) { 
                    if($this->uri->segment(3) == 'search'){
                        $data = $this->db->select('id, coach_id, date, start_time, cch_attend')->from('appointments')->where('coach_id', $c->user_id)->where('status', 'completed')->where('date BETWEEN "'. date('Y-m-d', strtotime($startdate)). '" and "'. date('Y-m-d', strtotime($enddate)).'"')->get()->result();
                    }else{
                      $data = $this->db->select('id, coach_id, date, start_time, cch_attend')->from('appointments')->where('coach_id', $c->user_id)->where('status', 'completed')->get()->result();  
                    }
                        foreach(@$data as $d)  { 
                        $cch_att_dif = strtotime($d->cch_attend) - strtotime($d->start_time);
                        $cch_att_val = date("H:i:s", $cch_att_dif);
                        if($cch_att_dif > '300'){
                    ?>
                    <tr>
                        <td> <?php echo $c->fullname; ?></td>
                        <td> <?php echo $c->name; ?></td>
                        <td> <?php echo $d->date; ?></td>
                        <td> <?php echo $d->start_time; ?></td>
                        <td> <?php echo $d->cch_attend; ?></td>
                    </tr>
                    <?php } } } ?>
                </tbody>
            </table>
    </div>  
</div>
        <script src="../js/main.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.tablescroll.js"></script>

        <script>
            // ajax
            // don't cache ajax or content won't be fresh
            $.ajaxSetup({
                cache: false
            });

            $('input#search').on('click', function() { 
                $(".fullname").click(function(e){
                e.preventDefault();  // stops the jump when an anchor clicked.
                var fullname = $(this).text(); // anchors do have text not values.

                $.ajax({
                url: '<?php echo site_url('partner_monitor/reporting/index') ;?>',
                data: {'fullname': fullname}, // change this to send js object
                type: "post",
                success: function(data){
                //document.write(data); just do not use document.write
                console.log(data);
        }
      });
   });
            });
       

            // load() functions
            var loadUrl = "<?php echo site_url('coach/ongoing_session'); ?>";
            $(".load_upcoming").click(function () {
                $("#result").load(loadUrl);
            });

            function getDate(dates){
                var now = new Date(dates);
                var day = ("0" + (now.getDate() + 1)).slice(-2);
                var month = ("0" + (now.getMonth() + 1)).slice(-2);
                var resultDate = now.getFullYear() + "-" + (month) + "-" + (day);
                return resultDate;
            }

            function removeDatepicker(){
                $('.datepicker2').datepicker('remove');
            }

            // datepicker
           $('.datepicker').datepicker({
                        format: 'yyyy-mm-dd',
                        endDate: "now",
                        autoclose:true
                    });

                    $('.datepicker').change(function(){
                        var dates = $(this).val();
                        removeDatepicker();
                        $('.datepicker2').datepicker({
                            format: 'yyyy-mm-dd',
                            startDate: getDate(dates),
                            endDate: "now",
                            autoclose: true
                });
            });    

            $('.height-plus').css({'height':'50px'});

            $('.rescheduled').click(function(){
                return false;
            });

            $('.cancel').click(function () {
                return false;
            });
            
            $(function() {
                $('#thetable').tableScroll({height:200});
                $('#thetable2').tableScroll({height:200})
            });
        </script>