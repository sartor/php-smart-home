<?php

namespace app\controllers;

use app\models\Sensor;
use yii\web\Controller;

class KeysController extends Controller
{
    public function actionIndex()
    {
        $sensors = Sensor::findAll('1');

        return $this->render('index', compact('sensors'));
    }
}
