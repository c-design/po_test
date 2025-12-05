<?php

use apple\command\UpdateStateForDeadApples;
use apple\query\FindAllByFilter;
use apple\repository\AppleRepository;
use apple\repository\ar\AppleRepository as ArAppleRepository;
use common\components\Clock\Clock;
use yii\caching\Cache;

return [
    'singletons' => [
        Cache::class => Yii::$app->cache,
        AppleRepository::class => ArAppleRepository::class,
        FindAllByFilter::class => static function () {
            $query = new \apple\query\ar\FindAllByFilter();
            return new  \apple\query\cached\FindAllByFilter(
                Yii::$app->cache,
                $query,
            );
        },
        UpdateStateForDeadApples::class => static function() {
            return new \apple\command\mysql\UpdateStateForDeadApples(Yii::$app->db, Yii::$container->get(Clock::class));
        }
    ]
];