<?php

namespace app\models;

use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

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
 * @property boolean $active
 * @property string $died_at
 * @property string $created_at
 * @property string $updated_at
 */
class Terrorist extends BaseModel
{
    const STATUS_ALIVE = 1;
    const STATUS_DEAD = 2;
    const STATUS_UPCOMING = 3;
    const STATUS_INJURED = 4;
    const STATUS_UNKNOWN = 5;

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
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['name', 'slug', 'active'], 'required'],
            [['info', 'feedback'], 'string'],
            [['status'], 'integer'],
            [['active'], 'boolean'],
            [['died_at', 'created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'department', 'post'], 'string', 'max' => 200],
        ];
    }

    public function init()
    {
        parent::init();

        $this->active = false;
        $this->status = static::STATUS_UNKNOWN;
    }

    public static function getStatuses()
    {
        return [
            static::STATUS_ALIVE => 'Ещё не ликвидирован',
            static::STATUS_DEAD => 'Ликвидирован',
            static::STATUS_UPCOMING => 'Уже скоро',
            static::STATUS_INJURED => 'На больничке',
            static::STATUS_UNKNOWN => 'Неизвестно',
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

    public static function getUpcoming()
    {
        return static::find()->where(['status' => static::STATUS_UPCOMING])->all();
    }

    public function getUrl()
    {
        return '/terrorists/'.$this->slug;
    }
}
