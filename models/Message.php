<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "messages".
 *
 * @property integer $id
 * @property string $email
 * @property string $text
 * @property string $created_at
 * @property string $updated_at
 */
class Message extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'messages';
    }

    public function rules()
    {
        return [
            [['email', 'text'], 'required'],
            [['text'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['email'], 'string', 'max' => 200],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'text' => 'Text',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
