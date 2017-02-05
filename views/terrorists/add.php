<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use app\models\Terrorist;

$this->title = 'Добавить объект';
?>
<div class="row">
    <div class="col-sm-6 col-sm-offset-3">
        <div class="site-contact">
            <h1><?= Html::encode($this->title) ?></h1>

            <?php if (Yii::$app->session->hasFlash('terroristAddFormSubmitted')): ?>

                <div class="alert alert-success">
                    Объект будет добавлен после модерации, спасибо.
                </div>

            <?php else: ?>

                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>
                    <?= $form->field($model, 'died_at')->textInput(['type' => 'date']) ?>
                    <?= $form->field($model, 'status')->dropDownList(Terrorist::getStatuses()) ?>
                    <?= $form->field($model, 'info')->textarea(['rows' => 6]) ?>
                    <?= $form->field($model, 'department') ?>
                    <?= $form->field($model, 'post') ?>

                    <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-3">{input}</div></div>',
                    ]) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            <?php endif; ?>
        </div>
    </div>
</div>