<?php

/* @var $this yii\web\View */

?>
<h1>Список</h1>
<ul class="timeline">

    <?php foreach ($terrorists as $t): /** @var $t \app\models\Terrorist */ ?>
    <li class="time-label">
        <span class="bg-blue"><?=date('d.m.Y', strtotime($t->died_at))?></span>
    </li>
    <li>
        <i class="fa fa-ban bg-red"></i>
        <div class="timeline-item">
            <?php if (!\Yii::$app->user->isGuest): ?>
                <span class="time"><a href="/admin/terrorists/delete/<?=$t->id?>" class="btn btn-danger btn-xs">Удалить</a></span>
            <?php endif; ?>

            <h3 class="timeline-header"><a href="<?=$t->getUrl()?>"><?=$t->name?></a> <?=mb_strtolower($t->getStatusText())?></h3>

            <div class="timeline-body">
                <div class="media">
                    <div class="media-left">
                        <a href="<?=$t->getUrl()?>">
                            <img src="<?=$t->getImageSrc()?>" alt="<?=$t->name?>" title="<?=$t->name?>" class="pull-left"/>
                        </a>
                    </div>
                    <div class="media-body">
                        <?=$t->info?>
                        <a href="<?=$t->getUrl()?>">дальше...</a>
                    </div>
                </div>
            </div>
        </div>
    </li>
    <?php endforeach; ?>
</ul>