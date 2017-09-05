<script src='//static.opentok.com/v2/js/opentok.min.js'></script> 

<style>
  .publisher {
      position: absolute;
    }
  .subscriber {
  top: 0;
  left: 0;
  width: 100%;
  z-index: 100;
}
.publisher {
  bottom: 3.4em;
  right: 0.9em;
  width: 20%;
  z-index: 200;
  border: 2px solid white;
  border-radius: 1px;
}
    
@media only screen and (max-width: 768px) {
    .publisher {
        display: none;
        width: 50%;
        bottom: 10em;
        height: 100px;
    }
}
</style>
<style>
#subscriberContainer:-webkit-full-screen {
  width: 100% !important;
  height: 500px !important;
}

#subscriberContainer:-moz-full-screen {
  width: 100% !important;
 height: 500px !important;
}

#subscriberContainer:-ms-full-screen {
  width: 100% !important;
 height: 500px !important;
}

#subscriberContainer:-o-full-screen {
  width: 100% !important;
  height: 500px !important;
}

#subscriberContainer:full-screen {
  width: 100% !important;
 height: 500px !important;
}
</style>
<div class="heading text-cl-primary padding15">
    <h1 class="margin0">Your Sessions for Today</h1>
</div>

<div class="box b-f3-1">
	<div class="content padding15">
            <?php
            if(!@$data) {
                echo "<div class='padding15'><div class='no-result'>No Data</div></div>";
            }
            ?>

            <?php if ($data) { ?>
                <table id="thetable" class="table-sessions"> 
                    <thead>
                        <tr>
                            <th class="padding15 sm-12 tb-ses-up">DATE</th>
                            <th class="padding15 md-none">TIME</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($data as $d) {
                            ?>
                            <tr>
                                <td class="padding-10-15 sm-12">
                                    <?php echo date('F j, Y', strtotime($d->date)); ?>
                                    <br>
                                    <span class="text-cl-green lg-none"><?php echo(date('H:i',strtotime($d->start_time)));?> - <?php echo(date('H:i',strtotime($d->end_time)));?></span>
                                    <span class="text-cl-primary lg-none">
                                        Coach <a href="<?php echo site_url('student/session/coach_detail/' . $d->coach_id); ?>" class="text-cl-secondary"><?php echo $d->coach_fullname ?>
                                    </span>
                                </td>
                                <td class="padding-10-15 md-none"><span class="text-cl-green"><?php echo(date('H:i',strtotime($d->start_time)));?> - <?php echo(date('H:i',strtotime($d->end_time)));?></span></td>
                                
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
		
		
	</div>
</div>