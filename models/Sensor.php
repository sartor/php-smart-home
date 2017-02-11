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
 *
 * @property SensorData[] $data
 */
class Sensor extends BaseModel
{
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
            [['icon', 'background'], 'string', 'max' => 20],
            ['active', 'boolean'],
            [['order', 'decimals', 'threshold'], 'integer'],
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
        $avg = $this->getData()
            ->select('AVG(value)')
            ->andWhere(['>=', 'created_at', date('Y-m-d H:i:s', strtotime('- 4 hours', strtotime($this->updated_at)))])
            ->scalar();

        $diff = $this->last_value - $avg;

        if ($diff < -10) $diff = -10;
        if ($diff > 10) $diff = 10;

        return round($diff * 5 + 50);
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
}
