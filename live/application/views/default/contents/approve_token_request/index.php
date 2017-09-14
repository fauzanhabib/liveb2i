<div class="heading text-cl-primary padding-l-20">

    <div class="breadcrumb-tabs pure-g">
        <div class="left-breadcrumb">
            <ul class="breadcrumb toolbar padding-l-0">
                <li id="breadcrum-home"><a href="<?php echo base_url();?>index.php/account/identity/detail/profile">
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
                <li><a href="#">Approve Tokens</a></li>
            </ul>
        </div>
    </div>

    <div class="heading text-cl-primary padding-l-20">

        <h1 class="margin0 left">Approve Tokens</h1>

        <div class="btn-goBack padding-l-250 padding-t-5">
<!--             <button class="btn-small border-1-blue bg-white-fff">
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
            </button> -->
        </div>

    </div>

</div>

<div class="box clear-both">

    <div class="heading pure-g">

        <div class="left-list-tabs pure-menu pure-menu-horizontal">
            <ul class="pure-menu-list m-l-20">
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('student_partner/approve_token_requests'); ?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5 active-tabs-blue">Approve Tokens</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url('student_partner/history_token_requests'); ?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">Request Histories</a></li>
            </ul>
        </div>
    </div>

    <div class="content">
        <div class="box">
		 <?php
            if(!@$data){
                ?>  
                <div class="padding15">    
                    <div class="no-result">
                        No Data
                    </div>
                </div>

                <?php
            }
            else{
            ?>
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

        <table id="userTable" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="bg-secondary text-cl-white border-none">Name</th>
                    <th class="bg-secondary text-cl-white border-none">Affiliate</th>
                    <th class="bg-secondary text-cl-white border-none">Token Requests</th>
                    <th class="bg-secondary text-cl-white border-none">Action</th>               
                </tr>
            </thead>
            <tbody>
			 <?php 
             // echo "<pre>";
             // print_r($data);
             // exit();
             $i =1; foreach(@$data as $d){ 
                        $users = $this->db->select('up.fullname as fullname, u.email, p.name as partner')
                            ->from('token_requests tr')
                            ->join('users u', 'u.id = tr.user_id')
                            ->join('user_profiles up', 'up.user_id = tr.user_id')
                            ->join('partners p', 'p.id = up.partner_id')
                            ->where('tr.user_id', $d->user_id)
                            ->get()->result();
                ?>
                <tr>
                    <td><?php echo $users[0]->fullname; ?></td>
                    <td><?php echo $users[0]->partner; ?></td>
                    <td><?php echo $d->token_amount; ?></td>
                    <td>
                        <div class="blue-red-btn">
                            <div class="pure-button btn-green btn-small"><a href="#modal1-<?php echo $i; ?>">Approve</a></div>
                            <div class="pure-button btn-red btn-small"><a href="#modal2-<?php echo $i; ?>">Decline</a></div>
                        </div>
                    </td>
                </tr>
                <?php $i++; } ?>
            </tbody>
        </table>
        </div>
		 <?php } ?>  

        <?php $i =1; foreach(@$data as $d){ ?>
        <!-- Modal -->
        <div class="remodal" data-remodal-id="modal1-<?php echo $i; ?>" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
          <div>
            <p id="modal1Desc font-16">
              Are you sure?
            </p>
          </div>
          <br>
            <div class="blue-red-btn">
                <div class="pure-button btn-blue btn-small width70"><a href="<?php echo site_url('student_partner/approve_token_requests/approve/'.@$d->id ); ?>" class="font-16">Yes</a></div>
                <div class="pure-button btn-red btn-small width70"><a href="<?php echo site_url('student_partner/approve_token_requests/index/'); ?>" class="font-16">No</a></div>
            </div>
        </div><!-- /.modal -->
		<!-- Modal -->
        <div class="remodal" data-remodal-id="modal2-<?php echo $i; ?>" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
          <div>
            <p id="modal1Desc font-16">
              Are you sure?
            </p>
          </div>
          <br>
            <div class="blue-red-btn">
                <div class="pure-button btn-blue btn-small width70"><a href="<?php echo site_url('student_partner/approve_token_requests/decline/'.@$d->id ); ?>" class="font-16">Yes</a></div>
                <div class="pure-button btn-red btn-small width70"><a href="<?php echo site_url('student_partner/approve_token_requests/index/'); ?>" class="font-16">No</a></div>
            </div>
        </div><!-- /.modal -->
        <?php $i++; } ?>
    </div>  
    
</div>


                </div>
            </div>
        </div>

<script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/js/main.js"></script>
<script src="<?php echo base_url(); ?>assets/js/remodal.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/main.js"></script>


</body>
</html>