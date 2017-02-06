<?php

namespace app\controllers\admin;

use app\models\Terrorist;
use yii\filters\AccessControl;
use yii\web\Controller;

class TerroristsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionDelete($id)
    {
        $t = Terrorist::findOne($id);

        if ($t) {
            $t->removeImages();
            $t->delete();
        }

        $this->goBack();
    }
}
