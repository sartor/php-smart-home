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

    public function actionData()
    {
        \Yii::$app->session->close(); // After this php can handle other requests

        $id = \Yii::$app->request->get('id');

        $s = Sensor::findOne(['id' => $id]);

        $result = [];

        $result[] = [
            'name' => $s->name,
            'type' => 'areaspline',
            'color' => '#dd4b39',
            'negativeColor' => '#00c0ef',
            'connectNulls' => true,
            'marker' => [
                'enabled' => false,
            ],
            'dataGrouping' => [
                'approximation' => 'average',
                'groupPixelWidth' => 7,
                'units' =>  [
                    ['minute', [1, 5, 30]],
                    ['hour', [1, 3, 6, 12]],
                    ['day', [1]]
                ],
            ],
            'threshold' => 0,
            'data' => $s->getChartData(),
        ];

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
