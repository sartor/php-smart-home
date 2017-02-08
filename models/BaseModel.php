<?php

namespace app\models;

abstract class BaseModel extends \yii\db\ActiveRecord
{
    public function getLocalDate($field)
    {
        $date = new \DateTime($this->$field);
        $date->setTimezone(new \DateTimeZone('Europe/Kiev'));

        return $date->format('Y-m-d H:i:s');
    }
}
