<?php

return [
    'apples' => [
        'class' => \yii\web\GroupUrlRule::class,
        'routePrefix' => 'apple',
        'rules' => [
            [
                'verb' => 'GET',
                'pattern' => 'apples',
                'route' => 'apple/list',
            ],
            [
                'verb' => 'GET',
                'pattern' => 'apples/generate',
                'route' => 'apple/generate',
            ],
            [
                'verb' => 'POST',
                'pattern' => 'apples/{id}/eat',
                'route' => 'apple/eat',
            ],
            [
                'verb' => 'POST',
                'pattern' => 'apples/{id}/fall',
                'route' => 'apple/fall-to-ground',
            ],
        ],
    ],
];
