
<div class="heading text-cl-primary padding-l-20">


    <div class="heading text-cl-primary padding-l-20">

    <h1 class="margin0 padding-r-20 left">Day-off Histories</h1>

    <div class="btn-goBack padding-t-5">
        <button class="btn-small border-1-blue bg-white-fff" style="display:none">
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

</div>

<div class="box clear-both">

    <div class="heading pure-g">

        <div class="left-list-tabs pure-menu pure-menu-horizontal">
            <ul class="pure-menu-list padding-l-20">
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('partner/approve_coach_day_off'); ?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">Day-off Requests</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('partner/history_coach_day_off'); ?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 active-tabs-blue">Day-off Histories</a></li>
            </ul>
        </div>
        
    </div>

    <div class="content">
        <div class="box">
        <script>
            $(document).ready(function() {
                $('#userTable').DataTable( {
                  "bLengthChange": false,
                  "searching": false,
                  "userTable": false,
                  "bInfo" : false,
                  "paging" : false
                });
            } );
        </script>
        <?php
        if(!@$data){
            echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
        }
        else {
        ?>
        <table id="userTable" class="display table-session" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="bg-secondary text-cl-white border-none">Coach</th>
                    <th class="bg-secondary text-cl-white border-none">Description</th>
                    <th class="bg-secondary text-cl-white border-none">Start Date</th>
                    <th class="bg-secondary text-cl-white border-none">End Date</th>
                    <th class="bg-secondary text-cl-white border-none">Status</th>              
                </tr>
            </thead>
            <tbody>
                <?php
                foreach(@$data as $d){ ?>
                <tr>
                    <td><?php echo $d->fullname; ?></td>
                    <td class="">
                        <h5 class="text-cl-tertiary m-b-0">Urgent</h5>
                        <p class="m-t-0"><?php echo $d->remark; ?></p>
                    </td>
                    <td><?php echo date('F d, Y', strtotime($d->start_date)); ?></td>
                    <td><?php echo date('F d, Y', strtotime($d->end_date)); ?></td>
                    <td>
                        <div class="blue-red-btn">
                            <div class="pure-button btn-blue btn-small width70 labels <?php echo $d->status; ?>"><a href=""><?php echo $d->status; ?></a></div>
                        </div>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        </div>
        <?php } ?>
    </div>  
    
</div>
 <?php echo @$pagination;?>
                

<script type="text/javascript" src="../js/main.js"></script>
<script type="text/javascript" src="../js/jquery.dataTables.js"></script>
<script type="text/javascript">
    $(".checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });
</script>