<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Partner Matches</h1>
</div>

<?php
$role_link = '';
if($this->auth_manager->role() == 'RAD'){
    $role_link = 'superadmin';
} else {
    $role_link = 'admin';
}
?>


<div class="box">
    <div class="heading pure-g">

        <div class="left-list-tabs pure-menu pure-menu-horizontal text-right padding-r-20">
            <ul class="pure-menu-list m-l-20">
                <li class="pure-menu-item pure-menu-selected no-hover tabs-blue-active"><a href="<?php echo site_url($role_link.'/match_partner'); ?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">Match List</a></li>
                <li class="pure-menu-item pure-menu-selected no-hover"><a href="<?php echo site_url($role_link.'/match_partner/add'); ?>" class="pure-menu-link padding-t-b-5 font-semi-bold font-14 padding-lr-5">New Matching</a></li>
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
                  "bInfo" : false
                });
            } );
        </script>

        <table id="userTable" class="display table-session" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="bg-secondary bg-none text-cl-white border-none" style="width:30px;">
                       No.
                    </th>
                    <th class="bg-secondary text-cl-white border-none">Student Partner</th>
                    <th class="bg-secondary text-cl-white border-none">Coach Partner</th>
                    <th class="bg-secondary text-cl-white border-none width20perc">Action</th>               
                </tr>
            </thead>
                    <?php 
                    $i = 1;

                    foreach (@$data as $d) { 
                        $ss = $d['student_supplier_data'];
                        $sp = $d['coach_supplier_data'];

                        if((count($ss) > 0) && (count($sp) > 0)){

                    ?>
                        <tr class="list-match">
                            <td class="padding15" data-label="NO"><?php echo($i++);?></td>
                            <td class="padding15" data-label="STUDENT PARTNER">
                                <span class="text-cl-secondary"><?php foreach($d['student_supplier_data'] as $d2){ @$temp1[]=@$d2->name; } echo @implode( ', ', @$temp1); @$temp1=''; ?></span>
                            </td>
                            <td class="padding15" data-label="COACH PARTNER">
                                <span class="text-cl-green"><?php foreach($d['coach_supplier_data'] as $d3){ @$temp2[] = @$d3->name; } echo @implode( ', ', @$temp2); @$temp2='';?></span>
                            </td>
                            <td>
                            <div class="blue-red-btn">
                            <a class="pure-button btn-blue btn-small text-cl-white approve-user edit_match" onclick="confirmation('<?php echo site_url($role_link.'/match_partner/edit/'.$d['id']); ?>', 'group', 'Edit Partner Matching', 'list-match', 'edit_match');">Edit</a>
                            <a class="pure-button btn-red btn-small text-cl-white approve-user delete_match" onclick="confirmation('<?php echo site_url($role_link.'/match_partner/delete/'.$d['id']); ?>', 'group', 'Delete Partner Matching', 'list-match', 'delete_match');">Delete</a>
                            </div>
                            </td>
                        </tr>
                        <?php
                    }
                    }
                    ?>

                </tbody>
            </table>
            </div>
        </div>
    </div>
<script type="text/javascript" src="js/main.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script type="text/javascript">
            $(".checkAll").change(function () {
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });
        </script>
<script type="text/javascript">
    $(function () {
        $('.edit_match').click(function(){
            return false;
        });
        
        $('.delete_match').click(function(){
            return false;
        });

        $('td').css({'vertical-align':'top'});
    });
</script>