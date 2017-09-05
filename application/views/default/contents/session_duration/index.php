<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Set Session Duration</h1>
</div>

<div class="box">
    <div class="heading pure-g"></div>

    <div class="content">
        <div class="box">
            <?php echo form_open('partner/session_duration/update', 'role="form" class="pure-form pure-form-aligned"');?>
                <fieldset>
                    <div class="pure-control-group">
                        <div class="label">
                            <label for="duration">Duration per session</label>
                        </div>
                        <div class="input">
                            <select name="session_per_block_by_partner" id="session_per_block_by_partner" class="pure-input-1-2" value="20">
                                <?php
                                    for ($i=20; $i <= 30 ; $i++) {
                                        if($i == $data->session_per_block_by_partner){
                                            echo '<option selected value="'.$i.'">'.$i.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                        }
                                        
                                        $i=$i+9;
                                    }
                                ?>
                            </select>
                            <?php //echo form_input('session_per_block_by_partner', set_value('session_per_block_by_partner', @$data->session_per_block_by_partner), 'id="session_per_block_by_partner" class="pure-input-1-2"') ?>
                        </div>
                    </div>
                    <div class="pure-control-group" style="border-top:1px solid #f3f3f3;padding: 15px 0px;">
                        <div class="label">
                            <?php echo form_submit('__submit', @$data->id ? 'UPDATE' : 'SUBMIT','class="pure-button btn-small btn-primary"'); ?>
                            <a href="<?php echo site_url('partner/member_list/coach');?>" class="pure-button btn-small btn-white">CANCEL</a>
                        </div>
                    </div>
                </fieldset>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
