<?php

/* @var $this yii\web\View */

?>
<h1>Лог датчика</h1>
<ul>
    <?php foreach ($data as $d): /** @var $d \app\models\SensorData */ ?>
    <li>
        <?=date('H:i:s', strtotime($d->getLocalDate('created_at')))?>: <?=$d->value?>
    </li>
    <?php endforeach; ?>
</ul>