<?php

/* @var $this yii\web\View */

\app\assets\HighChartsAsset::register($this);

?>
<h1><?=$s->name?></h1>
<div class="charts-chart" id="highchart-<?=$s->id?>" style="min-height: 700px; width: 100%;"></div>

<?php $this->registerJs(<<<JS

    Highcharts.setOptions({
        global: {
            timezoneOffset: (new Date()).getTimezoneOffset()
        }
    });

    var chart = $('#highchart-{$s->id}');
    
    $.getJSON('/sensors/data?id={$s->id}', function(series) {
        var chartConf = {
            chart: {
                zoomType: 'x'
            },
            
            legend: {
                enabled: false
            },
            
            rangeSelector: {
                enabled: true,
                inputEnabled: false,
                buttons: {$buttons},
                selected: 2
            },
            
            navigator: {
                enabled: true
            },
            
            scrollbar: {
                enabled: true
            },
            
            xAxis: {
                ordinal: false
            },
            yAxis: {
                title: {
                    text: 'Temperature (°C)'
                }
            },
            series: series
        };
        
        chart.highcharts('StockChart', chartConf);
    });
JS
) ?>