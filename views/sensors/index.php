<?php

/* @var $this yii\web\View */

?>
<h1>Список</h1>
<ul>

    <?php foreach ($sensors as $s): /** @var $s \app\models\Sensor */ ?>
    <li>
        <a href="/sensors/log?id=<?=$s->id?>"><?=$s->name?></a>: <?=$s->last_value?> (<?=$s->getLocalDate('updated_at')?>)
    </li>
    <?php endforeach; ?>
</ul>