<?php

namespace frontend\controllers\auth;

use application\services\SocialService;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\authclient\AuthAction;

class SocialLoginController extends Controller
{
    private $service;

    public function __construct($id, $module, SocialService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'afterOAuth'],
            ],
        ];
    }

    public function afterOAuth(ClientInterface $client) : void
    {
        $social = $client->getId();
        $attributes = $client->getUserAttributes();
        $identity = ArrayHelper::getValue($attributes, 'id');

        try {
            $user = $this->service->auth($social, $identity);
            Yii::$app->user->login($user, Yii::$app->params['user.rememberTime']);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }
}