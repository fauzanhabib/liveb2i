<div class="pure-u-lg-20-24 pure-u-md-24-24 pure-u-sm-24-24 content-center">

    <div class="heading text-cl-primary border-b-1 padding15">

        <h2 class="margin0">Token Request</h2>

    </div>

    <div class="box clear-both">
        <div class="heading pure-g padding-t-30">

            <div class="left-list-tabs pure-menu pure-menu-horizontal padding-l-25 margin0">
                <ul class="pure-menu-list">
                    <li class="pure-menu-item pure-menu-selected text-center width150 no-hover"><a href="<?php echo site_url('student/token'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey">Token History</a></li>
                    <li class="pure-menu-item pure-menu-selected text-center width150 no-hover"><a href="<?php echo site_url('student/token_requests'); ?>" class="pure-menu-link padding-t-b-5 font-16 padding-lr-0 font-light text-cl-lightGrey active-tabs-blue">Token Request</a></li>
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

                <table id="userTable" class="display left" cellspacing="0" width="30%">
                    <thead>
                        <tr>
                            <th class="text-cl-tertiary font-light font-16 border-none">BALANCE</th>
                            <?php echo ($data ? '<th class="text-cl-tertiary font-light font-16 border-none">REQUEST</th>' : ''); ?>
                            <th class="text-cl-tertiary font-light font-16 border-none">ACTION</th>    
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $remain_token->token_amount; ?></td>
                            <?php echo ($data ? '<td class="padding15">' . $data->token_amount . '</td>' : ''); ?>
                            <?php if ($data) { ?>
                                <td class="padding15">
                                    <a href="<?php echo site_url("student/token_requests/cancel/"); ?>" id="cancelRequest" class="pure-button btn-small btn-white"> Cancel Request </a>
                                </td>
                                <?php
                            } else {
                                ?>
                                <td class="padding15"> <a href="#" class="pure-button btn-small btn-white divModal" data-toggle="modal" data-target="#divModal"> Request More Token </a></td>
                                <?php
                            }
                            ?>

                            <!-- <td class="padding15"> <a href="#" class="pure-button btn-small btn-white" data-toggle="modal" data-target="#divModal"> Request More Token </a></td> -->
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>  
    </div>

</div>

<!-- Modal -->
<div class="modal hide fade" id="divModal" tabindex="-1" role="dialog" aria-labelledby="divModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon icon-close"></i></button>
                <h3 class="modal-title text-cl-primary" id="myModalLabel">Insert amount of tokens</h3>
            </div>
            <?php echo form_open('student/token_requests/create', 'role="form" class="pure-form" data-parsley-validate');?>
            <div class="modal-body">

                <?php
                    echo form_error('token_amount', '<small class="pull-right req">', '</small>');
                    echo form_input('token_amount', set_value('token_amount', @$data->email), 'id="token_amount" class="pure-input-1" placeholder="token amount" data-parsley-type="digits" required data-parsley-required-message="Please input how many token request" data-parsley-type-message="Please input numbers only"');
                ?>
            </div>
            <div class="modal-footer">
                <?php echo form_submit('__submit', @$data->id ? 'UPDATE' : 'SUBMIT','class="pure-button btn-primary btn-small btn-expand"'); ?>
            </div>
            <?php echo form_close(); ?>
    </div>
  </div>
</div>

<script type="text/javascript">
     $(document).ready(function() {

        $('.divModal').click(function(){
            $('#divModal').appendTo("body");
            $('#divModal').removeClass('hide');
        });

        

        $('#cancelRequest').click(function(event){
            event.preventDefault();
            var href = this.href;
            alertify.dialog('confirm').set({
                'title':'Cancel Request Token'
            });
            alertify.confirm("Are you sure?", function (e) {
                if (e) {
                    window.location.href = href;
                }
            });  
        });
    
    })
</script>