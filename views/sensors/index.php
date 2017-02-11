<?php

/* @var $this yii\web\View */

?>
<h1>Список</h1>
<div class="row">
    <?php foreach ($sensors as $s): /** @var $s \app\models\Sensor */ ?>
    <div class="col-sm-4">
        <div class="info-box">
            <span class="info-box-icon bg-<?=$s->background?>"><i class="fa fa-<?=$s->icon?>"></i></span>

            <div class="info-box-content">
                <span class="info-box-text"><a href="/sensors/chart?id=<?=$s->id?>"><?=$s->name?></a><?=$s->sensor?" ({$s->sensor})":''?></span>
                <span class="info-box-number"><?=number_format($s->last_value, $s->decimals)?><?=$s->unit?></span>

                <div class="progress">
                    <div class="progress-bar bg-<?=$s->background?>" style="width: <?=$s->getTrendPercent()?>%"></div>
                </div>
                <span class="progress-description text-muted">Последнее обновление в <?=date('H:i', strtotime($s->getLocalDate('updated_at')))?></span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
