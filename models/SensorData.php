<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "sensors_data".
 *
 * @property integer $id
 * @property integer $sensor_id
 * @property string $value
 * @property string $created_at
 *
 * @property Sensor $sensor
 */
class SensorData extends BaseModel
{
    public static function tableName()
    {
        return 'sensors_data';
    }

    public function rules()
    {
        return [
            [['sensor_id'], 'required'],
            [['sensor_id'], 'integer'],
            [['value'], 'number'],
            [['created_at'], 'safe'],
            [['sensor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sensor::class, 'targetAttribute' => ['sensor_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sensor_id' => 'Sensor ID',
            'value' => 'Value',
            'created_at' => 'Created At',
        ];
    }

    public function getSensor() : ActiveQuery
    {
        return $this->hasOne(Sensor::class, ['id' => 'sensor_id']);
    }
}
