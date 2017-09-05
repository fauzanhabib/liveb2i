<?php
if(@$student_vrm){ ?>
<div class="box">

    <div class="heading pure-g">
        <div class="pure-u-1 tab-list tab-link">
            <ul>
                <li class="current"><a href="#tab1<?php echo $student_id; ?>" class="active">Rm Data</a></li>
                <li><a href="#tab2<?php echo $student_id; ?>">Certificate Plan</a></li>
            </ul>    
        </div>
    </div>

    <div class="content">

        <div id="tab1<?php echo $student_id; ?>" class="tab active">

            <div class="pure-g">
                <div class="pure-u-12-24 prelative data-1 tooltip-bottom">
                    <div class="block-rm-data">
                        <div>
                            <div class="text">
                                <p>LAST PT</p>
                            </div>
                            <div class="block dark-blues last-pt">
                                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pure-u-12-24 prelative data-1 tooltip-bottom">
                    <div class="block-rm-data">
                        <div>
                            <div class="text">
                                <p>STUDY LEVEL</p>
                            </div>
                            <div class="block blues sl">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
            <div class="pure-g m-b-25">    

                <div class="pure-u-12-24 prelative data-1 tooltip-bottom">
                    <div class="block-rm-data">
                        <div>
                            <div class="text">
                                <p>PT 1</p>
                            </div>
                            <div class="block grey pt">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pure-u-12-24 prelative data-1 tooltip-bottom">
                    <div class="block-rm-data">
                        <div class="text">
                            <p>HOURS/WEEK</p>
                        </div>
                        <div class="block green">
                            <span class="progress-value hw"></span>
                            <progress max="100" class="val-hw"></progress>
                        </div>
                    </div>
                </div>
            </div>

            <div class="radar-outer">

                <div class="pure-g">
                    <div class="pure-u-1 pure-u-md-14-24">
                        <div id="chart-area" class="radar-ainner">
                            <canvas id="bar" class="radar" style="width: 100%;"></canvas>
                        </div>
                    </div>

                    <div class="pure-u-1 pure-u-md-8-24">
                        <div id="legend" class="radar-legend"></div>
                    </div>
                </div>

            </div>   

        </div>     

        <div id="tab2<?php echo $student_id; ?>" class="tab">
            <div class="content padding0">

                <div class="heading pure-g title">
                    <div class="pure-u-1">
                        <h3 class="h3 font-normal padding-tb-15 plan" style="color:#939393 !important;font-size:14px;"></h3>
                    </div>
                </div>

                <div class="padding15 no-data"><div class="no-result">No Data</div></div>
          
                <div class="pure-g m-b-25 cert_plan">
                    <div class="pure-u-1 pure-u-sm-8-24 pure-u-md-8-24">      
                        <div class="pure-u-1 prelative">
                            <div class="block-rm-data">
                                <div class="text">
                                    <p>A1</p>
                                </div>
                                <div class="block ac-blue a1">
                                    <span class="progress-value"></span>
                                    <progress value="65" max="100"></progress>
                                </div>
                            </div>
                        </div>
                        <div class="pure-u-1 prelative">
                            <div class="block-rm-data">
                                <div class="text">
                                    <p>A2</p>
                                </div>
                                <div class="block ac-blue a2">
                                    <span class="progress-value"></span>
                                    <progress value="65" max="100"></progress>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pure-u-1 pure-u-sm-8-24 pure-u-md-8-24">      
                        <div class="pure-u-1 prelative">
                            <div class="block-rm-data">
                                <div class="text">
                                    <p>B1</p>
                                </div>
                                <div class="block ac-blue b1">
                                    <span class="progress-value"></span>
                                    <progress value="65" max="100"></progress>
                                </div>
                            </div>
                        </div>
                        <div class="pure-u-1 prelative">
                            <div class="block-rm-data">
                                <div class="text">
                                    <p>B2</p>
                                </div>
                                <div class="block ac-blue b2">
                                    <span class="progress-value"></span>
                                    <progress value="65" max="100"></progress>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pure-u-1 pure-u-sm-8-24 pure-u-md-8-24">      
                        <div class="pure-u-1 prelative">
                            <div class="block-rm-data">
                                <div class="text">
                                    <p>C1</p>
                                </div>
                                <div class="block ac-blue c1">
                                    <span class="progress-value"></span>
                                    <progress value="65" max="100"></progress>
                                </div>
                            </div>
                        </div>
                        <div class="pure-u-1 prelative">
                            <div class="block-rm-data">
                                <div class="text">
                                    <p>C2</p>
                                </div>
                                <div class="block ac-blue c2">
                                    <span class="progress-value"></span>
                                    <progress value="65" max="100"></progress>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
                <div class="pure-g m-b-25 cert_plan2">
                    <div class="pure-u-1 pure-u-sm-8-24 pure-u-md-8-24">      
                        <div class="pure-u-1 prelative">
                            <div class="block-rm-data">
                                <div class="text">
                                    <p>A1</p>
                                </div>
                                <div class="block ac-blue a1">
                                    <span class="progress-value"></span>
                                    <progress value="65" max="100"></progress>
                                </div>
                            </div>
                        </div>
                        <div class="pure-u-1 prelative">
                            <div class="block-rm-data">
                                <div class="text">
                                    <p>A1+</p>
                                </div>
                                <div class="block ac-blue a1p">
                                    <span class="progress-value"></span>
                                    <progress value="65" max="100"></progress>
                                </div>
                            </div>
                        </div>
                        <div class="pure-u-1 prelative">
                            <div class="block-rm-data">
                                <div class="text">
                                    <p>A2</p>
                                </div>
                                <div class="block ac-blue a2">
                                    <span class="progress-value"></span>
                                    <progress value="65" max="100"></progress>
                                </div>
                            </div>
                        </div>
                        <div class="pure-u-1 prelative">
                            <div class="block-rm-data">
                                <div class="text">
                                    <p>A2+</p>
                                </div>
                                <div class="block ac-blue a2p">
                                    <span class="progress-value"></span>
                                    <progress value="65" max="100"></progress>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pure-u-1 pure-u-sm-8-24 pure-u-md-8-24">      
                        <div class="pure-u-1 prelative">
                            <div class="block-rm-data">
                                <div class="text">
                                    <p>B1</p>
                                </div>
                                <div class="block ac-blue b1">
                                    <span class="progress-value"></span>
                                    <progress value="65" max="100"></progress>
                                </div>
                            </div>
                        </div>
                        <div class="pure-u-1 prelative">
                            <div class="block-rm-data">
                                <div class="text">
                                    <p>B1+</p>
                                </div>
                                <div class="block ac-blue b1p">
                                    <span class="progress-value"></span>
                                    <progress value="65" max="100"></progress>
                                </div>
                            </div>
                        </div>
                        <div class="pure-u-1 prelative">
                            <div class="block-rm-data">
                                <div class="text">
                                    <p>B2</p>
                                </div>
                                <div class="block ac-blue b2">
                                    <span class="progress-value"></span>
                                    <progress value="65" max="100"></progress>
                                </div>
                            </div>
                        </div>
                        <div class="pure-u-1 prelative">
                            <div class="block-rm-data">
                                <div class="text">
                                    <p>B2+</p>
                                </div>
                                <div class="block ac-blue b2p">
                                    <span class="progress-value"></span>
                                    <progress value="65" max="100"></progress>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pure-u-1 pure-u-sm-8-24 pure-u-md-8-24">      
                        <div class="pure-u-1 prelative">
                            <div class="block-rm-data">
                                <div class="text">
                                    <p>C1</p>
                                </div>
                                <div class="block ac-blue c1">
                                    <span class="progress-value"></span>
                                    <progress value="65" max="100"></progress>
                                </div>
                            </div>
                        </div>
                        <div class="pure-u-1 prelative">
                            <div class="block-rm-data">
                                <div class="text">
                                    <p>C2</p>
                                </div>
                                <div class="block ac-blue c2">
                                    <span class="progress-value"></span>
                                    <progress value="65" max="100"></progress>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>

    </div>
</div>

</div>

<script src="<?php echo base_url(); ?>assets/vendor/chartjs/Chart.Core.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/chartjs/Chart.Radar.js"></script>

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
//console.log(student_vrm);

function Value(value, metadata){
    this.value= value;
    this.metadata = metadata;
}

Value.prototype.toString = function(){
    return this.value;
}

var wss = new Value(student_vrm.wss.percent_to_goal, {
    tooltipx : student_vrm.wss.raw_value
})

var repeat = new Value(student_vrm.repeats.percent_to_goal, {
    tooltipx : student_vrm.repeats.raw_value
})

var mic = new Value(student_vrm.mic.percent_to_goal, {
    tooltipx : student_vrm.mic.raw_value
})

var headphone = new Value(student_vrm.headphone.percent_to_goal, {
    tooltipx : student_vrm.headphone.raw_value
})
var sr = new Value(student_vrm.sr.percent_to_goal, {
    tooltipx : student_vrm.sr.raw_value
})

var mt = new Value(student_vrm.mt.percent_to_goal, {
    tooltipx : student_vrm.mt.raw_value
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
        scaleSteps: 4,
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
        scaleSteps: 4,
        scaleStepWidth: 28,
        scaleStartValue: 0,
        scaleLineColor : "#ededed",
    });
}

var legendHolder = document.createElement('div');
legendHolder.innerHTML = bar.generateLegend();

document.getElementById('legend').appendChild(legendHolder.firstChild);

//certification

$('.no-data').hide();


function certificate(student_vrm){
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
<?php
}
else{
    echo(@$student_fullname. ' does not connect to DynEd Pro yet.');
}
exit;
?>