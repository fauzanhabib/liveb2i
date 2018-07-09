    <style>
        @media screen and (max-width: 425px) {
            #bar {
                width: auto!important;
                height: 195px!important;
            }
        }

        @media screen and (max-width: 414px) {
            .radar {
                background-size: 33.5% auto!important;
            }
        }

        @media screen and (max-width: 375px) {
            .radar {
                background-size: 33% auto!important;
            }
        }
    </style>

    <div class="heading text-cl-primary border-b-1 padding15">

        <h2 class="margin0">Student Progress Report</h2>

    </div>

    <div class="box clearfix">
        <div class="content">
<!--             <div class="box-capsule m-t-20 margin-auto font-14" style="background: #e1662b !important;">
                <span>Data Displayed Based On The Last 8 Weeks Period</span>
            </div> -->
            <div class="box pure-g">
                <div class="pure-u-1 pure-u-sm-24-24 pure-u-md-12-24">
                    <div class="box-capsule m-t-20 margin-auto font-14 width190">
                        <span>Study Level</span>
                    </div>
                    <div class="text-center">
                        <div class="coaching-info-big m-tb-0 padding-l-0 padding-t-25">
                            <div class="coaching-info-box-big margin-auto ">
                                <div class="coaching-box-left-big">
                                    <span>Last PT</span>
                                </div>
                                <div class="coaching-box-right-big">
                                    <div class="last-pt"></div>
                                </div>
                            </div>
                            <div class="coaching-info-box-big margin-auto ">
                                <div class="coaching-box-left-big">
                                    <span>Hours/Week</span>
                                </div>
                                <div class="coaching-box-right-big">
                                    <div class="hw"></div>
                                </div>
                            </div>
                            <div class="coaching-info-box-big margin-auto ">
                                <div class="coaching-box-left-big">
                                    <span>WSS</span>
                                </div>
                                <div class="coaching-box-right-big">
                                    <div class="sl"></div>
                                </div>
                            </div>
                            <div class="coaching-info-box-big margin-auto ">
                                <div class="coaching-box-left-big">
                                    <span>Initial Placement Level</span>
                                </div>
                                <div class="coaching-box-right-big">
                                    <div class="pt"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="spdr-graph rdr-2">
                        <div id="chart-area" class="radar-ainner font-12">
                            <div class="hexagonal height-0 prelative">
                                <div class="hexagonBlue stud-dash-hexa position-absolute"></div>
                                <div class="hexagonGreen stud-dash-hexa position-absolute"></div>
                                <div class="hexagonYellow stud-dash-hexa position-absolute"></div>
                                <div class="hexagonRed stud-dash-hexa position-absolute"></div>
                            </div>


                            <canvas id="bar" class="radar" style="width: 100%"></canvas>

                        </div>


                    </div> -->
                    <div class="box-capsule m-t-20 margin-auto font-14 width190">
                        <span>Spider Graph For Last 8 Weeks</span>
                    </div>
                    <div class="margin-auto studashboard--spdr" style="display:flex;">
                        <div class="spdr-graph rdr-2 spdr-resp">
                            <div id="chart-area" class="radar-ainner font-12">
                                <!-- <div class="hexagonal height-0 prelative">
                                    <div class="hexagonBlue stud-dash-hexa position-absolute"></div>
                                    <div class="hexagonGreen stud-dash-hexa position-absolute"></div>
                                    <div class="hexagonYellow stud-dash-hexa position-absolute"></div>
                                    <div class="hexagonRed stud-dash-hexa position-absolute"></div>
                                </div> -->
                                <canvas id="bar" class="radar" style="width: 480px;
                                background-image: url(<?php echo base_url(); ?>assets/img/diagonal_chart.png);
                                height: 240px;
                                background-repeat: no-repeat, repeat;
                                background-size: 35% auto;
                                background-position: center;">
                                </canvas>
                            </div>
                        </div>
                        <?php
                        $pc_mt = @$student_vrm2->mt->percent_to_goal;
                        $pc_rp = @$student_vrm2->repeats->percent_to_goal;
                        $pc_hp = @$student_vrm2->headphone->percent_to_goal;
                        $pc_sp = @$student_vrm2->mic->percent_to_goal;
                        $pc_sr = @$student_vrm2->sr->percent_to_goal;
                        $pc_wss = @$student_vrm2->wss->percent_to_goal;

                        $val_mt = @$student_vrm2->mt->raw_value;
                        $val_rp = @$student_vrm2->repeats->raw_value;
                        $val_hp = @$student_vrm2->headphone->raw_value;
                        $val_sp = @$student_vrm2->mic->raw_value;
                        $val_sr = @$student_vrm2->sr->raw_value;
                        $val_wss = @$student_vrm2->wss->raw_value;
                        // echo "<pre>";print_r($student_vrm2);exit();
                        ?>
                        <div class="othr-graph hide">
                            <ul>
                                <?php if($pc_mt >= 0 && $pc_mt <= 50){?>
                                <li class="border-l-red">
                                <?php } else if($pc_mt >50  && $pc_mt <= 100){?>
                                <li class="border-l-yellow">
                                <?php } else if($pc_mt > 100){?>
                                <li class="border-l-green">
                                <?php } ?>
                                Mastery Test: <em class="text-cl-secondary font-semi-bold border-b-1-sec"><?php echo $val_mt; ?></em>
                                </li>

                                <?php if($pc_rp >= 0 && $pc_rp <= 50){?>
                                <li class="border-l-red">
                                <?php } else if($pc_rp >50  && $pc_rp <= 100){?>
                                <li class="border-l-yellow">
                                <?php } else if($pc_rp > 100){?>
                                <li class="border-l-green">
                                <?php } ?>
                                    Repeat: <em class="text-cl-secondary font-semi-bold border-b-1-sec"><?php echo $val_rp; ?></em>
                                </li>

                                <?php if($pc_hp >= 0 && $pc_hp <= 50){?>
                                <li class="border-l-red">
                                <?php } else if($pc_hp >50  && $pc_hp <= 100){?>
                                <li class="border-l-yellow">
                                <?php } else if($pc_hp > 100){?>
                                <li class="border-l-green">
                                <?php } ?>
                                Review (Headphone): <em class="text-cl-secondary font-semi-bold border-b-1-sec"><?php echo $val_hp; ?></em>
                                </li>

                                <?php if($pc_sp >= 0 && $pc_sp <= 50){?>
                                <li class="border-l-red">
                                <?php } else if($pc_sp >50  && $pc_sp <= 100){?>
                                <li class="border-l-yellow">
                                <?php } else if($pc_sp > 100){?>
                                <li class="border-l-green">
                                <?php } ?>
                                Speaking (Microphone): <em class="text-cl-secondary font-semi-bold border-b-1-sec"><?php echo $val_sp; ?></em>
                                </li>

                                <?php if($pc_sr >= 0 && $pc_sr <= 50){?>
                                <li class="border-l-red">
                                <?php } else if($pc_sr >50  && $pc_sr <= 100){?>
                                <li class="border-l-yellow">
                                <?php } else if($pc_sr > 100){?>
                                <li class="border-l-green">
                                <?php } ?>
                                Speech Recognition: <em class="text-cl-secondary font-semi-bold border-b-1-sec"><?php echo $val_sr; ?></em>
                                </li>

                                <?php if($pc_wss >= 0 && $pc_wss <= 50){?>
                                <li class="border-l-red">
                                <?php } else if($pc_wss >50  && $pc_wss <= 100){?>
                                <li class="border-l-yellow">
                                <?php } else if($pc_wss > 100){?>
                                <li class="border-l-green">
                                <?php } ?>
                                Weighted Study Score: <em class="text-cl-secondary font-semi-bold border-b-1-sec"><?php echo $val_wss; ?></em>
                                </li>
                            </ul>
                        </div>


                    </div>

                </div>
                <?php
               //      echo "<pre>";
               // print_r($student_vrm2->cert_level_completion);

                $cert_level = @$student_vrm2->cert_level_completion;
                // echo $cert_level->A1;
                // exit();

                ?>

                <!-- Check non certif user -->

                <div class="cert__plan pure-u-md-12-24" style="z-index:3">

                <div class="box-capsule m-t-20 margin-auto font-14 width190">
                    <span>Certificate Plan</span>
                </div>

                <?php if(@$student_vrm2->cert_studying != "Unknown" && @$f_completion == 'yes'){ ?>
                <div class="tabs1 cert_plan padding-t-20 text-center">
                    <a href="#content-a1" id="A1" class="block-rm-data progress-value square-tabs-2 bg-white-fff">
                        <h5 class="m-b-5 m-t-0 font-semi-bold">A1</h5>
                        <div class="block ac-blue a1">
                            <span class="progress-value"><i class="val"><?php echo @$cert_level->A1; ?>%</i></span>
                            <progress value="<?php echo @$cert_level->A1; ?>" max="100"></progress>
                        </div>
                    </a>
                    <a href="#content-b1" id="A2" class="block-rm-data progress-value square-tabs-2 bg-white-fff">
                        <h5 class="m-b-5 m-t-0 font-semi-bold">A2</h5>
                        <div class="block ac-blue a1">
                            <span class="progress-value"><i class="val"><?php echo @$cert_level->A2; ?>%</i></span>
                            <progress value="<?php echo @$cert_level->A2; ?>" max="100"></progress>
                        </div>
                    </a>
                    <a href="#content-c1" id="B1" class="block-rm-data progress-value square-tabs-2 bg-white-fff">
                        <h5 class="m-b-5 m-t-0 font-semi-bold">B1</h5>
                        <div class="block ac-blue a1">
                            <span class="progress-value"><i class="val"><?php echo @$cert_level->B1; ?>%</i></span>
                            <progress value="<?php echo @$cert_level->B1; ?>" max="100"></progress>
                        </div>
                    </a>
                    <a href="#content-a2" id="B2" class="block-rm-data progress-value square-tabs-2 bg-white-fff">
                        <h5 class="m-b-5 m-t-0 font-semi-bold">B2</h5>
                        <div class="block ac-blue a1">
                            <span class="progress-value"><i class="val"><?php echo @$cert_level->B2; ?>%</i></span>
                            <progress value="<?php echo @$cert_level->B2; ?>" max="100"></progress>
                        </div>
                    </a>
                    <a href="#content-b2" id="C1" class="block-rm-data progress-value square-tabs-2 bg-white-fff">
                        <h5 class="m-b-5 m-t-0 font-semi-bold">C1</h5>
                        <div class="block ac-blue a1">
                            <span class="progress-value"><i class="val"><?php echo @$cert_level->C1; ?>%</i></span>
                            <progress value="<?php echo @$cert_level->C1; ?>" max="100"></progress>
                        </div>
                    </a>
                    <a href="#content-c2" id="C2" class="block-rm-data progress-value square-tabs-2 bg-white-fff">
                        <h5 class="m-b-5 m-t-0 font-semi-bold">C2</h5>
                        <div class="block ac-blue a1">
                            <span class="progress-value"><i class="val"><?php echo @$cert_level->C2; ?>%</i></span>
                            <progress value="<?php echo @$cert_level->C2; ?>" max="100"></progress>
                        </div>
                    </a>
                </div>

                <div id="content-a1">
                <?php
                $nde1 = @$allmodule1['nde1'];
                $fe1  = @$allmodule1['fe1'];

                // echo "<pre>";
                // print_r($fe1);
                // exit();
                ?>
                    <div class="tabs-two tabs2 padding-t-20 padding-b-5 clearfix w3-animate-opacity">
                        <a href="#tabs-content1" class="square-tabs w3-animate-opacity active">
                            <h5>First English</h5>
                        </a>
                        <a href="#tabs-content2" class="square-tabs w3-animate-opacity">
                            <h5>New Dynamic English</h5>
                        </a>
                    </div>

                    <div id="tabs-content1" class="tabs-c clearfix" style="display: block;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $fe1['pcfe1']; ?>%</font><br>
                                <?php echo $fe1['fe1']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $fe1['pcfe2']; ?>%</font><br>
                                <?php echo $fe1['fe2']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $fe1['pcfe3']; ?>%</font><br>
                                <?php echo $fe1['fe3']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $fe1['pcfe4']; ?>%</font><br>
                                <?php echo $fe1['fe4']; ?></h5>
                        </div>
                    </div>
                    <div id="tabs-content2" class="tabs-c clearfix" style="display: none;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $nde1['pcnde1']; ?>%</font><br>
                                <?php echo $nde1['nde1']; ?></h5>
                        </div>
                    </div>
                </div>

                <div id="content-b1">
                <?php
                $nde2 = @$allmodule2['nde2'];
                $tls2  = @$allmodule2['tls2'];

                // echo "<pre>";
                // print_r($fe1);
                // exit();
                ?>
                    <div class="tabs-two tabs2 padding-t-20 padding-b-5 clearfix w3-animate-opacity">
                        <a href="#content-b1-lia" class="square-tabs w3-animate-opacity active">
                            <h5>New Dynamic English</h5>
                        </a>
                        <a href="#content-b1-fe" class="square-tabs w3-animate-opacity">
                            <h5>The Lost<br>Secret</h5>
                        </a>
                    </div>

                    <div id="content-b1-lia" class="tabs-c clearfix" style="display: block;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $nde2['pcnde2']; ?>%</font><br>
                                <?php echo $nde2['nde2']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $nde2['pcnde3']; ?>%</font><br>
                                <?php echo $nde2['nde3']; ?></h5>
                        </div>
                    </div>
                    <div id="content-b1-fe" class="tabs-c clearfix" style="display: none;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $tls2['pctls1']; ?>%</font><br>
                                <?php echo $tls2['tls1']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $tls2['pctls2']; ?>%</font><br>
                                <?php echo $tls2['tls2']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $tls2['pctls3']; ?>%</font><br>
                                <?php echo $tls2['tls3']; ?></h5>
                        </div>
                    </div>
                </div>

                <div id="content-c1">
                <?php
                $nde3  = @$allmodule3['nde3'];
                $dbe3  = @$allmodule3['dbe3'];
                $tls3  = @$allmodule3['tls3'];
                $ebn3  = @$allmodule3['ebn3'];

                // echo "<pre>";
                // print_r($fe1);
                // exit();
                ?>
                    <div class="tabs-two tabs2 padding-t-20 padding-b-5 clearfix w3-animate-opacity">
                        <a href="#tabs-nde3" class="square-tabs w3-animate-opacity active">
                            <h5>New Dynamic English</h5>
                        </a>
                        <a href="#tabs-dbe3" class="square-tabs w3-animate-opacity">
                            <h5>Dynamic Business English</h5>
                        </a>
                        <a href="#tabs-tls3" class="square-tabs w3-animate-opacity">
                            <h5>The Lost<br>Secret</h5>
                        </a>
                        <a href="#tabs-ebn3" class="square-tabs w3-animate-opacity">
                            <h5>English By The Numbers</h5>
                        </a>
                    </div>

                    <div id="tabs-nde3" class="tabs-c clearfix" style="display: block;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $nde3['pcnde4']; ?>%</font><br>
                                <?php echo $nde3['nde4']; ?></h5>
                        </div>
                    </div>
                    <div id="tabs-dbe3" class="tabs-c clearfix" style="display: none;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $dbe3['pcdbe1']; ?>%</font><br>
                                <?php echo $dbe3['dbe1']; ?></h5>

                        </div>
                    </div>
                    <div id="tabs-tls3" class="tabs-c clearfix" style="display: none;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $tls3['pctls4']; ?>%</font><br>
                                <?php echo $tls3['tls4']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $tls3['pctls5']; ?>%</font><br>
                                <?php echo $tls3['tls5']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $tls3['pctls6']; ?>%</font><br>
                                <?php echo $tls3['tls6']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $tls3['pctls7']; ?>%</font><br>
                                <?php echo $tls3['tls7']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $tls3['pctls8']; ?>%</font><br>
                                <?php echo $tls3['tls8']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $tls3['pctls9']; ?>%</font><br>
                                <?php echo $tls3['tls9']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $tls3['pctls10']; ?>%</font><br>
                                <?php echo $tls3['tls10']; ?></h5>
                        </div>
                    </div>
                    <div id="tabs-ebn3" class="tabs-c clearfix" style="display: none;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $ebn3['pcebn1']; ?>%</font><br>
                                <?php echo $ebn3['ebn1']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $ebn3['pcebn2']; ?>%</font><br>
                                <?php echo $ebn3['ebn2']; ?></h5>
                        </div>
                    </div>
                </div>

                <div id="content-a2">
                <?php
                $nde4  = @$allmodule4['nde4'];
                $dbe4  = @$allmodule4['dbe4'];
                $fib4  = @$allmodule4['fib4'];
                $ebn4  = @$allmodule4['ebn4'];

                // echo "<pre>";
                // print_r($fe1);
                // exit();
                ?>
                    <div class="tabs-two tabs2 padding-t-20 padding-b-5 clearfix w3-animate-opacity">
                        <a href="#content-nde4" class="square-tabs w3-animate-opacity active">
                            <h5>New Dynamic English</h5>
                        </a>
                        <a href="#content-dbe4" class="square-tabs w3-animate-opacity">
                            <h5>Dynamic Business English</h5>
                        </a>
                        <a href="#content-fib4" class="square-tabs w3-animate-opacity">
                            <h5>Functioning In Business</h5>
                        </a>
                        <a href="#content-ebn4" class="square-tabs w3-animate-opacity">
                            <h5>English By The Numbers</h5>
                        </a>
                    </div>

                    <div id="content-nde4" class="tabs-c clearfix" style="display: block;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $nde4['pcnde5']; ?>%</font><br>
                                <?php echo $nde4['nde5']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $nde4['pcnde6']; ?>%</font><br>
                                <?php echo $nde4['nde6']; ?></h5>
                        </div>
                    </div>
                    <div id="content-dbe4" class="tabs-c clearfix" style="display: none;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $dbe4['pcdbe2']; ?>%</font><br>
                                <?php echo $dbe4['dbe2']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $dbe4['pcdbe3']; ?>%</font><br>
                                <?php echo $dbe4['dbe3']; ?></h5>
                        </div>
                    </div>
                    <div id="content-fib4" class="tabs-c clearfix" style="display: none;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $fib4['pcfib1']; ?>%</font><br>
                                <?php echo $fib4['fib1']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $fib4['pcfib2']; ?>%</font><br>
                                <?php echo $fib4['fib2']; ?></h5>
                        </div>
                    </div>
                    <div id="content-ebn4" class="tabs-c clearfix" style="display: none;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $ebn4['pcebn3']; ?>%</font><br>
                                <?php echo $ebn4['ebn3']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $ebn4['pcebn4']; ?>%</font><br>
                                <?php echo $ebn4['ebn4']; ?></h5>
                        </div>
                    </div>
                </div>

                <div id="content-b2">
                <?php
                $nde5  = @$allmodule5['nde5'];
                $dbe5  = @$allmodule5['dbe5'];
                $fib5  = @$allmodule5['fib5'];
                $ebn5  = @$allmodule5['ebn5'];
                $dlg5  = @$allmodule5['dlg5'];
                $als5  = @$allmodule5['als5'];

                // echo "<pre>";
                // print_r($fe1);
                // exit();
                ?>
                    <div class="tabs-two tabs2 padding-t-20 padding-b-5 clearfix w3-animate-opacity">
                        <a href="#tabs-nde5" class="square-tabs w3-animate-opacity active">
                            <h5>New Dynamic English</h5>
                        </a>
                        <a href="#tabs-dbe5" class="square-tabs w3-animate-opacity">
                            <h5>Dynamic Business English</h5>
                        </a>
                        <a href="#tabs-fib5" class="square-tabs w3-animate-opacity">
                            <h5>Functioning In Business</h5>
                        </a>
                        <a href="#tabs-ebn5" class="square-tabs w3-animate-opacity">
                            <h5>English By The Numbers</h5>
                        </a>
                        <a href="#tabs-dlg5" class="square-tabs w3-animate-opacity">
                            <h5>Dialogue</h5>
                        </a>
                        <a href="#tabs-als5" class="square-tabs w3-animate-opacity">
                            <h5>Advanced Listening</h5>
                        </a>
                    </div>

                    <div id="tabs-nde5" class="tabs-c clearfix" style="display: block;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $nde5['pcnde7']; ?>%</font><br>
                                <?php echo $nde5['nde7']; ?></h5>
                        </div>
                    </div>
                    <div id="tabs-dbe5" class="tabs-c clearfix" style="display: none;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $dbe5['pcdbe4']; ?>%</font><br>
                                <?php echo $dbe5['dbe4']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $dbe5['pcdbe5']; ?>%</font><br>
                                <?php echo $dbe5['dbe5']; ?></h5>
                        </div>
                    </div>
                    <div id="tabs-fib5" class="tabs-c clearfix" style="display: block;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $fib5['pcfib3']; ?>%</font><br>
                                <?php echo $fib5['fib3']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $fib5['pcfib4']; ?>%</font><br>
                                <?php echo $fib5['fib4']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $fib5['pcfib5']; ?>%</font><br>
                                <?php echo $fib5['fib5']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $fib5['pcfib6']; ?>%</font><br>
                                <?php echo $fib5['fib6']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $fib5['pcfib7']; ?>%</font><br>
                                <?php echo $fib5['fib7']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $fib5['pcfib8']; ?>%</font><br>
                                <?php echo $fib5['fib8']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $fib5['pcfib9']; ?>%</font><br>
                                <?php echo $fib5['fib9']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $fib5['pcfib10']; ?>%</font><br>
                                <?php echo $fib5['fib10']; ?></h5>
                        </div>
                    </div>
                    <div id="tabs-ebn5" class="tabs-c clearfix" style="display: block;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $ebn5['pcebn5']; ?>%</font><br>
                                <?php echo $ebn5['ebn5']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $ebn5['pcebn6']; ?>%</font><br>
                                <?php echo $ebn5['ebn6']; ?></h5>
                        </div>
                    </div>
                    <div id="tabs-dlg5" class="tabs-c clearfix" style="display: block;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $dlg5['pcdlg1']; ?>%</font><br>
                                <?php echo $dlg5['dlg1']; ?></h5>
                        </div>
                    </div>
                    <div id="tabs-als5" class="tabs-c clearfix" style="display: block;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $als5['pcals1']; ?>%</font><br>
                                <?php echo $als5['als1']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $als5['pcals2']; ?>%</font><br>
                                <?php echo $als5['als2']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                            <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $als5['pcals3']; ?>%</font><br>
                                <?php echo $als5['als3']; ?></h5>
                        </div>
                    </div>
                </div>

                <div id="content-c2">
                <?php
                $nde6  = @$allmodule6['nde6'];
                $dbe6  = @$allmodule6['dbe6'];
                $ebn6  = @$allmodule6['ebn6'];
                $dlg6  = @$allmodule6['dlg6'];
                $als6  = @$allmodule6['als6'];

                // echo "<pre>";
                // print_r($fe1);
                // exit();
                ?>
                    <div class="tabs-two tabs2 padding-t-20 padding-b-5 clearfix w3-animate-opacity">
                        <a href="#content-nde6" class="square-tabs w3-animate-opacity active">
                            <h5>New Dynamic English</h5>
                        </a>
                        <a href="#content-dbe6" class="square-tabs w3-animate-opacity">
                            <h5>Dynamic Business English</h5>
                        </a>
                        <a href="#content-dlg6" class="square-tabs w3-animate-opacity">
                            <h5>Dialogue</h5>
                        </a>
                        <a href="#content-ebn6" class="square-tabs w3-animate-opacity">
                            <h5>English By The Numbers</h5>
                        </a>
                        <a href="#content-als6" class="square-tabs w3-animate-opacity">
                            <h5>Advanced Listening</h5>
                        </a>
                    </div>

                    <div id="content-nde6" class="tabs-c clearfix" style="display: block;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $nde6['pcnde8']; ?>%</font><br>
                                <?php echo $nde6['nde8']; ?></h5>
                        </div>
                    </div>
                    <div id="content-dbe6" class="tabs-c clearfix" style="display: block;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $dbe6['pcdbe6']; ?>%</font><br>
                                <?php echo $dbe6['dbe6']; ?></h5>
                        </div>
                    </div>
                    <div id="content-dlg6" class="tabs-c clearfix" style="display: block;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $dlg6['pcdlg2']; ?>%</font><br>
                                <?php echo $dlg6['dlg2']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $dlg6['pcdlg3']; ?>%</font><br>
                                <?php echo $dlg6['dlg3']; ?></h5>
                        </div>
                    </div>
                    <div id="content-ebn6" class="tabs-c clearfix" style="display: block;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $ebn6['pcebn7']; ?>%</font><br>
                                <?php echo $ebn6['ebn7']; ?></h5>
                        </div>
                    </div>
                    <div id="content-als6" class="tabs-c clearfix" style="display: block;">
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $als6['pcals4']; ?>%</font><br>
                                <?php echo $als6['als4']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $als6['pcals5']; ?>%</font><br>
                                <?php echo $als6['als5']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $als6['pcals6']; ?>%</font><br>
                                <?php echo $als6['als6']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $als6['pcals7']; ?>%</font><br>
                                <?php echo $als6['als7']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $als6['pcals8']; ?>%</font><br>
                                <?php echo $als6['als8']; ?></h5>
                        </div>
                        <div class="square-tabs w3-animate-opacity bg-forthblue">
                             <h5 class="m-b-5 text-cl-white"><font size="4.5"><?php echo $als6['pcals9']; ?>%</font><br>
                                <?php echo $als6['als9']; ?></h5>
                        </div>
                    </div>
                </div>

            </div>

          <?php }else{ ?>
            <div style="text-align: center;margin-top: 20px;">You are a non certification student</div>
          <?php } ?>
          <!-- Check non certif user -->

                <div class="padding-t-30 pure-u-1 visibility-hide">
                    <div class="box-capsule padding-tb-8 text-cl-tertiary margin-auto font-14 width190">
                        <span>Last Three Months</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>




        <script type="text/javascript">
            $(document).ready(function(){
                $(".listTop > a").prepend($("<span/>"));
                $(".listBottom > a").prepend($("<span/>"));
                $(".listTop, .listBottom").click(function(event){
                 event.stopPropagation();
                 $(this).children("ul").slideToggle();
               });
            });
        </script>



        <script src="<?php echo base_url(); ?>assets/vendor/chartjs/Chart.Core.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/chartjs/Chart.Radar.js"></script>
        <!--<script src="<?php //echo base_url(); ?>assets/js/vrm.js"></script>-->


      <script>

//TABS
$('.tab-link a').click(function(e){
    var currentValue = $(this).attr('href');

    $('.tab-link a').removeClass('active');
    $('.tab').removeClass('active');

    $(this).addClass('active');
    $(currentValue).addClass('active');

    e.preventDefault();

});

//RADAR

$('[data-tooltip]:after').css({'width':'115px'});

var student_vrm = <?php echo $student_vrm; ?>;

var get_cert_plan = student_vrm.cert_plan;
if(get_cert_plan == 1){
    $('.certificationplan').html('Academic I Plan');
} else if(get_cert_plan == 2){
    $('.certificationplan').html('Academic II Plan');
} else if(get_cert_plan == 3){
    $('.certificationplan').html('Professional Plan');
}

function Value(value, metadata){
    this.value= value;
    this.metadata = metadata;
}

Value.prototype.toString = function(){
    return this.value;
}

var wss = new Value(student_vrm.wss.percent_to_goal, {
    tooltipx : student_vrm.wss.raw_value + ' | Weighted Study Score (last 8 weeks)'
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
$('.sl').append('<div>'+student_vrm.wss.raw_value+'</div>');
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
        pointDotRadius : 3,
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
        scaleStepWidth: 20,
        scaleStartValue: 0,
        scaleEndValue: 110,
        scaleLineColor : "#ededed",

    });
}
else {
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
        scaleStepWidth: 20,
        scaleStartValue: 0,
        scaleEndValue: 110,
        scaleLineColor : "#ededed",
    });
}

var legendHolder = document.createElement('div');
legendHolder.innerHTML = bar.generateLegend();

document.getElementById('legend').appendChild(legendHolder.firstChild);

//certification

$('.no-data').hide();


function certificate(student_vrm){
    console.log(student_vrm.cert_level_completion.A2);
    $('.plan').append(cert_plan);
    $('.a1 .progress-value').append('<i class="val">'+student_vrm.cert_level_completion.A1+'%</i>');
    $('.a1 progress').val(student_vrm.cert_level_completion.A1);
    $('.a2 .progress-value').append('<i class="val">'+student_vrm.cert_level_completion.A2+'%</i>');
    $('.a2 progress').val(student_vrm.cert_level_completion.A2);
    $('.b1 .progress-value').append('<i class="val">'+student_vrm.cert_level_completion.B1+'%</i>');
    $('.b1 progress').val(student_vrm.cert_level_completion.B1);
    $('.b2 .progress-value').append('<i class="val">'+student_vrm.cert_level_completion.B2+'%</i>');
    $('.b2 progress').val(student_vrm.cert_level_completion.B2);
    $('.c1 .progress-value').append('<i class="val">'+student_vrm.cert_level_completion.C1+'%</i>');
    $('.c1 progress').val(student_vrm.cert_level_completion.C1);
    $('.c2 .progress-value').append('<i class="val">'+student_vrm.cert_level_completion.C2+'%</i>');
    $('.c2 progress').val(student_vrm.cert_level_completion.C2);
}

function certificate_2(student_vrm){
    //$('.plan').append(cert_plan);
    $('.a1p .progress-value').append('<i class="val">'+student_vrm.cert_level_completion.A1P+'%</i>');
    $('.a1p progress').val(student_vrm.cert_level_completion.A1);
    $('.a2p .progress-value').append('<i class="val">'+student_vrm.cert_level_completion.A2P+'%</i>');
    $('.a2p progress').val(student_vrm.cert_level_completion.A2P);
    $('.b1p .progress-value').append('<i class="val">'+student_vrm.cert_level_completion.B1P+'%</i>');
    $('.b1p progress').val(student_vrm.cert_level_completion.B1P);
    $('.b2p .progress-value').append('<i class="val">'+student_vrm.cert_level_completion.B2P+'%</i>');
    $('.b2p progress').val(student_vrm.cert_level_completion.B2P);
}



if (student_vrm.cert_plan == 1) {
    var cert_plan = 'ACADEMIC';
    $('.cert_plan2').remove();
    certificate(student_vrm);
}
else if (student_vrm.cert_plan == 2) {
    var cert_plan = 'ACADEMIC PLUS';
    $('.cert_plan').remove();

    certificate(student_vrm);
    certificate_2(student_vrm);
}
else if (student_vrm.cert_plan == 3) {
    var cert_plan = 'PRO';
    $('.cert_plan2').remove();
    certificate(student_vrm);
}
else if (student_vrm.cert_plan == 6) {
    var cert_plan = 'PRO EUROPE';
    $('.cert_plan2').remove();
    certificate(student_vrm);
}
else {
    $('.cert_plan').remove();
    $('.cert_plan2').remove();

    $('.no-data').show();
    $('.title').hide();
}
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
        $('div.tabs1').each(function(){
            // For each set of tabs, we want to keep track of
            // which tab is active and its associated content
            var $active, $content, $links = $(this).find('a');

            // If the location.hash matches one of the links, use that as the active tab.
            // If no match is found, use the first link as the initial active tab.
            $active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
            $name = ('<?php echo @$student_cert; ?>');
            $idName = $('#' + $name);
            console.log($name);
            // $active.addClass('active');

            $content = $($active[0].hash);

            // Hide the remaining content
            $links.not($active).each(function () {
                $(this.hash).hide();
            });

            //default active
            $idName.addClass('active');
            $idName.removeClass('bg-white-fff');
            $idName.css("background","rgba(64,245,151,0.5)");
            $content.show();
            $('#'+ $name +'h5').append(' (Current)');

            // Bind the click event handler
            $(this).on('click', 'a', function(e){
                // Make the old tab inactive.
                $active.removeClass('active');
                $content.hide();
                $idName.removeClass('active');

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
