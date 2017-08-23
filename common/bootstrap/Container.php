<?php

namespace common\bootstrap;

use application\services\FeedbackService;
use yii\base\BootstrapInterface;
use yii\di\Instance;
use yii\mail\MailerInterface;

/**
 * Class Container
 * @package common\bootstrap
 */
class Container implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $app
     * @return void
     */
    public function bootstrap($app) : void
    {
        $container = \Yii::$container;

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

        $container->setSingleton(FeedbackService::class, [], [
            $app->params['adminEmail'],
            Instance::of(MailerInterface::class)
        ]);
    }
}