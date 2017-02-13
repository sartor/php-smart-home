<?php

/* @var $this yii\web\View */

\app\assets\HighChartsAsset::register($this);

?>
<h1>Совмещённые графики</h1>
<div class="charts-chart" id="highchart" style="width: 100%;"></div>

<?php $this->registerJs(<<<JS

    Highcharts.setOptions({
        global: {
            timezoneOffset: (new Date()).getTimezoneOffset()
        }
    });

    var chart = $('#highchart');
    
    $.getJSON('/sensors/data?id={$id}', function(series) {
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
                ordinal: false
            },
            series: series
        };
        
        chart.highcharts('StockChart', chartConf);
    });
JS
) ?>