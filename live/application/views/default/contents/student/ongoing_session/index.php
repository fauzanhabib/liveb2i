<div class="heading text-cl-primary border-b-1 padding15">

    <h2 class="margin0">Current Sessions</h2>

</div>

<div class="box clear-both">
    <div class="heading pure-g padding-t-30">

        <div class="left-list-tabs pure-menu pure-menu-horizontal text-center margin0">
            <ul class="pure-menu-list">
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('student/ongoing_session'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue">Current Sessions</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('student/upcoming_session'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Upcoming Sessions</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('student/histories'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Session Histories</a></li>
                <li class="pure-menu-item pure-menu-selected text-center width250 no-hover"><a href="<?php echo site_url('student/histories/class_session');?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Class Session Histories</a></li>
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
                  "bPaginate": false
                });
            } );
        </script>
<?php if ($data) {?>
        <table id="userTable" class="display table-session" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-cl-tertiary font-light font-16 border-none">DATE</th>
                    <th class="text-cl-tertiary font-light font-16 border-none">START TIME</th>
                    <th class="text-cl-tertiary font-light font-16 border-none">END TIME</th>
                    <th class="text-cl-tertiary font-light font-16 border-none">COACH</th>
                    <th class="text-cl-tertiary font-light font-16 border-none">ACTION</th>               
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo date('F, j Y', strtotime($data[0]->date)); ?></td>

                    <td>
                        <div class="status-disable bg-green m-l-20">
                            <span class="text-cl-white"><?php  echo (date('H:i',strtotime($data[0]->start_time))); ?></span>
                        </div>
                    </td>
                    
                    <td>
                        <div class="status-disable bg-green m-l-20">
                            <span class="text-cl-white"><?php  echo (date('H:i',strtotime($data[0]->end_time))); ?></span>
                        </div>
                    </td>

                    <td>
                        <div class="status-disable bg-tertiary m-l-20">
                            <span class="text-cl-white"><?php echo @$coach_name->fullname ?></span>
                        </div>
                    </td>
                    <td>
                         <?php if($join_url && $session_status){?>
                            <a href="<?php echo $join_url;?>" target="_blank">WEBEX</a>
                        <?php }elseif($media == 'SKYPE'){ ?>
                            <a href="skype: <?php echo(@$coach_name->skype_id); ?>?call"><img src='<?php echo base_url(); ?>assets/icon/skype-icn.png'/></a> 
                        <?php }else{
                            echo "<span class='labels Book tooltip-bottom' data-tooltip='Coach not ready yet, refresh!'>WEBEX</span>"; 
                        }?>
                    </td>
                </tr>

            </tbody>
        </table>
                <?php } else{
                    echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
                } ?>
        </div>
    </div>  
</div>


<script type="text/javascript" src="<?php echo base_url('assets/js/skype-uri.js'); ?>"></script>