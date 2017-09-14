<div class="heading text-cl-primary padding15">

 <!--    <div class="breadcrumb-tabs pure-g">
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
                <li>
                    <form action="" autocomplete="on" class="search-box">
                      <input id="search" name="search" type="text" placeholder="Type here.."><input id="search_submit" value="Rechercher" type="submit">
                    </form>
                </li>
            </ul>
        </div>
    </div> -->

    <h1 class="margin0">Coach Approval</h1>
</div>

<div class="box">
    <div class="heading pure-g">

        <div class="left-list-tabs pure-menu pure-menu-horizontal">
            <ul class="pure-menu-list m-l-20">
                <!-- <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('admin/approve_user/index/student'); ?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">Student</a></li> -->
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('admin/approve_user/index/coach'); ?>" class="tabs-blue-active pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">Coach</a></li>
            </ul>
        </div>
        
    </div>

    <div class="content">
        <?php
        if(!@$users) {
            echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
        }
        else {
        ?>

        <div class="box">
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

        <table id="userTable" class="display table-session" cellspacing="0" width="100%">
            
            <thead>
                <tr>
                    <th class="bg-secondary text-cl-white border-none">Username</th>
                    <th class="bg-secondary text-cl-white border-none">Email</th>
                    <th class="bg-secondary text-cl-white border-none">Affiliate</th>
                    <th class="bg-secondary text-cl-white border-none">PT Score</th>
                    <th class="bg-secondary text-cl-white border-none">Status</th>
                    <th class="bg-secondary text-cl-white border-none">Action</th>               
                </tr>
            </thead>

            <tbody class="approve-users">
                <?php $i =1; foreach(@$users as $u){
                    if($u->role_id == 2) {
                        $id_partner = $this->auth_manager->partner_id($u->id); 
                        $id_region = $this->auth_manager->region_id($id_partner);
                        if($id_region == $this->auth_manager->userid()){

                 ?>
                <tr>

                    <td><?php echo $u->fullname; ?></td>
                    <td><?php echo $u->email; ?></td>
                    <td><?php 
                            foreach ($partner as $p) {
                                if($p->id == $id_partner){
                                    echo $p->name;
                                }
                            }
                        ?>
                    </td>
                    <td><?php echo $u->pt_score; ?></td>

                    <td>
                        <div class="status-disable m-l-20 rw">
                            <span class="bg-grey-ddd text-cl-white labels <?php echo $u->status; ?>"><?php echo $u->status; ?></span>
                        </div>
                    </td>

                    <td class="t-center">
                        <div class="rw">
                            <div class="blue-red-btn">
                                <div class="pure-button btn-blue btn-small approve-user" onclick="confirmation('<?php echo site_url('admin/approve_user/approve/'.@$u->id.'/coach' ); ?>', 'group', 'Approve User', 'approve-users', 'approve-user');">Approve</div>
                                <div class="pure-button btn-red btn-small decline-user" onclick="confirmation('<?php echo site_url('admin/approve_user/decline/'.@$u->id.'/coach' ); ?>', 'group', 'Decline User', 'approve-users', 'decline-user');">Decline</div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php } } } ?>

            </tbody>
        </table>
        </div>
        <?php }?>
    </div>  
</div>

<script type="text/javascript">
    $(function () {
        $('.approve-user').click(function(){
            return false;
        });
        $('.decline-user').click(function(){
            return false;
        });
    });
</script>