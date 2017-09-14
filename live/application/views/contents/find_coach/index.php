<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>

<?php
    echo("<br>Find Coaches<br><br>");
?>

    <div>    
        Filter : From <input name ="date_from" type="text" class="datepicker"> To <input name="date_to" type="text" class="datepicker">
        <?php echo form_submit('__submit', 'Go'); ?>
    </div>
    <br>

    
Search By
<?php
    //option to search coach
    $options = array(
                  'name'  => 'Name',
                  'country'    => 'Country',
                  'availability'   => 'Availability',
    );
    echo form_dropdown('search_by', @$options, @$selected, 'id = "search_by"');
    
    //setting the action of form
    $attribut = array('id' => 'category', 'role' => 'form');
    echo form_open('student/find_coaches/search/'.@$selected, $attribut);
    echo form_input('search_key', set_value('search_key'), 'class="form-control input-sm" id="search_key"');
    echo form_submit('__submit', @$this->auth_manager->userid() ? 'Search' : 'Search');
    echo form_close();
    ?>
<table border="1">
    <tr>
        <th>No</th>
        <th>Profile Picture</th>
        <th>Name</th>
        <th>Country</th>
        <th>Phone</th>
        <th>Rating</th>
        <th>Action</th>
    </tr>
    <?php $i =1; foreach(@$coaches as $c){ ?>
	<tr>
            <td><?php echo($i++);?></td>
            <td> <a href="<?php echo site_url('student/find_coaches/detail/'.$c->id); ?>"><?php echo $c->profile_picture; ?></a> </td>
            <td> <a href="<?php echo site_url('student/find_coaches/detail/'.$c->id); ?>"><?php echo $c->fullname; ?></a> </td>
            <td><?php echo $c->country; ?></td>
            <td><?php echo $c->phone; ?></td>
            <td><?php echo @$rating[$c->id]; ?></td>
            <td> <a href="<?php echo site_url('student/find_coaches/schedule_detail/'.$c->id); ?>"> View Schedule</a> </td>
        </tr>
    <?php } ?>
</table>

<script type="text/javascript">
    document.getElementById("search_by").onchange = function() {
        var newurl = "<?php echo site_url('student/find_coaches/search'); ?>"+"/"+this.value;
        $('#category').attr('action', newurl);
    };
    
    $(function () {
        var now = new Date();
        past = new Date(now.getFullYear(), now.getMonth() - 1, 1);
        $(".datepicker").datepicker({
            dateFormat: 'dd-mm-yy', 
            maxDate: new Date, 
            minDate: past
        });
    });
</script>
