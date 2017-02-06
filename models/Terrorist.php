<?php

namespace app\models;

use rico\yii2images\behaviors\ImageBehave;
use rico\yii2images\models\Image;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

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

    public $verifyCode;
    public $imageUrl;

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
            [
                'class' => ImageBehave::class,
            ]
        ];
    }

    public function rules()
    {
        return [
            [['name', 'active'], 'required'],
            [['info', 'feedback'], 'string', 'max' => 10000],
            [['status'], 'integer'],
            [['active'], 'boolean'],
            ['died_at', 'date', 'format' => 'yyyy-MM-dd'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'department', 'post'], 'string', 'max' => 200],
            ['verifyCode', 'captcha'],
            ['imageUrl', 'string'],
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
            static::STATUS_UPCOMING => 'Почти готов',
            static::STATUS_INJURED => 'На больничке',
            static::STATUS_UNKNOWN => 'Неизвестно',
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ФИО',
            'slug' => 'URL',
            'department' => 'Организация, отдел, орган...',
            'post' => 'Должность, пост...',
            'info' => 'Описание',
            'feedback' => 'Для обратной связи',
            'status' => 'Статус',
            'active' => 'Показывать',
            'died_at' => 'Ликвидирован',
            'created_at' => 'Добавлен в базу',
            'updated_at' => 'Последнее обновление',
            'verifyCode' => 'Проверочный код',
            'imageUrl' => 'Прямая ссылка на картинку',
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

    public function getStatusText()
    {

        return $this->status == static::STATUS_UNKNOWN ? '' : ArrayHelper::getValue(static::getStatuses(), $this->status);
    }

    public function getImageSrc()
    {
        /** @var Image $image */
        $image = $this->getImage();

        if ($image) {
            return $image->getPath('200x200');
        }

        return "http://placehold.it/200x200";
    }

    public function addImage()
    {
        $this->imageUrl = explode('?', $this->imageUrl)[0];
        $this->attachImage($this->imageUrl);
    }
}
