<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Manage Classes</h1>
</div>

<div class="box">
    <div class="heading pure-g">
        <div class="pure-u-12-24">
            <!--			<div class="h3 font-normal text-cl-secondary" style="padding: 10px 15px;">
                                            <form class="pure-form search-b-2" method="POST" action="site.php?role=student&page=student-book-by-name">
                                                    <input class="search-input" type="text" placeholder="Search by class name" style="font-size:14px;">
                                                    <button class="pure-button search-button"></button>
                                            </form> 
                                    </div>-->
        </div>
        <!-- block edit -->
        <div class="pure-u-12-24 edit no-left">
            <a href="<?php echo site_url('student_partner/managing/add_class'); ?>" class="add"><i class="icon icon-add"></i> Add a Class</a>
        </div>
        <!-- end block edit -->
    </div>

    <div class="content padding0">
        <div class="box b-pad">
            <table class="table-session tbl-manage-class">
                <thead>
                    <tr>
                        <th class="padding15">CLASS NAME</th>
                        <th class="padding15">MAX STUDENT</th>
                        <th class="padding15">START DATE</th>
                        <th class="padding15">END DATE</th>
                        <th class="padding15">ACTION</th>
                    </tr>
                </thead>
                <tbody class="manage-class">
                    <?php $i = 1;
                    foreach (@$classes as $c) {
                        ?>

                        <tr>
                            <td class="padding15" data-label="CLASS NAME">
                                <?php echo $c->class_name; ?>
                            </td>
                            <td class="padding15" data-label="MAX STUDENT"><?php echo $c->student_amount; ?></td>
                            <td class="padding15" data-label="START DATE"><?php echo date('M d, Y', strtotime($c->start_date)); ?></td>
                            <td class="padding15" data-label="END DATE"><?php echo date('M d, Y', strtotime($c->end_date)); ?></td>
                            
                            <td class="padding15 t-center">
                                <a href="<?php echo site_url('student_partner/managing/class_schedule/' . @$c->id); ?>" class="text-cl-green padding-r-5">VIEW</a>
                                <a class="edit-class padding-r-5" onclick="confirmation('<?php echo site_url('student_partner/managing/edit_class/' . @$c->id); ?>', 'group', 'Edit Class', 'manage-class', 'edit-class');">
                                    EDIT
                                </a>
<!--                                <a class="text-cl-primary delete-class" onclick="confirmation('<?php echo site_url('student_partner/managing/delete_class/' . @$c->id); ?>', 'group', 'Delete Class', 'manage-class', 'delete-class');">DELETE
                                </a>-->
                            </td>
                            <!--
                            <td class="view padding15 sm-12">
                                <a href="<?php echo site_url('student_partner/managing/class_schedule/' . @$c->id); ?>">VIEW</a></td>
                            <td class="padding15 sm-12">
                                <a class="edit-class padding15" onclick="confirmation('<?php echo site_url('student_partner/managing/edit_class/' . @$c->id); ?>', 'group', 'Edit Class', 'manage-class', 'edit-class');">
                                    EDIT
                                </a>
                            </td>
                            <td class="delete padding15 sm-12">
                                <a class="delete-class" onclick="confirmation('<?php echo site_url('student_partner/managing/delete_class/' . @$c->id); ?>', 'group', 'Delete Class', 'manage-class', 'delete-class');">DELETE
                                </a>
                            </td>-->
                        </tr>
                    <?php } ?>
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