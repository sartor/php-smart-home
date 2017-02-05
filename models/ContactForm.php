<?php

namespace app\models;

use yii\base\Model;

class ContactForm extends Model
{
    public $email;
    public $body;
    public $verifyCode;


    public function rules()
    {
        return [
            [['email', 'body'], 'required'],
            ['email', 'email'],
            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Проверочный код',
            'body' => 'Сообщение',
        ];
    }

    public function contact()
    {
        if ($this->validate()) {
            $message = new Message();
            $message->email = $this->email;
            $message->text = $this->body;

            return $message->save();
        }
        return false;
    }
}
