<?php

declare(strict_types=1);

namespace apple\service;

use yii\caching\Cache;

final readonly class AppleCacheService
{
    public const string KEY_PREFIX = 'apple';

    public function __construct(
        private Cache $cache
    ){
    }

    public function dropList(): void
    {

    }
}