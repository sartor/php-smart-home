<?php

/* @var $this yii\web\View */

?>
<h1>Список</h1>
<ul class="timeline">

    <?php foreach ($terrorists as $t): /** @var $t \app\models\Terrorist */ ?>
    <li class="time-label">
        <span class="bg-red"><?=date('d.m.Y', strtotime($t->died_at))?></span>
    </li>
    <li>
        <i class="fa fa-envelope bg-blue"></i>
        <div class="timeline-item">
            <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

            <h3 class="timeline-header"><a href="<?=$t->getUrl()?>"><?=$t->name?></a></h3>

            <div class="timeline-body">
                <?=$t->info?>
            </div>

            <div class="timeline-footer">
                <a class="btn btn-primary btn-xs">...</a>
            </div>
        </div>
    </li>
    <?php endforeach; ?>
</ul>