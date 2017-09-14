<div class="heading text-cl-primary padding15">

     <!-- <div class="breadcrumb-tabs pure-g">
        <div class="left-breadcrumb">
            <ul class="breadcrumb toolbar padding-l-0">
                <li id="breadcrum-home"><a href="#">
                    <div id="home-icon">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">
                        <g>
                            <path d="M2.7,14.1c0,0,0,0.3,0.3,0.3c0.4,0,3.7,0,3.7,0l0-3c0,0-0.1-0.5,0.4-0.5h1.5c0.6,0,0.5,0.5,0.5,0.5l0,3
                                c0,0,3.1,0,3.6,0c0.4,0,0.4-0.4,0.4-0.4V8.5L8.1,4L2.7,8.5L2.7,14.1z"/>
                            <path d="M0.7,8.1c0,0,0.5,0.8,1.5,0l5.9-5l5.6,5c1.2,0.8,1.6,0,1.6,0L8.1,1.5L0.7,8.1z"/>
                            <polygon points="13.6,3 12.1,3 12.1,4.8 13.6,6  "/>
                        </g>
                        </svg>
                    </div>
                </a></li>
                <li><a href="#">Regions</a></li>
                <li><a href="#">Indonesia</a></li>
                <li><a href="#">Development Solutions</a></li>
                <li><a href="#">Couch Group List</a></li>
                <li>
                    <form action="" autocomplete="on" class="search-box">
                      <input id="search" name="search" type="text" placeholder="Type here.."><input id="search_submit" value="Rechercher" type="submit">
                    </form>
                </li>
            </ul>
        </div>
    </div> -->

    <h1 class="margin0 padding-r-10 left">Manage Classes</h1>
</div>

<div class="box clear-both">
    <div class="heading pure-g m-r-10 right">

        <div class="delete-add-btn">
            <div class="btn-noborder btn-normal bg-white-fff"><a href="<?php echo site_url('student_partner/managing/add_class'); ?>"><img src="<?php echo base_url();?>assets/img/iconmonstr-plus-6-16.png" class="left padding-t-1 padding-r-5"><em class="textDec-none text-cl-tertiary">Add New Class</em></a></div>
        </div>  

    </div>

    <div class="content padding-t-0 clear-both">
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
                        <th class="bg-secondary text-cl-white border-none">Class Name</th>
                        <th class="bg-secondary text-cl-white border-none">Max No. Students</th>
                        <th class="bg-secondary text-cl-white border-none">Start Date</th>
                        <th class="bg-secondary text-cl-white border-none">End Date</th>
                        <th class="bg-secondary text-cl-white border-none">Action</th>               
                    </tr>
                </thead>
                <tbody class="manage-class">
                    <?php
                    $i = 1;
                if (@$classes == null) {
                   
                }
                else {
                    foreach (@$classes as $c) {
                        ?>
                    <tr>
                        <td class="text-grey-nodecoration"><?php echo $c->class_name; ?></a></td>
                        <td class="text-grey-nodecoration"><?php echo $c->student_amount; ?></a></td>
                        <td class="text-grey-nodecoration"><?php echo date('M d, Y', strtotime($c->start_date)); ?></a></td>
                        <td class="text-grey-nodecoration"><?php echo date('M d, Y', strtotime($c->end_date)); ?></td>
                        <td>
                            <div class="pure-button btn-blue btn-small"><a href="<?php echo site_url('student_partner/managing/class_schedule/' . @$c->id); ?>" class="text-cl-green padding-r-5">View</a></div>
                            <div class="pure-button btn-green btn-small"><a class="edit-class padding-r-5" onclick="confirmation('<?php echo site_url('student_partner/managing/edit_class/' . @$c->id); ?>', 'group', 'Edit Class', 'manage-class', 'edit-class');">Edit</a></div>
                            <div class="pure-button btn-red btn-small"><a class="text-cl-primary delete-class" onclick="confirmation('<?php echo site_url('student_partner/managing/delete_class/' . @$c->id); ?>', 'group', 'Delete Class', 'manage-class', 'delete-class');">Delete</a></div>
                        </td>
                    </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
    </div>    
</div>

<?php echo @$pagination;?>
<script type="text/javascript">
    $(function() {
        $('a.edit-class').click(function(){
            return false;
        })
        $('a.delete-class').click(function(){
            return false;
        })
    })
</script>