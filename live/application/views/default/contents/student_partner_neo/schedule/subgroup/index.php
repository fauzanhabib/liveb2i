<div class="heading text-cl-primary padding15">
    <h1 class="margin0">List of Subgroup</h1>
</div>

<div class="box">
    <div class="heading pure-g"> 

    </div>

    <div class="content">
        <div class="box pure-g list-coach">

            <?php
                if ($subgroup == null) {
                  echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
                }

            ?>

            <?php $i = 1;
            foreach ($subgroup as $d) {
                ?>

                <div class="grids list-people pure-u-1 pure-u-sm-12-24 pure-u-md-12-24 pure-u-lg-8-24 list">

                    <div class="box-info">

                        <div class="detail">
                            <a href="<?php echo site_url('student_partner/schedule/index/' . @$d->subgroup_id); ?>" class="name"><?php echo $d->name; ?></a>

                            <table class="margint25">
                                <tr>
                                    <td>Subgroup</td>
                                    <td>:</td>
                                    <td><?php echo $d->name; ?></td>
                                </tr>

                            </table>
                        </div>

                    </div>


                </div>
<?php } ?>
        </div>
    </div>		
</div>

<?php echo @$pagination; ?>

