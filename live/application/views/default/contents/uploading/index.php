

            <?php 
            echo form_open('student_partner/uploading/create', 'role="form" class="pure-form pure-form-aligned" name="import" method="post" enctype="multipart/form-data"');
            ?>
    	<input type="file" name="file" /><br />
        <input type="submit" name="submit" value="Submit" />
        <?php echo form_close();?>