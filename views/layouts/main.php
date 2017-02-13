<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="skin-blue layout-top-nav layout-boxed" style="height: auto;">
<?php $this->beginBody() ?>
<div class="wrapper" style="height: auto;">

    <header class="main-header">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->params['siteName'],
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-static-top',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'items' => [
                ['label' => 'Температуры', 'url' => ['/sensors/all?id=1,4,5']],
                //['label' => 'Добавить датчик', 'url' => ['/sensors/add']],
            ],
        ]);

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                Yii::$app->user->isGuest ? (
                ['label' => 'Вход', 'url' => ['/login']]
                ) : (
                    '<li>'
                    . Html::beginForm(['/logout'], 'post')
                    . Html::submitButton(
                        'Выход (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'btn btn-link logout', 'style' => 'color:white;']
                    )
                    . Html::endForm()
                    . '</li>'
                )
            ],
        ]);
        NavBar::end();
        ?>
    </header>
    <div class="content-wrapper" style="min-height: 421px;">
        <div class="container">
            <?= $content ?>
        </div>
    </div>
    <footer class="main-footer">
        <div class="container">
            <p class="pull-left">&copy; <?=Yii::$app->params['siteName']?> <?= date('Y') ?></p>

            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


