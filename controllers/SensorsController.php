<?php

namespace app\controllers;

use app\models\Sensor;
use app\models\SensorData;
use yii\web\Controller;

class SensorsController extends Controller
{
    public function actionIndex()
    {
        $sensors = Sensor::findAll('1');

        return $this->render('index', compact('sensors'));
    }

    public function actionAdd()
    {
        $get = \Yii::$app->request->get();

        if ($get) {
            $sensor = new Sensor($get);
            $sensor->save();

            return $this->renderContent('New ID: '.$sensor->id);
        }

        return $this->renderContent('Usage: ?name=SensorName');
    }

    public function actionLog()
    {
        $id = \Yii::$app->request->get('id');

        $data = SensorData::findAll(['sensor_id' => $id]);

        return $this->render('log', compact('data'));
    }

    public function actionReceive()
    {
        $data = \Yii::$app->request->get();

        if (empty($data['sensorId'])) {
            return "sensorId is empty";
        }

        if (!array_key_exists('value', $data)) {
            return "value is'n set";
        }

        $sensor = Sensor::findOne(['id' => $data['sensorId']]);

        if (!$sensor) {
            return "sensor with this ID not found";
        }

        if ($sensor->updateValue($data['value'])) {
            return 'OK';
        }

        return 'Unknown error';
    }
}
