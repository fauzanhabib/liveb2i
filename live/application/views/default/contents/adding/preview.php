<?php
// echo "<pre>";print_r($data);exit;
?><div class="heading text-cl-primary padding-l-20">


    <div class="heading text-cl-primary padding-l-20">

    <h1 class="margin0 left">Preview Add Multiple Students</h1>

    <div class="btn-goBack padding-l-250 padding-t-5">

    </div>

</div>

</div>

<div class="box clear-both">



    <div class="content">
        <div class="box">

        <script>
            $(document).ready(function() {
                $('#userTable').DataTable( {
                  "bLengthChange": false,
                  "searching": false,
                  "userTable": false,
                  "bInfo" : false,
                  "scrollX": true
                });
            } );
        </script>

        <style>
        .table-session th{
            text-align: left !important;
        }
        </style>

        <table id="userTable" class="display table-session" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="bg-secondary text-cl-white no-sort">#</th>
                    <?php
                        if($this->session->flashdata('finish') == 'finish'){
                    ?>
                        <th class="bg-secondary text-cl-white no-sort">Remark</th>
                    <?php } ?>

                    <th class="bg-secondary text-cl-white no-sort">Fullname</th>
                    <th class="bg-secondary text-cl-white no-sort">Email</th>
                    <th class="bg-secondary text-cl-white no-sort">Status Email</th>
                    <th class="bg-secondary text-cl-white no-sort">DynEd Pro ID</th>
                    <th class="bg-secondary text-cl-white no-sort">Status DynEd Pro ID</th>
                    <th class="bg-secondary text-cl-white no-sort">Server</th>
                    <th class="bg-secondary text-cl-white no-sort">PT Score</th>
          					<th class="bg-secondary text-cl-white no-sort">Birthdate</th>
          					<!-- <th class="bg-secondary text-cl-white no-sort">Gender</th> -->
          					<th class="bg-secondary text-cl-white no-sort">Phone</th>
          					<th class="bg-secondary text-cl-white no-sort">Token</th>
          					<!-- <th class="bg-secondary text-cl-white no-sort">Timezone</th> -->

                </tr>
            </thead>
            <tbody>
			<?php
			$total_token;
			$i =1;

            foreach(@$data as $d){
                        $userid = $this->auth_manager->userid();

                        ?>
                <tr>
                    <th><?php echo $i;?></th>
                    <?php
                        if(($this->session->flashdata('finish') == 'finish') && ($d->message == 'Succeded')){
                    ?>
                        <th style="color:green">Succeded</th>
                    <?php } else if(($this->session->flashdata('finish') == 'finish') && ($d->message != 'insert') ){ ?>
                        <th style="color:red"><?php echo str_replace(',', '<br>- ', $d->message);?></th>
                    <?php } ?>

                    <th><?php echo $d->fullname;?></th>
                    <th><?php echo $d->email;?></th>
                    <?php
                    if($d->status_email == 'Enable'){ ?>
                    <th><?php echo $d->status_email;?></th>
                    <?php } else { ?>
                    <th style="color:red"><?php echo $d->status_email;?></th>
                    <?php } ?>

                    <th><?php echo $d->dyned_pro_id;?></th>

                    <?php
                    if($d->status_email_dyned_pro == 'Enable'){ ?>
                    <th><?php echo $d->status_email_dyned_pro;?></th>
                    <?php } else { ?>
                    <th style="color:red"><?php echo $d->status_email_dyned_pro;?></th>
                    <?php } ?>

                    <th style="text-align:center !important;"><?php echo $d->server_dyned_pro;?></th>
                    <th style="text-align:center !important;"><?php echo $d->pt_score;?></th>
                    <th><?php echo $d->date_of_birth;?></th>
                    <!-- <th><?php echo $d->gender;?></th> -->
                    <th style="text-align:right !important;"><?php echo $d->phone;?></th>
                    <th style="text-align:center !important;"><?php echo $d->token_for_student;?></th>
                    <!-- <th><?php echo $d->timezone;?></th> -->

                    <?php (int)@$total_token += (int)$d->token_for_student; ?>


                </tr>
                <?php $i++; } ?>
            </tbody>

        </table>
        </div>

       	<div>
	        <span><b>* scroll to left or right in the table</b></span>
        </div>
        <hr>
        <?php
            if($this->session->flashdata('finish') != 'finish'){
        ?>
        <div>
        	note:
        		<ul>
        			<li>Tokens available: <b><?php echo $data[0]->my_token;?></b></li>
        			<li>Total token needed: <b><?php echo $total_token;?></b> </li>
        			<li><b>Used email and used DynEd Pro ID will not be processed</b></li>
        		</ul>
        </div>
        <div>
    		<th><a href="<?php echo base_url(); ?>index.php/student_partner/adding/submit_multiple_sudents" class="pure-button btn-small btn-blue">SUBMIT</a></th>
    		<th><a href="<?php echo base_url(); ?>index.php/student_partner/adding/cancel/<?php echo @$data[0]->subgroup_id;?>" class="pure-button btn-small btn-red text-cl-white">CANCEL</a></th>
    	</div>
        <?php } else { ?>
        <div>
            <th><a href="<?php echo base_url(); ?>student_partner/adding/cancel/<?php echo @$data[0]->subgroup_id;?>" class="pure-button btn-small btn-red text-cl-white">BACK</a></th>
        </div>
        <?php } ?>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/js/remodal.min.js"></script>


</body>
</html>
