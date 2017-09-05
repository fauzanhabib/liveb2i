<div class="heading text-cl-primary padding15">
    <h1 class="margin0">My Class</h1>
</div>


<div class="box b-f3-1">

    <div class="content padding0">
        <table class="table-session tbl-manage-class">
            <thead>
                <tr>
                    <th class="padding15 md-12">CLASS NAME</th>
                    <th class="padding15 md-none">START DATE</th>
                    <th class="padding15 md-none">END DATE</th>
                    <th class="padding15 md-12">ACTION</th>
                </tr>
            </thead>
            <tbody class="manage-class">
                <?php
                foreach ($data as $d) {
                    if ($d->start_date != '0000-00-00' && $d->end_date != '0000-00-00') {
                        ?>
                        <tr>
                            <td class="padding15 md-12">
        <?php echo(@$d->class_name); ?>
                                <div class="lg-none">
                                    <span class="text-cl-green"></span> 
                                </div>
                            </td>
                            <td class="padding15 md-none text-cl-green"><?php echo(date('M d, Y', strtotime(@$d->start_date))); ?></td>
                            <td class="padding15 md-none text-cl-green"><?php echo(date('M d, Y', strtotime(@$d->end_date))); ?></td>

                            <td class="padding15 md-12">
                                <a href="<?php echo site_url('coach/class_detail/schedule/'. $d->id);?>" class="pure-button btn-small btn-white">VIEW CLASS</a>
                            </td>
                        </tr>
        <?php
    }
}
?>

            </tbody>
        </table>
    </div>

</div>