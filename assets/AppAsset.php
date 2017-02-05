<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/site.css',
    ];

    public $js = [

    ];

    public $depends = [
        'yii\web\YiiAsset',
        'app\assets\AdminLteAsset',
    ];

    public $jsOptions = [
        'position' =>  View::POS_END,
    ];
}
