<?php

namespace app\models;

use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "terrorists".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $department
 * @property string $post
 * @property string $info
 * @property string $feedback
 * @property integer $status
 * @property string $died_at
 * @property string $created_at
 * @property string $updated_at
 */
class Terrorist extends BaseModel
{
    public static function tableName()
    {
        return 'terrorists';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'slugAttribute' => 'slug',
            ],
        ];
    }

    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['info', 'feedback'], 'string'],
            [['status'], 'integer'],
            [['died_at', 'created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'department', 'post'], 'string', 'max' => 200],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'department' => 'Department',
            'post' => 'Post',
            'info' => 'Info',
            'feedback' => 'Feedback',
            'status' => 'Status',
            'died_at' => 'Died At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getAll()
    {
        return static::find()->all();
    }

    public function getUrl()
    {
        return '/terrorists/'.$this->slug;
    }
}
