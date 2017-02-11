<?php
namespace app\assets;

use yii\web\AssetBundle;

class HighChartsAsset extends AssetBundle
{
    public $sourcePath = '@bower/highcharts';

    public $js = [
        //'highcharts.js',
        'highstock.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
