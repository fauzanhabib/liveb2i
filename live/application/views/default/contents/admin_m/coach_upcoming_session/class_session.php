<?php if($this->auth_manager->role() == 'RAD') {
    $role_link = "superadmin";
} else {
    $role_link = "admin";
}

?>

<div class="heading text-cl-primary padding15">

    <div class="breadcrumb-tabs pure-g">
        <div class="left-breadcrumb">
            <ul class="breadcrumb toolbar padding-l-0">
                <li id="breadcrum-home"><a href="#">
                    <div id="home-icon">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">
                        <g>
                            <path d="M2.7,14.1c0,0,0,0.3,0.3,0.3c0.4,0,3.7,0,3.7,0l0-3c0,0-0.1-0.5,0.4-0.5h1.5c0.6,0,0.5,0.5,0.5,0.5l0,3
                                c0,0,3.1,0,3.6,0c0.4,0,0.4-0.4,0.4-0.4V8.5L8.1,4L2.7,8.5L2.7,14.1z"/>
                            <path d="M0.7,8.1c0,0,0.5,0.8,1.5,0l5.9-5l5.6,5c1.2,0.8,1.6,0,1.6,0L8.1,1.5L0.7,8.1z"/>
                            <polygon points="13.6,3 12.1,3 12.1,4.8 13.6,6  "/>
                        </g>
                        </svg>
                    </div>
                </a></li>
                <li><a href="#">Regions</a></li>
                <li><a href="#">Indonesia</a></li>
                <li><a href="#">Development Solutions</a></li>
                <li><a href="#">Couch Group List</a></li>
                <li>
                    <form action="" autocomplete="on" class="search-box">
                        <div id="src__sign">Search..</div>
                      <input id="search" name="search" type="text" placeholder="Type here.."><input id="search_submit" value="Rechercher" type="submit">
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <h1 class="margin0 left">Amanda Chandelier <em class="font-26">(Coach Session)</em></h1>

    <div class="btn-goBack padding-l-500 padding-t-5">
        <button class="btn-small border-1-blue bg-white-fff">
            <div class="left padding-r-5">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve" class="width15">
                <g id="back-one-page">
                    <g>
                        <g id="XMLID_13_">
                            <path style="fill-rule:evenodd;clip-rule:evenodd;" d="M20,0c11.046,0,20,8.954,20,20s-8.954,20-20,20S0,31.046,0,20
                                S8.954,0,20,0z M37.002,20c0-9.39-7.612-17.002-17.002-17.002C10.611,2.998,2.998,10.61,2.998,20
                                c0,9.389,7.613,17.002,17.002,17.002C29.39,37.002,37.002,29.389,37.002,20z"/>
                        </g>
                        <g>
                            <g>
                                <path style="fill:#231F20;" d="M27.734,22.141H13.636c-1.182,0-2.141-0.958-2.141-2.141s0.959-2.141,2.141-2.141h14.098
                                    c1.182,0,2.141,0.958,2.141,2.141S28.916,22.141,27.734,22.141z"/>
                            </g>
                            <g>
                                <g>
                                    <path style="fill:#231F20;" d="M19.465,24.27l-2.611-2.822c-0.756-0.818-0.756-2.08,0-2.897l2.611-2.822
                                        c1.264-1.366,0.295-3.582-1.566-3.582h-0.353c-0.595,0-1.162,0.248-1.566,0.685l-5.288,5.719c-0.756,0.817-0.756,2.079,0,2.896
                                        l5.288,5.719c0.404,0.437,0.971,0.685,1.566,0.685h0.353C19.76,27.852,20.729,25.636,19.465,24.27z"/>
                                </g>
                            </g>
                        </g>
                    </g>
                </g>
                <g id="Layer_1">
                </g>
                </svg>
            </div>
            Go Back One Page
        </button>
    </div>
</div>

<div class="box clear-both">
    <div class="heading pure-g">

        <div class="left-list-tabs pure-menu pure-menu-horizontal margin0">
            <ul class="pure-menu-list padding-l-20">
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url($role_link.'/coach_upcoming_session/one_to_one_session/'.@$coach_id);?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20">One To One Session</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url($role_link.'/coach_upcoming_session/class_session/'.@$coach_id);?>" class="active-tabs-blue pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20">Class Session</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url($role_link.'/coach_histories/index/'.@$coach_id);?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20">Session Histories</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url($role_link.'/coach_histories/class_session/'.@$coach_id);?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-20">Class Session Histories</a></li>
            </ul>
        </div>

    </div>


            <div class="margin0 padding15">
                <a href="#" class="link-filter">Please select date to filter <i class="icon icon-arrow-down"></i></a>
                <?php 
                echo form_open($role_link.'/coach_upcoming_session/search/class', 'class="pure-form filter-form" style="border:none"'); 
                ?>
                <div class="pure-g">
                    <div class="pure-u-1">
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_from" class="datepicker frm-date margin0" type="text" readonly="">  
                            <span class="icon icon-date"></span>
                        </div>
                        <span style="font-size: 16px;margin:0px 10px;">to</span>  
                        <div class="frm-date" style="display:inline-block">
                            <input name="date_to" class="datepicker2 frm-date margin0" type="text" readonly="">  
                            <span class="icon icon-date"></span>
                        </div>
                        <?php echo form_submit('__submit', 'Go','class="pure-button btn-small btn-primary" style="margin:0px 10px;"'); ?>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>

    <div class="content">
        <div class="box border-t-1">
        <script>
            $(document).ready(function() {
                $('#userTable').DataTable( {
                  "bLengthChange": false,
                  "searching": false,
                  "userTable": false,
                  "bInfo" : false
                });
            } );
        </script>
        <?php 
            $i = 1;
            if (@$data) {
        ?>

        <table id="userTable" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-cl-grey border-none">Transaction</th>
                    <th class="text-cl-grey border-none">START TIME</th>
                    <th class="text-cl-grey border-none">END TIME</th>
                    <th class="text-cl-grey border-none">CLASS</th>
                    <th class="text-cl-grey border-none">ACTION</th>               
                </tr>
            </thead>
            <tbody>
            <?php
                foreach ($data_class as $d) {
                $link = site_url('webex/available_host/' . @$d->id);
            ?>
                <tr>
                    <td> <?php echo(date('F d, Y', strtotime(@$d->date))); ?></td>
                    <td><?php echo(date('H:i',strtotime(@$d->start_time)));?></td>
                    <td><?php echo(date('H:i',strtotime(@$d->end_time)));?></td>
                    <td>
                        <div class="status-disable m-l-20">
                            <span class="bg-tertiary text-cl-white">
                                <a href="<?php echo site_url('coach/upcoming_session/student_detail/' . $d->student_id); ?>" class="text-cl-secondary">
                                    <?php echo @$d->class_name; ?>
                                </a>
                            </span>
                       </div>
                    </td>
                    <td>
                        <a href="<?php echo site_url($role_link.'/vrm/multiple_student/'. @$d->class_id); ?>" class="pure-button btn-medium btn-expand-tiny btn-primary-border">Progress <div class="md-none" style='display:inline-block'>Report</div></a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php } else {
                echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
            } ?>
        </div>
    </div>  
</div>
<?php echo @$pagination;?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.tablescroll.js"></script>

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
        startDate: "now",
        autoclose:true
    });

    $('.datepicker').change(function(){

        var dates = $(this).val();
        removeDatepicker();
        $('.datepicker2').datepicker({
            format: 'yyyy-mm-dd',
            startDate: getDate(dates),
            autoclose: true
        });
    });    

    $('.height-plus').css({'height':'50px'});
    
    $(function() {
        $('#thetable').tableScroll({height:200});
        $('#thetable2').tableScroll({height:200})
    });
</script>