<div class="heading text-cl-primary padding15">
    <h1 class="margin0"><small>Available Host</small></h1>
</div>
<div class="content padding15">
    <div class="box">
        <div class="heading pure-g">
            <table class="table-session">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Host Name</th>
                        <th>Timezones</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <?php
                $i = 1;
                foreach ($available_host as $host) {
                    echo form_open('webex/create_session'. '/' . @$host->id . '/' . @$appointment_id, 'role="form"');?>
                    <tbody>    
                        <tr>
                            <td><?php echo($i++); ?></td>
                            <td><?php echo $host->webex_id; ?></td>
                            <td><?php echo timezone_menu($this->common_function->timezones()[$host->timezones], 'timezones', 'timezone'.$host->id ); ?></td>
                            <td><?php echo form_submit('__submit', 'USE', 'class="pure-button btn-small btn-primary"'); ?> </td>
                        </tr>
                    </tbody>
                <?php 
                echo form_close();
                } ?> 
            </table>
        </div>
    </div>
</div>
<script>
    $(function () {
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: new Date()
        });
        
        $('.timezones').attr('disabled', 'disabled');
    });
</script>