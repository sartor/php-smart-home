<?php
namespace app\assets;

use yii\web\AssetBundle;

class AdminLteAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/dist';

    public $js = [
        'js/app.min.js',
    ];

    public $css = [
        'css/AdminLTE.min.css',
        'css/skins/skin-yellow.min.css',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
