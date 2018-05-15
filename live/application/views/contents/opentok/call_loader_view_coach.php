<html>
<body>
<script>
$("#reloadajax4").click(function() {
    var std_id = "<?php echo $std_id_for_cert; ?>";
    // console.log('asdsafasf');
    $("#ajaxcall").hide();
    $("#reloading").show();

    $.post("<?php echo site_url('opentok/call_loader/call_ajax');?>", { 'std_id': std_id },function(data) {
     $("#reloading").hide();
     $("#ajaxcall").show();
     $("#ajaxcall").html(data);
     // alert(data);
     });

} );

</script>
<script>
    // Wait until the DOM has loaded before querying the document
    $(document).ready(function(){
        $('div.tabs2').each(function(){
            // For each set of tabs, we want to keep track of
            // which tab is active and its associated content
            var $active, $content, $links = $(this).find('a');

            // If the location.hash matches one of the links, use that as the active tab.
            // If no match is found, use the first link as the initial active tab.
            $active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
            $active.addClass('active');

            $content = $($active[0].hash);

            // Hide the remaining content
            $links.not($active).each(function () {
                $(this.hash).hide();
            });

            // Bind the click event handler
            $(this).on('click', 'a', function(e){
                // Make the old tab inactive.
                $active.removeClass('active');
                $content.hide();

                // Update the variables with the new link and content
                $active = $(this);
                $content = $(this.hash);

                // Make the tab active.
                $active.addClass('active');
                $content.show();

                // Prevent the anchor's default click action
                e.preventDefault();
            });
        });
    });
</script>
<script>
    // Wait until the DOM has loaded before querying the document
    $(document).ready(function(){
        $('ul.tabs').each(function(){
            // For each set of tabs, we want to keep track of
            // which tab is active and its associated content
            var $active, $content, $links = $(this).find('a');

            // If the location.hash matches one of the links, use that as the active tab.
            // If no match is found, use the first link as the initial active tab.
            $active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
            $active.addClass('active');

            $content = $($active[0].hash);

            // Hide the remaining content
            $links.not($active).each(function () {
                $(this.hash).hide();
            });

            // Bind the click event handler
            $(this).on('click', 'a', function(e){
                // Make the old tab inactive.
                $active.removeClass('active');
                $content.hide();

                // Update the variables with the new link and content
                $active = $(this);
                $content = $(this.hash);

                // Make the tab active.
                $active.addClass('active');
                $('.checkB').show();
                $('#tabs-content1').show();
                $('.tabs2').show();
                $content.show();

                // Prevent the anchor's default click action
                e.preventDefault();
            });
        });
    });
</script>
<?php

$wss_raw_value = @$student_vrm->wss->raw_value;
$hpw_raw_value = @$student_vrm->hours_per_week->raw_value;
$lpt_raw_value = @$student_vrm->last_pt_score;
$lst_raw_value = @$student_vrm->study_level;
$pt1_raw_value = @$student_vrm->initial_pt_score;

?>

<div id='tab1' class="pure-g clearfix w3-animate-opacity">
  <div class="refresh">
      <img src="<?php echo base_url();?>assets/images/reload-data.svg" id="reloadajax4">
  </div>
  <div class="box-capsule m-t-20 margin-auto font-14" style="background: #e1662b !important;">
      <span>Data Displayed Based On The Last 8 Weeks Period</span>
  </div>
  <?php if ($student_vrm_json){ ?>
      <ul class="coaching-info margin-auto padding-l-0 padding-t-10">
          <li class="coaching-info-box margin-auto clearfix">
              <div class="coaching-box-left text-cl-white">
                  <span>Last PT</span>
              </div>
              <div class="coaching-box-right bg-white">
                  <div><?php echo $lpt_raw_value;?></div>
              </div>
          </li>
          <li class="coaching-info-box margin-auto clearfix">
              <div class="coaching-box-left text-cl-white">
                  <span>Hours/Week</span>
              </div>
              <div class="coaching-box-right bg-white">
                  <div><?php echo $hpw_raw_value;?></div>
              </div>
          </li>
          <li class="coaching-info-box margin-auto clearfix">
              <div class="coaching-box-left text-cl-white">
                  <span>Level Study</span>
              </div>
              <div class="coaching-box-right bg-white">
                  <div><?php echo $lst_raw_value;?></div>
              </div>
          </li>
          <li class="coaching-info-box margin-auto clearfix">
              <div class="coaching-box-left text-cl-white">
                  <span>PT 1</span>
              </div>
              <div class="coaching-box-right bg-white">
                  <div><?php echo $pt1_raw_value;?></div>
              </div>
          </li>
      </ul>

      <div class="spdr-graph">
          <div id="chart-area" class="radar-ainner font-12">
              <div class="hexagonal height-0 prelative">
                  <div class="hexagonBlue position-absolute"></div>
                  <div class="hexagonGreen position-absolute"></div>
                  <div class="hexagonYellow position-absolute"></div>
                  <div class="hexagonRed position-absolute"></div>
              </div>
              <canvas id="bar" class="radar" style="width: 200%;"></canvas>
          </div>
      </div>
  <?php
  } else{
    if($student_vrm->cert_studying != "Unknown"){
  ?>
      Student has not connected DynEd Pro ID
  <?php }else{ echo "Student is a non certification student"; } } ?>
</div>

<div id='tab2' class="w3-animate-opacity"><?php if ($module_extract){ ?>
    <div class="coaching-info w3-animate-opacity">
        <div class="coaching-info-box display-inline-block clearfix">
            <div class="coaching-box-left">
                <span><b>WSS</b></span>
            </div>
            <div class="coaching-box-right">
                <div><b><?php echo $wss_raw_value;?></b></div>
            </div>
        </div>
        <div class="coaching-info-box display-inline-block width180 clearfix">
            <div class="coaching-box-left padding-l-10">
                <span><b>Certification Plan</b></span>
            </div>
            <div class="coaching-box-right">
                <div><b><?php echo @$student_vrm->cert_studying ?></b></div>
            </div>
        </div>
    </div>
    <div class="tabs-two tabs2 padding-t-20 padding-b-5 clearfix w3-animate-opacity">
    <?php
    $nde = @$allmodule['nde'];
    $fe  = @$allmodule['fe'];
    $fib = @$allmodule['fib'];
    $tls = @$allmodule['tls'];
    $dlg = @$allmodule['dlg'];
    $dbe = @$allmodule['dbe'];
    $als = @$allmodule['als'];
    $efs = @$allmodule['efs'];
    $rfs = @$allmodule['rfs'];
    $ebn = @$allmodule['ebn'];

    if(@$nde){
      $ndecheck = implode($nde);
    }else{ $ndecheck = "";}

    if(@$fe){
      $fecheck = implode($fe);
    }else{ $fecheck = "";}

    if(@$fib){
      $fibcheck = implode($fib);
    }else{ $fibcheck = "";}

    if(@$tls){
      $tlscheck = implode($tls);
    }else{ $tlscheck = "";}

    if(@$dlg){
      $dlgcheck = implode($dlg);
    }else{ $dlgcheck = "";}

    if(@$dbe){
      $dbecheck = implode($dbe);
    }else{ $dbecheck = "";}

    if(@$als){
      $alscheck = implode($als);
    }else{ $alscheck = "";}

    if(@$efs){
      $efscheck = implode($efs);
    }else{ $efscheck = "";}

    if(@$rfs){
      $rfscheck = implode($rfs);
    }else{ $rfscheck = "";}

    if(@$ebn){
      $ebncheck = implode($ebn);
    }else{ $ebncheck = "";}


    // $ebncheck = implode($ebn);
    // $num = 6;
    // $qwert = array_slice($rfs, 0, 20);
    // echo "<pre>";
    // print_r($ebn);
    // exit();
    ?>
        <?php if($fecheck != NULL) {?>
        <a href='#tabs-content1' class="square-tabs w3-animate-opacity">
            <h5><?php echo @$fe['titleFe']; ?></h5>
        </a>
        <?php } else{}
        if($ndecheck != NULL) {?>
        <a href='#tabs-content2' class="square-tabs w3-animate-opacity">
            <h5"><?php echo @$nde['titleNde']; ?></h5>
        </a>
        <?php } else{}
        if($efscheck != NULL) {?>
        <a href='#tabs-content3' class="square-tabs w3-animate-opacity">
            <h5"><?php echo @$efs['titleEfs']; ?></h5>
        </a>
        <?php } else{}
        if($rfscheck != NULL) {?>
        <a href='#tabs-content4' class="square-tabs w3-animate-opacity">
            <h5"><?php echo @$rfs['titleRfs']; ?></h5>
        </a>
        <?php } else{}
        if($dbecheck != NULL) {?>
        <a href='#tabs-content5' class="square-tabs w3-animate-opacity">
            <h5><?php echo @$dbe['titleDbe']; ?></h5>
        </a>
        <?php } else{}
        if($tlscheck != NULL) {?>
        <a href='#tabs-content6' class="square-tabs w3-animate-opacity">
            <h5><?php echo @$tls['titleTls']; ?></h5>
        </a>
        <?php } else{}
        if($ebncheck != NULL) {?>
        <a href='#tabs-content7' class="square-tabs w3-animate-opacity">
            <h5"><?php echo @$ebn['titleEbn']; ?></h5>
        </a>
        <?php } else{}
        if($fibcheck != NULL) {?>
        <a href='#tabs-content8' class="square-tabs w3-animate-opacity">
            <h5"><?php echo @$fib['titleFib']; ?></h5>
        </a>
        <?php } else{}
        if($alscheck != NULL) {?>
        <a href='#tabs-content9' class="square-tabs w3-animate-opacity">
            <h5><?php echo @$als['titleAls']; ?></h5>
        </a>
        <?php } else{}
        if($dlgcheck != NULL) {?>
        <a href='#tabs-content10' class="square-tabs w3-animate-opacity">
            <h5><?php echo @$dlg['titleDlg']; ?></h5>
        </a>
        <?php } else{}?>
    </div>

    <?php if($fecheck != NULL) {?>
    <div id="tabs-content1" class="tabs-c clearfix square-tabs">
        <?php if(@$fe['fe1'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
             <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$fe['pcfe1']; ?>%</h5>
             <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$fe['fe1']; ?></h5>
        </div>
        <?php }else{ } if(@$fe['fe2'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$fe['pcfe2']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$fe['fe2']; ?></h5>
        </div>
        <?php }else{ } if(@$fe['fe4'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$fe['pcfe3']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$fe['fe3']; ?></h5>

        </div>
        <?php }else{ } if(@$fe['fe4'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$fe['pcfe4']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$fe['fe4']; ?></h5>

        </div>
    <?php }else{ }?>
    </div>
    <?php } else {} if($ndecheck != NULL) { ?>
    <div id="tabs-content2" class="tabs-c clearfix">
        <?php if(@$nde['nde1'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$nde['pcnde1']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$nde['nde1']; ?></h5>

        </div>
        <?php }else{ } if(@$nde['nde2'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$nde['pcnde2']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$nde['nde2']; ?></h5>

        </div>
        <?php }else{ } if(@$nde['nde3'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$nde['pcnde3']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$nde['nde3']; ?></h5>

        </div>
       <?php }else{ } if(@$nde['nde4'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$nde['pcnde4']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$nde['nde4']; ?></h5>

        </div>
        <?php }else{ } if(@$nde['nde5'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$nde['pcnde5']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$nde['nde5']; ?></h5>

        </div>
        <?php }else{ } if(@$nde['nde6'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$nde['pcnde6']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$nde['nde6']; ?></h5>

        </div>
        <?php }else{ } if(@$nde['nde7'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$nde['pcnde7']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$nde['nde7']; ?></h5>

        </div>
        <?php }else{ } if(@$nde['nde8'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$nde['pcnde8']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$nde['nde8']; ?></h5>

        </div>
        <?php }else{ }?>
    </div>
    <?php } else {} if($efscheck != NULL) { ?>
    <div id="tabs-content3" class="tabs-c clearfix">
        <?php if(@$efs['efs1'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs1']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs1']; ?></h5>

        </div>
        <?php }else{ } if(@$efs['efs2'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs2']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs2']; ?></h5>

        </div>
        <?php }else{ } if(@$efs['efs3'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs3']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs3']; ?></h5>

        </div>
       <?php }else{ } if(@$efs['efs4'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs4']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs4']; ?></h5>

        </div>
        <?php }else{ } if(@$efs['efs5'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs5']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs5']; ?></h5>

        </div>
        <?php }else{ } if(@$efs['efs6'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs6']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs6']; ?></h5>

        </div>
        <?php }else{ } if(@$efs['efs7'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs7']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs7']; ?></h5>

        </div>
        <?php }else{ } if(@$efs['efs8'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs8']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs8']; ?></h5>

        </div>
        <?php }else{ }?>

        <?php if(@$efs['efs9'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs9']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs9']; ?></h5>

        </div>
        <?php }else{ } if(@$efs['efs10'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcef10']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs10']; ?></h5>

        </div>
        <?php }else{ } if(@$efs['efs11'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs11']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs11']; ?></h5>
        </div>
       <?php }else{ } if(@$efs['efs12'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs12']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs12']; ?></h5>

        </div>
        <?php }else{ } if(@$efs['efs13'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs13']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs13']; ?></h5>

        </div>
        <?php }else{ } if(@$efs['efs14'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs14']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs14']; ?></h5>

        </div>
        <?php }else{ } if(@$efs['efs15'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs15']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs15']; ?></h5>

        </div>
        <?php }else{ } if(@$efs['efs16'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs16']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs16']; ?></h5>

        </div>
        <?php }else{ } if(@$efs['efs17'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs17']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs17']; ?></h5>

        </div>
        <?php }else{ } if(@$efs['efs18'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs18']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs18']; ?></h5>

        </div>
        <?php }else{ } if(@$efs['efs19'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs19']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs19']; ?></h5>

        </div>
        <?php }else{ } if(@$efs['efs20'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$efs['pcefs20']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$efs['efs20']; ?></h5>

        </div>
        <?php }else{ }?>
    </div>
    <?php } else {} if($rfscheck != NULL) { ?>
    <div id="tabs-content4" class="tabs-c clearfix">
        <?php if(@$rfs['rfs1'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs1']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs1']; ?></h5>

        </div>
        <?php }else{ } if(@$rfs['rfs2'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs2']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs2']; ?></h5>

        </div>
        <?php }else{ } if(@$rfs['rfs3'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs3']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs3']; ?></h5>

        </div>
       <?php }else{ } if(@$rfs['rfs4'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs4']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs4']; ?></h5>

        </div>
        <?php }else{ } if(@$rfs['rfs5'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs5']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs5']; ?></h5>

        </div>
        <?php }else{ } if(@$rfs['rfs6'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs6']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs6']; ?></h5>

        </div>
        <?php }else{ } if(@$rfs['rfs7'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs7']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs7']; ?></h5>

        </div>
        <?php }else{ } if(@$rfs['rfs8'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs8']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs8']; ?></h5>

        </div>
        <?php }else{ }?>

        <?php if(@$rfs['rfs9'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs9']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs9']; ?></h5>

        </div>
        <?php }else{ } if(@$rfs['rfs10'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcef10']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs10']; ?></h5>

        </div>
        <?php }else{ } if(@$rfs['rfs11'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs11']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs11']; ?></h5>

        </div>
       <?php }else{ } if(@$rfs['rfs12'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs12']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs12']; ?></h5>

        </div>
        <?php }else{ } if(@$rfs['rfs13'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs13']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs13']; ?></h5>

        </div>
        <?php }else{ } if(@$rfs['rfs14'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs14']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs14']; ?></h5>

        </div>
        <?php }else{ } if(@$rfs['rfs15'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs15']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs15']; ?></h5>

        </div>
        <?php }else{ } if(@$rfs['rfs16'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs16']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs16']; ?></h5>

        </div>
        <?php }else{ } if(@$rfs['rfs17'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs17']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs17']; ?></h5>

        </div>
        <?php }else{ } if(@$rfs['rfs18'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs18']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs18']; ?></h5>

        </div>
        <?php }else{ } if(@$rfs['rfs19'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs19']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs19']; ?></h5>

        </div>
        <?php }else{ } if(@$rfs['rfs20'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$rfs['pcrfs20']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$rfs['rfs20']; ?></h5>

        </div>
        <?php }else{ }?>
    </div>
    <?php } else {} if($dbecheck != NULL) { ?>
    <div id="tabs-content5" class="tabs-c clearfix">
        <?php if(@$dbe['dbe1'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dbe['pcdbe1']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dbe['dbe1']; ?></h5>

        </div>
        <?php }else{ } if(@$dbe['dbe2'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dbe['pcdbe2']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dbe['dbe2']; ?></h5>

        </div>
        <?php }else{ } if(@$dbe['dbe3'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dbe['pcdbe3']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dbe['dbe3']; ?></h5>

        </div>
       <?php }else{ } if(@$dbe['dbe4'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dbe['pcdbe4']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dbe['dbe4']; ?></h5>

        </div>
        <?php }else{ } if(@$dbe['dbe5'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dbe['pcdbe5']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dbe['dbe5']; ?></h5>

        </div>
        <?php }else{ } if(@$dbe['dbe6'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dbe['pcdbe6']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dbe['dbe6']; ?></h5>

        </div>
        <?php }else{ }?>
    </div>
    <?php } else {} if($tlscheck != NULL) { ?>
    <div id="tabs-content6" class="tabs-c clearfix">
        <?php if(@$tls['tls1'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls1']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls1']; ?></h5>

        </div>
        <?php }else{ } if(@$tls['tls2'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls2']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls2']; ?></h5>

        </div>
        <?php }else{ } if(@$tls['tls3'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls3']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls3']; ?></h5>

        </div>
       <?php }else{ } if(@$tls['tls4'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls4']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls4']; ?></h5>

        </div>
        <?php }else{ } if(@$tls['tls5'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls5']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls5']; ?></h5>

        </div>
        <?php }else{ } if(@$tls['tls6'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls6']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls6']; ?></h5>

        </div>
        <?php }else{ } if(@$tls['tls7'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls7']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls7']; ?></h5>

        </div>
        <?php }else{ } if(@$tls['tls8'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls8']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls8']; ?></h5>

        </div>
        <?php }else{ } if(@$tls['tls9'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls9']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls9']; ?></h5>

        </div>
        <?php }else{ } if(@$tls['tls10'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$tls['pctls10']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$tls['tls10']; ?></h5>

        </div>
        <?php }else{ } ?>
    </div>
    <?php } else {} if($ebncheck != NULL) { ?>
    <div id="tabs-content7" class="tabs-c clearfix">
        <?php if(@$ebn['ebn1'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$ebn['pcebn1']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$ebn['ebn1']; ?></h5>

        </div>
        <?php }else{ } if(@$ebn['ebn2'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$ebn['pcebn2']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$ebn['ebn2']; ?></h5>

        </div>
        <?php }else{ } if(@$ebn['ebn3'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$ebn['pcebn3']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$ebn['ebn3']; ?></h5>

        </div>
       <?php }else{ } if(@$ebn['ebn4'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$ebn['pcebn4']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$ebn['ebn4']; ?></h5>

        </div>
        <?php }else{ } if(@$ebn['ebn5'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$ebn['pcebn5']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$ebn['ebn5']; ?></h5>

        </div>
        <?php }else{ } if(@$ebn['ebn6'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$ebn['pcebn6']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$ebn['ebn6']; ?></h5>

        </div>
        <?php }else{ } if(@$ebn['ebn7'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$ebn['pcebn7']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$ebn['ebn7']; ?></h5>

        </div>
        <?php }else{ } ?>
    </div>
    <?php } else {} if($fibcheck != NULL) { ?>
    <div id="tabs-content8" class="tabs-c clearfix">
        <?php if(@$fib['fib1'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$fib['pcfib1']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$fib['fib1']; ?></h5>

        </div>
        <?php }else{ } if(@$fib['fib2'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$fib['pcfib2']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$fib['fib2']; ?></h5>

        </div>
        <?php }else{ } if(@$fib['fib3'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$fib['pcfib3']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$fib['fib3']; ?></h5>

        </div>
       <?php }else{ } if(@$fib['fib4'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$fib['pcfib4']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$fib['fib4']; ?></h5>

        </div>
        <?php }else{ } ?>
    </div>
    <?php } else {} if($alscheck != NULL) { ?>
    <div id="tabs-content9" class="tabs-c clearfix">
        <?php if(@$als['als1'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals1']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als1']; ?></h5>

        </div>
        <?php }else{ } if(@$als['als2'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals2']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als2']; ?></h5>

        </div>
        <?php }else{ } if(@$als['als3'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals3']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als3']; ?></h5>

        </div>
       <?php }else{ } if(@$als['als4'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals4']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als4']; ?></h5>

        </div>
        <?php }else{ } if(@$als['als5'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals5']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als5']; ?></h5>

        </div>
        <?php }else{ } if(@$als['als6'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals6']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als6']; ?></h5>

        </div>
        <?php }else{ } if(@$als['als7'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals7']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als7']; ?></h5>

        </div>
        <?php }else{ } if(@$als['als8'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals8']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als8']; ?></h5>

        </div>
        <?php }else{ }?>

        <?php if(@$als['als9'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$als['pcals9']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$als['als9']; ?></h5>

        </div>
        <?php }else{ }?>
    </div>
    <?php } else {} if($dlgcheck != NULL) { ?>
    <div id="tabs-content10" class="tabs-c clearfix">
        <?php if(@$dlg['dlg1'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dlg['pcdlg1']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dlg['dlg1']; ?></h5>

        </div>
        <?php }else{ } if(@$dlg['dlg2'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dlg['pcdlg2']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dlg['dlg2']; ?></h5>

        </div>
        <?php }else{ } if(@$dlg['dlg3'] != null){ ?>
        <div class="square-tabs w3-animate-opacity bg-forthblue">
            <h5 class="m-b-5 font-20 text-cl-white"><?php echo @$dlg['pcdlg3']; ?>%</h5>
            <h5 class="margin0 padding-b-5 font-12 text-cl-white"><?php echo @$dlg['dlg3']; ?></h5>

        </div>
       <?php }else{ } ?>
    </div>
    <?php } else {}?>
<?php } else{ if($student_vrm->cert_studying != "Unknown"){ ?>
  <center>Student has not done any study yet</center>
<?php  }else{ ?>
  <center>Student is a non certification student</center>
<?php } } ?>
</div>



<script type="text/javascript" src="<?php echo base_url();?>assets/js/Chart.Core.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/Chart.Radar.js"></script>
<script>
// console.log('Faizal Cuk');
//RADAR

$('[data-tooltip]:after').css({'width':'115px'});

// var student_vrm = {"cert_level_completion": {"A1": 24, "A2": 10, "B1": 25, "B2": 56, "C1": 60, "C2": 33}, "cert_plan": 2, "headphone": {"percent_to_goal": 45, "raw_value": 213}, "hours_per_week": {"percent_to_goal": null, "raw_value": 0.7}, "initial_pt_score": 2.0, "last_pt_score": 2.0, "mic": {"percent_to_goal": 80, "raw_value": 397}, "mt": {"percent_to_goal": 60, "raw_value": 48}, "repeats": {"percent_to_goal": 102, "raw_value": 486}, "sr": {"percent_to_goal": 98, "raw_value": 73}, "study_level": 2.2, "wss": {"percent_to_goal": 87, "raw_value": 1.9}};
var student_vrm = <?php echo $student_vrm_json ?>;


function Value(value, metadata){
    this.value= value;
    this.metadata = metadata;
}

Value.prototype.toString = function(){
    return this.value;
}

var wss = new Value(student_vrm.wss.percent_to_goal, {
    tooltipx : student_vrm.wss.raw_value + ' | Weighted Study Score'
})

var repeat = new Value(student_vrm.repeats.percent_to_goal, {
    tooltipx : student_vrm.repeats.raw_value + ' | Repeat'
})

var mic = new Value(student_vrm.mic.percent_to_goal, {
    tooltipx : student_vrm.mic.raw_value + ' | Speaking (Microphone)'
})

var headphone = new Value(student_vrm.headphone.percent_to_goal, {
    tooltipx : student_vrm.headphone.raw_value + ' | Review (Headphone)'
})
var sr = new Value(student_vrm.sr.percent_to_goal, {
    tooltipx : student_vrm.sr.raw_value + ' | Speech Recognition'
})

var mt = new Value(student_vrm.mt.percent_to_goal, {
    tooltipx : student_vrm.mt.raw_value + ' | Mastery Test'
})

var valueData = function(point){
    return point.value.metadata.tooltipx;
}

$('.last-pt').append('<div>'+student_vrm.last_pt_score+'</div>');
$('.sl').append('<div>'+student_vrm.study_level+'</div>');
$('.pt').append('<div>'+student_vrm.initial_pt_score+'</div>');
$('.hw').append('<div>'+student_vrm.hours_per_week.raw_value+'</div>');

var helpers = Chart.helpers;
var canvas = document.getElementById('bar');

var data = {
    pointLabelFontFamily: "webFont",
    labels: ["WSS", "\ue031", "\ue02f", "\ue030", '\ue02e', "x MT"],
    datasets: [

        {
            label: "Progress",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: [wss, repeat, mic, headphone, sr, mt]
        }
    ]
};

if($(document).width() < 490){
    var bar = new Chart(canvas.getContext('2d')).Radar(data, {

        tooltipTemplate : valueData,
        legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        responsive: true,
        pointLabelFontFamily : '"webFont"',
        pointLabelFontSize : 18,
        pointLabelFontColor : '#666',
        pointotRadius : 3,
        pointDotStrokeWidth : 1,
        pointHitDetectionRadius : 25,
        datasetStroke : true,
        datasetStrokeWidth : 2,
        datasetFill : true,
        scaleFontFamily: "'webFont'",
        pointDot:true,
        showtooltipx: true,
        scaleOverride: true,
        scaleSteps: 6,
        scaleEndValue: 110,
        scaleStepWidth: 28,
        scaleStartValue: 0,
        scaleLineColor : "#ededed",

    });
}
else {
    var bar = new Chart(canvas.getContext('2d')).Radar(data, {

        tooltipTemplate : valueData,
        legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        responsive: true,
        pointLabelFontFamily : '"webFont"',
        pointLabelFontSize : 14,
        pointLabelFontColor : '#666',
        pointotRadius : 3,
        pointDotStrokeWidth : 1,
        pointHitDetectionRadius : 25,
        datasetStroke : true,
        datasetStrokeWidth : 2,
        datasetFill : true,
        scaleFontFamily: "'webFont'",
        pointDot:true,
        showtooltipx: true,
        scaleOverride: true,
        scaleSteps: 6,
        scaleEndValue: 110,
        scaleStepWidth: 28,
        scaleStartValue: 0,
        scaleLineColor : "#ededed",
    });
}

var legendHolder = document.createElement('div');
legendHolder.innerHTML = bar.generateLegend();

document.getElementById('legend').appendChild(legendHolder.firstChild);
</script>

</body>
</html>
