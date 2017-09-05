<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Coach Approvals</h1>
</div>

<div class="box">
    <div class="content" style="padding: 0;border-top: 2px solid #f3f3f3;">
        
        <?php
        if(!@$users) {
            echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
        }
        else {
        ?>
        <div class="b-pad">
        <table class="table-session"> 
            <thead>
                <tr>
                    <th class="padding15">USERNAME</th>
                    <th class="padding15">STATUS</th>
                    <th class="padding15">ACTION</th>
                </tr>
            </thead>
            <tbody class="approve-users">
                <?php $i =1; foreach(@$users as $u){ ?>
                <tr>
                    <td class="padding15" data-label="USERNAME">
                        <?php echo $u->email; ?>
                    </td>
                    <td class="padding15" data-label="STATUS"><span class="labels <?php echo $u->status; ?>" style="width:92px"><?php echo $u->status; ?></span></td>
                    <td class="padding15 t-center">
                        <div class="rw">
                            <div class="b-50">
                                <a class="pure-button btn-small btn-white approve-user" onclick="confirmation('<?php echo site_url('admin/approve_user/approve/'.@$u->id ); ?>', 'group', 'Approve User', 'approve-users', 'approve-user');">APPROVE
                                </a>
                            </div>
                            <div class="b-50">    
                                <a class="pure-button btn-small btn-white decline-user" onclick="confirmation('<?php echo site_url('admin/approve_user/decline/'.@$u->id ); ?>', 'group', 'Decline User', 'approve-users', 'decline-user');">DECLINE
                                </a>
                            </div>
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