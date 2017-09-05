<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Tokens</small></h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-1 tab-list tab-link">
            <ul>
                <li><a href="">Token Histories</a></li>
                <li class="current"><a href="" class="active">Request Token</a></li>
            </ul>
        </div>
    </div>

    <div class="content padding0">
        <div class="box">
            <table class="table-token"> 
                <thead>
                    <tr>
                        <th class="token-balance padding15 text-left">BALANCE</th>
                        <th class="token-req padding15">REQUEST</th>
                        <th class="padding15">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="token-balance padding15 text-left">100</td>
                        

                        <td class="token-balance padding15">1000</td>
                        <?php //if ($data) { ?>
                            <td class="padding15">
                                <a href="<?php echo site_url("student/token_requests/cancel/"); ?>" id="cancelRequest" class="pure-button btn-small btn-white"> Cancel Request </a>
                            </td>
                            <?php
                        //} else {
                            ?>
                            <!-- <td class="padding15"> <a href="#" class="pure-button btn-small btn-white" data-toggle="modal" data-target="#divModal"> Request More Token </a></td> -->
                            <?php
                        //}
                        ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>      
</div>

<!-- Modal -->
<div class="modal hide fade" id="divModal" tabindex="-1" role="dialog" aria-labelledby="divModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon icon-close"></i></button>
                <h3 class="modal-title text-cl-primary" id="myModalLabel">Token Request</h3>
            </div>
            <?php echo form_open('student/token_requests/create', 'role="form" class="pure-form" data-parsley-validate');?>
            <div class="modal-body">

                <?php
                    echo form_error('token_amount', '<small class="pull-right req">', '</small>');
                    echo form_input('token_amount', set_value('token_amount', @$data->email), 'id="token_amount" class="pure-input-1" placeholder="Insert your token ammount here" data-parsley-type="digits" required data-parsley-required-message="Please input how many token request" data-parsley-type-message="Please input numbers only"');
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

        $('#divModal').appendTo("body");

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