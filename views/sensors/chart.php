<?php

/* @var $this yii\web\View */

\app\assets\HighChartsAsset::register($this);

?>
<h1><?=$s->name?></h1>
<div class="charts-chart" id="highchart-<?=$s->id?>" style="width: 100%;"></div>

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
                selected: 3
            },
            
            navigator: {
                enabled: true
            },
            
            scrollbar: {
                enabled: true
            },
            
            xAxis: {
                ordinal: false,
                gridLineWidth: 1
            },
            yAxis: {
                title: {
                    text: '{$s->name} {$s->unit}'
                }
            },
            series: series
        };
        
        chart.highcharts('StockChart', chartConf);
    });
JS
) ?>