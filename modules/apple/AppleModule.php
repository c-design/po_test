<?php

declare(strict_types=1);

namespace apple;

use yii\base\Module;
use yii\console\Application as ConsoleApplication;
use yii\web\Application as WebApplication;

final class AppleModule extends Module
{
    public function init(): void
    {
        parent::init();
        \Yii::configure(\Yii::$container, require(__DIR__ . '/config/container.php'));

        if (\Yii::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'apple\controller\console';
        }

        if (\Yii::$app instanceof WebApplication) {
            $this->controllerNamespace = 'apple\controller\web';
        }
    }
}