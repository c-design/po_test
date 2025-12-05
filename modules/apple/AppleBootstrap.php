<?php

namespace apple;

use yii\base\BootstrapInterface;
use yii\web\Application;

class AppleBootstrap implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $app->on(Application::EVENT_BEFORE_REQUEST, function () use ($app) {
            $app->urlManager->addRules(require(__DIR__ . '/config/routes.php'));
        });
    }
}