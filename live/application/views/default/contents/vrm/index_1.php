<script src="<?php echo base_url('assets/vrm/echarts/dist/echarts-all.js'); ?>"></script>	
<script src="<?php echo base_url('assets/vrm/highcharts/highcharts.js'); ?>"></script>
<script src="<?php echo base_url('assets/vrm/highcharts/highcharts-more.js'); ?>"></script>
<script src="<?php echo base_url('assets/vrm/highcharts/modules/exporting.js'); ?>"></script>

<div class="row">
    <div class="col-md-12">
        <div class="col-lg-4 col-md-4 col-lg-xs-12">
            <div id="spider_diagram" style="width: 100%; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="col-lg-4 col-md-4 col-lg-xs-12">
            <div id="wss" style="width: 100%; height: 300px; margin: 0 auto"></div>
        </div>
        <div class="col-lg-4 col-md-4 col-lg-xs-12">
            <div id="daysPerWeek" style="width: 100%; height: 300px; margin: 0 auto"></div>
        </div>
        <div class="col-lg-4 col-md-4 col-lg-xs-12">
            <div id="hoursPerWeek" style="width: 100%; height: 300px; margin: 0 auto"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-lg-xs-12">
        <div id="wss-all-time" style="width: 100%; height: 300px; margin: 0 auto"></div>
    </div>
    <div class="col-lg-4 col-lg-xs-12">
        <div id="days-per-week-all-time" style="width: 100%; height: 300px; margin: 0 auto"></div>
    </div>
    <div class="col-lg-4 col-lg-xs-12">
        <div id="hours-per-week-all-time" style="width: 100%; height: 300px; margin: 0 auto"></div>
    </div>
</div>	

<script type="text/javascript">

    var this_week = <?php echo $this_week; ?>;

    // Default settings for Spedometer Chart
    var wss_this_week = parseInt(this_week.totalAverageWeightedStudyScore);
    var days_per_week = parseInt(this_week.totalStudyDays);
    var hours_per_week = parseInt(this_week.totalStudyTime);

    var credits = {enabled: false};
    var exporting = {enabled: false};
    var pane = {startAngle: -150, endAngle: 150, background: [{backgroundColor: {linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1}, stops: [[0, '#FFF'], [1, '#333']]}, borderWidth: 0, outerRadius: '109%'}, {backgroundColor: {linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1}, stops: [[0, '#333'], [1, '#FFF']]}, borderWidth: 1, outerRadius: '107%'}, {}, {backgroundColor: '#DDD', borderWidth: 0, outerRadius: '105%', innerRadius: '103%'}]};


    // Default settings for Line Chart
    var all_time_wss = <?php echo json_encode($all_time_wss); ?>;
    var all_time_days = <?php echo json_encode($all_time_days); ?>;
    var all_time_hours = <?php echo json_encode($all_time_hours); ?>;

    var credits = {enabled: false};
    var exporting = {enabled: false};
    var chart = {type: 'spline', zoomType: 'x'};
    var xAxis = {type: 'datetime', title: {text: null}, maxZoom: 14 * 24 * 3600000};
    var legend = {enabled: false};
    var plotOptions = {area: {fillColor: {linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1}, stops: [[0, Highcharts.getOptions().colors[0]], [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]]}, lineWidth: 1, marker: {enabled: false}, shadow: false, states: {hover: {lineWidth: 1}}, threshold: null}};

    //Default settings for Spider chart
    var dataRadar = <?php echo $dataRadar; ?>;
    var optionRadar = {
        title: {
            text: dataRadar.studentName,
            subtext: dataRadar.classroomName
        },
        tooltip: {
            trigger: 'axis'
        },
        //legend: {
        //    orient : 'vertical',
        //    x : 'right',
        //	  y : 'bottom',
        //     data:['Data 1','Data 2']
        //},
        toolbox: {
            show: false,
            feature: {
                mark: {show: true},
                dataView: {show: true, readOnly: false},
                restore: {show: true},
                saveAsImage: {show: true}
            }
        },
        polar: [
            {
                indicator: [
                    {text: 'WSS', max: dataRadar.wssMax, min: dataRadar.wssMin},
                    {text: 'SR %', max: dataRadar.srMax, min: dataRadar.srMin},
                    {text: 'Sentences Spoken', max: dataRadar.sentencesSpokenMax, min: dataRadar.sentencesSpokenMin},
                    {text: 'Sentences Heard', max: dataRadar.sentencesHeardMax, min: dataRadar.sentencesHeardMin},
                    {text: 'Avg. Days Per Week', max: dataRadar.avgDaysPerWeekMax, min: dataRadar.avgDaysPerWeekMin},
                    {text: 'Mastery test', max: dataRadar.masteryTestMax, min: dataRadar.masteryTestMin}
                ]
            }
        ],
        calculable: true,
        series: [{
                name: 'Name',
                type: 'radar',
                data: [
                    {
                        //[WSS SR SS SH AVG MT]
                        value: [dataRadar.WSS, dataRadar.SR, dataRadar.sentencesSpoken, dataRadar.sentencesHeard, dataRadar.avgDaysPerWeek, dataRadar.masteryTest],
                        name: 'Value 1'
                    }
                ]
            }
        ]
    };

    $(function () {
        $('#wss').highcharts({
            credits: credits,
            exporting: exporting,
            chart: {type: 'gauge', plotBackgroundColor: null, plotBackgroundImage: null, plotBorderWidth: 0, plotShadow: false},
            title: {text: 'WSS'},
            pane: pane,
            series: [{name: 'Progress', data: [wss_this_week], tooltip: {valueSuffix: ' WSS'}}],
            // The value axis
            yAxis: {min: -13, max: 13, minorTickInterval: 'auto', minorTickWidth: 1, minorTickLength: 10, minorTickPosition: 'inside', minorTickColor: '#666', tickPixelInterval: 30, tickWidth: 2, tickPosition: 'inside', tickLength: 10, tickColor: '#666', labels: {step: 2, rotation: 'auto'}, title: {text: 'WSS'}, plotBands: [{from: -12, to: 0, color: '#DF5353'}, {from: 0, to: 4, color: '#DDDF0D'}, {from: 4, to: 12, color: '#55BF3B'}]}
        });

        $('#daysPerWeek').highcharts({
            credits: credits,
            exporting: exporting,
            chart: {type: 'gauge', plotBackgroundColor: null, plotBackgroundImage: null, plotBorderWidth: 0, plotShadow: false},
            title: {text: 'Days / Week'},
            pane: pane,
            series: [{name: 'Progress', data: [days_per_week], tooltip: {valueSuffix: ' Days / Week'}}],
            // the value axis
            yAxis: {min: 0, max: 10, minorTickInterval: 'auto', minorTickWidth: 1, minorTickLength: 10, minorTickPosition: 'inside', minorTickColor: '#666', tickPixelInterval: 30, tickWidth: 2, tickPosition: 'inside', tickLength: 10, tickColor: '#666', labels: {step: 2, rotation: 'auto'}, title: {text: 'Days / Week'}, plotBands: [{from: 0, to: 1, color: '#DF5353'}, {from: 1, to: 4, color: '#DDDF0D'}, {from: 4, to: 7, color: '#55BF3B'}]}

        });

        $('#hoursPerWeek').highcharts({
            credits: credits,
            exporting: exporting,
            chart: {type: 'gauge', plotBackgroundColor: null, plotBackgroundImage: null, plotBorderWidth: 0, plotShadow: false},
            title: {text: 'Hours / Week'},
            pane: pane,
            series: [{name: 'Progress', data: [hours_per_week], tooltip: {valueSuffix: ' Hours / Week'}}],
            // the value axis
            yAxis: {min: 0, max: 8, minorTickInterval: 'auto', minorTickWidth: 1, minorTickLength: 10, minorTickPosition: 'inside', minorTickColor: '#666', tickPixelInterval: 30, tickWidth: 2, tickPosition: 'inside', tickLength: 10, tickColor: '#666', labels: {step: 2, rotation: 'auto'}, title: {text: 'Hours / Week'}, plotBands: [{from: 0, to: 1, color: '#DF5353'}, {from: 1, to: 2.5, color: '#DDDF0D'}, {from: 2.5, to: 5, color: '#55BF3B'}]}

        });

        $('#wss-all-time').highcharts({
            credits: credits,
            exporting: exporting,
            chart: chart,
            title: {text: 'WSS'},
            xAxis: xAxis,
            yAxis: {title: {text: 'Points'}, plotLines: [{color: '#2f7ed8', width: 1, value: 5}]},
            tooltip: {formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                            Highcharts.dateFormat('%e %B %Y', this.x) + ': ' + this.y;
                }},
            legend: legend,
            plotOptions: plotOptions,
            series: [{type: 'area', name: 'Result', pointInterval: 24 * 3600 * 1000 * 7,
                    data: all_time_wss
                }]
        });

        $('#days-per-week-all-time').highcharts({
            credits: credits,
            exporting: exporting,
            chart: chart,
            title: {text: 'Days / Week'},
            xAxis: xAxis,
            yAxis: {title: {text: 'Points'}, plotLines: [{color: '#2f7ed8', width: 1, value: 5}]},
            tooltip: {formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                            Highcharts.dateFormat('%e %B %Y', this.x) + ': ' + this.y;
                }},
            legend: legend,
            plotOptions: plotOptions,
            series: [{type: 'area', name: 'Result', pointInterval: 24 * 3600 * 1000 * 7,
                    data: all_time_days
                }]
        });

        $('#hours-per-week-all-time').highcharts({
            credits: credits,
            exporting: exporting,
            chart: chart,
            title: {text: 'Hours / Week'},
            xAxis: xAxis,
            yAxis: {title: {text: 'Points'}, plotLines: [{color: '#2f7ed8', width: 1, value: 5}]},
            tooltip: {formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                            Highcharts.dateFormat('%e %B %Y', this.x) + ': ' + this.y;
                }},
            legend: legend,
            plotOptions: plotOptions,
            series: [{type: 'area', name: 'Result', pointInterval: 24 * 3600 * 1000 * 7,
                    data: all_time_hours
                }]
        });
        var myRadarChart = echarts.init(document.getElementById('spider_diagram'));
        myRadarChart.setOption(optionRadar);

    });
</script>