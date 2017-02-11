<?php

/* @var $this yii\web\View */

?>
<h1>Список</h1>
<div class="row">
    <?php foreach ($sensors as $s): /** @var $s \app\models\Sensor */ ?>
    <div class="col-sm-4">
        <div class="info-box">
            <span class="info-box-icon  bg-aqua"><i class="fa fa-thermometer-half"></i></span>

            <div class="info-box-content">
                <span class="info-box-text"><a href="/sensors/chart?id=<?=$s->id?>"><?=$s->name?></a></span>
                <span class="info-box-number"><?=number_format($s->last_value, 1)?>&deg;C</span>

                <div class="progress">
                    <div class="progress-bar bg-aqua" style="width: <?=$s->getTrendPercent()?>%"></div>
                </div>
                <span class="progress-description text-muted">Последнее обновление в <?=date('H:i', strtotime($s->getLocalDate('updated_at')))?></span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
