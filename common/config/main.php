<?php

return [
    'aliases' => [
        '@root' => dirname(dirname(__DIR__)),
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@console'   => '@root/console',
        '@apple' => '@root/modules/apple',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\redis\Cache::class,
        ],
        'redis' => [
            'class' => \yii\redis\Connection::class,
            'hostname' => getenv('REDIS_HOST'),
            'port' => getenv('REDIS_PORT'),
            'database' => getenv('REDIS_DATABASE'),
        ],
    ],
    'container' => [
        'singletons' => [
            common\components\Clock\Clock::class => common\components\Clock\SystemClock::class,
        ]
    ]
];
