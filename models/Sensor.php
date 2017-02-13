<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sensors".
 *
 * @property integer $id
 * @property string $name
 * @property string $unit
 * @property string $last_value
 * @property string $created_at
 * @property string $updated_at
 * @property integer $decimals
 * @property string $icon
 * @property string $background
 * @property bool $active
 * @property integer $order
 * @property integer $threshold
 * @property string $sensor
 * @property integer $trend_limit
 * @property string $color
 * @property string $negative_color
 * @property integer $type
 * @property string $chart
 *
 * @property SensorData[] $data
 */
class Sensor extends BaseModel
{
    const TYPE_TEMP = 1;
    const TYPE_PRESSURE = 2;
    const TYPE_HUMIDITY = 3;
    const TYPE_LIGHT = 4;

    public static function tableName()
    {
        return 'sensors';
    }

    public function init()
    {
        parent::init();

        $this->icon = 'bullseye';
        $this->background = 'aqua';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['last_value'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'sensor'], 'string', 'max' => 100],
            [['unit'], 'string', 'max' => 10],
            [['icon', 'background', 'chart'], 'string', 'max' => 20],
            ['active', 'boolean'],
            [['order', 'decimals', 'threshold', 'trend_limit', 'type'], 'integer'],
            [['color', 'negative_color'], 'string', 'max' => 10],
        ];
    }

    public static function getActive()
    {
        return static::find()
            ->orderBy(['order' => SORT_ASC, 'id' => SORT_ASC])
            ->where(['active' => true])
            ->all();
    }

    public function getData() : ActiveQuery
    {
        return $this->hasMany(SensorData::class, ['sensor_id' => 'id']);
    }

    public function updateValue($value) : bool
    {
        $now = date('Y-m-d H:i:s');

        $data = new SensorData([
            'sensor_id' => $this->id,
            'value' => $value,
            'created_at' => $now,
        ]);

        if (!$data->save()) {
            return false;
        }

        $this->last_value = $value;
        $this->updated_at = $now;

        if (!$this->save()) {
            return false;
        }

        return true;
    }

    public function getUpdatedAtLocal()
    {
        $date = new \DateTime($this->updated_at);
        $date->setTimezone(new \DateTimeZone('Europe/Kiev'));

        return $date->format('Y-m-d H:i:s');
    }

    public function getTrendPercent() : int
    {
        if (empty($this->trend_limit)) {
            return 50;
        }

        $avg = $this->getData()
            ->select('AVG(value)')
            ->andWhere(['>=', 'created_at', date('Y-m-d H:i:s', strtotime('- 6 hours', strtotime($this->updated_at)))])
            ->scalar();

        $diff = $this->last_value - $avg;

        if ($diff < - $this->trend_limit) $diff = -$this->trend_limit;
        if ($diff > $this->trend_limit) $diff = $this->trend_limit;

        $diff += $this->trend_limit;

        return round($diff / ($this->trend_limit * 2) * 100);
    }

    public function getChartData($fromDate = null, $toDate = null)
    {
        if ($fromDate === null) {
            $fromDate = date('Y-m-d', strtotime('- 1 month'));
        }

        if ($toDate === null) {
            $toDate = date('Y-m-d', strtotime('+ 1 day'));
        }

        $fromTime = floor(strtotime($fromDate) / 60) * 60 * 1000;
        $toTime = floor(strtotime($toDate) / 60) * 60 * 1000;

        $rows = $this->getData()
            ->select([
                'x' => "EXTRACT('epoch' FROM created_at)::bigint / 60 * 60 * 1000",
                'y' => "value"
            ])
            ->andWhere(['>=', 'created_at', $fromDate])
            ->andWhere(['<', 'created_at', $toDate])
            ->asArray()
            ->all();

        $data = [];

        $xyArray = ArrayHelper::map($rows, 'x', 'y');

        for ($t = $fromTime; $t < $toTime; $t += 60 * 1000) {
            if (!isset($xyArray[(int)$t])) {
                continue;
            }

            $data[] = [$t, floatval($xyArray[(int)$t])];
        }

        return $data;
    }

    public function getChartSerie() : array
    {
        return [
            'name' => $this->name,
            'type' => $this->chart,
            'color' => $this->color,
            'negativeColor' => $this->negative_color,
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
            'threshold' => $this->threshold,
            'data' => $this->getChartData(),
            'tooltip' => [
                'valueDecimals' => 1,
                'valueSuffix' => ' '.$this->unit,
            ],
        ];
    }
}
