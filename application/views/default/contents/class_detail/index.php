<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Classes</h1>
</div>
<div class="box b-f3-1">
    <div class="content padding0">
        <?php
        if($data!=null) {
        ?>
        <div class="b-pad">
        <table class="table-session tbl-manage-class">
            <thead>
                <tr>
                    <th class="padding15">CLASS NAME</th>
                    <th class="padding15">START DATE</th>
                    <th class="padding15">END DATE</th>
                    <th class="padding15">ACTION</th>
                </tr>
            </thead>
            <tbody class="manage-class">
                <?php
                foreach ($data as $d) {
                    if ($d->start_date != '0000-00-00' && $d->end_date != '0000-00-00') {
                        ?>
                        <tr>
                            <td class="padding15" data-label="CLASS NAME">
                                <span><?php echo(@$d->class_name); ?></span>
                            </td>
                            <td class="padding15" data-label="START DATE"><span class="text-cl-green"><?php echo(date('M d, Y', strtotime(@$d->start_date))); ?></span></td>
                            <td class="padding15" data-label="END DATE"><span class="text-cl-green"><?php echo(date('M d, Y', strtotime(@$d->end_date))); ?></span></td>
                            <td class="padding15 t-center">
                                <a href="<?php echo site_url('student/class_detail/schedule/'. $d->id);?>" class="pure-button btn-small btn-white">VIEW CLASS</a>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
        </div>
        <?php }
        else {
        ?>
        <div class="padding15"><div class="no-result">No Data</div></div>
        <?php    
        }
        ?>
    </div>

</div>