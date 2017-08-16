<?php
namespace frontend\controllers\account;

use yii\filters\AccessControl;
use yii\web\Controller;

class MainController extends Controller
{
    public function behaviors() : array
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex() : string
    {
        return $this->render('index');
    }
}