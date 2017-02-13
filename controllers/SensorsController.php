<?php

namespace app\controllers;

use app\models\Sensor;
use app\models\SensorData;
use yii\helpers\Json;
use yii\web\Controller;

class SensorsController extends Controller
{
    private $buttons = [
        ['type' => 'hour', 'count' => 1, 'text' => '1h'],
        ['type' => 'hour', 'count' => 6, 'text' => '6h'],
        ['type' => 'day', 'count' => 1, 'text' => '1d'],
        ['type' => 'day', 'count' => 3, 'text' => '3d'],
        ['type' => 'week', 'count' => 1, 'text' => '1w'],
        ['type' => 'month', 'count' => 1, 'text' => '1m'],
    ];

    public function actionIndex()
    {
        $sensors = Sensor::getActive();

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

    public function actionChart()
    {
        $id = \Yii::$app->request->get('id');

        $s = Sensor::findOne(['id' => $id]);

        $buttons = Json::encode($this->buttons);

        return $this->render('chart', compact('s', 'buttons'));
    }

    public function actionAll()
    {
        $id = \Yii::$app->request->get('id');
        $buttons = Json::encode($this->buttons);

        return $this->render('all', compact('buttons', 'id'));
    }

    public function actionData()
    {
        \Yii::$app->session->close(); // After this php can handle other requests

        $ids = \Yii::$app->request->get('id');

        if (!is_array($ids)) {
            $ids = array_map('trim', explode(',', $ids));
        }

        $result = [];
        foreach ($ids as $id) {
            $s = Sensor::findOne(['id' => $id]);
            if (!$s) {
                continue;
            }

            $result[] = $s->getChartSerie();
        }

        return $this->asJson($result);
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

    public function actionReceiveArray()
    {
        $data = \Yii::$app->request->get();

        if (empty($data['sensors'])) {
            return "sensors is empty";
        }

        if (!is_array($data['sensors'])) {
            return "sensors is not an array";
        }

        $savedCount = 0;

        foreach ($data['sensors'] as $sensorId => $value) {
            if (!is_integer($sensorId)) {
                return "sensorId must be integer";
            }

            if (!is_numeric($value)) {
                return "value of sensor $sensorId must be number";
            }

            $sensor = Sensor::findOne(['id' => $sensorId]);

            if (!$sensor) {
                return "sensor $sensorId not found";
            }

            if ($sensor->updateValue($value)) {
                $savedCount++;
            }
        }

        return "OK. Count: $savedCount";
    }
}
