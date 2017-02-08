<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "sensors".
 *
 * @property integer $id
 * @property string $name
 * @property string $unit
 * @property string $last_value
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SensorData[] $sensorsDatas
 */
class Sensor extends BaseModel
{
    public static function tableName()
    {
        return 'sensors';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['last_value'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['unit'], 'string', 'max' => 10],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'unit' => 'Unit',
            'last_value' => 'Last Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getSensorsDatas() : ActiveQuery
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
}
