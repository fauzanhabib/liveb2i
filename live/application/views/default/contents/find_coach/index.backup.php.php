<?php
    echo("<br>Find Coaches<br><br>");
?>

<?php
//    //option to search coach
//    $options = array(
//                  'name'  => 'Name',
//                  'country'    => 'Country',
//                  'availability'   => 'Availability',
//    );
//    echo form_dropdown('search_by', @$options, @$selected, 'id = "search_by"');
//    
//    echo form_input('date', set_value('date'), 'class="datepicker" id="date"');
//    
//    echo form_input('date2', set_value('date2'), 'class="datepicker2" id="date2"');
//    
//    //setting the action of form
//    $attribut = array('id' => 'category', 'role' => 'form');
//    echo form_open('student/find_coaches/search/'.@$selected, $attribut);
//    echo form_input('search_key', set_value('search_key'), 'class="form-control input-sm" id="search_key"');
//    echo form_submit('__submit', @$this->auth_manager->userid() ? 'Search' : 'Search');
//    echo form_close();
    ?>

Search By
<?php
    echo anchor(base_url().'index.php/student/find_coaches/search/name', 'Name | ');
    echo anchor(base_url().'index.php/student/find_coaches/search/country', 'Country | ');
    echo anchor(base_url().'index.php/student/find_coaches/single_date', 'Availability');
    
    echo form_open('student/find_coaches/search/name');
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
</script>

<script>
    $(function () {
        var now = new Date();
        past = new Date(now.getFullYear(), now.getMonth() - 1, 1);
        $(".datepicker").datepicker({
            dateFormat: 'dd-mm-yy', 
            maxDate: new Date, 
            minDate: past
        });
    });
    
    $(function () {
        var now = new Date();
        past = new Date(now.getFullYear(), now.getMonth() + 1, 1);
        $(".datepicker2").datepicker({
            dateFormat: 'dd-mm-yy', 
            maxDate: past, 
            minDate: new Date
        });
    });
</script>