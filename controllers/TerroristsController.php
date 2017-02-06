<?php

namespace app\controllers;

use app\models\Terrorist;
use yii\web\Controller;

class TerroristsController extends Controller
{
    public function actionIndex()
    {
        $terrorists = Terrorist::getAll();

        return $this->render('index', compact('terrorists'));
    }

    public function actionUpcoming()
    {
        $terrorists = Terrorist::getUpcoming();

        return $this->render('index', compact('terrorists'));
    }

    public function actionAdd()
    {
        $model = new Terrorist();

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            $model->addImage();

            \Yii::$app->session->setFlash('terroristAddFormSubmitted');

            return $this->refresh();
        }

        return $this->render('add', [
            'model' => $model,
        ]);
    }
}
