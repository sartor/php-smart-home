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
}
